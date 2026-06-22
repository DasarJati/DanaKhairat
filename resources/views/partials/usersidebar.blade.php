<aside
    class="bg-gradient-to-b from-white to-gray-50 h-screen w-72 shadow-xl flex flex-col border-r border-gray-100 overflow-y-auto no-scrollbar">
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
        <a href="{{ route('user.dashboard') }}"
            class="flex items-center p-3 rounded-xl hover:bg-gold-50 text-gray-700 hover:text-gold-700 font-semibold transition-all duration-200 group {{ request()->routeIs('user.dashboard') ? 'bg-gold-50 text-gold-700' : '' }}">
            <div
                class="p-2 rounded-lg {{ request()->routeIs('user.dashboard') ? 'bg-gold-100' : 'bg-gray-50' }} group-hover:bg-gold-100 transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gold-600" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                </svg>
            </div>
            <span x-show="open" x-transition class="ml-3 text-sm tracking-wide">Laman Utama</span>
        </a>

        <div class="space-y-1">
            <button @click="openProfile = !openProfile"
                class="w-full flex items-center p-3 rounded-xl hover:bg-gold-50 text-gray-700 hover:text-gold-700 font-semibold transition-all duration-200 group">

                <div class="p-2 rounded-lg bg-gray-50 group-hover:bg-gold-100 transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gold-600" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                    </svg>
                </div>

                <span x-show="open" x-transition class="ml-3 flex-1 text-left text-sm tracking-wide">Pengurusan
                    Ahli</span>

                <svg x-show="open" xmlns="http://www.w3.org/2000/svg" :class="openProfile ? 'rotate-180' : ''"
                    class="h-4 w-4 text-gold-500 transition-transform duration-300" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                </svg>
            </button>

            <div x-show="openProfile && open" x-transition:enter="transition ease-out duration-200"
                x-transition:enter-start="opacity-0 -translate-y-2" x-transition:enter-end="opacity-100 translate-y-0"
                x-cloak class="ml-7 mt-1 border-l-2 border-gold-100 space-y-1">

                <a href="/ahli-keluarga"
                    class="group flex items-center p-2 pl-5 rounded-r-lg hover:bg-gold-50 text-gray-600 hover:text-gold-700 text-sm transition-all">
                    <span
                        class="w-1.5 h-1.5 rounded-full bg-gray-300 group-hover:bg-gold-400 mr-2 transition-colors"></span>
                    Senarai Ahli
                </a>
                <a href="/tanggungan/create"
                    class="group flex items-center p-2 pl-5 rounded-r-lg hover:bg-gold-50 text-gray-600 hover:text-gold-700 text-sm transition-all">
                    <span
                        class="w-1.5 h-1.5 rounded-full bg-gray-300 group-hover:bg-gold-400 mr-2 transition-colors"></span>
                    Tambahan Ahli
                </a>

                {{-- <a href="{{ route('user.tuntutan', auth()->user()) }}"
                    class="group flex items-center p-2 pl-5 rounded-r-lg hover:bg-gold-50 text-gray-600 hover:text-gold-700 text-sm transition-all">
                    <span
                        class="w-1.5 h-1.5 rounded-full bg-gray-300 group-hover:bg-gold-400 mr-2 transition-colors"></span>
                    Info Kematian
                </a> --}}
            </div>
        </div>

        

        <a href="{{ route('user.transactions', auth()->user()) }}"
            class="flex items-center p-3 rounded-xl hover:bg-gold-50 text-gray-700 hover:text-gold-700 font-semibold transition-all duration-200 group">
            <div class="p-2 rounded-lg bg-gray-50 group-hover:bg-gold-100 transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gold-600" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <span x-show="open" x-transition class="ml-3 text-sm tracking-wide">Kewangan</span>
        </a>
    </nav>

    <!-- Footer with version -->
    <div class="p-5 border-t border-gray-100">
        <div class="flex items-center justify-between">
            <p class="text-xs text-gray-400">DanaKhairat v2.0</p>
            <div class="h-1 w-12 bg-gold-200 rounded-full"></div>
        </div>
    </div>
</aside>

{{-- <!DOCTYPE html>
<html lang="ms">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Sidebar Aesthetic</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <script>
    tailwind.config = {
      theme: {
        extend: {
          colors: {
            'gold': {
              50: '#fefce8',
              100: '#fef9c3',
              200: '#fef08a',
              300: '#fde047',
              400: '#facc15',
              500: '#eab308',
              600: '#ca8a04',
              700: '#a16207',
              800: '#854d0e',
              900: '#713f12',
            }
          }
        }
      }
    }
  </script>
</head>
<body class="bg-gray-50">

  <aside 
      x-data="{ open: true, openCommittee: false, openPayment: false, openKhairat: false, openEvents: false, openHistory: false, openProfile: false }" 
      :class="open ? 'w-64' : 'w-20'" 
      class="bg-gradient-to-b from-white to-gray-50 h-screen shadow-lg flex flex-col transition-all duration-300 ease-in-out border-r border-gray-100">

    <!-- Header (Logo) -->
    <div class="flex items-center justify-center p-4">
        <a href="{{ route('user.dashboard') }}"> 
            <img src="{{ asset('images/logos.png') }}" 
                 alt="Logo" 
                 class="h-10 w-auto hover:cursor-pointer hover:scale-105 transition">
        </a>
    </div>

    <!-- Toggle button -->
    <button @click="open = !open" 
            class="p-2 rounded-lg hover:bg-gold-50 mx-2 transition-colors duration-200"
            :class="open ? '' : 'mx-auto'">
        <svg xmlns="http://www.w3.org/2000/svg" 
             class="h-5 w-5 text-gold-600" 
             fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                  d="M4 6h16M4 12h16M4 18h16"/>
        </svg>
    </button>

    <!-- Menu -->
    <nav class="flex-1 p-3 space-y-2">
        <a href="{{ route('user.dashboard') }}" 
          class="flex items-center p-3 rounded-xl hover:bg-gold-50 text-gray-700 hover:text-gold-700 font-semibold transition-all duration-200 group {{ request()->routeIs('user.dashboard') ? 'bg-gold-50 text-gold-700' : '' }}">
            <div class="p-2 rounded-lg {{ request()->routeIs('user.dashboard') ? 'bg-gold-100' : 'bg-gray-50' }} group-hover:bg-gold-100 transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gold-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                </svg>
            </div>
            <span x-show="open" x-transition class="ml-3 text-sm tracking-wide">Laman Utama</span>
        </a>
        
        <div class="space-y-1">
          <button @click="openProfile = !openProfile" 
              class="w-full flex items-center p-3 rounded-xl hover:bg-gold-50 text-gray-700 hover:text-gold-700 font-semibold transition-all duration-200 group">
              
              <div class="p-2 rounded-lg bg-gray-50 group-hover:bg-gold-100 transition-colors">
                  <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gold-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                  </svg>
              </div>

              <span x-show="open" x-transition class="ml-3 flex-1 text-left text-sm tracking-wide">Pengurusan Ahli</span>
              
              <svg x-show="open" xmlns="http://www.w3.org/2000/svg" 
                  :class="openProfile ? 'rotate-180' : ''" 
                  class="h-4 w-4 text-gold-500 transition-transform duration-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
              </svg>
          </button>

          <div x-show="openProfile && open" 
              x-transition:enter="transition ease-out duration-200"
              x-transition:enter-start="opacity-0 -translate-y-2"
              x-transition:enter-end="opacity-100 translate-y-0"
              x-cloak 
              class="ml-7 mt-1 border-l-2 border-gold-100 space-y-1">
              
              <a href="/ahli-keluarga" 
                class="group flex items-center p-2 pl-5 rounded-r-lg hover:bg-gold-50 text-gray-600 hover:text-gold-700 text-sm transition-all">
                  <span class="w-1.5 h-1.5 rounded-full bg-gray-300 group-hover:bg-gold-400 mr-2 transition-colors"></span>
                  Senarai Ahli
              </a>
              <a href="/tanggungan/create"
                class="group flex items-center p-2 pl-5 rounded-r-lg hover:bg-gold-50 text-gray-600 hover:text-gold-700 text-sm transition-all">
                  <span class="w-1.5 h-1.5 rounded-full bg-gray-300 group-hover:bg-gold-400 mr-2 transition-colors"></span>
                  Tambahan Ahli
              </a>

              <a href="{{ route('user.tuntutan' , auth()->user()) }}" 
                class="group flex items-center p-2 pl-5 rounded-r-lg hover:bg-gold-50 text-gray-600 hover:text-gold-700 text-sm transition-all">
                  <span class="w-1.5 h-1.5 rounded-full bg-gray-300 group-hover:bg-gold-400 mr-2 transition-colors"></span>
                  Info Kematian
              </a>
          </div>
      </div>

        <a href="/tanggungan/create" 
          class="flex items-center p-3 rounded-xl hover:bg-gold-50 text-gray-700 hover:text-gold-700 font-semibold transition-all duration-200 group">
            <div class="p-2 rounded-lg bg-gray-50 group-hover:bg-gold-100 transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gold-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                </svg>
            </div>
            <span x-show="open" x-transition class="ml-3 text-sm tracking-wide">Tambah Tanggungan</span>
        </a>

        <a href="{{ route('user.transactions' , auth()->user()) }}" 
          class="flex items-center p-3 rounded-xl hover:bg-gold-50 text-gray-700 hover:text-gold-700 font-semibold transition-all duration-200 group">
            <div class="p-2 rounded-lg bg-gray-50 group-hover:bg-gold-100 transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gold-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <span x-show="open" x-transition class="ml-3 text-sm tracking-wide">Kewangan</span>
        </a>
    </nav>
    <!-- Footer -->
    <div class="p-4 border-t border-gray-100 mt-auto">
      <div class="flex items-center justify-center">
        <div class="h-1 w-12 bg-gold-200 rounded-full"></div>
      </div>
    </div>
  </aside>

  <!-- Alpine.js -->
  <script src="//unpkg.com/alpinejs" defer></script>
</body>
</html> --}}
