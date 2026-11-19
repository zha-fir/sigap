<?php

namespace App\Http\Controllers\Kajur;

use App\Http\Controllers\Controller;
use App\Models\Pengajuan;
use App\StatusPengajuan;
use App\Services\SuratRenderer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SuratController extends Controller
{
    public function index(Request $request)
    {
        $cari = $request->query('cari');

        $pengajuans = Pengajuan::with([
            'mahasiswa.user',
            'jenisSurat',
            'surat',
        ])
        ->where('status', StatusPengajuan::MenungguTtd)
        ->when($cari, function ($query) use ($cari) {
            $query->where(function ($q) use ($cari) {
                $q->where('kode_tiket', 'like', "%{$cari}%")
                    ->orWhereHas('mahasiswa', function ($m) use ($cari) {
                        $m->where('nim', 'like', "%{$cari}%")
                            ->orWhereHas('user', function ($u) use ($cari) {
                                $u->where('name', 'like', "%{$cari}%");
                            });
                    })
                    ->orWhereHas('jenisSurat', function ($j) use ($cari) {
                        $j->where('nama', 'like', "%{$cari}%");
                    });
            });
        })
        ->latest('diajukan_at')
        ->paginate(10)
        ->withQueryString();

        return view('kajur.surat.index', compact('pengajuans', 'cari'));
    }

    public function show(Pengajuan $pengajuan, SuratRenderer $renderer)
    {
        abort_unless(
            $pengajuan->status === StatusPengajuan::MenungguTtd,
            404
        );

        $pengajuan->load([
            'mahasiswa.user',
            'mahasiswa.programStudi',
            'jenisSurat',
            'templateSurat.fields',
            'anggotas',
            'data',
            'lampirans',
            'surat.pejabat',
        ]);

        $isiSurat = $renderer->render($pengajuan);

        $urlPdf = $pengajuan->surat?->file_pdf
            ? Storage::disk('public')->url($pengajuan->surat->file_pdf)
            : null;

        return view('kajur.surat.show', compact(
            'pengajuan',
            'isiSurat',
            'urlPdf'
        ));
    }
}
