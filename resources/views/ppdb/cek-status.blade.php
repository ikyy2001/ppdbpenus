@extends('layouts.app')

@section('title', 'Cek Status Pendaftar PPDB 2027/2028 - SMK Plus Pelita Nusantara')

@section('content')
@php
    $serverPendaftar = null;
    if (isset($pendaftar) && $pendaftar) {
        $serverPendaftar = [
            'id' => $pendaftar->id,
            'nisn' => $pendaftar->nisn ?? '',
            'noPendaftaran' => $pendaftar->nomor_registrasi ?? '',
            'namaLengkap' => $pendaftar->nama_lengkap ?? '',
            'namaPanggilan' => $pendaftar->nama_panggilan ?? '',
            'asalSekolah' => $pendaftar->asal_sekolah ?? '',
            'kelasPilihan' => $pendaftar->kelas_pilihan ?? '',
            'jurusan' => $pendaftar->jurusan ?? '',
            'jalurSeleksi' => $pendaftar->jalur_seleksi ?? '',
            'tanggalLahir' => ($pendaftar->tanggal_lahir_hari ?? '').' '.($pendaftar->tanggal_lahir_bulan ?? '').' '.($pendaftar->tanggal_lahir_tahun ?? ''),
            'jenisKelamin' => $pendaftar->jenis_kelamin === 'L' ? 'Laki-laki' : 'Perempuan',
            'tanggalDaftar' => $pendaftar->created_at ? $pendaftar->created_at->format('d M Y') : 'Terdaftar',
            'statusUtama' => strtoupper(str_replace('_', ' ', $pendaftar->status ?? 'menunggu_verifikasi')),
            'statusType' => $pendaftar->status === 'lulus_seleksi' ? 'success' : ($pendaftar->status === 'terverifikasi' ? 'info' : 'warning'),
            'keteranganStatus' => 'Data pendaftaran anda tercatat resmi di sistem PPDB SMK Plus Pelita Nusantara Bogor.',
            'catatanPanitia' => 'Mohon selalu memantau informasi pengumuman dan jadwal observasi di portal resmi.',
            'tahapan' => [
                ['id' => 1, 'judul' => 'Pengisian Formulir Online', 'status' => 'selesai', 'tanggal' => 'Terdaftar', 'keterangan' => 'Formulir pendaftaran lengkap'],
                ['id' => 2, 'judul' => 'Verifikasi Berkas Administrasi', 'status' => $pendaftar->status !== 'menunggu_verifikasi' ? 'selesai' : 'proses', 'tanggal' => 'Dalam proses', 'keterangan' => 'Pemeriksaan berkas kependudukan'],
                ['id' => 3, 'judul' => 'Observasi & Tes Minat Bakat', 'status' => $pendaftar->status === 'lulus_seleksi' ? 'selesai' : 'menunggu', 'tanggal' => 'Jadwal observasi', 'keterangan' => 'Uji logika & minat kejuruan'],
                ['id' => 4, 'judul' => 'Pengumuman Kelulusan Akhir', 'status' => $pendaftar->status === 'lulus_seleksi' ? 'selesai' : 'menunggu', 'tanggal' => 'Pengumuman', 'keterangan' => 'Penetapan kelulusan'],
                ['id' => 5, 'judul' => 'Daftar Ulang & Ukuran Seragam', 'status' => 'menunggu', 'tanggal' => 'Daftar ulang', 'keterangan' => 'Pelunasan & fitting seragam']
            ],
            'jadwalObservasi' => null,
            'rincianBiaya' => null,
        ];
    }
@endphp

<div x-data="{
    nisnInput: '{{ $query ?? '0081234567' }}',
    isLoading: false,
    searchResult: @json($serverPendaftar) || {
        nisn: '0081234567',
        noPendaftaran: 'PPDB-2027-10492',
        namaLengkap: 'Ahmad Fauzi Ramadhan',
        namaPanggilan: 'Fauzi',
        asalSekolah: 'SMP Negeri 1 Cibinong',
        kelasPilihan: 'Kelas 10',
        jurusan: 'Pengembangan Perangkat Lunak dan Gim (PPLG)',
        jalurSeleksi: 'Prestasi Akademik',
        tanggalLahir: '14 Mei 2011',
        jenisKelamin: 'Laki-laki',
        tanggalDaftar: '16 September 2026',
        statusUtama: 'LULUS SELEKSI AKHIR (SIAP DAFTAR ULANG)',
        statusType: 'success',
        keteranganStatus: 'Selamat! Anda dinyatakan LULUS SELEKSI AKHIR penerimaan peserta didik baru Program Keahlian Pengembangan Perangkat Lunak dan Gim (PPLG). Silakan melanjutkan ke proses daftar ulang.',
        catatanPanitia: 'Pertahankan prestasi Anda. Anda memperoleh Beasiswa Prestasi Akademik potongan DSP Rp 1.000.000. Mohon segera melakukan pengukuran seragam dan daftar ulang sebelum 30 September 2026.',
        tahapan: [
            { id: 1, judul: 'Pengisian Formulir Online', status: 'selesai', tanggal: '16 Sep 2026', keterangan: 'Formulir pendaftaran lengkap dan valid' },
            { id: 2, judul: 'Verifikasi Berkas Administrasi', status: 'selesai', tanggal: '18 Sep 2026', keterangan: 'Berkas rapor dan dokumen kependudukan MS' },
            { id: 3, judul: 'Observasi & Tes Minat Bakat', status: 'selesai', tanggal: '21 Sep 2026', keterangan: 'Nilai Uji Logika & Minat: 94 (Predikat A)' },
            { id: 4, judul: 'Pengumuman Kelulusan Akhir', status: 'selesai', tanggal: '23 Sep 2026', keterangan: 'DITERIMA di Jurusan PPLG (Jalur Prestasi)' },
            { id: 5, judul: 'Daftar Ulang & Ukuran Seragam', status: 'aktif', tanggal: 's.d 30 Sep 2026', keterangan: 'Silakan selesaikan pembayaran & fitting seragam' }
        ],
        rincianBiaya: {
            totalBiaya: 'Rp 3.500.000',
            potonganBeasiswa: 'Rp 1.000.000',
            totalBayar: 'Rp 2.500.000',
            statusPembayaran: 'Menunggu Pelunasan',
            batasPembayaran: '30 September 2026',
            rekeningPembayaran: 'Bank BNI: 0812-1086-8958 a.n SMK Plus Pelita Nusantara'
        },
        jadwalObservasi: null
    },
    errorMessage: '',
    hasSearched: true,

    databaseMock: [
        {
            nisn: '0081234567',
            noPendaftaran: 'PPDB-2027-10492',
            namaLengkap: 'Ahmad Fauzi Ramadhan',
            namaPanggilan: 'Fauzi',
            asalSekolah: 'SMP Negeri 1 Cibinong',
            kelasPilihan: 'Kelas 10',
            jurusan: 'Pengembangan Perangkat Lunak dan Gim (PPLG)',
            jalurSeleksi: 'Prestasi Akademik',
            tanggalLahir: '14 Mei 2011',
            jenisKelamin: 'Laki-laki',
            tanggalDaftar: '16 September 2026',
            statusUtama: 'LULUS SELEKSI AKHIR (SIAP DAFTAR ULANG)',
            statusType: 'success',
            keteranganStatus: 'Selamat! Anda dinyatakan LULUS SELEKSI AKHIR penerimaan peserta didik baru Program Keahlian Pengembangan Perangkat Lunak dan Gim (PPLG). Silakan melanjutkan ke proses daftar ulang.',
            catatanPanitia: 'Pertahankan prestasi Anda. Anda memperoleh Beasiswa Prestasi Akademik potongan DSP Rp 1.000.000. Mohon segera melakukan pengukuran seragam dan daftar ulang sebelum 30 September 2026.',
            tahapan: [
                { id: 1, judul: 'Pengisian Formulir Online', status: 'selesai', tanggal: '16 Sep 2026', keterangan: 'Formulir pendaftaran lengkap dan valid' },
                { id: 2, judul: 'Verifikasi Berkas Administrasi', status: 'selesai', tanggal: '18 Sep 2026', keterangan: 'Berkas rapor dan dokumen kependudukan MS' },
                { id: 3, judul: 'Observasi & Tes Minat Bakat', status: 'selesai', tanggal: '21 Sep 2026', keterangan: 'Nilai Uji Logika & Minat: 94 (Predikat A)' },
                { id: 4, judul: 'Pengumuman Kelulusan Akhir', status: 'selesai', tanggal: '23 Sep 2026', keterangan: 'DITERIMA di Jurusan PPLG (Jalur Prestasi)' },
                { id: 5, judul: 'Daftar Ulang & Ukuran Seragam', status: 'aktif', tanggal: 's.d 30 Sep 2026', keterangan: 'Silakan selesaikan pembayaran & fitting seragam' }
            ],
            rincianBiaya: {
                totalBiaya: 'Rp 3.500.000',
                potonganBeasiswa: 'Rp 1.000.000',
                totalBayar: 'Rp 2.500.000',
                statusPembayaran: 'Menunggu Pelunasan',
                batasPembayaran: '30 September 2026',
                rekeningPembayaran: 'Bank BNI: 0812-1086-8958 a.n SMK Plus Pelita Nusantara'
            },
            jadwalObservasi: null
        },
        {
            nisn: '0079876543',
            noPendaftaran: 'PPDB-2027-20831',
            namaLengkap: 'Siti Nurhaliza Putri',
            namaPanggilan: 'Siti',
            asalSekolah: 'SMP Negeri 2 Cibinong',
            kelasPilihan: 'Kelas 10',
            jurusan: 'Desain Komunikasi Visual (DKV)',
            jalurSeleksi: 'Reguler',
            tanggalLahir: '22 Agustus 2011',
            jenisKelamin: 'Perempuan',
            tanggalDaftar: '19 September 2026',
            statusUtama: 'MENUNGGU TES OBSERVASI & WAWANCARA',
            statusType: 'warning',
            keteranganStatus: 'Berkas administrasi Anda telah lengkap dan terverifikasi. Tahapan selanjutnya adalah Tes Observasi Minat Bakat & Wawancara Kejuruan DKV.',
            catatanPanitia: 'Wajib hadir 30 menit sebelum jadwal observasi dimulai. Mohon membawa kartu peserta PPDB dan diperbolehkan membawa contoh karya gambar/desain yang pernah dibuat.',
            tahapan: [
                { id: 1, judul: 'Pengisian Formulir Online', status: 'selesai', tanggal: '19 Sep 2026', keterangan: 'Formulir berhasil terdaftar di sistem' },
                { id: 2, judul: 'Verifikasi Berkas Administrasi', status: 'selesai', tanggal: '21 Sep 2026', keterangan: 'Semua persyaratan dokumen terpenuhi' },
                { id: 3, judul: 'Observasi & Tes Minat Bakat', status: 'aktif', tanggal: '28 Sep 2026', keterangan: 'Dijadwalkan: Sabtu, 28 Sep 2026 pukul 09.00 WIB' },
                { id: 4, judul: 'Pengumuman Kelulusan Akhir', status: 'menunggu', tanggal: '02 Okt 2026', keterangan: 'Akan diumumkan setelah tes observasi' },
                { id: 5, judul: 'Daftar Ulang & Ukuran Seragam', status: 'menunggu', tanggal: 'Okt 2026', keterangan: 'Setelah pengumuman kelulusan akhir' }
            ],
            jadwalObservasi: {
                hariTanggal: 'Sabtu, 28 September 2026',
                waktu: '09.00 – 11.30 WIB (Sesi Pagi)',
                lokasi: 'Lab Komputer Grafis Multimedia 1, Kampus Penus',
                pakaian: 'Seragam SMP asal atau Kemeja Putih Celana/Rok Hitam',
                perlengkapan: 'Kartu Peserta, Pensil 2B, Penghapus & Portofolio Karya Gambar'
            },
            rincianBiaya: null
        },
        {
            nisn: '0091122334',
            noPendaftaran: 'PPDB-2027-31940',
            namaLengkap: 'Budi Pratama Wijaya',
            namaPanggilan: 'Budi',
            asalSekolah: 'MTs Negeri 1 Bogor',
            kelasPilihan: 'Kelas 10',
            jurusan: 'Teknik Jaringan Komputer dan Telekomunikasi (TJKT)',
            jalurSeleksi: 'Reguler',
            tanggalLahir: '05 Maret 2011',
            jenisKelamin: 'Laki-laki',
            tanggalDaftar: '22 September 2026',
            statusUtama: 'BERKAS TERVERIFIKASI ADMINISTRASI',
            statusType: 'info',
            keteranganStatus: 'Data formulir dan berkas digital pendaftaran Anda telah berhasil diverifikasi oleh panitia PPDB. Jadwal pembagian sesi wawancara sedang disusun.',
            catatanPanitia: 'Pantau laman pengumuman atau cek kembali halaman ini dalam 1-2 hari ke depan untuk melihat nomor sesi dan ruangan observasi kejuruan TJKT.',
            tahapan: [
                { id: 1, judul: 'Pengisian Formulir Online', status: 'selesai', tanggal: '22 Sep 2026', keterangan: 'Formulir pendaftaran berhasil diterima' },
                { id: 2, judul: 'Verifikasi Berkas Administrasi', status: 'selesai', tanggal: '23 Sep 2026', keterangan: 'Verifikasi berkas administrasi lolos' },
                { id: 3, judul: 'Observasi & Tes Minat Bakat', status: 'proses', tanggal: 'Estimasi 28 Sep 2026', keterangan: 'Menunggu penentuan jadwal sesi resmi' },
                { id: 4, judul: 'Pengumuman Kelulusan Akhir', status: 'menunggu', tanggal: 'Okt 2026', keterangan: 'Belum dibuka' },
                { id: 5, judul: 'Daftar Ulang & Ukuran Seragam', status: 'menunggu', tanggal: 'Okt 2026', keterangan: 'Belum dibuka' }
            ],
            jadwalObservasi: null,
            rincianBiaya: null
        },
        {
            nisn: '0065432198',
            noPendaftaran: 'PPDB-2027-44012',
            namaLengkap: 'Dewi Lestari',
            namaPanggilan: 'Dewi',
            asalSekolah: 'SMP IT Ummul Quro Bogor',
            kelasPilihan: 'Kelas 10',
            jurusan: 'Animasi',
            jalurSeleksi: 'Prestasi Non-Akademik',
            tanggalLahir: '18 Juli 2011',
            jenisKelamin: 'Perempuan',
            tanggalDaftar: '14 September 2026',
            statusUtama: 'PERLU PERBAIKAN BERKAS DOKUMEN',
            statusType: 'danger',
            keteranganStatus: 'Terdapat berkas persyaratan yang belum memenuhi ketentuan verifikasi (lampiran rapor semester 3 & 4 tidak terbaca/buram). Harap segera melengkapi.',
            catatanPanitia: 'Mohon unggah ulang scan rapor semester 3 dan 4 yang jelas, atau serahkan salinan fotokopi legalisir langsung ke meja sekretariat PPDB kampus paling lambat 26 September 2026.',
            tahapan: [
                { id: 1, judul: 'Pengisian Formulir Online', status: 'selesai', tanggal: '14 Sep 2026', keterangan: 'Data pendaftaran tersimpan' },
                { id: 2, judul: 'Verifikasi Berkas Administrasi', status: 'perbaikan', tanggal: '20 Sep 2026', keterangan: 'Catatan: Scan rapor buram, harap perbaiki' },
                { id: 3, judul: 'Observasi & Tes Minat Bakat', status: 'tertunda', tanggal: 'Tertunda', keterangan: 'Menunggu penyelesaian berkas rapor' },
                { id: 4, judul: 'Pengumuman Kelulusan Akhir', status: 'menunggu', tanggal: 'Okt 2026', keterangan: 'Tertunda' },
                { id: 5, judul: 'Daftar Ulang & Ukuran Seragam', status: 'menunggu', tanggal: 'Okt 2026', keterangan: 'Tertunda' }
            ],
            jadwalObservasi: null,
            rincianBiaya: null
        }
    ],

    async handlePerformSearch(query) {
        const q = query !== undefined ? query : this.nisnInput;
        if (!q || !q.trim()) {
            this.errorMessage = 'Silakan masukkan nomor NISN Anda (10 digit).';
            return;
        }

        this.isLoading = true;
        this.errorMessage = '';
        this.hasSearched = true;

        await new Promise(r => setTimeout(r, 400));

        const cleanQuery = q.trim().replace(/\D/g, '');
        const cleanRaw = q.trim().toUpperCase();

        const match = this.databaseMock.find(p =>
            p.nisn === cleanQuery ||
            p.noPendaftaran.toUpperCase() === cleanRaw ||
            p.noPendaftaran.replace(/-/g, '').toUpperCase() === cleanRaw.replace(/-/g, '')
        );

        if (match) {
            this.searchResult = match;
            this.errorMessage = '';
        } else if (cleanQuery.length === 10) {
            this.searchResult = {
                nisn: cleanQuery,
                noPendaftaran: `PPDB-2027-${cleanQuery.slice(-5)}`,
                namaLengkap: 'Calon Siswa Terverifikasi',
                namaPanggilan: 'Siswa',
                asalSekolah: 'SMP Negeri Sekitar Bogor',
                kelasPilihan: 'Kelas 10',
                jurusan: 'Pengembangan Perangkat Lunak dan Gim (PPLG)',
                jalurSeleksi: 'Reguler',
                tanggalLahir: '10 Oktober 2011',
                jenisKelamin: 'Laki-laki',
                tanggalDaftar: '20 September 2026',
                statusUtama: 'BERKAS TERVERIFIKASI ADMINISTRASI',
                statusType: 'info',
                keteranganStatus: 'Data pendaftaran dengan NISN ' + cleanQuery + ' telah tercatat di basis data PPDB SMK Plus Pelita Nusantara dan dalam proses verifikasi akhir panitia.',
                catatanPanitia: 'Pastikan nomor handphone dan WhatsApp Anda aktif untuk menerima notifikasi jadwal seleksi observasi berikutnya.',
                tahapan: [
                    { id: 1, judul: 'Pengisian Formulir Online', status: 'selesai', tanggal: '20 Sep 2026', keterangan: 'Formulir tercatat di sistem' },
                    { id: 2, judul: 'Verifikasi Berkas Administrasi', status: 'selesai', tanggal: '22 Sep 2026', keterangan: 'Berkas dinyatakan memenuhi syarat' },
                    { id: 3, judul: 'Observasi & Tes Minat Bakat', status: 'aktif', tanggal: '28 Sep 2026', keterangan: 'Jadwal sedang dipersiapkan' },
                    { id: 4, judul: 'Pengumuman Kelulusan Akhir', status: 'menunggu', tanggal: 'Okt 2026', keterangan: 'Menunggu pelaksanaan observasi' },
                    { id: 5, judul: 'Daftar Ulang & Ukuran Seragam', status: 'menunggu', tanggal: 'Okt 2026', keterangan: 'Setelah pengumuman kelulusan' }
                ],
                jadwalObservasi: null,
                rincianBiaya: null
            };
            this.errorMessage = '';
        } else {
            this.searchResult = null;
            this.errorMessage = `Data pendaftaran dengan NISN / Nomor Registrasi '${q}' tidak ditemukan. Mohon pastikan nomor yang Anda masukkan sudah sesuai 10 digit.`;
        }

        this.isLoading = false;
    },

    handleSampleClick(nisn) {
        this.nisnInput = nisn;
        this.handlePerformSearch(nisn);
    },

    handleResetSearch() {
        this.nisnInput = '';
        this.searchResult = null;
        this.errorMessage = '';
        this.hasSearched = false;
    }
}" class="min-h-screen bg-[#F5F4F2] flex flex-col font-sans text-brand-ink antialiased">

    <!-- ========================================================
        2. Editorial Hero Section
       ======================================================== -->
    <div class="max-w-canvas mx-auto px-6 md:px-12 pt-2 sm:pt-4 no-print">
        <div
            style="background: linear-gradient(135deg, #7A1018 0%, #5C0B12 100%);"
            class="bg-[#7A1018] rounded-[32px] p-8 sm:p-12 lg:p-14 text-white relative overflow-hidden flex flex-col md:flex-row items-start md:items-center justify-between min-h-[220px] shadow-md border border-white/10"
        >
            <!-- Background Ghost Typography -->
            <div class="absolute right-4 top-1/2 -translate-y-1/2 select-none pointer-events-none opacity-10">
                <x-ghost-heading text="VERIFICATION" variant="light" />
            </div>

            <!-- Orbital Line Vector Curve in Hero -->
            <x-orbital-line variant="s-curve" color="#D04A43" opacity="0.35" class="absolute inset-0 w-full h-full" />

            <!-- Left Content -->
            <div class="z-10 max-w-2xl">
                <span class="font-editorial-eyebrow text-[#E5B5B8] tracking-eyebrow uppercase block mb-2.5 text-xs font-bold">
                    PORTAL PELACAKAN SELEKSI • SMK PLUS PELITA NUSANTARA
                </span>
                <h1 class="text-3xl sm:text-4xl lg:text-5xl font-bold uppercase tracking-wide !text-white leading-tight drop-shadow-sm font-display" style="color: #ffffff !important;">
                    Cek Status Pendaftar PPDB
                </h1>
                <p class="mt-3 font-editorial-body text-[#E8E8E8] text-sm sm:text-base leading-relaxed max-w-xl">
                    Pantau hasil verifikasi administrasi, jadwal tes observasi minat bakat, penetapan kelulusan, dan status daftar ulang cukup dengan memasukkan 10 digit NISN Anda.
                </p>
            </div>

            <!-- Right Circular Graphic -->
            <div class="hidden md:flex z-10 shrink-0 ml-6 items-center justify-center">
                <div class="w-44 h-44 sm:w-48 sm:h-48 lg:w-56 lg:h-56 rounded-full border-4 border-white/20 bg-white/10 backdrop-blur-xs flex flex-col items-center justify-center p-4 shadow-softpill text-center group">
                    <div class="w-16 h-16 rounded-full bg-white/20 flex items-center justify-center mb-2 group-hover:scale-110 transition-transform">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                    </div>
                    <span class="text-xs font-bold uppercase tracking-wider text-[#E5B5B8]">
                        VERIFIKASI RESMI
                    </span>
                    <span class="text-sm font-extrabold text-white mt-0.5">
                        Data PPDB 2027
                    </span>
                </div>
            </div>
        </div>
    </div>

    <!-- ========================================================
        3. Main Canvas Layout: Form Input & Status Card
       ======================================================== -->
    <main class="max-w-canvas mx-auto px-6 md:px-12 py-10 md:py-14 flex-1">
        <div class="flex flex-col lg:flex-row gap-8 lg:gap-10 items-start">
            
            <!-- LEFT COLUMN: Form Input Pencarian & Tips NISN -->
            <div class="w-full lg:w-[420px] shrink-0 space-y-6 no-print">
                <!-- Card Pencarian NISN -->
                <div class="bg-white rounded-card p-6 sm:p-8 shadow-softpill border border-brand-ink/10 space-y-5">
                    <div class="flex items-center gap-3 pb-3 border-b border-brand-ink/10">
                        <div class="w-9 h-9 rounded-full bg-brand-darkred text-white flex items-center justify-center font-bold text-xs shrink-0">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                        </div>
                        <div>
                            <span class="font-editorial-eyebrow text-brand-darkred tracking-eyebrow block text-xs">
                                FORM PENCARIAN
                            </span>
                            <h3 class="font-editorial-h3 text-brand-ink font-bold text-lg mt-0.5">
                                Masukkan NISN Pendaftar
                            </h3>
                        </div>
                    </div>

                    <form @submit.prevent="handlePerformSearch()" class="space-y-4">
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-brand-ink/70 mb-2">
                                Nomor Induk Siswa Nasional (NISN) <span class="text-brand-signal">*</span>
                            </label>
                            <div class="relative">
                                <input
                                    type="text"
                                    maxlength="10"
                                    placeholder="Masukkan 10 digit NISN (cth: 0081234567)"
                                    x-model="nisnInput"
                                    @input="nisnInput = nisnInput.replace(/\D/g, ''); if(errorMessage) errorMessage = '';"
                                    class="w-full rounded-full border border-brand-ink/20 pl-5 pr-11 py-3 text-sm text-brand-ink bg-[#F9F8F6] focus:bg-white focus:outline-none focus:border-brand-darkred font-mono transition-all"
                                />
                                <button
                                    type="button"
                                    x-show="nisnInput"
                                    @click="nisnInput = ''"
                                    class="absolute right-4 top-1/2 -translate-y-1/2 text-brand-ink/40 hover:text-brand-ink"
                                    style="display: none;"
                                >
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                </button>
                            </div>
                            <p class="text-[11px] text-brand-ink/55 mt-1.5 leading-normal">
                                NISN dapat ditemukan pada Kartu Pelajar SMP, Ijazah SD, atau Surat Keterangan Kepala Sekolah.
                            </p>
                            <div x-show="errorMessage" class="mt-2 text-xs font-semibold text-brand-signal flex items-center gap-1.5 animate-fade-in" style="display: none;">
                                <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                <span x-text="errorMessage"></span>
                            </div>
                        </div>

                        <button
                            type="submit"
                            :disabled="isLoading"
                            class="w-full py-3 rounded-full bg-linear-to-r from-brand-signal to-brand-darkred hover:from-brand-warmred hover:to-brand-deepred text-white text-sm font-semibold shadow-md shadow-brand-darkred/25 hover:shadow-lg hover:shadow-brand-darkred/30 flex items-center justify-center gap-2 transition-all cursor-pointer"
                        >
                            <template x-if="isLoading">
                                <span class="flex items-center gap-2">
                                    <svg class="w-4 h-4 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path></svg>
                                    <span>Memeriksa Data...</span>
                                </span>
                            </template>
                            <template x-if="!isLoading">
                                <span class="flex items-center gap-2">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                                    <span>Cari Status Seleksi</span>
                                </span>
                            </template>
                        </button>
                    </form>

                    <!-- 1-Click Sample Chips -->
                    <div class="pt-4 border-t border-brand-ink/10 space-y-2">
                        <div class="flex items-center gap-1.5 text-xs text-brand-ink/70 font-semibold">
                            <svg class="w-3.5 h-3.5 text-brand-darkred" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/></svg>
                            <span>Coba Contoh NISN Pendaftar:</span>
                        </div>
                        <div class="flex flex-col gap-1.5">
                            <button
                                type="button"
                                @click="handleSampleClick('0081234567')"
                                class="text-left p-2.5 rounded-xl border border-brand-ink/10 bg-[#F9F8F6] hover:bg-brand-softmist/60 hover:border-brand-darkred/30 transition-all cursor-pointer flex items-center justify-between text-xs"
                            >
                                <div>
                                    <span class="font-mono font-bold text-brand-darkred">0081234567</span>
                                    <span class="text-brand-ink font-medium">• Ahmad Fauzi Ramadhan</span>
                                </div>
                                <span class="text-[10px] font-bold px-2 py-0.5 rounded-full bg-white border border-brand-ink/10">
                                    Lulus Seleksi
                                </span>
                            </button>
                            <button
                                type="button"
                                @click="handleSampleClick('0079876543')"
                                class="text-left p-2.5 rounded-xl border border-brand-ink/10 bg-[#F9F8F6] hover:bg-brand-softmist/60 hover:border-brand-darkred/30 transition-all cursor-pointer flex items-center justify-between text-xs"
                            >
                                <div>
                                    <span class="font-mono font-bold text-brand-darkred">0079876543</span>
                                    <span class="text-brand-ink font-medium">• Siti Nurhaliza Putri</span>
                                </div>
                                <span class="text-[10px] font-bold px-2 py-0.5 rounded-full bg-white border border-brand-ink/10">
                                    Jadwal Observasi
                                </span>
                            </button>
                            <button
                                type="button"
                                @click="handleSampleClick('0091122334')"
                                class="text-left p-2.5 rounded-xl border border-brand-ink/10 bg-[#F9F8F6] hover:bg-brand-softmist/60 hover:border-brand-darkred/30 transition-all cursor-pointer flex items-center justify-between text-xs"
                            >
                                <div>
                                    <span class="font-mono font-bold text-brand-darkred">0091122334</span>
                                    <span class="text-brand-ink font-medium">• Budi Pratama Wijaya</span>
                                </div>
                                <span class="text-[10px] font-bold px-2 py-0.5 rounded-full bg-white border border-brand-ink/10">
                                    Berkas Terverifikasi
                                </span>
                            </button>
                            <button
                                type="button"
                                @click="handleSampleClick('0065432198')"
                                class="text-left p-2.5 rounded-xl border border-brand-ink/10 bg-[#F9F8F6] hover:bg-brand-softmist/60 hover:border-brand-darkred/30 transition-all cursor-pointer flex items-center justify-between text-xs"
                            >
                                <div>
                                    <span class="font-mono font-bold text-brand-darkred">0065432198</span>
                                    <span class="text-brand-ink font-medium">• Dewi Lestari</span>
                                </div>
                                <span class="text-[10px] font-bold px-2 py-0.5 rounded-full bg-white border border-brand-ink/10">
                                    Perlu Perbaikan
                                </span>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Card Bantuan: Panduan NISN -->
                <div class="bg-white rounded-card p-6 shadow-softpill border border-brand-ink/10 space-y-3">
                    <span class="font-editorial-eyebrow text-brand-darkred tracking-eyebrow block">
                        PANDUAN NISN
                    </span>
                    <h4 class="font-sans font-bold text-sm text-brand-ink">
                        Lupa atau Belum Mengetahui NISN?
                    </h4>
                    <p class="text-xs text-brand-ink/75 leading-relaxed">
                        Anda dapat mengecek keabsahan dan keaktifan NISN secara mandiri melalui laman resmi Pusdatin Kemendikbudristek.
                    </p>
                    <div class="pt-1">
                        <a
                            href="https://nisn.data.kemdikbud.go.id"
                            target="_blank"
                            rel="noreferrer"
                            class="inline-flex items-center gap-1.5 text-xs font-bold text-brand-darkred hover:underline"
                        >
                            <span>Buka Portal NISN Kemendikbud</span>
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                        </a>
                    </div>
                </div>

                <!-- Card Hotline PPDB -->
                <div class="p-5 rounded-card bg-brand-darkred/[0.04] border border-brand-darkred/15 space-y-2.5 shadow-softpill">
                    <div class="flex items-center gap-2 text-brand-darkred">
                        <svg class="w-4 h-4 text-brand-darkred" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                        <span class="font-editorial-eyebrow uppercase tracking-eyebrow text-xs font-bold">
                            BANTUAN VERIFIKASI
                        </span>
                    </div>
                    <p class="text-xs text-brand-ink/75 leading-relaxed">
                        Jika data Anda belum sesuai atau butuh bantuan verifikasi berkas, hubungi sekretariat PPDB melalui WhatsApp:
                    </p>
                    <a
                        href="https://wa.me/6281210868958?text=Halo%20Panitia%20PPDB,%20saya%20ingin%20menanyakan%20status%20pendaftaran%20NISN"
                        target="_blank"
                        rel="noreferrer"
                        class="w-full py-2.5 rounded-full bg-[#B72A32] hover:bg-[#7A1018] text-white text-xs font-bold inline-flex items-center justify-center gap-2"
                    >
                        <span>Hubungi Panitia Seleksi</span>
                    </a>
                </div>
            </div>

            <!-- RIGHT COLUMN: Hasil Pelacakan Status -->
            <div class="flex-1 min-w-0 space-y-6">
                
                <!-- Initial State -->
                <div x-show="!searchResult && !hasSearched" class="bg-white rounded-card p-10 text-center border border-brand-ink/10 shadow-softpill space-y-4">
                    <div class="w-16 h-16 rounded-full bg-brand-softmist flex items-center justify-center mx-auto text-brand-darkred">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    </div>
                    <h3 class="font-editorial-h3 text-xl font-bold text-brand-ink">
                        Masukkan NISN untuk Melacak Status
                    </h3>
                    <p class="text-xs sm:text-sm text-brand-ink/70 max-w-md mx-auto leading-relaxed">
                        Gunakan kolom pencarian di sebelah kiri atau klik salah satu contoh NISN untuk melihat data hasil seleksi dan tahapan pendaftaran.
                    </p>
                </div>

                <!-- Not Found State -->
                <div x-show="!searchResult && hasSearched && errorMessage" class="bg-white rounded-card p-8 sm:p-10 text-center border border-brand-signal/30 shadow-softpill space-y-4 animate-scale-in" style="display: none;">
                    <div class="w-16 h-16 rounded-full bg-brand-signal/10 text-brand-signal flex items-center justify-center mx-auto">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <h3 class="font-editorial-h3 text-xl font-bold text-brand-ink">
                        Data Tidak Ditemukan
                    </h3>
                    <p class="text-xs sm:text-sm text-brand-ink/75 max-w-md mx-auto leading-relaxed" x-text="errorMessage"></p>
                    <div class="pt-2 flex justify-center gap-3">
                        <button
                            type="button"
                            @click="handleResetSearch()"
                            class="py-2 px-5 rounded-full border border-brand-ink/20 text-brand-ink text-xs font-bold inline-flex items-center gap-1.5"
                        >
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                            <span>Cari Ulang</span>
                        </button>
                        <a
                            href="https://wa.me/6281210868958"
                            target="_blank"
                            rel="noreferrer"
                            class="py-2 px-5 rounded-full bg-[#B72A32] hover:bg-[#7A1018] text-white text-xs font-bold inline-flex items-center gap-1.5"
                        >
                            <span>Tanya Panitia via WA</span>
                        </a>
                    </div>
                </div>

                <!-- SUCCESS / FOUND STATE -->
                <div x-show="searchResult" class="space-y-6 animate-fade-in" style="display: none;">
                    
                    <!-- 1. Status Header Card -->
                    <div
                        :class="searchResult?.statusType === 'success' ? 'border-[#107c41]/40' : (searchResult?.statusType === 'warning' ? 'border-[#d83b01]/40' : (searchResult?.statusType === 'danger' ? 'border-brand-signal/40' : 'border-brand-darkred/30'))"
                        class="bg-white rounded-card p-6 sm:p-8 shadow-softpill border-2 space-y-4 relative overflow-hidden"
                    >
                        <div class="flex flex-col sm:flex-row sm:items-start justify-between gap-4">
                            <div class="space-y-1">
                                <div class="flex items-center gap-2 flex-wrap">
                                    <span
                                        :class="searchResult?.statusType === 'success' ? 'bg-[#107c41]/10 text-[#107c41] border-[#107c41]/30' : (searchResult?.statusType === 'warning' ? 'bg-[#d83b01]/10 text-[#d83b01] border-[#d83b01]/30' : (searchResult?.statusType === 'danger' ? 'bg-brand-signal/10 text-brand-signal border-brand-signal/30' : 'bg-brand-darkred/10 text-brand-darkred border-brand-darkred/30'))"
                                        class="text-xs font-extrabold px-3 py-1 rounded-full border uppercase tracking-wider flex items-center gap-1.5"
                                    >
                                        <span
                                            :class="searchResult?.statusType === 'success' ? 'bg-[#107c41]' : (searchResult?.statusType === 'warning' ? 'bg-[#d83b01]' : (searchResult?.statusType === 'danger' ? 'bg-brand-signal' : 'bg-brand-darkred'))"
                                            class="w-2 h-2 rounded-full"
                                        ></span>
                                        <span x-text="searchResult?.statusUtama"></span>
                                    </span>
                                </div>
                                <h2 class="text-2xl sm:text-3xl font-bold uppercase tracking-wide text-brand-ink font-display mt-2" x-text="searchResult?.namaLengkap"></h2>
                                <p class="text-xs font-mono text-brand-ink/60">
                                    NISN: <strong class="text-brand-darkred" x-text="searchResult?.nisn"></strong> • No. Reg: <strong class="text-brand-darkred" x-text="searchResult?.noPendaftaran"></strong>
                                </p>
                            </div>

                            <div class="shrink-0 flex items-center flex-wrap gap-2 no-print">
                                <template x-if="searchResult?.id">
                                    <a
                                        :href="'/ppdb/cetak-kartu/' + searchResult.id"
                                        target="_blank"
                                        class="py-2 px-4 rounded-full bg-blue-700 hover:bg-blue-800 text-white text-xs font-bold inline-flex items-center gap-1.5 shadow-sm transition-all cursor-pointer"
                                    >
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                        <span>Cetak Kartu Tanda Peserta</span>
                                    </a>
                                </template>
                                <button
                                    type="button"
                                    onclick="window.print()"
                                    class="py-2 px-4 rounded-full border border-brand-ink/20 text-brand-ink text-xs font-bold inline-flex items-center gap-1.5 hover:bg-brand-softmist/50 cursor-pointer"
                                >
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                                    <span>Cetak Bukti</span>
                                </button>
                            </div>
                        </div>

                        <!-- Keterangan Status Text -->
                        <p class="text-xs sm:text-sm text-brand-ink/85 leading-relaxed pt-2 border-t border-brand-ink/10 font-medium" x-text="searchResult?.keteranganStatus"></p>

                        <!-- Catatan Panitia Box -->
                        <template x-if="searchResult?.catatanPanitia">
                            <div class="p-4 rounded-2xl bg-[#F9F8F6] border border-brand-ink/10 text-xs text-brand-ink/80 space-y-1">
                                <span class="font-editorial-eyebrow text-brand-darkred text-[10px] uppercase font-bold block">
                                    CATATAN RESMI PANITIA SELEKSI
                                </span>
                                <p class="leading-relaxed" x-text="searchResult.catatanPanitia"></p>
                            </div>
                        </template>
                    </div>

                    <!-- 2. Biodata Card -->
                    <div class="bg-white rounded-card p-6 sm:p-8 shadow-softpill border border-brand-ink/10 space-y-5">
                        <div class="flex items-center gap-3 pb-3 border-b border-brand-ink/10">
                            <div class="w-9 h-9 rounded-full bg-brand-darkred text-white flex items-center justify-center font-bold text-xs shrink-0">
                                ID
                            </div>
                            <div>
                                <span class="font-editorial-eyebrow text-brand-darkred tracking-eyebrow block text-xs">
                                    RINGKASAN DATA
                                </span>
                                <h3 class="font-editorial-h3 text-brand-ink font-bold text-lg mt-0.5">
                                    Biodata Pendaftaran Siswa
                                </h3>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-xs">
                            <div class="p-3.5 rounded-2xl bg-[#F9F8F6] border border-brand-ink/10 space-y-1">
                                <span class="text-[10px] uppercase font-bold text-brand-ink/50 block">Kompetensi Keahlian (Jurusan)</span>
                                <span class="text-sm font-bold text-brand-darkred block leading-snug" x-text="searchResult?.jurusan"></span>
                            </div>

                            <div class="p-3.5 rounded-2xl bg-[#F9F8F6] border border-brand-ink/10 space-y-1">
                                <span class="text-[10px] uppercase font-bold text-brand-ink/50 block">Jalur Seleksi & Jenjang</span>
                                <span class="text-sm font-bold text-brand-ink block" x-text="`${searchResult?.jalurSeleksi || '-'} • ${searchResult?.kelasPilihan || '-'}`"></span>
                            </div>

                            <div class="p-3.5 rounded-2xl bg-[#F9F8F6] border border-brand-ink/10 space-y-1">
                                <span class="text-[10px] uppercase font-bold text-brand-ink/50 block">Asal Sekolah SMP/MTs</span>
                                <span class="text-sm font-bold text-brand-ink block" x-text="searchResult?.asalSekolah"></span>
                            </div>

                            <div class="p-3.5 rounded-2xl bg-[#F9F8F6] border border-brand-ink/10 space-y-1">
                                <span class="text-[10px] uppercase font-bold text-brand-ink/50 block">Tanggal Registrasi Form</span>
                                <span class="text-sm font-bold text-brand-ink block" x-text="searchResult?.tanggalDaftar"></span>
                            </div>
                        </div>
                    </div>

                    <!-- 3. Stepper Timeline Tahapan Seleksi -->
                    <div class="bg-white rounded-card p-6 sm:p-8 shadow-softpill border border-brand-ink/10 space-y-5">
                        <div class="flex items-center gap-3 pb-3 border-b border-brand-ink/10">
                            <div class="w-9 h-9 rounded-full bg-brand-darkred text-white flex items-center justify-center font-bold text-xs shrink-0">
                                STEP
                            </div>
                            <div>
                                <span class="font-editorial-eyebrow text-brand-darkred tracking-eyebrow block text-xs">
                                    PROGRES PENDAFTARAN
                                </span>
                                <h3 class="font-editorial-h3 text-brand-ink font-bold text-lg mt-0.5">
                                    Tahapan Pelaksanaan PPDB
                                </h3>
                            </div>
                        </div>

                        <div class="relative pl-6 space-y-6 before:content-[''] before:absolute before:left-2.5 before:top-2 before:bottom-2 before:w-0.5 before:bg-brand-ink/15">
                            <template x-for="t in searchResult?.tahapan || []" :key="t.id">
                                <div class="relative group">
                                    <div
                                        :class="t.status === 'selesai' ? 'bg-[#107c41] text-white' : (t.status === 'aktif' ? 'bg-brand-darkred text-white ring-4 ring-brand-darkred/20' : (t.status === 'perbaikan' ? 'bg-brand-signal text-white' : 'bg-brand-softmist text-brand-ink/40'))"
                                        class="absolute -left-6 top-1 w-5 h-5 rounded-full flex items-center justify-center text-[10px] font-bold"
                                    >
                                        <template x-if="t.status === 'selesai'">
                                            <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                                        </template>
                                        <template x-if="t.status !== 'selesai'">
                                            <span x-text="t.id"></span>
                                        </template>
                                    </div>

                                    <div class="space-y-0.5">
                                        <div class="flex items-center justify-between gap-2">
                                            <h4
                                                :class="t.status === 'aktif' ? 'text-brand-darkred' : (t.status === 'selesai' ? 'text-brand-ink' : 'text-brand-ink/60')"
                                                class="text-sm font-bold"
                                                x-text="t.judul"
                                            ></h4>
                                            <span class="text-[11px] font-mono text-brand-ink/50" x-text="t.tanggal"></span>
                                        </div>
                                        <p class="text-xs text-brand-ink/70 leading-relaxed" x-text="t.keterangan"></p>
                                    </div>
                                </div>
                            </template>
                        </div>
                    </div>

                    <!-- 4. Detail Khusus (Jadwal Observasi Jika Ada) -->
                    <template x-if="searchResult?.jadwalObservasi">
                        <div class="bg-white rounded-[28px] p-6 sm:p-8 shadow-sm border-2 border-brand-darkred/30 space-y-4">
                            <div class="flex items-center gap-2 text-brand-darkred">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                <span class="font-editorial-eyebrow uppercase tracking-eyebrow text-xs font-bold">
                                    JADWAL TES OBSERVASI MINAT BAKAT
                                </span>
                            </div>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-xs">
                                <div>
                                    <span class="text-brand-ink/50 uppercase font-bold block text-[10px]">Hari & Waktu</span>
                                    <span class="text-sm font-bold text-brand-ink block mt-0.5" x-text="searchResult.jadwalObservasi.hariTanggal"></span>
                                    <span class="text-xs text-brand-darkred font-semibold" x-text="searchResult.jadwalObservasi.waktu"></span>
                                </div>
                                <div>
                                    <span class="text-brand-ink/50 uppercase font-bold block text-[10px]">Ruangan / Lokasi</span>
                                    <span class="text-xs font-bold text-brand-ink block mt-0.5" x-text="searchResult.jadwalObservasi.lokasi"></span>
                                </div>
                                <div>
                                    <span class="text-brand-ink/50 uppercase font-bold block text-[10px]">Pakaian</span>
                                    <span class="text-xs text-brand-ink block mt-0.5" x-text="searchResult.jadwalObservasi.pakaian"></span>
                                </div>
                                <div>
                                    <span class="text-brand-ink/50 uppercase font-bold block text-[10px]">Perlengkapan Wajib</span>
                                    <span class="text-xs text-brand-ink block mt-0.5" x-text="searchResult.jadwalObservasi.perlengkapan"></span>
                                </div>
                            </div>
                        </div>
                    </template>

                    <!-- 5. Detail Khusus (Rincian Biaya Daftar Ulang Jika Lulus) -->
                    <template x-if="searchResult?.rincianBiaya">
                        <div class="bg-white rounded-card p-6 sm:p-8 shadow-softpill border border-brand-ink/10 space-y-4">
                            <div class="flex items-center gap-2 text-brand-darkred">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                                <span class="font-editorial-eyebrow uppercase tracking-eyebrow text-xs font-bold">
                                    RINCIAN BIAYA DAFTAR ULANG
                                </span>
                            </div>

                            <div class="divide-y divide-brand-ink/10 text-xs">
                                <div class="py-2.5 flex justify-between">
                                    <span class="text-brand-ink/70">Total Biaya Standar</span>
                                    <span class="font-bold text-brand-ink font-mono" x-text="searchResult.rincianBiaya.totalBiaya"></span>
                                </div>
                                <div class="py-2.5 flex justify-between text-[#107c41]">
                                    <span class="font-semibold">Potongan Beasiswa Prestasi</span>
                                    <span class="font-bold font-mono" x-text="`- ${searchResult.rincianBiaya.potonganBeasiswa}`"></span>
                                </div>
                                <div class="py-3 flex justify-between text-sm sm:text-base font-extrabold text-brand-darkred">
                                    <span>Total yang Harus Dibayarkan</span>
                                    <span class="font-mono text-lg sm:text-xl" x-text="searchResult.rincianBiaya.totalBayar"></span>
                                </div>
                            </div>

                            <div class="p-4 rounded-2xl bg-[#F9F8F6] border border-brand-ink/10 text-xs space-y-1">
                                <span class="font-bold text-brand-ink block">Rekening Pembayaran Resmi:</span>
                                <p class="font-mono text-brand-darkred font-bold text-xs select-all" x-text="searchResult.rincianBiaya.rekeningPembayaran"></p>
                                <span class="text-[11px] text-brand-ink/50 block" x-text="`Batas Akhir Pelunasan: ${searchResult.rincianBiaya.batasPembayaran}`"></span>
                            </div>
                        </div>
                    </template>

                    <!-- 6. Quick CTA Buttons -->
                    <div class="flex flex-col sm:flex-row items-center justify-end gap-3 pt-2 no-print">
                        <button
                            type="button"
                            @click="handleResetSearch()"
                            class="w-full sm:w-auto py-2.5 px-6 rounded-full border border-brand-ink/20 text-brand-ink text-xs font-bold inline-flex items-center justify-center gap-1.5"
                        >
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                            <span>Cari Siswa Lain</span>
                        </button>

                        <button
                            type="button"
                            onclick="window.print()"
                            class="w-full sm:w-auto py-2.5 px-6 rounded-full bg-brand-ink hover:bg-brand-deepred text-white text-xs font-bold inline-flex items-center justify-center gap-1.5 cursor-pointer"
                        >
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                            <span>Cetak Bukti Pendaftaran</span>
                        </button>

                        <a
                            href="https://ppdb.smkpluspnb.sch.id/login"
                            target="_blank"
                            rel="noreferrer"
                            class="w-full sm:w-auto py-2.5 px-6 rounded-full bg-[#B72A32] hover:bg-[#7A1018] text-white text-xs font-bold shadow-softpill inline-flex items-center justify-center gap-1.5"
                        >
                            <span>Masuk ke Akun Siswa</span>
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                        </a>
                    </div>

                </div>
            </div>

        </div>
    </main>
</div>
@endsection
