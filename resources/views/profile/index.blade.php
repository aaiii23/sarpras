@extends('layouts.app')

@section('title', 'Profil & Pengaturan Akun')
@section('page-title', 'Pengaturan Profil Pengguna')

@section('styles')
<style>
    .profile-page {
        width: min(100%, 800px);
        margin: 0 auto;
    }

    .profile-form {
        padding: 24px;
    }

    .profile-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 0 14px;
    }

    .profile-actions {
        display: flex;
        justify-content: flex-end;
        margin-top: 6px;
    }

    @media (max-width: 600px) {
        .profile-form {
            padding: 18px 16px;
        }

        .profile-grid {
            grid-template-columns: 1fr;
        }

        .profile-actions .btn-primary {
            width: 100%;
            justify-content: center;
        }
    }
</style>
@endsection

@section('content')
<div class="profile-page">
    <!-- Profile Card -->
    <div class="card">
        <div class="card-header">
            <h3><i class="bi bi-person-badge-fill text-blue-600"></i> Informasi Profil Akun</h3>
        </div>

        <form action="{{ route('profile.update') }}" method="POST" class="profile-form">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label class="form-label" for="name">Nama Lengkap <span style="color: #ef4444;">*</span></label>
                <input type="text" name="name" id="name" class="form-control" value="{{ old('name', $user->name) }}" required>
            </div>

            <div class="profile-grid">
                <div class="form-group">
                    <label class="form-label" for="email">Alamat Email <span style="color: #ef4444;">*</span></label>
                    <input type="email" name="email" id="email" class="form-control" value="{{ old('email', $user->email) }}" required>
                </div>
                <div class="form-group">
                    <label class="form-label" for="role_display">Role / Hak Akses</label>
                    <input type="text" id="role_display" class="form-control" value="{{ $user->role === 'admin' ? 'Administrator' : ($user->role === 'guru' ? 'Guru' : 'Siswa / Warga Sekolah') }}" disabled style="background-color: #f1f5f9; font-weight: 600;">
                </div>
            </div>

            <div class="profile-grid">
                <div class="form-group">
                    <label class="form-label" for="nis">{{ $user->role === 'guru' ? 'Nomor Induk Pegawai (NIP Guru)' : ($user->role === 'siswa' ? 'Nomor Induk Siswa (NIS Siswa)' : 'Nomor Identitas (NIS / NIP)') }}</label>
                    <input type="text" name="nis" id="nis" class="form-control" value="{{ old('nis', $user->nis) }}" placeholder="{{ $user->role === 'guru' ? 'Contoh: 198501012010011001 (NIP)' : ($user->role === 'siswa' ? 'Contoh: 12345678 (NIS)' : 'Nomor NIS / NIP') }}">
                </div>
                <div class="form-group">
                    <label class="form-label" for="phone">Nomor HP / WhatsApp</label>
                    <input type="text" name="phone" id="phone" class="form-control" value="{{ old('phone', $user->phone) }}" placeholder="08xxxxxxxxxx">
                </div>
            </div>

            <div class="profile-actions">
                <button type="submit" class="btn-primary">
                    <i class="bi bi-floppy-fill"></i> Simpan Perubahan Profil
                </button>
            </div>
        </form>
    </div>

    <!-- Change Password Card -->
    <div class="card">
        <div class="card-header">
            <h3><i class="bi bi-key-fill text-amber-600"></i> Ganti Kata Sandi</h3>
        </div>

        <form action="{{ route('password.update') }}" method="POST" class="profile-form">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label class="form-label" for="current_password">Kata Sandi Saat Ini <span style="color: #ef4444;">*</span></label>
                <input type="password" name="current_password" id="current_password" class="form-control" placeholder="••••••••" required>
            </div>

            <div class="profile-grid">
                <div class="form-group">
                    <label class="form-label" for="new_password">Kata Sandi Baru <span style="color: #ef4444;">*</span></label>
                    <input type="password" name="password" id="new_password" class="form-control" placeholder="Min. 6 karakter" required>
                </div>
                <div class="form-group">
                    <label class="form-label" for="password_confirmation">Konfirmasi Kata Sandi Baru <span style="color: #ef4444;">*</span></label>
                    <input type="password" name="password_confirmation" id="password_confirmation" class="form-control" placeholder="Ulangi kata sandi baru" required>
                </div>
            </div>

            <div class="profile-actions">
                <button type="submit" class="btn-primary" style="background-color: #d97706;">
                    <i class="bi bi-shield-lock-fill"></i> Perbarui Kata Sandi
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
