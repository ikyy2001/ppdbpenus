<?php

namespace App\Http\Controllers;

use App\Models\PpdbRegistration;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class PpdbController extends Controller
{
    /**
     * Daftar 7 Kompetensi Keahlian SMK Plus Pelita Nusantara
     */
    protected array $majors = [
        'Pengembangan Perangkat Lunak dan Gim (PPLG)' => 'PPLG',
        'Teknik Jaringan Komputer dan Telekomunikasi (TJKT)' => 'TJKT',
        'Desain Komunikasi Visual (DKV)' => 'DKV',
        'Animasi' => 'Animasi',
        'Broadcasting dan Perfilman (BC)' => 'Broadcasting',
        'Akuntansi dan Keuangan Lembaga (AKL)' => 'AKL',
        'Manajemen Perkantoran dan Layanan Bisnis (MPLB)' => 'MPLB',
    ];

    /**
     * Halaman Utama Form Pendaftaran PPDB
     * GET /ppdb
     */
    public function index()
    {
        return view('ppdb.index', [
            'totalPendaftar' => PpdbRegistration::count(),
        ]);
    }

    /**
     * Simpan Data Pendaftaran PPDB (AJAX / Form Submit)
     * POST /ppdb/daftar
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'namaLengkap' => 'required|string|min:3|max:255',
            'namaPanggilan' => 'required|string|max:100',
            'nisn' => 'nullable|string|max:30',
            'nomorKK' => 'nullable|string|max:30',
            'tempatLahir' => 'required|string|max:100',
            'tanggalLahirHari' => 'required|string',
            'tanggalLahirBulan' => 'required|string',
            'tanggalLahirTahun' => 'required|string',
            'jenisKelamin' => 'required|in:L,P',
            'alamatLengkap' => 'nullable|string',
            'sekolahPilihanLevel' => 'nullable|string',
            'sekolahPilihanUnit' => 'nullable|string',
            'tipePendaftar' => 'required|string',
            'pindahTahunAjaran' => 'nullable|string',
            'tanggalMulaiMasuk' => 'nullable|string',
            'kelasPilihan' => 'required|string',
            'jurusan' => 'required|string',
            'jalurSeleksi' => 'required|string',
            'asalSekolah' => 'required|string|max:255',
            'nomorKontakPendaftar' => 'required|string|min:10|max:20',
            'nomorKontakOrtu' => 'required|string|min:10|max:20',
            'email' => 'nullable|email|max:150',
            'jenisLayanan' => 'nullable|array',
            'sumberInfo' => 'nullable|array',
            'sumberInfoLainnya' => 'nullable|string|max:255',
            'alasanMinat' => 'nullable|string|max:255',
            'alasanMinatLainnya' => 'nullable|string|max:255',
            'ukuranSeragam' => 'nullable|string|max:10',
        ]);

        // Generate Nomor Registrasi Unik
        $randomSuffix = rand(10000, 99999);
        $nomorRegistrasi = 'PPDB-2027-' . $randomSuffix;

        while (PpdbRegistration::where('nomor_registrasi', $nomorRegistrasi)->exists()) {
            $nomorRegistrasi = 'PPDB-2027-' . rand(10000, 99999);
        }

        $registration = PpdbRegistration::create([
            'nomor_registrasi' => $nomorRegistrasi,
            'nama_lengkap' => $validated['namaLengkap'],
            'nama_panggilan' => $validated['namaPanggilan'],
            'nisn' => $validated['nisn'] ?? null,
            'nomor_kk' => $validated['nomorKK'] ?? null,
            'tempat_lahir' => $validated['tempatLahir'],
            'tanggal_lahir_hari' => $validated['tanggalLahirHari'],
            'tanggal_lahir_bulan' => $validated['tanggalLahirBulan'],
            'tanggal_lahir_tahun' => $validated['tanggalLahirTahun'],
            'jenis_kelamin' => $validated['jenisKelamin'],
            'alamat_lengkap' => $validated['alamatLengkap'] ?? null,
            'sekolah_pilihan_level' => $validated['sekolahPilihanLevel'] ?? 'SMK',
            'sekolah_pilihan_unit' => $validated['sekolahPilihanUnit'] ?? 'SMK Plus Pelita Nusantara Bogor',
            'tipe_pendaftar' => $validated['tipePendaftar'],
            'pindah_tahun_ajaran' => $validated['pindahTahunAjaran'] ?? null,
            'tanggal_mulai_masuk' => $validated['tanggalMulaiMasuk'] ?? null,
            'kelas_pilihan' => $validated['kelasPilihan'],
            'jurusan' => $validated['jurusan'],
            'jalur_seleksi' => $validated['jalurSeleksi'],
            'asal_sekolah' => $validated['asalSekolah'],
            'nomor_kontak_pendaftar' => $validated['nomorKontakPendaftar'],
            'nomor_kontak_ortu' => $validated['nomorKontakOrtu'],
            'email' => $validated['email'] ?? null,
            'jenis_layanan' => $validated['jenisLayanan'] ?? [],
            'sumber_info' => $validated['sumberInfo'] ?? [],
            'sumber_info_lainnya' => $validated['sumberInfoLainnya'] ?? null,
            'alasan_minat' => $validated['alasanMinat'] ?? null,
            'alasan_minat_lainnya' => $validated['alasanMinatLainnya'] ?? null,
            'ukuran_seragam' => $validated['ukuranSeragam'] ?? null,
            'status' => 'menunggu_verifikasi',
        ]);

        $tanggalDaftar = Carbon::now()->locale('id')->isoFormat('D MMMM Y');

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'noPendaftaran' => $nomorRegistrasi,
                'tanggalDaftar' => $tanggalDaftar,
                'data' => [
                    'namaLengkap' => $registration->nama_lengkap,
                    'jurusan' => $registration->jurusan,
                    'asalSekolah' => $registration->asal_sekolah,
                    'jalurSeleksi' => $registration->jalur_seleksi,
                ]
            ]);
        }

        return redirect()->route('ppdb.index')->with('sukses_daftar', [
            'noPendaftaran' => $nomorRegistrasi,
            'namaLengkap' => $registration->nama_lengkap,
        ]);
    }

    /**
     * Halaman Detail Akomodasi
     * GET /ppdb/akomodasi
     */
    public function akomodasi()
    {
        return view('ppdb.akomodasi');
    }

    /**
     * Halaman Daftar Pengumuman PPDB
     * GET /ppdb/pengumuman
     */
    public function pengumuman(Request $request)
    {
        $kategori = $request->get('kategori', 'semua');
        return view('ppdb.pengumuman', compact('kategori'));
    }

    /**
     * Halaman Cek Status Pendaftar (Input NISN / Nomor Registrasi)
     * GET /ppdb/cek-status
     */
    public function cekStatus(Request $request)
    {
        $query = $request->get('q');
        $pendaftar = null;
        $searched = false;

        if ($query) {
            $searched = true;
            $cleanQuery = trim($query);
            $pendaftar = PpdbRegistration::where('nomor_registrasi', 'LIKE', "%{$cleanQuery}%")
                ->orWhere('nisn', 'LIKE', "%{$cleanQuery}%")
                ->orWhere('nomor_kk', 'LIKE', "%{$cleanQuery}%")
                ->orWhere('nomor_kontak_pendaftar', 'LIKE', "%{$cleanQuery}%")
                ->orWhere('nama_lengkap', 'LIKE', "%{$cleanQuery}%")
                ->first();
        }

        return view('ppdb.cek-status', compact('pendaftar', 'query', 'searched'));
    }

    /**
     * 1. Halaman Dashboard Utama (Overview)
     * GET /ppdb/dashboard
     */
    public function dashboard(Request $request)
    {
        $totalPendaftar = PpdbRegistration::count();
        $totalTerverifikasi = PpdbRegistration::where('status', 'terverifikasi')->count();
        $totalMenunggu = PpdbRegistration::where('status', 'menunggu_verifikasi')->count();
        $totalLulus = PpdbRegistration::where('status', 'lulus_seleksi')->count();
        $todayCount = PpdbRegistration::whereDate('created_at', Carbon::today())->count();

        // Distribusi per jurusan
        $jurusanCounts = PpdbRegistration::selectRaw('jurusan, count(*) as count')
            ->groupBy('jurusan')
            ->pluck('count', 'jurusan')
            ->toArray();

        // 7 Pendaftar terbaru untuk tabel ringkasan
        $recentPendaftar = PpdbRegistration::latest()->take(7)->get();

        $majors = $this->majors;

        return view('ppdb.dashboard.index', compact(
            'totalPendaftar',
            'totalTerverifikasi',
            'totalMenunggu',
            'totalLulus',
            'todayCount',
            'jurusanCounts',
            'recentPendaftar',
            'majors'
        ));
    }

    /**
     * 2. Halaman List Pendaftar (Pagination & Filters)
     * GET /ppdb/dashboard/pendaftar
     */
    public function pendaftarList(Request $request)
    {
        $search = $request->get('search');
        $jurusan = $request->get('jurusan');
        $status = $request->get('status');
        $jalur = $request->get('jalur');
        $sort = $request->get('sort', 'terbaru');

        $query = PpdbRegistration::query();

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('nama_lengkap', 'LIKE', "%{$search}%")
                  ->orWhere('nama_panggilan', 'LIKE', "%{$search}%")
                  ->orWhere('nomor_registrasi', 'LIKE', "%{$search}%")
                  ->orWhere('nisn', 'LIKE', "%{$search}%")
                  ->orWhere('asal_sekolah', 'LIKE', "%{$search}%")
                  ->orWhere('nomor_kontak_pendaftar', 'LIKE', "%{$search}%");
            });
        }

        if ($jurusan) {
            $query->where('jurusan', $jurusan);
        }

        if ($status) {
            $query->where('status', $status);
        }

        if ($jalur) {
            $query->where('jalur_seleksi', $jalur);
        }

        // Sorting
        match ($sort) {
            'terlama' => $query->oldest(),
            'nama_asc' => $query->orderBy('nama_lengkap', 'asc'),
            'nama_desc' => $query->orderBy('nama_lengkap', 'desc'),
            default => $query->latest(),
        };

        // Pagination (8 item per page agar pagination selalu terlihat dan rapi)
        $pendaftarList = $query->paginate(8)->withQueryString();

        // Hitungan ringkasan tab
        $countAll = PpdbRegistration::count();
        $countMenunggu = PpdbRegistration::where('status', 'menunggu_verifikasi')->count();
        $countTerverifikasi = PpdbRegistration::where('status', 'terverifikasi')->count();
        $countLulus = PpdbRegistration::where('status', 'lulus_seleksi')->count();

        $majors = $this->majors;

        return view('ppdb.dashboard.pendaftar', compact(
            'pendaftarList',
            'countAll',
            'countMenunggu',
            'countTerverifikasi',
            'countLulus',
            'search',
            'jurusan',
            'status',
            'jalur',
            'sort',
            'majors'
        ));
    }

    /**
     * 3. Halaman Detail Pendaftar untuk Update
     * GET /ppdb/dashboard/pendaftar/{id}
     */
    public function pendaftarDetail($id)
    {
        $pendaftar = PpdbRegistration::findOrFail($id);

        // Prev & Next student untuk kemudahan navigasi
        $prevStudent = PpdbRegistration::where('id', '<', $id)->orderBy('id', 'desc')->first();
        $nextStudent = PpdbRegistration::where('id', '>', $id)->orderBy('id', 'asc')->first();

        $majors = $this->majors;

        return view('ppdb.dashboard.detail', compact(
            'pendaftar',
            'prevStudent',
            'nextStudent',
            'majors'
        ));
    }

    /**
     * Simpan Update Data Pendaftar
     * PUT /ppdb/dashboard/pendaftar/{id}
     */
    public function pendaftarUpdate(Request $request, $id)
    {
        $pendaftar = PpdbRegistration::findOrFail($id);

        $validated = $request->validate([
            'nama_lengkap' => 'required|string|min:3|max:255',
            'nama_panggilan' => 'required|string|max:100',
            'nisn' => 'nullable|string|max:30',
            'nomor_kk' => 'nullable|string|max:30',
            'tempat_lahir' => 'required|string|max:100',
            'tanggal_lahir_hari' => 'required|string',
            'tanggal_lahir_bulan' => 'required|string',
            'tanggal_lahir_tahun' => 'required|string',
            'jenis_kelamin' => 'required|in:L,P',
            'alamat_lengkap' => 'nullable|string',
            'sekolah_pilihan_level' => 'nullable|string',
            'sekolah_pilihan_unit' => 'nullable|string',
            'tipe_pendaftar' => 'required|string',
            'pindah_tahun_ajaran' => 'nullable|string',
            'tanggal_mulai_masuk' => 'nullable|string',
            'kelas_pilihan' => 'required|string',
            'jurusan' => 'required|string',
            'jalur_seleksi' => 'required|string',
            'asal_sekolah' => 'required|string|max:255',
            'nomor_kontak_pendaftar' => 'required|string|min:10|max:20',
            'nomor_kontak_ortu' => 'required|string|min:10|max:20',
            'email' => 'nullable|email|max:150',
            'jenis_layanan' => 'nullable|array',
            'sumber_info' => 'nullable|array',
            'sumber_info_lainnya' => 'nullable|string|max:255',
            'alasan_minat' => 'nullable|string|max:255',
            'alasan_minat_lainnya' => 'nullable|string|max:255',
            'ukuran_seragam' => 'nullable|string|max:10',
            'status' => 'required|in:menunggu_verifikasi,terverifikasi,lulus_seleksi',
            'catatan' => 'nullable|string|max:1000',
        ]);

        $pendaftar->update($validated);

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Data pendaftar berhasil diperbarui!',
                'data' => $pendaftar
            ]);
        }

        return redirect()->route('ppdb.dashboard.pendaftar.detail', $pendaftar->id)
            ->with('success', 'Data pendaftar [' . $pendaftar->nomor_registrasi . '] berhasil diperbarui!');
    }

    /**
     * Update Status Cepat Pendaftar (AJAX / Form)
     * PATCH /ppdb/dashboard/{id}/status atau /ppdb/dashboard/pendaftar/{id}/status
     */
    public function updateStatus(Request $request, $id)
    {
        $validated = $request->validate([
            'status' => 'required|in:menunggu_verifikasi,terverifikasi,lulus_seleksi'
        ]);

        $registration = PpdbRegistration::findOrFail($id);
        $registration->update([
            'status' => $validated['status']
        ]);

        if ($request->wantsJson()) {
            return response()->json(['success' => true, 'status' => $validated['status']]);
        }

        return back()->with('success', 'Status pendaftar ' . $registration->nomor_registrasi . ' berhasil diperbarui.');
    }

    /**
     * Hapus Data Pendaftar
     * DELETE /ppdb/dashboard/pendaftar/{id}
     */
    public function pendaftarDestroy($id)
    {
        $pendaftar = PpdbRegistration::findOrFail($id);
        $noReg = $pendaftar->nomor_registrasi;
        $nama = $pendaftar->nama_lengkap;
        $pendaftar->delete();

        return redirect()->route('ppdb.dashboard.pendaftar')
            ->with('success', "Data pendaftar {$nama} ({$noReg}) berhasil dihapus.");
    }
}
