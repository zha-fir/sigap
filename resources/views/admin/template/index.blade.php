@extends('layouts.app')

@section('title', 'Template Surat')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="h4 mb-0">Template Surat</h1>
        <a href="{{ route('admin.template.create') }}" class="btn btn-primary">Tambah Template</a>
    </div>

    <div class="card">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>No</th>
                        <th>Nama template</th>
                        <th>Jenis surat</th>
                        <th>Field</th>
                        <th>Pengajuan</th>
                        <th>Status</th>
                        <th class="text-end">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($templates as $template)
                        <tr>
                            <td>{{ $templates->firstItem() + $loop->index }}</td>
                            <td>{{ $template->nama }}</td>
                            <td>{{ $template->jenisSurat->nama }}</td>
                            <td>{{ $template->fields_count }}</td>
                            <td>{{ $template->pengajuans_count }}</td>
                            <td>
                                @if ($template->is_active)
                                    <span class="badge text-bg-success">Aktif</span>
                                @else
                                    <span class="badge text-bg-secondary">Nonaktif</span>
                                @endif
                            </td>
                            <td class="text-end">
                                <a href="{{ route('admin.template.pratinjau', $template) }}"
                                   class="btn btn-sm btn-outline-secondary">Pratinjau</a>
                                <a href="{{ route('admin.template.edit', $template) }}"
                                   class="btn btn-sm btn-outline-primary">Ubah</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted py-4">Belum ada template.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-3">{{ $templates->links() }}</div>
@endsection