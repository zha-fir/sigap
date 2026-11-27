@extends('layouts.app')

@section('title', 'Edit Pengajuan')

@section('content')
    <div class="d-flex justify-content-between align-items-start mb-3">
        <div>
            <h1 class="h4 mb-1">Edit Pengajuan</h1>
            <div class="text-muted">
                {{ $pengajuan->kode_tiket }} &middot; {{ $template->jenisSurat->nama ?? $template->nama }}
            </div>
        </div>
        <a href="{{ route('mahasiswa.pengajuan.show', $pengajuan) }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-1"></i>Kembali
        </a>
    </div>

    @if ($pengajuan->catatan_revisi && $pengajuan->status === \App\StatusPengajuan::PerluRevisi)
        <div class="alert alert-warning">
            <strong>Catatan revisi dari operator:</strong><br>
            {{ $pengajuan->catatan_revisi }}
        </div>
    @endif

    @include('mahasiswa.partials.form-pengajuan', [
        'action' => route('mahasiswa.pengajuan.update', $pengajuan),
        'method' => 'PUT',
    ])
@endsection