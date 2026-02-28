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
        Schema::create('ref_kegiatans', function (Blueprint $table) {
            $table->id();
            $table->string('nama_jenis');     // Contoh: Apel, Rapat
            $table->string('nama_kegiatan');  // Contoh: Pagi, Webinar, Kunjungan
            $table->string('output');         // Contoh: Kegiatan
            $table->enum('aktif', ['ya', 'tidak'])->default('ya');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ref_kegiatans');
    }
};
