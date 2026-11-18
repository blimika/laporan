<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Translok.ai') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:300,400,500,600,700,800&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        <script src="https://cdn.tailwindcss.com"></script>
        <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
        <!-- @vite(['resources/css/app.css', 'resources/js/app.js']) -->
        <style>
            body { font-family: 'Figtree', sans-serif; }
        </style>
    </head>
    <body class="h-full font-sans text-slate-800 antialiased bg-slate-50 selection:bg-blue-600 selection:text-white">
        <div class="min-h-screen flex flex-col justify-between items-center py-10 px-4 sm:px-6 relative overflow-hidden">
            <!-- Background Ambient Glow -->
            <div class="absolute -top-32 -left-32 w-80 h-80 bg-blue-500/10 rounded-full blur-3xl pointer-events-none"></div>
            <div class="absolute -bottom-32 -right-32 w-80 h-80 bg-purple-500/10 rounded-full blur-3xl pointer-events-none"></div>

            <!-- Top Brand -->
            <div class="flex flex-col items-center">
                <a href="/" class="flex items-center gap-3 group">
                    <img src="{{ asset('images/translok_logo.jpg') }}" alt="Translok.ai Logo" class="h-12 w-12 object-contain rounded-2xl border border-blue-100 shadow-md group-hover:scale-105 transition-transform duration-200">
                    <span class="font-black text-2xl text-blue-700 tracking-tight">
                        Translok<span class="text-orange-500">.ai</span>
                    </span>
                </a>
            </div>

            <!-- Center Content Card -->
            <div class="w-full sm:max-w-md my-8 px-6 sm:px-8 py-8 bg-white shadow-xl shadow-slate-200/60 border border-slate-200/80 rounded-2xl relative z-10">
                {{ $slot }}
            </div>

            <!-- Bottom Back & Copyright -->
            <div class="text-center text-xs text-slate-500 space-y-2 relative z-10">
                <a href="{{ url('/') }}" class="inline-flex items-center gap-1.5 font-semibold text-blue-600 hover:text-blue-700 transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                    <span>Kembali ke Halaman Utama</span>
                </a>
                <p>&copy; {{ date('Y') }} Translok.ai. All rights reserved.</p>
            </div>
        </div>
    </body>
</html>