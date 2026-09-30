<!DOCTYPE html>
<html>
<head>
    <title>Laporan Perjalanan Dinas</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 11pt; line-height: 1.2; margin: 0; padding: 20px 40px; }
        .text-center { text-align: center; }
        .content { text-align: justify; line-height: 1.4; margin-top: 10px; }
        .content p { margin-top: 0; margin-bottom: 10px; }
        .content table { width: 100%; border-collapse: collapse; margin-bottom: 15px; }
        .content th, .content td { border: 1px solid black; padding: 5px; vertical-align: top; }
        .content table th:first-child, .content table td:first-child { width: 15%; }
        .signature { margin-top: 15px; float: right; width: 50%; text-align: center; }
        .page-break { page-break-before: always; }
        .section-title { font-weight: bold; margin-bottom: 2px; margin-top: 8px; }
        .info-table { width: 100%; margin-left: 15px; margin-bottom: 2px; }
        .info-table td { padding: 1px 0; vertical-align: top; }
        .w-35 { width: 35%; }
        .w-2 { width: 2%; }
    </style>
</head>
<body>
    @php
        $months = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
        ];
        
        $tglPerjalananStr = $laporan->tgl_perjalanan->format('d') . ' ' . $months[(int)$laporan->tgl_perjalanan->format('m')] . ' ' . $laporan->tgl_perjalanan->format('Y');
        $tglLaporanStr = $laporan->tgl_laporan->format('d') . ' ' . $months[(int)$laporan->tgl_laporan->format('m')] . ' ' . $laporan->tgl_laporan->format('Y');
    @endphp

    <div class="text-center" style="font-weight: bold; font-size: 12pt; margin-bottom: 30px;">
        LAPORAN PERJALANAN DINAS
    </div>

    <table style="width: 100%; border-spacing: 0;">
        <tr>
            <td style="width: 5%; vertical-align: top; font-weight: bold;">I.</td>
            <td style="width: 95%; font-weight: bold;">DASAR HUKUM</td>
        </tr>
    </table>
    <table class="info-table" style="margin-left: 5%;">
        <tr>
            <td style="width: 35%;">
                <span style="display:inline-block; width: 15px;">1.</span> Surat Tugas Nomor
            </td>
            <td class="w-2">:</td>
            <td>{{ $laporan->suratTugas->nomor_surat }}</td>
        </tr>
        <tr>
            <td>
                <span style="display:inline-block; width: 15px;">2.</span> SPD Nomor
            </td>
            <td>:</td>
            <td>{{ $laporan->suratTugas->spd->nomor_spd ?? '-' }}</td>
        </tr>
    </table>
    <br>

    <table style="width: 100%; border-spacing: 0;">
        <tr>
            <td style="width: 5%; vertical-align: top; font-weight: bold;">II.</td>
            <td style="width: 95%; font-weight: bold;">WAKTU PELAKSANAAN</td>
        </tr>
    </table>
    <table class="info-table" style="margin-left: 5%;">
        <tr>
            <td style="width: 35%;">
                Perjalanan dilaksanakan pada tanggal
            </td>
            <td class="w-2">:</td>
            <td>{{ $tglPerjalananStr }}</td>
        </tr>
    </table>
    <br>

    <table style="width: 100%; border-spacing: 0;">
        <tr>
            <td style="width: 5%; vertical-align: top; font-weight: bold;">III.</td>
            <td style="width: 95%; font-weight: bold;">LOKASI</td>
        </tr>
    </table>
    <table class="info-table" style="margin-left: 5%;">
        <tr>
            <td style="width: 35%;">
                Lokasi perjalanan adalah
            </td>
            <td class="w-2">:</td>
            <td>{{ $laporan->lokasi_perjalanan }}</td>
        </tr>
    </table>
    <br>

    <table style="width: 100%; border-spacing: 0;">
        <tr>
            <td style="width: 5%; vertical-align: top; font-weight: bold;">IV.</td>
            <td style="width: 95%; font-weight: bold;">TUJUAN PERJALANAN</td>
        </tr>
    </table>
    <table class="info-table" style="margin-left: 5%;">
        <tr>
            <td style="width: 35%;">
                Tujuan perjalanan adalah
            </td>
            <td class="w-2">:</td>
            <td>{{ $laporan->suratTugas->tugas }}</td>
        </tr>
    </table>
    <br>

    <table style="width: 100%; border-spacing: 0;">
        <tr>
            <td style="width: 5%; vertical-align: top; font-weight: bold;">V.</td>
            <td style="width: 95%; font-weight: bold;">HASIL PERJALANAN</td>
        </tr>
    </table>

    <div class="content">
        {!! \Illuminate\Support\Str::markdown($laporan->hasil_perjalanan) !!}
    </div>

    <div class="signature">
        Mataram, {{ $tglLaporanStr }}<br>
        Yang melaksanakan tugas,
        <div style="margin-top: 48pt;">
            <strong><u>{{ $laporan->suratTugas->pegawai->nama }}</u></strong><br>
            NIP. {{ $laporan->suratTugas->pegawai->nip }}
        </div>
    </div>

    @if($laporan->dokumentasi->count() > 0)
        <!-- Halaman Dokumentasi -->
        <div class="page-break"></div>
        <div class="text-center" style="font-weight: bold; font-size: 12pt; margin-bottom: 50px;">
            DOKUMENTASI
        </div>
        
        @foreach($laporan->dokumentasi as $doc)
            @php
                $imagePath = public_path($doc->file_path);
                $imageData = '';
                if(file_exists($imagePath)) {
                    $type = pathinfo($imagePath, PATHINFO_EXTENSION);
                    $data = file_get_contents($imagePath);
                    $imageData = 'data:image/' . $type . ';base64,' . base64_encode($data);
                }
            @endphp
            @if($imageData)
            <div style="text-align: center; margin-bottom: 40px; width: 100%;">
                <img src="{{ $imageData }}" alt="Dokumentasi" style="max-width: 100%; max-height: 450px; height: auto; width: auto;">
                @if($laporan->dokumentasi->count() > 1)
                    <div style="margin-top: 10px; font-style: italic; font-size: 10pt;">
                        Gambar {{ $loop->iteration }}: {{ $doc->keterangan_foto }}
                    </div>
                @endif
            </div>
            @endif
        @endforeach
    @endif
</body>
</html>
