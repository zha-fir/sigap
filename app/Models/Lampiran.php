<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Lampiran extends Model
{
    protected $fillable = ['pengajuan_id', 'nama_file', 'path', 'mime', 'ukuran'];

    public function pengajuan(): BelongsTo { return $this->belongsTo(Pengajuan::class); }
}