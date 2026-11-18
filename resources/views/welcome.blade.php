<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Translok.ai - Otomasi Laporan & Administrasi Perjalanan Dinas</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:300,400,500,600,700,800,900&display=swap" rel="stylesheet" />

    <!-- Tailwind CDN & Alpine.js -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
        body {
            font-family: 'Figtree', sans-serif;
        }
    </style>
</head>
<body class="antialiased text-slate-800 bg-slate-50 flex flex-col min-h-screen selection:bg-blue-600 selection:text-white" x-data="{ mobileMenuOpen: false }">

    <!-- Top Navigation Bar (Full Width) -->
    <header class="w-full bg-white/95 backdrop-blur-md sticky top-0 z-50 border-b border-slate-200/80 shadow-xs">
        <div class="w-full px-4 sm:px-8 lg:px-12 xl:px-16 py-3.5 flex items-center justify-between">
            <!-- Brand Logo -->
            <a href="{{ url('/') }}" class="flex items-center gap-3 group">
                <img src="{{ asset('images/translok_logo.jpg') }}" alt="Translok.ai Logo" class="h-11 w-11 object-contain rounded-xl border border-blue-100 shadow-xs group-hover:scale-105 transition-transform duration-200">
                <span class="font-black text-2xl text-blue-700 tracking-tight">
                    Translok<span class="text-orange-500">.ai</span>
                </span>
            </a>

            <!-- Desktop Nav Links -->
            <nav class="hidden md:flex items-center gap-8 text-sm font-semibold text-slate-600">
                <a href="#fitur" class="hover:text-blue-600 transition-colors">Fitur Unggulan</a>
                <a href="#alur-kerja" class="hover:text-blue-600 transition-colors">Alur Kerja</a>
                <a href="#dokumen" class="hover:text-blue-600 transition-colors">Format Dokumen</a>
                <a href="#tentang" class="hover:text-blue-600 transition-colors">Tentang Platform</a>
            </nav>

            <!-- Auth Buttons -->
            <div class="hidden sm:flex items-center gap-3">
                @if (Route::has('login'))
                    @auth
                        <a href="{{ url('/dashboard') }}" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white font-semibold text-sm shadow-md shadow-blue-500/20 transition-all duration-200 hover:shadow-lg hover:shadow-blue-500/30">
                            <span>Buka Dashboard</span>
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white font-semibold text-sm shadow-md shadow-blue-500/20 transition-all duration-200 hover:shadow-lg hover:shadow-blue-500/30">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/></svg>
                            <span>Masuk ke Sistem</span>
                        </a>
                    @endauth
                @endif
            </div>

            <!-- Mobile Menu Toggle Button -->
            <button type="button" @click="mobileMenuOpen = !mobileMenuOpen" class="md:hidden p-2 rounded-lg text-slate-600 hover:text-slate-900 hover:bg-slate-100 focus:outline-hidden" aria-label="Toggle menu">
                <svg x-show="!mobileMenuOpen" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                <svg x-show="mobileMenuOpen" x-cloak class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>

        <!-- Mobile Dropdown Menu -->
        <div x-show="mobileMenuOpen" x-cloak x-transition class="md:hidden w-full border-t border-slate-200 bg-white px-6 py-4 space-y-3">
            <a href="#fitur" @click="mobileMenuOpen = false" class="block py-2 text-slate-700 font-medium hover:text-blue-600">Fitur Unggulan</a>
            <a href="#alur-kerja" @click="mobileMenuOpen = false" class="block py-2 text-slate-700 font-medium hover:text-blue-600">Alur Kerja</a>
            <a href="#dokumen" @click="mobileMenuOpen = false" class="block py-2 text-slate-700 font-medium hover:text-blue-600">Format Dokumen</a>
            <a href="#tentang" @click="mobileMenuOpen = false" class="block py-2 text-slate-700 font-medium hover:text-blue-600">Tentang Platform</a>
            <div class="pt-3 border-t border-slate-100">
                @auth
                    <a href="{{ url('/dashboard') }}" class="block w-full text-center px-4 py-2.5 rounded-xl bg-blue-600 text-white font-semibold shadow-sm">
                        Buka Dashboard
                    </a>
                @else
                    <a href="{{ route('login') }}" class="block w-full text-center px-4 py-2.5 rounded-xl bg-blue-600 text-white font-semibold shadow-sm">
                        Masuk ke Sistem
                    </a>
                @endauth
            </div>
        </div>
    </header>

    <!-- Main Content Area (Full Width) -->
    <main class="grow w-full">

        <!-- Hero Section (Full Width) -->
        <section class="w-full relative overflow-hidden bg-gradient-to-b from-blue-50/70 via-indigo-50/30 to-white py-16 sm:py-20 lg:py-24 border-b border-slate-200/80">
            <!-- Background Decorative Glows -->
            <div class="absolute -top-32 -left-32 w-96 h-96 bg-blue-400/10 rounded-full blur-3xl pointer-events-none"></div>
            <div class="absolute top-1/2 -right-32 w-96 h-96 bg-purple-400/10 rounded-full blur-3xl pointer-events-none"></div>

            <div class="w-full px-4 sm:px-8 lg:px-12 xl:px-16">
                <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 lg:gap-14 items-center">

                    <!-- Hero Left: Typography & CTAs -->
                    <div class="lg:col-span-7 space-y-6">
                        <!-- Pill Badge -->
                        <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-blue-100/80 border border-blue-200 text-blue-900 text-xs sm:text-sm font-bold shadow-xs">
                            <span class="w-2.5 h-2.5 rounded-full bg-blue-600 animate-pulse"></span>
                            <span>✨ Asisten Cerdas Pertanggungjawaban Perjalanan Dinas</span>
                        </div>

                        <!-- Main Headline -->
                        <h1 class="text-3xl sm:text-4xl md:text-5xl xl:text-6xl font-black text-slate-900 tracking-tight leading-[1.12]">
                            Otomasi Laporan Dinas & SPPD, <br class="hidden sm:inline">
                            <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-600 via-indigo-600 to-purple-600">Ditenagai AI</span> Secara Presisi.
                        </h1>

                        <!-- Subtitle -->
                        <p class="text-base sm:text-lg md:text-xl text-slate-600 leading-relaxed font-normal max-w-3xl">
                            Tinggalkan cara manual menyusun Surat Tugas, SPD, DPR, dan lembar laporan perjalanan dinas yang menyita waktu. <strong class="text-slate-800 font-semibold">Translok.ai</strong> merangkai administrasi lapangan menjadi dokumen pelaporan lengkap berstandar A4 siap ekspor secara instan.
                        </p>

                        <!-- CTA Action Buttons -->
                        <div class="flex flex-wrap items-center gap-4 pt-2">
                            @auth
                                <a href="{{ url('/dashboard') }}" class="inline-flex items-center justify-center gap-2.5 px-7 py-3.5 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-bold text-base shadow-lg shadow-blue-600/25 transition-all hover:scale-[1.02] active:scale-[0.98]">
                                    <span>Buka Dashboard Utama</span>
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                                </a>
                            @else
                                <a href="{{ route('login') }}" class="inline-flex items-center justify-center gap-2.5 px-7 py-3.5 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-bold text-base shadow-lg shadow-blue-600/25 transition-all hover:scale-[1.02] active:scale-[0.98]">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/></svg>
                                    <span>Masuk ke Aplikasi Sekarang</span>
                                </a>
                            @endauth

                            <a href="#fitur" class="inline-flex items-center justify-center gap-2 px-6 py-3.5 rounded-xl bg-white hover:bg-slate-100 text-slate-700 font-semibold text-base border border-slate-300 shadow-xs transition-colors">
                                <span>Pelajari Fitur</span>
                                <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                            </a>
                        </div>

                        <!-- Key Pillars Checklist -->
                        <div class="pt-4 grid grid-cols-1 sm:grid-cols-2 gap-3 border-t border-slate-200/80">
                            <div class="flex items-center gap-2.5 text-sm font-medium text-slate-700">
                                <span class="w-5 h-5 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center shrink-0">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                                </span>
                                <span>Generator Narasi Laporan Otomatis (AI)</span>
                            </div>
                            <div class="flex items-center gap-2.5 text-sm font-medium text-slate-700">
                                <span class="w-5 h-5 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center shrink-0">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                                </span>
                                <span>Paket Lengkap: Surat Tugas, SPD, DPR</span>
                            </div>
                            <div class="flex items-center gap-2.5 text-sm font-medium text-slate-700">
                                <span class="w-5 h-5 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center shrink-0">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                                </span>
                                <span>Grid Foto Dokumentasi Standar A4</span>
                            </div>
                            <div class="flex items-center gap-2.5 text-sm font-medium text-slate-700">
                                <span class="w-5 h-5 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center shrink-0">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                                </span>
                                <span>Sinkronisasi DIPA / MAK & Ekspor PDF / Word</span>
                            </div>
                        </div>
                    </div>

                    <!-- Hero Right: Interactive Visual Showcase Card -->
                    <div class="lg:col-span-5">
                        <div class="relative mx-auto w-full max-w-xl">
                            <!-- Background Glow Effect behind Showcase -->
                            <div class="absolute -inset-1.5 bg-gradient-to-r from-blue-600 via-indigo-600 to-purple-600 rounded-3xl blur-xl opacity-25"></div>

                            <!-- Showcase Main Container -->
                            <div class="relative bg-white rounded-2xl shadow-xl border border-slate-200/90 overflow-hidden">
                                <!-- Mockup Top Header -->
                                <div class="bg-slate-900 px-5 py-3.5 flex items-center justify-between border-b border-slate-800">
                                    <div class="flex items-center gap-2">
                                        <div class="w-3 h-3 rounded-full bg-rose-500"></div>
                                        <div class="w-3 h-3 rounded-full bg-amber-500"></div>
                                        <div class="w-3 h-3 rounded-full bg-emerald-500"></div>
                                        <span class="ml-2 text-xs font-mono text-slate-400">translok.ai/suite</span>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <span class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded-full text-[11px] font-medium bg-emerald-500/20 text-emerald-400 border border-emerald-500/30">
                                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-400"></span>
                                            Ready v2.0
                                        </span>
                                    </div>
                                </div>

                                <!-- Mockup Body -->
                                <div class="p-5 sm:p-6 space-y-4">
                                    <!-- Brand Identification -->
                                    <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                                        <div class="flex items-center gap-3">
                                            <img src="{{ asset('images/translok_logo.jpg') }}" alt="Translok.ai" class="h-10 w-10 object-contain rounded-xl border border-slate-200 shadow-xs">
                                            <div>
                                                <h4 class="text-sm font-bold text-slate-900">Translok Hub Pelaporan</h4>
                                                <p class="text-xs text-slate-500">Tahun Anggaran {{ date('Y') }} &bull; DIPA / MAK</p>
                                            </div>
                                        </div>
                                        <span class="px-2.5 py-1 text-xs font-semibold rounded-md bg-blue-50 text-blue-700 border border-blue-200">
                                            Auto-Engine
                                        </span>
                                    </div>

                                    <!-- Document Pipeline Items -->
                                    <div class="space-y-2.5">
                                        <!-- Item 1: Surat Tugas & SPD -->
                                        <div class="flex items-center justify-between p-3 rounded-xl bg-slate-50 border border-slate-100 hover:border-slate-200 transition">
                                            <div class="flex items-center gap-3">
                                                <div class="w-8 h-8 rounded-lg bg-amber-100 text-amber-700 flex items-center justify-center shrink-0">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                                </div>
                                                <div>
                                                    <p class="text-xs font-bold text-slate-800">Surat Tugas &amp; Lembar SPD</p>
                                                    <p class="text-[11px] text-slate-500">Nomor otomatis &amp; pelaksana terhubung</p>
                                                </div>
                                            </div>
                                            <span class="text-[11px] font-semibold text-emerald-600 bg-emerald-50 px-2 py-0.5 rounded-full border border-emerald-200 flex items-center gap-1">
                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                                Terbit
                                            </span>
                                        </div>

                                        <!-- Item 2: AI Report Generator -->
                                        <div class="flex items-center justify-between p-3 rounded-xl bg-purple-50/60 border border-purple-100 hover:border-purple-200 transition">
                                            <div class="flex items-center gap-3">
                                                <div class="w-8 h-8 rounded-lg bg-purple-100 text-purple-700 flex items-center justify-center shrink-0">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                                                </div>
                                                <div>
                                                    <p class="text-xs font-bold text-slate-800">Narasi Laporan AI</p>
                                                    <p class="text-[11px] text-purple-700">Format baku bahasa Indonesia dinas</p>
                                                </div>
                                            </div>
                                            <span class="text-[11px] font-semibold text-purple-700 bg-purple-100 px-2 py-0.5 rounded-full border border-purple-200">
                                                ✨ AI Generated
                                            </span>
                                        </div>

                                        <!-- Item 3: DPR & Rincian Riil -->
                                        <div class="flex items-center justify-between p-3 rounded-xl bg-slate-50 border border-slate-100 hover:border-slate-200 transition">
                                            <div class="flex items-center gap-3">
                                                <div class="w-8 h-8 rounded-lg bg-emerald-100 text-emerald-700 flex items-center justify-center shrink-0">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                                                </div>
                                                <div>
                                                    <p class="text-xs font-bold text-slate-800">Daftar Pengeluaran Riil (DPR)</p>
                                                    <p class="text-[11px] text-slate-500">Kalkulasi uang harian &amp; transport</p>
                                                </div>
                                            </div>
                                            <span class="text-[11px] font-semibold text-slate-700 bg-slate-200/80 px-2 py-0.5 rounded-full">
                                                Presisi
                                            </span>
                                        </div>

                                        <!-- Item 4: Grid Dokumentasi Foto -->
                                        <div class="flex items-center justify-between p-3 rounded-xl bg-slate-50 border border-slate-100 hover:border-slate-200 transition">
                                            <div class="flex items-center gap-3">
                                                <div class="w-8 h-8 rounded-lg bg-blue-100 text-blue-700 flex items-center justify-center shrink-0">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                                </div>
                                                <div>
                                                    <p class="text-xs font-bold text-slate-800">Grid Foto Dokumentasi</p>
                                                    <p class="text-[11px] text-slate-500">Tata letak standar A4 otomatis</p>
                                                </div>
                                            </div>
                                            <span class="text-[11px] font-semibold text-blue-700 bg-blue-100 px-2 py-0.5 rounded-full border border-blue-200">
                                                Auto Layout
                                            </span>
                                        </div>
                                    </div>

                                    <!-- Mockup Footer Action -->
                                    <div class="pt-3 border-t border-slate-100 flex items-center justify-between">
                                        <div class="flex items-center gap-1.5">
                                            <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                                            <span class="text-xs font-medium text-slate-600">Ekspor PDF &amp; Word Siap</span>
                                        </div>
                                        <div class="flex items-center gap-2">
                                            <span class="px-2.5 py-1 text-[11px] font-bold rounded-md bg-rose-600 text-white shadow-xs">.PDF</span>
                                            <span class="px-2.5 py-1 text-[11px] font-bold rounded-md bg-blue-600 text-white shadow-xs">.DOCX</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </section>

        <!-- Full-Width Statistics & Impact Banner -->
        <section class="w-full bg-slate-900 text-white py-10 px-4 sm:px-8 lg:px-12 xl:px-16 border-b border-slate-800">
            <div class="w-full grid grid-cols-2 md:grid-cols-4 gap-6 sm:gap-8 text-center divide-y md:divide-y-0 md:divide-x divide-slate-800">
                <div class="pt-4 md:pt-0">
                    <p class="text-3xl sm:text-4xl font-black text-amber-300 font-mono">10x</p>
                    <p class="text-xs sm:text-sm text-slate-400 font-medium mt-1">Lebih Cepat Buat Laporan</p>
                </div>
                <div class="pt-4 md:pt-0">
                    <p class="text-3xl sm:text-4xl font-black text-blue-400 font-mono">100%</p>
                    <p class="text-xs sm:text-sm text-slate-400 font-medium mt-1">Format Standar Resmi A4</p>
                </div>
                <div class="pt-4 md:pt-0">
                    <p class="text-3xl sm:text-4xl font-black text-purple-400 font-mono">1-Klik</p>
                    <p class="text-xs sm:text-sm text-slate-400 font-medium mt-1">ST, SPD, DPR &amp; Laporan</p>
                </div>
                <div class="pt-4 md:pt-0">
                    <p class="text-3xl sm:text-4xl font-black text-emerald-400 font-mono">0%</p>
                    <p class="text-xs sm:text-sm text-slate-400 font-medium mt-1">Redundansi Data Anggaran</p>
                </div>
            </div>
        </section>

        <!-- Features Grid Section (Full Width) -->
        <section id="fitur" class="w-full bg-white py-16 sm:py-20 px-4 sm:px-8 lg:px-12 xl:px-16 border-b border-slate-200/80">
            <div class="w-full">
                <!-- Section Header -->
                <div class="text-center max-w-3xl mx-auto space-y-3">
                    <span class="text-xs sm:text-sm font-bold uppercase tracking-wider text-blue-600 bg-blue-50 px-3 py-1 rounded-full border border-blue-100">
                        Solusi Lengkap &amp; Terintegrasi
                    </span>
                    <h2 class="text-2xl sm:text-3xl lg:text-4xl font-black text-slate-900 tracking-tight">
                        Fitur Unggulan Translok.ai
                    </h2>
                    <p class="text-base text-slate-600">
                        Dirancang khusus untuk mempermudah pimpinan, penanggung jawab anggaran, dan pelaksana dinas dalam merapikan administrasi kegiatan dari awal hingga akhir.
                    </p>
                </div>

                <!-- 6 Feature Cards Grid (Full Width) -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 sm:gap-8 mt-12 sm:mt-14">

                    <!-- Card 1: AI Generator -->
                    <div class="bg-slate-50 hover:bg-white rounded-2xl p-6 sm:p-7 border border-slate-200/90 shadow-xs hover:shadow-xl transition-all duration-300 group">
                        <div class="w-12 h-12 rounded-xl bg-purple-100 text-purple-700 flex items-center justify-center mb-5 group-hover:scale-110 transition-transform">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                        </div>
                        <h3 class="text-lg font-bold text-slate-900 mb-2">AI Narrative Generator</h3>
                        <p class="text-sm text-slate-600 leading-relaxed">
                            Cukup masukkan poin-poin hasil kunjungan. Mesin AI cerdas kami merangkainya menjadi narasi laporan dinas formal berbahasa Indonesia yang terstruktur dan siap ditandatangani.
                        </p>
                    </div>

                    <!-- Card 2: ST & SPD -->
                    <div class="bg-slate-50 hover:bg-white rounded-2xl p-6 sm:p-7 border border-slate-200/90 shadow-xs hover:shadow-xl transition-all duration-300 group">
                        <div class="w-12 h-12 rounded-xl bg-amber-100 text-amber-700 flex items-center justify-center mb-5 group-hover:scale-110 transition-transform">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        </div>
                        <h3 class="text-lg font-bold text-slate-900 mb-2">Surat Tugas &amp; SPD Otomatis</h3>
                        <p class="text-sm text-slate-600 leading-relaxed">
                            Penerbitan Surat Tugas dan lembar SPD I &amp; II dengan nomor agenda berurutan, penetapan daftar pelaksana tugas, kendaraan, dan tanggal keberangkatan tanpa ketik ulang.
                        </p>
                    </div>

                    <!-- Card 3: DPR -->
                    <div class="bg-slate-50 hover:bg-white rounded-2xl p-6 sm:p-7 border border-slate-200/90 shadow-xs hover:shadow-xl transition-all duration-300 group">
                        <div class="w-12 h-12 rounded-xl bg-emerald-100 text-emerald-700 flex items-center justify-center mb-5 group-hover:scale-110 transition-transform">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                        </div>
                        <h3 class="text-lg font-bold text-slate-900 mb-2">Daftar Pengeluaran Riil (DPR)</h3>
                        <p class="text-sm text-slate-600 leading-relaxed">
                            Pencatatan rincian biaya riil (uang harian, biaya riil penginapan, dan transportasi lokal) dihitung otomatis secara presisi sesuai aturan pertanggungjawaban dinas.
                        </p>
                    </div>

                    <!-- Card 4: Grid Dokumentasi -->
                    <div class="bg-slate-50 hover:bg-white rounded-2xl p-6 sm:p-7 border border-slate-200/90 shadow-xs hover:shadow-xl transition-all duration-300 group">
                        <div class="w-12 h-12 rounded-xl bg-blue-100 text-blue-700 flex items-center justify-center mb-5 group-hover:scale-110 transition-transform">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        </div>
                        <h3 class="text-lg font-bold text-slate-900 mb-2">Tata Letak Bukti Foto Otomatis</h3>
                        <p class="text-sm text-slate-600 leading-relaxed">
                            Upload berkas foto kegiatan langsung disusun rapi ke dalam kisi dokumentasi berstandar cetak A4. Tak perlu lagi memotong atau mengatur gambar manual di Word.
                        </p>
                    </div>

                    <!-- Card 5: Ekspor PDF & Word -->
                    <div class="bg-slate-50 hover:bg-white rounded-2xl p-6 sm:p-7 border border-slate-200/90 shadow-xs hover:shadow-xl transition-all duration-300 group">
                        <div class="w-12 h-12 rounded-xl bg-rose-100 text-rose-700 flex items-center justify-center mb-5 group-hover:scale-110 transition-transform">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        </div>
                        <h3 class="text-lg font-bold text-slate-900 mb-2">Ekspor Instan PDF &amp; Word (.docx)</h3>
                        <p class="text-sm text-slate-600 leading-relaxed">
                            Unduh seluruh dokumen dalam format PDF berkualitas tinggi siap cetak, atau dalam format Word untuk fleksibilitas penyesuaian khusus kapan saja.
                        </p>
                    </div>

                    <!-- Card 6: Database & DIPA Integration -->
                    <div class="bg-slate-50 hover:bg-white rounded-2xl p-6 sm:p-7 border border-slate-200/90 shadow-xs hover:shadow-xl transition-all duration-300 group">
                        <div class="w-12 h-12 rounded-xl bg-indigo-100 text-indigo-700 flex items-center justify-center mb-5 group-hover:scale-110 transition-transform">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                        </div>
                        <h3 class="text-lg font-bold text-slate-900 mb-2">Sinkronisasi Database Pegawai &amp; MAK</h3>
                        <p class="text-sm text-slate-600 leading-relaxed">
                            Terintegrasi dengan database profil pegawai, NIP, pangkat, satker, dan kode alokasi anggaran DIPA/MAK guna mengeliminasi salah ketik dan duplikasi anggaran.
                        </p>
                    </div>

                </div>
            </div>
        </section>

        <!-- How It Works / Workflow Section (Full Width) -->
        <section id="alur-kerja" class="w-full bg-slate-50/70 py-16 sm:py-20 px-4 sm:px-8 lg:px-12 xl:px-16 border-b border-slate-200/80">
            <div class="w-full">
                <!-- Section Header -->
                <div class="text-center max-w-3xl mx-auto space-y-3">
                    <span class="text-xs sm:text-sm font-bold uppercase tracking-wider text-indigo-600 bg-indigo-50 px-3 py-1 rounded-full border border-indigo-100">
                        Mudah &amp; Terstruktur
                    </span>
                    <h2 class="text-2xl sm:text-3xl lg:text-4xl font-black text-slate-900 tracking-tight">
                        Alur Kerja Dalam 3 Langkah Singkat
                    </h2>
                    <p class="text-base text-slate-600">
                        Dari awal penugasan lapangan hingga seluruh berkas SPJ dan laporan selesai ditandatangani.
                    </p>
                </div>

                <!-- 3 Step Flow Grid (Full Width) -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mt-12 sm:mt-14 relative">

                    <!-- Step 1 -->
                    <div class="bg-white rounded-2xl p-6 sm:p-8 border border-slate-200/90 shadow-xs relative">
                        <div class="inline-flex items-center justify-center w-10 h-10 rounded-xl bg-blue-600 text-white font-black text-sm mb-5 shadow-sm shadow-blue-500/20">
                            01
                        </div>
                        <h3 class="text-lg font-bold text-slate-900 mb-2">Terbitkan Surat Tugas &amp; SPD</h3>
                        <p class="text-sm text-slate-600 leading-relaxed">
                            Pilih pelaksana tugas dari daftar pegawai, tentukan tanggal, tujuan, dan alokasi anggaran (MAK). Sistem langsung memvalidasi dan mencetak Surat Tugas dan lembar SPD resmi.
                        </p>
                    </div>

                    <!-- Step 2 -->
                    <div class="bg-white rounded-2xl p-6 sm:p-8 border border-slate-200/90 shadow-xs relative">
                        <div class="inline-flex items-center justify-center w-10 h-10 rounded-xl bg-indigo-600 text-white font-black text-sm mb-5 shadow-sm shadow-indigo-500/20">
                            02
                        </div>
                        <h3 class="text-lg font-bold text-slate-900 mb-2">Catat Hasil &amp; Unggah Foto</h3>
                        <p class="text-sm text-slate-600 leading-relaxed">
                            Selesai dinas, masukkan ringkasan poin temuan lapangan dan unggah foto dokumentasi kegiatan. Masukkan pula rincian pengeluaran riil untuk pencetakan DPR.
                        </p>
                    </div>

                    <!-- Step 3 -->
                    <div class="bg-white rounded-2xl p-6 sm:p-8 border border-slate-200/90 shadow-xs relative">
                        <div class="inline-flex items-center justify-center w-10 h-10 rounded-xl bg-purple-600 text-white font-black text-sm mb-5 shadow-sm shadow-purple-500/20">
                            03
                        </div>
                        <h3 class="text-lg font-bold text-slate-900 mb-2">Generate Narasi AI &amp; Ekspor</h3>
                        <p class="text-sm text-slate-600 leading-relaxed">
                            Klik satu tombol untuk meminta AI menyusun paragraf laporan dinas lengkap. Dokumen siap diunduh dalam format PDF atau Word berstandar A4 dalam hitungan detik.
                        </p>
                    </div>

                </div>
            </div>
        </section>

        <!-- Document Types Showcase (Full Width) -->
        <section id="dokumen" class="w-full bg-white py-16 sm:py-20 px-4 sm:px-8 lg:px-12 xl:px-16 border-b border-slate-200/80">
            <div class="w-full">
                <div class="text-center max-w-3xl mx-auto space-y-3 mb-12 sm:mb-14">
                    <span class="text-xs sm:text-sm font-bold uppercase tracking-wider text-emerald-600 bg-emerald-50 px-3 py-1 rounded-full border border-emerald-100">
                        Standar Output Dokumen
                    </span>
                    <h2 class="text-2xl sm:text-3xl lg:text-4xl font-black text-slate-900 tracking-tight">
                        Dokumen Yang Dihasilkan Oleh Translok.ai
                    </h2>
                    <p class="text-base text-slate-600">
                        Semua dokumen diformat sesuai kaidah tata naskah dinas resmi dan standar pertanggungjawaban anggaran.
                    </p>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                    <div class="p-6 rounded-2xl bg-slate-50 border border-slate-200/80 flex flex-col justify-between">
                        <div>
                            <div class="w-10 h-10 rounded-xl bg-amber-100 text-amber-700 flex items-center justify-center font-bold text-sm mb-4">
                                ST
                            </div>
                            <h4 class="font-bold text-slate-900 text-base mb-1">Surat Tugas</h4>
                            <p class="text-xs text-slate-600 leading-relaxed">
                                Lembar penugasan resmi lengkap dengan dasar hukum tugas, susunan tim/pelaksana, tujuan, dan periode perjalanan dinas.
                            </p>
                        </div>
                        <div class="mt-4 pt-3 border-t border-slate-200 text-xs text-slate-500 font-semibold flex items-center gap-1.5">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Siap Cetak PDF &amp; Word
                        </div>
                    </div>

                    <div class="p-6 rounded-2xl bg-slate-50 border border-slate-200/80 flex flex-col justify-between">
                        <div>
                            <div class="w-10 h-10 rounded-xl bg-blue-100 text-blue-700 flex items-center justify-center font-bold text-sm mb-4">
                                SPD
                            </div>
                            <h4 class="font-bold text-slate-900 text-base mb-1">Surat Perjalanan Dinas</h4>
                            <p class="text-xs text-slate-600 leading-relaxed">
                                Formulir SPD resmi muka dan belakang yang memuat riwayat perjalanan, cap berangkat/tiba, serta pejabat pemberi perintah.
                            </p>
                        </div>
                        <div class="mt-4 pt-3 border-t border-slate-200 text-xs text-slate-500 font-semibold flex items-center gap-1.5">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Format Baku 2 Sisi
                        </div>
                    </div>

                    <div class="p-6 rounded-2xl bg-slate-50 border border-slate-200/80 flex flex-col justify-between">
                        <div>
                            <div class="w-10 h-10 rounded-xl bg-emerald-100 text-emerald-700 flex items-center justify-center font-bold text-sm mb-4">
                                DPR
                            </div>
                            <h4 class="font-bold text-slate-900 text-base mb-1">Daftar Pengeluaran Riil</h4>
                            <p class="text-xs text-slate-600 leading-relaxed">
                                Lembar pertanggungjawaban pengeluaran tanpa bukti kuitansi (uang harian, transport lokal) sesuai pagu SBM yang berlaku.
                            </p>
                        </div>
                        <div class="mt-4 pt-3 border-t border-slate-200 text-xs text-slate-500 font-semibold flex items-center gap-1.5">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Terhitung Otomatis
                        </div>
                    </div>

                    <div class="p-6 rounded-2xl bg-slate-50 border border-slate-200/80 flex flex-col justify-between">
                        <div>
                            <div class="w-10 h-10 rounded-xl bg-purple-100 text-purple-700 flex items-center justify-center font-bold text-sm mb-4">
                                LAP
                            </div>
                            <h4 class="font-bold text-slate-900 text-base mb-1">Laporan Pelaksanaan</h4>
                            <p class="text-xs text-slate-600 leading-relaxed">
                                Laporan narasi dinas lengkap berisi pendahuluan, maksud tujuan, uraian pelaksanaan oleh AI, dan lampiran foto grid kegiatan.
                            </p>
                        </div>
                        <div class="mt-4 pt-3 border-t border-slate-200 text-xs text-slate-500 font-semibold flex items-center gap-1.5">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Dilengkapi Foto Grid A4
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Platform Highlight & Mission Banner (Full Width) -->
        <section id="tentang" class="w-full bg-gradient-to-r from-slate-950 via-indigo-950 to-slate-900 text-white py-16 sm:py-20 px-4 sm:px-8 lg:px-12 xl:px-16 relative overflow-hidden">
            <!-- Ambient Light Gradients -->
            <div class="absolute -top-24 -left-24 w-80 h-80 bg-blue-500/10 rounded-full blur-3xl pointer-events-none"></div>
            <div class="absolute -bottom-24 -right-24 w-80 h-80 bg-purple-500/20 rounded-full blur-3xl pointer-events-none"></div>

            <div class="w-full relative z-10 flex flex-col md:flex-row items-center justify-between gap-8 sm:gap-12">
                <div class="max-w-3xl space-y-4">
                    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-white/10 text-purple-200 border border-white/15 text-xs font-semibold">
                        <img src="{{ asset('images/translok_logo.jpg') }}" alt="Translok.ai" class="h-4 w-4 object-contain rounded-full">
                        <span>Tentang Platform Translok.ai</span>
                    </div>
                    <h3 class="text-2xl sm:text-3xl font-extrabold tracking-tight text-white leading-snug">
                        "Cerdas Mengelola, Cepat Melapor, Akurat Berstandar."
                    </h3>
                    <p class="text-sm sm:text-base text-slate-300 leading-relaxed font-light">
                        Translok.ai adalah platform administrasi perjalanan dinas cerdas yang mengubah parameter lapangan menjadi dokumen pelaporan lengkap berstandar A4 secara instan. Dilengkapi dengan AI Narrative Generator dan otomasi tata letak bukti kegiatan, memastikan pelaporan anggaran DIPA/MAK berjalan presisi, cepat, dan tanpa redundansi data.
                    </p>
                </div>

                <div class="shrink-0 w-full md:w-auto text-center md:text-right">
                    @auth
                        <a href="{{ url('/dashboard') }}" class="inline-flex items-center justify-center gap-2 px-8 py-4 rounded-xl bg-blue-600 hover:bg-blue-500 text-white font-bold text-base shadow-xl shadow-blue-500/25 transition-all hover:scale-105">
                            <span>Masuk ke Dashboard</span>
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="inline-flex items-center justify-center gap-2 px-8 py-4 rounded-xl bg-blue-600 hover:bg-blue-500 text-white font-bold text-base shadow-xl shadow-blue-500/25 transition-all hover:scale-105">
                            <span>Mulai Sekarang</span>
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                        </a>
                    @endauth
                </div>
            </div>
        </section>

    </main>

    <!-- Footer (Full Width) -->
    <footer class="w-full bg-slate-950 text-slate-400 py-10 px-4 sm:px-8 lg:px-12 xl:px-16 border-t border-slate-800">
        <div class="w-full flex flex-col md:flex-row items-center justify-between gap-6">
            <!-- Brand & Info -->
            <div class="flex items-center gap-3">
                <img src="{{ asset('images/translok_logo.jpg') }}" alt="Translok.ai Logo" class="h-9 w-9 object-contain rounded-lg border border-slate-700">
                <span class="font-bold text-lg text-white">Translok<span class="text-orange-500">.ai</span></span>
            </div>

            <!-- Footer Nav -->
            <div class="flex flex-wrap items-center justify-center gap-6 text-xs font-medium text-slate-400">
                <a href="#fitur" class="hover:text-white transition">Fitur Utama</a>
                <a href="#alur-kerja" class="hover:text-white transition">Alur Kerja</a>
                <a href="#dokumen" class="hover:text-white transition">Standar Dokumen</a>
                <a href="#tentang" class="hover:text-white transition">Tentang</a>
                @auth
                    <a href="{{ url('/dashboard') }}" class="text-blue-400 hover:text-blue-300 transition">Dashboard</a>
                @else
                    <a href="{{ route('login') }}" class="text-blue-400 hover:text-blue-300 transition">Login Petugas</a>
                @endauth
            </div>

            <!-- Copyright -->
            <div class="text-center md:text-right text-xs text-slate-500">
                <p>&copy; {{ date('Y') }} Translok.ai. All rights reserved.</p>
                <p class="text-[11px] text-slate-600 mt-0.5">Cerdas Mengelola, Cepat Melapor.</p>
            </div>
        </div>
    </footer>

</body>
</html>