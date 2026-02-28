<?php

namespace App\Livewire\Atasan;

use App\Models\Activity;
use App\Notifications\KinerjaBaruNotification; // Import class notifikasi
use Livewire\Component;
use Carbon\Carbon;

class ValidasiKinerja extends Component
{
    public $selected_id;
    public $catatan_atasan;
    public $showModalTolak = false;

    public function setuju($id)
    {
        $act = Activity::findOrFail($id);
        $act->update(['status' => 'approved']);

        // --- NOTIFIKASI KE PEGAWAI ---
        $act->user->notify(new KinerjaBaruNotification([
            'title' => 'Kinerja Disetujui ✅',
            'message' => "Laporan kinerja Anda tanggal " . Carbon::parse($act->tanggal)->format('d/m/Y') . " telah disetujui.",
            'action_url' => route('kinerja.index'),
        ]));

        session()->flash('message', 'Kinerja berhasil disetujui!');
    }

    public function persiapkanTolak($id)
    {
        $this->selected_id = $id;
        $this->catatan_atasan = '';
        $this->showModalTolak = true;
    }

    public function konfirmasiTolak()
    {
        $this->validate([
            'catatan_atasan' => 'required|min:5'
        ], [
            'catatan_atasan.required' => 'Alasan penolakan wajib diisi.',
            'catatan_atasan.min' => 'Alasan minimal 5 karakter.'
        ]);

        $act = Activity::findOrFail($this->selected_id);
        $act->update([
            'status' => 'rejected',
            'catatan' => $this->catatan_atasan // Pastikan nama kolom di DB sesuai (catatan/catatan_atasan)
        ]);

        // --- NOTIFIKASI KE PEGAWAI ---
        $act->user->notify(new KinerjaBaruNotification([
            'title' => 'Kinerja Perlu Perbaikan ❌',
            'message' => "Laporan tanggal " . Carbon::parse($act->tanggal)->format('d/m/Y') . " ditolak. Catatan: " . $this->catatan_atasan,
            'action_url' => route('kinerja.index'),
        ]));

        $this->showModalTolak = false;
        $this->reset(['selected_id', 'catatan_atasan']);
        session()->flash('message', 'Kinerja berhasil ditolak dengan catatan.');
    }

    public function render()
    {
        return view('livewire.atasan.validasi-kinerja', [
            'pendingActivities' => Activity::where('status', 'pending')
                ->with('user')
                ->orderBy('tanggal', 'desc')
                ->get()
        ])->layout('layouts.app');
    }
}