@extends('layouts.app')
@section('title', 'Detail Pengajuan')
@section('content')

<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h4 class="fw-bold mb-1">Detail Pengajuan</h4>
            <p class="text-muted small mb-0">
                {{ $pengajuan->kode_tiket }}
            </p>
        </div>

        <a href="{{ route('operator.verifikasi.index') }}"
           class="btn btn-outline-secondary btn-sm">
            Kembali
        </a>
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

    <div class="row g-3">

        <div class="col-lg-6">
            <div class="card border-0 shadow-sm h-100">

                <div class="card-header bg-white fw-semibold">
                    Data Mahasiswa
                </div>

                <div class="card-body">

                    <div class="mb-3">
                        <div class="fw-semibold">Nama Mahasiswa</div>
                        <div class="text-muted">
                            {{ $pengajuan->mahasiswa?->user?->name ?? '-' }}
                        </div>
                    </div>

                    <div class="mb-3">
                        <div class="fw-semibold">NIM</div>
                        <div class="text-muted">
                            {{ $pengajuan->mahasiswa?->nim ?? '-' }}
                        </div>
                    </div>

                    <div class="mb-3">
                        <div class="fw-semibold">Program Studi</div>
                        <div class="text-muted">
                            {{ $pengajuan->mahasiswa?->programStudi?->nama ?? '-' }}
                        </div>
                    </div>

                    <div class="mb-3">
                        <div class="fw-semibold">Angkatan</div>
                        <div class="text-muted">
                            {{ $pengajuan->mahasiswa?->angkatan ?? '-' }}
                        </div>
                    </div>

                    <div>
                        <div class="fw-semibold">Nomor Telepon</div>
                        <div class="text-muted">
                            {{ $pengajuan->mahasiswa?->no_hp ?? '-' }}
                        </div>
                    </div>

                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="card border-0 shadow-sm h-100">

                <div class="card-header bg-white fw-semibold">
                    Informasi Pengajuan
                </div>

                <div class="card-body">

                    <div class="mb-3">
                        <div class="fw-semibold">Kode Tiket</div>
                        <div>{{ $pengajuan->kode_tiket }}</div>
                    </div>

                    <div class="mb-3">
                        <div class="fw-semibold">Jenis Surat</div>
                        <div>
                            {{ $pengajuan->jenisSurat?->nama ?? '-' }}
                        </div>
                    </div>

                    <div class="mb-3">
                        <div class="fw-semibold">Tanggal Pengajuan</div>
                        <div>
                            {{ $pengajuan->diajukan_at?->format('d-m-Y H:i') ?? '-' }}
                        </div>
                    </div>

                    <div>
                        <div class="fw-semibold mb-2">Status</div>
                        <x-status-badge :status="$pengajuan->status" />
                    </div>

                </div>
            </div>
        </div>

        <div class="col-12">
            <div class="card border-0 shadow-sm">

                <div class="card-header bg-white fw-semibold">
                    Data Formulir
                </div>

                <div class="card-body">
                    <div class="row g-3">

                        @forelse($pengajuan->data as $data)

                            <div class="col-md-6">
                                <div class="fw-semibold mb-1">
                                    {{ $pengajuan->templateSurat?->fields?->firstWhere('key', $data->key)?->label ?? ucwords(str_replace('_', ' ', $data->key)) }}
                                </div>

                                <div class="form-control bg-light">
                                    {{ $data->value ?? '-' }}
                                </div>
                            </div>

                        @empty

                            <p class="text-muted mb-0">
                                Belum ada data formulir.
                            </p>

                        @endforelse

                    </div>
                </div>

            </div>
        </div>

        <div class="col-12">
            <div class="card border-0 shadow-sm">

                <div class="card-header bg-white fw-semibold">
                    Anggota Kelompok
                </div>

                <div class="card-body">
                    <div class="table-responsive">

                        <table class="table table-hover table-sm align-middle mb-0">

                            <thead class="table-light">
                                <tr>
                                    <th>No</th>
                                    <th>Nama</th>
                                    <th>NIM</th>
                                    <th>Program Studi</th>
                                </tr>
                            </thead>

                            <tbody>

                                @forelse($pengajuan->anggotas as $anggota)

                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $anggota->nama }}</td>
                                        <td>{{ $anggota->nim }}</td>
                                        <td>
                                            {{ $anggota->programStudi?->nama ?? '-' }}
                                        </td>
                                    </tr>

                                @empty

                                    <tr>
                                        <td colspan="4"
                                            class="text-center text-muted">
                                            Belum ada anggota kelompok.
                                        </td>
                                    </tr>

                                @endforelse

                            </tbody>

                        </table>

                    </div>
                </div>

            </div>
        </div>

        <div class="col-12">
            <div class="card border-0 shadow-sm">

                <div class="card-header bg-white fw-semibold">
                    Lampiran Pengajuan
                </div>

                <div class="card-body">

                    @forelse($pengajuan->lampirans as $lampiran)

                        <div class="d-flex justify-content-between align-items-center gap-2 mb-2">

                            <span>
                                <i class="bi bi-paperclip me-1"></i>
                                {{ $lampiran->nama_file }}
                            </span>

                            <a href="{{ route('operator.lampiran.unduh', $lampiran->id) }}"
                               class="btn btn-outline-primary btn-sm">
                                Unduh
                            </a>

                        </div>

                    @empty

                        <p class="text-muted mb-0">
                            Tidak ada lampiran.
                        </p>

                    @endforelse

                </div>

            </div>
        </div>

        <div class="col-12">
            <div class="card border-0 shadow-sm">

                <div class="card-header bg-white fw-semibold">
                    Riwayat Status
                </div>

                <div class="card-body">

                    @forelse($pengajuan->riwayats as $riwayat)

                        <div class="border-start border-2 ps-3 mb-3">

                            <div class="fw-semibold">
                                {{ \App\StatusPengajuan::tryFrom($riwayat->status_ke)?->label() ?? $riwayat->status_ke }}
                            </div>

                            <div class="small text-muted">
                                {{ $riwayat->created_at?->format('d-m-Y H:i') }}
                                —
                                {{ $riwayat->user?->name ?? 'Sistem' }}
                            </div>

                            @if($riwayat->catatan)

                                <div class="small mt-1">
                                    {{ $riwayat->catatan }}
                                </div>

                            @endif

                        </div>

                    @empty

                        <p class="text-muted mb-0">
                            Belum ada riwayat status.
                        </p>

                    @endforelse

                </div>

            </div>
        </div>

        @if($pengajuan->catatan_revisi || $pengajuan->alasan_tolak)

            <div class="col-12">
                <div class="alert alert-warning mb-0">

                    @if($pengajuan->catatan_revisi)
                        <div>
                            <strong>Catatan Revisi:</strong>
                            {{ $pengajuan->catatan_revisi }}
                        </div>
                    @endif

                    @if($pengajuan->alasan_tolak)
                        <div>
                            <strong>Alasan Penolakan:</strong>
                            {{ $pengajuan->alasan_tolak }}
                        </div>
                    @endif

                </div>
            </div>

        @endif

        <div class="col-12">
            <div class="card border-0 shadow-sm">

                <div class="card-header bg-white fw-semibold">
                    Tindakan Operator
                </div>

                <div class="card-body">

                    @if($pengajuan->status === \App\StatusPengajuan::Diajukan)

                        <form method="POST"
                              action="{{ route('operator.pengajuan.terima', $pengajuan->id) }}"
                              class="mb-3">

                            @csrf

                            <button type="submit"
                                    class="btn btn-success btn-sm">
                                Terima Pengajuan
                            </button>

                        </form>

                    @elseif($pengajuan->status === \App\StatusPengajuan::Diproses)

                        <a href="{{ route('operator.pengajuan.surat.edit', $pengajuan->id) }}"
                        class="btn btn-primary btn-sm">
                            <i class="bi bi-file-earmark-text me-1"></i>
                            Buat Surat
                        </a>

                    @endif

                    @if(in_array($pengajuan->status, [
                        \App\StatusPengajuan::Diajukan,
                        \App\StatusPengajuan::Diproses
                    ], true))

                        <div class="row g-3">

                            <div class="col-md-6">

                                <form method="POST"
                                      action="{{ route('operator.pengajuan.revisi', $pengajuan->id) }}">

                                    @csrf

                                    <label class="form-label fw-semibold">
                                        Catatan Revisi
                                    </label>

                                    <textarea name="catatan"
                                              class="form-control mb-2"
                                              rows="3"
                                              maxlength="1000"
                                              required>{{ old('catatan') }}</textarea>

                                    <button type="submit"
                                            class="btn btn-warning btn-sm">
                                        Minta Revisi
                                    </button>

                                </form>

                            </div>

                            <div class="col-md-6">

                                <form method="POST"
                                      action="{{ route('operator.pengajuan.tolak', $pengajuan->id) }}">

                                    @csrf

                                    <label class="form-label fw-semibold">
                                        Alasan Penolakan
                                    </label>

                                    <textarea name="alasan"
                                              class="form-control mb-2"
                                              rows="3"
                                              maxlength="1000"
                                              required>{{ old('alasan') }}</textarea>

                                    <button type="submit"
                                            class="btn btn-danger btn-sm">
                                        Tolak
                                    </button>

                                </form>

                            </div>

                        </div>

                    @endif

                    @if($pengajuan->status === \App\StatusPengajuan::MenungguTtd)

                        <div class="alert alert-info mb-0">
                            Surat sedang menunggu tanda tangan Kajur.
                        </div>

                    @endif

                    @if(in_array($pengajuan->status, [
                        \App\StatusPengajuan::Ditandatangani,
                        \App\StatusPengajuan::SiapDiambil,
                        \App\StatusPengajuan::Selesai
                    ], true))

                        <a href="{{ route('operator.surat.index') }}"
                           class="btn btn-outline-primary btn-sm">
                            Lihat di Daftar Surat
                        </a>

                    @endif

                </div>

            </div>
        </div>

    </div>

</div>

@endsection
