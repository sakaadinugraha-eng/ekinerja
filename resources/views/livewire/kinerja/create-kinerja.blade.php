<div x-data="{ showModal: false }" 
     x-on:close-modal.window="showModal = false" 
     x-on:open-modal.window="showModal = true" 
     class="space-y-6 font-sans">

    <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-100 flex flex-col md:flex-row justify-between items-center gap-4">
        <div>
            <h2 class="text-xl font-bold text-gray-800 tracking-tight">E-Kinerja</h2>
            <div class="flex items-center gap-2 mt-1">
                <span class="text-xs font-bold text-gray-400 uppercase">Periode:</span>
                <input type="month" wire:model.live="bulan" class="border-gray-200 bg-gray-50 rounded-lg text-sm font-semibold text-indigo-700 focus:ring-indigo-500 py-1 cursor-pointer">
            </div>
        </div>

        <div class="flex items-center gap-3 bg-indigo-50 px-4 py-2 rounded-xl border border-indigo-100 w-full md:w-auto shadow-sm">
            <div class="flex flex-col">
                <span class="text-[10px] font-bold text-indigo-400 uppercase tracking-wider">Tanggal Input</span>
                <input type="date" wire:model.live="tanggal" class="border-none bg-transparent p-0 focus:ring-0 text-sm font-black text-indigo-800 cursor-pointer">
            </div>
            <div class="h-8 w-px bg-indigo-200"></div>
            <div class="text-right">
                <span class="block text-[10px] font-bold text-indigo-400 uppercase">Status</span>
                <span class="block text-xs font-bold {{ $stats['is_libur'] ? 'text-red-500' : 'text-green-600' }}">
                    {{ $stats['is_libur'] ? 'LIBUR' : 'AKTIF' }}
                </span>
            </div>
        </div>
    </div>
    
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 relative overflow-hidden group hover:shadow-md transition-shadow">
            <div class="flex justify-between items-start relative z-10">
                <div>
                    <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest">Target Tgl {{ \Carbon\Carbon::parse($tanggal)->format('d') }}</p>
                    <h3 class="text-3xl font-extrabold text-gray-800 mt-1">
                        {{ $stats['total_menit'] }} <span class="text-sm text-gray-400 font-normal">/ {{ $stats['target_menit'] }}</span>
                    </h3>
                </div>
                <div class="p-2 {{ $stats['is_libur'] ? 'bg-red-50 text-red-500' : 'bg-blue-50 text-blue-600' }} rounded-lg">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
            </div>
            <div class="w-full bg-gray-100 rounded-full h-2 mt-4">
                <div class="h-2 rounded-full transition-all duration-700 ease-out {{ $stats['persentase'] >= 100 ? 'bg-green-500' : 'bg-indigo-600' }}" style="width: {{ $stats['persentase'] }}%"></div>
            </div>
        </div>

        <div class="{{ $stats['is_libur'] ? 'bg-red-50 border-red-200' : 'bg-white border-gray-100' }} p-6 rounded-xl shadow-sm border transition-colors duration-300">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-[10px] {{ $stats['is_libur'] ? 'text-red-400' : 'text-gray-400' }} font-bold uppercase tracking-widest">Sisa Kuota</p>
                    <h3 class="text-2xl font-extrabold mt-1 {{ $stats['is_libur'] ? 'text-red-700' : 'text-green-600' }}">
                        {{ $stats['is_libur'] ? 'OFF' : $stats['sisa_menit'] . ' m' }}
                    </h3>
                </div>
                <div class="mt-2 text-xs font-bold {{ $stats['is_libur'] ? 'text-red-500' : 'text-gray-400' }} bg-white/50 px-2 py-1 rounded">
                    {{ $stats['label_hari'] }}
                </div>
            </div>
        </div>

        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 flex flex-col justify-center">
            <button @click="showModal = true; $wire.resetInput()" 
                    @if($stats['is_libur']) disabled @endif
                    class="w-full {{ $stats['is_libur'] ? 'bg-gray-300 cursor-not-allowed' : 'bg-indigo-600 hover:bg-indigo-700 shadow-lg shadow-indigo-200' }} text-white py-3 px-4 rounded-xl flex items-center justify-center gap-2 font-bold transition transform active:scale-95 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                {{ $stats['is_libur'] ? 'Hari Libur' : 'Input Kinerja Baru' }}
            </button>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="p-6 border-b border-gray-100 bg-gray-50/50 flex flex-col md:flex-row justify-between items-center gap-4">
            <h3 class="font-bold text-gray-700 flex items-center gap-2">
                <svg class="w-5 h-5 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                Riwayat Bulan: <span class="text-indigo-600 uppercase">{{ \Carbon\Carbon::parse($bulan)->translatedFormat('F Y') }}</span>
            </h3>
            <span class="text-[10px] font-black uppercase tracking-wider bg-indigo-100 text-indigo-700 px-3 py-1.5 rounded-lg">
                Total Item: {{ $activities->count() }}
            </span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50 text-gray-500 uppercase text-[10px] font-black tracking-wider border-b border-gray-100">
                        <th class="px-6 py-4 w-12 text-center">No</th>
                        <th class="px-6 py-4 w-32">Tanggal</th>
                        <th class="px-6 py-4">Keterangan</th>
                        <th class="px-6 py-4 w-40">Output</th>
                        <th class="px-6 py-4 w-24 text-center">Menit</th>
                        <th class="px-6 py-4 w-24 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50 text-sm">
                    @forelse($activities as $item)
                        <tr class="hover:bg-indigo-50/30 transition duration-150 group">
                            <td class="px-6 py-4 text-center font-bold text-gray-400 group-hover:text-indigo-500">
                                {{ $loop->iteration }}
                            </td>

                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="font-bold text-gray-700 block">{{ \Carbon\Carbon::parse($item->tanggal)->format('d/m/Y') }}</span>
                                <span class="text-[10px] text-gray-400 uppercase font-bold">{{ \Carbon\Carbon::parse($item->tanggal)->translatedFormat('l') }}</span>
                            </td>

                            <td class="px-6 py-4">
                                <div class="flex flex-col">
                                    <span class="font-bold text-gray-800 text-sm">{{ $item->keterangan }}</span>
                                    @if($item->catatan)
                                        <div class="flex items-center gap-1 mt-1 text-gray-500">
                                            <svg class="w-3 h-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"></path></svg>
                                            <span class="text-xs italic">{{ $item->catatan }}</span>
                                        </div>
                                    @endif
                                </div>
                            </td>

                            <td class="px-6 py-4">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-md text-xs font-medium bg-gray-100 text-gray-800 border border-gray-200">
                                    {{ $item->output }}
                                </span>
                            </td>

                            <td class="px-6 py-4 text-center">
                                <span class="font-black text-indigo-600 bg-indigo-50 px-2 py-1 rounded-md">{{ $item->menit }}</span>
                            </td>

                            <td class="px-6 py-4 text-center">
                                <div class="flex items-center justify-center gap-2 opacity-100">
                                    <button wire:click="edit({{ $item->id }})" 
                                            @click="showModal = true"
                                            class="text-indigo-400 hover:text-white hover:bg-indigo-500 transition p-1.5 rounded-lg" title="Edit Data">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                                    </button>

                                    <button wire:click="hapus({{ $item->id }})" 
                                            wire:confirm="Yakin ingin menghapus data ini? Kuota menit akan dikembalikan."
                                            class="text-gray-400 hover:text-white hover:bg-red-500 transition p-1.5 rounded-lg" title="Hapus Permanen">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-16 text-center bg-gray-50/30">
                                <div class="flex flex-col items-center justify-center">
                                    <div class="bg-gray-100 p-4 rounded-full mb-3">
                                        <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                    </div>
                                    <p class="text-gray-500 font-bold text-sm">Belum ada data kinerja di bulan ini.</p>
                                    <p class="text-gray-400 text-xs mt-1">Silakan klik tombol "Input Kinerja" untuk memulai.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div x-show="showModal" style="display: none;" class="fixed inset-0 z-50 flex items-center justify-center p-4">
        <div @click="showModal = false" class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm transition-opacity"></div>
        
        <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-lg overflow-hidden transform transition-all scale-100">
            <div class="{{ $id_edit ? 'bg-orange-500' : 'bg-indigo-600' }} px-6 py-4 flex justify-between items-center transition-colors duration-300">
                <div>
                    <h3 class="text-white font-bold text-lg">{{ $id_edit ? 'Edit Kinerja' : 'Input Kinerja Baru' }}</h3>
                    <p class="text-white/80 text-xs font-medium">{{ \Carbon\Carbon::parse($tanggal)->translatedFormat('l, d F Y') }}</p>
                </div>
                <button @click="showModal = false" class="text-white/70 hover:text-white transition bg-white/10 p-1 rounded-full hover:bg-white/20">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>

            <form wire:submit="simpan" class="p-6 space-y-4">
                @if ($errors->any())
                <div class="bg-red-50 text-red-600 text-xs p-3 rounded-lg mb-2 border border-red-100 flex gap-2 items-start">
                    <svg class="w-4 h-4 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <ul class="list-disc ml-4 space-y-1">
                        @foreach ($errors->all() as $error) <li>{{ $error }}</li> @endforeach
                    </ul>
                </div>
                @endif

                <div>
                    <label class="block text-xs font-black uppercase text-gray-400 mb-1 tracking-wider">Tanggal Kegiatan</label>
                    <input type="date" wire:model.live="tanggal" class="w-full border-gray-200 rounded-xl text-sm focus:ring-indigo-500 bg-gray-50 font-bold text-gray-700 shadow-sm">
                </div>

                <div>
                    <label class="block text-xs font-black uppercase text-gray-400 mb-1 tracking-wider">Pilih Referensi</label>
                    <div class="relative">
                        <select wire:model.live="selected_kegiatan_id" class="w-full border-gray-200 rounded-xl text-sm focus:ring-indigo-500 pl-4 pr-10 shadow-sm">
                            <option value="">-- Cari Nama Kegiatan --</option>
                            @foreach($listReferensi ?? [] as $ref)
                                <option value="{{ $ref->id }}">{{ $ref->nama_kegiatan }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="bg-gray-50 p-3 rounded-xl border border-dashed border-gray-300 flex justify-between items-center">
                    <div>
                         <span class="block text-[10px] text-gray-400 uppercase font-bold tracking-wider">Kegiatan Terpilih</span>
                         <span class="block text-sm font-bold text-gray-800 truncate max-w-[200px]">{{ $nama_kegiatan ?? '-' }}</span>
                    </div>
                    <div class="text-right border-l pl-4 border-gray-200">
                         <span class="block text-[10px] text-gray-400 uppercase font-bold tracking-wider">Output</span>
                         <span class="block text-sm font-bold text-indigo-600">{{ $output ?? '-' }}</span>
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-black uppercase text-gray-400 mb-1 tracking-wider">Keterangan Detail</label>
                    <textarea wire:model="keterangan" rows="3" class="w-full border-gray-200 rounded-xl text-sm focus:ring-indigo-500 shadow-sm placeholder-gray-300" placeholder="Jelaskan detail pekerjaan Anda..."></textarea>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-black uppercase text-gray-400 mb-1 tracking-wider">Catatan (Opsional)</label>
                        <input type="text" wire:model="catatan" class="w-full border-gray-200 rounded-xl text-sm shadow-sm" placeholder="-">
                    </div>
                    <div>
                        <label class="block text-xs font-black uppercase text-gray-400 mb-1 tracking-wider">Durasi (Menit)</label>
                        <div class="relative">
                            <input type="number" wire:model="menit" class="w-full border-gray-200 rounded-xl text-sm focus:ring-indigo-500 font-bold text-indigo-600 shadow-sm pr-8">
                            <span class="absolute right-3 top-2.5 text-xs font-bold text-gray-400">m</span>
                        </div>
                    </div>
                </div>

                <div class="pt-4 flex gap-3 border-t border-gray-100 mt-2">
                    <button type="button" @click="showModal = false" class="flex-1 py-2.5 text-sm font-bold text-gray-500 hover:text-gray-700 transition bg-gray-100 hover:bg-gray-200 rounded-xl">Batal</button>
                    <button type="submit" 
                            class="flex-[2] py-2.5 rounded-xl font-bold text-white shadow-lg transition transform active:scale-95 flex justify-center items-center gap-2 {{ $id_edit ? 'bg-orange-500 hover:bg-orange-600 shadow-orange-200' : 'bg-indigo-600 hover:bg-indigo-700 shadow-indigo-200' }}" 
                            wire:loading.attr="disabled">
                        
                        <span wire:loading.remove>{{ $id_edit ? 'Simpan Perubahan' : 'Simpan Kinerja' }}</span>
                        
                        <div wire:loading class="flex items-center gap-2">
                            <svg class="animate-spin h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            <span>Menyimpan...</span>
                        </div>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>