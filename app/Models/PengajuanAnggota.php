<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PengajuanAnggota extends Model
{
    protected $fillable = ['pengajuan_id', 'nama', 'nim', 'program_studi_id', 'is_ketua'];

    protected function casts(): array { return ['is_ketua' => 'boolean']; }

    public function pengajuan(): BelongsTo { return $this->belongsTo(Pengajuan::class); }
    public function programStudi(): BelongsTo { return $this->belongsTo(ProgramStudi::class); }
}