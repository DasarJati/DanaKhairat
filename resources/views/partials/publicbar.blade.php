 {{-- Header Section --}}
    <header class="sticky-header bg-white/95 shadow-sm border-b border-gray-100">
        <nav class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center py-4">
                <!-- Logo -->
                <div class="flex items-center space-x-3">
                    <img src="{{ asset('images/logos.png') }}" alt="Logo Masjid" class="h-10 w-auto">
                    <div>
                        <h1 class="text-xl font-bold text-gray-900">DJariah</h1>
                        <p class="text-sm text-gray-600">eKhairat</p>
                    </div>
                </div>

                <!-- Desktop Navigation -->
                <div class="hidden md:flex items-center space-x-8">
                    <a href="#" class="text-gray-700 hover:text-yellow-600 font-medium transition duration-300">Utama</a>
                    <a href="#sahabat" class="text-gray-700 hover:text-yellow-600 font-medium transition duration-300">Sahabat</a>
                    <a href="#us" class="text-gray-700 hover:text-yellow-600 font-medium transition duration-300">Tentang Kami</a>
                    <a href="#us" class="text-gray-700 hover:text-yellow-600 font-medium transition duration-300">Kolaborasi</a>
                    <a href="#pakej" class="text-gray-700 hover:text-yellow-600 font-medium transition duration-300">Pakej</a>
                    <a href="#footer" class="text-gray-700 hover:text-yellow-600 font-medium transition duration-300">Hubungi</a>
                </div>
                <div class="md:hidden">
                    <button type="button" class="text-gray-700 hover:text-yellow-600 focus:outline-none">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                        </svg>
                    </button>
                </div>
                