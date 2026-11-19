<?php

namespace App\Http\Controllers\Kajur;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class TtdController extends Controller
{
    public function edit()
    {
        $pejabat = auth()->user()->pejabat;

        abort_if(!$pejabat, 404, 'Data pejabat tidak ditemukan.');

        return view('kajur.ttd.edit', compact('pejabat'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'file_ttd' => 'required|image|mimes:png|max:2048'
        ]);

        $pejabat = auth()->user()->pejabat;

        abort_if(!$pejabat, 404, 'Data pejabat tidak ditemukan.');

        $fileLama = $pejabat->file_ttd;

        $fileBaru = $request->file('file_ttd')
            ->store('tanda-tangan', 'public');

        $pejabat->update([
            'file_ttd' => $fileBaru
        ]);

        if ($fileLama) {
            Storage::disk('public')->delete($fileLama);
        }

        return back()->with('success', 'Tanda tangan berhasil disimpan.');
    }
}
