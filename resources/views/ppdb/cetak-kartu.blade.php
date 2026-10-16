<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kartu Tanda Peserta PPDB - {{ $pendaftar->nama_lengkap }} ({{ $pendaftar->nomor_registrasi }})</title>
    <link rel="icon" type="image/svg+xml" href="{{ asset('assets/favicon.svg') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Oswald:wght@700&family=Plus+Jakarta+Sans:ital,wght@0,400;0,500;0,600;0,700;1,400&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        @media print {
            body {
                background: white !important;
                padding: 0 !important;
                margin: 0 !important;
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
            }
            .no-print {
                display: none !important;
            }
            .print-card {
                box-shadow: none !important;
                border: 2px solid #241012 !important;
                border-radius: 0 !important;
                padding: 1.5rem !important;
                max-width: 100% !important;
                margin: 0 !important;
            }
            @page {
                size: A4 portrait;
                margin: 1.2cm;
            }
        }
    </style>
</head>
<body class="bg-brand-softmist min-h-screen py-8 px-4 font-sans text-brand-ink antialiased selection:bg-brand-warmred">

    <!-- Action Toolbar (Hidden during print) -->
    <div class="max-w-4xl mx-auto mb-6 flex flex-wrap items-center justify-between gap-4 no-print bg-white p-4 rounded-2xl shadow-sm border border-brand-ink/10">
        <div class="flex items-center gap-3">
            <a href="javascript:history.back()" class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl text-xs font-semibold text-brand-ink/85 bg-brand-softmist hover:bg-brand-mist transition-colors">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Kembali
            </a>
            <div class="text-xs text-brand-ink/60 hidden sm:block">
                Gunakan opsi <strong class="text-brand-ink">"Save as PDF"</strong> pada dialog print untuk menyimpan file digital.
            </div>
        </div>

        <div class="flex items-center gap-2">
            <button onclick="window.print()" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl text-xs font-bold text-white bg-brand-darkred hover:bg-brand-deepred shadow-md shadow-brand-darkred/20 transition-all duration-150 transform active:scale-95">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
                </svg>
                Cetak / Download PDF
            </button>
        </div>
    </div>

    <!-- Official Printable Card Document -->
    <div class="max-w-4xl mx-auto bg-white border border-brand-ink/20 rounded-2xl p-8 sm:p-10 shadow-xl print-card relative">
        
        <!-- Official Kop Surat / Header Sekolah -->
        <div class="flex items-center justify-between pb-6 border-b-2 border-brand-ink gap-4">
            <div class="w-20 h-20 shrink-0 flex items-center justify-center">
                <img src="{{ asset('assets/logo-penus.png') }}" alt="Logo SMK Plus Pelita Nusantara" class="max-w-full max-h-full object-contain" onerror="this.onerror=null; this.src='https://placehold.co/100x100/1e3a8a/white?text=PENUS';">
            </div>
            <div class="text-center flex-1 px-2">
                <p class="text-xs uppercase font-bold tracking-widest text-brand-ink/75">YAYASAN PELITA NUSANTARA</p>
                <h1 class="font-display text-lg sm:text-2xl font-bold uppercase tracking-wide text-brand-ink leading-snug">SMK PLUS PELITA NUSANTARA BOGOR</h1>
                <p class="text-[11px] sm:text-xs text-brand-ink/75 font-medium mt-0.5">
                    TERAKREDITASI "A" (UNGGUL) &bull; SK DISDIK PROV. JAWA BARAT
                </p>
                <p class="text-[10px] sm:text-[11px] text-brand-ink/60 mt-1">
                    Jl. Raya Golf Ciriung No. 1, Kec. Cibinong, Kabupaten Bogor, Jawa Barat 16918 &bull; Telp: (021) 8790-1234 &bull; Web: penus.sch.id
                </p>
            </div>
            <div class="w-20 text-right shrink-0 hidden sm:block">
                <div class="inline-block p-1.5 border border-brand-ink/20 rounded-lg text-center bg-[#F9F8F6]">
                    <p class="text-[9px] font-bold text-brand-ink/60">TAHUN AJARAN</p>
                    <p class="text-xs font-bold text-brand-ink">2027/2028</p>
                </div>
            </div>
        </div>

        <!-- Title & Status Banner -->
        <div class="mt-6 flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-4 border-b border-dashed border-brand-ink/20">
            <div>
                <span class="inline-block px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider bg-brand-ink text-white mb-1">
                    TANDA BUKTI PENDAFTARAN RESMI
                </span>
                <h2 class="font-display text-xl sm:text-2xl font-bold uppercase tracking-wide text-brand-ink">KARTU TANDA PESERTA PPDB</h2>
                <p class="text-xs text-brand-ink/60 font-medium">
                    Gelombang: <strong class="text-brand-ink">{{ $pendaftar->gelombang?->nama ?? 'Gelombang 1 (Utama)' }}</strong> &bull; Terdaftar pada: {{ $pendaftar->created_at ? $pendaftar->created_at->locale('id')->isoFormat('D MMMM Y, HH:mm') : '-' }} WIB
                </p>
            </div>

            <div class="flex items-center gap-3 self-start sm:self-center">
                <div class="text-right">
                    <p class="text-[10px] text-brand-ink/60 uppercase font-bold tracking-wider">Status Seleksi</p>
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold border {{ $pendaftar->status_badge['bg'] }}">
                        <span class="w-2 h-2 rounded-full {{ $pendaftar->status_badge['dot'] }}"></span>
                        {{ $pendaftar->status_badge['label'] }}
                    </span>
                </div>
            </div>
        </div>

        <!-- Main Body: Student Details and Photo Box -->
        <div class="mt-6 grid grid-cols-1 md:grid-cols-4 gap-6">
            
            <!-- Left Side: Pas Foto & Registration Barcode Box -->
            <div class="md:col-span-1 flex flex-col items-center justify-start text-center border-b md:border-b-0 md:border-r border-brand-ink/10 pb-6 md:pb-0 md:pr-4">
                
                <!-- Simulated 3x4 Photo Container -->
                <div class="w-32 h-44 rounded-xl border-2 border-dashed border-brand-ink/30 bg-[#F9F8F6] flex flex-col items-center justify-center p-2 text-brand-ink/45 mb-4 shadow-inner relative overflow-hidden">
                    <div class="w-12 h-12 rounded-full bg-brand-mist flex items-center justify-center text-brand-ink/45 mb-2">
                        <svg class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                    </div>
                    <span class="text-[10px] font-bold uppercase tracking-wider text-brand-ink/60">Pas Foto 3x4</span>
                    <span class="text-[9px] text-brand-ink/45 mt-0.5">Latar Merah</span>
                </div>

                <!-- Registration Number Badge -->
                <div class="w-full bg-brand-ink text-white rounded-xl py-2 px-3 mb-2 shadow-sm">
                    <p class="text-[9px] font-bold text-brand-rose uppercase tracking-widest">Nomor Registrasi</p>
                    <p class="text-sm sm:text-base font-bold tracking-wider tabular-nums">{{ $pendaftar->nomor_registrasi }}</p>
                </div>

                <!-- Barcode / QR Simulation -->
                <div class="text-center p-2 rounded-lg bg-[#F9F8F6] border border-brand-ink/10 w-full">
                    <div class="tabular-nums text-xs tracking-widest text-brand-ink/85 font-bold py-1">
                        *{{ $pendaftar->nomor_registrasi }}*
                    </div>
                    <p class="text-[9px] text-brand-ink/45">Verifikasi Panitia PPDB</p>
                </div>
            </div>

            <!-- Right Side: Detailed Student Information -->
            <div class="md:col-span-3 space-y-4">
                
                <!-- Data Pribadi -->
                <div>
                    <h3 class="font-display text-sm font-bold uppercase tracking-wide text-brand-deepred border-b border-brand-darkred/15 pb-1 mb-2">
                        I. Data Identitas Calon Siswa
                    </h3>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-x-6 gap-y-2 text-xs">
                        <div>
                            <span class="text-brand-ink/60 block text-[11px]">Nama Lengkap:</span>
                            <span class="font-bold text-brand-ink text-sm">{{ $pendaftar->nama_lengkap }}</span>
                        </div>
                        <div>
                            <span class="text-brand-ink/60 block text-[11px]">Nama Panggilan:</span>
                            <span class="font-semibold text-brand-ink">{{ $pendaftar->nama_panggilan }}</span>
                        </div>
                        <div>
                            <span class="text-brand-ink/60 block text-[11px]">NISN (Nomor Induk Siswa Nasional):</span>
                            <span class="font-bold text-brand-ink tabular-nums">{{ $pendaftar->nisn ?? '-' }}</span>
                        </div>
                        <div>
                            <span class="text-brand-ink/60 block text-[11px]">Nomor Kartu Keluarga (KK):</span>
                            <span class="font-semibold text-brand-ink tabular-nums">{{ $pendaftar->nomor_kk ?? '-' }}</span>
                        </div>
                        <div>
                            <span class="text-brand-ink/60 block text-[11px]">Tempat, Tanggal Lahir:</span>
                            <span class="font-semibold text-brand-ink">{{ $pendaftar->tempat_lahir }}, {{ $pendaftar->tanggal_lahir_formatted }}</span>
                        </div>
                        <div>
                            <span class="text-brand-ink/60 block text-[11px]">Jenis Kelamin:</span>
                            <span class="font-semibold text-brand-ink">{{ $pendaftar->jenis_kelamin == 'L' ? 'Laki-Laki' : 'Perempuan' }}</span>
                        </div>
                        <div class="sm:col-span-2">
                            <span class="text-brand-ink/60 block text-[11px]">Alamat Lengkap Domisili:</span>
                            <span class="font-semibold text-brand-ink">{{ $pendaftar->alamat_lengkap ?? '-' }}</span>
                        </div>
                    </div>
                </div>

                <!-- Pilihan Jurusan & Sekolah -->
                <div>
                    <h3 class="font-display text-sm font-bold uppercase tracking-wide text-brand-deepred border-b border-brand-darkred/15 pb-1 mb-2">
                        II. Pilihan Kompetensi Keahlian & Asal Sekolah
                    </h3>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-x-6 gap-y-2 text-xs">
                        <div>
                            <span class="text-brand-ink/60 block text-[11px]">Kompetensi Keahlian (Jurusan):</span>
                            <span class="font-bold text-brand-deepred text-sm">{{ $pendaftar->jurusan }}</span>
                        </div>
                        <div>
                            <span class="text-brand-ink/60 block text-[11px]">Kelas Pilihan:</span>
                            <span class="font-bold text-brand-ink">{{ $pendaftar->kelas_pilihan }}</span>
                        </div>
                        <div>
                            <span class="text-brand-ink/60 block text-[11px]">Asal Sekolah SMP/MTs:</span>
                            <span class="font-bold text-brand-ink">{{ $pendaftar->asal_sekolah }}</span>
                        </div>
                    </div>
                </div>

                <!-- Kontak & Narahubung -->
                <div>
                    <h3 class="font-display text-sm font-bold uppercase tracking-wide text-brand-deepred border-b border-brand-darkred/15 pb-1 mb-2">
                        III. Informasi Kontak
                    </h3>
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-x-4 gap-y-2 text-xs">
                        <div>
                            <span class="text-brand-ink/60 block text-[11px]">No. WhatsApp Siswa:</span>
                            <span class="font-semibold text-brand-ink tabular-nums">{{ $pendaftar->nomor_kontak_pendaftar }}</span>
                        </div>
                        <div>
                            <span class="text-brand-ink/60 block text-[11px]">No. WhatsApp Orang Tua:</span>
                            <span class="font-semibold text-brand-ink tabular-nums">{{ $pendaftar->nomor_kontak_ortu }}</span>
                        </div>
                        <div>
                            <span class="text-brand-ink/60 block text-[11px]">Email Terdaftar:</span>
                            <span class="font-semibold text-brand-ink">{{ $pendaftar->email ?? '-' }}</span>
                        </div>
                    </div>
                </div>

            </div>
        </div>

        <!-- Instructions Box for Selection & Interview Test -->
        <div class="mt-6 p-4 rounded-xl bg-[#F9F8F6] border border-brand-ink/20 text-xs">
            <h4 class="font-sans font-bold text-brand-ink mb-2 flex items-center gap-1.5">
                <svg class="w-4 h-4 text-brand-darkred" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                PETUNJUK OBSERVASI & TAHAP SELANJUTNYA:
            </h4>
            <ol class="list-decimal list-inside space-y-1 text-brand-ink/75 text-[11px] leading-relaxed">
                <li>Kartu Tanda Peserta ini wajib dicetak dan dibawa saat menghadiri Tes Observasi & Wawancara Minat Bakat.</li>
                <li>Membawa berkas fisik: Fotokopi Rapor SMP semester 1-5, Akta Kelahiran, Kartu Keluarga, dan Pas Foto 3x4 (4 lembar).</li>
                <li>Mengenakan seragam sekolah asal SMP/MTs lengkap, rapi, dan bersepatu tertutup.</li>
                <li>Simpan nomor registrasi <strong class="text-brand-ink tabular-nums">{{ $pendaftar->nomor_registrasi }}</strong> untuk memantau pengumuman kelulusan di portal <span class="text-brand-darkred font-medium">penus.sch.id/ppdb/cek-status</span>.</li>
            </ol>
        </div>

        <!-- Official Signatures Area -->
        <div class="mt-8 pt-4 grid grid-cols-2 gap-8 text-center text-xs">
            <div>
                <p class="text-brand-ink/60 text-[11px]">Calon Peserta Didik Baru,</p>
                <div class="h-16"></div>
                <p class="font-bold text-brand-ink underline">{{ $pendaftar->nama_lengkap }}</p>
                <p class="text-[10px] text-brand-ink/45">Tanda Tangan & Nama Terang</p>
            </div>
            <div>
                <p class="text-brand-ink/60 text-[11px]">Cibinong, {{ now()->locale('id')->isoFormat('D MMMM Y') }}<br>Panitia Pelaksana PPDB,</p>
                <div class="h-16 flex items-center justify-center">
                    <span class="text-[9px] font-bold text-brand-ink/30 uppercase tracking-widest border border-dashed border-brand-ink/20 px-3 py-1 rounded">Stempel Panitia PPDB</span>
                </div>
                <p class="font-bold text-brand-ink underline">Sekretariat PPDB SMK Penus</p>
                <p class="text-[10px] text-brand-ink/45">NIP / ID Panitia Terverifikasi</p>
            </div>
        </div>

    </div>

</body>
</html>
