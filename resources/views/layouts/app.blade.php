<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>{{ config('app.name', 'Laravel') }}</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased bg-gray-100" x-data="{ sidebarOpen: true }">
        <div class="min-h-screen flex overflow-hidden">
            
            <aside 
                :class="sidebarOpen ? 'translate-x-0 w-64' : '-translate-x-full md:translate-x-0 md:w-0'" 
                class="fixed inset-y-0 left-0 z-50 bg-indigo-900 text-white transform md:relative transition-all duration-300 ease-in-out shadow-2xl flex flex-col min-h-screen overflow-hidden"
            >
                <div class="p-6 text-center border-b border-indigo-800 whitespace-nowrap h-16 flex items-center justify-center">
                    <h1 class="text-2xl font-black tracking-tighter">E-KINERJA</h1>
                </div>

                <nav class="flex-1 px-4 py-6 space-y-2 overflow-y-auto whitespace-nowrap">
                    <a href="{{ route('dashboard') }}" 
                       class="flex items-center gap-4 px-4 py-3 rounded-xl transition duration-200 {{ request()->routeIs('dashboard') ? 'bg-indigo-700 text-white shadow-lg' : 'text-indigo-300 hover:bg-indigo-800 hover:text-white' }}">
                        <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                        <span class="font-bold text-sm">Dashboard</span>
                    </a>

                    <a href="{{ route('kinerja.index') }}" 
                       class="flex items-center gap-4 px-4 py-3 rounded-xl transition duration-200 {{ request()->routeIs('kinerja.index') ? 'bg-indigo-700 text-white shadow-lg' : 'text-indigo-300 hover:bg-indigo-800 hover:text-white' }}">
                        <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                        <span class="font-bold text-sm">Input Kinerja</span>
                    </a>
                </nav>

                <div class="p-4 border-t border-indigo-800 whitespace-nowrap">
                    <div class="hidden">
                        <livewire:layout.navigation />
                    </div>

                    <button onclick="if(confirm('Yakin ingin keluar aplikasi?')) document.getElementById('real-logout-btn').click();" 
                            class="w-full flex items-center gap-3 px-4 py-2 text-red-400 hover:bg-red-500/10 rounded-lg transition text-xs font-black uppercase tracking-widest border-none outline-none cursor-pointer">
                        <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                        </svg>
                        <span>Keluar Aplikasi</span>
                    </button>
                </div>
            </aside>

            <div class="flex-1 flex flex-col min-w-0 overflow-hidden">
                
                <header class="bg-white border-b border-gray-200 h-16 flex items-center justify-between px-4 sm:px-6 lg:px-8 z-10">
                    
                    <div class="flex items-center gap-4">
                        <button @click="sidebarOpen = !sidebarOpen" class="p-2 rounded-lg text-gray-500 hover:bg-gray-100 focus:outline-none transition-colors">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7" />
                            </svg>
                        </button>

                        @if (isset($header))
                            <div class="font-bold text-gray-800 text-lg uppercase tracking-tight truncate hidden md:block">
                                {{ $header }}
                            </div>
                        @endif
                    </div>

                    <div class="flex items-center gap-3">
                        <div class="text-right hidden md:block">
                            <p class="text-sm font-bold text-gray-700 leading-none">{{ Auth::user()->name }}</p>
                            <span class="text-[10px] text-indigo-500 font-bold uppercase tracking-wider">Pegawai Aktif</span>
                        </div>
                        <div class="h-9 w-9 rounded-full bg-indigo-600 flex items-center justify-center font-bold text-white shadow-md border-2 border-indigo-100 uppercase">
                            {{ substr(Auth::user()->name, 0, 1) }}
                        </div>
                    </div>

                </header>

                <main class="flex-1 overflow-y-auto bg-gray-100 p-4 md:p-8">
                    <div class="max-w-7xl mx-auto">
                        {{ $slot }}
                    </div>
                </main>
            </div>
        </div>
    </body>
</html>