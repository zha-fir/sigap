@extends('layouts.app')

@section('title', 'Jenis Surat')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="h4 mb-0">Jenis Surat</h1>
        <a href="{{ route('admin.jenis-surat.create') }}" class="btn btn-primary">Tambah Jenis Surat</a>
    </div>

    <form method="GET" class="row g-2 mb-3">
        <div class="col-md-5">
            <input type="text" name="cari" value="{{ request('cari') }}"
                   class="form-control" placeholder="Cari nama atau kode">
        </div>
        <div class="col-md-4">
            <button class="btn btn-outline-secondary">Cari</button>
            <a href="{{ route('admin.jenis-surat.index') }}" class="btn btn-link">Reset</a>
        </div>
    </form>

    <div class="card">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>No</th>
                        <th>Kode</th>
                        <th>Nama</th>
                        <th>Template</th>
                        <th>Pengajuan</th>
                        <th>Status</th>
                        <th class="text-end">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($jenisSurats as $jenis)
                        <tr>
                            <td>{{ $jenisSurats->firstItem() + $loop->index }}</td>
                            <td>{{ $jenis->kode }}</td>
                            <td>
                                {{ $jenis->nama }}
                                @if ($jenis->deskripsi)
                                    <div class="small text-muted">{{ $jenis->deskripsi }}</div>
                                @endif
                            </td>
                            <td>{{ $jenis->templates_count }}</td>
                            <td>{{ $jenis->pengajuans_count }}</td>
                            <td>
                                @if ($jenis->is_active)
                                    <span class="badge text-bg-success">Aktif</span>
                                @else
                                    <span class="badge text-bg-secondary">Nonaktif</span>
                                @endif
                            </td>
                            <td class="text-end">
                                <a href="{{ route('admin.jenis-surat.edit', $jenis) }}"
                                   class="btn btn-sm btn-outline-primary">Ubah</a>

                                <form method="POST" action="{{ route('admin.jenis-surat.toggle', $jenis) }}" class="d-inline">
                                    @csrf
                                    @method('PATCH')
                                    <button class="btn btn-sm {{ $jenis->is_active ? 'btn-outline-secondary' : 'btn-outline-success' }}">
                                        {{ $jenis->is_active ? 'Nonaktifkan' : 'Aktifkan' }}
                                    </button>
                                </form>

                                @if ($jenis->templates_count === 0 && $jenis->pengajuans_count === 0)
                                    <form method="POST" action="{{ route('admin.jenis-surat.destroy', $jenis) }}"
                                          class="d-inline" onsubmit="return confirm('Hapus jenis surat ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-sm btn-outline-danger">Hapus</button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted py-4">Belum ada jenis surat.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-3">{{ $jenisSurats->links() }}</div>
@endsection