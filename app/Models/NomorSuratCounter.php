<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class NomorSuratCounter extends Model
{
    protected $fillable = ['jenis_surat_id', 'tahun', 'urutan_terakhir'];

    public function jenisSurat(): BelongsTo { return $this->belongsTo(JenisSurat::class); }
}