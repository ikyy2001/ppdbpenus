<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('ppdb_registrations', function (Blueprint $table) {
            $table->id();
            $table->string('nomor_registrasi')->unique();

            // Section A: Personal
            $table->string('nama_lengkap');
            $table->string('nama_panggilan');
            $table->string('nisn')->nullable()->index();
            $table->string('nomor_kk')->nullable();
            $table->string('tempat_lahir');
            $table->string('tanggal_lahir_hari');
            $table->string('tanggal_lahir_bulan');
            $table->string('tanggal_lahir_tahun');
            $table->string('jenis_kelamin');
            $table->text('alamat_lengkap')->nullable();

            // Section B: Sekolah Pilihan & Jurusan
            $table->string('sekolah_pilihan_level')->default('SMK');
            $table->string('sekolah_pilihan_unit')->default('SMK Plus Pelita Nusantara Bogor');
            $table->string('tipe_pendaftar');
            $table->string('pindah_tahun_ajaran')->nullable();
            $table->string('tanggal_mulai_masuk')->nullable();
            $table->string('kelas_pilihan');
            $table->string('jurusan');
            $table->string('jalur_seleksi');
            $table->string('asal_sekolah');
            $table->string('nomor_kontak_pendaftar');
            $table->string('nomor_kontak_ortu');
            $table->string('email')->nullable();

            // Section C: Informasi Pendaftaran
            $table->json('jenis_layanan')->nullable();
            $table->json('sumber_info')->nullable();
            $table->string('sumber_info_lainnya')->nullable();
            $table->string('alasan_minat')->nullable();
            $table->string('alasan_minat_lainnya')->nullable();
            $table->string('ukuran_seragam')->nullable();

            // Status Pendaftaran
            $table->string('status')->default('menunggu_verifikasi'); // menunggu_verifikasi, terverifikasi, lulus_seleksi
            $table->text('catatan')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ppdb_registrations');
    }
};
