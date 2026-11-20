<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Lampiran Surat Tugas - SPD</title>
    <style>
        @page {
            size: A4 portrait;
            margin: 20mm 15mm 15mm 15mm;
        }
        body {
            font-family: Arial, "Helvetica Neue", Helvetica, sans-serif;
            font-size: 10pt;
            line-height: 1.25;
            color: #000;
            margin: 0;
            padding: 0;
        }

        .header-lampiran {
            margin-bottom: 12px;
        }
        .header-lampiran .title {
            font-size: 10.5pt;
            font-weight: normal;
            margin-bottom: 2px;
        }
        .header-lampiran table {
            border-collapse: collapse;
            font-size: 10pt;
            margin-left: 0;
        }
        .header-lampiran td {
            padding: 1px 0;
            vertical-align: top;
        }

        table.spd-grid {
            width: 100%;
            border-collapse: collapse;
            border: 1px solid #000;
            margin-top: 10px;
        }
        table.spd-grid > tbody > tr > td {
            border: 1px solid #000;
            vertical-align: top;
            padding: 10px 12px;
            width: 50%;
            box-sizing: border-box;
        }

        /* Inner tables for alignment */
        table.inner-info {
            width: 100%;
            border-collapse: collapse;
            font-size: 9.5pt;
        }
        table.inner-info td {
            padding: 1px 0;
            vertical-align: top;
        }

        .colon {
            width: 15px;
            text-align: center;
        }

        .sign-title {
            font-size: 9.5pt;
            line-height: 1.3;
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

        $tglBerangkatStr = $spd->suratTugas->tgl_berangkat->format('d') . ' ' . $months[(int)$spd->suratTugas->tgl_berangkat->format('m')] . ' ' . $spd->suratTugas->tgl_berangkat->format('Y');
        $tglKembaliStr = $spd->suratTugas->tgl_kembali->format('d') . ' ' . $months[(int)$spd->suratTugas->tgl_kembali->format('m')] . ' ' . $spd->suratTugas->tgl_kembali->format('Y');
        $tglSuratStr = $spd->suratTugas->tgl_surat->format('d') . ' ' . $months[(int)$spd->suratTugas->tgl_surat->format('m')] . ' ' . $spd->suratTugas->tgl_surat->format('Y');

        $satkerName = strtoupper($spd->suratTugas->satker->nama ?? 'Instansi');
        $satkerName = str_replace('BPS ', 'BADAN PUSAT STATISTIK ', $satkerName);
        $instansi = 'BADAN PUSAT STATISTIK';
        $daerah = str_replace('BADAN PUSAT STATISTIK ', '', $satkerName);
        $tempatKedudukan = trim(preg_replace('/^(BPS\s+|BADAN\s+PUSAT\s+STATISTIK\s+)?(KOTA\s+|KABUPATEN\s+|KAB\.\s+|PROVINSI\s+)?/i', '', $spd->suratTugas->satker->nama ?? 'Mataram')) ?: 'Mataram';
        $tujuan = $spd->suratTugas->tujuan ?? $tempatKedudukan;

        $kepalaPegawai = $spd->suratTugas->kepalaPegawai ?? null;
        $jabatanPenandatangan = 'Kepala';
        if ($kepalaPegawai) {
            if ($kepalaPegawai->hasTag('plh') || stripos($kepalaPegawai->jabatan ?? '', 'plh') !== false) {
                $jabatanPenandatangan = 'Plh. Kepala';
            } elseif ($kepalaPegawai->hasTag('plt') || stripos($kepalaPegawai->jabatan ?? '', 'plt') !== false) {
                $jabatanPenandatangan = 'Plt. Kepala';
            }
        }
    @endphp

    {{-- Header sesuai Gambar 1 (tanpa nomor SPD) --}}
    <div class="header-lampiran">
        <div class="title">Lampiran Surat Tugas</div>
        <table>
            <tr>
                <td style="width: 58px;">Nomor</td>
                <td class="colon">:</td>
                <td>{{ $spd->suratTugas->nomor_surat }}</td>
            </tr>
            <tr>
                <td>Tanggal</td>
                <td class="colon">:</td>
                <td>{{ $tglSuratStr }}</td>
            </tr>
        </table>
    </div>

    {{-- Grid 2 Kolom x 2 Baris --}}
    <table class="spd-grid">
        <tbody>
            {{-- BARIS 1 --}}
            <tr>
                {{-- Kolom 1 Baris 1: KOSONG (sesuai instruksi) --}}
                <td style="height: 220px;">
                    {{-- Kolom 1 Baris 1 dibiarkan kosong --}}
                </td>

                {{-- Kolom 2 Baris 1: Pindahan isi Kolom 1 Baris 1, TANPA Romawi I, Penandatangan sesuai Kepala/Plh/Plt --}}
                <td style="height: 220px;">
                    <table class="inner-info">
                        <tr>
                            <td style="width: 125px; vertical-align: top;">
                                Berangkat dari<br>
                                <span style="font-size: 8.5pt;">(tempat kedudukan)</span>
                            </td>
                            <td class="colon">:</td>
                            <td style="vertical-align: top;">{{ $tempatKedudukan }}</td>
                        </tr>
                        <tr>
                            <td style="vertical-align: top;">Ke</td>
                            <td class="colon">:</td>
                            <td style="vertical-align: top;">{{ $tujuan }}</td>
                        </tr>
                        <tr>
                            <td style="vertical-align: top;">Pada Tanggal</td>
                            <td class="colon">:</td>
                            <td style="vertical-align: top;">{{ $tglBerangkatStr }}</td>
                        </tr>
                    </table>

                    <div style="margin-top: 15px; margin-left: 5px;" class="sign-title">
                        {{ $jabatanPenandatangan }} Badan Pusat Statistik<br>
                        {{ ucwords(strtolower($daerah)) }}
                        <div style="height: 80px;"></div>
                        <strong><u>{{ $spd->suratTugas->kepalaPegawai->nama ?? '.......................................' }}</u></strong><br>
                        NIP. {{ $spd->suratTugas->kepalaPegawai->nip ?? '.......................................' }}
                    </div>
                </td>
            </tr>

            {{-- BARIS 2 --}}
            <tr>
                {{-- Kolom 1 Baris 2: Sesuai Gambar 3, TANPA Romawi II --}}
                <td style="height: 280px;">
                    <table class="inner-info">
                        <tr>
                            <td style="width: 95px; vertical-align: top;">Tiba di</td>
                            <td class="colon">:</td>
                            <td style="vertical-align: top;">{{ $tujuan }}</td>
                        </tr>
                        <tr>
                            <td style="vertical-align: top;">Pada Tanggal</td>
                            <td class="colon">:</td>
                            <td style="vertical-align: top;">{{ $tglBerangkatStr }}</td>
                        </tr>
                    </table>

                    {{-- Ruang kosong untuk stempel & tanda tangan pengesahan kedatangan di lokasi tugas --}}
                    <div style="height: 250px;"></div>
                </td>

                {{-- Kolom 2 Baris 2: Sesuai Gambar 3 --}}
                <td style="height: 280px;">
                    <table class="inner-info">
                        <tr>
                            <td style="width: 95px; vertical-align: top;">Berangkat dari</td>
                            <td class="colon">:</td>
                            <td style="vertical-align: top;">{{ $tujuan }}</td>
                        </tr>
                        <tr>
                            <td style="vertical-align: top;">Ke</td>
                            <td class="colon">:</td>
                            <td style="vertical-align: top;">{{ $tempatKedudukan }}</td>
                        </tr>
                        <tr>
                            <td style="vertical-align: top;">Pada Tanggal</td>
                            <td class="colon">:</td>
                            <td style="vertical-align: top;">{{ $tglKembaliStr }}</td>
                        </tr>
                    </table>

                    {{-- Ruang kosong untuk stempel & tanda tangan pengesahan keberangkatan di lokasi tugas --}}
                    <div style="height: 190px;"></div>
                    <div style="text-align: center;">
                        <span style="color: #777; font-size: 8.5pt; letter-spacing: 0.5px;">............................................................</span>
                    </div>
                </td>
            </tr>
        </tbody>
    </table>
</body>
</html>
