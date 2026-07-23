<?php

namespace App\Providers;

use App\Interfaces\PendudukRepositoryInterface;
use App\Interfaces\PengaduanRepositoryInterface;
use App\Repositories\PendudukRepository;
use App\Repositories\PengaduanRepository;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     * Binding Repository Pattern - Interface ke Implementasi
     */
    public function register(): void
    {
        $this->app->bind(
            PendudukRepositoryInterface::class,
            PendudukRepository::class
        );

        $this->app->bind(
            PengaduanRepositoryInterface::class,
            PengaduanRepository::class
        );
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Fix untuk MySQL older version (default string length)
        Schema::defaultStringLength(191);

        // Set locale untuk Carbon (format tanggal Indonesia)
        \Carbon\Carbon::setLocale(config('app.locale', 'id'));

        // Set timezone aplikasi
        date_default_timezone_set(config('app.timezone', 'Asia/Jakarta'));
    }
}
