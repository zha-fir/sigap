@php
    $mhs = auth()->user()->mahasiswa;
    $isian = isset($pengajuan) ? $pengajuan->dataArray() : [];
    $barisAnggota = old('anggota', isset($pengajuan)
        ? $pengajuan->anggotas->where('is_ketua', false)->map(fn ($a) => [
            'nama' => $a->nama, 'nim' => $a->nim, 'program_studi_id' => $a->program_studi_id,
        ])->values()->all()
        : []);
    $indexBerikut = $barisAnggota ? max(array_keys($barisAnggota)) + 1 : 0;
@endphp

<form method="POST" action="{{ $action }}" enctype="multipart/form-data">
    @csrf
    @isset($method) @method($method) @endisset
    <input type="hidden" name="template_surat_id" value="{{ $template->id }}">

    @if ($errors->any())
        <div class="alert alert-danger">
            Data belum lengkap atau ada yang salah. Periksa kolom yang ditandai merah.
        </div>
    @endif

    <div class="card mb-3">
        <div class="card-header fw-semibold">Data Isian</div>
        <div class="card-body">
            @foreach ($template->fields as $f)
                @php
                    $name = "isian[{$f->key}]";
                    $err = "isian.{$f->key}";
                    $nilai = old($err, $isian[$f->key] ?? '');
                @endphp
                <div class="mb-3">
                    <label class="form-label">
                        {{ $f->label }}
                        @if ($f->is_required)<span class="text-danger">*</span>@endif
                    </label>

                    @if ($f->tipe === 'textarea')
                        <textarea name="{{ $name }}" rows="3"
                                  class="form-control @error($err) is-invalid @enderror">{{ $nilai }}</textarea>
                    @elseif ($f->tipe === 'date')
                        <input type="date" name="{{ $name }}" value="{{ $nilai }}"
                               class="form-control @error($err) is-invalid @enderror">
                    @elseif ($f->tipe === 'select' && $f->opsi)
                        <select name="{{ $name }}" class="form-select @error($err) is-invalid @enderror">
                            <option value="">-- Pilih --</option>
                            @foreach ($f->opsi as $o)
                                <option value="{{ $o }}" @selected((string) $nilai === (string) $o)>{{ $o }}</option>
                            @endforeach
                        </select>
                    @else
                        <input type="text" name="{{ $name }}" value="{{ $nilai }}"
                               class="form-control @error($err) is-invalid @enderror">
                    @endif

                    @error($err)<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            @endforeach
        </div>
    </div>

    <div class="card mb-3">
        <div class="card-header fw-semibold">Anggota Kelompok</div>
        <div class="card-body">
            <p class="text-muted small mb-2">Kamu otomatis menjadi ketua kelompok. Tambahkan anggota lain jika ada.</p>

            <div class="row g-2 mb-2">
                <div class="col-md-4"><input class="form-control" value="{{ auth()->user()->name }}" disabled></div>
                <div class="col-md-3"><input class="form-control" value="{{ $mhs->nim }}" disabled></div>
                <div class="col-md-4"><input class="form-control" value="{{ $mhs->programStudi->nama ?? '-' }}" disabled></div>
                <div class="col-md-1 d-flex align-items-center"><span class="badge text-bg-primary">Ketua</span></div>
            </div>

            <div id="anggota-wrapper">
                @foreach ($barisAnggota as $i => $a)
                    @include('mahasiswa.partials.baris-anggota', ['i' => $i, 'a' => $a])
                @endforeach
            </div>

            <button type="button" id="btn-tambah-anggota" class="btn btn-sm btn-outline-primary mt-2">
                <i class="bi bi-plus-lg me-1"></i>Tambah Anggota
            </button>
        </div>
    </div>

    <div class="card mb-3">
        <div class="card-header fw-semibold">Lampiran (opsional)</div>
        <div class="card-body">
            <input type="file" name="lampiran[]" multiple
                   class="form-control @if ($errors->has('lampiran') || $errors->has('lampiran.*')) is-invalid @endif">
            <div class="form-text">Maksimal 5 file, masing-masing 2 MB. Format: PDF, JPG, PNG, DOC, DOCX.</div>
            @foreach (collect($errors->get('lampiran.*'))->flatten()->merge($errors->get('lampiran')) as $pesan)
                <div class="text-danger small">{{ $pesan }}</div>
            @endforeach
            @isset($pengajuan)
                @if ($pengajuan->lampirans->isNotEmpty())
                    <div class="form-text mt-2">Lampiran yang sudah ada tetap tersimpan; file baru akan ditambahkan.</div>
                @endif
            @endisset
        </div>
    </div>

    <div class="d-flex gap-2 mb-4">
        <a href="{{ route('mahasiswa.pengajuan.index') }}" class="btn btn-outline-secondary">Batal</a>
        <button type="submit" name="aksi" value="draft" class="btn btn-secondary">Simpan Draft</button>
        <button type="submit" name="aksi" value="ajukan" class="btn btn-primary"
                onclick="return confirm('Kirim pengajuan ke operator? Setelah dikirim, pengajuan tidak bisa diubah kecuali diminta revisi.')">
            <i class="bi bi-send me-1"></i>Ajukan
        </button>
    </div>
</form>

<template id="anggota-template">
    @include('mahasiswa.partials.baris-anggota', ['i' => '__INDEX__', 'a' => []])
</template>

<script>
    (function () {
        const wrapper = document.getElementById('anggota-wrapper');
        const cetakan = document.getElementById('anggota-template').innerHTML;
        let index = {{ $indexBerikut }};

        document.getElementById('btn-tambah-anggota').addEventListener('click', function () {
            wrapper.insertAdjacentHTML('beforeend', cetakan.replaceAll('__INDEX__', index++));
        });

        wrapper.addEventListener('click', function (e) {
            const tombol = e.target.closest('.btn-hapus-anggota');
            if (tombol) {
                tombol.closest('.baris-anggota').remove();
            }
        });
    })();
</script>