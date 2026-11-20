@extends('layouts.app')

@section('title', 'Kelola Pengguna')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="h4 mb-0">Kelola Pengguna</h1>
        <a href="{{ route('admin.users.create') }}" class="btn btn-primary">Tambah Pengguna</a>
    </div>

    {{-- Filter --}}
    <form method="GET" class="row g-2 mb-3">
        <div class="col-md-4">
            <input type="text" name="cari" value="{{ request('cari') }}"
                   class="form-control" placeholder="Cari nama atau email">
        </div>
        <div class="col-md-3">
            <select name="role" class="form-select">
                <option value="">Semua role</option>
                @foreach ($roles as $role)
                    <option value="{{ $role->value }}" @selected(request('role') === $role->value)>
                        {{ $role->label() }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="col-md-3">
            <button class="btn btn-outline-secondary">Filter</button>
            <a href="{{ route('admin.users.index') }}" class="btn btn-link">Reset</a>
        </div>
    </form>

    {{-- Tabel --}}
    <div class="card">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>No</th>
                        <th>Nama</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Status</th>
                        <th class="text-end">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($users as $user)
                        <tr>
                            <td>{{ $users->firstItem() + $loop->index }}</td>
                            <td>{{ $user->name }}</td>
                            <td>{{ $user->email }}</td>
                            <td>{{ $user->role->label() }}</td>
                            <td>
                                @if ($user->is_active)
                                    <span class="badge text-bg-success">Aktif</span>
                                @else
                                    <span class="badge text-bg-secondary">Nonaktif</span>
                                @endif
                            </td>
                            <td class="text-end">
                                @if ($user->role->value !== 'mahasiswa')
                                    <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-sm btn-outline-primary">Ubah</a>
                                @endif

                                @if ($user->id !== auth()->id())
                                    <form method="POST" action="{{ route('admin.users.toggle', $user) }}" class="d-inline"
                                        onsubmit="return confirm('Yakin ingin mengubah status akun ini?')">
                                        @csrf
                                        @method('PATCH')
                                        <button class="btn btn-sm {{ $user->is_active ? 'btn-outline-danger' : 'btn-outline-success' }}">
                                            {{ $user->is_active ? 'Nonaktifkan' : 'Aktifkan' }}
                                        </button>
                                    </form>
                                @else
                                    <span class="badge text-bg-light border">Akun Anda</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted py-4">Tidak ada pengguna.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-3">{{ $users->links() }}</div>
@endsection