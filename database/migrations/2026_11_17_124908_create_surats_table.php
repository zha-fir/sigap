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
        Schema::create('surats', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pengajuan_id')->unique()->constrained('pengajuans')->cascadeOnDelete();
            $table->string('nomor_surat')->nullable()->unique();
            $table->date('tanggal_surat')->nullable();
            $table->longText('isi_surat')->nullable();
            $table->string('file_pdf')->nullable();
            $table->string('kode_verifikasi', 64)->unique();
            $table->foreignId('pejabat_id')->nullable()->constrained('pejabats')->nullOnDelete();
            $table->foreignId('dibuat_oleh')->nullable()->constrained('users')->nullOnDelete();
            $table->string('status_ttd', 20)->default('belum');
            $table->timestamp('ditandatangani_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('surats');
    }
};