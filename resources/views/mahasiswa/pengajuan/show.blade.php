@extends('layouts.app')

@section('title', 'Detail Pengajuan')

@section('content')
    <style>
        .min-w-0 { min-width: 0; }
        .card-body dd, .card-body dt { overflow-wrap: anywhere; }
        aside { flex-shrink: 0; }
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
        <x-status-badge :status="$pengajuan->status" />
    </div>

    @if ($pengajuan->catatan_revisi && $pengajuan->status === \App\StatusPengajuan::PerluRevisi)
        <div class="alert alert-warning">
            <strong>Catatan revisi dari operator:</strong><br>
            {{ $pengajuan->catatan_revisi }}
        </div>
    @endif
    @if ($pengajuan->alasan_tolak && $pengajuan->status === \App\StatusPengajuan::Ditolak)
        <div class="alert alert-danger">
            <strong>Alasan penolakan:</strong><br>
            {{ $pengajuan->alasan_tolak }}
        </div>
    @endif

    <div class="row g-3" style="min-width: 0;">
        <div class="col-lg-8 min-w-0">
            {{-- Data isian --}}
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
                <a href="{{ route('mahasiswa.pengajuan.index') }}" class="btn btn-outline-secondary">Kembali</a>

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