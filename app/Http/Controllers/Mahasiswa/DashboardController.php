<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pengajuan;          
use App\StatusPengajuan;  

class DashboardController extends Controller
{
    public function index()
    {
         $mahasiswa = auth()->user()->mahasiswa;
        abort_if(! $mahasiswa, 403);

        $jumlah = Pengajuan::toBase()
            ->where('mahasiswa_id', $mahasiswa->id)
            ->selectRaw('status, count(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status');

            $ringkasan = collect(StatusPengajuan::cases())->map(fn ($s) => [
            'status' => $s,
            'total' => (int) ($jumlah[$s->value] ?? 0),
        ]);

         $terbaru = Pengajuan::with('jenisSurat')
            ->where('mahasiswa_id', $mahasiswa->id)
            ->latest()
            ->take(5)
            ->get();

        return view('mahasiswa.dashboard' , [
            'mahasiswa' => $mahasiswa,
            'totalPengajuan' => (int) $jumlah->sum(),
            'ringkasan' => $ringkasan,
            'terbaru' => $terbaru,
        ]);
    }
}