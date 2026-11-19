@csrf

@php $edit = isset($user); @endphp

<div class="mb-3">
    <label for="name" class="form-label">Nama lengkap</label>
    <input type="text" id="name" name="name" value="{{ old('name', $user->name ?? '') }}"
           class="form-control @error('name') is-invalid @enderror" required>
    @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
</div>

<div class="mb-3">
    <label for="email" class="form-label">Email</label>
    <input type="email" id="email" name="email" value="{{ old('email', $user->email ?? '') }}"
           class="form-control @error('email') is-invalid @enderror" required>
    @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
</div>

@if ($edit)
    <div class="mb-3">
        <label class="form-label">Role</label>
        <input type="text" class="form-control" value="{{ $user->role->label() }}" disabled>
        <div class="form-text">Role tidak dapat diubah setelah akun dibuat.</div>
    </div>
@else
    <div class="mb-3">
        <label for="role" class="form-label">Role</label>
        <select id="role" name="role" class="form-select @error('role') is-invalid @enderror" required>
            <option value="">Pilih...</option>
            @foreach ($roles as $role)
                <option value="{{ $role->value }}" @selected(old('role') === $role->value)>
                    {{ $role->label() }}
                </option>
            @endforeach
        </select>
        @error('role') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
@endif

@if (! $edit || $user->role->value === 'kajur')
    <div class="mb-3">
        <label for="nip" class="form-label">NIP <small class="text-muted">(diisi untuk Ketua Jurusan)</small></label>
        <input type="text" id="nip" name="nip" value="{{ old('nip', $user->pejabat->nip ?? '') }}"
               class="form-control @error('nip') is-invalid @enderror">
        @error('nip') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
@endif

<div class="mb-3">
    <label for="password" class="form-label">
        {{ $edit ? 'Password baru (kosongkan jika tidak diubah)' : 'Password' }}
    </label>
    <div class="input-group has-validation">
        <input type="password" id="password" name="password"
            class="form-control @error('password') is-invalid @enderror" {{ $edit ? '' : 'required' }}>
            <button type="button" class="btn btn-outline-secondary"
                    data-toggle-password="#password" aria-label="Tampilkan password">
                <i class="bi bi-eye"></i>
            </button>
        @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
</div>

<div class="mb-3">
    <label for="password_confirmation" class="form-label">Konfirmasi password</label>
    <div class="input-group has-validation">
        <input type="password" id="password_confirmation" name="password_confirmation" class="form-control">
        <button type="button" class="btn btn-outline-secondary"
                data-toggle-password="#password_confirmation" aria-label="Tampilkan konfirmasi password">
            <i class="bi bi-eye"></i>
        </button>
    </div>
    
</div>

<button class="btn btn-primary">Simpan</button>
<a href="{{ route('admin.users.index') }}" class="btn btn-danger">Batal</a>