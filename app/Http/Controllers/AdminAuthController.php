<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AdminAuthController extends Controller
{
    /**
     * Tampilkan halaman login password-only untuk admin
     */
    public function showLogin(Request $request)
    {
        if ($request->session()->get('admin_authenticated')) {
            return redirect()->route('ppdb.dashboard');
        }

        return view('ppdb.auth.login');
    }

    /**
     * Proses verifikasi password admin
     */
    public function login(Request $request)
    {
        $request->validate([
            'password' => 'required|string',
        ], [
            'password.required' => 'Password wajib diisi untuk masuk.',
        ]);

        $adminPassword = env('ADMIN_PASSWORD', 'adminpenus2026');

        if ($request->input('password') === $adminPassword) {
            $request->session()->regenerate();
            $request->session()->put('admin_authenticated', true);
            $request->session()->put('admin_login_at', now()->toDateTimeString());

            return redirect()->intended(route('ppdb.dashboard'))
                ->with('success', 'Autentikasi berhasil! Selamat datang di Dashboard Admin PPDB.');
        }

        return back()
            ->withInput()
            ->withErrors(['password' => 'Password yang Anda masukkan salah. Periksa kembali dan coba lagi.']);
    }

    /**
     * Logout admin dan bersihkan session
     */
    public function logout(Request $request)
    {
        $request->session()->forget('admin_authenticated');
        $request->session()->forget('admin_login_at');
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('ppdb.login')
            ->with('info', 'Sesi admin telah ditutup dengan aman.');
    }
}
