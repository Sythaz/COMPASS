<?php

namespace App\Http\Controllers\Dosen;

use App\Http\Controllers\Controller;
use App\Models\KategoriModel;
use App\Models\DosenModel;
use App\Models\PreferensiDosenModel;
use App\Models\UsersModel;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class ProfileDosenController extends Controller
{
    public function index()
    {
        $breadcrumb = (object) [
            'title' => 'Profil Dosen',
            'list'  => ['Home', 'Profil Dosen']
        ];

        $page = (object) [
            'title' => 'Profil Saya'
        ];

        $activeMenu = 'profil';

        $dosen = DosenModel::with('users')
            ->where('user_id', Auth::user()->user_id)
            ->firstOrFail();

        $daftarKategori = KategoriModel::all();
// UPDATE: 39 - 44 //
        $preferensi = PreferensiDosenModel::where('dosen_id', $dosen->dosen_id)->exists();

        $dataPreferensiBidang = PreferensiDosenModel::where('dosen_id', $dosen->dosen_id)
            ->orderBy('prioritas')
            ->with('kategori')
            ->get();

        return view('dosen.profile-dosen.index', compact(
            'breadcrumb', 
            'daftarKategori',
            'preferensi',
            'dataPreferensiBidang',
            'page', 
            'activeMenu', 
            'dosen'
        ));
    }

    public function kategori()
    {
        return $this->belongsTo(KategoriModel::class, 'kategori_id');
    }

    public function update(Request $request)
    {
        try {
            $dosen = DosenModel::where('user_id', Auth::user()->user_id)->firstOrFail();

            // Validasi - perhatikan penyesuaian 'img_profile'
            $request->validate([
                'nip_dosen'         => 'required',
                'nama_dosen'        => 'required|string|max:100',
                'alamat'            => 'required',
                'email'             => 'required|email|unique:t_dosen,email,' . $dosen->dosen_id . ',dosen_id',
                'no_hp'             => 'required|numeric|unique:t_dosen,no_hp,' . $dosen->dosen_id . ',dosen_id',
                'kelamin'           => 'required|in:L,P',
                'img_profile'       => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
                'username'          => 'nullable|int|unique:t_users,username,' . $dosen->user_id . ',user_id',
            ]);

            // Update data dosen
            $dosen->fill($request->only([
                'nip_dosen',
                'nama_dosen',
                'alamat',
                'email',
                'no_hp',
                'kelamin',
            ]));

            // upload foto jika ada 
            if ($request->hasFile('img_profile')) {
                $file = $request->file('img_profile');
                $filename = $dosen->dosen_id . '_' . time() . '.' . $file->getClientOriginalExtension();
                $path = $file->storeAs('public/img/profile', $filename);

                // // Update kolom img_dosen di database
                // $dosen->img_dosen = $filename;

                // Hapus foto lama jika ada
                if ($dosen->img_dosen && Storage::exists('public/img/profile' . $dosen->img_dosen)) {
                    Storage::delete('public/img/profile/' . $dosen->img_dosen);
                }

                $dosen->img_dosen = $filename;
            }

            //simpan perubahan
            $dosen->save();

            // update username jika diisi
            if ($request->filled('username')) {
                $dosen->users()->update(['username' => $request->username]);
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

        // harus mengembalikan string 'true' atau 'false', bukan JSON 
        return response($exists ? 'false' : 'true');
    }

    public function storePreferensi(Request $request)
    {
        try {
            // validtion rules 
            $rules = [
                'bidang1_id' => 'required|exists:t_kategori,kategori_id',
                'bidang2_id' => 'nullable|exists:t_kategori,kategori_id',
                'bidang3_id' => 'nullable|exists:t_kategori,kategori_id',
                'bidang4_id' => 'nullable|exists:t_kategori,kategori_id',
                'bidang5_id' => 'nullable|exists:t_kategori,kategori_id',
            ];

            $request->validate($rules);
            $dosen = DosenModel::where('user_id', $user->user_id)->firstOrFail();
            $user = Auth::user();
            // Hapus preferensi lama jika ada
            PreferensiDosenModel::where('dosen_id', $dosen->dosen_id)->delete();
           
            $preferensiData = [];

            // memasukkan preferensi bidang ke array untuk insert
            for ($i = 1; $i <= 5; $i++) {
                $kategoriId = $request->input("bidang{$i}_id");
                if ($kategoriId) {
                    //$kategori = DB::table('t_kategori')->where('kategori_id', $bidangId)->first();
                    $preferensiData[] = [
                        'dosen_id'    => $dosen->dosen_id,
                        'kategori_id' => $kategoriId,
                        'prioritas'   => $i,
                        'created_at'  => now(),
                        'updated_at'  => now(),
                    ];
                }
            }

            // insert data array preferensi user ke database 
            DB::table('t_preferensi_dosen')->insert($preferensiData);

            return response()->json([
                'success'   => true,
                'message'   => 'Preferensi berhasil disimpan.',
                'redirect'  => route('dosen.profile.index')
            ], 200);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Data tidak valid.',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menyimpan preferensi.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function changePassword(Request $request)
    {
        try {
            // validasi input 
            $validator = Validator::make($request->all(), [
                'username'                  => 'required|string',
                'phrase'                    => 'required|string',
                'passwordBaru'              => 'required|string|min:6',
                'passwordBaru_confirmation' => 'required|string|same:passwordBaru',
                'phraseBaru'                => 'nullable|string',
            ], [
                'username.required'                     => 'Username wajib diisi.',
                'phrase.required'                       => 'Phrase pemulihan wajib diisi.',
                'passwordBaru.required'                 => 'Password baru wajib diisi.',
                'passwordBaru.min'                      => 'Password minimal 6 karakter.',
                'passwordBaru_confirmation.required'    => 'Konfirmasi password wajib diisi.',
                'passwordBaru_confirmation.same'        => 'Konfirmasi password tidak sesuai.',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success'   => false,
                    'message'   => 'Validasi gagal',
                    'msgField'  => $validator->errors()->toArray()
                ], 422);
            }

            // cari user berdasarkan username
            $user = UsersModel::where('username', $request->username)->first();
            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'Username tidak ditemukan.',
                    'msgField'  => ['username' => ['Username tidak ditemukan.']]
                ], 422);
            }

            // validasi phrase pemulihan
            if ($user->phrase !== $request->phrase) {
                return response()->json([
                    'success'   => false,
                    'message'   => 'Phrase pemulihan tidak sesuai.',
                    'msgField' => ['phrase' => ['Phrase pemulihan tidak sesuai.']]
                ], 422);
            }

            // update password
            $user->password = Hash::make($request->passwordBaru);

            // update phrase jika ada phrase baru
            if ($request->filled('phraseBaru')) {
                $user->phrase = $request->phraseBaru;
            }

            $user->save();

            return response()->json([
                'success'   => true,
                'message'   => 'Password berhasil diubah. Silahkan login dengan password baru.',
                'redirect'  => route('login'),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success'   => false,
                'message'   => 'Terjadi kesalahan server. Silahkan coba lagi.'
            ], 500);
        }
    }
}
