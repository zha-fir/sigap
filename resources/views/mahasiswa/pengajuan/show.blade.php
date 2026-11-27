@extends('layouts.app')

@section('title', 'Detail Pengajuan')

@section('content')
@php
    $kembaliUrl = request('dari') === 'dashboard'
        ? route('mahasiswa.dashboard')
        : route('mahasiswa.pengajuan.index');
@endphp
    <style>
        .min-w-0 { min-width: 0; }
        .card-body dd, .card-body dt { overflow-wrap: anywhere; }
        aside { flex-shrink: 0; }

        .tracker { display: flex; overflow-x: auto; padding: .35rem .25rem .25rem; }
        .tracker-step { flex: 1 0 105px; position: relative; text-align: center; padding: 0 .25rem; }
        .tracker-step::before { content: ''; position: absolute; top: 19px; right: 50%; width: 100%; height: 3px; background: #dee2e6; }
        .tracker-step:first-child::before { display: none; }
        .tracker-icon { position: relative; z-index: 1; width: 40px; height: 40px; margin: 0 auto .5rem; border-radius: 50%;
                        display: flex; align-items: center; justify-content: center; font-size: 1.1rem;
                        background: #e9ecef; color: #adb5bd; border: 3px solid #dee2e6; }
        .tracker-label { font-size: .85rem; font-weight: 600; }
        .tracker-time { display: block; color: #6c757d; font-size: .75rem; }
        .tracker-step.upcoming .tracker-label { color: #adb5bd; font-weight: 500; }

        .tracker-step.done::before, .tracker-step.current::before, .tracker-step.warning::before { background: #198754; }
        .tracker-step.done .tracker-icon { background: #198754; border-color: #198754; color: #fff; }
        .tracker-step.current .tracker-icon { background: #0d6efd; border-color: #0d6efd; color: #fff; box-shadow: 0 0 0 .25rem rgba(13,110,253,.2); }
        .tracker-step.warning .tracker-icon { background: #ffc107; border-color: #ffc107; color: #000; box-shadow: 0 0 0 .25rem rgba(255,193,7,.3); }
        .tracker-step.danger::before { background: #dc3545; }
        .tracker-step.danger .tracker-icon { background: #dc3545; border-color: #dc3545; color: #fff; box-shadow: 0 0 0 .25rem rgba(220,53,69,.2); }
    </style>

    @php
        $isian = $pengajuan->dataArray();
        $fields = $pengajuan->templateSurat?->fields ?? collect();
    @endphp

        <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h1 class="h4 mb-1">{{ $pengajuan->kode_tiket }}</h1>
            <span class="text-muted">{{ $pengajuan->jenisSurat->nama ?? '-' }}</span>
        </div>
        <div class="d-flex flex-column align-items-end gap-2">
            <x-status-badge :status="$pengajuan->status" />
            <a href="{{ $kembaliUrl }}" class="btn btn-outline-secondary btn-sm">
                <i class="bi bi-arrow-left me-1"></i>Kembali
            </a>
        </div>
    </div>

    @if ($pengajuan->catatan_revisi && $pengajuan->status === \App\StatusPengajuan::PerluRevisi)
    <div class="alert alert-warning py-2 px-3 d-flex justify-content-between align-items-center gap-3">
        <div>
            <strong>Catatan revisi dari operator:</strong>
            {{ $pengajuan->catatan_revisi }}
        </div>
        @if (Route::has('mahasiswa.pengajuan.edit'))
            <a href="{{ route('mahasiswa.pengajuan.edit', $pengajuan) }}" class="btn btn-warning btn-sm flex-shrink-0">
                <i class="bi bi-pencil-square me-1"></i>Edit
            </a>
        @endif
    </div>
    @endif
    
    @if ($pengajuan->alasan_tolak && $pengajuan->status === \App\StatusPengajuan::Ditolak)
        <div class="alert alert-danger">
            <strong>Alasan penolakan:</strong><br>
            {{ $pengajuan->alasan_tolak }}
        </div>
    @endif

    @php
        $status = $pengajuan->status;
        $ditolak = $status === \App\StatusPengajuan::Ditolak;
        $revisi = $status === \App\StatusPengajuan::PerluRevisi;

        $alur = [
            [\App\StatusPengajuan::Draft, 'bi-pencil'],
            [\App\StatusPengajuan::Diajukan, 'bi-send'],
            [\App\StatusPengajuan::Diproses, 'bi-gear'],
            [\App\StatusPengajuan::MenungguTtd, 'bi-pen'],
            [\App\StatusPengajuan::Ditandatangani, 'bi-patch-check'],
            [\App\StatusPengajuan::SiapDiambil, 'bi-box-seam'],
            [\App\StatusPengajuan::Selesai, 'bi-check2-circle'],
        ];
        $urutan = array_map(fn ($a) => $a[0], $alur);

        if ($ditolak) {
            $dari = $pengajuan->riwayats->where('status_ke', 'ditolak')->last()?->status_dari;
            $posisi = array_search(\App\StatusPengajuan::tryFrom((string) $dari), $urutan, true);
            $posisi = $posisi === false ? 1 : $posisi;
        } elseif ($revisi) {
            $posisi = 1; // perlu revisi = kembali ke tahap Diajukan
        } else {
            $posisi = array_search($status, $urutan, true);
        }

        $langkah = [];
        foreach ($alur as $i => [$s, $ikon]) {
            if ($ditolak && $i > $posisi) {
                break; // pengajuan ditolak: tahap setelahnya tidak ditampilkan
            }

            if ($i < $posisi
                || ($i === $posisi && ($ditolak || $status === \App\StatusPengajuan::Selesai))) {
                $kelas = 'done';
            } elseif ($i === $posisi) {
                $kelas = $revisi ? 'warning' : 'current';
            } else {
                $kelas = 'upcoming';
            }

            $langkah[] = [
                'label' => $s->label(),
                'ikon' => $kelas === 'warning' ? 'bi-exclamation-triangle' : $ikon,
                'kelas' => $kelas,
                'waktu' => $kelas === 'upcoming'
                    ? null
                    : $pengajuan->riwayats->where('status_ke', $s->value)->last()?->created_at,
            ];
        }

        if ($ditolak) {
            $langkah[] = [
                'label' => 'Ditolak',
                'ikon' => 'bi-x-circle',
                'kelas' => 'danger',
                'waktu' => $pengajuan->riwayats->where('status_ke', 'ditolak')->last()?->created_at,
            ];
        }

        $keterangan = match ($status) {
            \App\StatusPengajuan::Draft => 'Pengajuan masih berupa draft. Lengkapi datanya lalu tekan Ajukan.',
            \App\StatusPengajuan::Diajukan => 'Pengajuan sudah dikirim dan menunggu diperiksa operator.',
            \App\StatusPengajuan::Diproses => 'Pengajuan diterima operator dan suratnya sedang dibuat.',
            \App\StatusPengajuan::PerluRevisi => 'Operator meminta perbaikan. Periksa catatan revisi, lalu kirim ulang pengajuan.',
            \App\StatusPengajuan::MenungguTtd => 'Surat sudah dikirim ke ketua jurusan dan menunggu tanda tangan.',
            \App\StatusPengajuan::Ditandatangani => 'Surat sudah ditandatangani ketua jurusan.',
            \App\StatusPengajuan::SiapDiambil => 'Surat sudah siap diambil atau dicetak.',
            \App\StatusPengajuan::Selesai => 'Proses selesai. Surat sudah kamu terima.',
            \App\StatusPengajuan::Ditolak => 'Pengajuan ditolak. Lihat alasan penolakan di atas.',
        };
    @endphp

    <div class="card mb-3">
        <div class="card-header fw-semibold">Status Pengajuan</div>
        <div class="card-body">
            <div class="tracker">
                @foreach ($langkah as $l)
                    <div class="tracker-step {{ $l['kelas'] }}">
                        <div class="tracker-icon"><i class="bi {{ $l['ikon'] }}"></i></div>
                        <div class="tracker-label">{{ $l['label'] }}</div>
                        @if ($l['waktu'])
                            <small class="tracker-time">{{ $l['waktu']->translatedFormat('d M Y, H:i') }}</small>
                        @endif
                    </div>
                @endforeach
            </div>
            <p class="text-muted small mb-0 mt-3">{{ $keterangan }}</p>
        </div>
    </div>

    <div class="row g-3" style="min-width: 0;">
        <div class="col-lg-8 min-w-0">
            <div class="card mb-3">
                <div class="card-header fw-semibold">Data Isian</div>
                <div class="card-body">
                    <dl class="row mb-0">
                        @forelse ($fields as $f)
                            <dt class="col-sm-4">{{ $f->label ?? $f->key }}</dt>
                            <dd class="col-sm-8">{{ $isian[$f->key] ?? '-' }}</dd>
                        @empty
                            @foreach ($isian as $key => $value)
                                <dt class="col-sm-4">{{ $key }}</dt>
                                <dd class="col-sm-8">{{ $value }}</dd>
                            @endforeach
                        @endforelse
                    </dl>
                </div>
            </div>

            <div class="card mb-3">
                <div class="card-header fw-semibold">Anggota Kelompok</div>
                <div class="table-responsive">
                    <table class="table mb-0">
                        <thead class="table-light">
                            <tr><th>Nama</th><th>NIM</th><th>Program Studi</th><th></th></tr>
                        </thead>
                        <tbody>
                            @forelse ($pengajuan->anggotas as $a)
                                <tr>
                                    <td>{{ $a->nama }}</td>
                                    <td>{{ $a->nim }}</td>
                                    <td>{{ $a->programStudi->nama ?? '-' }}</td>
                                    <td>
                                        @if ($a->is_ketua)
                                            <span class="badge text-bg-primary">Ketua</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="4" class="text-center text-muted">Belum ada anggota.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="card mb-3">
                <div class="card-header fw-semibold">Lampiran</div>
                <ul class="list-group list-group-flush">
                    @forelse ($pengajuan->lampirans as $l)
                        <li class="list-group-item d-flex justify-content-between">
                            <a href="{{ asset('storage/' . $l->path) }}" target="_blank">
                                <i class="bi bi-paperclip me-1"></i>{{ $l->nama_file }}
                            </a>
                            <small class="text-muted">{{ number_format($l->ukuran / 1024, 1) }} KB</small>
                        </li>
                    @empty
                        <li class="list-group-item text-muted">Tidak ada lampiran.</li>
                    @endforelse
                </ul>
            </div>

            <div class="d-flex gap-2">

                @if (Route::has('mahasiswa.pengajuan.edit')
                    && in_array($pengajuan->status, [\App\StatusPengajuan::Draft, \App\StatusPengajuan::PerluRevisi]))
                    <a href="{{ route('mahasiswa.pengajuan.edit', $pengajuan) }}" class="btn btn-warning">Edit</a>
                @endif

                @if (Route::has('mahasiswa.surat.cetak')
                    && in_array($pengajuan->status, [
                        \App\StatusPengajuan::Ditandatangani,
                        \App\StatusPengajuan::SiapDiambil,
                        \App\StatusPengajuan::Selesai,
                    ]))
                    <a href="{{ route('mahasiswa.surat.cetak', $pengajuan) }}" class="btn btn-success" target="_blank">
                        <i class="bi bi-printer me-1"></i>Cetak Surat
                    </a>
                @endif
            </div>
        </div>

        <div class="col-lg-4 min-w-0">
            <div class="card">
                <div class="card-header fw-semibold">Riwayat Status</div>
                <div class="card-body">
                    @forelse ($pengajuan->riwayats as $r)
                        @php $statusKe = \App\StatusPengajuan::tryFrom($r->status_ke); @endphp
                        <div class="d-flex mb-3">
                            <div class="me-3 text-primary"><i class="bi bi-circle-fill small"></i></div>
                            <div>
                                <div class="fw-semibold">{{ $statusKe?->label() ?? $r->status_ke }}</div>
                                <small class="text-muted d-block">
                                    {{ $r->created_at->translatedFormat('d F Y, H:i') }}
                                    @if ($r->user) &middot; {{ $r->user->name }} @endif
                                </small>
                                @if ($r->catatan)
                                    <div class="small mt-1">{{ $r->catatan }}</div>
                                @endif
                            </div>
                        </div>
                    @empty
                        <p class="text-muted mb-0">Belum ada riwayat.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
@endsection
