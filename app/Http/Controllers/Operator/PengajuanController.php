<?php

namespace App\Http\Controllers\Operator;

use App\Http\Controllers\Controller;
use App\Models\Pengajuan;
use App\StatusPengajuan;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class PengajuanController extends Controller
{
    public function index(Request $request)
    {
        $pilihanStatus = [
            StatusPengajuan::Diajukan,
            StatusPengajuan::Diproses,
            StatusPengajuan::PerluRevisi
        ];

        $request->validate([
            'cari' => ['nullable', 'string', 'max:100'],
            'status' => ['nullable', Rule::in(array_map(
                fn ($status) => $status->value,
                $pilihanStatus
            ))]
        ]);

        $pengajuans = Pengajuan::with([
            'mahasiswa.user',
            'jenisSurat',
            'data'
        ])
            ->whereIn('status', array_map(
                fn ($status) => $status->value,
                $pilihanStatus
            ))
            ->when($request->filled('cari'), function ($query) use ($request) {
                $cari = trim($request->input('cari'));

                $query->where(function ($q) use ($cari) {
                    $q->where('kode_tiket', 'like', "%{$cari}%")
                        ->orWhereHas('mahasiswa', function ($mahasiswa) use ($cari) {
                            $mahasiswa->where('nim', 'like', "%{$cari}%")
                                ->orWhereHas('user', function ($user) use ($cari) {
                                    $user->where('name', 'like', "%{$cari}%");
                                });
                        });
                });
            })
            ->when(
                $request->filled('status'),
                fn ($query) => $query->where('status', $request->input('status'))
            )
            ->latest('id')
            ->paginate(10)
            ->withQueryString();

        return view('operator.pengajuan', compact(
            'pengajuans',
            'pilihanStatus'
        ));
    }
}
