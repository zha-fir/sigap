<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Pengajuan extends Model
{
    protected $fillable = ['kode_tiket', 'mahasiswa_id', 'jenis_surat_id', 'template_surat_id',
                       'status', 'catatan_revisi', 'alasan_tolak', 'diajukan_at'];

    protected function casts(): array
    {
        return [
            'status' => \App\StatusPengajuan::class,
            'diajukan_at' => 'datetime',
        ];
    }

    public function mahasiswa(): BelongsTo { return $this->belongsTo(Mahasiswa::class); }
    public function jenisSurat(): BelongsTo { return $this->belongsTo(JenisSurat::class); }
    public function templateSurat(): BelongsTo { return $this->belongsTo(TemplateSurat::class); }
    public function anggotas(): HasMany { return $this->hasMany(PengajuanAnggota::class); }
    public function data(): HasMany { return $this->hasMany(PengajuanData::class); }
    public function lampirans(): HasMany { return $this->hasMany(Lampiran::class); }
    public function surat(): HasOne { return $this->hasOne(Surat::class); }

    public function riwayats(): HasMany
    {
        return $this->hasMany(RiwayatStatus::class)->orderBy('created_at');
    }

    // Isian dinamis jadi array: ['nama_lokasi' => 'SMA 1', ...]
    public function dataArray(): array
    {
        return $this->data->pluck('value', 'key')->all();
    }
}