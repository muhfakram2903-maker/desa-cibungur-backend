<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Public\HomeController;
use App\Http\Controllers\Public\BeritaController as PublicBeritaController;
use App\Http\Controllers\Public\ProfilController;
use App\Http\Controllers\Public\GaleriController as PublicGaleriController;
use App\Http\Controllers\Public\AgendaController as PublicAgendaController;
use App\Http\Controllers\Public\PengumumanController as PublicPengumumanController;
use App\Http\Controllers\Public\PengaduanController as PublicPengaduanController;
use App\Http\Controllers\Public\UmkmController as PublicUmkmController;
use App\Http\Controllers\Public\PotensiController as PublicPotensiController;
use App\Http\Controllers\Public\StatistikController;
use App\Http\Controllers\Public\TransparansiController;
use App\Http\Controllers\Public\KontakController;
use App\Http\Controllers\ProfileController;

// ============================================================
// PUBLIC ROUTES (No Auth Required)
// ============================================================
Route::get('/', [HomeController::class, 'index'])->name('home');

// Profil Desa
Route::prefix('profil')->name('profil.')->group(function () {
    Route::get('/', [ProfilController::class, 'index'])->name('index');
    Route::get('/sejarah', [ProfilController::class, 'sejarah'])->name('sejarah');
    Route::get('/visi-misi', [ProfilController::class, 'visiMisi'])->name('visi-misi');
    Route::get('/sambutan', [ProfilController::class, 'sambutan'])->name('sambutan');
    Route::get('/struktur-organisasi', [ProfilController::class, 'strukturOrganisasi'])->name('struktur');
    Route::get('/perangkat-desa', [ProfilController::class, 'perangkatDesa'])->name('perangkat');
    Route::get('/peta-desa', [ProfilController::class, 'petaDesa'])->name('peta');
});

// Berita
Route::prefix('berita')->name('berita.')->group(function () {
    Route::get('/', [PublicBeritaController::class, 'index'])->name('index');
    Route::get('/{slug}', [PublicBeritaController::class, 'show'])->name('show');
    Route::post('/{berita}/komentar', [PublicBeritaController::class, 'storeKomentar'])->name('komentar.store');
});

// Galeri
Route::prefix('galeri')->name('galeri.')->group(function () {
    Route::get('/', [PublicGaleriController::class, 'index'])->name('index');
    Route::get('/{slug}', [PublicGaleriController::class, 'show'])->name('show');
});

// Agenda
Route::prefix('agenda')->name('agenda.')->group(function () {
    Route::get('/', [PublicAgendaController::class, 'index'])->name('index');
    Route::get('/{slug}', [PublicAgendaController::class, 'show'])->name('show');
});

// Pengumuman
Route::prefix('pengumuman')->name('pengumuman.')->group(function () {
    Route::get('/', [PublicPengumumanController::class, 'index'])->name('index');
    Route::get('/{id}', [PublicPengumumanController::class, 'show'])->name('show');
});

// Potensi & UMKM
Route::prefix('potensi')->name('potensi.')->group(function () {
    Route::get('/', [PublicPotensiController::class, 'index'])->name('index');
    Route::get('/{slug}', [PublicPotensiController::class, 'show'])->name('show');
});

Route::prefix('umkm')->name('umkm.')->group(function () {
    Route::get('/', [PublicUmkmController::class, 'index'])->name('index');
    Route::get('/{slug}', [PublicUmkmController::class, 'show'])->name('show');
});

// Statistik & Transparansi
Route::get('/statistik-penduduk', [StatistikController::class, 'index'])->name('statistik.index');
Route::get('/transparansi-anggaran', [TransparansiController::class, 'index'])->name('transparansi.index');

// Pengaduan Publik
Route::prefix('pengaduan')->name('pengaduan.')->group(function () {
    Route::get('/', [PublicPengaduanController::class, 'index'])->name('index');
    Route::post('/', [PublicPengaduanController::class, 'store'])->name('store')->middleware('throttle:5,1');
    Route::get('/track', [PublicPengaduanController::class, 'track'])->name('track');
    Route::get('/track/{tiket}', [PublicPengaduanController::class, 'trackShow'])->name('track.show');
});

// Kontak
Route::prefix('kontak')->name('kontak.')->group(function () {
    Route::get('/', [KontakController::class, 'index'])->name('index');
    Route::post('/', [KontakController::class, 'send'])->name('send')->middleware('throttle:3,1');
});

// ============================================================
// AUTHENTICATED USER ROUTES
// ============================================================
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Pengaduan oleh user login
    Route::get('/my-pengaduan', [PublicPengaduanController::class, 'myPengaduan'])->name('pengaduan.my');
});

// ============================================================
// ADMIN ROUTES
// ============================================================
require __DIR__ . '/admin.php';

// Auth routes (Breeze)
require __DIR__ . '/auth.php';
