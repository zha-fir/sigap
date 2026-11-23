<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\JenisSurat;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class JenisSuratController extends Controller
{
    public function index(Request $request)
    {
        $query = JenisSurat::withCount(['templates', 'pengajuans']);

        if ($request->filled('cari')) {
            $query->where(function ($q) use ($request) {
                $q->where('nama', 'like', '%' . $request->cari . '%')
                  ->orWhere('kode', 'like', '%' . $request->cari . '%');
            });
        }

        $jenisSurats = $query->orderBy('nama')->paginate(10)->withQueryString();

        return view('admin.jenis_surat.index', compact('jenisSurats'));
    }

    public function create()
    {
        return view('admin.jenis_surat.create');
    }

    public function store(Request $request)
    {
        $request->merge(['kode' => strtoupper((string) $request->input('kode'))]);

        $data = $request->validate([
            'nama'      => ['required', 'string', 'max:255', 'unique:jenis_surats,nama'],
            'kode'      => ['required', 'string', 'max:20', 'alpha_dash', 'unique:jenis_surats,kode'],
            'deskripsi' => ['nullable', 'string', 'max:1000'],
        ]);

        $data['is_active'] = $request->boolean('is_active');

        JenisSurat::create($data);

        return redirect()->route('admin.jenis-surat.index')->with('success', 'Jenis surat berhasil ditambahkan.');
    }

    public function edit(JenisSurat $jenisSurat)
    {
        return view('admin.jenis_surat.edit', compact('jenisSurat'));
    }

    public function update(Request $request, JenisSurat $jenisSurat)
    {
        $request->merge(['kode' => strtoupper((string) $request->input('kode'))]);

        $data = $request->validate([
            'nama'      => ['required', 'string', 'max:255', Rule::unique('jenis_surats', 'nama')->ignore($jenisSurat->id)],
            'kode'      => ['required', 'string', 'max:20', 'alpha_dash', Rule::unique('jenis_surats', 'kode')->ignore($jenisSurat->id)],
            'deskripsi' => ['nullable', 'string', 'max:1000'],
        ]);

        $data['is_active'] = $request->boolean('is_active');

        $jenisSurat->update($data);

        return redirect()->route('admin.jenis-surat.index')
            ->with('success', 'Jenis surat berhasil diperbarui.');
    }

    public function toggle(JenisSurat $jenisSurat)
    {
        $jenisSurat->update(['is_active' => ! $jenisSurat->is_active]);

        return back()->with('success', 'Status jenis surat berhasil diperbarui.');
    }

    public function destroy(JenisSurat $jenisSurat)
    {
        if ($jenisSurat->templates()->exists() || $jenisSurat->pengajuans()->exists()) {
            return back()->with('error', 'Jenis surat tidak dapat dihapus karena sudah memiliki template atau pengajuan. Nonaktifkan saja.');
        }

        $jenisSurat->delete();

        return redirect()->route('admin.jenis-surat.index')
            ->with('success', 'Jenis surat berhasil dihapus.');
    }
}