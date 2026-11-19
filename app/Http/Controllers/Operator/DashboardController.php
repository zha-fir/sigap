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

        $statistik = [
            [
                'nama' => 'Pengajuan Baru',
                'jumlah' => $baru,
                'ikon' => 'inbox',
                'warna' => 'primary'
            ],
            [
                'nama' => 'Mendekati SLA',
                'jumlah' => null,
                'ikon' => 'clock',
                'warna' => 'warning'
            ],
            [
                'nama' => 'Lewat SLA',
                'jumlah' => null,
                'ikon' => 'exclamation-triangle',
                'warna' => 'danger'
            ],
            [
                'nama' => 'Menunggu Perbaikan',
                'jumlah' => $perbaikan,
                'ikon' => 'arrow-repeat',
                'warna' => 'info'
            ],
            [
                'nama' => 'Siap Diambil',
                'jumlah' => $siap,
                'ikon' => 'check-circle',
                'warna' => 'success'
            ],
            [
                'nama' => 'Hampir Kedaluwarsa',
                'jumlah' => null,
                'ikon' => 'calendar-x',
                'warna' => 'secondary'
            ]
        ];

        $prioritas = (clone $query)
            ->with(['mahasiswa.user', 'jenisSurat'])
            ->whereNotIn('status', [
                StatusPengajuan::Draft->value,
                StatusPengajuan::Selesai->value,
                StatusPengajuan::Ditolak->value
            ])
            ->orderBy('diajukan_at')
            ->orderBy('id')
            ->limit(10)
            ->get();

        return view('operator.dashboard', compact(
            'statistik',
            'prioritas'
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
