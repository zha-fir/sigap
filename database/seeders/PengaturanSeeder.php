<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Pengaturan;

class PengaturanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $daftar = [
            ['key' => 'jam_kerja_mulai',        'value' => '08:00', 'keterangan' => 'Jam mulai layanan'],
            ['key' => 'jam_kerja_selesai',      'value' => '15:00', 'keterangan' => 'Jam selesai layanan'],
            ['key' => 'estimasi_proses_hari',   'value' => '3',     'keterangan' => 'Estimasi lama proses (hari kerja)'],
            ['key' => 'batas_pengambilan_hari', 'value' => '7',     'keterangan' => 'Batas pengambilan surat (hari)'],
        ];

        foreach ($daftar as $row) {
            Pengaturan::updateOrCreate(['key' => $row['key']], $row);
        }
    }
}