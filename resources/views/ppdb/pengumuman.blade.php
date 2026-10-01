@extends('layouts.app')

@section('title', 'Daftar Pengumuman PPDB 2027/2028 - SMK Plus Pelita Nusantara')

@section('content')
<div x-data="{
    searchQuery: '',
    selectedKategori: '{{ $kategori === 'semua' ? 'Semua Kategori' : $kategori }}',
    onlyImportant: false,
    selectedPengumuman: null,

    daftarPengumuman: @json($pengumumanList ?? []),

    get filteredList() {
        return this.daftarPengumuman.filter(item => {
            const matchCategory = this.selectedKategori === 'Semua Kategori' || item.kategori === this.selectedKategori;
            const matchImportant = this.onlyImportant ? item.badge === 'PENTING' : true;
            const q = this.searchQuery.trim().toLowerCase();
            const matchQuery = q === '' ||
                item.judul.toLowerCase().includes(q) ||
                item.ringkasan.toLowerCase().includes(q) ||
                item.nomorSk.toLowerCase().includes(q);
            return matchCategory && matchImportant && matchQuery;
        });
    },

    get featuredItem() {
        return this.daftarPengumuman.find(item => item.isPinned) || this.daftarPengumuman[0];
    }
}" class="min-h-screen bg-[#F5F4F2] flex flex-col font-sans text-brand-ink antialiased">

    <!-- ========================================================
        2. Hero Header Card
       ======================================================== -->
    <div class="max-w-canvas mx-auto px-4 sm:px-6 md:px-12 pt-2 sm:pt-4">
        <div
            style="background: linear-gradient(135deg, #7A1018 0%, #5C0B12 100%);"
            class="bg-[#7A1018] rounded-[24px] sm:rounded-[32px] p-6 sm:p-10 lg:p-12 text-white relative shadow-sm border border-white/10"
        >
            <div class="max-w-3xl">
                <!-- Clean Eyebrow -->
                <div class="flex flex-wrap items-center gap-2 mb-3">
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-white/10 text-white/90 text-xs font-semibold border border-white/15">
                        <span class="w-1.5 h-1.5 rounded-full bg-[#E5A823]"></span>
                        Warta Resmi PPDB
                    </span>
                    <span class="text-xs text-white/50 hidden sm:inline">•</span>
                    <span class="text-xs text-white/80 font-medium hidden sm:inline">
                        Tahun Ajaran 2027/2028
                    </span>
                </div>

                <h1 class="text-2xl sm:text-4xl lg:text-5xl font-bold uppercase tracking-wide text-white leading-tight font-display">
                    Daftar Pengumuman PPDB
                </h1>

                <p class="mt-3.5 text-white/80 text-xs sm:text-sm md:text-base leading-relaxed max-w-2xl font-normal">
                    Pusat informasi terpadu pengumuman hasil verifikasi berkas administrasi, jadwal observasi minat bakat, petunjuk teknis daftar ulang, dan keputusan resmi penerimaan peserta didik baru SMK Plus Pelita Nusantara.
                </p>

                <div class="mt-6 flex flex-wrap items-center gap-3">
                    <a
                        href="{{ url('/ppdb/cek-status') }}"
                        class="inline-flex items-center gap-2 py-2.5 px-5 sm:px-6 rounded-full bg-[#E5A823] hover:bg-[#D49520] text-[#1A1D20] text-xs sm:text-sm font-bold shadow-sm transition-all active:scale-95"
                    >
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <span>Cek Status Kelulusan NISN</span>
                    </a>

                    <a
                        href="#daftar-pengumuman"
                        class="inline-flex items-center gap-2 py-2.5 px-5 sm:px-6 rounded-full bg-white/10 hover:bg-white/20 text-white text-xs sm:text-sm font-semibold border border-white/20 transition-all active:scale-95"
                    >
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"/></svg>
                        <span>Lihat Arsip Warta</span>
                    </a>

                    <a
                        href="{{ url('/ppdb') }}"
                        class="inline-flex items-center gap-1.5 py-2.5 px-4 text-white/80 hover:text-white text-xs sm:text-sm font-medium transition-colors"
                    >
                        <span>Form PPDB Online</span>
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    </a>
                </div>

                <!-- Natural Trust / Status Indicators -->
                <div class="mt-8 pt-5 border-t border-white/10">
                    <div class="flex flex-wrap items-center gap-2.5 sm:gap-3">
                        <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-white/10 border border-white/15 text-white/90 text-xs font-medium">
                            <span class="w-2 h-2 rounded-full bg-emerald-400"></span>
                            <span>Update Terkini Gelombang 1 & 2</span>
                        </div>
                        <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-white/10 border border-white/15 text-white/90 text-xs font-medium">
                            <svg class="w-4 h-4 text-[#E5A823] shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                            <span>Keputusan Resmi SK Panitia</span>
                        </div>
                        <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-white/10 border border-white/15 text-white/90 text-xs font-medium">
                            <svg class="w-4 h-4 text-[#E5A823] shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                            <span>Lampiran PDF Terverifikasi</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Featured Pinned Announcement Banner -->
        <template x-if="featuredItem">
            <div class="mt-5 sm:mt-6 bg-white rounded-card p-6 sm:p-8 border border-brand-ink/10 shadow-softpill relative overflow-hidden border-l-4 border-l-[#7A1018] transition-all duration-300 hover:-translate-y-1 hover:shadow-softpill">
                <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-6">
                    <div class="space-y-2.5 max-w-3xl">
                        <div class="flex items-center gap-2 flex-wrap">
                            <span class="bg-[#7A1018] text-white text-[10px] font-extrabold px-3 py-1 rounded-full uppercase tracking-wider shadow-xs">
                                PENGUMUMAN UTAMA
                            </span>
                            <span class="text-xs font-bold text-brand-darkred flex items-center gap-1.5">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                <span x-text="featuredItem.tanggal"></span>
                            </span>
                            <span class="text-xs text-brand-ink/40">•</span>
                            <span class="text-xs font-mono font-medium text-brand-ink/60">
                                SK: <span x-text="featuredItem.nomorSk"></span>
                            </span>
                        </div>
                        <h3 class="text-xl sm:text-2xl font-bold uppercase tracking-wide text-brand-ink font-display leading-snug" x-text="featuredItem.judul"></h3>
                        <p class="text-xs sm:text-sm text-brand-ink/75 leading-relaxed font-normal" x-text="featuredItem.ringkasan"></p>
                    </div>

                    <div class="flex flex-col sm:flex-row items-stretch lg:items-center gap-3 shrink-0">
                        <button
                            type="button"
                            @click="selectedPengumuman = featuredItem"
                            class="py-2.5 px-5 rounded-full bg-[#1A1D20] hover:bg-[#7A1018] text-white text-xs font-bold inline-flex items-center justify-center gap-2 transition-all cursor-pointer shadow-xs active:scale-95"
                        >
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                            <span>Baca Keputusan SK</span>
                        </button>
                        <a
                            href="{{ url('/ppdb/cek-status') }}"
                            class="py-2.5 px-5 rounded-full bg-[#E5A823] hover:bg-[#D49520] text-[#1A1D20] text-xs font-bold shadow-xs inline-flex items-center justify-center gap-2 transition-all active:scale-95"
                        >
                            <span>Cek Status NISN Anda</span>
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                        </a>
                    </div>
                </div>
            </div>
        </template>
    </div>

    <!-- ========================================================
        3. Main Canvas Layout with Search, Filters, and Stream Rows
       ======================================================== -->
    <main id="daftar-pengumuman" class="max-w-canvas mx-auto px-4 sm:px-6 md:px-12 py-8 md:py-12">
        <div class="flex flex-col lg:flex-row gap-8 lg:gap-10 items-start">
            
            <!-- LEFT COLUMN: Filters & Search -->
            <aside class="w-full lg:w-[350px] shrink-0 space-y-6 order-2 lg:order-1">
                <!-- Search Input Card -->
                <div class="bg-white rounded-card p-6 shadow-softpill border border-brand-ink/10 space-y-3">
                    <span class="font-editorial-eyebrow text-brand-darkred tracking-eyebrow block">
                        CARI INFORMASI
                    </span>
                    <h3 class="font-editorial-h3 text-brand-ink font-bold text-lg">
                        Pencarian Pengumuman
                    </h3>

                    <div class="relative">
                        <input
                            type="text"
                            placeholder="Ketik judul atau nomor SK..."
                            x-model="searchQuery"
                            class="w-full rounded-full border border-brand-ink/20 pl-10 pr-9 py-2.5 text-xs sm:text-sm text-brand-ink bg-[#F9F8F6] focus:bg-white focus:outline-none focus:border-brand-darkred transition-all"
                        />
                        <svg class="w-4 h-4 absolute left-3.5 top-1/2 -translate-y-1/2 text-brand-ink/40" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                        <button
                            type="button"
                            x-show="searchQuery"
                            @click="searchQuery = ''"
                            class="absolute right-3 top-1/2 -translate-y-1/2 text-brand-ink/40 hover:text-brand-ink"
                            style="display: none;"
                        >
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        </button>
                    </div>

                    <!-- Filter Prioritas Checkbox -->
                    <div class="pt-2">
                        <label class="flex items-center gap-2 cursor-pointer select-none text-xs font-semibold text-brand-ink/80">
                            <input
                                type="checkbox"
                                x-model="onlyImportant"
                                class="rounded text-brand-darkred focus:ring-brand-darkred w-4 h-4"
                            />
                            <span>Tampilkan hanya yang bertanda PENTING</span>
                        </label>
                    </div>
                </div>

                <!-- Category Filter Card -->
                <div class="bg-white rounded-card p-6 shadow-softpill border border-brand-ink/10">
                    <span class="font-editorial-eyebrow text-brand-darkred tracking-eyebrow block">
                        KATEGORI WARTA
                    </span>
                    <h3 class="font-editorial-h3 text-brand-ink font-bold text-lg mt-0.5 mb-4">
                        Pilih Berdasarkan Topik
                    </h3>

                    <div class="space-y-1.5">
                        @php
                            $categories = isset($kategoriList) && count($kategoriList) > 0 ? array_merge(['Semua Kategori'], $kategoriList) : ['Semua Kategori', 'Hasil Seleksi & Kelulusan', 'Jadwal & Gelombang', 'Tes Observasi & Wawancara', 'Daftar Ulang & Seragam', 'Informasi Beasiswa', 'Informasi Umum'];
                        @endphp
                        @foreach(array_unique($categories) as $kategoriItem)
                        <button
                            type="button"
                            @click="selectedKategori = '{{ $kategoriItem }}'"
                            :class="selectedKategori === '{{ $kategoriItem }}' ? 'bg-brand-darkred text-white shadow-xs' : 'text-brand-ink/75 hover:bg-brand-softmist/60 hover:text-brand-ink'"
                            class="w-full text-left px-4 py-2.5 rounded-xl font-bold text-xs sm:text-sm flex items-center justify-between transition-all cursor-pointer"
                        >
                            <span>{{ $kategoriItem }}</span>
                            <svg x-show="selectedKategori === '{{ $kategoriItem }}'" class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                        </button>
                        @endforeach
                    </div>
                </div>

                <!-- Quick Card: Cek Status NISN -->
                <div class="p-6 rounded-card bg-brand-darkred/[0.04] border border-brand-darkred/15 space-y-3 shadow-softpill">
                    <div class="flex items-center gap-2 text-brand-darkred">
                        <svg class="w-4 h-4 text-brand-darkred" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/></svg>
                        <span class="font-editorial-eyebrow uppercase tracking-eyebrow text-xs font-bold">
                            PELACAKAN HASIL SISWA
                        </span>
                    </div>
                    <h4 class="font-sans font-bold text-sm text-brand-ink">
                        Apakah Anda Lolos Seleksi?
                    </h4>
                    <p class="text-xs text-brand-ink/75 leading-relaxed">
                        Cek hasil kelulusan seleksi administrasi dan unduh bukti tanda lolos dengan memasukkan nomor NISN Anda.
                    </p>
                    <a
                        href="{{ url('/ppdb/cek-status') }}"
                        class="w-full py-2.5 rounded-full bg-[#B72A32] hover:bg-[#7A1018] text-white text-xs font-bold inline-flex items-center justify-center gap-2"
                    >
                        <span>Buka Cek Status NISN</span>
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                    </a>
                </div>

                <!-- Card: Unduh Dokumen Resmi -->
                <div class="bg-white rounded-card p-6 shadow-softpill border border-brand-ink/10 space-y-3">
                    <span class="font-editorial-eyebrow text-brand-darkred tracking-eyebrow block">
                        LAMPIRAN PENDUKUNG
                    </span>
                    <h4 class="font-sans font-bold text-sm text-brand-ink">
                        Unduh Dokumen Resmi
                    </h4>
                    <div class="divide-y divide-brand-ink/10">
                        <a
                            href="https://images.lekar.co.id/419/2025/file//2025112110025011_brosur_penus_lipat_3_bagian_depan_&_belakang_compressed.pdf"
                            target="_blank"
                            rel="noreferrer"
                            class="py-3 flex items-center justify-between gap-3 text-xs group hover:text-brand-darkred transition-colors"
                        >
                            <div class="space-y-0.5">
                                <span class="font-bold text-brand-ink group-hover:text-brand-darkred block leading-snug">
                                    Brosur Lengkap Profil PPDB Penus 2027/2028
                                </span>
                                <span class="text-[11px] text-brand-ink/50 font-medium">Format PDF • 4.2 MB</span>
                            </div>
                            <svg class="w-4 h-4 text-brand-darkred shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                        </a>
                        <a
                            href="https://images.lekar.co.id/file/pelita/infografis_pelita_nusantara.pdf"
                            target="_blank"
                            rel="noreferrer"
                            class="py-3 flex items-center justify-between gap-3 text-xs group hover:text-brand-darkred transition-colors"
                        >
                            <div class="space-y-0.5">
                                <span class="font-bold text-brand-ink group-hover:text-brand-darkred block leading-snug">
                                    Formulir Surat Pernyataan Tata Tertib Siswa
                                </span>
                                <span class="text-[11px] text-brand-ink/50 font-medium">Format PDF • 450 KB</span>
                            </div>
                            <svg class="w-4 h-4 text-brand-darkred shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                        </a>
                        <a
                            href="https://images.lekar.co.id/file/pelita/infografis_pelita_nusantara.pdf"
                            target="_blank"
                            rel="noreferrer"
                            class="py-3 flex items-center justify-between gap-3 text-xs group hover:text-brand-darkred transition-colors"
                        >
                            <div class="space-y-0.5">
                                <span class="font-bold text-brand-ink group-hover:text-brand-darkred block leading-snug">
                                    Kalender Akademik Pelaksanaan Seleksi PPDB
                                </span>
                                <span class="text-[11px] text-brand-ink/50 font-medium">Format PDF • 780 KB</span>
                            </div>
                            <svg class="w-4 h-4 text-brand-darkred shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                        </a>
                    </div>
                </div>
            </aside>

            <!-- RIGHT COLUMN: Editorial Stream List of Announcements -->
            <div class="flex-1 min-w-0 space-y-6 order-1 lg:order-2">
                
                <div class="flex items-center justify-between border-b border-brand-ink/10 pb-3">
                    <div>
                        <span class="font-editorial-eyebrow text-brand-darkred tracking-eyebrow block text-xs">
                            ARSIP WARTA RESMI
                        </span>
                        <h2 class="font-editorial-h3 text-brand-ink font-bold text-xl sm:text-2xl mt-0.5">
                            Daftar Warta & Keputusan Seleksi
                        </h2>
                    </div>
                    <span class="text-xs font-bold text-brand-ink/60" x-text="`${filteredList.length} Pengumuman`"></span>
                </div>

                <template x-if="filteredList.length === 0">
                    <div class="bg-white rounded-[26px] p-10 text-center border border-brand-ink/10 space-y-3">
                        <svg class="w-9 h-9 text-brand-signal mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <h4 class="font-sans font-bold text-base text-brand-ink">
                            Pengumuman Tidak Ditemukan
                        </h4>
                        <p class="text-xs text-brand-ink/65 max-w-sm mx-auto">
                            Tidak ada pengumuman yang sesuai dengan filter atau kata kunci pencarian Anda. Silakan coba kata kunci lain.
                        </p>
                        <button
                            type="button"
                            @click="searchQuery = ''; selectedKategori = 'Semua Kategori'; onlyImportant = false;"
                            class="py-2 px-5 rounded-full border border-brand-ink/20 text-brand-ink text-xs font-bold mt-2"
                        >
                            Reset Filter
                        </button>
                    </div>
                </template>

                <div class="space-y-4 sm:space-y-5" x-show="filteredList.length > 0">
                    <template x-for="item in filteredList" :key="item.id">
                        <article class="bg-white rounded-card p-5 sm:p-6 md:p-7 border border-brand-ink/10 shadow-softpill hover:shadow-softpill hover:-translate-y-1 transition-all duration-300 space-y-3.5 group">
                            <!-- Top Metadata Row -->
                            <div class="flex flex-wrap items-center justify-between gap-2.5 pb-3 border-b border-brand-ink/5">
                                <div class="flex items-center gap-2 flex-wrap">
                                    <span
                                        :class="{
                                            'bg-[#B72A32] text-white': item.badge === 'PENTING',
                                            'bg-emerald-600 text-white': item.badge === 'TERBARU',
                                            'bg-[#E5A823] text-[#1A1D20]': item.badge === 'GELOMBANG',
                                            'bg-[#F5F4F2] text-brand-darkred border border-brand-ink/10': item.badge !== 'PENTING' && item.badge !== 'TERBARU' && item.badge !== 'GELOMBANG'
                                        }"
                                        class="text-[10px] font-extrabold px-3 py-1 rounded-full uppercase tracking-wider shadow-xs"
                                        x-text="item.badge"
                                    ></span>
                                    <span class="text-xs font-semibold text-brand-ink/70 bg-[#F5F4F2] px-3 py-1 rounded-full border border-brand-ink/5" x-text="item.kategori"></span>
                                </div>

                                <div class="flex items-center gap-1.5 text-xs text-brand-ink/60 font-medium">
                                    <svg class="w-4 h-4 text-brand-darkred shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                    <span x-text="item.tanggal"></span>
                                </div>
                            </div>

                            <!-- Title & Summary -->
                            <div class="space-y-2">
                                <h3
                                    @click="selectedPengumuman = item"
                                    class="font-display font-bold text-base sm:text-lg lg:text-xl uppercase tracking-wide text-brand-ink leading-snug group-hover:text-brand-darkred transition-colors cursor-pointer"
                                    x-text="item.judul"
                                ></h3>
                                <p class="text-xs sm:text-sm text-brand-ink/75 leading-relaxed font-normal" x-text="item.ringkasan"></p>
                            </div>

                            <!-- Nomor SK & Attachment Row -->
                            <div class="pt-3.5 border-t border-brand-ink/10 flex flex-col sm:flex-row sm:items-center justify-between gap-3 text-xs">
                                <div class="inline-flex items-center gap-1.5 bg-[#F9F8F6] px-3 py-1.5 rounded-lg border border-brand-ink/5 w-fit">
                                    <span class="text-brand-ink/40 font-mono text-[11px]">SK:</span>
                                    <span class="font-mono text-brand-ink/70 font-semibold text-[11px]" x-text="item.nomorSk"></span>
                                </div>

                                <div class="flex items-center gap-2.5">
                                    <template x-if="item.fileAttachment">
                                        <a
                                            :href="item.fileAttachment.url"
                                            target="_blank"
                                            rel="noreferrer"
                                            class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full bg-brand-softmist/80 hover:bg-brand-softmist text-brand-ink/80 hover:text-brand-darkred font-semibold border border-brand-ink/10 transition-colors"
                                        >
                                            <svg class="w-3.5 h-3.5 text-brand-darkred shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                                            <span x-text="`Unduh PDF (${item.fileAttachment.ukuran})`"></span>
                                        </a>
                                    </template>
                                    <button
                                        type="button"
                                        @click="selectedPengumuman = item"
                                        class="inline-flex items-center gap-1.5 px-4 py-1.5 rounded-full bg-[#7A1018] hover:bg-[#5C0B12] text-white font-bold transition-all shadow-xs cursor-pointer active:scale-95"
                                    >
                                        <span>Baca Lengkap</span>
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                                    </button>
                                </div>
                            </div>
                        </article>
                    </template>
                </div>

            </div>
        </div>
    </main>

    <!-- ========================================================
        4. Interactive Modal: Detail SK Pengumuman Lengkap
       ======================================================== -->
    <div x-show="selectedPengumuman" class="fixed inset-0 z-50 bg-brand-ink/60 backdrop-blur-xs flex items-center justify-center p-4 overflow-y-auto" style="display: none;">
        <div class="bg-white rounded-[32px] max-w-2xl w-full p-6 sm:p-10 shadow-editorial border border-brand-ink/15 my-8 animate-scale-in relative">
            <button
                type="button"
                @click="selectedPengumuman = null"
                class="absolute top-6 right-6 p-2 rounded-full text-brand-ink/50 hover:text-brand-ink hover:bg-brand-softmist/60 transition-colors"
                aria-label="Tutup"
            >
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>

            <!-- Official Letterhead Header -->
            <div class="border-b-2 border-brand-ink/20 pb-4 mb-6">
                <span class="font-editorial-eyebrow text-brand-darkred tracking-eyebrow text-xs uppercase font-bold block mb-1">
                    SURAT KEPUTUSAN RESMI PANITIA PPDB
                </span>
                <h2 class="font-editorial-h3 text-xl sm:text-2xl font-extrabold text-brand-ink leading-tight" x-text="selectedPengumuman?.judul"></h2>
                <div class="mt-3 flex flex-wrap items-center gap-3 text-xs text-brand-ink/70 font-medium">
                    <span class="font-mono bg-[#F9F8F6] px-3 py-1 rounded-full border border-brand-ink/10" x-text="`Nomor: ${selectedPengumuman?.nomorSk}`"></span>
                    <span>•</span>
                    <span x-text="`Ditetapkan: ${selectedPengumuman?.tanggal}`"></span>
                    <span>•</span>
                    <span class="text-brand-darkred font-bold" x-text="selectedPengumuman?.kategori"></span>
                </div>
            </div>

            <!-- Document Content -->
            <div class="prose prose-sm max-w-none text-brand-ink/80 text-xs sm:text-sm leading-relaxed whitespace-pre-line space-y-4 max-h-[60vh] overflow-y-auto pr-2" x-text="selectedPengumuman?.isiLengkap"></div>

            <!-- Modal Actions -->
            <div class="mt-8 pt-4 border-t border-brand-ink/10 flex flex-col sm:flex-row items-center justify-between gap-3">
                <div class="flex items-center gap-2">
                    <button
                        type="button"
                        onclick="window.print()"
                        class="py-2 px-4 rounded-full border border-brand-ink/20 text-brand-ink text-xs font-bold inline-flex items-center gap-1.5 hover:bg-brand-softmist/50 cursor-pointer"
                    >
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                        <span>Cetak SK</span>
                    </button>
                    <template x-if="selectedPengumuman?.fileAttachment">
                        <a
                            :href="selectedPengumuman.fileAttachment.url"
                            target="_blank"
                            rel="noreferrer"
                            class="py-2 px-4 rounded-full bg-brand-ink hover:bg-brand-deepred text-white text-xs font-bold inline-flex items-center gap-1.5"
                        >
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                            <span>Unduh PDF</span>
                        </a>
                    </template>
                </div>

                <button
                    type="button"
                    @click="selectedPengumuman = null"
                    class="w-full sm:w-auto py-2.5 px-6 rounded-full bg-[#B72A32] hover:bg-[#7A1018] text-white text-xs font-bold"
                >
                    <span>Tutup Pengumuman</span>
                </button>
            </div>
        </div>
    </div>
</div>
@endsection
