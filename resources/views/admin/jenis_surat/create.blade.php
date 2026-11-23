@extends('layouts.app')

@section('title', 'Tambah Jenis Surat')

@section('content')
    <h1 class="h4 mb-3">Tambah Jenis Surat</h1>

    <div class="card" style="max-width: 600px;">
        <div class="card-body">
            <form method="POST" action="{{ route('admin.jenis-surat.store') }}">
                @include('admin.jenis_surat._form')
            </form>
        </div>
    </div>
@endsection