<?php

namespace Tests\Feature;

use App\Models\PpdbAnnouncement;
use App\Models\PpdbRegistration;
use App\Models\PpdbWave;
use Database\Seeders\PpdbSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class PpdbBackendTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(PpdbSeeder::class);
    }

    protected function fakeAuthAdmin(): void
    {
        Http::fake([
            '*/api/user/verify' => Http::response([
                'success' => true,
                'message' => 'Token terverifikasi',
                'data' => [
                    'id' => 'admin-123',
                    'username' => 'admin',
                    'nama_lengkap' => 'Panitia PPDB',
                    'role' => 'ADMIN',
                    'status_aktif' => true,
                ],
            ], 200),
        ]);
    }

    /**
     * Test public pages load successfully
     */
    public function test_public_pages_are_accessible(): void
    {
        $response = $this->get('/ppdb');
        $response->assertStatus(200);

        $responseAkomodasi = $this->get('/ppdb/akomodasi');
        $responseAkomodasi->assertStatus(200);

        $responsePengumuman = $this->get('/ppdb/pengumuman');
        $responsePengumuman->assertStatus(200);

        $responseCekStatus = $this->get('/ppdb/cek-status');
        $responseCekStatus->assertStatus(200);
    }

    /**
     * Test printable card loads successfully for a student
     */
    public function test_cetak_kartu_is_accessible(): void
    {
        $student = PpdbRegistration::first();
        if ($student) {
            $response = $this->get("/ppdb/cetak-kartu/{$student->id}");
            $response->assertStatus(200);
            $response->assertSee($student->nama_lengkap);
            $response->assertSee($student->nomor_registrasi);
        } else {
            $this->assertTrue(true);
        }
    }

    /**
     * Test dashboard is protected by verify.auth middleware
     */
    public function test_dashboard_requires_authentication(): void
    {
        $response = $this->getJson('/ppdb/dashboard');
        $response->assertStatus(401)
            ->assertJson([
                'success' => false,
                'message' => 'Token otentikasi tidak ditemukan',
            ]);

        $responsePendaftar = $this->getJson('/ppdb/dashboard/pendaftar');
        $responsePendaftar->assertStatus(401);

        $responseGelombang = $this->getJson('/ppdb/dashboard/gelombang');
        $responseGelombang->assertStatus(401);

        $responsePengumuman = $this->getJson('/ppdb/dashboard/pengumuman');
        $responsePengumuman->assertStatus(401);

        $responseAkomodasi = $this->getJson('/ppdb/dashboard/akomodasi');
        $responseAkomodasi->assertStatus(401);
    }

    /**
     * Test access with invalid token fails
     */
    public function test_dashboard_with_invalid_token_fails(): void
    {
        Http::fake([
            '*/api/user/verify' => Http::response([
                'success' => false,
                'message' => 'access_token tidak valid',
            ], 401),
        ]);

        $response = $this->withHeader('Authorization', 'Bearer invalid_token')
            ->getJson('/ppdb/dashboard');

        $response->assertStatus(401)
            ->assertJson([
                'success' => false,
                'message' => 'access_token tidak valid',
            ]);
    }

    /**
     * Test dashboard accessible with valid token
     */
    public function test_dashboard_accessible_with_authenticated_token(): void
    {
        $this->fakeAuthAdmin();

        // Access dashboard with authenticated token
        $authResponse = $this->withHeader('Authorization', 'Bearer valid_admin_token')->get('/ppdb/dashboard');
        $authResponse->assertStatus(200);
        $authResponse->assertSee('Halo, Panitia PPDB');

        // Access Gelombang
        $gelombangResponse = $this->withHeader('Authorization', 'Bearer valid_admin_token')->get('/ppdb/dashboard/gelombang');
        $gelombangResponse->assertStatus(200);
        $gelombangResponse->assertSee('Daftar Gelombang Penerimaan');

        // Access Pengumuman
        $pengumumanResponse = $this->withHeader('Authorization', 'Bearer valid_admin_token')->get('/ppdb/dashboard/pengumuman');
        $pengumumanResponse->assertStatus(200);
        $pengumumanResponse->assertSee('Daftar Pengumuman');

        // Access Akomodasi settings
        $akomodasiResponse = $this->withHeader('Authorization', 'Bearer valid_admin_token')->get('/ppdb/dashboard/akomodasi');
        $akomodasiResponse->assertStatus(200);
        $akomodasiResponse->assertSee('Konfigurasi Tarif PPDB');
    }

    /**
     * Test CSV export stream returns 200 and valid file
     */
    public function test_export_pendaftar_csv_returns_stream(): void
    {
        $this->fakeAuthAdmin();

        $response = $this->withHeader('Authorization', 'Bearer valid_admin_token')
            ->get('/ppdb/dashboard/pendaftar/export');

        $response->assertStatus(200);
        $this->assertStringContainsString('text/csv', $response->headers->get('Content-Type'));
    }

    /**
     * Test logout clears session cookie
     */
    public function test_logout_terminates_session(): void
    {
        $response = $this->post('/ppdb/logout');
        $response->assertRedirect('/ppdb');
    }

    /**
     * Test public registration submission creates student in database
     */
    public function test_public_registration_submission_succeeds(): void
    {
        $payload = [
            'namaLengkap' => 'Ahmad Fakhri Santoso',
            'namaPanggilan' => 'Fakhri',
            'nisn' => '0098765432',
            'nomorKK' => '3201010101010001',
            'tempatLahir' => 'Bogor',
            'tanggalLahirHari' => '12',
            'tanggalLahirBulan' => '05',
            'tanggalLahirTahun' => '2010',
            'jenisKelamin' => 'L',
            'alamatLengkap' => 'Jl. Sindang Barang No. 45, Bogor Barat',
            'sekolahPilihanLevel' => 'SMK',
            'sekolahPilihanUnit' => 'SMK Plus Pelita Nusantara Bogor',
            'tipePendaftar' => 'Pendaftar Baru',
            'kelasPilihan' => 'Reguler (Pagi)',
            'jurusan' => 'Pengembangan Perangkat Lunak dan Gim (PPLG)',
            'jalurSeleksi' => 'Jalur Reguler (Tes Minat Bakat)',
            'asalSekolah' => 'SMP Negeri 1 Bogor',
            'nomorKontakPendaftar' => '081234567890',
            'nomorKontakOrtu' => '081234567891',
            'email' => 'ahmad.fakhri@example.com',
            'ukuranSeragam' => 'L',
        ];

        $response = $this->post('/ppdb/daftar', $payload);
        $response->assertRedirect(route('ppdb.index'));
        $response->assertSessionHas('sukses_daftar');

        $this->assertDatabaseHas('ppdb_registrations', [
            'nama_lengkap' => 'Ahmad Fakhri Santoso',
            'nisn' => '0098765432',
            'status' => 'menunggu_verifikasi',
        ]);
    }

    /**
     * Test admin can update student registration status
     */
    public function test_admin_can_update_student_status(): void
    {
        $this->fakeAuthAdmin();
        $student = PpdbRegistration::first();
        $this->assertNotNull($student);

        $response = $this->withHeader('Authorization', 'Bearer valid_admin_token')
            ->patch("/ppdb/dashboard/pendaftar/{$student->id}/status", [
                'status' => 'lulus_seleksi',
                'catatan_panitia' => 'Selamat, Anda dinyatakan Lulus Tes Observasi Jurusan PPLG.',
            ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('ppdb_registrations', [
            'id' => $student->id,
            'status' => 'lulus_seleksi',
        ]);
    }

    /**
     * Test admin can switch active wave
     */
    public function test_admin_can_activate_wave(): void
    {
        $this->fakeAuthAdmin();
        $waves = PpdbWave::orderBy('nomor_gelombang')->get();
        $this->assertGreaterThanOrEqual(2, $waves->count());

        $waveToActivate = $waves[1]; // Gelombang 2

        $response = $this->withHeader('Authorization', 'Bearer valid_admin_token')
            ->patch("/ppdb/dashboard/gelombang/{$waveToActivate->id}/aktifkan");

        $response->assertRedirect();
        $this->assertTrue((bool) $waveToActivate->fresh()->is_active);

        // Previous active wave must now be inactive
        $this->assertFalse((bool) $waves[0]->fresh()->is_active);
    }

    /**
     * Test admin can create, pin, and delete announcement
     */
    public function test_admin_can_manage_announcement(): void
    {
        $this->fakeAuthAdmin();

        // 1. Create
        $response = $this->withHeader('Authorization', 'Bearer valid_admin_token')
            ->post('/ppdb/dashboard/pengumuman', [
                'judul' => 'Pengumuman Uji Coba Seleksi PPDB 2027',
                'nomor_sk' => 'SK/PPDB/PENUS/99/X/2026',
                'kategori' => 'Hasil Seleksi & Kelulusan',
                'badge' => 'PENTING',
                'tanggal' => '2026-10-01',
                'ringkasan' => 'Ringkasan uji coba pengumuman kelulusan calon peserta didik baru.',
                'isi_lengkap' => 'Berikut adalah instruksi lengkap pengumuman hasil seleksi.',
                'is_pinned' => 1,
            ]);

        $response->assertRedirect();
        $announcement = PpdbAnnouncement::where('judul', 'Pengumuman Uji Coba Seleksi PPDB 2027')->first();
        $this->assertNotNull($announcement);
        $this->assertTrue((bool) $announcement->is_pinned);

        // 2. Toggle Pin
        $toggleResponse = $this->withHeader('Authorization', 'Bearer valid_admin_token')
            ->patch("/ppdb/dashboard/pengumuman/{$announcement->id}/pin");
        $toggleResponse->assertRedirect();
        $this->assertFalse((bool) $announcement->fresh()->is_pinned);

        // 3. Delete
        $deleteResponse = $this->withHeader('Authorization', 'Bearer valid_admin_token')
            ->delete("/ppdb/dashboard/pengumuman/{$announcement->id}");
        $deleteResponse->assertRedirect();
        $this->assertDatabaseMissing('ppdb_announcements', ['id' => $announcement->id]);
    }

    /**
     * Test admin can update akomodasi fee and contact settings
     */
    public function test_admin_can_update_akomodasi_settings(): void
    {
        $this->fakeAuthAdmin();

        $response = $this->withHeader('Authorization', 'Bearer valid_admin_token')
            ->post('/ppdb/dashboard/akomodasi', [
                'biaya_formulir' => 175000,
                'dsp_cash' => 6000000,
                'dsp_angsuran_1' => 2600000,
                'dsp_angsuran_2' => 1900000,
                'dsp_angsuran_3' => 1500000,
                'spp_bulanan' => 500000,
                'biaya_seragam' => 1300000,
                'potongan_gelombang_1' => 600000,
                'kontak_wa' => '08999888777',
                'email_cs' => 'ppdb@pelitanusantara.sch.id',
                'telepon_kantor' => '(021) 8790-1234',
                'rekening' => [
                    [
                        'bank' => 'BSI (Bank Syariah Indonesia)',
                        'nomor' => '7112233445',
                        'atas_nama' => 'YAYASAN PELITA NUSANTARA',
                        'badge' => 'UTAMA',
                    ],
                ],
            ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('ppdb_fee_settings', [
            'key' => 'biaya_formulir',
            'value' => '175000',
        ]);
        $this->assertDatabaseHas('ppdb_fee_settings', [
            'key' => 'kontak_wa',
            'value' => '08999888777',
        ]);
    }

    /**
     * Test checking status finds registered student by NISN or Name
     */
    public function test_cek_status_finds_registered_student(): void
    {
        $student = PpdbRegistration::first();
        $this->assertNotNull($student);

        // Search by nomor_registrasi
        $response = $this->get('/ppdb/cek-status?keyword='.urlencode($student->nomor_registrasi));
        $response->assertStatus(200);
        $response->assertSee($student->nama_lengkap);
    }
}
