<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\RoleUser;
use App\Models\Mahasiswa;
use App\Models\ProgramStudi;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class RegisterController extends Controller
{
    // Menampilkan form registrasi (dengan daftar program studi untuk dropdown)
    public function create()
    {
        $programStudis = ProgramStudi::orderBy('nama')->get();

        return view('auth.register', compact('programStudis'));
    }

    // Memproses registrasi
    public function store(Request $request)
    {
        $data = $request->validate([
            'name'             => ['required', 'string', 'max:255'],
            'email'            => ['required', 'email', 'unique:users,email'],
            'nim'              => ['required', 'string', 'max:20', 'unique:mahasiswas,nim'],
            'program_studi_id' => ['required', 'exists:program_studis,id'],
            'angkatan'         => ['nullable', 'digits:4'],
            'no_hp'            => ['nullable', 'string', 'max:20'],
            'password'         => ['required', 'min:8', 'confirmed'],
        ]);

        // Buat akun + profil mahasiswa sekaligus
        $user = DB::transaction(function() use($data) {
            $user = User::create([
                'name'     => $data['name'],
                'email'    => $data['email'],
                'password' => $data['password'],
                'role'     => RoleUser::Mahasiswa,
            ]);

            Mahasiswa::create([
                'user_id'          => $user->id,
                'nim'              => $data['nim'],
                'program_studi_id' => $data['program_studi_id'],
                'angkatan'         => $data['angkatan'] ?? null,
                'no_hp'            => $data['no_hp'] ?? null,
            ]);

            return $user;
        });

        Auth::login($user);   // langsung login setelah daftar

        return redirect()->route('dashboard');
    }
}