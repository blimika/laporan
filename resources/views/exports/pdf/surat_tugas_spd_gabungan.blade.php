<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Surat Tugas & SPD</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }

        .page-break {
            page-break-after: always;
        }

        .clear {
            clear: both;
        }

        .text-center {
            text-align: center;
        }

        /* ================= Surat Tugas Styles ================= */
        .doc-st {
            font-size: 11pt;
            line-height: 1.5;
            padding: 20px;
        }
        .doc-st .title {
            font-weight: bold;
            text-decoration: underline;
            margin-bottom: 0;
            font-size: 12pt;
        }
        .doc-st .nomor {
            text-align: center;
            margin-top: 2px;
            font-size: 11pt;
        }
        .doc-st .instansi-header {
            font-weight: bold;
            font-size: 14pt;
            font-style: italic;
            margin-top: 0;
            margin-bottom: 0;
            line-height: 1.2;
        }
        .doc-st table {
            width: 100%;
            border-collapse: collapse;
        }
        .doc-st .details-table {
            margin-top: 20px;
        }
        .doc-st .details-table td {
            padding: 4px;
            vertical-align: top;
        }
        .doc-st .signature {
            margin-top: 50px;
            float: right;
            width: 350px;
            text-align: center;
        }
        .doc-st .logo-container {
            width: 100%;
            text-align: center;
            margin-bottom: 0px;
        }

        /* ================= SPD Styles ================= */
        .doc-spd {
            font-size: 10pt;
            line-height: 1.15;
            padding: 0 10px;
        }
        .doc-spd .instansi-header {
            font-weight: bold;
            font-size: 12pt;
            font-style: italic;
            margin-top: 0;
            margin-bottom: 0;
            line-height: 1.1;
        }
        .doc-spd .logo-container {
            width: 100%;
            text-align: center;
            margin-bottom: 0px;
        }
        .doc-spd table.info-header {
            width: 100%;
            margin-top: 5px;
            font-size: 10pt;
        }
        .doc-spd table.info-header td {
            padding: 0px 2px;
        }
        .doc-spd table.bordered {
            width: 100%;
            border-collapse: collapse;
            margin-top: 3px;
            font-size: 9.5pt;
        }
        .doc-spd table.bordered th, .doc-spd table.bordered td {
            border: 1px solid #000;
            padding: 2px 4px;
            vertical-align: top;
        }
        .doc-spd .signature {
            margin-top: 10px;
            width: 300px;
            float: right;
        }
        .doc-spd .signature td {
            padding: 1px;
        }
        .doc-spd .title {
            font-weight: bold;
            text-decoration: underline;
            margin-bottom: 0;
            font-size: 11pt;
        }
        .doc-spd table.hal2-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 5px;
            font-size: 9.5pt;
        }
        .doc-spd table.hal2-table td {
            border: 1px solid #000;
            padding: 4px;
            vertical-align: top;
            height: 250px;
        }
        .doc-spd .hal2-header-right {
            float: right;
            width: 48%;
            margin-bottom: 2px;
            font-size: 9.5pt;
            margin-top: 5px;
        }
        .doc-spd .note-row td {
            height: auto !important;
        }
        .doc-spd .attention-row td {
            height: auto !important;
            text-align: justify;
        }

        /* ================= SPD Template 2 (Lampiran ST) Styles ================= */
        .doc-spd-t2 {
            font-size: 10pt;
            line-height: 1.25;
            padding: 0 10px;
        }
        .doc-spd-t2 .header-lampiran {
            margin-bottom: 12px;
        }
        .doc-spd-t2 table.spd-grid {
            width: 100%;
            border-collapse: collapse;
            border: 1px solid #000;
            margin-top: 10px;
        }
        .doc-spd-t2 table.spd-grid > tbody > tr > td {
            border: 1px solid #000;
            vertical-align: top;
            padding: 10px 12px;
            width: 50%;
            box-sizing: border-box;
        }
        .doc-spd-t2 table.inner-info {
            width: 100%;
            border-collapse: collapse;
            font-size: 9.5pt;
        }
        .doc-spd-t2 table.inner-info td {
            padding: 1px 0;
            vertical-align: top;
        }

        /* ================= DPR Styles ================= */
        .doc-dpr {
            font-size: 11pt;
            line-height: 1.5;
            padding: 20px;
        }
        .doc-dpr table.bordered {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }
        .doc-dpr table.bordered th, .doc-dpr table.bordered td {
            border: 1px solid #000;
            padding: 6px 10px;
        }
        .doc-dpr .signature-container {
            width: 100%;
            margin-top: 30px;
        }
        .doc-dpr .signature-left {
            float: left;
            width: 45%;
            text-align: center;
        }
        .doc-dpr .signature-right {
            float: right;
            width: 45%;
            text-align: center;
        }
        .doc-dpr .title {
            font-weight: bold;
            margin-bottom: 30px;
            font-size: 12pt;
        }
        .doc-dpr .content-table td {
            padding: 3px 0;
            vertical-align: top;
        }
        .doc-dpr .w-20 { width: 20%; }
        .doc-dpr .w-2 { width: 2%; }
        .doc-dpr .w-78 { width: 78%; }

        /* ================= Pernyataan Styles ================= */
        .doc-pernyataan {
            font-size: 11pt;
            line-height: 1.5;
            padding: 20px 40px;
        }
        .doc-pernyataan .title {
            font-weight: bold;
            margin-bottom: 30px;
            font-size: 12pt;
        }
        .doc-pernyataan .content-table {
            width: 100%;
            margin-bottom: 25px;
        }
        .doc-pernyataan .content-table td {
            padding: 5px 0;
            vertical-align: top;
        }
        .doc-pernyataan .w-25 { width: 25%; }
        .doc-pernyataan .w-2 { width: 2%; }
        .doc-pernyataan .w-73 { width: 73%; }
        .doc-pernyataan .signature-right {
            float: right;
            width: 50%;
            text-align: center;
            margin-top: 50px;
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

        $satkerName = strtoupper($suratTugas->satker->nama ?? 'Instansi');
        $satkerName = str_replace('BPS ', 'BADAN PUSAT STATISTIK ', $satkerName);
        $instansi = 'BADAN PUSAT STATISTIK';
        $daerah = str_replace('BADAN PUSAT STATISTIK ', '', $satkerName);
        $tempatKedudukan = trim(preg_replace('/^(BPS\s+|BADAN\s+PUSAT\s+STATISTIK\s+)?(KOTA\s+|KABUPATEN\s+|KAB\.\s+|PROVINSI\s+)?/i', '', $suratTugas->satker->nama ?? 'Mataram')) ?: 'Mataram';
        $tujuan = $suratTugas->tujuan ?? $tempatKedudukan;

        $kepalaPegawai = $suratTugas->kepalaPegawai ?? null;
        $jabatanPenandatangan = 'Kepala';
        if ($kepalaPegawai) {
            if ($kepalaPegawai->hasTag('plh') || stripos($kepalaPegawai->jabatan ?? '', 'plh') !== false) {
                $jabatanPenandatangan = 'Plh. Kepala';
            } elseif ($kepalaPegawai->hasTag('plt') || stripos($kepalaPegawai->jabatan ?? '', 'plt') !== false) {
                $jabatanPenandatangan = 'Plt. Kepala';
            }
        }

        if (!function_exists('terbilang_helper')) {
            function terbilang_helper($angka) {
                $angka = abs($angka);
                $baca = array("", "Satu", "Dua", "Tiga", "Empat", "Lima", "Enam", "Tujuh", "Delapan", "Sembilan", "Sepuluh", "Sebelas");
                $terbilang = "";
                if ($angka < 12) {
                    $terbilang = " " . $baca[(int)$angka];
                } else if ($angka < 20) {
                    $terbilang = terbilang_helper($angka - 10) . " Belas";
                } else if ($angka < 100) {
                    $terbilang = terbilang_helper($angka / 10) . " Puluh " . terbilang_helper($angka % 10);
                } else if ($angka < 200) {
                    $terbilang = " Seratus " . terbilang_helper($angka - 100);
                } else if ($angka < 1000) {
                    $terbilang = terbilang_helper($angka / 100) . " Ratus " . terbilang_helper($angka % 100);
                } else if ($angka < 2000) {
                    $terbilang = " Seribu " . terbilang_helper($angka - 1000);
                } else if ($angka < 1000000) {
                    $terbilang = terbilang_helper($angka / 1000) . " Ribu " . terbilang_helper($angka % 1000);
                } else if ($angka < 1000000000) {
                    $terbilang = terbilang_helper($angka / 1000000) . " Juta " . terbilang_helper($angka % 1000000);
                }
                return trim($terbilang);
            }
        }
    @endphp

    {{-- ==================== HALAMAN 1: SURAT TUGAS ==================== --}}
    <div class="doc-st">
        <div class="logo-container">
            <img src="{{ public_path('logobps.jpg') }}" alt="Logo BPS" style="width: 100px; height: auto; margin-bottom: 0px;">
            <div class="instansi-header" style="margin-top: 5px;">{{ $instansi }}<br>{{ $daerah }}</div>
        </div>

        <div class="text-center" style="margin-top: 30px;">
            <h3 class="title">SURAT TUGAS</h3>
            <div class="nomor">NOMOR : {{ $suratTugas->nomor_surat }}</div>
        </div>

        <div style="margin-top: 30px;">
            <p>Yang bertandatangan di bawah ini:</p>
            <p class="text-center" style="font-weight: bold; margin-top:20px; margin-bottom:20px;">{{ ucwords(strtolower($jabatanPenandatangan . ' ' . $satkerName)) }}</p>
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
            {{ $tempatKedudukan }}, {{ $tglSuratStr }}<br>
            {{ $jabatanPenandatangan }} Badan Pusat Statistik<br>
            {{ ucwords(strtolower($daerah)) }}
            <br><br><br><br><br>
            <strong><u>{{ $suratTugas->kepalaPegawai->nama ?? '.......................................' }}</u></strong><br>
            NIP. {{ $suratTugas->kepalaPegawai->nip ?? '.......................................' }}
        </div>
        <div class="clear"></div>
    </div>

    @if($spd)
        <div class="page-break"></div>

        @if(($spdTemplate ?? 'template_1') === 'template_2')
            {{-- ==================== HALAMAN 2: SPD TEMPLATE 2 (LAMPIRAN SURAT TUGAS) ==================== --}}
            <div class="doc-spd-t2">
                <div class="header-lampiran">
                    <div style="font-size: 10.5pt; margin-bottom: 2px;">Lampiran Surat Tugas</div>
                    <table style="border-collapse: collapse; font-size: 10pt;">
                        <tr>
                            <td style="width: 58px; padding: 1px 0;">Nomor</td>
                            <td style="width: 15px; text-align: center; padding: 1px 0;">:</td>
                            <td style="padding: 1px 0;">{{ $suratTugas->nomor_surat }}</td>
                        </tr>
                        <tr>
                            <td style="padding: 1px 0;">Tanggal</td>
                            <td style="text-align: center; padding: 1px 0;">:</td>
                            <td style="padding: 1px 0;">{{ $tglSuratStr }}</td>
                        </tr>
                    </table>
                </div>

                <table class="spd-grid">
                    <tbody>
                        {{-- BARIS 1 --}}
                        <tr>
                            {{-- Kolom 1 Baris 1: KOSONG (sesuai instruksi) --}}
                            <td style="height: 220px; width: 50%; vertical-align: top; padding: 10px 12px; border: 1px solid #000;">
                                {{-- Kolom 1 Baris 1 dibiarkan kosong --}}
                            </td>

                            {{-- Kolom 2 Baris 1: Pindahan isi Kolom 1 Baris 1, TANPA Romawi I, Penandatangan sesuai Kepala/Plh/Plt --}}
                            <td style="height: 220px; width: 50%; vertical-align: top; padding: 10px 12px; border: 1px solid #000;">
                                <table class="inner-info">
                                    <tr>
                                        <td style="width: 125px; vertical-align: top;">
                                            Berangkat dari<br>
                                            <span style="font-size: 8.5pt;">(tempat kedudukan)</span>
                                        </td>
                                        <td style="width: 15px; text-align: center; vertical-align: top;">:</td>
                                        <td style="vertical-align: top;">{{ $tempatKedudukan }}</td>
                                    </tr>
                                    <tr>
                                        <td style="vertical-align: top;">Ke</td>
                                        <td style="width: 15px; text-align: center; vertical-align: top;">:</td>
                                        <td style="vertical-align: top;">{{ $tujuan }}</td>
                                    </tr>
                                    <tr>
                                        <td style="vertical-align: top;">Pada Tanggal</td>
                                        <td style="width: 15px; text-align: center; vertical-align: top;">:</td>
                                        <td style="vertical-align: top;">{{ $tglBerangkatStr }}</td>
                                    </tr>
                                </table>

                                <div style="margin-top: 20px; margin-left: 5px; font-size: 9.5pt; line-height: 1.3;">
                                    {{ $jabatanPenandatangan }} Badan Pusat Statistik<br>
                                    {{ ucwords(strtolower($daerah)) }}
                                    <div style="height: 65px;"></div>
                                    <strong><u>{{ $suratTugas->kepalaPegawai->nama ?? '.......................................' }}</u></strong><br>
                                    NIP. {{ $suratTugas->kepalaPegawai->nip ?? '.......................................' }}
                                </div>
                            </td>
                        </tr>

                        {{-- BARIS 2 --}}
                        <tr>
                            {{-- Kolom 1 Baris 2: Sesuai Gambar 3, TANPA Romawi II --}}
                            <td style="height: 280px; width: 50%; vertical-align: top; padding: 10px 12px; border: 1px solid #000;">
                                <table class="inner-info">
                                    <tr>
                                        <td style="width: 95px; vertical-align: top;">Tiba di</td>
                                        <td style="width: 15px; text-align: center; vertical-align: top;">:</td>
                                        <td style="vertical-align: top;">{{ $tujuan }}</td>
                                    </tr>
                                    <tr>
                                        <td style="vertical-align: top;">Pada Tanggal</td>
                                        <td style="width: 15px; text-align: center; vertical-align: top;">:</td>
                                        <td style="vertical-align: top;">{{ $tglBerangkatStr }}</td>
                                    </tr>
                                </table>
                                <div style="height: 250px;"></div>
                            </td>

                            {{-- Kolom 2 Baris 2: Sesuai Gambar 3 --}}
                            <td style="height: 280px; width: 50%; vertical-align: top; padding: 10px 12px; border: 1px solid #000;">
                                <table class="inner-info">
                                    <tr>
                                        <td style="width: 95px; vertical-align: top;">Berangkat dari</td>
                                        <td style="width: 15px; text-align: center; vertical-align: top;">:</td>
                                        <td style="vertical-align: top;">{{ $tujuan }}</td>
                                    </tr>
                                    <tr>
                                        <td style="vertical-align: top;">Ke</td>
                                        <td style="width: 15px; text-align: center; vertical-align: top;">:</td>
                                        <td style="vertical-align: top;">{{ $tempatKedudukan }}</td>
                                    </tr>
                                    <tr>
                                        <td style="vertical-align: top;">Pada Tanggal</td>
                                        <td style="width: 15px; text-align: center; vertical-align: top;">:</td>
                                        <td style="vertical-align: top;">{{ $tglKembaliStr }}</td>
                                    </tr>
                                </table>
                                <div style="height: 180px;"></div>
                                <div style="text-align: center;">
                                    <span style="color: #777; font-size: 8.5pt; letter-spacing: 0.5px;">............................................................</span>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        @else
            @php
                $lamaHari = $spd->suratTugas->tgl_berangkat->diffInDays($spd->suratTugas->tgl_kembali) + 1;
                $makFull = $spd->suratTugas->anggaran->mak ?? '-';
                $uraianFull = $spd->suratTugas->uraian_pembebanan ?? '-';
            @endphp

            {{-- ==================== HALAMAN 2: SPD LEMBAR 1 ==================== --}}
        <div class="doc-spd">
            <div class="logo-container">
                <img src="{{ public_path('logobps.jpg') }}" alt="Logo BPS" style="width: 80px; height: auto; margin-bottom: 0px;">
                <div class="instansi-header" style="margin-top: 3px;">{{ $instansi }}<br>{{ $daerah }}</div>
            </div>

            <table class="info-header">
                <tr>
                    <td width="60%"></td>
                    <td width="15%">Lembar ke</td>
                    <td width="2%">:</td>
                    <td></td>
                </tr>
                <tr>
                    <td></td>
                    <td>Kode Nomor</td>
                    <td>:</td>
                    <td></td>
                </tr>
                <tr>
                    <td></td>
                    <td>Nomor</td>
                    <td>:</td>
                    <td>{{ $spd->nomor_spd }}</td>
                </tr>
            </table>

            <div class="text-center" style="margin-top: 10px; margin-bottom:5px;">
                <h3 class="title">SURAT PERJALANAN DINAS (SPD)</h3>
            </div>

            <table class="bordered">
                <tr>
                    <td width="3%" class="text-center">1</td>
                    <td width="42%">Pejabat Pembuat Komitmen</td>
                    <td width="55%">{{ $spd->ppkPegawai->nama ?? '-' }}</td>
                </tr>
                <tr>
                    <td class="text-center">2</td>
                    <td>Nama Pegawai yang melaksanakan perjalanan dinas</td>
                    <td><strong>{{ $spd->suratTugas->pegawai->nama }}</strong></td>
                </tr>
                <tr>
                    <td class="text-center">3</td>
                    <td>
                        a. Pangkat dan Golongan<br>
                        b. Jabatan/Instansi<br>
                        c. Tingkat biaya perjalanan dinas
                    </td>
                    <td>
                        a. {{ $spd->suratTugas->pegawai->golongan }} - {{ $spd->suratTugas->pegawai->pangkat }}<br>
                        b. {{ $spd->suratTugas->pegawai->jabatan }}<br>
                        c. C
                    </td>
                </tr>
                <tr>
                    <td class="text-center">4</td>
                    <td>Maksud perjalanan dinas</td>
                    <td>{{ $spd->suratTugas->tugas }}</td>
                </tr>
                <tr>
                    <td class="text-center">5</td>
                    <td>Alat angkutan yang dipergunakan</td>
                    <td>{{ $spd->kendaraan }}</td>
                </tr>
                <tr>
                    <td class="text-center">6</td>
                    <td>
                        a. Tempat berangkat<br>
                        b. Tempat tujuan
                    </td>
                    <td>
                        a. Mataram<br>
                        b. Mataram
                    </td>
                </tr>
                <tr>
                    <td class="text-center">7</td>
                    <td>
                        a. Lama perjalanan dinas<br>
                        b. Tanggal berangkat<br>
                        c. Tanggal harus kembali / tiba di tempat baru *)
                    </td>
                    <td>
                        a. {{ $lamaHari }} Hari<br>
                        b. {{ $tglBerangkatStr }}<br>
                        c. {{ $tglKembaliStr }}
                    </td>
                </tr>
                <tr>
                    <td class="text-center">8</td>
                    <td>
                        <span style="float:left;">Pengikut</span>
                        <span style="float:right;">Nama</span>
                    </td>
                    <td>
                        <span style="float:left;">Tanggal lahir</span>
                        <span style="float:right; margin-right: 30px;">Keterangan</span>
                    </td>
                </tr>
                <tr>
                    <td></td>
                    <td>1</td>
                    <td></td>
                </tr>
                <tr>
                    <td class="text-center">9</td>
                    <td>Pembebanan anggaran<br>
                        <div style="padding-left:15px;">
                            a. Instansi<br><br>
                            b. Mata anggaran
                        </div>
                    </td>
                    <td>
                        <table style="width:100%; border:none; margin:0; padding:0; border-collapse:collapse;">
                            <tr>
                                <td style="border:none; padding:1px; width:20px;">a.</td>
                                <td style="border:none; padding:1px;" colspan="2">{{ $satkerName }}</td>
                            </tr>
                            <tr>
                                <td style="border:none; padding:1px;">b.</td>
                                <td style="border:none; padding:1px; width:80px; vertical-align:top;">Program</td>
                                <td style="border:none; padding:1px; vertical-align:top;">: ({{ $spd->suratTugas->anggaran->program_kode ?? '-' }}) {{ $spd->suratTugas->anggaran->program_uraian ?? '-' }}</td>
                            </tr>
                            <tr>
                                <td style="border:none; padding:1px;"></td>
                                <td style="border:none; padding:1px; vertical-align:top;">Kegiatan</td>
                                <td style="border:none; padding:1px; vertical-align:top;">: ({{ $spd->suratTugas->anggaran->kegiatan_kode ?? '-' }}) {{ $spd->suratTugas->anggaran->kegiatan_uraian ?? '-' }}</td>
                            </tr>
                            <tr>
                                <td style="border:none; padding:1px;"></td>
                                <td style="border:none; padding:1px; vertical-align:top;">Output</td>
                                <td style="border:none; padding:1px; vertical-align:top;">: ({{ $spd->suratTugas->anggaran->output_kode ?? '-' }}) {{ $spd->suratTugas->anggaran->output_uraian ?? '-' }}</td>
                            </tr>
                            <tr>
                                <td style="border:none; padding:1px;"></td>
                                <td style="border:none; padding:1px; vertical-align:top;">Suboutput</td>
                                <td style="border:none; padding:1px; vertical-align:top;">: ({{ $spd->suratTugas->anggaran->suboutput_kode ?? '-' }}) {{ $spd->suratTugas->anggaran->suboutput_uraian ?? '-' }}</td>
                            </tr>
                            <tr>
                                <td style="border:none; padding:1px;"></td>
                                <td style="border:none; padding:1px; vertical-align:top;">Komponen</td>
                                <td style="border:none; padding:1px; vertical-align:top;">: ({{ $spd->suratTugas->anggaran->komponen_kode ?? '-' }}) {{ $spd->suratTugas->anggaran->komponen_uraian ?? '-' }}</td>
                            </tr>
                            <tr>
                                <td style="border:none; padding:1px;"></td>
                                <td style="border:none; padding:1px; vertical-align:top;">Subkomponen</td>
                                <td style="border:none; padding:1px; vertical-align:top;">: ({{ $spd->suratTugas->anggaran->subkomponen_kode ?? '-' }}) {{ $spd->suratTugas->anggaran->subkomponen_uraian ?? '-' }}</td>
                            </tr>
                            <tr>
                                <td style="border:none; padding:1px;"></td>
                                <td style="border:none; padding:1px; vertical-align:top;">Akun</td>
                                <td style="border:none; padding:1px; vertical-align:top;">: ({{ $spd->suratTugas->anggaran->akun_kode ?? '-' }}) {{ $spd->suratTugas->anggaran->akun_uraian ?? '-' }}</td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td class="text-center">10</td>
                    <td>Keterangan lain - lain</td>
                    <td></td>
                </tr>
            </table>

            <table class="signature">
                <tr>
                    <td width="35%">Dikeluarkan di</td>
                    <td width="5%">:</td>
                    <td>Mataram</td>
                </tr>
                <tr>
                    <td>Tanggal</td>
                    <td>:</td>
                    <td>{{ $tglSuratStr }}</td>
                </tr>
                <tr>
                    <td colspan="3" style="padding-top: 10px;">
                        Pejabat Pembuat Komitmen<br>
                        {{ $spd->suratTugas->satker->nama ?? 'Instansi' }}<br><br><br><br><br>
                        <strong><u>{{ $spd->ppkPegawai->nama ?? '.......................................' }}</u></strong><br>
                        NIP. {{ $spd->ppkPegawai->nip ?? '.......................................' }}
                    </td>
                </tr>
            </table>

            <div class="clear"></div>
        </div>

        <div class="page-break"></div>

        {{-- ==================== HALAMAN 3: SPD LEMBAR 2 ==================== --}}
        <div class="doc-spd">
            <div class="hal2-header-right">
                <table style="width:100%; border:none;">
                    <tr>
                        <td width="5%" style="vertical-align:top;">I.</td>
                        <td width="40%" style="vertical-align:top;">Berangkat dari<br>(tempat kedudukan)</td>
                        <td width="5%" style="vertical-align:top;">:</td>
                        <td width="50%" style="vertical-align:top;">Mataram<br>&nbsp;</td>
                    </tr>
                    <tr>
                        <td></td>
                        <td>Ke</td>
                        <td>:</td>
                        <td>Mataram</td>
                    </tr>
                    <tr>
                        <td></td>
                        <td>Pada Tanggal</td>
                        <td>:</td>
                        <td>{{ $tglSuratStr }}</td>
                    </tr>
                </table>
                <div style="margin-top: 15px; margin-left: 15px;">
                    {{ $jabatanPenandatangan }} Badan Pusat Statistik<br>
                    {{ ucwords(strtolower($daerah)) }}<br><br><br><br><br>
                    <strong><u>{{ $spd->suratTugas->kepalaPegawai->nama ?? '.......................................' }}</u></strong><br>
                    NIP. {{ $spd->suratTugas->kepalaPegawai->nip ?? '.......................................' }}
                </div>
            </div>
            <div class="clear"></div>

            <table class="hal2-table">
                <tr>
                    <td width="50%">
                        <div style="float: left; width: 8%;">II.</div>
                        <div style="float: left; width: 25%;">Tiba di</div>
                        <div style="float: left; width: 2%;">:</div>
                        <div style="float: left; width: 65%;">Mataram</div>
                        <div style="clear: both;"></div>

                        <div style="float: left; width: 8%;">&nbsp;</div>
                        <div style="float: left; width: 25%;">Pada Tanggal</div>
                        <div style="float: left; width: 2%;">:</div>
                        <div style="float: left; width: 65%;">{{ $tglBerangkatStr }}</div>
                        <div style="clear: both;"></div>
                        <br><br><br><br>
                    </td>
                    <td width="50%">
                        <div style="float: left; width: 35%;">Berangkat dari</div>
                        <div style="float: left; width: 2%;">:</div>
                        <div style="float: left; width: 63%;">Mataram</div>
                        <div style="clear: both;"></div>

                        <div style="float: left; width: 35%;">Ke</div>
                        <div style="float: left; width: 2%;">:</div>
                        <div style="float: left; width: 63%;">Mataram</div>
                        <div style="clear: both;"></div>

                        <div style="float: left; width: 35%;">Pada Tanggal</div>
                        <div style="float: left; width: 2%;">:</div>
                        <div style="float: left; width: 63%;">{{ $tglBerangkatStr }}</div>
                        <div style="clear: both;"></div>
                        <br><br>
                    </td>
                </tr>
                <tr>
                    <td>
                        <div style="float: left; width: 8%;">III.</div>
                        <div style="float: left; width: 35%;">Tiba kembali di<br>(Tempat kedudukan)</div>
                        <div style="float: left; width: 2%;">:<br>&nbsp;</div>
                        <div style="float: left; width: 55%;">Mataram<br>&nbsp;</div>
                        <div style="clear: both;"></div>

                        <div style="float: left; width: 8%;">&nbsp;</div>
                        <div style="float: left; width: 35%;">Pada tanggal</div>
                        <div style="float: left; width: 2%;">:</div>
                        <div style="float: left; width: 55%;">{{ $tglKembaliStr }}</div>
                        <div style="clear: both;"></div>

                        <br>
                        <div style="margin-left:10%;">
                            Pejabat Pembuat Komitmen
                            <br><br><br><br><br><br><br><br>
                            <strong><u>{{ $spd->ppkPegawai->nama ?? '.......................................' }}</u></strong><br>
                            NIP. {{ $spd->ppkPegawai->nip ?? '.......................................' }}
                        </div>
                    </td>
                    <td>
                        Telah diperiksa dengan keterangan bahwa Perjalanan tersebut atas perintahnya dan semata - mata untuk kepentingan jabatan dalam waktu yang sesingkat - singkatnya.<br><br>
                        Pejabat Pembuat Komitmen
                        <br><br><br><br><br><br><br><br>
                        <strong><u>{{ $spd->ppkPegawai->nama ?? '.......................................' }}</u></strong><br>
                        NIP. {{ $spd->ppkPegawai->nip ?? '.......................................' }}
                    </td>
                </tr>
                <tr class="note-row">
                    <td colspan="2" style="padding: 3px 8px;">
                        IV. CATATAN LAIN - LAIN :
                    </td>
                </tr>
                <tr class="attention-row">
                    <td colspan="2" style="padding: 3px 8px;">
                        <table style="width:100%; border:none;">
                            <tr>
                                <td width="4%" style="border:none; padding:0;">V.</td>
                                <td width="12%" style="border:none; padding:0;">PERHATIAN</td>
                                <td width="2%" style="border:none; padding:0;">:</td>
                                <td width="82%" style="border:none; padding:0;">PPK yang menerbitkan SPD, Pegawai yang melakukan perjalanan dinas, para pejabat yang mengesahkan tanggal berangkat / tiba, serta bendahara pengeluaran bertanggung jawab berdasarkan peraturan - peraturan keuangan Negara apabila negara menderita rugi akibat kesalahan, kelalaian dan kealpaannya.</td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
        </div>
        @endif

        <div class="page-break"></div>

        @php
            $nominal = $spd->nilai_dpr ?? 100000;
            $terbilangStr = terbilang_helper($nominal) . " Rupiah";
        @endphp

        {{-- ==================== HALAMAN 4: DPR ==================== --}}
        <div class="doc-dpr">
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
            <div class="clear"></div>
        </div>

        <div class="page-break"></div>

        {{-- ==================== HALAMAN 5: SURAT PERNYATAAN ==================== --}}
        <div class="doc-pernyataan">
            <div class="text-center title">SURAT PERNYATAAN</div>

            <p style="margin-bottom: 15px;">Yang bertanda tangan di bawah ini :</p>

            <table class="content-table">
                <tr><td class="w-25">Nama</td><td class="w-2">:</td><td class="w-73">{{ $spd->suratTugas->pegawai->nama }}</td></tr>
                <tr><td>NIP</td><td>:</td><td>{{ $spd->suratTugas->pegawai->nip }}</td></tr>
                <tr><td>Pangkat/Golongan</td><td>:</td><td>{{ $spd->suratTugas->pegawai->golongan }} - {{ $spd->suratTugas->pegawai->pangkat }}</td></tr>
                <tr><td>Jabatan</td><td>:</td><td>{{ $spd->suratTugas->pegawai->jabatan }}</td></tr>
                <tr><td>Unit Kerja</td><td>:</td><td>{{ $spd->suratTugas->satker->nama ?? 'Instansi' }}</td></tr>
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
            <div class="clear"></div>
        </div>
    @endif
</body>
</html>
