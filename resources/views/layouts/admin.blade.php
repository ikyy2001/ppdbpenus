<!DOCTYPE html>
<html lang="id" class="h-full">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin Dashboard PPDB - SMK Plus Pelita Nusantara')</title>

    <!-- Favicon -->
    <link rel="icon" type="image/svg+xml" href="{{ asset('assets/favicon.svg') }}">

    <!-- Google Fonts: Oswald & Plus Jakarta Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Oswald:wght@500;600;700&family=Plus+Jakarta+Sans:ital,wght@0,400;0,500;0,600;0,700;1,400;1,500&display=swap"
        rel="stylesheet">

    <!-- Vite Styles & Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.14.8/dist/cdn.min.js"></script>

    <style>
        /* Strict No Border Radius & No Card Design Overrides */
        *,
        *::before,
        *::after {
            border-radius: 0px !important;
        }
    </style>
    @stack('styles')
</head>

<body x-data="{ sidebarOpen: false, profileDropdown: false, notifDropdown: false }"
    class="h-full bg-[#F3F2EE] text-brand-ink font-sans antialiased selection:bg-brand-darkred selection:text-white">
    <div class="min-h-screen flex flex-row">

        <!-- MOBILE SLIDE-OVER DRAWER -->
        <div x-show="sidebarOpen" x-cloak class="fixed inset-0 z-50 lg:hidden flex">
            <!-- Backdrop -->
            <div @click="sidebarOpen = false" class="fixed inset-0 bg-black/50 transition-opacity"></div>

            <!-- Drawer Sidebar -->
            <div
                class="relative w-64 bg-white border-r border-brand-ink/15 flex flex-col justify-between z-10 h-full select-none">
                <!-- Top Brand -->
                <div class="flex flex-col flex-1 overflow-y-auto">
                    <div class="h-16 px-5 border-b border-brand-ink/15 flex items-center justify-between bg-white">
                        <div class="flex items-center gap-2.5">
                            <div
                                class="w-8 h-8 bg-brand-darkred text-white flex items-center justify-center font-display font-bold text-sm shrink-0">
                                PPDB
                            </div>
                            <div>
                                <div class="font-display font-bold text-base uppercase tracking-wider text-brand-ink">
                                    PPDB ADMIN
                                </div>
                                <div class="text-[9px] font-semibold text-brand-ink/50 uppercase">
                                    SMK Plus Pelita Nusantara
                                </div>
                            </div>
                        </div>
                        <button @click="sidebarOpen = false"
                            class="p-1 text-brand-ink/60 hover:text-brand-ink">✕</button>
                    </div>

                    <!-- Navigation Items -->
                    <div class="py-4">
                        <div class="px-5 mb-2">
                            <span class="text-[10px] font-bold uppercase tracking-widest text-brand-ink/40">
                                MENU
                            </span>
                        </div>
                        <nav class="space-y-0.5">
                            <a href="{{ route('ppdb.dashboard') }}"
                                class="flex items-center gap-3 px-5 py-2.5 text-xs font-bold transition-colors border-l-4 {{ request()->routeIs('ppdb.dashboard') && !request()->routeIs('ppdb.dashboard.pendaftar*') ? 'bg-brand-darkred text-white border-brand-signal' : 'text-brand-ink/75 hover:bg-black/5' }}">
                                <span>Dashboard</span>
                            </a>
                            <a href="{{ route('ppdb.dashboard.pendaftar') }}"
                                class="flex items-center justify-between px-5 py-2.5 text-xs font-bold transition-colors border-l-4 {{ request()->routeIs('ppdb.dashboard.pendaftar*') ? 'bg-brand-darkred text-white border-brand-signal' : 'text-brand-ink/75 hover:bg-black/5' }}">
                                <span>Pendaftar Siswa</span>
                                <span class="text-[10px] font-mono px-1.5 py-0.5 bg-black/10 font-bold">
                                    {{ \App\Models\PpdbRegistration::count() }}
                                </span>
                            </a>
                            <a href="{{ route('ppdb.cek-status') }}" target="_blank"
                                class="flex items-center gap-3 px-5 py-2.5 text-xs font-bold text-brand-ink/75 hover:bg-black/5">
                                <span>Cek Status Siswa</span>
                            </a>
                            <a href="{{ route('ppdb.pengumuman') }}" target="_blank"
                                class="flex items-center gap-3 px-5 py-2.5 text-xs font-bold text-brand-ink/75 hover:bg-black/5">
                                <span>Pengumuman</span>
                            </a>
                            <a href="{{ route('ppdb.akomodasi') }}" target="_blank"
                                class="flex items-center gap-3 px-5 py-2.5 text-xs font-bold text-brand-ink/75 hover:bg-black/5">
                                <span>Biaya & Akomodasi</span>
                            </a>
                        </nav>
                    </div>
                </div>

                <!-- Bottom Links -->
                <div class="p-4 border-t border-brand-ink/15 space-y-2 bg-white">
                    <a href="{{ route('ppdb.index') }}" target="_blank"
                        class="block text-center py-2 border border-brand-ink/20 text-xs font-bold">
                        Form PPDB Publik &rarr;
                    </a>
                </div>
            </div>
        </div>

        <!-- LEFT SIDEBAR (PERMANENT STATIC / STICKY ON DESKTOP) -->
        <aside
            class="hidden lg:flex w-64 shrink-0 h-screen sticky top-0 bg-white border-r border-brand-ink/15 flex-col justify-between select-none z-20">
            <!-- TOP BRAND LOGO & MENU -->
            <div class="flex flex-col flex-1 overflow-y-auto">
                <!-- LOGO SECTION (Matches EduConnect logo header in reference) -->
                <div class="h-16 px-5 border-b border-brand-ink/15 flex items-center gap-3 bg-white">
                    <div class="min-w-0">
                        <div
                            class="font-display font-bold text-lg leading-tight uppercase tracking-wider text-brand-ink">
                            PPDB ADMIN
                        </div>
                        <div class="text-[10px] font-semibold text-brand-ink/50 uppercase tracking-tight truncate">
                            SMK Plus Pelita Nusantara
                        </div>
                    </div>
                </div>

                <!-- NAVIGATION ITEMS -->
                <div class="py-4">
                    <div class="px-5 mb-2">
                        <span class="text-[10px] font-bold uppercase tracking-widest text-brand-ink/40">
                            MENU
                        </span>
                    </div>

                    <nav class="space-y-0.5">
                        <!-- 1. DASHBOARD -->
                        <a href="{{ route('ppdb.dashboard') }}"
                            class="flex items-center gap-3 px-5 py-2.5 text-xs font-bold transition-colors border-l-4 {{ request()->routeIs('ppdb.dashboard') && !request()->routeIs('ppdb.dashboard.pendaftar*') ? 'bg-brand-darkred text-white border-brand-signal' : 'text-brand-ink/75 hover:bg-black/5 hover:text-brand-ink border-transparent' }}">
                            <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z">
                                </path>
                            </svg>
                            <span>Dashboard</span>
                        </a>

                        <!-- 2. DATA PENDAFTAR (PAGINATION) -->
                        <a href="{{ route('ppdb.dashboard.pendaftar') }}"
                            class="flex items-center justify-between px-5 py-2.5 text-xs font-bold transition-colors border-l-4 {{ request()->routeIs('ppdb.dashboard.pendaftar*') ? 'bg-brand-darkred text-white border-brand-signal' : 'text-brand-ink/75 hover:bg-black/5 hover:text-brand-ink border-transparent' }}">
                            <div class="flex items-center gap-3">
                                <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z">
                                    </path>
                                </svg>
                                <span>Pendaftar Siswa</span>
                            </div>
                            <span
                                class="text-[10px] font-mono px-1.5 py-0.5 {{ request()->routeIs('ppdb.dashboard.pendaftar*') ? 'bg-white/20 text-white' : 'bg-brand-darkred/10 text-brand-darkred' }} font-bold">
                                {{ \App\Models\PpdbRegistration::count() }}
                            </span>
                        </a>

                        <!-- 3. CEK STATUS NISN -->
                        <a href="{{ route('ppdb.cek-status') }}" target="_blank"
                            class="flex items-center gap-3 px-5 py-2.5 text-xs font-bold text-brand-ink/75 hover:bg-black/5 hover:text-brand-ink border-l-4 border-transparent transition-colors">
                            <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                            <span>Cek Status Siswa</span>
                        </a>

                        <!-- 4. PENGUMUMAN -->
                        <a href="{{ route('ppdb.pengumuman') }}" target="_blank"
                            class="flex items-center gap-3 px-5 py-2.5 text-xs font-bold text-brand-ink/75 hover:bg-black/5 hover:text-brand-ink border-l-4 border-transparent transition-colors">
                            <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z">
                                </path>
                            </svg>
                            <span>Pengumuman</span>
                        </a>

                        <!-- 5. AKOMODASI & BIAYA -->
                        <a href="{{ route('ppdb.akomodasi') }}" target="_blank"
                            class="flex items-center gap-3 px-5 py-2.5 text-xs font-bold text-brand-ink/75 hover:bg-black/5 hover:text-brand-ink border-l-4 border-transparent transition-colors">
                            <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                </path>
                            </svg>
                            <span>Biaya & Akomodasi</span>
                        </a>
                    </nav>
                </div>
            </div>

            <!-- BOTTOM ACTIONS -->
            <div class="p-4 border-t border-brand-ink/15 space-y-2 bg-white">
                <a href="{{ route('ppdb.index') }}" target="_blank"
                    class="flex items-center gap-2.5 px-3 py-2 text-xs font-bold text-brand-ink/80 hover:bg-black/5 hover:text-brand-ink border border-brand-ink/15 transition-all">
                    <svg class="w-4 h-4 text-brand-darkred shrink-0" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                    </svg>
                    <span>Form PPDB Publik</span>
                </a>

                <a href="{{ route('ppdb.index') }}"
                    class="flex items-center gap-2.5 px-3 py-2 text-xs font-bold text-red-700 hover:bg-red-50 border border-red-200 transition-all">
                    <svg class="w-4 h-4 shrink-0 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1">
                        </path>
                    </svg>
                    <span>Logout Panitia</span>
                </a>
            </div>
        </aside>

        <!-- RIGHT MAIN WRAPPER (OCCUPIES ALL REMAINING SPACE CLEANLY) -->
        <div class="flex-1 flex flex-col min-w-0">

            <!-- TOPBAR (Matches top header in reference: Title, Search, Notification, Profile) -->
            <header
                class="h-16 bg-white border-b border-brand-ink/15 px-4 sm:px-6 flex items-center justify-between sticky top-0 z-30">
                <div class="flex items-center gap-3 min-w-0">
                    <!-- Mobile Hamburger -->
                    <button @click="sidebarOpen = !sidebarOpen"
                        class="p-2 border border-brand-ink/20 lg:hidden text-brand-ink hover:bg-black/5 cursor-pointer">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 6h16M4 12h16M4 18h16"></path>
                        </svg>
                    </button>

                    <!-- Page Title / Eyebrow -->
                    <div>
                        <h1
                            class="font-display font-bold text-xl sm:text-2xl uppercase tracking-wider text-brand-ink truncate leading-tight">
                            @yield('page_title', 'Dashboard PPDB')
                        </h1>
                    </div>
                </div>

                <!-- RIGHT HEADER ACTIONS -->
                <div class="flex items-center gap-2 sm:gap-4">
                    <!-- Quick Search Link/Input -->
                    <a href="{{ route('ppdb.dashboard.pendaftar') }}"
                        class="hidden md:flex items-center gap-2 px-3 py-1.5 border border-brand-ink/20 text-brand-ink/60 hover:text-brand-ink hover:border-brand-darkred text-xs transition-colors">
                        <svg class="w-4 h-4 text-brand-ink/60" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                        <span>Cari Siswa...</span>
                        <kbd class="font-mono text-[10px] bg-black/5 px-1.5 py-0.5 border border-black/10">⌘K</kbd>
                    </a>

                    <!-- Notification Button -->
                    <div class="relative">
                        <button @click="notifDropdown = !notifDropdown"
                            class="w-9 h-9 border border-brand-ink/20 flex items-center justify-center text-brand-ink hover:bg-black/5 cursor-pointer relative">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9">
                                </path>
                            </svg>
                        </button>

                        <!-- Notification Dropdown -->
                        <div x-show="notifDropdown" x-cloak @click.away="notifDropdown = false"
                            class="absolute right-0 mt-1 w-72 bg-white border border-brand-ink/20 shadow-xl z-50 p-3 text-xs">
                            <div
                                class="font-bold border-b border-brand-ink/10 pb-2 mb-2 flex items-center justify-between">
                                <span>Notifikasi PPDB</span>
                                <span class="text-[10px] text-brand-darkred font-mono uppercase">Live</span>
                            </div>
                            <div class="space-y-2">
                                <div class="p-2 bg-brand-darkred/5 border-l-2 border-brand-darkred">
                                    <p class="font-bold text-[11px] text-brand-darkred">Pendaftar Baru Masuk</p>
                                    <p class="text-[11px] text-brand-ink/70">Pendaftar baru siap untuk diverifikasi
                                        panitia.</p>
                                </div>
                                <div class="p-2 bg-black/5 border-l-2 border-brand-ink/30">
                                    <p class="font-bold text-[11px] text-brand-ink">T.A 2027/2028 Aktif</p>
                                    <p class="text-[11px] text-brand-ink/70">Penerimaan calon siswa baru jalur reguler
                                        dibuka.</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Profile Widget (Matches Sarah Jenkins in reference) -->
                    <div class="relative">
                        <button @click="profileDropdown = !profileDropdown"
                            class="flex items-center gap-2.5 p-1 sm:px-2 sm:py-1 border border-brand-ink/20 hover:border-brand-darkred bg-white cursor-pointer transition-colors">
                            <div
                                class="w-8 h-8 bg-brand-darkred border rounded-full text-white flex items-center justify-center font-bold text-xs">
                                PA
                            </div>
                            <div class="hidden sm:block text-left">
                                <div class="text-xs font-bold text-brand-ink leading-tight">Panitia PPDB</div>
                                <div class="text-[10px] text-brand-ink/60 leading-tight">Administrator</div>
                            </div>
                            <svg class="w-3.5 h-3.5 text-brand-ink/60" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>

                        <!-- Profile Dropdown -->
                        <div x-show="profileDropdown" x-cloak @click.away="profileDropdown = false"
                            class="absolute right-0 mt-1 w-48 bg-white border border-brand-ink/20 shadow-xl z-50 py-1 text-xs">
                            <div class="px-4 py-2 border-b border-brand-ink/10">
                                <div class="font-bold text-brand-ink">Panitia PPDB 2027</div>
                                <div class="text-[10px] text-brand-ink/60">admin@smkpluspelitanusantara.sch.id</div>
                            </div>
                            <a href="{{ route('ppdb.dashboard') }}"
                                class="block px-4 py-2 hover:bg-black/5 font-semibold">
                                Dashboard Utama
                            </a>
                            <a href="{{ route('ppdb.dashboard.pendaftar') }}"
                                class="block px-4 py-2 hover:bg-black/5 font-semibold">
                                Kelola Pendaftar
                            </a>
                            <div class="border-t border-brand-ink/10 my-1"></div>
                            <a href="{{ route('ppdb.index') }}"
                                class="block px-4 py-2 text-red-700 hover:bg-red-50 font-bold">
                                Keluar Mode Admin
                            </a>
                        </div>
                    </div>
                </div>
            </header>

            <!-- FLASH ALERTS / NOTICES -->
            @if (session('success'))
                <div
                    class="bg-emerald-600 text-white px-4 sm:px-6 py-2.5 text-xs font-bold flex items-center justify-between border-b border-emerald-700">
                    <div class="flex items-center gap-2">
                        <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        <span>{{ session('success') }}</span>
                    </div>
                    <button type="button" onclick="this.parentElement.remove()"
                        class="text-white hover:text-white/80 cursor-pointer font-bold">✕</button>
                </div>
            @endif

            @if (session('error'))
                <div
                    class="bg-red-600 text-white px-4 sm:px-6 py-2.5 text-xs font-bold flex items-center justify-between border-b border-red-700">
                    <div class="flex items-center gap-2">
                        <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0114 0z"></path>
                        </svg>
                        <span>{{ session('error') }}</span>
                    </div>
                    <button type="button" onclick="this.parentElement.remove()"
                        class="text-white hover:text-white/80 cursor-pointer font-bold">✕</button>
                </div>
            @endif

            @if (isset($errors) && $errors->any())
                <div class="bg-amber-500 text-brand-ink px-4 sm:px-6 py-2.5 text-xs font-bold border-b border-amber-600">
                    <p class="font-bold mb-1">Terdapat kesalahan dalam pengisian formulir:</p>
                    <ul class="list-disc list-inside font-medium text-[11px]">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- MAIN BODY CONTENT -->
            <main class="flex-1 p-3 sm:p-5">
                @yield('content')
            </main>

            <!-- FOOTER INFO STRIP -->
            <footer
                class="bg-white border-t border-brand-ink/15 px-4 sm:px-6 py-3 flex flex-col sm:flex-row items-center justify-between gap-2 text-[11px] text-brand-ink/60">
                <div>
                    <strong>Sistem Informasi PPDB</strong> &copy; {{ date('Y') }} SMK Plus Pelita Nusantara Bogor. Hak
                    Cipta Dilindungi.
                </div>
                <div class="flex items-center gap-4 font-mono text-[10px]">
                    <span>STATUS: ONLINE</span>
                    <span class="text-brand-ink/30">|</span>
                    <span>T.A 2027/2028</span>
                </div>
            </footer>

        </div>
    </div>

    @stack('scripts')
</body>

</html>