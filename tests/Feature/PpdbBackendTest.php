<?php

namespace Tests\Feature;

use App\Models\PpdbAnnouncement;
use App\Models\PpdbRegistration;
use App\Models\PpdbWave;
use Database\Seeders\PpdbSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use PHPUnit\Framework\Attributes\DataProvider;
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
     * Test pengumuman page renders isi_lengkap as markdown HTML
     */
    public function test_pengumuman_page_renders_isi_lengkap_markdown(): void
    {
        $announcement = PpdbAnnouncement::first();
        $this->assertNotNull($announcement);
        $this->assertNotEmpty($announcement->isi_lengkap_html);

        $response = $this->get('/ppdb/pengumuman');
        $response->assertStatus(200);
        $response->assertViewHas('pengumumanList');

        $list = $response->viewData('pengumumanList');
        $this->assertIsArray($list);
        $this->assertNotEmpty($list);
        $this->assertArrayHasKey('isiLengkapHtml', $list[0]);
        $this->assertStringContainsString('<', $list[0]['isiLengkapHtml']);
    }

    /**
     * SEC-01: Test printable card loads successfully via UUID
     */
    public function test_cetak_kartu_is_accessible_via_uuid(): void
    {
        $student = PpdbRegistration::first();
        $this->assertNotNull($student);
        $this->assertNotEmpty($student->uuid);

        $response = $this->get("/ppdb/cetak-kartu/{$student->uuid}");
        $response->assertStatus(200);
        $response->assertSee($student->nama_lengkap);
        $response->assertSee($student->nomor_registrasi);
    }

    /**
     * SEC-01: Test sequential numeric IDs return 404 to prevent IDOR enumeration
     */
    public function test_cetak_kartu_blocks_sequential_integer_idor(): void
    {
        $student = PpdbRegistration::first();
        $this->assertNotNull($student);

        // Access via sequential integer ID must be rejected with 404
        $response = $this->get("/ppdb/cetak-kartu/{$student->id}");
        $response->assertStatus(404);

        $response1 = $this->get('/ppdb/cetak-kartu/1');
        $response1->assertStatus(404);
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
        $authResponse->assertSee('Panitia PPDB'); // nama dari auth service tampil di header & sapaan
        $authResponse->assertSee('Gelombang Aktif');
        $authResponse->assertSee('Status Seleksi');
        $authResponse->assertSee('Peminat Jurusan');

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
            'asalSekolah' => 'SMP Negeri 1 Bogor',
            'nomorKontakPendaftar' => '081234567890',
            'nomorKontakOrtu' => '081234567891',
            'email' => 'ahmad.fakhri@example.com',
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
     * Test public registration AJAX submission returns NISN in data
     */
    public function test_public_registration_ajax_submission_includes_nisn(): void
    {
        $payload = [
            'namaLengkap' => 'Dewi Sartika Putri',
            'namaPanggilan' => 'Dewi',
            'nisn' => '0089123456',
            'nomorKK' => '3201010101010002',
            'tempatLahir' => 'Bogor',
            'tanggalLahirHari' => '15',
            'tanggalLahirBulan' => '08',
            'tanggalLahirTahun' => '2010',
            'jenisKelamin' => 'P',
            'alamatLengkap' => 'Jl. Pajajaran No. 10',
            'sekolahPilihanLevel' => 'SMK',
            'sekolahPilihanUnit' => 'SMK Plus Pelita Nusantara Bogor',
            'tipePendaftar' => 'Pendaftar Baru',
            'kelasPilihan' => 'Reguler (Pagi)',
            'jurusan' => 'Desain Komunikasi Visual (DKV)',
            'asalSekolah' => 'SMP Negeri 2 Cibinong',
            'nomorKontakPendaftar' => '081234567892',
            'nomorKontakOrtu' => '081234567893',
        ];

        $response = $this->postJson('/ppdb/daftar', $payload);

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'data' => [
                    'namaLengkap' => 'Dewi Sartika Putri',
                    'nisn' => '0089123456',
                ],
            ]);

        $this->assertDatabaseHas('ppdb_registrations', [
            'nama_lengkap' => 'Dewi Sartika Putri',
            'nisn' => '0089123456',
        ]);
    }

    /**
     * Payload minimal pendaftaran yang valid (sekolah swasta: tanpa jalur seleksi & ukuran seragam)
     */
    protected function validRegistrationPayload(array $overrides = []): array
    {
        return array_merge([
            'namaLengkap' => 'Rizky Pratama',
            'namaPanggilan' => 'Rizky',
            'tempatLahir' => 'Bogor',
            'tanggalLahirHari' => '1',
            'tanggalLahirBulan' => '2',
            'tanggalLahirTahun' => '2011',
            'jenisKelamin' => 'L',
            'tipePendaftar' => 'Pendaftar Baru',
            'kelasPilihan' => 'Kelas 10',
            'jurusan' => 'Rekayasa Perangkat Lunak',
            'asalSekolah' => 'SMP Negeri 3 Cibinong',
            'nomorKontakPendaftar' => '081234567800',
            'nomorKontakOrtu' => '081234567801',
        ], $overrides);
    }

    #[DataProvider('invalidEmailProvider')]
    public function test_registration_rejects_invalid_email(string $email): void
    {
        $response = $this->postJson('/ppdb/daftar', $this->validRegistrationPayload(['email' => $email]));

        $response->assertStatus(422)->assertJsonValidationErrors(['email']);
        $this->assertSame('Format alamat email tidak valid. Contoh: nama@gmail.com', $response->json('errors.email.0'));
        $this->assertDatabaseMissing('ppdb_registrations', ['nama_lengkap' => 'Rizky Pratama']);
    }

    public static function invalidEmailProvider(): array
    {
        return [
            'tanpa @' => ['rizky.gmail.com'],
            'tanpa domain tingkat atas' => ['rizky@gmail'],
            'dua @' => ['rizky@@gmail.com'],
            'ada spasi' => ['rizky pratama@gmail.com'],
            'titik ganda' => ['rizky@gmail..com'],
        ];
    }

    public function test_registration_accepts_valid_or_empty_email(): void
    {
        $this->postJson('/ppdb/daftar', $this->validRegistrationPayload(['email' => 'rizky.pratama@gmail.co.id']))
            ->assertOk();
        $this->postJson('/ppdb/daftar', $this->validRegistrationPayload(['namaLengkap' => 'Tanpa Email']))
            ->assertOk();

        $this->assertDatabaseHas('ppdb_registrations', ['nama_lengkap' => 'Rizky Pratama', 'email' => 'rizky.pratama@gmail.co.id']);
        $this->assertDatabaseHas('ppdb_registrations', ['nama_lengkap' => 'Tanpa Email', 'email' => null]);
    }

    public function test_registration_stores_multiple_sumber_info_and_alasan_minat_without_jalur(): void
    {
        $response = $this->postJson('/ppdb/daftar', $this->validRegistrationPayload([
            'sumberInfo' => ['Brosur', 'Youtube', 'Lain-lain'],
            'sumberInfoLainnya' => 'Pameran pendidikan',
            'alasanMinat' => ['Digitalisasi Sekolah', 'Kualitas Guru yang Baik', 'Lainnya'],
            'alasanMinatLainnya' => 'Dekat tempat kerja orang tua',
        ]));

        $response->assertOk()->assertJsonMissingPath('data.jalurSeleksi');

        $registration = PpdbRegistration::where('nama_lengkap', 'Rizky Pratama')->firstOrFail();
        $this->assertSame(['Brosur', 'Youtube', 'Lain-lain'], $registration->sumber_info);
        $this->assertSame(['Digitalisasi Sekolah', 'Kualitas Guru yang Baik', 'Lainnya'], $registration->alasan_minat);
        $this->assertSame('Dekat tempat kerja orang tua', $registration->alasan_minat_lainnya);
        $this->assertNull($registration->jalur_seleksi);
        $this->assertNull($registration->ukuran_seragam);
    }

    public function test_registration_rejects_unknown_alasan_and_sumber_options(): void
    {
        $this->postJson('/ppdb/daftar', $this->validRegistrationPayload([
            'sumberInfo' => ['Brosur', 'Radio'],
            'alasanMinat' => ['Bukan Pilihan'],
        ]))->assertStatus(422)->assertJsonValidationErrors(['sumberInfo.1', 'alasanMinat.0']);
    }

    public function test_registration_form_offers_checkbox_lists_and_no_jalur_or_seragam(): void
    {
        $response = $this->get('/ppdb');

        $response->assertOk()
            ->assertSee('Sumber Informasi PPDB')
            ->assertSee('Alasan Utama Minat Mendaftar')
            ->assertSee('Reputasi Sekolah Baik')
            ->assertDontSee('Jalur Seleksi')
            ->assertDontSee('Ukuran Seragam');
    }

    public function test_admin_can_update_alasan_minat_as_list(): void
    {
        $this->fakeAuthAdmin();
        $student = PpdbRegistration::firstOrFail();

        $payload = [
            'nama_lengkap' => $student->nama_lengkap,
            'nama_panggilan' => $student->nama_panggilan,
            'tempat_lahir' => $student->tempat_lahir,
            'tanggal_lahir_hari' => $student->tanggal_lahir_hari,
            'tanggal_lahir_bulan' => $student->tanggal_lahir_bulan,
            'tanggal_lahir_tahun' => $student->tanggal_lahir_tahun,
            'jenis_kelamin' => $student->jenis_kelamin,
            'tipe_pendaftar' => $student->tipe_pendaftar,
            'kelas_pilihan' => $student->kelas_pilihan,
            'jurusan' => $student->jurusan,
            'asal_sekolah' => $student->asal_sekolah,
            'nomor_kontak_pendaftar' => '081234567890',
            'nomor_kontak_ortu' => '081234567891',
            'status' => $student->status,
            // Alasan lama milik pendaftar ini tetap boleh dipertahankan bersama pilihan baru
            'alasan_minat' => [...$student->alasan_minat, 'Reputasi Sekolah Baik'],
        ];

        $this->withHeaders(['Authorization' => 'Bearer valid-mock-token'])
            ->put("/ppdb/dashboard/pendaftar/{$student->id}", $payload)
            ->assertRedirect();

        $this->assertSame([...$student->alasan_minat, 'Reputasi Sekolah Baik'], $student->fresh()->alasan_minat);

        // Semua centang dilepas: alasan dikosongkan
        unset($payload['alasan_minat']);
        $this->withHeaders(['Authorization' => 'Bearer valid-mock-token'])
            ->put("/ppdb/dashboard/pendaftar/{$student->id}", $payload)
            ->assertRedirect();

        $this->assertSame([], $student->fresh()->alasan_minat);
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

    /**
     * Test fetching /ppdb/cek-status returns valid JSON for registered NISN
     */
    public function test_cek_status_json_returns_data_for_valid_nisn(): void
    {
        $student = PpdbRegistration::whereNotNull('nisn')->first();
        $this->assertNotNull($student);

        $response = $this->getJson('/ppdb/cek-status?nisn='.urlencode($student->nisn));

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'data' => [
                'id' => $student->id,
                'nomor_registrasi' => $student->nomor_registrasi,
                'nisn' => $student->nisn,
                'nama_lengkap' => $student->nama_lengkap,
                'jurusan' => $student->jurusan,
                'status' => $student->status,
            ],
        ]);

        // Verifikasi tidak ada field dummy
        $json = $response->json();
        $this->assertArrayNotHasKey('jadwalObservasi', $json['data']);
        $this->assertArrayNotHasKey('rincianBiaya', $json['data']);
        $this->assertArrayNotHasKey('tahapan', $json['data']);
    }

    /**
     * Test fetching /ppdb/cek-status with nonexistent NISN returns 404
     */
    public function test_cek_status_json_returns_404_for_unknown_nisn(): void
    {
        $response = $this->getJson('/ppdb/cek-status?nisn=0000000000');

        $response->assertStatus(404);
        $response->assertJson([
            'success' => false,
        ]);
    }

    /**
     * Test fetching /ppdb/cek-status with empty NISN returns 422
     */
    public function test_cek_status_json_returns_422_when_nisn_is_missing(): void
    {
        $response = $this->getJson('/ppdb/cek-status');

        $response->assertStatus(422);
        $response->assertJson([
            'success' => false,
        ]);
    }

    /**
     * SEC-02: Test checking status with wildcard substring does NOT leak records
     */
    public function test_cek_status_wildcard_substring_does_not_leak_records(): void
    {
        // Wildcard search like "2027" or "PPDB" or partial year must not return arbitrary records
        $response = $this->get('/ppdb/cek-status?keyword=2027');
        $response->assertStatus(200);
        $response->assertSee('Data Tidak Ditemukan');
        $response->assertDontSee('PPDB-2027-');

        $responsePpdb = $this->get('/ppdb/cek-status?keyword=PPDB');
        $responsePpdb->assertStatus(200);
        $responsePpdb->assertSee('Data Tidak Ditemukan');
        $responsePpdb->assertDontSee('PPDB-2027-');
    }

    /**
     * SEC-03: Test CSV export stream sanitizes formula injection prefixes
     */
    public function test_export_pendaftar_csv_sanitizes_formula_injection(): void
    {
        $this->fakeAuthAdmin();

        // Create student with malicious formula prefixes
        PpdbRegistration::create([
            'nomor_registrasi' => 'PPDB-2027-99991',
            'nama_lengkap' => '=cmd|\' /C calc\'!A0',
            'nama_panggilan' => '+62812000000',
            'nisn' => '0091112223',
            'tempat_lahir' => '-FormulaMinus',
            'tanggal_lahir_hari' => '01',
            'tanggal_lahir_bulan' => '01',
            'tanggal_lahir_tahun' => '2010',
            'jenis_kelamin' => 'L',
            'alamat_lengkap' => '@SUM(1+1)',
            'tipe_pendaftar' => 'Pendaftar Baru',
            'kelas_pilihan' => 'Reguler',
            'jurusan' => 'RPL',
            'asal_sekolah' => "\tTabIndentSchool",
            'nomor_kontak_pendaftar' => '+6281234567890',
            'nomor_kontak_ortu' => '081234567891',
        ]);

        $response = $this->withHeader('Authorization', 'Bearer valid_admin_token')
            ->get('/ppdb/dashboard/pendaftar/export');

        $response->assertStatus(200);
        $content = $response->streamedContent();

        // Assert formula characters are prepended with single quote
        $this->assertStringContainsString("'=cmd|", $content);
        $this->assertStringContainsString("'+62812000000", $content);
        $this->assertStringContainsString("'-FormulaMinus", $content);
        $this->assertStringContainsString("'@SUM(1+1)", $content);
        $this->assertStringContainsString("'\tTabIndentSchool", $content);
        $this->assertStringContainsString("'+6281234567890", $content);
    }

    /**
     * SEC-04: Test rate limiting on public registration and status lookups
     */
    public function test_rate_limiting_on_registration_and_status(): void
    {
        // 1. Test POST /ppdb/daftar throttled after 6 requests
        for ($i = 0; $i < 6; $i++) {
            $this->postJson('/ppdb/daftar', []);
        }
        $responseThrottleDaftar = $this->postJson('/ppdb/daftar', []);
        $responseThrottleDaftar->assertStatus(429);

        // 2. Test GET /ppdb/cek-status throttled after 20 requests
        for ($i = 0; $i < 20; $i++) {
            $this->getJson('/ppdb/cek-status?nisn=0000000000');
        }
        $responseThrottleStatus = $this->getJson('/ppdb/cek-status?nisn=0000000000');
        $responseThrottleStatus->assertStatus(429);

        Cache::flush();
    }

    public function test_dashboard_greets_logged_in_user_and_counts_legacy_jurusan_values(): void
    {
        Http::fake([
            '*/api/user/verify' => Http::response([
                'success' => true,
                'data' => ['id' => 9, 'username' => 'sari', 'nama_lengkap' => 'Sari Wulandari', 'role' => 'TU', 'status_aktif' => true],
            ], 200),
        ]);

        PpdbRegistration::query()->delete();

        $wave = PpdbWave::create([
            'nama' => 'Gelombang Uji', 'tahun_ajaran' => '2027/2028', 'tanggal_mulai' => now()->subDay(),
            'tanggal_selesai' => now()->addMonth(), 'kuota' => 100, 'biaya_formulir' => 0, 'is_active' => true,
        ]);
        foreach (['Desain Komunikasi Visual', 'Desain Komunikasi Visual (DKV)', 'Jurusan Tidak Dikenal'] as $i => $jurusan) {
            PpdbRegistration::create([
                'nomor_registrasi' => 'PPDB-UJI-'.$i, 'nama_lengkap' => 'Siswa '.$i, 'nama_panggilan' => 'S'.$i,
                'tempat_lahir' => 'Bogor', 'tanggal_lahir_hari' => '1', 'tanggal_lahir_bulan' => '1', 'tanggal_lahir_tahun' => '2011',
                'jenis_kelamin' => 'L', 'tipe_pendaftar' => 'Siswa Baru', 'kelas_pilihan' => 'X', 'jurusan' => $jurusan,
                'asal_sekolah' => 'SMP Uji', 'nomor_kontak_pendaftar' => '0812', 'nomor_kontak_ortu' => '0813',
                'status' => 'menunggu_verifikasi',
                'gelombang_id' => $wave->id,
            ]);
        }

        $html = $this->withHeader('Authorization', 'Bearer t')->get('/ppdb/dashboard');
        $html->assertStatus(200)->assertSee('Sari')->assertDontSee('Halo, Panitia PPDB');

        $json = $this->withHeader('Authorization', 'Bearer t')->getJson('/ppdb/dashboard');
        $json->assertOk()
            ->assertJsonPath('data.stats.total_pendaftar', 3)
            ->assertJsonPath('data.stats.menunggu_verifikasi', 3)
            ->assertJsonPath('data.per_jurusan.Desain Komunikasi Visual', 2)
            ->assertJsonPath('data.per_jurusan.Lainnya', 1);
    }
}
