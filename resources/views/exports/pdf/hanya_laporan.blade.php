<!DOCTYPE html>
<html>
<head>
    <title>Laporan Perjalanan Dinas</title>
    <style>
        @page {
            margin: 20mm 25mm 20mm 25mm;
        }
        body {
            font-family: Arial, sans-serif;
            font-size: 11pt;
            line-height: 1.3;
            margin: 0;
            padding: 0;
        }
        .text-center { text-align: center; }
        .content { text-align: justify; line-height: 1.4; margin-top: 6px; }
        .content p { margin-top: 0; margin-bottom: 8px; }
        .content table { width: 100%; border-collapse: collapse; margin-bottom: 12px; }
        .content th, .content td { border: 1px solid black; padding: 4px; vertical-align: top; }
        .content table th:first-child, .content table td:first-child { width: 15%; }
        .keep-together {
            page-break-inside: avoid;
        }
        .signature-table {
            width: 100%;
            border: none;
            border-collapse: collapse;
            margin-top: 15px;
            page-break-inside: avoid;
        }
        .page-break { page-break-before: always; }
        .section-title { font-weight: bold; margin-bottom: 2px; margin-top: 6px; }
        .section-table { width: 100%; border-spacing: 0; margin-top: 6px; margin-bottom: 2px; }
        .info-table { width: 100%; margin-left: 5%; margin-bottom: 8px; }
        .info-table td { padding: 1px 0; vertical-align: top; }
        .w-35 { width: 35%; }
        .w-2 { width: 2%; }

        /* ================= DPR & Pernyataan styles ================= */
        .dpr-bordered { width: 100%; border-collapse: collapse; margin-top: 15px; }
        .dpr-bordered th, .dpr-bordered td { border: 1px solid #000; padding: 6px 10px; }
        .dpr-sig-container { width: 100%; margin-top: 30px; }
        .dpr-sig-left { float: left; width: 45%; text-align: center; }
        .dpr-sig-right { float: right; width: 45%; text-align: center; }
        .clear { clear: both; }
        .content-table { width: 100%; margin-bottom: 20px; }
        .content-table td { padding: 4px 0; vertical-align: top; }
        .pernyataan-sig-right { float: right; width: 50%; text-align: center; margin-top: 40px; }
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

        $onlySection = $onlySection ?? request('only');
    @endphp

    @if(empty($onlySection) || $onlySection === 'laporan')
    <div class="text-center" style="font-weight: bold; font-size: 12pt; margin-bottom: 15px;">
        LAPORAN PERJALANAN DINAS
    </div>

    <table class="section-table">
        <tr>
            <td style="width: 5%; vertical-align: top; font-weight: bold;">I.</td>
            <td style="width: 95%; font-weight: bold;">DASAR HUKUM</td>
        </tr>
    </table>
    <table class="info-table">
        <tr>
            <td style="width: 35%;">
                <span style="display:inline-block; width: 15px;">1.</span> Surat Tugas Nomor
            </td>
            <td class="w-2">:</td>
            <td>{{ $laporan->nomor_st }}</td>
        </tr>
        <tr>
            <td>
                <span style="display:inline-block; width: 15px;">2.</span> SPD Nomor
            </td>
            <td class="w-2">:</td>
            <td>{{ $laporan->nomor_spd ?? '-' }}</td>
        </tr>
    </table>

    <table class="section-table">
        <tr>
            <td style="width: 5%; vertical-align: top; font-weight: bold;">II.</td>
            <td style="width: 95%; font-weight: bold;">WAKTU PELAKSANAAN</td>
        </tr>
    </table>
    <table class="info-table">
        <tr>
            <td style="width: 35%;">
                Perjalanan dilaksanakan pada tanggal
            </td>
            <td class="w-2">:</td>
            <td>{{ $tglPerjalananStr }}</td>
        </tr>
    </table>

    <table class="section-table">
        <tr>
            <td style="width: 5%; vertical-align: top; font-weight: bold;">III.</td>
            <td style="width: 95%; font-weight: bold;">LOKASI</td>
        </tr>
    </table>
    <table class="info-table">
        <tr>
            <td style="width: 35%;">
                Lokasi perjalanan adalah
            </td>
            <td class="w-2">:</td>
            <td>{{ $laporan->lokasi_perjalanan }}</td>
        </tr>
    </table>

    <table class="section-table">
        <tr>
            <td style="width: 5%; vertical-align: top; font-weight: bold;">IV.</td>
            <td style="width: 95%; font-weight: bold;">TUJUAN PERJALANAN</td>
        </tr>
    </table>
    <table class="info-table">
        <tr>
            <td style="width: 35%;">
                Tujuan perjalanan adalah
            </td>
            <td class="w-2">:</td>
            <td>{{ $laporan->tujuan_perjalanan }}</td>
        </tr>
    </table>

    <table class="section-table">
        <tr>
            <td style="width: 5%; vertical-align: top; font-weight: bold;">V.</td>
            <td style="width: 95%; font-weight: bold;">HASIL PERJALANAN</td>
        </tr>
    </table>

    @php
        $text = trim(str_replace("\r\n", "\n", $laporan->hasil_perjalanan ?? ''));
        $isTable = (strpos($text, '|---') !== false || strpos($text, '| ---') !== false || strpos($text, '|:---') !== false);
        $mainContent = '';
        $lastContent = '';

        if ($isTable) {
            $mainContent = $text;
            $lastContent = '';
        } else {
            $paragraphs = preg_split('/\n\s*\n/', $text);
            if (count($paragraphs) > 1) {
                $lastP = array_pop($paragraphs);
                $mainContent = implode("\n\n", $paragraphs);
                $lastContent = $lastP;
            } else {
                $mainContent = '';
                $lastContent = $text;
            }
        }
    @endphp

    @if(!empty($mainContent))
        <div class="content">
            {!! \Illuminate\Support\Str::markdown($mainContent) !!}
        </div>
    @endif

    <div class="keep-together">
        @if(!empty($lastContent))
            <div class="content">
                {!! \Illuminate\Support\Str::markdown($lastContent) !!}
            </div>
        @endif

        <table class="signature-table">
            <tr>
                <td style="width: 50%; border: none;"></td>
                <td style="width: 50%; border: none; text-align: center; vertical-align: top;">
                    Mataram, {{ $tglLaporanStr }}<br>
                    Yang melaksanakan tugas,
                    <div style="margin-top: 45pt;">
                        <strong><u>{{ $laporan->nama_pegawai }}</u></strong>
                        @if(!empty(trim($laporan->nip_pegawai ?? '')) && trim($laporan->nip_pegawai) !== '-')
                            <br>NIP. {{ $laporan->nip_pegawai }}
                        @endif
                    </div>
                </td>
            </tr>
        </table>
    </div>

    @if($laporan->dokumentasi->count() > 0)
        <!-- Halaman Dokumentasi -->
        <div class="page-break"></div>
        <div class="text-center" style="font-weight: bold; font-size: 12pt; margin-bottom: 50px;">
            DOKUMENTASI
        </div>
        
        @php
            $dtVal = $laporan->stamp_datetime ? \Carbon\Carbon::parse($laporan->stamp_datetime)->format('d/m/Y H:i') : '-';
        @endphp
        @foreach($laporan->dokumentasi as $doc)
            @php
                $imagePath = public_path($doc->file_path);
                $imageData = '';
                $isWord = $isWord ?? false;
                
                if(file_exists($imagePath)) {
                    $type = pathinfo($imagePath, PATHINFO_EXTENSION);
                    $data = file_get_contents($imagePath);
                    
                    if ($isWord && $laporan->is_stamped && function_exists('imagecreatefromstring')) {
                        $img = @imagecreatefromstring($data);
                        if ($img) {
                            $width = imagesx($img);
                            $height = imagesy($img);
                            if ($width > 1000) {
                                $newWidth = 1000;
                                $newHeight = intval($height * (1000 / $width));
                                $newImg = imagecreatetruecolor($newWidth, $newHeight);
                                imagecopyresampled($newImg, $img, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);
                                imagedestroy($img);
                                $img = $newImg;
                                $width = $newWidth;
                                $height = $newHeight;
                            }
                            
                            $black = imagecolorallocatealpha($img, 0, 0, 0, 50);
                            $white = imagecolorallocate($img, 255, 255, 255);
                            $yellow = imagecolorallocate($img, 255, 255, 0);
                            
                            $lines = [
                                $laporan->tujuan_perjalanan,
                                $dtVal,
                                $laporan->stamp_koordinat ?? '-'
                            ];
                            
                            $fontSize = 5;
                            $fontHeight = imagefontheight($fontSize);
                            $lineSpacing = 5;
                            $boxHeight = (count($lines) * ($fontHeight + $lineSpacing)) + 10;
                            
                            $yOffset = $height - $boxHeight - 10;
                            
                            imagefilledrectangle($img, 10, $yOffset, 400, $height - 10, $black);
                            imagefilledrectangle($img, 10, $yOffset, 15, $height - 10, $yellow);
                            
                            $currentY = $yOffset + 5;
                            foreach ($lines as $line) {
                                imagestring($img, $fontSize, 21, $currentY + 1, $line, imagecolorallocate($img, 0,0,0));
                                imagestring($img, $fontSize, 20, $currentY, $line, $white);
                                $currentY += $fontHeight + $lineSpacing;
                            }
                            
                            ob_start();
                            imagejpeg($img, null, 90);
                            $data = ob_get_clean();
                            imagedestroy($img);
                            $type = 'jpeg';
                            $laporan->is_stamped_baked = true; 
                        }
                    }
                    
                    $imageData = 'data:image/' . $type . ';base64,' . base64_encode($data);
                }
            @endphp
            @if($imageData)
            <div style="text-align: center; margin-bottom: 40px; width: 100%;">
                <table style="margin: 0 auto; border: none; border-collapse: collapse; width: auto;">
                    <tr>
                        <td style="padding: 0; border: none; text-align: left; vertical-align: bottom;">
                            <img src="{{ $imageData }}" alt="Dokumentasi" style="max-width: 100%; max-height: 450px; display: block; margin: 0;">
                            @if($laporan->is_stamped && !isset($laporan->is_stamped_baked))
                                <div style="margin-top: -50px; margin-left: 10px; margin-bottom: 10px;">
                                    <div style="display: inline-block; color: white; font-size: 10px; font-weight: bold; padding: 2px 5px; padding-left: 6px; border-left: 3px solid yellow; background-color: rgba(0,0,0,0.4); line-height: 1.2;">
                                        {{ $laporan->tujuan_perjalanan }}<br>
                                        {{ $dtVal }}<br>
                                        {{ $laporan->stamp_koordinat ?? '-' }}
                                    </div>
                                </div>
                            @endif
                        </td>
                    </tr>
                </table>
                @if($laporan->dokumentasi->count() > 1)
                    <div style="margin-top: 10px; font-style: italic; font-size: 10pt;">
                        Gambar {{ $loop->iteration }}: {{ $doc->keterangan_foto }}
                    </div>
                @endif
            </div>
            @endif
        @endforeach
    @endif
    @endif

    {{-- ==================== HALAMAN DPR (JIKA AKTIF) ==================== --}}
    @if($laporan->ada_dpr && (empty($onlySection) || $onlySection === 'dpr'))
        @php
            $nominal = $laporan->nilai_dpr ?? 0;
            $terbilangStr = terbilang_helper($nominal) . " Rupiah";
        @endphp
        @if(empty($onlySection))
            <div class="page-break"></div>
        @endif

        <div class="text-center" style="font-weight: bold; font-size: 12pt; margin-bottom: 25px;">
            DAFTAR PENGELUARAN RIIL
        </div>

        <p style="margin-bottom: 10px;">Yang bertanda tangan di bawah ini :</p>

        <table class="content-table" style="width:100%; margin-bottom: 10px;">
            <tr><td style="width: 20%;">Nama</td><td style="width: 2%;">:</td><td style="width: 78%;">{{ $laporan->nama_pegawai }}</td></tr>
            <tr><td>NIP</td><td>:</td><td>{{ $laporan->nip_pegawai ?: '-' }}</td></tr>
        </table>

        <p style="margin-bottom: 10px; margin-top: 15px;">Berdasarkan Surat Perintah Perjalanan Dinas (SPPD)</p>

        <table class="content-table" style="width:100%; margin-bottom: 15px;">
            <tr><td style="width: 20%;">Tanggal</td><td style="width: 2%;">:</td><td style="width: 78%;">{{ $tglLaporanStr }}</td></tr>
            <tr><td>Nomor</td><td>:</td><td>{{ $laporan->nomor_spd ?: $laporan->nomor_st }}</td></tr>
        </table>

        <p style="margin-bottom: 15px;">dengan ini kami menyatakan dengan sesungguhnya bahwa :</p>

        <table style="width:100%;">
            <tr>
                <td width="3%" style="vertical-align:top;">1.</td>
                <td width="97%" style="vertical-align:top;">Biaya transport pegawai dan/atau biaya penginapan di bawah ini yang tidak diperoleh bukti-bukti pengeluarannya, meliputi :</td>
            </tr>
        </table>

        <table class="dpr-bordered">
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

        <div class="dpr-sig-container">
            <div class="dpr-sig-left">
                Mengetahui/Menyetujui<br>
                Pejabat Pembuat Komitmen,
                <br><br><br><br><br><br>
                <strong><u>{{ $laporan->ppkPegawai->nama ?? '.......................................' }}</u></strong><br>
                NIP. {{ $laporan->ppkPegawai->nip ?? '.......................................' }}
            </div>
            <div class="dpr-sig-right">
                Mataram, {{ $tglLaporanStr }}<br>
                Pelaksana SPD
                <br><br><br><br><br><br>
                <strong><u>{{ $laporan->nama_pegawai }}</u></strong><br>
                @if(!empty(trim($laporan->nip_pegawai ?? '')) && trim($laporan->nip_pegawai) !== '-')
                    NIP. {{ $laporan->nip_pegawai }}
                @else
                    NIP. .......................................
                @endif
            </div>
        </div>
        <div class="clear"></div>
    @endif

    {{-- ==================== HALAMAN SURAT PERNYATAAN (JIKA AKTIF) ==================== --}}
    @if($laporan->ada_pernyataan && (empty($onlySection) || $onlySection === 'pernyataan'))
        @php
            $dbPegawai = $laporan->pegawai;
            $golonganStr = $dbPegawai && $dbPegawai->golongan ? ($dbPegawai->golongan . ' - ' . $dbPegawai->pangkat) : '-';
            $jabatanStr = $dbPegawai && $dbPegawai->jabatan ? $dbPegawai->jabatan : '-';
            $satkerName = $laporan->satker->nama ?? 'Badan Pusat Statistik';
        @endphp
        @if(empty($onlySection))
            <div class="page-break"></div>
        @endif

        <div class="text-center" style="font-weight: bold; font-size: 12pt; margin-bottom: 25px;">
            SURAT PERNYATAAN
        </div>

        <p style="margin-bottom: 15px;">Yang bertanda tangan di bawah ini :</p>

        <table class="content-table" style="width:100%;">
            <tr><td style="width: 25%;">Nama</td><td style="width: 2%;">:</td><td style="width: 73%;">{{ $laporan->nama_pegawai }}</td></tr>
            <tr><td>NIP</td><td>:</td><td>{{ $laporan->nip_pegawai ?: '-' }}</td></tr>
            <tr><td>Pangkat/Golongan</td><td>:</td><td>{{ $golonganStr }}</td></tr>
            <tr><td>Jabatan</td><td>:</td><td>{{ $jabatanStr }}</td></tr>
            <tr><td>Unit Kerja</td><td>:</td><td>{{ $satkerName }}</td></tr>
        </table>

        <p style="text-align: justify; margin-bottom: 20px;">
            Menerangkan bahwa dalam rangka melaksanakan perjalanan dinas dalam kota untuk melaksanakan tugas kedinasan sesuai surat tugas nomor: {{ $laporan->nomor_st }} pelaksanaan tanggal {{ $tglPerjalananStr }}, saya benar-benar tidak menggunakan kendaraan dinas.
        </p>

        <p style="text-align: justify;">
            Demikian pernyataan ini kami buat dengan sebenar-benarnya untuk dipergunakan sebagaimana mestinya. Apabila terdapat kekeliruan dalam pertanggungjawaban SPD dan mengakibatkan kerugian negara, saya bersedia dituntut sesuai aturan yang berlaku dan mengembalikan biaya transport lokal yang sudah saya terima ke kas negara.
        </p>

        <div class="pernyataan-sig-right">
            Mataram, {{ $tglLaporanStr }}<br>
            Pejabat Negara/Pegawai Negeri<br>
            Yang Melakukan Perjalanan Dinas
            <br><br><br><br><br><br>
            <strong><u>{{ $laporan->nama_pegawai }}</u></strong><br>
            @if(!empty(trim($laporan->nip_pegawai ?? '')) && trim($laporan->nip_pegawai) !== '-')
                NIP. {{ $laporan->nip_pegawai }}
            @else
                NIP. .......................................
            @endif
        </div>
        <div class="clear"></div>
    @endif
</body>
</html>

