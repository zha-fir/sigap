@csrf

<div class="mb-3">
    <label for="kode" class="form-label">Kode</label>
    <input type="text" id="kode" name="kode" value="{{ old('kode', $jenisSurat->kode ?? '') }}"
           class="form-control @error('kode') is-invalid @enderror" maxlength="20" required>
    @error('kode') <div class="invalid-feedback">{{ $message }}</div> @enderror
    <div class="form-text">Dipakai pada nomor surat, contoh: SPL. Hanya huruf, angka, tanda hubung, dan garis bawah.</div>
</div>

<div class="mb-3">
    <label for="nama" class="form-label">Nama jenis surat</label>
    <input type="text" id="nama" name="nama" value="{{ old('nama', $jenisSurat->nama ?? '') }}"
           class="form-control @error('nama') is-invalid @enderror" required>
    @error('nama') <div class="invalid-feedback">{{ $message }}</div> @enderror
</div>

<div class="mb-3">
    <label for="deskripsi" class="form-label">Deskripsi <small class="text-muted">(opsional)</small></label>
    <textarea id="deskripsi" name="deskripsi" rows="3"
              class="form-control @error('deskripsi') is-invalid @enderror">{{ old('deskripsi', $jenisSurat->deskripsi ?? '') }}</textarea>
    @error('deskripsi') <div class="invalid-feedback">{{ $message }}</div> @enderror
</div>

<div class="form-check mb-3">
    <input type="checkbox" id="is_active" name="is_active" value="1" class="form-check-input"
           @checked(old('is_active', $jenisSurat->is_active ?? true))>
    <label for="is_active" class="form-check-label">Aktif (dapat dipilih mahasiswa)</label>
</div>

<button class="btn btn-primary">Simpan</button>
<a href="{{ route('admin.jenis-surat.index') }}" class="btn btn-link">Batal</a>