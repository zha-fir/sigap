<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\ProgramStudi;

class ProgramStudiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $daftar = [
            ['kode' => 'SI', 'nama' => 'Sistem Informasi'],
            ['kode' => 'TI', 'nama' => 'Teknik Informatika'],
        ];

        foreach ($daftar as $prodi) {
            ProgramStudi::updateOrCreate(['kode' => $prodi['kode']], $prodi);
        }
    }
}