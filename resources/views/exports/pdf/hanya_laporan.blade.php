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
            <td>{{ $laporan->nomor_st }}</td>
        </tr>
        <tr>
            <td>
                <span style="display:inline-block; width: 15px;">2.</span> SPD Nomor
            </td>
            <td>:</td>
            <td>{{ $laporan->nomor_spd ?? '-' }}</td>
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
            <td>{{ $laporan->tujuan_perjalanan }}</td>
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
            <strong><u>{{ $laporan->nama_pegawai }}</u></strong><br>
            NIP. {{ $laporan->nip_pegawai }}
        </div>
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
</body>
</html>

