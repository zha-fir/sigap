@extends('layouts.app')

@section('title', 'Tambah Pengguna')

@section('content')
    <h1 class="h4 mb-3">Tambah Pengguna</h1>

    <div class="card" style="max-width: 600px;">
        <div class="card-body">
            <form method="POST" action="{{ route('admin.users.store') }}">
                @include('admin.users._form')
            </form>
        </div>
    </div>
@endsection