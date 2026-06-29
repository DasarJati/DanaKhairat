<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Semak Status Keahlian AJK</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>

<body class="bg-gray-50 min-h-screen">
    <!-- Header Section -->
    <div class="bg-cover bg-center bg-no-repeat shadow-sm relative min-h-[60vh] flex items-center">
        <video autoplay loop muted playsinline class="absolute inset-0 w-full h-full object-cover">
            <source src="{{ asset('videos/ekhairat.mp4') }}" type="video/mp4">
        </video>

        <!-- Overlay -->
        <div class="absolute inset-0 bg-black/40"></div>
        
        <!-- Back Button -->
        <a href="/" 
        class="absolute top-4 left-4 inline-flex items-center space-x-2 
        px-3 py-2 bg-white/90 rounded-lg text-gray-700 
        hover:bg-white transition-all duration-200 z-10 text-sm">
            <i class="fas fa-arrow-left"></i>
            <span class="font-medium">Kembali ke Laman Utama</span>
        </a>

        <div class="relative w-full px-4">
            <div class="text-center mb-8">
                <h1 class="text-2xl font-bold text-white mb-2">Semak Status Keahlian</h1>
                <p class="text-green-100 text-sm">Sila masukkan maklumat untuk semak status pendaftaran</p>
            </div>

            <!-- Search Form -->
            <div class="bg-white rounded-lg shadow-md p-4 max-w-md mx-auto">
                <form method="POST" action="{{ route('status.check') }}" class="space-y-3">
                    @csrf
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-search text-gray-400 text-sm"></i>
                        </div>
                        <input type="text" name="keyword" value="{{ old('keyword') }}"
                            placeholder="Masukkan email"
                            class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg
                            focus:ring-1 focus:ring-green-500 focus:border-green-500 transition-all duration-200 text-sm">
                    </div>

                    <button type="submit"
                        class="w-full bg-green-600 hover:bg-green-700
                        text-white py-2 rounded-lg font-medium transition-all duration-200 text-sm">
                        Semak Status
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Results Section -->
    <div class="max-w-md mx-auto px-4 py-4">
        <!-- ❌ NOT FOUND MESSAGE -->
        @if(session('not_found'))
            <div class="bg-white rounded-lg shadow-sm border-l-3 border-red-500 p-4 mb-4">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 bg-red-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-exclamation-triangle text-red-500 text-sm"></i>
                    </div>
                    <div class="flex-1">
                        <h3 class="font-semibold text-red-800 text-sm mb-1">Rekod Tidak Dijumpai</h3>
                        <p class="text-red-600 text-xs">{{ session('not_found') }}</p>
                    </div>
                </div>
            </div>
        @endif

        <!-- ✅ RECORD FOUND -->
        @if(isset($ajk) && $ajk)
            <div class="space-y-4">
                <!-- Status Card -->
                <div class="bg-white rounded-lg shadow-sm border-l-3 p-4
                    @if($ajk->status == 1)
                        border-emerald-500
                    @elseif($ajk->status == 0)
                        border-amber-500
                    @else
                        border-red-500
                    @endif">
                    
                    <div class="flex items-center space-x-3">
                        @if($ajk->status == 1)
                            <div class="w-10 h-10 bg-emerald-100 rounded-lg flex items-center justify-center">
                                <i class="fas fa-check-circle text-emerald-500"></i>
                            </div>
                        @elseif($ajk->status == 0)
                            <div class="w-10 h-10 bg-amber-100 rounded-lg flex items-center justify-center">
                                <i class="fas fa-clock text-amber-500"></i>
                            </div>
                        @else
                            <div class="w-10 h-10 bg-red-100 rounded-lg flex items-center justify-center">
                                <i class="fas fa-times-circle text-red-500"></i>
                            </div>
                        @endif
                        
                        <div class="flex-1">
                            <h3 class="font-semibold text-gray-800 text-sm mb-1">
                                @if($ajk->status == 1)
                                    Pendaftaran Lulus
                                @elseif($ajk->status == 0)
                                    Sedang Diproses
                                @else
                                    Pendaftaran Gagal
                                @endif
                            </h3>
                            <p class="text-gray-600 text-xs leading-relaxed">
                                @if($ajk->status == 1)
                                    Tahniah! Pendaftaran anda telah diluluskan.
                                @elseif($ajk->status == 0)
                                    Permohonan anda sedang dalam proses.
                                @else
                                    Maaf, pendaftaran anda tidak berjaya.
                                @endif
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Action Card -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4">
                    <div class="text-center">
                        <h4 class="font-semibold text-gray-800 text-sm mb-3">Langkah Seterusnya</h4>
                        
                        @if($ajk->status == 1)
                            <div class="flex items-center justify-center space-x-2 text-emerald-600 mb-3">
                                <i class="fas fa-envelope text-xs"></i>
                                <span class="font-medium text-xs">Semak email untuk arahan seterusnya</span>
                            </div>
                        @elseif($ajk->status == 0)
                            <div class="flex items-center justify-center space-x-2 text-amber-600 mb-3">
                                <i class="fas fa-hourglass-half text-xs"></i>
                                <span class="font-medium text-xs">Proses mengambil masa 24 jam</span>
                            </div>
                        @else
                            <div class="flex items-center justify-center space-x-2 text-red-600 mb-3">
                                <i class="fas fa-phone text-xs"></i>
                                <span class="font-medium text-xs">Hubungi PIC untuk bantuan</span>
                            </div>
                        @endif
                        
                        <div class="mt-4 pt-4 border-t border-gray-200">
                            <a href="tel:0123456789"
                                class="inline-flex items-center space-x-2 px-4 py-2 bg-green-600 hover:bg-green-700 
                                text-white rounded-lg transition-all duration-200 text-xs font-medium">
                                <i class="fas fa-phone"></i>
                                <span>Hubungi: 012-3456789</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <!-- Empty State -->
        @if(!isset($ajk) && !session('not_found'))
            <div class="text-center py-8">
                <div class="w-12 h-12 bg-gray-100 rounded-lg flex items-center justify-center mx-auto mb-3">
                    <i class="fas fa-search text-gray-400"></i>
                </div>
                <h3 class="font-medium text-gray-600 text-sm mb-1">Masukkan maklumat untuk semak status</h3>
            </div>
        @endif
    </div>
</body>
</html>