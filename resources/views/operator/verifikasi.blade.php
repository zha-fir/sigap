@extends('layouts.app')

@section('title', 'Detail dan Verifikasi')

@section('content')

<div class="container-fluid">

    <div class="mb-4">
        <h3 class="fw-bold mb-1">Detail & Verifikasi</h3>
        <p class="text-muted mb-0">
            Pemeriksaan pengajuan surat mahasiswa
        </p>
    </div>

    <div class="card border-0 shadow-sm">

        <div class="card-header bg-white py-3 px-4">

            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h5 class="fw-bold mb-1">Daftar Verifikasi Pengajuan</h5>
                    <p class="text-muted small mb-0">
                        Pengajuan yang perlu diperiksa dan diproses
                    </p>
                </div>

                <span class="badge bg-primary">
                    {{ $pengajuans->total() }} Pengajuan
                </span>
            </div>

        </div>

        <div class="card-body p-4">

            <div class="table-responsive">

                <table class="table table-hover align-middle">

                    <thead class="table-light">
                        <tr>
                            <th>No</th>
                            <th>Kode Pengajuan</th>
                            <th>Nama Mahasiswa</th>
                            <th>NIM</th>
                            <th>Jenis Surat</th>
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

                                <td>
                                    <span class="badge bg-{{ $item->status->badge() }}">
                                        {{ $item->status->label() }}
                                    </span>
                                </td>

                                <td>
                                    <a href="{{ route('operator.pengajuan.show', $item->id) }}"
                                       class="btn btn-outline-primary btn-sm">
                                        <i class="bi bi-eye me-1"></i>
                                        Periksa
                                    </a>
                                </td>
                            </tr>

                        @empty

                            <tr>
                                <td colspan="7"
                                    class="text-center text-muted py-4">
                                    Tidak ada pengajuan untuk diperiksa.
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
