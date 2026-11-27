<?php

namespace App\Http\Controllers\Operator;

use App\Http\Controllers\Controller;
use App\Models\Lampiran;
use App\Models\Pengajuan;
use App\Services\PengajuanStatusService;
use App\StatusPengajuan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;

class VerifikasiController extends Controller
{
    public function index()
    {
        $pengajuans = Pengajuan::with([
            'mahasiswa.user',
            'jenisSurat'
        ])
            ->whereIn('status', [
                StatusPengajuan::Diajukan->value,
                StatusPengajuan::Diproses->value,
                StatusPengajuan::PerluRevisi->value
            ])
            ->latest('id')
            ->paginate(10);

        return view('operator.verifikasi', compact('pengajuans'));
    }

    public function show(Pengajuan $pengajuan)
    {
        abort_if(
            $pengajuan->status === StatusPengajuan::Draft,
            404
        );

        $pengajuan->load([
            'mahasiswa.user',
            'mahasiswa.programStudi',
            'jenisSurat',
            'templateSurat.fields',
            'data',
            'anggotas.programStudi',
            'lampirans',
            'riwayats.user'
        ]);

        return view('operator.detail', compact('pengajuan'));
    }

    public function terima(
        Pengajuan $pengajuan,
        PengajuanStatusService $service
    ) {
        DB::transaction(function () use ($pengajuan, $service) {
            $data = Pengajuan::whereKey($pengajuan->id)
                ->lockForUpdate()
                ->firstOrFail();

            if ($data->status !== StatusPengajuan::Diajukan) {
                throw ValidationException::withMessages([
                    'status' => 'Pengajuan tidak dapat diterima.'
                ]);
            }

            $service->ubah(
                $data,
                StatusPengajuan::Diproses,
                auth()->user()
            );
        });

        return back()->with(
            'success',
            'Pengajuan berhasil diterima.'
        );
    }

    public function revisi(
        Request $request,
        Pengajuan $pengajuan,
        PengajuanStatusService $service
    ) {
        $validated = $request->validate([
            'catatan' => ['required', 'string', 'max:1000']
        ]);

        DB::transaction(function () use (
            $pengajuan,
            $service,
            $validated
        ) {
            $data = Pengajuan::whereKey($pengajuan->id)
                ->lockForUpdate()
                ->firstOrFail();

            if (!in_array($data->status, [
                StatusPengajuan::Diajukan,
                StatusPengajuan::Diproses
            ], true)) {
                throw ValidationException::withMessages([
                    'status' => 'Pengajuan tidak dapat direvisi.'
                ]);
            }

            $data->update([
                'catatan_revisi' => $validated['catatan'],
                'alasan_tolak' => null
            ]);

            $service->ubah(
                $data,
                StatusPengajuan::PerluRevisi,
                auth()->user(),
                $validated['catatan']
            );
        });

        return back()->with(
            'success',
            'Permintaan revisi berhasil dikirim.'
        );
    }

    public function tolak(
        Request $request,
        Pengajuan $pengajuan,
        PengajuanStatusService $service
    ) {
        $validated = $request->validate([
            'alasan' => ['required', 'string', 'max:1000']
        ]);

        DB::transaction(function () use (
            $pengajuan,
            $service,
            $validated
        ) {
            $data = Pengajuan::whereKey($pengajuan->id)
                ->lockForUpdate()
                ->firstOrFail();

            if (!in_array($data->status, [
                StatusPengajuan::Diajukan,
                StatusPengajuan::Diproses
            ], true)) {
                throw ValidationException::withMessages([
                    'status' => 'Pengajuan tidak dapat ditolak.'
                ]);
            }

            $data->update([
                'alasan_tolak' => $validated['alasan']
            ]);

            $service->ubah(
                $data,
                StatusPengajuan::Ditolak,
                auth()->user(),
                $validated['alasan']
            );
        });

        return back()->with(
            'success',
            'Pengajuan berhasil ditolak.'
        );
    }

    public function unduhLampiran(Lampiran $lampiran)
    {
        abort_unless($lampiran->pengajuan, 404);

        abort_if(
            $lampiran->pengajuan->status === StatusPengajuan::Draft,
            404
        );

        abort_unless(
            Storage::disk('public')->exists($lampiran->path),
            404
        );

        return Storage::disk('public')->download(
            $lampiran->path,
            $lampiran->nama_file
        );
    }
}
