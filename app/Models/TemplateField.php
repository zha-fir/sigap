<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TemplateField extends Model
{
    protected $fillable = ['template_surat_id', 'key', 'label', 'tipe', 'is_required', 'urutan', 'opsi'];

    protected function casts(): array
    {
        return [
            'is_required' => 'boolean',
            'opsi' => 'array',
        ];
    }

    public function templateSurat(): BelongsTo
    {
        return $this->belongsTo(TemplateSurat::class);
    }
}