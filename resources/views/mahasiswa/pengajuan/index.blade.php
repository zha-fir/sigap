@extends('layouts.app')

@section('title', 'Daftar Pengajuan')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="h4 mb-0">Daftar Pengajuan</h1>
        @if (Route::has('mahasiswa.pengajuan.create'))
            <a href="{{ route('mahasiswa.pengajuan.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-lg me-1"></i>Ajukan Surat Baru
            </a>
        @endif
    </div>

    {{-- Filter status --}}
    <form method="GET" action="{{ route('mahasiswa.pengajuan.index') }}" class="row g-2 mb-3">
        <div class="col-auto">
            <select name="status" class="form-select" onchange="this.form.submit()">
                <option value="">Semua status</option>
                @foreach ($statuses as $s)
                    <option value="{{ $s->value }}" @selected($statusAktif === $s)>{{ $s->label() }}</option>
                @endforeach
            </select>
        </div>
        @if ($statusAktif)
            <div class="col-auto">
                <a href="{{ route('mahasiswa.pengajuan.index') }}" class="btn btn-outline-secondary">Reset</a>
            </div>
        @endif
    </form>

    <div class="card">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Kode Tiket</th>
                        <th>Jenis Surat</th>
                        <th>Nama Lokasi</th>
                        <th>Tanggal Diajukan</th>
                        <th>Status</th>
                        <th class="text-end">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($pengajuans as $p)
                        <tr>
                            <td class="fw-semibold">{{ $p->kode_tiket }}</td>
                            <td>{{ $p->jenisSurat->nama ?? '-' }}</td>
                            <td>{{ $p->dataArray()['nama_lokasi'] ?? '-' }}</td>
                            <td>{{ $p->diajukan_at?->translatedFormat('d F Y') ?? '-' }}</td>
                            <td><x-status-badge :status="$p->status" /></td>
                            <td class="text-end">
                                <a href="{{ route('mahasiswa.pengajuan.show', $p) }}"
                                   class="btn btn-sm btn-outline-primary">Detail</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted py-4">Belum ada pengajuan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-3">
        {{ $pengajuans->links('pagination::bootstrap-5') }}
    </div>
@endsection