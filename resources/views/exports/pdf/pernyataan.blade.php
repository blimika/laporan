<!DOCTYPE html>
<html>
<head>
    <title>Surat Pernyataan</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 11pt; line-height: 1.5; margin: 0; padding: 20px 40px; }
        .text-center { text-align: center; }
        .title { font-weight: bold; margin-bottom: 30px; font-size: 12pt; }
        .content-table { width: 100%; margin-bottom: 25px; }
        .content-table td { padding: 5px 0; vertical-align: top; }
        
        .w-25 { width: 25%; }
        .w-2 { width: 2%; }
        .w-73 { width: 73%; }
        
        .signature-right { float: right; width: 50%; text-align: center; margin-top: 50px; }
    </style>
</head>
<body>
    @php
        $months = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
        ];
        
        $tglBerangkatStr = $spd->suratTugas->tgl_berangkat->format('d') . ' ' . $months[(int)$spd->suratTugas->tgl_berangkat->format('m')] . ' ' . $spd->suratTugas->tgl_berangkat->format('Y');
        $tglSuratStr = $spd->suratTugas->tgl_surat->format('d') . ' ' . $months[(int)$spd->suratTugas->tgl_surat->format('m')] . ' ' . $spd->suratTugas->tgl_surat->format('Y');
    @endphp

    <div class="text-center title">SURAT PERNYATAAN</div>

    <p style="margin-bottom: 15px;">Yang bertanda tangan di bawah ini :</p>
    
    <table class="content-table">
        <tr><td class="w-25">Nama</td><td class="w-2">:</td><td class="w-73">{{ $spd->suratTugas->pegawai->nama }}</td></tr>
        <tr><td>NIP</td><td>:</td><td>{{ $spd->suratTugas->pegawai->nip }}</td></tr>
        <tr><td>Pangkat/Golongan</td><td>:</td><td>{{ $spd->suratTugas->pegawai->golongan }} - {{ $spd->suratTugas->pegawai->pangkat }}</td></tr>
        <tr><td>Jabatan</td><td>:</td><td>{{ $spd->suratTugas->pegawai->jabatan }}</td></tr>
        <tr><td>Unit Kerja</td><td>:</td><td>BPS Kota Mataram</td></tr>
    </table>
    
    <p style="text-align: justify; margin-bottom: 20px;">
        Menerangkan bahwa dalam rangka melaksanakan perjalanan dinas dalam kota untuk melaksanakan tugas kedinasan sesuai surat tugas nomor: {{ $spd->suratTugas->nomor_surat }} pelaksanaan tanggal {{ $tglBerangkatStr }}, saya benar-benar tidak menggunakan kendaraan dinas.
    </p>
    
    <p style="text-align: justify;">
        Demikian pernyataan ini kami buat dengan sebenar-benarnya untuk dipergunakan sebagaimana mestinya. Apabila terdapat kekeliruan dalam pertanggungjawaban SPD dan mengakibatkan kerugian negara, saya bersedia dituntut sesuai aturan yang berlaku dan mengembalikan biaya transport lokal yang sudah saya terima ke kas negara.
    </p>

    <div class="signature-right">
        Mataram, {{ $tglSuratStr }}<br>
        Pejabat Negara/Pegawai Negeri<br>
        Yang Melakukan Perjalanan Dinas
        <br><br><br><br><br><br>
        <strong><u>{{ $spd->suratTugas->pegawai->nama }}</u></strong><br>
        NIP. {{ $spd->suratTugas->pegawai->nip }}
    </div>
</body>
</html>
