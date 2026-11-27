@extends('layouts.app')

@section('title', 'Daftar Pengajuan')

@section('content')

<div class="container-fluid">

    <div class="mb-4">
        <h3 class="fw-bold mb-1">Daftar Pengajuan</h3>
        <p class="text-muted mb-0">
            Data pengajuan surat mahasiswa
        </p>
    </div>

    <div class="card border-0 shadow-sm">

        <div class="card-header bg-white py-3 px-4">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h5 class="fw-bold mb-1">Data Pengajuan Surat</h5>
                    <p class="text-muted small mb-0">
                        Daftar pengajuan yang telah dikirim mahasiswa
                    </p>
                </div>

                <span class="badge bg-primary">
                    {{ $pengajuans->total() }} Pengajuan
                </span>
            </div>
        </div>

        <div class="card-body p-4">

            <form method="GET"
                  action="{{ route('operator.pengajuan.index') }}"
                  class="row g-3 mb-4">

                <div class="col-md-6">
                    <label class="form-label">Pencarian</label>
                    <input type="text"
                           name="cari"
                           class="form-control"
                           value="{{ request('cari') }}"
                           placeholder="Cari kode pengajuan atau NIM">
                </div>

                <div class="col-md-4">
                    <label class="form-label">Status Pengajuan</label>
                    <select name="status" class="form-select">
                        <option value="">Semua Status</option>

                        @foreach($pilihanStatus as $status)
                            <option value="{{ $status->value }}"
                                {{ request('status') === $status->value ? 'selected' : '' }}>
                                {{ $status->label() }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-2 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="bi bi-search me-1"></i>
                        Cari
                    </button>
                </div>

            </form>

            <div class="table-responsive">

                <table class="table table-hover align-middle">

                    <thead class="table-light">
                        <tr>
                            <th>No</th>
                            <th>Kode Pengajuan</th>
                            <th>Nama Mahasiswa</th>
                            <th>NIM</th>
                            <th>Jenis Surat</th>
                            <th>Tanggal Pengajuan</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>

                    <tbody>

                        @forelse($pengajuans as $item)
                            <tr>
                                <td>
                                    {{ $pengajuans->firstItem() + $loop->index }}
                                </td>

                                <td class="text-nowrap">
                                    {{ $item->kode_tiket }}
                                </td>

                                <td>
                                    {{ $item->mahasiswa?->user?->name ?? '-' }}
                                </td>

                                <td>
                                    {{ $item->mahasiswa?->nim ?? '-' }}
                                </td>

                                <td>
                                    {{ $item->jenisSurat?->nama ?? '-' }}
                                </td>

                                <td class="text-nowrap">
                                    {{ $item->diajukan_at?->format('d-m-Y') ?? '-' }}
                                </td>

                                <td>
                                    <span class="badge bg-{{ $item->status->badge() }}">
                                        {{ $item->status->label() }}
                                    </span>
                                </td>

                                <td>
                                    <a href="{{ route('operator.pengajuan.show', $item->id) }}"
                                       class="btn btn-outline-primary btn-sm">
                                        <i class="bi bi-eye me-1"></i>
                                        Detail
                                    </a>
                                </td>
                            </tr>

                        @empty
                            <tr>
                                <td colspan="8"
                                    class="text-center text-muted py-4">
                                    Data pengajuan tidak ditemukan.
                                </td>
                            </tr>
                        @endforelse

                    </tbody>

                </table>

            </div>

            <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mt-3">

                <small class="text-muted">
                    Menampilkan {{ $pengajuans->count() }}
                    dari {{ $pengajuans->total() }} pengajuan
                </small>

                {{ $pengajuans->links('pagination::bootstrap-5') }}

            </div>

        </div>

    </div>

</div>

@endsection
