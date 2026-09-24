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
        $suratTugas->load(['pegawai', 'kepalaPegawai', 'anggaran']);
        $pdf = Pdf::loadView('exports.pdf.surat_tugas', compact('suratTugas'));

        $safeName = str_replace(['/', '\\'], '_', $suratTugas->nomor_surat);
        return $pdf->stream('surat_tugas_'.$safeName.'.pdf');
    }

    public function exportSpd(Spd $spd)
    {
        $spd->load(['suratTugas.pegawai', 'suratTugas.anggaran', 'ppkPegawai']);
        $pdf = Pdf::loadView('exports.pdf.spd', compact('spd'));

        $safeName = str_replace(['/', '\\'], '_', $spd->nomor_spd);
        return $pdf->stream('spd_'.$safeName.'.pdf');
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
}
