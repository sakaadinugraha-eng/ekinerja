<?php

namespace App\Livewire\Kinerja;

use App\Models\Activity;
use App\Models\RefKegiatan;
use Livewire\Component;
use Livewire\WithPagination;
use Carbon\Carbon;

class CreateKinerja extends Component
{
    // Filter View
    public $bulan;

    // Form Input
    public $id_edit = null;
    public $tanggal;
    public $selected_jenis = ''; // Menampung pilihan Jenis
    public $selected_kegiatan_id;
    public $list_kegiatan_filtered = []; // Menampung hasil filter kegiatan
    
    public $nama_kegiatan;
    public $keterangan;
    public $catatan;
    public $output;
    public $menit;

    public function mount()
    {
        $this->bulan = date('Y-m');
        $this->tanggal = date('Y-m-d');
    }

    public function resetInput()
    {
        $this->reset([
            'id_edit', 'selected_jenis', 'selected_kegiatan_id', 
            'nama_kegiatan', 'keterangan', 'catatan', 'output', 'menit',
            'list_kegiatan_filtered'
        ]);
    }

    // --- LOGIC DEPENDENT DROPDOWN ---

    // 1. Ketika Jenis Dipilih
    public function updatedSelectedJenis($value)
    {
        if ($value) {
            // Ambil kegiatan berdasarkan jenis, ubah ke Array agar Alpine.js lancar membaca
            $this->list_kegiatan_filtered = RefKegiatan::where('nama_jenis', $value)
                ->where('aktif', 'ya')
                ->get()
                ->toArray();
        } else {
            $this->list_kegiatan_filtered = [];
        }
        
        // Reset pilihan kegiatan jika jenis diganti
        $this->selected_kegiatan_id = null;
        $this->nama_kegiatan = null;
        $this->output = null;
    }

    // 2. Ketika ID Kegiatan Dipilih (Dari Alpine.js Search)
    public function updatedSelectedKegiatanId($value)
    {
        if ($value) {
            $ref = RefKegiatan::find($value);
            if ($ref) {
                $this->nama_kegiatan = $ref->nama_kegiatan;
                $this->output = $ref->output;
            }
        }
    }

    // --- LOGIC CRUD ---

    public function edit($id)
    {
        $act = Activity::findOrFail($id);

        if ($act->user_id != auth()->id()) return;

        if (!in_array($act->status, ['draft', 'rejected']) && auth()->user()->role !== 'atasan') {
            session()->flash('error', 'Data terkunci dan tidak bisa diedit.');
            return;
        }

        $this->id_edit = $act->id;
        $this->tanggal = $act->tanggal;
        
        // Cari referensinya untuk menentukan jenisnya apa
        $ref = RefKegiatan::where('nama_kegiatan', $act->nama_kegiatan)->first();
        if($ref) {
            $this->selected_jenis = $ref->nama_jenis;
            // Trigger manual fungsi update jenis agar list_kegiatan_filtered terisi
            $this->updatedSelectedJenis($this->selected_jenis);
            $this->selected_kegiatan_id = $ref->id;
        }

        $this->nama_kegiatan = $act->nama_kegiatan;
        $this->keterangan = $act->keterangan;
        $this->catatan = $act->catatan;
        $this->output = $act->output;
        $this->menit = $act->menit;
    }

    private function getMaxMenit($date)
    {
        if (!$date) return 0;
        $dayName = Carbon::parse($date)->format('l');
        return match ($dayName) {
            'Friday' => 390,
            'Saturday', 'Sunday' => 0,
            default => 465,
        };
    }

    public function simpan()
    {
        $maxMenit = $this->getMaxMenit($this->tanggal);
        if ($maxMenit <= 0) {
            $this->addError('tanggal', 'Hari Sabtu & Minggu libur.');
            return;
        }

        if ($this->id_edit) {
            $existing = Activity::find($this->id_edit);
            if ($existing && !in_array($existing->status, ['draft', 'rejected']) && auth()->user()->role !== 'atasan') {
                session()->flash('error', 'Gagal menyimpan. Data terkunci.');
                $this->resetInput();
                $this->dispatch('close-modal');
                return;
            }
        }

        $query = Activity::where('user_id', auth()->id())->where('tanggal', $this->tanggal);
        if ($this->id_edit) $query->where('id', '!=', $this->id_edit);
        $totalSudahInput = $query->sum('menit');

        $this->validate([
            'tanggal' => 'required|date',
            'selected_jenis' => 'required',
            'selected_kegiatan_id' => 'required',
            'keterangan' => 'required|min:5',
            'output' => 'required',
            'menit' => [
                'required', 'numeric', 'min:1',
                function ($attribute, $value, $fail) use ($maxMenit, $totalSudahInput) {
                    if (($totalSudahInput + $value) > $maxMenit) {
                        $sisa = max(0, $maxMenit - $totalSudahInput);
                        $fail("Over kuota! Sisa: $sisa m.");
                    }
                },
            ],
        ]);

        Activity::updateOrCreate(
            ['id' => $this->id_edit],
            [
                'user_id' => auth()->id(),
                'tanggal' => $this->tanggal,
                'jenis_kegiatan' => $this->selected_jenis,
                'nama_kegiatan' => $this->nama_kegiatan,
                'keterangan' => $this->keterangan,
                'catatan' => $this->catatan,
                'output' => $this->output,
                'menit' => $this->menit,
                'status' => (auth()->user()->role === 'atasan') ? 'approved' : 'draft'
            ]
        );

        $this->resetInput();
        $this->dispatch('close-modal');
        session()->flash('message', 'Kinerja berhasil disimpan!');
    }
    
    public function ajukanKeAtasan()
    {
        $parts = explode('-', $this->bulan);
        $year = $parts[0] ?? date('Y');
        $month = $parts[1] ?? date('m');

        // Ambil data yang akan diajukan
        $query = \App\Models\Activity::where('user_id', auth()->id())
            ->whereYear('tanggal', $year)
            ->whereMonth('tanggal', $month)
            ->whereIn('status', ['draft', 'rejected']);

        $count = $query->count();
        $affected = $query->update(['status' => 'pending']);

        if ($affected > 0) {
            // --- LOGIKA NOTIFIKASI START ---
            $admins = \App\Models\User::where('role', 'atasan')->get();
            $namaPegawai = auth()->user()->name;
            $periode = Carbon::parse($this->bulan)->translatedFormat('F Y');

            foreach ($admins as $admin) {
                $admin->notify(new \App\Notifications\KinerjaBaruNotification([
                    'title' => 'Pengajuan Kinerja Baru',
                    'message' => "Pegawai $namaPegawai mengajukan $affected kegiatan untuk periode $periode.",
                    'action_url' => route('atasan.validasi'), // Arahkan atasan ke halaman validasi
                ]));
            }
            // --- LOGIKA NOTIFIKASI END ---

            session()->flash('message', "Berhasil mengajukan $affected kegiatan ke atasan!");
        } else {
            session()->flash('error', 'Tidak ada data draft untuk diajukan bulan ini.');
        }
    }

    public function render()
    {
        $parts = explode('-', $this->bulan);
        $year = $parts[0] ?? date('Y');
        $month = $parts[1] ?? date('m');

        $activities = auth()->user()->activities()
            ->whereYear('tanggal', $year)
            ->whereMonth('tanggal', $month)
            ->orderBy('tanggal', 'desc')
            ->get();

        $tglFokus = $this->tanggal ?? date('Y-m-d');
        $dailyTotalMenit = auth()->user()->activities()->where('tanggal', $tglFokus)->sum('menit');
        $targetMenit = $this->getMaxMenit($tglFokus);
        
        // Ambil list jenis unik untuk dropdown
        $listJenis = RefKegiatan::where('aktif', 'ya')->select('nama_jenis')->distinct()->pluck('nama_jenis');

        return view('livewire.kinerja.create-kinerja', [
            'activities' => $activities,
            'listJenis' => $listJenis,
            'stats' => [
                'total_menit' => $dailyTotalMenit,
                'target_menit' => $targetMenit,
                'sisa_menit' => max(0, $targetMenit - $dailyTotalMenit),
                'persentase' => ($targetMenit > 0) ? min(100, round(($dailyTotalMenit / $targetMenit) * 100)) : 0,
                'label_hari' => Carbon::parse($tglFokus)->translatedFormat('l'),
                'is_libur' => ($targetMenit <= 0)
            ]
        ])->layout('layouts.app');
    }
}