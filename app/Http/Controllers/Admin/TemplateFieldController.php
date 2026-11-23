<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TemplateField;
use App\Models\TemplateSurat;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class TemplateFieldController extends Controller
{
    // Placeholder yang diisi oleh sistem (SuratRenderer), bukan oleh mahasiswa
    public const PLACEHOLDER_SISTEM = [
        'nomor_surat'    => 'nomor surat (dibuat otomatis saat dikirim ke kajur)',
        'tanggal_surat'  => 'tanggal surat',
        'daftar_anggota' => 'tabel nama dan NIM anggota kelompok',
        'nama_kajur'     => 'nama ketua jurusan',
        'nip_kajur'      => 'NIP ketua jurusan',
        'ttd_kajur'      => 'gambar tanda tangan (tampil setelah ditandatangani)',
    ];

    public const TIPE = ['text', 'textarea', 'date', 'number'];

    // Tambah satu field
    public function store(Request $request, TemplateSurat $template)
    {
        $request->merge(['key' => strtolower(trim((string) $request->input('key')))]);

        $data = $request->validate([
            'key' => [
                'required', 'max:50', 'regex:/^[a-z][a-z0-9_]*$/',
                Rule::notIn(array_keys(self::PLACEHOLDER_SISTEM)),
                Rule::unique('template_fields', 'key')->where('template_surat_id', $template->id),
            ],
            'label' => ['required', 'string', 'max:255'],
            'tipe'  => ['required', Rule::in(self::TIPE)],
        ]);

        $data['is_required'] = $request->boolean('is_required');
        $data['urutan'] = (TemplateField::where('template_surat_id', $template->id)->max('urutan') ?? 0) + 1;

        $template->fields()->create($data);

        return back()->with('success', 'Field berhasil ditambahkan.');
    }

    // Simpan perubahan semua field sekaligus (label, tipe, wajib, urutan)
    public function update(Request $request, TemplateSurat $template)
    {
        $request->validate([
            'fields'          => ['required', 'array'],
            'fields.*.label'  => ['required', 'string', 'max:255'],
            'fields.*.tipe'   => ['required', Rule::in(self::TIPE)],
            'fields.*.urutan' => ['required', 'integer', 'min:1', 'max:999'],
        ]);

        foreach ($template->fields as $field) {
            $input = $request->input("fields.{$field->id}");

            if (! $input) {
                continue;
            }

            $field->update([
                'label'       => $input['label'],
                'tipe'        => $input['tipe'],
                'urutan'      => $input['urutan'],
                'is_required' => isset($input['is_required']),
            ]);
        }

        return back()->with('success', 'Perubahan field berhasil disimpan.');
    }

    // Hapus satu field
    public function destroy(TemplateSurat $template, TemplateField $field)
    {
        abort_if($field->template_surat_id !== $template->id, 404);

        $field->delete();

        return back()->with('success', 'Field berhasil dihapus.');
    }
}