<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pendaftaran - DanaKhairat</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');
        
        body {
            font-family: 'Inter', sans-serif;
        }
        
        .option-card {
            transition: all 0.3s ease;
            border: 2px solid transparent;
        }
        
        .option-card:hover {
            transform: translateY(-5px);
            border-color: #f59e0b;
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }
        
        .btn-primary {
            background: linear-gradient(135deg, #f59e0b, #eab308);
            transition: all 0.3s ease;
        }
        
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px -5px rgba(245, 158, 11, 0.4);
        }
        
        .floating-shapes {
            position: absolute;
            width: 100%;
            height: 100%;
            overflow: hidden;
            z-index: -1;
        }
        
        .shape {
            position: absolute;
            opacity: 0.1;
        }
        
        .shape-1 {
            top: 10%;
            left: 5%;
            width: 100px;
            height: 100px;
            background: #f59e0b;
            border-radius: 30% 70% 70% 30% / 30% 30% 70% 70%;
        }
        
        .shape-2 {
            bottom: 20%;
            right: 10%;
            width: 150px;
            height: 150px;
            background: #eab308;
            border-radius: 50%;
        }
    </style>
</head>
<body class="bg-gradient-to-br from-gray-50 to-gray-100 min-h-screen">
    <!-- Floating Background Shapes -->
    <div class="floating-shapes">
        <div class="shape shape-1"></div>
        <div class="shape shape-2"></div>
    </div>

    <!-- Header -->
    <header class="bg-white/80 backdrop-blur-sm border-b border-gray-200 sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center py-4">
                <!-- Logo -->
                <a href="/" class="flex items-center space-x-3">
                    <img src="{{ asset('images/logos.png') }}" alt="Logo Masjid" class="h-10 w-auto">
                </a>
                
                <!-- Navigation -->
                <nav class="hidden md:flex space-x-8">
                    <a href="/" class="text-gray-600 hover:text-yellow-600 transition duration-300 font-medium">Laman Utama</a>
                </nav>
                
                <!-- Login Button -->
                <a href="/login" class="bg-gray-100 hover:bg-yellow-100 text-gray-700 hover:text-yellow-700 px-6 py-2 rounded-full font-medium transition duration-300">
                    <i class="fas fa-sign-in-alt mr-2"></i>Log Masuk
                </a>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <!-- Hero Section -->
        <div class="text-center mb-16">
            <h1 class="text-4xl md:text-5xl font-bold text-gray-900 mb-4">
                Sertai <span class="text-yellow-600">DanaKhairat</span>
            </h1>
            <p class="text-xl text-gray-600 max-w-2xl mx-auto">
                Pilih jenis pendaftaran mengikut keperluan anda. Sama ada sebagai institusi atau ahli khairat.
            </p>
        </div>

        <!-- Options Grid -->
        <div class="grid md:grid-cols-2 gap-8 max-w-4xl mx-auto">
            <!-- Option 1 -->
            <div class="option-card bg-white rounded-2xl p-8 shadow-lg flex flex-col justify-between h-full">
                <div>
                    <div class="text-center mb-6">
                        <div class="w-20 h-20 bg-yellow-100 rounded-2xl flex items-center justify-center mx-auto mb-4 border border-yellow-200">
                            <i class="fas fa-mosque text-yellow-600 text-2xl"></i>
                        </div>
                        <h3 class="text-2xl font-bold text-gray-900 mb-2">Institusi Masjid/Surau</h3>
                        <p class="text-gray-600">Daftar institusi anda untuk menggunakan sistem khairat digital</p>
                    </div>

                    <div class="space-y-4 mb-8">
                        <div class="flex items-start space-x-3">
                            <div class="w-6 h-6 bg-green-100 rounded-full flex items-center justify-center mt-1 flex-shrink-0">
                                <i class="fas fa-check text-green-600 text-xs"></i>
                            </div>
                            <span class="text-gray-700">Akses penuh sistem pengurusan khairat</span>
                        </div>
                        <div class="flex items-start space-x-3">
                            <div class="w-6 h-6 bg-green-100 rounded-full flex items-center justify-center mt-1 flex-shrink-0">
                                <i class="fas fa-check text-green-600 text-xs"></i>
                            </div>
                            <span class="text-gray-700">Urus ahli khairat secara digital</span>
                        </div>
                        <div class="flex items-start space-x-3">
                            <div class="w-6 h-6 bg-green-100 rounded-full flex items-center justify-center mt-1 flex-shrink-0">
                                <i class="fas fa-check text-green-600 text-xs"></i>
                            </div>
                            <span class="text-gray-700">Laporan automatik dan analitik</span>
                        </div>
                        <div class="flex items-start space-x-3">
                            <div class="w-6 h-6 bg-green-100 rounded-full flex items-center justify-center mt-1 flex-shrink-0">
                                <i class="fas fa-check text-green-600 text-xs"></i>
                            </div>
                            <span class="text-gray-700">Sistem pembayaran bersepadu</span>
                        </div>
                    </div>
                </div>

                <div>
                    <a href="/ajk/register" class="btn-primary text-white font-bold py-4 px-8 rounded-xl w-full block text-center">
                        Daftar Institusi
                        <i class="fas fa-arrow-right ml-2"></i>
                    </a>
                    <p class="text-center text-gray-500 text-sm mt-4">
                        Untuk masjid, surau, atau institusi agama
                    </p>
                </div>
            </div>

            <!-- Option 2 -->
            <div class="option-card bg-white rounded-2xl p-8 shadow-lg flex flex-col justify-between h-full">
                <div>
                    <div class="text-center mb-6">
                        <div class="w-20 h-20 bg-blue-100 rounded-2xl flex items-center justify-center mx-auto mb-4 border border-blue-200">
                            <i class="fas fa-users text-blue-600 text-2xl"></i>
                        </div>
                        <h3 class="text-2xl font-bold text-gray-900 mb-2">Ahli Khairat</h3>
                        <p class="text-gray-600">Daftar sebagai ahli khairat masjid/surau anda</p>
                    </div>

                    <div class="space-y-4 mb-8">
                        <div class="flex items-start space-x-3">
                            <div class="w-6 h-6 bg-green-100 rounded-full flex items-center justify-center mt-1 flex-shrink-0">
                                <i class="fas fa-check text-green-600 text-xs"></i>
                            </div>
                            <span class="text-gray-700">Daftar keahlian khairat digital</span>
                        </div>
                        <div class="flex items-start space-x-3">
                            <div class="w-6 h-6 bg-green-100 rounded-full flex items-center justify-center mt-1 flex-shrink-0">
                                <i class="fas fa-check text-green-600 text-xs"></i>
                            </div>
                            <span class="text-gray-700">Urus pembayaran yuran secara online</span>
                        </div>
                        <div class="flex items-start space-x-3">
                            <div class="w-6 h-6 bg-green-100 rounded-full flex items-center justify-center mt-1 flex-shrink-0">
                                <i class="fas fa-check text-green-600 text-xs"></i>
                            </div>
                            <span class="text-gray-700">Akses maklumat keahlian terkini</span>
                        </div>
                        <div class="flex items-start space-x-3">
                            <div class="w-6 h-6 bg-green-100 rounded-full flex items-center justify-center mt-1 flex-shrink-0">
                                <i class="fas fa-check text-green-600 text-xs"></i>
                            </div>
                            <span class="text-gray-700">Notifikasi automatik berkaitan khairat</span>
                        </div>
                    </div>
                </div>

                <div>
                    <a href="/user/register" class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-4 px-8 rounded-xl w-full block text-center transition duration-300">
                        Daftar sebagai Ahli
                        <i class="fas fa-user-plus ml-2"></i>
                    </a>
                    <p class="text-center text-gray-500 text-sm mt-4">
                        Untuk individu yang ingin menjadi ahli khairat
                    </p>
                </div>
            </div>
        </div>
    </main>


    <script>
        // Simple animation on load
        document.addEventListener('DOMContentLoaded', function() {
            const cards = document.querySelectorAll('.option-card');
            cards.forEach((card, index) => {
                card.style.opacity = '0';
                card.style.transform = 'translateY(20px)';
                
                setTimeout(() => {
                    card.style.transition = 'all 0.6s ease';
                    card.style.opacity = '1';
                    card.style.transform = 'translateY(0)';
                }, index * 200);
            });
        });
    </script>
</body>
</html>