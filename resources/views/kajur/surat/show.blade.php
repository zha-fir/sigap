
@extends('layouts.app')

@section('title', 'Pratinjau Surat')

@section('content')
<div class="container-fluid py-3">

    {{-- Header --}}
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold mb-1" style="color: #1e293b;">
                Pratinjau Surat
            </h2>
            <p class="text-muted mb-0">
                Periksa isi dan kelengkapan dokumen pengajuan.
            </p>
        </div>

        <a href="{{ route('kajur.surat.index') }}"
           class="btn btn-outline-secondary mt-3 mt-md-0">
            <i class="bi bi-arrow-left me-1"></i>
            Kembali
        </a>
    </div>

    {{-- Informasi Pengajuan --}}
    <div class="card border-0 shadow-sm mb-4"
         style="border-radius: 14px;">
        <div class="card-body p-4">
            <h5 class="fw-bold mb-3">Informasi Pengajuan</h5>

            <div class="row g-3">
                <div class="col-md-6">
                    <small class="text-muted">Kode Tiket</small>
                    <div class="fw-semibold">{{ $pengajuan->kode_tiket }}</div>
                </div>

                <div class="col-md-6">
                    <small class="text-muted">Nama Pemohon</small>
                    <div class="fw-semibold">
                        {{ $pengajuan->mahasiswa?->user?->name ?? '-' }}
                    </div>
                </div>

                <div class="col-md-6">
                    <small class="text-muted">NIM</small>
                    <div class="fw-semibold">
                        {{ $pengajuan->mahasiswa?->nim ?? '-' }}
                    </div>
                </div>

                <div class="col-md-6">
                    <small class="text-muted">Program Studi</small>
                    <div class="fw-semibold">
                        {{ $pengajuan->mahasiswa?->programStudi?->nama ?? '-' }}
                    </div>
                </div>

                <div class="col-md-6">
                    <small class="text-muted">Jenis Surat</small>
                    <div class="fw-semibold">
                        {{ $pengajuan->jenisSurat?->nama ?? '-' }}
                    </div>
                </div>

                <div class="col-md-6">
                    <small class="text-muted">Tanggal Pengajuan</small>
                    <div class="fw-semibold">
                        {{ $pengajuan->diajukan_at?->format('d F Y') ?? '-' }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Dokumen Surat --}}
    <div class="card border-0 shadow-sm mb-4"
         style="border-radius: 14px;">
        <div class="card-body p-4">

            <div class="d-flex justify-content-between align-items-center mb-3">
                <div>
                    <h5 class="fw-bold mb-1">Isi Dokumen Surat</h5>
                    <p class="text-muted small mb-0">
                        Pratinjau isi surat berdasarkan template dan data pengajuan.
                    </p>
                </div>

                <i class="bi bi-file-earmark-text fs-3"
                   style="color: #0d9488;"></i>
            </div>

            @if ($urlPdf)
                <div class="mb-3">
                    <a href="{{ $urlPdf }}"
                       target="_blank"
                       rel="noopener"
                       class="btn btn-sm btn-outline-primary">
                        <i class="bi bi-file-pdf me-1"></i>
                        Buka PDF
                    </a>
                </div>

                <iframe
                    src="{{ $urlPdf }}"
                    title="Pratinjau PDF Surat"
                    width="100%"
                    height="650"
                    style="border: 1px solid #e2e8f0; border-radius: 10px;">
                </iframe>
            @else
                <div class="p-4 p-md-5 border rounded-3"
                     style="background: #fff; min-height: 400px;">

                    <div class="text-center mb-4">
                        <h5 class="fw-bold">DOKUMEN SURAT</h5>
                        <small class="text-muted">
                            {{ $pengajuan->jenisSurat?->nama ?? '' }}
                        </small>
                    </div>

                    <div style="line-height: 1.8; color: #334155;">
                        {!! $isiSurat !!}
                    </div>
                </div>

                <div class="alert alert-info mt-3 mb-0 small">
                    <i class="bi bi-info-circle me-1"></i>
                    PDF belum tersedia. Yang ditampilkan adalah pratinjau
                    isi surat dari template sistem.
                </div>
            @endif

        </div>
    </div>

    {{-- Lampiran --}}
    <div class="card border-0 shadow-sm"
         style="border-radius: 14px;">
        <div class="card-body p-4">
            <h5 class="fw-bold mb-3">Lampiran Pengajuan</h5>

            @forelse ($pengajuan->lampirans as $lampiran)
                <div class="d-flex flex-wrap justify-content-between align-items-center border rounded-3 p-3 mb-2">
                    <div class="d-flex align-items-center">
                        <i class="bi bi-file-earmark me-3 fs-4"
                           style="color: #0d9488;"></i>

                        <div>
                            <div class="fw-semibold">
                                {{ $lampiran->nama_file }}
                            </div>
                            <small class="text-muted">
                                {{ number_format($lampiran->ukuran / 1024, 1) }} KB
                            </small>
                        </div>
                    </div>

                    <a href="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($lampiran->path) }}"
                       target="_blank"
                       rel="noopener"
                       class="btn btn-sm btn-outline-primary mt-2 mt-md-0">
                        <i class="bi bi-download me-1"></i>
                        Lihat Lampiran
                    </a>
                </div>
            @empty
                <div class="text-center py-4">
                    <i class="bi bi-folder2-open fs-1 text-muted"></i>
                    <p class="text-muted mb-0 mt-2">
                        Tidak ada lampiran pada pengajuan ini.
                    </p>
                </div>
            @endforelse

        </div>
    </div>

</div>
@endsection
