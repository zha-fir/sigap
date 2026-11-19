<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title') | {{ config('app.name') }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <style>
        .sidebar {
            width: 230px;
        }
    </style>
</head>
<body class="bg-light">
    <div class="d-flex" style="min-height: 100vh;">

        {{-- SIDEBAR (menu di kiri) --}}
        <aside class="bg-dark text-white p-3 flex-shrink-0"
               style="width: 240px; min-width: 240px; min-height: 100vh;">
            <div class="fs-5 fw-bold mb-4 pb-2 border-bottom">
                <i class="bi bi-envelope-paper me-3"></i>CAMPUSIGN
            </div>
            @include('layouts.sidebar.' . auth()->user()->role->value)
        </aside>

        {{-- KONTEN (kanan) --}}
        <div class="flex-grow-1" style="min-width: 0;">

            {{-- Bagian atas: nama pengguna + tombol keluar --}}
            <nav class="navbar bg-white border-bottom px-3">
                <span class="ms-auto me-3">
                    <strong>{{ auth()->user()->name }}</strong>
                    <small class="text-muted">({{ auth()->user()->role->label() }})</small>
                </span>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button class="btn btn-sm btn-outline-danger">Keluar</button>
                </form>
            </nav>

            {{-- Isi halaman --}}
            <main class="p-4">
                @if (session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif

                @yield('content')
            </main>
        </div>
    </div>
</body>
</html>
