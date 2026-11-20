@extends('layouts.guest')

@section('title', 'Daftar')

@section('content')
    <h2 class="h5 mb-3">Registrasi Mahasiswa</h2>

    <form method="POST" action="{{ route('register.store') }}">
        @csrf

        <div class="mb-3">
            <label for="name" class="form-label">Nama lengkap</label>
            <input type="text" id="name" name="name" value="{{ old('name') }}"
                   class="form-control @error('name') is-invalid @enderror" required>
            @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div class="mb-3">
            <label for="nim" class="form-label">NIM</label>
            <input type="text" id="nim" name="nim" value="{{ old('nim') }}"
                   class="form-control @error('nim') is-invalid @enderror" required>
            @error('nim') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div class="mb-3">
            <label for="program_studi_id" class="form-label">Program studi</label>
            <select id="program_studi_id" name="program_studi_id"
                    class="form-select @error('program_studi_id') is-invalid @enderror" required>
                <option value="">Pilih...</option>
                @foreach ($programStudis as $prodi)
                    <option value="{{ $prodi->id }}" @selected(old('program_studi_id') == $prodi->id)>
                        {{ $prodi->nama }}
                    </option>
                @endforeach
            </select>
            @error('program_studi_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div class="row">
            <div class="col-md-6 mb-3">
                <label for="angkatan" class="form-label">Angkatan</label>
                <input type="text" id="angkatan" name="angkatan" value="{{ old('angkatan') }}" maxlength="4"
                       class="form-control @error('angkatan') is-invalid @enderror">
                @error('angkatan') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="col-md-6 mb-3">
                <label for="no_hp" class="form-label">No. HP</label>
                <input type="text" id="no_hp" name="no_hp" value="{{ old('no_hp') }}"
                       class="form-control @error('no_hp') is-invalid @enderror">
                @error('no_hp') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
        </div>

        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" id="email" name="email" value="{{ old('email') }}"
                   class="form-control @error('email') is-invalid @enderror" required>
            @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div class="mb-3">
            <label for="password" class="form-label">Password</label>
            <div class="input-group has-validation">
                <input type="password" id="password" name="password"
                    class="form-control @error('password') is-invalid @enderror" required>
                <button type="button" class="btn btn-outline-secondary"
                        data-toggle-password="#password" aria-label="Tampilkan password">
                    <i class="bi bi-eye"></i>
                </button>
                @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
        </div>

        <div class="mb-3">
            <label for="password_confirmation" class="form-label">Konfirmasi password</label>
            <div class="input-group">
                <input type="password" id="password_confirmation" name="password_confirmation"
                   class="form-control" required>
                <button type="button" class="btn btn-outline-secondary"
                        data-toggle-password="#password_confirmation" aria-label="Tampilkan konfirmasi password">
                    <i class="bi bi-eye"></i>
                </button>
            </div>
        </div>

        <button class="btn btn-primary w-100">Daftar</button>
    </form>

    <p class="text-center mt-3 mb-0">
        Sudah punya akun? <a href="{{ route('login') }}">Masuk</a>
    </p>
@endsection