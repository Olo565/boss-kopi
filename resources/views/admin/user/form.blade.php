@extends('layouts.app')
@php($user = $user ?? null)
@section('title', ($user ? 'Edit' : 'Tambah') . ' Pengguna — BOSS KOPI')
@section('page-title', $user ? 'Edit Pengguna' : 'Tambah Pengguna Baru')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-7">
        <div class="card">
            <div class="card-header">
                <i class="fa fa-user me-2"></i>
                {{ $user ? 'Edit: ' . $user?->name : 'Form Pengguna Baru' }}
            </div>
            <div class="card-body">
                <form action="{{ $user ? route('admin.user.update', $user) : route('admin.user.store') }}"
                    method="POST">
                    @csrf
                    @if($user) @method('PUT') @endif

                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                                value="{{ old('name', $user?->name ?? '') }}" required>
                            @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Email <span class="text-danger">*</span></label>
                            <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                                value="{{ old('email', $user?->email ?? '') }}" required>
                            @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Nomor HP <span class="text-danger">*</span></label>
                            <input type="text" name="no_hp" class="form-control @error('no_hp') is-invalid @enderror"
                                value="{{ old('no_hp', $user?->no_hp ?? '') }}" required>
                            @error('no_hp') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Role <span class="text-danger">*</span></label>
                            <select name="role" class="form-select @error('role') is-invalid @enderror">
                                <option value="pembeli" {{ old('role', $user?->role ?? '') === 'pembeli' ? 'selected' : '' }}>Pembeli</option>
                                <option value="kasir" {{ old('role', $user?->role ?? '') === 'kasir' ? 'selected' : '' }}>Kasir</option>
                                <option value="driver" {{ old('role', $user?->role ?? '') === 'driver' ? 'selected' : '' }}>Driver</option>
                                <option value="admin" {{ old('role', $user?->role ?? '') === 'admin' ? 'selected' : '' }}>Admin</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Kata Sandi {{ $user ? '(kosongkan jika tidak diubah)' : '' }} <span class="text-danger">{{ !$user ? '*' : '' }}</span></label>
                            <input type="password" name="password" class="form-control @error('password') is-invalid @enderror"
                                {{ !$user ? 'required' : '' }} placeholder="Minimal 6 karakter">
                            @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">
                                Konfirmasi Kata Sandi
                                @if(!$user)<span class="text-danger">*</span>@endif
                            </label>
                            <input type="password" name="password_confirmation" class="form-control"
                                {{ !$user ? 'required' : '' }}
                                placeholder="{{ $user ? 'Kosongkan jika tidak diubah' : 'Ulangi kata sandi' }}">
                        </div>
                        <div class="col-12">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" name="is_active" id="is_active"
                                    {{ old('is_active', $user?->is_active ?? true) ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_active">Akun aktif</label>
                            </div>
                        </div>
                        <div class="col-12 d-flex gap-2 justify-content-end">
                            <a href="{{ route('admin.user.index') }}" class="btn btn-latte">
                                <i class="fa fa-arrow-left me-1"></i> Batal
                            </a>
                            <button type="submit" class="btn btn-coffee">
                                <i class="fa fa-save me-1"></i>
                                {{ $user ? 'Simpan Perubahan' : 'Tambah Pengguna' }}
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        @if($user)
        <div class="card mt-3">
            <div class="card-header text-danger"><i class="fa fa-key me-2"></i>Reset Kata Sandi</div>
            <div class="card-body">
                <p class="text-muted small mb-3">Gunakan fitur ini jika pengguna lupa kata sandinya.</p>
                <form action="{{ route('admin.user.reset-password', $user) }}" method="POST">
                    @csrf
                    <div class="row g-3">
                        <div class="col-md-6">
                            <input type="password" name="password_baru" class="form-control @error('password_baru') is-invalid @enderror"
                                placeholder="Password baru (min. 6 karakter)" required>
                            @error('password_baru') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-6">
                            <input type="password" name="password_baru_confirmation" class="form-control"
                                placeholder="Konfirmasi password baru" required>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-outline-danger mt-3"
                        onclick="return confirm('Reset password {{ $user->name }}?')">
                        <i class="fa fa-key me-2"></i> Reset Password
                    </button>
                </form>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection
