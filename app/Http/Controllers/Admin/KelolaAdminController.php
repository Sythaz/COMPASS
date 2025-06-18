<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use App\Models\AdminModel;
use App\Models\UsersModel;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

class KelolaAdminController extends Controller
{
    public function index()
    {
        $breadcrumb = (object) [
            'list' => ['Kelola Pengguna', 'Kelola Admin']
        ];

        return view('admin.kelola-pengguna.kelola-admin.index', compact('breadcrumb'));
    }

    public function list(Request $request)
    {
        $currentAdminId = auth()->user()->admin->admin_id ?? null; // Mendapatkan ID admin yang sedang login

        $data = AdminModel::with('users')
            ->select('admin_id', 'user_id', 'nip_admin', 'nama_admin', 'email', 'status')
            ->where('status', 'Aktif') // Hanya menampilkan admin Aktif
            ->when($currentAdminId, function ($query, $currentAdminId) {
                return $query->where('admin_id', '!=', $currentAdminId);
            }) // Kecuali admin yang sedang login
            ->get();

        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('username', fn ($row) => $row->users ? ' ' . $row->users->username : '-')
            ->editColumn('nip_admin', function ($row) {
                return (string) $row->nip_admin;  // pastikan nip dikirim sebagai string
            })
            ->addColumn('status', function ($row) {
                if ($row->status === 'Aktif') {
                    return '<span class="label label-success">Aktif</span>';
                }
                return '<span class="badge bg-secondary">-</span>';
            })

            ->addColumn('aksi', function ($row) {
                $btn = '<button onclick="modalAction(\'' . url('admin/kelola-pengguna/admin/' . $row->admin_id . '/show_ajax') . '\')" class="btn btn-info btn-sm">Detail</button> ';
                $btn .= '<button onclick="modalAction(\'' . url('admin/kelola-pengguna/admin/' . $row->admin_id . '/edit_ajax') . '\')" class="btn btn-warning btn-sm">Edit</button> ';
                $btn .= '<button onclick="modalAction(\'' . url('admin/kelola-pengguna/admin/' . $row->admin_id . '/delete_ajax') . '\')" class="btn btn-danger btn-sm">Nonaktifkan</button>';
                return $btn;
            })
            ->rawColumns(['aksi', 'status'])
            ->make(true);
    }

    public function history()
    {
        $breadcrumb = (object) [
            'list' => ['Kelola Pengguna', 'Kelola Admin', 'Riwayat Kelola Admin']
        ];
        return view('admin.kelola-pengguna.kelola-admin.history', compact('breadcrumb'));
    }

    public function list_history(Request $request)
    {
        $data = AdminModel::with('users')
            ->select('admin_id', 'user_id', 'nip_admin', 'nama_admin', 'email', 'status')
            ->where('status', 'Nonaktif') // Hanya menampilkan admin Nonktif
            ->get();

        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('username', fn ($row) => $row->users ? ' ' . $row->users->username : '-')
            ->editColumn('nip_admin', function ($row) {
                return (string) $row->nip_admin;
            })
            ->addColumn('status', function ($row) {
                if ($row->status === 'Nonaktif') {
                    return '<span class="label label-danger">Nonaktif</span>';
                }
                return '<span class="badge bg-secondary">-</span>';
            })

            ->addColumn('aksi', function ($row) {
                $btn = '<span style="padding-right:10px;"><button onclick="modalAction(\'' . route('admin.history.aktivasi.konfirmasi', $row->admin_id) . '\')" class="btn btn-success btn-sm">Aktifkan</button></span>';
                $btn .= '<button onclick="modalAction(\'' . route('admin.history.delete', $row->admin_id) . '\')" class="btn btn-danger btn-sm">Nonaktifkan</button>';
                return $btn;
            })

            ->rawColumns(['aksi', 'status'])
            ->make(true);
    }

    // Show data detail for modal
    public function showAjax($id)
    {
        $admin = AdminModel::with('users')->findOrFail($id);
        return view('admin.kelola-pengguna.kelola-admin.show', compact('admin'));
    }

    // Edit data modal (form)
    public function editAjax($id)
    {
        $admin = AdminModel::with('users')->findOrFail($id);
        return view('admin.kelola-pengguna.kelola-admin.edit', compact('admin'));
    }

    // Delete confirmation modal
    public function deleteAjax($id)
    {
        $admin = AdminModel::findOrFail($id);
        return view('admin.kelola-pengguna.kelola-admin.delete', compact('admin'));
    }
    public function create()
    {
        return view('admin.kelola-pengguna.kelola-admin.create');
    }

    // Store new admin
    public function store(Request $request)
    {
        $request->validate([
            'nip_admin' => 'required|unique:t_admin,nip_admin',
            'nama_admin' => 'required',
            'role' => 'required',
            'email' => 'nullable|email|unique:t_admin,email',
            'no_hp' => 'nullable|unique:t_admin,no_hp',
            'alamat' => 'nullable|string|max:255',
            'kelamin' => 'required|in:L,P',
        ]);

        DB::transaction(function () use ($request) {
            $user = UsersModel::create([
                'username' => $request->nip_admin,
                'password' => Hash::make($request->nip_admin),
                'role' => $request->role,
                'phrase' => $request->nip_admin,
            ]);

            AdminModel::create([
                'user_id' => $user->user_id,
                'nip_admin' => $request->nip_admin,
                'nama_admin' => $request->nama_admin,
                'img_admin' => 'profil-default.jpg',
                'email' => $request->email ?: null,
                'no_hp' => $request->no_hp ?: null,
                'alamat' => $request->alamat ?: null,
                'kelamin' => $request->kelamin
            ]);
        });

        return response()->json([
            'success' => true,
            'message' => 'Admin berhasil ditambahkan dengan username & password sesuai dengan NIP',
        ]);
    }

    // Update existing admin
    public function update(Request $request, $id)
    {
        $admin = AdminModel::findOrFail($id);
        $user = $admin->users;

        $request->validate([
            'nip_admin' => 'required|unique:t_admin,nip_admin,' . $admin->admin_id . ',admin_id',
            'nama_admin' => 'required',
            'username' => 'required|unique:t_users,username,' . $user->user_id . ',user_id',
            'role' => 'required',
            'password' => 'nullable|min:6',

            // kolom opsional, jika diisi harus valid
            'email' => 'nullable|email|unique:t_admin,email,' . $admin->admin_id . ',admin_id',
            'no_hp' => 'nullable|unique:t_admin,no_hp,' . $admin->admin_id . ',admin_id',
            'alamat' => 'nullable|string|max:255',
            'kelamin' => 'nullable|in:L,P',
            'phrase' => 'nullable|string',
        ]);

        DB::transaction(function () use ($request, $admin, $user) {
            // Update tabel user
            $user->username = $request->username;
            if ($request->filled('password')) {
                $user->password = Hash::make($request->password);
            }
            $user->role = $request->role;
            $user->phrase = $request->input('phrase', $user->phrase);
            $user->save();

            // Update tabel admin
            $admin->nip_admin = $request->nip_admin;
            $admin->nama_admin = $request->nama_admin;
            $admin->alamat = $request->input('alamat', $admin->alamat);
            $admin->email = $request->input('email', $admin->email);
            $admin->no_hp = $request->input('no_hp', $admin->no_hp);
            $admin->kelamin = $request->input('kelamin', $admin->kelamin);
            $admin->save();
        });

        return response()->json([
            'success' => true,
            'message' => 'Data Admin Berhasil Diperbarui'
        ]);
    }

    public function nonAktif($id)
    {
        $admin = AdminModel::findOrFail($id);

        DB::transaction(function () use ($admin) {
            $admin->status = 'Nonaktif';
            $admin->save();
        });

        return response()->json([
            'success' => true,
            'message' => 'Data Admin Berhasil Dinonaktifkan'
        ]);
    }

    public function confirm_aktivasi($id)
    {
        $admin = AdminModel::with(['users'])->findOrFail($id);
        return view('admin.kelola-pengguna.kelola-admin.aktivasi', compact('admin'));
    }

    public function aktivasi($id)
    {
        $admin = AdminModel::findOrFail($id);

        DB::transaction(function () use ($admin) {
            $admin->status = 'Aktif';
            $admin->save();
        });

        return response()->json([
            'success' => true,
            'message' => 'Data Admin Berhasil Diaktifkan'
        ]);
    }

    public function delete_history($id)
    {
        $admin = AdminModel::findOrFail($id);
        return view('admin.kelola-pengguna.kelola-admin.destroy', compact('admin'));
    }

    // Delete admin permanen
    public function destroy($id)
    {
        $admin = AdminModel::findOrFail($id);
        DB::transaction(function () use ($admin) {
            $admin->delete();        // hapus admin dulu (child)
            $admin->users()->delete(); // hapus user (parent) setelahnya
        });
        return response()->json([
            'success' => true,
            'message' => 'Data Admin Berhasil Dihapus'
        ]);
    }

    // Export data admin ke Excel
    public function export_excel(Request $request)
    {
        $status = $request->input('status');

        $query = AdminModel::with('users')
            ->select(
                'admin_id',
                'user_id',
                'nip_admin',
                'nama_admin',
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

        $admin = $query->get();

        // inisialisasi spreadsheet
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // header kolom
        $sheet->setCellValue('A1', 'No');
        $sheet->setCellValue('B1', 'NIP');
        $sheet->setCellValue('C1', 'Nama Admin');
        $sheet->setCellValue('D1', 'Jenis Kelamin');
        $sheet->setCellValue('E1', 'Email');
        $sheet->setCellValue('F1', 'No HP');
        $sheet->setCellValue('G1', 'Alamat');
        $sheet->setCellValue('H1', 'Status');

        // Atur Header Style
        $sheet->getStyle('A1:H1')->getFont()->setBold(true);
        $sheet->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('D1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('H1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        // isi data
        $no = 1;
        $baris = 2;
        foreach ($admin as $row) {
            $sheet->setCellValue('A' . $baris, $no);
            $sheet->setCellValueExplicit('B' . $baris, $row->nip_admin, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
            $sheet->setCellValue('C' . $baris, $row->nama_admin);
            $sheet->setCellValue('D' . $baris, $row->kelamin ?? '-');
            $sheet->setCellValue('E' . $baris, $row->email ?? '-');
            $sheet->setCellValue('F' . $baris, $row->no_hp ?? '-');
            $sheet->setCellValue('G' . $baris, $row->alamat ?? '-');
            $sheet->setCellValue('H' . $baris, $row->status ?? '-');

            // Center kolom tertentu
            $sheet->getStyle("A2:A$baris")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle("D2:D$baris")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle("H2:H$baris")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

            $baris++;
            $no++;
        }

        // set kolom auto size
        foreach (range('A', 'H') as $columnID) {
            $sheet->getColumnDimension($columnID)->setAutoSize(true);
        }

        // export
        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        $filename = 'Data Admin ' . date('Ymd_His') . '.xlsx';

        return response()->streamDownload(function () use ($writer) {
            $writer->save('php://output');
        }, $filename);
    }

    // Export data admin ke PDF
    public function export_pdf(Request $request)
    {
        $status = $request->input('status');

        $query = AdminModel::select('nama_admin', 'nip_admin', 'kelamin', 'status')
            ->orderBy('nama_admin');

        // Filter berdasarkan status jika dipilih
        if ($status) {
            $query->where('status', $status);
        } else {
            $query->where('status', 'Aktif'); // Default hanya admin yang Aktif
        }

        $admin = $query->get();

        $viewPath = resource_path('views/admin/kelola-pengguna/kelola-admin/export_pdf.blade.php');

        $html = view()->file($viewPath, ['admin' => $admin])->render();

        $pdf = Pdf::loadHTML($html);
        $pdf->setPaper('a4', 'portrait');
        $pdf->setOption('isRemoteEnabled', true);

        return $pdf->stream('Data Admin ' . date('Y-m-d H:i:s') . '.pdf');
    }

    // View Import Form untuk Admin
    public function importForm()
    {
        return view('admin.kelola-pengguna.kelola-admin.import');
    }

    // Import Data Admin Excel
    public function import(Request $request)
    {
        if (!$request->ajax() && !$request->wantsJson()) {
            return redirect('/');
        }

        $request->validate([
            'file_admin' => 'required|file|mimes:xlsx|max:1024',
        ]);

        try {
            $data = IOFactory::createReader('Xlsx')
                ->setReadDataOnly(true)
                ->load($request->file('file_admin')->getRealPath())
                ->getActiveSheet()
                ->toArray(null, false, true, true);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Gagal membaca file Excel: ' . $e->getMessage()
            ]);
        }

        $jumlahBerhasil = 0;
        $skipped = 0;

        foreach ($data as $baris => $row) {
            if ($baris == 1) {
                continue;
            } // Skip header

            // Skip baris jika semua kolom kosong
            if (
                !trim($row['A'] ?? '') &&
                !trim($row['B'] ?? '') &&
                !trim($row['C'] ?? '') &&
                !trim($row['D'] ?? '') &&
                !trim($row['E'] ?? '') &&
                !trim($row['F'] ?? '')
            ) {
                continue;
            }

            $nip     = trim($row['A'] ?? '');
            $nama    = trim($row['B'] ?? '');
            $email   = trim($row['C'] ?? '');
            $no_hp   = trim($row['D'] ?? '');
            $alamat  = trim($row['E'] ?? '');
            $kelamin = in_array(trim($row['F'] ?? ''), ['L', 'P']) ? trim($row['F']) : 'Belum diisi';

            // Jika NIP atau Nama kosong, lewati
            if (!$nip || !$nama) {
                $skipped++;
                continue;
            }

            // Cek duplikat
            if (
                UsersModel::where('username', $nip)->exists() ||
                ($email && AdminModel::where('email', $email)->exists()) ||
                ($no_hp && AdminModel::where('no_hp', $no_hp)->exists())
            ) {
                $skipped++;
                continue;
            }

            try {
                $user = UsersModel::create([
                    'username' => $nip,
                    'password' => Hash::make($nip),
                    'role' => 'Admin',
                    'phrase' => $nip,
                ]);

                AdminModel::create([
                    'user_id' => $user->user_id,
                    'nip_admin' => $nip,
                    'nama_admin' => ucwords(strtolower($nama)),
                    'email' => $email,
                    'no_hp' => $no_hp,
                    'alamat' => $alamat,
                    'kelamin' => $kelamin,
                    'img_admin' => 'profil-default.jpg',
                    'status' => 'Aktif',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                $jumlahBerhasil++;
            } catch (\Exception $e) {
                Log::error("Baris $baris gagal disimpan: " . $e->getMessage());
                $skipped++;
            }
        }

        return response()->json([
            'status' => $jumlahBerhasil > 0,
            'message' => $jumlahBerhasil > 0
                ? "$jumlahBerhasil admin berhasil diimport" . ($skipped ? ", $skipped dilewati." : '')
                : 'Tidak ada data baru yang berhasil diimport'
        ]);
    }


}
