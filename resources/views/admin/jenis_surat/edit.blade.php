@extends('layouts.app')

@section('title', 'Ubah Jenis Surat')

@section('content')
    <h1 class="h4 mb-3">Ubah Jenis Surat</h1>

    <div class="card" style="max-width: 600px;">
        <div class="card-body">
            <form method="POST" action="{{ route('admin.jenis-surat.update', $jenisSurat) }}">
                @method('PUT')
                @include('admin.jenis_surat._form')
            </form>
        </div>
    </div>
@endsection