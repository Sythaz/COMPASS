<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AdminModel;
use App\Models\UsersModel;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class ProfileAdminController extends Controller
{
    public function index()
    {
        $breadcrumb = (object) [
            'title' => 'Profil Admin',
            'list'  => ['Home', 'Profil Admin']
        ];

        $page = (object) [
            'title' => 'Profil Saya'
        ];

        $activeMenu = 'profil';

        $admin = AdminModel::with('users')
            ->where('user_id', Auth::user()->user_id)
            ->firstOrFail();

        return view('admin.profile-admin.index', compact('breadcrumb', 'page', 'activeMenu', 'admin'));
    }

    public function update(Request $request)
    {
        try {
            $admin = AdminModel::where('user_id', Auth::user()->user_id)->firstOrFail();

            $request->validate([
                'nama_admin'    => 'required|string|max:100',
                'alamat'        => 'required',
                'email'         => 'required|email|unique:t_admin,email,' . $admin->admin_id . ',admin_id',
                'no_hp'         => 'required|numeric|unique:t_admin,no_hp,' . $admin->admin_id . ',admin_id',
                'kelamin'       => 'required|in:L,P',
                'img_profile'     => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
                'username'      => 'nullable|int|unique:t_users,username,' . $admin->user_id . ',user_id',
            ]);

            // Delete old image if new image is uploaded
            if ($request->hasFile('img_profile') && $admin->img_admin) {
                Storage::delete('public/storage/img/profile/' . $admin->img_admin);
            }

            $admin->nama_admin     = $request->nama_admin;
            $admin->alamat         = $request->alamat;
            $admin->email          = $request->email;
            $admin->no_hp          = $request->no_hp;
            $admin->kelamin        = $request->kelamin;

            if ($request->hasFile('img_profile')) {
                $file = $request->file('img_profile');
                $filename = $admin->admin_id . '_' . time() . '.' . $file->getClientOriginalExtension();
                $path = $file->storeAs('public/img/profile', $filename);

                // Update kolom img_admin di database
                $admin->img_admin = $filename;
            }

            $admin->save();

            // Jika request username ada maka update
            if ($request->filled('username')) {
                $admin->users()->update(['username' => $request->username]);
            }

            return response()->json([
                'success' => 'true',
                'message' => 'Profil Anda Berhasil Diperbarui'
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Data yang Anda masukkan tidak valid',
                'msgField' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menyimpan data.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function cekUsername(Request $request)
    {
        $request->validate([
            'username' => 'required|string|max:255'
        ]);

        $username = $request->input('username');
        $exists = UsersModel::where('username', $username)->exists();

        // Harus balikin string "true" atau "false", bukan JSON
        return response($exists ? 'false' : 'true');
    }

    public function changePassword(Request $request)
    {
        try {
            // Validasi input
            $validator = Validator::make($request->all(), [
                'username' => 'required|string',
                'phrase' => 'required|string',
                'passwordBaru' => 'required|string|min:6',
                'passwordBaru_confirmation' => 'required|string|same:passwordBaru',
                'phraseBaru' => 'nullable|string',
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
                    'msgField' => ['username' => ['Username tidak ditemukan.']]
                ], 422);
            }

            // Validasi phrase pemulihan
            if ($user->phrase !== $request->phrase) {
                return response()->json([
                    'success' => false,
                    'message' => 'Phrase pemulihan tidak sesuai.',
                    'msgField' => ['phrase' => ['Phrase pemulihan tidak sesuai.']]
                ], 422);
            }

            // Update password
            $user->password = Hash::make($request->passwordBaru);

            // Update phrase jika ada phrase baru
            if ($request->filled('phraseBaru')) {
                $user->phrase = $request->phraseBaru;
            }

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
