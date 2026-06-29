<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <title>Reset Password</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Tailwind CDN -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Optional: Tailwind Config -->
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

<body class="min-h-screen bg-gray-100 flex items-center justify-center">

    <form method="POST" action="/reset-password" class="w-full max-w-md bg-white p-8 rounded-xl shadow-lg">
        @csrf

        <input type="hidden" name="email" value="{{ request('email') }}">
        <input type="hidden" name="type" value="{{ request('type') }}">

        <!-- Header -->
        <div class="text-center mb-10">
            <h2 class="text-3xl font-bold text-gray-800 mb-2">
                Reset Password
            </h2>
            <p class="text-gray-600">
                Create a new password for your account
            </p>
        </div>

        <!-- Password -->
        <div class="mb-6">
            <label class="block text-sm font-medium text-gray-700 mb-2">
                Password Baru
            </label>

            <input
                type="password"
                name="password"
                required
                placeholder="Masukkan password baru"
                class="w-full px-4 py-3 border border-gray-300 rounded-lg
                       focus:ring-2 focus:ring-primary focus:border-primary
                       outline-none transition"
            >

            <p class="mt-2 text-xs text-gray-500">
                Minimum 8 karakter
            </p>
        </div>

        <!-- Confirm Password -->
        <div class="mb-8">
            <label class="block text-sm font-medium text-gray-700 mb-2">
                Sahkan Password
            </label>

            <input
                type="password"
                name="password_confirmation"
                required
                placeholder="Ulangi password baru"
                class="w-full px-4 py-3 border border-gray-300 rounded-lg
                       focus:ring-2 focus:ring-primary focus:border-primary
                       outline-none transition"
            >

            <p class="mt-2 text-xs text-gray-500">
                Pastikan password sama
            </p>
        </div>

        <!-- Submit -->
        <button
            type="submit"
            class="w-full bg-primary hover:bg-blue-700 text-white
                   font-semibold py-3 rounded-lg transition
                   focus:ring-2 focus:ring-primary focus:ring-offset-2"
        >
            Reset Password
        </button>

        <!-- Footer -->
        <div class="mt-8 pt-6 border-t text-center">
            <p class="text-sm text-gray-600">
                Kembali ke
                <a href="/login" class="text-primary font-medium hover:underline">
                    halaman login
                </a>
            </p>
        </div>
    </form>

</body>
</html>
