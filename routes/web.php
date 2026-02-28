<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\Kinerja\CreateKinerja;
use App\Livewire\Atasan\ValidasiKinerja;
use App\Livewire\Atasan\UserManager;
use App\Livewire\Profile\UpdateProfile;
use App\Livewire\Atasan\ReferensiManager; 
use App\Models\Activity;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

Route::view('/', 'welcome');

// --- AKSES SEMUA PEGAWAI (Auth & Verified Required) ---
Route::middleware(['auth', 'verified'])->group(function () {
    
    // 1. Dashboard
    Route::view('dashboard', 'dashboard')->name('dashboard');

    // 2. Input Kinerja (Pegawai)
    Route::get('input-kinerja', CreateKinerja::class)->name('kinerja.index');

    // 3. Profile
    Route::view('profile', 'profile')->name('profile');
    Route::get('/profile', \App\Livewire\Profile\UpdateProfile::class)->name('profile');

    // 4. Fitur Cetak PDF (Disederhanakan)
    Route::get('cetak-pdf/{bulan}', function ($bulan) {
        $user = auth()->user();
        
        // Menggunakan Eloquent untuk ambil data bulan terpilih
        $activities = Activity::where('user_id', $user->id)
            ->where('tanggal', 'like', $bulan . '%')
            ->orderBy('tanggal', 'asc')
            ->get();

        $pdf = Pdf::loadView('pages.kinerja.pdf', [
            'user' => $user,
            'activities' => $activities,
            'periode' => Carbon::parse($bulan)->translatedFormat('F Y'),
        ])->setPaper('a4', 'portrait');

        return $pdf->stream("Laporan-Kinerja-{$bulan}.pdf");
    })->name('kinerja.cetak');

});

// --- AKSES KHUSUS ATASAN / ADMIN (Role Protected) ---
// Pastikan Gate 'akses-atasan' sudah terdaftar di AppServiceProvider
Route::middleware(['auth', 'verified', 'can:akses-atasan'])->group(function () {

    // 5. Validasi Kinerja (Halaman Persetujuan)
    Route::get('validasi-kinerja', ValidasiKinerja::class)->name('atasan.validasi');

    // 6. Referensi Kegiatan
    Route::get('referensi-kegiatan', ReferensiManager::class)->name('referensi.index');

    // 7. Manajemen Pengguna (Role Management)
    Route::get('manage-users', UserManager::class)->name('atasan.users');

});

// Tambahkan ini di routes/web.php
Route::post('/logout', function () {
    Auth::logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();

    // Ubah baris di bawah ini:
    return redirect('/login'); 
})->name('logout');

require __DIR__.'/auth.php';