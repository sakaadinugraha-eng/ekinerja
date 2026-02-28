<div x-data="{ showModal: false }" 
     x-on:close-modal.window="showModal = false" 
     x-on:open-modal.window="showModal = true" 
     class="space-y-6 font-sans text-gray-900">

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
                <div class="mt-2 text-xs font-bold {{ $stats['is_libur'] ? 'text-red-500' : 'text-gray-400' }} bg-white/50 px-2 py-1 rounded text-center">
                    {{ $stats['label_hari'] }}
                </div>
            </div>
        </div>

        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 flex flex-col justify-center">
            <button @click="showModal = true; $wire.resetInput()" 
                    @if($stats['is_libur']) disabled @endif
                    class="w-full {{ $stats['is_libur'] ? 'bg-gray-300 cursor-not-allowed' : 'bg-indigo-600 hover:bg-indigo-700 shadow-lg shadow-indigo-200' }} text-white py-3 px-4 rounded-xl flex items-center justify-center gap-2 font-bold transition transform active:scale-95">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                {{ $stats['is_libur'] ? 'Hari Libur' : 'Input Kinerja Baru' }}
            </button>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="p-6 border-b border-gray-100 bg-gray-50/50 flex flex-col md:flex-row justify-between items-center gap-4">
            <div class="flex items-center gap-3">
                <h3 class="font-bold text-gray-700 flex items-center gap-2">
                    <svg class="w-5 h-5 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                    Riwayat: <span class="text-indigo-600 uppercase">{{ \Carbon\Carbon::parse($bulan)->translatedFormat('F Y') }}</span>
                </h3>
                <span class="text-[10px] font-black uppercase tracking-wider bg-indigo-100 text-indigo-700 px-3 py-1.5 rounded-lg">
                    {{ $activities->count() }} Item
                </span>
            </div>

            @if(auth()->user()->role !== 'atasan')
                <button wire:click="ajukanKeAtasan" 
                        wire:confirm="Ajukan semua DRAFT kinerja bulan ini?"
                        wire:loading.attr="disabled"
                        class="bg-orange-500 hover:bg-orange-600 text-white px-5 py-2.5 rounded-xl font-bold text-xs shadow-lg shadow-orange-100 flex items-center gap-2 transition active:scale-95">
                    <span wire:loading.remove>🚀 Ajukan ke Atasan</span>
                    <span wire:loading>Memproses...</span>
                </button>
            @else
                <span class="text-[10px] font-black text-emerald-600 bg-emerald-50 px-3 py-2 rounded-lg border border-emerald-100 uppercase tracking-widest text-center">
                    ✅ Mode Atasan: Auto-Approved
                </span>
            @endif
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50 text-gray-500 uppercase text-[10px] font-black tracking-wider border-b border-gray-100">
                        <th class="px-6 py-4 w-12 text-center">No</th>
                        <th class="px-6 py-4 w-32">Tanggal</th>
                        <th class="px-6 py-4 w-1/4">Kegiatan</th>
                        <th class="px-6 py-4">Keterangan</th>
                        <th class="px-6 py-4 w-28 text-center">Status</th> 
                        <th class="px-6 py-4 w-24 text-center">Menit</th>
                        <th class="px-6 py-4 w-24 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50 text-sm">
                    @forelse($activities as $item)
                        <tr class="hover:bg-indigo-50/30 transition group">
                            <td class="px-6 py-4 text-center font-bold text-gray-400">
                                {{ $loop->iteration }}
                            </td>

                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="font-bold text-gray-700 block">{{ \Carbon\Carbon::parse($item->tanggal)->format('d/m/Y') }}</span>
                                <span class="text-[10px] text-gray-400 uppercase font-bold">{{ \Carbon\Carbon::parse($item->tanggal)->translatedFormat('l') }}</span>
                            </td>

                            <td class="px-6 py-4">
                                <div class="flex flex-col">
                                    <span class="text-[10px] font-black uppercase tracking-widest text-indigo-400 leading-none mb-1">
                                        {{ $item->jenis_kegiatan }}
                                    </span>
                                    <span class="text-sm font-bold text-gray-700 leading-tight">
                                        {{ $item->nama_kegiatan }}
                                    </span>
                                </div>
                            </td>

                            <td class="px-6 py-4">
                                <div class="flex flex-col">
                                    <span class="font-bold text-gray-800 text-sm leading-tight">{{ $item->keterangan }}</span>
                                    <span class="text-[10px] text-gray-400 mt-1 uppercase tracking-wide italic">Output Target: {{ $item->output }}</span>
                                </div>
                            </td>

                            <td class="px-6 py-4 text-center">
                                <div class="flex flex-col items-center gap-1">
                                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-black uppercase tracking-widest 
                                        {{ $item->status == 'approved' ? 'bg-green-100 text-green-800' : 
                                        ($item->status == 'pending' ? 'bg-blue-100 text-blue-800' : 
                                        ($item->status == 'rejected' ? 'bg-red-100 text-red-800' : 'bg-gray-100 text-gray-500')) }}">
                                        {{ $item->status == 'approved' ? 'Disetujui' : ($item->status == 'pending' ? 'Menunggu' : ($item->status == 'rejected' ? 'Ditolak' : 'Draft')) }}
                                    </span>

                                    @if($item->status == 'rejected' && $item->catatan_atasan)
                                        <div x-data="{ showReason: false }" class="relative">
                                            <button @click="showReason = !showReason" 
                                                    class="text-[9px] font-bold text-red-600 underline decoration-dotted hover:text-red-800 transition">
                                                Lihat Alasan
                                            </button>

                                            <div x-show="showReason" 
                                                @click.away="showReason = false"
                                                x-transition
                                                class="absolute z-50 bottom-full mb-2 left-1/2 -translate-x-1/2 w-48 bg-gray-900 text-white text-[10px] p-2 rounded-lg shadow-xl border border-gray-700">
                                                <div class="font-black uppercase mb-1 text-red-400">Catatan Atasan:</div>
                                                <p class="leading-relaxed font-medium">{{ $item->catatan_atasan }}</p>
                                                <div class="absolute top-full left-1/2 -translate-x-1/2 border-8 border-transparent border-t-gray-900"></div>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </td>

                            <td class="px-6 py-4 text-center font-black text-indigo-600">
                                {{ $item->menit }}
                            </td>

                            <td class="px-6 py-4 text-center">
                                @if(in_array($item->status, ['draft', 'rejected']) || auth()->user()->role === 'atasan')
                                    <div class="flex justify-center gap-2">
                                        <button wire:click="edit({{ $item->id }})" @click="showModal = true" class="text-indigo-400 hover:bg-indigo-500 hover:text-white p-1.5 rounded-lg transition" title="Edit">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                                        </button>
                                        <button wire:click="hapus({{ $item->id }})" wire:confirm="Hapus data?" class="text-gray-400 hover:bg-red-500 hover:text-white p-1.5 rounded-lg transition" title="Hapus">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                        </button>
                                    </div>
                                @else
                                    <div class="flex justify-center text-gray-300" title="Data Terkunci">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                                    </div>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="7" class="px-6 py-12 text-center text-gray-400 italic font-bold">Belum ada data kinerja untuk ditampilkan.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div x-show="showModal" style="display: none;" class="fixed inset-0 z-50 flex items-center justify-center p-4">
        <div @click="showModal = false" class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm transition-opacity"></div>
        
        <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-lg overflow-hidden transform transition-all">
            <div class="{{ $id_edit ? 'bg-orange-500' : 'bg-indigo-600' }} px-6 py-4 flex justify-between items-center">
                <div>
                    <h3 class="text-white font-bold text-lg">{{ $id_edit ? 'Edit Kinerja' : 'Input Kinerja Baru' }}</h3>
                    <p class="text-white/80 text-xs">{{ \Carbon\Carbon::parse($tanggal)->translatedFormat('l, d F Y') }}</p>
                </div>
                <button @click="showModal = false" class="text-white/70 hover:text-white transition"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg></button>
            </div>

            <form wire:submit="simpan" class="p-6 space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-black uppercase text-gray-400 mb-1 tracking-wider">Tanggal</label>
                        <input type="date" wire:model.live="tanggal" class="w-full border-gray-200 rounded-xl text-sm focus:ring-indigo-500 font-bold bg-gray-50">
                    </div>

                    <div x-data="{
                        open: false,
                        search: '',
                        selectedJenis: @entangle('selected_jenis').live,
                        options: {{ Js::from($listJenis ?? []) }},
                        get filtered() {
                            if (this.search === '') return this.options;
                            return this.options.filter(opt => opt.toLowerCase().includes(this.search.toLowerCase()));
                        },
                        init() {
                            this.$watch('selectedJenis', v => { this.search = v ? v : '' });
                            if(this.selectedJenis) this.search = this.selectedJenis;
                        },
                        select(val) {
                            this.selectedJenis = val;
                            this.search = val;
                            this.open = false;
                        }
                    }" class="relative">
                        <label class="block text-xs font-black uppercase text-gray-400 mb-1 tracking-wider">Pilih Jenis (Ketik)</label>
                        <div class="relative">
                            <input type="text" x-model="search" @click="open = true" @click.away="open = false"
                                class="w-full border-gray-200 rounded-xl text-sm focus:ring-indigo-500 pl-4 pr-10 font-bold text-gray-700 bg-white"
                                placeholder="Cari Jenis...">
                            <div class="absolute right-3 top-2.5 text-gray-400">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 9l4-4 4 4m0 6l-4 4-4-4"></path></svg>
                            </div>
                        </div>
                        <div x-show="open && filtered.length > 0" 
                            x-transition.opacity
                            class="absolute z-[60] mt-1 w-full bg-white rounded-xl shadow-xl border border-gray-100 max-h-40 overflow-y-auto">
                            <template x-for="item in filtered" :key="item">
                                <div @click="select(item)" class="px-4 py-2 text-sm hover:bg-indigo-50 cursor-pointer border-b border-gray-50 font-bold text-gray-700" x-text="item"></div>
                            </template>
                        </div>
                    </div>
                </div>

                <div x-data="{
                    open: false,
                    search: '',
                    selectedId: @entangle('selected_kegiatan_id').live,
                    items: @entangle('list_kegiatan_filtered'),
                    init() {
                        this.$watch('selectedId', value => {
                            if(!value) this.search = '';
                            else {
                                const selected = this.items.find(i => i.id == value);
                                if(selected) this.search = selected.nama_kegiatan;
                            }
                        });
                        if(this.selectedId) {
                            const selected = this.items.find(i => i.id == this.selectedId);
                            if(selected) this.search = selected.nama_kegiatan;
                        }
                    },
                    get filtered() {
                        if (this.search === '') return this.items;
                        return this.items.filter(i => i.nama_kegiatan.toLowerCase().includes(this.search.toLowerCase()));
                    },
                    select(item) {
                        this.selectedId = item.id;
                        this.search = item.nama_kegiatan;
                        this.open = false;
                    }
                }" class="relative">
                    <label class="block text-xs font-black uppercase text-gray-400 mb-1 tracking-wider">Cari Nama Kegiatan</label>
                    <div class="relative">
                        <input type="text" x-model="search" @click="open = true" @click.away="open = false"
                            class="w-full border-gray-200 rounded-xl text-sm focus:ring-indigo-500 pl-4 pr-10 {{ !$selected_jenis ? 'bg-gray-100 cursor-not-allowed' : '' }}"
                            placeholder="{{ $selected_jenis ? 'Ketik untuk mencari...' : 'Pilih jenis kegiatan dulu' }}"
                            {{ !$selected_jenis ? 'disabled' : '' }}>
                        <div class="absolute right-3 top-2.5 text-gray-400">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                        </div>
                    </div>

                    <div x-show="open && items.length > 0" 
                        x-transition.opacity
                        class="absolute z-50 mt-1 w-full bg-white rounded-xl shadow-xl border border-gray-100 max-h-48 overflow-y-auto">
                        <template x-for="item in filtered" :key="item.id">
                            <div @click="select(item)" class="px-4 py-2 text-sm hover:bg-indigo-50 cursor-pointer border-b border-gray-50 flex flex-col">
                                <span class="font-bold text-gray-800" x-text="item.nama_kegiatan"></span>
                                <span class="text-[9px] text-indigo-500 font-bold" x-text="'Output: ' + item.output"></span>
                            </div>
                        </template>
                    </div>
                </div>

                <div class="bg-indigo-50/50 p-4 rounded-xl border border-dashed border-indigo-200 flex flex-col md:flex-row justify-between items-start md:items-center gap-4 transition-all">
                    <div class="flex-1">
                        <span class="block text-[10px] text-indigo-400 uppercase font-black tracking-widest mb-1">Kegiatan Terpilih</span>
                        <div class="flex items-start gap-2">
                            @if($selected_jenis && $nama_kegiatan)
                                <div class="mt-1 flex-shrink-0">
                                    <svg class="w-4 h-4 text-indigo-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                                </div>
                                <p class="text-sm font-bold text-gray-800 leading-relaxed">
                                    <span class="text-indigo-600 uppercase">{{ $selected_jenis }}</span> 
                                    <span class="text-gray-300 mx-1">—</span> 
                                    {{ $nama_kegiatan }}
                                </p>
                            @else
                                <span class="text-sm font-bold text-gray-400 italic">Belum ada kegiatan yang dipilih...</span>
                            @endif
                        </div>
                    </div>
                    
                    <div class="text-left md:text-right border-t md:border-t-0 md:border-l pt-3 md:pt-0 md:pl-6 border-indigo-100 min-w-[120px]">
                        <span class="block text-[10px] text-indigo-400 uppercase font-black tracking-widest mb-1">Output Target</span>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-md text-xs font-black bg-indigo-100 text-indigo-700 uppercase tracking-tighter">
                            {{ $output ?? '-' }}
                        </span>
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-black uppercase text-gray-400 mb-1">Keterangan Detail</label>
                    <textarea wire:model="keterangan" rows="3" class="w-full border-gray-200 rounded-xl text-sm focus:ring-indigo-500 shadow-sm" placeholder="Apa yang Anda kerjakan?"></textarea>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-black uppercase text-gray-400 mb-1">Catatan</label>
                        <input type="text" wire:model="catatan" class="w-full border-gray-200 rounded-xl text-sm shadow-sm" placeholder="-">
                    </div>
                    <div>
                        <label class="block text-xs font-black uppercase text-gray-400 mb-1">Durasi (Menit)</label>
                        <div class="relative">
                            <input type="number" wire:model="menit" class="w-full border-gray-200 rounded-xl text-sm font-bold text-indigo-600 focus:ring-indigo-500 shadow-sm">
                            <span class="absolute right-3 top-2 text-gray-400 text-xs font-bold">m</span>
                        </div>
                    </div>
                </div>

                <div class="pt-4 flex gap-3 border-t border-gray-100">
                    <button type="button" @click="showModal = false" class="flex-1 py-2.5 text-sm font-bold text-gray-500 bg-gray-100 hover:bg-gray-200 rounded-xl transition-colors">Batal</button>
                    <button type="submit" 
                            class="flex-[2] py-2.5 rounded-xl font-bold text-white shadow-lg transition transform active:scale-95 flex justify-center items-center gap-2 {{ $id_edit ? 'bg-orange-500 hover:bg-orange-600 shadow-orange-200' : 'bg-indigo-600 hover:bg-indigo-700 shadow-indigo-200' }}"
                            wire:loading.attr="disabled">
                        <span wire:loading.remove>{{ $id_edit ? 'Simpan Perubahan' : 'Simpan Kinerja' }}</span>
                        <div wire:loading class="animate-spin h-4 w-4 border-2 border-white border-t-transparent rounded-full"></div>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>