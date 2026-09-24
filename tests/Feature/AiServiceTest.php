<?php

namespace Tests\Feature;

use App\Services\AiReportGeneratorService;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class AiServiceTest extends TestCase
{
    public function test_gemini_api_can_generate_report(): void
    {
        config(['services.gemini.key' => 'fake_key']);

        Http::fake([
            '*' => Http::response([
                'candidates' => [
                    [
                        'content' => [
                            'parts' => [
                                ['text' => 'Ini adalah hasil narasi AI.'],
                            ],
                        ],
                    ],
                ],
            ], 200),
        ]);

        $service = new AiReportGeneratorService;
        $result = $service->generate([
            'tujuan_perjalanan' => 'Jakarta',
            'lokasi_perjalanan' => 'Kantor Pusat',
            'pegawai_ditemui' => 'Bapak Budi',
            'kategori' => 'Koordinasi',
            'kendala_ditemui' => 'Tidak ada',
        ]);

        $this->assertEquals('Ini adalah hasil narasi AI.', $result);
    }
}
