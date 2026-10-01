<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Translok.ai</title>
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <!-- Scripts -->
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="font-sans antialiased text-gray-900 bg-gray-50 flex flex-col min-h-screen">
    
    <header class="bg-white shadow">
        <div class="max-w-7xl mx-auto px-4 py-4 sm:px-6 lg:px-8 flex justify-between items-center">
            <div class="flex items-center">
                <img src="{{ asset('images/translok_logo.jpg') }}" alt="Translok.ai Logo" class="h-12 w-auto object-contain mr-3 rounded-full border border-gray-200">
                <span class="font-bold text-2xl text-blue-700 tracking-tight">Translok<span class="text-orange-500">.ai</span></span>
            </div>
            
            @if (Route::has('login'))
                <nav class="-mx-3 flex flex-1 justify-end">
                    @auth
                        <a href="{{ url('/dashboard') }}" class="rounded-md px-3 py-2 text-black ring-1 ring-transparent transition hover:text-black/70 focus:outline-none focus-visible:ring-[#FF2D20] font-bold">
                            Dashboard
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="rounded-md px-4 py-2 bg-blue-600 text-white font-semibold shadow hover:bg-blue-700 focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-600 transition">
                            Log in
                        </a>
                    @endauth
                </nav>
            @endif
        </div>
    </header>

    <main class="flex-grow flex flex-col items-center justify-center p-6 sm:p-12">
        <div class="max-w-4xl mx-auto bg-white rounded-xl shadow-xl overflow-hidden md:flex">
            <div class="md:shrink-0 bg-blue-50 p-8 flex items-center justify-center md:w-80 border-r border-gray-100">
                <img src="{{ asset('images/translok_logo.jpg') }}" alt="Translok.ai Logo" class="h-48 w-48 object-cover rounded-full shadow-lg border-4 border-white">
            </div>
            <div class="p-8 md:p-12">
                <div class="uppercase tracking-wide text-sm text-green-600 font-bold mb-1">Selamat Datang di Translok.ai</div>
                <h1 class="block mt-1 text-3xl leading-tight font-extrabold text-gray-900 mb-4">Otomasi Laporan Perjalanan Dinas, Ditenagai AI.</h1>
                <p class="mt-2 text-gray-600 text-lg mb-6">
                    Tinggalkan cara manual menyusun SPPD dan laporan translok yang menyita waktu. Translok.ai hadir sebagai asisten cerdas yang mengambil alih kerumitan administrasi perjalanan dinas Anda dari awal hingga akhir.
                </p>
                
                <div class="space-y-4">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-6 w-6 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-gray-700"><strong class="text-gray-900">Fokus pada Tugas, Bukan Format:</strong> AI cerdas kami akan merangkai hasil kunjungan Anda menjadi paragraf laporan formal secara otomatis.</p>
                        </div>
                    </div>
                    
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-6 w-6 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-gray-700"><strong class="text-gray-900">Satu Klik, Semua Dokumen Siap:</strong> Hasilkan Surat Tugas, SPD, DPR, dan Laporan Pelaksanaan lengkap dengan grid foto dokumentasi berstandar A4 dalam hitungan detik.</p>
                        </div>
                    </div>
                    
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-6 w-6 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-gray-700"><strong class="text-gray-900">Sinkronisasi Presisi:</strong> Terintegrasi langsung dengan database pegawai dan struktur anggaran (DIPA/MAK) untuk mencegah duplikasi data.</p>
                        </div>
                    </div>
                </div>

                <div class="mt-8">
                    @auth
                        <a href="{{ url('/dashboard') }}" class="inline-flex items-center justify-center px-6 py-3 border border-transparent text-base font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 shadow-sm transition">
                            Masuk ke Dashboard
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="inline-flex items-center justify-center px-6 py-3 border border-transparent text-base font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 shadow-sm transition">
                            Masuk sekarang dan rasakan efisiensi
                        </a>
                    @endauth
                </div>
            </div>
        </div>
        
        <div class="mt-12 max-w-4xl w-full text-center border-t border-gray-200 pt-8">
            <p class="text-sm text-gray-500 italic px-4">
                "Translok.ai adalah platform administrasi perjalanan dinas cerdas yang mengubah parameter lapangan menjadi dokumen pelaporan lengkap berstandar A4 secara instan. Dilengkapi dengan AI Narrative Generator dan otomasi tata letak bukti kegiatan, memastikan pelaporan anggaran DIPA/MAK berjalan presisi, cepat, dan tanpa redundansi data."
            </p>
        </div>
    </main>
    
    <footer class="bg-gray-800 text-white py-6 text-center text-sm">
        <p>&copy; {{ date('Y') }} Translok.ai - Cerdas Mengelola, Cepat Melapor.</p>
    </footer>
</body>
</html>
