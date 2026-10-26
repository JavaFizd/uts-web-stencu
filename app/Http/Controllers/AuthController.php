<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    /**
     * Tampilkan form login.
     */
    public function showLogin()
    {
        if (Auth::check()) {
            if (Auth::user()->role === 'customer') {
                return redirect()->route('public.menu');
            }
            return redirect()->route('dashboard');
        }

        return view('auth.login');
    }

    /**
     * Proses autentikasi login pengguna.
     */
    public function login(Request $request)
    {
        // 1. Validasi input email dan password
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ], [
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'password.required' => 'Password wajib diisi.',
        ]);

        // 2. Coba login dengan kredensial
        $remember = $request->boolean('remember');
        if (Auth::attempt($credentials, $remember)) {
            $request->session()->regenerate();

            $user = Auth::user();
            if ($user->role === 'customer') {
                $request->session()->forget('url.intended');
                return redirect()->route('public.menu')
                    ->with('success', 'Selamat datang di Stencu Coffee, ' . $user->name . '!');
            }

            return redirect()->intended(route('dashboard'))
                ->with('success', 'Selamat datang kembali, ' . $user->name . ' (' . ucfirst($user->role) . ')!');
        }

        // 3. Jika gagal, kembalikan dengan pesan error
        return back()->withErrors([
            'email' => 'Email atau password yang Anda masukkan salah.',
        ])->onlyInput('email');
    }

    /**
     * Tampilkan form registrasi pelanggan (customer).
     */
    public function showRegister()
    {
        return view('auth.register');
    }

    /**
     * Proses pendaftaran akun pelanggan baru.
     */
    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'email' => ['required', 'email', 'unique:users,email', 'max:150'],
            'phone' => ['nullable', 'string', 'max:20'],
            'password' => ['required', 'min:6', 'confirmed'],
        ], [
            'name.required' => 'Nama lengkap wajib diisi.',
            'email.required' => 'Email wajib diisi.',
            'email.unique' => 'Email ini sudah terdaftar.',
            'password.required' => 'Password wajib diisi.',
            'password.min' => 'Password minimal 6 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'] ?? null,
            'role' => 'customer',
            'password' => Hash::make($validated['password']),
        ]);

        Auth::login($user);

        return redirect()->route('public.menu')
            ->with('success', 'Akun berhasil dibuat! Selamat menikmati racikan kopi Stencu Coffee.');
    }

    /**
     * Proses logout dan hancurkan session.
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home')
            ->with('success', 'Anda telah berhasil logout.');
    }

    /**
     * Akses Cepat / Switch Peran Langsung Sekali Klik (Mode Pengujian UTS / Dosen).
     */
    public function quickLogin(string $role, Request $request)
    {
        $allowed = [
            'owner' => 'owner@stencu.com',
            'kasir' => 'kasir@stencu.com',
            'staff' => 'kasir@stencu.com',
            'pelanggan' => 'pelanggan@gmail.com',
            'customer' => 'pelanggan@gmail.com',
        ];

        if (!array_key_exists($role, $allowed)) {
            return redirect()->route('home')->withErrors(['error' => 'Peran tidak dikenali.']);
        }

        // Cari user berdasarkan email utama role
        $email = $allowed[$role];
        $user = User::where('email', $email)->first();

        if (!$user) {
            $userRole = in_array($role, ['kasir', 'staff']) ? 'staff' : (in_array($role, ['pelanggan', 'customer']) ? 'customer' : 'owner');
            $user = User::where('role', $userRole)->first();
        }

        if ($user) {
            Auth::login($user);
            $request->session()->regenerate();

            if ($user->role === 'owner') {
                return redirect()->route('dashboard')
                    ->with('success', 'Berhasil masuk sebagai Owner: ' . $user->name . '. Semua modul manajemen aktif.');
            } elseif ($user->role === 'staff') {
                return redirect()->route('sales.index')
                    ->with('success', 'Berhasil masuk sebagai Kasir: ' . $user->name . '. Anda dapat melihat pesanan pelanggan dan membuka POS.');
            } else {
                return redirect()->route('public.menu')
                    ->with('success', 'Berhasil masuk sebagai Pelanggan: ' . $user->name . '. Selamat memesan kopi & makanan!');
            }
        }

        return redirect()->route('login')->withErrors(['error' => 'Akun untuk peran ini belum tersedia di database.']);
    }
}
