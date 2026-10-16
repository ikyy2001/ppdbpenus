@extends('layouts.app')

@section('title', 'Formulir Pendaftaran Siswa PPDB 2027/2028 - SMK Plus Pelita Nusantara')

@section('content')
<div x-data="ppdbForm()" class="flex flex-col text-brand-ink">
    <!-- ========================================================
        LAYAR SUKSES (setelah kirim pendaftaran)
        ======================================================== -->
    <template x-if="isSubmittedSuccess">
        <main class="px-6 pt-32 md:pt-40">
            <div class="relative max-w-3xl mx-auto p-8 sm:p-12 text-center animate-fade-up">
                <x-sketch.box />

                <div class="w-20 h-20 sm:w-24 sm:h-24 rounded-full bg-linear-to-r from-brand-signal to-brand-darkred text-white flex items-center justify-center mx-auto mb-6 shadow-md shadow-brand-darkred/30">
                    <svg class="w-11 h-11" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" />
                    </svg>
                </div>

                <p class="eyebrow">
                    <x-sketch.sparks>Pendaftaran Resmi Diterima</x-sketch.sparks>
                </p>
                <h1 class="mt-4 font-display text-3xl sm:text-4xl font-bold uppercase tracking-wide leading-tight">
                    Pendaftaran Berhasil <x-sketch.underline tone="text-brand-signal" :delay="400">Dikirim!</x-sketch.underline>
                </h1>

                <p class="mt-8 text-brand-ink/70 max-w-xl mx-auto text-base leading-relaxed">
                    Terima kasih, data pendaftaran calon siswa <strong class="text-brand-darkred font-bold" x-text="formData.namaLengkap"></strong>
                    telah berhasil dicatat oleh sistem PPDB SMK Plus Pelita Nusantara.
                </p>

                <!-- Nomor Registrasi -->
                <div class="my-8 p-6 rounded-card bg-brand-softmist max-w-md mx-auto">
                    <span class="eyebrow block">Nomor Registrasi PPDB</span>
                    <span class="font-display text-3xl sm:text-4xl font-bold tracking-wide text-brand-darkred block mt-2 select-all"
                          x-text="submissionData.noPendaftaran"></span>
                    <span class="text-xs text-brand-ink/60 block mt-1.5">
                        Waktu Terdaftar: <span x-text="submissionData.tanggalDaftar"></span>
                    </span>
                </div>

                <!-- Ringkasan -->
                <dl class="max-w-md mx-auto text-left mb-10">
                    <x-review-row label="Nama Lengkap" x-text="formData.namaLengkap" />
                    <template x-if="formData.nisn">
                        <x-review-row label="NISN" class="tabular-nums" x-text="formData.nisn" />
                    </template>
                    <x-review-row label="Pilihan Keahlian" class="text-brand-darkred" x-text="formData.jurusan || '-'" />
                    <x-review-row label="Kontak Pendaftar" x-text="formData.nomorKontakPendaftar" />
                    <x-review-row label="Status Berkas">
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-brand-darkred/10 text-brand-darkred">
                            Menunggu Verifikasi Panitia
                        </span>
                    </x-review-row>
                </dl>

                <div class="flex flex-col sm:flex-row flex-wrap items-center justify-center gap-3 no-print">
                    <template x-if="submissionData.uuid">
                        <a :href="'/ppdb/cetak-kartu/' + submissionData.uuid" target="_blank" class="btn-primary w-full sm:w-auto">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            Cetak Kartu Peserta
                        </a>
                    </template>

                    <button type="button" @click="window.print()" class="btn-secondary w-full sm:w-auto">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                        </svg>
                        Cetak Bukti
                    </button>

                    <button type="button" @click="handleDaftarLagi()" class="btn-secondary w-full sm:w-auto">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                        </svg>
                        Daftar Siswa Lain
                    </button>

                    <a href="https://ppdb.smkpluspnb.sch.id/login" target="_blank" rel="noreferrer" class="group btn-secondary w-full sm:w-auto">
                        Masuk ke Akun
                        <x-sketch.arrow class="w-6 h-3 transition-transform group-hover:translate-x-1" />
                    </a>
                </div>
            </div>
        </main>
    </template>

    <!-- ========================================================
        FORMULIR (hero, jadwal & langkah isian)
        ======================================================== -->
    <template x-if="!isSubmittedSuccess">
        <div>
            <x-page-hero
                current="Formulir Pendaftaran"
                :eyebrow="'Penerimaan Peserta Didik Baru ' . ($activeWave->tahun_ajaran ?? '2027/2028') . ' • ' . ($activeWave->nama ?? 'Gelombang 1')"
                title="Formulir Pendaftaran Siswa"
                description="Gerbang awal menuju generasi unggul vokasi Indonesia. Mohon lengkapi seluruh isian data berikut secara cermat dan akurat."
            >
                <x-slot:aside>
                    {{-- Roket di depan orbit coretan, seperti foto siswa di banner footer landing page --}}
                    <div class="relative w-56 h-56 lg:w-64 lg:h-64 grid place-items-center">
                        <x-sketch.loop :delay="600" class="text-brand-signal/70 -left-6 -right-6 top-[28%] bottom-[28%] -rotate-12" />
                        <img src="{{ asset('assets/rocket-up.svg') }}" alt="Rocket Vokasi"
                             class="relative w-44 h-44 lg:w-52 lg:h-52 object-contain select-none drop-shadow-lg transition-transform duration-500 hover:scale-105"
                             onerror="this.src='https://ppdb.smkpluspnb.sch.id/assets/images/templates/rocket-up.svg'" />
                    </div>
                </x-slot:aside>
            </x-page-hero>

            <main class="px-6 pt-12 md:pt-16">
                <div class="max-w-6xl mx-auto">
                    <div id="stepper" class="scroll-mt-32">
                        <x-stepper />
                    </div>

                    <div class="mt-12 grid gap-12 lg:gap-14 lg:grid-cols-[1fr_2fr] items-start">

                        <!-- ----------------------------------------------------
                            SIDEBAR: Jadwal Gelombang
                            ---------------------------------------------------- -->
                        <aside class="order-2 lg:order-1 lg:sticky lg:top-32">
                            <div class="relative p-6">
                                <x-sketch.box />
                                <div class="flex items-start justify-between gap-4">
                                    <div>
                                        <p class="eyebrow">Jadwal Seleksi</p>
                                        <h2 class="mt-2 font-display text-xl font-bold uppercase tracking-wide leading-tight">Gelombang Pendaftaran</h2>
                                    </div>
                                    <button type="button" @click="isJadwalOpen = !isJadwalOpen"
                                            class="w-8 h-8 shrink-0 rounded-full bg-brand-softmist text-brand-ink flex items-center justify-center transition-colors hover:text-brand-darkred"
                                            :aria-expanded="isJadwalOpen" aria-label="Tampilkan jadwal gelombang">
                                        <svg class="w-4 h-4 transition-transform duration-300" :class="isJadwalOpen ? 'rotate-45' : ''" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" aria-hidden="true">
                                            <path d="M12 5v14M5 12h14" />
                                        </svg>
                                    </button>
                                </div>

                                <ul x-show="isJadwalOpen" x-transition.opacity class="mt-4">
                                    @forelse($waves ?? [] as $wave)
                                        <li class="relative py-4 space-y-1.5">
                                            <div class="flex items-start justify-between gap-3">
                                                <div>
                                                    <h3 class="font-sans text-sm font-semibold leading-snug">
                                                        {{ $wave->nama }} (T.A {{ $wave->tahun_ajaran }})
                                                    </h3>
                                                    @if($wave->deskripsi)
                                                        <p class="text-xs text-brand-ink/60 line-clamp-1 mt-0.5">{{ $wave->deskripsi }}</p>
                                                    @endif
                                                </div>
                                                @if($wave->is_active)
                                                    <span class="text-[10px] font-bold text-white px-2.5 py-0.5 rounded-full shrink-0 uppercase tracking-wider bg-brand-darkred">Buka</span>
                                                @elseif($wave->tanggal_mulai && $wave->tanggal_mulai->isFuture())
                                                    <span class="text-[10px] font-bold text-brand-darkred px-2.5 py-0.5 rounded-full shrink-0 uppercase tracking-wider bg-brand-darkred/10">Segera</span>
                                                @else
                                                    <span class="text-[10px] font-bold text-brand-ink/60 bg-brand-mist px-2.5 py-0.5 rounded-full shrink-0 uppercase tracking-wider">Tutup</span>
                                                @endif
                                            </div>
                                            <div class="flex items-center justify-between gap-3 text-xs text-brand-ink/70">
                                                <span class="flex items-center gap-1.5">
                                                    <svg class="w-3.5 h-3.5 text-brand-darkred shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                    </svg>
                                                    {{ $wave->periode_formatted }}
                                                </span>
                                                <span class="font-semibold text-brand-darkred whitespace-nowrap">{{ $wave->biaya_formulir_formatted }}</span>
                                            </div>
                                            @unless($loop->last)
                                                <x-sketch.rule class="text-brand-darkred/25 -left-1 -right-1 -bottom-1.5 h-3" />
                                            @endunless
                                        </li>
                                    @empty
                                        <li class="py-4 text-sm text-brand-ink/60">Jadwal gelombang pendaftaran belum dikonfigurasi.</li>
                                    @endforelse
                                </ul>
                            </div>
                        </aside>

                        <!-- ----------------------------------------------------
                            KOLOM UTAMA: Langkah isian / validasi
                            ---------------------------------------------------- -->
                        <div class="order-1 lg:order-2 min-w-0">
                            <!-- Peringatan kolom belum valid -->
                            <template x-if="isSubmittedAttempt && currentStep <= 3 && stepHasErrors(currentStep)">
                                <div role="alert" class="rounded-card bg-brand-signal/10 p-5 mb-8 flex items-start gap-3.5 animate-fade-up">
                                    <svg class="w-5 h-5 text-brand-signal shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    <div>
                                        <h3 class="font-sans font-semibold text-brand-signal text-base">Periksa Kembali Formulir Anda</h3>
                                        <p class="text-brand-ink/80 text-sm mt-1 leading-relaxed">
                                            Beberapa kolom wajib bertanda bintang belum terisi dengan benar. Mohon periksa
                                            tanda merah pada kolom yang bersangkutan.
                                        </p>
                                    </div>
                                </div>
                            </template>

                            <!-- ====================================================
                                LANGKAH 1-3: FORMULIR ISIAN (satu bagian per langkah)
                                ==================================================== -->
                            <div x-show="currentStep <= 3" class="flex flex-col gap-10">
                                <!-- ------------------------------------------------
                                    BAGIAN A: Informasi Personal
                                    ------------------------------------------------ -->
                                <section id="section-a" x-show="currentStep === 1" class="relative p-6 sm:p-8 animate-fade-up">
                                    <x-sketch.box />
                                    <header class="flex items-start gap-4 pb-8">
                                        <span class="font-display text-4xl md:text-5xl font-bold leading-none text-brand-darkred">01</span>
                                        <div>
                                            <p class="eyebrow">Bagian 01 • Identitas Diri</p>
                                            <h2 class="mt-2 font-display text-2xl md:text-3xl font-bold uppercase tracking-wide leading-tight">Informasi Personal Siswa</h2>
                                        </div>
                                    </header>

                                    <div class="space-y-6">
                                        <!-- Nama Lengkap -->
                                        <div>
                                            <label for="namaLengkap" class="ppdb-label">Nama Lengkap <span class="text-brand-signal">*</span></label>
                                            <input type="text" id="namaLengkap" x-model="formData.namaLengkap" @blur="handleBlur('namaLengkap')"
                                                   placeholder="Isi Nama Lengkap" autocomplete="name"
                                                   class="ppdb-input" :class="{ 'ppdb-input-error': touched.namaLengkap && errors.namaLengkap }" />
                                            <p x-show="touched.namaLengkap && errors.namaLengkap" class="ppdb-error" x-text="errors.namaLengkap"></p>
                                            <p x-show="!(touched.namaLengkap && errors.namaLengkap)" class="ppdb-hint">
                                                Mohon isi menggunakan huruf kapital di awal kata disertai spasi yang jelas
                                            </p>
                                        </div>

                                        <!-- Nama Panggilan -->
                                        <div>
                                            <label for="namaPanggilan" class="ppdb-label">Nama Panggilan <span class="text-brand-signal">*</span></label>
                                            <input type="text" id="namaPanggilan" x-model="formData.namaPanggilan" @blur="handleBlur('namaPanggilan')"
                                                   placeholder="Isi Nama Panggilan"
                                                   class="ppdb-input" :class="{ 'ppdb-input-error': touched.namaPanggilan && errors.namaPanggilan }" />
                                            <p x-show="touched.namaPanggilan && errors.namaPanggilan" class="ppdb-error" x-text="errors.namaPanggilan"></p>
                                        </div>

                                        <!-- Tempat Lahir -->
                                        <div>
                                            <label for="tempatLahir" class="ppdb-label">Tempat Lahir Siswa <span class="text-brand-signal">*</span></label>
                                            <input type="text" id="tempatLahir" x-model="formData.tempatLahir" @blur="handleBlur('tempatLahir')"
                                                   placeholder="Contoh: Bogor"
                                                   class="ppdb-input" :class="{ 'ppdb-input-error': touched.tempatLahir && errors.tempatLahir }" />
                                            <p x-show="touched.tempatLahir && errors.tempatLahir" class="ppdb-error" x-text="errors.tempatLahir"></p>
                                        </div>

                                        <!-- Tanggal Lahir (Tanggal, Bulan, Tahun) -->
                                        <fieldset>
                                            <legend class="ppdb-label">Tanggal Lahir Siswa <span class="text-brand-signal">*</span></legend>
                                            <div class="grid grid-cols-3 gap-2.5 sm:gap-3">
                                                <x-dropdown :columns="7">
                                                    <select id="tanggalLahirHari" x-model="formData.tanggalLahirHari" @blur="handleBlur('tanggalLahirHari')" aria-label="Tanggal"
                                                            class="ppdb-input appearance-none pl-4 pr-7 sm:pl-5 sm:pr-11" :class="{ 'ppdb-input-error': touched.tanggalLahirHari && errors.tanggalLahirHari }">
                                                        <option value="" disabled>Tanggal</option>
                                                        <template x-for="d in 31" :key="d">
                                                            <option :value="String(d)" x-text="d"></option>
                                                        </template>
                                                    </select>
                                                </x-dropdown>
                                                <x-dropdown :columns="3">
                                                    <select id="tanggalLahirBulan" x-model="formData.tanggalLahirBulan" @blur="handleBlur('tanggalLahirBulan')" aria-label="Bulan"
                                                            class="ppdb-input appearance-none pl-4 pr-7 sm:pl-5 sm:pr-11" :class="{ 'ppdb-input-error': touched.tanggalLahirBulan && errors.tanggalLahirBulan }">
                                                        <option value="" disabled>Bulan</option>
                                                        <template x-for="(nama, i) in bulanList" :key="i">
                                                            <option :value="String(i + 1)" x-text="nama"></option>
                                                        </template>
                                                    </select>
                                                </x-dropdown>
                                                <x-dropdown :columns="4">
                                                    <select id="tanggalLahirTahun" x-model="formData.tanggalLahirTahun" @blur="handleBlur('tanggalLahirTahun')" aria-label="Tahun"
                                                            class="ppdb-input appearance-none pl-4 pr-7 sm:pl-5 sm:pr-11" :class="{ 'ppdb-input-error': touched.tanggalLahirTahun && errors.tanggalLahirTahun }">
                                                        <option value="" disabled>Tahun</option>
                                                        <template x-for="y in 20" :key="y">
                                                            <option :value="String(2013 - (y - 1))" x-text="2013 - (y - 1)"></option>
                                                        </template>
                                                    </select>
                                                </x-dropdown>
                                            </div>
                                            <p x-show="(touched.tanggalLahirHari && errors.tanggalLahirHari) || (touched.tanggalLahirBulan && errors.tanggalLahirBulan) || (touched.tanggalLahirTahun && errors.tanggalLahirTahun)"
                                               class="ppdb-error">Lengkapi tanggal, bulan, dan tahun lahir.</p>
                                        </fieldset>

                                        <!-- Jenis Kelamin -->
                                        <fieldset>
                                            <legend class="ppdb-label">Jenis Kelamin <span class="text-brand-signal">*</span></legend>
                                            <div class="grid grid-cols-2 gap-2.5 sm:max-w-md">
                                                <label class="choice">
                                                    <input type="radio" value="L" x-model="formData.jenisKelamin" class="sr-only" />
                                                    <span class="choice-box rounded-full"><span class="w-2 h-2 rounded-full bg-current"></span></span>
                                                    Laki-laki
                                                </label>
                                                <label class="choice">
                                                    <input type="radio" value="P" x-model="formData.jenisKelamin" class="sr-only" />
                                                    <span class="choice-box rounded-full"><span class="w-2 h-2 rounded-full bg-current"></span></span>
                                                    Perempuan
                                                </label>
                                            </div>
                                        </fieldset>

                                        <!-- NISN -->
                                        <div>
                                            <label for="nisn" class="ppdb-label">Nomor Induk Siswa Nasional (NISN)</label>
                                            <input type="text" id="nisn" x-model="formData.nisn" inputmode="numeric"
                                                   @input="formData.nisn = formData.nisn.replace(/\D/g, '')" @blur="handleBlur('nisn')" maxlength="10"
                                                   placeholder="10 Digit NISN (cth: 0081298471)"
                                                   class="ppdb-input tabular-nums" :class="{ 'ppdb-input-error': touched.nisn && errors.nisn }" />
                                            <p x-show="touched.nisn && errors.nisn" class="ppdb-error" x-text="errors.nisn"></p>
                                            <p x-show="!(touched.nisn && errors.nisn)" class="ppdb-hint">
                                                10 digit nomor NISN resmi dari SMP/MTs (dapat dicek pada rapor atau kartu pelajar)
                                            </p>
                                        </div>

                                        <!-- Nomor KK -->
                                        <div>
                                            <label for="nomorKK" class="ppdb-label">Nomor Kartu Keluarga</label>
                                            <input type="text" id="nomorKK" x-model="formData.nomorKK" inputmode="numeric"
                                                   @input="formData.nomorKK = formData.nomorKK.replace(/\D/g, '')" maxlength="16"
                                                   placeholder="16 Digit Nomor KK" class="ppdb-input tabular-nums" />
                                            <p class="ppdb-hint">Opsional: Dapat dilengkapi nanti pada saat verifikasi fisik berkas</p>
                                        </div>

                                        <!-- Alamat Lengkap -->
                                        <div>
                                            <label for="alamatLengkap" class="ppdb-label">Alamat Lengkap Domisili</label>
                                            <textarea id="alamatLengkap" x-model="formData.alamatLengkap" rows="3"
                                                      placeholder="Nama jalan, RT/RW, Kelurahan, Kecamatan, Kota/Kabupaten"
                                                      class="ppdb-input rounded-card"></textarea>
                                        </div>
                                    </div>
                                </section>

                                <!-- ------------------------------------------------
                                    BAGIAN B: Sekolah Tujuan & Jurusan
                                    ------------------------------------------------ -->
                                <section id="section-b" x-show="currentStep === 2" class="relative p-6 sm:p-8 animate-fade-up">
                                    <x-sketch.box />
                                    <header class="flex items-start gap-4 pb-8">
                                        <span class="font-display text-4xl md:text-5xl font-bold leading-none text-brand-darkred">02</span>
                                        <div>
                                            <p class="eyebrow">Bagian 02 • Akademik &amp; Kompetensi</p>
                                            <h2 class="mt-2 font-display text-2xl md:text-3xl font-bold uppercase tracking-wide leading-tight">Sekolah Tujuan &amp; Peminatan Vokasi</h2>
                                        </div>
                                    </header>

                                    <div class="space-y-6">
                                        <!-- Sekolah Pilihan -->
                                        <div>
                                            <span class="ppdb-label">Sekolah Pilihan <span class="text-brand-signal">*</span></span>
                                            <div class="grid grid-cols-1 sm:grid-cols-12 gap-3">
                                                <input type="text" readonly value="SMK" aria-label="Jenjang" class="ppdb-input sm:col-span-4 cursor-not-allowed text-brand-ink/70 hover:border-transparent" />
                                                <input type="text" readonly value="SMK Plus Pelita Nusantara Bogor" aria-label="Sekolah" class="ppdb-input sm:col-span-8 cursor-not-allowed text-brand-ink/70 hover:border-transparent" />
                                            </div>
                                        </div>

                                        <!-- Tipe Pendaftar -->
                                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                            <div>
                                                <label for="tipePendaftar" class="ppdb-label">Tipe Pendaftar <span class="text-brand-signal">*</span></label>
                                                <x-dropdown>
                                                    <select id="tipePendaftar" x-model="formData.tipePendaftar" class="ppdb-input appearance-none pr-11">
                                                        <option value="Pendaftar Baru">Pendaftar Baru</option>
                                                        <option value="Pendaftar Pindahan Tengah Tahun Ajaran">Pendaftar Pindahan Tengah Tahun Ajaran</option>
                                                    </select>
                                                </x-dropdown>
                                            </div>

                                            <template x-if="formData.tipePendaftar === 'Pendaftar Pindahan Tengah Tahun Ajaran'">
                                                <div class="animate-fade-up">
                                                    <label for="pindahTahunAjaran" class="ppdb-label">Pindah di Tahun Ajaran <span class="text-brand-signal">*</span></label>
                                                    <x-dropdown>
                                                        <select id="pindahTahunAjaran" x-model="formData.pindahTahunAjaran"
                                                                class="ppdb-input appearance-none pr-11" :class="{ 'ppdb-input-error': touched.pindahTahunAjaran && errors.pindahTahunAjaran }">
                                                            <option value="" disabled>Pilih Tahun Ajaran</option>
                                                            <option value="2026/2027">2026/2027</option>
                                                            <option value="2027/2028">2027/2028</option>
                                                        </select>
                                                    </x-dropdown>
                                                </div>
                                            </template>
                                        </div>

                                        <!-- Kelas & Jurusan -->
                                        <fieldset>
                                            <legend class="ppdb-label">Kelas &amp; Jurusan Peminatan <span class="text-brand-signal">*</span></legend>
                                            <div class="grid grid-cols-1 sm:grid-cols-12 gap-3">
                                                <x-dropdown class="sm:col-span-5">
                                                    <select id="kelasPilihan" x-model="formData.kelasPilihan" aria-label="Kelas"
                                                            @change="handleInputChange('kelasPilihan', $event.target.value)"
                                                            class="ppdb-input appearance-none pr-11">
                                                        <option value="" disabled>Pilih Kelas</option>
                                                        <option value="Kelas 10">Kelas 10</option>
                                                        <option value="Kelas 11">Kelas 11</option>
                                                        <option value="Kelas 12">Kelas 12</option>
                                                    </select>
                                                </x-dropdown>
                                                <div class="sm:col-span-7">
                                                    <x-dropdown>
                                                        <select id="jurusan" x-model="formData.jurusan" aria-label="Jurusan"
                                                                @change="handleInputChange('jurusan', $event.target.value)"
                                                                class="ppdb-input appearance-none pr-11" :class="{ 'ppdb-input-error': touched.jurusan && errors.jurusan }">
                                                            <option value="" disabled>Pilih Jurusan</option>
                                                            @foreach($majors as $fullName => $shortName)
                                                                <option value="{{ $fullName }}">{{ $fullName }}</option>
                                                            @endforeach
                                                        </select>
                                                    </x-dropdown>
                                                    <p x-show="touched.jurusan && errors.jurusan" class="ppdb-error" x-text="errors.jurusan"></p>
                                                </div>
                                            </div>
                                        </fieldset>

                                        <!-- Asal Sekolah -->
                                        <div>
                                            <label for="asalSekolah" class="ppdb-label">Asal Sekolah (SMP / MTs) <span class="text-brand-signal">*</span></label>
                                            <input type="text" id="asalSekolah" x-model="formData.asalSekolah" @blur="handleBlur('asalSekolah')"
                                                   placeholder="Contoh: SMP Negeri 1 Cibinong"
                                                   class="ppdb-input" :class="{ 'ppdb-input-error': touched.asalSekolah && errors.asalSekolah }" />
                                            <p x-show="touched.asalSekolah && errors.asalSekolah" class="ppdb-error" x-text="errors.asalSekolah"></p>
                                            <p x-show="!(touched.asalSekolah && errors.asalSekolah)" class="ppdb-hint">
                                                Tuliskan nama lengkap sekolah asal (SMP / MTs / Sederajat)
                                            </p>
                                        </div>

                                        <!-- Kontak Handphone -->
                                        <fieldset>
                                            <legend class="ppdb-label">Kontak Handphone (WhatsApp) <span class="text-brand-signal">*</span></legend>
                                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                                <div>
                                                    <input type="tel" id="nomorKontakPendaftar" x-model="formData.nomorKontakPendaftar" @blur="handleBlur('nomorKontakPendaftar')"
                                                           placeholder="Handphone Pendaftar" aria-label="Handphone Pendaftar" autocomplete="tel"
                                                           class="ppdb-input" :class="{ 'ppdb-input-error': touched.nomorKontakPendaftar && errors.nomorKontakPendaftar }" />
                                                    <p x-show="touched.nomorKontakPendaftar && errors.nomorKontakPendaftar" class="ppdb-error" x-text="errors.nomorKontakPendaftar"></p>
                                                </div>
                                                <div>
                                                    <input type="tel" id="nomorKontakOrtu" x-model="formData.nomorKontakOrtu" @blur="handleBlur('nomorKontakOrtu')"
                                                           placeholder="Handphone Orang Tua / Wali" aria-label="Handphone Orang Tua / Wali"
                                                           class="ppdb-input" :class="{ 'ppdb-input-error': touched.nomorKontakOrtu && errors.nomorKontakOrtu }" />
                                                    <p x-show="touched.nomorKontakOrtu && errors.nomorKontakOrtu" class="ppdb-error" x-text="errors.nomorKontakOrtu"></p>
                                                </div>
                                            </div>
                                            <p class="ppdb-hint">Pastikan nomor handphone aktif terhubung dengan WhatsApp untuk konfirmasi akun pendaftar.</p>
                                        </fieldset>

                                        <!-- Email -->
                                        <div class="sm:max-w-[calc(50%-0.5rem)]">
                                            <label for="email" class="ppdb-label">Alamat Email Aktif</label>
                                            <input type="email" id="email" x-model.trim="formData.email" autocomplete="email" inputmode="email"
                                                   @blur="handleBlur('email')" @input="touched.email && validateField('email')"
                                                   placeholder="contoh@gmail.com"
                                                   class="ppdb-input" :class="{ 'ppdb-input-error': touched.email && errors.email }"
                                                   :aria-invalid="touched.email && errors.email ? 'true' : 'false'" aria-describedby="email-help" />
                                            <p id="email-help" x-show="touched.email && errors.email" class="ppdb-error" x-text="errors.email"></p>
                                            <p x-show="!(touched.email && errors.email)" class="ppdb-hint">Opsional: Untuk pengiriman bukti formulir digital</p>
                                        </div>
                                    </div>
                                </section>

                                <!-- ------------------------------------------------
                                    BAGIAN C: Informasi Tambahan
                                    ------------------------------------------------ -->
                                <section id="section-c" x-show="currentStep === 3" class="relative p-6 sm:p-8 animate-fade-up">
                                    <x-sketch.box />
                                    <header class="flex items-start gap-4 pb-8">
                                        <span class="font-display text-4xl md:text-5xl font-bold leading-none text-brand-darkred">03</span>
                                        <div>
                                            <p class="eyebrow">Bagian 03 • Preferensi &amp; Kelengkapan</p>
                                            <h2 class="mt-2 font-display text-2xl md:text-3xl font-bold uppercase tracking-wide leading-tight">Informasi Tambahan &amp; Layanan</h2>
                                        </div>
                                    </header>

                                    <div class="space-y-8">
                                        <!-- Sumber Informasi (pilih lebih dari satu) -->
                                        <fieldset>
                                            <legend class="ppdb-label">Sumber Informasi PPDB</legend>
                                            <p class="ppdb-hint mt-0 mb-3">Dari mana Anda mengetahui SMK Pelita Nusantara? Dapat memilih lebih dari 1 sumber informasi.</p>
                                            <div class="grid gap-2.5 sm:grid-cols-2">
                                                <template x-for="src in sumberInfoOptions" :key="src">
                                                    <label class="choice">
                                                        <input type="checkbox" class="sr-only" :value="src" x-model="formData.sumberInfo" />
                                                        <span class="choice-box">
                                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" /></svg>
                                                        </span>
                                                        <span x-text="src"></span>
                                                    </label>
                                                </template>
                                            </div>

                                            <div x-show="formData.sumberInfo.includes('Lain-lain')" class="mt-3 animate-fade-up">
                                                <input type="text" x-model="formData.sumberInfoLainnya" aria-label="Sumber informasi lainnya"
                                                       placeholder="Tuliskan sumber informasi lainnya" class="ppdb-input" />
                                            </div>
                                        </fieldset>

                                        <!-- Alasan Minat (pilih lebih dari satu) -->
                                        <fieldset>
                                            <legend class="ppdb-label">Alasan Utama Minat Mendaftar</legend>
                                            <p class="ppdb-hint mt-0 mb-3">Mengapa Anda memilih sekolah ini? Dapat memilih lebih dari 1 alasan.</p>
                                            <div class="grid gap-2.5 sm:grid-cols-2">
                                                <template x-for="alasan in alasanMinatOptions" :key="alasan">
                                                    <label class="choice">
                                                        <input type="checkbox" class="sr-only" :value="alasan" x-model="formData.alasanMinat" />
                                                        <span class="choice-box">
                                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" /></svg>
                                                        </span>
                                                        <span x-text="alasan"></span>
                                                    </label>
                                                </template>
                                            </div>

                                            <div x-show="formData.alasanMinat.includes('Lainnya')" class="mt-3 animate-fade-up">
                                                <input type="text" x-model="formData.alasanMinatLainnya" aria-label="Alasan lainnya"
                                                       placeholder="Tuliskan alasan lainnya" class="ppdb-input" />
                                            </div>
                                        </fieldset>
                                    </div>
                                </section>

                                <!-- ------------------------------------------------
                                    Persetujuan & tombol navigasi
                                    ------------------------------------------------ -->
                                <div class="space-y-6">
                                    <div x-show="currentStep === 3" class="rounded-card bg-brand-softmist p-6">
                                        <label class="flex items-start gap-3.5 select-none">
                                            <input type="checkbox" id="terms" x-model="termsAccepted"
                                                   class="w-5 h-5 rounded mt-0.5 shrink-0 accent-[#7A1018]" />
                                            <span class="text-sm text-brand-ink/80 leading-relaxed">
                                                <span class="text-brand-signal font-bold">* </span>
                                                Saya memahami bahwa setelah data disubmit dan diverifikasi, perubahan data
                                                utama hanya dapat dilakukan melalui panitia. Biaya pendaftaran yang telah
                                                dibayarkan tidak dapat ditarik kembali.
                                            </span>
                                        </label>
                                    </div>

                                    <div class="flex flex-col-reverse sm:flex-row gap-3">
                                        <button type="button" x-show="currentStep > 1" @click="handlePrev()" class="group btn-secondary sm:w-1/3 py-3.5">
                                            <x-sketch.arrow class="w-6 h-3 -scale-x-100 transition-transform group-hover:-translate-x-1" />
                                            Kembali
                                        </button>

                                        <button type="button" id="next-single-form" :disabled="currentStep === 3 && !termsAccepted" @click="handleNext()"
                                                class="group btn-primary flex-1 py-3.5">
                                            <span x-text="currentStep === 3 ? 'Lanjut ke Validasi Data' : 'Lanjut ke ' + steps[currentStep].label"></span>
                                            <x-sketch.arrow class="w-6 h-3 transition-transform group-hover:translate-x-1" />
                                        </button>
                                    </div>

                                    <div class="text-center">
                                        <button type="button" @click="handleBatal()" class="text-sm font-semibold text-brand-ink/60 transition-colors hover:text-brand-darkred">
                                            Batalkan Pengisian
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <!-- ====================================================
                                LANGKAH 4: VALIDASI DATA
                                ==================================================== -->
                            <div x-show="currentStep === 4" class="space-y-12 animate-fade-up">
                                <div>
                                    <p class="eyebrow"><x-sketch.sparks>Tahap Final • Konfirmasi Data</x-sketch.sparks></p>
                                    <h2 class="mt-3 font-display text-3xl md:text-4xl font-bold uppercase tracking-wide leading-tight">Validasi Data Pendaftaran</h2>
                                    <p class="mt-4 text-base leading-relaxed text-brand-ink/70">
                                        Mohon teliti kembali seluruh isian sebelum mengirimkan berkas pendaftaran Anda ke sistem resmi.
                                    </p>
                                </div>

                                <!-- Ringkasan A -->
                                <section class="relative p-6 sm:p-7">
                                    <x-sketch.box />
                                    <div class="flex items-center justify-between gap-4 pb-2">
                                        <h3 class="font-display text-lg font-bold uppercase tracking-wide text-brand-darkred">A. Informasi Personal</h3>
                                        <button type="button" @click="setStep(1)" class="inline-flex items-center gap-1.5 text-sm font-semibold text-brand-darkred hover:underline">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" /></svg>
                                            Ubah Data
                                        </button>
                                    </div>
                                    <dl>
                                        <x-review-row label="Nama Lengkap" x-text="formData.namaLengkap || '-'" />
                                        <x-review-row label="Nama Panggilan" x-text="formData.namaPanggilan || '-'" />
                                        <x-review-row label="Nomor NISN" class="tabular-nums" x-text="formData.nisn || '-'" />
                                        <x-review-row label="Nomor Kartu Keluarga" class="tabular-nums" x-text="formData.nomorKK || '-'" />
                                        <x-review-row label="Tempat Lahir" x-text="formData.tempatLahir || '-'" />
                                        <x-review-row label="Tanggal Lahir" x-text="tanggalLahirLabel()" />
                                        <x-review-row label="Jenis Kelamin" x-text="formData.jenisKelamin === 'L' ? 'Laki-laki' : 'Perempuan'" />
                                        <x-review-row label="Alamat Lengkap" x-text="formData.alamatLengkap || '-'" />
                                    </dl>
                                </section>

                                <!-- Ringkasan B -->
                                <section class="relative p-6 sm:p-7">
                                    <x-sketch.box />
                                    <div class="flex items-center justify-between gap-4 pb-2">
                                        <h3 class="font-display text-lg font-bold uppercase tracking-wide text-brand-darkred">B. Sekolah Tujuan &amp; Jurusan</h3>
                                        <button type="button" @click="setStep(2)" class="inline-flex items-center gap-1.5 text-sm font-semibold text-brand-darkred hover:underline">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" /></svg>
                                            Ubah Data
                                        </button>
                                    </div>
                                    <dl>
                                        <x-review-row label="Sekolah Pilihan">SMK - SMK Plus Pelita Nusantara Bogor</x-review-row>
                                        <x-review-row label="Tipe Pendaftar" x-text="formData.tipePendaftar" />
                                        <template x-if="formData.tipePendaftar === 'Pendaftar Pindahan Tengah Tahun Ajaran'">
                                            <x-review-row label="Pindah di Tahun Ajaran" x-text="formData.pindahTahunAjaran || '-'" />
                                        </template>
                                        <x-review-row label="Kelas Pilihan" x-text="formData.kelasPilihan" />
                                        <x-review-row label="Program Keahlian" class="text-brand-darkred" x-text="formData.jurusan || '-'" />
                                        <x-review-row label="Asal Sekolah" x-text="formData.asalSekolah" />
                                        <x-review-row label="Kontak Handphone Pendaftar" x-text="formData.nomorKontakPendaftar" />
                                        <x-review-row label="Kontak Handphone Orang Tua" x-text="formData.nomorKontakOrtu" />
                                        <x-review-row label="Email" x-text="formData.email || '-'" />
                                    </dl>
                                </section>

                                <!-- Ringkasan C -->
                                <section class="relative p-6 sm:p-7">
                                    <x-sketch.box />
                                    <div class="flex items-center justify-between gap-4 pb-2">
                                        <h3 class="font-display text-lg font-bold uppercase tracking-wide text-brand-darkred">C. Informasi Tambahan &amp; Layanan</h3>
                                        <button type="button" @click="setStep(3)" class="inline-flex items-center gap-1.5 text-sm font-semibold text-brand-darkred hover:underline">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" /></svg>
                                            Ubah Data
                                        </button>
                                    </div>
                                    <dl>
                                        <x-review-row label="Sumber Informasi PPDB">
                                            <span x-show="formData.sumberInfo.length === 0" class="text-brand-ink/40">-</span>
                                            <span class="flex flex-wrap gap-1.5">
                                                <template x-for="src in formData.sumberInfo" :key="src">
                                                    <span class="inline-block rounded-full bg-brand-darkred/10 px-3 py-1 text-xs font-semibold text-brand-darkred"
                                                          x-text="src === 'Lain-lain' && formData.sumberInfoLainnya ? 'Lain-lain (' + formData.sumberInfoLainnya + ')' : src"></span>
                                                </template>
                                            </span>
                                        </x-review-row>
                                        <x-review-row label="Alasan Minat Mendaftar">
                                            <span x-show="formData.alasanMinat.length === 0" class="text-brand-ink/40">-</span>
                                            <span class="flex flex-wrap gap-1.5">
                                                <template x-for="alasan in formData.alasanMinat" :key="alasan">
                                                    <span class="inline-block rounded-full bg-brand-darkred/10 px-3 py-1 text-xs font-semibold text-brand-darkred"
                                                          x-text="alasan === 'Lainnya' && formData.alasanMinatLainnya ? 'Lainnya (' + formData.alasanMinatLainnya + ')' : alasan"></span>
                                                </template>
                                            </span>
                                        </x-review-row>
                                    </dl>
                                </section>

                                <!-- Pernyataan kebenaran data -->
                                <div class="rounded-card bg-brand-softmist p-6">
                                    <label class="flex items-start gap-3.5 select-none">
                                        <input type="checkbox" x-model="validasiConfirmed" class="w-5 h-5 rounded mt-0.5 shrink-0 accent-[#7A1018]" />
                                        <span class="text-sm text-brand-ink/80 leading-relaxed">
                                            Saya menyatakan bahwa seluruh data yang diisikan di atas adalah benar, lengkap,
                                            dan dapat dipertanggungjawabkan sesuai ketentuan panitia PPDB SMK Plus Pelita
                                            Nusantara Bogor.
                                        </span>
                                    </label>
                                </div>

                                <div class="space-y-3">
                                    <button type="button" :disabled="!validasiConfirmed || isSubmitting" @click="handleSubmitFinal()" class="group btn-primary w-full py-4 text-base">
                                        <template x-if="isSubmitting">
                                            <span class="flex items-center gap-2">
                                                <svg class="animate-spin w-5 h-5" fill="none" viewBox="0 0 24 24" aria-hidden="true">
                                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
                                                </svg>
                                                Mengirim Berkas Pendaftaran...
                                            </span>
                                        </template>
                                        <template x-if="!isSubmitting">
                                            <span class="flex items-center gap-2">
                                                Kirim Pendaftaran Resmi
                                                <x-sketch.arrow class="w-7 h-3.5 transition-transform group-hover:translate-x-1" />
                                            </span>
                                        </template>
                                    </button>

                                    <button type="button" @click="setStep(3)" class="btn-secondary w-full">
                                        Kembali Periksa Formulir
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </template>
</div>
@endsection

@push('scripts')
<script>
    // Pola email sederhana: nama@domain.tld (tld minimal 2 huruf). Server memakai aturan email:rfc,filter yang setara.
    const PPDB_EMAIL_PATTERN = /^[a-z0-9_%+'-]+(\.[a-z0-9_%+'-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*\.[a-z]{2,}$/i;

    function emptyPpdbForm() {
        return {
            namaLengkap: '',
            namaPanggilan: '',
            nisn: '',
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
            asalSekolah: '',
            nomorKontakPendaftar: '',
            nomorKontakOrtu: '',
            email: '',
            jenisLayanan: [],
            sumberInfo: [],
            sumberInfoLainnya: '',
            alasanMinat: [],
            alasanMinatLainnya: '',
        };
    }

    function ppdbForm() {
        return {
            currentStep: 1,
            steps: [
                { n: 1, label: 'Identitas Diri' },
                { n: 2, label: 'Akademik & Kontak' },
                { n: 3, label: 'Info Tambahan' },
                { n: 4, label: 'Validasi Data' },
            ],
            bulanList: ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'],
            sumberInfoOptions: @json($sumberInfoOptions),
            alasanMinatOptions: @json($alasanMinatOptions),
            isJadwalOpen: true,
            termsAccepted: false,
            validasiConfirmed: false,
            isSubmitting: false,
            isSubmittedSuccess: false,
            isSubmittedAttempt: false,
            errors: {},
            touched: {},
            submissionData: { id: null, uuid: null, noPendaftaran: '', tanggalDaftar: '' },
            formData: emptyPpdbForm(),

            handleInputChange(field, value) {
                this.formData[field] = value;
                if (field === 'kelasPilihan') {
                    this.formData.jurusan = '';
                }
                if (this.errors[field]) {
                    delete this.errors[field];
                }
            },

            handleBlur(field) {
                this.touched[field] = true;
                this.validateField(field);
            },

            tanggalLahirLabel() {
                const { tanggalLahirHari: hari, tanggalLahirBulan: bulan, tanggalLahirTahun: tahun } = this.formData;
                if (!hari) return '-';
                return `${hari} ${this.bulanList[Number(bulan) - 1] ?? bulan} ${tahun}`;
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
                    case 'nisn':
                        if (val && val.trim() && val.trim().length !== 10) this.errors.nisn = 'NISN harus 10 digit angka.';
                        else delete this.errors.nisn;
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
                    case 'asalSekolah':
                        if (!val || !val.trim()) this.errors.asalSekolah = 'Asal sekolah wajib diisi.';
                        else delete this.errors.asalSekolah;
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
                    case 'email':
                        // Opsional, tapi kalau diisi harus berformat email yang benar
                        if (val && !PPDB_EMAIL_PATTERN.test(val)) this.errors.email = 'Format email tidak valid. Contoh: nama@gmail.com';
                        else delete this.errors.email;
                        break;
                }
            },

            // Kolom wajib di tiap langkah isian
            stepFields(step) {
                if (step === 1) {
                    return ['namaLengkap', 'namaPanggilan', 'tempatLahir', 'tanggalLahirHari', 'tanggalLahirBulan', 'tanggalLahirTahun', 'jenisKelamin'];
                }
                if (step === 2) {
                    const fields = ['tipePendaftar', 'kelasPilihan', 'jurusan', 'asalSekolah', 'nomorKontakPendaftar', 'nomorKontakOrtu'];
                    if (this.formData.tipePendaftar === 'Pendaftar Pindahan Tengah Tahun Ajaran') {
                        fields.push('pindahTahunAjaran');
                    }
                    return fields;
                }
                return [];
            },

            // Kolom opsional yang tetap divalidasi formatnya kalau diisi
            optionalStepFields(step) {
                return { 1: ['nisn'], 2: ['email'] }[step] ?? [];
            },

            // Langkah tempat sebuah kolom berada, dipakai untuk menampilkan galat dari server
            stepOfField(field) {
                if ([...this.stepFields(1), ...this.optionalStepFields(1), 'nomorKK', 'alamatLengkap'].includes(field)) return 1;
                if ([...this.stepFields(2), ...this.optionalStepFields(2), 'pindahTahunAjaran', 'sekolahPilihanLevel', 'sekolahPilihanUnit'].includes(field)) return 2;
                return 3;
            },

            stepHasErrors(step) {
                return [...this.stepFields(step), ...this.optionalStepFields(step)].some(f => this.errors[f]);
            },

            validateStep(step) {
                let isValid = true;

                this.stepFields(step).forEach(f => {
                    delete this.errors[f];
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

                this.optionalStepFields(step).forEach(f => {
                    this.touched[f] = true;
                    this.validateField(f);
                    if (this.errors[f]) isValid = false;
                });

                return isValid;
            },

            scrollToStepper() {
                this.$nextTick(() => {
                    document.getElementById('stepper')?.scrollIntoView({ behavior: 'smooth', block: 'start' });
                });
            },

            setStep(step) {
                this.currentStep = step;
                this.isSubmittedAttempt = false;
                this.scrollToStepper();
            },

            // Pindah ke langkah tertentu. Maju hanya boleh jika semua langkah isian sebelumnya valid.
            goToStep(target) {
                if (target === this.currentStep) return;
                if (target < this.currentStep) {
                    this.setStep(target);
                    return;
                }

                for (let step = this.currentStep; step < target && step <= 3; step++) {
                    if (step === 3 && !this.termsAccepted) {
                        this.setStep(3);
                        alert('Silahkan centang persetujuan kebenaran data terlebih dahulu.');
                        return;
                    }
                    if (!this.validateStep(step)) {
                        this.currentStep = step;
                        this.isSubmittedAttempt = true;
                        this.focusFirstError(step);
                        return;
                    }
                }
                this.setStep(target);
            },

            focusFirstError(step) {
                const key = [...this.stepFields(step), ...this.optionalStepFields(step)].find(f => this.errors[f]);
                this.$nextTick(() => {
                    const el = key && document.getElementById(key);
                    if (el) {
                        el.scrollIntoView({ behavior: 'smooth', block: 'center' });
                        el.focus();
                    } else {
                        this.scrollToStepper();
                    }
                });
            },

            handleNext() {
                if (this.currentStep === 3 && !this.termsAccepted) {
                    alert('Silahkan centang persetujuan kebenaran data terlebih dahulu.');
                    return;
                }

                if (this.currentStep > 3) return;

                if (this.validateStep(this.currentStep)) {
                    this.setStep(this.currentStep + 1);
                } else {
                    this.isSubmittedAttempt = true;
                    this.focusFirstError(this.currentStep);
                }
            },

            handlePrev() {
                if (this.currentStep > 1) this.setStep(this.currentStep - 1);
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
                this.submissionData = { id: null, uuid: null, noPendaftaran: '', tanggalDaftar: '' };
                this.formData = emptyPpdbForm();
                window.scrollTo({ top: 0, behavior: 'smooth' });
            },

            // Galat validasi dari server (422): tandai kolomnya lalu kembali ke langkah pertama yang bermasalah
            showServerErrors(serverErrors) {
                const fields = Object.keys(serverErrors);
                fields.forEach(field => {
                    const key = field.split('.')[0];
                    this.errors[key] = serverErrors[field][0];
                    this.touched[key] = true;
                });

                const step = Math.min(...fields.map(f => this.stepOfField(f.split('.')[0])));
                this.currentStep = step;
                this.isSubmittedAttempt = true;
                if (step <= 2) {
                    this.focusFirstError(step);
                } else {
                    this.scrollToStepper();
                    alert(Object.values(serverErrors)[0][0]);
                }
            },

            async handleSubmitFinal() {
                if (!this.validasiConfirmed || this.isSubmitting) return;

                this.isSubmitting = true;
                const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

                try {
                    const response = await fetch('/ppdb/daftar', {
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
                            id: data.data?.id,
                            uuid: data.data?.uuid,
                            noPendaftaran: data.noPendaftaran,
                            tanggalDaftar: data.tanggalDaftar,
                        };
                        this.isSubmittedSuccess = true;
                        window.scrollTo({ top: 0, behavior: 'smooth' });
                    } else if (response.status === 422 && data.errors) {
                        this.showServerErrors(data.errors);
                    } else {
                        alert(data.message || 'Gagal menyimpan pendaftaran.');
                    }
                } catch (err) {
                    console.error(err);
                    alert('Terjadi kesalahan jaringan.');
                } finally {
                    this.isSubmitting = false;
                }
            }
        };
    }
</script>
@endpush
