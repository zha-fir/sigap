<?php

namespace App\Http\Controllers\Operator;

use App\Http\Controllers\Controller;
use App\Models\Pengajuan;
use App\StatusPengajuan;

class DashboardController extends Controller
{
    public function index()
    {
        $statistik = [
            [
                'nama' => 'Menunggu Verifikasi',
                'jumlah' => Pengajuan::where(
                    'status',
                    StatusPengajuan::Diajukan->value
                )->count(),
                'ikon' => 'inbox',
                'warna' => 'primary'
            ],
            [
                'nama' => 'Sedang Diproses',
                'jumlah' => Pengajuan::where(
                    'status',
                    StatusPengajuan::Diproses->value
                )->count(),
                'ikon' => 'hourglass-split',
                'warna' => 'info'
            ],
            [
                'nama' => 'Menunggu TTD',
                'jumlah' => Pengajuan::where(
                    'status',
                    StatusPengajuan::MenungguTtd->value
                )->count(),
                'ikon' => 'pen',
                'warna' => 'warning'
            ],
            [
                'nama' => 'Selesai Bulan Ini',
                'jumlah' => Pengajuan::where(
                    'status',
                    StatusPengajuan::Selesai->value
                )->whereHas('riwayats', function ($query) {
                    $query->where(
                        'status_ke',
                        StatusPengajuan::Selesai->value
                    )->whereBetween('created_at', [
                        now()->startOfMonth(),
                        now()->endOfMonth()
                    ]);
                })->count(),
                'ikon' => 'check-circle',
                'warna' => 'success'
            ]
        ];

        $pengajuanTerakhir = Pengajuan::with('mahasiswa.user')
            ->where('status', '!=', StatusPengajuan::Draft->value)
            ->latest('diajukan_at')
            ->latest('id')
            ->first();

        $prioritas = Pengajuan::with([
            'mahasiswa.user',
            'jenisSurat',
            'data'
        ])
            ->whereIn('status', [
                StatusPengajuan::Diajukan->value,
                StatusPengajuan::Diproses->value,
                StatusPengajuan::PerluRevisi->value
            ])
            ->orderBy('diajukan_at')
            ->orderBy('id')
            ->limit(8)
            ->get();

        return view('operator.dashboard', compact(
            'statistik',
            'pengajuanTerakhir',
            'prioritas'
        ));
    }
}
