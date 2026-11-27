<?php

namespace App\Http\Controllers\Operator;

use App\Http\Controllers\Controller;
use App\Models\Pejabat;
use App\Models\Pengajuan;
use App\Services\NomorSuratService;
use App\Services\PengajuanStatusService;
use App\Services\SuratRenderer;
use App\StatusPengajuan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class SuratController extends Controller
{
    public function buat()
    {
        $pengajuans = Pengajuan::with([
            'mahasiswa.user',
            'jenisSurat',
            'templateSurat'
        ])
            ->where('status', StatusPengajuan::Diproses->value)
            ->latest('id')
            ->paginate(10);

        return view('operator.surat.buat', compact('pengajuans'));
    }

    public function edit(Pengajuan $pengajuan, SuratRenderer $renderer)
    {
        abort_unless(
            $pengajuan->status === StatusPengajuan::Diproses,
            404
        );

        $pengajuan->load([
            'mahasiswa.user',
            'jenisSurat',
            'templateSurat.fields',
            'anggotas',
            'data',
            'surat.pejabat'
        ]);

        $templateSiap = (bool) (
            $pengajuan->templateSurat?->is_active
            && $pengajuan->templateSurat?->isi_template
        );

        $isiSurat = $templateSiap
            ? $renderer->render($pengajuan)
            : null;

        $pejabatAktif = Pejabat::where('is_active', true)->exists();

        return view('operator.surat.edit', compact(
            'pengajuan',
            'templateSiap',
            'isiSurat',
            'pejabatAktif'
        ));
    }

    public function simpan(Request $request, Pengajuan $pengajuan)
    {
        $validated = $request->validate([
            'tanggal_surat' => ['required', 'date'],
            'isi_surat' => ['required', 'string', 'max:200000']
        ]);

        DB::transaction(function () use ($pengajuan, $validated) {
            $data = Pengajuan::with([
                'templateSurat',
                'surat'
            ])
                ->whereKey($pengajuan->id)
                ->lockForUpdate()
                ->firstOrFail();

            if ($data->status !== StatusPengajuan::Diproses) {
                throw ValidationException::withMessages([
                    'status' => 'Pengajuan belum dapat dibuatkan surat.'
                ]);
            }

            if (!$data->templateSurat?->is_active) {
                throw ValidationException::withMessages([
                    'template' => 'Template surat aktif belum tersedia.'
                ]);
            }

            if ($data->surat) {
                $data->surat->update($validated);
            } else {
                $pejabat = Pejabat::where('is_active', true)->first();

                $data->surat()->create($validated + [
                    'kode_verifikasi' => Str::random(40),
                    'dibuat_oleh' => auth()->id(),
                    'pejabat_id' => $pejabat?->id,
                    'status_ttd' => 'belum'
                ]);
            }
        });

        return back()->with(
            'success',
            'Draf surat berhasil disimpan.'
        );
    }

    public function kirim(
        Pengajuan $pengajuan,
        PengajuanStatusService $statusService,
        NomorSuratService $nomorService
    ) {
        DB::transaction(function () use (
            $pengajuan,
            $statusService,
            $nomorService
        ) {
            $data = Pengajuan::with([
                'templateSurat.jenisSurat',
                'surat'
            ])
                ->whereKey($pengajuan->id)
                ->lockForUpdate()
                ->firstOrFail();

            if ($data->status !== StatusPengajuan::Diproses) {
                throw ValidationException::withMessages([
                    'status' => 'Surat tidak dapat dikirim pada status ini.'
                ]);
            }

            if (!$data->templateSurat?->is_active) {
                throw ValidationException::withMessages([
                    'template' => 'Template surat aktif belum tersedia.'
                ]);
            }

            $surat = $data->surat;

            if (
                !$surat
                || !$surat->tanggal_surat
                || !$surat->isi_surat
            ) {
                throw ValidationException::withMessages([
                    'surat' => 'Simpan tanggal dan isi surat sebelum dikirim.'
                ]);
            }

            $pejabat = Pejabat::where('is_active', true)->first();

            if (!$pejabat) {
                throw ValidationException::withMessages([
                    'pejabat' => 'Kajur aktif belum tersedia.'
                ]);
            }

            $nomor = $surat->nomor_surat
                ?: $nomorService->generate($data->templateSurat);

            $surat->update([
                'nomor_surat' => $nomor,
                'pejabat_id' => $pejabat->id,
                'status_ttd' => 'belum'
            ]);

            $statusService->ubah(
                $data,
                StatusPengajuan::MenungguTtd,
                auth()->user()
            );
        });

        return redirect()
            ->route('operator.pengajuan.show', $pengajuan->id)
            ->with('success', 'Surat berhasil dikirim ke Kajur.');
    }

    public function index()
    {
        $pengajuans = Pengajuan::with([
            'mahasiswa.user',
            'jenisSurat',
            'surat'
        ])
            ->whereIn('status', [
                StatusPengajuan::Ditandatangani->value,
                StatusPengajuan::SiapDiambil->value,
                StatusPengajuan::Selesai->value
            ])
            ->whereHas('surat')
            ->latest('id')
            ->paginate(10);

        return view('operator.surat.index', compact('pengajuans'));
    }

    public function cetak(
        Pengajuan $pengajuan,
        SuratRenderer $renderer
    ) {
        abort_unless(in_array($pengajuan->status, [
            StatusPengajuan::Ditandatangani,
            StatusPengajuan::SiapDiambil,
            StatusPengajuan::Selesai
        ], true), 404);

        abort_unless($pengajuan->surat, 404);
        abort_unless(
            $pengajuan->surat->status_ttd === 'ditandatangani',
            409
        );

        $isiSurat = $renderer->render($pengajuan);

        return view('surat.cetak', compact(
            'pengajuan',
            'isiSurat'
        ));
    }

    public function siapDiambil(
        Pengajuan $pengajuan,
        PengajuanStatusService $service
    ) {
        DB::transaction(function () use ($pengajuan, $service) {
            $data = Pengajuan::whereKey($pengajuan->id)
                ->lockForUpdate()
                ->firstOrFail();

            if (
                $data->status !== StatusPengajuan::Ditandatangani
                || !$data->surat()->exists()
            ) {
                throw ValidationException::withMessages([
                    'status' => 'Surat belum ditandatangani Kajur.'
                ]);
            }

            $service->ubah(
                $data,
                StatusPengajuan::SiapDiambil,
                auth()->user()
            );
        });

        return back()->with(
            'success',
            'Surat sudah siap diambil.'
        );
    }

    public function selesai(
        Pengajuan $pengajuan,
        PengajuanStatusService $service
    ) {
        DB::transaction(function () use ($pengajuan, $service) {
            $data = Pengajuan::whereKey($pengajuan->id)
                ->lockForUpdate()
                ->firstOrFail();

            if ($data->status !== StatusPengajuan::SiapDiambil) {
                throw ValidationException::withMessages([
                    'status' => 'Surat belum siap diselesaikan.'
                ]);
            }

            $service->ubah(
                $data,
                StatusPengajuan::Selesai,
                auth()->user()
            );
        });

        return back()->with(
            'success',
            'Surat selesai diserahkan.'
        );
    }
}
