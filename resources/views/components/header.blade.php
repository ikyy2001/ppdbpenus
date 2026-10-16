{{-- Navbar publik PPDB, port dari lp/lpfe/src/components/Navbar.tsx supaya PPDB & landing page terasa satu aplikasi.
     Di paling atas halaman berupa bar penuh, setelah discroll berubah jadi pill mengambang. --}}
@php
    $navItems = [
        ['label' => 'Beranda', 'href' => route('ppdb.index'), 'active' => request()->routeIs('ppdb.index')],
        ['label' => 'Info Biaya', 'href' => route('ppdb.akomodasi'), 'active' => request()->routeIs('ppdb.akomodasi')],
        ['label' => 'Cek Status', 'href' => route('ppdb.cek-status'), 'active' => request()->routeIs('ppdb.cek-status')],
        ['label' => 'Pengumuman', 'href' => route('ppdb.pengumuman'), 'active' => request()->routeIs('ppdb.pengumuman')],
    ];
@endphp
<header
    x-data="{
        atTop: window.scrollY < 24,
        menuOpen: false,
        openMenu() {
            this.menuOpen = true;
            this.$refs.menu.showModal();
            document.body.style.overflow = 'hidden';
        },
        closeMenu() {
            this.menuOpen = false;
            this.$refs.menu.close();
            document.body.style.overflow = '';
        },
    }"
    x-init="
        // Tutup sendiri kalau layar dilebarkan sampai ukuran desktop
        window.matchMedia('(min-width: 64rem)').addEventListener('change', (e) => e.matches && menuOpen && closeMenu());
    "
    @scroll.window.passive="atTop = window.scrollY < 24"
    class="fixed left-0 right-0 z-50 flex justify-center pointer-events-none transition-all duration-300 motion-reduce:transition-none no-print"
    :class="atTop ? 'top-0 px-0' : 'top-6 px-4'"
>
    {{-- rounded-[2.5rem] (bukan rounded-full) supaya perubahan sudutnya ikut teranimasi dengan halus --}}
    <nav
        class="pointer-events-auto w-full border flex items-center justify-between gap-4 transition-all duration-300 motion-reduce:transition-none"
        :class="atTop
            ? 'max-w-full rounded-none bg-white border-transparent border-b-brand-ink/10 px-[max(1.5rem,calc((100%-72rem)/2))] py-3 md:py-4'
            : 'max-w-5xl rounded-[2.5rem] bg-brand-softmist border-brand-ink/10 shadow-softpill px-6 md:px-8 py-3 md:py-3.5'"
        aria-label="Navigasi Utama PPDB"
    >
        <a href="{{ route('ppdb.index') }}" class="flex items-center gap-2.5 sm:gap-3 group">
            <img
                src="{{ asset('assets/logosmkpenus.png') }}"
                alt="Logo SMK Pelita Nusantara"
                class="w-9 h-9 sm:w-10 sm:h-10 object-contain drop-shadow-xs group-hover:scale-105 transition-transform shrink-0"
                loading="eager"
            />
            <span class="flex flex-col">
                <span class="font-display text-base font-bold uppercase tracking-wide text-brand-ink leading-tight group-hover:text-brand-darkred transition-colors">
                    SMK PLUS PELITA NUSANTARA
                </span>
                <span class="text-[10px] uppercase font-semibold tracking-wider text-brand-darkred mt-0.5">
                    We Are Different
                </span>
            </span>
        </a>

        <ul class="hidden lg:flex items-center gap-1 xl:gap-1.5">
            @foreach ($navItems as $item)
                <li>
                    <a
                        href="{{ $item['href'] }}"
                        @if ($item['active']) aria-current="page" @endif
                        class="block px-3.5 py-1.5 rounded-full text-sm font-medium transition-colors duration-200 {{ $item['active'] ? 'text-brand-darkred' : 'text-brand-ink/80 hover:text-brand-ink hover:bg-black/5' }}"
                    >
                        {{-- Menu aktif ditandai coretan bawah, bukan latar pill --}}
                        @if ($item['active'])
                            <x-sketch.underline size="sm">{{ $item['label'] }}</x-sketch.underline>
                        @else
                            {{ $item['label'] }}
                        @endif
                    </a>
                </li>
            @endforeach
        </ul>

        <div class="flex items-center gap-2">
            {{-- Tombol utama: satu-satunya menu yang diberi warna penuh --}}
            <a
                href="{{ route('ppdb.index') }}"
                class="group hidden sm:inline-flex items-center gap-2 whitespace-nowrap rounded-full bg-linear-to-r from-brand-signal to-brand-darkred px-5 py-2 text-sm font-semibold text-white shadow-md shadow-brand-darkred/25 transition-shadow hover:shadow-lg hover:shadow-brand-darkred/30"
            >
                Daftar PPDB
                <x-sketch.arrow class="w-6 h-3 transition-transform group-hover:translate-x-0.5" />
            </a>

            <button
                type="button"
                @click="openMenu()"
                aria-haspopup="dialog"
                :aria-expanded="menuOpen"
                class="lg:hidden p-2 rounded-full text-brand-ink hover:bg-black/5"
                aria-label="Buka menu"
            >
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                </svg>
            </button>
        </div>
    </nav>

    {{-- Menu mobile pakai <dialog> supaya fokus keyboard tertahan di dalam menu & bisa ditutup dengan Escape --}}
    <dialog
        x-ref="menu"
        aria-label="Menu navigasi"
        @close="menuOpen && closeMenu()"
        class="pointer-events-auto size-full max-w-none max-h-none flex-col overscroll-contain bg-brand-darkred/95 backdrop-blur-lg p-6 sm:p-8 text-brand-mist open:flex"
    >
        <div class="flex items-center justify-between gap-4">
            <div class="flex items-center gap-3">
                <img src="{{ asset('assets/logosmkpenus.png') }}" alt="" class="w-9 h-9 object-contain drop-shadow shrink-0" />
                <div class="flex flex-col">
                    <span class="font-display text-base font-bold uppercase tracking-wide text-white leading-tight">SMK PLUS PELITA NUSANTARA</span>
                    <span class="text-[10px] uppercase font-semibold tracking-wider text-brand-mist/70 mt-0.5">We Are Different</span>
                </div>
            </div>
            <button
                type="button"
                @click="closeMenu()"
                class="p-2.5 rounded-full bg-white/10 hover:bg-white/20 text-white shrink-0"
                aria-label="Tutup menu"
            >
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <ul class="flex flex-col gap-5 py-10 animate-fade-up">
            @foreach ($navItems as $item)
                <li>
                    <a
                        href="{{ $item['href'] }}"
                        @if ($item['active']) aria-current="page" @endif
                        class="font-display text-3xl font-bold uppercase tracking-wide transition-colors {{ $item['active'] ? 'text-white' : 'text-brand-mist/80 hover:text-white' }}"
                    >
                        @if ($item['active'])
                            <x-sketch.underline tone="text-brand-warmred">{{ $item['label'] }}</x-sketch.underline>
                        @else
                            {{ $item['label'] }}
                        @endif
                    </a>
                </li>
            @endforeach
        </ul>

        <div class="mt-auto border-t border-white/15 pt-6 space-y-3">
            <a
                href="{{ route('ppdb.index') }}"
                class="group flex items-center justify-center gap-2 rounded-full bg-white px-7 py-3.5 text-sm font-semibold text-brand-darkred transition-colors hover:bg-brand-mist"
            >
                Daftar PPDB Sekarang
                <x-sketch.arrow class="w-6 h-3 transition-transform group-hover:translate-x-0.5" />
            </a>
            <a
                href="https://ppdb.smkpluspnb.sch.id/login"
                target="_blank"
                rel="noreferrer"
                class="flex items-center justify-center gap-2 rounded-full border-2 border-white/25 px-7 py-3 text-sm font-semibold text-white/90 transition-colors hover:border-white hover:bg-white/10"
            >
                Masuk ke Akun Siswa
            </a>
        </div>
    </dialog>
</header>
