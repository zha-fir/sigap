
@extends('layouts.app')

@section('title', 'Pengaturan Tanda Tangan')

@section('content')
<div class="container-fluid py-3">

    {{-- Header --}}
    <div class="mb-4">
        <div class="d-flex flex-wrap justify-content-between align-items-center">
            <div>
                <h2 class="fw-bold mb-1" style="color: #1e293b;">
                    Pengaturan Tanda Tangan
                </h2>
                <p class="text-muted mb-0">
                    Kelola tanda tangan digital Ketua Jurusan.
                </p>
            </div>

            <span class="badge rounded-pill px-3 py-2 mt-3 mt-md-0"
                  style="background: #ccfbf1; color: #0f766e;">
                <i class="bi bi-shield-check me-1"></i>
                Pengaturan Akun
            </span>
        </div>
    </div>

    {{-- Notifikasi --}}
    @if (session('success'))
        <div class="alert alert-success border-0 shadow-sm rounded-3">
            <i class="bi bi-check-circle-fill me-2"></i>
            {{ session('success') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger border-0 shadow-sm rounded-3">
            <div class="fw-semibold mb-2">
                <i class="bi bi-exclamation-circle-fill me-2"></i>
                Terjadi kesalahan
            </div>
            @foreach ($errors->all() as $error)
                <div>{{ $error }}</div>
            @endforeach
        </div>
    @endif

    <div class="row g-4">

        {{-- Informasi Pejabat --}}
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm h-100"
                 style="border-radius: 16px;">

                <div class="card-body p-4">

                    <div class="d-flex align-items-center mb-4">
                        <div class="rounded-3 p-3 me-3"
                             style="background: #dbeafe; color: #2563eb;">
                            <i class="bi bi-person-badge fs-4"></i>
                        </div>

                        <div>
                            <h5 class="fw-bold mb-1">Informasi Pejabat</h5>
                            <small class="text-muted">Identitas akun pejabat</small>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="text-muted small mb-1">Nama Lengkap</label>
                        <h6 class="fw-bold mb-0">{{ $pejabat->nama }}</h6>
                    </div>

                    <hr class="text-muted">

                    <div class="mb-4">
                        <label class="text-muted small mb-1">Nomor Induk Pegawai</label>
                        <h6 class="fw-semibold mb-0">{{ $pejabat->nip ?? '-' }}</h6>
                    </div>

                    <hr class="text-muted">

                    <div class="mb-4">
                        <label class="text-muted small mb-1">Jabatan</label>
                        <h6 class="fw-semibold mb-0">{{ $pejabat->jabatan }}</h6>
                    </div>

                    <hr class="text-muted">

                    <div>
                        <label class="text-muted small mb-2">Status Tanda Tangan</label>

                        @if ($pejabat->file_ttd)
                            <div>
                                <span class="badge rounded-pill px-3 py-2"
                                      style="background: #ccfbf1; color: #0f766e;">
                                    <i class="bi bi-check-circle-fill me-1"></i>
                                    Sudah Diunggah
                                </span>
                            </div>
                        @else
                            <div>
                                <span class="badge rounded-pill px-3 py-2"
                                      style="background: #fef3c7; color: #b45309;">
                                    <i class="bi bi-exclamation-circle-fill me-1"></i>
                                    Belum Diunggah
                                </span>
                            </div>
                        @endif
                    </div>

                </div>
            </div>
        </div>

        {{-- Pengaturan Tanda Tangan --}}
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm h-100"
                 style="border-radius: 16px;">

                <div class="card-body p-4 p-lg-5">

                    <div class="d-flex align-items-center mb-2">
                        <div class="rounded-3 p-3 me-3"
                             style="background: #ccfbf1; color: #0d9488;">
                            <i class="bi bi-pen fs-4"></i>
                        </div>

                        <div>
                            <h5 class="fw-bold mb-1">Tanda Tangan Digital</h5>
                            <small class="text-muted">
                                Pengaturan tanda tangan elektronik pejabat.
                            </small>
                        </div>
                    </div>

                    <p class="text-muted mt-3 mb-4">
                        Unggah gambar tanda tangan yang akan digunakan
                        dalam proses pengesahan surat.
                    </p>

                    {{-- Preview --}}
                    <div class="border rounded-4 p-4 mb-4 text-center"
                         style="background: #f8fafc; border-color: #e2e8f0 !important;">

                        <h6 class="fw-bold mb-3" style="color: #334155;">
                            Pratinjau Tanda Tangan
                        </h6>

                        @if ($pejabat->file_ttd)
                            <img
                                src="{{ $pejabat->ttdUrl() }}"
                                id="preview"
                                alt="Tanda Tangan"
                                class="img-fluid"
                                style="max-height: 180px; max-width: 100%; object-fit: contain;"
                            >

                            <div id="kosong" class="d-none text-muted py-4">
                                <i class="bi bi-image fs-1"></i>
                                <p class="mb-0 mt-2">Belum ada gambar.</p>
                            </div>
                        @else
                            <img
                                id="preview"
                                src=""
                                alt="Pratinjau Tanda Tangan"
                                class="img-fluid d-none"
                                style="max-height: 180px; max-width: 100%; object-fit: contain;"
                            >

                            <div id="kosong" class="text-muted py-4">
                                <i class="bi bi-image fs-1"></i>
                                <p class="mb-0 mt-2">Belum ada tanda tangan.</p>
                            </div>
                        @endif

                    </div>

                    {{-- Form Upload --}}
                    <form action="{{ route('kajur.ttd.update') }}"
                          method="POST"
                          enctype="multipart/form-data">

                        @csrf

                        <div class="mb-3">
                            <label for="file_ttd" class="form-label fw-semibold">
                                Upload Tanda Tangan Baru
                            </label>

                            <input
                                type="file"
                                name="file_ttd"
                                id="file_ttd"
                                class="form-control"
                                accept="image/png"
                                required
                            >

                            <div class="form-text mt-2">
                                <i class="bi bi-info-circle me-1"></i>
                                Format file PNG dengan ukuran maksimal 2 MB.
                            </div>
                        </div>

                        <div class="d-flex flex-wrap gap-2 mt-4">
                            <button type="submit"
                                    class="btn text-white px-4 py-2"
                                    style="background: #0d9488; border-radius: 9px;">
                                <i class="bi bi-cloud-arrow-up me-2"></i>
                                Simpan Tanda Tangan
                            </button>
                        </div>

                    </form>

                </div>
            </div>
        </div>

    </div>

    {{-- Informasi Tambahan --}}
    <div class="card border-0 shadow-sm mt-4"
         style="border-radius: 14px;">
        <div class="card-body p-3">
            <div class="d-flex align-items-start">
                <i class="bi bi-info-circle-fill me-3 fs-4"
                   style="color: #0d9488;"></i>

                <div>
                    <h6 class="fw-bold mb-1">Informasi Penggunaan</h6>
                    <p class="text-muted small mb-0">
                        Pastikan gambar tanda tangan terlihat jelas,
                        memiliki latar belakang transparan, dan merupakan
                        tanda tangan resmi Ketua Jurusan.
                    </p>
                </div>
            </div>
        </div>
    </div>

</div>

{{-- Preview sebelum upload --}}
<script>
    document.getElementById('file_ttd').addEventListener('change', function(e) {
        const file = e.target.files[0];

        if (file && file.type === 'image/png') {
            const reader = new FileReader();

            reader.onload = function(event) {
                const preview = document.getElementById('preview');
                const kosong = document.getElementById('kosong');

                preview.src = event.target.result;
                preview.classList.remove('d-none');

                if (kosong) {
                    kosong.classList.add('d-none');
                }
            };

            reader.readAsDataURL(file);
        }
    });
</script>
@endsection
