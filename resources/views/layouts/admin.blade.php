<!DOCTYPE html>
<html lang="id" class="h-full">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin Dashboard PPDB - SMK Plus Pelita Nusantara')</title>
    <link rel="icon" type="image/svg+xml" href="{{ asset('assets/favicon.svg') }}">

    <!-- Tipografi landing page: Oswald (display) & Plus Jakarta Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Oswald:wght@700&family=Plus+Jakarta+Sans:ital,wght@0,400;0,500;0,600;0,700;1,400&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.14.8/dist/cdn.min.js"></script>
    <!-- Lucide Icons (versi dikunci) -->
    <script src="https://unpkg.com/lucide@0.468.0/dist/umd/lucide.min.js"></script>

    <style>
        [x-cloak] { display: none !important; }

        body.admin-body {
            background-color: #F5F4F2;
            color: #241012;
        }

        ::-webkit-scrollbar { width: 6px; height: 6px; }
        ::-webkit-scrollbar-track { background: #F5F4F2; }
        ::-webkit-scrollbar-thumb { background: #DDDDDD; border-radius: 9999px; }
        ::-webkit-scrollbar-thumb:hover { background: #A66B6E; }
    </style>
    @stack('styles')
</head>

@php
    $authUser = $authUser ?? request()->auth_user ?? request()->attributes->get('auth_user') ?? [];
    $userName = $authUser['nama_lengkap'] ?? $authUser['username'] ?? 'Panitia PPDB';
    $userRole = str_replace('_', ' ', strtoupper((string) ($authUser['role'] ?? 'ADMIN')));
    $userEmail = $authUser['email'] ?? '-';
    $initials = collect(preg_split('/\s+/', trim((string) $userName)))->take(2)
        ->map(fn ($part) => mb_strtoupper(mb_substr($part, 0, 1)))->implode('') ?: 'PA';

    $unverifiedCount ??= 0;
    $recentRegistrations ??= collect();
    $sharedTotalPendaftar ??= 0;

    $navItems = [
        ['label' => 'Dashboard', 'icon' => 'layout-dashboard', 'route' => 'ppdb.dashboard', 'active' => fn () => request()->routeIs('ppdb.dashboard')],
        ['label' => 'Pendaftar Siswa', 'icon' => 'users', 'route' => 'ppdb.dashboard.pendaftar', 'active' => fn () => request()->routeIs('ppdb.dashboard.pendaftar*'), 'badge' => $unverifiedCount],
        ['label' => 'Gelombang PPDB', 'icon' => 'calendar-range', 'route' => 'ppdb.dashboard.gelombang', 'active' => fn () => request()->routeIs('ppdb.dashboard.gelombang*')],
        ['label' => 'Pengumuman', 'icon' => 'megaphone', 'route' => 'ppdb.dashboard.pengumuman', 'active' => fn () => request()->routeIs('ppdb.dashboard.pengumuman*')],
        ['label' => 'Biaya & Akomodasi', 'icon' => 'wallet', 'route' => 'ppdb.dashboard.akomodasi', 'active' => fn () => request()->routeIs('ppdb.dashboard.akomodasi*')],
    ];

    $publicLinks = [
        ['label' => 'Form Pendaftaran', 'icon' => 'file-pen-line', 'route' => 'ppdb.index'],
        ['label' => 'Cek Status Siswa', 'icon' => 'search-check', 'route' => 'ppdb.cek-status'],
        ['label' => 'Pengumuman Publik', 'icon' => 'newspaper', 'route' => 'ppdb.pengumuman'],
    ];

    $shortcuts = [
        ['label' => 'CMS Portal Utama', 'icon' => 'globe', 'href' => config('app.cms_admin_url')],
        ['label' => 'Admin BKK', 'icon' => 'briefcase', 'href' => config('app.bkk_admin_url')],
        ['label' => 'Admin PPDB', 'icon' => 'school', 'current' => true],
    ];
@endphp

<body x-data="{ sidebarOpen: false, profileDropdown: false, notifDropdown: false }"
    @keydown.escape.window="sidebarOpen = false; profileDropdown = false; notifDropdown = false"
    class="admin-body h-full antialiased selection:bg-brand-darkred selection:text-white">

    <!-- HEADER -->
    <header class="sticky top-0 z-30 h-16 bg-white/95 backdrop-blur border-b border-brand-ink/10 flex items-center gap-2 px-3 sm:px-5">
        <button type="button" @click="sidebarOpen = true" class="lg:hidden w-10 h-10 rounded-full grid place-items-center hover:bg-brand-softmist text-brand-ink transition-colors cursor-pointer" aria-label="Buka menu">
            <i data-lucide="menu" class="w-5 h-5"></i>
        </button>

        <a href="{{ route('ppdb.dashboard') }}" class="flex items-center gap-2.5 group shrink-0" title="Dashboard PPDB">
            <img src="{{ asset('assets/logo-penus.png') }}" alt="SMK Plus Pelita Nusantara" class="h-8 w-auto group-hover:opacity-90 transition-opacity">
            <span class="hidden sm:flex flex-col pl-2.5 border-l border-brand-ink/15">
                <span class="font-display text-[15px] font-bold uppercase tracking-wide text-brand-ink leading-tight group-hover:text-brand-darkred transition-colors">PPDB</span>
                <span class="text-[10px] uppercase font-semibold tracking-wider text-brand-darkred mt-0.5">Panel Panitia</span>
            </span>
        </a>

        <!-- Pencarian pendaftar -->
        <div class="flex-1 max-w-xl mx-auto hidden md:block">
            <form action="{{ route('ppdb.dashboard.pendaftar') }}" method="GET" role="search">
                <label class="flex items-center gap-3 h-10 rounded-full bg-[#F5F4F2] border border-brand-ink/10 focus-within:bg-white focus-within:border-brand-darkred focus-within:ring-4 focus-within:ring-brand-darkred/10 px-4 transition-all">
                    <i data-lucide="search" class="w-4 h-4 text-brand-ink/50"></i>
                    <input type="search" name="search" value="{{ request()->routeIs('ppdb.dashboard.pendaftar') ? request('search') : '' }}"
                        placeholder="Cari nama, no. registrasi, NISN, atau asal sekolah…" aria-label="Cari pendaftar"
                        class="flex-1 bg-transparent outline-none border-0 p-0 text-sm placeholder:text-brand-ink/40 text-brand-ink focus:ring-0">
                </label>
            </form>
        </div>

        <div class="ml-auto flex items-center gap-1.5 sm:gap-2.5">
            <a href="{{ route('ppdb.index') }}" target="_blank" rel="noopener" class="hidden sm:inline-flex items-center gap-1.5 h-9 px-3.5 rounded-full text-xs font-semibold text-brand-ink bg-white border border-brand-ink/15 hover:bg-brand-softmist transition-colors">
                <i data-lucide="external-link" class="w-3.5 h-3.5 text-brand-darkred"></i>
                <span>Form Publik</span>
            </a>

            <!-- Notifikasi -->
            <div class="relative" @click.outside="notifDropdown = false">
                <button type="button" @click="notifDropdown = !notifDropdown; profileDropdown = false" class="relative w-10 h-10 rounded-full grid place-items-center hover:bg-brand-softmist text-brand-ink transition-colors cursor-pointer" aria-label="Notifikasi ({{ $unverifiedCount }} menunggu verifikasi)">
                    <i data-lucide="bell" class="w-5 h-5"></i>
                    @if($unverifiedCount > 0)
                        <span class="absolute top-1 right-1 min-w-[18px] h-[18px] px-1 rounded-full bg-brand-darkred text-white text-[10px] font-bold grid place-items-center ring-2 ring-white">{{ $unverifiedCount > 99 ? '99+' : $unverifiedCount }}</span>
                    @endif
                </button>
                <div x-show="notifDropdown" x-cloak x-transition.opacity.duration.150ms class="absolute right-0 mt-2 w-[min(22rem,calc(100vw-1.5rem))] bg-white rounded-card border border-brand-ink/10 shadow-softpill overflow-hidden z-50">
                    <div class="px-4 py-3 border-b border-brand-ink/10 flex justify-between items-center bg-[#F5F4F2]">
                        <span class="font-display font-bold uppercase tracking-wide text-sm text-brand-ink">Pendaftar Terbaru</span>
                        <span class="text-[11px] font-bold px-2 py-0.5 rounded-full {{ $unverifiedCount > 0 ? 'text-brand-darkred bg-brand-darkred/10' : 'text-emerald-700 bg-emerald-50' }}">{{ $unverifiedCount }} menunggu</span>
                    </div>
                    <div class="divide-y divide-brand-ink/5 max-h-80 overflow-y-auto">
                        @forelse($recentRegistrations as $reg)
                            <a href="{{ route('ppdb.dashboard.pendaftar.detail', $reg->id) }}" class="flex gap-3 px-4 py-3 hover:bg-[#F5F4F2] transition-colors">
                                <span class="mt-1.5 w-2 h-2 rounded-full shrink-0 {{ $reg->status === 'menunggu_verifikasi' ? 'bg-brand-darkred' : 'bg-brand-ink/20' }}"></span>
                                <span class="min-w-0 flex-1">
                                    <span class="flex justify-between gap-2">
                                        <span class="text-sm font-semibold text-brand-ink truncate">{{ $reg->nama_lengkap }}</span>
                                        <span class="text-[11px] text-brand-ink/50 shrink-0">{{ $reg->created_at?->diffForHumans() }}</span>
                                    </span>
                                    <span class="block text-xs text-brand-ink/60 truncate">{{ $reg->nomor_registrasi }} · {{ $reg->jurusan }}</span>
                                </span>
                            </a>
                        @empty
                            <p class="px-4 py-8 text-center text-sm text-brand-ink/50">Belum ada pendaftaran baru.</p>
                        @endforelse
                    </div>
                    <a href="{{ route('ppdb.dashboard.pendaftar') }}" class="block px-4 py-2.5 text-center text-xs font-bold text-brand-darkred border-t border-brand-ink/10 hover:bg-[#F5F4F2]">Lihat seluruh pendaftar</a>
                </div>
            </div>

            <!-- Profil -->
            <div class="relative" @click.outside="profileDropdown = false">
                <button type="button" @click="profileDropdown = !profileDropdown; notifDropdown = false" class="flex items-center gap-2 h-10 pl-1 pr-1 sm:pr-3 rounded-full hover:bg-brand-softmist transition-colors cursor-pointer" aria-label="Menu profil">
                    @if(!empty($authUser['foto_profil']))
                        <img src="{{ $authUser['foto_profil'] }}" alt="" class="w-8 h-8 rounded-full object-cover">
                    @else
                        <span class="w-8 h-8 rounded-full grid place-items-center text-white text-xs font-bold bg-brand-darkred">{{ $initials }}</span>
                    @endif
                    <span class="hidden sm:block text-left leading-tight">
                        <span class="block text-xs font-bold text-brand-ink max-w-[140px] truncate">{{ $userName }}</span>
                        <span class="block text-[10px] font-semibold uppercase tracking-wider text-brand-ink/50">{{ $userRole }}</span>
                    </span>
                </button>
                <div x-show="profileDropdown" x-cloak x-transition.opacity.duration.150ms class="absolute right-0 mt-2 w-64 bg-white rounded-card border border-brand-ink/10 shadow-softpill p-3 z-50">
                    <div class="px-2 pb-3 mb-2 border-b border-brand-ink/10">
                        <div class="font-bold text-sm text-brand-ink truncate">{{ $userName }}</div>
                        <div class="text-xs text-brand-ink/60 truncate">{{ $userEmail }}</div>
                        <span class="inline-block mt-1.5 px-2 py-0.5 rounded-full text-[10px] font-bold tracking-wider bg-brand-softmist text-brand-ink">{{ $userRole }}</span>
                    </div>
                    <a href="{{ route('ppdb.dashboard') }}" class="flex items-center gap-3 px-2 py-2 rounded-xl text-xs font-medium text-brand-ink hover:bg-[#F5F4F2]"><i data-lucide="layout-dashboard" class="w-4 h-4 text-brand-ink/50"></i>Dashboard Utama</a>
                    <a href="{{ route('ppdb.dashboard.pendaftar') }}" class="flex items-center gap-3 px-2 py-2 rounded-xl text-xs font-medium text-brand-ink hover:bg-[#F5F4F2]"><i data-lucide="users" class="w-4 h-4 text-brand-ink/50"></i>Kelola Pendaftar</a>
                    <form action="{{ route('ppdb.logout') }}" method="POST" class="mt-1 pt-1 border-t border-brand-ink/10">
                        @csrf
                        <button type="submit" class="w-full flex items-center gap-3 px-2 py-2 rounded-xl text-xs font-bold text-brand-darkred hover:bg-brand-darkred/5 cursor-pointer"><i data-lucide="log-out" class="w-4 h-4"></i>Keluar Sesi</button>
                    </form>
                </div>
            </div>
        </div>
    </header>

    <div class="flex min-h-[calc(100vh-4rem)]">
        <!-- Backdrop mobile -->
        <div x-show="sidebarOpen" x-cloak x-transition.opacity @click="sidebarOpen = false" class="fixed inset-0 bg-brand-ink/40 z-40 lg:hidden"></div>

        <!-- SIDEBAR -->
        <aside :class="{ '-translate-x-full': !sidebarOpen }"
            class="w-[264px] shrink-0 fixed lg:sticky top-16 h-[calc(100vh-4rem)] left-0 z-40 bg-white border-r border-brand-ink/10 flex flex-col -translate-x-full lg:translate-x-0 transition-transform duration-200"
            aria-label="Navigasi admin PPDB">
            <div class="flex-1 overflow-y-auto p-3 space-y-5">
                <a href="{{ route('ppdb.dashboard.pendaftar', ['status' => 'menunggu_verifikasi']) }}" class="flex items-center justify-between gap-3 h-12 rounded-full bg-gradient-to-r from-brand-signal to-brand-darkred text-white px-4 shadow-[0_4px_14px_0_rgba(122,16,24,0.25)] hover:-translate-y-px transition-transform">
                    <span class="flex items-center gap-3"><i data-lucide="shield-check" class="w-4 h-4"></i><span class="text-sm font-semibold">Verifikasi Berkas</span></span>
                    <span class="min-w-6 h-6 px-1.5 rounded-full bg-white/20 text-xs font-bold grid place-items-center">{{ $unverifiedCount }}</span>
                </a>

                <nav class="space-y-0.5">
                    <div class="px-3 pb-1.5 flex items-center gap-2">
                        <span class="font-display text-[11px] font-bold uppercase tracking-[0.12em] text-brand-darkred">Manajemen PPDB</span>
                        <span class="h-px flex-1 bg-brand-ink/10"></span>
                    </div>
                    @foreach($navItems as $item)
                        @php $isActive = $item['active'](); @endphp
                        <a href="{{ route($item['route']) }}" @if($isActive) aria-current="page" @endif
                            class="group flex items-center justify-between gap-2 h-10 px-3 rounded-xl text-[13px] transition-colors {{ $isActive ? 'bg-[#F5F4F2] text-brand-darkred font-bold' : 'text-brand-ink font-medium hover:bg-[#F5F4F2]' }}">
                            <span class="flex items-center gap-3 min-w-0">
                                <i data-lucide="{{ $item['icon'] }}" class="w-4 h-4 shrink-0 {{ $isActive ? 'text-brand-darkred' : 'text-brand-ink/50 group-hover:text-brand-ink' }}"></i>
                                @if($isActive)
                                    <x-sketch.underline size="sm" class="truncate">{{ $item['label'] }}</x-sketch.underline>
                                @else
                                    <span class="truncate">{{ $item['label'] }}</span>
                                @endif
                            </span>
                            @if(!empty($item['badge']))
                                <span class="min-w-5 h-5 px-1.5 rounded-full bg-brand-darkred text-white text-[10px] font-bold grid place-items-center shrink-0">{{ $item['badge'] }}</span>
                            @endif
                        </a>
                    @endforeach
                </nav>

                <nav class="space-y-0.5">
                    <div class="px-3 pb-1.5 flex items-center gap-2">
                        <span class="font-display text-[11px] font-bold uppercase tracking-[0.12em] text-brand-darkred">Halaman Publik</span>
                        <span class="h-px flex-1 bg-brand-ink/10"></span>
                    </div>
                    @foreach($publicLinks as $item)
                        <a href="{{ route($item['route']) }}" target="_blank" rel="noopener" class="group flex items-center justify-between h-10 px-3 rounded-xl text-[13px] font-medium text-brand-ink hover:bg-[#F5F4F2] transition-colors">
                            <span class="flex items-center gap-3"><i data-lucide="{{ $item['icon'] }}" class="w-4 h-4 text-brand-ink/50 group-hover:text-brand-ink"></i>{{ $item['label'] }}</span>
                            <i data-lucide="arrow-up-right" class="w-3.5 h-3.5 text-brand-ink/40 group-hover:text-brand-darkred"></i>
                        </a>
                    @endforeach
                </nav>

                <nav class="space-y-0.5">
                    <div class="px-3 pb-1.5 flex items-center gap-2">
                        <span class="font-display text-[11px] font-bold uppercase tracking-[0.12em] text-brand-darkred">Pintas Dashboard</span>
                        <span class="h-px flex-1 bg-brand-ink/10"></span>
                    </div>
                    @foreach($shortcuts as $item)
                        @if(!empty($item['current']))
                            <div class="flex items-center justify-between h-10 px-3 rounded-xl text-[13px] font-semibold bg-brand-ink text-white">
                                <span class="flex items-center gap-3"><i data-lucide="{{ $item['icon'] }}" class="w-4 h-4"></i>{{ $item['label'] }}</span>
                                <span class="text-[9px] font-bold uppercase tracking-wider px-1.5 py-0.5 rounded-full bg-white/15">Aktif</span>
                            </div>
                        @else
                            <a href="{{ $item['href'] }}" class="group flex items-center justify-between h-10 px-3 rounded-xl text-[13px] font-medium text-brand-ink hover:bg-[#F5F4F2] transition-colors">
                                <span class="flex items-center gap-3"><i data-lucide="{{ $item['icon'] }}" class="w-4 h-4 text-brand-ink/50 group-hover:text-brand-ink"></i>{{ $item['label'] }}</span>
                                <i data-lucide="arrow-up-right" class="w-3.5 h-3.5 text-brand-ink/40 group-hover:text-brand-darkred"></i>
                            </a>
                        @endif
                    @endforeach
                </nav>
            </div>

            <div class="p-3 border-t border-brand-ink/10">
                <div class="rounded-card bg-brand-ink text-white p-4">
                    <span class="text-[10px] font-semibold uppercase tracking-wider text-[#F5C2C7]">
                        {{ $sharedActiveWave?->nama ?? 'PPDB' }} · T.A {{ $sharedActiveWave?->tahun_ajaran ?? '-' }}
                    </span>
                    <span class="mt-1 flex items-end justify-between">
                        <span class="font-display text-3xl font-bold leading-none">{{ number_format($sharedTotalPendaftar) }}</span>
                        <span class="text-[11px] text-white/60">total pendaftar</span>
                    </span>
                </div>
            </div>
        </aside>

        <!-- KONTEN -->
        <div class="flex-1 min-w-0 flex flex-col">
            <main class="flex-1 w-full max-w-[1400px] mx-auto p-4 sm:p-6 lg:p-8">
                @if (session('success'))
                    <div class="mb-5 bg-emerald-50 border border-emerald-200 text-emerald-900 rounded-card px-5 py-3.5 text-sm font-semibold flex items-center justify-between gap-3" x-data="{ show: true }" x-show="show">
                        <span class="flex items-center gap-2.5"><i data-lucide="check-circle-2" class="w-5 h-5 text-emerald-600"></i>{{ session('success') }}</span>
                        <button type="button" @click="show = false" class="text-emerald-700 hover:text-emerald-900 cursor-pointer p-1" aria-label="Tutup"><i data-lucide="x" class="w-4 h-4"></i></button>
                    </div>
                @endif

                @if (session('error'))
                    <div class="mb-5 bg-red-50 border border-red-200 text-red-900 rounded-card px-5 py-3.5 text-sm font-semibold flex items-center justify-between gap-3" x-data="{ show: true }" x-show="show">
                        <span class="flex items-center gap-2.5"><i data-lucide="alert-circle" class="w-5 h-5 text-red-600"></i>{{ session('error') }}</span>
                        <button type="button" @click="show = false" class="text-red-700 hover:text-red-900 cursor-pointer p-1" aria-label="Tutup"><i data-lucide="x" class="w-4 h-4"></i></button>
                    </div>
                @endif

                @if (isset($errors) && $errors->any())
                    <div class="mb-5 bg-amber-50 border border-amber-200 text-amber-900 rounded-card px-5 py-3.5 text-sm">
                        <p class="font-bold mb-1 flex items-center gap-2"><i data-lucide="alert-triangle" class="w-4 h-4"></i>Terdapat kesalahan dalam pengisian formulir:</p>
                        <ul class="list-disc list-inside text-xs ml-6 space-y-0.5">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @hasSection('page_title')
                    <h1 class="mb-6 font-display text-2xl sm:text-3xl font-bold uppercase tracking-wide text-brand-ink">
                        <x-sketch.underline size="md">@yield('page_title')</x-sketch.underline>
                    </h1>
                @endif

                @yield('content')
            </main>

            <footer class="border-t border-brand-ink/10 px-6 py-4 flex flex-col sm:flex-row items-center justify-between gap-2 text-xs text-brand-ink/50">
                <span><b class="text-brand-ink/80">Sistem Informasi PPDB</b> &copy; {{ date('Y') }} SMK Plus Pelita Nusantara Bogor</span>
                <span>T.A {{ $sharedActiveWave?->tahun_ajaran ?? '-' }}</span>
            </footer>
        </div>
    </div>

    @include('partials.dev-auth-banner')
    @include('partials.sketch-engine')
    <script>
        document.addEventListener('DOMContentLoaded', () => window.lucide?.createIcons());
    </script>
    @stack('scripts')
</body>

</html>
