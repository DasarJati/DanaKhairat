<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Portal DanaKhairat - Login</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        /* Specific pattern similar to the background image */
        .bg-pattern {
            background-color: #f8faff;
            background-image: url("{{ asset('images/bg_login.png') }}");
            background-size: cover;
            background-position: center;
        }

        .login-card {
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.05), 0 8px 10px -6px rgba(0, 0, 0, 0.05);
        }
    </style>
</head>

<body id="mainBody" class="bg-pattern min-h-screen flex flex-col font-sans">

    <div class="flex justify-center items-center px-6 lg:px-20 py-10">

        <div class="flex justify-center lg:justify-end items-center w-full mt-10">
            <div class="bg-white rounded-[2rem] p-8 lg:p-12 w-full max-w-lg login-card border border-white">

                <div class="text-center mb-8">
                    <div
                        class="w-14 h-14 bg-blue-50 text-blue-600 rounded-2xl flex items-center justify-center mx-auto mb-4 border border-blue-100 shadow-sm">
                        <i class="fa-solid fa-lock text-xl"></i>
                    </div>
                    <h2 class="text-3xl font-bold text-slate-800">Selamat Datang</h2>
                    <p class="text-slate-500 mt-2">Log masuk ke Portal DanaKhairat</p>
                </div>

                <form method="POST" action="{{ route('login') }}" class="space-y-6">
                    @csrf

                    <div class="flex bg-slate-100 p-1.5 rounded-2xl gap-1">
                        <label class="flex-1 cursor-pointer">
                            <input type="radio" name="login_type" value="admin" class="peer hidden loginType"
                                checked />
                            <div
                                class="py-3 text-center rounded-xl transition-all duration-300 font-semibold text-slate-500 peer-checked:bg-white peer-checked:text-blue-700 peer-checked:shadow-sm">
                                <i class="fa-solid fa-user-shield mr-2"></i>Admin
                            </div>
                        </label>
                        <label class="flex-1 cursor-pointer">
                            <input type="radio" name="login_type" value="ahli" class="peer hidden loginType" />
                            <div
                                class="py-3 text-center rounded-xl transition-all duration-300 font-semibold text-slate-500 peer-checked:bg-white peer-checked:text-emerald-700 peer-checked:shadow-sm">
                                <i class="fa-solid fa-users mr-2"></i>Ahli
                            </div>
                        </label>
                    </div>

                    <div>
                        <label class="text-sm font-bold text-slate-700 mb-2 block">E-mel atau ID Pengguna</label>
                        <div class="relative group">
                            <i
                                class="fa-regular fa-envelope absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 group-focus-within:text-blue-600 transition-colors"></i>
                            <input type="email" name="email" value="{{ old('email') }}" required
                                class="w-full h-14 rounded-xl border border-slate-200 bg-slate-50/50 pl-12 pr-4 outline-none focus:ring-2 focus:ring-blue-600/10 focus:border-blue-600 transition-all placeholder:text-slate-400"
                                placeholder="Masukkan e-mel atau ID pengguna" />
                        </div>
                        @error('email')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="text-sm font-bold text-slate-700 mb-2 block">Kata Laluan</label>
                        <div class="relative group">
                            <i
                                class="fa-solid fa-lock absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 group-focus-within:text-blue-600 transition-colors"></i>
                            <input type="password" name="password" id="password" required
                                class="w-full h-14 rounded-xl border border-slate-200 bg-slate-50/50 pl-12 pr-12 outline-none focus:ring-2 focus:ring-blue-600/10 focus:border-blue-600 transition-all placeholder:text-slate-400"
                                placeholder="Masukkan kata laluan" />
                            <button type="button" onclick="togglePassword()"
                                class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600">
                                <i class="fa-regular fa-eye" id="eyeIcon"></i>
                            </button>
                        </div>
                        @error('password')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex items-center justify-between px-1">
                        <label class="flex items-center gap-2 text-sm text-slate-600 cursor-pointer">
                            <input type="checkbox" name="remember"
                                class="rounded border-slate-300 text-blue-600 focus:ring-blue-600" />
                            Ingat saya
                        </label>
                        <a href="/forgot-password" class="text-sm font-semibold text-blue-600 hover:text-blue-800">Lupa
                            kata laluan?</a>
                    </div>

                    <button type="submit" id="submitBtn"
                        class="w-full h-14 rounded-xl bg-blue-800 hover:bg-blue-900 text-white font-bold text-lg transition-all shadow-lg shadow-blue-900/20 active:scale-[0.98]">
                        <span id="btnText">Log Masuk</span>
                    </button>

                    <div class="text-center pt-4 border-t border-slate-100">
                        <p class="text-gray-500 text-sm">
                           Tiada akaun?
                            <a href="/pilihan" class="font-semibold text-black hover:underline">
                                Daftar Di Sini
                            </a>
                        </p>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function togglePassword() {
            const password = document.getElementById("password");
            const eyeIcon = document.getElementById("eyeIcon");
            if (password.type === "password") {
                password.type = "text";
                eyeIcon.classList.replace("fa-eye", "fa-eye-slash");
            } else {
                password.type = "password";
                eyeIcon.classList.replace("fa-eye-slash", "fa-eye");
            }
        }

        const loginTypes = document.querySelectorAll(".loginType");
        loginTypes.forEach((radio) => {
            radio.addEventListener("change", function() {
                // Optional: change background based on user type
            });
        });
    </script>

    <!-- SweetAlert for session messages -->
    @if (session('error'))
    <script>
        Swal.fire({
            icon: 'error',
            title: 'Log Masuk Gagal',
            text: '{{ session('error') }}',
            confirmButtonColor: '#1e3a8a',
            confirmButtonText: 'Cuba Lagi'
        });
    </script>
    @endif

    @if (session('success'))
    <script>
        Swal.fire({
            icon: 'success',
            title: 'Berjaya!',
            text: '{{ session('success') }}',
            timer: 3000,
            showConfirmButton: false
        });
    </script>
    @endif

    @if ($errors->any())
    <script>
        let errorMessages = '';
        @foreach ($errors->all() as $error)
            errorMessages += '• {{ $error }}\n';
        @endforeach
        Swal.fire({
            icon: 'error',
            title: 'Ralat Pengesahan',
            text: errorMessages,
            confirmButtonColor: '#1e3a8a',
            confirmButtonText: 'OK'
        });
    </script>
    @endif

</body>

</html>