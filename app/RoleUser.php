<?php

namespace App;

enum RoleUser: string
{
    case SuperAdmin = 'super_admin';
    case Operator = 'operator';
    case Mahasiswa = 'mahasiswa';
    case Kajur = 'kajur';

    public function label(): string
    {
        return match ($this) {
            self::SuperAdmin => 'Super Admin',
            self::Operator   => 'Operator Jurusan',
            self::Mahasiswa  => 'Mahasiswa',
            self::Kajur      => 'Ketua Jurusan',
        };
    }

    public function dashboardRoute(): string
    {
        return match ($this) {
            self::SuperAdmin => 'admin.dashboard',
            self::Operator   => 'operator.dashboard',
            self::Mahasiswa  => 'mahasiswa.dashboard',
            self::Kajur      => 'kajur.dashboard',
        };
    }
}