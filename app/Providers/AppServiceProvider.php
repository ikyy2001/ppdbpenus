<?php

namespace App\Providers;

use App\Models\PpdbFeeSetting;
use App\Models\PpdbRegistration;
use App\Models\PpdbWave;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // View Composer for Public & PPDB Views (Components, Footer, Pages)
        View::composer(['components.footer', 'ppdb.*', 'layouts.admin', 'layouts.app'], function ($view) {
            try {
                if (Schema::hasTable('ppdb_fee_settings')) {
                    $rawWa = (string) PpdbFeeSetting::get('kontak_wa', '6281283921029');
                    $kontakWa = preg_replace('/[^0-9]/', '', $rawWa);

                    // Format WhatsApp display: +62 812-8392-1029
                    if (str_starts_with($kontakWa, '62') && strlen($kontakWa) >= 11) {
                        $prefix = '+62 ';
                        $mid1 = substr($kontakWa, 2, 3);
                        $mid2 = substr($kontakWa, 5, 4);
                        $rest = substr($kontakWa, 9);
                        $kontakWaFormatted = $prefix.$mid1.'-'.$mid2.'-'.$rest;
                    } elseif (str_starts_with($kontakWa, '08') && strlen($kontakWa) >= 10) {
                        $prefix = '08';
                        $mid1 = substr($kontakWa, 2, 2);
                        $mid2 = substr($kontakWa, 4, 4);
                        $rest = substr($kontakWa, 8);
                        $kontakWaFormatted = $prefix.$mid1.'-'.$mid2.'-'.$rest;
                    } else {
                        $kontakWaFormatted = $rawWa;
                    }

                    $emailCs = (string) PpdbFeeSetting::get('email_cs', 'ppdb@smkpelitanusantara.sch.id');
                    $teleponKantor = (string) PpdbFeeSetting::get('telepon_kantor', '(021) 8790-1234');
                    $alamatKampus = (string) PpdbFeeSetting::get('alamat_kampus', 'Jl. Raya Golf Ciriung No. 1, Cibinong, Kab. Bogor');
                } else {
                    $kontakWa = '6281283921029';
                    $kontakWaFormatted = '+62 812-8392-1029';
                    $emailCs = 'ppdb@smkpelitanusantara.sch.id';
                    $teleponKantor = '(021) 8790-1234';
                    $alamatKampus = 'Jl. Raya Golf Ciriung No. 1, Cibinong, Kab. Bogor';
                }

                $activeWave = null;
                if (Schema::hasTable('ppdb_waves')) {
                    $activeWave = PpdbWave::where('is_active', true)->first() ?? PpdbWave::first();
                }

                $totalPendaftar = 0;
                if (Schema::hasTable('ppdb_registrations')) {
                    $totalPendaftar = PpdbRegistration::count();
                }

                $view->with([
                    'kontakWa' => $kontakWa,
                    'kontakWaFormatted' => $kontakWaFormatted,
                    'emailCs' => $emailCs,
                    'teleponKantor' => $teleponKantor,
                    'alamatKampus' => $alamatKampus,
                    'sharedActiveWave' => $activeWave,
                    'sharedTotalPendaftar' => $totalPendaftar,
                ]);
            } catch (\Throwable $e) {
                // Fallback graceful defaults
                $view->with([
                    'kontakWa' => '6281283921029',
                    'kontakWaFormatted' => '+62 812-8392-1029',
                    'emailCs' => 'ppdb@smkpelitanusantara.sch.id',
                    'teleponKantor' => '(021) 8790-1234',
                    'alamatKampus' => 'Jl. Raya Golf Ciriung No. 1, Cibinong, Kab. Bogor',
                    'sharedActiveWave' => null,
                    'sharedTotalPendaftar' => 0,
                ]);
            }
        });

        // View Composer for Admin Layout Notifications
        View::composer('layouts.admin', function ($view) {
            try {
                if (Schema::hasTable('ppdb_registrations')) {
                    $unverifiedCount = PpdbRegistration::where('status', 'menunggu_verifikasi')->count();
                    $recentRegistrations = PpdbRegistration::latest('id')->take(4)->get();
                } else {
                    $unverifiedCount = 0;
                    $recentRegistrations = collect();
                }

                $view->with([
                    'unverifiedCount' => $unverifiedCount,
                    'recentRegistrations' => $recentRegistrations,
                ]);
            } catch (\Throwable $e) {
                $view->with([
                    'unverifiedCount' => 0,
                    'recentRegistrations' => collect(),
                ]);
            }
        });
    }
}
