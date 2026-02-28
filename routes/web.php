<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\Kinerja\CreateKinerja;

Route::view('/', 'welcome');

// 1. Dashboard - Mengarah ke folder pages/dashboard.blade.php
Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

// 2. Input Kinerja - Menggunakan Full Page Component
Route::get('input-kinerja', CreateKinerja::class)
    ->middleware(['auth', 'verified'])
    ->name('kinerja.index');

// 3. Referensi Kegiatan - Mengarah ke folder pages/referensi/index.blade.php
Route::view('referensi-kegiatan', 'pages.referensi.index')
    ->middleware(['auth', 'verified'])
    ->name('referensi.index');

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

require __DIR__.'/auth.php';