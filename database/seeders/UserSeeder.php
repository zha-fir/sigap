<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Pejabat;
use App\Models\Mahasiswa;
use App\Models\ProgramStudi;
use App\RoleUser;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@example.com'],
            ['name' => 'Super Admin', 'password' => 'password', 'role' => RoleUser::SuperAdmin, 'is_active' => true]
        );

        User::updateOrCreate(
            ['email' => 'operator@example.com'],
            ['name' => 'Operator Jurusan', 'password' => 'password', 'role' => RoleUser::Operator, 'is_active' => true]
        );

        $kajur = User::updateOrCreate(
            ['email' => 'kajur@example.com'],
            ['name' => 'Dr. Ketua Jurusan, M.Kom', 'password' => 'password', 'role' => RoleUser::Kajur, 'is_active' => true]
        );
        Pejabat::updateOrCreate(
            ['user_id' => $kajur->id],
            ['nama' => $kajur->name, 'nip' => '000000000000000000', 'jabatan' => 'Ketua Jurusan']
        );

        $mhs = User::updateOrCreate(
            ['email' => 'mahasiswa@example.com'],
            ['name' => 'Mahasiswa Demo', 'password' => 'password', 'role' => RoleUser::Mahasiswa, 'is_active' => true]
        );
        Mahasiswa::updateOrCreate(
            ['user_id' => $mhs->id],
            ['nim' => '000000001', 'program_studi_id' => ProgramStudi::first()->id, 'angkatan' => '2022']
        );
    }
}