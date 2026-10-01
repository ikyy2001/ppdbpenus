@extends('layouts.admin')

@section('title', 'Dashboard Utama PPDB - SMK Plus Pelita Nusantara')
@section('page_title', 'Dashboard PPDB')

@section('content')
<div class="space-y-5">

    <!-- SUB-NAVIGATION BAR & ACTION CTAS -->
    <div class="bg-white rounded-2xl border border-slate-200/80 p-3 sm:p-3.5 shadow-sm flex flex-col md:flex-row md:items-center justify-between gap-3">
        <!-- Sub-tabs -->
        <div class="flex items-center flex-wrap gap-1.5">
            <a href="{{ route('ppdb.dashboard') }}" 
               class="px-3.5 py-2 rounded-xl text-xs font-semibold bg-[#1E293B] text-white shadow-sm transition-all">
                Overview
            </a>
            <a href="{{ route('ppdb.dashboard.pendaftar') }}" 
               class="px-3.5 py-2 rounded-xl text-xs font-semibold text-slate-600 hover:text-slate-900 hover:bg-slate-100 transition-colors">
                Data Pendaftar
            </a>
            <a href="{{ route('ppdb.dashboard.gelombang') }}" 
               class="px-3.5 py-2 rounded-xl text-xs font-semibold text-slate-600 hover:text-slate-900 hover:bg-slate-100 transition-colors">
                Gelombang PPDB
            </a>
            <a href="{{ route('ppdb.dashboard.pengumuman') }}" 
               class="px-3.5 py-2 rounded-xl text-xs font-semibold text-slate-600 hover:text-slate-900 hover:bg-slate-100 transition-colors">
                Pengumuman
            </a>
            <a href="{{ route('ppdb.dashboard.akomodasi') }}" 
               class="px-3.5 py-2 rounded-xl text-xs font-semibold text-slate-600 hover:text-slate-900 hover:bg-slate-100 transition-colors">
                Biaya & Akomodasi
            </a>
            <a href="{{ route('ppdb.cek-status') }}" target="_blank" 
               class="px-3.5 py-2 rounded-xl text-xs font-semibold text-slate-500 hover:text-slate-900 hover:bg-slate-100 transition-colors">
                Cek NISN ↗
            </a>
        </div>

        <!-- Action CTAs -->
        <div class="flex items-center gap-2.5">
            <button 
                type="button" 
                onclick="window.print()" 
                class="px-4 py-2 rounded-xl bg-white hover:bg-slate-50 text-slate-700 border border-slate-200 text-xs font-semibold transition-all shadow-xs flex items-center gap-1.5 cursor-pointer">
                <svg class="w-3.5 h-3.5 text-[#8B1D24]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
                </svg>
                <span>Cetak Rekap</span>
            </button>

            <a href="{{ route('ppdb.index') }}" target="_blank"
               class="px-4 py-2 rounded-xl bg-[#8B1D24] hover:bg-[#72151B] text-white text-xs font-semibold transition-all flex items-center gap-1.5 shadow-sm">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"></path>
                </svg>
                <span>Tambah Pendaftar</span>
            </a>
        </div>
    </div>

    <!-- WELCOME HERO BANNER (Matches Hero Greeting & Progress Box in Reference Image) -->
    <div class="bg-white rounded-2xl border border-slate-200/80 p-6 sm:p-7 shadow-sm relative overflow-hidden">
        <!-- Subtle decorative glow -->
        <div class="absolute -right-16 -top-16 w-64 h-64 bg-red-50/60 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute -left-16 -bottom-16 w-64 h-64 bg-slate-100/60 rounded-full blur-3xl pointer-events-none"></div>

        <div class="relative z-10 flex flex-col lg:flex-row lg:items-center justify-between gap-6">
            <!-- Left Info & Greeting -->
            <div class="max-w-2xl">
                <div class="flex items-center gap-2.5 mb-2.5">
                    <span class="px-3 py-1 rounded-full bg-[#1E293B] text-white text-xs font-semibold shadow-xs">
                        Panitia PPDB 2027
                    </span>
                    <span class="text-xs font-medium text-slate-500">
                        SMK Plus Pelita Nusantara Bogor
                    </span>
                </div>
                <h1 class="text-2xl sm:text-3xl font-extrabold text-[#0F172A] tracking-tight">
                    Halo, Panitia PPDB 👋
                </h1>
                <p class="text-xs sm:text-sm text-[#64748B] mt-2 leading-relaxed">
                    Selamat datang di panel kontrol PPDB. Anda memiliki <span class="font-bold text-[#8B1D24]">{{ $totalMenunggu }} berkas pendaftar</span> yang menunggu konfirmasi verifikasi dokumen dan kelulusan.
                </p>
            </div>

            <!-- Right Progress Widget (Matches Kelengkapan Profil card in Reference Image) -->
            <div class="bg-slate-50/90 border border-slate-200/70 rounded-2xl p-4 sm:p-5 sm:min-w-[280px] shadow-2xs flex flex-col justify-between">
                <div class="flex items-center justify-between gap-3 mb-2">
                    <span class="text-xs font-semibold text-slate-700">Rasio Verifikasi Berkas</span>
                    <span class="text-sm font-extrabold text-[#0F172A]">
                        {{ $totalPendaftar > 0 ? round(($totalTerverifikasi / $totalPendaftar) * 100) : 0 }}%
                    </span>
                </div>
                
                <!-- Thin Maroon Progress Bar with Rounded Ends -->
                <div class="w-full bg-slate-200/80 h-2 rounded-full overflow-hidden my-1">
                    <div class="bg-[#8B1D24] h-full rounded-full transition-all duration-500" 
                         style="width: {{ $totalPendaftar > 0 ? min(100, round(($totalTerverifikasi / $totalPendaftar) * 100)) : 0 }}%">
                    </div>
                </div>

                <div class="mt-2.5 pt-2 border-t border-slate-200/60 flex items-center justify-between">
                    <a href="{{ route('ppdb.dashboard.pendaftar') }}" class="text-xs font-bold text-[#8B1D24] hover:text-[#72151B] inline-flex items-center gap-1 transition-colors">
                        <span>Kelola pendaftar sekarang</span>
                        <span>&rarr;</span>
                    </a>
                    <span class="text-[10px] font-mono text-slate-400">{{ $totalTerverifikasi }}/{{ $totalPendaftar }}</span>
                </div>
            </div>
        </div>
    </div>

    <!-- 4 METRIC COUNTERS GRID (Directly extracting the design from the 4 cards in Reference Image) -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-5">
        
        <!-- 1. Total Pendaftar (Matches Card 1: Lamaran Aktif style with mini bar chart) -->
        <div class="bg-white rounded-2xl border border-slate-200/80 p-5 sm:p-6 shadow-sm hover:shadow-md transition-all flex flex-col justify-between">
            <div class="flex items-center justify-between mb-3">
                <span class="text-xs font-semibold uppercase tracking-wider text-slate-500">
                    Total Pendaftar
                </span>
                <div class="w-9 h-9 rounded-xl bg-slate-100 text-slate-700 flex items-center justify-center">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                </div>
            </div>
            
            <div class="my-2">
                <div class="text-3xl sm:text-4xl font-extrabold text-[#0F172A] tracking-tight">
                    {{ $totalPendaftar }}
                </div>
                <div class="text-xs text-slate-500 mt-1">Calon siswa T.A 2027/2028</div>
            </div>

            <!-- Bottom Row: Trend and Mini Bar Chart -->
            <div class="flex items-end justify-between pt-3 border-t border-slate-100">
                <span class="text-xs font-semibold text-emerald-600 flex items-center gap-1">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                    </svg>
                    <span>+{{ $todayCount }} hari ini</span>
                </span>
                
                <!-- Mini Bar Representation (Slate/Navy) -->
                <div class="flex items-end gap-1 h-6">
                    <span class="w-1.5 bg-slate-200 rounded-full h-2.5"></span>
                    <span class="w-1.5 bg-slate-300 rounded-full h-4"></span>
                    <span class="w-1.5 bg-slate-300 rounded-full h-3"></span>
                    <span class="w-1.5 bg-slate-400 rounded-full h-5"></span>
                    <span class="w-1.5 bg-[#1E293B] rounded-full h-6"></span>
                </div>
            </div>
        </div>

        <!-- 2. Menunggu Verifikasi (Matches Card 2: Panggilan Interview style with Maroon Accent) -->
        <div class="bg-white rounded-2xl border border-red-100 p-5 sm:p-6 shadow-sm hover:shadow-md transition-all flex flex-col justify-between">
            <div class="flex items-center justify-between mb-3">
                <span class="text-xs font-semibold uppercase tracking-wider text-[#8B1D24]">
                    Menunggu Verifikasi
                </span>
                <div class="w-9 h-9 rounded-xl bg-[#8B1D24] text-white flex items-center justify-center shadow-xs">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>

            <div class="my-2">
                <div class="text-3xl sm:text-4xl font-extrabold text-[#0F172A] tracking-tight">
                    {{ $totalMenunggu }}
                </div>
                <div class="text-xs text-slate-500 mt-1">Perlu review panitia PPDB</div>
            </div>

            <div class="flex items-center justify-between pt-3 border-t border-red-50">
                <span class="px-2.5 py-0.5 rounded-full text-[11px] font-semibold bg-amber-50 text-amber-800 border border-amber-200">
                    Perlu Tindakan
                </span>
                <a href="{{ route('ppdb.dashboard.pendaftar', ['status' => 'menunggu_verifikasi']) }}" 
                   class="text-xs font-bold text-[#8B1D24] hover:underline flex items-center gap-0.5">
                    <span>Review</span>
                    <span>&rarr;</span>
                </a>
            </div>
        </div>

        <!-- 3. Berkas Terverifikasi (Matches Card 3: CV Health Score style with Dual Progress Bar) -->
        <div class="bg-white rounded-2xl border border-slate-200/80 p-5 sm:p-6 shadow-sm hover:shadow-md transition-all flex flex-col justify-between">
            <div class="flex items-center justify-between mb-3">
                <span class="text-xs font-semibold uppercase tracking-wider text-slate-500">
                    Berkas Terverifikasi
                </span>
                <div class="w-9 h-9 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>

            <div class="my-2">
                <div class="flex items-baseline gap-1">
                    <span class="text-3xl sm:text-4xl font-extrabold text-[#0F172A] tracking-tight">
                        {{ $totalTerverifikasi }}
                    </span>
                    <span class="text-sm font-bold text-slate-400">/{{ $totalPendaftar }}</span>
                </div>
                <!-- Dual Color Progress Bar (Navy to Maroon) -->
                <div class="w-full bg-slate-100 h-1.5 rounded-full overflow-hidden mt-2">
                    <div class="bg-gradient-to-r from-[#1E293B] to-[#8B1D24] h-full rounded-full transition-all duration-300"
                         style="width: {{ $totalPendaftar > 0 ? round(($totalTerverifikasi / $totalPendaftar) * 100) : 0 }}%">
                    </div>
                </div>
            </div>

            <div class="flex items-center justify-between pt-3 border-t border-slate-100 text-xs text-slate-500">
                <span class="text-emerald-700 font-semibold flex items-center gap-1">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                    Dokumen Lengkap
                </span>
                <span class="font-mono font-bold text-slate-700">
                    {{ $totalPendaftar > 0 ? round(($totalTerverifikasi / $totalPendaftar) * 100) : 0 }}%
                </span>
            </div>
        </div>

        <!-- 4. Lulus Seleksi (Matches Card 4: Profil Dilihat Mitra style with Maroon Mini Chart) -->
        <div class="bg-white rounded-2xl border border-slate-200/80 p-5 sm:p-6 shadow-sm hover:shadow-md transition-all flex flex-col justify-between">
            <div class="flex items-center justify-between mb-3">
                <span class="text-xs font-semibold uppercase tracking-wider text-slate-500">
                    Lulus Seleksi
                </span>
                <div class="w-9 h-9 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138z"></path>
                    </svg>
                </div>
            </div>

            <div class="my-2">
                <div class="text-3xl sm:text-4xl font-extrabold text-[#0F172A] tracking-tight">
                    {{ $totalLulus }}
                </div>
                <div class="text-xs text-slate-500 mt-1">Siap proses daftar ulang</div>
            </div>

            <!-- Bottom Row: Pill badge and Maroon mini chart -->
            <div class="flex items-end justify-between pt-3 border-t border-slate-100">
                <span class="px-2.5 py-0.5 rounded-full text-[11px] font-semibold bg-blue-50 text-blue-700 border border-blue-200">
                    Daftar Ulang
                </span>

                <!-- Mini Bar Representation (Maroon Tones from Reference) -->
                <div class="flex items-end gap-1 h-6">
                    <span class="w-1.5 bg-red-200 rounded-full h-2"></span>
                    <span class="w-1.5 bg-red-300 rounded-full h-3"></span>
                    <span class="w-1.5 bg-red-400 rounded-full h-4"></span>
                    <span class="w-1.5 bg-[#8B1D24]/80 rounded-full h-5"></span>
                    <span class="w-1.5 bg-[#8B1D24] rounded-full h-6"></span>
                </div>
            </div>
        </div>

    </div>

    <!-- QUICK SEARCH & FILTER BAR -->
    <div class="bg-white rounded-2xl border border-slate-200/80 p-3 sm:p-4 shadow-sm">
        <form action="{{ route('ppdb.dashboard.pendaftar') }}" method="GET" class="flex flex-col sm:flex-row gap-2.5">
            <div class="flex-1 relative">
                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </div>
                <input 
                    type="text" 
                    name="search" 
                    placeholder="Cari nama calon siswa, no registrasi, NISN, atau asal sekolah..."
                    class="w-full pl-10 pr-4 py-2.5 rounded-xl bg-[#F1F5F9] border-0 text-xs text-slate-800 placeholder-slate-400 focus:bg-white focus:ring-2 focus:ring-slate-300 focus:outline-none transition-all"
                />
            </div>

            <select 
                name="jurusan" 
                class="px-4 py-2.5 rounded-xl bg-[#F1F5F9] border-0 text-xs text-slate-700 font-medium focus:bg-white focus:ring-2 focus:ring-slate-300 focus:outline-none cursor-pointer">
                <option value="">Semua Jurusan</option>
                @foreach ($majors as $fullName => $shortName)
                    <option value="{{ $fullName }}">{{ $shortName }}</option>
                @endforeach
            </select>

            <select 
                name="status" 
                class="px-4 py-2.5 rounded-xl bg-[#F1F5F9] border-0 text-xs text-slate-700 font-medium focus:bg-white focus:ring-2 focus:ring-slate-300 focus:outline-none cursor-pointer">
                <option value="">Semua Status</option>
                <option value="menunggu_verifikasi">Menunggu Verifikasi</option>
                <option value="terverifikasi">Terverifikasi</option>
                <option value="lulus_seleksi">Lulus Seleksi</option>
            </select>

            <button type="submit" class="px-5 py-2.5 rounded-xl bg-[#1E293B] hover:bg-slate-800 text-white text-xs font-semibold transition-all cursor-pointer shrink-0 shadow-sm">
                Filter Data
            </button>
        </form>
    </div>

    <!-- TWO COLUMN HIGH-DENSITY SECTION: RECENT APPLICANTS & MAJORS DISTRIBUTION -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-5">
        
        <!-- LEFT COLUMN (7 COLS): RECENT REGISTRATIONS TABLE -->
        <div class="lg:col-span-7 bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden flex flex-col">
            <!-- Table Header Strip -->
            <div class="px-5 py-4 border-b border-slate-100 flex items-center justify-between">
                <div>
                    <h2 class="font-extrabold text-base text-[#0F172A] tracking-tight">
                        Pendaftar Masuk Terakhir
                    </h2>
                    <p class="text-xs text-slate-500 mt-0.5">Calon siswa terbaru yang mendaftar online</p>
                </div>
                <a href="{{ route('ppdb.dashboard.pendaftar') }}" class="text-xs font-bold text-[#8B1D24] hover:text-[#72151B] flex items-center gap-1 transition-colors">
                    <span>Lihat Semua ({{ $totalPendaftar }})</span>
                    <span>&rarr;</span>
                </a>
            </div>

            <!-- Table Container -->
            <div class="overflow-x-auto flex-1">
                <table class="w-full text-left text-xs border-collapse">
                    <thead>
                        <tr class="bg-slate-50/80 border-b border-slate-100 text-[11px] font-semibold uppercase tracking-wider text-slate-500">
                            <th class="py-3 px-4">No Reg</th>
                            <th class="py-3 px-4">Nama Siswa</th>
                            <th class="py-3 px-4">Jurusan</th>
                            <th class="py-3 px-4">Status</th>
                            <th class="py-3 px-4 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse ($recentPendaftar as $student)
                            @php
                                $badge = $student->status_badge;
                            @endphp
                            <tr class="hover:bg-slate-50/60 transition-colors">
                                <td class="py-3 px-4 whitespace-nowrap">
                                    <span class="font-mono font-bold text-xs text-[#8B1D24] bg-red-50/70 px-2.5 py-0.5 rounded-lg inline-block">
                                        {{ $student->nomor_registrasi }}
                                    </span>
                                </td>
                                <td class="py-3 px-4 whitespace-nowrap">
                                    <div class="font-bold text-slate-900 text-xs">{{ $student->nama_lengkap }}</div>
                                    <div class="text-[11px] text-slate-400 truncate max-w-[170px]">{{ $student->asal_sekolah }}</div>
                                </td>
                                <td class="py-3 px-4 whitespace-nowrap">
                                    <span class="font-semibold text-slate-700 text-xs">
                                        {{ $majors[$student->jurusan] ?? $student->jurusan }}
                                    </span>
                                </td>
                                <td class="py-3 px-4 whitespace-nowrap">
                                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-semibold border {{ $badge['bg'] }}">
                                        {{ $badge['label'] }}
                                    </span>
                                </td>
                                <td class="py-3 px-4 text-right whitespace-nowrap">
                                    <a href="{{ route('ppdb.dashboard.pendaftar.detail', $student->id) }}" 
                                       class="px-3 py-1.5 rounded-xl text-xs font-semibold bg-slate-100 hover:bg-[#1E293B] hover:text-white text-slate-700 transition-colors inline-block">
                                        Detail / Edit
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="py-8 text-center text-xs text-slate-400">
                                    Belum ada data pendaftar yang masuk.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Table Footer -->
            <div class="p-4 border-t border-slate-100 bg-slate-50/50 flex items-center justify-between text-xs">
                <span class="text-slate-500">Menampilkan 7 pendaftar terbaru</span>
                <a href="{{ route('ppdb.dashboard.pendaftar') }}" class="font-bold text-[#8B1D24] hover:underline flex items-center gap-1">
                    <span>Buka Semua Pendaftar</span>
                    <span>&rarr;</span>
                </a>
            </div>
        </div>

        <!-- RIGHT COLUMN (5 COLS): DISTRIBUSI KUOTA PER JURUSAN -->
        <div id="jurusan-section" class="lg:col-span-5 bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden flex flex-col">
            <!-- Header Strip -->
            <div class="px-5 py-4 border-b border-slate-100 flex items-center justify-between">
                <div>
                    <h2 class="font-extrabold text-base text-[#0F172A] tracking-tight">
                        Distribusi Jurusan
                    </h2>
                    <p class="text-xs text-slate-500 mt-0.5">Sebaran peminatan kompetensi keahlian</p>
                </div>
                <span class="px-3 py-1 rounded-full bg-slate-100 text-slate-600 text-[11px] font-semibold">
                    72 Kuota/Kelas
                </span>
            </div>

            <!-- List of Majors -->
            <div class="p-4 space-y-3.5 flex-1">
                @foreach ($majors as $fullName => $shortName)
                    @php
                        $count = $jurusanCounts[$fullName] ?? 0;
                        $percent = $totalPendaftar > 0 ? round(($count / $totalPendaftar) * 100) : 0;
                        $quotaPercent = min(100, round(($count / 72) * 100));
                    @endphp
                    <div>
                        <div class="flex items-center justify-between mb-1.5">
                            <div class="flex items-center gap-2">
                                <span class="font-bold text-xs text-slate-800">{{ $shortName }}</span>
                                <span class="text-[11px] text-slate-400 truncate max-w-[160px]" title="{{ $fullName }}">
                                    {{ $fullName }}
                                </span>
                            </div>
                            <div class="text-right">
                                <span class="font-mono font-bold text-xs text-[#8B1D24]">{{ $count }}</span>
                                <span class="text-[11px] text-slate-400">/ 72</span>
                            </div>
                        </div>

                        <!-- Thin Rounded Progress Bar with Maroon Gradient -->
                        <div class="w-full bg-slate-100 h-2 rounded-full overflow-hidden">
                            <div class="bg-gradient-to-r from-[#8B1D24] to-[#A11B24] h-full rounded-full transition-all duration-300" 
                                 style="width: {{ $quotaPercent }}%"></div>
                        </div>

                        <div class="flex items-center justify-between text-[10px] text-slate-400 mt-1">
                            <span>{{ $percent }}% dari total pendaftar</span>
                            <span>{{ $quotaPercent }}% kuota terpenuhi</span>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Footer Action -->
            <div class="p-4 border-t border-slate-100 bg-slate-50/50">
                <a href="{{ route('ppdb.akomodasi') }}" target="_blank" class="text-xs font-semibold text-[#8B1D24] hover:underline flex items-center justify-between">
                    <span>Lihat Informasi Rincian Biaya & Program Jurusan</span>
                    <span>&rarr;</span>
                </a>
            </div>
        </div>

    </div>

    <!-- QUICK ADMIN GUIDE STRIP (Clean Rounded Card with Step Badges) -->
    <div class="bg-white rounded-2xl border border-slate-200/80 p-5 sm:p-6 shadow-sm">
        <div class="flex items-center justify-between pb-3 mb-4 border-b border-slate-100">
            <div>
                <span class="font-extrabold text-sm text-[#0F172A] tracking-tight block">
                    Alur Kerja Panitia PPDB
                </span>
                <span class="text-xs text-slate-400">SMK Plus Pelita Nusantara Bogor</span>
            </div>
            <span class="px-2.5 py-0.5 rounded-full bg-slate-100 text-slate-600 text-[10px] font-bold uppercase tracking-wider">
                SOP 2027
            </span>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="p-4 rounded-xl bg-slate-50/70 border border-slate-100">
                <div class="flex items-center gap-2.5 mb-2">
                    <span class="w-7 h-7 rounded-full bg-[#1E293B] text-white flex items-center justify-center font-bold text-xs shadow-xs">1</span>
                    <span class="font-bold text-xs text-slate-900">Verifikasi Dokumen</span>
                </div>
                <p class="text-xs text-slate-500 leading-relaxed">
                    Buka menu Pendaftar Siswa, teliti NISN, KK, asal sekolah, dan nomor kontak pendaftar & wali siswa.
                </p>
            </div>
            <div class="p-4 rounded-xl bg-slate-50/70 border border-slate-100">
                <div class="flex items-center gap-2.5 mb-2">
                    <span class="w-7 h-7 rounded-full bg-[#8B1D24] text-white flex items-center justify-center font-bold text-xs shadow-xs">2</span>
                    <span class="font-bold text-xs text-slate-900">Update Status & Catatan</span>
                </div>
                <p class="text-xs text-slate-500 leading-relaxed">
                    Buka tombol "Detail/Edit", sesuaikan status berkas menjadi "Terverifikasi" atau "Lulus Seleksi".
                </p>
            </div>
            <div class="p-4 rounded-xl bg-slate-50/70 border border-slate-100">
                <div class="flex items-center gap-2.5 mb-2">
                    <span class="w-7 h-7 rounded-full bg-[#1E293B] text-white flex items-center justify-center font-bold text-xs shadow-xs">3</span>
                    <span class="font-bold text-xs text-slate-900">Hubungi Calon Siswa</span>
                </div>
                <p class="text-xs text-slate-500 leading-relaxed">
                    Kirim pesan konfirmasi langsung via WhatsApp dengan sekali klik tombol kontak yang tersedia di tabel.
                </p>
            </div>
        </div>
    </div>

</div>
@endsection
