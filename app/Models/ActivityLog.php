<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ActivityLog extends Model
{
    public const UPDATED_AT = null;

    protected $fillable = ['user_id', 'aksi', 'subjek_type', 'subjek_id', 'keterangan', 'ip_address'];

    public function user(): BelongsTo { return $this->belongsTo(User::class); }

    public function subjek(): \Illuminate\Database\Eloquent\Relations\MorphTo
    {
        return $this->morphTo();
    }
}