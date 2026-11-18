<?php

namespace App\Services;

use App\StatusPengajuan;
use App\Models\Pengajuan;
use App\Models\RiwayatStatus;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class PengajuanStatusService
{
    // Alur yang diperbolehkan: dari status (kiri) ke status (kanan)
    private const ALUR = [
        'draft'          => ['diajukan'],
        'diajukan'       => ['diproses', 'perlu_revisi', 'ditolak'],
        'diproses'       => ['perlu_revisi', 'ditolak', 'menunggu_ttd'],
        'perlu_revisi'   => ['diajukan'],
        'menunggu_ttd'   => ['ditandatangani', 'diproses'],
        'ditandatangani' => ['siap_diambil'],
        'siap_diambil'   => ['selesai'],
    ];

    public function ubah(Pengajuan $pengajuan, StatusPengajuan $statusBaru, ?User $user = null, ?string $catatan = null): Pengajuan
    {
        $statusLama = $pengajuan->status;

        $diizinkan = self::ALUR[$statusLama->value] ?? [];
        if (! in_array($statusBaru->value, $diizinkan, true)) {
            throw new \InvalidArgumentException(
                "Status tidak boleh berpindah dari {$statusLama->value} ke {$statusBaru->value}."
            );
        }

        DB::transaction(function () use ($pengajuan, $statusLama, $statusBaru, $user, $catatan) {
            $pengajuan->update(['status' => $statusBaru]);

            RiwayatStatus::create([
                'pengajuan_id' => $pengajuan->id,
                'status_dari'  => $statusLama->value,
                'status_ke'    => $statusBaru->value,
                'user_id'      => $user?->id,
                'catatan'      => $catatan,
            ]);
        });

        return $pengajuan;
    }
}