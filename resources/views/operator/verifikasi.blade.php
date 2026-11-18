@extends('layouts.app')

@section('title', 'Detail dan Verifikasi')

@section('content')

<div class="container-fluid">

    <div class="mb-4">
        <h3 class="fw-bold mb-1">Detail & Verifikasi</h3>
        <p class="text-muted">
            Pemeriksaan pengajuan surat mahasiswa
        </p>
    </div>

    <div class="card border-0 shadow-sm">

        <div class="card-header bg-white py-3">
            <h6 class="fw-bold mb-0">Daftar Verifikasi Pengajuan</h6>
        </div>

        <div class="card-body">

            <div class="table-responsive">

                <table class="table table-hover align-middle">

                    <thead class="table-light">
                        <tr>
                            <th>No</th>
                            <th>Kode Tiket</th>
                            <th>Nama Mahasiswa</th>
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

                                <td>{{ $item->jenisSurat?->nama ?? '-' }}</td>

                                <td>
                                    <span class="badge bg-{{ $item->status->badge() }}">
                                        {{ $item->status->label() }}
                                    </span>
                                </td>

                                <td>
                                    <a href="{{ route('operator.pengajuan.show', $item->id) }}"
                                       class="btn btn-primary btn-sm">
                                        <i class="bi bi-eye me-1"></i>
                                        Periksa
                                    </a>
                                </td>
                            </tr>

                        @empty

                            <tr>
                                <td colspan="6" class="text-center py-4 text-muted">
                                    Tidak ada pengajuan untuk diperiksa.
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
