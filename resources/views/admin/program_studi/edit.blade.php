@extends('layouts.app')

@section('title', 'Ubah Program Studi')

@section('content')
    <h1 class="h4 mb-3">Ubah Program Studi</h1>

    <div class="card" style="max-width: 600px;">
        <div class="card-body">
            <form method="POST" action="{{ route('admin.program-studi.update', $programStudi) }}">
                @method('PUT')
                @include('admin.program_studi._form')
            </form>
        </div>
    </div>
@endsection