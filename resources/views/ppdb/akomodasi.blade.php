@extends('layouts.app')

@section('title', 'Info Biaya PPDB - SMK Plus Pelita Nusantara')

@section('content')
@php
    $tahunAjaran = $activeWave?->tahun_ajaran ?? '2027/2028';
    $calcBiayaSeragam = (int) ($biayaSeragam ?? 1975000);
    $calcDspCash = (int) ($dspCash ?? 5850000);
    $calcSppBulanan = (int) ($sppBulanan ?? 450000);
    $calcBiayaPendidikan = max(0, $calcDspCash - $calcBiayaSeragam);
    $calcFixedBiaya = 500000;
    $calcDanaPembangunan = max(0, $calcBiayaPendidikan - $calcSppBulanan - $calcFixedBiaya);
    $tahunAjaranAwal = substr($tahunAjaran, 0, 4);
    $rupiah = fn (int $nilai) => 'Rp. '.number_format($nilai, 0, ',', '.').',-';

    $stats = [
        ['label' => 'Total Biaya Masuk', 'value' => 'Rp '.number_format($calcDspCash, 0, ',', '.'), 'note' => 'PPDB + 13 Item Seragam & Atribut'],
        ['label' => 'Biaya Pendidikan PPDB', 'value' => 'Rp '.number_format($calcBiayaPendidikan, 0, ',', '.'), 'note' => 'Termasuk SPP Bulan Pertama & Pelatihan Karakter'],
        ['label' => 'Seragam & Atribut', 'value' => 'Rp '.number_format($calcBiayaSeragam, 0, ',', '.'), 'note' => '13 Perlengkapan Resmi Sekolah'],
    ];

    $biayaPpdb = [
        ['uraian' => 'Dana Pembangunan Pendidikan', 'keterangan' => 'Pengembangan sarana & laboratorium kejuruan terpadu', 'biaya' => $calcDanaPembangunan],
        ['uraian' => 'SPP Bulan Pertama (Juli '.$tahunAjaranAwal.')', 'keterangan' => 'Iuran operasional pendidikan bulan pertama masuk', 'biaya' => $calcSppBulanan],
        ['uraian' => 'Pelatihan Learning Skill & Karakter', 'keterangan' => 'Pembinaan karakter taruna & kesiapan vokasi industri', 'biaya' => 300000],
        ['uraian' => 'Pass Photo', 'keterangan' => 'Foto resmi kartu tanda pelajar, rapor & arsip PPDB', 'biaya' => 100000],
        ['uraian' => 'Tabungan Simpanan Wajib Sekolah', 'keterangan' => 'Simpanan wajib siswa terdaftar', 'biaya' => 50000],
        ['uraian' => 'Tabungan Simpanan Pokok Koperasi', 'keterangan' => 'Keanggotaan koperasi sekolah siswa', 'biaya' => 50000],
    ];

    $biayaSeragamList = [
        ['uraian' => 'Jaket Almamater', 'jenis' => 'Pakaian Resmi', 'biaya' => 210000],
        ['uraian' => 'Seragam Olahraga', 'jenis' => 'Pakaian Olahraga', 'biaya' => 200000],
        ['uraian' => 'Seragam Taruna', 'jenis' => 'Pakaian Khas', 'biaya' => 470000],
        ['uraian' => 'Seragam Batik', 'jenis' => 'Pakaian Khas', 'biaya' => 140000],
        ['uraian' => 'Seragam Praktik', 'jenis' => 'Pakaian Kejuruan', 'biaya' => 165000],
        ['uraian' => 'Seragam Muslim', 'jenis' => 'Pakaian Khas', 'biaya' => 130000],
        ['uraian' => 'Seragam Putih', 'jenis' => 'Pakaian Harian', 'biaya' => 80000],
        ['uraian' => 'Topi Penus', 'jenis' => 'Atribut', 'biaya' => 55000],
        ['uraian' => 'Dasi Penus', 'jenis' => 'Atribut', 'biaya' => 45000],
        ['uraian' => '7 Badge Sekolah', 'jenis' => 'Atribut', 'biaya' => 65000],
        ['uraian' => 'Sepatu PDL', 'jenis' => 'Alas Kaki', 'biaya' => 220000],
        ['uraian' => 'Sepatu Cats', 'jenis' => 'Alas Kaki', 'biaya' => 150000],
        ['uraian' => 'Pin Logo SMK', 'jenis' => 'Atribut', 'biaya' => 45000],
    ];

    $faqList = [
        [
            'tanya' => 'Berapa total biaya pendaftaran awal masuk SMK Plus Pelita Nusantara?',
            'jawab' => 'Total biaya masuk cash adalah Rp '.number_format($calcDspCash, 0, ',', '.').',- yang sudah mencakup Rincian Biaya PPDB (Rp '.number_format($calcBiayaPendidikan, 0, ',', '.').',- termasuk SPP bulan pertama, dana pembangunan, pelatihan karakter, foto & simpanan koperasi) serta Rincian Seragam & 13 Item Atribut Resmi Lengkap (Rp '.number_format($calcBiayaSeragam, 0, ',', '.').',-).',
        ],
        [
            'tanya' => 'Apakah pembayaran biaya PPDB dapat diangsur / dicicil?',
            'jawab' => 'Ya, sekolah menyediakan skema pembayaran bertahap (angsuran) untuk memudahkan orang tua murid. Pembayaran tahap pertama dapat diselesaikan saat daftar ulang, dan sisa pembayaran dapat diangsur sesuai kesepakatan tertulis dengan pihak panitia PPDB.',
        ],
        [
            'tanya' => 'Apa saja seragam dan atribut yang didapatkan oleh siswa baru?',
            'jawab' => 'Siswa mendapatkan 13 perlengkapan lengkap: Jaket Almamater, Seragam Olahraga, Seragam Taruna, Seragam Batik, Seragam Praktik Kejuruan, Seragam Muslim, Seragam Putih, Topi Penus, Dasi Penus, 7 Badge Sekolah, Sepatu PDL, Sepatu Cats, dan Pin Logo SMK.',
        ],
        [
            'tanya' => 'Bagaimana cara melakukan pembayaran biaya PPDB?',
            'jawab' => 'Pembayaran dapat dilakukan langsung secara tunai (cash) di loket panitia PPDB kampus SMK Plus Pelita Nusantara Cibinong. Untuk pembayaran non-tunai, silakan hubungi panitia PPDB melalui WhatsApp untuk informasi dan konfirmasi pembayaran.',
        ],
        [
            'tanya' => 'Apakah ada program beasiswa untuk siswa berprestasi?',
            'jawab' => "Tersedia beasiswa jalur prestasi akademik (peringkat kelas / nilai rapor) serta prestasi non-akademik (olahraga, seni, tahfidz Al-Qur'an, dan kompetisi tingkat kota/nasional) dengan potongan biaya pendidikan sesuai ketentuan yayasan.",
        ],
    ];
@endphp

<div x-data="{
    openFaq: 0,
    modalKonsultasiOpen: false,
    konsultasiSuccess: false,
    konsultasiForm: {
        namaLengkap: '',
        nisn: '',
        noHpWa: '',
        jurusanMinat: 'Rekayasa Perangkat Lunak (RPL)',
        rencanaPembayaran: 'Cash Lunas (Rp {{ number_format($calcDspCash, 0, ',', '.') }})',
        catatan: ''
    },
    formErrors: {},
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
            `Saya ingin berkonsultasi mengenai pembiayaan & pendaftaran PPDB {{ $tahunAjaran }}:\n` +
            `• Nama Siswa: ${this.konsultasiForm.namaLengkap}\n` +
            `• NISN: ${this.konsultasiForm.nisn || '-'}\n` +
            `• No. WA: ${this.konsultasiForm.noHpWa}\n` +
            `• Jurusan Pilihan: ${this.konsultasiForm.jurusanMinat}\n` +
            `• Rencana Pembayaran: ${this.konsultasiForm.rencanaPembayaran}\n` +
            `• Pertanyaan / Catatan: ${this.konsultasiForm.catatan || '-'}\n\n` +
            `Mohon petunjuk prosedur pendaftaran dan rincian pembayarannya. Terima kasih!`
        );
        window.open(`https://wa.me/{{ $kontakWa ?? '6281283921029' }}?text=${text}`, '_blank');
    }
}" @keydown.escape.window="modalKonsultasiOpen = false" class="text-brand-ink">

    <x-page-hero
        current="Info Biaya"
        :eyebrow="'Tahun Ajaran ' . $tahunAjaran"
        title="Info Biaya PPDB"
        description="Rincian pembiayaan pendidikan serta seragam & atribut lengkap bagi calon peserta didik baru SMK Plus Pelita Nusantara."
    >
        <a href="#tabel-biaya" class="group btn-primary">
            Lihat Tabel Pembiayaan
            <x-sketch.arrow direction="down" class="w-3 h-6 transition-transform group-hover:translate-y-0.5" />
        </a>
        <button type="button" @click="handleOpenKonsultasi()" class="btn-secondary">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" /></svg>
            Konsultasi Pembiayaan
        </button>
        <a href="{{ url('/ppdb') }}" class="group inline-flex items-center gap-1.5 px-3 py-3 text-sm font-semibold text-brand-darkred">
            Form PPDB Online
            <x-sketch.arrow class="w-6 h-3 transition-transform group-hover:translate-x-1" />
        </a>
    </x-page-hero>

    <main class="px-6 pt-14 md:pt-20">
        <div class="max-w-6xl mx-auto">
            <!-- Ringkasan biaya, seperti baris angka di section "Mengapa Memilih" landing page -->
            <dl class="grid grid-cols-1 sm:grid-cols-3 gap-y-6 rounded-card bg-white py-6 shadow-softpill">
                @foreach ($stats as $stat)
                    <div class="relative flex flex-col-reverse px-5 text-center">
                        @unless ($loop->first)
                            <x-sketch.rule vertical :scale="1.6" :delay="$loop->index * 150" class="hidden sm:block text-brand-darkred/50 -top-1 -bottom-1 -left-1.5 w-3" />
                        @endunless
                        <dt class="mt-1 text-sm text-brand-ink/70">
                            <span class="block eyebrow text-[11px] mb-1">{{ $stat['label'] }}</span>
                            {{ $stat['note'] }}
                        </dt>
                        <dd class="font-display text-2xl md:text-3xl font-bold tracking-wide text-brand-darkred">{{ $stat['value'] }}</dd>
                    </div>
                @endforeach
            </dl>

            <div class="mt-16 grid gap-12 lg:gap-14 lg:grid-cols-[1fr_2fr] items-start">

                <!-- SIDEBAR -->
                <aside class="order-2 lg:order-1 space-y-8 lg:sticky lg:top-32">
                    <!-- Ringkasan skema cash -->
                    <div class="relative p-6">
                        <x-sketch.box />
                        <div class="flex items-center justify-between gap-3">
                            <p class="eyebrow">Skema Resmi Cash</p>
                            <span class="bg-brand-darkred/10 text-brand-darkred px-2.5 py-0.5 rounded-full text-[10px] font-bold">{{ $tahunAjaran }}</span>
                        </div>

                        <dl class="mt-5 space-y-2.5 text-sm">
                            <div class="flex justify-between gap-3 text-brand-ink/70">
                                <dt>Uraian PPDB (6 Item):</dt>
                                <dd class="font-semibold text-brand-ink whitespace-nowrap">{{ $rupiah($calcBiayaPendidikan) }}</dd>
                            </div>
                            <div class="flex justify-between gap-3 text-brand-ink/70">
                                <dt>Seragam &amp; Atribut (13 Item):</dt>
                                <dd class="font-semibold text-brand-ink whitespace-nowrap">{{ $rupiah($calcBiayaSeragam) }}</dd>
                            </div>
                            <div class="relative flex justify-between items-baseline gap-3 pt-4">
                                <x-sketch.rule class="text-brand-darkred/40 -left-1 -right-1 -top-1 h-3" />
                                <dt class="font-semibold text-brand-ink">Total Biaya Masuk:</dt>
                                <dd class="font-display text-xl font-bold tracking-wide text-brand-darkred whitespace-nowrap">{{ $rupiah($calcDspCash) }}</dd>
                            </div>
                        </dl>

                        <button type="button" @click="handleOpenKonsultasi()" class="btn-primary w-full mt-6">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" /></svg>
                            Ajukan Konsultasi / Cicilan
                        </button>
                    </div>

                    <!-- Tautan ke landing page -->
                    <div class="rounded-card bg-brand-softmist p-6">
                        <p class="eyebrow">Informasi Sekolah</p>
                        <h3 class="mt-2 font-sans text-base font-semibold">Kenali SMK Plus Pelita Nusantara</h3>
                        <p class="mt-1 text-sm leading-relaxed text-brand-ink/70">
                            Profil sekolah, kompetensi keahlian, program unggulan, ekstrakurikuler, dan prestasi siswa dapat dilihat di halaman utama.
                        </p>
                        <a href="/" class="group mt-5 inline-flex items-center gap-2 text-sm font-semibold text-brand-darkred">
                            Kunjungi Halaman Utama
                            <x-sketch.arrow class="w-6 h-3 transition-transform group-hover:translate-x-1" />
                        </a>
                    </div>
                </aside>

                <!-- KONTEN UTAMA -->
                <div class="order-1 lg:order-2 min-w-0 space-y-16">

                    <!-- 1. URAIAN PEMBIAYAAN PPDB -->
                    <section id="tabel-biaya" class="scroll-mt-32">
                        <header class="flex items-start gap-4">
                            <span class="font-display text-4xl md:text-5xl font-bold leading-none text-brand-darkred">01</span>
                            <div>
                                <p class="eyebrow">Uraian Biaya Utama</p>
                                <h2 class="mt-2 font-display text-2xl md:text-3xl font-bold uppercase tracking-wide leading-tight">Uraian Pembiayaan PPDB</h2>
                            </div>
                        </header>

                        <div class="relative mt-8">
                            <x-sketch.box />
                            <div class="overflow-x-auto">
                                <table class="w-full text-left text-sm">
                                    <thead>
                                        <tr class="relative text-[11px] uppercase tracking-[0.2em] text-brand-darkred">
                                            <th class="py-4 pl-5 pr-3 w-14 font-semibold">No</th>
                                            <th class="py-4 px-3 font-semibold">Uraian Biaya</th>
                                            <th class="py-4 pl-3 pr-5 text-right font-semibold whitespace-nowrap">
                                                Biaya Cash
                                                <x-sketch.rule bold class="text-brand-darkred/60 -left-1 -right-1 -bottom-1.5 h-3" />
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($biayaPpdb as $item)
                                            <tr class="relative">
                                                <td class="py-3.5 pl-5 pr-3 align-top font-display font-bold text-brand-darkred">{{ $loop->iteration }}.</td>
                                                <td class="py-3.5 px-3">
                                                    <div class="font-semibold">{{ $item['uraian'] }}</div>
                                                    <div class="mt-0.5 text-xs text-brand-ink/60">{{ $item['keterangan'] }}</div>
                                                </td>
                                                <td class="py-3.5 pl-3 pr-5 text-right font-semibold whitespace-nowrap align-top">
                                                    {{ $rupiah($item['biaya']) }}
                                                    <x-sketch.rule :delay="$loop->index * 80" class="text-brand-darkred/25 -left-1 -right-1 -bottom-1.5 h-3" />
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <td colspan="2" class="py-4 pl-5 pr-3 text-xs font-semibold uppercase tracking-[0.2em] text-brand-ink/70">Jumlah</td>
                                            <td class="py-4 pl-3 pr-5 text-right font-display text-xl font-bold tracking-wide text-brand-darkred whitespace-nowrap">{{ $rupiah($calcBiayaPendidikan) }}</td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </section>

                    <!-- 2. URAIAN PEMBIAYAAN SERAGAM & ATRIBUT -->
                    <section id="tabel-seragam">
                        <header class="flex items-start gap-4">
                            <span class="font-display text-4xl md:text-5xl font-bold leading-none text-brand-darkred">02</span>
                            <div>
                                <p class="eyebrow">Perlengkapan Resmi Lengkap</p>
                                <h2 class="mt-2 font-display text-2xl md:text-3xl font-bold uppercase tracking-wide leading-tight">Uraian Pembiayaan Seragam &amp; Atribut</h2>
                            </div>
                        </header>

                        <div class="relative mt-8">
                            <x-sketch.box />
                            <div class="overflow-x-auto">
                                <table class="w-full text-left text-sm">
                                    <thead>
                                        <tr class="relative text-[11px] uppercase tracking-[0.2em] text-brand-darkred">
                                            <th class="py-4 pl-5 pr-3 w-14 font-semibold">No</th>
                                            <th class="py-4 px-3 font-semibold">Uraian Biaya</th>
                                            <th class="py-4 pl-3 pr-5 text-right font-semibold whitespace-nowrap">
                                                Biaya Cash
                                                <x-sketch.rule bold class="text-brand-darkred/60 -left-1 -right-1 -bottom-1.5 h-3" />
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($biayaSeragamList as $item)
                                            <tr class="relative">
                                                <td class="py-3 pl-5 pr-3 font-display font-bold text-brand-darkred">{{ $loop->iteration }}.</td>
                                                <td class="py-3 px-3">
                                                    <div class="flex items-center justify-between gap-2">
                                                        <span class="font-semibold">{{ $item['uraian'] }}</span>
                                                        <span class="text-[10px] font-medium text-brand-ink/60 bg-brand-softmist px-2 py-0.5 rounded-full whitespace-nowrap">{{ $item['jenis'] }}</span>
                                                    </div>
                                                </td>
                                                <td class="py-3 pl-3 pr-5 text-right font-semibold whitespace-nowrap">
                                                    {{ $rupiah($item['biaya']) }}
                                                    <x-sketch.rule :delay="$loop->index * 60" class="text-brand-darkred/25 -left-1 -right-1 -bottom-1.5 h-3" />
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <td colspan="2" class="py-4 pl-5 pr-3 text-xs font-semibold uppercase tracking-[0.2em] text-brand-ink/70">Jumlah</td>
                                            <td class="py-4 pl-3 pr-5 text-right font-display text-xl font-bold tracking-wide text-brand-darkred whitespace-nowrap">{{ $rupiah($calcBiayaSeragam) }}</td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>

                        <!-- Total keseluruhan -->
                        <div class="mt-8 flex flex-col sm:flex-row sm:items-center justify-between gap-2 rounded-card bg-linear-135 from-brand-darkred to-brand-deepred px-6 py-5 text-white shadow-softpill">
                            <span class="text-xs font-bold uppercase tracking-[0.25em] text-[#F5C2C7]">Total Pembiayaan</span>
                            <span class="font-display text-2xl md:text-3xl font-bold tracking-wide">{{ $rupiah($calcDspCash) }}</span>
                        </div>

                        <div class="mt-6 rounded-card bg-brand-softmist p-6 text-sm text-brand-ink/75 space-y-2">
                            <p class="flex items-center gap-2 font-semibold text-brand-darkred">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                                Catatan Resmi Pembiayaan &amp; Pengambilan Atribut:
                            </p>
                            <p class="leading-relaxed pl-6">
                                • Total biaya cash di atas mencakup seluruh perlengkapan seragam (7 stel/jenis pakaian, 2 jenis sepatu PDL & Cats, dan 4 atribut sekolah) serta operasional awal pendidikan bulan Juli.
                            </p>
                            <p class="leading-relaxed pl-6">
                                • Pengukuran ukuran seragam dan sepatu dilakukan langsung di kampus sekolah saat konfirmasi daftar ulang.
                            </p>
                        </div>
                    </section>

                    <!-- 3. FAQ PEMBIAYAAN (accordion seperti FAQ di beranda landing page) -->
                    <section id="faq-biaya">
                        <header class="flex items-start gap-4">
                            <span class="font-display text-4xl md:text-5xl font-bold leading-none text-brand-darkred">03</span>
                            <div>
                                <p class="eyebrow"><x-sketch.sparks>Informasi Lengkap</x-sketch.sparks></p>
                                <h2 class="mt-2 font-display text-2xl md:text-3xl font-bold uppercase tracking-wide leading-tight">Tanya Jawab Seputar Pembiayaan</h2>
                            </div>
                        </header>

                        <ul class="mt-8 space-y-3">
                            @foreach ($faqList as $faq)
                                @php $i = $loop->index; @endphp
                                <li>
                                    <div class="group rounded-card transition duration-300"
                                         :class="openFaq === {{ $i }}
                                            ? 'bg-white shadow-softpill ring-1 ring-brand-ink/5'
                                            : 'bg-brand-softmist hover:bg-brand-mist hover:-translate-y-0.5 has-focus-visible:bg-brand-mist'">
                                        <h3 class="font-sans">
                                            <button type="button" id="faq-{{ $i }}-button" @click="openFaq = openFaq === {{ $i }} ? null : {{ $i }}"
                                                    :aria-expanded="openFaq === {{ $i }}" aria-controls="faq-{{ $i }}-panel"
                                                    class="flex w-full items-center gap-4 p-5 md:p-6 text-left rounded-card focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-brand-darkred">
                                                <span class="w-7 shrink-0 font-display text-lg font-bold tracking-wide text-brand-darkred" aria-hidden="true">{{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }}</span>
                                                <span class="flex-1 text-base font-semibold leading-snug">{{ $faq['tanya'] }}</span>
                                                <span class="w-8 h-8 shrink-0 rounded-full flex items-center justify-center transition-colors duration-300"
                                                      :class="openFaq === {{ $i }} ? 'bg-brand-darkred text-white' : 'bg-white text-brand-ink group-hover:text-brand-darkred'">
                                                    {{-- Tanda + berputar jadi × saat terbuka --}}
                                                    <svg class="w-4 h-4 transition-transform duration-300" :class="openFaq === {{ $i }} ? 'rotate-45' : 'group-hover:rotate-90'"
                                                         viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" aria-hidden="true">
                                                        <path d="M12 5v14M5 12h14" />
                                                    </svg>
                                                </span>
                                            </button>
                                        </h3>
                                        {{-- Animasi buka-tutup pakai grid-rows 0fr -> 1fr --}}
                                        <div id="faq-{{ $i }}-panel" role="region" aria-labelledby="faq-{{ $i }}-button"
                                             :inert="openFaq !== {{ $i }}"
                                             class="grid transition-[grid-template-rows] duration-300 ease-out motion-reduce:transition-none"
                                             :class="openFaq === {{ $i }} ? 'grid-rows-[1fr]' : 'grid-rows-[0fr]'">
                                            <div class="overflow-hidden">
                                                <p class="pl-16 pr-5 pb-6 md:pl-17 md:pr-8 text-sm md:text-base leading-relaxed text-brand-ink/70">{{ $faq['jawab'] }}</p>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    </section>
                </div>
            </div>
        </div>
    </main>

    <!-- ========================================================
        Modal: Formulir Konsultasi Pembiayaan & PPDB
       ======================================================== -->
    <div x-show="modalKonsultasiOpen" x-transition.opacity
         class="fixed inset-0 z-50 bg-brand-ink/60 backdrop-blur-xs flex items-center justify-center p-4 overflow-y-auto"
         @click.self="modalKonsultasiOpen = false" style="display: none;">
        <div role="dialog" aria-modal="true" aria-labelledby="konsultasi-title"
             class="bg-white rounded-card max-w-xl w-full p-6 sm:p-8 shadow-softpill my-8 animate-fade-up relative">
            <button type="button" @click="modalKonsultasiOpen = false"
                    class="absolute top-5 right-5 w-9 h-9 rounded-full bg-brand-softmist text-brand-ink flex items-center justify-center transition-colors hover:text-brand-darkred"
                    aria-label="Tutup">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M6 18L18 6M6 6l12 12" /></svg>
            </button>

            <div x-show="konsultasiSuccess" class="text-center py-4 space-y-5">
                <div class="w-16 h-16 rounded-full bg-linear-to-r from-brand-signal to-brand-darkred text-white flex items-center justify-center mx-auto shadow-md shadow-brand-darkred/30">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" /></svg>
                </div>
                <h3 class="font-display text-2xl font-bold uppercase tracking-wide">Data Konsultasi Siap Dikirim!</h3>
                <p class="text-sm text-brand-ink/70 leading-relaxed max-w-md mx-auto">
                    Terima kasih, data calon siswa <strong class="text-brand-darkred" x-text="konsultasiForm.namaLengkap"></strong> telah siap. Silakan klik tombol di bawah untuk langsung terhubung dengan Panitia PPDB via WhatsApp.
                </p>

                <dl class="p-5 rounded-card bg-brand-softmist text-left text-sm space-y-2">
                    <div class="flex justify-between gap-3">
                        <dt class="text-brand-ink/60">Jurusan Pilihan:</dt>
                        <dd class="font-semibold text-right" x-text="konsultasiForm.jurusanMinat"></dd>
                    </div>
                    <div class="flex justify-between gap-3">
                        <dt class="text-brand-ink/60">Rencana Pembayaran:</dt>
                        <dd class="font-semibold text-right" x-text="konsultasiForm.rencanaPembayaran"></dd>
                    </div>
                    <div class="flex justify-between gap-3">
                        <dt class="text-brand-ink/60">No. WhatsApp:</dt>
                        <dd class="font-semibold text-right" x-text="konsultasiForm.noHpWa"></dd>
                    </div>
                </dl>

                <div class="flex flex-col sm:flex-row gap-3">
                    <button type="button" @click="handleSendToWhatsApp()" class="group btn-primary w-full">
                        Lanjutkan ke WhatsApp Panitia
                        <x-sketch.arrow class="w-6 h-3 transition-transform group-hover:translate-x-1" />
                    </button>
                    <button type="button" @click="modalKonsultasiOpen = false" class="btn-secondary w-full">Tutup</button>
                </div>
            </div>

            <form x-show="!konsultasiSuccess" @submit.prevent="handleKonsultasiSubmit()" class="space-y-5" novalidate>
                <div class="pr-10">
                    <p class="eyebrow">Konsultasi PPDB {{ $tahunAjaran }}</p>
                    <h3 id="konsultasi-title" class="mt-2 font-display text-2xl font-bold uppercase tracking-wide leading-tight">
                        Konsultasi Pembiayaan &amp; Jurusan
                    </h3>
                    <p class="mt-2 text-sm text-brand-ink/60">
                        Silakan isi data untuk menanyakan skema angsuran, jalur beasiswa, atau ketersediaan kuota.
                    </p>
                </div>

                <div>
                    <label for="konsultasi-nama" class="ppdb-label">Nama Lengkap Calon Siswa <span class="text-brand-signal">*</span></label>
                    <input id="konsultasi-nama" type="text" placeholder="Contoh: Muhammad Rizky Ramadhan" x-model="konsultasiForm.namaLengkap"
                           class="ppdb-input" :class="{ 'ppdb-input-error': formErrors.namaLengkap }" />
                    <p x-show="formErrors.namaLengkap" class="ppdb-error" x-text="formErrors.namaLengkap"></p>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label for="konsultasi-nisn" class="ppdb-label">NISN (Opsional)</label>
                        <input id="konsultasi-nisn" type="text" inputmode="numeric" maxlength="10" placeholder="10 Digit NISN SMP/MTs" x-model="konsultasiForm.nisn"
                               class="ppdb-input tabular-nums" :class="{ 'ppdb-input-error': formErrors.nisn }" />
                        <p x-show="formErrors.nisn" class="ppdb-error" x-text="formErrors.nisn"></p>
                    </div>
                    <div>
                        <label for="konsultasi-wa" class="ppdb-label">Nomor WhatsApp <span class="text-brand-signal">*</span></label>
                        <input id="konsultasi-wa" type="tel" placeholder="0812xxxxxxxx" x-model="konsultasiForm.noHpWa"
                               class="ppdb-input" :class="{ 'ppdb-input-error': formErrors.noHpWa }" />
                        <p x-show="formErrors.noHpWa" class="ppdb-error" x-text="formErrors.noHpWa"></p>
                    </div>
                </div>

                <div>
                    <label for="konsultasi-jurusan" class="ppdb-label">Pilihan Kompetensi Keahlian (Jurusan)</label>
                    <x-dropdown>
                        <select id="konsultasi-jurusan" x-model="konsultasiForm.jurusanMinat" class="ppdb-input appearance-none pr-11">
                            <option value="Rekayasa Perangkat Lunak (RPL)">Rekayasa Perangkat Lunak (RPL)</option>
                            <option value="Teknik Komputer dan Jaringan (TKJ)">Teknik Komputer dan Jaringan (TKJ)</option>
                            <option value="Desain Komunikasi Visual (DKV)">Desain Komunikasi Visual (DKV)</option>
                            <option value="Layanan Perbankan (LPB)">Layanan Perbankan (LPB)</option>
                            <option value="Teknik Otomasi Industri (TOI)">Teknik Otomasi Industri (TOI)</option>
                        </select>
                    </x-dropdown>
                </div>

                <div>
                    <label for="konsultasi-bayar" class="ppdb-label">Rencana Skema Pembayaran</label>
                    <x-dropdown>
                        <select id="konsultasi-bayar" x-model="konsultasiForm.rencanaPembayaran" class="ppdb-input appearance-none pr-11">
                            <option value="Cash Lunas (Rp {{ number_format($calcDspCash, 0, ',', '.') }})">Pembayaran Tunai Lunas (Rp {{ number_format($calcDspCash, 0, ',', '.') }},-)</option>
                            <option value="Bertahap / Cicilan 2-3 Tahap">Skema Angsuran Bertahap (2 - 3 Tahap)</option>
                            <option value="Konsultasi Jalur Beasiswa Prestasi">Konsultasi Jalur Beasiswa Prestasi</option>
                        </select>
                    </x-dropdown>
                </div>

                <div>
                    <label for="konsultasi-catatan" class="ppdb-label">Pertanyaan / Catatan Tambahan</label>
                    <textarea id="konsultasi-catatan" rows="2" placeholder="Tuliskan pertanyaan seputar biaya, seragam, atau jadwal pendaftaran..."
                              x-model="konsultasiForm.catatan" class="ppdb-input rounded-card"></textarea>
                </div>

                <div class="pt-2 flex flex-col-reverse sm:flex-row sm:justify-end gap-3">
                    <button type="button" @click="modalKonsultasiOpen = false" class="btn-secondary">Batal</button>
                    <button type="submit" class="group btn-primary">
                        Lanjutkan Konsultasi
                        <x-sketch.arrow class="w-6 h-3 transition-transform group-hover:translate-x-1" />
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
