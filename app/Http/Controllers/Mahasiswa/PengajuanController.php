<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use App\Models\Pengajuan;
use App\StatusPengajuan;
use Illuminate\Http\Request;

class PengajuanController extends Controller
{
    /** ID mahasiswa yang sedang login. Kalau akunnya tidak punya data mahasiswa -> 403. */
    private function mahasiswaId(): int
    {
        $mahasiswa = auth()->user()->mahasiswa;
        abort_if(! $mahasiswa, 403);

        return (int) $mahasiswa->id;
    }

    public function index(Request $request)
    {
        $statusAktif = StatusPengajuan::tryFrom((string) $request->query('status'));

        $pengajuans = Pengajuan::with(['jenisSurat', 'data'])
            ->where('mahasiswa_id', $this->mahasiswaId())
            ->when($statusAktif, fn ($q) => $q->where('status', $statusAktif->value))
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('mahasiswa.pengajuan.index', [
            'pengajuans' => $pengajuans,
            'statuses' => StatusPengajuan::cases(),
            'statusAktif' => $statusAktif,
        ]);
    }

    public function show(Pengajuan $pengajuan)
    {
        abort_if((int) $pengajuan->mahasiswa_id !== $this->mahasiswaId(), 403);

        $pengajuan->load([
            'jenisSurat',
            'templateSurat.fields',
            'anggotas.programStudi',
            'lampirans',
            'riwayats.user',
        ]);

        return view('mahasiswa.pengajuan.show', compact('pengajuan'));
    }
}