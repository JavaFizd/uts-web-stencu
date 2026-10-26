<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserRole
{
    /**
     * Handle an incoming request.
     * Melindungi rute di level server berdasarkan role pengguna.
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        if (! $request->user()) {
            return redirect()->route('login');
        }

        // Jika role user saat ini ada di dalam daftar role yang diperbolehkan
        if (in_array($request->user()->role, $roles)) {
            return $next($request);
        }

        // Jika user adalah customer dan mencoba membuka halaman internal kasir/owner
        if ($request->user()->role === 'customer') {
            return redirect()->route('public.menu')
                ->with('error', 'Halaman tersebut khusus untuk Kasir atau Owner. Anda sedang login sebagai akun Pelanggan.');
        }

        // Jika user adalah staff dan mencoba membuka halaman khusus owner (seperti kelola user)
        abort(403, 'Akses Ditolak: Halaman ini hanya boleh diakses oleh Owner (Superadmin). Akun Anda saat ini adalah Staff / Barista.');
    }
}
