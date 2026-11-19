@extends('layouts.app')

@section('title', 'Ajukan Surat')

@section('content')
    <h1 class="h4 mb-3">Ajukan Surat</h1>

    @if (! $template)
        <div class="alert alert-warning">
            Belum ada template surat yang aktif. Silakan hubungi admin jurusan.
        </div>
    @else

        @if ($templates->count() > 1)
            <form method="GET" action="{{ route('mahasiswa.pengajuan.create') }}" class="mb-3">
                <label class="form-label">Jenis surat</label>
                <select name="template" class="form-select w-auto" onchange="this.form.submit()">
                    @foreach ($templates as $t)
                        <option value="{{ $t->id }}" @selected($t->id === $template->id)>{{ $t->nama }}</option>
                    @endforeach
                </select>
            </form>
        @else
            <p class="text-muted">{{ $template->jenisSurat->nama ?? $template->nama }}</p>
        @endif

        @include('mahasiswa.partials.form-pengajuan', [
            'action' => route('mahasiswa.pengajuan.store'),
        ])
    @endif
@endsection