
@extends('layouts.app')

@section('title', 'Dashboard Kajur')

@section('content')
<div class="container-fluid py-3">

    {{-- Header Dashboard --}}
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold mb-1" style="color: #1e293b;">
                Dashboard Kajur
            </h2>
            <p class="text-muted mb-0">
                Selamat datang di halaman administrasi Ketua Jurusan.
            </p>
        </div>

        <div class="mt-3 mt-md-0">
            <span class="badge rounded-pill px-3 py-2"
                  style="background: #ccfbf1; color: #0f766e;">
                <i class="bi bi-circle-fill me-1" style="font-size: 8px;"></i>
                Panel Ketua Jurusan
            </span>
        </div>
    </div>

    {{-- Welcome Card --}}
    <div class="card border-0 shadow-sm mb-4 overflow-hidden"
         style="border-radius: 16px; background: #172554;">
        <div class="card-body p-4 p-lg-5">
            <div class="row align-items-center">
                <div class="col-lg-8">
                    <span class="text-uppercase small fw-semibold"
                          style="color: #5eead4; letter-spacing: 1px;">
                        Sistem Informasi Administrasi
                    </span>

                    <h3 class="text-white fw-bold mt-2 mb-2">
                        Selamat Datang, Ketua Jurusan!
                    </h3>

                    <p class="mb-0" style="color: #cbd5e1;">
                        Pantau pengajuan surat, kelola tanda tangan digital,
                        dan lakukan pengesahan dokumen melalui satu halaman.
                    </p>
                </div>

                <div class="col-lg-4 text-lg-end mt-4 mt-lg-0">
                    <i class="bi bi-mortarboard"
                       style="font-size: 90px; color: #5eead4; opacity: .85;"></i>
                </div>
            </div>
        </div>
    </div>

    {{-- Ringkasan --}}
    <div class="d-flex align-items-center mb-3">
        <h5 class="fw-bold mb-0" style="color: #1e293b;">
            Ringkasan Aktivitas
        </h5>
    </div>

    <div class="row g-3 mb-4">

        <div class="col-sm-6 col-xl-3">
            <div class="card border-0 shadow-sm h-100"
                 style="border-radius: 14px;">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted small mb-2">Total Pengajuan</p>
                            <h3 class="fw-bold mb-0">—</h3>
                        </div>
                        <div class="rounded-3 p-3"
                             style="background: #dbeafe; color: #2563eb;">
                            <i class="bi bi-envelope-paper fs-4"></i>
                        </div>
                    </div>
                    <p class="text-muted small mt-3 mb-0">
                        <i class="bi bi-info-circle me-1"></i>
                        Data belum tersedia
                    </p>
                </div>
            </div>
        </div>

        <div class="col-sm-6 col-xl-3">
            <div class="card border-0 shadow-sm h-100"
                 style="border-radius: 14px;">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted small mb-2">Menunggu TTD</p>
                            <h3 class="fw-bold mb-0">—</h3>
                        </div>
                        <div class="rounded-3 p-3"
                             style="background: #fef3c7; color: #d97706;">
                            <i class="bi bi-clock-history fs-4"></i>
                        </div>
                    </div>
                    <p class="text-muted small mt-3 mb-0">
                        <i class="bi bi-info-circle me-1"></i>
                        Data belum tersedia
                    </p>
                </div>
            </div>
        </div>

        <div class="col-sm-6 col-xl-3">
            <div class="card border-0 shadow-sm h-100"
                 style="border-radius: 14px;">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted small mb-2">Surat Disahkan</p>
                            <h3 class="fw-bold mb-0">—</h3>
                        </div>
                        <div class="rounded-3 p-3"
                             style="background: #ccfbf1; color: #0d9488;">
                            <i class="bi bi-check-circle fs-4"></i>
                        </div>
                    </div>
                    <p class="text-muted small mt-3 mb-0">
                        <i class="bi bi-info-circle me-1"></i>
                        Data belum tersedia
                    </p>
                </div>
            </div>
        </div>

        <div class="col-sm-6 col-xl-3">
            <div class="card border-0 shadow-sm h-100"
                 style="border-radius: 14px;">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted small mb-2">Surat Dikembalikan</p>
                            <h3 class="fw-bold mb-0">—</h3>
                        </div>
                        <div class="rounded-3 p-3"
                             style="background: #fee2e2; color: #dc2626;">
                            <i class="bi bi-arrow-return-left fs-4"></i>
                        </div>
                    </div>
                    <p class="text-muted small mt-3 mb-0">
                        <i class="bi bi-info-circle me-1"></i>
                        Data belum tersedia
                    </p>
                </div>
            </div>
        </div>

    </div>

    {{-- Aktivitas dan Informasi --}}
    <div class="row g-3">

        <div class="col-lg-8">
            <div class="card border-0 shadow-sm h-100"
                 style="border-radius: 14px;">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <div>
                            <h5 class="fw-bold mb-1">Aktivitas Terbaru</h5>
                            <p class="text-muted small mb-0">
                                Riwayat aktivitas pengajuan surat.
                            </p>
                        </div>
                        <i class="bi bi-clock text-muted fs-4"></i>
                    </div>

                    <div class="text-center py-5">
                        <i class="bi bi-inbox"
                           style="font-size: 48px; color: #94a3b8;"></i>
                        <h6 class="fw-semibold mt-3">Belum Ada Aktivitas</h6>
                        <p class="text-muted small mb-0">
                            Aktivitas terbaru akan ditampilkan di sini.
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card border-0 shadow-sm h-100"
                 style="border-radius: 14px;">
                <div class="card-body p-4">
                    <h5 class="fw-bold mb-1">Informasi Akun</h5>
                    <p class="text-muted small mb-4">
                        Informasi akses Ketua Jurusan.
                    </p>

                    <div class="d-flex align-items-center mb-3">
                        <div class="rounded-circle p-3 me-3"
                             style="background: #e0f2fe; color: #0369a1;">
                            <i class="bi bi-person-badge fs-4"></i>
                        </div>
                        <div>
                            <p class="text-muted small mb-1">Hak Akses</p>
                            <h6 class="fw-bold mb-0">Ketua Jurusan</h6>
                        </div>
                    </div>

                    <hr>

                    <div class="d-flex align-items-center">
                        <div class="rounded-circle p-3 me-3"
                             style="background: #ccfbf1; color: #0f766e;">
                            <i class="bi bi-shield-check fs-4"></i>
                        </div>
                        <div>
                            <p class="text-muted small mb-1">Status Sistem</p>
                            <h6 class="fw-bold mb-0">Terhubung</h6>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

</div>
@endsection
