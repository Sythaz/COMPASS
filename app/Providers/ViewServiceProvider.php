<?php

namespace App\Providers;

use App\Models\NotifikasiModel;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class ViewServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        View::composer('layouts.header', function ($view) {
            $userId = auth()->id();
            $notifikasis = NotifikasiModel::where('user_id', $userId)->orderBy('created_at', 'desc')->get();
            $jumlahBelumDibaca = NotifikasiModel::where('user_id', $userId)->where('status_notifikasi', 'Belum Dibaca')->count();

            $view->with([
                'notifikasi' => $notifikasis,
                'jumlahBelumDibaca' => $jumlahBelumDibaca,
            ]);
        });
    }
}
