<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin')</title>

    <!-- Tailwind CSS CDN for quick test -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    {{-- Font Awesome CDN --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    @stack('styles')
</head>
<body class="bg-gradient-to-br from-gray-100 to-gray-200">


<div class="flex h-screen">
    <!-- Sidebar -->
    @include('admin.sidebar')

    <!-- Right section (Navbar + Content) -->
    <div class="flex-1 flex flex-col">

        <!-- Main Content -->
        <main class="flex-1 p-6 overflow-y-auto">
            @yield('content')
        </main>
    </div>
</div>

@yield('scripts')

</body>
</html>
