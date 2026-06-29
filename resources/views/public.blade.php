<!DOCTYPE html>
<html lang="en">
<html lang="ms" class="scroll-smooth">

<head>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DanaKhairat</title>
    <link rel="icon" href="{{ asset('asset/images/ekhairat.ico') }}" type="image/x-icon">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        /* Mobile menu styles */
        #mobile-menu {
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.5s ease-in-out;
        }

        #mobile-menu.open {
            max-height: 500px;
            /* Fallback, will be overridden by JS */
        }

        #mobile-sahabat-dropdown {
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.3s ease-in-out;
        }

        #mobile-sahabat-dropdown.open {
            max-height: 200px;
            /* Fallback, will be overridden by JS */
        }
    </style>
</head>

<body class="font-sans antialiased bg-white text-gray-900">

    {{-- Header Section --}}
    <header class="sticky-header bg-white/95 shadow-sm border-b border-gray-100">
        <nav class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center py-4">
                <!-- Logo -->
                <a href="/" class="flex items-center space-x-3">
                    <img src="{{ asset('images/logos.png') }}" alt="Logo Masjid" class="h-10 w-auto">
                </a>


                <!-- Desktop Navigation -->
                <div class="hidden md:flex items-center space-x-8">
                    <a href="#hero"
                        class="text-gray-700 hover:text-yellow-600 font-medium transition duration-300">Utama</a>
                    <a href="/checkstatus"
                        class="text-gray-700 hover:text-yellow-600 font-medium transition duration-300">Status
                        Sahabat</a>
                    <a href="#us"
                        class="text-gray-700 hover:text-yellow-600 font-medium transition duration-300">Tentang Kami</a>
                    <a href="#footer"
                        class="text-gray-700 hover:text-yellow-600 font-medium transition duration-300">Hubungi</a>
                </div>

                <!-- CTA Buttons -->
                <div class="hidden md:flex items-center space-x-4">
                    <a href="/login"
                        class="text-gray-700 hover:text-yellow-600 font-medium transition duration-300">Log Masuk</a>
                    <a href="/pilihan"
                        class="bg-yellow-500 hover:bg-yellow-600 text-gray-900 px-4 py-2 rounded-lg font-medium transition duration-300 transform hover:scale-105">
                        Daftar Akaun
                    </a>
                </div>

                <!-- Mobile menu button -->
                <div class="md:hidden">
                    <button id="mobile-btn" class="text-gray-700 focus:outline-none text-2xl">
                        <i id="menu-icon" class="fas fa-bars"></i>
                    </button>
                </div>
            </div>

            <div id="mobile-menu" class="md:hidden max-h-0 overflow-hidden transition-all duration-500 ease-in-out">
                <div class="flex flex-col space-y-4 py-4 border-t border-gray-200">

                    <a href="#hero" class="mobile-link text-gray-700 hover:text-yellow-600 font-medium">Utama</a>
                    <div class="flex flex-col">
                        <button id="mobile-sahabat-btn"
                            class="flex justify-between items-center text-gray-700 hover:text-yellow-600 font-medium py-2">
                            Sahabat
                            <svg class="w-4 h-4 transition-transform duration-300" id="sahabat-arrow" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>
                        <div id="mobile-sahabat-dropdown"
                            class="max-h-0 overflow-hidden transition-all duration-300 bg-gray-50 rounded-lg">
                            <a href="#sahabat"
                                class="mobile-link block px-6 py-2 text-sm text-gray-600 hover:text-yellow-600">Senarai
                                Sahabat</a>
                            <a href="/checkstatus"
                                class="mobile-link block px-6 py-2 text-sm text-gray-600 hover:text-yellow-600">Status
                                Sahabat</a>
                        </div>
                    </div>
                    <a href="#us" class="mobile-link text-gray-700 hover:text-yellow-600 font-medium">Tentang
                        Kami</a>
                    <a href="#footer" class="mobile-link text-gray-700 hover:text-yellow-600 font-medium">Hubungi</a>

                    <a href="/login" class="mobile-link text-gray-700 hover:text-yellow-600 font-medium">Log Masuk</a>

                    <a href="/pilihan"
                        class="mobile-link bg-yellow-500 text-center text-gray-900 px-4 py-2 rounded-lg font-medium">
                        Daftar Akaun
                    </a>

                </div>
            </div>

        </nav>
    </header>

    {{-- Hero Section --}}
    <section id="hero" class="hero-section relative min-h-screen flex items-center">
        <!-- Background image dengan cover -->
        <div class="absolute inset-0 z-0">
            <img src="https://i0.pickpik.com/photos/850/962/513/mosque-abu-dhabi-travel-white-preview.jpg"
                alt="Masjid Al Nabawi" class="w-full h-full object-cover">
            <!-- Overlay gelap untuk readability -->
            <div class="absolute inset-0 bg-black/50"></div>
        </div>

        <!-- Content di atas background -->
        <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 w-full text-white py-20">
            <div class="flex flex-col items-start text-left max-w-4xl">
                <a href="/" class="flex items-center space-x-3 mb-6">
                    <img src="{{ asset('images/logoswhite.png') }}" alt="Logo Masjid"
                        class="h-20 w-auto drop-shadow-lg">
                </a>


                <p class="text-xl md:text-2xl mb-8 leading-relaxed max-w-3xl">
                    Satu komuniti. Satu tujuan. <br>
                    Saling membantu ketika waktu memerlukan.<br>
                    Selamat datang ke Portal Rasmi Khairat Kematian.<br>
                    Sertai kami dalam usaha murni<br>
                    membina jaringan sokongan sesama ahli.<br>
                </p>

                <div class="flex gap-4 flex-wrap">
                    <a href="#us"
                        class="bg-yellow-500 hover:bg-yellow-600 text-gray-900 font-bold py-3 px-6 rounded-lg transform transition duration-300 ease-out hover:scale-105">
                        Ketahui Lebih Lanjut
                    </a>

                </div>
            </div>
        </div>
    </section>

    {{-- Top Sahabat Kariah --}}
    <section id="sahabat" class="py-12 sm:py-16 md:py-20 bg-gray-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header Section -->
            <div class="flex flex-col sm:flex-row justify-between items-center gap-4 mb-8 sm:mb-10 md:mb-12">
                <h2 class="text-2xl sm:text-3xl md:text-4xl font-extrabold text-center sm:text-left text-gray-900">
                    Senarai Sahabat Kariah
                </h2>
                <div class="flex gap-3 sm:gap-4">
                    <button id="prevBtn"
                        class="p-3 sm:p-4 rounded-full bg-white shadow-lg hover:bg-yellow-500 hover:text-white transition-all duration-300 transform hover:scale-110 border border-yellow-200 disabled:opacity-50 disabled:cursor-not-allowed">
                        &#10094;
                    </button>
                    <button id="nextBtn"
                        class="p-3 sm:p-4 rounded-full bg-white shadow-lg hover:bg-yellow-500 hover:text-white transition-all duration-300 transform hover:scale-110 border border-yellow-200 disabled:opacity-50 disabled:cursor-not-allowed">
                        &#10095;
                    </button>
                </div>
            </div>

            <!-- Carousel Container -->
            <div class="relative overflow-hidden">
                <div id="carouselTrack"
                    class="flex gap-4 sm:gap-5 md:gap-6 transition-transform duration-500 ease-in-out">
                    <!-- Cards -->
                    <div
                        class="carousel-item flex-shrink-0 w-full sm:w-1/2 md:w-1/3 lg:w-1/4 min-w-[280px] sm:min-w-[250px] bg-white rounded-2xl overflow-hidden shadow-lg border border-yellow-100">
                        <img src="{{ asset('images/tnb.jpg') }}" class="h-48 sm:h-52 md:h-56 w-full object-cover"
                            alt="TNB Mosque">
                        <div class="p-4 sm:p-5 md:p-6">
                            <h3 class="text-lg sm:text-xl font-bold text-gray-900">Masjid TNB Bangsar</h3>
                            <p class="text-sm sm:text-base text-gray-600 mt-2">Bangsar, Kuala Lumpur<br>800 Orang Ahli
                            </p>
                        </div>
                    </div>

                    <div
                        class="carousel-item flex-shrink-0 w-full sm:w-1/2 md:w-1/3 lg:w-1/4 min-w-[280px] sm:min-w-[250px] bg-white rounded-2xl overflow-hidden shadow-lg border border-yellow-100">
                        <img src="{{ asset('images/putra.jpg') }}" class="h-48 sm:h-52 md:h-56 w-full object-cover"
                            alt="Putra Mosque">
                        <div class="p-4 sm:p-5 md:p-6">
                            <h3 class="text-lg sm:text-xl font-bold text-gray-900">Masjid Putra</h3>
                            <p class="text-sm sm:text-base text-gray-600 mt-2">Putrajaya, Wilayah Persekutuan
                                Putrajaya<br>1200 Orang Ahli</p>
                        </div>
                    </div>

                    <div
                        class="carousel-item flex-shrink-0 w-full sm:w-1/2 md:w-1/3 lg:w-1/4 min-w-[280px] sm:min-w-[250px] bg-white rounded-2xl overflow-hidden shadow-lg border border-yellow-100">
                        <img src="{{ asset('images/nur.jpg') }}" class="h-48 sm:h-52 md:h-56 w-full object-cover"
                            alt="Nur Ramadhan">
                        <div class="p-4 sm:p-5 md:p-6">
                            <h3 class="text-lg sm:text-xl font-bold text-gray-900">Surau Nur Ramadhan</h3>
                            <p class="text-sm sm:text-base text-gray-600 mt-2">Kajang, Sg Merab<br>450 Orang Ahli</p>
                        </div>
                    </div>

                    <div
                        class="carousel-item flex-shrink-0 w-full sm:w-1/2 md:w-1/3 lg:w-1/4 min-w-[280px] sm:min-w-[250px] bg-white rounded-2xl overflow-hidden shadow-lg border border-yellow-100">
                        <img src="{{ asset('images/kelantan.jpeg') }}"
                            class="h-48 sm:h-52 md:h-56 w-full object-cover" alt="Andalusia Mosque">
                        <div class="p-4 sm:p-5 md:p-6">
                            <h3 class="text-lg sm:text-xl font-bold text-gray-900">Masjid Andalusia</h3>
                            <p class="text-sm sm:text-base text-gray-600 mt-2">Kota Bharu, Kelantan<br>2000 Orang Ahli
                            </p>
                        </div>
                    </div>

                    <div
                        class="carousel-item flex-shrink-0 w-full sm:w-1/2 md:w-1/3 lg:w-1/4 min-w-[280px] sm:min-w-[250px] bg-white rounded-2xl overflow-hidden shadow-lg border border-yellow-100">
                        <img src="{{ asset('images/shahalam.jpg') }}" class="h-48 sm:h-52 md:h-56 w-full object-cover"
                            alt="Al-Mawaddah">
                        <div class="p-4 sm:p-5 md:p-6">
                            <h3 class="text-lg sm:text-xl font-bold text-gray-900">Surau Al-Mawaddah</h3>
                            <p class="text-sm sm:text-base text-gray-600 mt-2">Sesyen 7, Shah Alam<br>150 Orang Ahli
                            </p>
                        </div>
                    </div>

                    <div
                        class="carousel-item flex-shrink-0 w-full sm:w-1/2 md:w-1/3 lg:w-1/4 min-w-[280px] sm:min-w-[250px] bg-white rounded-2xl overflow-hidden shadow-lg border border-yellow-100">
                        <img src="{{ asset('images/sendayan.jpg') }}" class="h-48 sm:h-52 md:h-56 w-full object-cover"
                            alt="Sri Sendayan">
                        <div class="p-4 sm:p-5 md:p-6">
                            <h3 class="text-lg sm:text-xl font-bold text-gray-900">Masjid Sri Sendayan</h3>
                            <p class="text-sm sm:text-base text-gray-600 mt-2">Seremban, Negeri Sembilan<br>3000 Orang
                                Ahli</p>
                        </div>
                    </div>

                    <div
                        class="carousel-item flex-shrink-0 w-full sm:w-1/2 md:w-1/3 lg:w-1/4 min-w-[280px] sm:min-w-[250px] bg-white rounded-2xl overflow-hidden shadow-lg border border-yellow-100">
                        <img src="{{ asset('/images/pinang.jpeg') }}" class="h-48 sm:h-52 md:h-56 w-full object-cover"
                            alt="Al-Muttaqin">
                        <div class="p-4 sm:p-5 md:p-6">
                            <h3 class="text-lg sm:text-xl font-bold text-gray-900">Surau Al-Muttaqin</h3>
                            <p class="text-sm sm:text-base text-gray-600 mt-2">Lilitan Sg Ara, Penang<br>200 Orang Ahli
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- How It Works Section --}}
    <section id="us" class="py-24 bg-gradient-to-br from-gray-50 to-yellow-50/30">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-20">
                <div
                    class="inline-flex items-center gap-2 bg-yellow-100 text-yellow-800 px-4 py-2 rounded-full text-sm font-medium mb-6 border border-yellow-200">
                    <i class="fas fa-lightbulb text-yellow-600"></i>
                    Proses Mudah & Cepat
                </div>
                <h2 class="text-5xl font-bold text-gray-900 mb-6">
                    Bagaimana <span class="text-yellow-600 relative">DanaKhairat
                        <svg class="absolute -bottom-2 left-0 w-full" viewBox="0 0 200 10" fill="none">
                            <path d="M0 8C50 2 150 2 200 8" stroke="#f59e0b" stroke-width="3"
                                stroke-linecap="round" />
                        </svg>
                    </span> Membantu
                </h2>
                <p class="text-xl text-gray-600 max-w-2xl mx-auto leading-relaxed">
                    Transformasi urusan khairat tradisional kepada digital dengan proses yang lebih mudah,
                    pantas dan telus melalui platform kami.
                </p>
            </div>

            <!-- Desktop Timeline -->
            <div class="hidden lg:block relative">
                <!-- Connecting Line -->
                <div
                    class="absolute top-20 left-0 right-0 h-1 bg-gradient-to-r from-yellow-400 to-yellow-600 rounded-full">
                </div>

                <div class="grid grid-cols-4 gap-8 relative z-10">
                    @php
                        $steps = [
                            [
                                'number' => '1',
                                'title' => 'Daftar Ahli Digital',
                                'description' =>
                                    'Isi maklumat diri secara online dengan selamat dan pantas tanpa perlu borang fizikal.',
                                'step' => 'langkah 1',
                                'color' => 'yellow',
                            ],
                            [
                                'number' => '2',
                                'title' => 'Proses Pembayaran',
                                'description' =>
                                    'Lengkapkan bayaran yuran, dan keahlian anda akan aktif dalam masa yang singkat',
                                'step' => 'langkah 2',
                                'color' => 'blue',
                            ],
                            [
                                'number' => '3',
                                'title' => 'Lindungan Aktif',
                                'description' =>
                                    'Perlindungan khairat automatik aktif sejurus selepas pembayaran disahkan.',
                                'step' => 'langkah 3',
                                'color' => 'green',
                            ],
                            [
                                'number' => '4',
                                'title' => 'Pengurusan Kematian',
                                'description' =>
                                    'Pengurusan kematian yang lebih cepat, sistematik dan telus apabila diperlukan.',
                                'step' => 'langkah 4',
                                'color' => 'purple',
                            ],
                        ];
                    @endphp

                    @foreach ($steps as $step)
                        <div class="group text-center">
                            <!-- Step Card -->
                            <div
                                class="bg-white rounded-2xl p-8 shadow-lg hover:shadow-2xl transition-all duration-500 transform hover:-translate-y-3 border border-gray-100 relative overflow-hidden">
                                <!-- Hover Effect Background -->
                                <div
                                    class="absolute inset-0 bg-gradient-to-br from-{{ $step['color'] }}-50 to-white opacity-0 group-hover:opacity-100 transition-opacity duration-500">
                                </div>


                                <!-- Icon -->
                                <!-- Step Number -->
                                <div
                                    class="w-16 h-16 mx-auto mb-4 flex items-center justify-center 
            rounded-xl bg-{{ $step['color'] }}-100 text-{{ $step['color'] }}-600 
            text-2xl font-bold group-hover:scale-110 transition-transform duration-300">
                                    {{ $step['number'] }}
                                </div>


                                <!-- Content -->
                                <h3 class="text-xl font-bold text-gray-900 mb-3 relative z-10">{{ $step['title'] }}
                                </h3>
                                <p class="text-gray-600 leading-relaxed relative z-10">{{ $step['description'] }}</p>

                            </div>

                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Mobile Timeline -->
            <div class="lg:hidden space-y-8">
                @foreach ($steps as $step)
                    <div class="bg-white rounded-2xl p-6 shadow-lg border border-gray-100">
                        <div class="flex items-start gap-4">
                            <!-- Step Number -->
                            <div
                                class="flex-shrink-0 w-16 h-16 flex items-center justify-center rounded-2xl bg-{{ $step['color'] }}-500 text-white text-xl font-bold shadow-lg">
                                {{ $step['number'] }}
                            </div>

                            <!-- Content -->
                            <div class="flex-1">
                                <div class="flex items-center gap-3 mb-2">
                                    <div
                                        class="w-10 h-10 flex items-center justify-center rounded-lg bg-{{ $step['color'] }}-100 text-{{ $step['color'] }}-600">
                                        <i class="{{ $step['number'] }}"></i>
                                    </div>
                                    <h3 class="text-lg font-bold text-gray-900">{{ $step['title'] }}</h3>
                                </div>
                                <p class="text-gray-600 text-sm leading-relaxed">{{ $step['description'] }}</p>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- CTA Section -->

        </div>
    </section>

    {{-- Mengapa Kami --}}
    @include('partials.whyus')


    {{-- Footer --}}
    <footer id="footer" class="bg-gray-900 text-white pt-12 pb-6">
        <div class="max-w-6xl mx-auto px-6">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <!-- Company Info -->
                <div class="md:col-span-2">
                    <h3 class="text-xl font-bold mb-4">DanaKhairat</h3>
                    <p class="text-gray-300 mb-6 max-w-lg">
                        Platform digital khairat yang memudahkan pengurusan dana kematian dengan efisien, selamat dan
                        telus.
                    </p>
                    <div class="flex space-x-6 mt-6">
                        <div class="flex items-center space-x-2">
                            <div class="w-6 h-6 bg-djariah-100 rounded flex items-center justify-center">
                                <i class="fas fa-shield-alt text-djariah-600 text-xs"></i>
                            </div>
                            <span class="text-gray-400 text-sm">Selamat</span>
                        </div>
                        <div class="flex items-center space-x-2">
                            <div class="w-6 h-6 bg-djariah-100 rounded flex items-center justify-center">
                                <i class="fas fa-bolt text-djariah-600 text-xs"></i>
                            </div>
                            <span class="text-gray-400 text-sm">Pantas</span>
                        </div>
                        <div class="flex items-center space-x-2">
                            <div class="w-6 h-6 bg-djariah-100 rounded flex items-center justify-center">
                                <i class="fas fa-mobile-alt text-djariah-600 text-xs"></i>
                            </div>
                            <span class="text-gray-400 text-sm">Mudah Alih</span>
                        </div>
                    </div>
                </div>

                <!-- Contact Info -->
                <div>
                    <h4 class="text-lg font-semibold mb-4">Hubungi Kami</h4>
                    <ul class="space-y-3">
                        <li class="flex items-center">
                            <i class="fas fa-map-marker-alt mr-3 text-yellow-400 text-sm"></i>
                            <span class="text-gray-300 text-sm">Plaza Paragon Point, A-07-1, Seksyen 9, 43650 Bandar
                                Baru Bangi, Selangor</span>
                        </li>
                        <li class="flex items-center">
                            <i class="fas fa-phone mr-3 text-yellow-400 text-sm"></i>
                            <span class="text-gray-300 text-sm">+03-8927 5213</span>
                        </li>
                        <li class="flex items-center">
                            <i class="fas fa-envelope mr-3 text-yellow-400 text-sm"></i>
                            <span class="text-gray-300 text-sm">helpdesk@danakhairat.my</span>
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Bottom Bar -->
            <div class="border-t border-gray-800 mt-8 pt-6 flex flex-col md:flex-row justify-between items-center">
                <p class="text-gray-400 text-sm mb-3 md:mb-0">
                    &copy; 2026 DanaKhairat. Hak Cipta Terpelihara.
                </p>
                <p class="text-gray-400 text-sm">
                    Web Ini Di Sediakan Oleh DasarJati Sdn. Bhd.
                </p>
            </div>
        </div>
    </footer>

    <script>
        document.addEventListener("DOMContentLoaded", () => {
            // Mobile Menu Script
            const mobileBtn = document.getElementById('mobile-btn');
            const mobileMenu = document.getElementById('mobile-menu');
            const menuIcon = document.getElementById('menu-icon');

            const sahabatBtn = document.getElementById('mobile-sahabat-btn');
            const sahabatDropdown = document.getElementById('mobile-sahabat-dropdown');
            const sahabatArrow = document.getElementById('sahabat-arrow');

            function updateMobileMenuHeight() {
                if (mobileMenu.classList.contains('open')) {
                    let totalHeight = mobileMenu.scrollHeight;
                    if (sahabatDropdown && sahabatDropdown.classList.contains('open')) {
                        totalHeight += sahabatDropdown.scrollHeight;
                    }
                    mobileMenu.style.maxHeight = totalHeight + "px";
                }
            }

            function toggleMobileMenu() {
                const isOpen = mobileMenu.classList.contains('open');

                if (!isOpen) {
                    mobileMenu.classList.add('open');
                    mobileMenu.style.maxHeight = mobileMenu.scrollHeight + "px";
                    menuIcon.classList.remove('fa-bars');
                    menuIcon.classList.add('fa-times');
                } else {
                    mobileMenu.classList.remove('open');
                    mobileMenu.style.maxHeight = "0px";
                    menuIcon.classList.remove('fa-times');
                    menuIcon.classList.add('fa-bars');

                    if (sahabatDropdown && sahabatDropdown.classList.contains('open')) {
                        sahabatDropdown.classList.remove('open');
                        sahabatDropdown.style.maxHeight = "0px";
                        if (sahabatArrow) sahabatArrow.style.transform = "rotate(0deg)";
                    }
                }
            }

            function toggleSahabatDropdown(e) {
                if (e) e.preventDefault();

                const isOpen = sahabatDropdown.classList.contains('open');

                if (!isOpen) {
                    sahabatDropdown.classList.add('open');
                    sahabatDropdown.style.maxHeight = sahabatDropdown.scrollHeight + "px";
                    if (sahabatArrow) sahabatArrow.style.transform = "rotate(180deg)";

                    if (mobileMenu.classList.contains('open')) {
                        setTimeout(() => {
                            mobileMenu.style.maxHeight = (mobileMenu.scrollHeight + sahabatDropdown
                                .scrollHeight) + "px";
                        }, 10);
                    }
                } else {
                    sahabatDropdown.classList.remove('open');
                    sahabatDropdown.style.maxHeight = "0px";
                    if (sahabatArrow) sahabatArrow.style.transform = "rotate(0deg)";

                    if (mobileMenu.classList.contains('open')) {
                        setTimeout(() => {
                            mobileMenu.style.maxHeight = mobileMenu.scrollHeight + "px";
                        }, 10);
                    }
                }
            }

            if (mobileBtn) {
                mobileBtn.addEventListener('click', toggleMobileMenu);
            }

            if (sahabatBtn) {
                sahabatBtn.addEventListener('click', toggleSahabatDropdown);
            }

            const mobileLinks = document.querySelectorAll('.mobile-link');
            mobileLinks.forEach(link => {
                link.addEventListener('click', (e) => {
                    if (e.target.closest('#mobile-sahabat-btn')) {
                        return;
                    }
                    if (mobileMenu.classList.contains('open')) {
                        toggleMobileMenu();
                    }
                });
            });

            mobileMenu.style.maxHeight = "0px";
            mobileMenu.classList.remove('open');

            window.addEventListener('resize', function() {
                if (window.innerWidth >= 768) {
                    if (mobileMenu.classList.contains('open')) {
                        toggleMobileMenu();
                    }
                }
            });

            // Carousel Script
            function initCarousel() {
        const track = document.getElementById('carouselTrack');
        const prevBtn = document.getElementById('prevBtn');
        const nextBtn = document.getElementById('nextBtn');
        const items = document.querySelectorAll('.carousel-item');
        
        if (!track || !items.length) {
            console.log('Carousel elements not found');
            return;
        }
        
        let currentIndex = 0;
        let itemsPerView = getItemsPerView();
        const totalItems = items.length;
        
        function getItemsPerView() {
            const width = window.innerWidth;
            if (width < 640) { // mobile
                return 1;
            } else if (width < 768) { // small tablet
                return 1;
            } else if (width < 1024) { // tablet
                return 2;
            } else if (width < 1280) { // laptop
                return 3;
            } else { // desktop
                return 4;
            }
        }
        
        function getMaxIndex() {
            return Math.max(0, totalItems - itemsPerView);
        }
        
        function updateCarousel() {
            itemsPerView = getItemsPerView();
            const maxIndex = getMaxIndex();
            
            // Ensure currentIndex is within bounds
            if (currentIndex > maxIndex) {
                currentIndex = maxIndex;
            }
            
            const itemWidth = items[0].offsetWidth;
            const gap = window.innerWidth < 640 ? 16 : window.innerWidth < 768 ? 20 : 24;
            const offset = currentIndex * (itemWidth + gap);
            track.style.transform = `translateX(-${offset}px)`;
            
            // Update button states
            if (prevBtn) {
                prevBtn.disabled = currentIndex === 0;
                prevBtn.style.opacity = currentIndex === 0 ? '0.5' : '1';
                prevBtn.style.cursor = currentIndex === 0 ? 'not-allowed' : 'pointer';
            }
            if (nextBtn) {
                const maxIdx = getMaxIndex();
                nextBtn.disabled = currentIndex >= maxIdx;
                nextBtn.style.opacity = currentIndex >= maxIdx ? '0.5' : '1';
                nextBtn.style.cursor = currentIndex >= maxIdx ? 'not-allowed' : 'pointer';
            }
        }
        
        function nextSlide() {
            const maxIndex = getMaxIndex();
            if (currentIndex < maxIndex) {
                currentIndex++;
                updateCarousel();
            }
        }
        
        function prevSlide() {
            if (currentIndex > 0) {
                currentIndex--;
                updateCarousel();
            }
        }
        
        if (nextBtn) nextBtn.addEventListener('click', nextSlide);
        if (prevBtn) prevBtn.addEventListener('click', prevSlide);
        
        // Handle resize with debounce
        let resizeTimer;
        window.addEventListener('resize', function() {
            clearTimeout(resizeTimer);
            resizeTimer = setTimeout(updateCarousel, 200);
        });
        
        // Initialize
        setTimeout(updateCarousel, 100);
        window.addEventListener('load', function() {
            setTimeout(updateCarousel, 200);
        });
    }
    
    initCarousel();
        });
    </script>
</body>

</html>
