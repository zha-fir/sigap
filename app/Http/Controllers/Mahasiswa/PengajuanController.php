<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use App\Models\Mahasiswa;
use App\Models\Pengajuan;
use App\Models\ProgramStudi;
use App\Models\RiwayatStatus;
use App\Models\TemplateSurat;
use App\Services\PengajuanStatusService;
use App\StatusPengajuan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class PengajuanController extends Controller
{
    private function mahasiswa(): Mahasiswa
    {
        $mahasiswa = auth()->user()->mahasiswa;
        abort_if(! $mahasiswa, 403);

        return $mahasiswa;
    }

    private function mahasiswaId(): int
    {
        return (int) $this->mahasiswa()->id;
    }


    public function index(Request $request)
    {
        $statusAktif = StatusPengajuan::tryFrom((string) $request->query('status'));

        $pengajuans = Pengajuan::with(['jenisSurat', 'data'])
            ->where('mahasiswa_id', $this->mahasiswaId())
            ->when($statusAktif, fn ($q) => $q->where('status', $statusAktif->value))
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('mahasiswa.pengajuan.index', [
            'pengajuans' => $pengajuans,
            'statuses' => StatusPengajuan::cases(),
            'statusAktif' => $statusAktif,
        ]);
    }

    public function show(Pengajuan $pengajuan)
    {
        abort_if((int) $pengajuan->mahasiswa_id !== $this->mahasiswaId(), 403);

        $pengajuan->load([
            'jenisSurat',
            'templateSurat.fields',
            'anggotas.programStudi',
            'lampirans',
            'riwayats.user',
        ]);

        return view('mahasiswa.pengajuan.show', compact('pengajuan'));
    }


    public function create(Request $request)
    {
        $mahasiswa = $this->mahasiswa();

        $templates = $this->templateAktif()->orderBy('id')->get();
        $template = $templates->firstWhere('id', (int) $request->query('template')) ?? $templates->first();

        return view('mahasiswa.pengajuan.create', [
            'mahasiswa' => $mahasiswa,
            'templates' => $templates,
            'template' => $template,
            'programStudis' => ProgramStudi::orderBy('nama')->get(),
        ]);
    }

    public function store(Request $request)
    {
        $mahasiswa = $this->mahasiswa();
        $template = $this->templateAktif()->findOrFail($request->input('template_surat_id'));
        $ajukan = $request->input('aksi') === 'ajukan';

        $request->validate(
            $this->aturan($template, $ajukan),
            $this->pesan(),
            $this->atribut($template)
        );
        $anggota = $this->ambilAnggota($request, $mahasiswa);

        $pengajuan = DB::transaction(function () use ($request, $template, $mahasiswa, $anggota, $ajukan) {
            $pengajuan = Pengajuan::create([
                'kode_tiket' => $this->buatKodeTiket(),
                'mahasiswa_id' => $mahasiswa->id,
                'jenis_surat_id' => $template->jenis_surat_id,
                'template_surat_id' => $template->id,
                'status' => StatusPengajuan::Draft,
            ]);

            RiwayatStatus::create([
                'pengajuan_id' => $pengajuan->id,
                'status_dari' => null,
                'status_ke' => StatusPengajuan::Draft->value,
                'user_id' => auth()->id(),
                'catatan' => 'Draft dibuat',
            ]);

            $this->simpanIsi($pengajuan, $template, $request, $mahasiswa, $anggota);

            if ($ajukan) {
                $this->kirim($pengajuan);
            }

            return $pengajuan;
        });

        return redirect()
            ->route('mahasiswa.pengajuan.show', $pengajuan)
            ->with('success', $ajukan ? 'Pengajuan berhasil dikirim ke operator.' : 'Draft berhasil disimpan.');
    }

    private function templateAktif()
    {
        return TemplateSurat::where('is_active', true)
            ->whereHas('jenisSurat', fn ($q) => $q->where('is_active', true))
            ->with(['fields', 'jenisSurat']);
    }


    private function buatKodeTiket(): string
    {
        $awalan = 'PJ-' . now()->format('Ymd') . '-';

        $terakhir = Pengajuan::where('kode_tiket', 'like', $awalan . '%')
            ->lockForUpdate()
            ->orderByDesc('kode_tiket')
            ->value('kode_tiket');

        $nomor = $terakhir ? ((int) Str::afterLast($terakhir, '-')) + 1 : 1;

        return $awalan . str_pad((string) $nomor, 3, '0', STR_PAD_LEFT);
    }

    private function aturan(TemplateSurat $template, bool $ajukan): array
    {
        $aturan = [
            'isian' => ['nullable', 'array'],
            'anggota' => ['nullable', 'array', 'max:20'],
            'anggota.*.nama' => ['nullable', 'string', 'max:255'],
            'anggota.*.nim' => ['nullable', 'string', 'max:20'],
            'anggota.*.program_studi_id' => ['nullable', 'exists:program_studis,id'],
            'lampiran' => ['nullable', 'array', 'max:5'],
            'lampiran.*' => ['file', 'max:2048', 'mimes:pdf,jpg,jpeg,png,doc,docx'],
        ];

        foreach ($template->fields as $f) {
            $dasar = [($ajukan && $f->is_required) ? 'required' : 'nullable'];

            $jenis = match ($f->tipe) {
                'date' => ['date'],
                'textarea' => ['string', 'max:2000'],
                'select' => $f->opsi ? [Rule::in($f->opsi)] : ['string', 'max:255'],
                default => ['string', 'max:255'],
            };

            $aturan["isian.{$f->key}"] = array_merge($dasar, $jenis);
        }

        return $aturan;
    }

    private function pesan(): array
    {
        return [
            'required' => ':attribute wajib diisi.',
            'string' => ':attribute harus berupa teks.',
            'array' => ':attribute tidak valid.',
            'date' => ':attribute harus berupa tanggal yang valid.',
            'in' => ':attribute tidak valid.',
            'exists' => ':attribute tidak valid.',
            'file' => ':attribute harus berupa file.',
            'uploaded' => ':attribute gagal diunggah (ukuran terlalu besar).',
            'mimes' => ':attribute harus berformat: :values.',
            'max.string' => ':attribute maksimal :max karakter.',
            'max.file' => ':attribute maksimal :max KB.',
            'max.array' => ':attribute maksimal :max item.',
        ];
    }

    private function atribut(TemplateSurat $template): array
    {
        $atribut = [
            'anggota.*.nama' => 'Nama anggota',
            'anggota.*.nim' => 'NIM anggota',
            'anggota.*.program_studi_id' => 'Program studi anggota',
            'lampiran' => 'Lampiran',
            'lampiran.*' => 'Lampiran',
        ];

        foreach ($template->fields as $f) {
            $atribut["isian.{$f->key}"] = $f->label;
        }

        return $atribut;
    }

    private function ambilAnggota(Request $request, Mahasiswa $mahasiswa): array
    {
        $baris = [];
        $galat = [];
        $nimTerpakai = [$mahasiswa->nim];

        foreach ((array) $request->input('anggota', []) as $i => $a) {
            $nama = trim((string) ($a['nama'] ?? ''));
            $nim = trim((string) ($a['nim'] ?? ''));

            if ($nama === '' && $nim === '') {
                continue;
            }
            if ($nama === '') {
                $galat["anggota.$i.nama"] = 'Nama anggota wajib diisi.';
            }
            if ($nim === '') {
                $galat["anggota.$i.nim"] = 'NIM anggota wajib diisi.';
            } elseif (in_array($nim, $nimTerpakai, true)) {
                $galat["anggota.$i.nim"] = "NIM $nim sudah dipakai di kelompok ini.";
            }

            $nimTerpakai[] = $nim;
            $baris[] = [
                'nama' => $nama,
                'nim' => $nim,
                'program_studi_id' => $a['program_studi_id'] ?? null,
            ];
        }

        if ($galat) {
            throw ValidationException::withMessages($galat);
        }

        return $baris;
    }

    private function simpanIsi(Pengajuan $pengajuan, TemplateSurat $template, Request $request, Mahasiswa $mahasiswa, array $anggota): void
    {
        $isian = (array) $request->input('isian', []);
        foreach ($template->fields as $f) {
            $pengajuan->data()->updateOrCreate(
                ['key' => $f->key],
                ['value' => $isian[$f->key] ?? null]
            );
        }

        $pengajuan->anggotas()->delete();
        $pengajuan->anggotas()->create([
            'nama' => auth()->user()->name,
            'nim' => $mahasiswa->nim,
            'program_studi_id' => $mahasiswa->program_studi_id,
            'is_ketua' => true,
        ]);
        foreach ($anggota as $a) {
            $pengajuan->anggotas()->create($a + ['is_ketua' => false]);
        }

        foreach ($request->file('lampiran') ?? [] as $file) {
            $pengajuan->lampirans()->create([
                'nama_file' => $file->getClientOriginalName(),
                'path' => $file->store('lampiran', 'public'),
                'mime' => $file->getClientMimeType(),
                'ukuran' => $file->getSize(),
            ]);
        }
    }

    private function kirim(Pengajuan $pengajuan): void
    {
        $pengajuan->update(['diajukan_at' => now()]);

        app(PengajuanStatusService::class)->ubah(
            $pengajuan,
            StatusPengajuan::Diajukan,
            auth()->user()
        );
    }
}