@extends('layouts.app')

@section('title', 'Tambah Program Studi')

@section('content')
    <h1 class="h4 mb-3">Tambah Program Studi</h1>

    <div class="card" style="max-width: 600px;">
        <div class="card-body">
            <form method="POST" action="{{ route('admin.program-studi.store') }}">
                @include('admin.program_studi._form')
            </form>
        </div>
    </div>
@endsection