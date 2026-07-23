<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\PendudukController;
use App\Http\Controllers\Admin\BeritaController;
use App\Http\Controllers\Admin\GaleriController;
use App\Http\Controllers\Admin\AgendaController;
use App\Http\Controllers\Admin\PengumumanController;
use App\Http\Controllers\Admin\PengaduanController;
use App\Http\Controllers\Admin\UmkmController;
use App\Http\Controllers\Admin\PotensiController;
use App\Http\Controllers\Admin\ApbdesController;
use App\Http\Controllers\Admin\PerangkatDesaController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\SliderController;
use App\Http\Controllers\Admin\FaqController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\KontakMasukController;

Route::prefix('admin')
    ->name('admin.')
    ->middleware(['auth', 'verified'])
    ->group(function () {

        // Dashboard
        Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

        // Data Penduduk
        Route::resource('penduduk', PendudukController::class);
        Route::post('penduduk/import', [PendudukController::class, 'import'])->name('penduduk.import');
        Route::get('penduduk/export/excel', [PendudukController::class, 'exportExcel'])->name('penduduk.export.excel');
        Route::get('penduduk/export/pdf', [PendudukController::class, 'exportPdf'])->name('penduduk.export.pdf');

        // Berita
        Route::resource('berita', BeritaController::class);
        Route::patch('berita/{berita}/publish', [BeritaController::class, 'publish'])->name('berita.publish');
        Route::patch('berita/{berita}/archive', [BeritaController::class, 'archive'])->name('berita.archive');

        // Galeri
        Route::resource('galeri', GaleriController::class);
        Route::resource('galeri.item', GaleriController::class);
        Route::post('galeri/{galeri}/upload-multi', [GaleriController::class, 'uploadMulti'])->name('galeri.upload-multi');

        // Agenda
        Route::resource('agenda', AgendaController::class);

        // Pengumuman
        Route::resource('pengumuman', PengumumanController::class);

        // Pengaduan
        Route::resource('pengaduan', PengaduanController::class)->only(['index', 'show', 'edit', 'update']);
        Route::patch('pengaduan/{pengaduan}/status', [PengaduanController::class, 'updateStatus'])->name('pengaduan.status');
        Route::post('pengaduan/{pengaduan}/respon', [PengaduanController::class, 'storeRespon'])->name('pengaduan.respon');

        // UMKM
        Route::resource('umkm', UmkmController::class);

        // Potensi Desa
        Route::resource('potensi', PotensiController::class);

        // APBDes
        Route::resource('apbdes', ApbdesController::class);
        Route::resource('apbdes.item', ApbdesController::class)->parameters(['item' => 'apbdes_item']);

        // Perangkat Desa
        Route::resource('perangkat-desa', PerangkatDesaController::class);

        // User Management
        Route::resource('users', UserController::class);
        Route::patch('users/{user}/toggle-active', [UserController::class, 'toggleActive'])->name('users.toggle-active');

        // Role Management
        Route::resource('roles', RoleController::class);

        // Settings
        Route::get('settings', [SettingController::class, 'index'])->name('settings.index');
        Route::post('settings', [SettingController::class, 'update'])->name('settings.update');
        Route::get('settings/{group}', [SettingController::class, 'show'])->name('settings.show');

        // Slider
        Route::resource('sliders', SliderController::class);
        Route::post('sliders/reorder', [SliderController::class, 'reorder'])->name('sliders.reorder');

        // FAQ
        Route::resource('faqs', FaqController::class);

        // Kontak Masuk
        Route::resource('kontak-masuk', KontakMasukController::class)->only(['index', 'show', 'destroy']);
        Route::patch('kontak-masuk/{kontak}/mark-read', [KontakMasukController::class, 'markRead'])->name('kontak-masuk.mark-read');

        // Reports
        Route::prefix('laporan')->name('laporan.')->group(function () {
            Route::get('/', [ReportController::class, 'index'])->name('index');
            Route::get('/penduduk', [ReportController::class, 'penduduk'])->name('penduduk');
            Route::get('/pengaduan', [ReportController::class, 'pengaduan'])->name('pengaduan');
            Route::get('/penduduk/export', [ReportController::class, 'exportPenduduk'])->name('penduduk.export');
            Route::get('/pengaduan/export', [ReportController::class, 'exportPengaduan'])->name('pengaduan.export');
        });
    });
