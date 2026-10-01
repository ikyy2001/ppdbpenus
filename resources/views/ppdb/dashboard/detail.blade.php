@extends('layouts.admin')

@section('title', 'Detail Pendaftar - ' . $pendaftar->nama_lengkap . ' (' . $pendaftar->nomor_registrasi . ')')
@section('page_title', 'Detail & Update Data Pendaftar')

@section('content')
<div class="space-y-5">

    <!-- SUB-NAV & BREADCRUMB BAR -->
    <div class="bg-white rounded-2xl border border-slate-200/80 p-3 sm:p-3.5 shadow-sm flex flex-col md:flex-row md:items-center justify-between gap-3">
        <!-- Breadcrumb / Navigator -->
        <div class="flex items-center gap-2 text-xs flex-wrap">
            <a href="{{ route('ppdb.dashboard') }}" class="text-slate-500 hover:text-[#8B1D24] font-semibold transition-colors">
                Dashboard
            </a>
            <span class="text-slate-300">/</span>
            <a href="{{ route('ppdb.dashboard.pendaftar') }}" class="text-slate-500 hover:text-[#8B1D24] font-semibold transition-colors">
                Pendaftar Siswa
            </a>
            <span class="text-slate-300">/</span>
            <span class="font-mono font-bold text-xs text-[#8B1D24] bg-red-50 px-2.5 py-1 rounded-full">
                {{ $pendaftar->nomor_registrasi }}
            </span>
        </div>

        <!-- Next / Prev Controls & Action CTA -->
        <div class="flex items-center gap-2 flex-wrap">
            @if ($prevStudent)
                <a href="{{ route('ppdb.dashboard.pendaftar.detail', $prevStudent->id) }}"
                    class="px-3.5 py-2 rounded-xl bg-white hover:bg-slate-50 text-slate-700 border border-slate-200 text-xs font-semibold transition-all shadow-2xs flex items-center gap-1">
                    &larr; Prev
                </a>
            @endif

            @if ($nextStudent)
                <a href="{{ route('ppdb.dashboard.pendaftar.detail', $nextStudent->id) }}"
                    class="px-3.5 py-2 rounded-xl bg-white hover:bg-slate-50 text-slate-700 border border-slate-200 text-xs font-semibold transition-all shadow-2xs flex items-center gap-1">
                    Next &rarr;
                </a>
            @endif

            <a href="{{ route('ppdb.cetak-kartu', $pendaftar->id) }}" target="_blank"
                class="px-3.5 py-2 rounded-xl bg-blue-700 hover:bg-blue-800 text-white text-xs font-bold transition-all shadow-xs flex items-center gap-1.5 cursor-pointer">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                <span>Cetak Kartu Peserta</span>
            </a>

            <button type="button" onclick="window.print()"
                class="px-3.5 py-2 rounded-xl bg-white hover:bg-slate-50 text-slate-700 border border-slate-200 text-xs font-semibold transition-all shadow-2xs flex items-center gap-1.5 cursor-pointer">
                <svg class="w-3.5 h-3.5 text-[#8B1D24]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z">
                    </path>
                </svg>
                <span>Cetak Lembar</span>
            </button>

            <a href="{{ route('ppdb.dashboard.pendaftar') }}"
                class="px-4 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-semibold transition-colors">
                &larr; Kembali
            </a>
        </div>
    </div>

    <!-- STUDENT SUMMARY BANNER -->
    <div class="bg-white rounded-2xl border border-slate-200/80 p-6 sm:p-7 shadow-sm relative overflow-hidden">
        <!-- Subtle decorative glow -->
        <div class="absolute -right-16 -top-16 w-64 h-64 bg-red-50/50 rounded-full blur-3xl pointer-events-none"></div>

        <div class="relative z-10 flex flex-col lg:flex-row lg:items-center justify-between gap-6">
            <div class="space-y-2">
                <div class="flex items-center gap-2 flex-wrap">
                    <span class="font-mono text-xs font-bold px-3 py-1 rounded-full bg-[#1E293B] text-white shadow-xs">
                        {{ $pendaftar->nomor_registrasi }}
                    </span>
                    @php $badge = $pendaftar->status_badge; @endphp
                    <span class="px-3 py-1 rounded-full text-xs font-semibold border {{ $badge['bg'] }}">
                        {{ $badge['label'] }}
                    </span>
                    <span class="text-xs text-slate-400 font-medium">
                        Terdaftar: {{ $pendaftar->created_at->format('d F Y, H:i') }} WIB
                    </span>
                </div>
                <h1 class="text-2xl sm:text-3xl font-extrabold text-[#0F172A] tracking-tight">
                    {{ $pendaftar->nama_lengkap }}
                </h1>
                <div class="text-xs sm:text-sm text-slate-500 flex items-center gap-2.5 flex-wrap">
                    <span>Panggilan: <strong class="text-slate-800">{{ $pendaftar->nama_panggilan ?? '-' }}</strong></span>
                    <span class="text-slate-300">•</span>
                    <span>Asal: <strong class="text-slate-800">{{ $pendaftar->asal_sekolah }}</strong></span>
                    <span class="text-slate-300">•</span>
                    <span>Pilihan: <strong class="text-slate-800">{{ $majors[$pendaftar->jurusan] ?? $pendaftar->jurusan }}</strong></span>
                </div>
            </div>

            <!-- Quick WhatsApp Links -->
            <div class="flex items-center gap-2.5 flex-wrap shrink-0">
                @if ($pendaftar->nomor_kontak_pendaftar)
                    <a href="https://wa.me/{{ preg_replace('/\D/', '', $pendaftar->nomor_kontak_pendaftar) }}"
                        target="_blank"
                        class="px-4 py-2.5 rounded-xl bg-emerald-50 hover:bg-emerald-100 text-emerald-800 border border-emerald-200/80 text-xs font-semibold flex items-center gap-2 transition-colors shadow-2xs">
                        <span>📱 Hubungi Siswa</span>
                        <span class="font-mono text-emerald-700">({{ $pendaftar->nomor_kontak_pendaftar }})</span>
                    </a>
                @endif
                @if ($pendaftar->nomor_kontak_ortu)
                    <a href="https://wa.me/{{ preg_replace('/\D/', '', $pendaftar->nomor_kontak_ortu) }}" target="_blank"
                        class="px-4 py-2.5 rounded-xl bg-emerald-50 hover:bg-emerald-100 text-emerald-800 border border-emerald-200/80 text-xs font-semibold flex items-center gap-2 transition-colors shadow-2xs">
                        <span>📱 Hubungi Ortu</span>
                        <span class="font-mono text-emerald-700">({{ $pendaftar->nomor_kontak_ortu }})</span>
                    </a>
                @endif
            </div>
        </div>
    </div>

    <!-- MAIN UPDATE FORM -->
    <form action="{{ route('ppdb.dashboard.pendaftar.update', $pendaftar->id) }}" method="POST" class="space-y-5">
        @csrf
        @method('PUT')

        <!-- SECTION 1: STATUS VERIFIKASI & CATATAN PANITIA (TOP PRIORITY) -->
        <div class="bg-white rounded-2xl border-2 border-red-100 p-5 sm:p-6 shadow-sm relative overflow-hidden">
            <div class="flex items-center gap-2.5 pb-3 mb-4 border-b border-slate-100">
                <span class="w-7 h-7 rounded-xl bg-[#8B1D24] text-white flex items-center justify-center font-bold text-xs shadow-xs">1</span>
                <div>
                    <h3 class="font-extrabold text-sm text-[#0F172A] tracking-tight">
                        Status Verifikasi & Catatan Panitia PPDB
                    </h3>
                    <p class="text-xs text-slate-400">Atur progres berkas dan catatan verifikasi pendaftar</p>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-12 gap-4">
                <div class="md:col-span-4">
                    <label class="block text-xs font-semibold text-slate-700 mb-1.5">
                        Status Pendaftaran Saat Ini <span class="text-red-600">*</span>
                    </label>
                    <select name="status" required
                        class="w-full px-4 py-2.5 rounded-xl bg-[#F1F5F9] border-0 text-xs font-bold text-slate-900 focus:bg-white focus:ring-2 focus:ring-[#8B1D24]/30 cursor-pointer transition-all">
                        <option value="menunggu_verifikasi" {{ old('status', $pendaftar->status) === 'menunggu_verifikasi' ? 'selected' : '' }}>
                            Menunggu Verifikasi
                        </option>
                        <option value="terverifikasi" {{ old('status', $pendaftar->status) === 'terverifikasi' ? 'selected' : '' }}>
                            Terverifikasi (Berkas Lengkap)
                        </option>
                        <option value="lulus_seleksi" {{ old('status', $pendaftar->status) === 'lulus_seleksi' ? 'selected' : '' }}>
                            Lulus Seleksi PPDB (Siap Daftar Ulang)
                        </option>
                        <option value="tidak_lulus" {{ old('status', $pendaftar->status) === 'tidak_lulus' ? 'selected' : '' }}>
                            Tidak Lolos Seleksi
                        </option>
                    </select>
                    <span class="text-[11px] text-slate-400 mt-1.5 block">
                        Ubah status ini sesuai hasil verifikasi berkas atau seleksi calon siswa.
                    </span>
                </div>

                <div class="md:col-span-8">
                    <label class="block text-xs font-semibold text-slate-700 mb-1.5">
                        Catatan Panitia Verifikator
                    </label>
                    <textarea name="catatan" rows="2"
                        placeholder="Contoh: Berkas ijazah dan KK telah diverifikasi panitia. Tinggal menunggu pasfoto fisik..."
                        class="w-full px-4 py-2.5 rounded-xl bg-[#F1F5F9] border-0 text-xs text-slate-800 placeholder-slate-400 focus:bg-white focus:ring-2 focus:ring-[#8B1D24]/30 transition-all">{{ old('catatan', $pendaftar->catatan) }}</textarea>
                </div>
            </div>
        </div>

        <!-- SECTION 2: DATA DIRI CALON SISWA -->
        <div class="bg-white rounded-2xl border border-slate-200/80 p-5 sm:p-6 shadow-sm">
            <div class="flex items-center gap-2.5 pb-3 mb-4 border-b border-slate-100">
                <span class="w-7 h-7 rounded-xl bg-[#1E293B] text-white flex items-center justify-center font-bold text-xs shadow-xs">2</span>
                <div>
                    <h3 class="font-extrabold text-sm text-[#0F172A] tracking-tight">
                        Data Diri Calon Siswa
                    </h3>
                    <p class="text-xs text-slate-400">Informasi identitas personal calon siswa baru</p>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-12 gap-4">
                <!-- Nama Lengkap -->
                <div class="md:col-span-6 sm:col-span-2">
                    <label class="block text-xs font-semibold text-slate-700 mb-1.5">
                        Nama Lengkap (Sesuai Ijazah/Akta) <span class="text-red-600">*</span>
                    </label>
                    <input type="text" name="nama_lengkap" value="{{ old('nama_lengkap', $pendaftar->nama_lengkap) }}"
                        required
                        class="w-full px-4 py-2.5 rounded-xl bg-[#F1F5F9] border-0 text-xs text-slate-800 focus:bg-white focus:ring-2 focus:ring-slate-300 focus:outline-none transition-all" />
                </div>

                <!-- Nama Panggilan -->
                <div class="md:col-span-3 sm:col-span-1">
                    <label class="block text-xs font-semibold text-slate-700 mb-1.5">
                        Nama Panggilan <span class="text-red-600">*</span>
                    </label>
                    <input type="text" name="nama_panggilan"
                        value="{{ old('nama_panggilan', $pendaftar->nama_panggilan) }}" required
                        class="w-full px-4 py-2.5 rounded-xl bg-[#F1F5F9] border-0 text-xs text-slate-800 focus:bg-white focus:ring-2 focus:ring-slate-300 focus:outline-none transition-all" />
                </div>

                <!-- Jenis Kelamin -->
                <div class="md:col-span-3 sm:col-span-1">
                    <label class="block text-xs font-semibold text-slate-700 mb-1.5">
                        Jenis Kelamin <span class="text-red-600">*</span>
                    </label>
                    <select name="jenis_kelamin" required
                        class="w-full px-4 py-2.5 rounded-xl bg-[#F1F5F9] border-0 text-xs text-slate-800 focus:bg-white focus:ring-2 focus:ring-slate-300 focus:outline-none cursor-pointer transition-all">
                        <option value="L" {{ old('jenis_kelamin', $pendaftar->jenis_kelamin) === 'L' ? 'selected' : '' }}>
                            Laki-laki</option>
                        <option value="P" {{ old('jenis_kelamin', $pendaftar->jenis_kelamin) === 'P' ? 'selected' : '' }}>
                            Perempuan</option>
                    </select>
                </div>

                <!-- NISN -->
                <div class="md:col-span-3 sm:col-span-1">
                    <label class="block text-xs font-semibold text-slate-700 mb-1.5">
                        Nomor NISN Siswa
                    </label>
                    <input type="text" name="nisn" value="{{ old('nisn', $pendaftar->nisn) }}"
                        placeholder="Contoh: 0081298471"
                        class="w-full px-4 py-2.5 rounded-xl bg-[#F1F5F9] border-0 text-xs font-mono text-slate-800 focus:bg-white focus:ring-2 focus:ring-slate-300 focus:outline-none transition-all" />
                </div>

                <!-- Nomor KK -->
                <div class="md:col-span-3 sm:col-span-1">
                    <label class="block text-xs font-semibold text-slate-700 mb-1.5">
                        Nomor Kartu Keluarga (KK)
                    </label>
                    <input type="text" name="nomor_kk" value="{{ old('nomor_kk', $pendaftar->nomor_kk) }}"
                        placeholder="16 digit nomor KK"
                        class="w-full px-4 py-2.5 rounded-xl bg-[#F1F5F9] border-0 text-xs font-mono text-slate-800 focus:bg-white focus:ring-2 focus:ring-slate-300 focus:outline-none transition-all" />
                </div>

                <!-- Tempat Lahir -->
                <div class="md:col-span-3 sm:col-span-1">
                    <label class="block text-xs font-semibold text-slate-700 mb-1.5">
                        Tempat Lahir <span class="text-red-600">*</span>
                    </label>
                    <input type="text" name="tempat_lahir" value="{{ old('tempat_lahir', $pendaftar->tempat_lahir) }}"
                        required
                        class="w-full px-4 py-2.5 rounded-xl bg-[#F1F5F9] border-0 text-xs text-slate-800 focus:bg-white focus:ring-2 focus:ring-slate-300 focus:outline-none transition-all" />
                </div>

                <!-- Tanggal Lahir (Hari, Bulan, Tahun) -->
                <div class="md:col-span-3 sm:col-span-1">
                    <label class="block text-xs font-semibold text-slate-700 mb-1.5">
                        Tanggal Lahir (H / B / T) <span class="text-red-600">*</span>
                    </label>
                    <div class="grid grid-cols-3 gap-1.5">
                        <input type="text" name="tanggal_lahir_hari"
                            value="{{ old('tanggal_lahir_hari', $pendaftar->tanggal_lahir_hari) }}" placeholder="DD"
                            required
                            class="px-2 py-2.5 rounded-xl bg-[#F1F5F9] border-0 text-xs text-center font-mono text-slate-800 focus:bg-white focus:ring-2 focus:ring-slate-300 focus:outline-none transition-all" />
                        <input type="text" name="tanggal_lahir_bulan"
                            value="{{ old('tanggal_lahir_bulan', $pendaftar->tanggal_lahir_bulan) }}" placeholder="MM"
                            required
                            class="px-2 py-2.5 rounded-xl bg-[#F1F5F9] border-0 text-xs text-center font-mono text-slate-800 focus:bg-white focus:ring-2 focus:ring-slate-300 focus:outline-none transition-all" />
                        <input type="text" name="tanggal_lahir_tahun"
                            value="{{ old('tanggal_lahir_tahun', $pendaftar->tanggal_lahir_tahun) }}" placeholder="YYYY"
                            required
                            class="px-2 py-2.5 rounded-xl bg-[#F1F5F9] border-0 text-xs text-center font-mono text-slate-800 focus:bg-white focus:ring-2 focus:ring-slate-300 focus:outline-none transition-all" />
                    </div>
                </div>

                <!-- Alamat Lengkap -->
                <div class="col-span-full">
                    <label class="block text-xs font-semibold text-slate-700 mb-1.5">
                        Alamat Lengkap Tempat Tinggal
                    </label>
                    <textarea name="alamat_lengkap" rows="2"
                        class="w-full px-4 py-2.5 rounded-xl bg-[#F1F5F9] border-0 text-xs text-slate-800 focus:bg-white focus:ring-2 focus:ring-slate-300 focus:outline-none transition-all">{{ old('alamat_lengkap', $pendaftar->alamat_lengkap) }}</textarea>
                </div>
            </div>
        </div>

        <!-- SECTION 3: PILIHAN JURUSAN & ASAL SEKOLAH -->
        <div class="bg-white rounded-2xl border border-slate-200/80 p-5 sm:p-6 shadow-sm">
            <div class="flex items-center gap-2.5 pb-3 mb-4 border-b border-slate-100">
                <span class="w-7 h-7 rounded-xl bg-[#1E293B] text-white flex items-center justify-center font-bold text-xs shadow-xs">3</span>
                <div>
                    <h3 class="font-extrabold text-sm text-[#0F172A] tracking-tight">
                        Pilihan Program Jurusan & Asal Sekolah
                    </h3>
                    <p class="text-xs text-slate-400">Kompetensi keahlian dan riwayat pendidikan sebelumnya</p>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-12 gap-4">
                <!-- Jurusan Pilihan -->
                <div class="md:col-span-6 sm:col-span-2">
                    <label class="block text-xs font-semibold text-slate-700 mb-1.5">
                        Kompetensi Keahlian (Jurusan) <span class="text-red-600">*</span>
                    </label>
                    <select name="jurusan" required
                        class="w-full px-4 py-2.5 rounded-xl bg-[#F1F5F9] border-0 text-xs font-semibold text-slate-800 focus:bg-white focus:ring-2 focus:ring-slate-300 focus:outline-none cursor-pointer transition-all">
                        @foreach ($majors as $fullName => $shortName)
                            <option value="{{ $fullName }}" {{ old('jurusan', $pendaftar->jurusan) === $fullName ? 'selected' : '' }}>
                                [{{ $shortName }}] - {{ $fullName }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Asal Sekolah -->
                <div class="md:col-span-6 sm:col-span-2">
                    <label class="block text-xs font-semibold text-slate-700 mb-1.5">
                        Nama Asal Sekolah (SMP / MTs) <span class="text-red-600">*</span>
                    </label>
                    <input type="text" name="asal_sekolah" value="{{ old('asal_sekolah', $pendaftar->asal_sekolah) }}"
                        required
                        class="w-full px-4 py-2.5 rounded-xl bg-[#F1F5F9] border-0 text-xs text-slate-800 focus:bg-white focus:ring-2 focus:ring-slate-300 focus:outline-none transition-all" />
                </div>

                <!-- Jalur Seleksi -->
                <div class="md:col-span-4 sm:col-span-1">
                    <label class="block text-xs font-semibold text-slate-700 mb-1.5">
                        Jalur Seleksi Pendaftaran <span class="text-red-600">*</span>
                    </label>
                    <select name="jalur_seleksi" required
                        class="w-full px-4 py-2.5 rounded-xl bg-[#F1F5F9] border-0 text-xs text-slate-800 focus:bg-white focus:ring-2 focus:ring-slate-300 focus:outline-none cursor-pointer transition-all">
                        <option value="Reguler" {{ old('jalur_seleksi', $pendaftar->jalur_seleksi) === 'Reguler' ? 'selected' : '' }}>Reguler</option>
                        <option value="Prestasi Akademik" {{ old('jalur_seleksi', $pendaftar->jalur_seleksi) === 'Prestasi Akademik' ? 'selected' : '' }}>Prestasi Akademik</option>
                        <option value="Prestasi Non-Akademik" {{ old('jalur_seleksi', $pendaftar->jalur_seleksi) === 'Prestasi Non-Akademik' ? 'selected' : '' }}>Prestasi Non-Akademik</option>
                        <option value="Afirmasi / KETM" {{ old('jalur_seleksi', $pendaftar->jalur_seleksi) === 'Afirmasi / KETM' ? 'selected' : '' }}>Afirmasi / KETM</option>
                    </select>
                </div>

                <!-- Tipe Pendaftar -->
                <div class="md:col-span-4 sm:col-span-1">
                    <label class="block text-xs font-semibold text-slate-700 mb-1.5">
                        Tipe Pendaftar <span class="text-red-600">*</span>
                    </label>
                    <select name="tipe_pendaftar" required
                        class="w-full px-4 py-2.5 rounded-xl bg-[#F1F5F9] border-0 text-xs text-slate-800 focus:bg-white focus:ring-2 focus:ring-slate-300 focus:outline-none cursor-pointer transition-all">
                        <option value="Pendaftar Baru" {{ old('tipe_pendaftar', $pendaftar->tipe_pendaftar) === 'Pendaftar Baru' ? 'selected' : '' }}>Pendaftar Baru (Tingkat X)</option>
                        <option value="Pindahan" {{ old('tipe_pendaftar', $pendaftar->tipe_pendaftar) === 'Pindahan' ? 'selected' : '' }}>Siswa Pindahan</option>
                    </select>
                </div>

                <!-- Gelombang Pendaftaran -->
                <div class="md:col-span-4 sm:col-span-1">
                    <label class="block text-xs font-semibold text-slate-700 mb-1.5">
                        Gelombang Pendaftaran
                    </label>
                    <select name="gelombang_id"
                        class="w-full px-4 py-2.5 rounded-xl bg-[#F1F5F9] border-0 text-xs text-slate-800 focus:bg-white focus:ring-2 focus:ring-slate-300 focus:outline-none cursor-pointer transition-all">
                        <option value="">-- Tanpa Gelombang --</option>
                        @foreach($waves ?? [] as $w)
                            <option value="{{ $w->id }}" @selected(old('gelombang_id', $pendaftar->gelombang_id) == $w->id)>
                                {{ $w->nama }} ({{ $w->tahun_ajaran }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Kelas Pilihan -->
                <div class="md:col-span-4 sm:col-span-2">
                    <label class="block text-xs font-semibold text-slate-700 mb-1.5">
                        Kelas Pilihan <span class="text-red-600">*</span>
                    </label>
                    <input type="text" name="kelas_pilihan"
                        value="{{ old('kelas_pilihan', $pendaftar->kelas_pilihan ?? 'Kelas 10') }}" required
                        class="w-full px-4 py-2.5 rounded-xl bg-[#F1F5F9] border-0 text-xs text-slate-800 focus:bg-white focus:ring-2 focus:ring-slate-300 focus:outline-none transition-all" />
                </div>
            </div>
        </div>

        <!-- SECTION 4: KONTAK SISWA & ORANG TUA -->
        <div class="bg-white rounded-2xl border border-slate-200/80 p-5 sm:p-6 shadow-sm">
            <div class="flex items-center gap-2.5 pb-3 mb-4 border-b border-slate-100">
                <span class="w-7 h-7 rounded-xl bg-[#1E293B] text-white flex items-center justify-center font-bold text-xs shadow-xs">4</span>
                <div>
                    <h3 class="font-extrabold text-sm text-[#0F172A] tracking-tight">
                        Nomor Kontak WhatsApp & Komunikasi
                    </h3>
                    <p class="text-xs text-slate-400">Kontak utama untuk notifikasi dan pengumuman seleksi</p>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <!-- Kontak Siswa -->
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1.5">
                        Nomor WhatsApp Siswa <span class="text-red-600">*</span>
                    </label>
                    <input type="text" name="nomor_kontak_pendaftar"
                        value="{{ old('nomor_kontak_pendaftar', $pendaftar->nomor_kontak_pendaftar) }}" required
                        class="w-full px-4 py-2.5 rounded-xl bg-[#F1F5F9] border-0 text-xs font-mono text-slate-800 focus:bg-white focus:ring-2 focus:ring-slate-300 focus:outline-none transition-all" />
                </div>

                <!-- Kontak Ortu -->
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1.5">
                        Nomor WhatsApp Orang Tua / Wali <span class="text-red-600">*</span>
                    </label>
                    <input type="text" name="nomor_kontak_ortu"
                        value="{{ old('nomor_kontak_ortu', $pendaftar->nomor_kontak_ortu) }}" required
                        class="w-full px-4 py-2.5 rounded-xl bg-[#F1F5F9] border-0 text-xs font-mono text-slate-800 focus:bg-white focus:ring-2 focus:ring-slate-300 focus:outline-none transition-all" />
                </div>

                <!-- Email -->
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1.5">
                        Alamat Email Siswa
                    </label>
                    <input type="email" name="email" value="{{ old('email', $pendaftar->email) }}"
                        placeholder="contoh@gmail.com"
                        class="w-full px-4 py-2.5 rounded-xl bg-[#F1F5F9] border-0 text-xs text-slate-800 focus:bg-white focus:ring-2 focus:ring-slate-300 focus:outline-none transition-all" />
                </div>
            </div>
        </div>

        <!-- SECTION 5: INFORMASI TAMBAHAN & LAYANAN -->
        <div class="bg-white rounded-2xl border border-slate-200/80 p-5 sm:p-6 shadow-sm">
            <div class="flex items-center gap-2.5 pb-3 mb-4 border-b border-slate-100">
                <span class="w-7 h-7 rounded-xl bg-[#1E293B] text-white flex items-center justify-center font-bold text-xs shadow-xs">5</span>
                <div>
                    <h3 class="font-extrabold text-sm text-[#0F172A] tracking-tight">
                        Informasi Seragam & Layanan Pendukung
                    </h3>
                    <p class="text-xs text-slate-400">Ukuran pakaian seragam dan preferensi pendaftar</p>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-12 gap-4">
                <!-- Ukuran Seragam -->
                <div class="md:col-span-4">
                    <label class="block text-xs font-semibold text-slate-700 mb-1.5">
                        Ukuran Seragam Siswa
                    </label>
                    <select name="ukuran_seragam"
                        class="w-full px-4 py-2.5 rounded-xl bg-[#F1F5F9] border-0 text-xs text-slate-800 focus:bg-white focus:ring-2 focus:ring-slate-300 focus:outline-none cursor-pointer transition-all">
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
                    <label class="block text-xs font-semibold text-slate-700 mb-1.5">
                        Alasan Memilih SMK Plus Pelita Nusantara
                    </label>
                    <input type="text" name="alasan_minat" value="{{ old('alasan_minat', $pendaftar->alasan_minat) }}"
                        placeholder="Contoh: Gedung dan Fasilitas Lengkap, Program Magang Industri..."
                        class="w-full px-4 py-2.5 rounded-xl bg-[#F1F5F9] border-0 text-xs text-slate-800 focus:bg-white focus:ring-2 focus:ring-slate-300 focus:outline-none transition-all" />
                </div>
            </div>
        </div>

        <!-- ACTION BAR (STICKY BOTTOM) -->
        <div
            class="bg-white/95 backdrop-blur-md rounded-2xl border border-slate-200/80 p-4 flex flex-col sm:flex-row items-center justify-between gap-3 sticky bottom-4 shadow-xl z-20">
            <div class="flex items-center gap-2">
                <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                <span class="text-xs text-slate-500">
                    Perubahan data pendaftar akan langsung tersimpan di basis data PPDB.
                </span>
            </div>

            <div class="flex items-center gap-2.5 w-full sm:w-auto">
                <a href="{{ route('ppdb.dashboard.pendaftar') }}"
                    class="flex-1 sm:flex-none px-5 py-2.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-semibold text-center transition-colors">
                    Batal
                </a>

                <button type="submit"
                    class="flex-1 sm:flex-none px-7 py-2.5 rounded-xl bg-[#8B1D24] hover:bg-[#72151B] text-white text-xs font-bold uppercase tracking-wider text-center transition-all cursor-pointer shadow-sm">
                    Simpan Perubahan
                </button>
            </div>
        </div>

    </form>

    <!-- DANGER ZONE: HAPUS PENDAFTAR -->
    <div class="bg-red-50/40 rounded-2xl border border-red-200/80 p-5 sm:p-6 shadow-sm">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h4 class="font-extrabold text-sm text-red-700 tracking-tight">
                    Hapus Data Calon Siswa Ini
                </h4>
                <p class="text-xs text-slate-500 mt-1">
                    Data pendaftar dengan nomor registrasi <span
                        class="font-mono font-bold text-slate-800">{{ $pendaftar->nomor_registrasi }}</span> akan dihapus permanen dari sistem PPDB.
                </p>
            </div>
            <form action="{{ route('ppdb.dashboard.pendaftar.destroy', $pendaftar->id) }}" method="POST"
                onsubmit="return confirm('Peringatan: Apakah Anda yakin ingin menghapus data calon siswa {{ $pendaftar->nama_lengkap }} ({{ $pendaftar->nomor_registrasi }}) secara permanen?');">
                @csrf
                @method('DELETE')
                <button type="submit"
                    class="px-5 py-2.5 rounded-xl bg-red-600 hover:bg-red-700 text-white text-xs font-semibold transition-colors cursor-pointer shadow-xs">
                    Hapus Pendaftar
                </button>
            </form>
        </div>
    </div>

</div>
@endsection