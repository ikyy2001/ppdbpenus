<?php

namespace Tests\Feature;

use Database\Seeders\PpdbSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class PpdbRoutingAndAuthTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(PpdbSeeder::class);
    }

    /**
     * Test public routes can be accessed without authentication.
     */
    public function test_public_routes_are_accessible_without_token(): void
    {
        // 1. GET / redirects to /ppdb
        $resRedirect = $this->get('/');
        $resRedirect->assertRedirect('/ppdb');

        // 2. GET /ppdb returns 200 OK
        $resIndex = $this->get('/ppdb');
        $resIndex->assertStatus(200);

        // 3. GET /ppdb/akomodasi returns 200 OK
        $resAkomodasi = $this->get('/ppdb/akomodasi');
        $resAkomodasi->assertStatus(200);

        // 4. GET /ppdb/pengumuman returns 200 OK
        $resPengumuman = $this->get('/ppdb/pengumuman');
        $resPengumuman->assertStatus(200);

        // 5. GET /ppdb/cek-status returns 200 OK
        $resCekStatus = $this->get('/ppdb/cek-status');
        $resCekStatus->assertStatus(200);
    }

    /**
     * Test dashboard returns 401 when token is missing.
     */
    public function test_dashboard_returns_401_when_unauthenticated(): void
    {
        $response = $this->getJson('/ppdb/dashboard');

        $response->assertStatus(401)
            ->assertJson([
                'success' => false,
                'message' => 'Token otentikasi tidak ditemukan',
            ]);
    }

    /**
     * Test dashboard returns 401 when token is invalid.
     */
    public function test_dashboard_returns_401_when_token_is_invalid(): void
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
     * Test dashboard returns 403 when user account is inactive.
     */
    public function test_dashboard_returns_403_when_account_is_inactive(): void
    {
        Http::fake([
            '*/api/user/verify' => Http::response([
                'success' => true,
                'message' => 'Token terverifikasi',
                'data' => [
                    'id' => 'user-inactive',
                    'username' => 'guru_nonaktif',
                    'role' => 'ADMIN',
                    'status_aktif' => false,
                ],
            ], 200),
        ]);

        $response = $this->withHeader('Authorization', 'Bearer valid_token')
            ->getJson('/ppdb/dashboard');

        $response->assertStatus(403)
            ->assertJson([
                'success' => false,
                'message' => 'Akun pengguna sedang dinonaktifkan',
            ]);
    }

    /**
     * Test dashboard returns 403 when user role is not authorized.
     */
    public function test_dashboard_returns_403_for_unauthorized_role(): void
    {
        Http::fake([
            '*/api/user/verify' => Http::response([
                'success' => true,
                'message' => 'Token terverifikasi',
                'data' => [
                    'id' => 'user-siswa',
                    'username' => 'siswa_rizky',
                    'role' => 'SISWA',
                    'status_aktif' => true,
                ],
            ], 200),
        ]);

        $response = $this->withHeader('Authorization', 'Bearer token_siswa')
            ->getJson('/ppdb/dashboard');

        $response->assertStatus(403)
            ->assertJson([
                'success' => false,
            ]);
    }

    /**
     * Test dashboard succeeds for authorized role via Bearer Header.
     */
    public function test_dashboard_accessible_with_valid_bearer_token(): void
    {
        Http::fake([
            '*/api/user/verify' => Http::response([
                'success' => true,
                'message' => 'Token terverifikasi',
                'data' => [
                    'id' => 'admin-id',
                    'username' => 'admin',
                    'nama_lengkap' => 'Administrator IT',
                    'role' => 'ADMIN',
                    'status_aktif' => true,
                ],
            ], 200),
        ]);

        $response = $this->withHeader('Authorization', 'Bearer valid_admin_token')
            ->getJson('/ppdb/dashboard');

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'data' => [
                    'user' => [
                        'username' => 'admin',
                        'role' => 'ADMIN',
                    ],
                ],
            ]);

        // Verifikasi bahwa access_token diteruskan ke auth server dalam bentuk JSON payload
        Http::assertSent(function ($request) {
            return str_contains($request->url(), '/api/user/verify')
                && $request->isJson()
                && $request['access_token'] === 'valid_admin_token';
        });
    }

    /**
     * Test token extraction from cookie succeeds.
     */
    public function test_dashboard_accessible_via_cookie(): void
    {
        Http::fake([
            '*/api/user/verify' => Http::response([
                'success' => true,
                'message' => 'Token terverifikasi',
                'data' => [
                    'id' => 'kepsek-id',
                    'username' => 'kepsek',
                    'nama_lengkap' => 'Dr. H. Bambang',
                    'role' => 'KEPALA_SEKOLAH',
                    'status_aktif' => true,
                ],
            ], 200),
        ]);

        $resCookie = $this->withCredentials()
            ->withUnencryptedCookie('access_token', 'cookie_token')
            ->getJson('/ppdb/dashboard');
        $resCookie->assertStatus(200);
    }

    /**
     * SEC-05: Test token passed via URL query parameter is rejected with 401.
     */
    public function test_dashboard_rejects_query_param_token(): void
    {
        $resQuery = $this->getJson('/ppdb/dashboard?access_token=query_token');
        $resQuery->assertStatus(401)
            ->assertJson([
                'success' => false,
                'message' => 'Token otentikasi tidak ditemukan',
            ]);
    }

    /**
     * SEC-05 & SEC-07: Test unauthenticated browser request redirects to home with flash warning.
     */
    public function test_unauthenticated_browser_request_redirects_to_index_with_warning(): void
    {
        // Akses langsung melalui browser web (tanpa Accept: application/json)
        $response = $this->get('/ppdb/dashboard');

        $response->assertRedirect(route('ppdb.index'));
        $response->assertSessionHas('warning', 'Token otentikasi tidak ditemukan');
    }

    /**
     * SEC-05: Test external auth microservice outage masks exception details.
     */
    public function test_auth_service_outage_masks_exception_details(): void
    {
        Http::fake([
            '*/api/user/verify' => fn () => throw new ConnectionException('cURL error 7: Failed to connect to localhost port 3000'),
        ]);

        // JSON Request: Wajib mengembalikan 503 dengan pesan aman tanpa bocoran networking
        $resJson = $this->withHeader('Authorization', 'Bearer valid_token')
            ->getJson('/ppdb/dashboard');

        $resJson->assertStatus(503)
            ->assertJson([
                'success' => false,
                'message' => 'Layanan otentikasi sedang tidak tersedia. Silakan coba beberapa saat lagi.',
            ]);

        $this->assertStringNotContainsString('port 3000', $resJson->getContent());
        $this->assertStringNotContainsString('cURL error 7', $resJson->getContent());

        // Browser Request: Wajib redirect ke home dengan warning
        $resBrowser = $this->withHeader('Authorization', 'Bearer valid_token')
            ->get('/ppdb/dashboard');

        $resBrowser->assertRedirect(route('ppdb.index'));
        $resBrowser->assertSessionHas('warning', 'Layanan otentikasi sedang tidak tersedia. Silakan coba beberapa saat lagi.');
    }
}
