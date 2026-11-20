<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ProgramStudi;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ProgramStudiController extends Controller
{
    // Daftar program studi
    public function index(Request $request)
    {
        $query = ProgramStudi::withCount('mahasiswas');

        if ($request->filled('cari')) {
            $query->where(function ($q) use ($request) {
                $q->where('nama', 'like', '%' . $request->cari . '%')
                  ->orWhere('kode', 'like', '%' . $request->cari . '%');
            });
        }

        $programStudis = $query->orderBy('nama')->paginate(10)->withQueryString();

        return view('admin.program_studi.index', compact('programStudis'));
    }

    // Form tambah
    public function create()
    {
        return view('admin.program_studi.create');
    }

    // Simpan data baru
    public function store(Request $request)
    {
        $request->merge(['kode' => strtoupper((string) $request->input('kode'))]);

        $data = $request->validate([
            'nama' => ['required', 'string', 'max:255', 'unique:program_studis,nama'],
            'kode' => ['required', 'string', 'max:20', 'unique:program_studis,kode'],
        ]);

        ProgramStudi::create($data);

        return redirect()->route('admin.program-studi.index')
            ->with('success', 'Program studi berhasil ditambahkan.');
    }

    // Form ubah
    public function edit(ProgramStudi $programStudi)
    {
        return view('admin.program_studi.edit', compact('programStudi'));
    }

    // Simpan perubahan
    public function update(Request $request, ProgramStudi $programStudi)
    {
        $request->merge(['kode' => strtoupper((string) $request->input('kode'))]);

        $data = $request->validate([
            'nama' => ['required', 'string', 'max:255', Rule::unique('program_studis', 'nama')->ignore($programStudi->id)],
            'kode' => ['required', 'string', 'max:20', Rule::unique('program_studis', 'kode')->ignore($programStudi->id)],
        ]);

        $programStudi->update($data);

        return redirect()->route('admin.program-studi.index')
            ->with('success', 'Program studi berhasil diperbarui.');
    }

    // Hapus
    public function destroy(ProgramStudi $programStudi)
    {
        if ($programStudi->mahasiswas()->exists()) {
            return back()->with('error', 'Program studi tidak dapat dihapus karena masih dipakai mahasiswa.');
        }

        $programStudi->delete();

        return redirect()->route('admin.program-studi.index')
            ->with('success', 'Program studi berhasil dihapus.');
    }
}