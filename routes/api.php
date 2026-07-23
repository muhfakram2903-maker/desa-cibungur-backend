<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\Auth\LoginController;
use App\Http\Controllers\Api\V1\Auth\RegisterController;
use App\Http\Controllers\Api\V1\BeritaController;
use App\Http\Controllers\Api\V1\AgendaController;
use App\Http\Controllers\Api\V1\PengumumanController;
use App\Http\Controllers\Api\V1\PengaduanController;
use App\Http\Controllers\Api\V1\GaleriController;
use App\Http\Controllers\Api\V1\UmkmController;
use App\Http\Controllers\Api\V1\StatistikController;

// ============================================================
// API v1 Routes
// ============================================================
Route::prefix('v1')->name('api.v1.')->group(function () {

    // ---- PUBLIC API (No Auth) ----
    Route::prefix('public')->name('public.')->group(function () {
        Route::get('berita', [BeritaController::class, 'index'])->name('berita.index');
        Route::get('berita/{slug}', [BeritaController::class, 'show'])->name('berita.show');
        Route::get('agenda', [AgendaController::class, 'index'])->name('agenda.index');
        Route::get('pengumuman', [PengumumanController::class, 'index'])->name('pengumuman.index');
        Route::get('galeri', [GaleriController::class, 'index'])->name('galeri.index');
        Route::get('galeri/{slug}', [GaleriController::class, 'show'])->name('galeri.show');
        Route::get('umkm', [UmkmController::class, 'index'])->name('umkm.index');
        Route::get('umkm/{slug}', [UmkmController::class, 'show'])->name('umkm.show');
        Route::get('statistik', [StatistikController::class, 'index'])->name('statistik.index');
        Route::get('pengaduan/track/{tiket}', [PengaduanController::class, 'track'])->name('pengaduan.track');
    });

    // ---- AUTH API ----
    Route::prefix('auth')->name('auth.')->group(function () {
        Route::post('login', [LoginController::class, 'login'])->name('login')->middleware('throttle:10,1');
        Route::post('register', [RegisterController::class, 'register'])->name('register');
        Route::post('forgot-password', [LoginController::class, 'forgotPassword'])->name('forgot-password');

        // Protected auth routes
        Route::middleware('auth:sanctum')->group(function () {
            Route::post('logout', [LoginController::class, 'logout'])->name('logout');
            Route::get('me', [LoginController::class, 'me'])->name('me');
            Route::post('change-password', [LoginController::class, 'changePassword'])->name('change-password');
        });
    });

    // ---- PROTECTED API ----
    Route::middleware('auth:sanctum')->group(function () {
        // Pengaduan (perlu login)
        Route::post('pengaduan', [PengaduanController::class, 'store'])->name('pengaduan.store');
        Route::get('pengaduan', [PengaduanController::class, 'index'])->name('pengaduan.index');
        Route::get('pengaduan/{id}', [PengaduanController::class, 'show'])->name('pengaduan.show');
        Route::post('pengaduan/{id}/respon', [PengaduanController::class, 'respond'])->name('pengaduan.respond');

        // User info
        Route::get('user', function (Request $request) {
            return $request->user()->load('roles');
        })->name('user');
    });
});
