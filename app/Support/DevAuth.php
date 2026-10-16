<?php

namespace App\Support;

use Illuminate\Http\Request;

/**
 * Login uji coba KHUSUS development lokal, supaya halaman admin/siswa bisa dites tanpa menjalankan auth service.
 *
 * Hanya aktif kalau ketiga syarat ini terpenuhi sekaligus:
 *  1. APP_ENV=local
 *  2. AUTH_DEV_BYPASS=true
 *  3. Request datang langsung dari mesin sendiri (REMOTE_ADDR 127.0.0.1 / ::1, bukan lewat reverse proxy)
 *
 * Peran dipilih lewat halaman /ppdb/dev/masuk (disimpan di cookie), bawaannya AUTH_DEV_ROLE.
 */
class DevAuth
{
    public const COOKIE = 'dev_auth_role';

    /**
     * Persona uji coba. Peran SISWA dipakai untuk mengecek bahwa dashboard panitia menolak akses.
     */
    public const PERSONAS = [
        'ADMIN' => [
            'id' => 'usr-admin-001', 'username' => 'admin', 'nama_lengkap' => 'Admin Uji Coba',
            'email' => 'admin@smkpenus.sch.id', 'nomor_induk' => 'ADM-DEV-001',
            'label' => 'Admin PPDB', 'desc' => 'Dashboard, pendaftar, gelombang, pengumuman, biaya', 'home' => '/ppdb/dashboard',
        ],
        'TU' => [
            'id' => 'usr-tu-001', 'username' => 'tu', 'nama_lengkap' => 'Staf TU Uji Coba',
            'email' => 'tu@smkpenus.sch.id', 'nomor_induk' => 'TU-DEV-001',
            'label' => 'Tata Usaha', 'desc' => 'Akses panitia yang sama dengan peran Admin', 'home' => '/ppdb/dashboard',
        ],
        'SISWA' => [
            'id' => 'usr-siswa-001', 'username' => 'siswa', 'nama_lengkap' => 'Siswa Uji Coba',
            'email' => 'siswa@smkpenus.sch.id', 'nomor_induk' => '0061234567',
            'label' => 'Siswa (uji tolak akses)', 'desc' => 'Harus ditolak saat membuka dashboard panitia', 'home' => '/ppdb/dashboard',
        ],
    ];

    /**
     * Rute /dev/masuk didaftarkan? (Dicek saat boot, jadi tanpa syarat alamat IP.)
     */
    public static function configured(): bool
    {
        return app()->environment('local') && (bool) config('services.auth_service.dev_bypass', false);
    }

    public static function enabled(Request $request): bool
    {
        // REMOTE_ADDR langsung, bukan $request->ip(), supaya tidak bisa dipalsukan lewat header X-Forwarded-For
        return self::configured() && in_array($request->server('REMOTE_ADDR'), ['127.0.0.1', '::1'], true);
    }

    public static function currentRole(Request $request): ?string
    {
        if (! self::enabled($request)) {
            return null;
        }

        $role = strtoupper((string) ($request->cookie(self::COOKIE) ?: config('services.auth_service.dev_role', 'ADMIN')));

        return array_key_exists($role, self::PERSONAS) ? $role : null;
    }

    /**
     * Data user palsu dengan bentuk yang sama seperti respon auth service (/api/user/verify).
     */
    public static function user(Request $request): ?array
    {
        $role = self::currentRole($request);

        if ($role === null) {
            return null;
        }

        $persona = self::PERSONAS[$role];

        return [
            'id' => $persona['id'],
            'username' => $persona['username'],
            'nama_lengkap' => $persona['nama_lengkap'],
            'email' => $persona['email'],
            'nomor_induk' => $persona['nomor_induk'],
            'role' => $role,
            'status_aktif' => true,
            'is_dev_login' => true,
        ];
    }
}
