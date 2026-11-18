@extends('layouts.app')

@section('title', 'Detail Pengajuan')

@section('content')

<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h3 class="fw-bold mb-1">Detail Pengajuan</h3>
            <p class="text-muted mb-0">
                {{ $pengajuan->kode_tiket }}
            </p>
        </div>

        <a href="{{ route('operator.verifikasi.index') }}"
           class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-1"></i>
            Kembali
        </a>

    </div>

    <div class="row g-3">

        <div class="col-md-6">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-header bg-white py-3">
                    <h6 class="fw-bold mb-0">Data Mahasiswa</h6>
                </div>

                <div class="card-body">

                    <p>
                        <strong>Nama</strong>
                        <span class="d-block text-muted">
                            {{ $pengajuan->mahasiswa?->user?->name ?? '-' }}
                        </span>
                    </p>

                    <p>
                        <strong>NIM</strong>
                        <span class="d-block text-muted">
                            {{ $pengajuan->mahasiswa?->nim ?? '-' }}
                        </span>
                    </p>

                    <p>
                        <strong>Program Studi</strong>
                        <span class="d-block text-muted">
                            {{ $pengajuan->mahasiswa?->programStudi?->nama ?? '-' }}
                        </span>
                    </p>

                    <p>
                        <strong>Angkatan</strong>
                        <span class="d-block text-muted">
                            {{ $pengajuan->mahasiswa?->angkatan ?? '-' }}
                        </span>
                    </p>

                    <p class="mb-0">
                        <strong>Nomor Telepon</strong>
                        <span class="d-block text-muted">
                            {{ $pengajuan->mahasiswa?->no_hp ?? '-' }}
                        </span>
                    </p>

                </div>
            </div>

        </div>

        <div class="col-md-6">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-header bg-white py-3">
                    <h6 class="fw-bold mb-0">Informasi Surat</h6>
                </div>

                <div class="card-body">

                    <p>
                        <strong>Kode Pengajuan</strong>
                        <span class="d-block text-muted">
                            {{ $pengajuan->kode_tiket }}
                        </span>
                    </p>

                    <p>
                        <strong>Jenis Surat</strong>
                        <span class="d-block text-muted">
                            {{ $pengajuan->jenisSurat?->nama ?? '-' }}
                        </span>
                    </p>

                    <p>
                        <strong>Tanggal Pengajuan</strong>
                        <span class="d-block text-muted">
                            {{ $pengajuan->diajukan_at?->format('d-m-Y') ?? '-' }}
                        </span>
                    </p>

                    <p class="mb-0">
                        <strong>Status</strong>
                        <span class="d-block mt-2">
                            <span class="badge bg-{{ $pengajuan->status->badge() }}">
                                {{ $pengajuan->status->label() }}
                            </span>
                        </span>
                    </p>

                </div>
            </div>

        </div>

        <div class="col-12">

            <div class="card border-0 shadow-sm">

                <div class="card-header bg-white py-3">
                    <h6 class="fw-bold mb-0">Data Formulir</h6>
                </div>

                <div class="card-body">

                    <div class="row g-3">

                        @forelse($pengajuan->data as $data)

                            <div class="col-md-6">
                                <label class="fw-semibold">
                                    {{ ucwords(str_replace('_', ' ', $data->key)) }}
                                </label>

                                <div class="form-control bg-light">
                                    {{ $data->value ?? '-' }}
                                </div>
                            </div>

                        @empty

                            <div class="col-12 text-muted">
                                Belum ada data formulir.
                            </div>

                        @endforelse

                    </div>

                </div>
            </div>

        </div>

        <div class="col-12">

            <div class="card border-0 shadow-sm">

                <div class="card-header bg-white py-3">
                    <h6 class="fw-bold mb-0">Lampiran Pengajuan</h6>
                </div>

                <div class="card-body">

                    <ul class="list-group list-group-flush">

                        @forelse($pengajuan->lampirans as $lampiran)

                            <li class="list-group-item px-0">
                                <i class="bi bi-paperclip me-2 text-primary"></i>
                                {{ $lampiran->nama_file }}
                            </li>

                        @empty

                            <li class="list-group-item px-0 text-muted">
                                Belum ada lampiran.
                            </li>

                        @endforelse

                    </ul>

                </div>
            </div>

        </div>

        <div class="col-12">

            <div class="card border-0 shadow-sm">

                <div class="card-header bg-white py-3">
                    <h6 class="fw-bold mb-0">Verifikasi Pengajuan</h6>
                </div>

                <div class="card-body">

                    <div class="d-flex gap-2 flex-wrap">

                        <button class="btn btn-success" disabled>
                            <i class="bi bi-check-circle me-1"></i>
                            Terima
                        </button>

                        <button class="btn btn-warning" disabled>
                            <i class="bi bi-arrow-repeat me-1"></i>
                            Minta Perbaikan
                        </button>

                        <button class="btn btn-danger" disabled>
                            <i class="bi bi-x-circle me-1"></i>
                            Tolak
                        </button>

                    </div>

                </div>
            </div>

        </div>

    </div>

</div>

@endsection
