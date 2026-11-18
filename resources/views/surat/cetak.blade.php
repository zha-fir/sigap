<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Surat {{ $pengajuan->kode_tiket }}</title>
    <style>
        body { font-family: "Times New Roman", serif; font-size: 12pt; background: #e9ecef; }
        .kertas { width: 210mm; min-height: 297mm; margin: 20px auto; padding: 25mm; background: #fff; }
        .tabel-anggota { border-collapse: collapse; width: 100%; }
        .tabel-anggota th, .tabel-anggota td { border: 1px solid #000; padding: 4px 8px; }
        .aksi { text-align: center; margin: 20px; font-family: sans-serif; }
        @media print {
            body { background: #fff; }
            .kertas { margin: 0; padding: 0; width: auto; }
            .aksi { display: none; }
        }
    </style>
</head>
<body>
    <div class="aksi">
        <button onclick="window.print()">Cetak / Simpan PDF</button>
        <a href="javascript:history.back()">Kembali</a>
    </div>

    <div class="kertas">
        {!! $isiSurat !!}
    </div>
</body>
</html>