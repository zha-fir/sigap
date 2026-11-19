@extends('layouts.app')
@section('title', 'Dashboard Operator')
@section('content')

<div class="container-fluid">

    <div class="mb-4">
        <h3 class="fw-bold mb-1">Dashboard Antrean</h3>
        <p class="text-muted mb-0">
            Ringkasan pengajuan dan pelayanan surat jurusan
        </p>
    </div>

    <div class="row g-3 mb-4">

        @foreach($statistik as $item)

            <div class="col-md-6 col-xl-4">

                <div class="card border-0 shadow-sm h-100">

                    <div class="card-body p-4">

                        <div class="d-flex align-items-center justify-content-between">

                            <div>
                                <p class="text-muted mb-2">
                                    {{ $item['nama'] }}
                                </p>

                                <h2 class="fw-bold mb-0">
                                    {{ $item['jumlah'] ?? '—' }}
                                </h2>
                            </div>

                            <div class="bg-light rounded-3 p-3">

                                <i class="bi bi-{{ $item['ikon'] }}
                                    fs-2 text-{{ $item['warna'] }}">
                                </i>

                            </div>

                        </div>

                    </div>

                </div>

            </div>

        @endforeach

    </div>

    <div class="card border-0 shadow-sm">

        <div class="card-header bg-white py-3">

            <div class="d-flex justify-content-between align-items-center">

                <div>
                    <h5 class="fw-bold mb-1">
                        Daftar Prioritas Pengajuan
                    </h5>

                    <p class="text-muted small mb-0">
                        Pengajuan surat yang masih dalam proses
                    </p>
                </div>

                <a href="{{ route('operator.pengajuan.index') }}"
                   class="btn btn-outline-primary btn-sm">

                    <i class="bi bi-list-ul me-1"></i>
                    Lihat Semua

                </a>

            </div>

        </div>

        <div class="card-body">

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

                        @forelse($prioritas as $item)

                            <tr>

                                <td>{{ $loop->iteration }}</td>

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

                                    Belum ada pengajuan yang perlu diproses.

                                </td>
                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>

    </div>

</div>

@endsection
