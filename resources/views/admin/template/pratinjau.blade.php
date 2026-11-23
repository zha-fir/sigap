@extends('layouts.app')

@section('title', 'Pratinjau Template')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="h4 mb-0">Pratinjau: {{ $template->nama }}</h1>
        <div>
            <a href="{{ route('admin.template.edit', $template) }}" class="btn btn-outline-primary">Ubah Template</a>
            <a href="{{ route('admin.template.index') }}" class="btn btn-link">Kembali</a>
        </div>
    </div>

    <div class="alert alert-info small">
        Pratinjau memakai data contoh, bukan data pengajuan sungguhan. Tampilan akhir saat dicetak
        mengikuti halaman cetak surat.
    </div>

    @if (count($tidakDikenal) > 0)
        <div class="alert alert-warning">
            <strong>Placeholder tidak dikenali:</strong>
            @foreach ($tidakDikenal as $nama)
                <code>[[{{ $nama }}]]</code>
            @endforeach
            <div class="small">Periksa kemungkinan salah ketik, atau tambahkan field dengan key tersebut.</div>
        </div>
    @endif

    <div class="card">
        <div class="card-body p-5" style="font-family: 'Times New Roman', serif; font-size: 12pt;">
            {!! $isi !!}
        </div>
    </div>
@endsection