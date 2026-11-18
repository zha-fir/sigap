<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\JenisSurat;
use App\Models\TemplateSurat;

class JenisSuratSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $jenis = JenisSurat::updateOrCreate(
                ['kode' => 'SPL'],
                ['nama' => 'Surat Pengantar Turun Lapangan', 'deskripsi' => 'Pengantar pengambilan data di lapangan.', 'is_active' => true]
            );

            $isi = <<<'HTML'
        <p style="text-align:center"><strong>SURAT PENGANTAR</strong><br>Nomor: [[nomor_surat]]</p>
        <p>Yth. Pimpinan [[nama_lokasi]]<br>[[alamat_lokasi]]</p>
        <p>Dengan hormat, kami memohon izin bagi mahasiswa berikut untuk melakukan kegiatan
        <strong>[[keperluan]]</strong> dengan tujuan [[tujuan]], pada tanggal [[tanggal_mulai]]
        s.d. [[tanggal_selesai]]:</p>
        [[daftar_anggota]]
        <p>Demikian surat ini kami sampaikan, atas perhatian dan kerja samanya kami ucapkan terima kasih.</p>
        <p style="text-align:right">[[tanggal_surat]]<br>Ketua Jurusan,<br>[[ttd_kajur]]<br>
        <u>[[nama_kajur]]</u><br>NIP. [[nip_kajur]]</p>
        HTML;

            $template = TemplateSurat::updateOrCreate(
                ['jenis_surat_id' => $jenis->id, 'nama' => 'Template Surat Pengantar Turun Lapangan'],
                ['isi_template' => $isi, 'is_active' => true]
            );

            $fields = [
                ['key' => 'keperluan',       'label' => 'Keperluan',              'tipe' => 'textarea'],
                ['key' => 'nama_lokasi',     'label' => 'Nama lokasi / instansi', 'tipe' => 'text'],
                ['key' => 'alamat_lokasi',   'label' => 'Alamat lokasi',          'tipe' => 'textarea'],
                ['key' => 'tujuan',          'label' => 'Tujuan kegiatan',        'tipe' => 'textarea'],
                ['key' => 'tanggal_mulai',   'label' => 'Tanggal mulai',          'tipe' => 'date'],
                ['key' => 'tanggal_selesai', 'label' => 'Tanggal selesai',        'tipe' => 'date'],
            ];

            foreach ($fields as $i => $field) {
                $template->fields()->updateOrCreate(
                    ['key' => $field['key']],
                    $field + ['is_required' => true, 'urutan' => $i + 1]
                );
            }
    }
}