@extends('layouts.app')

@section('title', 'Buat Surat')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center gap-2 flex-wrap mb-3">
        <div>
            <h4 class="fw-bold mb-1">Buat Surat</h4>
            <p class="text-muted small mb-0">
                {{ $pengajuan->kode_tiket }} —
                {{ $pengajuan->mahasiswa?->user?->name ?? '-' }}
            </p>
        </div>

        <a href="{{ route('operator.surat.buat') }}"
           class="btn btn-outline-secondary btn-sm">
            Kembali
        </a>
    </div>

    @if($errors->any())
        <div class="alert alert-danger">
            {{ $errors->first() }}
        </div>
    @endif

    @unless($templateSiap)
        <div class="alert alert-info">
            Template surat untuk pengajuan ini belum tersedia atau belum aktif.
            Pembuatan surat menunggu pengelolaan template dari Admin.
        </div>
    @else
        <div class="row g-3">
            <div class="col-lg-5">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white py-3">
                        <h6 class="fw-semibold mb-0">
                            Edit Isi Surat
                        </h6>
                    </div>

                    <div class="card-body">
                        <form method="POST"
                              action="{{ route('operator.pengajuan.surat.simpan', $pengajuan->id) }}">
                            @csrf

                            <div class="mb-3">
                                <label class="form-label">
                                    Tanggal Surat
                                </label>

                                <input type="date"
                                       name="tanggal_surat"
                                       class="form-control"
                                       value="{{ old('tanggal_surat', $pengajuan->surat?->tanggal_surat?->format('Y-m-d') ?? now()->format('Y-m-d')) }}"
                                       required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">
                                    Isi Surat (HTML Template)
                                </label>

                                <textarea name="isi_surat"
                                          class="form-control font-monospace"
                                          rows="15"
                                          required>{{ old('isi_surat', $pengajuan->surat?->isi_surat ?: $pengajuan->templateSurat->isi_template) }}</textarea>

                                <div class="form-text">
                                    Pertahankan placeholder seperti
                                    [[nama_lokasi]], [[nomor_surat]],
                                    dan [[ttd_kajur]].
                                </div>
                            </div>

                            <button type="submit"
                                    class="btn btn-primary btn-sm">
                                Simpan Draf Surat
                            </button>
                        </form>

                        <hr>

                        @if(!$pejabatAktif)
                            <div class="alert alert-warning small mb-0">
                                Kirim ke Kajur belum tersedia karena
                                belum ada Kajur aktif.
                            </div>
                        @elseif(!$pengajuan->surat?->tanggal_surat || !$pengajuan->surat?->isi_surat)
                            <p class="text-muted small mb-0">
                                Simpan draf dahulu untuk mengaktifkan
                                Kirim ke Kajur.
                            </p>
                        @else
                            <form method="POST"
                                  action="{{ route('operator.pengajuan.surat.kirim', $pengajuan->id) }}"
                                  onsubmit="return confirm('Kirim surat ini ke Kajur untuk ditandatangani?')">
                                @csrf

                                <button type="submit"
                                        class="btn btn-success btn-sm">
                                    Kirim ke Kajur
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>

            <div class="col-lg-7">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white py-3">
                        <h6 class="fw-semibold mb-0">
                            Pratinjau Surat Tersimpan
                        </h6>
                    </div>

                    <div class="card-body">
                        <div class="border rounded bg-white p-3 p-md-4"
                             style="min-height:350px; overflow-x:auto; line-height:1.7">
                            {!! $isiSurat !!}
                        </div>

                        <p class="text-muted small mt-2 mb-0">
                            Pratinjau diperbarui setelah tombol
                            Simpan Draf Surat ditekan.
                            Nomor surat otomatis dibuat ketika
                            dikirim ke Kajur.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    @endunless
</div>
@endsection
