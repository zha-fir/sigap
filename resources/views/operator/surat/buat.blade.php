@extends('layouts.app')

@section('title', 'Buat Surat')

@section('content')
<div class="container-fluid">
    <div class="mb-3">
        <h4 class="fw-bold mb-1">Buat Surat</h4>
        <p class="text-muted small mb-0">
            Pilih pengajuan yang sudah diterima Operator.
        </p>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white py-3">
            <h6 class="fw-semibold mb-0">
                Pengajuan Sedang Diproses
            </h6>
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Kode Tiket</th>
                            <th>Nama Pengaju</th>
                            <th>Jenis Surat</th>
                            <th>Template</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($pengajuans as $item)
                            <tr>
                                <td>{{ $item->kode_tiket }}</td>

                                <td>
                                    {{ $item->mahasiswa?->user?->name ?? '-' }}
                                </td>

                                <td>
                                    {{ $item->jenisSurat?->nama ?? '-' }}
                                </td>

                                <td>
                                    @if($item->templateSurat?->is_active)
                                        <span class="badge bg-success">
                                            Tersedia
                                        </span>
                                    @else
                                        <span class="badge bg-secondary">
                                            Menunggu Admin
                                        </span>
                                    @endif
                                </td>

                                <td>
                                    <a href="{{ route('operator.pengajuan.surat.edit', $item->id) }}"
                                       class="btn btn-outline-primary btn-sm">
                                        Buka
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted py-4">
                                    Belum ada pengajuan berstatus Diproses.
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
