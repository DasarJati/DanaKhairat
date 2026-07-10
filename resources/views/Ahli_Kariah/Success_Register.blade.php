<!DOCTYPE html>
<html lang="ms">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Permohonan Dihantar - Djariah eKhairat</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap');

        body {
            font-family: -apple-system, BlinkMacSystemFont, "SF Pro Display", "SF Pro Text", "Inter", "Segoe UI", sans-serif;
            -webkit-font-smoothing: antialiased;
        }

        .success-check {
            animation: checkmark 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards;
        }

        @keyframes checkmark {
            0% {
                transform: scale(0.6);
                opacity: 0;
            }
            100% {
                transform: scale(1);
                opacity: 1;
            }
        }

        .fade-up {
            animation: fadeUp 0.8s cubic-bezier(0.16, 1, 0.3, 1) forwards;
            opacity: 0;
            transform: translateY(16px);
        }

        @keyframes fadeUp {
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .delay-1 { animation-delay: 0.1s; }
        .delay-2 { animation-delay: 0.2s; }
        .delay-3 { animation-delay: 0.3s; }
    </style>
</head>

<body class="bg-[#f5f5f7] min-h-screen antialiased flex items-center justify-center">

    <!-- Main Container -->
    <div class="max-w-[560px] w-full mx-auto px-6 py-12">

        <!-- Success Card -->
        <div class="bg-white rounded-2xl border border-[#e5e5ea] overflow-hidden fade-up delay-1">
            
            <!-- Success Top Section (Apple Crisp White Title Stack) -->
            <div class="px-8 pt-12 pb-6 text-center">
                <div class="flex justify-center mb-5">
                    <!-- Clean, Non-gradient Icon Container -->
                    <div class="w-16 h-16 bg-[#f5f5f7] rounded-full flex items-center justify-center success-check">
                        <i class="fas fa-paper-plane text-2xl text-[#0066cc]"></i>
                    </div>
                </div>
                <h2 class="text-3xl font-semibold tracking-tight text-[#1d1d1f] mb-2">Permohonan Dihantar</h2>
                <p class="text-[#86868b] text-[15px]">Terima kasih atas permohonan anda.</p>
            </div>

            <!-- Content -->
            <div class="px-8 pb-10">
                
                <!-- Main Status Box -->
                <div class="mb-8 fade-up delay-2">
                    <div class="bg-[#f5f5f7] rounded-xl p-6 border border-[#e5e5ea] text-center">
                        <p class="text-[15px] text-[#424245] leading-relaxed mb-4">
                            Permohonan anda akan diproses dan mengambil masa untuk pengesahan.
                        </p>
                        
                        <div class="bg-white rounded-lg p-4 border border-[#e5e5ea] inline-flex flex-col items-center w-full">
                            <div class="flex items-center gap-2.5 text-sm text-[#424245]">
                                <i class="fas fa-envelope text-[#0066cc]"></i>
                                <span>Maklumat lanjut telah dihantar ke e-mel anda.</span>
                            </div>
                            <p class="text-xs text-[#86868b] mt-2 flex items-center gap-1.5">
                                <i class="fas fa-info-circle text-[#86868b]"></i>
                                Sila periksa folder Spam atau Junk sekiranya tiada di peti masuk.
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Contact Information (Refined Clean List) -->
                {{-- <div class="bg-white border border-[#e5e5ea] p-5 rounded-xl mb-8 fade-up delay-3">
                    <h4 class="font-semibold text-[#1d1d1f] text-[15px] mb-3">Hubungi Pihak Pengurusan</h4>
                    
                    <div class="space-y-2.5 text-sm text-[#424245]">
                        <div class="flex items-center justify-between py-1.5 border-b border-[#f2f2f7]">
                            <span class="text-[#86868b]"><i class="fas fa-phone w-5"></i> Telefon</span>
                            <span class="font-medium text-[#1d1d1f]">03-1234 5678</span>
                        </div>
                        <div class="flex items-center justify-between py-1.5 border-b border-[#f2f2f7]">
                            <span class="text-[#86868b]"><i class="fas fa-envelope w-5"></i> E-mel</span>
                            <a href="mailto:support@djariah.com" class="text-[#0066cc] hover:underline">support@djariah.com</a>
                        </div>
                        <div class="flex items-center justify-between py-1.5">
                            <span class="text-[#86868b]"><i class="fas fa-clock w-5"></i> Waktu Operasi</span>
                            <span class="text-[#1d1d1f]">Isnin - Jumaat (9:00 AM - 5:00 PM)</span>
                        </div>
                    </div>
                </div> --}}

                <!-- Action Buttons (Flat Apple Buttons) -->
                <div class="flex flex-col sm:flex-row gap-3 justify-center fade-up delay-3">
                    <a href="{{ route('public') }}"
                        class="px-6 py-2.5 bg-[#f5f5f7] hover:bg-[#e8e8ed] text-[#1d1d1f] text-[15px] font-medium rounded-lg transition-colors duration-200 flex items-center justify-center gap-2 w-full sm:w-auto">
                        Laman Utama
                    </a>
                    <a href="{{ route('login') }}"
                        class="px-6 py-2.5 bg-[#0066cc] hover:bg-[#0077ed] text-white text-[15px] font-medium rounded-lg transition-colors duration-200 flex items-center justify-center gap-2 w-full sm:w-auto">
                        Log Masuk
                    </a>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <div class="text-center mt-8 text-xs text-[#86868b] space-y-1.5 fade-up delay-3">
            <p>© {{ date('Y') }} Djariah eKhairat. Hak Cipta Terpelihara.</p>
            <p class="text-[#a1a1a6] flex items-center justify-center gap-1.5">
                Sistem Pengurusan Kariah Masjid
            </p>
        </div>
    </div>

</body>

</html>