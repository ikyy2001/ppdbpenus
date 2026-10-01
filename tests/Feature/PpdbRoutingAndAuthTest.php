<?php

namespace Tests\Feature;

use Database\Seeders\PpdbSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
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
     * Test token extraction from cookie and body.
     */
    public function test_dashboard_accessible_via_cookie_and_body(): void
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

        // Via Cookie
        $resCookie = $this->withCredentials()
            ->withUnencryptedCookie('access_token', 'cookie_token')
            ->getJson('/ppdb/dashboard');
        $resCookie->assertStatus(200);

        // Via Request Query/Body
        $resBody = $this->getJson('/ppdb/dashboard?access_token=body_token');
        $resBody->assertStatus(200);
    }
}
