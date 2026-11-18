<?php

namespace App\Services;

use App\Models\Pejabat;
use App\Models\Pengajuan;
use Carbon\Carbon;

class SuratRenderer
{
    public function render(Pengajuan $pengajuan): string
    {
        $pengajuan->loadMissing(['templateSurat.fields', 'anggotas', 'data', 'surat.pejabat']);

        $surat   = $pengajuan->surat;
        $pejabat = $surat?->pejabat ?? Pejabat::where('is_active', true)->first();
        $tipe    = $pengajuan->templateSurat->fields->pluck('tipe', 'key');

        // 1. Isian dari mahasiswa: [[key]] => nilai
        $ganti = [];
        foreach ($pengajuan->dataArray() as $key => $nilai) {
            if (($tipe[$key] ?? null) === 'date' && $nilai) {
                $nilai = Carbon::parse($nilai)->translatedFormat('d F Y');
            }
            $ganti["[[$key]]"] = e($nilai);
        }

        // 2. Isian dari sistem
        $tanggalSurat = $surat?->tanggal_surat ?? now();
        $ganti += [
            '[[nomor_surat]]'    => e($surat?->nomor_surat ?? '(nomor dibuat otomatis)'),
            '[[tanggal_surat]]'  => $tanggalSurat->translatedFormat('d F Y'),
            '[[daftar_anggota]]' => $this->daftarAnggota($pengajuan),
            '[[nama_kajur]]'     => e($pejabat?->nama ?? '-'),
            '[[nip_kajur]]'      => e($pejabat?->nip ?? '-'),
            '[[ttd_kajur]]'      => $this->ttd($pengajuan, $pejabat),
        ];

        // 3. Pakai teks hasil edit operator jika ada, kalau tidak pakai template asli
        $isi = $surat?->isi_surat ?: $pengajuan->templateSurat->isi_template;

        return strtr($isi, $ganti);
    }

    private function daftarAnggota(Pengajuan $pengajuan): string
    {
        $baris = '';
        foreach ($pengajuan->anggotas as $i => $anggota) {
            $baris .= '<tr><td>' . ($i + 1) . '</td><td>' . e($anggota->nama) . '</td><td>' . e($anggota->nim) . '</td></tr>';
        }

        return '<table class="tabel-anggota"><tr><th>No</th><th>Nama</th><th>NIM</th></tr>' . $baris . '</table>';
    }

    private function ttd(Pengajuan $pengajuan, ?Pejabat $pejabat): string
    {
        $sudahTtd = $pengajuan->surat?->status_ttd === 'ditandatangani';

        if ($sudahTtd && $pejabat?->file_ttd) {
            return '<img src="' . e($pejabat->ttdUrl()) . '" alt="Tanda tangan" style="height:80px">';
        }

        return '<br><br><br>';   // ruang kosong jika belum ditandatangani
    }
}