<div class="row g-2 mb-2 baris-anggota">
    <div class="col-md-4">
        <input type="text" name="anggota[{{ $i }}][nama]" value="{{ $a['nama'] ?? '' }}"
               class="form-control @error("anggota.$i.nama") is-invalid @enderror" placeholder="Nama lengkap">
        @error("anggota.$i.nama")<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-3">
        <input type="text" name="anggota[{{ $i }}][nim]" value="{{ $a['nim'] ?? '' }}"
               class="form-control @error("anggota.$i.nim") is-invalid @enderror" placeholder="NIM">
        @error("anggota.$i.nim")<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-4">
        <select name="anggota[{{ $i }}][program_studi_id]" class="form-select">
            @foreach ($programStudis as $ps)
                <option value="{{ $ps->id }}"
                    @selected((int) ($a['program_studi_id'] ?? auth()->user()->mahasiswa->program_studi_id) === $ps->id)>
                    {{ $ps->nama }}
                </option>
            @endforeach
        </select>
    </div>
    <div class="col-md-1">
        <button type="button" class="btn btn-outline-danger btn-hapus-anggota" title="Hapus">
            <i class="bi bi-trash"></i>
        </button>
    </div>
</div>