<?php

namespace Database\Seeders;

use App\RoleUser;
use App\StatusPengajuan;
use App\Models\Mahasiswa;
use App\Models\Pejabat;
use App\Models\Pengajuan;
use App\Models\RiwayatStatus;
use App\Models\Surat;
use App\Models\TemplateSurat;
use App\Models\User;
use Illuminate\Database\Seeder;

class PengajuanSeeder extends Seeder
{
    public function run(): void
    {
        $mhs      = Mahasiswa::first();
        $template = TemplateSurat::first();
        $pejabat  = Pejabat::first();
        $operator = User::where('role', RoleUser::Operator)->first();

        // Status yang sudah punya surat
        $punyaSurat = [
            StatusPengajuan::MenungguTtd,
            StatusPengajuan::Ditandatangani,
            StatusPengajuan::SiapDiambil,
            StatusPengajuan::Selesai,
        ];
        $sudahTtd = [
            StatusPengajuan::Ditandatangani,
            StatusPengajuan::SiapDiambil,
            StatusPengajuan::Selesai,
        ];

        // Satu pengajuan contoh untuk setiap status (9 pengajuan)
        foreach (StatusPengajuan::cases() as $i => $status) {
            $no = $i + 1;

            $pengajuan = Pengajuan::updateOrCreate(
                ['kode_tiket' => 'PJ-CONTOH-' . str_pad($no, 3, '0', STR_PAD_LEFT)],
                [
                    'mahasiswa_id'      => $mhs->id,
                    'jenis_surat_id'    => $template->jenis_surat_id,
                    'template_surat_id' => $template->id,
                    'status'            => $status,
                    'catatan_revisi'    => $status === StatusPengajuan::PerluRevisi ? 'Mohon lengkapi alamat lokasi.' : null,
                    'alasan_tolak'      => $status === StatusPengajuan::Ditolak ? 'Lokasi tidak sesuai dengan kegiatan.' : null,
                    'diajukan_at'       => $status === StatusPengajuan::Draft ? null : now()->subDays(5),
                ]
            );

            // Anggota kelompok
            $pengajuan->anggotas()->updateOrCreate(
                ['nim' => $mhs->nim],
                ['nama' => $mhs->user->name, 'program_studi_id' => $mhs->program_studi_id, 'is_ketua' => true]
            );
            $pengajuan->anggotas()->updateOrCreate(
                ['nim' => '000000002'],
                ['nama' => 'Anggota Kelompok Dua', 'program_studi_id' => $mhs->program_studi_id]
            );

            // Isian dinamis (key sama dengan template_fields)
            $isian = [
                'keperluan'       => 'Pengambilan data penelitian',
                'nama_lokasi'     => 'SMA Negeri 1 Contoh',
                'alamat_lokasi'   => 'Jl. Contoh No. 1',
                'tujuan'          => 'Wawancara dan observasi',
                'tanggal_mulai'   => now()->addDays(7)->toDateString(),
                'tanggal_selesai' => now()->addDays(14)->toDateString(),
            ];
            foreach ($isian as $key => $nilai) {
                $pengajuan->data()->updateOrCreate(['key' => $key], ['value' => $nilai]);
            }

            // Riwayat status (satu catatan per pengajuan, kecuali draft)
            if ($status !== StatusPengajuan::Draft) {
                RiwayatStatus::firstOrCreate(
                    ['pengajuan_id' => $pengajuan->id, 'status_ke' => $status->value],
                    ['user_id' => $mhs->user_id]
                );
            }

            // Surat (untuk status yang sudah sampai tahap surat)
            if (in_array($status, $punyaSurat, true)) {
                $ttd = in_array($status, $sudahTtd, true);

                Surat::updateOrCreate(
                    ['pengajuan_id' => $pengajuan->id],
                    [
                        'nomor_surat'       => sprintf('%03d/SPL/XI/2026', $no),
                        'tanggal_surat'     => now()->subDays(2)->toDateString(),
                        'kode_verifikasi'   => hash('sha256', 'contoh-' . $pengajuan->id),
                        'pejabat_id'        => $pejabat->id,
                        'dibuat_oleh'       => $operator->id,
                        'status_ttd'        => $ttd ? 'ditandatangani' : 'belum',
                        'ditandatangani_at' => $ttd ? now()->subDay() : null,
                    ]
                );
            }
        }
    }
}