<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class NotifikasiController extends Controller
{
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
}
