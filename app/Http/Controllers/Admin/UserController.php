<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pejabat;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use App\RoleUser;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::query();
        
        if ($request->filled('role')){
            $query->where('role', $request->role);
        }

        if ($request->filled('cari')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->cari . '%')
                ->orWhere('email', 'like', '%' . $request->cari . '%');
            });
        }

        $users = $query->orderBy('name')->paginate(10)->withQueryString();

        return view('admin.users.index', [
            'users' => $users,
            'roles' => RoleUser::cases(),
        ]);
    }
    public function create()
    {
        return view('admin.users.create', [
            'roles' => [RoleUser::Operator, RoleUser::Kajur, RoleUser::SuperAdmin],
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'email', 'max:255', 'unique:users,email'],
            'role'     => ['required', Rule::in(['operator', 'kajur', 'super_admin'])],
            'nip'      => ['nullable', 'string', 'max:30'],
            'password' => ['required', 'min:8', 'confirmed'],
        ]);

        DB::transaction(function () use ($data) {
            $user = User::create([
                'name'     => $data['name'],
                'email'    => $data['email'],
                'password' => $data['password'],
                'role'     => RoleUser::from($data['role']),
            ]);

            // Akun Ketua Jurusan otomatis punya data pejabat
            if ($user->role === RoleUser::Kajur) {
                Pejabat::create([
                    'user_id' => $user->id,
                    'nama'    => $user->name,
                    'nip'     => $data['nip'] ?? null,
                    'jabatan' => 'Ketua Jurusan',
                ]);
            }
        });

        return redirect()->route('admin.users.index')->with('success', 'Pengguna berhasil ditambahkan.');
    }

    public function edit(User $user)
    {
        abort_if($user->role === RoleUser::Mahasiswa, 403, 'Data mahasiswa tidak dapat diubah oleh admin.');

        return view('admin.users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        abort_if($user->role === RoleUser::Mahasiswa, 403, 'Data mahasiswa tidak dapat diubah oleh admin.');

        $data = $request->validate([
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user->id)],
            'nip'      => ['nullable', 'string', 'max:30'],
            'password' => ['nullable', 'min:8', 'confirmed'],
        ]);

        DB::transaction(function () use ($data, $user) {
            $user->name  = $data['name'];
            $user->email = $data['email'];

            // password hanya diganti jika diisi
            if (! empty($data['password'])) {
                $user->password = $data['password'];
            }
            $user->save();

            // Data pejabat ikut diperbarui untuk akun Ketua Jurusan
            if ($user->role === RoleUser::Kajur) {
                Pejabat::updateOrCreate(
                    ['user_id' => $user->id],
                    ['nama' => $user->name, 'nip' => $data['nip'] ?? null]
                );
            }
        });

        return redirect()->route('admin.users.index')->with('success', 'Data pengguna berhasil diperbarui.');
    }

    public function toggle(User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->with('error', 'Anda tidak dapat menonaktifkan akun sendiri.');
        }

        $user->update(['is_active' => ! $user->is_active]);

        // Data pejabat ikut aktif/nonaktif agar surat tidak memakai kajur yang nonaktif
        $user->pejabat?->update(['is_active' => $user->is_active]);

        return back()->with('success', 'Status akun berhasil diperbarui.');
    }

}