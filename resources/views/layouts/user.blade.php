<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-theme="cartoon">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'e-Khairat')</title>

    <!-- Tailwind CSS CDN -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <!-- Alpine.js -->
    <script src="//unpkg.com/alpinejs" defer></script>

    <!-- Font Awesome CDN -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

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

    <style>
        /* Smooth transitions */
        .sidebar-transition {
            transition: transform 0.3s ease-in-out;
        }
        
        /* Hide scrollbar but keep functionality */
        .no-scrollbar::-webkit-scrollbar {
            display: none;
        }
        .no-scrollbar {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }
    </style>

    @stack('styles')
</head>

<body class="bg-gray-100 min-h-screen overflow-hidden" 
      x-data="{ 
        sidebarOpen: window.innerWidth >= 1024, // Desktop: true, Mobile: false
        open: true,
        openDropdown: false,
        openCommittee: false, 
        openPayment: false, 
        openKhairat: false, 
        openEvents: false, 
        openHistory: false, 
        openProfile: false 
      }"
      x-init="$watch('sidebarOpen', val => {
          if (window.innerWidth < 1024) {
              // Prevent body scroll when sidebar is open on mobile
              document.body.style.overflow = val ? 'hidden' : 'auto';
          }
      })"
      @resize.window="sidebarOpen = window.innerWidth >= 1024 ? true : false">

    <div class="flex h-screen overflow-hidden">
        <!-- Mobile overlay -->
        <div x-show="sidebarOpen && window.innerWidth < 1024" 
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             @click="sidebarOpen = false"
             class="fixed inset-0 bg-black/60 backdrop-blur-sm z-40 lg:hidden">
        </div>

        <!-- Sidebar -->
        <div x-show="sidebarOpen" 
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 -translate-x-full"
             x-transition:enter-end="opacity-100 translate-x-0"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 translate-x-0"
             x-transition:leave-end="opacity-0 -translate-x-full"
             class="fixed lg:relative z-50 h-full">
            @include('partials.usersidebar')
        </div>

        <!-- Main Content Area -->
        <div class="flex-1 flex flex-col w-full min-w-0">
            <!-- Navbar -->
            @include('partials.usernavbar')

            <!-- Main Content with scrolling -->
            <main class="flex-1 overflow-y-auto overflow-x-hidden p-4 md:p-6 no-scrollbar" id="main-content">
                @yield('content')
            </main>
        </div>
    </div>

    @stack('scripts')
</body>
</html>


{{-- <!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'e-Khairat')</title>

    <!-- Tailwind CSS CDN for quick test -->
    

    Font Awesome CDN
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    @stack('styles')
</head>
<body class="bg-gray-100">

<div class="flex h-screen">
    <!-- Sidebar -->
    @include('partials.usersidebar')

    <!-- Right section (Navbar + Content) -->
    <div class="flex-1 flex flex-col">
        <!-- Navbar -->
        @include('partials.usernavbar')

        <!-- Main Content -->
        <main class="flex-1 p-6 overflow-y-auto">
            @yield('content')
        </main>
    </div>
</div>

@yield('scripts')

</body>
</html> --}}
