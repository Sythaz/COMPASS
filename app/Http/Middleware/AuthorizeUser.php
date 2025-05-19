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
     * @param  mixed ...$roles  // menerima banyak role: authorize:admin,dosen
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        $user = Auth::user();

        // Jika tidak login
        if (!$user) {
            abort(403, 'Forbidden. Kamu belum login.');
        }

        // Bandingkan role secara case-insensitive
        $userRole = strtolower($user->role);
        $allowedRoles = array_map('strtolower', $roles);

        if (!in_array($userRole, $allowedRoles)) {
            // abort(403, 'Forbidden. Kamu tidak punya akses ke halaman ini.');
            abort(403, 'Jangan Kepo ya Adik-adik.');
        }

        return $next($request);
    }
}
