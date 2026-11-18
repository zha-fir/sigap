@extends('layouts.guest')

@section('title', 'Masuk')

@section('content')
<h2 class="h5 mb-3">Masuk</h2>

<form method="POST" action="{{ route('login.store') }}">
    @csrf

    <div class="mb-3">
        <label for="email" class="form-label">Email</label>
        <input type="email" id="email" name="email" value="{{ old('email') }}"
            class="form-control @error('email') is-invalid @enderror" required autofocus>
        @error('email')
        <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="mb-3">
        <label for="password" class="form-label">Password</label>
        <input type="password" id="password" name="password" class="form-control" required>
    </div>

    <button class="btn btn-primary w-100">Masuk</button>
</form>

<p class="text-center mt-3 mb-0">
    Mahasiswa belum punya akun? <a href="{{ route('register') }}">Daftar</a>
</p>
@endsection