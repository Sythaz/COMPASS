<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use App\Models\KategoriModel;
use App\Models\MahasiswaModel;
use App\Models\ProdiModel;
use App\Models\PeriodeModel;
use App\Models\PreferensiUserModel;
use App\Models\TingkatLombaModel;
use App\Models\UsersModel;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class ProfileMahasiswaController extends Controller
{
    public function index()
    {
        $breadcrumb = (object) [
            'title' => 'Profil Mahasiswa',
            'list'  => ['Home', 'Profil Mahasiswa']
        ];

        $page = (object) [
            'title' => 'Profil Saya'
        ];

        $preferensi = true;
        if (PreferensiUserModel::where('user_id', auth()->id())->doesntExist()) {
            $preferensi = false;
        }

        $activeMenu = 'profil';

        $mahasiswa = MahasiswaModel::with('users', 'prodi', 'periode')
            ->where('user_id', Auth::user()->user_id)
            ->firstOrFail();

        $prodi   = ProdiModel::all();
        $periode = PeriodeModel::all();

        $daftarKategori = KategoriModel::all();

        $daftarTingkat = TingkatLombaModel::all();
        $dataPreferensi = PreferensiUserModel::where('user_id', auth()->id())
            ->get()
            ->groupBy('kriteria')
            ->mapWithKeys(function ($item, $key) {
                return [$key => $item->keyBy('prioritas')];
            });

        $dataPreferensiBidang = $dataPreferensi['bidang'] ?? collect();
        $dataPreferensiTingkat = $dataPreferensi['tingkat'] ?? collect();
        $dataPreferensiPenyelenggara = $dataPreferensi['penyelenggara'] ?? collect();
        $dataPreferensiLokasi = $dataPreferensi['lokasi'] ?? collect();
        $dataPreferensiBiaya = $dataPreferensi['biaya'] ?? collect();

        return view('mahasiswa.profile-mahasiswa.index', compact(
            'breadcrumb',
            'daftarKategori',
            'daftarTingkat',
            'preferensi',
            'dataPreferensiBidang',
            'dataPreferensiTingkat',
            'dataPreferensiPenyelenggara',
            'dataPreferensiLokasi',
            'dataPreferensiBiaya',
            'page',
            'activeMenu',
            'mahasiswa',
            'prodi',
            'periode'
        ));
    }

    public function update(Request $request)
    {
        try {
            $mahasiswa = MahasiswaModel::where('user_id', Auth::user()->user_id)->firstOrFail();

            // Validasi - perhatikan penyesuaian 'img_profile'
            $request->validate([
                'prodi_id'          => 'required|exists:t_prodi,prodi_id',
                'periode_id'        => 'required|exists:t_periode,periode_id',
                'nim_mahasiswa'     => 'required',
                'nama_mahasiswa'    => 'required|string|max:100',
                'alamat'            => 'required',
                'email'             => 'required|email|unique:t_mahasiswa,email,' . $mahasiswa->mahasiswa_id . ',mahasiswa_id',
                'no_hp'             => 'required|numeric|unique:t_mahasiswa,no_hp,' . $mahasiswa->mahasiswa_id . ',mahasiswa_id',
                'kelamin'           => 'required|in:L,P',
                'img_profile'       => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
                'username'          => 'nullable|string|unique:t_users,username,' . $mahasiswa->user_id . ',user_id',
            ]);

            // Update data mahasiswa
            $mahasiswa->fill($request->only([
                'prodi_id',
                'periode_id',
                'nim_mahasiswa',
                'nama_mahasiswa',
                'alamat',
                'email',
                'no_hp',
                'kelamin',
            ]));

            // Upload foto jika ada
            if ($request->hasFile('img_profile')) {
                $file = $request->file('img_profile');
                $filename = $mahasiswa->mahasiswa_id . '_' . time() . '.' . $file->getClientOriginalExtension();
                $path = $file->storeAs('public/img/profile', $filename);

                // Hapus foto lama jika ada
                if ($mahasiswa->img_mahasiswa && Storage::exists('public/img/profile/' . $mahasiswa->img_mahasiswa)) {
                    Storage::delete('public/img/profile/' . $mahasiswa->img_mahasiswa);
                }

                $mahasiswa->img_mahasiswa = $filename;
            }

            // Simpan perubahan
            $mahasiswa->save();

            // Update username jika diisi
            if ($request->filled('username')) {
                $mahasiswa->users()->update(['username' => $request->username]);
            }

            return response()->json([
                'success' => true,
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
        $exists   = UsersModel::where('username', $username)->exists();

        return response($exists ? 'false' : 'true');
    }

    public function storePreferensi(Request $request)
    {
        try {
            // Validation rules
            $rules = [
                'bidang1_id' => 'required|exists:t_kategori,kategori_id',
                'bidang2_id' => 'nullable|exists:t_kategori,kategori_id',
                'bidang3_id' => 'nullable|exists:t_kategori,kategori_id',
                'bidang4_id' => 'nullable|exists:t_kategori,kategori_id',
                'bidang5_id' => 'nullable|exists:t_kategori,kategori_id',
            ];

            // Validation tingkat lomba
            $daftarTingkatCount = TingkatLombaModel::count();
            for ($i = 1; $i <= $daftarTingkatCount; $i++) {
                $rules["tingkat_lomba{$i}_id"] = 'required|exists:t_tingkat_lomba,tingkat_lomba_id';
            }

            // Validation jenis penyelenggara
            for ($i = 1; $i <= 3; $i++) {
                $rules["jenis_penyelenggara{$i}_id"] = 'required|in:Institusi,Kampus,Komunitas';
            }

            // Validation lokasi
            for ($i = 1; $i <= 4; $i++) {
                $rules["lokasi{$i}_id"] = 'required|in:Offline Dalam Kota,Offline Luar Kota,Online,Hybrid';
            }

            // Validation biaya
            for ($i = 1; $i <= 2; $i++) {
                $rules["biaya{$i}_id"] = 'required|in:Tanpa Biaya,Dengan Biaya';
            }

            $request->validate($rules);

            $user = Auth::user();
            $mahasiswa = $user->mahasiswa;

            // Hapus preferensi lama jika ada
            DB::table('t_preferensi_user')
                ->where('user_id', $user->user_id)
                ->where('mahasiswa_id', $mahasiswa->mahasiswa_id)
                ->delete();

            $preferensiData = [];

            // Memasukkan preferensi bidang ke array untuk insert nanti
            for ($i = 1; $i <= 5; $i++) {
                $bidangId = $request->input("bidang{$i}_id");
                if ($bidangId) {
                    $kategori = DB::table('t_kategori')->where('kategori_id', $bidangId)->first();
                    $preferensiData[] = [
                        'user_id' => $user->user_id,
                        'mahasiswa_id' => $mahasiswa->mahasiswa_id,
                        'kriteria' => 'bidang',
                        'nilai' => $kategori->nama_kategori,
                        'prioritas' => $i,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                }
            }

            // Memasukkan preferensi tingkat ke array untuk insert nanti
            for ($i = 1; $i <= $daftarTingkatCount; $i++) { // sesuaikan dengan jumlah tingkat
                $tingkatId = $request->input("tingkat_lomba{$i}_id");
                if ($tingkatId) {
                    $tingkat = DB::table('t_tingkat_lomba')->where('tingkat_lomba_id', $tingkatId)->first();
                    $preferensiData[] = [
                        'user_id' => $user->user_id,
                        'mahasiswa_id' => $mahasiswa->mahasiswa_id,
                        'kriteria' => 'tingkat',
                        'nilai' => $tingkat->nama_tingkat,
                        'prioritas' => $i,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                }
            }

            // Memasukkan preferensi penyelenggara ke array untuk insert nanti
            for ($i = 1; $i <= 3; $i++) {
                $penyelenggaraValue = $request->input("jenis_penyelenggara{$i}_id");
                if ($penyelenggaraValue) {
                    $preferensiData[] = [
                        'user_id' => $user->user_id,
                        'mahasiswa_id' => $mahasiswa->mahasiswa_id,
                        'kriteria' => 'penyelenggara',
                        'nilai' => $penyelenggaraValue,
                        'prioritas' => $i,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                }
            }

            // Memasukkan preferensi lokasi ke array untuk insert nanti
            for ($i = 1; $i <= 4; $i++) {
                $lokasiValue = $request->input("lokasi{$i}_id");
                if ($lokasiValue) {
                    $preferensiData[] = [
                        'user_id' => $user->user_id,
                        'mahasiswa_id' => $mahasiswa->mahasiswa_id,
                        'kriteria' => 'lokasi',
                        'nilai' => $lokasiValue,
                        'prioritas' => $i,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                }
            }

            // Memasukkan preferensi biaya ke array untuk insert nanti
            for ($i = 1; $i <= 2; $i++) {
                $biayaValue = $request->input("biaya{$i}_id");
                if ($biayaValue) {
                    $preferensiData[] = [
                        'user_id' => $user->user_id,
                        'mahasiswa_id' => $mahasiswa->mahasiswa_id,
                        'kriteria' => 'biaya',
                        'nilai' => $biayaValue,
                        'prioritas' => $i,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                }
            }

            // Insert data array preferensi user ke database
            DB::table('t_preferensi_user')->insert($preferensiData);

            return response()->json([
                'success' => true,
                'message' => 'Preferensi berhasil disimpan.',
                'redirect' => route('mahasiswa.profile.index')
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
                'username'  => 'required|string',
                'phrase'    => 'required|string',
                'passwordBaru'  => 'required|string|min:6',
                'passwordBaru_confirmation' => 'required|string|same:passwordBaru',
                'phraseBaru'    => 'nullable|string',
            ], [
                'username.required' => 'Username wajib diisi.',
                'phrase.required'   => 'Phrase pemulihan wajib diisi.',
                'passwordBaru.required' => 'Password baru wajib diisi.',
                'passwordBaru.min'      => 'Password minimal 6 karakter.',
                'passwordBaru_confirmation.required' => 'Konfirmasi password wajib diisi.',
                'passwordBaru_confirmation.same'    => 'Konfirmasi password tidak sesuai.',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success'   => false,
                    'message'   => 'Validasi gagal.',
                    'msgField'  => $validator->errors()->toArray()
                ], 422);
            }

            // cari user berdasarkan username
            $user = UsersModel::where('username', $request->username)->first();
            if (!$user) {
                return response()->json([
                    'success'   => false,
                    'message'   => 'username tidak ditemukan.',
                    'msgField'  => ['username' => ['Username tidak ditemukan.']]
                ], 422);
            }

            // validasi phrase pemulihan 
            if ($user->phrase !== $request->phrase) {
                return response()->json([
                    'success'   => false,
                    'message'   => 'Phrase pemulihan tidak sesuai.',
                    'msgField'  => ['phrase' => ['Phrase pemulihan tidak sesuai.']]
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
                'message'   => 'password berhasil diubah. Silahkan login dengan password baru.',
                'redirect'  => route('login'),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success'   => false,
                'message'   => 'Terjadi kesalahan server. Silahkan coba lagi. '
            ], 500);
        }
    }
}
