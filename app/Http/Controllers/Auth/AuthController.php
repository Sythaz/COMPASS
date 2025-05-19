<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\UsersModel;

class AuthController extends Controller
{
    /**
     * Tampilkan halaman login
     */
    public function login()
    {
        if (Auth::check()) {
            return redirect('/');
        }
        return view('auth.login');
    }

    /**
     * Proses login
     */
    public function postlogin(Request $request)
    {
        $credentials = $request->only('username', 'password');

        if (Auth::attempt($credentials)) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'status' => true,
                    'message' => 'Login Berhasil',
                    'redirect' => url('/')
                ]);
            }

            return redirect('/');
        }

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'status' => false,
                'message' => 'Username atau Password salah!'
            ]);
        }

        return redirect('login')->withErrors(['message' => 'Username atau Password salah!']);
    }

    /**
     * Tampilkan halaman register
     */
    public function register()
    {
        if (Auth::check()) {
            return redirect('/');
        }
        return view('auth.register');
    }

    /**
     * Proses register
     */
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
                'password' => $request->password, // otomatis di-hash
                'phrase' => $request->phrase,
                'role' => $request->role
            ]);

            $user->save();

            // Login otomatis setelah register
            Auth::login($user);

            return response()->json([
                'status' => true,
                'message' => 'Register Berhasil',
                'redirect' => url('/')
            ]);
        }

        return redirect('register');
    }

    /**
     * Logout
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('login');
    }
}
