<div class="space-y-6" 
     x-data="{ showModal: false, showImport: false }" 
     x-on:open-modal-ref.window="showModal = true" 
     x-on:close-modal-ref.window="showModal = false"
     x-on:close-import-modal.window="showImport = false">

    <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
            <h2 class="text-xl font-black text-indigo-900 uppercase tracking-tight">Referensi Kegiatan</h2>
            <p class="text-[10px] text-gray-400 font-bold uppercase mt-1 tracking-widest">Master data daftar kegiatan pegawai</p>
        </div>
        <div class="flex flex-wrap gap-2 w-full md:w-auto">
            <button @click="showImport = true" class="bg-emerald-500 text-white px-5 py-3 rounded-xl text-xs font-black uppercase tracking-widest shadow-lg shadow-emerald-100 hover:bg-emerald-600 transition transform active:scale-95 flex items-center justify-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg>
                Import Excel
            </button>
            <button wire:click="tambah" class="bg-indigo-600 text-white px-5 py-3 rounded-xl text-xs font-black uppercase tracking-widest shadow-lg shadow-indigo-100 hover:bg-indigo-700 transition transform active:scale-95 flex items-center justify-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                Tambah Baru
            </button>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div class="md:col-span-2 relative group">
            <input type="text" 
                   wire:model.live.debounce.300ms="search" 
                   placeholder="Cari nama kegiatan..." 
                   class="w-full pl-10 pr-10 py-3.5 rounded-xl border-gray-200 shadow-sm focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 text-sm font-medium text-gray-700 transition-all duration-200 placeholder-gray-400 bg-gray-50/50 focus:bg-white">
            
            <div wire:loading wire:target="search" class="absolute inset-y-0 right-3 flex items-center">
                <svg class="animate-spin h-5 w-5 text-indigo-500" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
            </div>
            
            @if($search)
            <button @click="$wire.set('search', '')" class="absolute inset-y-0 right-3 flex items-center text-gray-400 hover:text-red-500 transition-colors" wire:loading.remove wire:target="search">
                 <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
            @endif
        </div>

        <div class="relative">
            <select wire:model.live="filterJenis" class="w-full pl-10 pr-10 py-3.5 rounded-xl border-gray-200 shadow-sm focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 text-sm font-semibold text-gray-600 appearance-none bg-gray-50/50 focus:bg-white transition-all">
                <option value="">Semua Jenis</option>
                @foreach($listJenis as $jenis)
                    <option value="{{ $jenis }}">{{ $jenis }}</option>
                @endforeach
            </select>
            <div class="absolute inset-y-0 right-3 flex items-center pointer-events-none text-gray-400">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
            </div>
        </div>
    </div>

    @if (session()->has('message'))
        <div x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 3000)" class="bg-emerald-50 text-emerald-600 p-4 rounded-xl border border-emerald-100 text-sm font-bold flex items-center gap-3">
            <svg class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            {{ session('message') }}
        </div>
    @endif

    @if (session()->has('error'))
        <div class="bg-red-50 text-red-600 p-4 rounded-xl border border-red-100 text-sm font-bold flex items-center gap-3">
            <svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            {{ session('error') }}
        </div>
    @endif

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden relative">
        <div wire:loading.flex wire:target="filterJenis, search, gotoPage" class="absolute inset-0 bg-white/60 backdrop-blur-sm z-10 items-center justify-center">
             <svg class="animate-spin h-8 w-8 text-indigo-500" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead class="bg-gray-50 text-[10px] font-black text-gray-400 uppercase tracking-widest border-b border-gray-100">
                    <tr>
                        <th class="px-6 py-4 w-12 text-center">No</th>
                        
                        <th class="px-6 py-4">Jenis</th>
                        <th class="px-6 py-4 w-1/3">Nama Kegiatan</th>
                        <th class="px-6 py-4">Output</th>
                        <th class="px-6 py-4 text-center">Status</th>
                        <th class="px-6 py-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($referensi as $ref)
                    <tr class="hover:bg-indigo-50/30 transition group">
                        
                        <td class="px-6 py-4 text-center text-gray-400 font-bold text-xs">
                            {{ $referensi->firstItem() + $loop->index }}
                        </td>

                        <td class="px-6 py-4">
                            <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-[10px] font-black uppercase tracking-wider bg-indigo-50 text-indigo-600 border border-indigo-100 group-hover:bg-indigo-100 transition-colors">
                                {{ $ref->nama_jenis }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <p class="font-bold text-gray-700 text-sm group-hover:text-indigo-800 transition-colors">{{ $ref->nama_kegiatan }}</p>
                        </td>
                        <td class="px-6 py-4 text-gray-500 text-xs font-medium">{{ $ref->output }}</td>
                        <td class="px-6 py-4 text-center">
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[10px] font-black uppercase tracking-widest {{ $ref->aktif == 'ya' ? 'bg-emerald-50 text-emerald-600' : 'bg-red-50 text-red-600' }}">
                                <span class="w-1.5 h-1.5 rounded-full {{ $ref->aktif == 'ya' ? 'bg-emerald-500' : 'bg-red-500' }}"></span>
                                {{ $ref->aktif == 'ya' ? 'Aktif' : 'Non-Aktif' }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center justify-center gap-2">
                                <button wire:click="edit({{ $ref->id }})" class="p-2 rounded-xl text-indigo-400 hover:text-white hover:bg-indigo-500 transition-all shadow-indigo-100">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                                </button>
                                <button wire:click="hapus({{ $ref->id }})" wire:confirm="Yakin hapus referensi ini?" class="p-2 rounded-xl text-gray-400 hover:text-white hover:bg-red-500 transition-all shadow-red-100">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-16 text-center text-gray-400 font-bold uppercase tracking-widest italic text-xs">
                            Data tidak ditemukan...
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-6 py-4 bg-gray-50/50 border-t border-gray-100">
            {{ $referensi->links() }}
        </div>
    </div>

    <div x-show="showImport" style="display: none;" 
         x-transition.opacity
         class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-gray-900/60 backdrop-blur-sm">
        <div @click.away="showImport = false" class="bg-white rounded-2xl shadow-2xl w-full max-w-md overflow-hidden transform transition-all">
            <div class="bg-emerald-600 px-6 py-4 flex justify-between items-center text-white">
                <h3 class="font-bold uppercase text-xs tracking-widest">Import Data Excel</h3>
                <button @click="showImport = false" class="text-white/70 hover:text-white">✕</button>
            </div>
            <form wire:submit="importExcel" class="p-6 space-y-4">
                <div class="p-6 border-2 border-dashed border-gray-200 rounded-2xl text-center bg-gray-50 group hover:border-emerald-300 transition-colors">
                    <input type="file" wire:model="file_excel" id="uploadExcel" class="hidden">
                    <label for="uploadExcel" class="cursor-pointer">
                        <svg class="w-10 h-10 text-gray-300 group-hover:text-emerald-500 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 13h6m-3-3v6m-9 1V7a2 2 0 012-2h6l2 2h6a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2z"></path></svg>
                        <p class="text-xs font-black text-gray-500 uppercase tracking-widest">Klik untuk Pilih File</p>
                        <p class="text-[9px] text-gray-400 mt-1 italic">Format: .xlsx / .xls / .csv (Max 10MB)</p>
                    </label>
                    <div wire:loading wire:target="file_excel" class="mt-2 text-[10px] font-bold text-indigo-500 uppercase animate-pulse">Mengunggah...</div>
                    @if($file_excel)
                        <div class="mt-2 text-[10px] font-black text-emerald-600 bg-emerald-50 py-1 rounded uppercase tracking-tighter">
                            ✅ {{ $file_excel->getClientOriginalName() }}
                        </div>
                    @endif
                </div>
                <div class="flex gap-2">
                    <button type="button" @click="showImport = false" class="flex-1 py-3 text-xs font-black uppercase text-gray-400 bg-gray-100 rounded-xl hover:bg-gray-200 transition">Batal</button>
                    <button type="submit" class="flex-[2] py-3 bg-emerald-600 text-white rounded-xl text-xs font-black uppercase shadow-lg shadow-emerald-100 hover:bg-emerald-700 transition flex justify-center items-center gap-2" wire:loading.attr="disabled">
                        <span wire:loading.remove wire:target="importExcel">Mulai Import</span>
                        <svg wire:loading wire:target="importExcel" class="animate-spin h-4 w-4 text-white" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                    </button>
                </div>
                <p class="text-[9px] text-red-500 font-bold text-center leading-tight mt-2">⚠️ Perhatian: Data lama di database akan dihapus & digantikan oleh data dari file ini.</p>
            </form>
        </div>
    </div>

    <div x-show="showModal" style="display: none;" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-gray-900/60 backdrop-blur-sm">
        
        <div @click.away="showModal = false" 
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
             x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
             x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
             class="bg-white rounded-2xl shadow-2xl w-full max-w-lg overflow-hidden transform transition-all">
            
            <div class="bg-indigo-600 px-6 py-4 flex justify-between items-center text-white shadow-lg">
                <div class="flex items-center gap-2">
                    <div class="bg-white/20 p-1.5 rounded-lg">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                    </div>
                    <h3 class="font-bold uppercase text-xs tracking-widest">{{ $isEdit ? 'Edit Referensi' : 'Tambah Referensi' }}</h3>
                </div>
                <button @click="showModal = false" class="text-white/70 hover:text-white bg-white/10 hover:bg-white/20 p-1 rounded-full transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>

            <form wire:submit="simpan" class="p-6 space-y-5">
                <div class="grid grid-cols-2 gap-5">
                    <div>
                        <label class="block text-[10px] font-black uppercase text-gray-400 mb-1.5 tracking-wide">Nama Jenis</label>
                        <input type="text" wire:model="nama_jenis" placeholder="Contoh: Harian" class="w-full border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all bg-gray-50 focus:bg-white placeholder-gray-300">
                        @error('nama_jenis') <span class="text-[10px] text-red-500 font-bold mt-1 block">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-[10px] font-black uppercase text-gray-400 mb-1.5 tracking-wide">Output</label>
                        <input type="text" wire:model="output" placeholder="Contoh: Laporan" class="w-full border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all bg-gray-50 focus:bg-white placeholder-gray-300">
                        @error('output') <span class="text-[10px] text-red-500 font-bold mt-1 block">{{ $message }}</span> @enderror
                    </div>
                </div>
                <div>
                    <label class="block text-[10px] font-black uppercase text-gray-400 mb-1.5 tracking-wide">Nama Kegiatan</label>
                    <textarea wire:model="nama_kegiatan" rows="3" placeholder="Deskripsikan nama kegiatan secara lengkap..." class="w-full border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all bg-gray-50 focus:bg-white placeholder-gray-300"></textarea>
                    @error('nama_kegiatan') <span class="text-[10px] text-red-500 font-bold mt-1 block">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="block text-[10px] font-black uppercase text-gray-400 mb-1.5 tracking-wide">Status Aktif</label>
                    <div class="relative">
                        <select wire:model="aktif" class="w-full border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all bg-gray-50 focus:bg-white appearance-none pr-10">
                            <option value="ya">YA - Tampilkan di Menu</option>
                            <option value="tidak">TIDAK - Sembunyikan</option>
                        </select>
                         <div class="absolute inset-y-0 right-3 flex items-center pointer-events-none text-gray-400">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                        </div>
                    </div>
                </div>
                <div class="flex gap-3 pt-4 border-t border-gray-100">
                    <button type="button" @click="showModal = false" class="flex-1 py-3 text-xs font-black uppercase text-gray-500 bg-gray-100 hover:bg-gray-200 rounded-xl transition-colors">Batal</button>
                    <button type="submit" class="flex-[2] py-3 bg-indigo-600 text-white rounded-xl text-xs font-black uppercase shadow-lg shadow-indigo-100 hover:bg-indigo-700 transition transform active:scale-95 flex justify-center items-center gap-2" wire:loading.attr="disabled">
                        <span wire:loading.remove>{{ $isEdit ? 'Simpan Perubahan' : 'Simpan Data Baru' }}</span>
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