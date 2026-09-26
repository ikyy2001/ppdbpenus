@extends('layouts.app')

@section('title', 'Formulir Pendaftaran Siswa PPDB 2027/2028 - SMK Plus Pelita Nusantara')

@section('content')
<div x-data="ppdbForm()" x-init="init()" class="min-h-screen bg-[#F5F4F2] flex flex-col font-sans text-brand-ink antialiased">
    <!-- ========================================================
        FULL-PAGE SUCCESS SCREEN (Setelah Submit Final - Step 3)
        ======================================================== -->
    <template x-if="isSubmittedSuccess">
        <main class="flex-1 max-w-3xl w-full mx-auto px-6 py-12 sm:py-16 animate-fade-in">
            <div class="bg-white rounded-card sm:rounded-[24px] p-8 sm:p-12 shadow-softpill border border-brand-ink/10 text-center relative overflow-hidden">
                <!-- Circular Success Badge -->
                <div class="w-20 h-20 sm:w-24 sm:h-24 rounded-full bg-linear-to-r from-brand-signal to-brand-darkred text-white flex items-center justify-center mx-auto mb-6 shadow-md shadow-brand-darkred/30 border-4 border-brand-mist/50 animate-scale-in">
                    <svg class="w-11 h-11 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/>
                    </svg>
                </div>

                <span class="font-display text-brand-darkred tracking-wider uppercase block mb-2 text-xs font-bold">
                    PENDAFTARAN RESMI DITERIMA
                </span>

                <h1 class="font-display uppercase tracking-wide text-brand-ink leading-tight text-3xl sm:text-4xl font-bold">
                    Pendaftaran Berhasil Dikirim!
                </h1>

                <p class="mt-3 text-brand-ink/75 max-w-xl mx-auto text-sm sm:text-base leading-relaxed">
                    Terima kasih, data pendaftaran calon siswa <strong class="text-brand-darkred font-bold" x-text="formData.namaLengkap"></strong> telah berhasil dicatat oleh sistem PPDB SMK Plus Pelita Nusantara.
                </p>

                <!-- Registration Code Box -->
                <div class="my-8 p-6 rounded-card bg-[#F9F8F6] border border-brand-ink/15 max-w-md mx-auto shadow-xs">
                    <span class="text-brand-ink/60 uppercase tracking-wider block text-xs font-semibold">
                        Nomor Registrasi PPDB
                    </span>
                    <span class="font-mono text-2xl sm:text-3xl font-extrabold text-brand-darkred tracking-wider block mt-2 select-all" x-text="submissionData.noPendaftaran">
                    </span>
                    <span class="text-xs text-brand-ink/50 block mt-1.5 font-medium">
                        Waktu Terdaftar: <span x-text="submissionData.tanggalDaftar"></span>
                    </span>
                </div>

                <!-- Stream Flow Summary Table -->
                <div class="max-w-md mx-auto text-left divide-y divide-brand-ink/10 border-y border-brand-ink/10 py-2 mb-10">
                    <div class="py-2.5 flex justify-between items-center text-sm">
                        <span class="text-brand-ink/60 text-xs font-semibold uppercase tracking-wider">Nama Lengkap</span>
                        <span class="font-bold text-brand-ink" x-text="formData.namaLengkap"></span>
                    </div>
                    <div class="py-2.5 flex justify-between items-center text-sm">
                        <span class="text-brand-ink/60 text-xs font-semibold uppercase tracking-wider">Pilihan Keahlian</span>
                        <span class="font-bold text-brand-darkred text-right" x-text="formData.jurusan || '-'"></span>
                    </div>
                    <div class="py-2.5 flex justify-between items-center text-sm">
                        <span class="text-brand-ink/60 text-xs font-semibold uppercase tracking-wider">Jalur Seleksi</span>
                        <span class="font-medium text-brand-ink" x-text="formData.jalurSeleksi || '-'"></span>
                    </div>
                    <div class="py-2.5 flex justify-between items-center text-sm">
                        <span class="text-brand-ink/60 text-xs font-semibold uppercase tracking-wider">Kontak Pendaftar</span>
                        <span class="font-medium text-brand-ink" x-text="formData.nomorKontakPendaftar"></span>
                    </div>
                    <div class="py-2.5 flex justify-between items-center text-sm">
                        <span class="text-brand-ink/60 text-xs font-semibold uppercase tracking-wider">Status Berkas</span>
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-emerald-50 text-emerald-800 border border-emerald-200">
                            Menunggu Verifikasi Panitia
                        </span>
                    </div>
                </div>

                <!-- Action Buttons using PillButton -->
                <div class="flex flex-col sm:flex-row items-center justify-center gap-3.5 no-print">
                    <button
                        type="button"
                        @click="window.print()"
                        class="w-full sm:w-auto inline-flex items-center justify-center gap-2 bg-brand-darkred hover:bg-brand-deepred text-white px-6 py-3 rounded-full border border-transparent shadow-softpill text-sm font-semibold transition-all active:scale-[0.98] cursor-pointer"
                    >
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                        <span>Cetak Bukti</span>
                    </button>

                    <button
                        type="button"
                        @click="handleDaftarLagi()"
                        class="w-full sm:w-auto inline-flex items-center justify-center gap-2 bg-white hover:bg-brand-softmist text-brand-ink border border-brand-ink/20 px-6 py-3 rounded-full text-sm font-semibold transition-all active:scale-[0.98] cursor-pointer shadow-xs"
                    >
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                        <span>Daftar Siswa Lain</span>
                    </button>

                    <a
                        href="https://ppdb.smkpluspnb.sch.id/login"
                        target="_blank"
                        rel="noreferrer"
                        class="w-full sm:w-auto inline-flex items-center justify-center gap-2 bg-linear-to-r from-brand-signal to-brand-darkred hover:opacity-95 text-white px-7 py-3 rounded-full shadow-md shadow-brand-darkred/25 text-sm font-semibold transition-all active:scale-[0.98] cursor-pointer"
                    >
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/></svg>
                        <span>Masuk ke Akun</span>
                    </a>
                </div>
            </div>
        </main>
    </template>

    <!-- ========================================================
        FORM MAIN VIEW (Hero Banner, Sidebar & Form Cards)
        ======================================================== -->
    <template x-if="!isSubmittedSuccess">
        <div class="flex-1">
            <!-- 2. Editorial Hero Section (Dark Red with Ghost Typography & Rocket Graphic) -->
            <div class="max-w-canvas mx-auto px-6 md:px-12 pt-2 sm:pt-4">
                <div
                    style="background: linear-gradient(135deg, #7A1018 0%, #5C0B12 100%);"
                    class="bg-[#7A1018] rounded-[32px] p-8 sm:p-12 lg:p-14 text-white relative overflow-hidden flex flex-col md:flex-row items-start md:items-center justify-between min-h-[220px] shadow-md border border-white/10"
                >
                    <!-- Background Ghost Typography -->
                    <div class="absolute right-4 top-1/2 -translate-y-1/2 select-none pointer-events-none opacity-10">
                        <x-ghost-heading text="ADMISSIONS" variant="light" />
                    </div>

                    <!-- Orbital Line Vector Curve in Hero -->
                    <x-orbital-line
                        variant="s-curve"
                        color="#D04A43"
                        opacity="0.35"
                        class="absolute inset-0 w-full h-full"
                    />

                    <!-- Left Content -->
                    <div class="z-10 max-w-2xl">
                        <span class="font-display text-[#E5B5B8] tracking-wider uppercase block mb-2.5 text-xs font-bold">
                            PENERIMAAN PESERTA DIDIK BARU 2027/2028
                        </span>
                        <h1 class="text-3xl sm:text-4xl lg:text-5xl font-bold tracking-wide !text-white leading-tight drop-shadow-sm font-display uppercase" style="color: #ffffff !important;">
                            Formulir Pendaftaran Siswa
                        </h1>
                        <p class="mt-3 text-[#E8E8E8] text-sm sm:text-base leading-relaxed max-w-xl">
                            Gerbang awal menuju generasi unggul vokasi Indonesia. Mohon lengkapi seluruh isian data berikut secara cermat dan akurat.
                        </p>
                    </div>

                    <!-- Right Circular Framing Graphic with Enlarged Rocket -->
                    <div class="hidden md:flex z-10 shrink-0 ml-6 items-center justify-center">
                        <div class="w-44 h-44 sm:w-48 sm:h-48 lg:w-56 lg:h-56 rounded-full border-4 border-white/20 bg-white/10 backdrop-blur-xs flex items-center justify-center p-3 shadow-softpill group">
                            <img
                                src="{{ asset('assets/rocket-up.svg') }}"
                                alt="Rocket Vokasi"
                                class="w-36 h-36 sm:w-40 sm:h-40 lg:w-48 lg:h-48 object-contain select-none transition-transform duration-500 group-hover:scale-110 drop-shadow-lg"
                                onerror="this.src='https://ppdb.smkpluspnb.sch.id/assets/images/templates/rocket-up.svg'"
                            />
                        </div>
                    </div>
                </div>

                <!-- Floating Stepper Wizard Pill -->
                <div class="-mt-6 ml-4 sm:ml-8 relative z-20">
                    <div class="rounded-full bg-white/95 backdrop-blur-md shadow-softpill border border-brand-ink/10 px-5 py-2.5 sm:px-8 sm:py-3 inline-flex items-center gap-6 sm:gap-10 select-none overflow-x-auto">
                        <!-- Step 1: Informasi Pendaftar -->
                        <button
                            type="button"
                            @click="if (currentStep === 2) { currentStep = 1; window.scrollTo({ top: 0, behavior: 'smooth' }); }"
                            class="flex items-center gap-2.5 sm:gap-3 cursor-pointer group focus:outline-none"
                        >
                            <div
                                class="w-6 h-6 sm:w-7 sm:h-7 rounded-full flex items-center justify-center font-bold text-xs transition-colors duration-200"
                                :class="currentStep === 1 || currentStep === 2 ? 'bg-brand-darkred text-brand-mist shadow-xs' : 'bg-brand-mist text-brand-ink/60'"
                            >
                                1
                            </div>
                            <span
                                class="text-xs sm:text-sm tracking-tight transition-colors duration-200 font-sans"
                                :class="currentStep === 1 ? 'font-bold text-brand-darkred' : 'font-semibold text-brand-ink/80 group-hover:text-brand-darkred'"
                            >
                                Informasi Pendaftar
                            </span>
                        </button>

                        <!-- Step 2: Validasi Data -->
                        <button
                            type="button"
                            @click="if (currentStep === 1) { handleNext(); }"
                            class="flex items-center gap-2.5 sm:gap-3 cursor-pointer group focus:outline-none"
                        >
                            <div
                                class="w-6 h-6 sm:w-7 sm:h-7 rounded-full flex items-center justify-center font-bold text-xs transition-colors duration-200"
                                :class="currentStep === 2 ? 'bg-brand-darkred text-brand-mist shadow-xs' : 'bg-brand-mist text-brand-ink/50'"
                            >
                                2
                            </div>
                            <span
                                class="text-xs sm:text-sm tracking-tight transition-colors duration-200 font-sans"
                                :class="currentStep === 2 ? 'font-bold text-brand-darkred' : 'font-semibold text-brand-ink/50 group-hover:text-brand-darkred'"
                            >
                                Validasi Data
                            </span>
                        </button>
                    </div>
                </div>
            </div>

            <!-- 3. Main 2-Column Canvas Layout with Selective Modern Cards -->
            <main class="max-w-canvas mx-auto px-6 md:px-12 py-10 md:py-14">
                <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 lg:gap-10 items-start">
                    
                    <!-- ----------------------------------------------------
                        LEFT COLUMN: Sidebar Cards (Jadwal & Petunjuk)
                        ---------------------------------------------------- -->
                    <aside class="lg:col-span-5 xl:col-span-4 space-y-6 order-2 lg:order-1">
                        <!-- Card 1: Jadwal Pendaftaran -->
                        <div class="bg-white rounded-card p-6 shadow-softpill border border-brand-ink/10 transition-all duration-300 hover:shadow-softpill">
                            <div class="flex items-center justify-between pb-3 border-b border-brand-ink/10">
                                <div>
                                    <span class="font-display text-brand-darkred tracking-wider uppercase block text-xs font-bold">
                                        JADWAL SELEKSI
                                    </span>
                                    <h3 class="font-display uppercase tracking-wide text-brand-ink font-bold text-lg mt-0.5">
                                        Gelombang Pendaftaran
                                    </h3>
                                </div>
                                <button
                                    type="button"
                                    @click="isJadwalOpen = !isJadwalOpen"
                                    class="text-brand-ink/60 hover:text-brand-darkred p-1.5 rounded-full hover:bg-brand-softmist transition-colors cursor-pointer"
                                    aria-label="Toggle Jadwal"
                                >
                                    <svg class="w-5 h-5 transition-transform" :class="isJadwalOpen ? '' : 'rotate-180'" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"/>
                                    </svg>
                                </button>
                            </div>

                            <div x-show="isJadwalOpen" x-transition.opacity class="divide-y divide-brand-ink/10 mt-3 animate-fade-in">
                                <div class="py-3.5 space-y-1.5 first:pt-1">
                                    <div class="flex items-start justify-between gap-3">
                                        <h4 class="font-sans font-bold text-sm text-brand-ink leading-snug">
                                            SMK Plus Pelita Nusantara Bogor 2027/2028
                                        </h4>
                                        <span class="text-[10px] font-bold text-white px-2.5 py-0.5 rounded-full shrink-0 uppercase tracking-wider bg-brand-darkred">
                                            normal
                                        </span>
                                    </div>
                                    <div class="flex items-center gap-1.5 text-xs text-brand-ink/70 font-medium">
                                        <svg class="w-3.5 h-3.5 text-brand-darkred shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                        <span>15 September 2026 – 30 September 2028</span>
                                    </div>
                                </div>

                                <div class="py-3.5 space-y-1.5 last:pb-0">
                                    <div class="flex items-start justify-between gap-3">
                                        <h4 class="font-sans font-bold text-sm text-brand-ink leading-snug">
                                            SMK Plus Pelita Nusantara Bogor 2026/2027
                                        </h4>
                                        <span class="text-[10px] font-bold text-white px-2.5 py-0.5 rounded-full shrink-0 uppercase tracking-wider bg-brand-signal">
                                            pindahan
                                        </span>
                                    </div>
                                    <div class="flex items-center gap-1.5 text-xs text-brand-ink/70 font-medium">
                                        <svg class="w-3.5 h-3.5 text-brand-darkred shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                        <span>01 Agustus 2026 – 21 Juni 2027</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Card 2: Petunjuk Pendaftaran -->
                        <div class="bg-white rounded-card p-6 shadow-softpill border border-brand-ink/10 transition-all duration-300 hover:shadow-softpill">
                            <div class="flex items-center justify-between pb-3 border-b border-brand-ink/10">
                                <div>
                                    <span class="font-display text-brand-darkred tracking-wider uppercase block text-xs font-bold">
                                        PANDUAN ALUR
                                    </span>
                                    <h3 class="font-display uppercase tracking-wide text-brand-ink font-bold text-lg mt-0.5">
                                        Petunjuk Pendaftaran
                                    </h3>
                                </div>
                                <button
                                    type="button"
                                    @click="showModalTerm = true"
                                    class="text-brand-darkred hover:text-brand-deepred p-1.5 rounded-full hover:bg-brand-softmist transition-colors cursor-pointer"
                                    title="Buka Perhatian Lengkap"
                                >
                                    <svg class="w-4.5 h-4.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
                                </button>
                            </div>

                            <div class="divide-y divide-brand-ink/10 mt-2">
                                <div class="py-3 flex items-start gap-3">
                                    <span class="w-5 h-5 rounded-full bg-brand-darkred/10 text-brand-darkred text-xs font-bold flex items-center justify-center shrink-0 mt-0.5">1</span>
                                    <div class="text-xs text-brand-ink/80 leading-relaxed">
                                        Untuk Calon Pendaftar Harus Terdaftar sebagai Siswa kelas 9 (sembilan) SMP / Madrasah Tsanawiyah / Sederajat.
                                    </div>
                                </div>
                                <div class="py-3 flex items-start gap-3">
                                    <span class="w-5 h-5 rounded-full bg-brand-darkred/10 text-brand-darkred text-xs font-bold flex items-center justify-center shrink-0 mt-0.5">2</span>
                                    <div class="text-xs text-brand-ink/80 leading-relaxed">
                                        Sungguh-sungguh berminat untuk menjadi siswa SMK Plus Pelita Nusantara
                                    </div>
                                </div>
                                <div class="py-3 flex items-start gap-3">
                                    <span class="w-5 h-5 rounded-full bg-brand-darkred/10 text-brand-darkred text-xs font-bold flex items-center justify-center shrink-0 mt-0.5">3</span>
                                    <div class="text-xs text-brand-ink/80 leading-relaxed">
                                        Isi Form Pendaftaran Berikut dengan Data yang Benar dan Valid.
                                    </div>
                                </div>
                                <div class="py-3 flex items-start gap-3">
                                    <span class="w-5 h-5 rounded-full bg-brand-darkred/10 text-brand-darkred text-xs font-bold flex items-center justify-center shrink-0 mt-0.5">4</span>
                                    <div class="text-xs text-brand-ink/80 leading-relaxed">
                                        Jika ada hal-hal yang ingin ditanyakan mengenai proses pendaftaran silahkan hubungi 
                                        <a href="https://wa.me/6281210868958" target="_blank" class="font-bold text-brand-darkred hover:underline">+62 812-1086-8958</a>
                                    </div>
                                </div>
                                <div class="py-3 flex items-start gap-3">
                                    <span class="w-5 h-5 rounded-full bg-brand-darkred/10 text-brand-darkred text-xs font-bold flex items-center justify-center shrink-0 mt-0.5">5</span>
                                    <div class="text-xs text-brand-ink/80 leading-relaxed">
                                        Untuk informasi lebih lanjut silahkan klik 
                                        <a href="https://images.lekar.co.id/419/2025/file//2025112110025011_brosur_penus_lipat_3_bagian_depan_&_belakang_compressed.pdf" target="_blank" class="font-bold text-brand-signal hover:underline">Link Brosur</a>
                                    </div>
                                </div>
                                <div class="py-3 flex items-start gap-3">
                                    <span class="w-5 h-5 rounded-full bg-brand-darkred/10 text-brand-darkred text-xs font-bold flex items-center justify-center shrink-0 mt-0.5">6</span>
                                    <div class="text-xs text-brand-ink/80 leading-relaxed">
                                        Jika sudah melakukan proses pendaftaran dan mendapatkan username beserta password, silahkan klik 
                                        <a href="https://ppdb.smkpluspnb.sch.id/login" target="_blank" class="font-bold text-brand-darkred hover:underline">Masuk</a> atau klik tombol masuk dipojok kanan atas.
                                    </div>
                                </div>
                            </div>

                            <!-- Unduh Brosur Button -->
                            <div class="mt-5 pt-3 border-t border-brand-ink/10">
                                <a
                                    href="https://images.lekar.co.id/file/pelita/infografis_pelita_nusantara.pdf"
                                    target="_blank"
                                    rel="noreferrer"
                                    class="w-full py-3 inline-flex items-center justify-center gap-2 bg-brand-darkred hover:bg-brand-deepred text-white px-6 rounded-full border border-transparent shadow-softpill text-sm font-semibold transition-all active:scale-[0.98] cursor-pointer"
                                >
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                                    <span>Unduh Alur Proses PDF</span>
                                </a>
                            </div>
                        </div>

                        <!-- Section 3: Bantuan Hotline (Seamless Soft Card) -->
                        <div class="p-5 rounded-card bg-brand-darkred/[0.04] border border-brand-darkred/15 space-y-2.5 shadow-softpill">
                            <div class="flex items-center gap-2 text-brand-darkred">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/></svg>
                                <span class="font-display uppercase tracking-wider text-xs font-bold">
                                    BANTUAN REGISTRASI
                                </span>
                            </div>
                            <p class="text-xs text-brand-ink/75 leading-relaxed">
                                Butuh panduan pengisian formulir? Tim sekretariat PPDB siap membantu melalui kontak hotline resmi.
                            </p>
                            <a
                                href="https://wa.me/6281210868958"
                                target="_blank"
                                rel="noreferrer"
                                class="w-full py-2.5 text-xs font-bold inline-flex items-center justify-center gap-2 bg-linear-to-r from-brand-signal to-brand-darkred hover:opacity-95 text-white px-7 rounded-full shadow-md shadow-brand-darkred/25 transition-all active:scale-[0.98] cursor-pointer"
                            >
                                Chat CS WhatsApp PPDB
                            </a>
                        </div>
                    </aside>

                    <!-- ----------------------------------------------------
                        RIGHT COLUMN: Form Sections / Review Step 2
                        ---------------------------------------------------- -->
                    <div class="lg:col-span-7 xl:col-span-8 order-1 lg:order-2">
                        <!-- Global Error Alert Banner -->
                        <template x-if="isSubmittedAttempt && Object.values(errors).some(Boolean) && currentStep === 1">
                            <div class="rounded-2xl border border-brand-signal/30 bg-brand-signal/10 p-5 mb-6 flex items-start gap-3.5 animate-scale-in">
                                <svg class="w-5 h-5 text-brand-signal shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                <div>
                                    <h4 class="font-bold text-brand-signal text-sm uppercase tracking-wide">
                                        Periksa Kembali Formulir Anda
                                    </h4>
                                    <p class="text-brand-ink/80 text-xs sm:text-sm mt-1 leading-relaxed">
                                        Beberapa kolom wajib bertanda bintang belum terisi dengan benar. Mohon periksa tanda merah pada kolom yang bersangkutan.
                                    </p>
                                </div>
                            </div>
                        </template>

                        <!-- ====================================================
                            STEP 1: FORMULIR INPUT PENDAFTARAN
                            ==================================================== -->
                        <div x-show="currentStep === 1" class="space-y-8 animate-fade-in">
                            <!-- ------------------------------------------------
                                CARD SECTION A: Informasi Personal
                                ------------------------------------------------ -->
                            <div
                                id="section-a"
                                class="bg-white rounded-card p-6 sm:p-8 shadow-softpill border border-brand-ink/10 transition-all duration-300 hover:shadow-softpill space-y-6"
                            >
                                <div class="flex items-center gap-3.5 pb-4 border-b border-brand-ink/10">
                                    <div class="w-10 h-10 rounded-full bg-brand-darkred text-white flex items-center justify-center font-bold text-sm shrink-0 shadow-xs">
                                        A
                                    </div>
                                    <div>
                                        <span class="font-display text-brand-darkred tracking-wider uppercase block text-xs font-bold">
                                            BAGIAN 01 • IDENTITAS DIRI
                                        </span>
                                        <h2 class="font-display uppercase tracking-wide text-brand-ink font-bold text-xl sm:text-2xl mt-0.5">
                                            Informasi Personal Siswa
                                        </h2>
                                    </div>
                                </div>

                                <!-- 1. Nama Lengkap -->
                                <div class="w-full">
                                    <label class="block text-xs font-bold uppercase tracking-wider text-brand-ink/70 mb-2 select-none">
                                        Nama Lengkap <span class="text-brand-signal font-bold ml-1">*</span>
                                    </label>
                                    <input
                                        type="text"
                                        id="namaLengkap"
                                        x-model="formData.namaLengkap"
                                        @blur="handleBlur('namaLengkap')"
                                        placeholder="Isi Nama Lengkap"
                                        class="w-full rounded-full border text-sm text-brand-ink placeholder:text-brand-ink/40 bg-[#F9F8F6] focus:bg-white px-5 py-3 transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-brand-darkred/20 focus:border-brand-darkred"
                                        :class="touched.namaLengkap && errors.namaLengkap ? 'border-brand-signal ring-1 ring-brand-signal/30' : 'border-brand-ink/15 hover:border-brand-ink/35'"
                                    />
                                    <template x-if="touched.namaLengkap && errors.namaLengkap">
                                        <p class="text-[12px] text-brand-signal font-semibold mt-1.5" x-text="errors.namaLengkap"></p>
                                    </template>
                                    <template x-if="!(touched.namaLengkap && errors.namaLengkap)">
                                        <p class="text-[12px] text-brand-ink/60 mt-1.5 leading-normal">
                                            Mohon isi menggunakan huruf kapital di awal kata disertai spasi yang jelas
                                        </p>
                                    </template>
                                </div>

                                <!-- 2. Nama Panggilan -->
                                <div class="w-full">
                                    <label class="block text-xs font-bold uppercase tracking-wider text-brand-ink/70 mb-2 select-none">
                                        Nama Panggilan <span class="text-brand-signal font-bold ml-1">*</span>
                                    </label>
                                    <input
                                        type="text"
                                        id="namaPanggilan"
                                        x-model="formData.namaPanggilan"
                                        @blur="handleBlur('namaPanggilan')"
                                        placeholder="Isi Nama Panggilan"
                                        class="w-full rounded-full border text-sm text-brand-ink placeholder:text-brand-ink/40 bg-[#F9F8F6] focus:bg-white px-5 py-3 transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-brand-darkred/20 focus:border-brand-darkred"
                                        :class="touched.namaPanggilan && errors.namaPanggilan ? 'border-brand-signal ring-1 ring-brand-signal/30' : 'border-brand-ink/15 hover:border-brand-ink/35'"
                                    />
                                    <template x-if="touched.namaPanggilan && errors.namaPanggilan">
                                        <p class="text-[12px] text-brand-signal font-semibold mt-1.5" x-text="errors.namaPanggilan"></p>
                                    </template>
                                </div>

                                <!-- 3. Tempat Lahir Siswa -->
                                <div class="w-full">
                                    <label class="block text-xs font-bold uppercase tracking-wider text-brand-ink/70 mb-2 select-none">
                                        Tempat Lahir Siswa <span class="text-brand-signal font-bold ml-1">*</span>
                                    </label>
                                    <input
                                        type="text"
                                        id="tempatLahir"
                                        x-model="formData.tempatLahir"
                                        @blur="handleBlur('tempatLahir')"
                                        placeholder="Contoh: Bogor"
                                        class="w-full rounded-full border text-sm text-brand-ink placeholder:text-brand-ink/40 bg-[#F9F8F6] focus:bg-white px-5 py-3 transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-brand-darkred/20 focus:border-brand-darkred"
                                        :class="touched.tempatLahir && errors.tempatLahir ? 'border-brand-signal ring-1 ring-brand-signal/30' : 'border-brand-ink/15 hover:border-brand-ink/35'"
                                    />
                                    <template x-if="touched.tempatLahir && errors.tempatLahir">
                                        <p class="text-[12px] text-brand-signal font-semibold mt-1.5" x-text="errors.tempatLahir"></p>
                                    </template>
                                </div>

                                <!-- 4. Tanggal Lahir Siswa (3 Kolom: Tanggal, Bulan, Tahun) -->
                                <div>
                                    <label class="block text-xs font-bold uppercase tracking-wider text-brand-ink/70 mb-2 select-none">
                                        Tanggal Lahir Siswa <span class="text-brand-signal font-bold ml-1">*</span>
                                    </label>
                                    <div class="grid grid-cols-3 gap-2.5 sm:gap-3">
                                        <!-- Hari -->
                                        <div class="relative flex items-center">
                                            <select
                                                id="tanggalLahirHari"
                                                x-model="formData.tanggalLahirHari"
                                                @blur="handleBlur('tanggalLahirHari')"
                                                class="w-full appearance-none rounded-full border text-sm bg-[#F9F8F6] focus:bg-white px-5 py-3 pr-11 transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-brand-darkred/20 focus:border-brand-darkred cursor-pointer"
                                                :class="touched.tanggalLahirHari && errors.tanggalLahirHari ? 'border-brand-signal' : 'border-brand-ink/15'"
                                            >
                                                <option value="" disabled selected>Tanggal</option>
                                                <template x-for="d in 31" :key="d">
                                                    <option :value="String(d)" x-text="d"></option>
                                                </template>
                                            </select>
                                            <div class="absolute right-4 pointer-events-none text-brand-ink/50">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                                            </div>
                                        </div>

                                        <!-- Bulan -->
                                        <div class="relative flex items-center">
                                            <select
                                                id="tanggalLahirBulan"
                                                x-model="formData.tanggalLahirBulan"
                                                @blur="handleBlur('tanggalLahirBulan')"
                                                class="w-full appearance-none rounded-full border text-sm bg-[#F9F8F6] focus:bg-white px-5 py-3 pr-11 transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-brand-darkred/20 focus:border-brand-darkred cursor-pointer"
                                                :class="touched.tanggalLahirBulan && errors.tanggalLahirBulan ? 'border-brand-signal' : 'border-brand-ink/15'"
                                            >
                                                <option value="" disabled selected>Bulan</option>
                                                <option value="1">Januari</option>
                                                <option value="2">Februari</option>
                                                <option value="3">Maret</option>
                                                <option value="4">April</option>
                                                <option value="5">Mei</option>
                                                <option value="6">Juni</option>
                                                <option value="7">Juli</option>
                                                <option value="8">Agustus</option>
                                                <option value="9">September</option>
                                                <option value="10">Oktober</option>
                                                <option value="11">November</option>
                                                <option value="12">Desember</option>
                                            </select>
                                            <div class="absolute right-4 pointer-events-none text-brand-ink/50">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                                            </div>
                                        </div>

                                        <!-- Tahun -->
                                        <div class="relative flex items-center">
                                            <select
                                                id="tanggalLahirTahun"
                                                x-model="formData.tanggalLahirTahun"
                                                @blur="handleBlur('tanggalLahirTahun')"
                                                class="w-full appearance-none rounded-full border text-sm bg-[#F9F8F6] focus:bg-white px-5 py-3 pr-11 transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-brand-darkred/20 focus:border-brand-darkred cursor-pointer"
                                                :class="touched.tanggalLahirTahun && errors.tanggalLahirTahun ? 'border-brand-signal' : 'border-brand-ink/15'"
                                            >
                                                <option value="" disabled selected>Tahun</option>
                                                <template x-for="y in 20" :key="y">
                                                    <option :value="String(2013 - (y - 1))" x-text="2013 - (y - 1)"></option>
                                                </template>
                                            </select>
                                            <div class="absolute right-4 pointer-events-none text-brand-ink/50">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                                            </div>
                                        </div>
                                    </div>
                                    <template x-if="(touched.tanggalLahirHari && errors.tanggalLahirHari) || (touched.tanggalLahirBulan && errors.tanggalLahirBulan) || (touched.tanggalLahirTahun && errors.tanggalLahirTahun)">
                                        <p class="text-[12px] text-brand-signal font-semibold mt-1.5">Lengkapi tanggal, bulan, dan tahun lahir.</p>
                                    </template>
                                </div>

                                <!-- 5. Jenis Kelamin (Radio Buttons) -->
                                <div class="w-full">
                                    <label class="block text-xs font-bold uppercase tracking-wider text-brand-ink/70 mb-2 select-none">
                                        Jenis Kelamin <span class="text-brand-signal font-bold ml-1">*</span>
                                    </label>
                                    <div class="flex items-center gap-6 sm:gap-8 pt-1">
                                        <label class="inline-flex items-center gap-2.5 cursor-pointer select-none group">
                                            <input
                                                type="radio"
                                                value="L"
                                                x-model="formData.jenisKelamin"
                                                class="w-4 h-4 text-brand-darkred border-brand-ink/30 focus:ring-brand-darkred focus:ring-1 cursor-pointer accent-[#7A1018]"
                                            />
                                            <span class="text-sm font-semibold text-brand-ink">Laki-laki</span>
                                        </label>
                                        <label class="inline-flex items-center gap-2.5 cursor-pointer select-none group">
                                            <input
                                                type="radio"
                                                value="P"
                                                x-model="formData.jenisKelamin"
                                                class="w-4 h-4 text-brand-darkred border-brand-ink/30 focus:ring-brand-darkred focus:ring-1 cursor-pointer accent-[#7A1018]"
                                            />
                                            <span class="text-sm font-semibold text-brand-ink">Perempuan</span>
                                        </label>
                                    </div>
                                </div>

                                <!-- 6. Nomor Kartu Keluarga (Optional) -->
                                <div class="w-full">
                                    <label class="block text-xs font-bold uppercase tracking-wider text-brand-ink/70 mb-2 select-none">
                                        Nomor Kartu Keluarga
                                    </label>
                                    <input
                                        type="text"
                                        id="nomorKK"
                                        x-model="formData.nomorKK"
                                        maxlength="16"
                                        placeholder="16 Digit Nomor KK"
                                        class="w-full rounded-full border text-sm text-brand-ink placeholder:text-brand-ink/40 bg-[#F9F8F6] focus:bg-white px-5 py-3 transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-brand-darkred/20 focus:border-brand-darkred border-brand-ink/15 hover:border-brand-ink/35"
                                    />
                                    <p class="text-[12px] text-brand-ink/60 mt-1.5 leading-normal">
                                        Opsional: Dapat dilengkapi nanti pada saat verifikasi fisik berkas
                                    </p>
                                </div>

                                <!-- 7. Alamat Lengkap -->
                                <div class="w-full">
                                    <label class="block text-xs font-bold uppercase tracking-wider text-brand-ink/70 mb-2 select-none">
                                        Alamat Lengkap Domisili
                                    </label>
                                    <textarea
                                        id="alamatLengkap"
                                        x-model="formData.alamatLengkap"
                                        rows="3"
                                        placeholder="Nama jalan, RT/RW, Kelurahan, Kecamatan, Kota/Kabupaten"
                                        class="w-full rounded-[20px] border text-sm text-brand-ink placeholder:text-brand-ink/40 bg-[#F9F8F6] focus:bg-white p-4 transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-brand-darkred/20 focus:border-brand-darkred border-brand-ink/15 hover:border-brand-ink/35"
                                    ></textarea>
                                </div>
                            </div>

                            <!-- ------------------------------------------------
                                CARD SECTION B: Sekolah Tujuan & Jurusan
                                ------------------------------------------------ -->
                            <div
                                id="section-b"
                                class="bg-white rounded-card p-6 sm:p-8 shadow-softpill border border-brand-ink/10 transition-all duration-300 hover:shadow-softpill space-y-6"
                            >
                                <div class="flex items-center gap-3.5 pb-4 border-b border-brand-ink/10">
                                    <div class="w-10 h-10 rounded-full bg-brand-darkred text-white flex items-center justify-center font-bold text-sm shrink-0 shadow-xs">
                                        B
                                    </div>
                                    <div>
                                        <span class="font-display text-brand-darkred tracking-wider uppercase block text-xs font-bold">
                                            BAGIAN 02 • AKADEMIK & KOMPETENSI
                                        </span>
                                        <h2 class="font-display uppercase tracking-wide text-brand-ink font-bold text-xl sm:text-2xl mt-0.5">
                                            Sekolah Tujuan & Peminatan Vokasi
                                        </h2>
                                    </div>
                                </div>

                                <!-- 8. Sekolah Pilihan -->
                                <div>
                                    <label class="block text-xs font-bold uppercase tracking-wider text-brand-ink/70 mb-2 select-none">
                                        Sekolah Pilihan <span class="text-brand-signal font-bold ml-1">*</span>
                                    </label>
                                    <div class="grid grid-cols-1 sm:grid-cols-12 gap-3">
                                        <div class="sm:col-span-4">
                                            <input
                                                type="text"
                                                readonly
                                                value="SMK"
                                                class="w-full rounded-full border text-sm bg-brand-softmist/50 border-brand-ink/15 px-5 py-3 text-brand-ink font-medium cursor-not-allowed"
                                            />
                                        </div>
                                        <div class="sm:col-span-8">
                                            <input
                                                type="text"
                                                readonly
                                                value="SMK Plus Pelita Nusantara Bogor"
                                                class="w-full rounded-full border text-sm bg-brand-softmist/50 border-brand-ink/15 px-5 py-3 text-brand-ink font-medium cursor-not-allowed"
                                            />
                                        </div>
                                    </div>
                                </div>

                                <!-- 9. Tipe Pendaftar -->
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-xs font-bold uppercase tracking-wider text-brand-ink/70 mb-2 select-none">
                                            Tipe Pendaftar <span class="text-brand-signal font-bold ml-1">*</span>
                                        </label>
                                        <div class="relative flex items-center">
                                            <select
                                                id="tipePendaftar"
                                                x-model="formData.tipePendaftar"
                                                class="w-full appearance-none rounded-full border text-sm bg-[#F9F8F6] focus:bg-white px-5 py-3 pr-11 transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-brand-darkred/20 focus:border-brand-darkred border-brand-ink/15 cursor-pointer text-brand-ink font-medium"
                                            >
                                                <option value="Pendaftar Baru">Pendaftar Baru</option>
                                                <option value="Pendaftar Pindahan Tengah Tahun Ajaran">Pendaftar Pindahan Tengah Tahun Ajaran</option>
                                            </select>
                                            <div class="absolute right-4 pointer-events-none text-brand-ink/50">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                                            </div>
                                        </div>
                                    </div>

                                    <template x-if="formData.tipePendaftar === 'Pendaftar Pindahan Tengah Tahun Ajaran'">
                                        <div class="animate-fade-in">
                                            <label class="block text-xs font-bold uppercase tracking-wider text-brand-ink/70 mb-2 select-none">
                                                Pindah di Tahun Ajaran <span class="text-brand-signal font-bold ml-1">*</span>
                                            </label>
                                            <div class="relative flex items-center">
                                                <select
                                                    x-model="formData.pindahTahunAjaran"
                                                    class="w-full appearance-none rounded-full border text-sm bg-[#F9F8F6] focus:bg-white px-5 py-3 pr-11 transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-brand-darkred/20 focus:border-brand-darkred border-brand-ink/15 cursor-pointer text-brand-ink font-medium"
                                                >
                                                    <option value="" disabled selected>Pilih Tahun Ajaran</option>
                                                    <option value="2026/2027">2026/2027</option>
                                                    <option value="2027/2028">2027/2028</option>
                                                </select>
                                                <div class="absolute right-4 pointer-events-none text-brand-ink/50">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                                                </div>
                                            </div>
                                        </div>
                                    </template>
                                </div>

                                <!-- 10. Kelas Pilihan & Tentukan Jurusan -->
                                <div>
                                    <label class="block text-xs font-bold uppercase tracking-wider text-brand-ink/70 mb-2 select-none">
                                        Kelas & Jurusan Peminatan <span class="text-brand-signal font-bold ml-1">*</span>
                                    </label>
                                    <div class="grid grid-cols-1 sm:grid-cols-12 gap-3">
                                        <div class="sm:col-span-5">
                                            <div class="relative flex items-center">
                                                <select
                                                    id="kelasPilihan"
                                                    x-model="formData.kelasPilihan"
                                                    @change="handleInputChange('kelasPilihan', $event.target.value)"
                                                    class="w-full appearance-none rounded-full border text-sm bg-[#F9F8F6] focus:bg-white px-5 py-3 pr-11 transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-brand-darkred/20 focus:border-brand-darkred border-brand-ink/15 cursor-pointer text-brand-ink font-medium"
                                                >
                                                    <option value="" disabled selected>Pilih Kelas</option>
                                                    <option value="Kelas 10">Kelas 10</option>
                                                    <option value="Kelas 11">Kelas 11</option>
                                                    <option value="Kelas 12">Kelas 12</option>
                                                </select>
                                                <div class="absolute right-4 pointer-events-none text-brand-ink/50">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="sm:col-span-7">
                                            <!-- Card-based Jurusan Selection -->
                                            <div class="grid grid-cols-2 gap-2.5" x-data="{}">
                                                <template x-for="j in jurusanList" :key="j.value">
                                                    <div
                                                        @click="if (formData.kelasPilihan) { formData.jurusan = j.value; }"
                                                        :class="formData.kelasPilihan ? (formData.jurusan === j.value ? 'border-brand-darkred bg-brand-softmist/60 ring-2 ring-brand-darkred/20 cursor-pointer' : 'border-brand-ink/15 bg-[#F9F8F6] hover:border-brand-ink/35 hover:bg-white cursor-pointer opacity-100') : (formData.jurusan === j.value ? 'border-brand-darkred bg-brand-softmist/60 ring-2 ring-brand-darkred/20' : 'border-brand-ink/15 bg-[#F9F8F6] opacity-60 cursor-not-allowed')"
                                                        class="relative rounded-xl border-2 p-3 transition-all duration-200 select-none"
                                                    >
                                                        <div class="flex items-center gap-2.5">
                                                            <div class="w-9 h-9 rounded-lg flex items-center justify-center shrink-0 text-lg"
                                                                :class="formData.jurusan === j.value ? 'bg-brand-darkred text-white' : 'bg-brand-softmist text-brand-darkred'">
                                                                <span x-text="j.icon"></span>
                                                            </div>
                                                            <div class="flex-1 min-w-0">
                                                                <p class="font-bold text-xs text-brand-ink truncate" x-text="j.short"></p>
                                                                <p class="text-[10px] text-brand-ink/50 truncate" x-text="j.desc"></p>
                                                            </div>
                                                            <div x-show="formData.jurusan === j.value" class="shrink-0">
                                                                <svg class="w-4 h-4 text-brand-darkred" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </template>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- 11. Jalur Seleksi -->
                                <div>
                                    <label class="block text-xs font-bold uppercase tracking-wider text-brand-ink/70 mb-2 select-none">
                                        Jalur Seleksi Masuk <span class="text-brand-signal font-bold ml-1">*</span>
                                    </label>
                                    <div class="relative flex items-center">
                                        <select
                                            id="jalurSeleksi"
                                            x-model="formData.jalurSeleksi"
                                            class="w-full appearance-none rounded-full border text-sm bg-[#F9F8F6] focus:bg-white px-5 py-3 pr-11 transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-brand-darkred/20 focus:border-brand-darkred border-brand-ink/15 cursor-pointer text-brand-ink font-medium"
                                        >
                                            <option value="" disabled selected>Pilih Jalur Seleksi</option>
                                            <option value="Reguler">Reguler</option>
                                            <option value="Prestasi Akademik">Prestasi Akademik</option>
                                            <option value="Prestasi Non-Akademik">Prestasi Non-Akademik</option>
                                            <option value="Afirmasi / KETM">Afirmasi / KETM</option>
                                        </select>
                                        <div class="absolute right-4 pointer-events-none text-brand-ink/50">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                                        </div>
                                    </div>
                                </div>

                                <!-- 12. Asal Sekolah (Searchable Combobox) -->
                                <div class="w-full relative" x-data="{ open: false, query: '' }" @click.away="open = false">
                                    <label class="block text-xs font-bold uppercase tracking-wider text-brand-ink/70 mb-2 select-none">
                                        Asal Sekolah (SMP / MTs) <span class="text-brand-signal font-bold ml-1">*</span>
                                    </label>

                                    <div class="relative">
                                        <div
                                            @click="open = !open"
                                            class="w-full flex items-center justify-between rounded-full border text-sm bg-[#F9F8F6] py-3 px-5 transition-all duration-200 cursor-pointer border-brand-ink/15 hover:border-brand-ink/35"
                                            :class="open ? 'border-brand-darkred ring-2 ring-brand-darkred/20 bg-white' : ''"
                                        >
                                            <span class="truncate" :class="formData.asalSekolah ? 'text-brand-ink font-medium' : 'text-brand-ink/40'" x-text="formData.asalSekolah || 'Cari atau pilih nama sekolah asal'"></span>
                                            <div class="flex items-center gap-1.5 ml-2 text-brand-ink/50">
                                                <template x-if="formData.asalSekolah">
                                                    <button type="button" @click.stop="formData.asalSekolah = ''" class="hover:text-brand-ink p-0.5 rounded-full">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                                    </button>
                                                </template>
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                                            </div>
                                        </div>

                                        <!-- Dropdown List -->
                                        <div x-show="open" x-transition.opacity class="absolute top-full left-0 right-0 mt-2 z-40 bg-white rounded-card border border-brand-ink/15 shadow-softpill overflow-hidden animate-fade-in">
                                            <div class="p-3 border-b border-brand-ink/10 bg-brand-softmist/40">
                                                <div class="relative flex items-center">
                                                    <svg class="w-4 h-4 absolute left-3.5 text-brand-ink/40" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                                                    <input
                                                        type="text"
                                                        x-model="query"
                                                        placeholder="Ketik nama sekolah..."
                                                        class="w-full bg-white border border-brand-ink/20 rounded-full pl-9 pr-4 py-2 text-xs text-brand-ink placeholder:text-brand-ink/40 focus:outline-none focus:ring-1 focus:ring-brand-darkred focus:border-brand-darkred"
                                                    />
                                                </div>
                                            </div>

                                            <div class="max-h-56 overflow-y-auto divide-y divide-brand-ink/10">
                                                <template x-for="sch in sekolahList.filter(s => s.toLowerCase().includes(query.toLowerCase()))" :key="sch">
                                                    <div
                                                        @click="formData.asalSekolah = sch; open = false; query = '';"
                                                        class="px-4 py-2.5 text-xs sm:text-sm cursor-pointer flex items-center justify-between transition-colors hover:bg-brand-softmist/50"
                                                        :class="formData.asalSekolah === sch ? 'bg-brand-softmist font-semibold text-brand-darkred' : 'text-brand-ink'"
                                                    >
                                                        <span x-text="sch"></span>
                                                        <template x-if="formData.asalSekolah === sch">
                                                            <svg class="w-4 h-4 text-brand-darkred" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                                        </template>
                                                    </div>
                                                </template>
                                            </div>
                                        </div>
                                    </div>
                                    <p class="text-[12px] text-brand-ink/60 mt-1.5 leading-normal">Pilih dari daftar sekolah atau ketik nama sekolah Anda</p>
                                </div>

                                <!-- 13. Kontak Handphone -->
                                <div>
                                    <label class="block text-xs font-bold uppercase tracking-wider text-brand-ink/70 mb-2 select-none">
                                        Kontak Handphone (WhatsApp) <span class="text-brand-signal font-bold ml-1">*</span>
                                    </label>
                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                        <div>
                                            <input
                                                type="tel"
                                                id="nomorKontakPendaftar"
                                                x-model="formData.nomorKontakPendaftar"
                                                @blur="handleBlur('nomorKontakPendaftar')"
                                                placeholder="Handphone Pendaftar"
                                                class="w-full rounded-full border text-sm text-brand-ink placeholder:text-brand-ink/40 bg-[#F9F8F6] focus:bg-white px-5 py-3 transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-brand-darkred/20 focus:border-brand-darkred"
                                                :class="touched.nomorKontakPendaftar && errors.nomorKontakPendaftar ? 'border-brand-signal ring-1 ring-brand-signal/30' : 'border-brand-ink/15 hover:border-brand-ink/35'"
                                            />
                                            <template x-if="touched.nomorKontakPendaftar && errors.nomorKontakPendaftar">
                                                <p class="text-[12px] text-brand-signal font-semibold mt-1.5" x-text="errors.nomorKontakPendaftar"></p>
                                            </template>
                                        </div>
                                        <div>
                                            <input
                                                type="tel"
                                                id="nomorKontakOrtu"
                                                x-model="formData.nomorKontakOrtu"
                                                @blur="handleBlur('nomorKontakOrtu')"
                                                placeholder="Handphone Orang Tua / Wali"
                                                class="w-full rounded-full border text-sm text-brand-ink placeholder:text-brand-ink/40 bg-[#F9F8F6] focus:bg-white px-5 py-3 transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-brand-darkred/20 focus:border-brand-darkred"
                                                :class="touched.nomorKontakOrtu && errors.nomorKontakOrtu ? 'border-brand-signal ring-1 ring-brand-signal/30' : 'border-brand-ink/15 hover:border-brand-ink/35'"
                                            />
                                            <template x-if="touched.nomorKontakOrtu && errors.nomorKontakOrtu">
                                                <p class="text-[12px] text-brand-signal font-semibold mt-1.5" x-text="errors.nomorKontakOrtu"></p>
                                            </template>
                                        </div>
                                    </div>
                                    <p class="text-[12px] text-brand-ink/60 mt-1.5 font-medium">
                                        Pastikan nomor handphone aktif terhubung dengan WhatsApp untuk konfirmasi akun pendaftar.
                                    </p>
                                </div>

                                <!-- 14. Email -->
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-xs font-bold uppercase tracking-wider text-brand-ink/70 mb-2 select-none">
                                            Alamat Email Aktif
                                        </label>
                                        <input
                                            type="email"
                                            id="email"
                                            x-model="formData.email"
                                            placeholder="contoh@gmail.com"
                                            class="w-full rounded-full border text-sm text-brand-ink placeholder:text-brand-ink/40 bg-[#F9F8F6] focus:bg-white px-5 py-3 transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-brand-darkred/20 focus:border-brand-darkred border-brand-ink/15 hover:border-brand-ink/35"
                                        />
                                        <p class="text-[12px] text-brand-ink/60 mt-1.5 leading-normal">
                                            Opsional: Untuk pengiriman bukti formulir digital
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <!-- ------------------------------------------------
                                CARD SECTION C: Informasi Tambahan & Layanan
                                ------------------------------------------------ -->
                            <div
                                id="section-c"
                                class="bg-white rounded-card p-6 sm:p-8 shadow-softpill border border-brand-ink/10 transition-all duration-300 hover:shadow-softpill space-y-6"
                            >
                                <div class="flex items-center gap-3.5 pb-4 border-b border-brand-ink/10">
                                    <div class="w-10 h-10 rounded-full bg-brand-darkred text-white flex items-center justify-center font-bold text-sm shrink-0 shadow-xs">
                                        C
                                    </div>
                                    <div>
                                        <span class="font-display text-brand-darkred tracking-wider uppercase block text-xs font-bold">
                                            BAGIAN 03 • PREFERENSI & KELENGKAPAN
                                        </span>
                                        <h2 class="font-display uppercase tracking-wide text-brand-ink font-bold text-xl sm:text-2xl mt-0.5">
                                            Informasi Tambahan & Layanan
                                        </h2>
                                    </div>
                                </div>

                                <!-- 15. Jenis Layanan (Multi-Select Dropdown) -->
                                <div class="w-full relative" x-data="{ open: false }" @click.away="open = false">
                                    <label class="block text-xs font-bold uppercase tracking-wider text-brand-ink/70 mb-2 select-none">
                                        Peminatan Fasilitas Layanan Tambahan
                                    </label>
                                    <div class="relative">
                                        <div
                                            @click="open = !open"
                                            class="w-full flex items-center justify-between rounded-full border text-sm bg-[#F9F8F6] py-3 px-5 transition-all duration-200 cursor-pointer border-brand-ink/15 hover:border-brand-ink/35"
                                            :class="open ? 'border-brand-darkred ring-2 ring-brand-darkred/20 bg-white' : ''"
                                        >
                                            <span class="truncate" :class="formData.jenisLayanan.length > 0 ? 'text-brand-ink font-medium' : 'text-brand-ink/40'" x-text="formData.jenisLayanan.length === 0 ? 'Pilih layanan jika berminat' : (formData.jenisLayanan.length <= 2 ? formData.jenisLayanan.join(', ') : formData.jenisLayanan.length + ' item dipilih')"></span>
                                            <svg class="w-4 h-4 text-brand-ink/50 ml-2 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                                        </div>

                                        <div x-show="open" x-transition.opacity class="absolute top-full left-0 right-0 mt-2 z-40 bg-white rounded-card border border-brand-ink/15 shadow-softpill max-h-60 overflow-y-auto divide-y divide-brand-ink/10 py-1 animate-fade-in">
                                            <template x-for="lay in ['Catering', 'Antar Jemput', 'AHA Music Course', 'Laundry']" :key="lay">
                                                <div
                                                    @click="toggleArray(formData.jenisLayanan, lay)"
                                                    class="px-4 py-2.5 text-xs sm:text-sm cursor-pointer flex items-center gap-3 transition-colors hover:bg-brand-softmist/50"
                                                    :class="formData.jenisLayanan.includes(lay) ? 'bg-brand-softmist font-semibold text-brand-darkred' : 'text-brand-ink'"
                                                >
                                                    <input type="checkbox" :checked="formData.jenisLayanan.includes(lay)" class="w-4 h-4 rounded text-brand-darkred border-brand-ink/30 accent-[#7A1018] pointer-events-none"/>
                                                    <span class="flex-1 select-none" x-text="lay"></span>
                                                    <template x-if="formData.jenisLayanan.includes(lay)">
                                                        <svg class="w-4 h-4 text-brand-darkred" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                                    </template>
                                                </div>
                                            </template>
                                        </div>
                                    </div>
                                    <p class="text-[12px] text-brand-ink/60 mt-1.5 leading-normal">
                                        Dapat memilih lebih dari 1 opsi (Catering, Antar Jemput, Music Course, dll)
                                    </p>
                                </div>

                                <!-- 16. Sumber Informasi -->
                                <div class="w-full relative" x-data="{ open: false }" @click.away="open = false">
                                    <label class="block text-xs font-bold uppercase tracking-wider text-brand-ink/70 mb-2 select-none">
                                        Sumber Informasi PPDB
                                    </label>
                                    <div class="relative">
                                        <div
                                            @click="open = !open"
                                            class="w-full flex items-center justify-between rounded-full border text-sm bg-[#F9F8F6] py-3 px-5 transition-all duration-200 cursor-pointer border-brand-ink/15 hover:border-brand-ink/35"
                                            :class="open ? 'border-brand-darkred ring-2 ring-brand-darkred/20 bg-white' : ''"
                                        >
                                            <span class="truncate" :class="formData.sumberInfo.length > 0 ? 'text-brand-ink font-medium' : 'text-brand-ink/40'" x-text="formData.sumberInfo.length === 0 ? 'Dari mana Anda mengetahui SMK Pelita Nusantara?' : (formData.sumberInfo.length <= 2 ? formData.sumberInfo.join(', ') : formData.sumberInfo.length + ' item dipilih')"></span>
                                            <svg class="w-4 h-4 text-brand-ink/50 ml-2 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                                        </div>

                                        <div x-show="open" x-transition.opacity class="absolute top-full left-0 right-0 mt-2 z-40 bg-white rounded-card border border-brand-ink/15 shadow-softpill max-h-60 overflow-y-auto divide-y divide-brand-ink/10 py-1 animate-fade-in">
                                            <template x-for="src in ['Spanduk', 'Brosur', 'Iklan', 'Keluarga / Kerabat / Teman', 'Pernah Datang Ke Sekolah', 'Youtube', 'Lain-lain']" :key="src">
                                                <div
                                                    @click="toggleArray(formData.sumberInfo, src)"
                                                    class="px-4 py-2.5 text-xs sm:text-sm cursor-pointer flex items-center gap-3 transition-colors hover:bg-brand-softmist/50"
                                                    :class="formData.sumberInfo.includes(src) ? 'bg-brand-softmist font-semibold text-brand-darkred' : 'text-brand-ink'"
                                                >
                                                    <input type="checkbox" :checked="formData.sumberInfo.includes(src)" class="w-4 h-4 rounded text-brand-darkred border-brand-ink/30 accent-[#7A1018] pointer-events-none"/>
                                                    <span class="flex-1 select-none" x-text="src"></span>
                                                    <template x-if="formData.sumberInfo.includes(src)">
                                                        <svg class="w-4 h-4 text-brand-darkred" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                                    </template>
                                                </div>
                                            </template>
                                        </div>
                                    </div>
                                    <p class="text-[12px] text-brand-ink/60 mt-1.5 leading-normal">
                                        Dapat memilih lebih dari 1 sumber informasi
                                    </p>

                                    <template x-if="formData.sumberInfo.includes('Lain-lain')">
                                        <div class="mt-3 animate-fade-in">
                                            <input
                                                type="text"
                                                x-model="formData.sumberInfoLainnya"
                                                placeholder="Tuliskan sumber informasi lainnya"
                                                class="w-full rounded-full border text-sm text-brand-ink placeholder:text-brand-ink/40 bg-[#F9F8F6] focus:bg-white px-5 py-3 transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-brand-darkred/20 focus:border-brand-darkred border-brand-ink/15 hover:border-brand-ink/35"
                                            />
                                        </div>
                                    </template>
                                </div>

                                <!-- 17. Alasan Minat Mendaftar -->
                                <div>
                                    <label class="block text-xs font-bold uppercase tracking-wider text-brand-ink/70 mb-2 select-none">
                                        Alasan Utama Minat Mendaftar
                                    </label>
                                    <div class="relative flex items-center">
                                        <select
                                            id="alasanMinat"
                                            x-model="formData.alasanMinat"
                                            class="w-full appearance-none rounded-full border text-sm bg-[#F9F8F6] focus:bg-white px-5 py-3 pr-11 transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-brand-darkred/20 focus:border-brand-darkred border-brand-ink/15 cursor-pointer text-brand-ink font-medium"
                                        >
                                            <option value="" disabled selected>Pilih alasan utama Anda memilih sekolah ini</option>
                                            <option value="Tim Akademik yang berpengalaman">Tim Akademik yang berpengalaman</option>
                                            <option value="Lokasi Sekolah dekat Dari Rumah">Lokasi Sekolah dekat Dari Rumah</option>
                                            <option value="Digitalisasi Sekolah">Digitalisasi Sekolah</option>
                                            <option value="Gedung dan Sarana Lengkap">Gedung dan Sarana Lengkap</option>
                                            <option value="Kualitas Guru yang Baik">Kualitas Guru yang Baik</option>
                                            <option value="Reputasi Sekolah Baik">Reputasi Sekolah Baik</option>
                                            <option value="Lainnya">Lainnya</option>
                                        </select>
                                        <div class="absolute right-4 pointer-events-none text-brand-ink/50">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                                        </div>
                                    </div>

                                    <template x-if="formData.alasanMinat === 'Lainnya'">
                                        <div class="mt-3 animate-fade-in">
                                            <input
                                                type="text"
                                                x-model="formData.alasanMinatLainnya"
                                                placeholder="Tuliskan alasan lainnya"
                                                class="w-full rounded-full border text-sm text-brand-ink placeholder:text-brand-ink/40 bg-[#F9F8F6] focus:bg-white px-5 py-3 transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-brand-darkred/20 focus:border-brand-darkred border-brand-ink/15 hover:border-brand-ink/35"
                                            />
                                        </div>
                                    </template>
                                </div>

                                <!-- 18. Ukuran Seragam -->
                                <div>
                                    <label class="block text-xs font-bold uppercase tracking-wider text-brand-ink/70 mb-2 select-none">
                                        Ukuran Seragam Siswa
                                    </label>
                                    <div class="relative flex items-center">
                                        <select
                                            id="ukuranSeragam"
                                            x-model="formData.ukuranSeragam"
                                            class="w-full appearance-none rounded-full border text-sm bg-[#F9F8F6] focus:bg-white px-5 py-3 pr-11 transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-brand-darkred/20 focus:border-brand-darkred border-brand-ink/15 cursor-pointer text-brand-ink font-medium"
                                        >
                                            <option value="" disabled selected>Pilih Ukuran Seragam Anda</option>
                                            <option value="S">S</option>
                                            <option value="M">M</option>
                                            <option value="L">L</option>
                                            <option value="XL">XL</option>
                                            <option value="XXL">XXL</option>
                                        </select>
                                        <div class="absolute right-4 pointer-events-none text-brand-ink/50">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                                        </div>
                                    </div>
                                    <p class="text-[12px] text-brand-ink/60 mt-1.5 leading-normal">
                                        Panduan ukuran standar kemeja dan jas almamater vokasi
                                    </p>
                                </div>
                            </div>

                            <!-- ------------------------------------------------
                                CANVAS DIRECT PLACEMENT: Persetujuan & Aksi Tombol
                                ------------------------------------------------ -->
                            <div class="space-y-6 pt-2">
                                <!-- Persetujuan Checkbox -->
                                <div class="p-6 rounded-card bg-white border border-brand-ink/10 shadow-softpill">
                                    <label class="flex items-start gap-3.5 cursor-pointer select-none group">
                                        <input
                                            type="checkbox"
                                            id="terms"
                                            x-model="termsAccepted"
                                            class="w-5 h-5 sm:w-6 sm:h-6 rounded text-brand-darkred border-brand-ink/30 focus:ring-brand-darkred cursor-pointer mt-0.5 shrink-0 accent-[#7A1018]"
                                        />
                                        <div class="text-xs sm:text-sm font-medium text-brand-ink leading-relaxed">
                                            <span class="text-brand-signal font-bold">* </span>
                                            Saya memahami bahwa setelah data disubmit dan diverifikasi, perubahan data utama hanya dapat dilakukan melalui panitia. Biaya pendaftaran yang telah dibayarkan tidak dapat ditarik kembali.
                                            <p class="mt-1 text-xs text-brand-ink/70">
                                                Untuk pertanyaan seputar proses seleksi, hubungi hotline resmi panitia di 
                                                <a
                                                    href="https://wa.me/6281210868958"
                                                    target="_blank"
                                                    rel="noreferrer"
                                                    class="text-brand-darkred font-bold underline hover:text-brand-deepred"
                                                >
                                                    0812-1086-8958
                                                </a>
                                            </p>
                                        </div>
                                    </label>
                                </div>

                                <!-- Tombol Aksi Navigasi -->
                                <div class="space-y-3.5">
                                    <button
                                        type="button"
                                        id="next-single-form"
                                        :disabled="!termsAccepted"
                                        @click="handleNext()"
                                        class="w-full py-4 text-base font-bold rounded-full inline-flex items-center justify-center gap-2 bg-linear-to-r from-brand-signal to-brand-darkred hover:opacity-95 text-white shadow-md shadow-brand-darkred/25 hover:shadow-lg hover:shadow-brand-darkred/30 active:scale-[0.98] transition-all disabled:opacity-50 disabled:cursor-not-allowed cursor-pointer"
                                    >
                                        <span>Lanjut ke Validasi Data</span>
                                        <svg class="w-4.5 h-4.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                                    </button>

                                    <button
                                        type="button"
                                        @click="handleBatal()"
                                        class="w-full py-3.5 text-sm font-semibold rounded-full border border-brand-ink/20 hover:bg-brand-softmist text-brand-ink transition-all active:scale-[0.98] cursor-pointer"
                                    >
                                        Batalkan Pengisian
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- ====================================================
                            STEP 2: VALIDASI DATA RINGKASAN
                            ==================================================== -->
                        <div x-show="currentStep === 2" class="space-y-6 animate-fade-in">
                            <div>
                                <span class="font-display text-brand-darkred tracking-wider uppercase block text-xs font-bold">
                                    TAHAP FINAL • KONFIRMASI DATA
                                </span>
                                <h2 class="font-display uppercase tracking-wide text-brand-ink text-2xl sm:text-3xl font-bold mt-0.5">
                                    Validasi Data Pendaftaran
                                </h2>
                                <p class="text-brand-ink/70 text-sm mt-1">
                                    Mohon teliti kembali seluruh isian sebelum mengirimkan berkas pendaftaran Anda ke sistem resmi.
                                </p>
                            </div>

                            <!-- Card Section A Review -->
                            <div class="bg-white rounded-card p-6 sm:p-7 shadow-softpill border border-brand-ink/10 transition-all duration-300 hover:shadow-softpill">
                                <div class="flex items-center justify-between pb-3.5 border-b border-brand-ink/10">
                                    <div class="flex items-center gap-2.5">
                                        <svg class="w-4.5 h-4.5 text-brand-darkred" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                        <h3 class="font-display uppercase tracking-wide text-base text-brand-darkred font-bold">
                                            A. Informasi Personal
                                        </h3>
                                    </div>
                                    <button
                                        type="button"
                                        @click="currentStep = 1; $nextTick(() => document.getElementById('section-a')?.scrollIntoView({ behavior: 'smooth' }));"
                                        class="inline-flex items-center gap-1.5 text-xs font-bold text-brand-darkred hover:bg-brand-darkred/10 px-3 py-1 rounded-full transition-all cursor-pointer"
                                    >
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                                        <span>Ubah Data</span>
                                    </button>
                                </div>
                                <div class="divide-y divide-brand-ink/10 mt-1 text-sm">
                                    <div class="py-3.5 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-1">
                                        <span class="text-xs font-bold uppercase tracking-wider text-brand-ink/60">Nama Lengkap</span>
                                        <span class="font-semibold text-brand-ink text-left sm:text-right" x-text="formData.namaLengkap || '-'"></span>
                                    </div>
                                    <div class="py-3.5 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-1">
                                        <span class="text-xs font-bold uppercase tracking-wider text-brand-ink/60">Nama Panggilan</span>
                                        <span class="font-semibold text-brand-ink text-left sm:text-right" x-text="formData.namaPanggilan || '-'"></span>
                                    </div>
                                    <div class="py-3.5 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-1">
                                        <span class="text-xs font-bold uppercase tracking-wider text-brand-ink/60">Nomor Kartu Keluarga</span>
                                        <span class="font-semibold text-brand-ink text-left sm:text-right" x-text="formData.nomorKK || '-'"></span>
                                    </div>
                                    <div class="py-3.5 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-1">
                                        <span class="text-xs font-bold uppercase tracking-wider text-brand-ink/60">Tempat Lahir</span>
                                        <span class="font-semibold text-brand-ink text-left sm:text-right" x-text="formData.tempatLahir || '-'"></span>
                                    </div>
                                    <div class="py-3.5 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-1">
                                        <span class="text-xs font-bold uppercase tracking-wider text-brand-ink/60">Tanggal Lahir</span>
                                        <span class="font-semibold text-brand-ink text-left sm:text-right" x-text="formData.tanggalLahirHari ? (formData.tanggalLahirHari + ' - Bulan ' + formData.tanggalLahirBulan + ' - ' + formData.tanggalLahirTahun) : '-'"></span>
                                    </div>
                                    <div class="py-3.5 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-1">
                                        <span class="text-xs font-bold uppercase tracking-wider text-brand-ink/60">Jenis Kelamin</span>
                                        <span class="font-semibold text-brand-ink text-left sm:text-right" x-text="formData.jenisKelamin === 'L' ? 'Laki-laki' : 'Perempuan'"></span>
                                    </div>
                                    <div class="py-3.5 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-1">
                                        <span class="text-xs font-bold uppercase tracking-wider text-brand-ink/60">Alamat Lengkap</span>
                                        <span class="font-semibold text-brand-ink text-left sm:text-right max-w-xs" x-text="formData.alamatLengkap || '-'"></span>
                                    </div>
                                </div>
                            </div>

                            <!-- Card Section B Review -->
                            <div class="bg-white rounded-card p-6 sm:p-7 shadow-softpill border border-brand-ink/10 transition-all duration-300 hover:shadow-softpill">
                                <div class="flex items-center justify-between pb-3.5 border-b border-brand-ink/10">
                                    <div class="flex items-center gap-2.5">
                                        <svg class="w-4.5 h-4.5 text-brand-darkred" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"/></svg>
                                        <h3 class="font-display uppercase tracking-wide text-base text-brand-darkred font-bold">
                                            B. Sekolah Tujuan & Jurusan
                                        </h3>
                                    </div>
                                    <button
                                        type="button"
                                        @click="currentStep = 1; $nextTick(() => document.getElementById('section-b')?.scrollIntoView({ behavior: 'smooth' }));"
                                        class="inline-flex items-center gap-1.5 text-xs font-bold text-brand-darkred hover:bg-brand-darkred/10 px-3 py-1 rounded-full transition-all cursor-pointer"
                                    >
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                                        <span>Ubah Data</span>
                                    </button>
                                </div>
                                <div class="divide-y divide-brand-ink/10 mt-1 text-sm">
                                    <div class="py-3.5 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-1">
                                        <span class="text-xs font-bold uppercase tracking-wider text-brand-ink/60">Sekolah Pilihan</span>
                                        <span class="font-semibold text-brand-ink text-left sm:text-right">SMK - SMK Plus Pelita Nusantara Bogor</span>
                                    </div>
                                    <div class="py-3.5 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-1">
                                        <span class="text-xs font-bold uppercase tracking-wider text-brand-ink/60">Tipe Pendaftar</span>
                                        <span class="font-semibold text-brand-ink text-left sm:text-right" x-text="formData.tipePendaftar"></span>
                                    </div>
                                    <template x-if="formData.tipePendaftar === 'Pendaftar Pindahan Tengah Tahun Ajaran'">
                                        <div class="py-3.5 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-1">
                                            <span class="text-xs font-bold uppercase tracking-wider text-brand-ink/60">Pindah di Tahun Ajaran</span>
                                            <span class="font-semibold text-brand-ink text-left sm:text-right" x-text="formData.pindahTahunAjaran || '-'"></span>
                                        </div>
                                    </template>
                                    <div class="py-3.5 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-1">
                                        <span class="text-xs font-bold uppercase tracking-wider text-brand-ink/60">Kelas Pilihan</span>
                                        <span class="font-semibold text-brand-ink text-left sm:text-right" x-text="formData.kelasPilihan"></span>
                                    </div>
                                    <div class="py-3.5 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-1">
                                        <span class="text-xs font-bold uppercase tracking-wider text-brand-ink/60">Program Keahlian</span>
                                        <span class="font-bold text-brand-darkred text-left sm:text-right" x-text="formData.jurusan || '-'"></span>
                                    </div>
                                    <div class="py-3.5 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-1">
                                        <span class="text-xs font-bold uppercase tracking-wider text-brand-ink/60">Jalur Seleksi</span>
                                        <span class="font-semibold text-brand-ink text-left sm:text-right" x-text="formData.jalurSeleksi"></span>
                                    </div>
                                    <div class="py-3.5 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-1">
                                        <span class="text-xs font-bold uppercase tracking-wider text-brand-ink/60">Asal Sekolah</span>
                                        <span class="font-semibold text-brand-ink text-left sm:text-right" x-text="formData.asalSekolah"></span>
                                    </div>
                                    <div class="py-3.5 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-1">
                                        <span class="text-xs font-bold uppercase tracking-wider text-brand-ink/60">Kontak Handphone Pendaftar</span>
                                        <span class="font-semibold text-brand-ink text-left sm:text-right" x-text="formData.nomorKontakPendaftar"></span>
                                    </div>
                                    <div class="py-3.5 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-1">
                                        <span class="text-xs font-bold uppercase tracking-wider text-brand-ink/60">Kontak Handphone Orang Tua</span>
                                        <span class="font-semibold text-brand-ink text-left sm:text-right" x-text="formData.nomorKontakOrtu"></span>
                                    </div>
                                    <div class="py-3.5 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-1">
                                        <span class="text-xs font-bold uppercase tracking-wider text-brand-ink/60">Email</span>
                                        <span class="font-semibold text-brand-ink text-left sm:text-right" x-text="formData.email || '-'"></span>
                                    </div>
                                </div>
                            </div>

                            <!-- Card Section C Review -->
                            <div class="bg-white rounded-card p-6 sm:p-7 shadow-softpill border border-brand-ink/10 transition-all duration-300 hover:shadow-softpill">
                                <div class="flex items-center justify-between pb-3.5 border-b border-brand-ink/10">
                                    <div class="flex items-center gap-2.5">
                                        <svg class="w-4.5 h-4.5 text-brand-darkred" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                                        <h3 class="font-display uppercase tracking-wide text-base text-brand-darkred font-bold">
                                            C. Informasi Tambahan & Layanan
                                        </h3>
                                    </div>
                                    <button
                                        type="button"
                                        @click="currentStep = 1; $nextTick(() => document.getElementById('section-c')?.scrollIntoView({ behavior: 'smooth' }));"
                                        class="inline-flex items-center gap-1.5 text-xs font-bold text-brand-darkred hover:bg-brand-darkred/10 px-3 py-1 rounded-full transition-all cursor-pointer"
                                    >
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                                        <span>Ubah Data</span>
                                    </button>
                                </div>
                                <div class="divide-y divide-brand-ink/10 mt-1 text-sm">
                                    <div class="py-3.5 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-1">
                                        <span class="text-xs font-bold uppercase tracking-wider text-brand-ink/60">Peminatan Fasilitas Layanan</span>
                                        <div class="flex flex-wrap gap-1.5 justify-start sm:justify-end">
                                            <template x-if="formData.jenisLayanan.length === 0">
                                                <span class="text-brand-ink/40 italic text-sm">-</span>
                                            </template>
                                            <template x-for="lay in formData.jenisLayanan" :key="lay">
                                                <span class="inline-block bg-brand-softmist text-brand-darkred text-xs font-semibold px-3 py-1 rounded-full border border-brand-ink/10" x-text="lay"></span>
                                            </template>
                                        </div>
                                    </div>
                                    <div class="py-3.5 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-1">
                                        <span class="text-xs font-bold uppercase tracking-wider text-brand-ink/60">Sumber Informasi PPDB</span>
                                        <div class="flex flex-wrap gap-1.5 justify-start sm:justify-end">
                                            <template x-if="formData.sumberInfo.length === 0">
                                                <span class="text-brand-ink/40 italic text-sm">-</span>
                                            </template>
                                            <template x-for="src in formData.sumberInfo" :key="src">
                                                <span class="inline-block bg-brand-softmist text-brand-darkred text-xs font-semibold px-3 py-1 rounded-full border border-brand-ink/10" x-text="src === 'Lain-lain' && formData.sumberInfoLainnya ? 'Lain-lain (' + formData.sumberInfoLainnya + ')' : src"></span>
                                            </template>
                                        </div>
                                    </div>
                                    <div class="py-3.5 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-1">
                                        <span class="text-xs font-bold uppercase tracking-wider text-brand-ink/60">Alasan Minat Mendaftar</span>
                                        <span class="font-semibold text-brand-ink text-left sm:text-right" x-text="formData.alasanMinat === 'Lainnya' && formData.alasanMinatLainnya ? 'Lainnya (' + formData.alasanMinatLainnya + ')' : (formData.alasanMinat || '-')"></span>
                                    </div>
                                    <div class="py-3.5 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-1">
                                        <span class="text-xs font-bold uppercase tracking-wider text-brand-ink/60">Ukuran Seragam</span>
                                        <span class="font-semibold text-brand-ink text-left sm:text-right" x-text="formData.ukuranSeragam || '-'"></span>
                                    </div>
                                </div>
                            </div>

                            <!-- Confirmation Checkbox Box -->
                            <div class="p-5 rounded-card bg-white border border-brand-ink/10 shadow-softpill">
                                <label class="flex items-start gap-3 cursor-pointer select-none">
                                    <input
                                        type="checkbox"
                                        x-model="validasiConfirmed"
                                        class="w-5 h-5 rounded text-brand-darkred border-brand-ink/30 focus:ring-brand-darkred cursor-pointer mt-0.5 accent-[#7A1018]"
                                    />
                                    <span class="text-xs sm:text-sm text-brand-ink/90 leading-relaxed font-medium">
                                        Saya menyatakan bahwa seluruh data yang diisikan di atas adalah benar, lengkap, dan dapat dipertanggungjawabkan sesuai ketentuan panitia PPDB SMK Plus Pelita Nusantara Bogor.
                                    </span>
                                </label>
                            </div>

                            <!-- Final Action Buttons -->
                            <div class="pt-2 space-y-3.5">
                                <button
                                    type="button"
                                    :disabled="!validasiConfirmed || isSubmitting"
                                    @click="handleSubmitFinal()"
                                    class="w-full py-4 text-base font-bold rounded-full bg-linear-to-r from-brand-signal to-brand-darkred hover:opacity-95 text-white shadow-md shadow-brand-darkred/25 hover:shadow-lg hover:shadow-brand-darkred/30 inline-flex items-center justify-center gap-2 transition-all active:scale-[0.98] disabled:opacity-50 disabled:cursor-not-allowed cursor-pointer"
                                >
                                    <template x-if="isSubmitting">
                                        <div class="flex items-center gap-2">
                                            <svg class="animate-spin w-5 h-5 text-white" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path></svg>
                                            <span>Mengirim Berkas Pendaftaran...</span>
                                        </div>
                                    </template>
                                    <template x-if="!isSubmitting">
                                        <div class="flex items-center gap-2">
                                            <span>Kirim Pendaftaran Resmi</span>
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                        </div>
                                    </template>
                                </button>

                                <button
                                    type="button"
                                    @click="currentStep = 1; window.scrollTo({ top: 0, behavior: 'smooth' });"
                                    class="w-full py-3.5 text-sm font-semibold rounded-full border border-brand-ink/20 hover:bg-brand-softmist text-brand-ink transition-all cursor-pointer"
                                >
                                    Kembali Periksa Formulir
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </template>

    <!-- ========================================================
        4. Modal "Perhatian" (modalTerm - Editorial Dialog)
        ======================================================== -->
    <div
        x-show="showModalTerm"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-brand-ink/60 backdrop-blur-xs animate-fade-in"
        style="display: none;"
    >
        <div class="bg-white rounded-card sm:rounded-[24px] max-w-lg w-full p-6 sm:p-8 shadow-softpill border border-brand-ink/15 relative animate-scale-in">
            <button
                type="button"
                @click="showModalTerm = false"
                class="absolute top-5 right-5 text-brand-ink/50 hover:text-brand-ink p-1 rounded-full hover:bg-brand-softmist transition-colors cursor-pointer"
            >
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>

            <div class="flex items-center gap-3 mb-5">
                <div class="w-10 h-10 rounded-full bg-brand-darkred/10 text-brand-darkred flex items-center justify-center">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
                </div>
                <div>
                    <span class="font-display text-brand-darkred tracking-wider uppercase block text-xs font-bold">
                        INFORMASI PENTING
                    </span>
                    <h3 class="font-display uppercase tracking-wide text-brand-ink font-bold text-lg">
                        Ketentuan PPDB
                    </h3>
                </div>
            </div>

            <div class="divide-y divide-brand-ink/10 max-h-80 overflow-y-auto pr-1">
                <div class="py-3 flex items-start gap-3">
                    <span class="w-5 h-5 rounded-full bg-brand-softmist text-brand-darkred text-xs font-bold flex items-center justify-center shrink-0 mt-0.5">1</span>
                    <div class="text-xs sm:text-sm text-brand-ink/80 leading-relaxed">
                        Untuk Calon Pendaftar Harus Terdaftar sebagai Siswa kelas 9 (sembilan) SMP / Madrasah Tsanawiyah / Sederajat.
                    </div>
                </div>
                <div class="py-3 flex items-start gap-3">
                    <span class="w-5 h-5 rounded-full bg-brand-softmist text-brand-darkred text-xs font-bold flex items-center justify-center shrink-0 mt-0.5">2</span>
                    <div class="text-xs sm:text-sm text-brand-ink/80 leading-relaxed">
                        Sungguh-sungguh berminat untuk menjadi siswa SMK Plus Pelita Nusantara
                    </div>
                </div>
                <div class="py-3 flex items-start gap-3">
                    <span class="w-5 h-5 rounded-full bg-brand-softmist text-brand-darkred text-xs font-bold flex items-center justify-center shrink-0 mt-0.5">3</span>
                    <div class="text-xs sm:text-sm text-brand-ink/80 leading-relaxed">
                        Isi Form Pendaftaran Berikut dengan Data yang Benar dan Valid.
                    </div>
                </div>
                <div class="py-3 flex items-start gap-3">
                    <span class="w-5 h-5 rounded-full bg-brand-softmist text-brand-darkred text-xs font-bold flex items-center justify-center shrink-0 mt-0.5">4</span>
                    <div class="text-xs sm:text-sm text-brand-ink/80 leading-relaxed">
                        Jika ada hal-hal yang ingin ditanyakan mengenai proses pendaftaran silahkan hubungi 
                        <a href="https://wa.me/6281210868958" target="_blank" class="font-bold text-brand-darkred hover:underline">+62 812-1086-8958</a>
                    </div>
                </div>
                <div class="py-3 flex items-start gap-3">
                    <span class="w-5 h-5 rounded-full bg-brand-softmist text-brand-darkred text-xs font-bold flex items-center justify-center shrink-0 mt-0.5">5</span>
                    <div class="text-xs sm:text-sm text-brand-ink/80 leading-relaxed">
                        Untuk informasi lebih lanjut silahkan klik 
                        <a href="https://images.lekar.co.id/419/2025/file//2025112110025011_brosur_penus_lipat_3_bagian_depan_&_belakang_compressed.pdf" target="_blank" class="font-bold text-brand-signal hover:underline">Link Brosur</a>
                    </div>
                </div>
                <div class="py-3 flex items-start gap-3">
                    <span class="w-5 h-5 rounded-full bg-brand-softmist text-brand-darkred text-xs font-bold flex items-center justify-center shrink-0 mt-0.5">6</span>
                    <div class="text-xs sm:text-sm text-brand-ink/80 leading-relaxed">
                        Jika sudah melakukan proses pendaftaran dan mendapatkan username beserta password, silahkan klik 
                        <a href="https://ppdb.smkpluspnb.sch.id/login" target="_blank" class="font-bold text-brand-darkred hover:underline">Masuk</a> atau klik tombol masuk dipojok kanan atas.
                    </div>
                </div>
            </div>

            <div class="mt-7 flex justify-end">
                <button
                    type="button"
                    @click="showModalTerm = false"
                    class="px-7 py-2.5 rounded-full bg-linear-to-r from-brand-signal to-brand-darkred hover:opacity-95 text-white font-semibold text-sm transition-all shadow-md shadow-brand-darkred/25 cursor-pointer"
                >
                    Saya Mengerti
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function ppdbForm() {
    return {
        currentStep: 1,
        isJadwalOpen: true,
        showModalTerm: false,
        termsAccepted: false,
        validasiConfirmed: false,
        isSubmitting: false,
        isSubmittedSuccess: false,
        isSubmittedAttempt: false,
        errors: {},
        touched: {},
        submissionData: {
            noPendaftaran: '',
            tanggalDaftar: '',
        },
        sekolahList: [
            "SMP Negeri 1 Cibinong",
            "SMP Negeri 2 Cibinong",
            "SMP Negeri 3 Cibinong",
            "SMP Negeri 4 Cibinong",
            "SMP Negeri 1 Bogor",
            "SMP Negeri 2 Bogor",
            "SMP Negeri 3 Bogor",
            "MTs Negeri 1 Bogor",
            "MTs Negeri 2 Cibinong",
            "SMP Plus PGRI Cibinong",
            "SMP IT Al Madinah",
            "SMP IT Ummul Quro",
            "SMP Taruna Bangsa",
            "SMP Regina Pacis Bogor",
            "SMP Islam Al-Azhar Cibinong",
            "SMP PGRI 1 Cibinong"
        ],
        jurusanList: [
            {
                value: "Teknik Jaringan Komputer dan Telekomunikasi (TJKT)",
                short: "TKJ",
                desc: "Jaringan & Telekomunikasi",
                icon: "🖥️"
            },
            {
                value: "Rekayasa Perangkat Lunak (RPL)",
                short: "RPL",
                desc: "Pengembangan Software",
                icon: "💻"
            },
            {
                value: "Desain Komunikasi Visual (DKV)",
                short: "DKV",
                desc: "Desain Grafis & Media",
                icon: "🎨"
            },
            {
                value: "Perbankan dan Keuangan Mikro (PKM)",
                short: "PKM",
                desc: "Keuangan & Perbankan",
                icon: "🏦"
            }
        ],
        formData: {
            namaLengkap: '',
            namaPanggilan: '',
            nomorKK: '',
            tempatLahir: '',
            tanggalLahirHari: '',
            tanggalLahirBulan: '',
            tanggalLahirTahun: '',
            jenisKelamin: 'L',
            alamatLengkap: '',
            sekolahPilihanLevel: 'SMK',
            sekolahPilihanUnit: 'SMK Plus Pelita Nusantara Bogor',
            tipePendaftar: 'Pendaftar Baru',
            pindahTahunAjaran: '',
            kelasPilihan: 'Kelas 10',
            jurusan: '',
            jalurSeleksi: 'Reguler',
            asalSekolah: '',
            nomorKontakPendaftar: '',
            nomorKontakOrtu: '',
            email: '',
            jenisLayanan: [],
            sumberInfo: [],
            sumberInfoLainnya: '',
            alasanMinat: '',
            alasanMinatLainnya: '',
            ukuranSeragam: '',
        },

        init() {
            // initial setup
        },

        handleInputChange(field, value) {
            this.formData[field] = value;
            if (field === 'kelasPilihan') {
                this.formData.jurusan = '';
            }
            if (this.errors[field]) {
                this.errors[field] = '';
            }
        },

        handleBlur(field) {
            this.touched[field] = true;
            this.validateField(field);
        },

        toggleArray(arr, val) {
            const idx = arr.indexOf(val);
            if (idx > -1) {
                arr.splice(idx, 1);
            } else {
                arr.push(val);
            }
        },

        validateField(field) {
            const val = this.formData[field];
            switch (field) {
                case 'namaLengkap':
                    if (!val || !val.trim()) this.errors.namaLengkap = 'Nama lengkap wajib diisi.';
                    else if (val.trim().length < 3) this.errors.namaLengkap = 'Nama lengkap minimal 3 karakter.';
                    else delete this.errors.namaLengkap;
                    break;
                case 'namaPanggilan':
                    if (!val || !val.trim()) this.errors.namaPanggilan = 'Nama panggilan wajib diisi.';
                    else delete this.errors.namaPanggilan;
                    break;
                case 'tempatLahir':
                    if (!val || !val.trim()) this.errors.tempatLahir = 'Tempat lahir wajib diisi.';
                    else delete this.errors.tempatLahir;
                    break;
                case 'tanggalLahirHari':
                    if (!val) this.errors.tanggalLahirHari = 'Pilih tanggal.';
                    else delete this.errors.tanggalLahirHari;
                    break;
                case 'tanggalLahirBulan':
                    if (!val) this.errors.tanggalLahirBulan = 'Pilih bulan.';
                    else delete this.errors.tanggalLahirBulan;
                    break;
                case 'tanggalLahirTahun':
                    if (!val) this.errors.tanggalLahirTahun = 'Pilih tahun.';
                    else delete this.errors.tanggalLahirTahun;
                    break;
                case 'nomorKontakPendaftar':
                    if (!val || !val.trim()) this.errors.nomorKontakPendaftar = 'Nomor handphone pendaftar wajib diisi.';
                    else if (val.replace(/\D/g, '').length < 10) this.errors.nomorKontakPendaftar = 'Nomor handphone minimal 10 digit.';
                    else delete this.errors.nomorKontakPendaftar;
                    break;
                case 'nomorKontakOrtu':
                    if (!val || !val.trim()) this.errors.nomorKontakOrtu = 'Nomor handphone orang tua wajib diisi.';
                    else if (val.replace(/\D/g, '').length < 10) this.errors.nomorKontakOrtu = 'Nomor handphone minimal 10 digit.';
                    else delete this.errors.nomorKontakOrtu;
                    break;
            }
        },

        validateStep1() {
            const req = [
                'namaLengkap', 'namaPanggilan', 'tempatLahir',
                'tanggalLahirHari', 'tanggalLahirBulan', 'tanggalLahirTahun',
                'jenisKelamin', 'tipePendaftar', 'kelasPilihan', 'jurusan',
                'jalurSeleksi', 'asalSekolah', 'nomorKontakPendaftar', 'nomorKontakOrtu'
            ];

            if (this.formData.tipePendaftar === 'Pendaftar Pindahan Tengah Tahun Ajaran') {
                req.push('pindahTahunAjaran');
            }

            let isValid = true;
            this.errors = {};

            req.forEach(f => {
                this.touched[f] = true;
                const v = this.formData[f];
                if (!v || (typeof v === 'string' && !v.trim())) {
                    this.errors[f] = 'Wajib diisi';
                    isValid = false;
                } else if ((f === 'nomorKontakPendaftar' || f === 'nomorKontakOrtu') && v.replace(/\D/g, '').length < 10) {
                    this.errors[f] = 'Minimal 10 digit';
                    isValid = false;
                }
            });

            return isValid;
        },

        handleNext() {
            if (!this.termsAccepted) {
                alert('Silahkan centang persetujuan kebenaran data terlebih dahulu.');
                return;
            }

            this.isSubmittedAttempt = true;
            const valid = this.validateStep1();

            if (valid) {
                this.currentStep = 2;
                window.scrollTo({ top: 0, behavior: 'smooth' });
            } else {
                const firstErrKey = Object.keys(this.errors)[0];
                if (firstErrKey) {
                    const el = document.getElementById(firstErrKey);
                    if (el) {
                        el.scrollIntoView({ behavior: 'smooth', block: 'center' });
                        el.focus();
                    } else {
                        window.scrollTo({ top: 250, behavior: 'smooth' });
                    }
                }
            }
        },

        handleBatal() {
            if (confirm('Apakah Anda yakin ingin membatalkan pengisian formulir? Seluruh data yang diisi akan dikosongkan.')) {
                this.handleDaftarLagi();
            }
        },

        handleDaftarLagi() {
            this.currentStep = 1;
            this.termsAccepted = false;
            this.validasiConfirmed = false;
            this.isSubmitting = false;
            this.isSubmittedSuccess = false;
            this.isSubmittedAttempt = false;
            this.errors = {};
            this.touched = {};
            this.submissionData = { noPendaftaran: '', tanggalDaftar: '' };
            this.formData = {
                namaLengkap: '',
                namaPanggilan: '',
                nomorKK: '',
                tempatLahir: '',
                tanggalLahirHari: '',
                tanggalLahirBulan: '',
                tanggalLahirTahun: '',
                jenisKelamin: 'L',
                alamatLengkap: '',
                sekolahPilihanLevel: 'SMK',
                sekolahPilihanUnit: 'SMK Plus Pelita Nusantara Bogor',
                tipePendaftar: 'Pendaftar Baru',
                pindahTahunAjaran: '',
                kelasPilihan: 'Kelas 10',
                jurusan: '',
                jalurSeleksi: 'Reguler',
                asalSekolah: '',
                nomorKontakPendaftar: '',
                nomorKontakOrtu: '',
                email: '',
                jenisLayanan: [],
                sumberInfo: [],
                sumberInfoLainnya: '',
                alasanMinat: '',
                alasanMinatLainnya: '',
                ukuranSeragam: '',
            };
            window.scrollTo({ top: 0, behavior: 'smooth' });
        },

        async handleSubmitFinal() {
            if (!this.validasiConfirmed || this.isSubmitting) return;

            this.isSubmitting = true;
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

            try {
                const response = await fetch("{{ route('ppdb.store') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify(this.formData)
                });

                const data = await response.json();

                if (response.ok && data.success) {
                    this.submissionData = {
                        noPendaftaran: data.noPendaftaran,
                        tanggalDaftar: data.tanggalDaftar,
                    };
                    this.isSubmitting = false;
                    this.isSubmittedSuccess = true;
                    window.scrollTo({ top: 0, behavior: 'smooth' });
                } else {
                    alert(data.message || 'Gagal menyimpan pendaftaran.');
                    this.isSubmitting = false;
                }
            } catch (err) {
                console.error(err);
                alert('Terjadi kesalahan jaringan.');
                this.isSubmitting = false;
            }
        }
    };
}
</script>
@endpush
