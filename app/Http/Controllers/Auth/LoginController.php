<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;    

class LoginController extends Controller
{
    public function create()
    {
        return view('auth.login');
    }

    // Memproses form login
    public function store(Request $request)
    {
        // 1. Cek isian form
        $data = $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required'],
        ]);

        // 2. Cocokkan email + password, dan akun harus aktif
        if (! Auth::attempt($data + ['is_active' => true])) {
            return back()->withErrors(['email' => 'Email atau password salah, atau akun tidak aktif.']);
        }

        // 3. Login berhasil: buat session baru, lalu arahkan ke dashboard
        $request->session()->regenerate();

        return redirect()->route('dashboard');
    }

    // Keluar
    public function destroy(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}