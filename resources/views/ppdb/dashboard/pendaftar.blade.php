@extends('layouts.admin')

@section('title', 'Data Pendaftar Siswa PPDB - SMK Plus Pelita Nusantara')
@section('page_title', 'Daftar Calon Peserta Didik Baru')

@section('content')
<div x-data="pendaftarManager()" class="space-y-5">

    <!-- SUB-NAVIGATION TABS & ACTION BUTTONS -->
    <div class="bg-white rounded-2xl border border-slate-200/80 p-3 sm:p-3.5 shadow-sm flex flex-col md:flex-row md:items-center justify-between gap-3">
        <!-- Status Filter Tabs -->
        <div class="flex items-center flex-wrap gap-1.5">
            <a href="{{ route('ppdb.dashboard.pendaftar', array_merge(request()->except('status', 'page'), [])) }}" 
               class="px-4 py-2 rounded-xl text-xs font-semibold {{ empty($status) ? 'bg-[#1E293B] text-white shadow-sm' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900' }} transition-all flex items-center gap-2">
                <span>Semua</span>
                <span class="px-2 py-0.5 rounded-full text-[10px] font-bold {{ empty($status) ? 'bg-white/20 text-white' : 'bg-slate-200/70 text-slate-600' }}">
                    {{ $countAll }}
                </span>
            </a>
            <a href="{{ route('ppdb.dashboard.pendaftar', array_merge(request()->except('page'), ['status' => 'menunggu_verifikasi'])) }}" 
               class="px-4 py-2 rounded-xl text-xs font-semibold {{ $status === 'menunggu_verifikasi' ? 'bg-[#1E293B] text-white shadow-sm' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900' }} transition-all flex items-center gap-2">
                <span>Menunggu</span>
                <span class="px-2 py-0.5 rounded-full text-[10px] font-bold {{ $status === 'menunggu_verifikasi' ? 'bg-white/20 text-white' : 'bg-amber-100 text-amber-800' }}">
                    {{ $countMenunggu }}
                </span>
            </a>
            <a href="{{ route('ppdb.dashboard.pendaftar', array_merge(request()->except('page'), ['status' => 'terverifikasi'])) }}" 
               class="px-4 py-2 rounded-xl text-xs font-semibold {{ $status === 'terverifikasi' ? 'bg-[#1E293B] text-white shadow-sm' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900' }} transition-all flex items-center gap-2">
                <span>Terverifikasi</span>
                <span class="px-2 py-0.5 rounded-full text-[10px] font-bold {{ $status === 'terverifikasi' ? 'bg-white/20 text-white' : 'bg-emerald-100 text-emerald-800' }}">
                    {{ $countTerverifikasi }}
                </span>
            </a>
            <a href="{{ route('ppdb.dashboard.pendaftar', array_merge(request()->except('page'), ['status' => 'lulus_seleksi'])) }}" 
               class="px-4 py-2 rounded-xl text-xs font-semibold {{ $status === 'lulus_seleksi' ? 'bg-[#1E293B] text-white shadow-sm' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900' }} transition-all flex items-center gap-2">
                <span>Lulus</span>
                <span class="px-2 py-0.5 rounded-full text-[10px] font-bold {{ $status === 'lulus_seleksi' ? 'bg-white/20 text-white' : 'bg-blue-100 text-blue-800' }}">
                    {{ $countLulus }}
                </span>
            </a>
        </div>

        <!-- Action CTAs -->
        <div class="flex items-center flex-wrap gap-2.5">
            <a href="{{ route('ppdb.dashboard.pendaftar.export', request()->query()) }}" 
               class="px-3.5 py-2 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-semibold transition-all shadow-xs flex items-center gap-1.5 cursor-pointer">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                <span>Export CSV</span>
            </a>

            <button 
                type="button" 
                onclick="window.print()" 
                class="px-3.5 py-2 rounded-xl bg-white hover:bg-slate-50 text-slate-700 border border-slate-200 text-xs font-semibold transition-all shadow-xs flex items-center gap-1.5 cursor-pointer">
                <svg class="w-3.5 h-3.5 text-[#8B1D24]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
                </svg>
                <span>Cetak Rekap</span>
            </button>

            <a href="{{ route('ppdb.index') }}" target="_blank"
               class="px-3.5 py-2 rounded-xl bg-[#8B1D24] hover:bg-[#72151B] text-white text-xs font-semibold transition-all shadow-sm flex items-center gap-1.5">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"></path>
                </svg>
                <span>Tambah Pendaftar</span>
            </a>
        </div>
    </div>

    <!-- FILTER TOOLBAR -->
    <div class="bg-white rounded-2xl border border-slate-200/80 p-4 shadow-sm">
        <form action="{{ route('ppdb.dashboard.pendaftar') }}" method="GET" class="grid grid-cols-1 sm:grid-cols-12 gap-3">
            <!-- Search Query -->
            <div class="sm:col-span-4 relative">
                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </div>
                <input 
                    type="text" 
                    name="search" 
                    value="{{ $search ?? '' }}"
                    placeholder="Cari nama, No Reg, NISN, atau sekolah..."
                    class="w-full pl-10 pr-4 py-2.5 rounded-xl bg-[#F1F5F9] border-0 text-xs text-slate-800 placeholder-slate-400 focus:bg-white focus:ring-2 focus:ring-slate-300 focus:outline-none transition-all"
                />
            </div>

            <!-- Jurusan Filter -->
            <div class="sm:col-span-3">
                <select 
                    name="jurusan" 
                    class="w-full px-3.5 py-2.5 rounded-xl bg-[#F1F5F9] border-0 text-xs text-slate-700 font-medium focus:bg-white focus:ring-2 focus:ring-slate-300 focus:outline-none cursor-pointer">
                    <option value="">Semua Jurusan</option>
                    @foreach ($majors as $fullName => $shortName)
                        <option value="{{ $fullName }}" {{ ($jurusan ?? '') === $fullName ? 'selected' : '' }}>
                            {{ $shortName }} - {{ $fullName }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Status Filter -->
            <div class="sm:col-span-2">
                <select 
                    name="status" 
                    class="w-full px-3.5 py-2.5 rounded-xl bg-[#F1F5F9] border-0 text-xs text-slate-700 font-medium focus:bg-white focus:ring-2 focus:ring-slate-300 focus:outline-none cursor-pointer">
                    <option value="">Semua Status</option>
                    <option value="menunggu_verifikasi" {{ ($status ?? '') === 'menunggu_verifikasi' ? 'selected' : '' }}>Menunggu Verifikasi</option>
                    <option value="terverifikasi" {{ ($status ?? '') === 'terverifikasi' ? 'selected' : '' }}>Terverifikasi</option>
                    <option value="lulus_seleksi" {{ ($status ?? '') === 'lulus_seleksi' ? 'selected' : '' }}>Lulus Seleksi</option>
                </select>
            </div>

            <!-- Sort By -->
            <div class="sm:col-span-2">
                <select 
                    name="sort" 
                    class="w-full px-3.5 py-2.5 rounded-xl bg-[#F1F5F9] border-0 text-xs text-slate-700 font-medium focus:bg-white focus:ring-2 focus:ring-slate-300 focus:outline-none cursor-pointer">
                    <option value="terbaru" {{ ($sort ?? '') === 'terbaru' ? 'selected' : '' }}>Urut: Terbaru</option>
                    <option value="terlama" {{ ($sort ?? '') === 'terlama' ? 'selected' : '' }}>Urut: Terlama</option>
                    <option value="nama_asc" {{ ($sort ?? '') === 'nama_asc' ? 'selected' : '' }}>Nama: A - Z</option>
                    <option value="nama_desc" {{ ($sort ?? '') === 'nama_desc' ? 'selected' : '' }}>Nama: Z - A</option>
                </select>
            </div>

            <!-- Submit & Reset -->
            <div class="sm:col-span-1 flex items-center gap-1.5">
                <button type="submit" class="flex-1 py-2.5 rounded-xl bg-[#1E293B] hover:bg-slate-800 text-white text-xs font-semibold transition-all cursor-pointer text-center shadow-xs">
                    Cari
                </button>
                @if ($search || $jurusan || $status || $jalur || ($sort && $sort !== 'terbaru'))
                    <a href="{{ route('ppdb.dashboard.pendaftar') }}" 
                       class="px-3 py-2.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-600 text-xs font-bold cursor-pointer transition-colors" 
                       title="Reset Filter">
                        ✕
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- MAIN DATA TABLE -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
        
        <!-- Table Top Info Strip -->
        <div class="px-5 py-3.5 border-b border-slate-100 flex flex-col sm:flex-row sm:items-center justify-between gap-2.5 bg-slate-50/50">
            <div class="flex items-center gap-2.5">
                <span class="text-xs font-bold text-[#0F172A]">
                    Total Ditemukan:
                </span>
                <span class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-[#1E293B] text-white">
                    {{ $pendaftarList->total() }} Pendaftar
                </span>
                @if ($search)
                    <span class="text-xs text-[#8B1D24] font-medium">
                        (Kata kunci: "{{ $search }}")
                    </span>
                @endif
            </div>

            <div class="text-xs text-slate-500 font-medium">
                Halaman {{ $pendaftarList->currentPage() }} dari {{ $pendaftarList->lastPage() }}
            </div>
        </div>

        <!-- Responsive Table Container -->
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs border-collapse">
                <thead>
                    <tr class="bg-slate-50/80 border-b border-slate-100 text-[11px] font-semibold uppercase tracking-wider text-slate-500">
                        <th class="py-3.5 px-4 whitespace-nowrap">No Reg</th>
                        <th class="py-3.5 px-4 whitespace-nowrap">Nama Calon Siswa</th>
                        <th class="py-3.5 px-4 whitespace-nowrap">NISN / KK</th>
                        <th class="py-3.5 px-4 whitespace-nowrap">Jurusan Pilihan</th>
                        <th class="py-3.5 px-4 whitespace-nowrap">Asal Sekolah</th>
                        <th class="py-3.5 px-4 whitespace-nowrap">Jalur Seleksi</th>
                        <th class="py-3.5 px-4 whitespace-nowrap">Kontak WhatsApp</th>
                        <th class="py-3.5 px-4 whitespace-nowrap">Status</th>
                        <th class="py-3.5 px-4 whitespace-nowrap text-center">Aksi / Update</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($pendaftarList as $pendaftar)
                        @php
                            $badge = $pendaftar->status_badge;
                        @endphp
                        <tr class="hover:bg-slate-50/60 transition-colors">
                            <!-- No Reg -->
                            <td class="py-3.5 px-4 whitespace-nowrap">
                                <a href="{{ route('ppdb.dashboard.pendaftar.detail', $pendaftar->id) }}" 
                                   class="font-mono font-bold text-xs text-[#8B1D24] bg-red-50/70 hover:bg-red-100 px-2.5 py-1 rounded-lg inline-block transition-colors">
                                    {{ $pendaftar->nomor_registrasi }}
                                </a>
                                <div class="text-[10px] text-slate-400 mt-1">
                                    {{ $pendaftar->created_at->format('d/m/Y H:i') }}
                                </div>
                            </td>

                            <!-- Nama Lengkap & Panggilan -->
                            <td class="py-3.5 px-4 whitespace-nowrap">
                                <a href="{{ route('ppdb.dashboard.pendaftar.detail', $pendaftar->id) }}" 
                                   class="font-bold text-slate-900 hover:text-[#8B1D24] block text-xs transition-colors">
                                    {{ $pendaftar->nama_lengkap }}
                                </a>
                                <div class="text-[11px] text-slate-500 mt-0.5 flex items-center gap-1.5">
                                    <span>Panggilan: <strong class="text-slate-700">{{ $pendaftar->nama_panggilan ?? '-' }}</strong></span>
                                    <span class="px-1.5 py-0.2 rounded-full text-[10px] font-bold {{ $pendaftar->jenis_kelamin === 'L' ? 'bg-blue-50 text-blue-700' : 'bg-pink-50 text-pink-700' }}">
                                        {{ $pendaftar->jenis_kelamin === 'L' ? 'L' : 'P' }}
                                    </span>
                                </div>
                            </td>

                            <!-- NISN & KK -->
                            <td class="py-3.5 px-4 font-mono text-[11px] whitespace-nowrap">
                                <div><span class="text-slate-400 font-sans">NISN:</span> <span class="font-bold text-slate-800">{{ $pendaftar->nisn ?? '-' }}</span></div>
                                <div class="text-[10px] text-slate-400 mt-0.5"><span class="font-sans">KK:</span> {{ $pendaftar->nomor_kk ?? '-' }}</div>
                            </td>

                            <!-- Jurusan -->
                            <td class="py-3.5 px-4 whitespace-nowrap">
                                <span class="font-bold text-slate-800 text-xs block">
                                    {{ $majors[$pendaftar->jurusan] ?? $pendaftar->jurusan }}
                                </span>
                                <span class="text-[11px] text-slate-400 block truncate max-w-[170px]" title="{{ $pendaftar->jurusan }}">
                                    {{ $pendaftar->jurusan }}
                                </span>
                            </td>

                            <!-- Asal Sekolah -->
                            <td class="py-3.5 px-4 text-xs text-slate-600 font-medium whitespace-nowrap">
                                {{ $pendaftar->asal_sekolah }}
                            </td>

                            <!-- Jalur -->
                            <td class="py-3.5 px-4 whitespace-nowrap">
                                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-semibold bg-slate-100 text-slate-700 border border-slate-200/60">
                                    {{ $pendaftar->jalur_seleksi }}
                                </span>
                            </td>

                            <!-- Kontak WA Siswa & Wali -->
                            <td class="py-3.5 px-4 whitespace-nowrap">
                                <div>
                                    <a href="https://wa.me/{{ preg_replace('/\D/', '', $pendaftar->nomor_kontak_pendaftar) }}" target="_blank" 
                                       class="font-mono text-emerald-800 hover:text-emerald-950 font-bold bg-emerald-50 hover:bg-emerald-100 border border-emerald-200/80 px-2.5 py-1 rounded-full flex items-center gap-1.5 text-[11px] transition-colors w-fit">
                                        <span>📱</span>
                                        <span>{{ $pendaftar->nomor_kontak_pendaftar }}</span>
                                    </a>
                                </div>
                                <div class="text-[10px] text-slate-400 mt-1 pl-1">
                                    Ortu: {{ $pendaftar->nomor_kontak_ortu }}
                                </div>
                            </td>

                            <!-- Status Badge -->
                            <td class="py-3.5 px-4 whitespace-nowrap">
                                <span class="px-3 py-1 rounded-full text-[11px] font-semibold border {{ $badge['bg'] }} inline-block">
                                    {{ $badge['label'] }}
                                </span>
                            </td>

                            <!-- Actions -->
                            <td class="py-3.5 px-4 whitespace-nowrap text-center">
                                <div class="flex items-center justify-center gap-1.5">
                                    <!-- Cetak Kartu Button -->
                                    <a href="{{ route('ppdb.cetak-kartu', $pendaftar->id) }}" target="_blank"
                                       class="p-1.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 transition-colors inline-flex items-center justify-center"
                                       title="Cetak Kartu Peserta Resmi">
                                        <svg class="w-3.5 h-3.5 text-blue-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
                                        </svg>
                                    </a>

                                    <!-- Detail & Update Button -->
                                    <a href="{{ route('ppdb.dashboard.pendaftar.detail', $pendaftar->id) }}" 
                                       class="px-3 py-1.5 rounded-xl text-xs font-semibold bg-[#1E293B] hover:bg-slate-800 text-white shadow-xs transition-all inline-block"
                                       title="Edit Data Pendaftar">
                                        Edit / Detail
                                    </a>

                                    <!-- Quick Status Toggle Dropdown -->
                                    <div class="relative" x-data="{ statusMenu: false }">
                                        <button 
                                            @click="statusMenu = !statusMenu" 
                                            class="px-2.5 py-1.5 rounded-xl text-xs font-semibold bg-slate-100 hover:bg-slate-200 text-slate-700 transition-colors cursor-pointer"
                                            title="Ubah Status Cepat">
                                            Status ▾
                                        </button>
                                        <div 
                                            x-show="statusMenu" 
                                            x-cloak
                                            @click.away="statusMenu = false"
                                            class="absolute right-0 mt-2 w-48 bg-white rounded-2xl border border-slate-100 shadow-xl z-30 p-1.5 text-left"
                                            x-transition:enter="transition ease-out duration-150"
                                            x-transition:enter-start="opacity-0 scale-95"
                                            x-transition:enter-end="opacity-100 scale-100">
                                            <button 
                                                @click="changeStatus({{ $pendaftar->id }}, 'menunggu_verifikasi'); statusMenu = false;"
                                                class="w-full text-left px-3 py-2 rounded-xl hover:bg-amber-50 text-xs font-semibold text-amber-800 cursor-pointer transition-colors">
                                                ● Menunggu Verifikasi
                                            </button>
                                            <button 
                                                @click="changeStatus({{ $pendaftar->id }}, 'terverifikasi'); statusMenu = false;"
                                                class="w-full text-left px-3 py-2 rounded-xl hover:bg-emerald-50 text-xs font-semibold text-emerald-800 cursor-pointer transition-colors">
                                                ● Terverifikasi
                                            </button>
                                            <button 
                                                @click="changeStatus({{ $pendaftar->id }}, 'lulus_seleksi'); statusMenu = false;"
                                                class="w-full text-left px-3 py-2 rounded-xl hover:bg-blue-50 text-xs font-semibold text-blue-800 cursor-pointer transition-colors">
                                                ● Lulus Seleksi
                                            </button>
                                            <button 
                                                @click="changeStatus({{ $pendaftar->id }}, 'tidak_lulus'); statusMenu = false;"
                                                class="w-full text-left px-3 py-2 rounded-xl hover:bg-rose-50 text-xs font-semibold text-rose-800 cursor-pointer transition-colors">
                                                ● Tidak Lolos
                                            </button>
                                        </div>
                                    </div>

                                    <!-- Delete Button -->
                                    <form action="{{ route('ppdb.dashboard.pendaftar.destroy', $pendaftar->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data pendaftar {{ $pendaftar->nama_lengkap }} ({{ $pendaftar->nomor_registrasi }})? Data tidak dapat dipulihkan.');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-1.5 rounded-xl text-red-600 hover:text-red-800 hover:bg-red-50 border border-red-200/80 cursor-pointer transition-colors" title="Hapus Data">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="py-12 text-center text-xs text-slate-500">
                                <div class="w-12 h-12 rounded-2xl bg-slate-100 text-slate-400 mx-auto flex items-center justify-center font-bold mb-3">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                                <div class="font-bold text-slate-800 text-sm">Tidak Ditemukan Calon Siswa</div>
                                <div class="text-slate-400 mt-1">Coba sesuaikan kata kunci pencarian atau reset filter.</div>
                                <a href="{{ route('ppdb.dashboard.pendaftar') }}" class="inline-block mt-3 px-4 py-2 rounded-xl bg-[#8B1D24] text-white text-xs font-semibold shadow-xs">
                                    Reset Semua Filter
                                </a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- PAGINATION STRIP -->
        <div class="p-4 border-t border-slate-100 bg-slate-50/50 flex flex-col sm:flex-row sm:items-center justify-between gap-3 text-xs">
            <div class="text-slate-500">
                Menampilkan <span class="font-bold text-slate-800">{{ $pendaftarList->firstItem() ?? 0 }}</span> sampai <span class="font-bold text-slate-800">{{ $pendaftarList->lastItem() ?? 0 }}</span> dari <span class="font-bold text-slate-800">{{ $pendaftarList->total() }}</span> calon siswa terdaftar
            </div>

            <!-- Pagination Links -->
            <div class="overflow-x-auto">
                {{ $pendaftarList->links('pagination::tailwind') }}
            </div>
        </div>

    </div>

</div>
@endsection

@push('scripts')
<script>
function pendaftarManager() {
    return {
        async changeStatus(studentId, newStatus) {
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
            try {
                const response = await fetch(`/ppdb/dashboard/pendaftar/${studentId}/status`, {
                    method: 'PATCH',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ status: newStatus })
                });

                const data = await response.json();
                if (response.ok && data.success) {
                    window.location.reload();
                } else {
                    alert('Gagal memperbarui status pendaftar.');
                }
            } catch (e) {
                console.error(e);
                alert('Terjadi kesalahan jaringan.');
            }
        }
    };
}
</script>
@endpush
