<header class="bg-white/80 backdrop-blur-md border-b border-gray-100 h-16 flex items-center justify-between px-4 sm:px-6 lg:px-8 sticky top-0 z-40 shadow-sm">
                    <div class="flex items-center gap-4">
                        <button @click="sidebarOpen = !sidebarOpen" 
                                class="p-2 rounded-xl text-gray-400 hover:bg-indigo-50 hover:text-indigo-600 focus:outline-none transition-all duration-200 active:scale-90 group">
                            <svg class="w-6 h-6 transition-transform duration-300" 
                                :class="sidebarOpen ? 'rotate-180' : ''" 
                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path x-show="!sidebarOpen" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                                <path x-show="sidebarOpen" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7" />
                            </svg>
                        </button>

                        @if (isset($header))
                            <div class="hidden md:flex items-center gap-2">
                                <div class="h-4 w-[2px] bg-gray-200 rounded-full"></div>
                                <h1 class="font-black text-gray-800 text-sm uppercase tracking-widest truncate">
                                    {{ $header }}
                                </h1>
                            </div>
                        @endif
                    </div>

                    <div class="flex items-center gap-2">
                        <div x-data="{ openNotifications: false }" class="relative">
                            <button @click="openNotifications = !openNotifications" 
                                    @click.away="openNotifications = false"
                                    class="relative p-2 text-gray-400 hover:bg-indigo-50 hover:text-indigo-600 rounded-xl transition-all active:scale-90">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                                </svg>
                                
                                @if(auth()->user()->unreadNotifications->count() > 0)
                                    <span class="absolute top-1.5 right-1.5 flex h-4 w-4">
                                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-400 opacity-75"></span>
                                        <span class="relative inline-flex rounded-full h-4 w-4 bg-red-500 text-[9px] font-black text-white items-center justify-center">
                                            {{ auth()->user()->unreadNotifications->count() }}
                                        </span>
                                    </span>
                                @endif
                            </button>

                            <div x-show="openNotifications" 
                                x-cloak
                                x-transition:enter="transition ease-out duration-200"
                                x-transition:enter-start="opacity-0 translate-y-4"
                                x-transition:enter-end="opacity-100 translate-y-0"
                                class="absolute right-0 mt-3 w-80 bg-white rounded-2xl shadow-[0_20px_50px_rgba(0,0,0,0.1)] border border-gray-100 py-2 z-50 overflow-hidden"
                                style="display: none;">
                                
                                <div class="px-5 py-4 border-b border-gray-50 flex justify-between items-center bg-gray-50/50">
                                    <span class="text-[10px] font-black uppercase text-gray-800 tracking-widest">Notifikasi</span>
                                    @if(auth()->user()->unreadNotifications->count() > 0)
                                        <button wire:click="markAsRead" class="text-[9px] font-black text-indigo-600 uppercase hover:underline">Tandai Baca Semua</button>
                                    @endif
                                </div>

                                <div class="max-h-80 overflow-y-auto custom-scrollbar">
                                    @forelse(auth()->user()->unreadNotifications as $notification)
                                        <a href="#" 
                                        wire:click.prevent="markAsRead('{{ $notification->id }}')" 
                                        class="block px-5 py-4 hover:bg-indigo-50/30 transition border-b border-gray-50 last:border-0 group/item">
                                            <p class="text-[11px] font-black text-indigo-600 uppercase tracking-tighter mb-1 group-hover/item:text-indigo-700">{{ $notification->data['title'] }}</p>
                                            <p class="text-xs text-gray-600 leading-relaxed">{{ $notification->data['message'] }}</p>
                                            <p class="text-[9px] text-gray-400 mt-2 font-bold italic">{{ $notification->created_at->diffForHumans() }}</p>
                                        </a>
                                    @empty
                                        <div class="px-5 py-10 text-center">
                                            <div class="w-12 h-12 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-3">
                                                <svg class="w-6 h-6 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                                            </div>
                                            <p class="text-[10px] font-black text-gray-400 uppercase">Semua kabar sudah dibaca</p>
                                        </div>
                                    @endforelse
                                </div>
                            </div>
                        </div>

                        <div x-data="{ userMenuOpen: false }" class="relative">
                            <button @click="userMenuOpen = !userMenuOpen" 
                                    @click.away="userMenuOpen = false" 
                                    class="flex items-center gap-3 group p-1 pr-3 rounded-2xl hover:bg-gray-50 transition-all duration-300">
                                
                                <div class="text-right hidden md:block">
                                    <p class="text-sm font-black text-gray-800 leading-none mb-1">{{ Auth::user()->name }}</p>
                                    <div class="flex justify-end">
                                        <span class="text-[9px] font-black uppercase tracking-tighter px-1.5 py-0.5 rounded-md border 
                                            {{ Auth::user()->role === 'atasan' 
                                                ? 'text-orange-600 bg-orange-50 border-orange-100' 
                                                : 'text-indigo-600 bg-indigo-50 border-indigo-100' }}">
                                            {{ Auth::user()->role === 'atasan' ? 'Administrator' : 'Pegawai Aktif' }}
                                        </span>
                                    </div>
                                </div>

                                <div class="relative">
                                    @if (Auth::user()->profile_photo_path)
                                        <img src="{{ Storage::url(Auth::user()->profile_photo_path) }}" 
                                            class="w-10 h-10 rounded-xl object-cover ring-2 ring-white shadow-md group-hover:ring-indigo-100 transition-all">
                                    @else
                                        <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center shadow-lg shadow-indigo-100 group-hover:scale-105 transition-transform">
                                            <span class="text-sm font-black text-white uppercase">{{ substr(Auth::user()->name, 0, 1) }}</span>
                                        </div>
                                    @endif
                                    <div class="absolute -bottom-0.5 -right-0.5 w-3.5 h-3.5 bg-green-500 border-2 border-white rounded-full"></div>
                                </div>
                            </button>

                            <div x-show="userMenuOpen" 
                                x-cloak
                                x-transition:enter="transition ease-out duration-200"
                                x-transition:enter-start="opacity-0 translate-y-4 scale-95"
                                x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                                x-transition:leave="transition ease-in duration-100"
                                class="absolute right-0 mt-3 w-64 bg-white rounded-2xl shadow-[0_20px_50px_rgba(0,0,0,0.1)] border border-gray-100 py-2 z-50 overflow-hidden"
                            >
                                <nav class="px-2 space-y-1">
                                    <a href="{{ route('profile') }}" 
                                    wire:navigate 
                                    class="group flex items-center gap-3 px-3 py-2.5 text-xs font-bold text-gray-600 rounded-xl hover:bg-indigo-50 hover:text-indigo-600 transition-all duration-200">
                                        <div class="p-2 rounded-lg bg-gray-50 group-hover:bg-white transition-colors">
                                            <svg class="w-4 h-4 text-gray-400 group-hover:text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                            </svg>
                                        </div>
                                        Pengaturan Profil
                                    </a>
                                </nav>

                                <div class="h-px bg-gray-50 my-2 mx-4"></div>

                                <div class="px-2">
                                    <button @click="if(confirm('Yakin ingin keluar aplikasi?')) document.getElementById('logout-form').submit();" 
                                            class="w-full flex items-center gap-3 px-3 py-2 text-red-500 rounded-xl hover:bg-red-50 transition-all duration-200 group">
                                        <div class="p-2 rounded-lg bg-red-50 group-hover:bg-red-500 group-hover:text-white transition-colors">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                            </svg>
                                        </div>
                                        <span class="text-[10px] font-black uppercase tracking-widest">Logout System</span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">
                        @csrf
                    </form>
                </header>