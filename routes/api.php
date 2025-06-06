<?php

use App\Http\Controllers\Api\DataLombaController;
use App\Http\Controllers\PrometheeRekomendasiController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/data-lomba/{id}', [DataLombaController::class, 'dataLomba'])->name('data-lomba');

Route::post('/rekomendasi', [PrometheeRekomendasiController::class, 'calculate']);
Route::get('/rekomendasi/test', function () {
    $controller = new PrometheeRekomendasiController();
    return $controller->calculateTest();
});
