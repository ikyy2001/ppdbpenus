@extends('layouts.admin')

@section('title', 'Pengaturan Biaya & Akomodasi - SMK Plus Pelita Nusantara')
@section('page_title', 'Pengaturan Biaya & Akomodasi')

@section('content')
<div class="space-y-6" x-data="{
    rekeningList: {{ json_encode($rekeningList ?? []) }},
    addRekening() {
        this.rekeningList.push({
            bank: '',
            nomor: '',
            atas_nama: '',
            badge: 'Transfer Bank'
        });
    },
    removeRekening(index) {
        if (this.rekeningList.length > 1) {
            this.rekeningList.splice(index, 1);
        } else {
            alert('Minimal harus ada 1 nomor rekening terdaftar.');
        }
    }
}">

    <!-- TOP HEADER -->
    <div class="bg-white rounded-2xl border border-slate-200/80 p-4 sm:p-5 shadow-sm flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <div class="flex items-center gap-2">
                <span class="px-2.5 py-0.5 rounded-full bg-slate-900 text-white text-[10px] font-bold uppercase tracking-wider">
                    Keuangan & Rekening
                </span>
                <span class="text-xs text-slate-500 font-medium">Sinkron dengan /ppdb/akomodasi</span>
            </div>
            <h2 class="text-lg sm:text-xl font-extrabold text-slate-900 mt-1">Konfigurasi Tarif PPDB, SPP & Rekening</h2>
            <p class="text-xs text-slate-500 mt-0.5">Nilai yang diubah di sini langsung memengaruhi kalkulator simulasi cicilan dan formulir publik.</p>
        </div>

        <div>
            <a href="{{ route('ppdb.akomodasi') }}" target="_blank"
                class="px-4 py-2 rounded-xl border border-slate-200 bg-white hover:bg-slate-50 text-slate-700 text-xs font-semibold transition-colors flex items-center gap-1.5">
                <span>Lihat Laman Akomodasi Publik ↗</span>
            </a>
        </div>
    </div>

    <form action="{{ route('ppdb.dashboard.akomodasi.update') }}" method="POST" class="space-y-6">
        @csrf

        <!-- SECTION 1: TARIF & BIAYA PENDIDIKAN -->
        <div class="bg-white rounded-2xl border border-slate-200/80 p-6 shadow-sm space-y-5">
            <div class="flex items-center gap-3 pb-3 border-b border-slate-100">
                <div class="w-9 h-9 rounded-xl bg-red-50 text-[#8B1D24] flex items-center justify-center font-bold text-xs">
                    Rp
                </div>
                <div>
                    <h3 class="font-bold text-sm text-slate-900">Komponen Biaya PPDB & Sumbangan Pendidikan</h3>
                    <p class="text-xs text-slate-500">Nominal standar biaya masuk dan uang pangkal.</p>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1.5">Biaya Formulir (Rp)</label>
                    <input type="number" name="biaya_formulir" value="{{ old('biaya_formulir', (int)$biayaFormulir) }}" required step="5000" min="0" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2.5 text-xs text-slate-900 font-bold focus:outline-none focus:border-red-600 focus:bg-white">
                    <p class="text-[10px] text-slate-400 mt-1">Pembelian nomor registrasi formulir.</p>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1.5">DSP Cash Lunas (Rp)</label>
                    <input type="number" name="dsp_cash" value="{{ old('dsp_cash', (int)$dspCash) }}" required step="10000" min="0" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2.5 text-xs text-slate-900 font-bold focus:outline-none focus:border-red-600 focus:bg-white">
                    <p class="text-[10px] text-slate-400 mt-1">Total DSP jika dibayar tunai 100%.</p>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1.5">Potongan Early Bird Gel. 1 (Rp)</label>
                    <input type="number" name="potongan_gelombang_1" value="{{ old('potongan_gelombang_1', (int)$potonganGelombang1) }}" required step="10000" min="0" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2.5 text-xs text-slate-900 font-bold focus:outline-none focus:border-red-600 focus:bg-white">
                    <p class="text-[10px] text-slate-400 mt-1">Diskon khusus pendaftar gelombang pertama.</p>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1.5">DSP Angsuran Tahap 1 (Rp)</label>
                    <input type="number" name="dsp_angsuran_1" value="{{ old('dsp_angsuran_1', (int)$dspAngsuran1) }}" required step="10000" min="0" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2.5 text-xs text-slate-900 font-bold focus:outline-none focus:border-red-600 focus:bg-white">
                    <p class="text-[10px] text-slate-400 mt-1">Dibayar saat daftar ulang.</p>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1.5">DSP Angsuran Tahap 2 (Rp)</label>
                    <input type="number" name="dsp_angsuran_2" value="{{ old('dsp_angsuran_2', (int)$dspAngsuran2) }}" required step="10000" min="0" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2.5 text-xs text-slate-900 font-bold focus:outline-none focus:border-red-600 focus:bg-white">
                    <p class="text-[10px] text-slate-400 mt-1">Sebelum Ujian Akhir Semester 1.</p>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1.5">DSP Angsuran Tahap 3 (Rp)</label>
                    <input type="number" name="dsp_angsuran_3" value="{{ old('dsp_angsuran_3', (int)$dspAngsuran3) }}" required step="10000" min="0" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2.5 text-xs text-slate-900 font-bold focus:outline-none focus:border-red-600 focus:bg-white">
                    <p class="text-[10px] text-slate-400 mt-1">Sebelum Kenaikan Kelas (Semester 2).</p>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1.5">SPP Bulanan (Rp)</label>
                    <input type="number" name="spp_bulanan" value="{{ old('spp_bulanan', (int)$sppBulanan) }}" required step="5000" min="0" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2.5 text-xs text-slate-900 font-bold focus:outline-none focus:border-red-600 focus:bg-white">
                    <p class="text-[10px] text-slate-400 mt-1">Biaya operasional SPP per bulan.</p>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1.5">Paket Seragam & Atribut (Rp)</label>
                    <input type="number" name="biaya_seragam" value="{{ old('biaya_seragam', (int)$biayaSeragam) }}" required step="10000" min="0" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2.5 text-xs text-slate-900 font-bold focus:outline-none focus:border-red-600 focus:bg-white">
                    <p class="text-[10px] text-slate-400 mt-1">5 stel seragam + almamater + atribut.</p>
                </div>
            </div>
        </div>

        <!-- SECTION 2: DAFTAR REKENING TRANSFER BANK -->
        <div class="bg-white rounded-2xl border border-slate-200/80 p-6 shadow-sm space-y-4">
            <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-xl bg-blue-50 text-blue-700 flex items-center justify-center font-bold text-xs">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                    </div>
                    <div>
                        <h3 class="font-bold text-sm text-slate-900">Rekening Resmi Pembayaran PPDB</h3>
                        <p class="text-xs text-slate-500">Nomor rekening yang ditampilkan kepada orang tua/calon siswa.</p>
                    </div>
                </div>

                <button 
                    type="button" 
                    @click="addRekening()" 
                    class="px-3.5 py-1.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold transition-colors flex items-center gap-1.5 cursor-pointer">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                    <span>Tambah Bank</span>
                </button>
            </div>

            <div class="space-y-3">
                <template x-for="(rek, index) in rekeningList" :key="index">
                    <div class="p-4 rounded-xl bg-slate-50 border border-slate-200/80 flex flex-col md:flex-row items-stretch md:items-center gap-3">
                        <div class="flex-1 grid grid-cols-1 sm:grid-cols-4 gap-3">
                            <div>
                                <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1">Nama Bank</label>
                                <input type="text" :name="'rekening['+index+'][bank]'" x-model="rek.bank" required placeholder="cth: Bank Syariah Indonesia" class="w-full bg-white border border-slate-200 rounded-lg px-3 py-1.5 text-xs text-slate-900 font-semibold focus:outline-none focus:border-blue-600">
                            </div>
                            <div>
                                <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1">Nomor Rekening</label>
                                <input type="text" :name="'rekening['+index+'][nomor]'" x-model="rek.nomor" required placeholder="7188-299-102" class="w-full bg-white border border-slate-200 rounded-lg px-3 py-1.5 text-xs text-slate-900 font-mono font-bold focus:outline-none focus:border-blue-600">
                            </div>
                            <div>
                                <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1">Atas Nama (Pemilik)</label>
                                <input type="text" :name="'rekening['+index+'][atas_nama]'" x-model="rek.atas_nama" required placeholder="YAYASAN PELITA NUSANTARA" class="w-full bg-white border border-slate-200 rounded-lg px-3 py-1.5 text-xs text-slate-900 font-semibold focus:outline-none focus:border-blue-600">
                            </div>
                            <div>
                                <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1">Badge Keterangan</label>
                                <input type="text" :name="'rekening['+index+'][badge]'" x-model="rek.badge" placeholder="Utama / ATM" class="w-full bg-white border border-slate-200 rounded-lg px-3 py-1.5 text-xs text-slate-900 focus:outline-none focus:border-blue-600">
                            </div>
                        </div>

                        <div class="shrink-0 flex items-center justify-end">
                            <button 
                                type="button" 
                                @click="removeRekening(index)" 
                                class="p-2 rounded-lg text-slate-400 hover:text-red-600 hover:bg-red-50 transition-colors"
                                title="Hapus Rekening">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                            </button>
                        </div>
                    </div>
                </template>
            </div>
        </div>

        <!-- SECTION 3: KONTAK & HELPDESK PANITIA -->
        <div class="bg-white rounded-2xl border border-slate-200/80 p-6 shadow-sm space-y-4">
            <div class="flex items-center gap-3 pb-3 border-b border-slate-100">
                <div class="w-9 h-9 rounded-xl bg-emerald-50 text-emerald-700 flex items-center justify-center font-bold text-xs">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                </div>
                <div>
                    <h3 class="font-bold text-sm text-slate-900">Saluran Bantuan & Helpdesk WhatsApp</h3>
                    <p class="text-xs text-slate-500">Nomor narahubung yang terhubung langsung pada tombol "Konsultasi Pembiayaan".</p>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1.5">No. WhatsApp Panitia (Awali 62)</label>
                    <input type="text" name="kontak_wa" value="{{ old('kontak_wa', $kontakWa) }}" required placeholder="6281283921029" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2.5 text-xs text-slate-900 font-mono font-bold focus:outline-none focus:border-emerald-600 focus:bg-white">
                    <p class="text-[10px] text-slate-400 mt-1">Gunakan format internasional tanpa spasi (cth: 62812xxxxxx).</p>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1.5">Email Sekretariat PPDB</label>
                    <input type="email" name="email_cs" value="{{ old('email_cs', $emailCs) }}" required placeholder="ppdb@smkpelitanusantara.sch.id" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2.5 text-xs text-slate-900 font-semibold focus:outline-none focus:border-emerald-600 focus:bg-white">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1.5">Telepon Kantor Kampus</label>
                    <input type="text" name="telepon_kantor" value="{{ old('telepon_kantor', $teleponKantor) }}" placeholder="(021) 8790-1234" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2.5 text-xs text-slate-900 focus:outline-none focus:border-emerald-600 focus:bg-white">
                </div>
            </div>
        </div>

        <!-- SAVE BUTTON BAR -->
        <div class="bg-white rounded-2xl border border-slate-200/80 p-4 shadow-sm flex items-center justify-between">
            <span class="text-xs text-slate-500">Periksa kembali data sebelum menyimpan perubahan.</span>
            <button 
                type="submit" 
                class="px-6 py-2.5 rounded-xl bg-[#8B1D24] hover:bg-[#72151B] text-white text-xs font-bold shadow-md shadow-red-900/20 transition-all flex items-center gap-2 cursor-pointer transform active:scale-95">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                <span>Simpan Seluruh Pengaturan</span>
            </button>
        </div>
    </form>

</div>
@endsection
