<?php

namespace Tests\Feature;

use App\Models\PpdbFeeSetting;
use App\Models\PpdbRegistration;
use App\Models\PpdbWave;
use Database\Seeders\PpdbSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class PpdbFrontendSynchronizationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(PpdbSeeder::class);
    }

    /**
     * STL-01: Public Akomodasi Page reflects dynamic fees and academic year.
     */
    public function test_akomodasi_page_displays_dynamic_fees_and_academic_year(): void
    {
        // Update fee settings
        PpdbFeeSetting::set('dsp_cash', 6250000, 'DSP Cash Lunas', 'biaya');
        PpdbFeeSetting::set('spp_bulanan', 525000, 'SPP Bulanan', 'biaya');
        PpdbFeeSetting::set('biaya_seragam', 2000000, 'Biaya Seragam', 'biaya');

        // Update active wave academic year
        $activeWave = PpdbWave::where('is_active', true)->first();
        $activeWave->update(['tahun_ajaran' => '2028/2029']);

        $response = $this->get('/ppdb/akomodasi');

        $response->assertStatus(200);
        $response->assertSee('Tahun Ajaran 2028/2029');
        $response->assertSee('Rp 6.250.000');
        $response->assertSee('525.000');
        $response->assertSee('Rp 2.000.000');
        // Check outdated year and hardcoded month are gone
        $response->assertDontSee('Tahun Ajaran 2024/2025');
        $response->assertDontSee('SPP Bulan Juli 2023');
    }

    /**
     * STL-02: Halaman Info Biaya tidak menampilkan data rekening bank.
     */
    public function test_info_biaya_page_does_not_display_bank_accounts(): void
    {
        PpdbFeeSetting::set('daftar_rekening', [[
            'bank' => 'Bank Mandiri Syariah Khusus',
            'nomor' => '9999-888-777',
            'atas_nama' => 'YAYASAN PPDB TEST',
        ]], 'Daftar Rekening Bank', 'rekening');

        $response = $this->get('/ppdb/akomodasi');

        $response->assertStatus(200);
        $response->assertSee('Info Biaya');
        $response->assertDontSee('REKENING RESMI');
        $response->assertDontSee('Bank Mandiri Syariah Khusus');
        $response->assertDontSee('9999-888-777');
    }

    /**
     * STL-03: Halaman Info Biaya hanya memuat rincian biaya dan tanya jawab pembayaran.
     */
    public function test_info_biaya_page_only_contains_fee_content(): void
    {
        $response = $this->get('/ppdb/akomodasi');

        $response->assertStatus(200);
        $response->assertSee('Uraian Pembiayaan PPDB');
        $response->assertSee('Tanya Jawab Seputar Pembiayaan');

        $response->assertDontSee('Akreditasi');
        $response->assertDontSee('5 Kompetensi Keahlian');
        $response->assertDontSee('Program Unggulan');
        $response->assertDontSee('Ekstrakurikuler');
        $response->assertDontSee('Mitra Kerjasama');
    }

    /**
     * STL-04: Registration Form displays dynamic wave schedules from database.
     */
    public function test_registration_form_displays_dynamic_wave_schedules(): void
    {
        $wave = PpdbWave::first();
        $wave->update([
            'nama' => 'Gelombang Alpha Unggulan',
            'tahun_ajaran' => '2027/2028',
            'tanggal_mulai' => Carbon::parse('2026-10-01'),
            'tanggal_selesai' => Carbon::parse('2026-12-31'),
        ]);

        $response = $this->get('/ppdb');

        $response->assertStatus(200);
        $response->assertSee('Gelombang Alpha Unggulan');
        $response->assertDontSee('15 September 2026 – 30 September 2028');
        $response->assertDontSee('01 Agustus 2026 – 21 Juni 2027');
    }

    /**
     * STL-05: Unified contacts are shared across views via AppServiceProvider View Composer.
     */
    public function test_unified_contacts_are_consistent_across_public_views(): void
    {
        PpdbFeeSetting::set('kontak_wa', '6281288889999', 'WA', 'kontak');
        PpdbFeeSetting::set('email_cs', 'kontak-resmi@smkpelitanusantara.sch.id', 'Email', 'kontak');

        // Check Index
        $resIndex = $this->get('/ppdb');
        $resIndex->assertStatus(200);
        $resIndex->assertSee('https://wa.me/6281288889999');
        $resIndex->assertDontSee('6281210868958');
        $resIndex->assertDontSee('informasi@smkpluspnb.sch.id');

        // Check Cek Status
        $resStatus = $this->get('/ppdb/cek-status');
        $resStatus->assertStatus(200);
        $resStatus->assertSee('https://wa.me/6281288889999');
        $resStatus->assertDontSee('6281210868958');

        // Check Akomodasi
        $resAkom = $this->get('/ppdb/akomodasi');
        $resAkom->assertStatus(200);
        $resAkom->assertSee('https://wa.me/6281288889999');
        $resAkom->assertDontSee('6281210868958');
    }

    /**
     * STL-06: Footer renders dynamic PPDB stats instead of fabricated visitor metrics.
     */
    public function test_footer_displays_dynamic_ppdb_stats_instead_of_mock_visitor_metrics(): void
    {
        $response = $this->get('/ppdb');

        $response->assertStatus(200);
        $response->assertSee('Informasi PPDB Terkini');
        $response->assertSee('Pendaftar Terdata:');
        $response->assertDontSee('Pengunjung Website');
        $response->assertDontSee('Pengunjung Hari ini : 30');
        $response->assertDontSee('Pengunjung Bulan ini : 1.596');
        $response->assertDontSee('Pengunjung Tahun ini : 42.831');
    }

    /**
     * STL-06: Admin layout renders dynamic notification metrics.
     */
    public function test_admin_layout_shows_unverified_registration_metrics(): void
    {
        // Generate auth token for mock admin request
        $token = 'test-valid-admin-token';
        Http::fake([
            '*/api/user/verify' => Http::response([
                'success' => true,
                'data' => [
                    'id' => 1,
                    'name' => 'Admin PPDB',
                    'email' => 'admin@penus.sch.id',
                    'role' => 'ADMIN',
                    'status_aktif' => true,
                ],
            ], 200),
        ]);

        // Add 1 pending registration
        PpdbRegistration::create([
            'nomor_registrasi' => 'PPDB-TEST-99999',
            'nama_lengkap' => 'Calon Siswa Baru Notif',
            'nama_panggilan' => 'Notif',
            'tempat_lahir' => 'Bogor',
            'tanggal_lahir_hari' => '1',
            'tanggal_lahir_bulan' => '1',
            'tanggal_lahir_tahun' => '2010',
            'jenis_kelamin' => 'L',
            'tipe_pendaftar' => 'Siswa Baru',
            'kelas_pilihan' => 'Kelas X',
            'jurusan' => 'Rekayasa Perangkat Lunak',
            'asal_sekolah' => 'SMP 1',
            'nomor_kontak_pendaftar' => '081234567890',
            'nomor_kontak_ortu' => '081234567890',
            'status' => 'menunggu_verifikasi',
        ]);

        $response = $this->withHeaders([
            'Authorization' => "Bearer {$token}",
        ])->get('/ppdb/dashboard');

        $response->assertStatus(200);
        $response->assertSee('Calon Siswa Baru Notif');
        $response->assertSee('Menunggu');
    }
}
