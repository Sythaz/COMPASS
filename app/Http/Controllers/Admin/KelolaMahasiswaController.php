<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use App\Models\MahasiswaModel;
use App\Models\UsersModel;
use App\Models\PeriodeModel;
use App\Models\ProdiModel;
use App\Models\LevelMinatBakatModel;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

class KelolaMahasiswaController extends Controller
{
    public function index()
    {
        $breadcrumb = (object) [
            'list' => ['Kelola Pengguna', 'Kelola Mahasiswa']
        ];

        return view('admin.kelola-pengguna.kelola-mahasiswa.index', compact('breadcrumb'));
    }

    public function list(Request $request)
    {
        $data = MahasiswaModel::with(['users', 'prodi', 'periode', 'level_minat_bakat'])
            ->select('mahasiswa_id', 'user_id', 'prodi_id', 'periode_id', 'level_minbak_id', 'nim_mahasiswa', 'nama_mahasiswa', 'angkatan')
            ->get();

        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('username', fn($row) => $row->users ? ' ' . $row->users->username : '-')
            ->addColumn('role', fn($row) => $row->users->role ?? '-')
            ->addColumn('prodi', fn($row) => $row->prodi->nama_prodi ?? '-')
            ->addColumn('periode', fn($row) => $row->periode->semester_periode ?? '-')
            ->addColumn('level_minat_bakat', fn($row) => $row->level_minat_bakat->level_minbak ?? '-')
            ->addColumn('angkatan', fn($row) => $row->angkatan ?? '-')
            ->addColumn('aksi', function ($row) {
                $btn = '<button onclick="modalAction(\'' . url('admin/kelola-pengguna/mahasiswa/' . $row->mahasiswa_id . '/show_ajax') . '\')" class="btn btn-info btn-sm">Detail</button> ';
                $btn .= '<button onclick="modalAction(\'' . url('admin/kelola-pengguna/mahasiswa/' . $row->mahasiswa_id . '/edit_ajax') . '\')" class="btn btn-warning btn-sm">Edit</button> ';
                $btn .= '<button onclick="modalAction(\'' . url('admin/kelola-pengguna/mahasiswa/' . $row->mahasiswa_id . '/delete_ajax') . '\')" class="btn btn-danger btn-sm">Hapus</button>';
                return $btn;
            })
            ->rawColumns(['aksi'])
            ->make(true);
    }

    public function create()
    {
        $list_prodi = ProdiModel::all();
        $list_periode = PeriodeModel::all();
        $list_level = LevelMinatBakatModel::all();

        return view('admin.kelola-pengguna.kelola-mahasiswa.create', compact('list_prodi', 'list_periode', 'list_level'));
    }

    public function showAjax($id)
    {
        $mahasiswa = MahasiswaModel::with(['users', 'prodi', 'periode', 'level_minat_bakat'])->findOrFail($id);
        return view('admin.kelola-pengguna.kelola-mahasiswa.show', compact('mahasiswa'));
    }

    public function editAjax($id)
    {
        $mahasiswa = MahasiswaModel::with(['users', 'prodi', 'periode', 'level_minat_bakat'])->findOrFail($id);
        $list_prodi = ProdiModel::all();
        $list_periode = PeriodeModel::all();
        $list_level = LevelMinatBakatModel::all();

        return view('admin.kelola-pengguna.kelola-mahasiswa.edit', compact('mahasiswa', 'list_prodi', 'list_periode', 'list_level'));
    }

    public function deleteAjax($id)
    {
        $mahasiswa = MahasiswaModel::findOrFail($id);
        return view('admin.kelola-pengguna.kelola-mahasiswa.delete', compact('mahasiswa'));
    }

    public function store(Request $request)
    {

        $request->validate([
            'nim_mahasiswa' => 'required|unique:t_mahasiswa,nim_mahasiswa',
            'nama_mahasiswa' => 'required',
            'prodi_id' => 'required|exists:t_prodi,prodi_id',
            'periode_id' => 'required|exists:t_periode,periode_id',
            'level_minbak_id' => 'required|exists:t_level_minat_bakat,level_minbak_id',
            'angkatan' => 'nullable|integer',
            'role' => 'required',
            'email' => 'nullable|email|unique:t_dosen,email',
            'no_hp' => 'nullable|unique:t_dosen,no_hp',
            'alamat' => 'nullable|string|max:255',
            'kelamin' => 'required|in:L,P',
        ]);

        // Validasi username yang akan sama nim belum dipakai
        if (UsersModel::where('username', $request->nim_mahasiswa)->exists()) {
            return response()->json([
                'success' => false,
                'message' => 'Username (NIM Mahasiswa) sudah digunakan di sistem.'
            ], 422);
        }

        DB::transaction(function () use ($request) {
            $user = UsersModel::create([
                'username' => $request->nim_mahasiswa,
                'password' => Hash::make($request->nim_mahasiswa), // password sama dengan nim_mahasiswa (di-hash)
                'role' => $request->role,
                'phrase' => $request->nim_mahasiswa,
            ]);

            MahasiswaModel::create([
                'user_id' => $user->user_id,
                'prodi_id' => $request->prodi_id,
                'periode_id' => $request->periode_id,
                'level_minbak_id' => $request->level_minbak_id,
                'nim_mahasiswa' => $request->nim_mahasiswa,
                'nama_mahasiswa' => strtoupper($request->nama_mahasiswa),
                'img_mahasiswa' => 'profil-default.jpg',
                'angkatan' => $request->angkatan ?? 2025,
                'kelamin' => $request->kelamin,
                'email' => $request->email ?: 'Belum diisi!',
                'no_hp' => $request->no_hp ?: 'Belum diisi!',
                'alamat' => $request->alamat ?: 'Belum diisi!',
            ]);
        });

        return response()->json([
            'success' => true,
            'message' => 'Mahasiswa berhasil ditambahkan dengan username & password sesuai dengan NIM.'
        ]);
    }

    public function update(Request $request, $id)
    {
        $mahasiswa = MahasiswaModel::findOrFail($id);
        $user = $mahasiswa->users;

        $request->validate([
            'nim_mahasiswa' => 'required|unique:t_mahasiswa,nim_mahasiswa,' . $mahasiswa->mahasiswa_id . ',mahasiswa_id',
            'nama_mahasiswa' => 'required',
            'prodi_id' => 'required|exists:t_prodi,prodi_id',
            'periode_id' => 'required|exists:t_periode,periode_id',
            'level_minbak_id' => 'required|exists:t_level_minat_bakat,level_minbak_id',
            'status' => 'required|in:aktif,nonaktif',
            'username' => 'required|unique:t_users,username,' . $user->user_id . ',user_id',
            'alamat' => 'nullable|string|max:255',
            'email' => 'nullable|email|unique:t_mahasiswa,email,' . $mahasiswa->mahasiswa_id . ',mahasiswa_id',
            'no_hp' => 'nullable|unique:t_mahasiswa,no_hp,' . $mahasiswa->mahasiswa_id . ',mahasiswa_id',
            'role' => 'required',
            'password' => 'nullable|min:6',
            'angkatan' => 'nullable|integer',
            'phrase' => 'nullable|string',
        ]);

        DB::transaction(function () use ($request, $mahasiswa, $user) {
            // Update user
            $user->username = $request->username;
            if ($request->filled('password')) {
                $user->password = Hash::make($request->password);
            }
            $user->role = $request->role;
            $user->phrase = $request->input('phrase', $user->phrase);
            $user->save();

            // Update mahasiswa
            $mahasiswa->prodi_id = $request->prodi_id;
            $mahasiswa->periode_id = $request->periode_id;
            $mahasiswa->level_minbak_id = $request->level_minbak_id;
            $mahasiswa->status = $request->status;
            $mahasiswa->nim_mahasiswa = $request->nim_mahasiswa;
            $mahasiswa->nama_mahasiswa = $request->nama_mahasiswa;

            $mahasiswa->alamat = $request->has('alamat') ? $request->alamat : $mahasiswa->alamat;
            $mahasiswa->email = $request->has('email') ? $request->email : $mahasiswa->email;
            $mahasiswa->no_hp = $request->has('no_hp') ? $request->no_hp : $mahasiswa->no_hp;

            $mahasiswa->angkatan = $request->angkatan ?? $mahasiswa->angkatan ?? 2025;

            $mahasiswa->save();
        });

        return response()->json([
            'success' => true,
            'message' => 'Data Mahasiswa Berhasil Diperbarui'
        ]);
    }

    public function destroy($id)
    {
        $mahasiswa = MahasiswaModel::findOrFail($id);
        $user = $mahasiswa->users;

        DB::transaction(function () use ($mahasiswa, $user) {
            $mahasiswa->delete();
            $user->delete();
        });

        return response()->json([
            'success' => true,
            'message' => 'Data Mahasiswa berhasil dihapus'
        ]);
    }

    // Export data mahasiswa ke Excel
    public function export_excel()
    {
        // ambil data mahasiswa dengan relasi prodi
        $mahasiswa = MahasiswaModel::with('prodi')
            ->select('mahasiswa_id', 'prodi_id', 'nim_mahasiswa', 'nama_mahasiswa')
            ->get();

        // inisialisasi spreadsheet
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // header kolom
        $sheet->setCellValue('A1', 'No');
        $sheet->setCellValue('B1', 'NIM');
        $sheet->setCellValue('C1', 'Nama');
        $sheet->setCellValue('D1', 'Program Studi');

        $sheet->getStyle('A1:D1')->getFont()->setBold(true); // bold header

        // isi data
        $no = 1;
        $baris = 2;
        foreach ($mahasiswa as $row) {
            $sheet->setCellValue('A' . $baris, $no);
            $sheet->setCellValueExplicit('B' . $baris, $row->nim_mahasiswa, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
            $sheet->setCellValue('C' . $baris, $row->nama_mahasiswa);
            $sheet->setCellValue('D' . $baris, $row->prodi->nama_prodi ?? '-');

            $baris++;
            $no++;
        }

        // set kolom auto size
        foreach (range('A', 'D') as $columnID) {
            $sheet->getColumnDimension($columnID)->setAutoSize(true);
        }

        $sheet->setTitle('Data Mahasiswa');

        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        $filename = 'Data Mahasiswa ' . date('Y-m-d H:i:s') . '.xlsx';

        // header download
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');
        header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
        header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
        header('Cache-Control: cache, must-revalidate');
        header('Pragma: public');

        $writer->save('php://output');
        exit;
    }

    public function export_pdf(Request $request)
    {
        $mahasiswa = MahasiswaModel::select('nama_mahasiswa', 'nim_mahasiswa')
            ->orderBy('nama_mahasiswa')
            ->get();

        // Jika menggunakan Kebab case (kelola-mahasiswa) harus load satu-satu ya!
        $viewPath = resource_path('views/admin/kelola-pengguna/kelola-mahasiswa/export_pdf.blade.php');
        // Fungsi render() mengubah view menjadi HTML string
        $html = view()->file($viewPath, ['mahasiswa' => $mahasiswa])->render();
        // Memuat HTML string $html ke PDF generator (misal dompdf atau barryvdh/laravel-dompdf)
        $pdf = Pdf::loadHTML($html);

        $pdf->setPaper('a4', 'portrait');
        $pdf->setOption('isRemoteEnabled', true);

        return $pdf->stream('Data Mahasiswa ' . date('Y-m-d H:i:s') . '.pdf');
    }

    public function importForm()
    {
        return view('admin.kelola-pengguna.kelola-mahasiswa.import');
    }


    public function import(Request $request)
    {
        if ($request->ajax() || $request->wantsJson()) {
            // Validasi file
            $rules = [
                'file_mahasiswa' => ['required', 'mimes:xlsx', 'max:1024'], // max 1MB
            ];

            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validasi Gagal',
                    'msgField' => $validator->errors()
                ]);
            }

            $file = $request->file('file_mahasiswa');
            $reader = IOFactory::createReader('Xlsx');
            $reader->setReadDataOnly(true);

            try {
                $spreadsheet = $reader->load($file->getRealPath());
            } catch (\Exception $e) {
                return response()->json([
                    'status' => false,
                    'message' => 'Gagal membaca file Excel: ' . $e->getMessage()
                ]);
            }

            $sheet = $spreadsheet->getActiveSheet();
            $data = $sheet->toArray(null, false, true, true);

            $jumlahBerhasil = 0;
            $skipped = 0;

            if (count($data) > 1) {
                foreach ($data as $baris => $value) {
                    if ($baris == 1) {
                        // Skip header
                        continue;
                    }

                    $nim = trim($value['A']);
                    $nama = trim($value['B']);
                    $nama_prodi = trim($value['C']);
                    $email = trim($value['D']);
                    $no_hp = trim($value['E']);
                    $alamat = trim($value['F']);

                    // Validasi data penting
                    if (empty($nim) || empty($nama) || empty($nama_prodi)) {
                        Log::warning("Baris $baris dilewati: Data penting kosong (NIM/Nama/Prodi)");
                        $skipped++;
                        continue;
                    }

                    // Cari prodi_id dari nama_prodi (case-insensitive)
                    $prodi = ProdiModel::whereRaw('LOWER(nama_prodi) = ?', [strtolower($nama_prodi)])->first();
                    if (!$prodi) {
                        Log::warning("Baris $baris dilewati: Prodi '$nama_prodi' tidak ditemukan");
                        $skipped++;
                        continue;
                    }

                    // Cek jika user dengan username (NIM) sudah ada
                    $existingUser = UsersModel::where('username', $nim)->first();
                    if ($existingUser) {
                        Log::info("Baris $baris dilewati: User dengan NIM '$nim' sudah ada");
                        $skipped++;
                        continue;
                    }

                    try {
                        // Buat user baru
                        $user = UsersModel::create([
                            'username' => $nim,
                            'password' => Hash::make($nim),
                            'role' => 'Mahasiswa',
                            'phrase' => $nim,
                        ]);

                        // Buat data mahasiswa
                        MahasiswaModel::create([
                            'user_id' => $user->user_id,
                            'prodi_id' => $prodi->prodi_id,
                            'periode_id' => 1,
                            'level_minbak_id' => 1,
                            'nim_mahasiswa' => $nim,
                            'nama_mahasiswa' => strtoupper($nama),  // Mengubah menjadi kapital semua
                            'email' => $email,
                            'no_hp' => $no_hp,
                            'alamat' => $alamat,
                            'img_mahasiswa' => 'profil-default.jpg',
                            'angkatan' => 2023,
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]);

                        $jumlahBerhasil++;
                    } catch (\Exception $e) {
                        Log::error("Baris $baris gagal disimpan: " . $e->getMessage());
                        $skipped++;
                        continue;
                    }
                }

                if ($jumlahBerhasil > 0) {
                    $message = "$jumlahBerhasil data mahasiswa berhasil diimport";
                    if ($skipped > 0) {
                        $message .= ", $skipped data dilewati karena duplikat atau data tidak valid.";
                    }

                    return response()->json([
                        'status' => true,
                        'message' => $message
                    ]);
                } else {
                    return response()->json([
                        'status' => false,
                        'message' => 'Tidak ada data baru yang berhasil diimport'
                    ]);
                }
            }

            return response()->json([
                'status' => false,
                'message' => 'File Excel kosong atau format tidak sesuai'
            ]);
        }

        return redirect('/');
    }

}
