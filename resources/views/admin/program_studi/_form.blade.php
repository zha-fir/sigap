@csrf

<div class="mb-3">
    <label for="kode" class="form-label">Kode</label>
    <input type="text" id="kode" name="kode" value="{{ old('kode', $programStudi->kode ?? '') }}"
           class="form-control @error('kode') is-invalid @enderror" maxlength="20" required>
    @error('kode') <div class="invalid-feedback">{{ $message }}</div> @enderror
    <div class="form-text">Contoh: SI, TI. Otomatis menjadi huruf besar.</div>
</div>

<div class="mb-3">
    <label for="nama" class="form-label">Nama program studi</label>
    <input type="text" id="nama" name="nama" value="{{ old('nama', $programStudi->nama ?? '') }}"
           class="form-control @error('nama') is-invalid @enderror" required>
    @error('nama') <div class="invalid-feedback">{{ $message }}</div> @enderror
</div>

<button class="btn btn-primary">Simpan</button>
<a href="{{ route('admin.program-studi.index') }}" class="btn btn-link">
    <button class="btn btn-danger" type="button">
        Batal
    </button> 
</a>