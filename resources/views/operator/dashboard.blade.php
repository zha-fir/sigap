@extends('layouts.app')

@section('title', 'Dashboard Operator')

@section('content')

<div class="container-fluid">

    <div class="mb-4">
        <h3 class="fw-bold mb-1">Dashboard Operator</h3>
        <p class="text-muted">
            Sistem Informasi Layanan Surat Jurusan
        </p>
    </div>

    @php
        $statistik = [
            ['Pengajuan Baru', $baru, 'inbox', 'primary'],
            ['Mendekati SLA', null, 'clock', 'warning'],
            ['Lewat SLA', null, 'exclamation-triangle', 'danger'],
            ['Menunggu Perbaikan', $perbaikan, 'arrow-repeat', 'info'],
            ['Siap Diambil', $siap, 'check-circle', 'success'],
            ['Hampir Kedaluwarsa', null, 'calendar-x', 'secondary']
        ];
    @endphp

    <div class="row g-3 mb-4">

        @foreach($statistik as $item)
            <div class="col-md-6 col-xl-4">

                <div class="card border-0 shadow-sm h-100">

                    <div class="card-body">

                        <div class="d-flex justify-content-between align-items-center">

                            <div>
                                <p class="text-muted mb-2">
                                    {{ $item[0] }}
                                </p>

                                <h2 class="fw-bold mb-0">
                                    {{ $item[1] ?? '—' }}
                                </h2>
                            </div>

                            <div class="bg-light rounded-3 p-3">

                                <i class="bi bi-{{ $item[2] }} fs-3 text-{{ $item[3] }}"></i>

                            </div>

                        </div>

                    </div>

                </div>

            </div>
        @endforeach

    </div>

    <h5 class="fw-bold mb-3">Menu Layanan Operator</h5>

    @php
        $fitur = [
            [
                'kode' => '',
                'nama' => 'Dashboard Antrean',
                'ikon' => 'speedometer2',
                'deskripsi' => 'Ringkasan dan antrean pengajuan',
                'route' => 'operator.dashboard'
            ],
            [
                'kode' => '',
                'nama' => 'Daftar Pengajuan',
                'ikon' => 'inboxes',
                'deskripsi' => 'Daftar permohonan surat mahasiswa',
                'route' => 'operator.pengajuan.index'
            ],
            [
                'kode' => '',
                'nama' => 'Detail & Verifikasi',
                'ikon' => 'clipboard-check',
                'deskripsi' => 'Pemeriksaan data pengajuan',
                'route' => 'operator.verifikasi.index'
            ],
            [
                'kode' => '',
                'nama' => 'Editor & Unggah Surat',
                'ikon' => 'file-earmark-text',
                'deskripsi' => 'Pembuatan dokumen surat',
                'route' => null
            ],
            [
                'kode' => '',
                'nama' => 'Kirim ke Kajur',
                'ikon' => 'send',
                'deskripsi' => 'Pengiriman dokumen persetujuan',
                'route' => null
            ],
            [
                'kode' => '',
                'nama' => 'Serah Terima',
                'ikon' => 'check2-square',
                'deskripsi' => 'Pengelolaan pengambilan surat',
                'route' => null
            ],
            [
                'kode' => '',
                'nama' => 'Arsip Surat Keluar',
                'ikon' => 'archive',
                'deskripsi' => 'Penyimpanan dan pencarian surat',
                'route' => null
            ],
            [
                'kode' => '',
                'nama' => 'Laporan',
                'ikon' => 'bar-chart',
                'deskripsi' => 'Laporan administrasi surat',
                'route' => null
            ]
        ];
    @endphp

    <div class="row g-3 mb-4">

        @foreach($fitur as $item)

            <div class="col-md-6 col-xl-3">

                <div class="card border-0 shadow-sm h-100">

                    <div class="card-body d-flex flex-column">

                        <div class="d-flex justify-content-between align-items-center mb-3">

                            <i class="bi bi-{{ $item['ikon'] }} fs-2 text-primary"></i>

                            <span class="badge bg-light text-secondary">
                                {{ $item['kode'] }}
                            </span>

                        </div>

                        <h6 class="fw-bold">
                            {{ $item['nama'] }}
                        </h6>

                        <p class="text-muted small flex-grow-1">
                            {{ $item['deskripsi'] }}
                        </p>

                        @if($item['route'])

                            <a href="{{ route($item['route']) }}"
                               class="btn btn-primary btn-sm w-100">
                                Buka Menu
                            </a>

                        @else

                            <button type="button" class="btn btn-primary btn-sm w-100">
                                Buka Menu
                            </button>

                        @endif

                    </div>

                </div>

            </div>

        @endforeach

    </div>

    <div class="card border-0 shadow-sm">

        <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">

            <h6 class="fw-bold mb-0">Pengajuan Terbaru</h6>

            <a href="{{ route('operator.pengajuan.index') }}"
               class="btn btn-outline-primary btn-sm">
                Lihat Semua
            </a>

        </div>

        <div class="card-body">

            <div class="table-responsive">

                <table class="table table-hover align-middle">

                    <thead class="table-light">

                        <tr>
                            <th>No</th>
                            <th>Kode Tiket</th>
                            <th>Mahasiswa</th>
                            <th>Jenis Surat</th>
                            <th>Status</th>
                        </tr>

                    </thead>

                    <tbody>

                        @forelse($terbaru as $item)

                            <tr>
                                <td>{{ $loop->iteration }}</td>

                                <td class="text-nowrap">
                                    {{ $item->kode_tiket }}
                                </td>

                                <td>
                                    {{ $item->mahasiswa?->user?->name ?? '-' }}
                                </td>

                                <td>
                                    {{ $item->jenisSurat?->nama ?? '-' }}
                                </td>

                                <td>
                                    <span class="badge bg-{{ $item->status->badge() }}">
                                        {{ $item->status->label() }}
                                    </span>
                                </td>
                            </tr>

                        @empty

                            <tr>
                                <td colspan="5" class="text-center text-muted py-4">
                                    Belum ada pengajuan.
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
