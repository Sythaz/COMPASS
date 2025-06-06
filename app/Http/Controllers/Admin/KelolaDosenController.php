<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use App\Models\DosenModel;
use App\Models\UsersModel;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\KategoriModel;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;

class KelolaDosenController extends Controller
{
    public function index()
    {
        $breadcrumb = (object) [
            'list' => ['Kelola Pengguna', 'Kelola Dosen']
        ];

        return view('admin.kelola-pengguna.kelola-dosen.index', compact('breadcrumb'));
    }

    // List data Dosen
    public function list(Request $request)
    {
        $data = DosenModel::with(['users', 'kategoris']) // pakai 'kategoris' sesuai nama relasi
            ->select('dosen_id', 'user_id', 'nip_dosen', 'nama_dosen', 'status')
            ->where('status', 'Aktif')
            ->get();

        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('username', fn($row) => $row->users ? ' ' . $row->users->username : '-')
            ->addColumn('kategori', function ($row) {
                // Gabungkan nama kategori jika banyak
                if ($row->kategoris && $row->kategoris->count()) {
                    return $row->kategoris->pluck('nama_kategori')->implode(', ');
                }
                return '-';
            })
            ->editColumn('nip_dosen', function ($row) {
                return (string) $row->nip_dosen;  // pastikan nip dikirim sebagai string
            })
            ->addColumn('status', function ($row) {
                if ($row->status === 'Aktif') {
                    return '<span class="label label-success">Aktif</span>';
                }
                return '<span class="badge bg-secondary">-</span>';
            })
            ->addColumn('aksi', function ($row) {
                $btn = '<button onclick="modalAction(\'' . url('admin/kelola-pengguna/dosen/' . $row->dosen_id . '/show_ajax') . '\')" class="btn btn-info btn-sm">Detail</button> ';
                $btn .= '<button onclick="modalAction(\'' . url('admin/kelola-pengguna/dosen/' . $row->dosen_id . '/edit_ajax') . '\')" class="btn btn-warning btn-sm">Edit</button> ';
                $btn .= '<button onclick="modalAction(\'' . url('admin/kelola-pengguna/dosen/' . $row->dosen_id . '/delete_ajax') . '\')" class="btn btn-danger btn-sm">Nonaktifkan</button>';
                return $btn;
            })
            ->rawColumns(['aksi', 'status'])
            ->make(true);
    }

    public function history()
    {
        $breadcrumb = (object) [
            'list' => ['Kelola Pengguna', 'Kelola Dosen', 'History']
        ];
        return view('admin.kelola-pengguna.kelola-dosen.history', compact('breadcrumb'));
    }

    public function list_history(Request $request)
    {
        $data = DosenModel::with(['users', 'kategoris']) // ganti 'kategori' jadi 'kategoris'
            ->where('status', 'Nonaktif') // hanya ambil dosen yang statusnya Nonaktif
            ->select(
                'dosen_id',
                'user_id',
                'nip_dosen',
                'nama_dosen',
                'status'
            )
            ->get();

        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('nip', fn($row) => (string) $row->nip_dosen)
            ->addColumn('nama', fn($row) => $row->nama_dosen)
            ->addColumn('username', fn($row) => $row->users->username ?? '-')
            ->addColumn('kategori', function ($row) {
                return $row->kategoris->pluck('nama_kategori')->implode(', ') ?: '-';
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
                $btn = '<span style="padding-right:10px;"><button onclick="modalAction(\'' . route('dosen.history.aktivasi.konfirmasi', $row->dosen_id) . '\')" class="btn btn-success btn-sm">Aktifkan</button></span>';
                $btn .= '<button onclick="modalAction(\'' . url('admin/kelola-pengguna/dosen/history/delete/' . $row->dosen_id) . '\')" class="btn btn-danger btn-sm">Hapus</button>';
                return $btn;
            })
            ->rawColumns(['status', 'aksi'])
            ->make(true);
    }

    // Form Create
    public function create()
    {
        $kategoris = KategoriModel::all();
        return view('admin.kelola-pengguna.kelola-dosen.create', compact('kategoris'));
    }

    // Modal actions Show
    public function showAjax($id)
    {
        $dosen = DosenModel::with(['users', 'kategoris'])->findOrFail($id);
        return view('admin.kelola-pengguna.kelola-dosen.show', compact('dosen'));
    }

    // Modal actions Edit
    public function editAjax($id)
    {
        $dosen = DosenModel::with(['users', 'kategoris'])->findOrFail($id);
        $kategoris = KategoriModel::all();
        return view('admin.kelola-pengguna.kelola-dosen.edit', compact('dosen', 'kategoris'));
    }

    // Modal actions Delete
    public function deleteAjax($id)
    {
        $dosen = DosenModel::findOrFail($id);
        return view('admin.kelola-pengguna.kelola-dosen.delete', compact('dosen'));
    }

    // Store new Dosen
    public function store(Request $request)
    {
        $request->validate([
            'nip_dosen' => 'required|unique:t_dosen,nip_dosen',
            'nama_dosen' => 'required',
            'kategori_id' => 'required|array',
            'kategori_id.*' => 'exists:t_kategori,kategori_id',
            'role' => 'required',
            'email' => 'nullable|email|unique:t_dosen,email',
            'no_hp' => 'nullable|unique:t_dosen,no_hp',
            'alamat' => 'nullable|string|max:255',
            'kelamin' => 'required|in:L,P',
        ]);

        DB::transaction(function () use ($request) {
            $user = UsersModel::create([
                'username' => $request->nip_dosen,
                'password' => Hash::make($request->nip_dosen),
                'role' => $request->role,
                'phrase' => $request->nip_dosen,
            ]);

            $dosen = DosenModel::create([
                'user_id' => $user->user_id,
                'nip_dosen' => $request->nip_dosen,
                'nama_dosen' => ucwords(strtolower($request->nama_dosen)),
                'img_dosen' => 'profil-default.jpg',
                'email' => $request->email ?: null,
                'no_hp' => $request->no_hp ?: null,
                'alamat' => $request->alamat ?: null,
                'kelamin' => $request->kelamin,
            ]);

            // Simpan relasi many-to-many dosen-kategori
            $dosen->kategoris()->sync($request->kategori_id);
        });

        return response()->json([
            'success' => true,
            'message' => 'Dosen berhasil ditambahkan dengan username & password sesuai dengan NIP',
        ]);
    }

    // Update data Dosen
    public function update(Request $request, $id)
    {
        $dosen = DosenModel::findOrFail($id);
        $user = $dosen->users;

        $request->validate([
            'nip_dosen' => [
                'required',
                Rule::unique('t_dosen', 'nip_dosen')->ignore($dosen->dosen_id, 'dosen_id'),
            ],
            'nama_dosen' => 'required',
            'kategori_id' => 'required|array',
            'kategori_id.*' => 'exists:t_kategori,kategori_id',
            'role' => 'required',
            'password' => 'nullable|min:6',
            'phrase' => 'nullable',
            'alamat' => 'nullable|string|max:255',
            'email' => [
                'nullable',
                'email',
                Rule::unique('t_dosen', 'email')->ignore($dosen->dosen_id, 'dosen_id'),
            ],
            'no_hp' => [
                'nullable',
                Rule::unique('t_dosen', 'no_hp')->ignore($dosen->dosen_id, 'dosen_id'),
            ],
            'kelamin' => 'required|in:L,P',
        ]);

        DB::transaction(function () use ($request, $dosen, $user) {
            // Update username agar selalu ikut nip
            $user->username = $request->nip_dosen;

            // Update password hanya jika dikirim
            if ($request->filled('password')) {
                $user->password = Hash::make($request->password);
            }

            $user->role = $request->role;
            $user->phrase = $request->input('phrase', $user->phrase);
            $user->save();

            // Update dosen
            $dosen->nip_dosen = $request->nip_dosen;
            $dosen->nama_dosen = ucwords(strtolower($request->nama_dosen));
            $dosen->email = $request->email ?: null;
            $dosen->no_hp = $request->no_hp ?: null;
            $dosen->alamat = $request->alamat ?: null;
            $dosen->kelamin = $request->kelamin;
            $dosen->save();

            // Update many-to-many kategori
            $dosen->kategoris()->sync($request->kategori_id);
        });

        return response()->json([
            'success' => true,
            'message' => 'Data Dosen Berhasil Diperbarui',
        ]);
    }

    public function nonAktif($id)
    {
        $dosen = DosenModel::findOrFail($id);

        DB::transaction(function () use ($dosen) {
            $dosen->status = 'Nonaktif';
            $dosen->save();
        });

        return response()->json([
            'success' => true,
            'message' => 'Data Dosen Berhasil Dinonaktifkan'
        ]);
    }
    public function confirm_aktivasi($id)
    {
        $dosen = DosenModel::findOrFail($id);
        return view('admin.kelola-pengguna.kelola-dosen.aktivasi', compact('dosen'));
    }

    public function aktivasi($id)
    {
        $dosen = DosenModel::findOrFail($id);

        DB::transaction(function () use ($dosen) {
            $dosen->status = 'Aktif';
            $dosen->save();
        });

        return response()->json([
            'success' => true,
            'message' => 'Data Dosen Berhasil Diaktifkan'
        ]);
    }

    public function delete_history($id)
    {
        $dosen = DosenModel::findOrFail($id);
        return view('admin.kelola-pengguna.kelola-dosen.destroy', compact('dosen'));
    }

    // Hapus Dosen Permanen
    public function destroy($id)
    {
        $dosen = DosenModel::findOrFail($id);
        $userId = $dosen->user_id;

        DB::transaction(function () use ($id, $userId) {
            // Hapus dari tabel dosen berdasarkan dosen_id
            DosenModel::where('dosen_id', $id)->delete();
            // Hapus dari tabel user berdasarkan user_id
            UsersModel::where('user_id', $userId)->delete();
        });

        return response()->json([
            'success' => true,
            'message' => 'Data Dosen Berhasil Dihapus PERMANEN!'
        ]);
    }

    // Export data dosen ke Excel
    public function export_excel(Request $request)
    {
        $status = $request->input('status');
        $kategori_id = $request->input('kategori_id');

        $query = DosenModel::with(['kategoris', 'users']) // perhatikan plural 'kategoris' karena relasi many-to-many
            ->select(
                'dosen_id',
                'user_id',
                'nip_dosen',
                'nama_dosen',
                'kelamin',
                'status',
                'alamat',
                'email',
                'no_hp'
            );

        if ($status) {
            $query->where('status', $status);
        } else {
            $query->where('status', 'Aktif');
        }

        // Jika ingin filter kategori, karena many-to-many, gunakan whereHas
        if ($kategori_id) {
            $query->whereHas('kategoris', function ($q) use ($kategori_id) {
                $q->where('kategori_id', $kategori_id);
            });
        }

        $dosen = $query->get();

        // inisialisasi spreadsheet
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // header kolom
        $sheet->setCellValue('A1', 'No');
        $sheet->setCellValue('B1', 'NIP');
        $sheet->setCellValue('C1', 'Nama Dosen');
        $sheet->setCellValue('D1', 'Bidang');
        $sheet->setCellValue('E1', 'Jenis Kelamin');
        $sheet->setCellValue('F1', 'Email');
        $sheet->setCellValue('G1', 'No HP');
        $sheet->setCellValue('H1', 'Alamat');
        $sheet->setCellValue('I1', 'Status');

        // Atur Header Style
        $sheet->getStyle('A1:I1')->getFont()->setBold(true);
        $sheet->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('E1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('I1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        // isi data
        $no = 1;
        $baris = 2;
        foreach ($dosen as $row) {
            // gabungkan nama kategori yang terkait, pisahkan dengan koma
            $bidang = $row->kategoris->pluck('nama_kategori')->implode(', ');

            $sheet->setCellValue('A' . $baris, $no);
            $sheet->setCellValueExplicit('B' . $baris, $row->nip_dosen, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
            $sheet->setCellValue('C' . $baris, $row->nama_dosen);
            $sheet->setCellValue('D' . $baris, $bidang ?: '-');
            $sheet->setCellValue('E' . $baris, $row->kelamin ?? '-');
            $sheet->setCellValue('F' . $baris, $row->email ?? '-');
            $sheet->setCellValue('G' . $baris, $row->no_hp ?? '-');
            $sheet->setCellValue('H' . $baris, $row->alamat ?? '-');
            $sheet->setCellValue('I' . $baris, $row->status ?? '-');

            // Center kolom tertentu
            $sheet->getStyle("A2:A$baris")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle("E2:E$baris")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle("I2:I$baris")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

            $baris++;
            $no++;
        }

        // set kolom auto size
        foreach (range('A', 'I') as $columnID) {
            $sheet->getColumnDimension($columnID)->setAutoSize(true);
        }

        // export
        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        $filename = 'Data Dosen ' . date('Ymd_His') . '.xlsx';

        return response()->streamDownload(function () use ($writer) {
            $writer->save('php://output');
        }, $filename);
    }

    // Export data dosen ke PDF
    public function export_pdf(Request $request)
    {
        $status = $request->input('status');

        $query = DosenModel::select('nama_dosen', 'nip_dosen', 'kelamin', 'status')
            ->orderBy('nama_dosen');

        // Filter status jika tersedia
        if ($status) {
            $query->where('status', $status);
        } else {
            $query->where('status', 'Aktif'); // Default hanya dosen Aktif
        }

        $dosen = $query->get();

        $viewPath = resource_path('views/admin/kelola-pengguna/kelola-dosen/export_pdf.blade.php');

        $html = view()->file($viewPath, ['dosen' => $dosen])->render();

        $pdf = Pdf::loadHTML($html);
        $pdf->setPaper('a4', 'portrait');
        $pdf->setOption('isRemoteEnabled', true);

        return $pdf->stream('Data Dosen ' . date('Y-m-d H:i:s') . '.pdf');
    }

    // View Import Form
    public function importForm()
    {
        return view('admin.kelola-pengguna.kelola-dosen.import');
    }

    // Import Datas Dosen Excel
    public function import(Request $request)
    {
        if ($request->ajax() || $request->wantsJson()) {
            // Validasi file
            $rules = [
                'file_dosen' => ['required', 'mimes:xlsx', 'max:1024'], // max 1MB
            ];

            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validasi Gagal',
                    'msgField' => $validator->errors()
                ]);
            }

            $file = $request->file('file_dosen');
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

                    $nip = trim($value['A']); // Kolom A → NIP
                    $nama = trim($value['B']); // Kolom B → Nama Dosen
                    $kategoriStr = trim($value['C']); // Kolom C → Nama Kategori (bidang), bisa lebih dari 1 kategori pisah koma
                    $email = trim($value['D']); // Kolom D → Email
                    $no_hp = trim($value['E']); // Kolom E → No HP
                    $alamat = trim($value['F']); // Kolom F → Alamat
                    $kelamin = trim($value['G']); // Kolom G → Jenis Kelamin

                    if ($kelamin !== 'L' && $kelamin !== 'P') {
                        $kelamin = 'Belum diisi';
                    }

                    if (empty($nip) || empty($nama) || empty($kategoriStr)) {
                        Log::warning("Baris $baris dilewati: Data penting kosong (NIP/Nama/Kategori)");
                        $skipped++;
                        continue;
                    }

                    // Parse kategori, support multiple kategori pisah koma
                    $kategoriNames = array_map('trim', explode(',', $kategoriStr));
                    // Cari kategori yang sesuai dengan nama, case insensitive
                    $kategoriModels = KategoriModel::whereIn('nama_kategori', $kategoriNames)->get();

                    // Jika jumlah kategori yang ditemukan tidak sama dengan jumlah nama kategori input, berarti ada kategori tidak ditemukan
                    if ($kategoriModels->count() !== count($kategoriNames)) {
                        Log::warning("Baris $baris dilewati: Ada kategori yang tidak ditemukan");
                        $skipped++;
                        continue;
                    }

                    $existingUser = UsersModel::where('username', $nip)->first();
                    if ($existingUser) {
                        Log::info("Baris $baris dilewati: User dengan NIP '$nip' sudah ada");
                        $skipped++;
                        continue;
                    }

                    // Cek duplikat email dan no_hp di t_dosen
                    $existingEmail = DosenModel::where('email', $email)->first();
                    if ($email && $existingEmail) {
                        Log::info("Baris $baris dilewati: Email '$email' sudah digunakan");
                        $skipped++;
                        continue;
                    }

                    $existingNoHp = DosenModel::where('no_hp', $no_hp)->first();
                    if ($no_hp && $existingNoHp) {
                        Log::info("Baris $baris dilewati: No HP '$no_hp' sudah digunakan");
                        $skipped++;
                        continue;
                    }

                    try {
                        $user = UsersModel::create([
                            'username' => $nip,
                            'password' => Hash::make($nip),
                            'role' => 'Dosen',
                            'phrase' => $nip,
                        ]);

                        $dosen = DosenModel::create([
                            'user_id' => $user->user_id,
                            'nip_dosen' => $nip,
                            'nama_dosen' => ucwords(strtolower($nama)),
                            'email' => $email ?: null,
                            'no_hp' => $no_hp ?: null,
                            'alamat' => $alamat ?: null,
                            'kelamin' => $kelamin,
                            'img_dosen' => 'profil-default.jpg',
                            'status' => 'Aktif',
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]);

                        // Hubungkan dosen dengan kategori many-to-many
                        $dosen->kategoris()->sync($kategoriModels->pluck('kategori_id')->toArray());

                        $jumlahBerhasil++;
                    } catch (\Exception $e) {
                        Log::error("Baris $baris gagal disimpan: " . $e->getMessage());
                        $skipped++;
                        continue;
                    }
                }

                if ($jumlahBerhasil > 0) {
                    $message = "$jumlahBerhasil data dosen berhasil diimport";
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
