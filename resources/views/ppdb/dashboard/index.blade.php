@extends('layouts.admin')

@section('title', 'Dashboard Utama PPDB - SMK Plus Pelita Nusantara')
@section('page_title', 'Dashboard PPDB')

@section('content')
<div class="space-y-3">

    <!-- SUB-NAVIGATION BAR (Matches pill tabs & CTA in reference image, with NO CARD & NO BORDER RADIUS) -->
    <div class="bg-white border border-brand-ink/15 p-2 sm:p-2.5 flex flex-col md:flex-row md:items-center justify-between gap-2.5">
        <!-- Sub-tabs -->
        <div class="flex items-center flex-wrap gap-1">
            <a href="{{ route('ppdb.dashboard') }}" 
               class="px-4 py-1.5 text-xs font-bold uppercase tracking-wider bg-brand-darkred text-white border border-brand-darkred transition-colors">
                Overview
            </a>
            <a href="{{ route('ppdb.dashboard.pendaftar') }}" 
               class="px-4 py-1.5 text-xs font-bold uppercase tracking-wider text-brand-ink/75 hover:text-brand-ink hover:bg-black/5 border border-transparent transition-colors">
                Data Pendaftar
            </a>
            <a href="#jurusan-section" 
               class="px-4 py-1.5 text-xs font-bold uppercase tracking-wider text-brand-ink/75 hover:text-brand-ink hover:bg-black/5 border border-transparent transition-colors">
                Sebaran Jurusan
            </a>
            <a href="{{ route('ppdb.cek-status') }}" target="_blank"
               class="px-4 py-1.5 text-xs font-bold uppercase tracking-wider text-brand-ink/75 hover:text-brand-ink hover:bg-black/5 border border-transparent transition-colors">
                Cek NISN
            </a>
        </div>

        <!-- Action CTAs (Matches "Suggest a Cause" CTA button in reference) -->
        <div class="flex items-center gap-2">
            <button 
                type="button" 
                onclick="window.print()" 
                class="px-3.5 py-1.5 bg-white hover:bg-black/5 text-brand-ink border border-brand-ink/20 text-xs font-bold transition-all flex items-center gap-1.5 cursor-pointer">
                <svg class="w-3.5 h-3.5 text-brand-darkred" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
                </svg>
                <span>Cetak Rekap</span>
            </button>

            <a href="{{ route('ppdb.index') }}" target="_blank"
               class="px-4 py-1.5 bg-brand-darkred hover:bg-brand-deepred text-white text-xs font-bold uppercase tracking-wider transition-all flex items-center gap-1.5 shadow-none border border-brand-darkred">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"></path>
                </svg>
                <span>Tambah Pendaftar</span>
            </a>
        </div>
    </div>

    <!-- 4 METRIC COUNTERS ROW (Connected Border Grid - NO CARD DESIGN, NO RADIUS, ZERO GAP) -->
    <div class="bg-white border border-brand-ink/15 grid grid-cols-2 lg:grid-cols-4 divide-y sm:divide-y-0 sm:divide-x divide-brand-ink/15">
        <!-- 1. Total Pendaftar -->
        <div class="p-4 sm:p-5 flex flex-col justify-between hover:bg-black/[0.015] transition-colors">
            <div class="flex items-center justify-between mb-3">
                <div class="w-9 h-9 bg-brand-darkred/10 border border-brand-darkred/20 text-brand-darkred flex items-center justify-center font-bold">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                </div>
                <span class="px-2 py-0.5 text-[10px] font-bold uppercase tracking-wider bg-emerald-100 text-emerald-800 border border-emerald-300">
                    +{{ $todayCount }} Hari Ini
                </span>
            </div>
            <div>
                <span class="text-[11px] font-bold uppercase tracking-wider text-brand-ink/60 block">
                    Total Pendaftar
                </span>
                <div class="text-3xl sm:text-4xl font-display font-bold text-brand-ink leading-tight mt-0.5">
                    {{ $totalPendaftar }}
                </div>
                <div class="text-[11px] text-brand-ink/50 mt-1">Calon siswa T.A 2027/2028</div>
            </div>
        </div>

        <!-- 2. Terverifikasi -->
        <div class="p-4 sm:p-5 flex flex-col justify-between hover:bg-black/[0.015] transition-colors">
            <div class="flex items-center justify-between mb-3">
                <div class="w-9 h-9 bg-emerald-50 border border-emerald-300 text-emerald-700 flex items-center justify-center font-bold">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <span class="px-2 py-0.5 text-[10px] font-bold uppercase tracking-wider bg-emerald-50 text-emerald-700 border border-emerald-200">
                    {{ $totalPendaftar > 0 ? round(($totalTerverifikasi / $totalPendaftar) * 100) : 0 }}% Lolos
                </span>
            </div>
            <div>
                <span class="text-[11px] font-bold uppercase tracking-wider text-brand-ink/60 block">
                    Berkas Terverifikasi
                </span>
                <div class="text-3xl sm:text-4xl font-display font-bold text-emerald-600 leading-tight mt-0.5">
                    {{ $totalTerverifikasi }}
                </div>
                <div class="text-[11px] text-emerald-700/80 mt-1">Dokumen lengkap & valid</div>
            </div>
        </div>

        <!-- 3. Menunggu Verifikasi -->
        <div class="p-4 sm:p-5 flex flex-col justify-between hover:bg-black/[0.015] transition-colors">
            <div class="flex items-center justify-between mb-3">
                <div class="w-9 h-9 bg-amber-50 border border-amber-300 text-amber-700 flex items-center justify-center font-bold">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <span class="px-2 py-0.5 text-[10px] font-bold uppercase tracking-wider bg-amber-100 text-amber-900 border border-amber-300">
                    Perlu Tindakan
                </span>
            </div>
            <div>
                <span class="text-[11px] font-bold uppercase tracking-wider text-brand-ink/60 block">
                    Menunggu Verifikasi
                </span>
                <div class="text-3xl sm:text-4xl font-display font-bold text-amber-500 leading-tight mt-0.5">
                    {{ $totalMenunggu }}
                </div>
                <div class="text-[11px] text-amber-800/80 mt-1">Perlu review panitia PPDB</div>
            </div>
        </div>

        <!-- 4. Lulus Seleksi -->
        <div class="p-4 sm:p-5 flex flex-col justify-between hover:bg-black/[0.015] transition-colors">
            <div class="flex items-center justify-between mb-3">
                <div class="w-9 h-9 bg-blue-50 border border-blue-300 text-blue-700 flex items-center justify-center font-bold">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"></path>
                    </svg>
                </div>
                <span class="px-2 py-0.5 text-[10px] font-bold uppercase tracking-wider bg-blue-100 text-blue-900 border border-blue-300">
                    Daftar Ulang
                </span>
            </div>
            <div>
                <span class="text-[11px] font-bold uppercase tracking-wider text-brand-ink/60 block">
                    Lulus Seleksi
                </span>
                <div class="text-3xl sm:text-4xl font-display font-bold text-blue-600 leading-tight mt-0.5">
                    {{ $totalLulus }}
                </div>
                <div class="text-[11px] text-blue-800/80 mt-1">Siap proses daftar ulang</div>
            </div>
        </div>
    </div>

    <!-- QUICK SEARCH & FILTER BAR (Matches search & filter bar in reference) -->
    <div class="bg-white border border-brand-ink/15 p-2 sm:p-3">
        <form action="{{ route('ppdb.dashboard.pendaftar') }}" method="GET" class="flex flex-col sm:flex-row gap-2">
            <div class="flex-1 relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-brand-ink/40">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </div>
                <input 
                    type="text" 
                    name="search" 
                    placeholder="Cari nama calon siswa, no registrasi, NISN, atau asal sekolah..."
                    class="w-full pl-9 pr-3 py-2 border border-brand-ink/20 text-xs focus:outline-none focus:border-brand-darkred bg-white"
                />
            </div>

            <select 
                name="jurusan" 
                class="px-3 py-2 border border-brand-ink/20 text-xs focus:outline-none focus:border-brand-darkred bg-white">
                <option value="">Semua Jurusan</option>
                @foreach ($majors as $fullName => $shortName)
                    <option value="{{ $fullName }}">{{ $shortName }}</option>
                @endforeach
            </select>

            <select 
                name="status" 
                class="px-3 py-2 border border-brand-ink/20 text-xs focus:outline-none focus:border-brand-darkred bg-white">
                <option value="">Semua Status</option>
                <option value="menunggu_verifikasi">Menunggu</option>
                <option value="terverifikasi">Terverifikasi</option>
                <option value="lulus_seleksi">Lulus</option>
            </select>

            <button type="submit" class="px-5 py-2 bg-brand-darkred hover:bg-brand-deepred text-white text-xs font-bold uppercase tracking-wider transition-colors cursor-pointer shrink-0">
                Filter Data
            </button>
        </form>
    </div>

    <!-- TWO COLUMN HIGH-DENSITY SECTION: RECENT APPLICANTS & MAJORS DISTRIBUTION -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-3">
        
        <!-- LEFT COLUMN (7 COLS): RECENT REGISTRATIONS TABLE -->
        <div class="lg:col-span-7 bg-white border border-brand-ink/15 flex flex-col">
            <!-- Table Header Strip -->
            <div class="px-4 py-3 border-b border-brand-ink/15 flex items-center justify-between bg-black/[0.02]">
                <div class="flex items-center gap-2">
                    <h2 class="font-display font-bold text-sm uppercase tracking-wider text-brand-ink">
                        Pendaftar Masuk Terakhir
                    </h2>
                </div>
                <a href="{{ route('ppdb.dashboard.pendaftar') }}" class="text-[11px] font-bold text-brand-darkred hover:underline flex items-center gap-1">
                    <span>Lihat Semua ({{ $totalPendaftar }})</span>
                    <span>&rarr;</span>
                </a>
            </div>

            <!-- Table Container (NO CARD, FLAT STRUCTURED) -->
            <div class="overflow-x-auto flex-1">
                <table class="w-full text-left text-xs border-collapse">
                    <thead>
                        <tr class="bg-black/[0.03] border-b border-brand-ink/15 text-[10px] font-bold uppercase tracking-wider text-brand-ink/60">
                            <th class="py-2.5 px-3">No Reg</th>
                            <th class="py-2.5 px-3">Nama Siswa</th>
                            <th class="py-2.5 px-3">Jurusan</th>
                            <th class="py-2.5 px-3">Status</th>
                            <th class="py-2.5 px-3 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-brand-ink/10">
                        @forelse ($recentPendaftar as $student)
                            @php
                                $badge = $student->status_badge;
                            @endphp
                            <tr class="hover:bg-brand-darkred/[0.02] transition-colors">
                                <td class="py-2.5 px-3 font-mono font-bold text-brand-darkred whitespace-nowrap text-[11px]">
                                    {{ $student->nomor_registrasi }}
                                </td>
                                <td class="py-2.5 px-3 whitespace-nowrap">
                                    <div class="font-bold text-brand-ink">{{ $student->nama_lengkap }}</div>
                                    <div class="text-[10px] text-brand-ink/50 truncate max-w-[160px]">{{ $student->asal_sekolah }}</div>
                                </td>
                                <td class="py-2.5 px-3 whitespace-nowrap">
                                    <span class="font-semibold text-brand-ink/80 text-[11px]">
                                        {{ $majors[$student->jurusan] ?? $student->jurusan }}
                                    </span>
                                </td>
                                <td class="py-2.5 px-3 whitespace-nowrap">
                                    <span class="px-2 py-0.5 text-[10px] font-bold border {{ $badge['bg'] }}">
                                        {{ $badge['label'] }}
                                    </span>
                                </td>
                                <td class="py-2.5 px-3 text-right whitespace-nowrap">
                                    <a href="{{ route('ppdb.dashboard.pendaftar.detail', $student->id) }}" 
                                       class="px-2.5 py-1 text-[11px] font-bold bg-white hover:bg-brand-darkred hover:text-white text-brand-ink border border-brand-ink/20 transition-all inline-block">
                                        Detail / Edit
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="py-8 text-center text-xs text-brand-ink/50">
                                    Belum ada data pendaftar yang masuk.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Table Footer -->
            <div class="p-3 border-t border-brand-ink/15 bg-black/[0.015] flex items-center justify-between text-[11px]">
                <span class="text-brand-ink/60">Menampilkan 7 pendaftar terbaru</span>
                <a href="{{ route('ppdb.dashboard.pendaftar') }}" class="font-bold text-brand-darkred hover:underline">
                    Buka Halaman List Pendaftar (Pagination) &rarr;
                </a>
            </div>
        </div>

        <!-- RIGHT COLUMN (5 COLS): DISTRIBUSI KUOTA PER JURUSAN -->
        <div id="jurusan-section" class="lg:col-span-5 bg-white border border-brand-ink/15 flex flex-col">
            <!-- Header Strip -->
            <div class="px-4 py-3 border-b border-brand-ink/15 flex items-center justify-between bg-black/[0.02]">
                <div class="flex items-center gap-2">
                    <h2 class="font-display font-bold text-sm uppercase tracking-wider text-brand-ink">
                        Distribusi 5 Jurusan
                    </h2>
                </div>
                <span class="text-[11px] font-mono text-brand-ink/60 font-bold">72 Kuota/Kelas</span>
            </div>

            <!-- List of Majors -->
            <div class="p-3 divide-y divide-brand-ink/10 flex-1">
                @foreach ($majors as $fullName => $shortName)
                    @php
                        $count = $jurusanCounts[$fullName] ?? 0;
                        $percent = $totalPendaftar > 0 ? round(($count / $totalPendaftar) * 100) : 0;
                        $quotaPercent = min(100, round(($count / 72) * 100));
                    @endphp
                    <div class="py-2.5 first:pt-1 last:pb-1">
                        <div class="flex items-center justify-between mb-1">
                            <div class="flex items-center gap-2">
                                <span class="font-bold text-xs text-brand-ink">{{ $shortName }}</span>
                                <span class="text-[10px] text-brand-ink/50 truncate max-w-[150px]" title="{{ $fullName }}">
                                    {{ $fullName }}
                                </span>
                            </div>
                            <div class="text-right">
                                <span class="font-mono font-bold text-xs text-brand-darkred">{{ $count }}</span>
                                <span class="text-[10px] text-brand-ink/40">/ 72</span>
                            </div>
                        </div>

                        <!-- Sharp Progress Bar (No Radius!) -->
                        <div class="w-full bg-black/10 h-1.5 overflow-hidden">
                            <div class="bg-brand-darkred h-full transition-all duration-300" style="width: {{ $quotaPercent }}%"></div>
                        </div>

                        <div class="flex items-center justify-between text-[10px] text-brand-ink/50 mt-1 font-mono">
                            <span>{{ $percent }}% dari total pendaftar</span>
                            <span>{{ $quotaPercent }}% kuota terpenuhi</span>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Footer Action -->
            <div class="p-3 border-t border-brand-ink/15 bg-black/[0.015]">
                <a href="{{ route('ppdb.akomodasi') }}" target="_blank" class="text-xs font-bold text-brand-darkred hover:underline flex items-center justify-between">
                    <span>Lihat Informasi Rincian Biaya & Program Jurusan</span>
                    <span>&rarr;</span>
                </a>
            </div>
        </div>

    </div>

    <!-- QUICK ADMIN GUIDE STRIP (NO CARD, CRISP BORDERED) -->
    <div class="bg-white border border-brand-ink/15 p-4">
        <div class="flex items-center justify-between pb-2 mb-3 border-b border-brand-ink/10">
            <span class="text-[11px] font-bold uppercase tracking-wider text-brand-ink/60">
                Alur Kerja Panitia PPDB SMK Plus Pelita Nusantara
            </span>
            <span class="text-[10px] font-mono text-brand-ink/40">SOP 2027</span>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-3 divide-y md:divide-y-0 md:divide-x divide-brand-ink/15">
            <div class="p-2 md:px-4 first:pl-0">
                <div class="flex items-center gap-2 mb-1">
                    <span class="w-5 h-5 bg-brand-darkred text-white flex items-center justify-center font-bold text-[10px]">1</span>
                    <span class="font-bold text-xs text-brand-ink">Verifikasi Dokumen</span>
                </div>
                <p class="text-[11px] text-brand-ink/70">
                    Buka menu Pendaftar Siswa, teliti NISN, KK, asal sekolah, dan nomor kontak pendaftar & wali.
                </p>
            </div>
            <div class="p-2 md:px-4">
                <div class="flex items-center gap-2 mb-1">
                    <span class="w-5 h-5 bg-brand-darkred text-white flex items-center justify-center font-bold text-[10px]">2</span>
                    <span class="font-bold text-xs text-brand-ink">Update Status & Catatan</span>
                </div>
                <p class="text-[11px] text-brand-ink/70">
                    Klik "Detail/Edit", ubah status menjadi "Terverifikasi" atau "Lulus Seleksi", lalu simpan perubahan.
                </p>
            </div>
            <div class="p-2 md:px-4 last:pr-0">
                <div class="flex items-center gap-2 mb-1">
                    <span class="w-5 h-5 bg-brand-darkred text-white flex items-center justify-center font-bold text-[10px]">3</span>
                    <span class="font-bold text-xs text-brand-ink">Cetak & Notifikasi Siswa</span>
                </div>
                <p class="text-[11px] text-brand-ink/70">
                    Hubungi calon siswa via WhatsApp langsung melalui link kontak yang tersedia untuk daftar ulang.
                </p>
            </div>
        </div>
    </div>

</div>
@endsection
