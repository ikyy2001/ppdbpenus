<?php

namespace App\Http\Controllers;

use App\Support\DevAuth;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

/**
 * Pemilih peran untuk login uji coba lokal (lihat App\Support\DevAuth). Rutenya hanya terdaftar saat
 * APP_ENV=local + AUTH_DEV_BYPASS=true, dan tetap 404 kalau dibuka dari luar localhost.
 */
class DevAuthController extends Controller
{
    public function index(Request $request): View
    {
        abort_unless(DevAuth::enabled($request), 404);

        return view('dev.masuk', [
            'appName' => 'PPDB',
            'personas' => DevAuth::PERSONAS,
            'currentRole' => DevAuth::currentRole($request),
            'extraLinks' => [
                ['label' => 'Formulir pendaftaran publik', 'href' => route('ppdb.index'), 'note' => 'Isi formulir untuk menambah data pendaftar'],
                ['label' => 'Cek status pendaftar', 'href' => route('ppdb.cek-status'), 'note' => 'Coba NISN dari data seeder, mis. 0081293841'],
            ],
        ]);
    }

    public function switch(Request $request, string $role): RedirectResponse
    {
        abort_unless(DevAuth::enabled($request), 404);

        $role = strtoupper($role);
        abort_unless(array_key_exists($role, DevAuth::PERSONAS), 404);

        return redirect(DevAuth::PERSONAS[$role]['home'])
            ->withCookie(cookie(DevAuth::COOKIE, $role, 60 * 24 * 7));
    }
}
