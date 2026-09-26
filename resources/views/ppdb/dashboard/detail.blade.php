@extends('layouts.admin')

@section('title', 'Detail Pendaftar - ' . $pendaftar->nama_lengkap . ' (' . $pendaftar->nomor_registrasi . ')')
@section('page_title', 'Detail & Update Data Pendaftar')

@section('content')
    <div class="space-y-3">

        <!-- SUB-NAV & BREADCRUMB BAR (Matches reference subnav: NO CARD, NO RADIUS) -->
        <div
            class="bg-white border border-brand-ink/15 p-2 sm:p-2.5 flex flex-col md:flex-row md:items-center justify-between gap-2.5">
            <!-- Breadcrumb / Navigator -->
            <div class="flex items-center gap-2 text-xs flex-wrap">
                <a href="{{ route('ppdb.dashboard') }}" class="text-brand-ink/60 hover:text-brand-darkred font-semibold">
                    Dashboard
                </a>
                <span class="text-brand-ink/30">/</span>
                <a href="{{ route('ppdb.dashboard.pendaftar') }}"
                    class="text-brand-ink/60 hover:text-brand-darkred font-semibold">
                    Pendaftar Siswa
                </a>
                <span class="text-brand-ink/30">/</span>
                <span class="font-mono font-bold text-brand-darkred bg-brand-darkred/10 px-2 py-0.5">
                    {{ $pendaftar->nomor_registrasi }}
                </span>
            </div>

            <!-- Next / Prev Controls & Action CTA -->
            <div class="flex items-center gap-2 flex-wrap">
                @if ($prevStudent)
                    <a href="{{ route('ppdb.dashboard.pendaftar.detail', $prevStudent->id) }}"
                        class="px-3 py-1.5 bg-white hover:bg-black/5 text-brand-ink border border-brand-ink/20 text-xs font-bold transition-all flex items-center gap-1">
                        &larr; Prev
                    </a>
                @endif

                @if ($nextStudent)
                    <a href="{{ route('ppdb.dashboard.pendaftar.detail', $nextStudent->id) }}"
                        class="px-3 py-1.5 bg-white hover:bg-black/5 text-brand-ink border border-brand-ink/20 text-xs font-bold transition-all flex items-center gap-1">
                        Next &rarr;
                    </a>
                @endif

                <button type="button" onclick="window.print()"
                    class="px-3 py-1.5 bg-white hover:bg-black/5 text-brand-ink border border-brand-ink/20 text-xs font-bold transition-all flex items-center gap-1.5 cursor-pointer">
                    <svg class="w-3.5 h-3.5 text-brand-darkred" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z">
                        </path>
                    </svg>
                    <span>Cetak Lembar</span>
                </button>

                <a href="{{ route('ppdb.dashboard.pendaftar') }}"
                    class="px-3.5 py-1.5 bg-black/5 hover:bg-black/10 text-brand-ink text-xs font-bold uppercase tracking-wider transition-colors border border-black/10">
                    &larr; Kembali
                </a>
            </div>
        </div>

        <!-- STUDENT SUMMARY BANNER (NO CARD, FLAT STRUCTURED STRIP) -->
        <div class="bg-white border border-brand-ink/15 p-4 sm:p-5">
            <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4">
                <div class="space-y-1">
                    <div class="flex items-center gap-2 flex-wrap">
                        <span
                            class="font-mono text-xs font-bold px-2 py-0.5 bg-black/5 border border-black/10 text-brand-ink">
                            {{ $pendaftar->nomor_registrasi }}
                        </span>
                        @php $badge = $pendaftar->status_badge; @endphp
                        <span class="px-2.5 py-0.5 text-[10px] font-bold border {{ $badge['bg'] }}">
                            {{ $badge['label'] }}
                        </span>
                        <span class="text-[11px] text-brand-ink/60">
                            Terdaftar: {{ $pendaftar->created_at->format('d F Y, H:i') }} WIB
                        </span>
                    </div>
                    <h2 class="font-display font-bold text-2xl sm:text-3xl uppercase tracking-wide text-brand-ink">
                        {{ $pendaftar->nama_lengkap }}
                    </h2>
                    <div class="text-xs text-brand-ink/70 flex items-center gap-3 flex-wrap">
                        <span>Panggilan: <strong>{{ $pendaftar->nama_panggilan ?? '-' }}</strong></span>
                        <span class="text-brand-ink/30">•</span>
                        <span>Asal: <strong>{{ $pendaftar->asal_sekolah }}</strong></span>
                        <span class="text-brand-ink/30">•</span>
                        <span>Pilihan: <strong>{{ $majors[$pendaftar->jurusan] ?? $pendaftar->jurusan }}</strong></span>
                    </div>
                </div>

                <!-- Quick WhatsApp Links -->
                <div class="flex items-center gap-2 flex-wrap shrink-0">
                    @if ($pendaftar->nomor_kontak_pendaftar)
                        <a href="https://wa.me/{{ preg_replace('/\D/', '', $pendaftar->nomor_kontak_pendaftar) }}"
                            target="_blank"
                            class="px-3 py-1.5 bg-emerald-50 hover:bg-emerald-100 text-emerald-800 border border-emerald-300 text-xs font-bold flex items-center gap-1.5 transition-colors">
                            <span>WA Siswa</span>
                            <span class="font-mono font-normal">({{ $pendaftar->nomor_kontak_pendaftar }})</span>
                        </a>
                    @endif
                    @if ($pendaftar->nomor_kontak_ortu)
                        <a href="https://wa.me/{{ preg_replace('/\D/', '', $pendaftar->nomor_kontak_ortu) }}" target="_blank"
                            class="px-3 py-1.5 bg-emerald-50 hover:bg-emerald-100 text-emerald-800 border border-emerald-300 text-xs font-bold flex items-center gap-1.5 transition-colors">
                            <span>WA Ortu</span>
                            <span class="font-mono font-normal">({{ $pendaftar->nomor_kontak_ortu }})</span>
                        </a>
                    @endif
                </div>
            </div>
        </div>

        <!-- MAIN UPDATE FORM (NO CARD DESIGN, COMPACT RECTANGULAR PANELS) -->
        <form action="{{ route('ppdb.dashboard.pendaftar.update', $pendaftar->id) }}" method="POST" class="space-y-3">
            @csrf
            @method('PUT')

            <!-- SECTION 1: STATUS VERIFIKASI & CATATAN PANITIA (TOP PRIORITY) -->
            <div class="bg-white border-2 border-brand-darkred/40 p-4">
                <div class="flex items-center gap-2 pb-2 mb-3 border-b border-brand-ink/10">
                    <h3 class="font-display font-bold text-sm uppercase tracking-wider text-brand-ink">
                        1. Status Verifikasi & Catatan Panitia PPDB
                    </h3>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-12 gap-3">
                    <div class="md:col-span-4">
                        <label class="block text-[11px] font-bold uppercase tracking-wider text-brand-ink/70 mb-1">
                            Status Pendaftaran Saat Ini <span class="text-red-600">*</span>
                        </label>
                        <select name="status" required
                            class="w-full px-3 py-2 border border-brand-ink/30 text-xs font-bold bg-white focus:outline-none focus:border-brand-darkred cursor-pointer">
                            <option value="menunggu_verifikasi" {{ old('status', $pendaftar->status) === 'menunggu_verifikasi' ? 'selected' : '' }}>
                                Menunggu Verifikasi
                            </option>
                            <option value="terverifikasi" {{ old('status', $pendaftar->status) === 'terverifikasi' ? 'selected' : '' }}>
                                Terverifikasi (Berkas Lengkap)
                            </option>
                            <option value="lulus_seleksi" {{ old('status', $pendaftar->status) === 'lulus_seleksi' ? 'selected' : '' }}>
                                Lulus Seleksi PPDB (Siap Daftar Ulang)
                            </option>
                        </select>
                        <span class="text-[10px] text-brand-ink/50 mt-1 block">
                            Ubah status ini sesuai hasil verifikasi berkas atau seleksi calon siswa.
                        </span>
                    </div>

                    <div class="md:col-span-8">
                        <label class="block text-[11px] font-bold uppercase tracking-wider text-brand-ink/70 mb-1">
                            Catatan Panitia Verifikator
                        </label>
                        <textarea name="catatan" rows="2"
                            placeholder="Contoh: Berkas ijazah dan KK telah diverifikasi panitia. Tinggal menunggu pasfoto fisik..."
                            class="w-full px-3 py-2 border border-brand-ink/30 text-xs bg-white focus:outline-none focus:border-brand-darkred">{{ old('catatan', $pendaftar->catatan) }}</textarea>
                    </div>
                </div>
            </div>

            <!-- SECTION 2: DATA DIRI CALON SISWA -->
            <div class="bg-white border border-brand-ink/15 p-4">
                <div class="flex items-center gap-2 pb-2 mb-3 border-b border-brand-ink/10">
                    <h3 class="font-display font-bold text-sm uppercase tracking-wider text-brand-ink">
                        2. Data Diri Calon Siswa
                    </h3>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-12 gap-3">
                    <!-- Nama Lengkap -->
                    <div class="md:col-span-6 sm:col-span-2">
                        <label class="block text-[11px] font-bold uppercase tracking-wider text-brand-ink/70 mb-1">
                            Nama Lengkap (Sesuai Ijazah/Akta) <span class="text-red-600">*</span>
                        </label>
                        <input type="text" name="nama_lengkap" value="{{ old('nama_lengkap', $pendaftar->nama_lengkap) }}"
                            required
                            class="w-full px-3 py-2 border border-brand-ink/20 text-xs focus:outline-none focus:border-brand-darkred" />
                    </div>

                    <!-- Nama Panggilan -->
                    <div class="md:col-span-3 sm:col-span-1">
                        <label class="block text-[11px] font-bold uppercase tracking-wider text-brand-ink/70 mb-1">
                            Nama Panggilan <span class="text-red-600">*</span>
                        </label>
                        <input type="text" name="nama_panggilan"
                            value="{{ old('nama_panggilan', $pendaftar->nama_panggilan) }}" required
                            class="w-full px-3 py-2 border border-brand-ink/20 text-xs focus:outline-none focus:border-brand-darkred" />
                    </div>

                    <!-- Jenis Kelamin -->
                    <div class="md:col-span-3 sm:col-span-1">
                        <label class="block text-[11px] font-bold uppercase tracking-wider text-brand-ink/70 mb-1">
                            Jenis Kelamin <span class="text-red-600">*</span>
                        </label>
                        <select name="jenis_kelamin" required
                            class="w-full px-3 py-2 border border-brand-ink/20 text-xs focus:outline-none focus:border-brand-darkred bg-white cursor-pointer">
                            <option value="L" {{ old('jenis_kelamin', $pendaftar->jenis_kelamin) === 'L' ? 'selected' : '' }}>
                                Laki-laki</option>
                            <option value="P" {{ old('jenis_kelamin', $pendaftar->jenis_kelamin) === 'P' ? 'selected' : '' }}>
                                Perempuan</option>
                        </select>
                    </div>

                    <!-- NISN -->
                    <div class="md:col-span-3 sm:col-span-1">
                        <label class="block text-[11px] font-bold uppercase tracking-wider text-brand-ink/70 mb-1">
                            Nomor NISN Siswa
                        </label>
                        <input type="text" name="nisn" value="{{ old('nisn', $pendaftar->nisn) }}"
                            placeholder="Contoh: 0081298471"
                            class="w-full px-3 py-2 border border-brand-ink/20 text-xs font-mono focus:outline-none focus:border-brand-darkred" />
                    </div>

                    <!-- Nomor KK -->
                    <div class="md:col-span-3 sm:col-span-1">
                        <label class="block text-[11px] font-bold uppercase tracking-wider text-brand-ink/70 mb-1">
                            Nomor Kartu Keluarga (KK)
                        </label>
                        <input type="text" name="nomor_kk" value="{{ old('nomor_kk', $pendaftar->nomor_kk) }}"
                            placeholder="16 digit nomor KK"
                            class="w-full px-3 py-2 border border-brand-ink/20 text-xs font-mono focus:outline-none focus:border-brand-darkred" />
                    </div>

                    <!-- Tempat Lahir -->
                    <div class="md:col-span-3 sm:col-span-1">
                        <label class="block text-[11px] font-bold uppercase tracking-wider text-brand-ink/70 mb-1">
                            Tempat Lahir <span class="text-red-600">*</span>
                        </label>
                        <input type="text" name="tempat_lahir" value="{{ old('tempat_lahir', $pendaftar->tempat_lahir) }}"
                            required
                            class="w-full px-3 py-2 border border-brand-ink/20 text-xs focus:outline-none focus:border-brand-darkred" />
                    </div>

                    <!-- Tanggal Lahir (Hari, Bulan, Tahun) -->
                    <div class="md:col-span-3 sm:col-span-1">
                        <label class="block text-[11px] font-bold uppercase tracking-wider text-brand-ink/70 mb-1">
                            Tanggal Lahir (H / B / T) <span class="text-red-600">*</span>
                        </label>
                        <div class="grid grid-cols-3 gap-1">
                            <input type="text" name="tanggal_lahir_hari"
                                value="{{ old('tanggal_lahir_hari', $pendaftar->tanggal_lahir_hari) }}" placeholder="DD"
                                required
                                class="px-2 py-2 border border-brand-ink/20 text-xs text-center font-mono focus:outline-none focus:border-brand-darkred" />
                            <input type="text" name="tanggal_lahir_bulan"
                                value="{{ old('tanggal_lahir_bulan', $pendaftar->tanggal_lahir_bulan) }}" placeholder="MM"
                                required
                                class="px-2 py-2 border border-brand-ink/20 text-xs text-center font-mono focus:outline-none focus:border-brand-darkred" />
                            <input type="text" name="tanggal_lahir_tahun"
                                value="{{ old('tanggal_lahir_tahun', $pendaftar->tanggal_lahir_tahun) }}" placeholder="YYYY"
                                required
                                class="px-2 py-2 border border-brand-ink/20 text-xs text-center font-mono focus:outline-none focus:border-brand-darkred" />
                        </div>
                    </div>

                    <!-- Alamat Lengkap -->
                    <div class="col-span-full">
                        <label class="block text-[11px] font-bold uppercase tracking-wider text-brand-ink/70 mb-1">
                            Alamat Lengkap Tempat Tinggal
                        </label>
                        <textarea name="alamat_lengkap" rows="2"
                            class="w-full px-3 py-2 border border-brand-ink/20 text-xs focus:outline-none focus:border-brand-darkred">{{ old('alamat_lengkap', $pendaftar->alamat_lengkap) }}</textarea>
                    </div>
                </div>
            </div>

            <!-- SECTION 3: PILIHAN JURUSAN & ASAL SEKOLAH -->
            <div class="bg-white border border-brand-ink/15 p-4">
                <div class="flex items-center gap-2 pb-2 mb-3 border-b border-brand-ink/10">
                    <h3 class="font-display font-bold text-sm uppercase tracking-wider text-brand-ink">
                        3. Pilihan Program Jurusan & Asal Sekolah
                    </h3>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-12 gap-3">
                    <!-- Jurusan Pilihan -->
                    <div class="md:col-span-6 sm:col-span-2">
                        <label class="block text-[11px] font-bold uppercase tracking-wider text-brand-ink/70 mb-1">
                            Kompetensi Keahlian (Jurusan) <span class="text-red-600">*</span>
                        </label>
                        <select name="jurusan" required
                            class="w-full px-3 py-2 border border-brand-ink/20 text-xs font-semibold focus:outline-none focus:border-brand-darkred bg-white cursor-pointer">
                            @foreach ($majors as $fullName => $shortName)
                                <option value="{{ $fullName }}" {{ old('jurusan', $pendaftar->jurusan) === $fullName ? 'selected' : '' }}>
                                    [{{ $shortName }}] - {{ $fullName }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Asal Sekolah -->
                    <div class="md:col-span-6 sm:col-span-2">
                        <label class="block text-[11px] font-bold uppercase tracking-wider text-brand-ink/70 mb-1">
                            Nama Asal Sekolah (SMP / MTs) <span class="text-red-600">*</span>
                        </label>
                        <input type="text" name="asal_sekolah" value="{{ old('asal_sekolah', $pendaftar->asal_sekolah) }}"
                            required
                            class="w-full px-3 py-2 border border-brand-ink/20 text-xs focus:outline-none focus:border-brand-darkred" />
                    </div>

                    <!-- Jalur Seleksi -->
                    <div class="md:col-span-4 sm:col-span-1">
                        <label class="block text-[11px] font-bold uppercase tracking-wider text-brand-ink/70 mb-1">
                            Jalur Seleksi Pendaftaran <span class="text-red-600">*</span>
                        </label>
                        <select name="jalur_seleksi" required
                            class="w-full px-3 py-2 border border-brand-ink/20 text-xs focus:outline-none focus:border-brand-darkred bg-white cursor-pointer">
                            <option value="Reguler" {{ old('jalur_seleksi', $pendaftar->jalur_seleksi) === 'Reguler' ? 'selected' : '' }}>Reguler</option>
                            <option value="Prestasi Akademik" {{ old('jalur_seleksi', $pendaftar->jalur_seleksi) === 'Prestasi Akademik' ? 'selected' : '' }}>Prestasi Akademik</option>
                            <option value="Prestasi Non-Akademik" {{ old('jalur_seleksi', $pendaftar->jalur_seleksi) === 'Prestasi Non-Akademik' ? 'selected' : '' }}>Prestasi
                                Non-Akademik</option>
                            <option value="Afirmasi / KETM" {{ old('jalur_seleksi', $pendaftar->jalur_seleksi) === 'Afirmasi / KETM' ? 'selected' : '' }}>Afirmasi / KETM</option>
                        </select>
                    </div>

                    <!-- Tipe Pendaftar -->
                    <div class="md:col-span-4 sm:col-span-1">
                        <label class="block text-[11px] font-bold uppercase tracking-wider text-brand-ink/70 mb-1">
                            Tipe Pendaftar <span class="text-red-600">*</span>
                        </label>
                        <select name="tipe_pendaftar" required
                            class="w-full px-3 py-2 border border-brand-ink/20 text-xs focus:outline-none focus:border-brand-darkred bg-white cursor-pointer">
                            <option value="Pendaftar Baru" {{ old('tipe_pendaftar', $pendaftar->tipe_pendaftar) === 'Pendaftar Baru' ? 'selected' : '' }}>Pendaftar Baru (Tingkat X)</option>
                            <option value="Pindahan" {{ old('tipe_pendaftar', $pendaftar->tipe_pendaftar) === 'Pindahan' ? 'selected' : '' }}>Siswa Pindahan</option>
                        </select>
                    </div>

                    <!-- Kelas Pilihan -->
                    <div class="md:col-span-4 sm:col-span-2">
                        <label class="block text-[11px] font-bold uppercase tracking-wider text-brand-ink/70 mb-1">
                            Kelas Pilihan <span class="text-red-600">*</span>
                        </label>
                        <input type="text" name="kelas_pilihan"
                            value="{{ old('kelas_pilihan', $pendaftar->kelas_pilihan ?? 'Kelas 10') }}" required
                            class="w-full px-3 py-2 border border-brand-ink/20 text-xs focus:outline-none focus:border-brand-darkred" />
                    </div>
                </div>
            </div>

            <!-- SECTION 4: KONTAK SISWA & ORANG TUA -->
            <div class="bg-white border border-brand-ink/15 p-4">
                <div class="flex items-center gap-2 pb-2 mb-3 border-b border-brand-ink/10">
                    <h3 class="font-display font-bold text-sm uppercase tracking-wider text-brand-ink">
                        4. Nomor Kontak WhatsApp & Komunikasi
                    </h3>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                    <!-- Kontak Siswa -->
                    <div>
                        <label class="block text-[11px] font-bold uppercase tracking-wider text-brand-ink/70 mb-1">
                            Nomor WhatsApp Calon Siswa <span class="text-red-600">*</span>
                        </label>
                        <input type="text" name="nomor_kontak_pendaftar"
                            value="{{ old('nomor_kontak_pendaftar', $pendaftar->nomor_kontak_pendaftar) }}" required
                            class="w-full px-3 py-2 border border-brand-ink/20 text-xs font-mono focus:outline-none focus:border-brand-darkred" />
                    </div>

                    <!-- Kontak Ortu -->
                    <div>
                        <label class="block text-[11px] font-bold uppercase tracking-wider text-brand-ink/70 mb-1">
                            Nomor WhatsApp Orang Tua / Wali <span class="text-red-600">*</span>
                        </label>
                        <input type="text" name="nomor_kontak_ortu"
                            value="{{ old('nomor_kontak_ortu', $pendaftar->nomor_kontak_ortu) }}" required
                            class="w-full px-3 py-2 border border-brand-ink/20 text-xs font-mono focus:outline-none focus:border-brand-darkred" />
                    </div>

                    <!-- Email -->
                    <div>
                        <label class="block text-[11px] font-bold uppercase tracking-wider text-brand-ink/70 mb-1">
                            Alamat Email Siswa
                        </label>
                        <input type="email" name="email" value="{{ old('email', $pendaftar->email) }}"
                            placeholder="contoh@gmail.com"
                            class="w-full px-3 py-2 border border-brand-ink/20 text-xs focus:outline-none focus:border-brand-darkred" />
                    </div>
                </div>
            </div>

            <!-- SECTION 5: INFORMASI TAMBAHAN & LAYANAN -->
            <div class="bg-white border border-brand-ink/15 p-4">
                <div class="flex items-center gap-2 pb-2 mb-3 border-b border-brand-ink/10">
                    <h3 class="font-display font-bold text-sm uppercase tracking-wider text-brand-ink">
                        5. Informasi Seragam & Layanan Pendukung
                    </h3>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-12 gap-3">
                    <!-- Ukuran Seragam -->
                    <div class="md:col-span-4">
                        <label class="block text-[11px] font-bold uppercase tracking-wider text-brand-ink/70 mb-1">
                            Ukuran Seragam Siswa
                        </label>
                        <select name="ukuran_seragam"
                            class="w-full px-3 py-2 border border-brand-ink/20 text-xs focus:outline-none focus:border-brand-darkred bg-white cursor-pointer">
                            <option value="">Belum Memilih</option>
                            @foreach (['S', 'M', 'L', 'XL', 'XXL', 'XXXL'] as $size)
                                <option value="{{ $size }}" {{ old('ukuran_seragam', $pendaftar->ukuran_seragam) === $size ? 'selected' : '' }}>
                                    Ukuran {{ $size }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Alasan Minat -->
                    <div class="md:col-span-8">
                        <label class="block text-[11px] font-bold uppercase tracking-wider text-brand-ink/70 mb-1">
                            Alasan Memilih SMK Plus Pelita Nusantara
                        </label>
                        <input type="text" name="alasan_minat" value="{{ old('alasan_minat', $pendaftar->alasan_minat) }}"
                            placeholder="Contoh: Gedung dan Fasilitas Lengkap, Program Magang Industri..."
                            class="w-full px-3 py-2 border border-brand-ink/20 text-xs focus:outline-none focus:border-brand-darkred" />
                    </div>
                </div>
            </div>

            <!-- ACTION BAR (STICKY BOTTOM, NO CARD, FLAT RECTANGULAR BUTTONS) -->
            <div
                class="bg-white border border-brand-ink/15 p-3 flex flex-col sm:flex-row items-center justify-between gap-3 sticky bottom-3 shadow-lg z-20">
                <div class="flex items-center gap-2">
                    <span class="text-xs text-emerald-600">
                        Perubahan data pendaftar akan langsung tersimpan di basis data PPDB.
                    </span>
                </div>

                <div class="flex items-center gap-2 w-full sm:w-auto">
                    <a href="{{ route('ppdb.dashboard.pendaftar') }}"
                        class="flex-1 sm:flex-none px-4 py-2 bg-black/5 hover:bg-black/10 text-brand-ink text-xs font-bold uppercase tracking-wider text-center border border-black/10 transition-colors">
                        Batal
                    </a>

                    <button type="submit"
                        class="flex-1 sm:flex-none px-6 py-2 bg-brand-darkred hover:bg-brand-deepred text-white text-xs font-bold uppercase tracking-wider text-center transition-all cursor-pointer border border-brand-darkred">
                        Simpan
                    </button>
                </div>
            </div>

        </form>

        <!-- DANGER ZONE: HAPUS PENDAFTAR -->
        <div class="bg-white border border-red-200 p-4">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                <div>
                    <h4 class="font-display font-bold text-sm text-red-700 uppercase tracking-wider">
                        Hapus Data Calon Siswa Ini
                    </h4>
                    <p class="text-xs text-brand-ink/60">
                        Data pendaftar dengan nomor registrasi <span
                            class="font-mono font-bold">{{ $pendaftar->nomor_registrasi }}</span> akan dihapus permanen dari
                        sistem PPDB.
                    </p>
                </div>
                <form action="{{ route('ppdb.dashboard.pendaftar.destroy', $pendaftar->id) }}" method="POST"
                    onsubmit="return confirm('Peringatan: Apakah Anda yakin ingin menghapus data calon siswa {{ $pendaftar->nama_lengkap }} ({{ $pendaftar->nomor_registrasi }}) secara permanen?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                        class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white text-xs font-bold uppercase tracking-wider transition-colors cursor-pointer">
                        Hapus Pendaftar
                    </button>
                </form>
            </div>
        </div>

    </div>
@endsection