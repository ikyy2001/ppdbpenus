@extends('layouts.admin')

@section('title', 'Manajemen Gelombang PPDB - SMK Plus Pelita Nusantara')
@section('page_title', 'Kelola Gelombang Pendaftaran')

@section('content')
<div class="space-y-6" x-data="{
    createModalOpen: false,
    editModalOpen: false,
    editWave: {
        id: null,
        nama: '',
        tahun_ajaran: '',
        tanggal_mulai: '',
        tanggal_selesai: '',
        kuota: 350,
        biaya_formulir: 150000,
        deskripsi: ''
    },
    openEdit(wave) {
        this.editWave = { ...wave };
        this.editModalOpen = true;
    }
}">

    <!-- TOP HEADER BAR -->
    <div class="bg-white rounded-2xl border border-slate-200/80 p-4 sm:p-5 shadow-sm flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <div class="flex items-center gap-2">
                <span class="px-2.5 py-0.5 rounded-full bg-slate-900 text-white text-[10px] font-bold uppercase tracking-wider">
                    Jadwal & Kuota
                </span>
                <span class="text-xs text-slate-500 font-medium">T.A 2027/2028</span>
            </div>
            <h2 class="text-lg sm:text-xl font-extrabold text-slate-900 mt-1">Daftar Gelombang Penerimaan Siswa Baru</h2>
            <p class="text-xs text-slate-500 mt-0.5">Atur periode pembukaan, kuota pendaftaran, dan biaya formulir yang berlaku.</p>
        </div>

        <div>
            <button 
                type="button" 
                @click="createModalOpen = true"
                class="px-4 py-2.5 rounded-xl bg-[#8B1D24] hover:bg-[#72151B] text-white text-xs font-bold transition-all shadow-sm flex items-center gap-2 cursor-pointer">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"></path>
                </svg>
                <span>Tambah Gelombang Baru</span>
            </button>
        </div>
    </div>

    <!-- WAVE CARDS GRID -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
        @foreach($waves as $wave)
        <div class="bg-white rounded-2xl border @if($wave->is_active) border-emerald-400 ring-2 ring-emerald-400/20 @else border-slate-200/80 @endif p-5 shadow-sm flex flex-col justify-between relative overflow-hidden transition-all hover:shadow-md">
            
            @if($wave->is_active)
            <div class="absolute top-0 right-0">
                <div class="bg-emerald-500 text-white text-[10px] font-extrabold uppercase tracking-wider px-3 py-1 rounded-bl-xl shadow-xs flex items-center gap-1">
                    <span class="w-1.5 h-1.5 rounded-full bg-white animate-ping"></span>
                    SEDANG AKTIF
                </div>
            </div>
            @endif

            <div>
                <!-- Wave Name & Year -->
                <div class="mb-3 pr-20">
                    <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">
                        {{ $wave->tahun_ajaran }}
                    </span>
                    <h3 class="text-lg font-extrabold text-slate-900">{{ $wave->nama }}</h3>
                </div>

                <!-- Description -->
                <p class="text-xs text-slate-600 mb-4 line-clamp-2 leading-relaxed">
                    {{ $wave->deskripsi ?? 'Tidak ada catatan tambahan untuk gelombang ini.' }}
                </p>

                <!-- Key Metrics Info Box -->
                <div class="space-y-2.5 p-3.5 rounded-xl bg-slate-50 border border-slate-100 text-xs mb-4">
                    <div class="flex items-center justify-between">
                        <span class="text-slate-500">Periode:</span>
                        <span class="font-bold text-slate-800 text-[11px]">{{ $wave->periode_formatted }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-slate-500">Biaya Formulir:</span>
                        <span class="font-extrabold text-[#8B1D24]">{{ $wave->biaya_formulir_formatted }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-slate-500">Pendaftar Masuk:</span>
                        <span class="font-bold text-slate-900">{{ $wave->pendaftar_count }} / {{ $wave->kuota }} Siswa</span>
                    </div>

                    <!-- Quota Progress Bar -->
                    <div class="pt-1">
                        <div class="w-full bg-slate-200 h-2 rounded-full overflow-hidden">
                            <div class="bg-[#8B1D24] h-full rounded-full transition-all duration-500" 
                                 style="width: {{ $wave->persentase_terisi }}%">
                            </div>
                        </div>
                        <div class="flex items-center justify-between text-[10px] text-slate-500 mt-1 font-medium">
                            <span>Terisi {{ $wave->persentase_terisi }}%</span>
                            <span>Sisa Kuota: {{ $wave->sisa_kuota }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Card Bottom Actions -->
            <div class="pt-3 border-t border-slate-100 flex items-center justify-between gap-2">
                @if(!$wave->is_active)
                <form action="{{ route('ppdb.dashboard.gelombang.activate', $wave->id) }}" method="POST">
                    @csrf
                    @method('PATCH')
                    <button type="submit" 
                        class="px-3 py-1.5 rounded-lg bg-emerald-50 hover:bg-emerald-100 text-emerald-700 text-xs font-bold transition-colors cursor-pointer border border-emerald-200 flex items-center gap-1.5">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        <span>Aktifkan</span>
                    </button>
                </form>
                @else
                <span class="inline-flex items-center gap-1 text-xs font-bold text-emerald-600">
                    <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                    Aktif di Form Publik
                </span>
                @endif

                <div class="flex items-center gap-1.5 ml-auto">
                    <button 
                        type="button" 
                        @click="openEdit({{ json_encode([
                            'id' => $wave->id,
                            'nama' => $wave->nama,
                            'tahun_ajaran' => $wave->tahun_ajaran,
                            'tanggal_mulai' => $wave->tanggal_mulai ? $wave->tanggal_mulai->format('Y-m-d') : '',
                            'tanggal_selesai' => $wave->tanggal_selesai ? $wave->tanggal_selesai->format('Y-m-d') : '',
                            'kuota' => $wave->kuota,
                            'biaya_formulir' => (int) $wave->biaya_formulir,
                            'deskripsi' => $wave->deskripsi ?? '',
                        ]) }})"
                        class="p-2 rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-700 transition-colors text-xs font-semibold cursor-pointer"
                        title="Edit Gelombang">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                        </svg>
                    </button>

                    @if($wave->pendaftar_count === 0 && !$wave->is_active)
                    <form action="{{ route('ppdb.dashboard.gelombang.destroy', $wave->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus gelombang ini?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" 
                            class="p-2 rounded-lg bg-red-50 hover:bg-red-100 text-red-600 transition-colors text-xs font-semibold cursor-pointer"
                            title="Hapus Gelombang">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                            </svg>
                        </button>
                    </form>
                    @endif
                </div>
            </div>

        </div>
        @endforeach
    </div>

    <!-- MODAL CREATE GELOMBANG -->
    <div x-show="createModalOpen" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4">
        <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-xs" @click="createModalOpen = false"></div>
        <div class="bg-white rounded-3xl max-w-lg w-full p-6 shadow-2xl relative z-10 border border-slate-100">
            <div class="flex items-center justify-between pb-4 border-b border-slate-100">
                <h3 class="text-base font-bold text-slate-900">Tambah Gelombang Baru</h3>
                <button type="button" @click="createModalOpen = false" class="text-slate-400 hover:text-slate-700">✕</button>
            </div>

            <form action="{{ route('ppdb.dashboard.gelombang.store') }}" method="POST" class="mt-4 space-y-4">
                @csrf
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Nama Gelombang</label>
                        <input type="text" name="nama" required placeholder="cth: Gelombang 4" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-xs focus:outline-none focus:border-red-600">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Tahun Ajaran</label>
                        <input type="text" name="tahun_ajaran" required value="2027/2028" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-xs focus:outline-none focus:border-red-600">
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Tanggal Mulai</label>
                        <input type="date" name="tanggal_mulai" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-xs focus:outline-none focus:border-red-600">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Tanggal Selesai</label>
                        <input type="date" name="tanggal_selesai" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-xs focus:outline-none focus:border-red-600">
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Kuota Siswa</label>
                        <input type="number" name="kuota" required value="200" min="1" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-xs focus:outline-none focus:border-red-600">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Biaya Formulir (Rp)</label>
                        <input type="number" name="biaya_formulir" required value="150000" min="0" step="5000" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-xs focus:outline-none focus:border-red-600">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Deskripsi / Catatan Tambahan</label>
                    <textarea name="deskripsi" rows="2" placeholder="Catatan gelombang, promo potongan biaya, dll..." class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-xs focus:outline-none focus:border-red-600"></textarea>
                </div>

                <div class="pt-2 flex items-center justify-end gap-2">
                    <button type="button" @click="createModalOpen = false" class="px-4 py-2 rounded-xl text-xs font-semibold text-slate-600 hover:bg-slate-100">Batal</button>
                    <button type="submit" class="px-5 py-2 rounded-xl bg-[#8B1D24] hover:bg-[#72151B] text-white text-xs font-bold">Simpan Gelombang</button>
                </div>
            </form>
        </div>
    </div>

    <!-- MODAL EDIT GELOMBANG -->
    <div x-show="editModalOpen" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4">
        <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-xs" @click="editModalOpen = false"></div>
        <div class="bg-white rounded-3xl max-w-lg w-full p-6 shadow-2xl relative z-10 border border-slate-100">
            <div class="flex items-center justify-between pb-4 border-b border-slate-100">
                <h3 class="text-base font-bold text-slate-900">Perbarui Data Gelombang</h3>
                <button type="button" @click="editModalOpen = false" class="text-slate-400 hover:text-slate-700">✕</button>
            </div>

            <form :action="'{{ url('/ppdb/dashboard/gelombang') }}/' + editWave.id" method="POST" class="mt-4 space-y-4">
                @csrf
                @method('PUT')
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Nama Gelombang</label>
                        <input type="text" name="nama" required x-model="editWave.nama" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-xs focus:outline-none focus:border-red-600">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Tahun Ajaran</label>
                        <input type="text" name="tahun_ajaran" required x-model="editWave.tahun_ajaran" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-xs focus:outline-none focus:border-red-600">
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Tanggal Mulai</label>
                        <input type="date" name="tanggal_mulai" required x-model="editWave.tanggal_mulai" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-xs focus:outline-none focus:border-red-600">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Tanggal Selesai</label>
                        <input type="date" name="tanggal_selesai" required x-model="editWave.tanggal_selesai" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-xs focus:outline-none focus:border-red-600">
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Kuota Siswa</label>
                        <input type="number" name="kuota" required x-model="editWave.kuota" min="1" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-xs focus:outline-none focus:border-red-600">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Biaya Formulir (Rp)</label>
                        <input type="number" name="biaya_formulir" required x-model="editWave.biaya_formulir" min="0" step="5000" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-xs focus:outline-none focus:border-red-600">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Deskripsi / Catatan</label>
                    <textarea name="deskripsi" rows="2" x-model="editWave.deskripsi" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-xs focus:outline-none focus:border-red-600"></textarea>
                </div>

                <div class="pt-2 flex items-center justify-end gap-2">
                    <button type="button" @click="editModalOpen = false" class="px-4 py-2 rounded-xl text-xs font-semibold text-slate-600 hover:bg-slate-100">Batal</button>
                    <button type="submit" class="px-5 py-2 rounded-xl bg-[#8B1D24] hover:bg-[#72151B] text-white text-xs font-bold">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>

</div>
@endsection
