<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Http\View\Composers\NotificationComposer;

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
        // Menyuntikkan data Notifikasi secara cerdas tanpa membebani controller
        // Sesuaikan array view di bawah dengan nama file layout header Anda
        View::composer(
            ['layouts.master', 'layouts.header', 'layouts.topbar'],
            NotificationComposer::class
        );
    }
}
