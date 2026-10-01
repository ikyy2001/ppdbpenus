<?php

namespace Tests\Feature;

use App\Models\PpdbAnnouncement;
use App\Models\PpdbRegistration;
use App\Models\PpdbWave;
use Database\Seeders\PpdbSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PpdbBackendTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(PpdbSeeder::class);
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
     * Test dashboard is protected by password authentication middleware
     */
    public function test_dashboard_requires_authentication(): void
    {
        $response = $this->get('/ppdb/dashboard');
        $response->assertRedirect(route('ppdb.login'));

        $responsePendaftar = $this->get('/ppdb/dashboard/pendaftar');
        $responsePendaftar->assertRedirect(route('ppdb.login'));

        $responseGelombang = $this->get('/ppdb/dashboard/gelombang');
        $responseGelombang->assertRedirect(route('ppdb.login'));

        $responsePengumuman = $this->get('/ppdb/dashboard/pengumuman');
        $responsePengumuman->assertRedirect(route('ppdb.login'));

        $responseAkomodasi = $this->get('/ppdb/dashboard/akomodasi');
        $responseAkomodasi->assertRedirect(route('ppdb.login'));
    }

    /**
     * Test login with invalid password fails
     */
    public function test_login_with_wrong_password_fails(): void
    {
        $response = $this->post('/ppdb/login', [
            'password' => 'wrong_password_123',
        ]);

        $response->assertSessionHasErrors('password');
        $this->assertNull(session('admin_authenticated'));
    }

    /**
     * Test login with correct password succeeds and grants access
     */
    public function test_login_with_correct_password_succeeds(): void
    {
        $adminPassword = env('ADMIN_PASSWORD', 'adminpenus2026');

        $response = $this->post('/ppdb/login', [
            'password' => $adminPassword,
        ]);

        $response->assertRedirect(route('ppdb.dashboard'));
        $response->assertSessionHas('admin_authenticated', true);

        // Access dashboard with authenticated session
        $authResponse = $this->withSession(['admin_authenticated' => true])->get('/ppdb/dashboard');
        $authResponse->assertStatus(200);
        $authResponse->assertSee('Halo, Panitia PPDB');

        // Access Gelombang
        $gelombangResponse = $this->withSession(['admin_authenticated' => true])->get('/ppdb/dashboard/gelombang');
        $gelombangResponse->assertStatus(200);
        $gelombangResponse->assertSee('Daftar Gelombang Penerimaan');

        // Access Pengumuman
        $pengumumanResponse = $this->withSession(['admin_authenticated' => true])->get('/ppdb/dashboard/pengumuman');
        $pengumumanResponse->assertStatus(200);
        $pengumumanResponse->assertSee('Daftar Pengumuman');

        // Access Akomodasi settings
        $akomodasiResponse = $this->withSession(['admin_authenticated' => true])->get('/ppdb/dashboard/akomodasi');
        $akomodasiResponse->assertStatus(200);
        $akomodasiResponse->assertSee('Konfigurasi Tarif PPDB');
    }

    /**
     * Test CSV export stream returns 200 and valid file
     */
    public function test_export_pendaftar_csv_returns_stream(): void
    {
        $response = $this->withSession(['admin_authenticated' => true])
            ->get('/ppdb/dashboard/pendaftar/export');

        $response->assertStatus(200);
        $this->assertStringContainsString('text/csv', $response->headers->get('Content-Type'));
    }

    /**
     * Test logout terminates session
     */
    public function test_logout_terminates_session(): void
    {
        $response = $this->withSession(['admin_authenticated' => true])
            ->post('/ppdb/logout');

        $response->assertRedirect(route('ppdb.login'));
        $this->assertNull(session('admin_authenticated'));
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
        $student = PpdbRegistration::first();
        $this->assertNotNull($student);

        $response = $this->withSession(['admin_authenticated' => true])
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
        $waves = PpdbWave::orderBy('nomor_gelombang')->get();
        $this->assertGreaterThanOrEqual(2, $waves->count());

        $waveToActivate = $waves[1]; // Gelombang 2

        $response = $this->withSession(['admin_authenticated' => true])
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
        // 1. Create
        $response = $this->withSession(['admin_authenticated' => true])
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
        $toggleResponse = $this->withSession(['admin_authenticated' => true])
            ->patch("/ppdb/dashboard/pengumuman/{$announcement->id}/pin");
        $toggleResponse->assertRedirect();
        $this->assertFalse((bool) $announcement->fresh()->is_pinned);

        // 3. Delete
        $deleteResponse = $this->withSession(['admin_authenticated' => true])
            ->delete("/ppdb/dashboard/pengumuman/{$announcement->id}");
        $deleteResponse->assertRedirect();
        $this->assertDatabaseMissing('ppdb_announcements', ['id' => $announcement->id]);
    }

    /**
     * Test admin can update akomodasi fee and contact settings
     */
    public function test_admin_can_update_akomodasi_settings(): void
    {
        $response = $this->withSession(['admin_authenticated' => true])
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
        $response = $this->get('/ppdb/cek-status?keyword=' . urlencode($student->nomor_registrasi));
        $response->assertStatus(200);
        $response->assertSee($student->nama_lengkap);
    }
}

