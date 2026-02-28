<?php

namespace App\Livewire\Profile;

use Livewire\Component;
use Livewire\WithFileUploads; // WAJIB TAMBAHKAN INI
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules\Password;
use App\Models\Activity;

class UpdateProfile extends Component
{
    use WithFileUploads; // WAJIB GUNAKAN TRAIT INI

    // Properti Form Profil
    public $name;
    public $email;
    public $photo; // Properti untuk file upload baru

    // Properti Form Password
    public $current_password;
    public $new_password;
    public $new_password_confirmation;

    public function mount()
    {
        $user = Auth::user();
        $this->name = $user->name;
        $this->email = $user->email;
    }

    public function updateProfile()
    {
        $user = Auth::user();
        
        $validatedData = $this->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'photo' => 'nullable|image|max:1024', // Validasi foto: Opsional, Gambar, Max 1MB
        ]);

        // Logika Upload Foto
        if ($this->photo) {
            // Hapus foto lama jika ada
            if ($user->profile_photo_path) {
                Storage::disk('public')->delete($user->profile_photo_path);
            }

            // Simpan foto baru ke folder 'profile-photos' di disk public
            $path = $this->photo->store('profile-photos', 'public');
            $user->update(['profile_photo_path' => $path]);
        }

        // Update data nama dan email
        $user->update([
            'name' => $this->name,
            'email' => $this->email,
        ]);

        $this->reset('photo'); // Reset input file setelah sukses
        session()->flash('profile_success', 'Informasi profil berhasil diperbarui.');
    }

    // Fungsi updatePassword tetap sama (tidak berubah)
    public function updatePassword()
    {
        $this->validate([
            'current_password' => 'required|current_password',
            'new_password' => ['required', 'confirmed', Password::min(8)],
        ]);

        Auth::user()->update([
            'password' => Hash::make($this->new_password),
        ]);

        $this->reset(['current_password', 'new_password', 'new_password_confirmation']);
        session()->flash('password_success', 'Password berhasil diubah.');
    }

    public function render()
    {
        $stats = [
            'total' => Activity::where('user_id', auth()->id())->count(),
            'pending' => Activity::where('user_id', auth()->id())->where('status', 'pending')->count(),
        ];

        return view('livewire.profile.update-profile', compact('stats'))
                ->layout('layouts.app');
    }
}