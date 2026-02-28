<div class="min-h-screen bg-gray-50/50 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-6xl mx-auto space-y-8">
        
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 bg-white p-6 rounded-3xl border border-gray-100 shadow-sm">
            <div>
                <h1 class="text-2xl font-black text-gray-800">Pengaturan Profil</h1>
                <p class="text-sm text-gray-500 font-medium">Kelola identitas dan keamanan akun Anda</p>
            </div>
            <div class="flex gap-3">
                <div class="bg-indigo-50 px-4 py-2 rounded-2xl border border-indigo-100 text-center">
                    <p class="text-[9px] font-black text-indigo-400 uppercase tracking-wider">Total Input</p>
                    <p class="text-lg font-black text-indigo-700">{{ $stats['total'] }}</p>
                </div>
                <div class="bg-orange-50 px-4 py-2 rounded-2xl border border-orange-100 text-center">
                    <p class="text-[9px] font-black text-orange-400 uppercase tracking-wider">Pending</p>
                    <p class="text-lg font-black text-orange-700">{{ $stats['pending'] }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-3xl border border-gray-100 shadow-sm overflow-hidden">
            <div class="px-8 py-6 border-b border-gray-50 bg-gray-50/30">
                <h2 class="font-black text-gray-700 uppercase text-xs tracking-widest">Informasi Dasar</h2>
            </div>
            
            <form wire:submit.prevent="updateProfile" class="p-8 space-y-8">
                @if (session()->has('profile_success'))
                    <div class="p-4 bg-emerald-50 text-emerald-600 rounded-2xl text-xs font-bold border border-emerald-100 italic">
                        {{ session('profile_success') }}
                    </div>
                @endif

                <div class="flex flex-col sm:flex-row items-center gap-6 pb-6 border-b border-gray-50">
                    <div class="relative group">
                        @if ($photo)
                            <img src="{{ $photo->temporaryUrl() }}" class="w-24 h-24 rounded-full object-cover border-4 border-white shadow-lg shadow-indigo-100">
                        @elseif (Auth::user()->profile_photo_path)
                            <img src="{{ Storage::url(Auth::user()->profile_photo_path) }}" class="w-24 h-24 rounded-full object-cover border-4 border-white shadow-lg shadow-indigo-100">
                        @else
                            <div class="w-24 h-24 rounded-full bg-indigo-600 flex items-center justify-center border-4 border-white shadow-lg shadow-indigo-100">
                                <span class="text-3xl font-black text-indigo-100 uppercase">{{ substr(Auth::user()->name, 0, 1) }}</span>
                            </div>
                        @endif
                        
                        <div wire:loading wire:target="photo" class="absolute inset-0 bg-white/70 rounded-full flex items-center justify-center">
                            <svg class="animate-spin h-6 w-6 text-indigo-600" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                        </div>
                    </div>

                    <div class="flex-1 text-center sm:text-left">
                        <label class="text-[10px] font-black text-gray-400 uppercase tracking-wider mb-1 block">Foto Profil</label>
                        <p class="text-xs text-gray-500 mb-3 font-medium">PNG, JPG, max 1MB. Seret foto atau klik tombol.</p>
                        
                        <input type="file" wire:model="photo" id="photo_input" class="hidden" accept="image/png, image/jpeg">
                        
                        <button type="button" onclick="document.getElementById('photo_input').click()" 
                            class="px-5 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-xl text-xs font-bold transition-all shadow-sm active:scale-95">
                            Pilih Foto
                        </button>
                        
                        @error('photo') <p class="text-red-500 text-[10px] font-bold mt-2">{{ $message }}</p> @enderror
                    </div>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-gray-400 uppercase ml-1">Nama Lengkap</label>
                        <input type="text" wire:model="name" class="w-full px-4 py-3 rounded-xl border-gray-200 bg-gray-50 focus:bg-white focus:ring-2 focus:ring-indigo-500 transition-all text-sm font-bold text-gray-700">
                        @error('name') <span class="text-red-500 text-[10px] font-bold">{{ $message }}</span> @enderror
                    </div>

                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-gray-400 uppercase ml-1">Alamat Email</label>
                        <input type="email" wire:model="email" class="w-full px-4 py-3 rounded-xl border-gray-200 bg-gray-50 focus:bg-white focus:ring-2 focus:ring-indigo-500 transition-all text-sm font-bold text-gray-700">
                        @error('email') <span class="text-red-500 text-[10px] font-bold">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="flex justify-end pt-2">
                    <button type="submit" wire:loading.attr="disabled" class="px-6 py-3 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-xs font-black uppercase tracking-widest transition-all active:scale-95 shadow-lg shadow-indigo-100 disabled:opacity-50">
                        <span wire:loading.remove wire:target="updateProfile">Simpan Profil</span>
                        <span wire:loading wire:target="updateProfile">Menyimpan...</span>
                    </button>
                </div>
            </form>
        </div>

        <div class="bg-white rounded-3xl border border-gray-100 shadow-sm overflow-hidden">
            <div class="px-8 py-6 border-b border-gray-50 bg-gray-50/30">
                <h2 class="font-black text-gray-700 uppercase text-xs tracking-widest text-red-500">Keamanan Password</h2>
            </div>
            
            <form wire:submit.prevent="updatePassword" class="p-8 space-y-6">
                @if (session()->has('password_success'))
                    <div class="p-4 bg-emerald-50 text-emerald-600 rounded-2xl text-xs font-bold border border-emerald-100 italic">
                        {{ session('password_success') }}
                    </div>
                @endif

                <div class="space-y-4">
                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-gray-400 uppercase ml-1">Password Saat Ini</label>
                        <input type="password" wire:model="current_password" class="w-full px-4 py-3 rounded-xl border-gray-200 bg-gray-50 focus:bg-white focus:ring-2 focus:ring-red-500 transition-all text-sm font-bold">
                        @error('current_password') <span class="text-red-500 text-[10px] font-bold">{{ $message }}</span> @enderror
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-2">
                            <label class="text-[10px] font-black text-gray-400 uppercase ml-1">Password Baru</label>
                            <input type="password" wire:model="new_password" class="w-full px-4 py-3 rounded-xl border-gray-200 bg-gray-50 focus:bg-white focus:ring-2 focus:ring-red-500 transition-all text-sm font-bold">
                            @error('new_password') <span class="text-red-500 text-[10px] font-bold">{{ $message }}</span> @enderror
                        </div>
                        <div class="space-y-2">
                            <label class="text-[10px] font-black text-gray-400 uppercase ml-1">Konfirmasi Password Baru</label>
                            <input type="password" wire:model="new_password_confirmation" class="w-full px-4 py-3 rounded-xl border-gray-200 bg-gray-50 focus:bg-white focus:ring-2 focus:ring-red-500 transition-all text-sm font-bold">
                        </div>
                    </div>
                </div>

                <div class="flex justify-end pt-2">
                    <button type="submit" class="px-6 py-3 bg-red-500 hover:bg-red-600 text-white rounded-xl text-xs font-black uppercase tracking-widest transition-all active:scale-95 shadow-lg shadow-red-100">
                        Ganti Password
                    </button>
                </div>
            </form>
        </div>

    </div>
</div>