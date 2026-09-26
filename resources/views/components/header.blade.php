<header 
    x-data="{ 
        mobileOpen: false
    }" 
    class="fixed top-4 sm:top-6 left-0 right-0 z-50 flex justify-center px-4 pointer-events-none"
>
    <nav 
        class="pointer-events-auto w-full max-w-5xl bg-brand-softmist rounded-full px-5 sm:px-7 py-2 sm:py-2.5 shadow-softpill border border-brand-ink/10 flex items-center justify-between gap-3 sm:gap-4 transition-all duration-300"
        aria-label="Navigasi Utama PPDB"
    >
        <!-- Brand Logo Image (Official logo-penus.png with embedded emblem & typography) -->
        <a href="{{ route('ppdb.index') }}" class="flex items-center group shrink-0 py-0.5" title="SMK Plus Pelita Nusantara">
            <img 
                src="{{ asset('assets/logo-penus.png') }}" 
                alt="SMK Plus Pelita Nusantara" 
                class="h-8 sm:h-9 md:h-9.5 w-auto object-contain transition-transform duration-200 group-hover:scale-102"
                onerror="this.src='https://images.lekar.co.id/file/pelita/logo%20halaman%20pilih%20siswa.png'"
                loading="eager"
            />
        </a>

        <!-- Desktop Navigation Links (Tailored to actual PPDB content) -->
        <ul class="hidden lg:flex items-center gap-1 xl:gap-1.5 select-none">
            @php
                $isHome = request()->routeIs('ppdb.index') && !request()->is('ppdb/*') || request()->is('/');
                $isAkomodasi = request()->routeIs('ppdb.akomodasi') || request()->is('ppdb/akomodasi*') || request()->is('akomodasi*');
                $isCekStatus = request()->routeIs('ppdb.cek-status') || request()->is('ppdb/cek-status*') || request()->is('cek-status*');
                $isPengumuman = request()->routeIs('ppdb.pengumuman') || request()->is('ppdb/pengumuman*') || request()->is('pengumuman*');
                $isDashboard = request()->routeIs('ppdb.dashboard') || request()->is('ppdb/dashboard*') || request()->is('dashboard*');
            @endphp

            <!-- 1. Beranda / Form Pendaftaran -->
            <li>
                <a 
                    href="{{ route('ppdb.index') }}"
                    class="block px-3.5 py-1.5 rounded-full text-xs sm:text-sm font-semibold transition-all duration-200 {{ $isHome ? 'bg-brand-darkred/10 text-brand-darkred shadow-2xs' : 'text-brand-ink/80 hover:text-brand-darkred hover:bg-black/5' }}"
                >
                    Beranda
                </a>
            </li>

            <!-- 2. Biaya & Akomodasi -->
            <li>
                <a 
                    href="{{ route('ppdb.akomodasi') }}"
                    class="block px-3.5 py-1.5 rounded-full text-xs sm:text-sm font-semibold transition-all duration-200 {{ $isAkomodasi ? 'bg-brand-darkred/10 text-brand-darkred shadow-2xs' : 'text-brand-ink/80 hover:text-brand-darkred hover:bg-black/5' }}"
                >
                    Biaya & Akomodasi
                </a>
            </li>

            <!-- 3. Cek Status Pendaftar -->
            <li>
                <a 
                    href="{{ route('ppdb.cek-status') }}"
                    class="block px-3.5 py-1.5 rounded-full text-xs sm:text-sm font-semibold transition-all duration-200 {{ $isCekStatus ? 'bg-brand-darkred/10 text-brand-darkred shadow-2xs' : 'text-brand-ink/80 hover:text-brand-darkred hover:bg-black/5' }}"
                >
                    Cek Status
                </a>
            </li>

            <!-- 4. Pengumuman Seleksi -->
            <li>
                <a 
                    href="{{ route('ppdb.pengumuman') }}"
                    class="block px-3.5 py-1.5 rounded-full text-xs sm:text-sm font-semibold transition-all duration-200 {{ $isPengumuman ? 'bg-brand-darkred/10 text-brand-darkred shadow-2xs' : 'text-brand-ink/80 hover:text-brand-darkred hover:bg-black/5' }}"
                >
                    Pengumuman
                </a>
            </li>

            <!-- 5. Dashboard Rekap -->
            <li>
                <a 
                    href="{{ route('ppdb.dashboard') }}"
                    class="block px-3.5 py-1.5 rounded-full text-xs sm:text-sm font-semibold transition-all duration-200 {{ $isDashboard ? 'bg-brand-darkred/10 text-brand-darkred shadow-2xs' : 'text-brand-ink/80 hover:text-brand-darkred hover:bg-black/5' }}"
                >
                    Dashboard
                </a>
            </li>
        </ul>

        <!-- Action Buttons (Daftar PPDB -> Exact Pill from Image) -->
        <div class="flex items-center gap-2">
            <a 
                href="{{ route('ppdb.index') }}"
                class="group inline-flex items-center gap-1.5 sm:gap-2 whitespace-nowrap rounded-full bg-linear-to-r from-brand-signal to-brand-darkred hover:opacity-95 px-4 sm:px-6 py-2 text-xs sm:text-sm font-semibold text-white shadow-md shadow-brand-darkred/25 transition-all hover:shadow-lg hover:shadow-brand-darkred/30 active:scale-95 cursor-pointer"
            >
                <span>Daftar PPDB</span>
                <svg 
                    class="w-4 h-4 transition-transform group-hover:translate-x-0.5" 
                    viewBox="0 0 24 24" 
                    fill="none" 
                    stroke="currentColor" 
                    stroke-width="2" 
                    stroke-linecap="round" 
                    stroke-linejoin="round"
                    aria-hidden="true"
                >
                    <path d="M5 12h14M13 6l6 6-6 6"/>
                </svg>
            </a>

            <!-- Mobile Hamburger Button -->
            <button 
                type="button" 
                @click="mobileOpen = true"
                class="lg:hidden p-2 rounded-full text-brand-ink hover:bg-black/5 transition-colors cursor-pointer"
                aria-label="Buka menu navigasi"
            >
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                </svg>
            </button>
        </div>
    </nav>

    <!-- Mobile Drawer Overlay Menu (Deep crimson from mylovelyzh.com) -->
    <div 
        x-show="mobileOpen" 
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 translate-y-4"
        x-transition:enter-end="opacity-100 translate-y-0"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100 translate-y-0"
        x-transition:leave-end="opacity-0 translate-y-4"
        class="fixed inset-0 z-50 pointer-events-auto flex flex-col overscroll-contain bg-brand-darkred/95 backdrop-blur-lg p-6 sm:p-8 text-brand-mist overflow-y-auto"
        style="display: none;"
    >
        <!-- Mobile Top Header -->
        <div class="flex items-center justify-between gap-4 pb-6 border-b border-white/10">
            <a href="{{ route('ppdb.index') }}" @click="mobileOpen = false" class="bg-white/95 rounded-2xl px-3.5 py-1.5 shadow-sm inline-flex items-center">
                <img 
                    src="{{ asset('assets/logo-penus.png') }}" 
                    alt="SMK Plus Pelita Nusantara" 
                    class="h-7 sm:h-8 w-auto object-contain"
                    onerror="this.src='https://images.lekar.co.id/file/pelita/logo%20halaman%20pilih%20siswa.png'"
                />
            </a>

            <!-- Close Button -->
            <button 
                type="button" 
                @click="mobileOpen = false"
                class="p-2.5 rounded-full bg-white/10 hover:bg-white/20 text-white shrink-0 transition-colors cursor-pointer"
                aria-label="Tutup menu"
            >
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        <!-- Mobile Nav Links -->
        <div class="py-6 space-y-2 flex-1">
            <!-- 1. Beranda -->
            <a 
                href="{{ route('ppdb.index') }}"
                @click="mobileOpen = false"
                class="flex items-center justify-between px-4 py-3 rounded-2xl text-base font-semibold transition-colors {{ $isHome ? 'bg-white/15 text-white' : 'text-brand-mist hover:bg-white/10 hover:text-white' }}"
            >
                <span>Beranda (Form Pendaftaran)</span>
                <svg class="w-4 h-4 opacity-60" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            </a>

            <!-- 2. Biaya & Akomodasi -->
            <a 
                href="{{ route('ppdb.akomodasi') }}"
                @click="mobileOpen = false"
                class="flex items-center justify-between px-4 py-3 rounded-2xl text-base font-semibold transition-colors {{ $isAkomodasi ? 'bg-white/15 text-white' : 'text-brand-mist hover:bg-white/10 hover:text-white' }}"
            >
                <span>Biaya & Akomodasi</span>
                <svg class="w-4 h-4 opacity-60" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            </a>

            <!-- 3. Cek Status -->
            <a 
                href="{{ route('ppdb.cek-status') }}"
                @click="mobileOpen = false"
                class="flex items-center justify-between px-4 py-3 rounded-2xl text-base font-semibold transition-colors {{ $isCekStatus ? 'bg-white/15 text-white' : 'text-brand-mist hover:bg-white/10 hover:text-white' }}"
            >
                <span>Cek Status Pendaftar</span>
                <svg class="w-4 h-4 opacity-60" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            </a>

            <!-- 4. Pengumuman -->
            <a 
                href="{{ route('ppdb.pengumuman') }}"
                @click="mobileOpen = false"
                class="flex items-center justify-between px-4 py-3 rounded-2xl text-base font-semibold transition-colors {{ $isPengumuman ? 'bg-white/15 text-white' : 'text-brand-mist hover:bg-white/10 hover:text-white' }}"
            >
                <span>Pengumuman Hasil Seleksi</span>
                <svg class="w-4 h-4 opacity-60" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            </a>

            <!-- 5. Dashboard -->
            <a 
                href="{{ route('ppdb.dashboard') }}"
                @click="mobileOpen = false"
                class="flex items-center justify-between px-4 py-3 rounded-2xl text-base font-semibold transition-colors {{ $isDashboard ? 'bg-white/15 text-white' : 'text-brand-mist hover:bg-white/10 hover:text-white' }}"
            >
                <span>Dashboard Rekapitulasi</span>
                <svg class="w-4 h-4 opacity-60" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            </a>
        </div>

        <!-- Mobile CTA Button -->
        <div class="pt-4 border-t border-white/10 space-y-3">
            <a 
                href="{{ route('ppdb.index') }}"
                @click="mobileOpen = false"
                class="w-full flex items-center justify-center gap-2.5 rounded-full bg-linear-to-r from-brand-signal to-brand-warmred px-6 py-3.5 text-base font-bold text-white shadow-xl shadow-black/20 cursor-pointer"
            >
                <span>Daftar PPDB Sekarang</span>
                <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M5 12h14M13 6l6 6-6 6"/>
                </svg>
            </a>

            <a 
                href="https://ppdb.smkpluspnb.sch.id/login"
                target="_blank"
                rel="noreferrer"
                class="w-full flex items-center justify-center gap-2 rounded-full border border-white/20 px-6 py-2.5 text-sm font-semibold text-white/90 hover:bg-white/10"
            >
                <span>Masuk ke Akun Siswa</span>
                <svg class="w-4 h-4 opacity-75" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                </svg>
            </a>
        </div>
    </div>
</header>
