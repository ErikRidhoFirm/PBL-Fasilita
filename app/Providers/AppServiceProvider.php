<?php

namespace App\Providers;

use App\Models\Peran;
use App\Models\Pengguna;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use App\Models\RiwayatLaporanFasilitas;
use Illuminate\Support\ServiceProvider;
use App\Observers\RiwayatLaporanFasilitasObserver;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // if (config('app.env') === 'local') {//Add commentMore actions
        //     URL::forceScheme('https');
        // }

        // mengambil data user yang telah login dan menampilkannya ke layout main
        View::composer('*', function ($view) {
            $view->with('authUser', Auth::user());
        });

        RiwayatLaporanFasilitas::observe(RiwayatLaporanFasilitasObserver::class);
    }
}
