<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\UsersModel;

class AuthController extends Controller
{
    public function login()
    {
        if (Auth::check()) {
            return redirect('/');
        }
        return view('auth.login');
    }

    public function postlogin(Request $request)
    {
        $credentials = $request->only('username', 'password');

        if (Auth::attempt($credentials)) {
            // Ambil user yang login, plus relasi status
            $user = Auth::user()->load('mahasiswa', 'dosen', 'admin');

            // Cek status aktif sesuai role
            $role = strtolower($user->role);
            $isActive = true;

            switch ($role) {
                case 'mahasiswa':
                    $isActive = $user->mahasiswa && strcasecmp($user->mahasiswa->status, 'Aktif') === 0;
                    break;
                case 'dosen':
                    $isActive = $user->dosen && strcasecmp($user->dosen->status, 'Aktif') === 0;
                    break;
                case 'admin':
                    $isActive = $user->admin && strcasecmp($user->admin->status, 'Aktif') === 0;
                    break;
            }

            if (!$isActive) {
                Auth::logout();

                $message = 'Akun Anda tidak aktif. Silakan hubungi administrator.';

                if ($request->ajax() || $request->wantsJson()) {
                    return response()->json([
                        'status' => false,
                        'message' => $message,
                    ]);
                }

                return redirect('login')->withErrors(['message' => $message]);
            }

            // Jika aktif, proses login sukses
            if ($request->ajax() || $request->wantsJson()) {
                // Redirect sesuai role
                $redirectUrl = match ($role) {
                    'admin' => route('admin.dashboard'),
                    'dosen' => route('dosen.dashboard'),
                    'mahasiswa' => route('mahasiswa.dashboard'),
                    default => url('/'),
                };

                return response()->json([
                    'status' => true,
                    'message' => 'Login Berhasil',
                    'redirect' => $redirectUrl,
                ]);
            }

        }

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'status' => false,
                'message' => 'Username atau Password salah!'
            ]);
        }

        return redirect('login')->withErrors(['message' => 'Username atau Password salah!']);
    }

    public function postregister(Request $request)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $request->validate([
                'username' => 'required|unique:t_users,username',
                'password' => 'required|min:6',
                'phrase' => 'required',
                'role' => 'required'
            ]);

            $user = new UsersModel([
                'username' => $request->username,
                'password' => $request->password,
                'phrase' => $request->phrase,
                'role' => $request->role
            ]);

            $user->save();

            Auth::login($user);
            Auth::user()->load('mahasiswa', 'dosen', 'admin');

            return response()->json([
                'status' => true,
                'message' => 'Register Berhasil',
                'redirect' => url('/')
            ]);
        }

        return redirect('register');
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('login');
    }
}
