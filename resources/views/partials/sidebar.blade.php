

<aside
    class="bg-gradient-to-b from-white to-gray-50 h-screen w-64 shadow-xl flex flex-col border-r border-gray-100 overflow-y-auto no-scrollbar">
    <!-- Header with logo and close button for mobile -->
    <div class="flex items-center justify-center p-5 border-b border-gray-100">
        <div class="flex items-center space-x-3">
            <a href="{{ route('pic.dashboard') }}" class="flex items-center  group">
                <img src="{{ asset('images/logos.png') }}" alt="Logo"
                    class="h-10 w-auto  shadow-sm group-hover:scale-105 transition-transform">

            </a>
        </div>

        <!-- Close button - visible on mobile only -->
        <button @click="sidebarOpen = false" class="lg:hidden p-2 hover:bg-gold-100 rounded-lg transition-colors">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-500" fill="none" viewBox="0 0 24 24"
                stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>
    </div>

    <!-- User info for mobile -->
    @auth
        <div class="lg:hidden p-4 bg-gradient-to-r from-gold-50 to-white border-b border-gray-100">
            <div class="flex items-center space-x-3">
                <div
                    class="w-10 h-10 bg-gradient-to-br from-gold-400 to-gold-600 rounded-full flex items-center justify-center">
                    <span class="text-white font-bold text-sm">
                        {{ substr(auth()->user()->nama ?? 'U', 0, 1) }}
                    </span>
                </div>
                <div>
                    <p class="text-sm font-bold text-gray-800">{{ auth()->user()->nama ?? 'User' }}</p>
                    <p class="text-xs text-gold-600">
                        @if (auth()->user()->role == 1)
                            AJK Masjid
                        @elseif(auth()->user()->role == 2)
                            Ahli Khairat
                        @elseif(auth()->user()->role == 0)
                            Admin
                        @endif
                    </p>
                </div>
            </div>
        </div>
    @endauth

    <!-- Menu -->
    <nav class="flex-1 p-4 space-y-1.5">
        <!-- Dashboard -->
        <a href="{{ route('pic.dashboard') }}"
            class="flex items-center p-3 rounded-xl hover:bg-gold-50 text-gray-700 font-medium transition-all duration-200 group {{ request()->routeIs('pic.dashboard') ? 'bg-gold-50 text-gold-700' : '' }}">
            <div class="p-1.5 rounded-lg bg-gold-50 group-hover:bg-gold-100 transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gold-600" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M3 3h7v7H3zM14 3h7v7h-7zM14 14h7v7h-7zM3 14h7v7H3z" />
                </svg>
            </div>
            <span class="ml-3">Laman Utama</span>

            @if (request()->routeIs('pic.dashboard'))
                <span class="ml-auto w-1.5 h-1.5 bg-gold-500 rounded-full"></span>
            @endif
        </a>

        <!-- Pengurusan Masjid -->
        <a href="{{ route('masjid.index') }}"
            class="flex items-center p-3 rounded-xl hover:bg-gold-50 text-gray-700 font-medium transition-all duration-200 group {{ request()->routeIs('masjid.index') ? 'bg-gold-50 text-gold-700' : '' }}">
            <div class="p-1.5 rounded-lg bg-gold-50 group-hover:bg-gold-100 transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gold-600" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3l8 5v13H4V8l8-5z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 21V12h6v9" />
                </svg>
            </div>
            <span class="ml-3">Pengurusan</span>

            @if (request()->routeIs('masjid.index'))
                <span class="ml-auto w-1.5 h-1.5 bg-gold-500 rounded-full"></span>
            @endif
        </a>

        <!-- Ahli Khairat -->
        {{-- <a href="{{ route('list.kariah') }}"
            class="flex items-center p-3 rounded-xl hover:bg-gold-50 text-gray-700 font-medium transition-all duration-200 group {{ request()->routeIs('list.kariah') ? 'bg-gold-50 text-gold-700' : '' }}">
            <div class="p-1.5 rounded-lg bg-gold-50 group-hover:bg-gold-100 transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gold-600" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                </svg>
            </div>
            <span class="ml-3">Ahli Khairat</span>

            @if (request()->routeIs('list.kariah'))
                <span class="ml-auto w-1.5 h-1.5 bg-gold-500 rounded-full"></span>
            @endif
        </a> --}}

        <!-- Ahli Khairat -->
        <div x-data="{ open: {{ request()->routeIs('kariah*') ? 'true' : 'false' }} }" class="relative">
            <!-- Main Menu Button -->
            <button @click="open = !open"
                class="w-full flex items-center justify-between p-3 rounded-xl hover:bg-gold-50 text-gray-700 font-medium transition-all duration-200 group {{ request()->routeIs('kariah*') ? 'bg-gold-50 text-gold-700' : '' }}">
                <div class="flex items-center">
                    <div class="p-1.5 rounded-lg bg-gold-50 group-hover:bg-gold-100 transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="h-5 w-5 text-gold-600">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M18 18.72a9.094 9.094 0 0 0 3.741-.479 3 3 0 0 0-4.682-2.72m.94 3.198.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0 1 12 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 0 1 6 18.719m12 0a5.971 5.971 0 0 0-.941-3.197m0 0A5.995 5.995 0 0 0 12 12.75a5.995 5.995 0 0 0-5.058 2.772m0 0a3 3 0 0 0-4.681 2.72 8.986 8.986 0 0 0 3.74.477m.94-3.197a5.971 5.971 0 0 0-.94 3.197M15 6.75a3 3 0 1 1-6 0 3 3 0 0 1 6 0Zm6 3a2.25 2.25 0 1 1-4.5 0 2.25 2.25 0 0 1 4.5 0Zm-13.5 0a2.25 2.25 0 1 1-4.5 0 2.25 2.25 0 0 1 4.5 0Z" />
                        </svg>

                    </div>
                    <span class="ml-3">Ahli Khairat</span>
                </div>

                <!-- Dropdown Arrow & Active Indicator -->
                <div class="flex items-center gap-2">
                    <svg class="w-4 h-4 transition-transform duration-200 {{ request()->routeIs('kariah*') ? 'text-gold-600' : 'text-gray-400' }}"
                        :class="{ 'rotate-180': open }" xmlns="http://www.w3.org/2000/svg" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                    @if (request()->routeIs('kariah*'))
                        <span class="w-1.5 h-1.5 bg-gold-500 rounded-full"></span>
                    @endif
                </div>
            </button>

            <!-- Dropdown Menu -->
            <div x-show="open" x-transition:enter="transition ease-out duration-200"
                x-transition:enter-start="opacity-0 -translate-y-2" x-transition:enter-end="opacity-100 translate-y-0"
                x-transition:leave="transition ease-in duration-150"
                x-transition:leave-start="opacity-100 translate-y-0" x-transition:leave-end="opacity-0 -translate-y-2"
                class="mt-1 ml-1 space-y-1">

                <!-- Sub-menu 1: Ringkasan Kewangan -->
                <a href="{{ route('kariah.list') }}"
                    class="block px-4 py-2.5 text-sm rounded-lg transition-all duration-200 {{ request()->routeIs('kariah.list') ? 'bg-gold-100 text-gold-700 font-semibold' : 'text-gray-600 hover:bg-gold-50 hover:text-gold-700' }}">
                    <div class="flex items-center">
                        <span
                            class="w-1.5 h-1.5 rounded-full {{ request()->routeIs('kariah.list') ? 'bg-gold-500' : 'bg-gray-300' }} mr-3"></span>
                        <span>Senarai</span>
                    </div>
                </a>

                <!-- Sub-menu 2: Laporan Kewangan -->
                <a href="{{ route('kariah.list.pengesahan') }}"
                    class="block px-4 py-2.5 text-sm rounded-lg transition-all duration-200 {{ request()->routeIs('kariah.list.pengesahan') ? 'bg-gold-100 text-gold-700 font-semibold' : 'text-gray-600 hover:bg-gold-50 hover:text-gold-700' }}">
                    <div class="flex items-center">
                        <span
                            class="w-1.5 h-1.5 rounded-full {{ request()->routeIs('kariah.list.pengesahan') ? 'bg-gold-500' : 'bg-gray-300' }} mr-3"></span>
                        <span>Pengesahan</span>
                    </div>
                </a>

                <a href="{{ route('change-ketua.index') }}"
                    class="block px-4 py-2.5 text-sm rounded-lg transition-all duration-200 {{ request()->routeIs('change-ketua.index') ? 'bg-gold-100 text-gold-700 font-semibold' : 'text-gray-600 hover:bg-gold-50 hover:text-gold-700' }}">
                    <div class="flex items-center">
                        <span
                            class="w-1.5 h-1.5 rounded-full {{ request()->routeIs('change-ketua.index') ? 'bg-gold-500' : 'bg-gray-300' }} mr-3"></span>
                        <span>Tukar Ketua</span>
                    </div>
                </a>

                <a href="{{ route('ajk.bulk-register.index') }}"
                    class="block px-4 py-2.5 text-sm rounded-lg transition-all duration-200 {{ request()->routeIs('ajk.bulk-register.index') ? 'bg-gold-100 text-gold-700 font-semibold' : 'text-gray-600 hover:bg-gold-50 hover:text-gold-700' }}">
                    <div class="flex items-center">
                        <span
                            class="w-1.5 h-1.5 rounded-full {{ request()->routeIs('ajk.bulk-register.index') ? 'bg-gold-500' : 'bg-gray-300' }} mr-3"></span>
                        <span>Bulk Daftar</span>
                    </div>
                </a>
            </div>
        </div>

        <!-- Kewangan -->
        <div x-data="{ open: {{ request()->routeIs('finance*') ? 'true' : 'false' }} }" class="relative">
    <!-- Main Menu Button -->
    <button @click="open = !open" 
            class="w-full flex items-center justify-between p-3 rounded-xl hover:bg-gold-50 text-gray-700 font-medium transition-all duration-200 group {{ request()->routeIs('finance*') ? 'bg-gold-50 text-gold-700' : '' }}">
        <div class="flex items-center">
            <div class="p-1.5 rounded-lg bg-gold-50 group-hover:bg-gold-100 transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                    stroke="currentColor" class="h-5 w-5 text-gold-600">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M2.25 18.75a60.07 60.07 0 0 1 15.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 0 1 3 6h-.75m0 0v-.375c0-.621.504-1.125 1.125-1.125H20.25M2.25 6v9m18-10.5v.75c0 .414.336.75.75.75h.75m-1.5-1.5h.375c.621 0 1.125.504 1.125 1.125v9.75c0 .621-.504 1.125-1.125 1.125h-.375m1.5-1.5H21a.75.75 0 0 0-.75.75v.75m0 0H3.75m0 0h-.375a1.125 1.125 0 0 1-1.125-1.125V15m1.5 1.5v-.75A.75.75 0 0 0 3 15h-.75M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Zm3 0h.008v.008H18V10.5Zm-12 0h.008v.008H6V10.5Z" />
                </svg>
            </div>
            <span class="ml-3">Kewangan</span>
        </div>
        
        <!-- Dropdown Arrow & Active Indicator -->
        <div class="flex items-center gap-2">
            <svg class="w-4 h-4 transition-transform duration-200 {{ request()->routeIs('finance*') ? 'text-gold-600' : 'text-gray-400' }}" 
                 :class="{ 'rotate-180': open }" 
                 xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
            </svg>
             @if (request()->routeIs('finance*'))
                <span class="w-1.5 h-1.5 bg-gold-500 rounded-full"></span>
            @endif
        </div>
    </button>

    <!-- Dropdown Menu -->
    <div x-show="open" 
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 -translate-y-2"
         x-transition:enter-end="opacity-100 translate-y-0"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100 translate-y-0"
         x-transition:leave-end="opacity-0 -translate-y-2"
         class="mt-1 ml-1 space-y-1">
        
        <!-- Sub-menu 1: Ringkasan Kewangan -->
        <a href="{{ route('finance.dana') }}" 
           class="block px-4 py-2.5 text-sm rounded-lg transition-all duration-200 {{ request()->routeIs('finance.dana') ? 'bg-gold-100 text-gold-700 font-semibold' : 'text-gray-600 hover:bg-gold-50 hover:text-gold-700' }}">
            <div class="flex items-center">
                <span class="w-1.5 h-1.5 rounded-full {{ request()->routeIs('finance.dana') ? 'bg-gold-500' : 'bg-gray-300' }} mr-3"></span>
                <span>Pengesahan Bayaran</span>
            </div>
        </a>

        <!-- Sub-menu 2: Laporan Kewangan -->
        <a href="{{ route('finance') }}" 
           class="block px-4 py-2.5 text-sm rounded-lg transition-all duration-200 {{ request()->routeIs('finance') ? 'bg-gold-100 text-gold-700 font-semibold' : 'text-gray-600 hover:bg-gold-50 hover:text-gold-700' }}">
            <div class="flex items-center">
                <span class="w-1.5 h-1.5 rounded-full {{ request()->routeIs('finance') ? 'bg-gold-500' : 'bg-gray-300' }} mr-3"></span>
                <span>Senarai Keluar/Masuk</span>
            </div>
        </a>
    </div>
</div>

        {{-- <a href="{{ route('finance') }}"
            class="flex items-center p-3 rounded-xl hover:bg-gold-50 text-gray-700 font-medium transition-all duration-200 group {{ request()->routeIs('finance') ? 'bg-gold-50 text-gold-700' : '' }}">
            <div class="p-1.5 rounded-lg bg-gold-50 group-hover:bg-gold-100 transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                    stroke="currentColor" class="h-5 w-5 text-gold-600">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M2.25 18.75a60.07 60.07 0 0 1 15.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 0 1 3 6h-.75m0 0v-.375c0-.621.504-1.125 1.125-1.125H20.25M2.25 6v9m18-10.5v.75c0 .414.336.75.75.75h.75m-1.5-1.5h.375c.621 0 1.125.504 1.125 1.125v9.75c0 .621-.504 1.125-1.125 1.125h-.375m1.5-1.5H21a.75.75 0 0 0-.75.75v.75m0 0H3.75m0 0h-.375a1.125 1.125 0 0 1-1.125-1.125V15m1.5 1.5v-.75A.75.75 0 0 0 3 15h-.75M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Zm3 0h.008v.008H18V10.5Zm-12 0h.008v.008H6V10.5Z" />
                </svg>
            </div>
            <span class="ml-3">Kewangan</span>

            @if (request()->routeIs('finance'))
                <span class="ml-auto w-1.5 h-1.5 bg-gold-500 rounded-full"></span>
            @endif
        </a> --}}

        <!-- Rekod Kematian -->
        <a href="{{ route('tuntut') }}"
            class="flex items-center p-3 rounded-xl hover:bg-gold-50 text-gray-700 font-medium transition-all duration-200 group {{ request()->routeIs('tuntut') ? 'bg-gold-50 text-gold-700' : '' }}">
            <div class="p-1.5 rounded-lg bg-gold-50 group-hover:bg-gold-100 transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gold-600" fill="none"
                    viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M7 21h10a2 2 0 002-2V7l-5-5H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                </svg>
            </div>
            <span class="ml-3">Rekod Kematian</span>

            @if (request()->routeIs('tuntut'))
                <span class="ml-auto w-1.5 h-1.5 bg-gold-500 rounded-full"></span>
            @endif
        </a>
    </nav>

    <!-- Footer with version -->
    
</aside>
