@extends('layouts.app')

@section('title', 'Daftar Pengajuan')

@section('content')

<div class="container-fluid">

    <div class="mb-4">
        <h3 class="fw-bold mb-1">Daftar Pengajuan</h3>
        <p class="text-muted">Data pengajuan surat mahasiswa</p>
    </div>

    <div class="card border-0 shadow-sm">

        <div class="card-header bg-white py-3">
            <h6 class="fw-bold mb-0">Data Pengajuan Surat</h6>
        </div>

        <div class="card-body">

            <form method="GET"
                  action="{{ route('operator.pengajuan.index') }}"
                  class="row g-2 mb-4">

                <div class="col-md-6">
                    <input type="text"
                           name="cari"
                           class="form-control"
                           value="{{ request('cari') }}"
                           placeholder="Cari kode tiket atau NIM">
                </div>

                <div class="col-md-4">
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

                <div class="col-md-2">
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
                            <th>Kode Tiket</th>
                            <th>Mahasiswa</th>
                            <th>NIM</th>
                            <th>Jenis Surat</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>

                    <tbody>

                        @forelse($pengajuans as $item)

                            <tr>
                                <td>{{ $pengajuans->firstItem() + $loop->index }}</td>

                                <td>{{ $item->kode_tiket }}</td>

                                <td>
                                    {{ $item->mahasiswa?->user?->name ?? '-' }}
                                </td>

                                <td>{{ $item->mahasiswa?->nim ?? '-' }}</td>

                                <td>{{ $item->jenisSurat?->nama ?? '-' }}</td>

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
                                <td colspan="7" class="text-center text-muted py-4">
                                    Data pengajuan tidak ditemukan.
                                </td>
                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

            <div class="mt-3">
                {{ $pengajuans->links('pagination::bootstrap-5') }}
            </div>

        </div>

    </div>

</div>

@endsection
