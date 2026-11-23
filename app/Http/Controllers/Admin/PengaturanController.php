<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pengaturan;
use Illuminate\Http\Request;

class PengaturanController extends Controller
{
    // Menampilkan form berisi nilai saat ini
    public function edit()
    {
        $nilai = Pengaturan::pluck('value', 'key');

        return view('admin.pengaturan.edit', compact('nilai'));
    }

    // Menyimpan perubahan
    public function update(Request $request)
    {
        $data = $request->validate([
            'jam_kerja_mulai'        => ['required', 'date_format:H:i'],
            'jam_kerja_selesai'      => ['required', 'date_format:H:i', 'after:jam_kerja_mulai'],
            'estimasi_proses_hari'   => ['required', 'integer', 'min:1', 'max:30'],
            'batas_pengambilan_hari' => ['required', 'integer', 'min:1', 'max:90'],
        ]);

        foreach ($data as $key => $value) {
            Pengaturan::updateOrCreate(['key' => $key], ['value' => $value]);
        }

        return redirect()->route('admin.pengaturan.edit')
            ->with('success', 'Pengaturan berhasil disimpan.');
    }
}