<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RiwayatStatus extends Model
{
    public const UPDATED_AT = null;

    protected $fillable = ['pengajuan_id', 'status_dari', 'status_ke', 'user_id', 'catatan'];

    public function pengajuan(): BelongsTo { return $this->belongsTo(Pengajuan::class); }
    public function user(): BelongsTo { return $this->belongsTo(User::class); }
}