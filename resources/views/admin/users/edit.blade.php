@extends('layouts.app')

@section('title', 'Ubah Pengguna')

@section('content')
    <h1 class="h4 mb-3">Ubah Pengguna</h1>

    <div class="card" style="max-width: 600px;">
        <div class="card-body">
            <form method="POST" action="{{ route('admin.users.update', $user) }}">
                @method('PUT')
                @include('admin.users._form')
            </form>
        </div>
    </div>
@endsection