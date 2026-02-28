<div x-data="{ showModal: false }" 
     x-on:open-modal-user.window="showModal = true" 
     x-on:close-modal-user.window="showModal = false" 
     class="space-y-6">

    <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex justify-between items-center">
        <div>
            <h2 class="text-xl font-black text-gray-800 uppercase tracking-tight text-indigo-900">Manajemen Pengguna</h2>
            <p class="text-[10px] text-gray-400 font-bold uppercase mt-1 tracking-widest">Total: {{ $users->count() }} Akun Terdaftar</p>
        </div>
        <div class="flex items-center gap-3">
            @if (session()->has('message'))
                <span class="text-xs font-bold text-green-600 bg-green-50 px-3 py-1 rounded-lg border border-green-100 italic">
                    {{ session('message') }}
                </span>
            @endif
            <button wire:click="tambah" class="bg-indigo-600 text-white px-5 py-2.5 rounded-xl text-xs font-black uppercase tracking-widest shadow-lg shadow-indigo-200 hover:bg-indigo-700 transition-all active:scale-95">
                + Tambah User
            </button>
        </div>
    </div>

    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead class="bg-gray-50/50 text-[10px] font-black text-gray-400 uppercase tracking-widest border-b border-gray-100">
                    <tr>
                        <th class="px-6 py-5">Informasi Pengguna</th>
                        <th class="px-6 py-5">Kontak Email</th>
                        <th class="px-6 py-5 text-center">Otoritas / Role</th>
                        <th class="px-6 py-5 text-center">Kelola</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 text-sm">
                    @foreach($users as $user)
                    <tr class="hover:bg-gray-50/50 transition duration-150 group">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-4">
                                <div class="relative">
                                    @if ($user->profile_photo_path)
                                        <img src="{{ Storage::url($user->profile_photo_path) }}" class="w-10 h-10 rounded-xl object-cover ring-2 ring-white shadow-sm group-hover:ring-indigo-100 transition-all">
                                    @else
                                        <div class="w-10 h-10 rounded-xl bg-indigo-50 border border-indigo-100 flex items-center justify-center text-indigo-600 font-black text-xs uppercase group-hover:bg-indigo-600 group-hover:text-white transition-colors">
                                            {{ substr($user->name, 0, 1) }}
                                        </div>
                                    @endif
                                </div>
                                <div class="flex flex-col">
                                    <span class="font-bold text-gray-800">{{ $user->name }}</span>
                                    <span class="text-[9px] text-gray-400 font-bold uppercase tracking-tighter italic">ID: #{{ str_pad($user->id, 4, '0', STR_PAD_LEFT) }}</span>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="text-gray-500 font-medium">{{ $user->email }}</span>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <button wire:click="toggleRole({{ $user->id }})" 
                                    class="px-3 py-1.5 rounded-lg text-[9px] font-black uppercase tracking-widest transition-all {{ $user->role === 'atasan' ? 'bg-orange-50 text-orange-600 border border-orange-100' : 'bg-indigo-50 text-indigo-600 border border-indigo-100' }}">
                                {{ $user->role ?? 'pegawai' }}
                            </button>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center justify-center gap-2">
                                <button wire:click="edit({{ $user->id }})" class="p-2 text-indigo-400 hover:bg-indigo-50 hover:text-indigo-600 rounded-lg transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                                </button>
                                @if($user->id !== auth()->id())
                                <button wire:click="hapus({{ $user->id }})" wire:confirm="Yakin ingin menghapus data user ini secara permanen?" class="p-2 text-gray-300 hover:bg-red-50 hover:text-red-500 rounded-lg transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                </button>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div x-show="showModal" x-cloak style="display: none;" class="fixed inset-0 z-50 flex items-center justify-center p-4">
        <div @click="showModal = false" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm"></div>
        
        <div x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" 
             class="relative bg-white rounded-3xl shadow-2xl w-full max-w-md overflow-hidden transform transition-all">
            
            <div class="bg-indigo-600 px-6 py-5 flex justify-between items-center">
                <h3 class="text-white font-black uppercase text-xs tracking-widest">{{ $isEdit ? 'Edit Akun Pengguna' : 'Daftarkan User Baru' }}</h3>
                <button @click="showModal = false" class="text-indigo-200 hover:text-white transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            <form wire:submit="simpan" class="p-8 space-y-5">
                <div class="flex items-center gap-5 p-4 bg-gray-50 rounded-2xl border border-dashed border-gray-200">
                    <div class="relative group">
                        @if ($photo)
                            <img src="{{ $photo->temporaryUrl() }}" class="w-16 h-16 rounded-xl object-cover shadow-md border-2 border-white">
                        @else
                            <div class="w-16 h-16 rounded-xl bg-white border border-gray-100 flex items-center justify-center text-gray-300">
                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                            </div>
                        @endif
                        
                        <div wire:loading wire:target="photo" class="absolute inset-0 bg-white/80 rounded-xl flex items-center justify-center">
                            <svg class="animate-spin h-5 w-5 text-indigo-600" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                        </div>
                    </div>
                    
                    <div class="flex-1">
                        <label class="block text-[10px] font-black uppercase text-gray-400 mb-1 tracking-widest">Foto Identitas</label>
                        <input type="file" wire:model="photo" id="photo_manager" class="hidden" accept="image/png, image/jpeg">
                        <button type="button" onclick="document.getElementById('photo_manager').click()" class="px-3 py-1.5 bg-white border border-gray-200 rounded-lg text-[10px] font-black uppercase tracking-widest text-gray-600 hover:bg-gray-100 transition-all active:scale-95 shadow-sm">
                            Pilih Foto
                        </button>
                        @error('photo') <p class="text-[9px] text-red-500 font-bold mt-1 uppercase">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="space-y-4">
                    <div>
                        <label class="block text-[10px] font-black uppercase text-gray-400 mb-1 ml-1">Nama Lengkap</label>
                        <input type="text" wire:model="name" class="w-full border-gray-100 bg-gray-50 rounded-xl text-sm font-bold focus:bg-white focus:ring-2 focus:ring-indigo-500 transition-all">
                        @error('name') <span class="text-red-500 text-[10px] font-bold">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-[10px] font-black uppercase text-gray-400 mb-1 ml-1">Email Address</label>
                        <input type="email" wire:model="email" class="w-full border-gray-100 bg-gray-50 rounded-xl text-sm font-bold focus:bg-white focus:ring-2 focus:ring-indigo-500 transition-all">
                        @error('email') <span class="text-red-500 text-[10px] font-bold">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-[10px] font-black uppercase text-gray-400 mb-1 ml-1">Hak Akses (Role)</label>
                        <select wire:model="role" class="w-full border-gray-100 bg-gray-50 rounded-xl text-sm font-bold focus:bg-white focus:ring-2 focus:ring-indigo-500 transition-all">
                            <option value="">Pilih Role...</option>
                            <option value="pegawai">Pegawai Tetap</option>
                            <option value="atasan">Administrator / Atasan</option>
                        </select>
                        @error('role') <span class="text-red-500 text-[10px] font-bold">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-[10px] font-black uppercase text-gray-400 mb-1 ml-1">Password {{ $isEdit ? '(Kosongkan jika tidak diubah)' : '' }}</label>
                        <input type="password" wire:model="password" class="w-full border-gray-100 bg-gray-50 rounded-xl text-sm font-bold focus:bg-white focus:ring-2 focus:ring-indigo-500 transition-all">
                        @error('password') <span class="text-red-500 text-[10px] font-bold">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="flex gap-3 pt-6">
                    <button type="button" @click="showModal = false" class="flex-1 py-3 text-[10px] font-black uppercase tracking-widest text-gray-400 bg-gray-50 rounded-xl hover:bg-gray-100 transition-all">Batal</button>
                    <button type="submit" wire:loading.attr="disabled" class="flex-[2] bg-indigo-600 text-white py-3 rounded-xl text-[10px] font-black uppercase tracking-widest shadow-lg shadow-indigo-100 hover:bg-indigo-700 transition-all disabled:opacity-50">
                        <span wire:loading.remove wire:target="simpan">Simpan Perubahan</span>
                        <span wire:loading wire:target="simpan italic">Memproses Data...</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>