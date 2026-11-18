<?php

namespace App;

enum StatusPengajuan: string
{
    case Draft = 'draft';
    case Diajukan = 'diajukan';
    case Diproses = 'diproses';
    case PerluRevisi = 'perlu_revisi';
    case MenungguTtd = 'menunggu_ttd';
    case Ditandatangani = 'ditandatangani';
    case SiapDiambil = 'siap_diambil';
    case Selesai = 'selesai';
    case Ditolak = 'ditolak';

    public function label(): string
    {
        return match ($this) {
            self::Draft => 'Draft',
            self::Diajukan => 'Diajukan',
            self::Diproses => 'Diproses Operator',
            self::PerluRevisi => 'Perlu Revisi',
            self::MenungguTtd => 'Menunggu TTD Kajur',
            self::Ditandatangani => 'Ditandatangani',
            self::SiapDiambil => 'Siap Diambil',
            self::Selesai => 'Selesai',
            self::Ditolak => 'Ditolak',
        };
    }

    public function badge(): string
    {
        return match ($this) {
            self::Draft => 'secondary',
            self::Diajukan => 'primary',
            self::Diproses => 'info',
            self::PerluRevisi, self::MenungguTtd => 'warning',
            self::Ditandatangani, self::SiapDiambil => 'success',
            self::Selesai => 'dark',
            self::Ditolak => 'danger',
        };
    }
}