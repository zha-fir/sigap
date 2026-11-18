<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TemplateSurat extends Model
{
    protected $fillable = ['jenis_surat_id', 'nama', 'isi_template', 'format_nomor', 'is_active'];

    protected function casts(): array
    {
        return ['is_active' => 'boolean'];
    }

    public function jenisSurat(): BelongsTo
    {
        return $this->belongsTo(JenisSurat::class);
    }

    public function fields(): HasMany
    {
        return $this->hasMany(TemplateField::class)->orderBy('urutan');
    }
    public function pengajuans(): HasMany
    {
        return $this->hasMany(Pengajuan::class);
    }
}