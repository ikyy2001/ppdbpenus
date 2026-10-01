@extends('layouts.admin')

@section('title', 'Manajemen Pengumuman PPDB - SMK Plus Pelita Nusantara')
@section('page_title', 'Kelola Pengumuman PPDB')

@section('content')
<div class="space-y-6" x-data="{
    createModalOpen: false,
    editModalOpen: false,
    editItem: {
        id: null,
        judul: '',
        nomor_sk: '',
        kategori: 'Hasil Seleksi & Kelulusan',
        badge: 'PENTING',
        tanggal: '',
        is_pinned: false,
        ringkasan: '',
        isi_lengkap: '',
        action_link: '',
        action_text: ''
    },
    openEdit(item) {
        this.editItem = { ...item };
        this.editModalOpen = true;
    }
}">

    <!-- TOP BAR & CONTROLS -->
    <div class="bg-white rounded-2xl border border-slate-200/80 p-4 sm:p-5 shadow-sm flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <div class="flex items-center gap-2">
                <span class="px-2.5 py-0.5 rounded-full bg-slate-900 text-white text-[10px] font-bold uppercase tracking-wider">
                    Warta Resmi
                </span>
                <span class="text-xs text-slate-500 font-medium">Terhubung Langsung ke /ppdb/pengumuman</span>
            </div>
            <h2 class="text-lg sm:text-xl font-extrabold text-slate-900 mt-1">Daftar Pengumuman & Keputusan PPDB</h2>
            <p class="text-xs text-slate-500 mt-0.5">Kelola berkas SK kelulusan, jadwal observasi, dan surat edaran panitia.</p>
        </div>

        <div class="flex items-center flex-wrap gap-2.5">
            <a href="{{ route('ppdb.pengumuman') }}" target="_blank"
                class="px-3.5 py-2.5 rounded-xl border border-slate-200 bg-white hover:bg-slate-50 text-slate-700 text-xs font-semibold transition-colors flex items-center gap-1.5">
                <span>Lihat Tampilan Publik ↗</span>
            </a>

            <button 
                type="button" 
                @click="createModalOpen = true"
                class="px-4 py-2.5 rounded-xl bg-[#8B1D24] hover:bg-[#72151B] text-white text-xs font-bold transition-all shadow-sm flex items-center gap-2 cursor-pointer">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"></path>
                </svg>
                <span>Tambah Pengumuman</span>
            </button>
        </div>
    </div>

    <!-- FILTER & SEARCH BAR -->
    <div class="bg-white rounded-2xl border border-slate-200/80 p-3.5 sm:p-4 shadow-sm">
        <form method="GET" action="{{ route('ppdb.dashboard.pengumuman') }}" class="flex flex-col sm:flex-row items-stretch sm:items-center gap-3">
            <div class="relative flex-1">
                <input 
                    type="text" 
                    name="search" 
                    value="{{ request('search') }}"
                    placeholder="Cari judul, nomor SK, atau kata kunci ringkasan..."
                    class="w-full bg-slate-50 border border-slate-200 rounded-xl pl-10 pr-4 py-2.5 text-xs text-slate-800 placeholder-slate-400 focus:outline-none focus:border-red-600 focus:bg-white transition-all">
                <svg class="w-4 h-4 text-slate-400 absolute left-3.5 top-1/2 -translate-y-1/2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
            </div>

            <div class="flex items-center gap-2">
                <select name="kategori" class="bg-slate-50 border border-slate-200 rounded-xl px-3 py-2.5 text-xs text-slate-700 font-medium focus:outline-none focus:border-red-600">
                    <option value="">Semua Kategori</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat }}" @selected(request('kategori') === $cat)>{{ $cat }}</option>
                    @endforeach
                </select>

                <button type="submit" class="px-4 py-2.5 rounded-xl bg-slate-900 text-white text-xs font-bold hover:bg-slate-800 transition-colors">
                    Filter
                </button>

                @if(request('search') || request('kategori'))
                    <a href="{{ route('ppdb.dashboard.pengumuman') }}" class="px-3 py-2.5 rounded-xl border border-slate-200 text-slate-500 hover:text-slate-800 text-xs font-semibold">
                        Reset
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- ANNOUNCEMENT TABLE / LIST -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/80 border-b border-slate-200 text-[11px] font-bold uppercase tracking-wider text-slate-500">
                        <th class="py-3 px-4">Warta / Judul</th>
                        <th class="py-3 px-4">Kategori & Badge</th>
                        <th class="py-3 px-4">Nomor SK</th>
                        <th class="py-3 px-4">Tanggal Publikasi</th>
                        <th class="py-3 px-4">Lampiran</th>
                        <th class="py-3 px-4 text-center">Pin</th>
                        <th class="py-3 px-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-xs">
                    @forelse($announcements as $item)
                    <tr class="hover:bg-slate-50/60 transition-colors">
                        <td class="py-3.5 px-4 max-w-sm">
                            <div class="font-bold text-slate-900 leading-snug line-clamp-2">{{ $item->judul }}</div>
                            <div class="text-[11px] text-slate-500 line-clamp-1 mt-0.5">{{ $item->ringkasan }}</div>
                        </td>
                        <td class="py-3.5 px-4 whitespace-nowrap">
                            <span class="inline-block px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-slate-100 text-slate-700 border border-slate-200">
                                {{ $item->kategori }}
                            </span>
                            @if($item->badge)
                                <span class="inline-block px-2 py-0.5 rounded-full text-[9px] font-extrabold uppercase bg-amber-50 text-amber-700 border border-amber-200 ml-1">
                                    {{ $item->badge }}
                                </span>
                            @endif
                        </td>
                        <td class="py-3.5 px-4 font-mono text-[11px] text-slate-600 whitespace-nowrap">
                            {{ $item->nomor_sk ?? '-' }}
                        </td>
                        <td class="py-3.5 px-4 whitespace-nowrap text-slate-600">
                            {{ $item->tanggal_formatted }}
                        </td>
                        <td class="py-3.5 px-4 whitespace-nowrap">
                            @if($item->file_nama)
                                <a href="{{ $item->file_url }}" target="_blank" class="inline-flex items-center gap-1.5 text-blue-700 hover:text-blue-900 font-semibold text-[11px]">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                    <span class="max-w-[120px] truncate">{{ $item->file_nama }}</span>
                                    <span class="text-[9px] text-slate-400">({{ $item->file_ukuran }})</span>
                                </a>
                            @else
                                <span class="text-slate-400 text-[11px]">Tanpa File</span>
                            @endif
                        </td>
                        <td class="py-3.5 px-4 text-center whitespace-nowrap">
                            <form action="{{ route('ppdb.dashboard.pengumuman.pin', $item->id) }}" method="POST">
                                @csrf
                                @method('PATCH')
                                <button type="submit" 
                                    class="p-1.5 rounded-lg transition-colors cursor-pointer {{ $item->is_pinned ? 'text-amber-500 bg-amber-50' : 'text-slate-300 hover:text-slate-600 hover:bg-slate-100' }}"
                                    title="{{ $item->is_pinned ? 'Lepas Sematan' : 'Sematkan ke Atas' }}">
                                    <svg class="w-4 h-4 fill-current" viewBox="0 0 20 20">
                                        <path d="M10 2a1 1 0 011 1v1.323l3.954 1.582 1.599-.8a1 1 0 01.894 1.79l-1.233.616 1.738 5.215A1 1 0 0117 14h-6v4a1 1 0 11-2 0v-4H3a1 1 0 01-.952-1.314l1.738-5.215-1.233-.617a1 1 0 01.894-1.788l1.599.799L9 4.323V3a1 1 0 011-1z"/>
                                    </svg>
                                </button>
                            </form>
                        </td>
                        <td class="py-3.5 px-4 text-right whitespace-nowrap">
                            <div class="inline-flex items-center gap-1.5">
                                <button 
                                    type="button" 
                                    @click="openEdit({{ json_encode([
                                        'id' => $item->id,
                                        'judul' => $item->judul,
                                        'nomor_sk' => $item->nomor_sk ?? '',
                                        'kategori' => $item->kategori,
                                        'badge' => $item->badge ?? 'INFO',
                                        'tanggal' => $item->tanggal ? $item->tanggal->format('Y-m-d') : '',
                                        'is_pinned' => (bool) $item->is_pinned,
                                        'ringkasan' => $item->ringkasan ?? '',
                                        'isi_lengkap' => $item->isi_lengkap,
                                        'action_link' => $item->action_link ?? '',
                                        'action_text' => $item->action_text ?? '',
                                    ]) }})"
                                    class="p-2 rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-700 transition-colors text-xs font-semibold cursor-pointer"
                                    title="Edit Pengumuman">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    </svg>
                                </button>

                                <form action="{{ route('ppdb.dashboard.pengumuman.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus pengumuman ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" 
                                        class="p-2 rounded-lg bg-red-50 hover:bg-red-100 text-red-600 transition-colors text-xs font-semibold cursor-pointer"
                                        title="Hapus Pengumuman">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="py-12 text-center text-slate-500">
                            <p class="font-bold">Belum ada pengumuman yang sesuai kriteria.</p>
                            <p class="text-xs text-slate-400 mt-1">Silakan klik tombol "Tambah Pengumuman" di atas untuk mempublikasikan pengumuman baru.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($announcements->hasPages())
        <div class="p-4 border-t border-slate-100">
            {{ $announcements->links() }}
        </div>
        @endif
    </div>

    <!-- MODAL CREATE PENGUMUMAN -->
    <div x-show="createModalOpen" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4">
        <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-xs" @click="createModalOpen = false"></div>
        <div class="bg-white rounded-3xl max-w-2xl w-full p-6 shadow-2xl relative z-10 border border-slate-100 max-h-[90vh] overflow-y-auto">
            <div class="flex items-center justify-between pb-4 border-b border-slate-100">
                <h3 class="text-base font-bold text-slate-900">Publikasi Pengumuman Baru</h3>
                <button type="button" @click="createModalOpen = false" class="text-slate-400 hover:text-slate-700">✕</button>
            </div>

            <form action="{{ route('ppdb.dashboard.pengumuman.store') }}" method="POST" enctype="multipart/form-data" class="mt-4 space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Judul Pengumuman</label>
                    <input type="text" name="judul" required placeholder="cth: Pengumuman Hasil Verifikasi Berkas Gelombang 1" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-xs focus:outline-none focus:border-red-600">
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Nomor SK / Surat</label>
                        <input type="text" name="nomor_sk" placeholder="084/PPDB-SMKPNB/2026" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-xs focus:outline-none focus:border-red-600">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Kategori</label>
                        <input type="text" name="kategori" required value="Hasil Seleksi & Kelulusan" list="kategoriOptions" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-xs focus:outline-none focus:border-red-600">
                        <datalist id="kategoriOptions">
                            <option value="Hasil Seleksi & Kelulusan">
                            <option value="Tes Observasi & Wawancara">
                            <option value="Daftar Ulang & Seragam">
                            <option value="Jadwal & Gelombang">
                            <option value="Informasi Beasiswa">
                            <option value="Informasi Umum">
                        </datalist>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Badge</label>
                        <select name="badge" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-xs focus:outline-none focus:border-red-600">
                            <option value="PENTING">PENTING</option>
                            <option value="TERBARU">TERBARU</option>
                            <option value="BEASISWA">BEASISWA</option>
                            <option value="GELOMBANG">GELOMBANG</option>
                            <option value="INFORMASI">INFORMASI</option>
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Tanggal Publikasi</label>
                        <input type="date" name="tanggal" required value="{{ date('Y-m-d') }}" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-xs focus:outline-none focus:border-red-600">
                    </div>
                    <div class="flex items-center pt-5">
                        <label class="flex items-center gap-2 cursor-pointer select-none text-xs font-bold text-slate-700">
                            <input type="checkbox" name="is_pinned" value="1" class="rounded text-red-600 w-4 h-4">
                            <span>Sematkan ke Bagian Teratas (Pinned)</span>
                        </label>
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Ringkasan Singkat</label>
                    <textarea name="ringkasan" required rows="2" placeholder="Ringkasan 1-2 kalimat untuk kartu pengumuman..." class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-xs focus:outline-none focus:border-red-600"></textarea>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Isi Lengkap Keputusan / Warta</label>
                    <textarea name="isi_lengkap" required rows="6" placeholder="Ketik isi lengkap keputusan SK atau instruksi pengumuman..." class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-xs focus:outline-none focus:border-red-600 font-mono"></textarea>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Upload File Lampiran (PDF / Gambar)</label>
                        <input type="file" name="file_lampiran" accept=".pdf,.doc,.docx,.jpg,.png,.jpeg" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-xs focus:outline-none focus:border-red-600">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Tautan Aksi (Opsional)</label>
                        <input type="text" name="action_link" placeholder="/ppdb/cek-status" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-xs focus:outline-none focus:border-red-600">
                    </div>
                </div>

                <div class="pt-3 border-t border-slate-100 flex items-center justify-end gap-2">
                    <button type="button" @click="createModalOpen = false" class="px-4 py-2 rounded-xl text-xs font-semibold text-slate-600 hover:bg-slate-100">Batal</button>
                    <button type="submit" class="px-5 py-2 rounded-xl bg-[#8B1D24] hover:bg-[#72151B] text-white text-xs font-bold">Publikasikan</button>
                </div>
            </form>
        </div>
    </div>

    <!-- MODAL EDIT PENGUMUMAN -->
    <div x-show="editModalOpen" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4">
        <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-xs" @click="editModalOpen = false"></div>
        <div class="bg-white rounded-3xl max-w-2xl w-full p-6 shadow-2xl relative z-10 border border-slate-100 max-h-[90vh] overflow-y-auto">
            <div class="flex items-center justify-between pb-4 border-b border-slate-100">
                <h3 class="text-base font-bold text-slate-900">Perbarui Pengumuman</h3>
                <button type="button" @click="editModalOpen = false" class="text-slate-400 hover:text-slate-700">✕</button>
            </div>

            <form :action="'{{ url('/ppdb/dashboard/pengumuman') }}/' + editItem.id" method="POST" enctype="multipart/form-data" class="mt-4 space-y-4">
                @csrf
                @method('PUT')
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Judul Pengumuman</label>
                    <input type="text" name="judul" required x-model="editItem.judul" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-xs focus:outline-none focus:border-red-600">
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Nomor SK / Surat</label>
                        <input type="text" name="nomor_sk" x-model="editItem.nomor_sk" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-xs focus:outline-none focus:border-red-600">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Kategori</label>
                        <input type="text" name="kategori" required x-model="editItem.kategori" list="kategoriOptionsEdit" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-xs focus:outline-none focus:border-red-600">
                        <datalist id="kategoriOptionsEdit">
                            <option value="Hasil Seleksi & Kelulusan">
                            <option value="Tes Observasi & Wawancara">
                            <option value="Daftar Ulang & Seragam">
                            <option value="Jadwal & Gelombang">
                            <option value="Informasi Beasiswa">
                            <option value="Informasi Umum">
                        </datalist>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Badge</label>
                        <select name="badge" x-model="editItem.badge" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-xs focus:outline-none focus:border-red-600">
                            <option value="PENTING">PENTING</option>
                            <option value="TERBARU">TERBARU</option>
                            <option value="BEASISWA">BEASISWA</option>
                            <option value="GELOMBANG">GELOMBANG</option>
                            <option value="INFORMASI">INFORMASI</option>
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Tanggal Publikasi</label>
                        <input type="date" name="tanggal" required x-model="editItem.tanggal" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-xs focus:outline-none focus:border-red-600">
                    </div>
                    <div class="flex items-center pt-5">
                        <label class="flex items-center gap-2 cursor-pointer select-none text-xs font-bold text-slate-700">
                            <input type="checkbox" name="is_pinned" value="1" x-model="editItem.is_pinned" class="rounded text-red-600 w-4 h-4">
                            <span>Sematkan ke Bagian Teratas (Pinned)</span>
                        </label>
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Ringkasan Singkat</label>
                    <textarea name="ringkasan" required rows="2" x-model="editItem.ringkasan" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-xs focus:outline-none focus:border-red-600"></textarea>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Isi Lengkap Keputusan / Warta</label>
                    <textarea name="isi_lengkap" required rows="6" x-model="editItem.isi_lengkap" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-xs focus:outline-none focus:border-red-600 font-mono"></textarea>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Ganti File Lampiran (Opsional)</label>
                        <input type="file" name="file_lampiran" accept=".pdf,.doc,.docx,.jpg,.png,.jpeg" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-xs focus:outline-none focus:border-red-600">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Tautan Aksi (Opsional)</label>
                        <input type="text" name="action_link" x-model="editItem.action_link" placeholder="/ppdb/cek-status" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-xs focus:outline-none focus:border-red-600">
                    </div>
                </div>

                <div class="pt-3 border-t border-slate-100 flex items-center justify-end gap-2">
                    <button type="button" @click="editModalOpen = false" class="px-4 py-2 rounded-xl text-xs font-semibold text-slate-600 hover:bg-slate-100">Batal</button>
                    <button type="submit" class="px-5 py-2 rounded-xl bg-[#8B1D24] hover:bg-[#72151B] text-white text-xs font-bold">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>

</div>
@endsection
