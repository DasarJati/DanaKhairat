<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pelan Premium</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'premium': {
                            '50': '#fffbeb',
                            '100': '#fef3c7',
                            '500': '#f59e0b',
                            '600': '#d97706',
                            '700': '#b45309',
                        },
                        'mega': {
                            '50': '#fdf4ff',
                            '100': '#fae8ff',
                            '500': '#d946ef',
                            '600': '#c026d3',
                            '700': '#a21caf',
                        }
                    },
                    animation: {
                        'float': 'float 6s ease-in-out infinite',
                        'pulse-slow': 'pulse 3s ease-in-out infinite',
                        'slide-up': 'slideUp 0.5s ease-out',
                    },
                    keyframes: {
                        float: {
                            '0%, 100%': { transform: 'translateY(0)' },
                            '50%': { transform: 'translateY(-10px)' },
                        },
                        slideUp: {
                            '0%': { transform: 'translateY(20px)', opacity: '0' },
                            '100%': { transform: 'translateY(0)', opacity: '1' },
                        }
                    }
                }
            }
        }
    </script>
    <style>
        .card-hover {
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        }
        
        .feature-icon {
            transition: transform 0.3s ease;
        }
        
        .feature-item:hover .feature-icon {
            transform: scale(1.1) rotate(5deg);
        }
        
        .shine-effect::after {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: linear-gradient(
                to bottom right,
                rgba(255, 255, 255, 0) 0%,
                rgba(255, 255, 255, 0.1) 50%,
                rgba(255, 255, 255, 0) 100%
            );
            transform: rotate(30deg);
            transition: all 0.6s;
            opacity: 0;
        }
        
        .shine-effect:hover::after {
            opacity: 1;
            top: -30%;
            left: -30%;
        }
    </style>
</head>
<body class="bg-gradient-to-br from-gray-100 to-gray-100 min-h-screen">
    <section id="pakej" class="py-16 w-full">
        <div class="container mx-auto px-6 max-w-6xl">
            <!-- Section Header -->
            <div class="text-center mb-16 animate-slide-up">
                <h2 class="text-4xl md:text-5xl font-bold text-gray-900 mb-4">Pilih Pelan Premium</h2>
            </div>

            <!-- Pricing Cards -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 lg:gap-12">
                
                <!-- PREMIUM CARD -->
                <div class="card-hover bg-[#f9fafb] rounded-3xl border-2 border-premium-100 transition-all duration-500 hover:shadow-2xl relative overflow-hidden group shine-effect">

                    <!-- Decorative Elements -->
                    <div class="absolute -top-24 -right-24 w-48 h-48 bg-premium-100 rounded-full opacity-50 group-hover:opacity-70 transition-opacity duration-500"></div>
                    <div class="absolute -bottom-24 -left-24 w-48 h-48 bg-premium-50 rounded-full opacity-50 group-hover:opacity-70 transition-opacity duration-500"></div>
                    
                    <!-- Popular Badge -->
                    <div class="absolute -top-4 left-1/2 transform -translate-x-1/2 z-20">
                        
                    </div>
                    
                    <!-- Card Content -->
                    <div class="relative z-10 p-8">
                        <!-- Header -->
                        <div class="mb-8">
                            <div class="flex justify-between items-start mb-4">
                                <div>
                                    <h3 class="text-2xl font-bold text-gray-900">BASIC</h3>
                                    <div class="flex items-center mt-1">
                                        <i class="fas fa-star text-premium-500 text-sm mr-1"></i>
                                        <i class="fas fa-star text-premium-500 text-sm mr-1"></i>
                                        <i class="fas fa-star text-premium-500 text-sm mr-1"></i>
                                        <i class="fas fa-star text-premium-500 text-sm mr-1"></i>
                                        <i class="fas fa-star text-premium-500 text-sm"></i>
                                    </div>
                                </div>
                                <span class="inline-flex items-center bg-premium-100 text-premium-700 text-xs font-semibold px-3 py-1 rounded-full">
                                    <i class="fas fa-gift text-premium-500 mr-1"></i>
                                    2 BULAN PERCUMA
                                </span>
                            </div>
                            
                            <div class="mb-4">
                                <div class="flex items-baseline">
                                    <span class="text-4xl font-bold text-gray-900">RM 99</span>
                                    <span class="text-gray-500 ml-2">/ bulan</span>
                                </div>
                                <div class="mt-2 bg-premium-50 text-premium-700 text-sm font-medium px-4 py-2 rounded-lg inline-block border border-premium-200">
                                    <i class="fas fa-users mr-2"></i> RM10 / Ahli
                                </div>
                            </div>
                            
                            <p class="text-gray-600">Sesuai untuk institusi kecil hingga sederhana</p>
                        </div>

                        <!-- Features -->
                        <div class="mb-8">
                            <ul class="space-y-4">
                                <li class="feature-item flex items-start">
                                    <div class="bg-premium-100 rounded-lg p-2 mr-4 flex-shrink-0 feature-icon">
                                        <i class="fas fa-check text-premium-600"></i>
                                    </div>
                                    <span class="text-gray-700">200 - 500 ahli</span>
                                </li>
                                <li class="feature-item flex items-start">
                                    <div class="bg-premium-100 rounded-lg p-2 mr-4 flex-shrink-0 feature-icon">
                                        <i class="fas fa-check text-premium-600"></i>
                                    </div>
                                    <span class="text-gray-700">1 akaun Pengurus Sistem</span>
                                </li>
                               
                                <li class="feature-item flex items-start">
                                    <div class="bg-premium-100 rounded-lg p-2 mr-4 flex-shrink-0 feature-icon">
                                        <i class="fas fa-check text-premium-600"></i>
                                    </div>
                                    <span class="text-gray-700">Paparan Khas + Logo</span>
                                </li>
                               
                                <li class="feature-item flex items-start">
                                    <div class="bg-premium-100 rounded-lg p-2 mr-4 flex-shrink-0 feature-icon">
                                        <i class="fas fa-check text-premium-600"></i>
                                    </div>
                                    <span class="text-gray-700">Training percuma (online)</span>
                                </li>
                            </ul>
                        </div>

                        <!-- CTA Button -->
                        <button onclick="window.location.href='/ajk/register'" 
                                class="w-full bg-gradient-to-r from-premium-500 to-premium-600 hover:from-premium-600 hover:to-premium-700 text-black font-semibold py-4 px-6 rounded-xl transition-all duration-300 transform group-hover:-translate-y-1 shadow-lg hover:shadow-xl flex items-center justify-center">
                            <span>Sertai Sekarang</span>
                            <i class="fas fa-arrow-right ml-2 transition-transform group-hover:translate-x-1"></i>
                        </button>
                        
                        <!-- Footer Note -->
                        <div class="mt-6 text-center">
                            <p class="text-xs text-gray-500 flex items-center justify-center">
                                <i class="fas fa-info-circle mr-1"></i>
                                <span> Terma & Syarat dikenakan</span>
                            </p>
                        </div>
                    </div>
                </div>

                <!-- MEGA PREMIUM CARD -->
                <div class="card-hover relative text-white rounded-3xl border-2 border-purple-700 transition-all duration-500 hover:shadow-2xl group overflow-hidden shine-effect">
                    <!-- Background Gradient -->
                    <div class="absolute inset-0 bg-gradient-to-br from-purple-900 via-indigo-900 to-gray-900 z-0"></div>

                    <!-- Decorative Elements -->
                    <div class="absolute -top-24 -right-24 w-48 h-48 bg-purple-800 rounded-full opacity-30 z-0"></div>
                    <div class="absolute -bottom-24 -left-24 w-48 h-48 bg-indigo-800 rounded-full opacity-30 z-0"></div>
                    
                    <!-- Premium Badge -->
                    <div class="absolute -top-4 left-1/2 transform -translate-x-1/2 z-20">
                        
                    </div>

                    <!-- Card Content -->
                    <div class="relative z-10 p-8 rounded-2xl shadow-2xl border border-white/10"
     style="background: linear-gradient(to right, #1a1741, #3c358a, #2f2b55);">
  
  <!-- Header -->
  <div class="flex justify-between items-start mb-4">
    <div>
      <h3 class="text-2xl font-bold text-white tracking-wide">PREMIUM</h3>
      <div class="flex items-center mt-1">
        <i class="fas fa-star text-yellow-400 text-sm mr-1"></i>
        <i class="fas fa-star text-yellow-400 text-sm mr-1"></i>
        <i class="fas fa-star text-yellow-400 text-sm mr-1"></i>
        <i class="fas fa-star text-yellow-400 text-sm mr-1"></i>
        <i class="fas fa-star text-yellow-400 text-sm"></i>
      </div>
    </div>
    <span class="inline-flex items-center bg-yellow-500/10 text-yellow-300 text-xs font-semibold px-3 py-1 rounded-full border border-yellow-500/20">
      <i class="fas fa-gift text-yellow-400 mr-1"></i>
      2 BULAN PERCUMA
    </span>
  </div>

  <!-- Price -->
  <div class="mb-4">
    <div class="flex items-baseline">
      <span class="text-4xl font-bold text-white drop-shadow-lg">RM 149</span>
      <span class="text-gray-300 ml-2">/ bulan</span>
    </div>
    <div class="mt-2 bg-white/10 text-gray-100 text-sm font-medium px-4 py-2 rounded-lg inline-block border border-white/20 backdrop-blur-sm">
      <i class="fas fa-users mr-2 text-indigo-300"></i> RM 10 / ahli
    </div>
  </div>

  <p class="text-gray-200">Untuk institusi besar dengan keperluan lengkap</p>

  <!-- Features -->
  <div class="mb-8 mt-6">
    <ul class="space-y-4">
      <li class="feature-item flex items-start">
        <div class="bg-green-500/10 rounded-lg p-2 mr-4 flex-shrink-0 border border-green-400/20">
          <i class="fas fa-check text-green-400"></i>
        </div>
        <span class="text-gray-100">Tiada had Ahli</span>
      </li>
      <li class="feature-item flex items-start">
        <div class="bg-green-500/10 rounded-lg p-2 mr-4 flex-shrink-0 border border-green-400/20">
          <i class="fas fa-check text-green-400"></i>
        </div>
        <span class="text-gray-100">3 akaun Pengurus</span>
      </li>
      
      <li class="feature-item flex items-start">
        <div class="bg-green-500/10 rounded-lg p-2 mr-4 flex-shrink-0 border border-green-400/20">
          <i class="fas fa-check text-green-400"></i>
        </div>
        <span class="text-gray-100">Website khas identiti masjid</span>
      </li>
      
      <li class="feature-item flex items-start">
        <div class="bg-green-500/10 rounded-lg p-2 mr-4 flex-shrink-0 border border-green-400/20">
          <i class="fas fa-check text-green-400"></i>
        </div>
        <span class="text-gray-100">2 training setahun</span>
      </li>
    </ul>
  </div>

  <!-- CTA Button -->
  <button onclick="window.location.href='/ajk/register'" 
    class="w-full bg-gradient-to-r from-indigo-500 to-purple-600 hover:from-indigo-600 hover:to-purple-700 text-white font-semibold py-4 px-6 rounded-xl transition-all duration-300 transform hover:-translate-y-1 shadow-lg hover:shadow-purple-600/30 flex items-center justify-center">
    <span>Sertai Sekarang</span>
    <i class="fas fa-arrow-right ml-2 transition-transform group-hover:translate-x-1"></i>
  </button>

  <!-- Footer -->
  <div class="mt-6 text-center">
    <p class="text-xs text-gray-400 flex items-center justify-center">
      <i class="fas fa-info-circle mr-1 text-gray-400"></i>
      <span>Terma dan syarat dikenakan</span>
    </p>
  </div>
</div>

                </div>
            </div>

            <!-- Additional Info -->
            <div class="mt-16 text-center animate-slide-up">
                <div class="inline-flex items-center bg-white/80 backdrop-blur-sm rounded-2xl px-6 py-4 shadow-lg border border-gray-200">
                    <i class="fas fa-shield-alt text-premium-500 text-xl mr-3"></i>
                    <p class="text-gray-700 font-medium">Semua pakej dilengkapi dengan jaminan keselamatan data</p>
                </div>
            </div>
        </div>
    </section>
</body>
</html>