<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Mahasiswa;
use App\Http\Controllers\Operator;
use App\Http\Controllers\Kajur;
use App\Http\Controllers\Admin;
use Illuminate\Support\Facades\Route;

// ===== HALAMAN UMUM =====
Route::get('/', function () {
    return auth()->check() ? redirect()->route('dashboard') : redirect()->route('login');
});

// Hanya untuk yang BELUM login
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'create'])->name('login');
    Route::post('/login', [LoginController::class, 'store'])->name('login.store');
    Route::get('/register', [RegisterController::class, 'create'])->name('register');
    Route::post('/register', [RegisterController::class, 'store'])->name('register.store');
});

// Hanya untuk yang SUDAH login
Route::middleware('auth')->group(function () {
    Route::post('/logout', [LoginController::class, 'destroy'])->name('logout');

    // Mengarahkan ke dashboard sesuai role
    Route::get('/dashboard', function () {
        return redirect()->route(auth()->user()->role->dashboardRoute());
    })->name('dashboard');
});

// ===== MAHASISWA =====
Route::middleware(['auth', 'role:mahasiswa'])->prefix('mahasiswa')->name('mahasiswa.')->group(function () {
    Route::get('/dashboard', [Mahasiswa\DashboardController::class, 'index'])->name('dashboard');
    Route::get('/pengajuan', [Mahasiswa\PengajuanController::class, 'index'])->name('pengajuan.index');
    Route::get('/pengajuan', [Mahasiswa\PengajuanController::class, 'index'])->name('pengajuan.index');
    Route::get('/pengajuan/create', [Mahasiswa\PengajuanController::class, 'create'])->name('pengajuan.create');
    Route::post('/pengajuan', [Mahasiswa\PengajuanController::class, 'store'])->name('pengajuan.store');
    Route::get('/pengajuan/{pengajuan}', [Mahasiswa\PengajuanController::class, 'show'])->name('pengajuan.show');
});


 // ===== OPERATOR =====
Route::middleware(['auth', 'role:operator'])
    ->prefix('operator')
    ->name('operator.')
    ->group(function () {

        Route::get('/dashboard',
            [Operator\DashboardController::class, 'index']
        )->name('dashboard');

        Route::get('/pengajuan',
            [Operator\PengajuanController::class, 'index']
        )->name('pengajuan.index');

        Route::get('/verifikasi',
            [Operator\VerifikasiController::class, 'index']
        )->name('verifikasi.index');

        Route::get('/pengajuan/{pengajuan}',
            [Operator\VerifikasiController::class, 'show']
        )->name('pengajuan.show');

        Route::post('/pengajuan/{pengajuan}/terima',
            [Operator\VerifikasiController::class, 'terima']
        )->name('pengajuan.terima');

        Route::post('/pengajuan/{pengajuan}/revisi',
            [Operator\VerifikasiController::class, 'revisi']
        )->name('pengajuan.revisi');

        Route::post('/pengajuan/{pengajuan}/tolak',
            [Operator\VerifikasiController::class, 'tolak']
        )->name('pengajuan.tolak');

        Route::get('/lampiran/{lampiran}/unduh',
            [Operator\VerifikasiController::class, 'unduhLampiran']
        )->name('lampiran.unduh');

        Route::get('/buat-surat',
            [Operator\SuratController::class, 'buat']
        )->name('surat.buat');

        Route::get('/pengajuan/{pengajuan}/surat',
            [Operator\SuratController::class, 'edit']
        )->name('pengajuan.surat.edit');

        Route::post('/pengajuan/{pengajuan}/surat',
            [Operator\SuratController::class, 'simpan']
        )->name('pengajuan.surat.simpan');

        Route::post('/pengajuan/{pengajuan}/surat/kirim',
            [Operator\SuratController::class, 'kirim']
        )->name('pengajuan.surat.kirim');

        Route::get('/surat',
            [Operator\SuratController::class, 'index']
        )->name('surat.index');

        Route::get('/surat/{pengajuan}/cetak',
            [Operator\SuratController::class, 'cetak']
        )->name('surat.cetak');

        Route::post('/surat/{pengajuan}/siap-diambil',
            [Operator\SuratController::class, 'siapDiambil']
        )->name('surat.siap-diambil');

        Route::post('/surat/{pengajuan}/selesai',
            [Operator\SuratController::class, 'selesai']
        )->name('surat.selesai');
    });

// ===== KETUA JURUSAN =====
Route::middleware(['auth', 'role:kajur'])->prefix('kajur')->name('kajur.')->group(function () {
    Route::get('/dashboard', [Kajur\DashboardController::class, 'index'])->name('dashboard');

    Route::get('/ttd', [Kajur\TtdController::class, 'edit'])->name('ttd.edit');
    Route::post('/ttd', [Kajur\TtdController::class, 'update'])->name('ttd.update');

Route::get('/surat', [Kajur\SuratController::class, 'index'])
    ->name('surat.index');

Route::get('/surat/{pengajuan}', [Kajur\SuratController::class, 'show'])
    ->name('surat.show');
Route::post('/surat/{pengajuan}/tandatangani',[Kajur\SuratController::class, 'tandatangani']
)->name('surat.tandatangani');

Route::post('/surat/{pengajuan}/kembalikan',[Kajur\SuratController::class, 'kembalikan']
)->name('surat.kembalikan');

});

// ===== SUPER ADMIN =====
Route::middleware(['auth', 'role:super_admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [Admin\DashboardController::class, 'index'])->name('dashboard');
    
    Route::get('/users', [Admin\UserController::class, 'index'])->name('users.index');
    Route::get('/users/create', [Admin\UserController::class, 'create'])->name('users.create');
    Route::post('/users', [Admin\UserController::class, 'store'])->name('users.store');
    Route::get('/users/{user}/edit', [Admin\UserController::class, 'edit'])->name('users.edit');
    Route::put('/users/{user}', [Admin\UserController::class, 'update'])->name('users.update');
    Route::patch('/users/{user}/toggle', [Admin\UserController::class, 'toggle'])->name('users.toggle');
    
    Route::get('/program-studi', [Admin\ProgramStudiController::class, 'index'])->name('program-studi.index');
    Route::get('/program-studi/create', [Admin\ProgramStudiController::class, 'create'])->name('program-studi.create');
    Route::post('/program-studi', [Admin\ProgramStudiController::class, 'store'])->name('program-studi.store');
    Route::get('/program-studi/{programStudi}/edit', [Admin\ProgramStudiController::class, 'edit'])->name('program-studi.edit');
    Route::put('/program-studi/{programStudi}', [Admin\ProgramStudiController::class, 'update'])->name('program-studi.update');
    Route::delete('/program-studi/{programStudi}', [Admin\ProgramStudiController::class, 'destroy'])->name('program-studi.destroy');

    Route::get('/jenis-surat', [Admin\JenisSuratController::class, 'index'])->name('jenis-surat.index');
    
    Route::get('/template', [Admin\TemplateController::class, 'index'])->name('template.index');
    Route::get('/template/create', [Admin\TemplateController::class, 'create'])->name('template.create');
    Route::post('/template', [Admin\TemplateController::class, 'store'])->name('template.store');
    Route::get('/template/{template}/edit', [Admin\TemplateController::class, 'edit'])->name('template.edit');
    Route::put('/template/{template}', [Admin\TemplateController::class, 'update'])->name('template.update');
    Route::get('/template/{template}/pratinjau', [Admin\TemplateController::class, 'pratinjau'])->name('template.pratinjau');
    Route::post('/template/{template}/fields', [Admin\TemplateFieldController::class, 'store'])->name('template.fields.store');
    Route::put('/template/{template}/fields', [Admin\TemplateFieldController::class, 'update'])->name('template.fields.update');
    Route::delete('/template/{template}/fields/{field}', [Admin\TemplateFieldController::class, 'destroy'])->name('template.fields.destroy');

    Route::get('/jenis-surat/create', [Admin\JenisSuratController::class, 'create'])->name('jenis-surat.create');
    Route::post('/jenis-surat', [Admin\JenisSuratController::class, 'store'])->name('jenis-surat.store');
    Route::get('/jenis-surat/{jenisSurat}/edit', [Admin\JenisSuratController::class, 'edit'])->name('jenis-surat.edit');
    Route::put('/jenis-surat/{jenisSurat}', [Admin\JenisSuratController::class, 'update'])->name('jenis-surat.update');
    Route::patch('/jenis-surat/{jenisSurat}/toggle', [Admin\JenisSuratController::class, 'toggle'])->name('jenis-surat.toggle');
    Route::delete('/jenis-surat/{jenisSurat}', [Admin\JenisSuratController::class, 'destroy'])->name('jenis-surat.destroy');

    Route::get('/template', [Admin\TemplateController::class, 'index'])->name('template.index');
    
    Route::get('/pengaturan', [Admin\PengaturanController::class, 'edit'])->name('pengaturan.edit');
    Route::put('/pengaturan', [Admin\PengaturanController::class, 'update'])->name('pengaturan.update');

    Route::get('/log', [Admin\LogController::class, 'index'])->name('log.index');
});