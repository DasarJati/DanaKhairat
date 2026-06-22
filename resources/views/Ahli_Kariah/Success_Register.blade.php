<!DOCTYPE html>
<html lang="ms">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Permohonan Dihantar - Djariah eKhairat</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap');

        body {
            font-family: 'Inter', sans-serif;
        }

        .success-check {
            animation: checkmark 0.5s ease-in-out forwards;
        }

        @keyframes checkmark {
            0% {
                transform: scale(0);
                opacity: 0;
            }
            50% {
                transform: scale(1.2);
            }
            100% {
                transform: scale(1);
                opacity: 1;
            }
        }

        .fade-up {
            animation: fadeUp 0.6s ease-out forwards;
            opacity: 0;
            transform: translateY(20px);
        }

        @keyframes fadeUp {
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .delay-1 {
            animation-delay: 0.1s;
        }

        .delay-2 {
            animation-delay: 0.2s;
        }

        .delay-3 {
            animation-delay: 0.3s;
        }
    </style>
</head>

<body class="bg-gradient-to-br from-orange-50 via-white to-amber-50 min-h-screen">

    <!-- Main Container -->
    <div class="max-w-2xl mx-auto px-4 py-12">

        <!-- Success Card -->
        <div class="bg-white rounded-3xl shadow-2xl overflow-hidden border border-orange-100 fade-up delay-1">
            <!-- Success Banner -->
            <div class="bg-gradient-to-r from-blue-500 to-blue-600 px-6 py-12 text-white text-center relative overflow-hidden">
                <div class="absolute inset-0 bg-white opacity-10">
                    <i class="fas fa-envelope absolute text-8xl -right-4 -top-4 opacity-20"></i>
                </div>
                <div class="relative z-10">
                    <div class="flex justify-center mb-4">
                        <div class="w-24 h-24 bg-white bg-opacity-20 rounded-full flex items-center justify-center backdrop-blur-sm success-check">
                            <i class="fas fa-paper-plane text-5xl text-white"></i>
                        </div>
                    </div>
                    <h2 class="text-3xl md:text-4xl font-extrabold mb-4">Permohonan Dihantar!</h2>
                    <p class="text-blue-100 text-lg">Terima kasih atas permohonan anda</p>
                </div>
            </div>

            <!-- Content -->
            <div class="p-8">
                <!-- Message -->
                <div class="text-center mb-8 fade-up delay-2">
                    <div class="bg-blue-50 rounded-2xl p-6 border border-blue-100">
                        <i class="fas fa-clock text-blue-500 text-4xl mb-3"></i>
                        <h3 class="text-xl font-bold text-gray-800 mb-4">Sila Semak E-mel Anda</h3>
                        <p class="text-gray-600 mb-4">
                            Permohonan anda akan diproses dan mengambil masa <strong class="text-blue-600">1 - 3 hari</strong> untuk pengesahan.
                        </p>
                        <div class="bg-white rounded-xl p-4 mt-4">
                            <div class="flex items-center justify-center gap-3 text-sm">
                                <i class="fas fa-envelope text-blue-500 text-lg"></i>
                                <span class="text-gray-700">Sila semak e-mel anda untuk maklumat lanjut</span>
                            </div>
                            <p class="text-xs text-gray-500 mt-3">
                                <i class="fas fa-info-circle"></i>
                                Jangan lupa periksa folder Spam/Junk Mail
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Contact Information -->
                <div class="bg-amber-50 border-l-4 border-amber-500 p-4 rounded-r-xl mb-8 fade-up delay-3">
                    <div class="flex items-start gap-3">
                        <i class="fas fa-phone-alt text-amber-500 text-xl"></i>
                        <div>
                            <p class="font-semibold text-amber-800 mb-1">Ada Sebarang Masalah?</p>
                            <p class="text-sm text-amber-700">
                                Sila hubungi pihak kami di:
                            </p>
                            <div class="mt-2 space-y-1">
                                <p class="text-sm text-amber-700">
                                    <i class="fas fa-phone mr-2"></i> Telefon: 03-1234 5678
                                </p>
                                <p class="text-sm text-amber-700">
                                    <i class="fas fa-envelope mr-2"></i> E-mel: support@djarah.com
                                </p>
                                <p class="text-sm text-amber-700">
                                    <i class="fas fa-clock mr-2"></i> Waktu Pejabat: Isnin - Jumaat (9am - 5pm)
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex flex-col sm:flex-row gap-4 justify-center fade-up delay-3">
                    <a href="{{ route('public') }}"
                        class="px-8 py-3 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-xl font-medium transition-all duration-300 flex items-center justify-center gap-2">
                        <i class="fas fa-home"></i>
                        Laman Utama
                    </a>
                    <a href="{{ route('login') }}"
                        class="px-8 py-3 bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white rounded-xl font-medium transition-all duration-300 shadow-lg hover:shadow-xl flex items-center justify-center gap-2">
                        <i class="fas fa-sign-in-alt"></i>
                        Log Masuk
                    </a>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <div class="text-center mt-8 text-sm text-gray-500 fade-up delay-3">
            <p>© {{ date('Y') }} Djariah eKhairat. Hak Cipta Terpelihara.</p>
            <p class="mt-1">
                <i class="fas fa-mosque text-orange-400"></i>
                Sistem Pengurusan Kariah Masjid
            </p>
        </div>
    </div>

</body>

</html>