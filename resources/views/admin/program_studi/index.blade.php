@extends('layouts.app')

@section('title', 'Program Studi')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="h4 mb-0">Program Studi</h1>
        <a href="{{ route('admin.program-studi.create') }}" class="btn btn-primary">Tambah Program Studi</a>
    </div>

    {{-- Pencarian --}}
    <form method="GET" class="row g-2 mb-3">
        <div class="col-md-5">
            <input type="text" name="cari" value="{{ request('cari') }}"
                   class="form-control" placeholder="Cari nama atau kode">
        </div>
        <div class="col-md-4">
            <button class="btn btn-outline-secondary">Cari</button>
            <a href="{{ route('admin.program-studi.index') }}" class="btn btn-link">Reset</a>
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
                        <th>Jumlah Mahasiswa</th>
                        <th class="text-end">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($programStudis as $prodi)
                        <tr>
                            <td>{{ $programStudis->firstItem() + $loop->index }}</td>
                            <td>{{ $prodi->kode }}</td>
                            <td>{{ $prodi->nama }}</td>
                            <td>{{ $prodi->mahasiswas_count }}</td>
                            <td class="text-end">
                                <a href="{{ route('admin.program-studi.edit', $prodi) }}"
                                   class="btn btn-sm btn-outline-primary">Ubah</a>

                                @if ($prodi->mahasiswas_count === 0)
                                    <form method="POST" action="{{ route('admin.program-studi.destroy', $prodi) }}"
                                          class="d-inline"
                                          onsubmit="return confirm('Hapus program studi ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-sm btn-outline-danger">Hapus</button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted py-4">Belum ada program studi.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-3">{{ $programStudis->links() }}</div>
@endsection