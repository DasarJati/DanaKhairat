<!DOCTYPE html>
<html lang="ms">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Sidebar Admin Premium</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  <script>
    tailwind.config = {
      theme: {
        extend: {
          fontFamily: { sans: ['Inter', 'sans-serif'] },
          colors: {
            'gold': {
              50: '#fefce8', 100: '#fef9c3', 200: '#fef08a', 300: '#fde047',
              400: '#facc15', 500: '#eab308', 600: '#ca8a04', 700: '#a16207',
              800: '#854d0e', 900: '#713f12',
            }
          }
        }
      }
    }
  </script>
  <style>
    [x-cloak] { display: none !important; }
    .custom-scrollbar::-webkit-scrollbar { width: 4px; }
    .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background: #eab308; border-radius: 10px; }
  </style>
</head>
<body class="bg-gray-50">

  <aside 
      x-data="{ 
        open: true, 
        openPengesahan: false, 
        openAhli: false, 
        openWallet: false,
        openProfile: false 
      }" 
      :class="open ? 'w-72' : 'w-20'" 
      class="bg-gradient-to-b from-white to-gray-50 h-screen shadow-xl flex flex-col transition-all duration-500 ease-in-out border-r border-gray-200 overflow-hidden"
  >

    <div class="flex flex-col items-center py-6 px-4">
        <div class="relative group">
            <div class="flex items-center justify-center p-4">
            <img src="{{ asset('images/logos.png') }}" 
                 alt="Logo" 
                 class="h-10 w-auto hover:cursor-pointer hover:scale-105 transition">
        </div>
    </div>

    <div class="px-4 mb-4">
        <button @click="open = !open" 
                class="flex items-center justify-center p-2 w-full rounded-xl hover:bg-gold-50 text-gold-600 transition-all duration-300">
            <svg xmlns="http://www.w3.org/2000/svg" :class="!open && 'rotate-180'" class="h-5 w-5 transition-transform duration-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 19l-7-7 7-7m8 14l-7-7 7-7"/>
            </svg>
        </button>
    </div>

    <nav class="flex-1 px-4 space-y-2 overflow-y-auto custom-scrollbar relative">
        
        <div class="pb-2">
            <p x-show="open" class="px-4 text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-2">Utama</p>
            
            <a href="{{ route('admin.main') }}" 
               class="group flex items-center p-3 rounded-2xl text-gray-600 hover:text-gold-700 hover:bg-gold-50/50 transition-all duration-300">
                <div class="p-2 rounded-xl bg-gray-100 group-hover:bg-gold-100 group-hover:text-gold-600 transition-colors">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="M3 3h7v7H3zM14 3h7v7h-7zM14 14h7v7h-7zM3 14h7v7H3z"/>
                    </svg>
                </div>
                <span x-show="open" class="ml-4 font-semibold text-sm">Dashboard</span>
            </a>
        </div>

        <div class="pb-2">
            <p x-show="open" class="px-4 text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-2">Pengurusan</p>

            <div class="space-y-1">
                <button @click="openPengesahan = !openPengesahan" 
                        class="group flex items-center w-full p-3 rounded-2xl text-gray-600 hover:text-gold-700 hover:bg-gold-50/50 transition-all">
                    <div class="p-2 rounded-xl bg-gray-100 group-hover:bg-gold-100 group-hover:text-gold-600">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <span x-show="open" class="ml-4 flex-1 text-left text-sm font-semibold">Pengesahan</span>
                    <svg x-show="open" :class="openPengesahan && 'rotate-180'" class="h-4 w-4 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                    </svg>
                </button>
                <div x-show="openPengesahan && open" x-cloak class="ml-12 space-y-1 border-l-2 border-gold-100">
                    <a href="/admin/dashboard" class="block p-2 pl-4 text-xs text-gray-500 hover:text-gold-600 transition-colors">Institusi</a>
                    <a href="/user-approval" class="block p-2 pl-4 text-xs text-gray-500 hover:text-gold-600 transition-colors">Ahli Khairat</a>
                </div>
            </div>

            <div class="space-y-1">
                <button @click="openAhli = !openAhli" 
                        class="group flex items-center w-full p-3 rounded-2xl text-gray-600 hover:text-gold-700 hover:bg-gold-50/50 transition-all">
                    <div class="p-2 rounded-xl bg-gray-100 group-hover:bg-gold-100 group-hover:text-gold-600">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                        </svg>
                    </div>
                    <span x-show="open" class="ml-4 flex-1 text-left text-sm font-semibold">Senarai</span>
                    <svg x-show="open" :class="openAhli && 'rotate-180'" class="h-4 w-4 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-width="2" d="M19 9l-7 7-7-7"/>
                    </svg>
                </button>
                <div x-show="openAhli && open" x-cloak class="ml-12 space-y-1 border-l-2 border-gold-100">
                    <a href="{{ route('admin.list.masjid') }}" class="block p-2 pl-4 text-xs text-gray-500 hover:text-gold-600 transition-colors">Institusi</a>
                    <a href="/admin/list-kariah" class="block p-2 pl-4 text-xs text-gray-500 hover:text-gold-600 transition-colors">Ahli Khairat</a>
                </div>
            </div>

            <div class="space-y-1">
                <button @click="openWallet = !openWallet" 
                        class="group flex items-center w-full p-3 rounded-2xl text-gray-600 hover:text-gold-700 hover:bg-gold-50/50 transition-all">
                    <div class="p-2 rounded-xl bg-gray-100 group-hover:bg-gold-100 group-hover:text-gold-600">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <span x-show="open" class="ml-4 flex-1 text-left text-sm font-semibold">Kewangan</span>
                    <svg x-show="open" :class="openWallet && 'rotate-180'" class="h-4 w-4 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-width="2" d="M19 9l-7 7-7-7"/>
                    </svg>
                </button>
                <div x-show="openWallet && open" x-cloak class="ml-12 space-y-1 border-l-2 border-gold-100">
                    <a href="/adminwallet" class="block p-2 pl-4 text-xs text-gray-500 hover:text-gold-600 transition-colors">Transaksi</a>
                    <a href="#" class="block p-2 pl-4 text-xs text-gray-500 hover:text-gold-600 transition-colors">Laporan</a>
                </div>
            </div>
        </div>
    </nav>


  </aside>

  <script src="//unpkg.com/alpinejs" defer></script>
</body>
</html>