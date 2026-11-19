
@extends('layouts.app')

@section('title', 'Surat Menunggu Tanda Tangan')

@section('content')
<div class="container-fluid py-3">

    <div class="mb-4">
        <h2 class="fw-bold mb-1" style="color: #1e293b;">
            Surat Menunggu Tanda Tangan
        </h2>
        <p class="text-muted mb-0">
            Daftar pengajuan surat yang perlu diperiksa oleh Ketua Jurusan.
        </p>
    </div>

    {{-- Ringkasan --}}
    <div class="card border-0 shadow-sm mb-4"
         style="border-radius: 14px;">
        <div class="card-body p-4">
            <div class="d-flex align-items-center">
                <div class="rounded-3 p-3 me-3"
                     style="background: #fef3c7; color: #d97706;">
                    <i class="bi bi-clock-history fs-3"></i>
                </div>
                <div>
                    <p class="text-muted small mb-1">Antrean Surat</p>
                    <h3 class="fw-bold mb-0">{{ $pengajuans->total() }}</h3>
                    <small class="text-muted">Surat menunggu tanda tangan</small>
                </div>
            </div>
        </div>
    </div>

    {{-- Daftar Surat --}}
    <div class="card border-0 shadow-sm"
         style="border-radius: 14px;">

        <div class="card-body p-4">

            <div class="d-flex flex-wrap justify-content-between align-items-center mb-4">
                <div>
                    <h5 class="fw-bold mb-1">Daftar Pengajuan</h5>
                    <p class="text-muted small mb-0">
                        Periksa detail surat sebelum proses pengesahan.
                    </p>
                </div>

                <form method="GET"
                      action="{{ route('kajur.surat.index') }}"
                      class="d-flex gap-2 mt-3 mt-md-0">

                    <input
                        type="text"
                        name="cari"
                        value="{{ $cari }}"
                        class="form-control"
                        placeholder="Cari kode, nama, atau NIM..."
                    >

                    <button class="btn text-white"
                            style="background: #0d9488;">
                        <i class="bi bi-search"></i>
                    </button>

                    @if ($cari)
                        <a href="{{ route('kajur.surat.index') }}"
                           class="btn btn-outline-secondary">
                            Reset
                        </a>
                    @endif
                </form>
            </div>

            <div class="table-responsive">
                <table class="table align-middle">
                    <thead style="background: #f8fafc;">
                        <tr>
                            <th class="text-muted small py-3">No.</th>
                            <th class="text-muted small py-3">Kode Tiket</th>
                            <th class="text-muted small py-3">Pemohon</th>
                            <th class="text-muted small py-3">Jenis Surat</th>
                            <th class="text-muted small py-3">Tanggal Pengajuan</th>
                            <th class="text-muted small py-3">Status</th>
                            <th class="text-muted small py-3 text-center">Aksi</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse ($pengajuans as $index => $pengajuan)
                            <tr>
                                <td>
                                    {{ $pengajuans->firstItem() + $index }}
                                </td>

                                <td>
                                    <span class="fw-semibold">
                                        {{ $pengajuan->kode_tiket }}
                                    </span>
                                </td>

                                <td>
                                    <div class="fw-semibold">
                                        {{ $pengajuan->mahasiswa?->user?->name ?? 'Nama tidak tersedia' }}
                                    </div>
                                    <small class="text-muted">
                                        NIM: {{ $pengajuan->mahasiswa?->nim ?? '-' }}
                                    </small>
                                </td>

                                <td>
                                    {{ $pengajuan->jenisSurat?->nama ?? '-' }}
                                </td>

                                <td>
                                    {{ $pengajuan->diajukan_at?->format('d/m/Y') ?? '-' }}
                                </td>

                                <td>
                                    <span class="badge rounded-pill px-3 py-2"
                                          style="background: #fef3c7; color: #b45309;">
                                        Menunggu TTD
                                    </span>
                                </td>

                                <td class="text-center">
                                    <a href="{{ route('kajur.surat.show', $pengajuan) }}"
                                       class="btn btn-sm text-white"
                                       style="background: #0d9488;">
                                        <i class="bi bi-eye me-1"></i>
                                        Pratinjau
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-5">
                                    <i class="bi bi-inbox"
                                       style="font-size: 45px; color: #94a3b8;"></i>

                                    <h6 class="fw-bold mt-3">
                                        Belum Ada Surat
                                    </h6>

                                    <p class="text-muted small mb-0">
                                        Tidak ada pengajuan yang menunggu tanda tangan saat ini.
                                    </p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-3">
                {{ $pengajuans->links() }}
            </div>

        </div>
    </div>

</div>
@endsection
