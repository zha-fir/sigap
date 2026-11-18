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
    // tambah route fitur mahasiswa di sini
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
    // tambah route fitur ketua jurusan di sini
});

// ===== SUPER ADMIN =====
Route::middleware(['auth', 'role:super_admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [Admin\DashboardController::class, 'index'])->name('dashboard');
    // tambah route fitur super admin di sini
});