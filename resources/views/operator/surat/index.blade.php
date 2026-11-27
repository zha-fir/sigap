@extends('layouts.app')

@section('title', 'Daftar Surat')

@section('content')

<div class="container-fluid">

    <div class="mb-3">
        <h4 class="fw-bold mb-1">Daftar Surat</h4>
        <p class="text-muted small mb-0">
            Surat yang sudah ditandatangani oleh Ketua Jurusan
        </p>
    </div>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger">
            {{ $errors->first() }}
        </div>
    @endif

    <div class="card border-0 shadow-sm">

        <div class="card-body p-3">

            <div class="table-responsive">

                <table class="table table-hover align-middle mb-0">

                    <thead class="table-light">
                        <tr>
                            <th>No</th>
                            <th>Nomor Surat</th>
                            <th>Nama Mahasiswa</th>
                            <th>Jenis Surat</th>
                            <th>Tanggal TTD</th>
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

                                <td>
                                    {{ $item->surat?->nomor_surat ?? '-' }}
                                </td>

                                <td>
                                    {{ $item->mahasiswa?->user?->name ?? '-' }}
                                </td>

                                <td>
                                    {{ $item->jenisSurat?->nama ?? '-' }}
                                </td>

                                <td>
                                    {{ $item->surat?->ditandatangani_at?->format('d-m-Y') ?? '-' }}
                                </td>

                                <td>
                                    <x-status-badge :status="$item->status" />
                                </td>

                                <td>
                                    <div class="d-flex flex-wrap gap-2">

                                        @if($item->surat?->status_ttd === 'ditandatangani')

                                            <a href="{{ route('operator.surat.cetak', $item->id) }}"
                                               class="btn btn-outline-primary btn-sm">
                                                Cetak
                                            </a>

                                        @else

                                            <span class="text-muted small">
                                                Cetak menunggu sinkronisasi TTD
                                            </span>

                                        @endif

                                        @if($item->status === \App\StatusPengajuan::Ditandatangani)

                                            <form method="POST"
                                                  action="{{ route('operator.surat.siap-diambil', $item->id) }}">

                                                @csrf

                                                <button type="submit"
                                                        class="btn btn-success btn-sm">
                                                    Siap Diambil
                                                </button>

                                            </form>

                                        @elseif($item->status === \App\StatusPengajuan::SiapDiambil)

                                            <form method="POST"
                                                  action="{{ route('operator.surat.selesai', $item->id) }}"
                                                  onsubmit="return confirm('Surat sudah diterima oleh mahasiswa?')">

                                                @csrf

                                                <button type="submit"
                                                        class="btn btn-primary btn-sm">
                                                    Selesai
                                                </button>

                                            </form>

                                        @endif

                                    </div>
                                </td>
                            </tr>

                        @empty

                            <tr>
                                <td colspan="7"
                                    class="text-center text-muted py-4">
                                    Belum ada surat yang ditandatangani oleh Kajur.
                                </td>
                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>

        @if($pengajuans->hasPages())

            <div class="card-footer bg-white">
                {{ $pengajuans->links('pagination::bootstrap-5') }}
            </div>

        @endif

    </div>

</div>

@endsection
