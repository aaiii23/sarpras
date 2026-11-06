<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class AuthController extends Controller
{
    public function showLogin()
    {
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }

        return response()->view('auth.login')
            ->header('Cache-Control', 'no-store, no-cache, must-revalidate')
            ->header('Pragma', 'no-cache');
    }

    public function login(Request $request)
    {
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }

        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ], [
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'password.required' => 'Password wajib diisi.',
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();
            $roleName = Auth::user()->role_label;
            return redirect()->route('dashboard')
                ->with('success', "Selamat datang kembali, {$roleName} (" . Auth::user()->name . ")!");
        }

        return back()->withErrors([
            'email' => 'Akun tidak terdeteksi. Silakan periksa kembali apakah ada kesalahan ketik (typo) pada email/kata sandi, atau buat akun terlebih dahulu jika belum terdaftar.',
        ])->onlyInput('email');
    }

    public function showRegister()
    {
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $role = $request->input('role', 'siswa');
        if (!in_array($role, ['siswa', 'guru'])) {
            $role = 'siswa';
        }

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'role' => ['nullable', 'in:siswa,guru'],
            'nis' => [$role === 'siswa' ? 'required' : 'nullable', 'string', 'max:30', 'unique:users,nis'],
            'email' => ['required', 'string', 'email', 'max:255', 'regex:/^[^@\s]+@gmail\.com$/i', 'unique:users,email'],
            'phone' => ['nullable', 'string', 'max:20'],
            'password' => ['required', 'confirmed', Password::min(6)],
        ], [
            'name.required' => 'Nama lengkap wajib diisi.',
            'nis.required' => 'NIS wajib diisi untuk siswa.',
            'nis.unique' => 'Nomor identitas (NIS/NIP) sudah terdaftar.',
            'email.required' => 'Alamat email wajib diisi.',
            'email.regex' => 'Alamat email harus menggunakan @gmail.com.',
            'email.unique' => 'Alamat email sudah terdaftar.',
            'password.required' => 'Password wajib diisi.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
            'password.min' => 'Password minimal 6 karakter.',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'role' => $role,
            'nis' => $validated['nis'] ?? null,
            'email' => $validated['email'],
            'phone' => $validated['phone'] ?? null,
            'password' => Hash::make($validated['password']),
        ]);

        Auth::login($user);

        return redirect()->route('dashboard')
            ->with('success', "Pendaftaran akun {$user->role_label} berhasil! Selamat datang di SARPRAS.");
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')
            ->with('success', 'Anda telah berhasil keluar dari sistem.');
    }

    public function showProfile()
    {
        return view('profile.index', [
            'user' => Auth::user(),
        ]);
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'regex:/^[^@\s]+@gmail\.com$/i', 'unique:users,email,' . $user->id],
            'phone' => ['nullable', 'string', 'max:20'],
            'nis' => ['nullable', 'string', 'max:20', 'unique:users,nis,' . $user->id],
        ]);

        $user->update($validated);

        return back()->with('success', 'Data profil Anda berhasil diperbarui.');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'confirmed', Password::min(6)],
        ], [
            'current_password.required' => 'Password saat ini wajib diisi.',
            'current_password.current_password' => 'Password saat ini salah.',
            'password.required' => 'Password baru wajib diisi.',
            'password.confirmed' => 'Konfirmasi password baru tidak cocok.',
            'password.min' => 'Password baru minimal 6 karakter.',
        ]);

        Auth::user()->update([
            'password' => Hash::make($request->password),
        ]);

        return back()->with('success', 'Password Anda berhasil diganti.');
    }
}
