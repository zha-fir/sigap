@extends('layouts.app')
@section('title', 'Dashboard Operator')
@section('content')

<div class="container-fluid">

    <div class="mb-3">
        <h4 class="fw-bold mb-1">Dashboard Operator</h4>
        <p class="text-muted small mb-0">
            Ringkasan layanan pengajuan surat jurusan
        </p>
    </div>

    <div class="row g-3 mb-4">

        @foreach($statistik as $item)
            <div class="col-6 col-xl-3">

                <div class="card border-0 shadow-sm h-100">

                    <div class="card-body p-3">

                        <div class="d-flex justify-content-between align-items-start gap-2">

                            <span class="text-muted small">
                                {{ $item['nama'] }}
                            </span>

                            <i class="bi bi-{{ $item['ikon'] }} fs-6 text-secondary"></i>

                        </div>

                        <h5 class="fw-bold mt-3 mb-0">
                            {{ $item['jumlah'] }}
                        </h5>

                    </div>

                </div>

            </div>
        @endforeach

    </div>

    <div class="card border-0 shadow-sm">

        <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">

            <h6 class="fw-bold mb-0">
                Antrean Pengajuan
            </h6>

            <a href="{{ route('operator.pengajuan.index') }}"
               class="btn btn-outline-primary btn-sm">
                Lihat Semua
            </a>

        </div>

        <div class="card-body p-3">

            <div class="table-responsive">

                <table class="table table-hover table-sm align-middle mb-0">

                    <thead class="table-light">

                        <tr>
                            <th>No</th>
                            <th>Kode Tiket</th>
                            <th>Nama Mahasiswa</th>
                            <th>Lokasi</th>
                            <th>Tanggal</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>

                    </thead>

                    <tbody>

                        @forelse($prioritas as $item)

                            <tr>

                                <td>
                                    {{ $loop->iteration }}
                                </td>

                                <td class="text-nowrap">
                                    {{ $item->kode_tiket }}
                                </td>

                                <td>
                                    {{ $item->mahasiswa?->user?->name ?? '-' }}
                                </td>

                                <td>
                                    {{ $item->data->firstWhere('key', 'nama_lokasi')?->value ?? '-' }}
                                </td>

                                <td class="text-nowrap">
                                    {{ $item->diajukan_at?->format('d-m-Y') ?? '-' }}
                                </td>

                                <td>
                                    <x-status-badge :status="$item->status" />
                                </td>

                                <td>
                                    <a href="{{ route('operator.pengajuan.show', $item->id) }}"
                                       class="btn btn-outline-primary btn-sm">
                                        Detail
                                    </a>
                                </td>

                            </tr>

                        @empty

                            <tr>
                                <td colspan="7" class="text-center text-muted py-4">
                                    Tidak ada pengajuan dalam antrean.
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
