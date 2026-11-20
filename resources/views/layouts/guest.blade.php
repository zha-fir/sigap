<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title') | {{ config('app.name') }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-5">
                <h1 class="h4 text-center mb-4">{{ config('app.name') }}</h1>
                <div class="card shadow-sm">
                    <div class="card-body p-4">
                        @yield('content')
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.querySelectorAll('[data-toggle-password]').forEach(function (tombol) {
            tombol.addEventListener('click', function () {
                var input = document.querySelector(tombol.dataset.togglePassword);
                var ikon  = tombol.querySelector('i');
                var tampil = input.type === 'password';

                input.type = tampil ? 'text' : 'password';
                ikon.className = tampil ? 'bi bi-eye-slash' : 'bi bi-eye';
            });
        });
    </script>
</body>
</html>