@csrf

@php $edit = isset($template); @endphp

<div class="mb-3">
    <label for="nama" class="form-label">Nama template</label>
    <input type="text" id="nama" name="nama" value="{{ old('nama', $template->nama ?? '') }}"
           class="form-control @error('nama') is-invalid @enderror" required>
    @error('nama') <div class="invalid-feedback">{{ $message }}</div> @enderror
</div>

<div class="mb-3">
    <label for="jenis_surat_id" class="form-label">Jenis surat</label>
    @if ($edit)
        <input type="text" class="form-control" value="{{ $template->jenisSurat->nama }}" disabled>
        <div class="form-text">Jenis surat tidak dapat diubah setelah template dibuat.</div>
    @else
        <select id="jenis_surat_id" name="jenis_surat_id"
                class="form-select @error('jenis_surat_id') is-invalid @enderror" required>
            <option value="">Pilih...</option>
            @foreach ($jenisSurats as $jenis)
                <option value="{{ $jenis->id }}" @selected(old('jenis_surat_id') == $jenis->id)>
                    {{ $jenis->nama }} ({{ $jenis->kode }})
                </option>
            @endforeach
        </select>
        @error('jenis_surat_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
    @endif
</div>

<div class="mb-3">
    <label for="format_nomor" class="form-label">Format nomor surat</label>
    <input type="text" id="format_nomor" name="format_nomor"
           value="{{ old('format_nomor', $template->format_nomor ?? '{urutan}/{kode}/{bulan_romawi}/{tahun}') }}"
           class="form-control @error('format_nomor') is-invalid @enderror" required>
    @error('format_nomor') <div class="invalid-feedback">{{ $message }}</div> @enderror
    <div class="form-text">
        Token: <code>{urutan}</code> (wajib), <code>{kode}</code>, <code>{bulan_romawi}</code>, <code>{tahun}</code>.
        Contoh hasil: 001/SPL/XI/2026
    </div>
</div>

<div class="mb-3">
    <label for="isi_template" class="form-label">Isi template (HTML)</label>
    <textarea id="isi_template" name="isi_template" rows="16"
              class="form-control font-monospace @error('isi_template') is-invalid @enderror"
              required>{{ old('isi_template', $template->isi_template ?? '') }}</textarea>
    @error('isi_template') <div class="invalid-feedback">{{ $message }}</div> @enderror
    <div class="form-text">
        Tulis isi surat dengan HTML sederhana (<code>&lt;p&gt;</code>, <code>&lt;strong&gt;</code>, <code>&lt;table&gt;</code>).
        Tempat isian ditulis <code>[[nama_field]]</code>.
    </div>
</div>

<div class="form-check mb-3">
    <input type="checkbox" id="is_active" name="is_active" value="1" class="form-check-input"
           @checked(old('is_active', $template->is_active ?? true))>
    <label for="is_active" class="form-check-label">Aktif (dapat dipilih mahasiswa)</label>
</div>

<button class="btn btn-primary">Simpan Template</button>
<a href="{{ route('admin.template.index') }}" class="btn btn-link">Batal</a>