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
            [Operator\DashboardController::class, 'pengajuan']
        )->name('pengajuan.index');

        Route::get('/verifikasi',
            [Operator\DashboardController::class, 'verifikasi']
        )->name('verifikasi.index');

        Route::get('/pengajuan/{pengajuan}',
            [Operator\DashboardController::class, 'detail']
        )->name('pengajuan.show');

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

});

// ===== SUPER ADMIN =====
Route::middleware(['auth', 'role:super_admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [Admin\DashboardController::class, 'index'])->name('dashboard');
    Route::get('/users', [Admin\UserController::class, 'index'])->name('users.index');
    Route::get('/program-studi', [Admin\ProgramStudiController::class, 'index'])->name('program-studi.index');
    Route::get('/jenis-surat', [Admin\JenisSuratController::class, 'index'])->name('jenis-surat.index');
    Route::get('/template', [Admin\TemplateController::class, 'index'])->name('template.index');
    Route::get('/pengaturan', [Admin\PengaturanController::class, 'edit'])->name('pengaturan.edit');
    Route::get('/log', [Admin\LogController::class, 'index'])->name('log.index');
});