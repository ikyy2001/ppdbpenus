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
        Schema::create('ppdb_announcements', function (Blueprint $table) {
            $table->id();
            $table->string('judul');
            $table->string('nomor_sk')->nullable();
            $table->string('kategori')->default('Informasi Umum');
            $table->string('badge')->nullable(); // PENTING, TERBARU, INFO
            $table->date('tanggal');
            $table->boolean('is_pinned')->default(false);
            $table->text('ringkasan')->nullable();
            $table->longText('isi_lengkap');
            $table->string('file_nama')->nullable();
            $table->string('file_path')->nullable();
            $table->string('file_ukuran')->nullable();
            $table->string('action_link')->nullable();
            $table->string('action_text')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ppdb_announcements');
    }
};
