<?php

namespace App\Livewire\Atasan;

use App\Models\RefKegiatan;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads; // Pindahkan ke sini
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\RefKegiatanImport; // Jangan lupa import class ini

class ReferensiManager extends Component
{
    use WithPagination, WithFileUploads;

    public $id_ref, $nama_jenis, $nama_kegiatan, $output, $aktif = 'ya';
    public $isEdit = false;

    public $search = '';
    public $filterJenis = '';
    public $file_excel; 

   public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingFilterJenis()
    {
        $this->resetPage();
    }

    public function resetInput()
    {
        $this->reset(['id_ref', 'nama_jenis', 'nama_kegiatan', 'output', 'aktif', 'isEdit', 'file_excel']);
    }

    public function tambah()
    {
        $this->resetInput();
        $this->dispatch('open-modal-ref');
    }

    public function edit($id)
    {
        $ref = RefKegiatan::findOrFail($id);
        $this->id_ref = $ref->id;
        $this->nama_jenis = $ref->nama_jenis;
        $this->nama_kegiatan = $ref->nama_kegiatan;
        $this->output = $ref->output;
        $this->aktif = $ref->aktif;
        $this->isEdit = true;
        $this->dispatch('open-modal-ref');
    }

    public function simpan()
    {
        $this->validate([
            'nama_jenis' => 'required',
            'nama_kegiatan' => 'required|min:5',
            'output' => 'required',
            'aktif' => 'required|in:ya,tidak',
        ]);

        RefKegiatan::updateOrCreate(['id' => $this->id_ref], [
            'nama_jenis' => $this->nama_jenis,
            'nama_kegiatan' => $this->nama_kegiatan,
            'output' => $this->output,
            'aktif' => $this->aktif,
        ]);

        session()->flash('message', $this->isEdit ? 'Referensi diperbarui!' : 'Referensi ditambahkan!');
        $this->resetInput();
        $this->dispatch('close-modal-ref');
    }

    public function hapus($id)
    {
        RefKegiatan::find($id)->delete();
        session()->flash('message', 'Data referensi berhasil dihapus.');
    }

    public function importExcel()
    {
        $this->validate([
            'file_excel' => 'required|mimes:xlsx,xls,csv|max:10240',
        ]);

        try {
            RefKegiatan::truncate(); 

            Excel::import(new RefKegiatanImport, $this->file_excel->getRealPath());
            
            session()->flash('message', 'Data lama dihapus dan berhasil import data baru!');
            $this->resetInput();
            $this->dispatch('close-import-modal');
        } catch (\Exception $e) {
            session()->flash('error', 'Gagal import: ' . $e->getMessage());
        }
    }

    public function render()
    {
        $query = RefKegiatan::query()
            ->when($this->search, function($q) {
                $q->where(function($sub) {
                    $sub->where('nama_kegiatan', 'like', '%' . $this->search . '%')
                        ->orWhere('nama_jenis', 'like', '%' . $this->search . '%');
                });
            })
            ->when($this->filterJenis, function($q) {
                $q->where('nama_jenis', $this->filterJenis);
            });

        $listJenis = RefKegiatan::select('nama_jenis')->distinct()->pluck('nama_jenis');

        return view('livewire.atasan.referensi-manager', [
            'referensi' => $query->orderBy('updated_at', 'desc')->paginate(10),
            'listJenis' => $listJenis
        ])->layout('layouts.app');
    }
}