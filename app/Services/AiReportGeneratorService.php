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
        
        $apiKey = null;
        $isCentralized = $satker->is_centralized_api;
        
        if ($isCentralized) {
            $apiKey = $provider === 'deepseek' ? $satker->deepseek_api_key : $satker->gemini_api_key;
        } else {
            $user = auth()->user();
            if (!$user) {
                return 'Error: Anda belum login.';
            }
            $apiKey = $provider === 'deepseek' ? $user->deepseek_api_key : $user->gemini_api_key;
        }

        if ($provider === 'deepseek') {
            return $this->generateWithDeepseek($prompt, $apiKey, $isCentralized);
        }

        return $this->generateWithGemini($prompt, $apiKey, $isCentralized);
    }

    private function generateWithDeepseek(string $prompt, ?string $apiKey, bool $isCentralized): string
    {
        if (empty($apiKey)) {
            Log::error('Deepseek API Key is not set.');
            return $isCentralized 
                ? 'Error: API Key Deepseek untuk Satker Anda belum dikonfigurasi oleh Admin.'
                : 'Error: Anda belum mengisi Deepseek API Key di pengaturan Profil Anda.';
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

    private function generateWithGemini(string $prompt, ?string $apiKey, bool $isCentralized): string
    {
        if (empty($apiKey)) {
            Log::error('Gemini API Key is not set.');
            return $isCentralized 
                ? 'Error: API Key Gemini untuk Satker Anda belum dikonfigurasi oleh Admin.'
                : 'Error: Anda belum mengisi Google Gemini API Key di pengaturan Profil Anda.';
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
        $is8Jam = !empty($data['is_8_jam']);

        if ($is8Jam) {
            return <<<EOT
Peran: Anda adalah staf penyusun laporan kedinasan/administrasi pemerintahan di lingkungan instansi statistik/pemerintahan.

Tugas: Susun laporan "Hasil Perjalanan Dinas" transpor lokal dalam bahasa Indonesia baku dan formal. Anda HARUS menghasilkan output dalam format TABEL MARKDOWN.

Data Masukan:
- Tujuan Perjalanan: {$tujuan}
- Lokasi: {$lokasi}
- Pihak/Pegawai yang Ditemui: {$pegawai}
- Kategori Kegiatan: {$kategori}
- Kendala/Masalah yang Dihadapi: {$kendala}

Format Laporan (Tabel Markdown):
Tabel harus memiliki tepat 2 kolom: "Waktu" dan "Kegiatan".
Buatlah rentang waktu yang logis dari jam 08.00 hingga 17.00 WITA. Harus ada jam keberangkatan (08.00), kedatangan di lokasi, pelaksanaan substansi kegiatan sesuai tujuan, waktu istirahat (Ishoma pada pukul 12.00-13.00), penutup/pengecekan akhir, dan perjalanan kembali ke kantor hingga jam 17.00 WITA.

Contoh Format:
| Waktu | Kegiatan |
|---|---|
| 08.00-08.30 WITA | Berangkat menuju {$lokasi} |
| 08.30-09.00 WITA | Tiba di lokasi dan bertemu dengan {$pegawai} ... |
| 09.00-12.00 WITA | Melaksanakan kegiatan {$kategori} ... |
| 12.00-13.00 WITA | Ishoma |
| 13.00-16.00 WITA | Melanjutkan kegiatan ... |
| 16.00-17.00 WITA | Perjalanan kembali ke kantor |

Ketentuan:
- HANYA output tabel Markdown. Jangan tambahkan teks apa pun sebelum atau sesudah tabel.
EOT;
        }

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
