<!DOCTYPE html>
<html>
<head>
    <title>Daftar Pengeluaran Riil (DPR)</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 11pt; line-height: 1.5; margin: 0; padding: 20px; }
        .text-center { text-align: center; }
        table.bordered { width: 100%; border-collapse: collapse; margin-top: 15px; }
        table.bordered th, table.bordered td { border: 1px solid #000; padding: 6px 10px; }
        .signature-container { width: 100%; margin-top: 30px; }
        .signature-left { float: left; width: 45%; text-align: center; }
        .signature-right { float: right; width: 45%; text-align: center; }
        .clear { clear: both; }
        .title { font-weight: bold; margin-bottom: 30px; font-size: 12pt; }
        .content-table td { padding: 3px 0; vertical-align: top; }
        
        /* Utility */
        .w-20 { width: 20%; }
        .w-2 { width: 2%; }
        .w-78 { width: 78%; }
    </style>
</head>
<body>
    @php
        $months = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
        ];
        
        $tglSuratStr = $spd->suratTugas->tgl_surat->format('d') . ' ' . $months[(int)$spd->suratTugas->tgl_surat->format('m')] . ' ' . $spd->suratTugas->tgl_surat->format('Y');
        
        // Helper fungsi terbilang sederhana
        function terbilang($angka) {
            $angka = abs($angka);
            $baca = array("", "Satu", "Dua", "Tiga", "Empat", "Lima", "Enam", "Tujuh", "Delapan", "Sembilan", "Sepuluh", "Sebelas");
            $terbilang = "";
            if ($angka < 12) {
                $terbilang = " " . $baca[(int)$angka];
            } else if ($angka < 20) {
                $terbilang = terbilang($angka - 10) . " Belas";
            } else if ($angka < 100) {
                $terbilang = terbilang($angka / 10) . " Puluh " . terbilang($angka % 10);
            } else if ($angka < 200) {
                $terbilang = " Seratus " . terbilang($angka - 100);
            } else if ($angka < 1000) {
                $terbilang = terbilang($angka / 100) . " Ratus " . terbilang($angka % 100);
            } else if ($angka < 2000) {
                $terbilang = " Seribu " . terbilang($angka - 1000);
            } else if ($angka < 1000000) {
                $terbilang = terbilang($angka / 1000) . " Ribu " . terbilang($angka % 1000);
            } else if ($angka < 1000000000) {
                $terbilang = terbilang($angka / 1000000) . " Juta " . terbilang($angka % 1000000);
            }
            return trim($terbilang);
        }
        
        $nominal = $spd->nilai_dpr ?? 100000; // As per screenshot, defaults to 100.000 if null
        $terbilangStr = terbilang($nominal) . " Rupiah";
    @endphp

    <div class="text-center title">DAFTAR PENGELUARAN RIIL</div>

    <p style="margin-bottom: 10px;">Yang bertanda tangan di bawah ini :</p>
    
    <table class="content-table" style="width:100%; margin-bottom: 10px;">
        <tr><td class="w-20">Nama</td><td class="w-2">:</td><td class="w-78">{{ $spd->suratTugas->pegawai->nama }}</td></tr>
        <tr><td>NIP</td><td>:</td><td>{{ $spd->suratTugas->pegawai->nip }}</td></tr>
    </table>
    
    <p style="margin-bottom: 10px; margin-top: 15px;">Berdasarkan Surat Perintah Perjalanan Dinas (SPPD)</p>
    
    <table class="content-table" style="width:100%; margin-bottom: 15px;">
        <tr><td class="w-20">Tanggal</td><td class="w-2">:</td><td class="w-78">{{ $tglSuratStr }}</td></tr>
        <tr><td>Nomor</td><td>:</td><td>{{ $spd->nomor_spd }}</td></tr>
    </table>

    <p style="margin-bottom: 15px;">dengan ini kami menyatakan dengan sesungguhnya bahwa :</p>
    
    <table style="width:100%;">
        <tr>
            <td width="3%" style="vertical-align:top;">1.</td>
            <td width="97%" style="vertical-align:top;">Biaya transport pegawai dan/atau biaya penginapan di bawah ini yang tidak diperoleh bukti-bukti pengeluarannya, meliputi :</td>
        </tr>
    </table>
    
    <table class="bordered">
        <thead>
            <tr>
                <th width="8%">No.</th>
                <th width="62%">Uraian</th>
                <th width="30%">Jumlah (Rp)</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td class="text-center" style="height: 40px; vertical-align: middle;">1</td>
                <td style="vertical-align: middle;">Transport Lokal</td>
                <td class="text-center" style="vertical-align: middle;">{{ number_format($nominal, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td colspan="2" class="text-center">Total</td>
                <td class="text-center">{{ number_format($nominal, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td colspan="3" class="text-center" style="font-style: italic;">{{ $terbilangStr }}</td>
            </tr>
        </tbody>
    </table>

    <table style="width:100%; margin-top: 20px;">
        <tr>
            <td width="3%" style="vertical-align:top;">2.</td>
            <td width="97%" style="vertical-align:top; text-align: justify;">Jumlah uang tersebut pada angka 1 di atas benar-benar dikeluarkan untuk atas perjalanan dinas dimaksud dan apabila di kemudian hari terdapat kelebihan pembayaran, kami bersedia untuk menyetorkan kelebihan tersebut ke Kas Negara.</td>
        </tr>
    </table>

    <p style="margin-top: 25px; text-align: justify;">Demikian pernyataan ini kami buat dengan sebenarnya, untuk dipergunakan sebagaimana mestinya.</p>

    <div class="signature-container">
        <div class="signature-left">
            Mengetahui/Menyetujui<br>
            Pejabat Pembuat Komitmen,
            <br><br><br><br><br><br>
            <strong><u>{{ $spd->ppkPegawai->nama ?? '.......................................' }}</u></strong><br>
            NIP. {{ $spd->ppkPegawai->nip ?? '.......................................' }}
        </div>
        <div class="signature-right">
            Mataram, {{ $tglSuratStr }}<br>
            Pelaksana SPD
            <br><br><br><br><br><br>
            <strong><u>{{ $spd->suratTugas->pegawai->nama }}</u></strong><br>
            NIP. {{ $spd->suratTugas->pegawai->nip }}
        </div>
    </div>
</body>
</html>
