<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany; 

class JenisSurat extends Model
{
    protected $fillable = ['nama', 'kode', 'deskripsi', 'is_active'];

    protected function casts(): array
    {
        return ['is_active' => 'boolean'];
    }

    public function templates(): HasMany
    {
        return $this->hasMany(TemplateSurat::class);
    }
    public function pengajuans(): HasMany { return $this->hasMany(Pengajuan::class); }
}