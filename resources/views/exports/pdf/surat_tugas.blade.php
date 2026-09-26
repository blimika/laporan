<!DOCTYPE html>
<html>
<head>
    <title>Surat Tugas</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 11pt; line-height: 1.5; margin: 0; padding: 20px; }
        .text-center { text-align: center; }
        .title { font-weight: bold; text-decoration: underline; margin-bottom: 0; font-size: 12pt; }
        .nomor { text-align: center; margin-top: 2px; font-size: 11pt; }
        .instansi-header { font-weight: bold; font-size: 14pt; font-style: italic; margin-top: 0; margin-bottom: 0; line-height: 1.2; }
        table { width: 100%; border-collapse: collapse; }
        .details-table { margin-top: 20px; }
        .details-table td { padding: 4px; vertical-align: top; }
        .signature { margin-top: 50px; float: right; width: 350px; text-align: center; }
        
        .logo-container { width: 100%; text-align: center; margin-bottom: 0px; }
        .logo-placeholder { 
            display: inline-block;
            font-size: 30pt; 
            font-weight: bold; 
            color: #0088cc;
            font-family: Arial;
        }
    </style>
</head>
<body>
    @php
        $months = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
        ];
        
        $tglBerangkatStr = $suratTugas->tgl_berangkat->format('d') . ' ' . $months[(int)$suratTugas->tgl_berangkat->format('m')] . ' ' . $suratTugas->tgl_berangkat->format('Y');
        $tglKembaliStr = $suratTugas->tgl_kembali->format('d') . ' ' . $months[(int)$suratTugas->tgl_kembali->format('m')] . ' ' . $suratTugas->tgl_kembali->format('Y');
        $tglSuratStr = $suratTugas->tgl_surat->format('d') . ' ' . $months[(int)$suratTugas->tgl_surat->format('m')] . ' ' . $suratTugas->tgl_surat->format('Y');
    @endphp

    <div class="logo-container">
        <img src="{{ public_path('logobps.jpg') }}" alt="Logo BPS" style="width: 100px; height: auto; margin-bottom: 0px;">
        @php
            $satkerName = strtoupper($suratTugas->satker->nama ?? 'Instansi');
            // Jika ada kata BPS, ubah menjadi BADAN PUSAT STATISTIK
            $satkerName = str_replace('BPS ', 'BADAN PUSAT STATISTIK ', $satkerName);
            // Pecah nama menjadi 2 baris (instansi dan nama kota/kab)
            $instansi = 'BADAN PUSAT STATISTIK';
            $daerah = str_replace('BADAN PUSAT STATISTIK ', '', $satkerName);
        @endphp
        <div class="instansi-header" style="margin-top: 5px;">{{ $instansi }}<br>{{ $daerah }}</div>
    </div>

    <div class="text-center" style="margin-top: 30px;">
        <h3 class="title">SURAT TUGAS</h3>
        <div class="nomor">NOMOR : {{ $suratTugas->nomor_surat }}</div>
    </div>

    <div style="margin-top: 30px;">
        <p>Yang bertandatangan di bawah ini:</p>
        <p class="text-center" style="font-weight: bold; margin-top:20px; margin-bottom:20px;">KEPALA {{ $satkerName }}</p>
        <p>Memberi tugas kepada:</p>
    </div>

    <div>
        <table class="details-table">
            <tr>
                <td width="25%">Nama</td>
                <td width="3%">:</td>
                <td>{{ $suratTugas->pegawai->nama }}</td>
            </tr>
            <tr>
                <td>Jabatan</td>
                <td>:</td>
                <td>{{ $suratTugas->pegawai->jabatan }}</td>
            </tr>
            <tr>
                <td>Tujuan/Tugas</td>
                <td>:</td>
                <td>{{ $suratTugas->tujuan }}/{{ $suratTugas->tugas }}</td>
            </tr>
            <tr>
                <td>Waktu Pelaksanaan</td>
                <td>:</td>
                <td>
                    @if($suratTugas->tgl_berangkat->equalTo($suratTugas->tgl_kembali))
                        {{ $tglBerangkatStr }}
                    @else
                        {{ $tglBerangkatStr }} s.d. {{ $tglKembaliStr }}
                    @endif
                </td>
            </tr>
            <tr>
                <td>Pembebanan</td>
                <td>:</td>
                <td>
                    {{ $suratTugas->anggaran->mak ?? '-' }}<br>
                    {{ $suratTugas->uraian_pembebanan ?? '-' }}
                </td>
            </tr>
        </table>
    </div>

    <div class="signature">
        Mataram, {{ $tglSuratStr }}<br>
        Kepala {{ $instansi }}<br>
        {{ ucwords(strtolower($daerah)) }}
        <br><br><br><br><br>
        <strong><u>{{ $suratTugas->kepalaPegawai->nama ?? '.......................................' }}</u></strong><br>
        NIP. {{ $suratTugas->kepalaPegawai->nip ?? '.......................................' }}
    </div>
</body>
</html>
