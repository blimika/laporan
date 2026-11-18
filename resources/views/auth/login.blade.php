<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Masuk ke Akun - Translok.ai</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:300,400,500,600,700,800&display=swap" rel="stylesheet" />

    <!-- Tailwind CDN & Alpine.js -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
        body {
            font-family: 'Figtree', sans-serif;
        }
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="h-full bg-slate-50 text-slate-800 antialiased selection:bg-blue-600 selection:text-white">

    <div class="min-h-screen w-full flex flex-col lg:flex-row">

        <!-- Left Column: Branding & Hero Showcase (Desktop) -->
        <div class="hidden lg:flex lg:w-5/12 xl:w-1/2 bg-gradient-to-br from-slate-950 via-indigo-950 to-blue-950 text-white p-8 lg:p-12 xl:p-16 flex-col justify-between relative overflow-hidden">
            <!-- Background Ambient Glow & Grid Pattern -->
            <div class="absolute -top-32 -left-32 w-96 h-96 bg-blue-500/15 rounded-full blur-3xl pointer-events-none"></div>
            <div class="absolute -bottom-32 -right-32 w-96 h-96 bg-purple-500/20 rounded-full blur-3xl pointer-events-none"></div>
            <div class="absolute inset-0 bg-[radial-gradient(#38bdf8_1px,transparent_1px)] [background-size:28px_28px] opacity-10 pointer-events-none"></div>

            <!-- Top Brand -->
            <div class="relative z-10 flex items-center gap-3">
                <a href="{{ url('/') }}" class="flex items-center gap-3 group">
                    <img src="{{ asset('images/translok_logo.jpg') }}" alt="Translok.ai Logo" class="h-12 w-12 object-contain rounded-2xl border border-white/20 shadow-lg group-hover:scale-105 transition-transform duration-200">
                    <span class="font-black text-2xl text-white tracking-tight">
                        Translok<span class="text-orange-400">.ai</span>
                    </span>
                </a>
            </div>

            <!-- Middle Value Proposition -->
            <div class="relative z-10 my-auto py-12 space-y-6">
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-blue-500/20 border border-blue-400/30 text-blue-200 text-xs font-semibold">
                    <span class="w-2 h-2 rounded-full bg-blue-400 animate-pulse"></span>
                    <span>Portal Akses Terintegrasi</span>
                </div>

                <h1 class="text-3xl xl:text-4xl font-extrabold text-white tracking-tight leading-tight">
                    Kelola Perjalanan Dinas Lebih Cepat, Akurat &amp; Ditenagai AI.
                </h1>

                <p class="text-sm xl:text-base text-slate-300 leading-relaxed font-light max-w-lg">
                    Sistem otomasi administrasi perjalanan dinas dari penerbitan Surat Tugas, lembar SPD, DPR, hingga generasi narasi laporan kegiatan berstandar resmi A4 secara otomatis.
                </p>

                <!-- 3 Key Features -->
                <div class="space-y-3.5 pt-4">
                    <div class="flex items-start gap-3">
                        <div class="w-8 h-8 rounded-lg bg-blue-500/20 text-blue-300 flex items-center justify-center shrink-0 border border-blue-400/20">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                        </div>
                        <div>
                            <h4 class="text-xs sm:text-sm font-bold text-white">AI Narrative Generator</h4>
                            <p class="text-xs text-slate-400 mt-0.5">Rangkai poin kunjungan lapangan jadi laporan formal dalam detik.</p>
                        </div>
                    </div>

                    <div class="flex items-start gap-3">
                        <div class="w-8 h-8 rounded-lg bg-emerald-500/20 text-emerald-300 flex items-center justify-center shrink-0 border border-emerald-400/20">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        </div>
                        <div>
                            <h4 class="text-xs sm:text-sm font-bold text-white">Paket Berkas Resmi A4</h4>
                            <p class="text-xs text-slate-400 mt-0.5">Surat Tugas, SPD muka-belakang, DPR, dan grid foto siap cetak.</p>
                        </div>
                    </div>

                    <div class="flex items-start gap-3">
                        <div class="w-8 h-8 rounded-lg bg-purple-500/20 text-purple-300 flex items-center justify-center shrink-0 border border-purple-400/20">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                        </div>
                        <div>
                            <h4 class="text-xs sm:text-sm font-bold text-white">Sinkronisasi DIPA &amp; Pegawai</h4>
                            <p class="text-xs text-slate-400 mt-0.5">Database terintegrasi mencegah salah rekap dan duplikasi anggaran.</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Bottom Note -->
            <div class="relative z-10 pt-6 border-t border-white/10 flex items-center justify-between text-xs text-slate-400">
                <span>&copy; {{ date('Y') }} Translok.ai</span>
                <span class="inline-flex items-center gap-1.5 text-emerald-400">
                    <span class="w-2 h-2 rounded-full bg-emerald-400"></span> Sistem Siap Digunakan
                </span>
            </div>
        </div>

        <!-- Right Column: Login Form Container -->
        <div class="grow w-full lg:w-7/12 xl:w-1/2 flex flex-col justify-between p-6 sm:p-10 lg:p-14 xl:p-16 bg-white relative">

            <!-- Top Nav Back Link -->
            <div class="w-full flex items-center justify-between pb-6">
                <a href="{{ url('/') }}" class="inline-flex items-center gap-2 text-xs sm:text-sm font-semibold text-slate-500 hover:text-blue-600 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                    <span>Kembali ke Beranda</span>
                </a>

                <!-- Mobile Only Logo -->
                <div class="flex lg:hidden items-center gap-2">
                    <img src="{{ asset('images/translok_logo.jpg') }}" alt="Translok.ai" class="h-8 w-8 object-contain rounded-lg border border-slate-200">
                    <span class="font-bold text-base text-blue-700">Translok<span class="text-orange-500">.ai</span></span>
                </div>
            </div>

            <!-- Main Form Section -->
            <div class="w-full max-w-md mx-auto my-auto py-4">

                <!-- Heading & Subtitle -->
                <div class="mb-8">
                    <h2 class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight">
                        Masuk ke Akun
                    </h2>
                    <p class="text-sm text-slate-500 mt-2">
                        Silakan masukkan kredensial akun Anda dan pilih tahun anggaran aktif untuk melanjutkan.
                    </p>
                </div>

                <!-- Session Status Notification -->
                @if (session('status'))
                    <div class="mb-6 p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-sm flex items-start gap-3">
                        <svg class="w-5 h-5 text-emerald-600 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <span>{{ session('status') }}</span>
                    </div>
                @endif

                <!-- Validation Errors Alert -->
                @if ($errors->any())
                    <div class="mb-6 p-4 rounded-xl bg-rose-50 border border-rose-200 text-rose-800 text-sm">
                        <div class="flex items-center gap-2 font-bold mb-1">
                            <svg class="w-5 h-5 text-rose-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                            <span>Terjadi kesalahan saat masuk</span>
                        </div>
                        <ul class="list-disc list-inside space-y-0.5 text-xs text-rose-700">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <!-- Login Form -->
                <form method="POST" action="{{ route('login') }}" class="space-y-5" x-data="{ showPassword: false }">
                    @csrf

                    <!-- Username Field -->
                    <div>
                        <label for="username" class="block text-xs sm:text-sm font-bold text-slate-700 mb-1.5">
                            Username
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                            </div>
                            <input
                                id="username"
                                type="text"
                                name="username"
                                value="{{ old('username') }}"
                                required
                                autofocus
                                autocomplete="username"
                                placeholder="Masukkan username Anda"
                                class="w-full pl-10 pr-4 py-3 rounded-xl border {{ $errors->has('username') ? 'border-rose-400 focus:border-rose-500 focus:ring-rose-500/10' : 'border-slate-300 focus:border-blue-600 focus:ring-blue-600/10' }} bg-white text-slate-900 placeholder-slate-400 text-sm font-medium focus:outline-hidden focus:ring-4 transition shadow-xs"
                            >
                        </div>
                        @if ($errors->has('username'))
                            <p class="mt-1.5 text-xs text-rose-600 font-medium">{{ $errors->first('username') }}</p>
                        @endif
                    </div>

                    <!-- Password Field -->
                    <div>
                        <div class="flex items-center justify-between mb-1.5">
                            <label for="password" class="block text-xs sm:text-sm font-bold text-slate-700">
                                Password
                            </label>
                            @if (Route::has('password.request'))
                                <a href="{{ route('password.request') }}" class="text-xs font-semibold text-blue-600 hover:text-blue-700 hover:underline">
                                    Lupa password?
                                </a>
                            @endif
                        </div>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                            </div>
                            <input
                                id="password"
                                :type="showPassword ? 'text' : 'password'"
                                name="password"
                                required
                                autocomplete="current-password"
                                placeholder="Masukkan password Anda"
                                class="w-full pl-10 pr-11 py-3 rounded-xl border {{ $errors->has('password') ? 'border-rose-400 focus:border-rose-500 focus:ring-rose-500/10' : 'border-slate-300 focus:border-blue-600 focus:ring-blue-600/10' }} bg-white text-slate-900 placeholder-slate-400 text-sm font-medium focus:outline-hidden focus:ring-4 transition shadow-xs"
                            >
                            <!-- Show / Hide Password Button -->
                            <button
                                type="button"
                                @click="showPassword = !showPassword"
                                class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-slate-400 hover:text-slate-600 focus:outline-hidden cursor-pointer"
                                aria-label="Toggle password visibility"
                            >
                                <svg x-show="!showPassword" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                <svg x-show="showPassword" x-cloak class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18"/></svg>
                            </button>
                        </div>
                        @if ($errors->has('password'))
                            <p class="mt-1.5 text-xs text-rose-600 font-medium">{{ $errors->first('password') }}</p>
                        @endif
                    </div>

                    <!-- Tahun Anggaran Field -->
                    <div>
                        <div class="flex items-center justify-between mb-1.5">
                            <label for="tahun_id" class="block text-xs sm:text-sm font-bold text-slate-700">
                                Tahun Anggaran
                            </label>
                            <span class="text-[11px] font-semibold text-blue-600 bg-blue-50 px-2 py-0.5 rounded-full border border-blue-100">
                                Sesi Kerja
                            </span>
                        </div>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            </div>
                            <select
                                id="tahun_id"
                                name="tahun_id"
                                required
                                class="w-full pl-10 pr-10 py-3 rounded-xl border {{ $errors->has('tahun_id') ? 'border-rose-400 focus:border-rose-500 focus:ring-rose-500/10' : 'border-slate-300 focus:border-blue-600 focus:ring-blue-600/10' }} bg-white text-slate-900 text-sm font-medium focus:outline-hidden focus:ring-4 transition shadow-xs cursor-pointer appearance-none"
                            >
                                <option value="">-- Pilih Tahun Anggaran --</option>
                                @foreach($tahuns as $tahun)
                                    <option value="{{ $tahun->id }}" {{ (old('tahun_id') == $tahun->id || (!old('tahun_id') && ($tahun->aktif || $tahun->tahun == date('Y')))) ? 'selected' : '' }}>
                                        Tahun Anggaran {{ $tahun->tahun }} {{ $tahun->aktif ? '(Aktif)' : '' }}
                                    </option>
                                @endforeach
                            </select>
                            <div class="absolute inset-y-0 right-0 pr-3.5 flex items-center pointer-events-none text-slate-400">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                            </div>
                        </div>
                        @if ($errors->has('tahun_id'))
                            <p class="mt-1.5 text-xs text-rose-600 font-medium">{{ $errors->first('tahun_id') }}</p>
                        @endif
                    </div>

                    <!-- Remember Me Checkbox -->
                    <div class="flex items-center pt-1">
                        <label for="remember_me" class="inline-flex items-center gap-2 cursor-pointer group">
                            <input
                                id="remember_me"
                                type="checkbox"
                                name="remember"
                                class="w-4 h-4 rounded-md border-slate-300 text-blue-600 focus:ring-blue-500 focus:ring-offset-0 cursor-pointer"
                            >
                            <span class="text-xs sm:text-sm text-slate-600 group-hover:text-slate-900 transition-colors">
                                Ingat sesi login di perangkat ini
                            </span>
                        </label>
                    </div>

                    <!-- Submit Button -->
                    <div class="pt-2">
                        <button
                            type="submit"
                            class="w-full py-3.5 px-5 rounded-xl bg-gradient-to-r from-blue-600 via-blue-700 to-indigo-700 hover:from-blue-700 hover:to-indigo-800 text-white font-bold text-sm sm:text-base shadow-lg shadow-blue-600/25 transition-all duration-200 hover:shadow-blue-600/35 hover:scale-[1.008] active:scale-[0.99] flex items-center justify-center gap-2.5 cursor-pointer"
                        >
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/></svg>
                            <span>Masuk ke Sistem</span>
                        </button>
                    </div>

                </form>

            </div>

            <!-- Footer Security & Support Note -->
            <div class="w-full pt-8 text-center border-t border-slate-100">
                <div class="inline-flex items-center gap-1.5 text-[11px] text-slate-500 font-medium">
                    <svg class="w-3.5 h-3.5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                    <span>Koneksi aman terenkripsi &bull; Hak Akses Terproteksi</span>
                </div>
            </div>

        </div>

    </div>

</body>
</html>