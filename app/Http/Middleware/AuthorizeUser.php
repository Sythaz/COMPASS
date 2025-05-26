<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class AuthorizeUser
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  mixed ...$roles
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        $user = Auth::user();

        if (!$user) {
            abort(403, 'Forbidden. Kamu belum login.');
        }

        $userRole = strtolower($user->role);
        $allowedRoles = array_map('strtolower', $roles);

        if (!in_array($userRole, $allowedRoles)) {
            abort(403, 'Upss.. Anda tidak memiliki izin untuk mengakses halaman ini.');
        }

        // Pastikan relasi sudah ada dan status dicek secara case-insensitive
        switch ($userRole) {
            case 'mahasiswa':
                if (!$user->mahasiswa || strcasecmp($user->mahasiswa->status, 'Aktif') !== 0) {
                    Auth::logout();
                    abort(403, 'Akun Mahasiswa Anda tidak aktif.');
                }
                break;

            case 'dosen':
                if (!$user->dosen || strcasecmp($user->dosen->status, 'Aktif') !== 0) {
                    Auth::logout();
                    abort(403, 'Akun Dosen Anda tidak aktif.');
                }
                break;

            case 'admin':
                if (!$user->admin || strcasecmp($user->admin->status, 'Aktif') !== 0) {
                    Auth::logout();
                    abort(403, 'Akun Admin Anda tidak aktif.');
                }
                break;
        }

        return $next($request);
    }
}
