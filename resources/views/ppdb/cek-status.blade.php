@extends('layouts.app')

@section('title', 'Cek Status Pendaftar PPDB 2027/2028 - SMK Plus Pelita Nusantara')

@section('content')
@php
    $serverData = $serverPendaftar ?? null;
@endphp

<div x-data="{
    nisnInput: @js($query ?? ''),
    isLoading: false,
    searchResult: @js($serverData),
    errorMessage: @js(($searched && ! $serverData) ? 'Data pendaftar dengan NISN tersebut tidak ditemukan.' : ''),
    hasSearched: {{ isset($searched) && $searched ? 'true' : 'false' }},

    async handlePerformSearch(query) {
        const q = (query !== undefined ? query : this.nisnInput || '').trim();
        if (!q) {
            this.errorMessage = 'Silakan masukkan nomor NISN Anda.';
            this.hasSearched = false;
            return;
        }

        if (!/^\d+$/.test(q)) {
            this.errorMessage = 'NISN harus berupa digit angka.';
            this.hasSearched = false;
            return;
        }

        this.isLoading = true;
        this.errorMessage = '';
        this.hasSearched = true;

        try {
            const response = await fetch(`/ppdb/cek-status?nisn=${encodeURIComponent(q)}`, {
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });

            const result = await response.json();

            if (response.ok && result.success && result.data) {
                this.searchResult = result.data;
                this.errorMessage = '';
            } else {
                this.searchResult = null;
                this.errorMessage = result.message || `Data pendaftar dengan NISN '${q}' tidak ditemukan.`;
            }
        } catch (err) {
            console.error('Fetch error:', err);
            this.searchResult = null;
            this.errorMessage = 'Terjadi kesalahan saat memeriksa data. Silakan coba kembali.';
        } finally {
            this.isLoading = false;
        }
    },

    handleResetSearch() {
        this.nisnInput = '';
        this.searchResult = null;
        this.errorMessage = '';
        this.hasSearched = false;
    }
}" class="text-brand-ink">

    <main class="px-6 pt-32 md:pt-40">
        <div class="max-w-3xl mx-auto">
            <div class="no-print">
                <h1 class="font-display text-3xl md:text-4xl font-bold uppercase tracking-wide leading-tight animate-fade-up">
                    Cek <x-sketch.underline tone="text-brand-signal">Status</x-sketch.underline>
                </h1>

                <!-- Satu bar pencarian NISN, tombol cari menempel di kanan seperti bar pencarian pengumuman -->
                <form @submit.prevent="handlePerformSearch()" role="search"
                      class="mt-12 flex items-center gap-2 rounded-full border bg-brand-softmist p-1.5 transition-colors duration-200 focus-within:border-brand-darkred focus-within:bg-white focus-within:ring-4 focus-within:ring-brand-darkred/10"
                      :class="errorMessage && !hasSearched ? 'border-brand-signal bg-brand-signal/5' : 'border-transparent hover:border-brand-ink/15'">
                    <div class="relative flex-1 min-w-0">
                        <svg class="w-4 h-4 absolute left-4 top-1/2 -translate-y-1/2 text-brand-ink/40 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                        <input id="nisn-cari" type="text" inputmode="numeric" maxlength="10"
                               placeholder="NISN (10 digit)"
                               aria-label="Nomor Induk Siswa Nasional (NISN)"
                               x-model="nisnInput"
                               @input="nisnInput = nisnInput.replace(/\D/g, ''); if (errorMessage) errorMessage = '';"
                               class="w-full bg-transparent pl-10 pr-9 py-2.5 text-sm tabular-nums text-brand-ink placeholder:text-brand-ink/40 focus:outline-none" />
                        <button type="button" x-show="nisnInput" @click="nisnInput = ''; $el.previousElementSibling.focus()" aria-label="Hapus NISN"
                                class="absolute right-2 top-1/2 -translate-y-1/2 p-1 text-brand-ink/40 hover:text-brand-ink" style="display: none;">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                        </button>
                    </div>

                    <button type="submit" :disabled="isLoading" class="btn-primary shrink-0 px-5 sm:px-6 py-2.5 hover:translate-y-0">
                        <svg x-show="isLoading" class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24" aria-hidden="true" style="display: none;"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path></svg>
                        <span x-text="isLoading ? 'Memeriksa' : 'Cek Status'"></span>
                    </button>
                </form>

                <p x-show="errorMessage && !hasSearched" class="ppdb-error mt-3 px-5" x-text="errorMessage" style="display: none;"></p>

                <!-- Panduan NISN: ringkas di bawah bar, gaya baris info seperti meta pengumuman -->
                <div class="mt-5 flex items-start gap-3 px-5 text-sm leading-relaxed text-brand-ink/65">
                    <span class="mt-0.5 grid h-5 w-5 shrink-0 place-items-center rounded-full bg-brand-darkred/10 text-xs font-bold text-brand-darkred" aria-hidden="true">?</span>
                    <p>
                        <span class="font-semibold text-brand-ink">Lupa NISN?</span>
                        Lihat di Kartu Pelajar SMP, Ijazah SD, atau Surat Keterangan Kepala Sekolah, atau cek langsung di
                        <a href="https://nisn.data.kemdikbud.go.id" target="_blank" rel="noreferrer" class="group inline-flex items-center gap-1.5 font-semibold text-brand-darkred">
                            Portal NISN Kemendikbud
                            <x-sketch.arrow class="w-5 h-2.5 transition-transform group-hover:translate-x-1" />
                        </a>
                    </p>
                </div>
            </div>

            <div class="mt-10">
                <!-- Tidak ditemukan -->
                <div x-show="!searchResult && hasSearched && errorMessage && !isLoading" class="relative py-10 text-center space-y-3 animate-fade-up no-print" style="display: none;">
                    <x-sketch.rule class="text-brand-darkred/25 -left-1 -right-1 -top-1 h-3" />
                    <h2 class="font-sans text-lg font-semibold">Data Tidak Ditemukan</h2>
                    <p class="text-sm text-brand-ink/65" x-text="errorMessage"></p>
                    <button type="button" @click="handleResetSearch(); $nextTick(() => document.getElementById('nisn-cari').focus())" class="btn-secondary mt-2">
                        Cari Ulang
                    </button>
                </div>

                <!-- Ditemukan -->
                <template x-if="searchResult">
                    <div class="space-y-10 animate-fade-up">
                        <!-- Kartu status -->
                        <section class="relative p-6 sm:p-8">
                            <x-sketch.box />
                            {{-- Tombol di bawah nama (bukan di samping) supaya nama panjang tidak terlipat sempit --}}
                            <div class="flex flex-col gap-5">
                                <div>
                                    <span :class="{
                                            'bg-brand-deepred/10 text-brand-deepred': searchResult.statusType === 'success',
                                            'bg-brand-warmred/10 text-brand-warmred': searchResult.statusType === 'warning',
                                            'bg-brand-ink/10 text-brand-ink': searchResult.statusType === 'danger',
                                            'bg-brand-darkred/10 text-brand-darkred': !['success', 'warning', 'danger'].includes(searchResult.statusType)
                                          }"
                                          class="inline-flex items-center gap-1.5 text-xs font-bold px-3 py-1 rounded-full uppercase tracking-wider">
                                        <span class="w-2 h-2 rounded-full bg-current"></span>
                                        <span x-text="searchResult.statusUtama"></span>
                                    </span>
                                    <h2 class="mt-4 font-display text-2xl sm:text-3xl font-bold uppercase tracking-wide leading-tight" x-text="searchResult.namaLengkap"></h2>
                                    <p class="mt-1.5 text-xs tabular-nums text-brand-ink/60">
                                        NISN: <strong class="text-brand-darkred" x-text="searchResult.nisn"></strong> •
                                        No. Reg: <strong class="text-brand-darkred" x-text="searchResult.noPendaftaran"></strong>
                                    </p>
                                </div>

                                <div class="shrink-0 flex flex-wrap items-center gap-2 no-print">
                                    <template x-if="searchResult.uuid">
                                        <a :href="'/ppdb/cetak-kartu/' + searchResult.uuid" target="_blank" class="btn-primary px-5 py-2.5">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                                            Cetak Kartu Tanda Peserta
                                        </a>
                                    </template>
                                    <button type="button" onclick="window.print()" class="btn-secondary px-5 py-2.5">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" /></svg>
                                        Cetak Bukti
                                    </button>
                                </div>
                            </div>

                            <div class="relative mt-6 pt-5">
                                <x-sketch.rule class="text-brand-darkred/25 -left-1 -right-1 -top-1 h-3" />
                                <p class="text-sm leading-relaxed text-brand-ink/80" x-text="searchResult.keteranganStatus"></p>
                            </div>

                            <template x-if="searchResult.catatanPanitia">
                                <div class="mt-5 p-5 rounded-card bg-brand-softmist text-sm text-brand-ink/80">
                                    <p class="eyebrow text-[10px]">Catatan Resmi Panitia Seleksi</p>
                                    <p class="mt-1.5 leading-relaxed" x-text="searchResult.catatanPanitia"></p>
                                </div>
                            </template>
                        </section>

                        <!-- Biodata -->
                        <section>
                            <p class="eyebrow">Ringkasan Data</p>
                            <h3 class="mt-2 font-display text-xl md:text-2xl font-bold uppercase tracking-wide">Biodata Pendaftaran Siswa</h3>

                            <div class="relative mt-6 px-5 md:px-6 py-1">
                                <x-sketch.box />
                                <dl>
                                    <x-review-row label="Kompetensi Keahlian (Jurusan)" class="text-brand-darkred" x-text="searchResult.jurusan || '-'" />
                                    <x-review-row label="Kelas Pilihan" x-text="searchResult.kelas_pilihan || searchResult.kelasPilihan || '-'" />
                                    <x-review-row label="Gelombang Pendaftaran" x-text="searchResult.gelombang || 'Gelombang 1'" />
                                    <x-review-row label="Asal Sekolah SMP/MTs" x-text="searchResult.asal_sekolah || searchResult.asalSekolah || '-'" />
                                    <x-review-row label="Jenis Kelamin" x-text="searchResult.jenis_kelamin_label || (searchResult.jenis_kelamin === 'L' ? 'Laki-laki' : 'Perempuan')" />
                                    <x-review-row label="Tempat & Tanggal Lahir" x-text="`${searchResult.tempat_lahir || '-'}, ${searchResult.tanggal_lahir || searchResult.tanggalLahir || '-'}`" />
                                    <x-review-row label="Nomor HP Siswa" class="tabular-nums" x-text="searchResult.nomor_kontak_pendaftar || '-'" />
                                    <x-review-row label="Nomor HP Orang Tua" class="tabular-nums" x-text="searchResult.nomor_kontak_ortu || '-'" />
                                    <x-review-row label="Tanggal Registrasi" x-text="searchResult.tanggal_daftar || searchResult.tanggalDaftar || '-'" />
                                    <x-review-row label="Alamat Tempat Tinggal" class="font-normal leading-relaxed" x-text="searchResult.alamat_lengkap || '-'" />
                                </dl>
                            </div>
                        </section>

                        <div class="no-print">
                            <button type="button" @click="handleResetSearch(); window.scrollTo({ top: 0 }); $nextTick(() => document.getElementById('nisn-cari').focus())"
                                    class="group inline-flex items-center gap-2 text-sm font-semibold text-brand-darkred">
                                <x-sketch.arrow class="w-6 h-3 -scale-x-100 transition-transform group-hover:-translate-x-1" />
                                Cari NISN lain
                            </button>
                        </div>
                    </div>
                </template>
            </div>
        </div>
    </main>
</div>
@endsection
