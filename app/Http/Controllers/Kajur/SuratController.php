<?php

namespace App\Http\Controllers\Kajur;

use App\Http\Controllers\Controller;
use App\Models\Pengajuan;
use App\StatusPengajuan;
use App\Services\SuratRenderer;
use App\Services\PengajuanStatusService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class SuratController extends Controller
{
    // 1. Menampilkan daftar surat
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

    // 2. Menampilkan detail surat
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

    // 3. Proses tanda tangan
    public function tandatangani(
        Pengajuan $pengajuan,
        PengajuanStatusService $service
    ) {
        abort_unless(
            $pengajuan->status === StatusPengajuan::MenungguTtd,
            404
        );

        $pejabat = auth()->user()->pejabat;

        abort_if(
            !$pejabat || !$pejabat->is_active || !$pejabat->file_ttd,
            403,
            'Data pejabat atau tanda tangan belum tersedia.'
        );

        $surat = $pengajuan->surat;

        abort_if(!$surat, 404, 'Data surat tidak ditemukan.');

        DB::transaction(function () use (
            $surat,
            $pejabat,
            $pengajuan,
            $service
        ) {
            $surat->update([
                'pejabat_id' => $pejabat->id,
                'status_ttd' => 'sudah',
                'ditandatangani_at' => now(),
            ]);

            $service->ubah(
                $pengajuan,
                StatusPengajuan::Ditandatangani,
                auth()->user(),
                'Surat berhasil ditandatangani Kajur.'
            );
        });

        return redirect()
            ->route('kajur.surat.index')
            ->with('success', 'Surat berhasil ditandatangani.');
    }

    // 4. Mengembalikan surat dari kajur kepada Operator
    public function kembalikan(
        Request $request,
        Pengajuan $pengajuan,
        PengajuanStatusService $service
    ) {
        $validated = $request->validate([
            'catatan' => ['required', 'string', 'max:1000'],
        ], [
            'catatan.required' => 'Catatan pengembalian wajib diisi.',
            'catatan.max' => 'Catatan maksimal 1000 karakter.',
        ]);

        abort_unless(
            $pengajuan->status === StatusPengajuan::MenungguTtd,
            404,
            'Pengajuan tidak dapat dikembalikan pada status ini.'
        );

        $service->ubah(
            $pengajuan,
            StatusPengajuan::Diproses,
            auth()->user(),
            $validated['catatan']
        );

        return redirect()
            ->route('kajur.surat.index')
            ->with('success', 'Surat berhasil dikembalikan kepada Operator.');
    }
}
