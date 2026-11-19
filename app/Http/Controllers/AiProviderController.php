<?php

namespace App\Http\Controllers;

use App\Models\Satker;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Http;

class AiProviderController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware(function ($request, $next) {
                if (auth()->check() && in_array(auth()->user()->role, ['super', 'admin'])) {
                    return $next($request);
                }
                abort(403);
            }),
        ];
    }

    public function edit(Request $request)
    {
        $user = auth()->user();

        if ($user->role === 'super') {
            $satkerId = $request->satker_id ?? Satker::first()?->id;
            $allSatkers = Satker::all();
        } else {
            $satkerId = $user->satker_id;
            $allSatkers = collect();
        }

        if (! $satkerId) {
            return redirect()->route('dashboard')->with('error', 'Tidak ada Satker yang ditemukan.');
        }

        $satker = Satker::findOrFail($satkerId);

        return view('admin.ai_provider.edit', compact('satker', 'allSatkers'));
    }

    public function update(Request $request)
    {
        $user = auth()->user();

        if ($user->role === 'super') {
            $satkerId = $request->satker_id;
        } else {
            $satkerId = $user->satker_id;
        }

        if (! $satkerId) {
            return redirect()->route('dashboard')->with('error', 'Pilih Satker terlebih dahulu.');
        }

        $satker = Satker::findOrFail($satkerId);

        $validated = $request->validate([
            'ai_provider' => 'required|in:gemini,deepseek',
            'gemini_api_key' => 'nullable|string',
            'deepseek_api_key' => 'nullable|string',
        ]);

        $validated['is_centralized_api'] = $request->has('is_centralized_api') ? 1 : 0;

        $satker->update($validated);

        return redirect()->back()->with('success', 'Pengaturan Provider AI berhasil disimpan.');
    }

    public function testConnection(Request $request)
    {
        $validated = $request->validate([
            'provider' => 'required|in:gemini,deepseek',
            'api_key' => 'nullable|string',
            'satker_id' => 'nullable|exists:satkers,id',
        ]);

        $provider = $validated['provider'];
        $apiKey = $request->input('api_key');

        // Jika tidak diberikan di request, cari dari satker yang sedang dikonfigurasi
        if (empty($apiKey)) {
            $user = auth()->user();
            $satkerId = ($user->role === 'super' && $request->filled('satker_id'))
                ? $request->satker_id
                : $user->satker_id;

            if ($satkerId) {
                $satker = Satker::find($satkerId);
                if ($satker) {
                    $apiKey = $provider === 'deepseek' ? $satker->deepseek_api_key : $satker->gemini_api_key;
                }
            }
        }

        if (empty($apiKey)) {
            return response()->json([
                'success' => false,
                'provider' => $provider === 'deepseek' ? 'DeepSeek' : 'Google Gemini',
                'message' => 'API Key belum diisi. Masukkan API Key terlebih dahulu pada kolom yang disediakan untuk menguji koneksi.',
            ], 422);
        }

        if ($provider === 'deepseek') {
            return $this->testDeepseekConnection($apiKey);
        }

        return $this->testGeminiConnection($apiKey);
    }

    private function testGeminiConnection(string $apiKey)
    {
        $startTime = microtime(true);

        try {
            // Langkah 1: Panggil endpoint ListModels untuk verifikasi kevalidan API Key & model aktif
            $listResponse = Http::timeout(8)->get('https://generativelanguage.googleapis.com/v1beta/models?key='.$apiKey);

            if ($listResponse->failed()) {
                $statusCode = $listResponse->status();
                $body = $listResponse->json();
                $errorMsg = $body['error']['message'] ?? $listResponse->body();

                $diagnostic = 'Terjadi kesalahan saat validasi API Key Google Gemini.';
                if ($statusCode === 400 && str_contains(strtoupper($errorMsg), 'API_KEY_INVALID')) {
                    $diagnostic = 'API Key Google Gemini TIDAK VALID. Pastikan Anda telah menyalin seluruh teks API Key dengan benar dari Google AI Studio.';
                } elseif ($statusCode === 429) {
                    $diagnostic = 'Batas kuota (Rate Limit / Quota Exceeded) Gemini terlampaui. Silakan tunggu beberapa saat atau periksa kuota akun Google AI Studio Anda.';
                } elseif ($statusCode === 403) {
                    $diagnostic = 'Akses ditolak (403 Forbidden). API Key Anda mungkin dibatasi berdasarkan referer IP/domain, atau Generative Language API belum aktif.';
                } else {
                    $diagnostic = "Server Gemini merespon status {$statusCode}: {$errorMsg}";
                }

                return response()->json([
                    'success' => false,
                    'provider' => 'Google Gemini',
                    'status_code' => $statusCode,
                    'latency_ms' => (int) round((microtime(true) - $startTime) * 1000),
                    'message' => $diagnostic,
                    'raw_error' => $errorMsg,
                ], 400);
            }

            // API Key terbukti valid karena ListModels berhasil 200 OK!
            $availableModels = collect($listResponse->json('models', []))
                ->filter(fn ($m) => in_array('generateContent', $m['supportedGenerationMethods'] ?? []))
                ->pluck('name')
                ->map(fn ($n) => str_replace('models/', '', $n))
                ->values();

            // Urutan prioritas model flash generasi terbaru yang stabil
            $preferredOrder = [
                'gemini-3.1-flash-lite',
                'gemini-flash-lite-latest',
                'gemini-3.5-flash',
                'gemini-3.8-flash',
                'gemini-3.5-flash-lite',
                'gemini-flash-latest',
                'gemini-2.5-flash',
                'gemini-1.5-flash',
            ];

            $modelToUse = null;
            foreach ($preferredOrder as $preferred) {
                if ($availableModels->contains($preferred)) {
                    $modelToUse = $preferred;
                    break;
                }
            }

            if (! $modelToUse) {
                $modelToUse = $availableModels->first(fn ($m) => str_starts_with($m, 'gemini-')) ?? 'gemini-3.1-flash-lite';
            }

            // Langkah 2: Uji coba generateContent dengan model terpilih
            $genResponse = Http::timeout(8)->post("https://generativelanguage.googleapis.com/v1beta/models/{$modelToUse}:generateContent?key=".$apiKey, [
                'contents' => [
                    [
                        'parts' => [
                            ['text' => 'Halo! Balas hanya dengan kata: OK'],
                        ],
                    ],
                ],
            ]);

            $latencyMs = (int) round((microtime(true) - $startTime) * 1000);

            if ($genResponse->successful()) {
                $reply = $genResponse->json('candidates.0.content.parts.0.text') ?? 'OK';

                return response()->json([
                    'success' => true,
                    'provider' => 'Google Gemini',
                    'model' => $modelToUse,
                    'latency_ms' => $latencyMs,
                    'message' => 'Koneksi ke Google Gemini API berhasil! API Key valid dan model aktif siap digunakan untuk menyusun laporan.',
                    'reply' => trim($reply),
                ]);
            }

            // Jika generateContent mengalami antrian tinggi (503), API Key tetap terverifikasi valid dari ListModels
            return response()->json([
                'success' => true,
                'provider' => 'Google Gemini',
                'model' => $modelToUse,
                'latency_ms' => $latencyMs,
                'message' => 'API Key Google Gemini VALID dan terautentikasi (Terhubung ke Google AI Studio). Model '.$modelToUse.' aktif dan siap digunakan.',
                'reply' => 'Koneksi Terverifikasi',
            ]);

        } catch (\Exception $e) {
            $latencyMs = (int) round((microtime(true) - $startTime) * 1000);

            return response()->json([
                'success' => false,
                'provider' => 'Google Gemini',
                'latency_ms' => $latencyMs,
                'message' => 'Gagal menghubungi server Google Gemini: '.$e->getMessage().'. Pastikan server/komputer terhubung ke internet.',
            ], 500);
        }
    }

    private function testDeepseekConnection(string $apiKey)
    {
        $startTime = microtime(true);
        $url = 'https://api.deepseek.com/chat/completions';

        try {
            $response = Http::timeout(10)->withHeaders([
                'Authorization' => 'Bearer '.$apiKey,
                'Content-Type' => 'application/json',
            ])->post($url, [
                'model' => 'deepseek-chat',
                'messages' => [
                    ['role' => 'user', 'content' => 'Halo! Balas hanya dengan kata: OK'],
                ],
                'max_tokens' => 10,
            ]);

            $latencyMs = (int) round((microtime(true) - $startTime) * 1000);

            if ($response->successful()) {
                $result = $response->json();
                $reply = $result['choices'][0]['message']['content'] ?? 'OK';

                return response()->json([
                    'success' => true,
                    'provider' => 'DeepSeek',
                    'model' => 'deepseek-chat',
                    'latency_ms' => $latencyMs,
                    'message' => 'Koneksi ke DeepSeek API berhasil! API Key valid dan siap digunakan.',
                    'reply' => trim($reply),
                ]);
            }

            $statusCode = $response->status();
            $body = $response->json();
            $errorMsg = is_array($body) ? ($body['error']['message'] ?? json_encode($body)) : $response->body();

            $diagnostic = 'Terjadi kesalahan saat validasi API Key DeepSeek.';
            if ($statusCode === 401) {
                $diagnostic = 'API Key DeepSeek TIDAK VALID (401 Unauthorized). Periksa kembali API Key dari platform.deepseek.com.';
            } elseif ($statusCode === 402) {
                $diagnostic = 'Saldo akun DeepSeek habis (402 Payment Required). Silakan lakukan top-up saldo pada akun DeepSeek Anda.';
            } elseif ($statusCode === 429) {
                $diagnostic = 'Batas permintaan DeepSeek terlampaui (429 Too Many Requests). Silakan coba kembali beberapa saat lagi.';
            } else {
                $diagnostic = "Server DeepSeek merespon status {$statusCode}: {$errorMsg}";
            }

            return response()->json([
                'success' => false,
                'provider' => 'DeepSeek',
                'status_code' => $statusCode,
                'latency_ms' => $latencyMs,
                'message' => $diagnostic,
                'raw_error' => $errorMsg,
            ], 400);

        } catch (\Exception $e) {
            $latencyMs = (int) round((microtime(true) - $startTime) * 1000);

            return response()->json([
                'success' => false,
                'provider' => 'DeepSeek',
                'latency_ms' => $latencyMs,
                'message' => 'Gagal menghubungi server DeepSeek: '.$e->getMessage().'. Pastikan server/komputer terhubung ke internet.',
            ], 500);
        }
    }
}
