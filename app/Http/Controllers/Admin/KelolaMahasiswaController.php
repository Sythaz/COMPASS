<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use App\Models\MahasiswaModel;
use App\Models\UsersModel;
use App\Models\PeriodeModel;
use App\Models\ProdiModel;
use App\Models\KategoriModel;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
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
        $data = MahasiswaModel::with(['users', 'prodi', 'periode', 'kategoris'])
            ->where('status', 'Aktif')
            ->select(
                'mahasiswa_id',
                'user_id',
                'prodi_id',
                'periode_id',
                'nim_mahasiswa',
                'nama_mahasiswa',
                'status'
            )
            ->get();

        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('username', fn($row) => $row->users ? $row->users->username : '-')
            ->addColumn('prodi', fn($row) => $row->prodi->nama_prodi ?? '-')
            ->addColumn('periode', fn($row) => $row->periode->semester_periode ?? '-')
            ->addColumn('kategori', function ($row) {
                if ($row->kategoris && $row->kategoris->count() > 0) {
                    return $row->kategoris->pluck('nama_kategori')->implode(', ');
                }
                return '-';
            })
            ->addColumn('status', function ($row) {
                if ($row->status === 'Aktif') {
                    return '<span class="label label-success">Aktif</span>';
                } elseif ($row->status === 'Nonaktif') {
                    return '<span class="label label-danger">Nonaktif</span>';
                }
                return '<span class="badge bg-secondary">-</span>';
            })
            ->addColumn('aksi', function ($row) {
                $btn = '<button onclick="modalAction(\'' . url('admin/kelola-pengguna/mahasiswa/' . $row->mahasiswa_id . '/show_ajax') . '\')" class="btn btn-info btn-sm">Detail</button> ';
                $btn .= '<button onclick="modalAction(\'' . url('admin/kelola-pengguna/mahasiswa/' . $row->mahasiswa_id . '/edit_ajax') . '\')" class="btn btn-warning btn-sm">Edit</button> ';
                $btn .= '<button onclick="modalAction(\'' . url('admin/kelola-pengguna/mahasiswa/' . $row->mahasiswa_id . '/delete_ajax') . '\')" class="btn btn-danger btn-sm">Nonaktifkan</button>';
                return $btn;
            })
            ->rawColumns(['status', 'aksi'])
            ->make(true);
    }

    public function history()
    {
        $breadcrumb = (object) [
            'list' => ['Kelola Pengguna', 'Kelola Mahasiswa', 'History']
        ];
        return view('admin.kelola-pengguna.kelola-mahasiswa.history', compact('breadcrumb'));
    }

    public function list_history(Request $request)
    {
        $data = MahasiswaModel::with(['users', 'prodi', 'periode'])
            ->where('status', 'Nonaktif') // hanya ambil mahasiswa yang statusnya Nonaktif
            ->select(
                'mahasiswa_id',
                'user_id',
                'prodi_id',
                'periode_id',
                'angkatan',
                'nim_mahasiswa',
                'nama_mahasiswa',
                'status'
            )
            ->get();

        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('username', fn($row) => $row->users ? ' ' . $row->users->username : '-')
            ->addColumn('prodi', fn($row) => $row->prodi->nama_prodi ?? '-')
            ->addColumn('periode', fn($row) => $row->periode->semester_periode ?? '-')
            ->addColumn('status', function ($row) {
                if ($row->status === 'Aktif') {
                    return '<span class="label label-success">Aktif</span>';
                } elseif ($row->status === 'Nonaktif') {
                    return '<span class="label label-danger">Nonaktif</span>';
                }
                return '<span class="badge bg-secondary">-</span>';
            })
            ->addColumn('aksi', function ($row) {
                $btn = '<span style="padding-right:10px;"><button onclick="modalAction(\'' . route('mahasiswa.history.aktivasi.konfirmasi', $row->mahasiswa_id) . '\')" class="btn btn-success btn-sm">Aktifkan</button></span>';
                $btn .= '<button onclick="modalAction(\'' . url('admin/kelola-pengguna/mahasiswa/history/delete/' . $row->mahasiswa_id) . '\')" class="btn btn-danger btn-sm">Hapus</button>';
                return $btn;
            })
            ->rawColumns(['status', 'aksi'])
            ->make(true);
    }

    public function create()
    {
        $list_prodi = ProdiModel::all();
        $list_periode = PeriodeModel::all();
        $kategoris = KategoriModel::all();

        return view('admin.kelola-pengguna.kelola-mahasiswa.create', compact('list_prodi', 'list_periode', 'kategoris'));
    }

    public function showAjax($id)
    {
        $mahasiswa = MahasiswaModel::with(['users', 'prodi', 'periode', 'kategoris'])->findOrFail($id);
        return view('admin.kelola-pengguna.kelola-mahasiswa.show', compact('mahasiswa'));
    }

    public function editAjax($id)
    {
        $mahasiswa = MahasiswaModel::with(['users', 'prodi', 'periode', 'kategoris'])->findOrFail($id);
        $list_prodi = ProdiModel::all();
        $list_periode = PeriodeModel::all();
        $kategoris = KategoriModel::all();

        return view('admin.kelola-pengguna.kelola-mahasiswa.edit', compact('mahasiswa', 'list_prodi', 'list_periode', 'kategoris'));
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
            'angkatan' => 'nullable|integer',
            'role' => 'required',
            'email' => 'nullable|email|unique:t_mahasiswa,email',
            'no_hp' => 'nullable|unique:t_mahasiswa,no_hp',
            'alamat' => 'nullable|string|max:255',
            'kelamin' => 'required|in:L,P',
            'kategori_id' => 'required|array|min:1', // minimal 1 kategori
            'kategori_id.*' => 'exists:t_kategori,kategori_id', // setiap id kategori valid
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

            $mahasiswa = MahasiswaModel::create([
                'user_id' => $user->user_id,
                'prodi_id' => $request->prodi_id,
                'periode_id' => $request->periode_id,
                'nim_mahasiswa' => $request->nim_mahasiswa,
                'nama_mahasiswa' => ucwords(strtolower($request->nama_mahasiswa)),
                'img_mahasiswa' => 'profil-default.jpg',
                'angkatan' => $request->angkatan ?? date('Y'),
                'kelamin' => $request->kelamin,
                'email' => $request->email ?: null,
                'no_hp' => $request->no_hp ?: null,
                'alamat' => $request->alamat ?: null,
            ]);

            // Sync kategori many-to-many
            $mahasiswa->kategoris()->sync($request->kategori_id);
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
            'username' => 'required|unique:t_users,username,' . $user->user_id . ',user_id',
            'alamat' => 'nullable|string|max:255',
            'email' => 'nullable|email|unique:t_mahasiswa,email,' . $mahasiswa->mahasiswa_id . ',mahasiswa_id',
            'no_hp' => 'nullable|unique:t_mahasiswa,no_hp,' . $mahasiswa->mahasiswa_id . ',mahasiswa_id',
            'role' => 'required',
            'password' => 'nullable|min:6',
            'angkatan' => 'nullable|integer',
            'phrase' => 'nullable|string',
            'kelamin' => 'required|in:L,P',
            'kategori_id' => 'nullable|array',
            'kategori_id.*' => 'exists:t_kategori,kategori_id',
        ]);

        DB::transaction(function () use ($request, $mahasiswa, $user) {
            // Update tabel users
            $user->username = $request->username;
            $user->role = $request->role;
            $user->phrase = $request->input('phrase', $user->phrase);
            if ($request->filled('password')) {
                $user->password = Hash::make($request->password);
            }
            $user->save();

            // Update tabel mahasiswa
            $mahasiswa->update([
                'prodi_id' => $request->prodi_id,
                'periode_id' => $request->periode_id,
                'nim_mahasiswa' => $request->nim_mahasiswa,
                'nama_mahasiswa' => $request->nama_mahasiswa,
                'alamat' => $request->alamat,
                'email' => $request->email,
                'no_hp' => $request->no_hp,
                'kelamin' => $request->kelamin,
                'angkatan' => $request->angkatan ?? $mahasiswa->angkatan ?? 2025,
            ]);

            // Sinkronisasi kategori
            $mahasiswa->kategoris()->sync($request->kategori_id ?? []);
        });

        return response()->json([
            'success' => true,
            'message' => 'Data Mahasiswa berhasil diperbarui.'
        ]);
    }

    public function nonAktif($id)
    {
        $mahasiswa = MahasiswaModel::findOrFail($id);

        DB::transaction(function () use ($mahasiswa) {
            $mahasiswa->status = 'Nonaktif';
            $mahasiswa->save();
        });

        return response()->json([
            'success' => true,
            'message' => 'Data Mahasiswa Berhasil Dinonaktifkan'
        ]);
    }

    public function confirm_aktivasi($id)
    {
        $mahasiswa = MahasiswaModel::with(['prodi', 'users'])->findOrFail($id);
        return view('admin.kelola-pengguna.kelola-mahasiswa.aktivasi', compact('mahasiswa'));
    }

    public function aktivasi($id)
    {
        $mahasiswa = MahasiswaModel::findOrFail($id);

        DB::transaction(function () use ($mahasiswa) {
            $mahasiswa->status = 'Aktif';
            $mahasiswa->save();
        });

        return response()->json([
            'success' => true,
            'message' => 'Data Mahasiswa Berhasil Diaktifkan'
        ]);
    }

    public function delete_history($id)
    {
        $mahasiswa = MahasiswaModel::findOrFail($id);
        return view('admin.kelola-pengguna.kelola-mahasiswa.destroy', compact('mahasiswa'));
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
            'message' => 'Data Mahasiswa Berhasil Dihapus PERMANEN!'
        ]);
    }

    // Export data mahasiswa ke Excel
    public function export_excel(Request $request)
    {
        $status = $request->input('status');
        $prodi_id = $request->input('prodi_id');
        $periode = $request->input('semester_periode');

        $query = MahasiswaModel::with(['prodi', 'periode', 'kategoris']) // pastikan relasi kategoris() ada
            ->select(
                'mahasiswa_id',
                'prodi_id',
                'periode_id',
                'nim_mahasiswa',
                'nama_mahasiswa',
                'angkatan',
                'email',
                'no_hp',
                'alamat',
                'status'
            );

        if ($status) {
            $query->where('status', $status);
        } else {
            $query->where('status', 'Aktif'); // Filter mahasiswa aktif 
        }

        if ($prodi_id) {
            $query->where('prodi_id', $prodi_id);
        }

        if ($periode) {
            $query->where('semester_periode', $periode);
        }

        $mahasiswa = $query->get();

        // inisialisasi spreadsheet
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // header kolom
        $sheet->setCellValue('A1', 'No');
        $sheet->setCellValue('B1', 'NIM');
        $sheet->setCellValue('C1', 'Nama');
        $sheet->setCellValue('D1', 'Program Studi');
        $sheet->setCellValue('E1', 'Periode');
        $sheet->setCellValue('F1', 'Angkatan');
        $sheet->setCellValue('G1', 'Email');
        $sheet->setCellValue('H1', 'No Handphone');
        $sheet->setCellValue('I1', 'Alamat');
        $sheet->setCellValue('J1', 'Status');
        $sheet->setCellValue('K1', 'Minat & Bakat');

        // Atur Text Center
        $sheet->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('F1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('J1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('K1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);
        $sheet->getStyle('A1:K1')->getFont()->setBold(true);

        // isi data
        $no = 1;
        $baris = 2;
        foreach ($mahasiswa as $row) {
            $sheet->setCellValue('A' . $baris, $no);
            $sheet->setCellValueExplicit('B' . $baris, $row->nim_mahasiswa, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
            $sheet->setCellValue('C' . $baris, $row->nama_mahasiswa);
            $sheet->setCellValue('D' . $baris, $row->prodi->nama_prodi ?? '-');
            $sheet->setCellValue('E' . $baris, $row->periode->semester_periode ?? '-');
            $sheet->setCellValue('F' . $baris, $row->angkatan ?? '-');
            $sheet->setCellValue('G' . $baris, $row->email ?? '-');
            $sheet->setCellValue('H' . $baris, $row->no_hp ?? '-');
            $sheet->setCellValue('I' . $baris, $row->alamat ?? '-');
            $sheet->setCellValue('J' . $baris, $row->status ?? '-');

            // Ambil dan tampilkan kategori
            $kategoriList = $row->kategoris->pluck('nama_kategori')->toArray();
            $kategoriText = count($kategoriList) > 0 ? implode(', ', $kategoriList) : 'Belum memilih minat & bakat';
            $sheet->setCellValue('K' . $baris, $kategoriText);

            // Atur Alignment per baris
            $sheet->getStyle("A$baris")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle("F$baris")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle("J$baris")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle("K$baris")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);

            $baris++;
            $no++;
        }

        // set kolom auto size
        foreach (range('A', 'K') as $columnID) {
            $sheet->getColumnDimension($columnID)->setAutoSize(true);
        }

        $sheet->setTitle('Data Mahasiswa');

        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        $filename = 'Data Mahasiswa ' . date('Y-m-d H-i-s') . '.xlsx';

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
        $status = $request->input('status');

        $query = MahasiswaModel::select('nama_mahasiswa', 'nim_mahasiswa', 'kelamin', 'status')
            ->orderBy('nama_mahasiswa');

        // Filter status jika tersedia
        if ($status) {
            $query->where('status', $status);
        } else {
            $query->where('status', 'Aktif'); // Default hanya mahasiswa Aktif
        }

        $mahasiswa = $query->get();

        $viewPath = resource_path('views/admin/kelola-pengguna/kelola-mahasiswa/export_pdf.blade.php');

        $html = view()->file($viewPath, ['mahasiswa' => $mahasiswa])->render();

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
                    if ($baris == 1)
                        continue; // Skip header

                    $nim = trim($value['A']);
                    $nama = trim($value['B']);
                    $nama_prodi = trim($value['C']);
                    $email = trim($value['D']);
                    $no_hp = trim($value['E']);
                    $alamat = trim($value['F']);
                    $kelamin = trim($value['G']);
                    $nama_kategori = isset($value['H']) ? trim($value['H']) : null;

                    if ($kelamin !== 'L' && $kelamin !== 'P') {
                        $kelamin = 'Belum diisi';
                    }

                    if (empty($nim) || empty($nama) || empty($nama_prodi)) {
                        Log::warning("Baris $baris dilewati: Data penting kosong (NIM/Nama/Prodi)");
                        $skipped++;
                        continue;
                    }

                    $prodi = ProdiModel::whereRaw('LOWER(nama_prodi) = ?', [strtolower($nama_prodi)])->first();
                    if (!$prodi) {
                        Log::warning("Baris $baris dilewati: Prodi '$nama_prodi' tidak ditemukan");
                        $skipped++;
                        continue;
                    }

                    $existingUser = UsersModel::where('username', $nim)->first();
                    if ($existingUser) {
                        Log::info("Baris $baris dilewati: User dengan NIM '$nim' sudah ada");
                        $skipped++;
                        continue;
                    }

                    $existingEmail = MahasiswaModel::where('email', $email)->first();
                    if ($email && $existingEmail) {
                        Log::info("Baris $baris dilewati: Email '$email' sudah digunakan");
                        $skipped++;
                        continue;
                    }

                    $existingNoHp = MahasiswaModel::where('no_hp', $no_hp)->first();
                    if ($no_hp && $existingNoHp) {
                        Log::info("Baris $baris dilewati: No HP '$no_hp' sudah digunakan");
                        $skipped++;
                        continue;
                    }

                    try {
                        $user = UsersModel::create([
                            'username' => $nim,
                            'password' => Hash::make($nim),
                            'role' => 'Mahasiswa',
                            'phrase' => $nim,
                        ]);

                        $mahasiswa = MahasiswaModel::create([
                            'user_id' => $user->user_id,
                            'prodi_id' => $prodi->prodi_id,
                            'periode_id' => 1,
                            'level_minbak_id' => 1,
                            'nim_mahasiswa' => $nim,
                            'nama_mahasiswa' => ucwords(strtolower($nama)),
                            'kelamin' => $kelamin,
                            'email' => $email,
                            'no_hp' => $no_hp,
                            'alamat' => $alamat,
                            'img_mahasiswa' => 'profil-default.jpg',
                            'angkatan' => 2023,
                            'status' => 'Aktif',
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]);

                        // Tambah kategori jika tersedia
                        if (!empty($nama_kategori)) {
                            $kategori = KategoriModel::whereRaw('LOWER(nama_kategori) = ?', [strtolower($nama_kategori)])->first();
                            if ($kategori) {
                                $mahasiswa->kategoris()->sync([$kategori->kategori_id]);
                            } else {
                                Log::warning("Baris $baris: Kategori '$nama_kategori' tidak ditemukan");
                            }
                        }

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
