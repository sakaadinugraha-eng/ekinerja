<?php

namespace App\Livewire\Layouts;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class Navbar extends Component
{
    // Fungsi untuk menandai notifikasi sudah dibaca
    public function markAsRead($id = null)
    {
        if ($id) {
            // Jika spesifik satu notifikasi diklik
            $notification = auth()->user()->notifications()->find($id);
            if ($notification) {
                $notification->markAsRead();
                
                // Redirect ke halaman tujuan (misal halaman validasi atasan)
                return redirect($notification->data['action_url'] ?? route('dashboard'));
            }
        } else {
            // Jika tombol "Tandai Baca Semua" diklik
            auth()->user()->unreadNotifications->markAsRead();
        }
    }

    public function logout()
    {
        Auth::logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();

        return redirect('/login');
    }

    public function render()
    {
        return view('livewire.layouts.navbar');
    }
}