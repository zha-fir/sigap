@extends('layouts.app')

@section('title', 'Pengaturan')

@section('content')
    <h1 class="h4 mb-3">Pengaturan</h1>

    <div class="card" style="max-width: 700px;">
        <div class="card-body">
            <form method="POST" action="{{ route('admin.pengaturan.update') }}">
                @csrf
                @method('PUT')

                <h2 class="h6 text-muted mb-3">Jam layanan</h2>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="jam_kerja_mulai" class="form-label">Jam mulai</label>
                        <input type="time" id="jam_kerja_mulai" name="jam_kerja_mulai"
                               value="{{ old('jam_kerja_mulai', $nilai['jam_kerja_mulai'] ?? '') }}"
                               class="form-control @error('jam_kerja_mulai') is-invalid @enderror" required>
                        @error('jam_kerja_mulai') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="jam_kerja_selesai" class="form-label">Jam selesai</label>
                        <input type="time" id="jam_kerja_selesai" name="jam_kerja_selesai"
                               value="{{ old('jam_kerja_selesai', $nilai['jam_kerja_selesai'] ?? '') }}"
                               class="form-control @error('jam_kerja_selesai') is-invalid @enderror" required>
                        @error('jam_kerja_selesai') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>

                <h2 class="h6 text-muted mt-2 mb-3">Proses surat</h2>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="estimasi_proses_hari" class="form-label">Estimasi lama proses</label>
                        <div class="input-group has-validation">
                            <input type="number" id="estimasi_proses_hari" name="estimasi_proses_hari"
                                   min="1" max="30"
                                   value="{{ old('estimasi_proses_hari', $nilai['estimasi_proses_hari'] ?? '') }}"
                                   class="form-control @error('estimasi_proses_hari') is-invalid @enderror" required>
                            <span class="input-group-text">hari kerja</span>
                            @error('estimasi_proses_hari') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="batas_pengambilan_hari" class="form-label">Batas pengambilan surat</label>
                        <div class="input-group has-validation">
                            <input type="number" id="batas_pengambilan_hari" name="batas_pengambilan_hari"
                                   min="1" max="90"
                                   value="{{ old('batas_pengambilan_hari', $nilai['batas_pengambilan_hari'] ?? '') }}"
                                   class="form-control @error('batas_pengambilan_hari') is-invalid @enderror" required>
                            <span class="input-group-text">hari</span>
                            @error('batas_pengambilan_hari') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>
                </div>

                <button class="btn btn-primary">Simpan Pengaturan</button>
            </form>
        </div>
    </div>
@endsection