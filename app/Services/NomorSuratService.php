<?php

namespace App\Services;

use App\Models\NomorSuratCounter;
use App\Models\TemplateSurat;
use Illuminate\Support\Facades\DB;

class NomorSuratService
{
    private const ROMAWI = ['', 'I', 'II', 'III', 'IV', 'V', 'VI', 'VII', 'VIII', 'IX', 'X', 'XI', 'XII'];

    public function generate(TemplateSurat $template): string
    {
        $jenis = $template->jenisSurat;
        $tahun = now()->year;

        return DB::transaction(function () use ($template, $jenis, $tahun) {
            // pastikan baris penghitung untuk jenis surat + tahun ini ada
            NomorSuratCounter::firstOrCreate(
                ['jenis_surat_id' => $jenis->id, 'tahun' => $tahun],
                ['urutan_terakhir' => 0]
            );

            // kunci baris agar dua operator tidak mendapat nomor yang sama
            $counter = NomorSuratCounter::where('jenis_surat_id', $jenis->id)
                ->where('tahun', $tahun)
                ->lockForUpdate()
                ->first();

            $counter->increment('urutan_terakhir');

            return strtr($template->format_nomor, [
                '{urutan}'       => str_pad($counter->urutan_terakhir, 3, '0', STR_PAD_LEFT),
                '{kode}'         => $jenis->kode,
                '{bulan_romawi}' => self::ROMAWI[now()->month],
                '{tahun}'        => (string) $tahun,
            ]);
        });
    }
}