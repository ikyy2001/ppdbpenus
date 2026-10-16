<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * SMK Plus Pelita Nusantara sekolah swasta, jadi formulir tidak lagi menanyakan jalur seleksi (dan ukuran seragam).
 * Kolomnya tidak dihapus supaya data pendaftar lama tetap ada; jalur_seleksi dijadikan nullable.
 * "Alasan minat" sekarang boleh lebih dari satu, jadi disimpan sebagai daftar JSON seperti sumber_info.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('ppdb_registrations', function (Blueprint $table) {
            $table->string('jalur_seleksi')->nullable()->change();
            // text dulu: nilai lama yang dibungkus jadi JSON bisa lebih panjang dari 255 karakter
            $table->text('alasan_minat')->nullable()->change();
        });

        // Nilai lama berupa satu teks, bungkus jadi daftar berisi satu alasan
        DB::table('ppdb_registrations')
            ->whereNotNull('alasan_minat')
            ->orderBy('id')
            ->each(function (object $row) {
                $decoded = json_decode($row->alasan_minat, true);
                if (is_array($decoded)) {
                    return;
                }

                $value = trim($row->alasan_minat);
                DB::table('ppdb_registrations')->where('id', $row->id)->update([
                    'alasan_minat' => $value === '' ? null : json_encode([$value]),
                ]);
            });
    }

    public function down(): void
    {
        // Kembalikan ke satu teks: alasan-alasan digabung dengan koma
        DB::table('ppdb_registrations')
            ->whereNotNull('alasan_minat')
            ->orderBy('id')
            ->each(function (object $row) {
                $decoded = json_decode($row->alasan_minat, true);
                if (! is_array($decoded)) {
                    return;
                }

                DB::table('ppdb_registrations')->where('id', $row->id)->update([
                    'alasan_minat' => $decoded === [] ? null : mb_substr(implode(', ', $decoded), 0, 255),
                ]);
            });

        DB::table('ppdb_registrations')->whereNull('jalur_seleksi')->update(['jalur_seleksi' => 'Reguler']);

        Schema::table('ppdb_registrations', function (Blueprint $table) {
            $table->string('jalur_seleksi')->nullable(false)->change();
            $table->string('alasan_minat')->nullable()->change();
        });
    }
};
