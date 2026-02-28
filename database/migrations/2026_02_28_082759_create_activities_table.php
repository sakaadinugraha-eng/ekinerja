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
    Schema::create('activities', function (Blueprint $table) {
        $table->id();
        // Foreign key ke user, otomatis hapus aktivitas jika user dihapus
        $table->foreignId('user_id')->constrained()->cascadeOnDelete();
        
        $table->date('tanggal');
        $table->time('jam_mulai');
        $table->time('jam_selesai');
        $table->text('deskripsi'); // Apa yang dikerjakan
        $table->string('bukti_dukung')->nullable(); // Link file/foto (opsional)
        
        // Status validasi atasan
        $table->enum('status', ['draft', 'diajukan', 'disetujui', 'ditolak'])->default('draft');
        
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('activities');
    }
};
