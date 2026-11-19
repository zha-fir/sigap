@extends('layouts.app')

@section('title', 'Dashboard Mahasiswa')

@section('content')
    <div class="d-flex justify-content-between align-items-start mb-3">
        <div>
            <h1 class="h3 fw-bold mb-1">Dashboard Mahasiswa</h1>
            <span class="text-muted">Selamat datang, {{ auth()->user()->name }}.</span>
        </div>
        @if (Route::has('mahasiswa.pengajuan.create'))
            <a href="{{ route('mahasiswa.pengajuan.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-lg me-1"></i>Ajukan Surat Baru
            </a>
        @endif
    </div>

    <div class="rounded-4 text-white p-4 mb-4" style="background: #172554;">
        <div class="small fw-semibold text-uppercase mb-1" style="color: #5eead4; letter-spacing: .05em;">
            Sistem Informasi Administrasi
        </div>
        <h2 class="h4 fw-bold mb-1">Halo, {{ auth()->user()->name }}!</h2>
        <p class="mb-0">
            Kamu punya <strong>{{ $totalPengajuan }}</strong> pengajuan surat.
            Pantau status dan riwayatnya di sini.
        </p>
    </div>

    <h2 class="h5 fw-bold mb-3">Ringkasan Aktivitas</h2>
    <div class="row g-3 mb-4">
        <div class="col-6 col-lg-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="text-muted small">Total Pengajuan</div>
                    <div class="fs-2 fw-bold">{{ $totalPengajuan }}</div>
                </div>
            </div>
        </div>
        @foreach ($ringkasan as $r)
            <div class="col-6 col-lg-3">
                <a href="{{ Route::has('mahasiswa.pengajuan.index') ? route('mahasiswa.pengajuan.index', ['status' => $r['status']->value]) : '#' }}"
                   class="text-decoration-none text-body">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body">
                            <div class="text-muted small">{{ $r['status']->label() }}</div>
                            <div class="fs-2 fw-bold">{{ $r['total'] }}</div>
                        </div>
                    </div>
                </a>
            </div>
        @endforeach
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white d-flex justify-content-between align-items-center">
            <span class="fw-semibold">Pengajuan Terbaru</span>
            <a href="{{ route('mahasiswa.pengajuan.index') }}" class="small">Lihat semua</a>
        </div>
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Kode Tiket</th>
                        <th>Jenis Surat</th>
                        <th>Status</th>
                        <th class="text-end">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($terbaru as $p)
                        <tr>
                            <td class="fw-semibold">{{ $p->kode_tiket }}</td>
                            <td>{{ $p->jenisSurat->nama ?? '-' }}</td>
                            <td><x-status-badge :status="$p->status" /></td>
                            <td class="text-end">
                                <a href="{{ route('mahasiswa.pengajuan.show', $p) }}"
                                   class="btn btn-sm btn-outline-primary">Detail</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center text-muted py-4">Belum ada pengajuan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection