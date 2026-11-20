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
        $request->validate([
            'cari' => 'nullable|string|max:100',
            'status' => ['nullable', Rule::enum(StatusPengajuan::class)]
        ]);

        $query = Pengajuan::with([
            'mahasiswa.user',
            'jenisSurat'
        ])->where('status', '!=', StatusPengajuan::Draft->value);

        if ($request->filled('cari')) {
            $cari = trim($request->input('cari'));

            $query->where(function ($q) use ($cari) {
                $q->where('kode_tiket', 'like', "%{$cari}%")
                    ->orWhereHas('mahasiswa', function ($m) use ($cari) {
                        $m->where('nim', 'like', "%{$cari}%");
                    });
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        $pengajuans = $query
            ->latest('id')
            ->paginate(10)
            ->withQueryString();

        $pilihanStatus = StatusPengajuan::cases();

        return view('operator.pengajuan', compact(
            'pengajuans',
            'pilihanStatus'
        ));
    }
}
