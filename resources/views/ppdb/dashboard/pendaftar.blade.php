@extends('layouts.admin')

@section('title', 'Data Pendaftar Siswa PPDB - SMK Plus Pelita Nusantara')
@section('page_title', 'Daftar Calon Peserta Didik Baru')

@section('content')
<div x-data="pendaftarManager()" class="space-y-3">

    <!-- SUB-NAVIGATION TABS & ACTION BUTTONS (Matches subnav in reference image: NO CARD, NO RADIUS) -->
    <div class="bg-white border border-brand-ink/15 p-2 sm:p-2.5 flex flex-col md:flex-row md:items-center justify-between gap-2.5">
        <!-- Status Filter Tabs -->
        <div class="flex items-center flex-wrap gap-1">
            <a href="{{ route('ppdb.dashboard.pendaftar', array_merge(request()->except('status', 'page'), [])) }}" 
               class="px-3.5 py-1.5 text-xs font-bold uppercase tracking-wider {{ empty($status) ? 'bg-brand-darkred text-white border border-brand-darkred' : 'text-brand-ink/75 hover:bg-black/5 hover:text-brand-ink border border-transparent' }} transition-colors">
                Semua ({{ $countAll }})
            </a>
            <a href="{{ route('ppdb.dashboard.pendaftar', array_merge(request()->except('page'), ['status' => 'menunggu_verifikasi'])) }}" 
               class="px-3.5 py-1.5 text-xs font-bold uppercase tracking-wider {{ $status === 'menunggu_verifikasi' ? 'bg-brand-darkred text-white border border-brand-darkred' : 'text-brand-ink/75 hover:bg-black/5 hover:text-brand-ink border border-transparent' }} transition-colors">
                Menunggu ({{ $countMenunggu }})
            </a>
            <a href="{{ route('ppdb.dashboard.pendaftar', array_merge(request()->except('page'), ['status' => 'terverifikasi'])) }}" 
               class="px-3.5 py-1.5 text-xs font-bold uppercase tracking-wider {{ $status === 'terverifikasi' ? 'bg-brand-darkred text-white border border-brand-darkred' : 'text-brand-ink/75 hover:bg-black/5 hover:text-brand-ink border border-transparent' }} transition-colors">
                Terverifikasi ({{ $countTerverifikasi }})
            </a>
            <a href="{{ route('ppdb.dashboard.pendaftar', array_merge(request()->except('page'), ['status' => 'lulus_seleksi'])) }}" 
               class="px-3.5 py-1.5 text-xs font-bold uppercase tracking-wider {{ $status === 'lulus_seleksi' ? 'bg-brand-darkred text-white border border-brand-darkred' : 'text-brand-ink/75 hover:bg-black/5 hover:text-brand-ink border border-transparent' }} transition-colors">
                Lulus ({{ $countLulus }})
            </a>
        </div>

        <!-- Action CTAs -->
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
               class="px-4 py-1.5 bg-brand-darkred hover:bg-brand-deepred text-white text-xs font-bold uppercase tracking-wider transition-all flex items-center gap-1.5 border border-brand-darkred">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"></path>
                </svg>
                <span>Tambah Pendaftar</span>
            </a>
        </div>
    </div>

    <!-- FILTER TOOLBAR (Matches search & filter in reference: NO CARD, NO RADIUS, COMPACT) -->
    <div class="bg-white border border-brand-ink/15 p-3">
        <form action="{{ route('ppdb.dashboard.pendaftar') }}" method="GET" class="grid grid-cols-1 sm:grid-cols-12 gap-2">
            <!-- Search Query -->
            <div class="sm:col-span-4 relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-brand-ink/40">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </div>
                <input 
                    type="text" 
                    name="search" 
                    value="{{ $search ?? '' }}"
                    placeholder="Cari nama, No Reg, NISN, atau asal sekolah..."
                    class="w-full pl-9 pr-3 py-2 border border-brand-ink/20 text-xs focus:outline-none focus:border-brand-darkred bg-white"
                />
            </div>

            <!-- Jurusan Filter -->
            <div class="sm:col-span-3">
                <select 
                    name="jurusan" 
                    class="w-full px-3 py-2 border border-brand-ink/20 text-xs focus:outline-none focus:border-brand-darkred bg-white cursor-pointer">
                    <option value="">Semua Jurusan (7 Keahlian)</option>
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
                    class="w-full px-3 py-2 border border-brand-ink/20 text-xs focus:outline-none focus:border-brand-darkred bg-white cursor-pointer">
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
                    class="w-full px-3 py-2 border border-brand-ink/20 text-xs focus:outline-none focus:border-brand-darkred bg-white cursor-pointer">
                    <option value="terbaru" {{ ($sort ?? '') === 'terbaru' ? 'selected' : '' }}>Urut: Terbaru</option>
                    <option value="terlama" {{ ($sort ?? '') === 'terlama' ? 'selected' : '' }}>Urut: Terlama</option>
                    <option value="nama_asc" {{ ($sort ?? '') === 'nama_asc' ? 'selected' : '' }}>Nama: A - Z</option>
                    <option value="nama_desc" {{ ($sort ?? '') === 'nama_desc' ? 'selected' : '' }}>Nama: Z - A</option>
                </select>
            </div>

            <!-- Submit & Reset -->
            <div class="sm:col-span-1 flex items-center gap-1">
                <button type="submit" class="flex-1 py-2 bg-brand-darkred hover:bg-brand-deepred text-white text-xs font-bold uppercase transition-colors cursor-pointer text-center">
                    Cari
                </button>
                @if ($search || $jurusan || $status || $jalur || ($sort && $sort !== 'terbaru'))
                    <a href="{{ route('ppdb.dashboard.pendaftar') }}" 
                       class="px-2.5 py-2 bg-black/10 hover:bg-black/20 text-brand-ink text-xs font-bold cursor-pointer" 
                       title="Reset Filter">
                        ✕
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- MAIN DATA TABLE (NO CARD DESIGN, COMPACT FLAT BORDERED STRUCTURE) -->
    <div class="bg-white border border-brand-ink/15">
        
        <!-- Table Top Info Strip -->
        <div class="px-4 py-2.5 border-b border-brand-ink/15 flex flex-col sm:flex-row sm:items-center justify-between gap-2 bg-black/[0.02]">
            <div class="flex items-center gap-2">
                <span class="text-xs font-bold text-brand-ink uppercase tracking-wider">
                    Total Ditemukan: {{ $pendaftarList->total() }} Pendaftar
                </span>
                @if ($search)
                    <span class="text-[11px] text-brand-darkred font-semibold">
                        (Kata kunci: "{{ $search }}")
                    </span>
                @endif
            </div>

            <div class="text-[11px] text-brand-ink/60 font-mono">
                Halaman {{ $pendaftarList->currentPage() }} dari {{ $pendaftarList->lastPage() }}
            </div>
        </div>

        <!-- Responsive Table Container -->
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs border-collapse">
                <thead>
                    <tr class="bg-black/[0.03] border-b border-brand-ink/15 text-[10px] font-bold uppercase tracking-wider text-brand-ink/70">
                        <th class="py-3 px-3.5 whitespace-nowrap">No Reg</th>
                        <th class="py-3 px-3.5 whitespace-nowrap">Nama Calon Siswa</th>
                        <th class="py-3 px-3.5 whitespace-nowrap">NISN / KK</th>
                        <th class="py-3 px-3.5 whitespace-nowrap">Jurusan Pilihan</th>
                        <th class="py-3 px-3.5 whitespace-nowrap">Asal Sekolah</th>
                        <th class="py-3 px-3.5 whitespace-nowrap">Jalur Seleksi</th>
                        <th class="py-3 px-3.5 whitespace-nowrap">Kontak WhatsApp</th>
                        <th class="py-3 px-3.5 whitespace-nowrap">Status</th>
                        <th class="py-3 px-3.5 whitespace-nowrap text-center">Aksi / Update</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-brand-ink/10">
                    @forelse ($pendaftarList as $pendaftar)
                        @php
                            $badge = $pendaftar->status_badge;
                        @endphp
                        <tr class="hover:bg-brand-darkred/[0.02] transition-colors">
                            <!-- No Reg -->
                            <td class="py-3 px-3.5 font-mono font-bold text-brand-darkred whitespace-nowrap">
                                <a href="{{ route('ppdb.dashboard.pendaftar.detail', $pendaftar->id) }}" class="hover:underline">
                                    {{ $pendaftar->nomor_registrasi }}
                                </a>
                                <div class="text-[10px] text-brand-ink/40 font-sans font-normal">
                                    {{ $pendaftar->created_at->format('d/m/Y H:i') }}
                                </div>
                            </td>

                            <!-- Nama Lengkap & Panggilan -->
                            <td class="py-3 px-3.5 whitespace-nowrap">
                                <a href="{{ route('ppdb.dashboard.pendaftar.detail', $pendaftar->id) }}" class="font-bold text-brand-ink hover:text-brand-darkred hover:underline block text-xs">
                                    {{ $pendaftar->nama_lengkap }}
                                </a>
                                <div class="text-[10px] text-brand-ink/60">
                                    Panggilan: <span class="font-medium text-brand-ink">{{ $pendaftar->nama_panggilan ?? '-' }}</span> 
                                    ({{ $pendaftar->jenis_kelamin === 'L' ? 'Laki-laki' : 'Perempuan' }})
                                </div>
                            </td>

                            <!-- NISN & KK -->
                            <td class="py-3 px-3.5 font-mono text-[11px] whitespace-nowrap">
                                <div>NISN: <span class="font-bold text-brand-ink">{{ $pendaftar->nisn ?? '-' }}</span></div>
                                <div class="text-[10px] text-brand-ink/50">KK: {{ $pendaftar->nomor_kk ?? '-' }}</div>
                            </td>

                            <!-- Jurusan -->
                            <td class="py-3 px-3.5 whitespace-nowrap">
                                <span class="font-bold text-brand-ink text-xs block">
                                    {{ $majors[$pendaftar->jurusan] ?? $pendaftar->jurusan }}
                                </span>
                                <span class="text-[10px] text-brand-ink/50 block truncate max-w-[170px]" title="{{ $pendaftar->jurusan }}">
                                    {{ $pendaftar->jurusan }}
                                </span>
                            </td>

                            <!-- Asal Sekolah -->
                            <td class="py-3 px-3.5 text-xs text-brand-ink/80 whitespace-nowrap">
                                {{ $pendaftar->asal_sekolah }}
                            </td>

                            <!-- Jalur -->
                            <td class="py-3 px-3.5 whitespace-nowrap">
                                <span class="px-2 py-0.5 text-[10px] font-bold bg-black/5 text-brand-ink border border-black/10">
                                    {{ $pendaftar->jalur_seleksi }}
                                </span>
                            </td>

                            <!-- Kontak WA Siswa & Wali -->
                            <td class="py-3 px-3.5 whitespace-nowrap">
                                <div>
                                    <a href="https://wa.me/{{ preg_replace('/\D/', '', $pendaftar->nomor_kontak_pendaftar) }}" target="_blank" class="font-mono text-emerald-700 font-bold hover:underline flex items-center gap-1 text-[11px]">
                                        <span>📱</span>
                                        <span>{{ $pendaftar->nomor_kontak_pendaftar }}</span>
                                    </a>
                                </div>
                                <div class="text-[10px] text-brand-ink/50">
                                    Ortu: {{ $pendaftar->nomor_kontak_ortu }}
                                </div>
                            </td>

                            <!-- Status Badge -->
                            <td class="py-3 px-3.5 whitespace-nowrap">
                                <span class="px-2 py-1 text-[10px] font-bold border {{ $badge['bg'] }} inline-block">
                                    {{ $badge['label'] }}
                                </span>
                            </td>

                            <!-- Actions -->
                            <td class="py-3 px-3.5 whitespace-nowrap text-center">
                                <div class="flex items-center justify-center gap-1">
                                    <!-- Detail & Update Button -->
                                    <a href="{{ route('ppdb.dashboard.pendaftar.detail', $pendaftar->id) }}" 
                                       class="px-2.5 py-1 text-[11px] font-bold bg-brand-darkred hover:bg-brand-deepred text-white transition-colors inline-block"
                                       title="Edit Data Pendaftar">
                                        Edit / Detail
                                    </a>

                                    <!-- Quick Status Toggle Dropdown -->
                                    <div class="relative" x-data="{ statusMenu: false }">
                                        <button 
                                            @click="statusMenu = !statusMenu" 
                                            class="px-2 py-1 text-[11px] font-bold bg-white hover:bg-black/5 text-brand-ink border border-brand-ink/20 cursor-pointer"
                                            title="Ubah Status Cepat">
                                            Status ▾
                                        </button>
                                        <div 
                                            x-show="statusMenu" 
                                            x-cloak
                                            @click.away="statusMenu = false"
                                            class="absolute right-0 mt-1 w-44 bg-white border border-brand-ink/20 shadow-xl z-30 py-1 text-left">
                                            <button 
                                                @click="changeStatus({{ $pendaftar->id }}, 'menunggu_verifikasi'); statusMenu = false;"
                                                class="w-full text-left px-3 py-1.5 hover:bg-amber-50 text-[11px] font-bold text-amber-700 cursor-pointer">
                                                ● Menunggu Verifikasi
                                            </button>
                                            <button 
                                                @click="changeStatus({{ $pendaftar->id }}, 'terverifikasi'); statusMenu = false;"
                                                class="w-full text-left px-3 py-1.5 hover:bg-emerald-50 text-[11px] font-bold text-emerald-700 cursor-pointer">
                                                ● Terverifikasi
                                            </button>
                                            <button 
                                                @click="changeStatus({{ $pendaftar->id }}, 'lulus_seleksi'); statusMenu = false;"
                                                class="w-full text-left px-3 py-1.5 hover:bg-blue-50 text-[11px] font-bold text-blue-700 cursor-pointer">
                                                ● Lulus Seleksi
                                            </button>
                                        </div>
                                    </div>

                                    <!-- Delete Button -->
                                    <form action="{{ route('ppdb.dashboard.pendaftar.destroy', $pendaftar->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data pendaftar {{ $pendaftar->nama_lengkap }} ({{ $pendaftar->nomor_registrasi }})? Data tidak dapat dipulihkan.');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-1 text-red-600 hover:text-red-800 hover:bg-red-50 border border-red-200 cursor-pointer" title="Hapus Data">
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
                            <td colspan="9" class="py-12 text-center text-xs text-brand-ink/60">
                                <div class="w-12 h-12 bg-black/5 text-brand-ink/30 mx-auto flex items-center justify-center font-bold mb-2">
                                    ✕
                                </div>
                                <div class="font-bold text-brand-ink text-sm">Tidak Ditemukan Calon Siswa</div>
                                <div class="text-brand-ink/60 mt-1">Coba sesuaikan kata kunci pencarian atau reset filter.</div>
                                <a href="{{ route('ppdb.dashboard.pendaftar') }}" class="inline-block mt-3 px-4 py-1.5 bg-brand-darkred text-white text-xs font-bold uppercase tracking-wider">
                                    Reset Semua Filter
                                </a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- PAGINATION STRIP (SHARP, NO RADIUS) -->
        <div class="p-3 border-t border-brand-ink/15 bg-black/[0.015] flex flex-col sm:flex-row sm:items-center justify-between gap-3 text-xs">
            <div class="text-brand-ink/70">
                Menampilkan <span class="font-bold text-brand-ink">{{ $pendaftarList->firstItem() ?? 0 }}</span> sampai <span class="font-bold text-brand-ink">{{ $pendaftarList->lastItem() ?? 0 }}</span> dari <span class="font-bold text-brand-ink">{{ $pendaftarList->total() }}</span> calon siswa terdaftar
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
