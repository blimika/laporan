<?php

namespace App\Services;

use App\Models\LaporanPerjalanan;
use App\Models\Spd;
use App\Models\SuratTugas;
use Barryvdh\DomPDF\Facade\Pdf;

class DocumentExportService
{
    public function exportSuratTugas(SuratTugas $suratTugas)
    {
        $suratTugas->load(['pegawai', 'kepalaPegawai', 'anggaran', 'satker']);
        $pdf = Pdf::loadView('exports.pdf.surat_tugas', compact('suratTugas'));

        $safeName = str_replace(['/', '\\'], '_', $suratTugas->nomor_surat);

        return $pdf->stream('surat_tugas_'.$safeName.'.pdf');
    }

    public function exportSuratTugasGabungan(SuratTugas $suratTugas, string $spdTemplate = 'template_1')
    {
        $suratTugas->load([
            'pegawai',
            'kepalaPegawai',
            'anggaran',
            'satker',
            'spd.ppkPegawai',
            'spd.suratTugas.pegawai',
            'spd.suratTugas.kepalaPegawai',
            'spd.suratTugas.anggaran',
            'spd.suratTugas.satker',
        ]);

        $spd = $suratTugas->spd;
        $pdf = Pdf::loadView('exports.pdf.surat_tugas_spd_gabungan', compact('suratTugas', 'spd', 'spdTemplate'));

        $safeName = str_replace(['/', '\\'], '_', $suratTugas->nomor_surat);
        $fileName = $spd ? 'dokumen_lengkap_'.$safeName.'.pdf' : 'surat_tugas_'.$safeName.'.pdf';

        return $pdf->stream($fileName);
    }

    public function exportSpd(Spd $spd, string $template = 'template_1')
    {
        $spd->load(['suratTugas.pegawai', 'suratTugas.anggaran', 'suratTugas.satker', 'suratTugas.kepalaPegawai', 'ppkPegawai']);
        $pdf = Pdf::loadView('exports.pdf.spd', compact('spd', 'template'));

        $safeName = str_replace(['/', '\\'], '_', $spd->nomor_spd);
        $fileName = ($template === 'template_2')
            ? 'spd_lampiran_st_'.$safeName.'.pdf'
            : 'spd_'.$safeName.'.pdf';

        return $pdf->stream($fileName);
    }

    public function exportDpr(Spd $spd)
    {
        $spd->load(['suratTugas.pegawai', 'ppkPegawai']);
        $pdf = Pdf::loadView('exports.pdf.dpr', compact('spd'));

        $safeName = str_replace(['/', '\\'], '_', $spd->nomor_spd);

        return $pdf->stream('dpr_'.$safeName.'.pdf');
    }

    public function exportPernyataan(Spd $spd)
    {
        $spd->load(['suratTugas.pegawai', 'suratTugas.anggaran']);
        $pdf = Pdf::loadView('exports.pdf.pernyataan', compact('spd'));

        $safeName = str_replace(['/', '\\'], '_', $spd->nomor_spd);

        return $pdf->stream('pernyataan_riil_'.$safeName.'.pdf');
    }

    public function exportLaporan(LaporanPerjalanan $laporan)
    {
        $laporan->load(['suratTugas.pegawai', 'suratTugas.spd', 'dokumentasi']);
        $pdf = Pdf::loadView('exports.pdf.laporan_perjalanan', compact('laporan'));

        $safeName = str_replace(['/', '\\'], '_', $laporan->suratTugas->nomor_surat);

        return $pdf->stream('laporan_perjalanan_'.$safeName.'.pdf');
    }

    public function exportLaporanWord(LaporanPerjalanan $laporan)
    {
        $laporan->load(['suratTugas.pegawai', 'suratTugas.spd', 'dokumentasi']);
        $isWord = true;
        $html = view('exports.pdf.laporan_perjalanan', compact('laporan', 'isWord'))->render();

        $safeName = str_replace(['/', '\\'], '_', $laporan->suratTugas->nomor_surat);

        return response($html)
            ->header('Content-Type', 'application/vnd.ms-word')
            ->header('Content-Disposition', 'attachment; filename="laporan_perjalanan_'.$safeName.'.doc"');
    }
}
