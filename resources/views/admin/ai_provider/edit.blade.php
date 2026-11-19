<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <div class="flex items-center gap-2 mb-1">
                    <a href="{{ route('dashboard') }}" class="text-xs font-semibold text-purple-600 hover:text-purple-800 flex items-center gap-1 transition">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                        </svg>
                        Kembali ke Dashboard
                    </a>
                    <span class="text-gray-300">•</span>
                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[11px] font-semibold bg-purple-100 text-purple-800 border border-purple-200">
                        🤖 Engine AI Multi-Provider
                    </span>
                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[11px] font-semibold bg-indigo-100 text-indigo-800 border border-indigo-200">
                        ✨ Google Gemini & DeepSeek
                    </span>
                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[11px] font-semibold bg-emerald-100 text-emerald-800 border border-emerald-200">
                        ⚡ Uji Coba API Key Langsung
                    </span>
                </div>
                <h2 class="font-extrabold text-2xl text-gray-900 leading-tight flex items-center gap-2.5">
                    <span class="p-2 bg-gradient-to-tr from-purple-600 via-indigo-600 to-purple-800 text-white rounded-xl shadow-sm">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                    </span>
                    <span>{{ __('Setting Provider AI & API Key') }}</span>
                </h2>
                <p class="text-xs text-gray-500 mt-1 max-w-3xl">
                    Konfigurasi mesin kecerdasan buatan untuk generate narasi laporan perjalanan dinas otomatis. Anda dapat melakukan pengujian koneksi API Key secara langsung untuk memastikan kunci yang diinput valid dan siap digunakan.
                </p>
            </div>

            <div class="flex items-center flex-wrap gap-2 self-start md:self-auto">
                <a href="https://aistudio.google.com/" target="_blank" class="inline-flex items-center px-3.5 py-2 bg-white border border-gray-300 rounded-xl text-xs font-semibold text-gray-700 hover:bg-gray-50 shadow-xs transition" title="Buka Google AI Studio untuk membuat atau melihat API Key">
                    <svg class="w-3.5 h-3.5 mr-1.5 text-blue-600" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-1 14.5v-9l6 4.5-6 4.5z"/>
                    </svg>
                    Google AI Studio ↗
                </a>
                <a href="https://platform.deepseek.com/" target="_blank" class="inline-flex items-center px-3.5 py-2 bg-purple-50 border border-purple-200 text-purple-700 hover:bg-purple-100 rounded-xl text-xs font-semibold shadow-xs transition" title="Buka DeepSeek Platform untuk API Key">
                    <svg class="w-3.5 h-3.5 mr-1.5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                    </svg>
                    DeepSeek Platform ↗
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-8 bg-gray-50/60 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            <!-- NOTIFIKASI SUCCESS & ERROR -->
            @if(session('success'))
                <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 p-4 rounded-2xl shadow-xs flex items-start gap-3">
                    <svg class="w-5 h-5 text-emerald-600 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <div>
                        <h4 class="text-xs font-bold uppercase tracking-wider text-emerald-900">Pengaturan Berhasil Disimpan</h4>
                        <p class="text-xs text-emerald-700 mt-0.5">{{ session('success') }}</p>
                    </div>
                </div>
            @endif

            @if(session('error'))
                <div class="bg-rose-50 border border-rose-200 text-rose-800 p-4 rounded-2xl shadow-xs flex items-start gap-3">
                    <svg class="w-5 h-5 text-rose-600 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                    <div>
                        <h4 class="text-xs font-bold uppercase tracking-wider text-rose-900">Terjadi Kesalahan</h4>
                        <p class="text-xs text-rose-700 mt-0.5">{{ session('error') }}</p>
                    </div>
                </div>
            @endif

            <!-- SUPER ADMIN SATKER SWITCHER -->
            @if(auth()->user()->role === 'super' && $allSatkers->count() > 0)
                <div class="bg-white rounded-2xl shadow-xs border border-gray-200/80 p-4 md:p-5">
                    <form method="GET" action="{{ route('admin.ai_provider.edit') }}" class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                        <div class="flex items-center gap-2.5">
                            <span class="p-2 rounded-xl bg-purple-100 text-purple-700">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                </svg>
                            </span>
                            <div>
                                <h4 class="text-xs font-bold text-gray-900 uppercase tracking-wider">Super Admin: Pilih Satker Sasaran</h4>
                                <p class="text-xs text-gray-500">Konfigurasi provider AI dan API Key berlaku spesifik untuk Satuan Kerja terpilih.</p>
                            </div>
                        </div>

                        <div class="w-full sm:w-80">
                            <select id="satker_switcher" name="satker_id" onchange="this.form.submit()" class="block w-full text-xs font-semibold border-gray-300 focus:border-purple-600 focus:ring-purple-600 rounded-xl shadow-xs bg-gray-50">
                                @foreach($allSatkers as $s)
                                    <option value="{{ $s->id }}" {{ $satker->id == $s->id ? 'selected' : '' }}>
                                        {{ $s->kode }} - {{ $s->nama }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </form>
                </div>
            @endif

            <!-- CARD CONTEXT: OVERVIEW KONFIGURASI SAAT INI -->
            <div class="bg-gradient-to-r from-slate-900 via-indigo-950 to-purple-950 text-white rounded-2xl shadow-md p-5 sm:p-6 relative overflow-hidden">
                <div class="absolute -right-8 -bottom-8 w-48 h-48 bg-white/5 rounded-full pointer-events-none"></div>

                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-4 border-b border-white/10">
                    <div class="flex items-center gap-3">
                        <div class="w-12 h-12 rounded-xl bg-white/10 backdrop-blur-xs border border-white/15 flex items-center justify-center text-purple-300 font-black text-2xl shadow-xs">
                            🤖
                        </div>
                        <div>
                            <div class="flex items-center gap-2">
                                <span class="text-xs font-bold uppercase tracking-wider text-purple-300">Status Provider AI Satker</span>
                                <span class="px-2 py-0.5 text-[10px] font-bold rounded-full {{ $satker->ai_provider === 'deepseek' ? 'bg-blue-500/20 text-blue-300 border border-blue-500/30' : 'bg-emerald-500/20 text-emerald-300 border border-emerald-500/30' }} flex items-center gap-1">
                                    <span class="w-1.5 h-1.5 rounded-full {{ $satker->ai_provider === 'deepseek' ? 'bg-blue-400' : 'bg-emerald-400' }} animate-pulse"></span>
                                    {{ $satker->ai_provider === 'deepseek' ? 'DeepSeek Aktif' : 'Google Gemini Aktif' }}
                                </span>
                            </div>
                            <h3 class="text-lg sm:text-xl font-bold text-white tracking-wide mt-0.5">
                                {{ $satker->kode }} — {{ $satker->nama }}
                            </h3>
                        </div>
                    </div>

                    <div class="flex flex-col sm:items-end text-xs text-purple-200/90">
                        <span class="text-[11px] text-gray-400">Mode Distribusi Kunci:</span>
                        <span class="font-bold text-white text-xs mt-0.5 px-2.5 py-1 rounded-lg {{ $satker->is_centralized_api ? 'bg-indigo-600/60 border border-indigo-400/40 text-indigo-100' : 'bg-amber-600/40 border border-amber-400/40 text-amber-200' }}">
                            {{ $satker->is_centralized_api ? '🏢 Terpusat (Global Satker)' : '👤 Mandiri (Per Profil Pegawai)' }}
                        </span>
                    </div>
                </div>

                <!-- Status Kunci Detail -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 mt-4 text-xs">
                    <div class="bg-white/5 rounded-xl p-3 border border-white/5 flex items-center justify-between">
                        <div>
                            <span class="text-gray-400 block text-[11px]">API Key Google Gemini Satker:</span>
                            <span class="font-bold text-white mt-0.5 block font-mono">
                                @if(!empty($satker->gemini_api_key))
                                    {{ substr($satker->gemini_api_key, 0, 8) }}••••••••{{ substr($satker->gemini_api_key, -4) }}
                                @else
                                    <span class="text-amber-300 font-sans font-normal italic">Belum dikonfigurasi</span>
                                @endif
                            </span>
                        </div>
                        <span class="px-2 py-0.5 rounded text-[10px] font-bold {{ !empty($satker->gemini_api_key) ? 'bg-emerald-500/20 text-emerald-300' : 'bg-rose-500/20 text-rose-300' }}">
                            {{ !empty($satker->gemini_api_key) ? '✓ Terpasang' : '✕ Kosong' }}
                        </span>
                    </div>

                    <div class="bg-white/5 rounded-xl p-3 border border-white/5 flex items-center justify-between">
                        <div>
                            <span class="text-gray-400 block text-[11px]">API Key DeepSeek Satker:</span>
                            <span class="font-bold text-white mt-0.5 block font-mono">
                                @if(!empty($satker->deepseek_api_key))
                                    {{ substr($satker->deepseek_api_key, 0, 8) }}••••••••{{ substr($satker->deepseek_api_key, -4) }}
                                @else
                                    <span class="text-amber-300 font-sans font-normal italic">Belum dikonfigurasi</span>
                                @endif
                            </span>
                        </div>
                        <span class="px-2 py-0.5 rounded text-[10px] font-bold {{ !empty($satker->deepseek_api_key) ? 'bg-emerald-500/20 text-emerald-300' : 'bg-rose-500/20 text-rose-300' }}">
                            {{ !empty($satker->deepseek_api_key) ? '✓ Terpasang' : '✕ Kosong' }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- FORM UTAMA PENGATURAN PROVIDER AI -->
            <form id="form-ai-provider" method="POST" action="{{ route('admin.ai_provider.update') }}" class="space-y-6">
                @csrf
                @method('PUT')

                @if(auth()->user()->role === 'super')
                    <input type="hidden" name="satker_id" id="satker_id" value="{{ $satker->id }}">
                @endif

                <!-- ======================================================== -->
                <!-- SECTION 1: MODE DISTRIBUSI API KEY -->
                <!-- ======================================================== -->
                <div class="bg-white rounded-2xl border border-gray-100 shadow-xs p-6 md:p-7">
                    <div class="flex items-center gap-2.5 mb-5 pb-3 border-b border-gray-100">
                        <span class="w-7 h-7 rounded-lg bg-indigo-100 text-indigo-700 font-bold text-xs flex items-center justify-center">1</span>
                        <div>
                            <h3 class="text-sm font-bold text-gray-900">Kebijakan Mode Distribusi API Key</h3>
                            <p class="text-xs text-gray-500">Tentukan apakah API Key disediakan terpusat oleh Admin atau masing-masing pegawai menginput mandiri.</p>
                        </div>
                    </div>

                    <div class="p-4 rounded-xl border border-gray-200 bg-gray-50/70 hover:border-purple-200 transition">
                        <label class="flex items-start gap-3.5 cursor-pointer select-none">
                            <div class="pt-0.5">
                                <input
                                    id="is_centralized_api"
                                    name="is_centralized_api"
                                    type="checkbox"
                                    value="1"
                                    {{ old('is_centralized_api', $satker->is_centralized_api) ? 'checked' : '' }}
                                    class="w-5 h-5 rounded border-gray-300 text-purple-600 shadow-xs focus:ring-purple-500 cursor-pointer"
                                    onchange="toggleCentralizedNotice(this.checked)"
                                >
                            </div>
                            <div class="flex-1">
                                <div class="flex items-center gap-2">
                                    <span class="text-sm font-bold text-gray-900">Gunakan API Key Terpusat (Global Satker)</span>
                                    <span class="px-2 py-0.5 text-[10px] font-bold rounded-full bg-purple-100 text-purple-800 border border-purple-200">
                                        Rekomendasi Kantor
                                    </span>
                                </div>
                                <p class="text-xs text-gray-600 mt-1 leading-relaxed">
                                    Bila dicentang, <strong>seluruh pegawai</strong> di Satker ini dapat langsung meng-generate laporan dinas menggunakan API Key kantor yang diinput di bawah tanpa perlu mendaftar ke AI Studio secara personal. Kolom API Key di profil masing-masing user akan disembunyikan.
                                </p>
                                <p class="text-[11px] text-gray-400 mt-1">
                                    Bila tidak dicentang, setiap pegawai wajib mengisi API Key mereka masing-masing pada menu Edit Profil User.
                                </p>
                            </div>
                        </label>
                    </div>
                </div>

                <!-- ======================================================== -->
                <!-- SECTION 2: PEMILIHAN ENGINE PROVIDER AI -->
                <!-- ======================================================== -->
                <div class="bg-white rounded-2xl border border-gray-100 shadow-xs p-6 md:p-7">
                    <div class="flex items-center gap-2.5 mb-5 pb-3 border-b border-gray-100">
                        <span class="w-7 h-7 rounded-lg bg-purple-100 text-purple-700 font-bold text-xs flex items-center justify-center">2</span>
                        <div>
                            <h3 class="text-sm font-bold text-gray-900">Pilihan Engine Provider AI Utama</h3>
                            <p class="text-xs text-gray-500">Pilih provider yang akan aktif memproses penyusunan narasi laporan dinas pada aplikasi.</p>
                        </div>
                    </div>

                    @php
                        $currentProvider = old('ai_provider', $satker->ai_provider ?? 'gemini');
                    @endphp

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4" id="provider-cards-wrapper">
                        <!-- Option 1: Google Gemini -->
                        <label class="provider-card relative flex flex-col p-5 rounded-2xl border-2 cursor-pointer transition shadow-xs {{ $currentProvider === 'gemini' ? 'border-purple-600 bg-purple-50/40 ring-2 ring-purple-200' : 'border-gray-200 bg-white hover:border-purple-300' }}">
                            <div class="flex items-start justify-between">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-xl bg-gradient-to-tr from-blue-600 to-indigo-600 text-white flex items-center justify-center font-bold text-lg shadow-2xs">
                                        ✨
                                    </div>
                                    <div>
                                        <div class="flex items-center gap-2">
                                            <h4 class="text-sm font-bold text-gray-900">Google Gemini</h4>
                                            <span class="px-2 py-0.5 text-[10px] font-bold rounded-full bg-emerald-100 text-emerald-800 border border-emerald-200">
                                                Disarankan
                                            </span>
                                        </div>
                                        <p class="text-xs text-gray-500 font-mono mt-0.5">Model: gemini-2.5-flash / fallback</p>
                                    </div>
                                </div>
                                <input type="radio" name="ai_provider" value="gemini" class="w-4 h-4 text-purple-600 focus:ring-purple-500 border-gray-300" {{ $currentProvider === 'gemini' ? 'checked' : '' }} onchange="selectProvider('gemini')">
                            </div>

                            <ul class="text-xs text-gray-600 mt-4 space-y-1.5 border-t border-gray-100 pt-3">
                                <li class="flex items-center gap-1.5">
                                    <svg class="w-3.5 h-3.5 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                    <span>Tersedia kuota gratis (Free Tier) melimpah dari Google AI Studio.</span>
                                </li>
                                <li class="flex items-center gap-1.5">
                                    <svg class="w-3.5 h-3.5 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                    <span>Kecepatan respons tinggi dan tata bahasa kedinasan Indonesia sangat baik.</span>
                                </li>
                            </ul>
                        </label>

                        <!-- Option 2: DeepSeek -->
                        <label class="provider-card relative flex flex-col p-5 rounded-2xl border-2 cursor-pointer transition shadow-xs {{ $currentProvider === 'deepseek' ? 'border-purple-600 bg-purple-50/40 ring-2 ring-purple-200' : 'border-gray-200 bg-white hover:border-purple-300' }}">
                            <div class="flex items-start justify-between">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-xl bg-gradient-to-tr from-cyan-600 to-blue-700 text-white flex items-center justify-center font-bold text-lg shadow-2xs">
                                        🐋
                                    </div>
                                    <div>
                                        <div class="flex items-center gap-2">
                                            <h4 class="text-sm font-bold text-gray-900">DeepSeek</h4>
                                            <span class="px-2 py-0.5 text-[10px] font-bold rounded-full bg-blue-100 text-blue-800 border border-blue-200">
                                                Alternatif Handal
                                            </span>
                                        </div>
                                        <p class="text-xs text-gray-500 font-mono mt-0.5">Model: deepseek-chat (V3)</p>
                                    </div>
                                </div>
                                <input type="radio" name="ai_provider" value="deepseek" class="w-4 h-4 text-purple-600 focus:ring-purple-500 border-gray-300" {{ $currentProvider === 'deepseek' ? 'checked' : '' }} onchange="selectProvider('deepseek')">
                            </div>

                            <ul class="text-xs text-gray-600 mt-4 space-y-1.5 border-t border-gray-100 pt-3">
                                <li class="flex items-center gap-1.5">
                                    <svg class="w-3.5 h-3.5 text-blue-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                    <span>Kemampuan penalaran logis dan evaluasi kendala sangat mendalam.</span>
                                </li>
                                <li class="flex items-center gap-1.5">
                                    <svg class="w-3.5 h-3.5 text-blue-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                    <span>Biaya per token sangat ekonomis pada platform.deepseek.com.</span>
                                </li>
                            </ul>
                        </label>
                    </div>
                    <x-input-error :messages="$errors->get('ai_provider')" class="mt-2" />
                </div>

                <!-- ======================================================== -->
                <!-- SECTION 3: KONFIGURASI & UJI COBA API KEY (INTERACTIVE STUDIO) -->
                <!-- ======================================================== -->
                <div class="bg-white rounded-2xl border border-gray-100 shadow-xs p-6 md:p-7">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 mb-5 pb-3 border-b border-gray-100">
                        <div class="flex items-center gap-2.5">
                            <span class="w-7 h-7 rounded-lg bg-emerald-100 text-emerald-700 font-bold text-xs flex items-center justify-center">3</span>
                            <div>
                                <h3 class="text-sm font-bold text-gray-900">Konfigurasi & Uji Coba API Key</h3>
                                <p class="text-xs text-gray-500">Masukkan kunci API rahasia dan lakukan uji coba koneksi langsung ke server AI sebelum disimpan.</p>
                            </div>
                        </div>

                        <span class="text-[11px] text-gray-400 bg-gray-50 border border-gray-200 px-2.5 py-1 rounded-lg">
                            🔒 Kunci tersimpan aman di server
                        </span>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                        <!-- CARD 1: GOOGLE GEMINI API KEY -->
                        <div id="card-gemini-key" class="p-5 rounded-2xl border transition {{ $currentProvider === 'gemini' ? 'border-purple-300 bg-purple-50/20 shadow-xs' : 'border-gray-200 bg-gray-50/50' }}">
                            <div class="flex items-center justify-between mb-3">
                                <div class="flex items-center gap-2">
                                    <span class="w-2.5 h-2.5 rounded-full {{ !empty($satker->gemini_api_key) ? 'bg-emerald-500' : 'bg-gray-300' }}"></span>
                                    <label for="gemini_api_key" class="text-xs font-bold text-gray-900 uppercase tracking-wider block">
                                        Google Gemini API Key
                                    </label>
                                </div>
                                <span class="text-[10px] font-bold px-2 py-0.5 rounded-full {{ !empty($satker->gemini_api_key) ? 'bg-emerald-100 text-emerald-800' : 'bg-gray-100 text-gray-500' }}">
                                    {{ !empty($satker->gemini_api_key) ? 'Tersimpan' : 'Kosong' }}
                                </span>
                            </div>

                            <div class="relative rounded-xl shadow-xs">
                                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-gray-400">
                                    <svg class="w-4 h-4 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z" />
                                    </svg>
                                </div>
                                <input
                                    id="gemini_api_key"
                                    name="gemini_api_key"
                                    type="password"
                                    value="{{ old('gemini_api_key', $satker->gemini_api_key) }}"
                                    placeholder="Contoh: AIzaSyD..."
                                    class="block w-full pl-10 pr-10 py-2.5 border-gray-300 focus:border-purple-600 focus:ring-purple-600 rounded-xl font-mono text-xs text-gray-900 placeholder:font-sans placeholder:text-gray-400"
                                />
                                <button
                                    type="button"
                                    onclick="togglePasswordVisibility('gemini_api_key', 'btn-eye-gemini')"
                                    id="btn-eye-gemini"
                                    class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-gray-400 hover:text-gray-600 transition"
                                    title="Tampilkan / Sembunyikan API Key"
                                >
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                </button>
                            </div>
                            <x-input-error :messages="$errors->get('gemini_api_key')" class="mt-1" />

                            <div class="mt-3 flex items-center justify-between">
                                <a href="https://aistudio.google.com/app/apikey" target="_blank" class="text-[11px] text-purple-600 hover:text-purple-800 hover:underline inline-flex items-center gap-1">
                                    <span>Dapatkan API Key di Google AI Studio</span>
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                                </a>

                                <button
                                    type="button"
                                    id="btn-test-gemini"
                                    onclick="testApiKey('gemini')"
                                    class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white rounded-xl text-xs font-bold shadow-xs hover:shadow-blue-600/20 transition active:scale-95"
                                >
                                    <svg class="w-3.5 h-3.5 text-yellow-300" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M11.3 1.046A1 1 0 0112 2v5h4a1 1 0 01.82 1.573l-7 10A1 1 0 018 18v-5H4a1 1 0 01-.82-1.573l7-10a1 1 0 011.12-.38z" clip-rule="evenodd" />
                                    </svg>
                                    <span>Test Koneksi Gemini</span>
                                </button>
                            </div>

                            <!-- Live Feedback Container Gemini -->
                            <div id="test-feedback-gemini" class="hidden mt-3 pt-3 border-t border-gray-200"></div>
                        </div>

                        <!-- CARD 2: DEEPSEEK API KEY -->
                        <div id="card-deepseek-key" class="p-5 rounded-2xl border transition {{ $currentProvider === 'deepseek' ? 'border-purple-300 bg-purple-50/20 shadow-xs' : 'border-gray-200 bg-gray-50/50' }}">
                            <div class="flex items-center justify-between mb-3">
                                <div class="flex items-center gap-2">
                                    <span class="w-2.5 h-2.5 rounded-full {{ !empty($satker->deepseek_api_key) ? 'bg-blue-500' : 'bg-gray-300' }}"></span>
                                    <label for="deepseek_api_key" class="text-xs font-bold text-gray-900 uppercase tracking-wider block">
                                        DeepSeek API Key
                                    </label>
                                </div>
                                <span class="text-[10px] font-bold px-2 py-0.5 rounded-full {{ !empty($satker->deepseek_api_key) ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-500' }}">
                                    {{ !empty($satker->deepseek_api_key) ? 'Tersimpan' : 'Kosong' }}
                                </span>
                            </div>

                            <div class="relative rounded-xl shadow-xs">
                                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-gray-400">
                                    <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z" />
                                    </svg>
                                </div>
                                <input
                                    id="deepseek_api_key"
                                    name="deepseek_api_key"
                                    type="password"
                                    value="{{ old('deepseek_api_key', $satker->deepseek_api_key) }}"
                                    placeholder="Contoh: sk-..."
                                    class="block w-full pl-10 pr-10 py-2.5 border-gray-300 focus:border-purple-600 focus:ring-purple-600 rounded-xl font-mono text-xs text-gray-900 placeholder:font-sans placeholder:text-gray-400"
                                />
                                <button
                                    type="button"
                                    onclick="togglePasswordVisibility('deepseek_api_key', 'btn-eye-deepseek')"
                                    id="btn-eye-deepseek"
                                    class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-gray-400 hover:text-gray-600 transition"
                                    title="Tampilkan / Sembunyikan API Key"
                                >
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                </button>
                            </div>
                            <x-input-error :messages="$errors->get('deepseek_api_key')" class="mt-1" />

                            <div class="mt-3 flex items-center justify-between">
                                <a href="https://platform.deepseek.com/api_keys" target="_blank" class="text-[11px] text-blue-600 hover:text-blue-800 hover:underline inline-flex items-center gap-1">
                                    <span>Buka Dashboard platform.deepseek.com</span>
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                                </a>

                                <button
                                    type="button"
                                    id="btn-test-deepseek"
                                    onclick="testApiKey('deepseek')"
                                    class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-gradient-to-r from-cyan-600 to-blue-600 hover:from-cyan-700 hover:to-blue-700 text-white rounded-xl text-xs font-bold shadow-xs hover:shadow-cyan-600/20 transition active:scale-95"
                                >
                                    <svg class="w-3.5 h-3.5 text-yellow-300" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M11.3 1.046A1 1 0 0112 2v5h4a1 1 0 01.82 1.573l-7 10A1 1 0 018 18v-5H4a1 1 0 01-.82-1.573l7-10a1 1 0 011.12-.38z" clip-rule="evenodd" />
                                    </svg>
                                    <span>Test Koneksi DeepSeek</span>
                                </button>
                            </div>

                            <!-- Live Feedback Container Deepseek -->
                            <div id="test-feedback-deepseek" class="hidden mt-3 pt-3 border-t border-gray-200"></div>
                        </div>

                    </div>
                </div>

                <!-- ======================================================== -->
                <!-- SECTION 4: PANDUAN CARA MENDAPATKAN API KEY GRATIS -->
                <!-- ======================================================== -->
                <div class="bg-white rounded-2xl border border-gray-100 shadow-xs p-6 md:p-7">
                    <h4 class="text-xs font-bold uppercase tracking-wider text-gray-800 flex items-center gap-2 mb-4">
                        <svg class="w-4 h-4 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span>Panduan Ringkas Mendapatkan API Key</span>
                    </h4>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-xs">
                        <!-- Step Google Gemini -->
                        <div class="p-4 rounded-xl bg-purple-50/50 border border-purple-200/80 space-y-2">
                            <div class="flex items-center gap-2">
                                <span class="w-5 h-5 rounded-full bg-purple-600 text-white font-bold text-[10px] flex items-center justify-center">G</span>
                                <h5 class="font-bold text-purple-950">Langkah Membuat Google Gemini API Key:</h5>
                            </div>
                            <ol class="list-decimal list-inside space-y-1.5 text-gray-600 pl-1">
                                <li>Buka peramban dan kunjungi <strong><a href="https://aistudio.google.com/" target="_blank" class="text-purple-700 underline">aistudio.google.com</a></strong>.</li>
                                <li>Login dengan akun Google/Gmail dinas atau pribadi Anda.</li>
                                <li>Pilih menu <strong>"Get API key"</strong> di bilah navigasi sebelah kiri.</li>
                                <li>Klik tombol <strong>"Create API key"</strong>, lalu salin (*copy*) kodenya.</li>
                                <li>Tempelkan ke kolom <strong>Gemini API Key</strong> di atas, lalu klik <strong>Test Koneksi</strong>.</li>
                            </ol>
                            <p class="text-[11px] text-purple-800 font-medium pt-1">
                                💡 Kuota gratis Google AI Studio sangat cukup untuk kebutuhan ratusan laporan dinas setiap bulannya tanpa biaya.
                            </p>
                        </div>

                        <!-- Step DeepSeek -->
                        <div class="p-4 rounded-xl bg-blue-50/50 border border-blue-200/80 space-y-2">
                            <div class="flex items-center gap-2">
                                <span class="w-5 h-5 rounded-full bg-blue-600 text-white font-bold text-[10px] flex items-center justify-center">D</span>
                                <h5 class="font-bold text-blue-950">Langkah Membuat DeepSeek API Key:</h5>
                            </div>
                            <ol class="list-decimal list-inside space-y-1.5 text-gray-600 pl-1">
                                <li>Kunjungi situs resmi <strong><a href="https://platform.deepseek.com/" target="_blank" class="text-blue-700 underline">platform.deepseek.com</a></strong>.</li>
                                <li>Daftar atau masuk menggunakan alamat email Anda.</li>
                                <li>Buka menu <strong>"API Keys"</strong> pada bilah sisi kiri dashboard.</li>
                                <li>Klik <strong>"Create new secret key"</strong>, beri nama, lalu salin kodenya.</li>
                                <li>Tempelkan ke kolom <strong>DeepSeek API Key</strong> di atas, lalu klik <strong>Test Koneksi</strong>.</li>
                            </ol>
                            <p class="text-[11px] text-blue-800 font-medium pt-1">
                                💡 DeepSeek menyediakan model deepseek-chat dengan performa tinggi dan biaya per token yang sangat bersaing.
                            </p>
                        </div>
                    </div>
                </div>

                <!-- ======================================================== -->
                <!-- SECTION 5: FOOTER ACTIONS -->
                <!-- ======================================================== -->
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5 flex flex-col sm:flex-row items-center justify-between gap-4 sticky bottom-4 z-10">
                    <div class="flex items-center gap-2 text-xs text-gray-500">
                        <svg class="w-4 h-4 text-emerald-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span>Gunakan tombol "Test Koneksi" di atas untuk memastikan API Key aktif sebelum menyimpan konfigurasi.</span>
                    </div>

                    <div class="flex items-center gap-3 w-full sm:w-auto justify-end">
                        <a href="{{ route('dashboard') }}" class="px-5 py-2.5 bg-white hover:bg-gray-100 text-gray-700 border border-gray-300 rounded-xl text-xs font-bold transition text-center">
                            Batal
                        </a>
                        <button type="submit" class="px-7 py-2.5 bg-gradient-to-r from-purple-600 via-indigo-600 to-purple-700 hover:from-purple-700 hover:to-indigo-800 text-white rounded-xl text-xs font-bold shadow-md hover:shadow-purple-600/30 flex items-center justify-center gap-2 transition active:scale-95">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            <span>Simpan Pengaturan Provider AI</span>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- JAVASCRIPT LOGIC -->
    <script>
        // Toggle Show / Hide Password
        function togglePasswordVisibility(inputId, btnId) {
            const input = document.getElementById(inputId);
            const btn = document.getElementById(btnId);
            if (!input) return;

            if (input.type === 'password') {
                input.type = 'text';
                btn.innerHTML = `
                    <svg class="w-4 h-4 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                    </svg>
                `;
            } else {
                input.type = 'password';
                btn.innerHTML = `
                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                    </svg>
                `;
            }
        }

        // Selection Visuals for Provider Cards
        function selectProvider(provider) {
            const cards = document.querySelectorAll('#provider-cards-wrapper .provider-card');
            cards.forEach(card => {
                const radio = card.querySelector('input[type="radio"]');
                if (radio && radio.value === provider) {
                    card.classList.add('border-purple-600', 'bg-purple-50/40', 'ring-2', 'ring-purple-200');
                    card.classList.remove('border-gray-200', 'bg-white');
                    radio.checked = true;
                } else {
                    card.classList.remove('border-purple-600', 'bg-purple-50/40', 'ring-2', 'ring-purple-200');
                    card.classList.add('border-gray-200', 'bg-white');
                }
            });

            // Highlight the corresponding key card
            const geminiCard = document.getElementById('card-gemini-key');
            const deepseekCard = document.getElementById('card-deepseek-key');

            if (provider === 'gemini') {
                if (geminiCard) geminiCard.className = 'p-5 rounded-2xl border transition border-purple-300 bg-purple-50/20 shadow-xs';
                if (deepseekCard) deepseekCard.className = 'p-5 rounded-2xl border transition border-gray-200 bg-gray-50/50';
            } else {
                if (geminiCard) geminiCard.className = 'p-5 rounded-2xl border transition border-gray-200 bg-gray-50/50';
                if (deepseekCard) deepseekCard.className = 'p-5 rounded-2xl border transition border-purple-300 bg-purple-50/20 shadow-xs';
            }
        }

        function toggleCentralizedNotice(checked) {
            // Can be used for custom notification state
        }

        // Async API Key Tester
        async function testApiKey(provider) {
            const btn = document.getElementById('btn-test-' + provider);
            const feedbackContainer = document.getElementById('test-feedback-' + provider);
            const inputKey = document.getElementById(provider + '_api_key');
            const satkerIdInput = document.getElementById('satker_id');

            const currentKeyVal = inputKey ? inputKey.value.trim() : '';

            // Loading State
            const originalBtnHTML = btn.innerHTML;
            btn.disabled = true;
            btn.classList.add('opacity-50', 'cursor-not-allowed');
            btn.innerHTML = `
                <svg class="animate-spin h-3.5 w-3.5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <span>Menguji...</span>
            `;

            feedbackContainer.classList.remove('hidden');
            feedbackContainer.innerHTML = `
                <div class="flex items-center gap-2 text-xs text-gray-500 py-1">
                    <svg class="animate-spin h-3.5 w-3.5 text-purple-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    <span>Mengirim ping uji coba ke server <strong>${provider === 'gemini' ? 'Google Gemini' : 'DeepSeek'}</strong>...</span>
                </div>
            `;

            try {
                const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') 
                    || document.querySelector('input[name="_token"]')?.value;

                const payload = {
                    provider: provider,
                    api_key: currentKeyVal,
                };

                if (satkerIdInput && satkerIdInput.value) {
                    payload.satker_id = satkerIdInput.value;
                }

                const response = await fetch("{{ route('admin.ai_provider.test') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify(payload)
                });

                const data = await response.json();

                if (response.ok && data.success) {
                    // Sukses
                    feedbackContainer.innerHTML = `
                        <div class="p-3 rounded-xl bg-emerald-50 border border-emerald-200 text-xs text-emerald-900 space-y-1.5 animate-fadeIn">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-1.5 font-bold text-emerald-800">
                                    <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                    <span>API Key Valid &amp; Terhubung Sukses!</span>
                                </div>
                                <span class="font-mono text-[10px] bg-emerald-100 text-emerald-800 px-2 py-0.5 rounded-full font-bold">
                                    ${data.latency_ms || 0} ms
                                </span>
                            </div>
                            <p class="text-[11px] text-emerald-700 leading-relaxed">${data.message}</p>
                            <div class="flex items-center gap-1 text-[10px] text-emerald-600 pt-1 border-t border-emerald-200/60">
                                <span class="font-bold">Model Diuji:</span> <span>${data.model || '-'}</span>
                                ${data.reply ? `• <span class="font-bold">Respon Balasan:</span> <em>"${data.reply}"</em>` : ''}
                            </div>
                        </div>
                    `;
                } else {
                    // Gagal
                    const errMsg = data.message || 'API Key tidak valid atau tidak dapat terhubung.';
                    feedbackContainer.innerHTML = `
                        <div class="p-3 rounded-xl bg-rose-50 border border-rose-200 text-xs text-rose-900 space-y-1.5 animate-fadeIn">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-1.5 font-bold text-rose-800">
                                    <svg class="w-4 h-4 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                    <span>Uji Coba Gagal / API Key Salah</span>
                                </div>
                                ${data.status_code ? `<span class="font-mono text-[10px] bg-rose-100 text-rose-800 px-2 py-0.5 rounded font-bold">HTTP ${data.status_code}</span>` : ''}
                            </div>
                            <p class="text-[11px] text-rose-700 leading-relaxed">${errMsg}</p>
                            ${data.raw_error && data.raw_error !== errMsg ? `
                                <div class="text-[10px] text-gray-500 font-mono bg-white p-1.5 rounded border border-rose-200 truncate" title="${data.raw_error}">
                                    Detail: ${data.raw_error}
                                </div>
                            ` : ''}
                        </div>
                    `;
                }

            } catch (err) {
                feedbackContainer.innerHTML = `
                    <div class="p-3 rounded-xl bg-rose-50 border border-rose-200 text-xs text-rose-800 space-y-1">
                        <div class="flex items-center gap-1.5 font-bold text-rose-900">
                            <svg class="w-4 h-4 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            <span>Gangguan Jaringan / Server</span>
                        </div>
                        <p class="text-[11px] text-rose-700">${err.message || 'Gagal menghubungi endpoint pengujian.'}</p>
                    </div>
                `;
            } finally {
                btn.disabled = false;
                btn.classList.remove('opacity-50', 'cursor-not-allowed');
                btn.innerHTML = originalBtnHTML;
            }
        }
    </script>
</x-app-layout>
