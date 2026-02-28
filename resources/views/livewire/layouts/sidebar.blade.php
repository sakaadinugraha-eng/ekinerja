<aside 
                :class="sidebarOpen ? 'translate-x-0 w-64' : '-translate-x-full md:translate-x-0 md:w-20'" 
                class="fixed inset-y-0 left-0 z-50 bg-[#1e1b4b] text-indigo-100 transform md:relative transition-all duration-300 ease-in-out shadow-2xl flex flex-col min-h-screen overflow-hidden border-r border-indigo-800/50"
            >
                <div class="p-6 h-16 flex items-center justify-center border-b border-indigo-800/50 bg-indigo-950/30 backdrop-blur-md">
                    <div class="flex items-center gap-3 group cursor-default">
                        <div class="w-9 h-9 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-xl flex items-center justify-center shadow-lg shadow-indigo-500/30 group-hover:scale-110 transition-transform duration-300">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 10V3L4 14h7v7l9-11h-7z" />
                            </svg>
                        </div>
                        <h1 x-show="sidebarOpen" x-transition.opacity.duration.300ms class="text-xl font-black tracking-tighter text-white italic whitespace-nowrap">
                            E-KINERJA
                        </h1>
                    </div>
                </div>

                <nav class="flex-1 px-3 py-6 space-y-1.5 overflow-y-auto whitespace-nowrap scrollbar-hide">
                    <p x-show="sidebarOpen" class="text-[10px] font-black text-indigo-400 uppercase px-4 mb-3 tracking-[0.2em] opacity-70">Utama</p>
                    
                    <a href="{{ route('dashboard') }}" 
                    class="group flex items-center gap-4 px-4 py-3 rounded-xl transition-all duration-200 
                    {{ request()->routeIs('dashboard') ? 'bg-white text-indigo-900 shadow-xl shadow-indigo-950/20' : 'hover:bg-indigo-800/40 hover:text-white' }}">
                        <svg class="w-5 h-5 flex-shrink-0 {{ request()->routeIs('dashboard') ? 'text-indigo-600' : 'text-indigo-300 group-hover:text-white' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                        </svg>
                        <span x-show="sidebarOpen" class="font-bold text-sm">Dashboard</span>
                    </a>

                    <a href="{{ route('kinerja.index') }}" 
                    class="group flex items-center gap-4 px-4 py-3 rounded-xl transition-all duration-200 
                    {{ request()->routeIs('kinerja.index') ? 'bg-white text-indigo-900 shadow-xl shadow-indigo-950/20' : 'hover:bg-indigo-800/40 hover:text-white' }}">
                        <svg class="w-5 h-5 flex-shrink-0 {{ request()->routeIs('kinerja.index') ? 'text-indigo-600' : 'text-indigo-300 group-hover:text-white' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                        </svg>
                        <span x-show="sidebarOpen" class="font-bold text-sm">Input Kinerja</span>
                    </a>

                    @can('akses-atasan')
                    <div class="pt-6 mt-6 border-t border-indigo-800/50">
                        <p x-show="sidebarOpen" class="text-[10px] font-black text-indigo-400 uppercase px-4 mb-3 tracking-[0.2em] opacity-70">Atasan / Admin</p>
                        
                        <a href="{{ route('atasan.validasi') }}" 
                        class="group flex items-center gap-4 px-4 py-3 rounded-xl transition-all duration-200 
                        {{ request()->routeIs('atasan.validasi') ? 'bg-white text-indigo-900 shadow-xl shadow-indigo-950/20' : 'hover:bg-indigo-800/40 hover:text-white' }}">
                            <svg class="w-5 h-5 flex-shrink-0 {{ request()->routeIs('atasan.validasi') ? 'text-indigo-600' : 'text-indigo-300 group-hover:text-white' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <span x-show="sidebarOpen" class="font-bold text-sm">Validasi Kinerja</span>
                        </a>

                        <a href="{{ route('referensi.index') }}" 
                        class="group flex items-center gap-4 px-4 py-3 rounded-xl transition-all duration-200 
                        {{ request()->routeIs('referensi.index') ? 'bg-white text-indigo-900 shadow-xl shadow-indigo-950/20' : 'hover:bg-indigo-800/40 hover:text-white' }}">
                            <svg class="w-5 h-5 flex-shrink-0 {{ request()->routeIs('referensi.index') ? 'text-indigo-600' : 'text-indigo-300 group-hover:text-white' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                            </svg>
                            <span x-show="sidebarOpen" class="font-bold text-sm">Data Referensi</span>
                        </a>

                        <a href="{{ route('atasan.users') }}" 
                        class="group flex items-center gap-4 px-4 py-3 rounded-xl transition-all duration-200 
                        {{ request()->routeIs('atasan.users') ? 'bg-white text-indigo-900 shadow-xl shadow-indigo-950/20' : 'hover:bg-indigo-800/40 hover:text-white' }}">
                            <svg class="w-5 h-5 flex-shrink-0 {{ request()->routeIs('atasan.users') ? 'text-indigo-600' : 'text-indigo-300 group-hover:text-white' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                            </svg>
                            <span x-show="sidebarOpen" class="font-bold text-sm">Kelola User</span>
                        </a>
                    </div>
                    @endcan
                </nav>
            </aside>