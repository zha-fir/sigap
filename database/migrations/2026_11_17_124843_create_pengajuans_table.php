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
        Schema::create('pengajuans', function (Blueprint $table) {
        $table->id();
        $table->string('kode_tiket', 30)->unique();
        $table->foreignId('mahasiswa_id')->constrained('mahasiswas');
        $table->foreignId('jenis_surat_id')->constrained('jenis_surats');
        $table->foreignId('template_surat_id')->constrained('template_surats');
        $table->string('status', 20)->default('draft')->index();
        $table->text('catatan_revisi')->nullable();
        $table->text('alasan_tolak')->nullable();
        $table->timestamp('diajukan_at')->nullable();
        $table->timestamps();
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengajuans');
    }
};