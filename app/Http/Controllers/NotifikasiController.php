<?php

namespace App\Http\Controllers;

use App\Models\NotifikasiModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class NotifikasiController extends Controller
{
    public function index()
    {
        $breadcrumb = (object) [
            'title' => 'Notifikasi',
            'list'  => ['Home', 'Notifikasi']
        ];

        $userId = auth()->id();
        $notifikasi = NotifikasiModel::where('user_id', $userId)->orderBy('created_at', 'desc')->get();

        return view('notifikasi.index', compact('breadcrumb', 'notifikasi'));
    }
    public function bacaSemuaNotifikasi()
    {
        $userId = auth()->id();

        DB::table('t_notifikasi')
            ->where('user_id', $userId)
            ->where('status_notifikasi', 'Belum Dibaca')
            ->update(['status_notifikasi' => 'Sudah Dibaca']);

        return response()->json(['success' => true]);
    }

    public function tandaiSudahDibacaNotifikasi($notifikasiId)
    {
        DB::table('t_notifikasi')
            ->where('notifikasi_id', $notifikasiId)
            ->update(['status_notifikasi' => 'Sudah Dibaca']);

        return response()->json(['success' => true]);
    }

    public function hapusNotifikasi($notifikasiId)
    {
        $userId = auth()->id();

        $notifikasi = NotifikasiModel::where('notifikasi_id', $notifikasiId)
            ->where('user_id', $userId)
            ->first();

        if (!$notifikasi) {
            return response()->json(['success' => false, 'message' => 'Notifikasi tidak ditemukan'], 404);
        }

        $notifikasi->delete();

        return response()->json(['success' => true]);
    }

    public function hapusBanyakNotifikasi(Request $request)
    {
        $ids = $request->input('ids');

        if (!$ids || !is_array($ids)) {
            return response()->json(['success' => false, 'message' => 'Tidak ada notifikasi yang dipilih']);
        }

        $userId = auth()->id();
        $deleted = NotifikasiModel::whereIn('notifikasi_id', $ids)
            ->where('user_id', $userId)
            ->delete();

        return response()->json([
            'success' => true,
            'deleted_ids' => $ids
        ]);
    }
}
