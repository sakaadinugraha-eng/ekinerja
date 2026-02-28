<?php

namespace App\Livewire\Atasan;

use App\Models\User;
use Livewire\Component;
use Livewire\WithFileUploads; // Tambahkan ini
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage; // Tambahkan ini
use Illuminate\Validation\Rule;

class UserManager extends Component
{
    use WithFileUploads; // Gunakan trait untuk upload file

    public $id_user, $name, $email, $role, $password, $photo; // Tambahkan $photo
    public $isEdit = false;

    // Fungsi Reset Form
    public function resetInput()
    {
        $this->reset(['id_user', 'name', 'email', 'role', 'password', 'photo', 'isEdit']);
    }

    // Tampilkan Modal Tambah
    public function tambah()
    {
        $this->resetInput();
        $this->dispatch('open-modal-user');
    }

    // Tampilkan Modal Edit
    public function edit($id)
    {
        $user = User::findOrFail($id);
        $this->id_user = $user->id;
        $this->name = $user->name;
        $this->email = $user->email;
        $this->role = $user->role;
        $this->isEdit = true;
        $this->dispatch('open-modal-user');
    }

    public function simpan()
    {
        $rules = [
            'name'  => 'required|min:3',
            'email' => ['required', 'email', Rule::unique('users')->ignore($this->id_user)],
            'role'  => 'required',
            'photo' => 'nullable|image|max:1024', // Validasi foto (Max 1MB)
        ];

        if (!$this->isEdit) {
            $rules['password'] = 'required|min:8';
        }

        $this->validate($rules);

        $user = $this->id_user ? User::find($this->id_user) : new User();

        // Handle Upload Foto
        if ($this->photo) {
            // Hapus foto lama jika sedang edit dan ada foto lama
            if ($user->profile_photo_path) {
                Storage::disk('public')->delete($user->profile_photo_path);
            }
            $user->profile_photo_path = $this->photo->store('profile-photos', 'public');
        }

        $user->name = $this->name;
        $user->email = $this->email;
        $user->role = $this->role;

        if ($this->password) {
            $user->password = Hash::make($this->password);
        }

        $user->save();

        session()->flash('message', $this->isEdit ? 'User diperbarui!' : 'User ditambahkan!');
        $this->resetInput();
        $this->dispatch('close-modal-user');
    }

    public function hapus($id)
    {
        if ($id === auth()->id()) {
            session()->flash('error', 'Tidak bisa menghapus akun sendiri!');
            return;
        }

        $user = User::find($id);
        
        // Hapus file foto dari storage sebelum hapus data di DB
        if ($user->profile_photo_path) {
            Storage::disk('public')->delete($user->profile_photo_path);
        }

        $user->delete();
        session()->flash('message', 'User berhasil dihapus.');
    }

    public function toggleRole($id)
    {
        $user = User::findOrFail($id);
        if ($user->id === auth()->id()) {
            session()->flash('error', 'Gunakan halaman profil untuk ubah data sendiri!');
            return;
        }
        $user->role = ($user->role === 'atasan') ? 'pegawai' : 'atasan';
        $user->save();
    }

    public function render()
    {
        return view('livewire.atasan.user-manager', [
            'users' => User::orderBy('name', 'asc')->get()
        ])->layout('layouts.app');
    }
}