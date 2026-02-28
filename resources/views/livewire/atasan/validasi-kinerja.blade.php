<div class="space-y-6">
    <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex justify-between items-center">
        <div>
            <h2 class="text-xl font-black text-gray-800 uppercase tracking-tight">Persetujuan Kinerja</h2>
            <p class="text-xs text-gray-400 font-bold uppercase mt-1">Daftar laporan yang butuh validasi Anda</p>
        </div>
        @if (session()->has('message'))
            <span class="text-xs font-bold text-green-600 bg-green-50 px-3 py-1 rounded-lg border border-green-100">{{ session('message') }}</span>
        @endif
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <table class="w-full text-left">
            <thead class="bg-gray-50 text-[10px] font-black text-gray-400 uppercase tracking-widest border-b">
                <tr>
                    <th class="px-6 py-4">Pegawai</th>
                    <th class="px-6 py-4">Detail Kegiatan</th>
                    <th class="px-6 py-4 text-center">Durasi</th>
                    <th class="px-6 py-4 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($pendingActivities as $act)
                <tr class="hover:bg-gray-50/50 transition duration-150">
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-3">
                            @if ($act->user->profile_photo_path)
                                <img src="{{ Storage::url($act->user->profile_photo_path) }}" class="w-9 h-9 rounded-xl object-cover shadow-sm">
                            @else
                                <div class="w-9 h-9 rounded-xl bg-indigo-100 flex items-center justify-center text-indigo-600 font-black text-xs uppercase">
                                    {{ substr($act->user->name, 0, 1) }}
                                </div>
                            @endif
                            <div class="flex flex-col">
                                <span class="font-black text-indigo-900 leading-none mb-1">{{ $act->user->name }}</span>
                                <span class="text-[9px] text-gray-400 font-bold uppercase tracking-tighter">{{ $act->user->role }}</span>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        <p class="font-bold text-gray-800 text-sm">{{ $act->nama_kegiatan }}</p>
                        <p class="text-[10px] text-gray-400 font-bold italic">{{ \Carbon\Carbon::parse($act->tanggal)->translatedFormat('d F Y') }}</p>
                    </td>
                    <td class="px-6 py-4 text-center">
                        <span class="bg-indigo-50 text-indigo-600 px-2 py-1 rounded font-black text-xs">{{ $act->menit }} m</span>
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex items-center justify-center gap-2">
                            <button wire:click="setuju({{ $act->id }})" 
                                    class="bg-emerald-500 hover:bg-emerald-600 text-white px-4 py-2 rounded-xl text-[10px] font-black uppercase transition shadow-lg shadow-emerald-100">
                                Setuju
                            </button>
                            <button wire:click="persiapkanTolak({{ $act->id }})" 
                                    class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-xl text-[10px] font-black uppercase transition shadow-lg shadow-red-100">
                                Tolak
                            </button>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="px-6 py-12 text-center">
                        <p class="text-sm font-bold text-gray-400 uppercase tracking-widest">☕ Semua kinerja sudah divalidasi!</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
        @if($showModalTolak)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-gray-900/50 backdrop-blur-sm">
            <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md overflow-hidden animate-in fade-in zoom-in duration-200">
                <div class="bg-red-600 px-6 py-4 text-white flex justify-between items-center">
                    <h3 class="font-black uppercase text-xs tracking-widest">Berikan Alasan Penolakan</h3>
                    <button wire:click="$set('showModalTolak', false)" class="text-white hover:scale-110 transition">✕</button>
                </div>
                
                <div class="p-6 space-y-4">
                    <div>
                        <label class="block text-[10px] font-black uppercase text-gray-400 mb-2">Catatan Perbaikan</label>
                        <textarea wire:model="catatan_atasan" 
                                rows="4" 
                                placeholder="Contoh: Deskripsi kegiatan kurang jelas atau lampiran tidak sesuai..."
                                class="w-full border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-red-500/20 focus:border-red-500 bg-gray-50"></textarea>
                        @error('catatan_atasan') <p class="text-[10px] text-red-500 font-bold mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="flex gap-3 pt-2">
                        <button type="button" wire:click="$set('showModalTolak', false)" 
                                class="flex-1 py-3 text-xs font-black uppercase text-gray-500 bg-gray-100 hover:bg-gray-200 rounded-xl">Batal</button>
                        <button type="button" wire:click="konfirmasiTolak" 
                                class="flex-[2] py-3 bg-red-600 text-white rounded-xl text-xs font-black uppercase shadow-lg shadow-red-200 hover:bg-red-700 transition flex justify-center items-center gap-2">
                            <span wire:loading.remove wire:target="konfirmasiTolak">Kirim Penolakan</span>
                            <div wire:loading wire:target="konfirmasiTolak" class="animate-spin h-4 w-4 border-2 border-white border-t-transparent rounded-full"></div>
                        </button>
                    </div>
                </div>
            </div>
        </div>
        @endif
    </div>
</div>