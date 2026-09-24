<!DOCTYPE html>
<html>
<head>
    <title>Surat Perjalanan Dinas (SPD)</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 10pt; line-height: 1.15; margin: 0; padding: 0 10px; }
        .text-center { text-align: center; }
        .instansi-header { font-weight: bold; font-size: 12pt; font-style: italic; margin-top: 0; margin-bottom: 0; line-height: 1.1; }
        .logo-container { width: 100%; text-align: center; margin-bottom: 0px; }
        table.info-header { width: 100%; margin-top: 5px; font-size: 10pt; }
        table.info-header td { padding: 0px 2px; }
        table.bordered { width: 100%; border-collapse: collapse; margin-top: 3px; font-size: 9.5pt; }
        table.bordered th, table.bordered td { border: 1px solid #000; padding: 2px 4px; vertical-align: top; }
        .page-break { page-break-after: always; }
        .signature { margin-top: 10px; width: 300px; float: right;}
        .signature td { padding: 1px; }
        .clear { clear: both; }
        .title { font-weight: bold; text-decoration: underline; margin-bottom: 0; font-size: 11pt; }

        /* Hal 2 styles */
        table.hal2-table { width: 100%; border-collapse: collapse; margin-top: 5px; font-size: 9.5pt; }
        table.hal2-table td { border: 1px solid #000; padding: 4px; vertical-align: top; height: 250px; }
        .hal2-header-right { float: right; width: 48%; margin-bottom: 2px; font-size: 9.5pt; margin-top: 5px;}
        .note-row td { height: auto !important; }
        .attention-row td { height: auto !important; text-align: justify; }
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

        $lamaHari = $spd->suratTugas->tgl_berangkat->diffInDays($spd->suratTugas->tgl_kembali) + 1;

        // Helper to extract POK components safely if string format allows it
        $makFull = $spd->suratTugas->anggaran->mak ?? '-';
        // For visual separation, just using static text structure combined with the single MAK string
        $uraianFull = $spd->suratTugas->uraian_pembebanan ?? '-';
    @endphp

    <div class="logo-container">
        <img src="{{ public_path('logobps.jpg') }}" alt="Logo BPS" style="width: 80px; height: auto; margin-bottom: 0px;">
        <div class="instansi-header" style="margin-top: 3px;">BADAN PUSAT STATISTIK<br>KOTA MATARAM</div>
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
            <td>{{ $spd->suratTugas->tujuan }}/{{ $spd->suratTugas->tugas }}</td>
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
                        <td style="border:none; padding:1px;" colspan="2">BADAN PUSAT STATISTIK KOTA MATARAM</td>
                    </tr>
                    <tr>
                        <td style="border:none; padding:1px;">b.</td>
                        <td style="border:none; padding:1px; width:80px;">Program</td>
                        <td style="border:none; padding:1px;">: (054.01.GG) Program Penyediaan dan Pelayanan Informasi Statistik</td>
                    </tr>
                    <tr>
                        <td style="border:none; padding:1px;"></td>
                        <td style="border:none; padding:1px;">Kegiatan</td>
                        <td style="border:none; padding:1px;">: (2902) Penyediaan dan Pengembangan Statistik Distribusi</td>
                    </tr>
                    <tr>
                        <td style="border:none; padding:1px;"></td>
                        <td style="border:none; padding:1px;">Output</td>
                        <td style="border:none; padding:1px;">: (FAN) Pemenuhan Prioritas Direktif Presiden</td>
                    </tr>
                    <tr>
                        <td style="border:none; padding:1px;"></td>
                        <td style="border:none; padding:1px;">Suboutput</td>
                        <td style="border:none; padding:1px;">: (ZZ1) Pemenuhan Prioritas Direktif Presiden</td>
                    </tr>
                    <tr>
                        <td style="border:none; padding:1px;"></td>
                        <td style="border:none; padding:1px;">Komponen</td>
                        <td style="border:none; padding:1px;">: (051) SENSUS EKONOMI 2026</td>
                    </tr>
                    <tr>
                        <td style="border:none; padding:1px;"></td>
                        <td style="border:none; padding:1px;">Subkomponen</td>
                        <td style="border:none; padding:1px;">: (A) TANPA SUB KOMPONEN</td>
                    </tr>
                    <tr>
                        <td style="border:none; padding:1px;"></td>
                        <td style="border:none; padding:1px;">Akun</td>
                        <td style="border:none; padding:1px;">: (524113) Belanja Perjalanan Dinas Dalam Kota</td>
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
                BPS Kota Mataram<br><br><br><br><br>
                <strong><u>{{ $spd->ppkPegawai->nama ?? '.......................................' }}</u></strong><br>
                NIP. {{ $spd->ppkPegawai->nip ?? '.......................................' }}
            </td>
        </tr>
    </table>

    <div class="clear"></div>

    <div class="page-break"></div>

    <!-- Lembar 2 -->
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
            Kepala Badan Pusat Statistik<br>
            Kota Mataram<br><br><br><br><br>
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
</body>
</html>
