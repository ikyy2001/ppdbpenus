<?php

namespace App\Http\Controllers;

use App\Models\PpdbAnnouncement;
use App\Models\PpdbFeeSetting;
use App\Models\PpdbRegistration;
use App\Models\PpdbWave;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Symfony\Component\HttpFoundation\StreamedResponse;

class PpdbController extends Controller
{
    /**
     * Daftar 7 Kompetensi Keahlian SMK Plus Pelita Nusantara
     */
    protected array $majors = [
        'Rekayasa Perangkat Lunak' => 'RPL',
        'Teknik Komputer Jaringan' => 'TKJ',
        'Desain Komunikasi Visual' => 'DKV',
        'Layanan Perbankan' => 'LPB',
        'Teknik Otomasi Industri' => 'TOI',
    ];

    /**
     * Halaman Utama Form Pendaftaran PPDB
     * GET /ppdb
     */
    public function index()
    {
        $activeWave = PpdbWave::where('is_active', true)->first() ?? PpdbWave::first();
        $waves = PpdbWave::orderBy('tanggal_mulai')->get();
        $totalPendaftar = PpdbRegistration::count();
        $majors = $this->majors;
        $sumberInfoOptions = PpdbRegistration::SUMBER_INFO_OPTIONS;
        $alasanMinatOptions = PpdbRegistration::ALASAN_MINAT_OPTIONS;

        return view('ppdb.index', compact('activeWave', 'waves', 'totalPendaftar', 'majors', 'sumberInfoOptions', 'alasanMinatOptions'));
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
            'asalSekolah' => 'required|string|max:255',
            'nomorKontakPendaftar' => 'required|string|min:10|max:20',
            'nomorKontakOrtu' => 'required|string|min:10|max:20',
            // rfc,filter: menolak salah ketik umum seperti "nama@gmail" (tanpa .com) yang lolos aturan email biasa
            'email' => 'nullable|email:rfc,filter|max:150',
            'jenisLayanan' => 'nullable|array',
            'sumberInfo' => 'nullable|array',
            'sumberInfo.*' => ['string', Rule::in(PpdbRegistration::SUMBER_INFO_OPTIONS)],
            'sumberInfoLainnya' => 'nullable|string|max:255',
            'alasanMinat' => 'nullable|array',
            'alasanMinat.*' => ['string', Rule::in(PpdbRegistration::ALASAN_MINAT_OPTIONS)],
            'alasanMinatLainnya' => 'nullable|string|max:255',
        ], [
            'email.email' => 'Format alamat email tidak valid. Contoh: nama@gmail.com',
            'email.max' => 'Alamat email maksimal 150 karakter.',
            'sumberInfo.*.in' => 'Pilihan sumber informasi tidak dikenal.',
            'alasanMinat.*.in' => 'Pilihan alasan minat tidak dikenal.',
        ]);

        $activeWave = PpdbWave::where('is_active', true)->first();

        // Generate Nomor Registrasi Unik
        $randomSuffix = rand(10000, 99999);
        $nomorRegistrasi = 'PPDB-2027-'.$randomSuffix;

        while (PpdbRegistration::where('nomor_registrasi', $nomorRegistrasi)->exists()) {
            $nomorRegistrasi = 'PPDB-2027-'.rand(10000, 99999);
        }

        $registration = PpdbRegistration::create([
            'nomor_registrasi' => $nomorRegistrasi,
            'gelombang_id' => $activeWave?->id,
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
            'asal_sekolah' => $validated['asalSekolah'],
            'nomor_kontak_pendaftar' => $validated['nomorKontakPendaftar'],
            'nomor_kontak_ortu' => $validated['nomorKontakOrtu'],
            'email' => $validated['email'] ?? null,
            'jenis_layanan' => $validated['jenisLayanan'] ?? [],
            'sumber_info' => $validated['sumberInfo'] ?? [],
            'sumber_info_lainnya' => $validated['sumberInfoLainnya'] ?? null,
            'alasan_minat' => $validated['alasanMinat'] ?? [],
            'alasan_minat_lainnya' => $validated['alasanMinatLainnya'] ?? null,
            'status' => 'menunggu_verifikasi',
        ]);

        $tanggalDaftar = Carbon::now()->locale('id')->isoFormat('D MMMM Y');

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'noPendaftaran' => $nomorRegistrasi,
                'tanggalDaftar' => $tanggalDaftar,
                'data' => [
                    'id' => $registration->id,
                    'uuid' => $registration->uuid,
                    'namaLengkap' => $registration->nama_lengkap,
                    'nisn' => $registration->nisn,
                    'jurusan' => $registration->jurusan,
                    'asalSekolah' => $registration->asal_sekolah,
                ],
            ]);
        }

        return redirect()->route('ppdb.index')->with('sukses_daftar', [
            'id' => $registration->id,
            'uuid' => $registration->uuid,
            'noPendaftaran' => $nomorRegistrasi,
            'namaLengkap' => $registration->nama_lengkap,
        ]);
    }

    /**
     * Halaman Detail Akomodasi & Pembiayaan
     * GET /ppdb/akomodasi
     */
    public function akomodasi()
    {
        $activeWave = PpdbWave::where('is_active', true)->first() ?? PpdbWave::first();
        $kontakWa = PpdbFeeSetting::get('kontak_wa', '6281283921029');
        $rekeningList = PpdbFeeSetting::get('daftar_rekening', []);
        $biayaFormulir = PpdbFeeSetting::get('biaya_formulir', 150000);
        $dspCash = PpdbFeeSetting::get('dsp_cash', 5850000);
        $dspAngsuran1 = PpdbFeeSetting::get('dsp_angsuran_1', 2500000);
        $dspAngsuran2 = PpdbFeeSetting::get('dsp_angsuran_2', 1850000);
        $dspAngsuran3 = PpdbFeeSetting::get('dsp_angsuran_3', 1500000);
        $sppBulanan = PpdbFeeSetting::get('spp_bulanan', 450000);
        $biayaSeragam = PpdbFeeSetting::get('biaya_seragam', 1975000);
        $potonganGelombang1 = PpdbFeeSetting::get('potongan_gelombang_1', 500000);
        $majors = $this->majors;

        return view('ppdb.akomodasi', compact(
            'activeWave',
            'kontakWa',
            'rekeningList',
            'biayaFormulir',
            'dspCash',
            'dspAngsuran1',
            'dspAngsuran2',
            'dspAngsuran3',
            'sppBulanan',
            'biayaSeragam',
            'potonganGelombang1',
            'majors'
        ));
    }

    /**
     * Halaman Daftar Pengumuman PPDB Dinamis
     * GET /ppdb/pengumuman
     */
    public function pengumuman(Request $request)
    {
        $kategori = $request->get('kategori', 'semua');

        $announcements = PpdbAnnouncement::published()
            ->orderByDesc('is_pinned')
            ->orderByDesc('tanggal')
            ->get();

        $pengumumanList = $announcements->map(function ($item) {
            return [
                'id' => 'pengumuman-'.$item->id,
                'dbId' => $item->id,
                'judul' => $item->judul,
                'nomorSk' => $item->nomor_sk ?? '000/PPDB-SMKPNB/2026',
                'tanggal' => $item->tanggal ? $item->tanggal->locale('id')->isoFormat('D MMMM Y') : '-',
                'kategori' => $item->kategori,
                'badge' => $item->badge ?? 'INFO',
                'isPinned' => (bool) $item->is_pinned,
                'ringkasan' => $item->ringkasan ?? '',
                'isiLengkap' => $item->isi_lengkap,
                'isiLengkapHtml' => $item->isi_lengkap_html,
                'fileAttachment' => $item->file_nama ? [
                    'nama' => $item->file_nama,
                    'ukuran' => $item->file_ukuran ?? 'PDF Dokumen',
                    'url' => $item->file_url,
                ] : null,
                'actionLink' => $item->action_link,
                'actionText' => $item->action_text,
            ];
        })->toArray();

        $kategoriList = PpdbAnnouncement::published()->pluck('kategori')->unique()->values()->all();

        return view('ppdb.pengumuman', compact('pengumumanList', 'kategoriList', 'kategori'));
    }

    /**
     * Halaman & API Cek Status Pendaftar (Input NISN)
     * GET|POST /ppdb/cek-status
     */
    public function cekStatus(Request $request)
    {
        $nisnInput = $request->input('nisn') ?? $request->input('keyword') ?? $request->input('q');
        $cleanNisn = $nisnInput !== null ? trim((string) $nisnInput) : null;
        $isJson = $request->wantsJson() || $request->ajax() || $request->header('Accept') === 'application/json';

        // 1. Respon JSON untuk AJAX / Fetching API
        if ($isJson) {
            if (empty($cleanNisn)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Silakan masukkan nomor NISN calon siswa.',
                ], 422);
            }

            // Input harus NISN
            $pendaftar = PpdbRegistration::with('gelombang')
                ->where('nisn', $cleanNisn)
                ->first();

            // Toleransi jika user memasukkan nomor registrasi
            if (! $pendaftar) {
                $pendaftar = PpdbRegistration::with('gelombang')
                    ->where('nomor_registrasi', $cleanNisn)
                    ->first();
            }

            if (! $pendaftar) {
                return response()->json([
                    'success' => false,
                    'message' => "Data pendaftar dengan NISN '{$cleanNisn}' tidak ditemukan di sistem PPDB.",
                ], 404);
            }

            return response()->json([
                'success' => true,
                'data' => $this->formatPendaftarData($pendaftar),
            ]);
        }

        // 2. Respon Halaman Blade HTML
        $pendaftar = null;
        $searched = false;

        if (! empty($cleanNisn)) {
            $searched = true;
            $pendaftar = PpdbRegistration::with('gelombang')
                ->where(function ($query) use ($cleanNisn) {
                    $query->where('nisn', $cleanNisn)
                        ->orWhere('nomor_registrasi', $cleanNisn);
                })
                ->first();
        }

        $serverPendaftar = $pendaftar ? $this->formatPendaftarData($pendaftar) : null;
        $query = $cleanNisn;

        return view('ppdb.cek-status', compact('pendaftar', 'serverPendaftar', 'query', 'searched'));
    }

    /**
     * Format data record database pendaftar secara konsisten sesuai kolom DB
     */
    protected function formatPendaftarData(PpdbRegistration $pendaftar): array
    {
        $statusTypeMap = [
            'lulus_seleksi' => 'success',
            'terverifikasi' => 'info',
            'tidak_lulus' => 'danger',
            'menunggu_verifikasi' => 'warning',
        ];

        $keteranganMap = [
            'lulus_seleksi' => 'Selamat! Anda dinyatakan LULUS SELEKSI penerimaan peserta didik baru SMK Plus Pelita Nusantara.',
            'terverifikasi' => 'Berkas pendaftaran Anda telah berhasil DIVERIFIKASI oleh panitia seleKSI PPDB.',
            'tidak_lulus' => 'Mohon maaf, Anda belum memenuhi kualifikasi seleksi PPDB pada periode ini.',
            'menunggu_verifikasi' => 'Data pendaftaran Anda telah tercatat dan sedang dalam antrean verifikasi panitia.',
        ];

        $statusBadge = $pendaftar->status_badge;
        $statusLabel = $statusBadge['label'] ?? strtoupper(str_replace('_', ' ', $pendaftar->status));

        return [
            'id' => $pendaftar->id,
            'uuid' => $pendaftar->uuid,
            'nomor_registrasi' => $pendaftar->nomor_registrasi,
            'noPendaftaran' => $pendaftar->nomor_registrasi,
            'nisn' => $pendaftar->nisn,
            'nama_lengkap' => $pendaftar->nama_lengkap,
            'namaLengkap' => $pendaftar->nama_lengkap,
            'nama_panggilan' => $pendaftar->nama_panggilan,
            'namaPanggilan' => $pendaftar->nama_panggilan,
            'nomor_kk' => $pendaftar->nomor_kk,
            'jenis_kelamin' => $pendaftar->jenis_kelamin,
            'jenis_kelamin_label' => $pendaftar->jenis_kelamin === 'L' ? 'Laki-laki' : 'Perempuan',
            'tempat_lahir' => $pendaftar->tempat_lahir,
            'tanggal_lahir' => $pendaftar->tanggal_lahir_formatted,
            'tanggal_lahir_hari' => $pendaftar->tanggal_lahir_hari,
            'tanggal_lahir_bulan' => $pendaftar->tanggal_lahir_bulan,
            'tanggal_lahir_tahun' => $pendaftar->tanggal_lahir_tahun,
            'alamat_lengkap' => $pendaftar->alamat_lengkap,
            'asal_sekolah' => $pendaftar->asal_sekolah,
            'asalSekolah' => $pendaftar->asal_sekolah,
            'kelas_pilihan' => $pendaftar->kelas_pilihan,
            'kelasPilihan' => $pendaftar->kelas_pilihan,
            'jurusan' => $pendaftar->jurusan,
            'tipe_pendaftar' => $pendaftar->tipe_pendaftar,
            'sekolah_pilihan_level' => $pendaftar->sekolah_pilihan_level,
            'sekolah_pilihan_unit' => $pendaftar->sekolah_pilihan_unit,
            'nomor_kontak_pendaftar' => $pendaftar->nomor_kontak_pendaftar,
            'nomor_kontak_ortu' => $pendaftar->nomor_kontak_ortu,
            'email' => $pendaftar->email,
            'gelombang' => $pendaftar->gelombang?->nama ?? 'Gelombang 1',
            'status' => $pendaftar->status,
            'status_label' => $statusLabel,
            'statusUtama' => strtoupper(str_replace('_', ' ', $pendaftar->status ?? 'menunggu_verifikasi')),
            'status_type' => $statusTypeMap[$pendaftar->status] ?? 'warning',
            'statusType' => $statusTypeMap[$pendaftar->status] ?? 'warning',
            'keterangan_status' => $keteranganMap[$pendaftar->status] ?? 'Data pendaftaran Anda tercatat di sistem PPDB SMK Plus Pelita Nusantara.',
            'keteranganStatus' => $keteranganMap[$pendaftar->status] ?? 'Data pendaftaran Anda tercatat di sistem PPDB SMK Plus Pelita Nusantara.',
            'catatan' => $pendaftar->catatan,
            'catatanPanitia' => $pendaftar->catatan,
            'tanggal_daftar' => $pendaftar->created_at ? $pendaftar->created_at->format('d M Y') : 'Terdaftar',
            'tanggalDaftar' => $pendaftar->created_at ? $pendaftar->created_at->format('d M Y') : 'Terdaftar',
            'created_at' => $pendaftar->created_at?->toIso8601String(),
        ];
    }

    /**
     * Cetak Kartu Tanda Peserta / Bukti Pendaftaran Resmi (Print-Ready)
     * GET /ppdb/cetak-kartu/{uuid}
     */
    public function cetakKartu(string $uuid)
    {
        if (! Str::isUuid($uuid)) {
            abort(404, 'Kartu tanda peserta tidak ditemukan.');
        }

        $pendaftar = PpdbRegistration::with('gelombang')
            ->where('uuid', $uuid)
            ->firstOrFail();

        return view('ppdb.cetak-kartu', compact('pendaftar'));
    }

    /**
     * Export Data Pendaftar ke File CSV/Excel
     * GET /ppdb/dashboard/pendaftar/export
     */
    public function exportPendaftar(Request $request): StreamedResponse
    {
        $query = PpdbRegistration::with('gelombang');

        if ($request->filled('jurusan')) {
            $query->where('jurusan', $request->jurusan);
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nama_lengkap', 'LIKE', "%{$search}%")
                    ->orWhere('nomor_registrasi', 'LIKE', "%{$search}%")
                    ->orWhere('nisn', 'LIKE', "%{$search}%")
                    ->orWhere('asal_sekolah', 'LIKE', "%{$search}%");
            });
        }

        $pendaftarData = $query->latest()->get();
        $filename = 'data-pendaftar-ppdb-smk-penus-'.date('Y-m-d_His').'.csv';

        return response()->streamDownload(function () use ($pendaftarData) {
            $handle = fopen('php://output', 'w');

            // UTF-8 BOM untuk kompatibilitas Microsoft Excel
            fprintf($handle, chr(0xEF).chr(0xBB).chr(0xBF));

            // Header kolom
            fputcsv($handle, [
                'No. Registrasi',
                'Gelombang',
                'Status Seleksi',
                'Nama Lengkap',
                'Nama Panggilan',
                'NISN',
                'No. KK',
                'Jenis Kelamin',
                'Tempat Lahir',
                'Tgl Lahir',
                'Kompetensi Keahlian (Jurusan)',
                'Asal Sekolah',
                'No. HP Siswa',
                'No. HP Orang Tua',
                'Email',
                'Alamat Lengkap',
                'Tgl Mendaftar',
                'Catatan Panitia',
            ]);

            foreach ($pendaftarData as $row) {
                $csvRow = [
                    $row->nomor_registrasi,
                    $row->gelombang?->nama ?? 'Gelombang 1',
                    strtoupper(str_replace('_', ' ', $row->status)),
                    $row->nama_lengkap,
                    $row->nama_panggilan,
                    $row->nisn ?? '-',
                    $row->nomor_kk ?? '-',
                    $row->jenis_kelamin === 'L' ? 'Laki-Laki' : 'Perempuan',
                    $row->tempat_lahir,
                    $row->tanggal_lahir_formatted,
                    $row->jurusan,
                    $row->asal_sekolah,
                    $row->nomor_kontak_pendaftar,
                    $row->nomor_kontak_ortu,
                    $row->email ?? '-',
                    $row->alamat_lengkap ?? '-',
                    $row->created_at ? $row->created_at->format('Y-m-d H:i') : '-',
                    $row->catatan ?? '-',
                ];

                fputcsv($handle, array_map([$this, 'sanitizeCsvField'], $csvRow));
            }

            fclose($handle);
        }, $filename, [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ]);
    }

    /**
     * Sanitasi nilai kolom CSV untuk mencegah Formula / CSV Injection (CWE-1236).
     * Jika diawali formula operator (=, +, -, @, \t, \r), tambahkan prefix petik tunggal (').
     */
    protected function sanitizeCsvField(mixed $value): mixed
    {
        if (! is_string($value)) {
            return $value;
        }

        $dangerousChars = ['=', '+', '-', '@', "\t", "\r"];
        if ($value !== '' && in_array($value[0], $dangerousChars, true)) {
            return "'".$value;
        }

        return $value;
    }

    /**
     * 1. Halaman Dashboard Utama (Overview)
     * GET /ppdb/dashboard
     */
    public function dashboard(Request $request)
    {
        $authUser = $request->auth_user ?? $request->input('auth_user');

        $statusCounts = PpdbRegistration::query()
            ->selectRaw('status, count(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status');

        $totalPendaftar = (int) $statusCounts->sum();
        $totalMenunggu = (int) ($statusCounts['menunggu_verifikasi'] ?? 0);
        $totalTerverifikasi = (int) ($statusCounts['terverifikasi'] ?? 0);
        $totalLulus = (int) ($statusCounts['lulus_seleksi'] ?? 0);
        $totalTidakLulus = (int) ($statusCounts['tidak_lulus'] ?? 0);
        $todayCount = PpdbRegistration::whereDate('created_at', Carbon::today())->count();

        $activeWave = PpdbWave::where('is_active', true)->withCount('pendaftar')->first();
        $waves = PpdbWave::withCount('pendaftar')->orderBy('tanggal_mulai')->get();
        $totalPengumuman = PpdbAnnouncement::count();

        $jurusanCounts = $this->countPerMajor(
            PpdbRegistration::query()->selectRaw('jurusan, count(*) as total')->groupBy('jurusan')->pluck('total', 'jurusan')
        );

        $genderCounts = PpdbRegistration::query()
            ->selectRaw('jenis_kelamin, count(*) as total')
            ->groupBy('jenis_kelamin')
            ->pluck('total', 'jenis_kelamin');

        $topSekolah = PpdbRegistration::query()
            ->whereNotNull('asal_sekolah')
            ->selectRaw('asal_sekolah, count(*) as total')
            ->groupBy('asal_sekolah')
            ->orderByDesc('total')
            ->limit(5)
            ->pluck('total', 'asal_sekolah')
            ->toArray();

        // Tren 14 hari terakhir; dikelompokkan di PHP supaya sama di MySQL, PostgreSQL, maupun SQLite
        $trendStart = Carbon::today()->subDays(13);
        $dailyCounts = PpdbRegistration::where('created_at', '>=', $trendStart)
            ->pluck('created_at')
            ->countBy(fn ($date) => Carbon::parse($date)->toDateString());
        $trend = collect(range(0, 13))->map(function (int $offset) use ($trendStart, $dailyCounts) {
            $day = $trendStart->copy()->addDays($offset);

            return ['date' => $day, 'total' => (int) ($dailyCounts[$day->toDateString()] ?? 0)];
        })->all();

        $recentPendaftar = PpdbRegistration::with('gelombang')->latest()->take(7)->get();

        $majors = $this->majors;

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'data' => [
                    'user' => $authUser,
                    'stats' => [
                        'total_pendaftar' => $totalPendaftar,
                        'menunggu_verifikasi' => $totalMenunggu,
                        'terverifikasi' => $totalTerverifikasi,
                        'lulus_seleksi' => $totalLulus,
                        'tidak_lulus' => $totalTidakLulus,
                        'hari_ini' => $todayCount,
                        'total_pengumuman' => $totalPengumuman,
                    ],
                    'per_jurusan' => $jurusanCounts,
                    'per_gelombang' => $waves->map(fn (PpdbWave $wave) => [
                        'nama' => $wave->nama,
                        'kuota' => $wave->kuota,
                        'pendaftar' => $wave->pendaftar_count,
                        'is_active' => $wave->is_active,
                    ]),
                    'tren_harian' => collect($trend)->map(fn ($row) => ['tanggal' => $row['date']->toDateString(), 'total' => $row['total']]),
                ],
            ]);
        }

        return view('ppdb.dashboard.index', compact(
            'authUser',
            'totalPendaftar',
            'totalTerverifikasi',
            'totalMenunggu',
            'totalLulus',
            'totalTidakLulus',
            'todayCount',
            'activeWave',
            'waves',
            'totalPengumuman',
            'jurusanCounts',
            'genderCounts',
            'topSekolah',
            'trend',
            'recentPendaftar',
            'majors'
        ));
    }

    /**
     * Petakan nilai kolom jurusan ke daftar jurusan resmi ($this->majors). Nilai lama seperti
     * "Desain Komunikasi Visual (DKV)" atau kode "DKV" tetap terhitung; sisanya masuk "Lainnya".
     *
     * @param  Collection<string, int>  $rawCounts
     * @return array<string, int>
     */
    protected function countPerMajor($rawCounts): array
    {
        $counts = array_fill_keys(array_keys($this->majors), 0);
        $lainnya = 0;

        foreach ($rawCounts as $jurusan => $total) {
            $match = collect($this->majors)->search(function (string $code, string $name) use ($jurusan) {
                $value = strtolower(trim((string) $jurusan));

                return $value === strtolower($name)
                    || $value === strtolower($code)
                    || str_starts_with($value, strtolower($name))
                    || str_contains($value, '('.strtolower($code).')');
            });

            if ($match !== false) {
                $counts[$match] += (int) $total;
            } else {
                $lainnya += (int) $total;
            }
        }

        if ($lainnya > 0) {
            $counts['Lainnya'] = $lainnya;
        }

        return $counts;
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
        $gelombangId = $request->get('gelombang');
        $sort = $request->get('sort', 'terbaru');

        $query = PpdbRegistration::query()->select([
            'id',
            'nomor_registrasi',
            'nama_lengkap',
            'nama_panggilan',
            'jenis_kelamin',
            'nisn',
            'nomor_kk',
            'jurusan',
            'status',
            'created_at',
        ]);

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

        if ($gelombangId) {
            $query->where('gelombang_id', $gelombangId);
        }

        // Sorting
        match ($sort) {
            'terlama' => $query->oldest(),
            'nama_asc' => $query->orderBy('nama_lengkap', 'asc'),
            'nama_desc' => $query->orderBy('nama_lengkap', 'desc'),
            default => $query->latest(),
        };

        $pendaftarList = $query->paginate(8)->withQueryString();

        // Hitungan ringkasan tab (1 query agregasi efisien via toBase)
        $counts = PpdbRegistration::toBase()
            ->selectRaw("
                COUNT(*) as count_all,
                SUM(CASE WHEN status = 'menunggu_verifikasi' THEN 1 ELSE 0 END) as count_menunggu,
                SUM(CASE WHEN status = 'terverifikasi' THEN 1 ELSE 0 END) as count_terverifikasi,
                SUM(CASE WHEN status = 'lulus_seleksi' THEN 1 ELSE 0 END) as count_lulus
            ")
            ->first();

        $countAll = (int) ($counts->count_all ?? 0);
        $countMenunggu = (int) ($counts->count_menunggu ?? 0);
        $countTerverifikasi = (int) ($counts->count_terverifikasi ?? 0);
        $countLulus = (int) ($counts->count_lulus ?? 0);

        $majors = $this->majors;
        $waves = PpdbWave::all();

        return view('ppdb.dashboard.pendaftar', compact(
            'pendaftarList',
            'countAll',
            'countMenunggu',
            'countTerverifikasi',
            'countLulus',
            'search',
            'jurusan',
            'status',
            'gelombangId',
            'sort',
            'majors',
            'waves'
        ));
    }

    /**
     * 3. Halaman Detail Pendaftar
     * GET /ppdb/dashboard/pendaftar/{id}
     */
    public function pendaftarDetail($id)
    {
        $pendaftar = PpdbRegistration::with('gelombang')->findOrFail($id);
        $waves = PpdbWave::all();

        $prevStudent = PpdbRegistration::where('id', '<', $id)->orderBy('id', 'desc')->first();
        $nextStudent = PpdbRegistration::where('id', '>', $id)->orderBy('id', 'asc')->first();

        $majors = $this->majors;
        $alasanMinatOptions = PpdbRegistration::ALASAN_MINAT_OPTIONS;

        return view('ppdb.dashboard.detail', compact(
            'pendaftar',
            'prevStudent',
            'nextStudent',
            'majors',
            'waves',
            'alasanMinatOptions'
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
            'gelombang_id' => 'nullable|exists:ppdb_waves,id',
            'kelas_pilihan' => 'required|string',
            'jurusan' => 'required|string',
            'asal_sekolah' => 'required|string|max:255',
            'nomor_kontak_pendaftar' => 'required|string|min:10|max:20',
            'nomor_kontak_ortu' => 'required|string|min:10|max:20',
            'email' => 'nullable|email:rfc,filter|max:150',
            'alasan_minat' => 'nullable|array',
            // Alasan lama milik pendaftar ini (sebelum ada daftar pilihan) tetap boleh dipertahankan
            'alasan_minat.*' => ['string', Rule::in(array_merge(PpdbRegistration::ALASAN_MINAT_OPTIONS, $pendaftar->alasan_minat ?? []))],
            'alasan_minat_lainnya' => 'nullable|string|max:255',
            'status' => 'required|in:menunggu_verifikasi,terverifikasi,lulus_seleksi,tidak_lulus',
            'catatan' => 'nullable|string|max:1000',
        ]);

        // Checkbox yang semuanya tidak dicentang tidak ikut terkirim, jadi kosongkan secara eksplisit
        $validated['alasan_minat'] ??= [];

        $pendaftar->update($validated);

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Data pendaftar berhasil diperbarui!',
                'data' => $pendaftar,
            ]);
        }

        return redirect()->route('ppdb.dashboard.pendaftar.detail', $pendaftar->id)
            ->with('success', 'Data pendaftar ['.$pendaftar->nomor_registrasi.'] berhasil diperbarui!');
    }

    /**
     * Update Status Cepat Pendaftar (AJAX / Form)
     * PATCH /ppdb/dashboard/pendaftar/{id}/status
     */
    public function updateStatus(Request $request, $id)
    {
        $validated = $request->validate([
            'status' => 'required|in:menunggu_verifikasi,terverifikasi,lulus_seleksi,tidak_lulus',
        ]);

        $registration = PpdbRegistration::findOrFail($id);
        $registration->update([
            'status' => $validated['status'],
        ]);

        if ($request->wantsJson()) {
            return response()->json(['success' => true, 'status' => $validated['status']]);
        }

        return back()->with('success', 'Status pendaftar '.$registration->nomor_registrasi.' berhasil diperbarui.');
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

    /* =========================================================================
     * 4. MANAJEMEN GELOMBANG PPDB
     * ========================================================================= */

    public function gelombangIndex()
    {
        $waves = PpdbWave::withCount('pendaftar')->orderBy('tanggal_mulai', 'asc')->get();

        return view('ppdb.dashboard.gelombang', compact('waves'));
    }

    public function gelombangStore(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:100',
            'tahun_ajaran' => 'required|string|max:50',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
            'kuota' => 'required|integer|min:1',
            'biaya_formulir' => 'required|numeric|min:0',
            'is_active' => 'nullable|boolean',
            'deskripsi' => 'nullable|string|max:500',
        ]);

        $isActive = $request->boolean('is_active');

        if ($isActive) {
            PpdbWave::query()->update(['is_active' => false]);
        }

        PpdbWave::create([
            'nama' => $validated['nama'],
            'tahun_ajaran' => $validated['tahun_ajaran'],
            'tanggal_mulai' => $validated['tanggal_mulai'],
            'tanggal_selesai' => $validated['tanggal_selesai'],
            'kuota' => $validated['kuota'],
            'biaya_formulir' => $validated['biaya_formulir'],
            'is_active' => $isActive,
            'deskripsi' => $validated['deskripsi'] ?? null,
        ]);

        return redirect()->route('ppdb.dashboard.gelombang')->with('success', 'Gelombang pendaftaran baru berhasil ditambahkan.');
    }

    public function gelombangUpdate(Request $request, $id)
    {
        $wave = PpdbWave::findOrFail($id);

        $validated = $request->validate([
            'nama' => 'required|string|max:100',
            'tahun_ajaran' => 'required|string|max:50',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
            'kuota' => 'required|integer|min:1',
            'biaya_formulir' => 'required|numeric|min:0',
            'deskripsi' => 'nullable|string|max:500',
        ]);

        $wave->update($validated);

        return redirect()->route('ppdb.dashboard.gelombang')->with('success', "Gelombang [{$wave->nama}] berhasil diperbarui.");
    }

    public function gelombangSetActive($id)
    {
        PpdbWave::query()->update(['is_active' => false]);
        $wave = PpdbWave::findOrFail($id);
        $wave->update(['is_active' => true]);

        return back()->with('success', "Gelombang [{$wave->nama}] kini ditetapkan sebagai gelombang aktif!");
    }

    public function gelombangDestroy($id)
    {
        $wave = PpdbWave::withCount('pendaftar')->findOrFail($id);
        if ($wave->pendaftar_count > 0) {
            return back()->with('error', "Gelombang ini tidak dapat dihapus karena sudah memiliki {$wave->pendaftar_count} data pendaftar.");
        }

        $wave->delete();

        return redirect()->route('ppdb.dashboard.gelombang')->with('success', 'Gelombang berhasil dihapus.');
    }

    /* =========================================================================
     * 5. MANAJEMEN PENGUMUMAN PPDB
     * ========================================================================= */

    public function pengumumanIndex(Request $request)
    {
        $query = PpdbAnnouncement::query();

        if ($request->filled('kategori')) {
            $query->where('kategori', $request->kategori);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('judul', 'LIKE', "%{$search}%")
                    ->orWhere('nomor_sk', 'LIKE', "%{$search}%")
                    ->orWhere('ringkasan', 'LIKE', "%{$search}%");
            });
        }

        $announcements = $query->orderByDesc('is_pinned')->orderByDesc('tanggal')->paginate(8)->withQueryString();
        $categories = PpdbAnnouncement::pluck('kategori')->unique()->values()->all();

        return view('ppdb.dashboard.pengumuman', compact('announcements', 'categories'));
    }

    public function pengumumanStore(Request $request)
    {
        $validated = $request->validate([
            'judul' => 'required|string|max:255',
            'nomor_sk' => 'nullable|string|max:100',
            'kategori' => 'required|string|max:100',
            'badge' => 'nullable|string|max:50',
            'tanggal' => 'required|date',
            'is_pinned' => 'nullable|boolean',
            'ringkasan' => 'required|string',
            'isi_lengkap' => 'required|string',
            'action_link' => 'nullable|string|max:255',
            'action_text' => 'nullable|string|max:100',
            'file_lampiran' => 'nullable|file|mimes:pdf,doc,docx,jpg,png,jpeg|max:10240', // 10MB
        ]);

        $filePath = null;
        $fileName = null;
        $fileSize = null;

        if ($request->hasFile('file_lampiran')) {
            $file = $request->file('file_lampiran');
            $fileName = $file->getClientOriginalName();
            $fileSize = round($file->getSize() / 1024, 1).' KB';
            if ($file->getSize() > 1048576) {
                $fileSize = round($file->getSize() / 1048576, 1).' MB';
            }
            $filePath = $file->store('pengumuman', 'public');
        }

        PpdbAnnouncement::create([
            'judul' => $validated['judul'],
            'nomor_sk' => $validated['nomor_sk'] ?? null,
            'kategori' => $validated['kategori'],
            'badge' => $validated['badge'] ?? 'INFO',
            'tanggal' => $validated['tanggal'],
            'is_pinned' => $request->boolean('is_pinned'),
            'ringkasan' => $validated['ringkasan'],
            'isi_lengkap' => $validated['isi_lengkap'],
            'file_nama' => $fileName,
            'file_path' => $filePath,
            'file_ukuran' => $fileSize,
            'action_link' => $validated['action_link'] ?? null,
            'action_text' => $validated['action_text'] ?? null,
            'is_active' => true,
        ]);

        return redirect()->route('ppdb.dashboard.pengumuman')->with('success', 'Pengumuman baru berhasil dipublikasikan!');
    }

    public function pengumumanUpdate(Request $request, $id)
    {
        $announcement = PpdbAnnouncement::findOrFail($id);

        $validated = $request->validate([
            'judul' => 'required|string|max:255',
            'nomor_sk' => 'nullable|string|max:100',
            'kategori' => 'required|string|max:100',
            'badge' => 'nullable|string|max:50',
            'tanggal' => 'required|date',
            'is_pinned' => 'nullable|boolean',
            'ringkasan' => 'required|string',
            'isi_lengkap' => 'required|string',
            'action_link' => 'nullable|string|max:255',
            'action_text' => 'nullable|string|max:100',
            'file_lampiran' => 'nullable|file|mimes:pdf,doc,docx,jpg,png,jpeg|max:10240',
        ]);

        if ($request->hasFile('file_lampiran')) {
            // Hapus file lama jika ada di storage lokal
            if ($announcement->file_path && ! str_starts_with($announcement->file_path, 'http')) {
                Storage::disk('public')->delete($announcement->file_path);
            }

            $file = $request->file('file_lampiran');
            $announcement->file_nama = $file->getClientOriginalName();
            $fileSize = round($file->getSize() / 1024, 1).' KB';
            if ($file->getSize() > 1048576) {
                $fileSize = round($file->getSize() / 1048576, 1).' MB';
            }
            $announcement->file_ukuran = $fileSize;
            $announcement->file_path = $file->store('pengumuman', 'public');
        }

        $announcement->update([
            'judul' => $validated['judul'],
            'nomor_sk' => $validated['nomor_sk'] ?? null,
            'kategori' => $validated['kategori'],
            'badge' => $validated['badge'] ?? 'INFO',
            'tanggal' => $validated['tanggal'],
            'is_pinned' => $request->boolean('is_pinned'),
            'ringkasan' => $validated['ringkasan'],
            'isi_lengkap' => $validated['isi_lengkap'],
            'action_link' => $validated['action_link'] ?? null,
            'action_text' => $validated['action_text'] ?? null,
        ]);

        return redirect()->route('ppdb.dashboard.pengumuman')->with('success', 'Pengumuman berhasil diperbarui.');
    }

    public function pengumumanTogglePin($id)
    {
        $announcement = PpdbAnnouncement::findOrFail($id);
        $announcement->is_pinned = ! $announcement->is_pinned;
        $announcement->save();

        $status = $announcement->is_pinned ? 'disematkan ke atas' : 'dilepas dari sematan';

        return back()->with('success', "Pengumuman berhasil {$status}.");
    }

    public function pengumumanDestroy($id)
    {
        $announcement = PpdbAnnouncement::findOrFail($id);
        if ($announcement->file_path && ! str_starts_with($announcement->file_path, 'http')) {
            Storage::disk('public')->delete($announcement->file_path);
        }
        $announcement->delete();

        return redirect()->route('ppdb.dashboard.pengumuman')->with('success', 'Pengumuman berhasil dihapus.');
    }

    /* =========================================================================
     * 6. MANAJEMEN AKOMODASI & BIAYA
     * ========================================================================= */

    public function akomodasiIndex()
    {
        $biayaFormulir = PpdbFeeSetting::get('biaya_formulir', 150000);
        $dspCash = PpdbFeeSetting::get('dsp_cash', 5850000);
        $dspAngsuran1 = PpdbFeeSetting::get('dsp_angsuran_1', 2500000);
        $dspAngsuran2 = PpdbFeeSetting::get('dsp_angsuran_2', 1850000);
        $dspAngsuran3 = PpdbFeeSetting::get('dsp_angsuran_3', 1500000);
        $sppBulanan = PpdbFeeSetting::get('spp_bulanan', 450000);
        $biayaSeragam = PpdbFeeSetting::get('biaya_seragam', 1250000);
        $potonganGelombang1 = PpdbFeeSetting::get('potongan_gelombang_1', 500000);

        $rekeningList = PpdbFeeSetting::get('daftar_rekening', []);
        $kontakWa = PpdbFeeSetting::get('kontak_wa', '6281283921029');
        $emailCs = PpdbFeeSetting::get('email_cs', 'ppdb@smkpelitanusantara.sch.id');
        $teleponKantor = PpdbFeeSetting::get('telepon_kantor', '(021) 8790-1234');

        return view('ppdb.dashboard.akomodasi', compact(
            'biayaFormulir',
            'dspCash',
            'dspAngsuran1',
            'dspAngsuran2',
            'dspAngsuran3',
            'sppBulanan',
            'biayaSeragam',
            'potonganGelombang1',
            'rekeningList',
            'kontakWa',
            'emailCs',
            'teleponKantor'
        ));
    }

    public function akomodasiUpdate(Request $request)
    {
        $validated = $request->validate([
            'biaya_formulir' => 'required|numeric|min:0',
            'dsp_cash' => 'required|numeric|min:0',
            'dsp_angsuran_1' => 'required|numeric|min:0',
            'dsp_angsuran_2' => 'required|numeric|min:0',
            'dsp_angsuran_3' => 'required|numeric|min:0',
            'spp_bulanan' => 'required|numeric|min:0',
            'biaya_seragam' => 'required|numeric|min:0',
            'potongan_gelombang_1' => 'required|numeric|min:0',
            'kontak_wa' => 'required|string|max:30',
            'email_cs' => 'required|email|max:100',
            'telepon_kantor' => 'nullable|string|max:50',
            'rekening' => 'nullable|array',
            'rekening.*.bank' => 'required|string|max:100',
            'rekening.*.nomor' => 'required|string|max:100',
            'rekening.*.atas_nama' => 'required|string|max:150',
            'rekening.*.badge' => 'nullable|string|max:50',
        ]);

        PpdbFeeSetting::set('biaya_formulir', $validated['biaya_formulir'], 'Biaya Formulir', 'biaya');
        PpdbFeeSetting::set('dsp_cash', $validated['dsp_cash'], 'DSP Cash Lunas', 'biaya');
        PpdbFeeSetting::set('dsp_angsuran_1', $validated['dsp_angsuran_1'], 'DSP Angsuran 1', 'biaya');
        PpdbFeeSetting::set('dsp_angsuran_2', $validated['dsp_angsuran_2'], 'DSP Angsuran 2', 'biaya');
        PpdbFeeSetting::set('dsp_angsuran_3', $validated['dsp_angsuran_3'], 'DSP Angsuran 3', 'biaya');
        PpdbFeeSetting::set('spp_bulanan', $validated['spp_bulanan'], 'SPP Bulanan', 'biaya');
        PpdbFeeSetting::set('biaya_seragam', $validated['biaya_seragam'], 'Biaya Seragam', 'biaya');
        PpdbFeeSetting::set('potongan_gelombang_1', $validated['potongan_gelombang_1'], 'Potongan Gelombang 1', 'biaya');

        PpdbFeeSetting::set('kontak_wa', preg_replace('/[^0-9]/', '', $validated['kontak_wa']), 'WhatsApp Helpdesk', 'kontak');
        PpdbFeeSetting::set('email_cs', $validated['email_cs'], 'Email CS', 'kontak');
        PpdbFeeSetting::set('telepon_kantor', $validated['telepon_kantor'] ?? '-', 'Telepon Kantor', 'kontak');

        if (isset($validated['rekening']) && is_array($validated['rekening'])) {
            PpdbFeeSetting::set('daftar_rekening', array_values($validated['rekening']), 'Daftar Rekening Bank', 'rekening');
        }

        return redirect()->route('ppdb.dashboard.akomodasi')->with('success', 'Pengaturan biaya, rekening transfer, dan kontak panitia berhasil disimpan!');
    }
}
