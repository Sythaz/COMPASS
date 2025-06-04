<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\UsersModel;
use Illuminate\Support\Facades\Validator;

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

    public function lupaPassword()
    {
        return view('password.index'); // Pastikan view ini sesuai
    }

    public function postlupaPassword(Request $request)
    {
        try {
            // Validasi input
            $validator = Validator::make($request->all(), [
                'username' => 'required|string',
                'phrase' => 'required|string',
                'passwordBaru' => 'required|string|min:6',
                'passwordBaru_confirmation' => 'required|string|same:passwordBaru',
            ], [
                'username.required' => 'Username wajib diisi.',
                'phrase.required' => 'Phrase pemulihan wajib diisi.',
                'passwordBaru.required' => 'Password baru wajib diisi.',
                'passwordBaru.min' => 'Password minimal 6 karakter.',
                'passwordBaru_confirmation.required' => 'Konfirmasi password wajib diisi.',
                'passwordBaru_confirmation.same' => 'Konfirmasi password tidak sesuai.',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validasi gagal.',
                    'msgField' => $validator->errors()->toArray()
                ], 422);
            }

            // Cari user berdasarkan username
            $user = UsersModel::where('username', $request->username)->first();

            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'Username tidak ditemukan.',
                    'msgField' => [
                        'username' => ['Username tidak ditemukan.']
                    ]
                ], 422);
            }

            // Validasi phrase pemulihan
            if ($user->phrase !== $request->phrase) {
                return response()->json([
                    'success' => false,
                    'message' => 'Phrase pemulihan tidak sesuai.',
                    'msgField' => [
                        'phrase' => ['Phrase pemulihan tidak sesuai.']
                    ]
                ], 422);
            }

            // Update password
            $user->password = bcrypt($request->passwordBaru);
            $user->save();

            return response()->json([
                'success' => true,
                'message' => 'Password berhasil diubah. Silakan login dengan password baru.',
                'redirect' => route('login'),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan server. Silakan coba lagi.',
            ], 500);
        }
    }
}
