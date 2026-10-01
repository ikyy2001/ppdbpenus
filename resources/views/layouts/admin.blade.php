<!DOCTYPE html>
<html lang="id" class="h-full">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin Dashboard PPDB - SMK Plus Pelita Nusantara')</title>

    <!-- Favicon -->
    <link rel="icon" type="image/svg+xml" href="{{ asset('assets/favicon.svg') }}">

    <!-- Google Fonts: Plus Jakarta Sans & Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;1,400;1,600&family=Inter:wght@400;500;600;700&display=swap"
        rel="stylesheet">

    <!-- Vite Styles & Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.14.8/dist/cdn.min.js"></script>

    <style>
        /* Modern Clean Admin Dashboard Design System */
        :root {
            --admin-bg: #F8F9FA;
            --admin-card: #FFFFFF;
            --admin-border: #E5E7EB;
            --admin-primary: #1E293B;
            --admin-accent: #8B1D24;
            --admin-title: #0F172A;
            --admin-body: #64748B;
        }

        body.admin-body {
            font-family: 'Plus Jakarta Sans', 'Inter', system-ui, -apple-system, sans-serif !important;
            background-color: #F8F9FA !important;
            color: #0F172A !important;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }

        /* Headings in admin use modern Sans-serif with tight tracking */
        .admin-body h1, 
        .admin-body h2, 
        .admin-body h3, 
        .admin-body h4, 
        .admin-body h5, 
        .admin-body h6 {
            font-family: 'Plus Jakarta Sans', 'Inter', system-ui, -apple-system, sans-serif !important;
            letter-spacing: -0.02em !important;
        }

        /* Custom soft scrollbar */
        ::-webkit-scrollbar {
            width: 6px;
            height: 6px;
        }
        ::-webkit-scrollbar-track {
            background: #F1F5F9;
        }
        ::-webkit-scrollbar-thumb {
            background: #CBD5E1;
            border-radius: 9999px;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: #94A3B8;
        }
    </style>
    @stack('styles')
</head>

<body x-data="{ sidebarOpen: false, profileDropdown: false, notifDropdown: false }"
    class="admin-body h-full bg-[#F8F9FA] text-[#0F172A] font-sans antialiased selection:bg-[#8B1D24] selection:text-white">
@php
    $authUser = $authUser ?? request()->auth_user ?? request()->attributes->get('auth_user') ?? [];
    $userName = $authUser['nama_lengkap'] ?? $authUser['username'] ?? 'Panitia PPDB';
    $userRole = strtoupper((string) ($authUser['role'] ?? 'ADMINISTRATOR'));
    $userEmail = $authUser['email'] ?? (!empty($authUser['username']) ? $authUser['username'] . '@sekolah.sch.id' : 'admin@smkpluspelitanusantara.sch.id');
    
    // Inisial untuk avatar
    $nameParts = preg_split('/\s+/', trim((string) $userName));
    $initials = '';
    foreach (array_slice($nameParts, 0, 2) as $part) {
        $initials .= strtoupper(substr($part, 0, 1));
    }
    $initials = $initials ?: 'PA';
@endphp
    <div class="min-h-screen flex flex-row">

        <!-- MOBILE SLIDE-OVER DRAWER -->
        <div x-show="sidebarOpen" x-cloak class="fixed inset-0 z-50 lg:hidden flex"
             x-transition:enter="transition-opacity ease-linear duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition-opacity ease-linear duration-300"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0">
            <!-- Backdrop -->
            <div @click="sidebarOpen = false" class="fixed inset-0 bg-slate-900/60 backdrop-blur-xs transition-opacity"></div>

            <!-- Drawer Sidebar -->
            <div
                class="relative w-72 bg-white border-r border-slate-200 flex flex-col justify-between z-10 h-full select-none shadow-2xl"
                x-transition:enter="transition ease-in-out duration-300 transform"
                x-transition:enter-start="-translate-x-full"
                x-transition:enter-end="translate-x-0"
                x-transition:leave="transition ease-in-out duration-300 transform"
                x-transition:leave-start="translate-x-0"
                x-transition:leave-end="-translate-x-full">
                <!-- Top Brand -->
                <div class="flex flex-col flex-1 overflow-y-auto">
                    <div class="h-18 px-5 border-b border-slate-100 flex items-center justify-between bg-white">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-2xl bg-[#1E293B] text-white flex items-center justify-center font-bold text-base shadow-sm shrink-0">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"></path>
                                </svg>
                            </div>
                            <div>
                                <div class="font-extrabold text-base tracking-tight text-[#0F172A] leading-snug">
                                    PPDB Penus
                                </div>
                                <div class="text-[10px] font-medium text-[#64748B]">
                                    SMK Plus Pelita Nusantara
                                </div>
                            </div>
                        </div>
                        <button @click="sidebarOpen = false"
                            class="w-8 h-8 rounded-xl bg-slate-100 text-slate-500 hover:text-slate-800 hover:bg-slate-200 flex items-center justify-center transition-colors">✕</button>
                    </div>

                    <!-- Navigation Items -->
                    <div class="p-4 space-y-6">
                        <div>
                            <div class="px-3 mb-2">
                                <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400">
                                    MANAJEMEN PPDB
                                </span>
                            </div>
                            <nav class="space-y-1">
                                <a href="{{ route('ppdb.dashboard') }}"
                                    class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs font-semibold transition-all {{ request()->routeIs('ppdb.dashboard') && !request()->routeIs('ppdb.dashboard.pendaftar*') && !request()->routeIs('ppdb.dashboard.gelombang*') && !request()->routeIs('ppdb.dashboard.pengumuman*') && !request()->routeIs('ppdb.dashboard.akomodasi*') ? 'bg-[#1E293B] text-white shadow-sm' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900' }}">
                                    <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path>
                                    </svg>
                                    <span>Dashboard</span>
                                </a>
                                <a href="{{ route('ppdb.dashboard.pendaftar') }}"
                                    class="flex items-center justify-between px-3.5 py-2.5 rounded-xl text-xs font-semibold transition-all {{ request()->routeIs('ppdb.dashboard.pendaftar*') ? 'bg-[#1E293B] text-white shadow-sm' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900' }}">
                                    <div class="flex items-center gap-3">
                                        <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                                        </svg>
                                        <span>Pendaftar Siswa</span>
                                    </div>
                                    <span class="text-[10px] font-bold px-2 py-0.5 rounded-full {{ request()->routeIs('ppdb.dashboard.pendaftar*') ? 'bg-white/20 text-white' : 'bg-red-50 text-[#8B1D24]' }}">
                                        {{ \App\Models\PpdbRegistration::count() }}
                                    </span>
                                </a>
                                <a href="{{ route('ppdb.dashboard.gelombang') }}"
                                    class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs font-semibold transition-all {{ request()->routeIs('ppdb.dashboard.gelombang*') ? 'bg-[#1E293B] text-white shadow-sm' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900' }}">
                                    <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                    <span>Gelombang PPDB</span>
                                </a>
                                <a href="{{ route('ppdb.dashboard.pengumuman') }}"
                                    class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs font-semibold transition-all {{ request()->routeIs('ppdb.dashboard.pengumuman*') ? 'bg-[#1E293B] text-white shadow-sm' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900' }}">
                                    <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"></path>
                                    </svg>
                                    <span>Manajemen Pengumuman</span>
                                </a>
                                <a href="{{ route('ppdb.dashboard.akomodasi') }}"
                                    class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs font-semibold transition-all {{ request()->routeIs('ppdb.dashboard.akomodasi*') ? 'bg-[#1E293B] text-white shadow-sm' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900' }}">
                                    <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                    </svg>
                                    <span>Biaya & Akomodasi</span>
                                </a>
                            </nav>
                        </div>
                    </div>
                </div>

                <!-- Bottom Links / Widget Card -->
                <div class="p-4 border-t border-slate-100 space-y-3 bg-white">
                    <div class="rounded-2xl bg-gradient-to-br from-[#1E293B] to-[#0F172A] p-4 text-white shadow-md relative overflow-hidden">
                        <div class="flex items-center gap-1.5 mb-1.5">
                            <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                            <span class="text-[10px] font-bold uppercase tracking-wider text-slate-300">PPDB T.A 2027/2028</span>
                        </div>
                        <div class="text-xs font-bold text-white mb-2.5">
                            Penerimaan Siswa Aktif
                        </div>
                        <a href="{{ route('ppdb.index') }}" target="_blank"
                            class="block text-center py-2 px-3 rounded-xl bg-white/10 hover:bg-white/20 text-xs font-semibold text-white transition-colors">
                            Form PPDB Publik ↗
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- LEFT SIDEBAR (PERMANENT STICKY ON DESKTOP) -->
        <aside
            class="hidden lg:flex w-68 shrink-0 h-screen sticky top-0 bg-white border-r border-slate-200/80 flex-col justify-between select-none z-20">
            <!-- TOP BRAND LOGO & MENU -->
            <div class="flex flex-col flex-1 overflow-y-auto">
                <!-- LOGO SECTION -->
                <div class="h-18 px-5 border-b border-slate-100 flex items-center gap-3 bg-white">
                    <div class="w-10 h-10 rounded-2xl bg-[#1E293B] text-white flex items-center justify-center font-bold text-base shadow-sm shrink-0">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"></path>
                        </svg>
                    </div>
                    <div class="min-w-0">
                        <div class="font-extrabold text-base leading-tight tracking-tight text-[#0F172A] truncate">
                            PPDB Penus
                        </div>
                        <div class="text-[10px] font-medium text-[#64748B] truncate">
                            SMK Plus Pelita Nusantara
                        </div>
                    </div>
                </div>

                <!-- NAVIGATION ITEMS -->
                <div class="p-4 space-y-6">
                    <!-- SECTION 1: MANAJEMEN PPDB -->
                    <div>
                        <div class="px-3 mb-2">
                            <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400">
                                MANAJEMEN PPDB
                            </span>
                        </div>

                        <nav class="space-y-1">
                            <!-- 1. DASHBOARD -->
                            <a href="{{ route('ppdb.dashboard') }}"
                                class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs font-semibold transition-all {{ request()->routeIs('ppdb.dashboard') && !request()->routeIs('ppdb.dashboard.pendaftar*') && !request()->routeIs('ppdb.dashboard.gelombang*') && !request()->routeIs('ppdb.dashboard.pengumuman*') && !request()->routeIs('ppdb.dashboard.akomodasi*') ? 'bg-[#1E293B] text-white shadow-sm' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900' }}">
                                <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z">
                                    </path>
                                </svg>
                                <span>Dashboard</span>
                            </a>

                            <!-- 2. DATA PENDAFTAR -->
                            <a href="{{ route('ppdb.dashboard.pendaftar') }}"
                                class="flex items-center justify-between px-3.5 py-2.5 rounded-xl text-xs font-semibold transition-all {{ request()->routeIs('ppdb.dashboard.pendaftar*') ? 'bg-[#1E293B] text-white shadow-sm' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900' }}">
                                <div class="flex items-center gap-3">
                                    <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z">
                                        </path>
                                    </svg>
                                    <span>Pendaftar Siswa</span>
                                </div>
                                <span
                                    class="text-[10px] font-bold px-2 py-0.5 rounded-full {{ request()->routeIs('ppdb.dashboard.pendaftar*') ? 'bg-white/20 text-white' : 'bg-red-50 text-[#8B1D24]' }}">
                                    {{ \App\Models\PpdbRegistration::count() }}
                                </span>
                            </a>

                            <!-- 3. KELOLA GELOMBANG -->
                            <a href="{{ route('ppdb.dashboard.gelombang') }}"
                                class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs font-semibold transition-all {{ request()->routeIs('ppdb.dashboard.gelombang*') ? 'bg-[#1E293B] text-white shadow-sm' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900' }}">
                                <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                                <span>Gelombang PPDB</span>
                            </a>

                            <!-- 4. KELOLA PENGUMUMAN -->
                            <a href="{{ route('ppdb.dashboard.pengumuman') }}"
                                class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs font-semibold transition-all {{ request()->routeIs('ppdb.dashboard.pengumuman*') ? 'bg-[#1E293B] text-white shadow-sm' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900' }}">
                                <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"></path>
                                </svg>
                                <span>Manajemen Pengumuman</span>
                            </a>

                            <!-- 5. BIAYA & AKOMODASI -->
                            <a href="{{ route('ppdb.dashboard.akomodasi') }}"
                                class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs font-semibold transition-all {{ request()->routeIs('ppdb.dashboard.akomodasi*') ? 'bg-[#1E293B] text-white shadow-sm' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900' }}">
                                <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                </svg>
                                <span>Biaya & Akomodasi</span>
                            </a>
                        </nav>
                    </div>

                    <!-- SECTION 2: PORTAL & INFORMASI -->
                    <div>
                        <div class="px-3 mb-2">
                            <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400">
                                PORTAL & INFORMASI
                            </span>
                        </div>

                        <nav class="space-y-1">
                            <!-- 3. CEK STATUS NISN -->
                            <a href="{{ route('ppdb.cek-status') }}" target="_blank"
                                class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs font-semibold text-slate-600 hover:bg-slate-100 hover:text-slate-900 transition-colors">
                                <svg class="w-4 h-4 text-slate-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                </svg>
                                <span>Cek Status Siswa</span>
                            </a>

                            <!-- 4. PENGUMUMAN -->
                            <a href="{{ route('ppdb.pengumuman') }}" target="_blank"
                                class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs font-semibold text-slate-600 hover:bg-slate-100 hover:text-slate-900 transition-colors">
                                <svg class="w-4 h-4 text-slate-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z">
                                    </path>
                                </svg>
                                <span>Pengumuman</span>
                            </a>

                            <!-- 5. AKOMODASI & BIAYA -->
                            <a href="{{ route('ppdb.akomodasi') }}" target="_blank"
                                class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs font-semibold text-slate-600 hover:bg-slate-100 hover:text-slate-900 transition-colors">
                                <svg class="w-4 h-4 text-slate-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                    </path>
                                </svg>
                                <span>Biaya & Akomodasi</span>
                            </a>
                        </nav>
                    </div>
                </div>
            </div>

            <!-- BOTTOM CARD WIDGET (Like Status PKL card in reference) -->
            <div class="p-4 border-t border-slate-100 space-y-3 bg-white">
                <div class="rounded-2xl bg-gradient-to-br from-[#1E293B] to-[#0F172A] p-4 text-white shadow-md relative overflow-hidden">
                    <div class="flex items-center gap-1.5 mb-1.5">
                        <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                        <span class="text-[10px] font-bold uppercase tracking-wider text-slate-300">PPDB T.A 2027/2028</span>
                    </div>
                    <div class="text-xs font-bold text-white mb-2.5">
                        Penerimaan Siswa Aktif
                    </div>
                    <a href="{{ route('ppdb.index') }}" target="_blank"
                        class="block text-center py-2 px-3 rounded-xl bg-white/10 hover:bg-white/20 text-xs font-semibold text-white transition-colors">
                        Form PPDB Publik ↗
                    </a>
                </div>

                <form action="{{ route('ppdb.logout') }}" method="POST">
                    @csrf
                    <button type="submit"
                        class="w-full flex items-center justify-center gap-2 px-3 py-2.5 rounded-xl text-xs font-bold text-red-600 hover:bg-red-50 hover:text-red-700 transition-colors cursor-pointer border border-transparent hover:border-red-100">
                        <svg class="w-4 h-4 shrink-0 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1">
                            </path>
                        </svg>
                        <span>Keluar Sesi Admin</span>
                    </button>
                </form>
            </div>
        </aside>

        <!-- RIGHT MAIN WRAPPER -->
        <div class="flex-1 flex flex-col min-w-0">

            <!-- TOPBAR (Matches top header in reference: Title, Search, Status Pill, Notification, Profile) -->
            <header
                class="h-18 bg-white border-b border-slate-200/80 px-4 sm:px-6 flex items-center justify-between sticky top-0 z-30">
                <div class="flex items-center gap-3 min-w-0">
                    <!-- Mobile Hamburger -->
                    <button @click="sidebarOpen = !sidebarOpen"
                        class="p-2 rounded-xl border border-slate-200 lg:hidden text-slate-700 hover:bg-slate-100 cursor-pointer transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 6h16M4 12h16M4 18h16"></path>
                        </svg>
                    </button>

                    <!-- Page Title -->
                    <div>
                        <h1
                            class="font-extrabold text-lg sm:text-xl tracking-tight text-[#0F172A] truncate leading-tight">
                            @yield('page_title', 'Dashboard PPDB')
                        </h1>
                    </div>
                </div>

                <!-- CENTER QUICK SEARCH (Matches search bar in reference image) -->
                <div class="hidden md:flex flex-1 max-w-md mx-6">
                    <a href="{{ route('ppdb.dashboard.pendaftar') }}"
                        class="w-full flex items-center gap-2.5 px-4 py-2 rounded-full bg-[#F1F5F9] hover:bg-slate-100 text-slate-500 hover:text-slate-800 text-xs transition-colors border border-slate-200/50">
                        <svg class="w-4 h-4 text-slate-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                        <span class="truncate">Cari pendaftar, no registrasi, NISN, atau sekolah...</span>
                        <kbd class="font-sans text-[10px] bg-white text-slate-500 px-2 py-0.5 rounded-full border border-slate-200 shadow-2xs font-semibold ml-auto shrink-0">⌘K</kbd>
                    </a>
                </div>

                <!-- RIGHT HEADER ACTIONS -->
                <div class="flex items-center gap-2.5 sm:gap-3 shrink-0">
                    <!-- Status Pill (Matches [Siswa PKL] style pill in reference) -->
                    <div class="hidden sm:flex items-center gap-1.5 px-3 py-1 rounded-full bg-[#1E293B] text-white text-[11px] font-semibold shadow-xs">
                        <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                        <span>[Panitia PPDB]</span>
                    </div>

                    <!-- Notification Button -->
                    <div class="relative">
                        <button @click="notifDropdown = !notifDropdown"
                            class="w-9 h-9 rounded-full bg-slate-100 hover:bg-slate-200 flex items-center justify-center text-slate-600 transition-colors cursor-pointer relative">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9">
                                </path>
                            </svg>
                            <!-- Notification Red Dot -->
                            <span class="absolute top-1.5 right-1.5 w-2 h-2 rounded-full bg-[#8B1D24]"></span>
                        </button>

                        <!-- Notification Dropdown -->
                        <div x-show="notifDropdown" x-cloak @click.away="notifDropdown = false"
                            class="absolute right-0 mt-2 w-80 bg-white rounded-2xl border border-slate-100 shadow-xl z-50 p-4 text-xs"
                            x-transition:enter="transition ease-out duration-150"
                            x-transition:enter-start="opacity-0 scale-95"
                            x-transition:enter-end="opacity-100 scale-100">
                            <div class="font-bold border-b border-slate-100 pb-2.5 mb-2.5 flex items-center justify-between text-slate-900">
                                <span>Notifikasi PPDB</span>
                                <span class="text-[10px] text-[#8B1D24] bg-red-50 px-2 py-0.5 rounded-full font-bold uppercase">Live</span>
                            </div>
                            <div class="space-y-2">
                                <div class="p-2.5 rounded-xl bg-red-50/60 border border-red-100">
                                    <p class="font-bold text-[11px] text-[#8B1D24]">Pendaftar Baru Masuk</p>
                                    <p class="text-[11px] text-slate-600 mt-0.5">Pendaftar baru siap untuk diverifikasi oleh panitia.</p>
                                </div>
                                <div class="p-2.5 rounded-xl bg-slate-50 border border-slate-100">
                                    <p class="font-bold text-[11px] text-slate-800">T.A 2027/2028 Aktif</p>
                                    <p class="text-[11px] text-slate-600 mt-0.5">Penerimaan calon siswa baru jalur reguler & prestasi dibuka.</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Profile Avatar Widget (Matches circular AR avatar in reference) -->
                    <div class="relative">
                        <button @click="profileDropdown = !profileDropdown"
                            class="flex items-center gap-2 p-1 sm:px-2.5 sm:py-1.5 rounded-full hover:bg-slate-100 bg-white cursor-pointer transition-colors border border-slate-200/60">
                            @if (!empty($authUser['foto_profil']))
                                <img src="{{ $authUser['foto_profil'] }}" alt="{{ $userName }}" class="w-8 h-8 rounded-full object-cover shadow-xs border border-slate-200">
                            @else
                                <div
                                    class="w-8 h-8 rounded-full bg-[#1E293B] text-white flex items-center justify-center font-bold text-xs shadow-xs">
                                    {{ $initials }}
                                </div>
                            @endif
                            <div class="hidden sm:block text-left">
                                <div class="text-xs font-bold text-[#0F172A] leading-tight">{{ $userName }}</div>
                                <div class="text-[10px] text-[#64748B] leading-tight font-medium">{{ $userRole }}</div>
                            </div>
                            <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>

                        <!-- Profile Dropdown -->
                        <div x-show="profileDropdown" x-cloak @click.away="profileDropdown = false"
                            class="absolute right-0 mt-2 w-56 bg-white rounded-2xl border border-slate-100 shadow-xl z-50 p-2 text-xs"
                            x-transition:enter="transition ease-out duration-150"
                            x-transition:enter-start="opacity-0 scale-95"
                            x-transition:enter-end="opacity-100 scale-100">
                            <div class="px-3 py-2.5 border-b border-slate-100 mb-1">
                                <div class="font-bold text-slate-900 truncate">{{ $userName }}</div>
                                <div class="text-[10px] text-slate-500 truncate">{{ $userEmail }}</div>
                                <span class="inline-block mt-1 px-1.5 py-0.5 rounded text-[9px] font-bold bg-slate-100 text-slate-700 tracking-wider">{{ $userRole }}</span>
                            </div>
                            <a href="{{ route('ppdb.dashboard') }}"
                                class="flex items-center gap-2 px-3 py-2 rounded-xl hover:bg-slate-100 text-slate-700 font-medium transition-colors">
                                <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                                </svg>
                                <span>Dashboard Utama</span>
                            </a>
                            <a href="{{ route('ppdb.dashboard.pendaftar') }}"
                                class="flex items-center gap-2 px-3 py-2 rounded-xl hover:bg-slate-100 text-slate-700 font-medium transition-colors">
                                <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                                </svg>
                                <span>Kelola Pendaftar</span>
                            </a>
                            <div class="border-t border-slate-100 my-1"></div>
                            <form action="{{ route('ppdb.logout') }}" method="POST">
                                @csrf
                                <button type="submit"
                                    class="w-full flex items-center gap-2 px-3 py-2 rounded-xl text-red-700 hover:bg-red-50 font-bold transition-colors cursor-pointer text-left">
                                    <svg class="w-4 h-4 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                                    </svg>
                                    <span>Keluar Sesi Admin</span>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </header>

            <!-- FLASH ALERTS / NOTICES -->
            <div class="px-4 sm:px-6 pt-4 space-y-3">
                @if (session('success'))
                    <div
                        class="bg-emerald-50 border border-emerald-200 text-emerald-900 rounded-2xl px-5 py-3.5 text-xs font-semibold flex items-center justify-between shadow-xs">
                        <div class="flex items-center gap-2.5">
                            <span class="w-6 h-6 rounded-full bg-emerald-100 text-emerald-700 flex items-center justify-center shrink-0">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path>
                                </svg>
                            </span>
                            <span>{{ session('success') }}</span>
                        </div>
                        <button type="button" onclick="this.closest('.bg-emerald-50').remove()"
                            class="text-emerald-700 hover:text-emerald-900 cursor-pointer font-bold p-1">✕</button>
                    </div>
                @endif

                @if (session('error'))
                    <div
                        class="bg-red-50 border border-red-200 text-red-900 rounded-2xl px-5 py-3.5 text-xs font-semibold flex items-center justify-between shadow-xs">
                        <div class="flex items-center gap-2.5">
                            <span class="w-6 h-6 rounded-full bg-red-100 text-red-700 flex items-center justify-center shrink-0">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                        d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0114 0z"></path>
                                </svg>
                            </span>
                            <span>{{ session('error') }}</span>
                        </div>
                        <button type="button" onclick="this.closest('.bg-red-50').remove()"
                            class="text-red-700 hover:text-red-900 cursor-pointer font-bold p-1">✕</button>
                    </div>
                @endif

                @if (isset($errors) && $errors->any())
                    <div class="bg-amber-50 border border-amber-200 text-amber-900 rounded-2xl px-5 py-3.5 text-xs font-semibold shadow-xs">
                        <p class="font-bold mb-1 flex items-center gap-2">
                            <span class="w-5 h-5 rounded-full bg-amber-200 text-amber-800 flex items-center justify-center text-[11px]">!</span>
                            Terdapat kesalahan dalam pengisian formulir:
                        </p>
                        <ul class="list-disc list-inside font-medium text-[11px] ml-6 space-y-0.5">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
            </div>

            <!-- MAIN BODY CONTENT -->
            <main class="flex-1 p-4 sm:p-6">
                @yield('content')
            </main>

            <!-- FOOTER INFO STRIP -->
            <footer
                class="bg-white/80 border-t border-slate-200/80 px-6 py-4 flex flex-col sm:flex-row items-center justify-between gap-3 text-xs text-slate-500">
                <div>
                    <span class="font-bold text-slate-700">Sistem Informasi PPDB</span> &copy; {{ date('Y') }} SMK Plus Pelita Nusantara Bogor. Hak Cipta Dilindungi.
                </div>
                <div class="flex items-center gap-3 text-[11px] font-medium text-slate-400">
                    <span class="flex items-center gap-1.5">
                        <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                        <span class="text-slate-600 font-semibold">STATUS: ONLINE</span>
                    </span>
                    <span>•</span>
                    <span>T.A 2027/2028</span>
                </div>
            </footer>

        </div>
    </div>

    @stack('scripts')
</body>

</html>