{{-- Kepala halaman publik PPDB, mengikuti kepala halaman berita landing page (HeroNews.tsx): latar terang,
     breadcrumb, eyebrow berkilau, judul Oswald dengan coretan di kata terakhir. pt besar supaya tidak tertutup navbar.
     Slot default = tombol aksi, slot $aside = ilustrasi di kanan (desktop). --}}
@props(['eyebrow', 'title', 'description' => null, 'current'])
@php
    // Coretan hanya di kata terakhir: kalau seluruh judul, garisnya ikut melebar saat judul terlipat beberapa baris
    $words = explode(' ', trim($title));
    $lastWord = array_pop($words);
    $firstWords = implode(' ', $words);
@endphp
<section {{ $attributes->class('relative bg-white text-brand-ink px-6 pt-32 md:pt-40 no-print') }}>
    {{-- Dekorasi titik-titik --}}
    <div aria-hidden="true" class="pointer-events-none absolute right-6 top-28 hidden md:block w-40 h-28 bg-[radial-gradient(circle,var(--color-brand-mist)_2px,transparent_2.5px)] bg-size-[22px_22px]"></div>

    <div class="relative max-w-6xl mx-auto grid gap-10 md:grid-cols-[1fr_auto] md:items-center animate-fade-up">
        <div>
            <nav aria-label="Breadcrumb">
                <ol class="flex flex-wrap items-center gap-2 text-sm text-brand-ink/50">
                    <li><a href="/" class="transition-colors hover:text-brand-darkred">Beranda</a></li>
                    <li class="flex items-center gap-2">
                        <svg class="w-3.5 h-3.5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="m9 6 6 6-6 6" /></svg>
                        <a href="{{ route('ppdb.index') }}" class="transition-colors hover:text-brand-darkred">PPDB</a>
                    </li>
                    <li class="flex items-center gap-2">
                        <svg class="w-3.5 h-3.5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="m9 6 6 6-6 6" /></svg>
                        <span aria-current="page" class="font-medium text-brand-ink">{{ $current }}</span>
                    </li>
                </ol>
            </nav>

            <p class="mt-10 md:mt-12 text-xs sm:text-sm uppercase tracking-[0.25em] font-semibold text-brand-darkred">
                <x-sketch.sparks>{{ $eyebrow }}</x-sketch.sparks>
            </p>
            {{-- delay: coretan mulai setelah teks selesai muncul --}}
            <h1 class="mt-4 max-w-4xl text-left font-display text-3xl md:text-4xl lg:text-5xl font-bold uppercase tracking-wide leading-tight">
                {{ $firstWords }} <x-sketch.underline size="lg" tone="text-brand-signal" :delay="500">{{ $lastWord }}</x-sketch.underline>
            </h1>

            @if ($description)
                {{-- mt-10: memberi ruang untuk garis coretan yang menggantung di bawah judul --}}
                <p class="mt-10 max-w-2xl text-base md:text-lg leading-relaxed text-brand-ink/70">{{ $description }}</p>
            @endif

            @if ($slot->isNotEmpty())
                <div class="mt-8 flex flex-wrap items-center gap-3">
                    {{ $slot }}
                </div>
            @endif
        </div>

        @isset($aside)
            <div class="hidden md:block">{{ $aside }}</div>
        @endisset
    </div>
</section>
