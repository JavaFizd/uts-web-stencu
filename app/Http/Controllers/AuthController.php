<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    /**
     * Tampilkan form login (Selalu tampilkan form bersih tanpa auto-redirect agar bebas ganti akun).
     */
    public function showLogin(Request $request)
    {
        // Jika pengguna sudah dalam keadaan login tetapi sengaja membuka URL /login,
        // reset sesi lama agar selalu menampilkan form login bersih untuk memilih akun baru
        if (Auth::check()) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
        }

        return view('auth.login');
    }

    /**
     * Proses autentikasi login pengguna (Bisa pakai Akun Perusahaan "staff@stencu.com", Username "admin", "kasir", "dapur" atau Email).
     */
    public function login(Request $request)
    {
        $input = $request->input('login') ?? $request->input('email');
        if (empty($input)) {
            return back()->withErrors(['login' => 'Email atau Username wajib diisi.'])->withInput();
        }

        $request->validate([
            'password' => ['required', 'string'],
        ], [
            'password.required' => 'Password wajib diisi.',
        ]);

        $loginInput = strtolower(trim($input));
        $password = $request->password;
        $requestedStation = strtolower(trim((string) $request->input('station', '')));

        // Cari akun berdasarkan email resmi perusahaan atau username fleksibel
        $user = null;
        if (in_array($loginInput, ['admin', 'owner', 'admin@stencu.com', 'owner@stencu.com'])) {
            $user = User::where('role', 'owner')->first();
        } elseif (in_array($loginInput, ['staff', 'staff@stencu.com', 'crew', 'operasional', 'karyawan'])) {
            // Akun Perusahaan Utama untuk Staf Operasional
            $user = User::where('email', 'staff@stencu.com')->first() 
                ?? User::where('email', 'kasir@stencu.com')->first() 
                ?? User::where('role', 'staff')->first();
        } elseif (in_array($loginInput, ['kasir', 'cashier', 'kasir@stencu.com'])) {
            $user = User::where('email', 'staff@stencu.com')->first() 
                ?? User::where('email', 'kasir@stencu.com')->first() 
                ?? User::where('role', 'staff')->first();
            if (empty($requestedStation)) {
                $requestedStation = 'kasir';
            }
        } elseif (in_array($loginInput, ['dapur', 'barista', 'kitchen', 'dapur@stencu.com', 'barista@stencu.com'])) {
            $user = User::where('email', 'staff@stencu.com')->first() 
                ?? User::where('email', 'dapur@stencu.com')->first() 
                ?? User::where('role', 'staff')->first();
            if (empty($requestedStation)) {
                $requestedStation = 'dapur';
            }
        } else {
            $user = User::where('email', $loginInput)->first();
        }

        // Autentikasi fleksibel: cocokkan hash password atau shortcut demo
        if ($user && (Hash::check($password, $user->password) || in_array($password, ['password', 'admin', 'kasir', 'dapur', 'barista', '123456']))) {
            $remember = $request->boolean('remember');
            Auth::login($user, $remember);
            $request->session()->regenerate();

            if ($user->role === 'customer') {
                $request->session()->forget('url.intended');
                return redirect()->route('public.menu')
                    ->with('success', 'Selamat datang di Stencu Coffee, ' . $user->name . '!');
            }

            if ($user->role === 'staff') {
                $request->session()->forget('url.intended');

                // Tentukan stasiun kerja (Job Desk) yang dipilih oleh staf
                $station = 'kasir';
                if (in_array($requestedStation, ['dapur', 'barista', 'kitchen']) || in_array($loginInput, ['dapur', 'barista', 'dapur@stencu.com'])) {
                    $station = 'dapur';
                }

                $request->session()->put('staff_station', $station);

                if ($station === 'dapur') {
                    return redirect()->route('sales.index')
                        ->with('success', 'Selamat bertugas di Stasiun Dapur & Barista! Antrean pesanan siap diracik.');
                }

                return redirect()->route('sales.create')
                    ->with('success', 'Selamat bertugas di Stasiun Kasir POS! Layar siap melayani transaksi.');
            }

            return redirect()->intended(route('dashboard'))
                ->with('success', 'Selamat datang kembali, Owner.');
        }

        return back()->withErrors([
            'login' => 'Email atau password yang Anda masukkan salah.',
            'email' => 'Email atau password yang Anda masukkan salah.',
        ])->withInput();
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

        return redirect()->route('login')
            ->with('success', 'Anda telah berhasil logout.');
    }

    /**
     * Akses Cepat / Switch Peran Langsung Sekali Klik (Mode Pengujian UTS / Dosen).
     */
    public function quickLogin(string $role, Request $request)
    {
        $role = strtolower(trim($role));
        $user = null;
        $targetStation = 'kasir';

        if (in_array($role, ['owner', 'admin'])) {
            $user = User::where('role', 'owner')->first();
        } elseif (in_array($role, ['kasir', 'cashier'])) {
            $user = User::where('email', 'staff@stencu.com')->first() 
                ?? User::where('email', 'kasir@stencu.com')->first() 
                ?? User::where('role', 'staff')->first();
            $targetStation = 'kasir';
        } elseif (in_array($role, ['dapur', 'barista', 'kitchen'])) {
            $user = User::where('email', 'staff@stencu.com')->first() 
                ?? User::where('email', 'dapur@stencu.com')->first() 
                ?? User::where('role', 'staff')->first();
            $targetStation = 'dapur';
        } elseif ($role === 'staff') {
            $user = User::where('email', 'staff@stencu.com')->first() 
                ?? User::where('email', 'kasir@stencu.com')->first() 
                ?? User::where('role', 'staff')->first();
            $targetStation = 'kasir';
        } else {
            $user = User::where('role', 'customer')->first();
        }

        if ($user) {
            Auth::login($user);
            $request->session()->regenerate();

            if ($user->role === 'owner') {
                return redirect()->route('dashboard')
                    ->with('success', 'Berhasil masuk sebagai Owner.');
            } elseif ($user->role === 'staff') {
                $request->session()->put('staff_station', $targetStation);
                if ($targetStation === 'dapur') {
                    return redirect()->route('sales.index')
                        ->with('success', 'Berhasil masuk sebagai Staf di Stasiun Dapur & Barista. Antrean pesanan siap diracik.');
                }
                return redirect()->route('sales.create')
                    ->with('success', 'Berhasil masuk sebagai Staf di Stasiun Kasir POS. Layar Kasir POS aktif.');
            } else {
                return redirect()->route('public.menu')
                    ->with('success', 'Berhasil masuk sebagai Pelanggan.');
            }
        }

        return redirect()->route('login')->withErrors(['error' => 'Akun peran ini belum tersedia.']);
    }

    /**
     * Beralih Stasiun Kerja Staf secara Cepat (Kasir <-> Dapur & Barista) tanpa logout.
     */
    public function switchStation(string $station, Request $request)
    {
        $station = in_array(strtolower(trim($station)), ['dapur', 'barista', 'kitchen']) ? 'dapur' : 'kasir';
        $request->session()->put('staff_station', $station);

        if ($station === 'dapur') {
            return redirect()->route('sales.index')
                ->with('success', 'Berhasil beralih ke Stasiun Dapur & Barista. Monitor antrean pesanan aktif.');
        }

        return redirect()->route('sales.create')
            ->with('success', 'Berhasil beralih ke Stasiun Kasir POS. Layar siap melayani transaksi.');
    }
}
