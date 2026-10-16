@extends('layouts.app')

@section('title', 'Daftar Pengumuman PPDB 2027/2028 - SMK Plus Pelita Nusantara')

@section('content')
@php
    $categories = isset($kategoriList) && count($kategoriList) > 0
        ? array_merge(['Semua Kategori'], $kategoriList)
        : ['Semua Kategori', 'Hasil Seleksi & Kelulusan', 'Jadwal & Gelombang', 'Tes Observasi & Wawancara', 'Daftar Ulang & Seragam', 'Informasi Beasiswa', 'Informasi Umum'];
@endphp
<div x-data="pengumumanPage()" @keydown.escape.window="selectedPengumuman = null" class="text-brand-ink">

    <main class="px-6 pt-32 md:pt-40">
        <div class="max-w-3xl mx-auto">
            <h1 class="font-display text-3xl md:text-4xl font-bold uppercase tracking-wide leading-tight animate-fade-up">
                <x-sketch.underline tone="text-brand-signal">Pengumuman</x-sketch.underline>
            </h1>

            <!-- Satu bar pencarian: kategori di kiri, kata kunci di tengah, tombol cari di kanan (sama seperti cek status).
                 Daftar sudah tersaring saat mengetik; tombol cari menutup keyboard HP & menggulir ke hasil. -->
            <form role="search" @submit.prevent="$refs.cari.blur(); document.getElementById('hasil-pengumuman').scrollIntoView({ behavior: 'smooth', block: 'start' })"
                  class="mt-12 flex items-center gap-1 rounded-full border border-transparent bg-brand-softmist p-1.5 transition-colors duration-200 hover:border-brand-ink/15 focus-within:border-brand-darkred focus-within:bg-white focus-within:ring-4 focus-within:ring-brand-darkred/10">
                <x-dropdown class="shrink-0 w-28 sm:w-56">
                    <select x-model="selectedKategori" aria-label="Kategori pengumuman"
                            class="appearance-none bg-transparent w-full truncate rounded-l-full pl-4 pr-10 py-2.5 text-sm font-semibold text-brand-darkred focus:outline-none cursor-pointer">
                        @foreach (array_unique($categories) as $kategoriItem)
                            <option value="{{ $kategoriItem }}">{{ $kategoriItem === 'Semua Kategori' ? 'Semua' : $kategoriItem }}</option>
                        @endforeach
                    </select>
                </x-dropdown>

                <span class="h-6 w-px shrink-0 bg-brand-ink/15" aria-hidden="true"></span>

                <div class="relative flex-1 min-w-0">
                    <svg class="hidden sm:block w-4 h-4 absolute left-4 top-1/2 -translate-y-1/2 text-brand-ink/40 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                    <input type="search" x-ref="cari" placeholder="Kata kunci..." x-model="searchQuery" aria-label="Cari pengumuman"
                           class="w-full bg-transparent pl-3 sm:pl-10 pr-9 py-2.5 text-sm text-brand-ink placeholder:text-brand-ink/40 focus:outline-none [&::-webkit-search-cancel-button]:hidden" />
                    <button type="button" x-show="searchQuery" @click="searchQuery = ''; $refs.cari.focus()" aria-label="Hapus pencarian"
                            class="absolute right-2 top-1/2 -translate-y-1/2 p-1 text-brand-ink/40 hover:text-brand-ink" style="display: none;">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                    </button>
                </div>

                <button type="submit" class="btn-primary shrink-0 px-5 sm:px-6 py-2.5 hover:translate-y-0">Cari</button>
            </form>

            <p id="hasil-pengumuman" class="scroll-mt-28 mt-4 px-5 text-xs text-brand-ink/50" x-text="`${filteredList.length} pengumuman`"></p>

            <template x-if="filteredList.length === 0">
                <div class="mt-10 py-10 text-center space-y-3 animate-fade-up">
                    <h2 class="font-sans text-lg font-semibold">Pengumuman tidak ditemukan</h2>
                    <p class="text-sm text-brand-ink/65">Coba kata kunci atau kategori lain.</p>
                    <button type="button" @click="searchQuery = ''; selectedKategori = 'Semua Kategori'" class="btn-secondary mt-2">
                        Reset Pencarian
                    </button>
                </div>
            </template>

            <!-- Daftar berita: dipisah garis coretan tipis -->
            <ul class="mt-6" x-show="filteredList.length > 0">
                <template x-for="item in filteredList" :key="item.id">
                    <li class="relative py-8 animate-fade-up">
                        <x-sketch.rule class="text-brand-darkred/25 -left-1 -right-1 -top-1 h-3" />
                        <article class="group">
                            <div class="flex flex-wrap items-center gap-x-3 gap-y-1.5 text-xs">
                                <span class="font-semibold text-brand-darkred" x-text="item.kategori"></span>
                                <span class="text-brand-ink/30" aria-hidden="true">•</span>
                                <span class="text-brand-ink/60" x-text="item.tanggal"></span>
                                <span x-show="item.badge === 'PENTING' || item.isPinned"
                                      class="text-[10px] font-bold px-2.5 py-0.5 rounded-full uppercase tracking-wider bg-brand-signal text-white"
                                      x-text="item.badge === 'PENTING' ? 'Penting' : 'Disematkan'"></span>
                            </div>

                            <h2 class="mt-3 font-display text-lg md:text-xl font-bold uppercase tracking-wide leading-snug">
                                <button type="button" @click="selectedPengumuman = item" class="text-left transition-colors group-hover:text-brand-darkred" x-text="item.judul"></button>
                            </h2>
                            <p class="mt-2 text-sm leading-relaxed text-brand-ink/70" x-text="item.ringkasan"></p>

                            <button type="button" @click="selectedPengumuman = item" class="group/btn mt-4 inline-flex items-center gap-2 text-sm font-semibold text-brand-darkred">
                                Baca selengkapnya
                                <x-sketch.arrow class="w-6 h-3 transition-transform group-hover/btn:translate-x-1" />
                            </button>
                        </article>
                    </li>
                </template>
            </ul>
        </div>
    </main>

    <!-- ========================================================
        Modal: Detail SK Pengumuman Lengkap
       ======================================================== -->
    <div x-show="selectedPengumuman" x-transition.opacity
         class="fixed inset-0 z-50 bg-brand-ink/60 backdrop-blur-xs flex items-center justify-center p-4 overflow-y-auto"
         @click.self="selectedPengumuman = null" style="display: none;">
        <div role="dialog" aria-modal="true" aria-labelledby="sk-title"
             class="bg-white rounded-card max-w-2xl w-full p-6 sm:p-10 shadow-softpill my-8 animate-fade-up relative">
            <button type="button" @click="selectedPengumuman = null"
                    class="absolute top-6 right-6 w-9 h-9 rounded-full bg-brand-softmist text-brand-ink flex items-center justify-center transition-colors hover:text-brand-darkred"
                    aria-label="Tutup">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M6 18L18 6M6 6l12 12" /></svg>
            </button>

            <div class="relative pb-6 mb-6 pr-10">
                <p class="eyebrow">Surat Keputusan Resmi Panitia PPDB</p>
                <h2 id="sk-title" class="mt-2 font-display text-xl sm:text-2xl font-bold uppercase tracking-wide leading-tight" x-text="selectedPengumuman?.judul"></h2>
                <div class="mt-3 flex flex-wrap items-center gap-x-3 gap-y-1.5 text-xs text-brand-ink/70">
                    <span class="tabular-nums bg-brand-softmist px-3 py-1 rounded-full" x-text="`Nomor: ${selectedPengumuman?.nomorSk}`"></span>
                    <span x-text="`Ditetapkan: ${selectedPengumuman?.tanggal}`"></span>
                    <span class="text-brand-darkred font-semibold" x-text="selectedPengumuman?.kategori"></span>
                </div>
                <x-sketch.rule bold class="text-brand-darkred/50 -left-1 -right-1 -bottom-1 h-3" />
            </div>

            <div class="markdown-content max-h-[60vh] overflow-y-auto pr-2" x-html="selectedPengumuman?.isiLengkapHtml"></div>

            <div class="mt-8 flex flex-col sm:flex-row items-center justify-between gap-3">
                <div class="flex items-center gap-2">
                    <button type="button" onclick="window.print()" class="btn-secondary px-5 py-2.5">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" /></svg>
                        Cetak SK
                    </button>
                    <template x-if="selectedPengumuman?.fileAttachment">
                        <a :href="selectedPengumuman.fileAttachment.url" target="_blank" rel="noreferrer" class="btn-secondary px-5 py-2.5">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" /></svg>
                            Unduh PDF
                        </a>
                    </template>
                </div>

                <button type="button" @click="selectedPengumuman = null" class="btn-primary w-full sm:w-auto">Tutup Pengumuman</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function pengumumanPage() {
        return {
            searchQuery: '',
            selectedKategori: @json($kategori === 'semua' ? 'Semua Kategori' : $kategori),
            selectedPengumuman: null,

            daftarPengumuman: @json($pengumumanList ?? []),

            get filteredList() {
                return this.daftarPengumuman.filter(item => {
                    const matchCategory = this.selectedKategori === 'Semua Kategori' || item.kategori === this.selectedKategori;
                    const q = this.searchQuery.trim().toLowerCase();
                    const matchQuery = q === '' ||
                        (item.judul && item.judul.toLowerCase().includes(q)) ||
                        (item.ringkasan && item.ringkasan.toLowerCase().includes(q)) ||
                        (item.nomorSk && item.nomorSk.toLowerCase().includes(q));
                    return matchCategory && matchQuery;
                });
            }
        };
    }
</script>
@endpush
