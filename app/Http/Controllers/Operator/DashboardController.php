<?php

namespace App\Http\Controllers\Operator;

use App\Http\Controllers\Controller;
use App\Models\Pengajuan;
use App\StatusPengajuan;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class DashboardController extends Controller
{
    private function dataPengajuan()
    {
        return Pengajuan::where('kode_tiket', 'like', 'PJ-CONTOH-%');
    }

    public function index()
    {
        $query = $this->dataPengajuan();

        $baru = (clone $query)
            ->where('status', StatusPengajuan::Diajukan->value)
            ->count();

        $perbaikan = (clone $query)
            ->where('status', StatusPengajuan::PerluRevisi->value)
            ->count();

        $siap = (clone $query)
            ->where('status', StatusPengajuan::SiapDiambil->value)
            ->count();

        $terbaru = (clone $query)
            ->with(['mahasiswa.user', 'jenisSurat'])
            ->latest('id')
            ->take(5)
            ->get();

        return view('operator.dashboard', compact(
            'baru',
            'perbaikan',
            'siap',
            'terbaru'
        ));
    }

    public function pengajuan(Request $request)
    {
        $request->validate([
            'cari' => 'nullable|string|max:100',
            'status' => ['nullable', Rule::enum(StatusPengajuan::class)]
        ]);

        $query = $this->dataPengajuan()
            ->with(['mahasiswa.user', 'jenisSurat']);

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

    public function verifikasi()
    {
        $pengajuans = $this->dataPengajuan()
            ->with(['mahasiswa.user', 'jenisSurat'])
            ->whereIn('status', [
                StatusPengajuan::Diajukan->value,
                StatusPengajuan::Diproses->value,
                StatusPengajuan::PerluRevisi->value
            ])
            ->latest('id')
            ->paginate(10);

        return view('operator.verifikasi', compact('pengajuans'));
    }

    public function detail(Pengajuan $pengajuan)
    {
        abort_unless(
            str_starts_with($pengajuan->kode_tiket, 'PJ-CONTOH-'),
            404
        );

        $pengajuan->load([
            'mahasiswa.user',
            'mahasiswa.programStudi',
            'jenisSurat',
            'data',
            'lampirans'
        ]);

        return view('operator.detail', compact('pengajuan'));
    }
}
