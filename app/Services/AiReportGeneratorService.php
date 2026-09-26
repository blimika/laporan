<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AiReportGeneratorService
{
    public function generate(array $data): ?string
    {
        $prompt = $this->buildPrompt($data);

        // Ambil provider & key dari tabel satkers
        $satkerId = session('satker_id');
        $satker = \App\Models\Satker::find($satkerId);

        if (!$satker) {
            return 'Error: Satuan Kerja tidak ditemukan di sesi Anda.';
        }

        $provider = $satker->ai_provider ?? 'gemini';

        if ($provider === 'deepseek') {
            return $this->generateWithDeepseek($prompt, $satker->deepseek_api_key);
        }

        return $this->generateWithGemini($prompt, $satker->gemini_api_key);
    }

    private function generateWithDeepseek(string $prompt, ?string $apiKey): string
    {
        if (empty($apiKey)) {
            Log::error('Deepseek API Key is not set.');
            return 'Error: API Key Deepseek untuk Satker Anda belum dikonfigurasi oleh Admin.';
        }

        $url = 'https://api.deepseek.com/chat/completions';

        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $apiKey,
                'Content-Type' => 'application/json',
            ])->post($url, [
                'model' => 'deepseek-chat',
                'messages' => [
                    ['role' => 'system', 'content' => 'Anda adalah asisten AI.'],
                    ['role' => 'user', 'content' => $prompt]
                ],
                'max_tokens' => 1024,
            ]);

            if ($response->successful()) {
                $result = $response->json();
                return $result['choices'][0]['message']['content'] ?? null;
            }

            Log::error('Deepseek API Error: '.$response->body());
            return 'Error: Gagal menghasilkan narasi dari AI Deepseek.';
        } catch (\Exception $e) {
            Log::error('Deepseek API Exception: '.$e->getMessage());
            return 'Error: Terjadi kesalahan saat menghubungi API Deepseek.';
        }
    }

    private function generateWithGemini(string $prompt, ?string $apiKey): string
    {
        if (empty($apiKey)) {
            Log::error('Gemini API Key is not set.');
            return 'Error: API Key Gemini untuk Satker Anda belum dikonfigurasi oleh Admin.';
        }

        $url = 'https://generativelanguage.googleapis.com/v1beta/models/gemini-2.5-flash:generateContent?key='.$apiKey;

        try {
            $response = Http::post($url, [
                'contents' => [
                    [
                        'parts' => [
                            ['text' => $prompt],
                        ],
                    ],
                ],
            ]);

            if ($response->failed()) {
                Log::warning('Gemini 2.5 Flash gagal, mencoba fallback. Error: '.$response->body());
                $url = 'https://generativelanguage.googleapis.com/v1beta/models/gemini-3.5-flash:generateContent?key='.$apiKey;
                $response = Http::post($url, [
                    'contents' => [
                        [
                            'parts' => [
                                ['text' => $prompt],
                            ],
                        ],
                    ],
                ]);

                if ($response->failed()) {
                    Log::warning('Gemini 3.5 Flash gagal. Error: '.$response->body());
                }
            }

            if ($response->successful()) {
                $result = $response->json();
                return $result['candidates'][0]['content']['parts'][0]['text'] ?? null;
            }

            Log::error('Gemini API Error: '.$response->body());
            return 'Error: Gagal menghasilkan narasi dari AI.';
        } catch (\Exception $e) {
            Log::error('Gemini API Exception: '.$e->getMessage());
            return 'Error: Terjadi kesalahan saat menghubungi API.';
        }
    }

    /**
     * Build the prompt based on the template
     */
    private function buildPrompt(array $data): string
    {
        $tujuan = $data['tujuan_perjalanan'] ?? '-';
        $lokasi = $data['lokasi_perjalanan'] ?? '-';
        $pegawai = $data['pegawai_ditemui'] ?? '-';
        $kategori = $data['kategori'] ?? '-';
        $kendala = $data['kendala_ditemui'] ?? 'Tidak ada kendala';

        return <<<EOT
Peran: Anda adalah staf penyusun laporan kedinasan/administrasi pemerintahan di lingkungan instansi statistik/pemerintahan.

Tugas: Susun narasi "Hasil Perjalanan Dinas" transpor lokal dalam bahasa Indonesia baku dan formal. Panjang narasi HARUS TEPAT DUA (2) PARAGRAF saja.

Data Masukan:
- Tujuan Perjalanan: {$tujuan}
- Lokasi: {$lokasi}
- Pihak/Pegawai yang Ditemui: {$pegawai}
- Kategori Kegiatan: {$kategori}
- Kendala/Masalah yang Dihadapi: {$kendala}

Format Narasi (2 Paragraf):
- Paragraf 1: Penjelasan kedatangan di lokasi, koordinasi awal dengan pihak yang ditemui, dan rincian pelaksanaan substansi kegiatan.
- Paragraf 2: Rangkuman temuan atau kendala (jika ada), hasil akhir pelaksanaan, output data, serta tindak lanjut yang diperlukan (jika relevan).

Ketentuan:
- Hindari bahasa informal, gunakan ejaan EYD/PUEBI dan terminologi birokrasi pemerintahan.
- Jangan menambahkan salam pembuka/penutup, langsung berikan 2 paragraf narasi.
EOT;
    }
}
