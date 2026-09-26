@extends('layouts.app')

@section('title', 'Rincian Biaya & Informasi PPDB - SMK Plus Pelita Nusantara')

@section('content')
<div x-data="{
    openFaqId: 1,
    modalKonsultasiOpen: false,
    konsultasiSuccess: false,
    konsultasiForm: {
        namaLengkap: '',
        nisn: '',
        noHpWa: '',
        jurusanMinat: 'Rekayasa Perangkat Lunak (RPL)',
        rencanaPembayaran: 'Cash Lunas (Rp 5.850.000)',
        catatan: ''
    },
    formErrors: {},
    toggleFaq(id) {
        this.openFaqId = this.openFaqId === id ? null : id;
    },
    handleOpenKonsultasi(jurusan = null) {
        if (jurusan) {
            this.konsultasiForm.jurusanMinat = jurusan;
        }
        this.formErrors = {};
        this.konsultasiSuccess = false;
        this.modalKonsultasiOpen = true;
    },
    handleKonsultasiSubmit() {
        let errors = {};
        if (!this.konsultasiForm.namaLengkap.trim()) {
            errors.namaLengkap = 'Nama lengkap siswa wajib diisi';
        }
        if (!this.konsultasiForm.noHpWa.trim()) {
            errors.noHpWa = 'Nomor WhatsApp wajib diisi';
        }
        if (this.konsultasiForm.nisn && this.konsultasiForm.nisn.replace(/\D/g, '').length !== 10) {
            errors.nisn = 'NISN harus berupa 10 digit angka';
        }

        if (Object.keys(errors).length > 0) {
            this.formErrors = errors;
            return;
        }

        this.formErrors = {};
        this.konsultasiSuccess = true;
    },
    handleSendToWhatsApp() {
        const text = encodeURIComponent(
            `Halo Panitia PPDB SMK Plus Pelita Nusantara,\n\n` +
            `Saya ingin berkonsultasi mengenai pembiayaan & pendaftaran PPDB 2024/2025:\n` +
            `• Nama Siswa: ${this.konsultasiForm.namaLengkap}\n` +
            `• NISN: ${this.konsultasiForm.nisn || '-'}\n` +
            `• No. WA: ${this.konsultasiForm.noHpWa}\n` +
            `• Jurusan Pilihan: ${this.konsultasiForm.jurusanMinat}\n` +
            `• Rencana Pembayaran: ${this.konsultasiForm.rencanaPembayaran}\n` +
            `• Pertanyaan / Catatan: ${this.konsultasiForm.catatan || '-'}\n\n` +
            `Mohon petunjuk prosedur pendaftaran dan rincian pembayarannya. Terima kasih!`
        );
        window.open(`https://wa.me/6281210868958?text=${text}`, '_blank');
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
                        Tahun Ajaran 2024/2025
                    </span>
                    <span class="text-xs text-white/50 hidden sm:inline">•</span>
                    <span class="text-xs text-white/80 font-medium hidden sm:inline">
                        Binaan SMKN 1 Cibinong & SMA Plus PGRI 1 Cibinong
                    </span>
                </div>

                <h1 class="text-2xl sm:text-4xl lg:text-5xl font-bold uppercase tracking-wide text-white leading-tight font-display">
                    Rincian Biaya & Informasi PPDB
                </h1>

                <p class="mt-3.5 text-white/80 text-xs sm:text-sm md:text-base leading-relaxed max-w-2xl font-normal">
                    Transparansi uraian pembiayaan pendidikan, seragam & atribut lengkap, 4 kompetensi keahlian unggulan, program pembinaan karakter, dan prestasi siswa SMK Plus Pelita Nusantara.
                </p>

                <div class="mt-6 flex flex-wrap items-center gap-3">
                    <a
                        href="#tabel-biaya"
                        class="inline-flex items-center gap-2 py-2.5 px-5 sm:px-6 rounded-full bg-[#E5A823] hover:bg-[#D49520] text-[#1A1D20] text-xs sm:text-sm font-bold shadow-sm transition-all active:scale-95"
                    >
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"/></svg>
                        <span>Lihat Tabel Pembiayaan</span>
                    </a>

                    <button
                        type="button"
                        @click="handleOpenKonsultasi()"
                        class="inline-flex items-center gap-2 py-2.5 px-5 sm:px-6 rounded-full bg-white/10 hover:bg-white/20 text-white text-xs sm:text-sm font-semibold border border-white/20 transition-all active:scale-95 cursor-pointer"
                    >
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                        <span>Konsultasi Pembiayaan</span>
                    </button>

                    <a
                        href="{{ url('/ppdb') }}"
                        class="inline-flex items-center gap-1.5 py-2.5 px-4 text-white/80 hover:text-white text-xs sm:text-sm font-medium transition-colors"
                    >
                        <span>Form PPDB Online</span>
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    </a>
                </div>

                <!-- Natural Trust Indicators -->
                <div class="mt-8 pt-5 border-t border-white/10">
                    <div class="flex flex-wrap items-center gap-2.5 sm:gap-3">
                        <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-white/10 border border-white/15 text-white/90 text-xs font-medium">
                            <svg class="w-4 h-4 text-[#E5A823] shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                            <span>Terakreditasi 'A' UNGGUL</span>
                        </div>
                        <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-white/10 border border-white/15 text-white/90 text-xs font-medium">
                            <svg class="w-4 h-4 text-[#E5A823] shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                            <span>Kerjasama LSP, BNSP & PJJ-PENS</span>
                        </div>
                        <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-white/10 border border-white/15 text-white/90 text-xs font-medium">
                            <svg class="w-4 h-4 text-[#E5A823] shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                            <span>13 Perlengkapan Seragam & Atribut Resmi</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Stats Bar -->
        <div class="mt-5 sm:mt-6 grid grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4">
            <div class="bg-white rounded-card p-4 sm:p-5 border border-brand-ink/10 shadow-softpill hover:shadow-softpill hover:-translate-y-1 transition-all duration-300 flex flex-col justify-between">
                <span class="font-editorial-eyebrow text-brand-darkred text-[10px] sm:text-[11px] tracking-eyebrow uppercase font-bold">
                    Total Biaya Masuk
                </span>
                <div class="my-1 sm:my-1.5">
                    <span class="text-xl sm:text-2xl lg:text-3xl font-bold text-brand-ink font-display tracking-wide">
                        Rp 5.850.000
                    </span>
                </div>
                <span class="text-[11px] sm:text-xs text-brand-ink/65 font-medium leading-snug">
                    PPDB + 13 Item Seragam & Atribut
                </span>
            </div>

            <div class="bg-white rounded-card p-4 sm:p-5 border border-brand-ink/10 shadow-softpill hover:shadow-softpill hover:-translate-y-1 transition-all duration-300 flex flex-col justify-between">
                <span class="font-editorial-eyebrow text-brand-darkred text-[10px] sm:text-[11px] tracking-eyebrow uppercase font-bold">
                    Biaya Pendidikan PPDB
                </span>
                <div class="my-1 sm:my-1.5">
                    <span class="text-xl sm:text-2xl lg:text-3xl font-bold text-brand-darkred font-display tracking-wide">
                        Rp 3.875.000
                    </span>
                </div>
                <span class="text-[11px] sm:text-xs text-brand-ink/65 font-medium leading-snug">
                    Termasuk SPP Juli & Pelatihan Karakter
                </span>
            </div>

            <div class="bg-white rounded-card p-4 sm:p-5 border border-brand-ink/10 shadow-softpill hover:shadow-softpill hover:-translate-y-1 transition-all duration-300 flex flex-col justify-between">
                <span class="font-editorial-eyebrow text-brand-darkred text-[10px] sm:text-[11px] tracking-eyebrow uppercase font-bold">
                    Seragam & Atribut
                </span>
                <div class="my-1 sm:my-1.5">
                    <span class="text-xl sm:text-2xl lg:text-3xl font-bold text-brand-ink font-display tracking-wide">
                        13 Item Lengkap
                    </span>
                </div>
                <span class="text-[11px] sm:text-xs text-brand-ink/65 font-medium leading-snug">
                    13 Perlengkapan Resmi Sekolah
                </span>
            </div>

            <div class="bg-white rounded-card p-4 sm:p-5 border border-brand-ink/10 shadow-softpill hover:shadow-softpill hover:-translate-y-1 transition-all duration-300 flex flex-col justify-between">
                <span class="font-editorial-eyebrow text-brand-darkred text-[10px] sm:text-[11px] tracking-eyebrow uppercase font-bold">
                    Akreditasi Sekolah
                </span>
                <div class="my-1 sm:my-1.5">
                    <span class="text-xl sm:text-2xl lg:text-3xl font-bold text-brand-ink font-display tracking-wide">
                        A (Unggul)
                    </span>
                </div>
                <span class="text-[11px] sm:text-xs text-brand-ink/65 font-medium leading-snug">
                    LSP, BNSP & Kerjasama PENS
                </span>
            </div>
        </div>
    </div>

    <!-- ========================================================
        3. Main 2-Column Canvas Layout
       ======================================================== -->
    <main class="max-w-canvas mx-auto px-4 sm:px-6 md:px-12 py-8 sm:py-12">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 lg:gap-8 items-start">
            
            <!-- LEFT COLUMN: Sticky Sidebar Cards -->
            <aside class="lg:col-span-4 space-y-5 order-2 lg:order-1">
                <!-- Card: Ringkasan Total & Rekening Pembayaran -->
                <div class="bg-white rounded-card p-5 sm:p-6 shadow-softpill border border-brand-ink/10 space-y-3.5">
                    <div class="flex items-center justify-between pb-3 border-b border-brand-ink/10">
                        <span class="font-editorial-eyebrow text-brand-darkred text-xs uppercase font-bold">
                            SKEMA RESMI CASH
                        </span>
                        <span class="bg-[#E5A823]/20 text-[#8B5E00] px-2.5 py-0.5 rounded-full text-[10px] font-bold">
                            2024/2025
                        </span>
                    </div>

                    <div class="space-y-2 text-xs">
                        <div class="flex justify-between items-center text-brand-ink/75">
                            <span>Uraian PPDB (6 Item):</span>
                            <span class="font-bold text-brand-ink">Rp. 3.875.000,-</span>
                        </div>
                        <div class="flex justify-between items-center text-brand-ink/75">
                            <span>Seragam & Atribut (13 Item):</span>
                            <span class="font-bold text-brand-ink">Rp. 1.975.000,-</span>
                        </div>
                        <div class="pt-2 border-t border-dashed border-brand-ink/20 flex justify-between items-baseline">
                            <span class="font-bold text-sm text-brand-ink">Total Biaya Masuk:</span>
                            <span class="font-black text-lg text-brand-darkred font-sans">
                                Rp. 5.850.000,-
                            </span>
                        </div>
                    </div>

                    <div class="pt-2">
                        <button
                            type="button"
                            @click="handleOpenKonsultasi()"
                            class="w-full py-2.5 rounded-full bg-[#B72A32] hover:bg-[#7A1018] text-white text-xs font-bold shadow-softpill flex items-center justify-center gap-2 cursor-pointer"
                        >
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                            <span>Ajukan Konsultasi / Cicilan</span>
                        </button>
                    </div>
                </div>

                <!-- Card 3: Profil Pembina & Identitas Sekolah -->
                <div class="bg-white rounded-card p-5 sm:p-6 shadow-softpill border border-brand-ink/10 space-y-3">
                    <span class="font-editorial-eyebrow text-brand-darkred tracking-eyebrow block text-xs font-bold">
                        LEMBAGA PENDIDIKAN
                    </span>
                    <h4 class="font-sans font-bold text-sm text-brand-ink">
                        SMK Plus Pelita Nusantara
                    </h4>
                    <p class="text-xs text-brand-ink/70 leading-relaxed">
                        <strong>Pembina YPNB:</strong> Dr. H. Basyarudin Thayib M.Pd.
                        <br />
                        <strong>Binaan:</strong> SMKN 1 Cibinong & SMA Plus PGRI 1 Cibinong
                        <br />
                        <strong>Status:</strong> Terakreditasi "A" UNGGUL
                    </p>

                    <div class="p-3 bg-brand-softmist/80 rounded-xl text-[11px] text-brand-ink/80 italic font-medium border border-brand-ink/5">
                        "SUCCESS BY CHARACTER • WE ARE DIFFERENT • THE FUTURE IS OURS"
                    </div>
                </div>

                <!-- Card 4: Hotline Bantuan & WhatsApp -->
                <div class="p-5 sm:p-6 rounded-card bg-brand-darkred/[0.04] border border-brand-darkred/15 space-y-3 shadow-softpill">
                    <div class="flex items-center gap-2 text-brand-darkred">
                        <svg class="w-4 h-4 text-brand-darkred" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/></svg>
                        <span class="font-editorial-eyebrow uppercase tracking-eyebrow text-xs font-bold">
                            BANTUAN INFORMASI
                        </span>
                    </div>
                    <p class="text-xs text-brand-ink/75 leading-relaxed">
                        Ada pertanyaan tentang rincian pembayaran, beasiswa, atau jadwal pengambilan seragam?
                    </p>
                    <a
                        href="https://wa.me/6281210868958?text=Halo%20Panitia%20PPDB%20Penus,%20saya%20ingin%20tanya%20mengenai%20rincian%20pembiayaan"
                        target="_blank"
                        rel="noreferrer"
                        class="w-full py-2.5 rounded-full bg-[#B72A32] hover:bg-[#7A1018] text-white text-xs font-bold shadow-softpill flex items-center justify-center gap-2"
                    >
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                        <span>Chat Panitia PPDB (WhatsApp)</span>
                    </a>
                </div>

                <!-- Card 5: Unduh Rincian / Cetak -->
                <div class="bg-white rounded-card p-5 sm:p-6 shadow-softpill border border-brand-ink/10">
                    <span class="font-editorial-eyebrow text-brand-darkred tracking-eyebrow block text-xs font-bold">
                        ARSIP & CETAK
                    </span>
                    <h4 class="font-sans font-bold text-sm text-brand-ink mt-0.5 mb-1.5">
                        Cetak Rincian Pembiayaan
                    </h4>
                    <p class="text-xs text-brand-ink/65 leading-relaxed mb-3.5">
                        Simpan atau cetak tabel pembiayaan resmi ini untuk arsip orang tua / wali calon siswa.
                    </p>
                    <button
                        type="button"
                        onclick="window.print()"
                        class="w-full py-2.5 px-4 rounded-full border border-brand-ink/15 text-xs font-bold text-brand-ink hover:bg-brand-softmist/60 transition-colors flex items-center justify-center gap-2 cursor-pointer"
                    >
                        <svg class="w-4 h-4 text-brand-darkred" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                        <span>Cetak / Simpan PDF Halaman</span>
                    </button>
                </div>
            </aside>

            <!-- RIGHT COLUMN: Main Content Stream -->
            <div class="lg:col-span-8 space-y-8 sm:space-y-10 order-1 lg:order-2">
                
                <!-- SECTION 1: TABEL URAIAN PEMBIAYAAN PPDB -->
                <div id="tabel-biaya" class="space-y-4">
                    <div class="flex items-center gap-3 border-b border-brand-ink/10 pb-3">
                        <div class="w-8 h-8 rounded-full bg-brand-darkred text-white flex items-center justify-center font-bold text-xs shrink-0">
                            1
                        </div>
                        <div>
                            <span class="font-editorial-eyebrow text-brand-darkred tracking-eyebrow block text-[11px] sm:text-xs font-bold">
                                URAIAN BIAYA UTAMA
                            </span>
                            <h2 class="font-editorial-h3 text-brand-ink font-bold text-xl sm:text-2xl">
                                Uraian Pembiayaan PPDB
                            </h2>
                        </div>
                    </div>

                    <!-- Exact Table Card following design -->
                    <div class="bg-white rounded-card sm:rounded-[24px] overflow-hidden shadow-softpill border border-brand-ink/10">
                        <div class="bg-[#E5A823] px-6 py-3.5 text-center border-b border-[#C88E12]">
                            <h3 class="font-sans font-extrabold text-sm sm:text-base tracking-wider uppercase text-[#1A1D20]">
                                URAIAN PEMBIAYAAN PPDB
                            </h3>
                        </div>

                        <div class="overflow-x-auto">
                            <table class="w-full text-left border-collapse">
                                <thead>
                                    <tr class="bg-[#2D6898] text-white text-xs sm:text-sm font-bold uppercase tracking-wider">
                                        <th class="py-3 px-4 text-center w-14 sm:w-16 border-r border-white/20">NO</th>
                                        <th class="py-3 px-4 sm:px-6 border-r border-white/20">URAIAN BIAYA</th>
                                        <th class="py-3 px-4 sm:px-6 text-right w-40 sm:w-48">BIAYA CASH</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-brand-ink/10 text-xs sm:text-sm">
                                    <tr class="hover:bg-brand-softmist/40 transition-colors">
                                        <td class="py-3 sm:py-3.5 px-4 text-center font-bold text-brand-darkred bg-[#F9F8F6] border-r border-brand-ink/10">1.</td>
                                        <td class="py-3 sm:py-3.5 px-4 sm:px-6 font-semibold text-brand-ink border-r border-brand-ink/10">
                                            <div>Dana Pembangunan Pendidikan</div>
                                            <div class="text-[11px] text-brand-ink/55 font-normal mt-0.5">Pengembangan sarana & laboratorium kejuruan terpadu</div>
                                        </td>
                                        <td class="py-3 sm:py-3.5 px-4 sm:px-6 text-right font-bold text-brand-ink font-sans whitespace-nowrap">Rp. 3.000.000,-</td>
                                    </tr>
                                    <tr class="hover:bg-brand-softmist/40 transition-colors">
                                        <td class="py-3 sm:py-3.5 px-4 text-center font-bold text-brand-darkred bg-[#F9F8F6] border-r border-brand-ink/10">2.</td>
                                        <td class="py-3 sm:py-3.5 px-4 sm:px-6 font-semibold text-brand-ink border-r border-brand-ink/10">
                                            <div>SPP Bulan Juli 2023</div>
                                            <div class="text-[11px] text-brand-ink/55 font-normal mt-0.5">Iuran operasional pendidikan bulan pertama masuk</div>
                                        </td>
                                        <td class="py-3 sm:py-3.5 px-4 sm:px-6 text-right font-bold text-brand-ink font-sans whitespace-nowrap">Rp. 375.000,-</td>
                                    </tr>
                                    <tr class="hover:bg-brand-softmist/40 transition-colors">
                                        <td class="py-3 sm:py-3.5 px-4 text-center font-bold text-brand-darkred bg-[#F9F8F6] border-r border-brand-ink/10">3.</td>
                                        <td class="py-3 sm:py-3.5 px-4 sm:px-6 font-semibold text-brand-ink border-r border-brand-ink/10">
                                            <div>Pelatihan Learning Skill & Karakter</div>
                                            <div class="text-[11px] text-brand-ink/55 font-normal mt-0.5">Pembinaan karakter taruna & kesiapan vokasi industri</div>
                                        </td>
                                        <td class="py-3 sm:py-3.5 px-4 sm:px-6 text-right font-bold text-brand-ink font-sans whitespace-nowrap">Rp. 300.000,-</td>
                                    </tr>
                                    <tr class="hover:bg-brand-softmist/40 transition-colors">
                                        <td class="py-3 sm:py-3.5 px-4 text-center font-bold text-brand-darkred bg-[#F9F8F6] border-r border-brand-ink/10">4.</td>
                                        <td class="py-3 sm:py-3.5 px-4 sm:px-6 font-semibold text-brand-ink border-r border-brand-ink/10">
                                            <div>Pass Photo</div>
                                            <div class="text-[11px] text-brand-ink/55 font-normal mt-0.5">Foto resmi kartu tanda pelajar, rapor & arsip PPDB</div>
                                        </td>
                                        <td class="py-3 sm:py-3.5 px-4 sm:px-6 text-right font-bold text-brand-ink font-sans whitespace-nowrap">Rp. 100.000,-</td>
                                    </tr>
                                    <tr class="hover:bg-brand-softmist/40 transition-colors">
                                        <td class="py-3 sm:py-3.5 px-4 text-center font-bold text-brand-darkred bg-[#F9F8F6] border-r border-brand-ink/10">5.</td>
                                        <td class="py-3 sm:py-3.5 px-4 sm:px-6 font-semibold text-brand-ink border-r border-brand-ink/10">
                                            <div>Tabungan Simpanan Wajib Sekolah</div>
                                            <div class="text-[11px] text-brand-ink/55 font-normal mt-0.5">Simpanan wajib siswa terdaftar</div>
                                        </td>
                                        <td class="py-3 sm:py-3.5 px-4 sm:px-6 text-right font-bold text-brand-ink font-sans whitespace-nowrap">Rp. 50.000,-</td>
                                    </tr>
                                    <tr class="hover:bg-brand-softmist/40 transition-colors">
                                        <td class="py-3 sm:py-3.5 px-4 text-center font-bold text-brand-darkred bg-[#F9F8F6] border-r border-brand-ink/10">6.</td>
                                        <td class="py-3 sm:py-3.5 px-4 sm:px-6 font-semibold text-brand-ink border-r border-brand-ink/10">
                                            <div>Tabungan Simpanan Pokok Koperasi</div>
                                            <div class="text-[11px] text-brand-ink/55 font-normal mt-0.5">Keanggotaan koperasi sekolah siswa</div>
                                        </td>
                                        <td class="py-3 sm:py-3.5 px-4 sm:px-6 text-right font-bold text-brand-ink font-sans whitespace-nowrap">Rp. 50.000,-</td>
                                    </tr>
                                </tbody>
                                <tfoot>
                                    <tr class="bg-[#2D6898] text-white font-extrabold text-xs sm:text-sm">
                                        <td colSpan="2" class="py-3.5 px-4 sm:px-6 text-center tracking-wider uppercase border-r border-white/20">
                                            JUMLAH
                                        </td>
                                        <td class="py-3.5 px-4 sm:px-6 text-right font-sans tracking-wide whitespace-nowrap">
                                            Rp. 3.875.000,-
                                        </td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- SECTION 2: TABEL URAIAN PEMBIAYAAN SERAGAM & ATRIBUT -->
                <div id="tabel-seragam" class="space-y-4 pt-2">
                    <div class="flex items-center gap-3 border-b border-brand-ink/10 pb-3">
                        <div class="w-8 h-8 rounded-full bg-brand-darkred text-white flex items-center justify-center font-bold text-xs shrink-0">
                            2
                        </div>
                        <div>
                            <span class="font-editorial-eyebrow text-brand-darkred tracking-eyebrow block text-[11px] sm:text-xs font-bold">
                                PERLENGKAPAN RESMI LENGKAP
                            </span>
                            <h2 class="font-editorial-h3 text-brand-ink font-bold text-xl sm:text-2xl">
                                Uraian Pembiayaan Seragam & Atribut
                            </h2>
                        </div>
                    </div>

                    <div class="bg-white rounded-card sm:rounded-[24px] overflow-hidden shadow-softpill border border-brand-ink/10">
                        <div class="bg-[#E5A823] px-6 py-3.5 text-center border-b border-[#C88E12]">
                            <h3 class="font-sans font-extrabold text-sm sm:text-base tracking-wider uppercase text-[#1A1D20]">
                                URAIAN PEMBIAYAAN SERAGAM & ATRIBUT
                            </h3>
                        </div>

                        <div class="overflow-x-auto">
                            <table class="w-full text-left border-collapse">
                                <thead>
                                    <tr class="bg-[#2D6898] text-white text-xs sm:text-sm font-bold uppercase tracking-wider">
                                        <th class="py-3 px-4 text-center w-14 sm:w-16 border-r border-white/20">NO</th>
                                        <th class="py-3 px-4 sm:px-6 border-r border-white/20">URAIAN BIAYA</th>
                                        <th class="py-3 px-4 sm:px-6 text-right w-40 sm:w-48">BIAYA CASH</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-brand-ink/10 text-xs sm:text-sm">
                                    <tr class="hover:bg-brand-softmist/40 transition-colors">
                                        <td class="py-2.5 sm:py-3 px-4 text-center font-bold text-brand-darkred bg-[#F9F8F6] border-r border-brand-ink/10">1.</td>
                                        <td class="py-2.5 sm:py-3 px-4 sm:px-6 font-semibold text-brand-ink border-r border-brand-ink/10">
                                            <div class="flex items-center justify-between gap-2"><span>Jaket Almamater</span><span class="text-[10px] text-brand-ink/50 bg-black/5 px-2 py-0.5 rounded-full font-medium">Pakaian Resmi</span></div>
                                        </td>
                                        <td class="py-2.5 sm:py-3 px-4 sm:px-6 text-right font-bold text-brand-ink font-sans whitespace-nowrap">Rp. 210.000,-</td>
                                    </tr>
                                    <tr class="hover:bg-brand-softmist/40 transition-colors">
                                        <td class="py-2.5 sm:py-3 px-4 text-center font-bold text-brand-darkred bg-[#F9F8F6] border-r border-brand-ink/10">2.</td>
                                        <td class="py-2.5 sm:py-3 px-4 sm:px-6 font-semibold text-brand-ink border-r border-brand-ink/10">
                                            <div class="flex items-center justify-between gap-2"><span>Seragam Olahraga</span><span class="text-[10px] text-brand-ink/50 bg-black/5 px-2 py-0.5 rounded-full font-medium">Pakaian Olahraga</span></div>
                                        </td>
                                        <td class="py-2.5 sm:py-3 px-4 sm:px-6 text-right font-bold text-brand-ink font-sans whitespace-nowrap">Rp. 200.000,-</td>
                                    </tr>
                                    <tr class="hover:bg-brand-softmist/40 transition-colors">
                                        <td class="py-2.5 sm:py-3 px-4 text-center font-bold text-brand-darkred bg-[#F9F8F6] border-r border-brand-ink/10">3.</td>
                                        <td class="py-2.5 sm:py-3 px-4 sm:px-6 font-semibold text-brand-ink border-r border-brand-ink/10">
                                            <div class="flex items-center justify-between gap-2"><span>Seragam Taruna</span><span class="text-[10px] text-brand-ink/50 bg-black/5 px-2 py-0.5 rounded-full font-medium">Pakaian Khas</span></div>
                                        </td>
                                        <td class="py-2.5 sm:py-3 px-4 sm:px-6 text-right font-bold text-brand-ink font-sans whitespace-nowrap">Rp. 470.000,-</td>
                                    </tr>
                                    <tr class="hover:bg-brand-softmist/40 transition-colors">
                                        <td class="py-2.5 sm:py-3 px-4 text-center font-bold text-brand-darkred bg-[#F9F8F6] border-r border-brand-ink/10">4.</td>
                                        <td class="py-2.5 sm:py-3 px-4 sm:px-6 font-semibold text-brand-ink border-r border-brand-ink/10">
                                            <div class="flex items-center justify-between gap-2"><span>Seragam Batik</span><span class="text-[10px] text-brand-ink/50 bg-black/5 px-2 py-0.5 rounded-full font-medium">Pakaian Khas</span></div>
                                        </td>
                                        <td class="py-2.5 sm:py-3 px-4 sm:px-6 text-right font-bold text-brand-ink font-sans whitespace-nowrap">Rp. 140.000,-</td>
                                    </tr>
                                    <tr class="hover:bg-brand-softmist/40 transition-colors">
                                        <td class="py-2.5 sm:py-3 px-4 text-center font-bold text-brand-darkred bg-[#F9F8F6] border-r border-brand-ink/10">5.</td>
                                        <td class="py-2.5 sm:py-3 px-4 sm:px-6 font-semibold text-brand-ink border-r border-brand-ink/10">
                                            <div class="flex items-center justify-between gap-2"><span>Seragam Praktik</span><span class="text-[10px] text-brand-ink/50 bg-black/5 px-2 py-0.5 rounded-full font-medium">Pakaian Kejuruan</span></div>
                                        </td>
                                        <td class="py-2.5 sm:py-3 px-4 sm:px-6 text-right font-bold text-brand-ink font-sans whitespace-nowrap">Rp. 165.000,-</td>
                                    </tr>
                                    <tr class="hover:bg-brand-softmist/40 transition-colors">
                                        <td class="py-2.5 sm:py-3 px-4 text-center font-bold text-brand-darkred bg-[#F9F8F6] border-r border-brand-ink/10">6.</td>
                                        <td class="py-2.5 sm:py-3 px-4 sm:px-6 font-semibold text-brand-ink border-r border-brand-ink/10">
                                            <div class="flex items-center justify-between gap-2"><span>Seragam Muslim</span><span class="text-[10px] text-brand-ink/50 bg-black/5 px-2 py-0.5 rounded-full font-medium">Pakaian Khas</span></div>
                                        </td>
                                        <td class="py-2.5 sm:py-3 px-4 sm:px-6 text-right font-bold text-brand-ink font-sans whitespace-nowrap">Rp. 130.000,-</td>
                                    </tr>
                                    <tr class="hover:bg-brand-softmist/40 transition-colors">
                                        <td class="py-2.5 sm:py-3 px-4 text-center font-bold text-brand-darkred bg-[#F9F8F6] border-r border-brand-ink/10">7.</td>
                                        <td class="py-2.5 sm:py-3 px-4 sm:px-6 font-semibold text-brand-ink border-r border-brand-ink/10">
                                            <div class="flex items-center justify-between gap-2"><span>Seragam Putih</span><span class="text-[10px] text-brand-ink/50 bg-black/5 px-2 py-0.5 rounded-full font-medium">Pakaian Harian</span></div>
                                        </td>
                                        <td class="py-2.5 sm:py-3 px-4 sm:px-6 text-right font-bold text-brand-ink font-sans whitespace-nowrap">Rp. 80.000,-</td>
                                    </tr>
                                    <tr class="hover:bg-brand-softmist/40 transition-colors">
                                        <td class="py-2.5 sm:py-3 px-4 text-center font-bold text-brand-darkred bg-[#F9F8F6] border-r border-brand-ink/10">8.</td>
                                        <td class="py-2.5 sm:py-3 px-4 sm:px-6 font-semibold text-brand-ink border-r border-brand-ink/10">
                                            <div class="flex items-center justify-between gap-2"><span>Topi Penus</span><span class="text-[10px] text-brand-ink/50 bg-black/5 px-2 py-0.5 rounded-full font-medium">Atribut</span></div>
                                        </td>
                                        <td class="py-2.5 sm:py-3 px-4 sm:px-6 text-right font-bold text-brand-ink font-sans whitespace-nowrap">Rp. 55.000,-</td>
                                    </tr>
                                    <tr class="hover:bg-brand-softmist/40 transition-colors">
                                        <td class="py-2.5 sm:py-3 px-4 text-center font-bold text-brand-darkred bg-[#F9F8F6] border-r border-brand-ink/10">9.</td>
                                        <td class="py-2.5 sm:py-3 px-4 sm:px-6 font-semibold text-brand-ink border-r border-brand-ink/10">
                                            <div class="flex items-center justify-between gap-2"><span>Dasi Penus</span><span class="text-[10px] text-brand-ink/50 bg-black/5 px-2 py-0.5 rounded-full font-medium">Atribut</span></div>
                                        </td>
                                        <td class="py-2.5 sm:py-3 px-4 sm:px-6 text-right font-bold text-brand-ink font-sans whitespace-nowrap">Rp. 45.000,-</td>
                                    </tr>
                                    <tr class="hover:bg-brand-softmist/40 transition-colors">
                                        <td class="py-2.5 sm:py-3 px-4 text-center font-bold text-brand-darkred bg-[#F9F8F6] border-r border-brand-ink/10">10.</td>
                                        <td class="py-2.5 sm:py-3 px-4 sm:px-6 font-semibold text-brand-ink border-r border-brand-ink/10">
                                            <div class="flex items-center justify-between gap-2"><span>7 Badge Sekolah</span><span class="text-[10px] text-brand-ink/50 bg-black/5 px-2 py-0.5 rounded-full font-medium">Atribut</span></div>
                                        </td>
                                        <td class="py-2.5 sm:py-3 px-4 sm:px-6 text-right font-bold text-brand-ink font-sans whitespace-nowrap">Rp. 65.000,-</td>
                                    </tr>
                                    <tr class="hover:bg-brand-softmist/40 transition-colors">
                                        <td class="py-2.5 sm:py-3 px-4 text-center font-bold text-brand-darkred bg-[#F9F8F6] border-r border-brand-ink/10">11.</td>
                                        <td class="py-2.5 sm:py-3 px-4 sm:px-6 font-semibold text-brand-ink border-r border-brand-ink/10">
                                            <div class="flex items-center justify-between gap-2"><span>Sepatu PDL</span><span class="text-[10px] text-brand-ink/50 bg-black/5 px-2 py-0.5 rounded-full font-medium">Alas Kaki</span></div>
                                        </td>
                                        <td class="py-2.5 sm:py-3 px-4 sm:px-6 text-right font-bold text-brand-ink font-sans whitespace-nowrap">Rp. 220.000,-</td>
                                    </tr>
                                    <tr class="hover:bg-brand-softmist/40 transition-colors">
                                        <td class="py-2.5 sm:py-3 px-4 text-center font-bold text-brand-darkred bg-[#F9F8F6] border-r border-brand-ink/10">12.</td>
                                        <td class="py-2.5 sm:py-3 px-4 sm:px-6 font-semibold text-brand-ink border-r border-brand-ink/10">
                                            <div class="flex items-center justify-between gap-2"><span>Sepatu Cats</span><span class="text-[10px] text-brand-ink/50 bg-black/5 px-2 py-0.5 rounded-full font-medium">Alas Kaki</span></div>
                                        </td>
                                        <td class="py-2.5 sm:py-3 px-4 sm:px-6 text-right font-bold text-brand-ink font-sans whitespace-nowrap">Rp. 150.000,-</td>
                                    </tr>
                                    <tr class="hover:bg-brand-softmist/40 transition-colors">
                                        <td class="py-2.5 sm:py-3 px-4 text-center font-bold text-brand-darkred bg-[#F9F8F6] border-r border-brand-ink/10">13.</td>
                                        <td class="py-2.5 sm:py-3 px-4 sm:px-6 font-semibold text-brand-ink border-r border-brand-ink/10">
                                            <div class="flex items-center justify-between gap-2"><span>Pin Logo SMK</span><span class="text-[10px] text-brand-ink/50 bg-black/5 px-2 py-0.5 rounded-full font-medium">Atribut</span></div>
                                        </td>
                                        <td class="py-2.5 sm:py-3 px-4 sm:px-6 text-right font-bold text-brand-ink font-sans whitespace-nowrap">Rp. 45.000,-</td>
                                    </tr>
                                </tbody>
                                <tfoot>
                                    <tr class="bg-[#2D6898] text-white font-extrabold text-xs sm:text-sm">
                                        <td colSpan="2" class="py-3 px-4 sm:px-6 text-center tracking-wider uppercase border-r border-white/20">
                                            JUMLAH
                                        </td>
                                        <td class="py-3 px-4 sm:px-6 text-right font-sans tracking-wide whitespace-nowrap">
                                            Rp. 1.975.000,-
                                        </td>
                                    </tr>
                                    <tr class="bg-[#E5A823] text-[#1A1D20] font-black text-sm sm:text-base border-t-2 border-[#C88E12]">
                                        <td colSpan="2" class="py-4 px-4 sm:px-6 text-center tracking-wider uppercase border-r border-[#C88E12]">
                                            TOTAL PEMBIAYAAN
                                        </td>
                                        <td class="py-4 px-4 sm:px-6 text-right font-sans tracking-tight text-base sm:text-lg whitespace-nowrap text-[#5C0B12]">
                                            Rp. 5.850.000,-
                                        </td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>

                    <div class="p-4 rounded-2xl bg-white border border-brand-ink/10 text-xs text-brand-ink/75 space-y-1.5 shadow-xs">
                        <div class="flex items-center gap-2 font-bold text-brand-darkred">
                            <svg class="w-4 h-4 text-brand-darkred" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            <span>Catatan Resmi Pembiayaan & Pengambilan Atribut:</span>
                        </div>
                        <p class="leading-relaxed pl-5">
                            • Total biaya cash di atas mencakup seluruh perlengkapan seragam (7 stel/jenis pakaian, 2 jenis sepatu PDL & Cats, dan 4 atribut sekolah) serta operasional awal pendidikan bulan Juli.
                        </p>
                        <p class="leading-relaxed pl-5">
                            • Pengukuran ukuran seragam dan sepatu dilakukan langsung di kampus sekolah saat konfirmasi daftar ulang.
                        </p>
                    </div>
                </div>

                <!-- SECTION 3: 4 KOMPETENSI KEAHLIAN (JURUSAN) -->
                <div id="jurusan" class="space-y-5 pt-4">
                    <div class="flex items-center gap-3 border-b border-brand-ink/10 pb-3">
                        <div class="w-8 h-8 rounded-full bg-brand-darkred text-white flex items-center justify-center font-bold text-xs shrink-0">
                            3
                        </div>
                        <div>
                            <span class="font-editorial-eyebrow text-brand-darkred tracking-eyebrow block text-[11px] sm:text-xs font-bold">
                                PILIHAN JURUSAN UNGGULAN
                            </span>
                            <h2 class="font-editorial-h3 text-brand-ink font-bold text-xl sm:text-2xl">
                                4 Kompetensi Keahlian
                            </h2>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div class="bg-white rounded-card p-5 sm:p-6 shadow-softpill border border-brand-ink/10 flex flex-col justify-between hover:shadow-softpill hover:-translate-y-1 transition-all duration-300 group">
                            <div class="space-y-2.5">
                                <div class="flex items-center justify-between">
                                    <span class="bg-brand-darkred text-white font-bold text-xs px-3 py-1 rounded-full shadow-xs">RPL</span>
                                    <span class="text-[11px] font-bold text-brand-darkred font-mono">PPLG / RPL</span>
                                </div>
                                <h3 class="font-display font-bold uppercase tracking-wide text-base sm:text-lg text-brand-ink group-hover:text-brand-darkred transition-colors">
                                    Rekayasa Perangkat Lunak
                                </h3>
                                <p class="text-xs text-brand-ink/75 leading-relaxed">
                                    Fokus pada pengembangan aplikasi web, software desktop, mobile programming, database, dan gim digital berstandar industri modern.
                                </p>
                            </div>
                            <div class="mt-4 pt-3 border-t border-brand-ink/10 flex items-center justify-between text-xs">
                                <span class="font-semibold text-brand-darkred text-[11px]">★ Kerjasama Devaccto IT & Peluang Kerja Tinggi</span>
                                <button type="button" @click="handleOpenKonsultasi('Rekayasa Perangkat Lunak (RPL)')" class="text-brand-ink/70 hover:text-brand-darkred font-bold text-[11px] underline underline-offset-2 cursor-pointer">
                                    Pilih Jurusan
                                </button>
                            </div>
                        </div>

                        <div class="bg-white rounded-card p-5 sm:p-6 shadow-softpill border border-brand-ink/10 flex flex-col justify-between hover:shadow-softpill hover:-translate-y-1 transition-all duration-300 group">
                            <div class="space-y-2.5">
                                <div class="flex items-center justify-between">
                                    <span class="bg-brand-darkred text-white font-bold text-xs px-3 py-1 rounded-full shadow-xs">TKJ</span>
                                    <span class="text-[11px] font-bold text-brand-darkred font-mono">TJKT / TKJ</span>
                                </div>
                                <h3 class="font-display font-bold uppercase tracking-wide text-base sm:text-lg text-brand-ink group-hover:text-brand-darkred transition-colors">
                                    Teknik Komputer dan Jaringan
                                </h3>
                                <p class="text-xs text-brand-ink/75 leading-relaxed">
                                    Penguasaan infrastruktur jaringan, konfigurasi server, cloud computing, fiber optic, dan keamanan sistem komputer berlisensi resmi.
                                </p>
                            </div>
                            <div class="mt-4 pt-3 border-t border-brand-ink/10 flex items-center justify-between text-xs">
                                <span class="font-semibold text-brand-darkred text-[11px]">★ Sertifikasi Industri Mikrotik & Cisco</span>
                                <button type="button" @click="handleOpenKonsultasi('Teknik Komputer dan Jaringan (TKJ)')" class="text-brand-ink/70 hover:text-brand-darkred font-bold text-[11px] underline underline-offset-2 cursor-pointer">
                                    Pilih Jurusan
                                </button>
                            </div>
                        </div>

                        <div class="bg-white rounded-card p-5 sm:p-6 shadow-softpill border border-brand-ink/10 flex flex-col justify-between hover:shadow-softpill hover:-translate-y-1 transition-all duration-300 group">
                            <div class="space-y-2.5">
                                <div class="flex items-center justify-between">
                                    <span class="bg-brand-darkred text-white font-bold text-xs px-3 py-1 rounded-full shadow-xs">MM</span>
                                    <span class="text-[11px] font-bold text-brand-darkred font-mono">DKV / MM</span>
                                </div>
                                <h3 class="font-display font-bold uppercase tracking-wide text-base sm:text-lg text-brand-ink group-hover:text-brand-darkred transition-colors">
                                    Multimedia
                                </h3>
                                <p class="text-xs text-brand-ink/75 leading-relaxed">
                                    Keahlian desain grafis, animasi 2D/3D, audio-video editing, fotografi, dan produksi konten digital kreatif berdaya saing.
                                </p>
                            </div>
                            <div class="mt-4 pt-3 border-t border-brand-ink/10 flex items-center justify-between text-xs">
                                <span class="font-semibold text-brand-darkred text-[11px]">★ Lab Multimedia & Broadcasting Lengkap</span>
                                <button type="button" @click="handleOpenKonsultasi('Multimedia (MM)')" class="text-brand-ink/70 hover:text-brand-darkred font-bold text-[11px] underline underline-offset-2 cursor-pointer">
                                    Pilih Jurusan
                                </button>
                            </div>
                        </div>

                        <div class="bg-white rounded-card p-5 sm:p-6 shadow-softpill border border-brand-ink/10 flex flex-col justify-between hover:shadow-softpill hover:-translate-y-1 transition-all duration-300 group">
                            <div class="space-y-2.5">
                                <div class="flex items-center justify-between">
                                    <span class="bg-brand-darkred text-white font-bold text-xs px-3 py-1 rounded-full shadow-xs">PKM</span>
                                    <span class="text-[11px] font-bold text-brand-darkred font-mono">AKL / PKM</span>
                                </div>
                                <h3 class="font-display font-bold uppercase tracking-wide text-base sm:text-lg text-brand-ink group-hover:text-brand-darkred transition-colors">
                                    Perbankan dan Keuangan Mikro
                                </h3>
                                <p class="text-xs text-brand-ink/75 leading-relaxed">
                                    Pendidikan akuntansi perbankan syariah/konvensional, pengelolaan kas teller, administrasi pembukuan software Zahir, dan perbankan digital.
                                </p>
                            </div>
                            <div class="mt-4 pt-3 border-t border-brand-ink/10 flex items-center justify-between text-xs">
                                <span class="font-semibold text-brand-darkred text-[11px]">★ Bank Mini Sekolah & Kerjasama Bank Mandiri</span>
                                <button type="button" @click="handleOpenKonsultasi('Perbankan dan Keuangan Mikro (PKM)')" class="text-brand-ink/70 hover:text-brand-darkred font-bold text-[11px] underline underline-offset-2 cursor-pointer">
                                    Pilih Jurusan
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- SECTION 4: PROGRAM UNGGULAN & TALENT DAY -->
                <div id="program-unggulan" class="space-y-6 pt-4">
                    <div class="flex items-center gap-3 border-b border-brand-ink/10 pb-3">
                        <div class="w-8 h-8 rounded-full bg-brand-darkred text-white flex items-center justify-center font-bold text-xs shrink-0">
                            4
                        </div>
                        <div>
                            <span class="font-editorial-eyebrow text-brand-darkred tracking-eyebrow block text-[11px] sm:text-xs font-bold">
                                PENGEMBANGAN KARAKTER & MINAT
                            </span>
                            <h2 class="font-editorial-h3 text-brand-ink font-bold text-xl sm:text-2xl">
                                Program Unggulan & Talent Day
                            </h2>
                        </div>
                    </div>

                    <!-- 5 Program Unggulan -->
                    <div class="bg-white rounded-card p-6 shadow-softpill border border-brand-ink/10 space-y-4">
                        <div class="flex items-center gap-2">
                            <svg class="w-5 h-5 text-brand-darkred" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/></svg>
                            <h3 class="font-sans font-bold text-base text-brand-ink">
                                5 Program Unggulan Sekolah
                            </h3>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3">
                            <div class="p-3.5 rounded-2xl bg-brand-softmist/60 border border-brand-ink/5 space-y-1">
                                <div class="flex items-center gap-2">
                                    <span class="w-5 h-5 rounded-full bg-brand-darkred text-white font-bold text-[10px] flex items-center justify-center shrink-0">1</span>
                                    <h4 class="font-sans font-bold text-xs sm:text-sm text-brand-ink">English Camp</h4>
                                </div>
                                <p class="text-[11px] text-brand-ink/70 leading-relaxed pl-7">Pembekalan intensif percakapan bahasa Inggris aktif untuk kesiapan kerja global.</p>
                            </div>
                            <div class="p-3.5 rounded-2xl bg-brand-softmist/60 border border-brand-ink/5 space-y-1">
                                <div class="flex items-center gap-2">
                                    <span class="w-5 h-5 rounded-full bg-brand-darkred text-white font-bold text-[10px] flex items-center justify-center shrink-0">2</span>
                                    <h4 class="font-sans font-bold text-xs sm:text-sm text-brand-ink">DUQUBA</h4>
                                </div>
                                <p class="text-[11px] text-brand-ink/70 leading-relaxed pl-7">Dhuha, Al-Qur'an dan Bahasa — pembiasaan ibadah sunnah & literasi islami setiap pagi.</p>
                            </div>
                            <div class="p-3.5 rounded-2xl bg-brand-softmist/60 border border-brand-ink/5 space-y-1">
                                <div class="flex items-center gap-2">
                                    <span class="w-5 h-5 rounded-full bg-brand-darkred text-white font-bold text-[10px] flex items-center justify-center shrink-0">3</span>
                                    <h4 class="font-sans font-bold text-xs sm:text-sm text-brand-ink">Tahfidz Al-Qur'an</h4>
                                </div>
                                <p class="text-[11px] text-brand-ink/70 leading-relaxed pl-7">Program bimbingan menghafal Al-Qur'an terstruktur bagi taruna/taruni.</p>
                            </div>
                            <div class="p-3.5 rounded-2xl bg-brand-softmist/60 border border-brand-ink/5 space-y-1">
                                <div class="flex items-center gap-2">
                                    <span class="w-5 h-5 rounded-full bg-brand-darkred text-white font-bold text-[10px] flex items-center justify-center shrink-0">4</span>
                                    <h4 class="font-sans font-bold text-xs sm:text-sm text-brand-ink">Public Speaking</h4>
                                </div>
                                <p class="text-[11px] text-brand-ink/70 leading-relaxed pl-7">Pelatihan retorika, kepemimpinan, dan komunikasi percaya diri di depan umum.</p>
                            </div>
                            <div class="p-3.5 rounded-2xl bg-brand-softmist/60 border border-brand-ink/5 space-y-1">
                                <div class="flex items-center gap-2">
                                    <span class="w-5 h-5 rounded-full bg-brand-darkred text-white font-bold text-[10px] flex items-center justify-center shrink-0">5</span>
                                    <h4 class="font-sans font-bold text-xs sm:text-sm text-brand-ink">Devaccto IT</h4>
                                </div>
                                <p class="text-[11px] text-brand-ink/70 leading-relaxed pl-7">Development and Acceleration Troops — wadah talenta IT elite siswa sekolah.</p>
                            </div>
                        </div>
                    </div>

                    <!-- 8 Talent Day -->
                    <div class="bg-white rounded-card p-6 shadow-softpill border border-brand-ink/10 space-y-3.5">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-2">
                                <svg class="w-5 h-5 text-[#E5A823]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z"/></svg>
                                <h3 class="font-sans font-bold text-base text-brand-ink">
                                    Talent Day (8 Pilihan Minat)
                                </h3>
                            </div>
                            <span class="text-[11px] font-bold text-brand-darkred bg-brand-darkred/10 px-3 py-0.5 rounded-full">
                                Eksplorasi Bakat
                            </span>
                        </div>

                        <div class="grid grid-cols-2 sm:grid-cols-4 gap-2.5">
                            @foreach(['Animasi', 'Broadcasting', 'Programing', 'Elektronika', 'Musik Tradisional', 'Musik Modern', 'Tata Boga', 'Teater'] as $idx => $talent)
                            <div class="flex items-center gap-2 p-2.5 rounded-xl bg-[#F9F8F6] border border-brand-ink/5 text-xs font-bold text-brand-ink">
                                <span class="text-brand-darkred font-mono text-xs">{{ $idx + 1 }}.</span>
                                <span>{{ $talent }}</span>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- SECTION 5: 16 EKSTRAKURIKULER -->
                <div id="ekstrakurikuler" class="space-y-4 pt-4">
                    <div class="flex items-center gap-3 border-b border-brand-ink/10 pb-3">
                        <div class="w-8 h-8 rounded-full bg-brand-darkred text-white flex items-center justify-center font-bold text-xs shrink-0">
                            5
                        </div>
                        <div>
                            <span class="font-editorial-eyebrow text-brand-darkred tracking-eyebrow block text-[11px] sm:text-xs font-bold">
                                AKTIVITAS KESISWAAN
                            </span>
                            <h2 class="font-editorial-h3 text-brand-ink font-bold text-xl sm:text-2xl">
                                16 Ekstrakurikuler
                            </h2>
                        </div>
                    </div>

                    <div class="bg-white rounded-card p-6 shadow-softpill border border-brand-ink/10">
                        <p class="text-xs text-brand-ink/70 mb-4">
                            SMK Plus Pelita Nusantara menyediakan 16 ekstrakurikuler terakreditasi untuk mengasah kepemimpinan, olahraga, seni, dan spiritual siswa:
                        </p>

                        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-2.5">
                            @php
                            $ekskulList = [
                                ['nama' => 'Pramuka', 'status' => 'Wajib', 'kategori' => 'Kepanduan'],
                                ['nama' => 'English Club', 'status' => 'Pilihan', 'kategori' => 'Bahasa'],
                                ['nama' => 'Japanese Club', 'status' => 'Pilihan', 'kategori' => 'Bahasa'],
                                ['nama' => 'Accounting Club', 'status' => 'Pilihan', 'kategori' => 'Akademik'],
                                ['nama' => 'Majalah Dinding', 'status' => 'Pilihan', 'kategori' => 'Literasi'],
                                ['nama' => 'Pencak Silat', 'status' => 'Pilihan', 'kategori' => 'Bela Diri'],
                                ['nama' => 'Paduan Suara', 'status' => 'Pilihan', 'kategori' => 'Seni Suara'],
                                ['nama' => 'Taekwondo', 'status' => 'Pilihan', 'kategori' => 'Bela Diri'],
                                ['nama' => 'Paskibra', 'status' => 'Pilihan', 'kategori' => 'Kepemimpinan'],
                                ['nama' => 'Futsal', 'status' => 'Pilihan', 'kategori' => 'Olahraga'],
                                ['nama' => 'Basket', 'status' => 'Pilihan', 'kategori' => 'Olahraga'],
                                ['nama' => 'Badminton', 'status' => 'Pilihan', 'kategori' => 'Olahraga'],
                                ['nama' => 'Hadroh', 'status' => 'Pilihan', 'kategori' => 'Keagamaan'],
                                ['nama' => 'PMR', 'status' => 'Pilihan', 'kategori' => 'Kemanusiaan'],
                                ['nama' => 'Rohis', 'status' => 'Pilihan', 'kategori' => 'Keagamaan'],
                                ['nama' => 'Rokris', 'status' => 'Pilihan', 'kategori' => 'Keagamaan'],
                            ];
                            @endphp

                            @foreach($ekskulList as $idx => $ekskul)
                            <div class="p-3 rounded-2xl border text-xs flex flex-col justify-between {{ $ekskul['status'] === 'Wajib' ? 'bg-brand-darkred/5 border-brand-darkred/30 text-brand-darkred' : 'bg-[#F9F8F6] border-brand-ink/10 text-brand-ink' }}">
                                <div class="flex items-center justify-between mb-1">
                                    <span class="font-mono text-[11px] opacity-60">{{ $idx + 1 }}.</span>
                                    <span class="text-[9px] font-extrabold uppercase px-2 py-0.5 rounded-full {{ $ekskul['status'] === 'Wajib' ? 'bg-brand-darkred text-white' : 'bg-black/5 text-brand-ink/60' }}">
                                        {{ $ekskul['status'] }}
                                    </span>
                                </div>
                                <span class="font-bold text-xs leading-snug">{{ $ekskul['nama'] }}</span>
                                <span class="text-[10px] text-brand-ink/50 mt-1">{{ $ekskul['kategori'] }}</span>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- SECTION 6: 18 PRESTASI SISWA -->
                <div id="prestasi" class="space-y-4 pt-4">
                    <div class="flex items-center gap-3 border-b border-brand-ink/10 pb-3">
                        <div class="w-8 h-8 rounded-full bg-brand-darkred text-white flex items-center justify-center font-bold text-xs shrink-0">
                            6
                        </div>
                        <div>
                            <span class="font-editorial-eyebrow text-brand-darkred tracking-eyebrow block text-[11px] sm:text-xs font-bold">
                                REKAM JEJAK KEBERHASILAN
                            </span>
                            <h2 class="font-editorial-h3 text-brand-ink font-bold text-xl sm:text-2xl">
                                18 Prestasi Siswa Terpilih
                            </h2>
                        </div>
                    </div>

                    <div class="bg-white rounded-card p-6 shadow-softpill border border-brand-ink/10 space-y-3">
                        <p class="text-xs text-brand-ink/70">
                            Bukti nyata kualitas pembinaan taruna dan taruni SMK Plus Pelita Nusantara dalam ajang kompetisi tingkat Kota, Provinsi, hingga Nasional:
                        </p>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 text-xs">
                            @php
                            $prestasiList = [
                                "Juara 3 Nasional KKSI Story Telling 2021",
                                "Peringkat 39 Nasional KKSI Animasi 2021",
                                "Juara 3 Film Pendek Hut KKS ke-47",
                                "Juara 1 Nasional Mewarnai Rajawali Operation",
                                "Juara Umum Lomba Kwaci Ireng Jilid 2",
                                "Juara 2 Tari Tradisional Univ. Trisakti",
                                "Juara 1 Futsal Tropeo AH Project",
                                "Penyelenggara Vaksinasi Covid 19",
                                "Siaran bersama Radio Tegar Beriman",
                                "Terpilih mengikuti BootCamp Recruitment di Walden Global Service",
                                "Juara Umum Pencak Silat tingkat Daerah",
                                "Juara 1 Futsal Antar Pelajar Kab. Bogor",
                                "Juara 2 Tinju POPDA Se Jawa Barat",
                                "Juara 2 Lomba INC LKS SMK Kab. Bogor",
                                "Juara 1 Lomba E-Sport Mobile Legend tingkat Kabupaten",
                                "Juara 3 E-Sport tingkat Nasional di Surabaya 2023",
                                "Juara 1 Karate Tingkat Nasional Kemenpora",
                                "Juara Umum 2 Tingkat Nasional Kejuaraan Depok Open Taekwondo 2023"
                            ];
                            @endphp

                            @foreach($prestasiList as $idx => $prestasi)
                            <div class="flex items-start gap-2.5 p-2.5 rounded-xl bg-brand-softmist/50 hover:bg-brand-softmist border border-brand-ink/5 transition-colors">
                                <span class="w-5 h-5 rounded-full bg-[#E5A823] text-[#5C0B12] font-black text-[10px] flex items-center justify-center shrink-0 mt-0.5">
                                    {{ $idx + 1 }}
                                </span>
                                <span class="font-medium text-brand-ink leading-snug">
                                    {{ $prestasi }}
                                </span>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- SECTION 7: MITRA KERJASAMA -->
                <div id="mitra" class="space-y-4 pt-4">
                    <div class="flex items-center gap-3 border-b border-brand-ink/10 pb-3">
                        <div class="w-8 h-8 rounded-full bg-brand-darkred text-white flex items-center justify-center font-bold text-xs shrink-0">
                            7
                        </div>
                        <div>
                            <span class="font-editorial-eyebrow text-brand-darkred tracking-eyebrow block text-[11px] sm:text-xs font-bold">
                                JARINGAN INDUSTRI & AKADEMIK
                            </span>
                            <h2 class="font-editorial-h3 text-brand-ink font-bold text-xl sm:text-2xl">
                                Mitra Kerjasama (Our Partners)
                            </h2>
                        </div>
                    </div>

                    <div class="bg-white rounded-card p-6 shadow-softpill border border-brand-ink/10 space-y-4">
                        <p class="text-xs text-brand-ink/70">
                            Kerjasama resmi sertifikasi, perguruan tinggi kedinasan/negeri, perbankan, dan mitra industri teknologi untuk memastikan lulusan siap kerja:
                        </p>

                        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-3">
                            @php
                            $mitraList = [
                                ['nama' => 'LSP', 'jenis' => 'Sertifikasi Profesi'],
                                ['nama' => 'BNSP', 'jenis' => 'Standar Kompetensi'],
                                ['nama' => 'SEAMEO BIOTROP', 'jenis' => 'Pendidikan Asia Tenggara'],
                                ['nama' => 'PENS Surabaya', 'jenis' => 'Pendidikan Vokasi Tinggi'],
                                ['nama' => 'JGU', 'jenis' => 'Universitas Kemitraan'],
                                ['nama' => 'LP3I', 'jenis' => 'Kampus Vokasi Siap Kerja'],
                                ['nama' => 'Thailand College', 'jenis' => 'Kerjasama Internasional'],
                                ['nama' => 'PT Telkom Indonesia', 'jenis' => 'Mitra Industri Telekomunikasi'],
                                ['nama' => 'Bank Mandiri', 'jenis' => 'Mitra Perbankan'],
                                ['nama' => 'Pintro', 'jenis' => 'Sistem Keuangan Sekolah'],
                                ['nama' => 'Zahir Accounting', 'jenis' => 'Software Akuntansi'],
                            ];
                            @endphp

                            @foreach($mitraList as $mitra)
                            <div class="p-3 rounded-2xl bg-[#F9F8F6] border border-brand-ink/10 flex flex-col justify-center text-center hover:border-brand-darkred/30 transition-all">
                                <span class="font-bold text-xs text-brand-ink leading-snug">{{ $mitra['nama'] }}</span>
                                <span class="text-[10px] text-brand-ink/50 mt-1">{{ $mitra['jenis'] }}</span>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- SECTION 8: FAQ PEMBIAYAAN & PPDB -->
                <div id="faq-biaya" class="space-y-4 pt-4">
                    <div class="flex items-center gap-3 border-b border-brand-ink/10 pb-3">
                        <div class="w-8 h-8 rounded-full bg-brand-darkred text-white flex items-center justify-center font-bold text-xs shrink-0">
                            8
                        </div>
                        <div>
                            <span class="font-editorial-eyebrow text-brand-darkred tracking-eyebrow block text-[11px] sm:text-xs font-bold">
                                INFORMASI LENGKAP
                            </span>
                            <h2 class="font-editorial-h3 text-brand-ink font-bold text-xl sm:text-2xl">
                                Tanya Jawab Seputar Pembiayaan
                            </h2>
                        </div>
                    </div>

                    <div class="space-y-3">
                        @php
                        $faqList = [
                            [
                                'id' => 1,
                                'tanya' => 'Berapa total biaya pendaftaran awal masuk SMK Plus Pelita Nusantara?',
                                'jawab' => 'Total biaya masuk cash adalah Rp 5.850.000,- yang sudah mencakup Rincian Biaya PPDB (Rp 3.875.000,- termasuk SPP bulan Juli, dana pembangunan, pelatihan karakter, foto & simpanan koperasi) serta Rincian Seragam & 13 Item Atribut Resmi Lengkap (Rp 1.975.000,-).'
                            ],
                            [
                                'id' => 2,
                                'tanya' => 'Apakah pembayaran biaya PPDB dapat diangsur / dicicil?',
                                'jawab' => 'Ya, sekolah menyediakan skema pembayaran bertahap (angsuran) untuk memudahkan orang tua murid. Pembayaran tahap pertama dapat diselesaikan saat daftar ulang, dan sisa pembayaran dapat diangsur sesuai kesepakatan tertulis dengan pihak panitia PPDB.'
                            ],
                            [
                                'id' => 3,
                                'tanya' => 'Apa saja seragam dan atribut yang didapatkan oleh siswa baru?',
                                'jawab' => 'Siswa mendapatkan 13 perlengkapan lengkap: Jaket Almamater, Seragam Olahraga, Seragam Taruna, Seragam Batik, Seragam Praktik Kejuruan, Seragam Muslim, Seragam Putih, Topi Penus, Dasi Penus, 7 Badge Sekolah, Sepatu PDL, Sepatu Cats, dan Pin Logo SMK.'
                            ],
                            [
                                'id' => 4,
                                'tanya' => 'Bagaimana cara melakukan pembayaran biaya PPDB?',
                                'jawab' => 'Pembayaran dapat dilakukan langsung secara tunai (cash) di loket panitia PPDB kampus SMK Plus Pelita Nusantara Cibinong, atau transfer melalui rekening resmi yayasan (Bank Mandiri / sistem aplikasi Pintro) dengan verifikasi bukti transfer ke bagian administrasi.'
                            ],
                            [
                                'id' => 5,
                                'tanya' => 'Apa saja kompetensi keahlian (jurusan) yang tersedia?',
                                'jawab' => "Terdapat 4 kompetensi keahlian unggulan berakreditasi 'A' Unggul: Rekayasa Perangkat Lunak (RPL), Teknik Komputer dan Jaringan (TKJ), Multimedia (MM), serta Perbankan dan Keuangan Mikro (PKM)."
                            ],
                            [
                                'id' => 6,
                                'tanya' => 'Apakah ada program beasiswa untuk siswa berprestasi?',
                                'jawab' => "Tersedia beasiswa jalur prestasi akademik (peringkat kelas / nilai rapor) serta prestasi non-akademik (olahraga, seni, tahfidz Al-Qur'an, dan kompetisi tingkat kota/nasional) dengan potongan biaya pendidikan sesuai ketentuan yayasan."
                            ],
                        ];
                        @endphp

                        @foreach($faqList as $faq)
                        <div class="bg-white rounded-[22px] border border-brand-ink/10 overflow-hidden shadow-xs transition-all">
                            <button
                                type="button"
                                @click="toggleFaq({{ $faq['id'] }})"
                                class="w-full px-5 py-4 text-left font-bold text-xs sm:text-sm text-brand-ink flex items-center justify-between gap-3 hover:bg-brand-softmist/40 cursor-pointer"
                            >
                                <span class="flex items-center gap-2">
                                    <span class="w-5 h-5 rounded-full bg-brand-darkred/10 text-brand-darkred text-[11px] font-extrabold flex items-center justify-center shrink-0">
                                        Q
                                    </span>
                                    <span>{{ $faq['tanya'] }}</span>
                                </span>
                                <svg x-show="openFaqId === {{ $faq['id'] }}" class="w-4 h-4 text-brand-darkred shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"/></svg>
                                <svg x-show="openFaqId !== {{ $faq['id'] }}" class="w-4 h-4 text-brand-ink/40 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                            </button>

                            <div x-show="openFaqId === {{ $faq['id'] }}" class="px-5 pb-4 pt-1 text-xs text-brand-ink/80 leading-relaxed border-t border-brand-ink/5 bg-[#FBFBFA] animate-fade-in pl-12" style="display: none;">
                                {{ $faq['jawab'] }}
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>

            </div>
        </div>
    </main>

    <!-- ========================================================
        4. Interactive Modal: Formulir Konsultasi Pembiayaan & PPDB
       ======================================================== -->
    <div x-show="modalKonsultasiOpen" class="fixed inset-0 z-50 bg-brand-ink/60 backdrop-blur-xs flex items-center justify-center p-4 overflow-y-auto" style="display: none;">
        <div class="bg-white rounded-[28px] sm:rounded-[32px] max-w-xl w-full p-6 sm:p-8 shadow-editorial border border-brand-ink/15 my-8 animate-scale-in relative">
            <button
                type="button"
                @click="modalKonsultasiOpen = false"
                class="absolute top-5 right-5 p-2 rounded-full text-brand-ink/50 hover:text-brand-ink hover:bg-brand-softmist/60 transition-colors"
                aria-label="Tutup"
            >
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>

            <div x-show="konsultasiSuccess" class="text-center py-5 space-y-4 animate-scale-in">
                <div class="w-16 h-16 rounded-full bg-[#107c41] text-white flex items-center justify-center mx-auto shadow-md">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                </div>
                <h3 class="font-editorial-h3 text-2xl font-bold text-brand-ink">
                    Data Konsultasi Siap Dikirim!
                </h3>
                <p class="text-xs sm:text-sm text-brand-ink/75 leading-relaxed max-w-md mx-auto">
                    Terima kasih, data calon siswa <strong class="text-brand-darkred" x-text="konsultasiForm.namaLengkap"></strong> telah siap. Silakan klik tombol di bawah untuk langsung terhubung dengan Panitia PPDB via WhatsApp.
                </p>

                <div class="p-4 rounded-2xl bg-[#F9F8F6] border border-brand-ink/10 text-left text-xs space-y-2">
                    <div class="flex justify-between">
                        <span class="text-brand-ink/60">Jurusan Pilihan:</span>
                        <span class="font-bold text-brand-ink" x-text="konsultasiForm.jurusanMinat"></span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-brand-ink/60">Rencana Pembayaran:</span>
                        <span class="font-bold text-brand-ink" x-text="konsultasiForm.rencanaPembayaran"></span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-brand-ink/60">No. WhatsApp:</span>
                        <span class="font-bold text-brand-ink" x-text="konsultasiForm.noHpWa"></span>
                    </div>
                </div>

                <div class="pt-2 flex flex-col sm:flex-row gap-3">
                    <button
                        type="button"
                        @click="handleSendToWhatsApp()"
                        class="w-full py-3 rounded-full bg-[#B72A32] hover:bg-[#7A1018] text-white text-xs font-bold flex items-center justify-center gap-2 cursor-pointer shadow-softpill"
                    >
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>
                        <span>Lanjutkan ke WhatsApp Panitia</span>
                    </button>
                    <button
                        type="button"
                        @click="modalKonsultasiOpen = false"
                        class="w-full py-3 rounded-full border border-brand-ink/20 text-brand-ink hover:bg-brand-softmist/50 text-xs font-bold"
                    >
                        <span>Tutup</span>
                    </button>
                </div>
            </div>

            <form x-show="!konsultasiSuccess" @submit.prevent="handleKonsultasiSubmit()" class="space-y-4">
                <div class="pb-3 border-b border-brand-ink/10">
                    <span class="font-editorial-eyebrow text-brand-darkred tracking-eyebrow text-xs uppercase block font-bold">
                        KONSULTASI PPDB 2024/2025
                    </span>
                    <h3 class="font-editorial-h3 text-brand-ink font-bold text-xl sm:text-2xl mt-0.5">
                        Konsultasi Pembiayaan & Jurusan
                    </h3>
                    <p class="text-xs text-brand-ink/60 mt-1">
                        Silakan isi data untuk menanyakan skema angsuran, jalur beasiswa, atau ketersediaan kuota.
                    </p>
                </div>

                <!-- 1. Nama Lengkap Siswa -->
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-brand-ink/70 mb-1.5">
                        Nama Lengkap Calon Siswa <span class="text-brand-signal">*</span>
                    </label>
                    <input
                        type="text"
                        required
                        placeholder="Contoh: Muhammad Rizky Ramadhan"
                        x-model="konsultasiForm.namaLengkap"
                        class="w-full rounded-full border border-brand-ink/20 px-4 py-2.5 text-xs sm:text-sm text-brand-ink bg-[#F9F8F6] focus:bg-white focus:outline-none focus:border-brand-darkred transition-all"
                    />
                    <span x-show="formErrors.namaLengkap" class="text-xs text-brand-signal font-semibold mt-1 block" x-text="formErrors.namaLengkap"></span>
                </div>

                <!-- 2. NISN & WhatsApp -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-brand-ink/70 mb-1.5">
                            NISN (Opsional)
                        </label>
                        <input
                            type="text"
                            maxlength="10"
                            placeholder="10 Digit NISN SMP/MTs"
                            x-model="konsultasiForm.nisn"
                            class="w-full rounded-full border border-brand-ink/20 px-4 py-2.5 text-xs sm:text-sm text-brand-ink bg-[#F9F8F6] focus:bg-white focus:outline-none focus:border-brand-darkred transition-all"
                        />
                        <span x-show="formErrors.nisn" class="text-xs text-brand-signal font-semibold mt-1 block" x-text="formErrors.nisn"></span>
                    </div>
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-brand-ink/70 mb-1.5">
                            Nomor WhatsApp <span class="text-brand-signal">*</span>
                        </label>
                        <input
                            type="tel"
                            required
                            placeholder="0812xxxxxxxx"
                            x-model="konsultasiForm.noHpWa"
                            class="w-full rounded-full border border-brand-ink/20 px-4 py-2.5 text-xs sm:text-sm text-brand-ink bg-[#F9F8F6] focus:bg-white focus:outline-none focus:border-brand-darkred transition-all"
                        />
                        <span x-show="formErrors.noHpWa" class="text-xs text-brand-signal font-semibold mt-1 block" x-text="formErrors.noHpWa"></span>
                    </div>
                </div>

                <!-- 3. Pilihan Jurusan -->
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-brand-ink/70 mb-1.5">
                        Pilihan Kompetensi Keahlian (Jurusan)
                    </label>
                    <select
                        x-model="konsultasiForm.jurusanMinat"
                        class="w-full rounded-full border border-brand-ink/20 px-4 py-2.5 text-xs sm:text-sm text-brand-ink bg-[#F9F8F6] focus:bg-white focus:outline-none focus:border-brand-darkred transition-all"
                    >
                        <option value="Rekayasa Perangkat Lunak (RPL)">Rekayasa Perangkat Lunak (RPL)</option>
                        <option value="Teknik Komputer dan Jaringan (TKJ)">Teknik Komputer dan Jaringan (TKJ)</option>
                        <option value="Multimedia (MM)">Multimedia (MM)</option>
                        <option value="Perbankan dan Keuangan Mikro (PKM)">Perbankan dan Keuangan Mikro (PKM)</option>
                    </select>
                </div>

                <!-- 4. Rencana Pembayaran -->
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-brand-ink/70 mb-1.5">
                        Rencana Skema Pembayaran
                    </label>
                    <select
                        x-model="konsultasiForm.rencanaPembayaran"
                        class="w-full rounded-full border border-brand-ink/20 px-4 py-2.5 text-xs sm:text-sm text-brand-ink bg-[#F9F8F6] focus:bg-white focus:outline-none focus:border-brand-darkred transition-all"
                    >
                        <option value="Cash Lunas (Rp 5.850.000)">Pembayaran Tunai Lunas (Rp 5.850.000,-)</option>
                        <option value="Bertahap / Cicilan 2-3 Tahap">Skema Angsuran Bertahap (2 - 3 Tahap)</option>
                        <option value="Konsultasi Jalur Beasiswa Prestasi">Konsultasi Jalur Beasiswa Prestasi</option>
                    </select>
                </div>

                <!-- 5. Catatan Khusus -->
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-brand-ink/70 mb-1.5">
                        Pertanyaan / Catatan Tambahan
                    </label>
                    <textarea
                        rows="2"
                        placeholder="Tuliskan pertanyaan seputar biaya, seragam, atau jadwal pendaftaran..."
                        x-model="konsultasiForm.catatan"
                        class="w-full rounded-2xl border border-brand-ink/20 px-4 py-2 text-xs sm:text-sm text-brand-ink bg-[#F9F8F6] focus:bg-white focus:outline-none focus:border-brand-darkred transition-all"
                    ></textarea>
                </div>

                <div class="pt-3 border-t border-brand-ink/10 flex justify-end gap-3">
                    <button
                        type="button"
                        @click="modalKonsultasiOpen = false"
                        class="px-5 py-2.5 rounded-full border border-brand-ink/20 text-brand-ink hover:bg-brand-softmist/50 text-xs font-bold"
                    >
                        Batal
                    </button>
                    <button
                        type="submit"
                        class="px-7 py-2.5 rounded-full bg-[#B72A32] hover:bg-[#7A1018] text-white text-xs font-bold shadow-softpill"
                    >
                        <span>Lanjutkan Konsultasi</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
