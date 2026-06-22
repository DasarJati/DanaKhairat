{{-- Simple Single Partner Collab --}}
<section id="kolaborasi" class="py-20 bg-white">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <h2 class="text-3xl font-bold text-gray-900 mb-4">Kolaborasi Kami</h2>
            <p class="text-gray-600 text-lg">Bekerjasama dengan organisasi terkemuka</p>
        </div>

   <!-- Single Partner Card - Simple & Clean -->
<div class="relative bg-white rounded-3xl p-10 text-center shadow-2xl border border-gray-200 hover:shadow-2xl transition-all duration-300">

    <!-- Simple Icon Box -->
    <div class="w-24 h-24 bg-gray-100 rounded-2xl flex items-center justify-center mx-auto mb-6 border border-gray-300">
        <img src="{{ asset('images/usolli.jpeg') }}" alt="Logo USOLLI" class="w-16 h-16 object-contain">
    </div>

    <!-- Title -->
    <h3 class="text-3xl font-bold text-gray-900 mb-2">USOLLI</h3>
    <p class="text-gray-600 text-lg mb-6">Waktu Solat & Kariah</p>
    
    <!-- Partner Badge -->
    <div class="inline-flex items-center bg-yellow-100 px-5 py-2 rounded-full mb-6 border border-yellow-200">
        <i class="fas fa-star text-yellow-600 mr-2"></i>
        <span class="font-semibold text-yellow-800 uppercase tracking-wide">Partner Rasmi</span>
    </div>
    
    <!-- Description -->
    <p class="text-gray-700 mb-8 leading-relaxed max-w-3xl mx-auto text-lg">
        Kerjasama strategik dalam memastikan sistem khairat digital mematuhi 
        standard <span class="font-semibold text-gray-900">Shariah</span> 
        yang ditetapkan oleh <span class="font-semibold text-gray-900">Jabatan Kemajuan Islam Malaysia</span>.
    </p>
    
    <!-- Features -->
    <div class="flex flex-wrap justify-center gap-3 text-sm font-medium">
        <span class="bg-gray-100 px-4 py-2 rounded-full border border-gray-300 text-gray-800">✅ Shariah Compliant</span>
        <span class="bg-gray-100 px-4 py-2 rounded-full border border-gray-300 text-gray-800">🏅 Pengiktirafan Rasmi</span>
        <span class="bg-gray-100 px-4 py-2 rounded-full border border-gray-300 text-gray-800">📅 Sejak 2022</span>
    </div>
</div>

    <!-- Subtle Floating Elements -->
    <div class="absolute top-4 right-4 w-3 h-3 bg-yellow-400 rounded-full opacity-60 animate-bounce"></div>
    <div class="absolute bottom-4 left-4 w-2 h-2 bg-blue-400 rounded-full opacity-60 animate-bounce" style="animation-delay: 0.5s;"></div>
</div>

<style>
@keyframes pulse-slow {
    0%, 100% { opacity: 0.3; }
    50% { opacity: 0.6; }
}
.animate-pulse-slow {
    animation: pulse-slow 4s cubic-bezier(0.4, 0, 0.6, 1) infinite;
}
</style>


        <!-- Future Collaboration Message -->
        <div class="text-center mt-8">
            <p class="text-gray-500 italic">
                * Lebih banyak kolaborasi akan diumumkan tidak lama lagi
            </p>
        </div>
    </div>
</section>

<!-- {{-- Collab Section --}}
<section id="kolaborasi" class="py-20 bg-gradient-to-br from-gray-50 to-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    
        <div class="text-center mb-16">
            <span class="inline-block px-4 py-2 bg-yellow-100 text-yellow-800 rounded-full text-sm font-semibold mb-4">
                <i class="fas fa-handshake mr-2"></i>
                Kerjasama Strategik
            </span>
            <h2 class="text-4xl font-bold text-gray-900 mb-4">Rangkaian Kolaborasi Kami</h2>
            <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                Berkolaborasi dengan organisasi terkemuka untuk membawa khidmat terbaik kepada komuniti masjid
            </p>
            <div class="w-24 h-1 bg-yellow-400 mx-auto mt-6 rounded-full"></div>
        </div>

        <div class="relative">
           
            <div class="overflow-hidden">
                <div id="collab-carousel" class="flex space-x-8 animate-scroll">
                   
                    <div class="flex-shrink-0 w-64 bg-white rounded-2xl shadow-lg hover:shadow-xl transition-all duration-500 transform hover:-translate-y-2 border border-gray-100">
                        <div class="p-6">
                            <div class="w-16 h-16 bg-blue-50 rounded-xl flex items-center justify-center mb-4 mx-auto">
                                <i class="fas fa-mosque text-blue-600 text-2xl"></i>
                            </div>
                            <h3 class="text-lg font-bold text-gray-900 text-center mb-2">JAKIM</h3>
                            <p class="text-gray-600 text-sm text-center">Jabatan Kemajuan Islam Malaysia</p>
                            <div class="mt-4 pt-4 border-t border-gray-200">
                                <p class="text-xs text-gray-500 text-center">Partner Rasmi</p>
                            </div>
                        </div>
                    </div>

               
                    <div class="flex-shrink-0 w-64 bg-white rounded-2xl shadow-lg hover:shadow-xl transition-all duration-500 transform hover:-translate-y-2 border border-gray-100">
                        <div class="p-6">
                            <div class="w-16 h-16 bg-green-50 rounded-xl flex items-center justify-center mb-4 mx-auto">
                                <i class="fas fa-university text-green-600 text-2xl"></i>
                            </div>
                            <h3 class="text-lg font-bold text-gray-900 text-center mb-2">Bank Islam</h3>
                            <p class="text-gray-600 text-sm text-center">Banking & Financial Solutions</p>
                            <div class="mt-4 pt-4 border-t border-gray-200">
                                <p class="text-xs text-gray-500 text-center">Financial Partner</p>
                            </div>
                        </div>
                    </div>

               
                    <div class="flex-shrink-0 w-64 bg-white rounded-2xl shadow-lg hover:shadow-xl transition-all duration-500 transform hover:-translate-y-2 border border-gray-100">
                        <div class="p-6">
                            <div class="w-16 h-16 bg-purple-50 rounded-xl flex items-center justify-center mb-4 mx-auto">
                                <i class="fas fa-heartbeat text-purple-600 text-2xl"></i>
                            </div>
                            <h3 class="text-lg font-bold text-gray-900 text-center mb-2">MyCARE</h3>
                            <p class="text-gray-600 text-sm text-center">Kebajikan & Sumbangan</p>
                            <div class="mt-4 pt-4 border-t border-gray-200">
                                <p class="text-xs text-gray-500 text-center">Welfare Partner</p>
                            </div>
                        </div>
                    </div>

                   
                    <div class="flex-shrink-0 w-64 bg-white rounded-2xl shadow-lg hover:shadow-xl transition-all duration-500 transform hover:-translate-y-2 border border-gray-100">
                        <div class="p-6">
                            <div class="w-16 h-16 bg-red-50 rounded-xl flex items-center justify-center mb-4 mx-auto">
                                <i class="fas fa-gem text-red-600 text-2xl"></i>
                            </div>
                            <h3 class="text-lg font-bold text-gray-900 text-center mb-2">CIMB Islamic</h3>
                            <p class="text-gray-600 text-sm text-center">Islamic Banking Services</p>
                            <div class="mt-4 pt-4 border-t border-gray-200">
                                <p class="text-xs text-gray-500 text-center">Banking Partner</p>
                            </div>
                        </div>
                    </div>

               
                    <div class="flex-shrink-0 w-64 bg-white rounded-2xl shadow-lg hover:shadow-xl transition-all duration-500 transform hover:-translate-y-2 border border-gray-100">
                        <div class="p-6">
                            <div class="w-16 h-16 bg-indigo-50 rounded-xl flex items-center justify-center mb-4 mx-auto">
                                <i class="fas fa-hands-helping text-indigo-600 text-2xl"></i>
                            </div>
                            <h3 class="text-lg font-bold text-gray-900 text-center mb-2">Yayasan PETRONAS</h3>
                            <p class="text-gray-600 text-sm text-center">CSR & Community Development</p>
                            <div class="mt-4 pt-4 border-t border-gray-200">
                                <p class="text-xs text-gray-500 text-center">CSR Partner</p>
                            </div>
                        </div>
                    </div>

                 
                    <div class="flex-shrink-0 w-64 bg-white rounded-2xl shadow-lg hover:shadow-xl transition-all duration-500 transform hover:-translate-y-2 border border-gray-100">
                        <div class="p-6">
                            <div class="w-16 h-16 bg-teal-50 rounded-xl flex items-center justify-center mb-4 mx-auto">
                                <i class="fas fa-book text-teal-600 text-2xl"></i>
                            </div>
                            <h3 class="text-lg font-bold text-gray-900 text-center mb-2">PTS</h3>
                            <p class="text-gray-600 text-sm text-center">Islamic Publications</p>
                            <div class="mt-4 pt-4 border-t border-gray-200">
                                <p class="text-xs text-gray-500 text-center">Education Partner</p>
                            </div>
                        </div>
                    </div>

                
                    <div class="flex-shrink-0 w-64 bg-white rounded-2xl shadow-lg hover:shadow-xl transition-all duration-500 transform hover:-translate-y-2 border border-gray-100">
                        <div class="p-6">
                            <div class="w-16 h-16 bg-blue-50 rounded-xl flex items-center justify-center mb-4 mx-auto">
                                <i class="fas fa-mosque text-blue-600 text-2xl"></i>
                            </div>
                            <h3 class="text-lg font-bold text-gray-900 text-center mb-2">JAKIM</h3>
                            <p class="text-gray-600 text-sm text-center">Jabatan Kemajuan Islam Malaysia</p>
                            <div class="mt-4 pt-4 border-t border-gray-200">
                                <p class="text-xs text-gray-500 text-center">Partner Rasmi</p>
                            </div>
                        </div>
                    </div>

                    <div class="flex-shrink-0 w-64 bg-white rounded-2xl shadow-lg hover:shadow-xl transition-all duration-500 transform hover:-translate-y-2 border border-gray-100">
                        <div class="p-6">
                            <div class="w-16 h-16 bg-green-50 rounded-xl flex items-center justify-center mb-4 mx-auto">
                                <i class="fas fa-university text-green-600 text-2xl"></i>
                            </div>
                            <h3 class="text-lg font-bold text-gray-900 text-center mb-2">Bank Islam</h3>
                            <p class="text-gray-600 text-sm text-center">Banking & Financial Solutions</p>
                            <div class="mt-4 pt-4 border-t border-gray-200">
                                <p class="text-xs text-gray-500 text-center">Financial Partner</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

           
            <div class="absolute left-0 top-0 bottom-0 w-20 bg-gradient-to-r from-gray-50 to-transparent z-10"></div>
            <div class="absolute right-0 top-0 bottom-0 w-20 bg-gradient-to-l from-gray-50 to-transparent z-10"></div>
        </div>

     

       
    </div>
</section>

<style>
    @keyframes scroll {
        0% {
            transform: translateX(0);
        }
        100% {
            transform: translateX(calc(-272px * 6));
        }
    }

    .animate-scroll {
        animation: scroll 30s linear infinite;
    }

    .animate-scroll:hover {
        animation-play-state: paused;
    }

 
    @media (max-width: 768px) {
        @keyframes scroll {
            0% {
                transform: translateX(0);
            }
            100% {
                transform: translateX(calc(-272px * 3));
            }
        }
    }
</style>

<script>
    
    document.addEventListener('DOMContentLoaded', function() {
        const carousel = document.getElementById('collab-carousel');
        
        carousel.addEventListener('mouseenter', function() {
            this.style.animationPlayState = 'paused';
        });
        
        carousel.addEventListener('mouseleave', function() {
            this.style.animationPlayState = 'running';
        });

        // Touch events for mobile
        carousel.addEventListener('touchstart', function() {
            this.style.animationPlayState = 'paused';
        });
        
        carousel.addEventListener('touchend', function() {
            this.style.animationPlayState = 'running';
        });
    });
</script> -->