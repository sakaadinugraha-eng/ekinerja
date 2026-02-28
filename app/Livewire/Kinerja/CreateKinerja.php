<?php

namespace App\Livewire\Kinerja;

use App\Models\Activity;
use App\Models\RefKegiatan; // Sesuaikan dengan model referensi kamu (ActivityReference/RefKegiatan)
use Livewire\Component;
use Livewire\Attributes\Validate;
use Carbon\Carbon;

class CreateKinerja extends Component
{
    // Filter View
    public $bulan;

    // Form Input
    public $id_edit = null; // TAMBAHAN: Untuk menyimpan ID saat mode edit
    public $tanggal;
    public $selected_kegiatan_id;
    public $jenis_kegiatan;
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

    // Reset form saat modal ditutup atau selesai simpan
    public function resetInput()
    {
        $this->reset(['id_edit', 'selected_kegiatan_id', 'jenis_kegiatan', 'nama_kegiatan', 'keterangan', 'catatan', 'output', 'menit']);
        // Tanggal jangan direset biar user gak capek milih tanggal lagi
    }

    public function updatedSelectedKegiatanId($id)
    {
        // Sesuaikan Model ini dengan nama Model Referensi kamu (ActivityReference atau RefKegiatan)
        $ref = RefKegiatan::find($id);
        
        if ($ref) {
            // Jika referensi punya kolom 'nama_jenis', sesuaikan. 
            // Kalau di tabel cuma ada 'nama_kegiatan', hapus bagian jenis_kegiatan
            $this->jenis_kegiatan = $ref->nama_jenis ?? '-'; 
            $this->nama_kegiatan = $ref->nama_kegiatan;
            $this->output = $ref->output ?? 'Kegiatan'; // Default jika output null
            $this->menit = $ref->menit_standar ?? 0; // Otomatis isi menit standar jika ada
        } 
    }

    // FUNGSI BARU: Menangani tombol Edit ditekan
    public function edit($id)
    {
        $act = Activity::findOrFail($id);

        // Pastikan yang diedit adalah milik user sendiri
        if ($act->user_id != auth()->id()) {
            return;
        }

        $this->id_edit = $act->id;
        $this->tanggal = $act->tanggal;
        
        // Kita cari ID referensi berdasarkan nama kegiatan (Manual matching)
        // Atau jika tabel activity menyimpan referensi_id, pakai itu lebih baik.
        // Di sini saya asumsikan manual matching atau null jika custom
        $ref = RefKegiatan::where('nama_kegiatan', $act->nama_kegiatan)->first();
        $this->selected_kegiatan_id = $ref ? $ref->id : null;

        $this->jenis_kegiatan = $act->jenis_kegiatan;
        $this->nama_kegiatan = $act->nama_kegiatan;
        $this->keterangan = $act->keterangan;
        $this->catatan = $act->catatan;
        $this->output = $act->output;
        $this->menit = $act->menit;

        // Buka Modal (Opsional, jika menggunakan alpine watcher di view)
        // $this->dispatch('open-modal'); 
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
            $this->addError('tanggal', 'Hari Sabtu & Minggu adalah hari libur (0 menit).');
            return;
        }

        // HITUNG TOTAL: Kecualikan ID yang sedang diedit agar tidak double counting
        $query = Activity::where('user_id', auth()->id())->where('tanggal', $this->tanggal);
        
        if ($this->id_edit) {
            $query->where('id', '!=', $this->id_edit);
        }

        $totalSudahInput = $query->sum('menit');

        $this->validate([
            'tanggal' => 'required|date',
            'keterangan' => 'required|min:5',
            'output' => 'required',
            'menit' => [
                'required', 'numeric', 'min:1',
                function ($attribute, $value, $fail) use ($maxMenit, $totalSudahInput) {
                    if (($totalSudahInput + $value) > $maxMenit) {
                        $sisa = max(0, $maxMenit - $totalSudahInput);
                        $fail("Over kuota! Batas: $maxMenit m. Sisa kuota: $sisa m.");
                    }
                },
            ],
        ], [
            'keterangan.required' => 'Keterangan wajib diisi.',
            'menit.required' => 'Durasi wajib diisi.',
        ]);

        // LOGIKA UPDATE ATAU CREATE (Pakai updateOrCreate)
        Activity::updateOrCreate(
            ['id' => $this->id_edit], // Kunci pencarian (jika null, akan create baru)
            [
                'user_id' => auth()->id(),
                'tanggal' => $this->tanggal,
                'jenis_kegiatan' => $this->jenis_kegiatan ?? 'Harian',
                'nama_kegiatan' => $this->nama_kegiatan ?? $this->keterangan, // Fallback jika kosong
                'keterangan' => $this->keterangan,
                'catatan' => $this->catatan,
                'output' => $this->output,
                'menit' => $this->menit,
                'status' => 'draft'
            ]
        );

        $this->resetInput(); // Reset form
        $this->dispatch('close-modal'); // Tutup modal di view
        
        session()->flash('message', $this->id_edit ? 'Kinerja berhasil diperbarui!' : 'Kinerja berhasil disimpan!');
    }

    public function hapus($id)
    {
        $activity = Activity::find($id);
        if ($activity && $activity->user_id == auth()->id()) {
            $activity->delete();
            session()->flash('message', 'Data berhasil dihapus.');
        }
    }

    public function render()
    {
        // 1. DATA TABEL (Berdasarkan BULAN filter)
        $parts = explode('-', $this->bulan);
        $year = $parts[0] ?? date('Y');
        $month = $parts[1] ?? date('m');

        $activities = auth()->user()->activities()
            ->whereYear('tanggal', $year)
            ->whereMonth('tanggal', $month)
            ->orderBy('tanggal', 'desc')
            ->get();

        // 2. DATA STATISTIK (Berdasarkan TANGGAL INPUT untuk progress bar)
        $tglFokus = $this->tanggal ?? date('Y-m-d');
        
        // Ambil data hari itu untuk hitung progress bar
        $dailyStatsActivity = auth()->user()->activities()->where('tanggal', $tglFokus)->get();
        
        $totalMenit = $dailyStatsActivity->sum('menit');
        $targetMenit = $this->getMaxMenit($tglFokus);
        $persentase = ($targetMenit > 0) ? round(($totalMenit / $targetMenit) * 100) : 0;
        
        // Kirim List Referensi (Pastikan nama Modelnya benar, misal ActivityReference)
        // Jika nama modelmu RefKegiatan, pakai RefKegiatan. Jika ActivityReference, ganti.
        $listReferensi = RefKegiatan::all(); 

        return view('livewire.kinerja.create-kinerja', [
            'activities' => $activities,
            'listReferensi' => $listReferensi,
            'stats' => [
                'total_menit' => $totalMenit,
                'target_menit' => $targetMenit,
                'sisa_menit' => max(0, $targetMenit - $totalMenit),
                'persentase' => min(100, $persentase),
                'label_hari' => Carbon::parse($tglFokus)->translatedFormat('l'),
                'is_libur' => ($targetMenit <= 0)
            ]
        ])->layout('layouts.app');
    }
}