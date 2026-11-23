<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\JenisSurat;
use App\Models\TemplateSurat;
use Illuminate\Http\Request;

class TemplateController extends Controller
{
    // Daftar template
    public function index()
    {
        $templates = TemplateSurat::with('jenisSurat')
            ->withCount(['fields', 'pengajuans'])
            ->orderBy('nama')
            ->paginate(10);

        return view('admin.template.index', compact('templates'));
    }

    // Form tambah
    public function create()
    {
        return view('admin.template.create', [
            'jenisSurats' => JenisSurat::orderBy('nama')->get(),
        ]);
    }

    // Simpan template baru
    public function store(Request $request)
    {
        $data = $request->validate([
            'jenis_surat_id' => ['required', 'exists:jenis_surats,id'],
            'nama'           => ['required', 'string', 'max:255'],
            'isi_template'   => ['required', 'string'],
            'format_nomor'   => ['required', 'string', 'max:255', $this->aturanFormatNomor()],
        ]);

        $data['is_active'] = $request->boolean('is_active');

        $template = TemplateSurat::create($data);

        return redirect()->route('admin.template.edit', $template)
            ->with('success', 'Template dibuat. Sekarang tambahkan field isian.');
    }

    // Form ubah + kelola field
    public function edit(TemplateSurat $template)
    {
        $template->load('fields');

        return view('admin.template.edit', [
            'template'          => $template,
            'placeholderSistem' => TemplateFieldController::PLACEHOLDER_SISTEM,
            'tipeField'         => TemplateFieldController::TIPE,
        ]);
    }

    // Simpan perubahan template (jenis surat tidak dapat diubah)
    public function update(Request $request, TemplateSurat $template)
    {
        $data = $request->validate([
            'nama'         => ['required', 'string', 'max:255'],
            'isi_template' => ['required', 'string'],
            'format_nomor' => ['required', 'string', 'max:255', $this->aturanFormatNomor()],
        ]);

        $data['is_active'] = $request->boolean('is_active');

        $template->update($data);

        return redirect()->route('admin.template.edit', $template)
            ->with('success', 'Template berhasil diperbarui.');
    }

    // Pratinjau dengan data contoh
    public function pratinjau(TemplateSurat $template)
    {
        $template->load('fields', 'jenisSurat');

        $ganti = [];
        foreach ($template->fields as $field) {
            $contoh = match ($field->tipe) {
                'date'   => now()->addDays(7)->translatedFormat('d F Y'),
                'number' => '10',
                default  => '(contoh ' . $field->label . ')',
            };
            $ganti['[[' . $field->key . ']]'] = e($contoh);
        }

        $nomor = strtr($template->format_nomor, [
            '{urutan}'       => '001',
            '{kode}'         => $template->jenisSurat->kode,
            '{bulan_romawi}' => 'XI',
            '{tahun}'        => (string) now()->year,
        ]);

        $anggota = '<table class="table table-bordered table-sm"><tr><th>No</th><th>Nama</th><th>NIM</th></tr>'
            . '<tr><td>1</td><td>Mahasiswa Contoh Satu</td><td>000000001</td></tr>'
            . '<tr><td>2</td><td>Mahasiswa Contoh Dua</td><td>000000002</td></tr></table>';

        $ganti += [
            '[[nomor_surat]]'    => e($nomor),
            '[[tanggal_surat]]'  => now()->translatedFormat('d F Y'),
            '[[daftar_anggota]]' => $anggota,
            '[[nama_kajur]]'     => '(Nama Ketua Jurusan)',
            '[[nip_kajur]]'      => '(NIP)',
            '[[ttd_kajur]]'      => '<br><br><em>[tanda tangan]</em><br>',
        ];

        $isi = strtr($template->isi_template, $ganti);

        // placeholder yang tidak dikenali (biasanya salah ketik)
        preg_match_all('/\[\[(.*?)\]\]/', $isi, $cocok);
        $tidakDikenal = array_unique($cocok[1]);

        return view('admin.template.pratinjau', compact('template', 'isi', 'tidakDikenal'));
    }

    // Format nomor wajib memuat {urutan} agar nomor surat tidak kembar
    private function aturanFormatNomor(): \Closure
    {
        return function ($atribut, $nilai, $gagal) {
            if (! str_contains($nilai, '{urutan}')) {
                $gagal('Format nomor harus memuat {urutan}.');
            }
        };
    }
}