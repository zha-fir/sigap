<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Surat extends Model
{
    protected $fillable = ['pengajuan_id', 'nomor_surat', 'tanggal_surat', 'isi_surat', 'file_pdf',
                       'kode_verifikasi', 'pejabat_id', 'dibuat_oleh', 'status_ttd', 'ditandatangani_at'];

    protected function casts(): array
    {
        return ['tanggal_surat' => 'date', 'ditandatangani_at' => 'datetime'];
    }

    public function pengajuan(): BelongsTo { return $this->belongsTo(Pengajuan::class); }
    public function pejabat(): BelongsTo { return $this->belongsTo(Pejabat::class); }
    public function pembuat(): BelongsTo { return $this->belongsTo(User::class, 'dibuat_oleh'); }
}