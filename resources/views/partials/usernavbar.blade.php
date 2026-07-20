<nav class="sticky top-0 z-30 bg-white/40 backdrop-blur-md px-4 md:px-6 py-3 flex justify-between items-center border-b border-white/20 shadow-lg">
    
    <!-- Left side with toggle button and title -->
    <div class="flex items-center space-x-3 md:space-x-4">
        <!-- Toggle button - visible on all screens now -->
        <button @click="sidebarOpen = !sidebarOpen"
            class="p-2 rounded-lg hover:bg-gold-100 transition-all duration-200 flex items-center justify-center group z-50"
            >
            <!-- Animated hamburger icon that changes to close when sidebar is open -->
            <div class="relative w-6 h-5">
                <!-- Top line -->
                <span class="absolute block w-6 h-0.5 bg-gold-600 rounded-full transition-all duration-300"
                      :class="sidebarOpen ? 'rotate-45 top-2' : 'top-0'"></span>
                <!-- Middle line -->
                <span class="absolute block w-6 h-0.5 bg-gold-600 rounded-full transition-all duration-300 top-2"
                      :class="sidebarOpen ? 'opacity-0' : 'opacity-100'"></span>
                <!-- Bottom line -->
                <span class="absolute block w-6 h-0.5 bg-gold-600 rounded-full transition-all duration-300"
                      :class="sidebarOpen ? '-rotate-45 top-2' : 'top-4'"></span>
            </div>
            
            <!-- Tooltip for desktop -->
            <span class="absolute left-16 bg-gray-800 text-white text-xs rounded px-2 py-1 opacity-0 group-hover:opacity-100 transition-opacity duration-200 pointer-events-none whitespace-nowrap lg:block hidden"
                  x-text="sidebarOpen ? 'Tutup Sidebar' : 'Buka Sidebar'">
            </span>
        </button>

        <!-- Title -->
        <span class="text-lg md:text-xl font-bold text-gray-500 tracking-tight drop-shadow-sm">
            @auth
                @if(auth()->user()->role == 1) 
                    {{ optional(auth()->user()->masjid)->nama ?? 'Masjid' }}
                @elseif(auth()->user()->role == 2)
                    Dana eKhairat
                @elseif(auth()->user()->role == 0)
                    Admin Panel
                @endif
            @else
                    Dana eKhairat
            @endauth
        </span>
    </div>

    <!-- Right side user menu -->
    <div class="flex items-center space-x-2 md:space-x-4 relative" x-data="{ userMenuOpen: false }">
        @auth
        <!-- Welcome text - hidden on mobile -->
        <div class="hidden md:block text-right">
            <p class="text-[10px] uppercase tracking-widest text-gray-500 font-bold">Selamat Datang,</p>
            <p class="text-gold-700 font-bold text-sm">
                @if(auth()->user()->role == 1) {{ auth()->user()->nama ?? 'AJK User' }}
                @elseif(auth()->user()->role == 2) {{ auth()->user()->nama ?? 'Ahli Khairat' }}
                @elseif(auth()->user()->role == 0) {{ auth()->user()->name ?? 'Admin' }}
                @endif
            </p>
        </div>

        <!-- User avatar button -->
        <button @click="userMenuOpen = !userMenuOpen" 
                @click.away="userMenuOpen = false"
                class="flex items-center space-x-1 md:space-x-2 p-1 pr-2 md:pr-3 bg-white/50 border border-white/50 rounded-full shadow-sm hover:bg-white/80 transition-all duration-300 focus:outline-none relative">
            
            <div class="w-8 h-8 md:w-9 md:h-9 bg-gradient-to-tr from-gold-400 to-gold-600 rounded-full flex items-center justify-center shadow-inner">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 md:w-5 md:h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                </svg>
            </div>

            <svg xmlns="http://www.w3.org/2000/svg"
                class="w-3 h-3 md:w-4 md:h-4 text-gold-700 transition-transform duration-300"
                :class="userMenuOpen ? 'rotate-180' : 'rotate-0'"
                fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
            </svg>

            <!-- Status indicator for mobile -->
            <span class="absolute -top-1 -right-1 w-3 h-3 bg-green-500 border-2 border-white rounded-full md:hidden"></span>
        </button>

        <!-- User Dropdown Menu -->
        <div
            x-show="userMenuOpen"
            x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0 scale-95 translate-y-2"
            x-transition:enter-end="opacity-100 scale-100 translate-y-0"
            x-transition:leave="transition ease-in duration-150"
            x-transition:leave-start="opacity-100 scale-100 translate-y-0"
            x-transition:leave-end="opacity-0 scale-95 translate-y-2"
            class="absolute right-0 top-12 md:top-16 w-56 bg-white/90 backdrop-blur-xl border border-white/30 shadow-2xl rounded-2xl py-2 z-50 overflow-hidden"
            @click.away="userMenuOpen = false">
            
            <!-- Mobile user info -->
            <div class="lg:hidden px-4 py-3 bg-gradient-to-r from-gold-50 to-white border-b border-gray-100">
                <p class="text-sm font-bold text-gray-800">{{ auth()->user()->nama ?? 'User' }}</p>
                <p class="text-xs text-gold-600 mt-0.5">
                    @if(auth()->user()->role == 1) AJK Masjid
                    @elseif(auth()->user()->role == 2) Ahli Khairat
                    @elseif(auth()->user()->role == 0) Admin
                    @endif
                </p>
            </div>

            <!-- Desktop user info -->
            {{-- <div class="hidden lg:block px-4 py-2 mb-1 border-b border-gray-100">
                <p class="text-xs text-gray-500">Dilog masuk sebagai</p>
                <p class="text-sm font-bold text-gold-700 truncate">{{ auth()->user()->nama ?? 'User' }}</p>
            </div> --}}

            @if(auth()->user()->role == 1)
                <a href="{{ route('ajk.profile') }}"
                    class="flex items-center px-4 py-3 text-sm font-medium text-gray-700 hover:bg-gold-500 hover:text-white transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                    Profil AJK
                </a>
            @elseif(auth()->user()->role == 2)
                <a href="{{ route('ahli.profile') }}"
                    class="flex items-center px-4 py-3 text-sm font-medium text-gray-700 hover:bg-gold-500 hover:text-white transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                    Profil Ahli
                </a>
            @endif

            {{-- <a href="/pic/editprofile" class="flex items-center px-4 py-3 text-sm font-medium text-gray-700 hover:bg-gold-500 hover:text-white transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                </svg>
                Kemaskini Profil
            </a> --}}

            <div class="border-t border-gray-100/50 my-1"></div>

            <form method="POST" action="{{ route('logout') }}" class="w-full">
                @csrf
                <button type="submit" class="w-full text-left flex items-center px-4 py-3 text-sm font-bold text-red-500 hover:bg-red-50 transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                    </svg>
                    Log Keluar
                </button>
            </form>
        </div>
        @endauth

        @guest
        <a href="{{ route('login') }}" class="px-4 py-2 bg-gold-500 text-white rounded-lg text-sm font-bold hover:bg-gold-600 transition">
            Log Masuk
        </a>
        @endguest
    </div>
</nav>

{{-- <nav class="bg-gradient-to-b from-white to-gray-50 px-4 md:px-8 py-4 flex justify-between items-center shadow-sm border-b border-gray-100">

    @if(Auth::guard('web')->check())
        <div class="flex flex-col">
            <div class="flex flex-wrap items-center gap-x-3 gap-y-1 text-[11px] md:text-sm text-gray-600 mt-1 md:mt-2">
                <span class="flex items-center gap-1">
                    <span class="hidden md:inline text-gray-400">Status:</span>
                    <span class="px-2 py-0.5 rounded-full text-[10px] md:text-sm font-bold md:font-semibold
                        {{ Auth::guard('web')->user()->status === 'ACTIVE' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                        {{ Auth::guard('web')->user()->status }}
                    </span>
                </span>

                <span class="hidden md:inline text-gray-300">|</span>

                <span class="flex items-center">
                    <span class="hidden md:inline text-gray-400 mr-1">Tempoh Keahlian:</span>
                    <span class="font-medium text-gray-700 text-[10px] md:text-sm bg-gray-100 md:bg-transparent px-2 py-0.5 rounded md:p-0">
                        {{ Auth::guard('web')->user()->membership_start
                            ? \Carbon\Carbon::parse(Auth::guard('web')->user()->membership_start)->format('d/m/y')
                            : '-' }}
                        <span class="mx-0.5">–</span>
                        {{ Auth::guard('web')->user()->membership_end
                            ? \Carbon\Carbon::parse(Auth::guard('web')->user()->membership_end)->format('d/m/y')
                            : '-' }}
                    </span>
                </span>
            </div>
        </div>
    @endif

    <div class="flex items-center gap-2 md:gap-4 relative" x-data="{ open: false }">

        @if(Auth::guard('web')->check())
            <div class="text-right leading-tight">
                <p class="text-[10px] text-gray-400 font-medium uppercase tracking-wider">User</p>
                <p class="text-xs md:text-sm font-bold text-gray-800 truncate max-w-[80px] md:max-w-[150px]">
                    {{ Auth::guard('web')->user()->nama }}
                </p>
            </div>
        @endif

        <button
            @click="open = !open"
            class="flex items-center justify-center w-8 h-8 md:w-9 md:h-9 rounded-full bg-amber-100 hover:bg-amber-200 transition-colors shadow-sm"
        >
            <svg xmlns="http://www.w3.org/2000/svg"
                 class="w-3 h-3 md:w-4 md:h-4 text-amber-700 transition-transform duration-200"
                 :class="open ? 'rotate-180' : 'rotate-0'"
                 fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M19 9l-7 7-7-7"/>
            </svg>
        </button>

        <div
            x-show="open"
            @click.outside="open = false"
            x-transition:enter="transition ease-out duration-100"
            x-transition:enter-start="opacity-0 scale-95"
            x-transition:enter-end="opacity-100 scale-100"
            class="absolute right-0 top-11 md:top-12 w-40 md:w-48 bg-white border border-amber-100 rounded-xl shadow-xl py-2 z-[100]"
        >
            <div class="block md:hidden px-4 py-2 border-b border-gray-50 mb-1">
                <p class="text-[10px] text-gray-400 uppercase">Akaun</p>
                <p class="text-xs font-bold text-gray-700 truncate">{{ Auth::guard('web')->user()->nama }}</p>
            </div>
            
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit"
                        class="w-full flex items-center px-4 py-2 text-sm text-red-600 hover:bg-red-50 transition">
                    <svg xmlns="http://www.w3.org/2000/svg"
                         class="h-4 w-4 text-red-500 mr-2"
                         fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                    </svg>
                    Log Keluar
                </button>
            </form>
        </div>
    </div>
</nav> --}}
