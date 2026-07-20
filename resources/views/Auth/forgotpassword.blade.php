<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <title>Lupa Kata Laluan</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Tailwind CDN -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Optional Tailwind Config -->
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#2563eb',
                    }
                }
            }
        }
    </script>
</head>

<body class="min-h-screen flex items-center justify-center bg-gradient-to-br from-gray-50 to-gray-100 px-4">

    <div class="bg-white p-8 rounded-2xl shadow-lg w-full max-w-md border border-gray-100">

        <!-- Icon -->
        <div class="flex justify-center mb-6">
            <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center">
                <svg class="w-6 h-6 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z" />
                </svg>
            </div>
        </div>

        <!-- Header -->
        <div class="text-center mb-8">
            <h1 class="text-2xl font-bold text-gray-900 mb-2">
                Lupa Kata Laluan
            </h1>
            <p class="text-gray-600 text-sm leading-relaxed">
                Masukkan e-mel berdaftar anda. Sistem akan menetapkan semula kata laluan dan menghantar kata laluan baharu kepada anda.
            </p>
        </div>

        <!-- Error -->
        @if ($errors->any())
            <div class="mb-6 p-4 bg-red-50 border border-red-100 rounded-xl">
                <div class="flex items-start">
                    <svg class="w-5 h-5 text-red-500 mt-0.5 mr-3 flex-shrink-0"
                         fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <p class="text-sm text-red-700">{{ $errors->first() }}</p>
                </div>
            </div>
        @endif

        <!-- Success -->
        @if (session('success'))
            <div class="mb-6 p-4 bg-green-50 border border-green-100 rounded-xl">
                <div class="flex items-start">
                    <svg class="w-5 h-5 text-green-500 mt-0.5 mr-3 flex-shrink-0"
                         fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <p class="text-sm text-green-700">{{ session('success') }}</p>
                </div>
            </div>
        @endif

        <!-- Form -->
        <form method="POST" action="/forgot-password" class="space-y-6">
            @csrf

            <div>
                <label class="block text-sm font-medium text-gray-800 mb-2">
                    Alamat Email
                </label>

                <div class="relative">
                    <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                        </svg>
                    </span>

                    <input
                        type="email"
                        name="email"
                        value="{{ old('email') }}"
                        required
                        placeholder="contoh: ajk@masjid.com"
                        class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-xl
                               focus:ring-2 focus:ring-primary focus:border-primary
                               focus:outline-none transition"
                    >
                </div>

                <p class="mt-2 text-xs text-gray-500">
                    Pastikan email yang dimasukkan adalah aktif
                </p>
            </div>

            <button
                type="submit"
                class="w-full bg-gradient-to-r from-primary to-blue-700
                       hover:from-blue-700 hover:to-blue-800
                       text-white font-semibold py-3.5 rounded-xl
                       transition focus:ring-2 focus:ring-primary focus:ring-offset-2
                       shadow-md hover:shadow-lg active:scale-[0.99]"
            >
                Hantar Kata Laluan Baharu
            </button>
        </form>

        <!-- Footer -->
        <div class="mt-8 pt-6 border-t border-gray-100 text-center">
            <p class="text-sm text-gray-600">
                Ingat kata laluan anda?
                <a href="/login"
                   class="font-semibold text-primary hover:text-blue-800 ml-1 transition">
                    Log Masuk
                </a>
            </p>
        </div>

    </div>

</body>
</html>
