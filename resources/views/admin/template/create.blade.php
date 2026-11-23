@extends('layouts.app')

@section('title', 'Tambah Template')

@section('content')
    <h1 class="h4 mb-3">Tambah Template Surat</h1>

    <div class="card" style="max-width: 900px;">
        <div class="card-body">
            <form method="POST" action="{{ route('admin.template.store') }}">
                @include('admin.template._form')
            </form>
        </div>
    </div>
    <p class="text-muted mt-2">Field isian ditambahkan setelah template disimpan.</p>
@endsection