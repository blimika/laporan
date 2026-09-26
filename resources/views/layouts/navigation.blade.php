<nav x-data="{ open: false }" class="bg-white border-b border-gray-100">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}">
                        <x-application-logo class="block h-9 w-auto fill-current text-gray-800" />
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                        {{ __('Dashboard') }}
                    </x-nav-link>
                    <x-nav-link :href="route('surat-tugas.index')" :active="request()->routeIs('surat-tugas.*') || request()->routeIs('spd.*')">
                        {{ __('Surat Tugas & SPD') }}
                    </x-nav-link>
                    <x-nav-link :href="route('laporan.index')" :active="request()->routeIs('laporan.*')">
                        {{ __('Laporan AI') }}
                    </x-nav-link>
                    <x-nav-link :href="route('hanya-laporan.index')" :active="request()->routeIs('hanya-laporan.*')">
                        {{ __('Hanya Laporan') }}
                    </x-nav-link>
                    <x-nav-link :href="route('pegawais.index')" :active="request()->routeIs('pegawais.*')">
                        {{ __('Data Pegawai') }}
                    </x-nav-link>
                    <x-nav-link :href="route('anggarans.index')" :active="request()->routeIs('anggarans.*')">
                        {{ __('Data Anggaran') }}
                    </x-nav-link>
                    
                    @if(in_array(auth()->user()->role, ['super', 'admin']))
                    <div class="hidden sm:flex sm:items-center sm:ms-6">
                        <x-dropdown align="right" width="48">
                            <x-slot name="trigger">
                                <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150">
                                    <div>Master Data</div>
                                    <div class="ms-1">
                                        <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                </button>
                            </x-slot>
                            <x-slot name="content">
                                <x-dropdown-link :href="route('admin.users.index')">Users</x-dropdown-link>
                                @if(auth()->user()->role === 'super')
                                <x-dropdown-link :href="route('admin.satkers.index')">Satker</x-dropdown-link>
                                @endif
                                <x-dropdown-link :href="route('admin.tahuns.index')">Tahun</x-dropdown-link>
                                <x-dropdown-link :href="route('admin.ai_provider.edit')">Provider AI</x-dropdown-link>
                            </x-slot>
                        </x-dropdown>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Settings Dropdown -->
            <div class="hidden sm:flex sm:items-center sm:ms-6">
                <!-- Context Info -->
                @php
                    $satker = \App\Models\Satker::find(session('satker_id'));
                    $tahun = \App\Models\Tahun::find(session('tahun_id'));
                @endphp
                @if($satker && $tahun)
                <div class="text-xs text-gray-500 me-4 text-right">
                    <span class="font-bold text-gray-700">{{ $satker->kode }}</span> - {{ $satker->nama }} <br>
                    Tahun: <span class="font-bold text-gray-700">{{ $tahun->tahun }}</span>
                </div>
                @endif
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150">
                            <div>{{ Auth::user()->name }}</div>

                            <div class="ms-1">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile.edit')">
                            {{ __('Profile') }}
                        </x-dropdown-link>

                        <!-- Authentication -->
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf

                            <x-dropdown-link :href="route('logout')"
                                    onclick="event.preventDefault();
                                                this.closest('form').submit();">
                                {{ __('Log Out') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            <!-- Hamburger -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                {{ __('Dashboard') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('surat-tugas.index')" :active="request()->routeIs('surat-tugas.*') || request()->routeIs('spd.*')">
                {{ __('Surat Tugas & SPD') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('laporan.index')" :active="request()->routeIs('laporan.*')">
                {{ __('Laporan AI') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('hanya-laporan.index')" :active="request()->routeIs('hanya-laporan.*')">
                {{ __('Hanya Laporan') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('pegawais.index')" :active="request()->routeIs('pegawais.*')">
                {{ __('Data Pegawai') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('anggarans.index')" :active="request()->routeIs('anggarans.*')">
                {{ __('Data Anggaran') }}
            </x-responsive-nav-link>

            @if(in_array(auth()->user()->role, ['super', 'admin']))
            <div class="border-t border-gray-200 pt-2 pb-2 mt-2">
                <div class="px-4 text-xs font-semibold text-gray-500 uppercase">Master Data</div>
                <x-responsive-nav-link :href="route('admin.users.index')" :active="request()->routeIs('admin.users.*')">
                    Users
                </x-responsive-nav-link>
                @if(auth()->user()->role === 'super')
                <x-responsive-nav-link :href="route('admin.satkers.index')" :active="request()->routeIs('admin.satkers.*')">
                    Satker
                </x-responsive-nav-link>
                @endif
                <x-responsive-nav-link :href="route('admin.tahuns.index')" :active="request()->routeIs('admin.tahuns.*')">
                    Tahun
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('admin.ai_provider.edit')" :active="request()->routeIs('admin.ai_provider.*')">
                    Provider AI
                </x-responsive-nav-link>
            </div>
            @endif
        </div>

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-t border-gray-200">
            <div class="px-4">
                <div class="font-medium text-base text-gray-800">{{ Auth::user()->name }}</div>
                <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
            </div>

            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('profile.edit')">
                    {{ __('Profile') }}
                </x-responsive-nav-link>

                <!-- Authentication -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf

                    <x-responsive-nav-link :href="route('logout')"
                            onclick="event.preventDefault();
                                        this.closest('form').submit();">
                        {{ __('Log Out') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>
