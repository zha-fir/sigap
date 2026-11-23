@extends('layouts.app')

@section('title', 'Ubah Template')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="h4 mb-0">Ubah Template Surat</h1>
        <a href="{{ route('admin.template.pratinjau', $template) }}" class="btn btn-outline-secondary">Pratinjau</a>
    </div>

    <div class="row g-3">
        <div class="col-lg-8">

            {{-- 1. Data template --}}
            <div class="card mb-3">
                <div class="card-header">Data template</div>
                <div class="card-body">
                    <form method="POST" action="{{ route('admin.template.update', $template) }}">
                        @method('PUT')
                        @include('admin.template._form')
                    </form>
                </div>
            </div>

            {{-- 2. Field isian --}}
            <div class="card">
                <div class="card-header">Field isian (form yang diisi mahasiswa)</div>
                <div class="card-body">

                    @if ($template->fields->isEmpty())
                        <p class="text-muted">Belum ada field. Tambahkan di bawah.</p>
                    @else
                        <form method="POST" action="{{ route('admin.template.fields.update', $template) }}">
                            @csrf
                            @method('PUT')

                            <div class="table-responsive">
                                <table class="table align-middle">
                                    <thead class="table-light">
                                        <tr>
                                            <th style="width: 80px;">Urutan</th>
                                            <th>Key</th>
                                            <th>Label</th>
                                            <th style="width: 140px;">Tipe</th>
                                            <th style="width: 80px;">Wajib</th>
                                            <th style="width: 80px;"></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($template->fields as $field)
                                            <tr>
                                                <td>
                                                    <input type="number" name="fields[{{ $field->id }}][urutan]"
                                                           value="{{ old("fields.{$field->id}.urutan", $field->urutan) }}"
                                                           class="form-control form-control-sm" min="1" max="999">
                                                </td>
                                                <td><code>[[{{ $field->key }}]]</code></td>
                                                <td>
                                                    <input type="text" name="fields[{{ $field->id }}][label]"
                                                           value="{{ old("fields.{$field->id}.label", $field->label) }}"
                                                           class="form-control form-control-sm">
                                                    @error("fields.{$field->id}.label")
                                                        <div class="text-danger small">{{ $message }}</div>
                                                    @enderror
                                                </td>
                                                <td>
                                                    <select name="fields[{{ $field->id }}][tipe]" class="form-select form-select-sm">
                                                        @foreach ($tipeField as $tipe)
                                                            <option value="{{ $tipe }}" @selected($field->tipe === $tipe)>{{ $tipe }}</option>
                                                        @endforeach
                                                    </select>
                                                </td>
                                                <td>
                                                    <input type="checkbox" name="fields[{{ $field->id }}][is_required]"
                                                           value="1" class="form-check-input" @checked($field->is_required)>
                                                </td>
                                                <td class="text-end">
                                                    <button type="submit" form="hapus-field-{{ $field->id }}"
                                                            class="btn btn-sm btn-outline-danger"
                                                            onclick="return confirm('Hapus field ini?')">Hapus</button>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <button class="btn btn-primary btn-sm">Simpan Perubahan Field</button>
                        </form>

                        {{-- Form hapus diletakkan di luar form di atas (HTML tidak boleh form di dalam form) --}}
                        @foreach ($template->fields as $field)
                            <form id="hapus-field-{{ $field->id }}" method="POST"
                                  action="{{ route('admin.template.fields.destroy', [$template, $field]) }}">
                                @csrf
                                @method('DELETE')
                            </form>
                        @endforeach
                    @endif

                    <div class="alert alert-warning small mt-3 mb-0">
                        Mengubah atau menghapus field memengaruhi form pengajuan berikutnya. Pengajuan yang sudah
                        ada tetap menyimpan datanya, tetapi placeholder yang tidak lagi sesuai akan tampil sebagai
                        <code>[[...]]</code> di surat. Untuk template yang sudah dipakai pengajuan nyata,
                        sebaiknya buat template baru lalu nonaktifkan yang lama.
                    </div>

                    <hr>

                    {{-- Tambah field --}}
                    <h2 class="h6">Tambah field</h2>
                    <form method="POST" action="{{ route('admin.template.fields.store', $template) }}" class="row g-2">
                        @csrf
                        <div class="col-md-3">
                            <input type="text" name="key" value="{{ old('key') }}" placeholder="key, contoh: nama_lokasi"
                                   class="form-control @error('key') is-invalid @enderror">
                            @error('key') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-4">
                            <input type="text" name="label" value="{{ old('label') }}" placeholder="Label, contoh: Nama lokasi"
                                   class="form-control @error('label') is-invalid @enderror">
                            @error('label') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-2">
                            <select name="tipe" class="form-select @error('tipe') is-invalid @enderror">
                                @foreach ($tipeField as $tipe)
                                    <option value="{{ $tipe }}" @selected(old('tipe') === $tipe)>{{ $tipe }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2 d-flex align-items-center">
                            <div class="form-check">
                                <input type="checkbox" id="field_wajib" name="is_required" value="1"
                                       class="form-check-input" @checked(old('is_required', true))>
                                <label for="field_wajib" class="form-check-label">Wajib</label>
                            </div>
                        </div>
                        <div class="col-md-1">
                            <button class="btn btn-outline-primary">Tambah</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        {{-- 3. Panduan placeholder --}}
        <div class="col-lg-4">
            <div class="card">
                <div class="card-header">Panduan placeholder</div>
                <div class="card-body small">
                    <p class="fw-semibold mb-1">Dari field isian</p>
                    @forelse ($template->fields as $field)
                        <div><code>[[{{ $field->key }}]]</code> {{ $field->label }}</div>
                    @empty
                        <div class="text-muted">Belum ada field.</div>
                    @endforelse

                    <p class="fw-semibold mt-3 mb-1">Dari sistem</p>
                    @foreach ($placeholderSistem as $key => $keterangan)
                        <div><code>[[{{ $key }}]]</code> {{ $keterangan }}</div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
@endsection