<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PengajuanData extends Model
{
    protected $table = 'pengajuan_data';
    protected $fillable = ['pengajuan_id', 'key', 'value'];

    public function pengajuan(): BelongsTo { return $this->belongsTo(Pengajuan::class); }
}