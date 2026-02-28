<?php

namespace App\Providers;

// BARIS INI YANG BIASANYA HILANG ATAU SALAH:
use Illuminate\Support\ServiceProvider; 
use Illuminate\Support\Facades\Gate;
use App\Models\User;

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
        // Gate untuk akses Atasan yang kita buat tadi
        Gate::define('akses-atasan', function (User $user) {
            return $user->role === 'atasan';
        });
    }
}