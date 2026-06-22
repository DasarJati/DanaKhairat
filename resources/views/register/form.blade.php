<!DOCTYPE html>
<html lang="ms">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pendaftaran AJK - Sistem Khairat Digital</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>

<body class="bg-gradient-to-br from-gray-50 to-gray-100 min-h-screen">
    <!-- Header Section -->
    <header class="sticky top-0 z-50 bg-white/95 backdrop-blur-sm shadow-sm border-b border-gray-200">
        <nav class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center py-3">
                <div class="flex items-center justify-between w-full">

                    <!-- Back Button -->
                    <a href="/"
                        class="flex items-center space-x-2 bg-gray-100 hover:bg-gray-200 px-3 py-2 rounded-lg transition-all duration-200 group"
                        title="Kembali ke Laman Utama">

                        <i
                            class="fas fa-arrow-left text-gray-600 group-hover:text-gray-800 transition-colors duration-200"></i>

                        <!-- Hide text on mobile -->
                        <span class="hidden sm:inline text-sm text-gray-700 group-hover:text-gray-900">
                            Kembali ke Laman Utama
                        </span>
                    </a>

                    <!-- Logo Tengah -->
                    <div class="flex items-center space-x-3 absolute left-1/2 transform -translate-x-1/2">
                        <img src="{{ asset('images/logos.png') }}" alt="Logo DJariah" class="h-11 w-auto">
                    </div>

                </div>
            </div>
        </nav>
    </header>

    <!-- Main Content -->
    <div class="py-4">
        <div class="max-w-7xl mx-auto px-4">
            <!-- Main Card -->
            <div class="max-w-screen-2xl mx-auto">
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                    <!-- Progress Bar -->
                    <div class="bg-gradient-to-r from-gray-800 to-gray-900 px-6 py-4">
                        <div class="flex items-center justify-between max-w-3xl mx-auto">
                            <!-- Step 1 -->
                            <div class="flex flex-col items-center">
                                <div id="step1"
                                    class="w-10 h-10 flex items-center justify-center rounded-full bg-white text-gray-800 font-bold text-sm shadow-sm border border-gray-300 step-indicator active">
                                    <i class="fas fa-user-edit text-xs"></i>
                                </div>
                                <span
                                    class="text-white font-medium mt-1 text-xs bg-gray-700/50 px-2 py-1 rounded">Maklumat
                                    Institusi</span>
                            </div>

                            <!-- Progress Line 1 -->
                            <div id="progressLine1" class="flex-1 h-1 bg-gray-600 mx-2 rounded-full"></div>

                            <!-- Step 2 -->
                            <div class="flex flex-col items-center">
                                <div id="step2"
                                    class="w-10 h-10 flex items-center justify-center rounded-full bg-gray-600 text-gray-300 font-bold text-sm border border-gray-500 step-indicator">
                                    <i class="fas fa-folder-open text-xs"></i>
                                </div>
                                <span class="text-gray-300 font-medium mt-1 text-xs">Dokumen</span>
                            </div>


                            <!-- <div id="progressLine2" class="flex-1 h-1 bg-gray-600 mx-2 rounded-full"></div>

                          
                            <div class="flex flex-col items-center">
                                <div id="step3" class="w-10 h-10 flex items-center justify-center rounded-full bg-gray-600 text-gray-300 font-bold text-sm border border-gray-500 step-indicator">
                                    <i class="fas fa-box text-xs"></i>
                                </div>
                                <span class="text-gray-300 font-medium mt-1 text-xs">Pilih Pakej</span>
                            </div>

                          
                            <div id="progressLine3" class="flex-1 h-1 bg-gray-600 mx-2 rounded-full"></div>
                            <div class="flex flex-col items-center">
                                <div id="step4" class="w-10 h-10 flex items-center justify-center rounded-full bg-gray-600 text-gray-300 font-bold text-sm border border-gray-500 step-indicator">
                                    <i class="fas fa-credit-card text-xs"></i>
                                </div>
                                <span class="text-gray-300 font-medium mt-1 text-xs">Pembayaran</span>
                            </div> -->
                        </div>
                    </div>

                    <!-- Form -->
                    <form id="complete-form" action="{{ route('ajk.complete') }}" method="POST" enctype="multipart/form-data">
                        
                        @csrf

                        <!-- Tab 1 - Professional Admin Panel Style -->
                        <div id="tab1" class="p-4">
                            <div class="text-center mb-4">
                                <h2 class="text-2xl font-bold text-gray-800 mb-1">Pendaftaran</h2>
                                <div class="w-16 h-1 bg-gray-400 mx-auto rounded-full mb-2"></div>
                                <p class="text-gray-600 text-sm">Sila lengkapkan maklumat institusi anda</p>
                            </div>


                            <div class="grid lg:grid-cols-2 gap-5">

                                <div class="space-y-4">

                                    <div class="border border-gray-200 rounded-lg bg-white">
                                        <div class="bg-gray-50 px-4 py-3 border-b border-gray-200">
                                            <h3 class="text-sm font-semibold text-gray-700 flex items-center">
                                                <i class="fas fa-mosque text-gray-500 mr-2 text-xs"></i>
                                                Maklumat
                                            </h3>
                                        </div>
                                        <div class="p-4 space-y-4">

                                            <div class="grid grid-cols-2 gap-3">

                                                <div>
                                                    <label
                                                        class="block text-xs font-medium text-gray-600 mb-1">Jenis</label>
                                                    <select name="type" id="type" required
                                                        class="w-full border border-gray-300 rounded px-3 py-3 text-sm focus:ring-1 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                                                        <option value="">-- Pilih --</option>
                                                        <option>Surau</option>
                                                        <option>Masjid</option>
                                                        <option>Institusi</option>
                                                        <option>Komuniti</option>
                                                        <option>Lain-Lain</option>
                                                    </select>
                                                </div>
                                                <div>
                                                    <label class="block text-xs font-medium text-gray-600 mb-1">Nama
                                                        Masjid/Surau/Institusi/Komuniti/Lain-Lain</label>
                                                    <input type="text" name="nama_masjid" id="nama_masjid" required
                                                        class="w-full border border-gray-300 rounded px-3 py-3 text-sm focus:ring-1 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                                        placeholder="Nama Institusi anda">
                                                </div>
                                            </div>


                                            <div class="grid grid-cols-1 gap-2">

                                                <div>
                                                    <div>
                                                        <label class="block text-xs font-medium text-gray-600 mb-1">
                                                            Alamat
                                                        </label>

                                                        <textarea name="alamat" id="alamat" rows="2"
                                                            class="w-full border border-gray-300 rounded px-3 py-3 text-sm 
                                                                    focus:ring-1 focus:ring-blue-500 focus:border-blue-500 
                                                                    transition-colors resize-none"
                                                            placeholder="Masukkan alamat"></textarea>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="grid grid-cols-3 gap-3">

                                                <div>
                                                    <label
                                                        class="block text-xs font-medium text-gray-600 mb-1">Negeri</label>
                                                    <select name="negeri" id="negeri" required
                                                        class="w-full border border-gray-300 rounded px-3 py-3 text-sm focus:ring-1 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                                                        <option value="">-- Pilih --</option>
                                                        <option>Perlis</option>
                                                        <option>Kedah</option>
                                                        <option>Pulau Pinang</option>
                                                        <option>Perak</option>
                                                        <option>Selangor</option>
                                                        <option>Negeri Sembilan</option>
                                                        <option>Melaka</option>
                                                        <option>Johor</option>
                                                        <option>Pahang</option>
                                                        <option>Terengganu</option>
                                                        <option>Kelantan</option>
                                                        <option>WP Kuala Lumpur</option>
                                                        <option>WP Putrajaya</option>
                                                        <option>WP Labuan</option>
                                                        <option>Sarawak</option>
                                                        <option>Sabah</option>
                                                    </select>
                                                </div>
                                                <input type="hidden" name="negeri_id" id="negeri_id">
                                                <input type="hidden" name="poskod_id" id="poskod_id">
                                                <input type="hidden" name="kariah_id" id="kariah_id">
                                                <div class="mb-4">
                                                    <label
                                                        class="block text-xs font-medium text-gray-600 mb-1">Poskod</label>

                                                    <div class="relative w-full">
                                                        <input type="text" name="poskod" id="poskod"
                                                            class="w-full border border-gray-300 rounded px-3 py-3 text-sm focus:ring-1 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                                            placeholder="Taip atau pilih poskod..." required
                                                            autocomplete="off">

                                                        <div
                                                            class="absolute inset-y-0 right-0 flex items-center px-2 pointer-events-none text-gray-400">
                                                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                                                                stroke="currentColor">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2" d="M19 9l-7 7-7-7" />
                                                            </svg>
                                                        </div>

                                                        <div id="poskod_dropdown"
                                                            class="hidden absolute left-0 z-[100] w-full mt-1 bg-white border border-gray-200 rounded-md shadow-xl overflow-hidden">
                                                            <ul id="poskod_results"
                                                                class="py-1 text-sm text-gray-700 max-h-60 overflow-y-auto">
                                                            </ul>
                                                        </div>
                                                    </div>

                                                    <p id="poskod_status" class="text-[10px] text-gray-400 mt-1">
                                                        *Jika tiada dalam senarai, sila taip poskod baru.
                                                    </p>
                                                </div>

                                                <div>
                                                    <label
                                                        class="block text-xs font-medium text-gray-600 mb-1">Bandar</label>


                                                    <select name="bandar_select" id="bandar_select"
                                                        class="w-full border border-gray-300 rounded px-3 py-3 text-sm focus:ring-1 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                                                        <option value="">-- Pilih Bandar --</option>
                                                    </select>


                                                    <input type="text" name="bandar_manual" id="bandar_manual"
                                                        class="w-full border border-gray-300 rounded px-3 py-3 text-sm mt-2 hidden"
                                                        placeholder="Masukkan bandar baru">
                                                </div>


                                            </div>



                                            <div class="grid grid-cols-2 gap-3">
                                                <div class="relative"> <label
                                                        class="block text-xs font-medium text-gray-600 mb-1">Kariah</label>

                                                    <div class="relative">
                                                        <input type="text" name="kariah" id="kariah"
                                                            class="w-full border border-gray-300 rounded px-3 py-3 text-sm focus:ring-1 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                                            placeholder="Sila pilih negeri dahulu..." required
                                                            autocomplete="off">

                                                        <div
                                                            class="absolute inset-y-0 right-0 flex items-center px-2 pointer-events-none text-gray-400">
                                                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                                                                stroke="currentColor">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2" d="M19 9l-7 7-7-7" />
                                                            </svg>
                                                        </div>
                                                    </div>

                                                    <div id="kariah_dropdown"
                                                        class="hidden absolute left-0 right-0 mt-1 bg-white border border-gray-200 rounded-md shadow-lg max-h-60 overflow-auto z-50">
                                                        <ul id="kariah_results" class="py-1 text-sm text-gray-700">
                                                        </ul>
                                                    </div>

                                                    <p id="kariah_status" class="text-[10px] text-gray-400 mt-1">*Jika
                                                        tiada dalam senarai, sila taip nama kariah baru.</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>


                                <div class="space-y-4">


                                    <div class="border border-gray-200 rounded-lg bg-white">
                                        <div class="bg-gray-50 px-4 py-3 border-b border-gray-200">
                                            <h3 class="text-sm font-semibold text-gray-700 flex items-center">
                                                <i class="fas fa-key text-gray-500 mr-2 text-xs"></i>
                                                Maklumat Akaun
                                            </h3>
                                        </div>
                                        <div class="p-4 space-y-4">
                                            <div>
                                                <label class="block text-xs font-medium text-gray-600 mb-1">E-mel</label>
                                                <input type="email" name="email" id="email" required
                                                    class="w-full border border-gray-300 rounded px-3 py-3 text-sm focus:ring-1 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                                    placeholder="nama@contoh.com">
                                                <label class="text-[10px] text-red-500">Pastikan emel rasmi kariah wujud. E-mel ini sebagai nama pengguna untuk akses masuk ke sistem.</label>
                                            </div>

                                            <div class="grid grid-cols-2 gap-3">
                                                <div>
                                                    <label class="block text-xs font-medium text-gray-600 mb-1">Kata
                                                        Laluan</label>
                                                    <input type="password" name="password" id="password" required
                                                        class="w-full border border-gray-300 rounded px-3 py-3 text-sm focus:ring-1 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                                        placeholder="Masukkan kata laluan">
                                                </div>
                                                <div>
                                                    <label class="block text-xs font-medium text-gray-600 mb-1">Sahkan
                                                        Kata Laluan</label>
                                                    <input type="password" name="password_confirmation"
                                                        id="password_confirmation" required
                                                        class="w-full border border-gray-300 rounded px-3 py-3 text-sm focus:ring-1 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                                        placeholder="Sahkan kata laluan">
                                                </div>
                                            </div>
                                        </div>
                                    </div>


                                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-3">
                                        <div class="flex items-start">
                                            <i class="fas fa-info-circle text-blue-500 mt-0.5 mr-2 text-xs"></i>
                                            <div>
                                                <h4 class="font-medium text-blue-800 text-xs mb-1">Maklumat Penting
                                                </h4>
                                                <p class="text-blue-700 text-xs">Pastikan semua maklumat yang
                                                    dimasukkan adalah tepat dan lengkap sebelum meneruskan.</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>


                            <div class="flex justify-center mt-6 pt-4 border-t border-gray-200">
                                <button type="button" onclick="nextTab(2)"
                                    class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2.5 px-6 rounded-lg shadow-sm hover:shadow transition-all duration-200 text-sm">
                                    Seterusnya ke Dokumen
                                </button>
                            </div>
                        </div>


                        <div id="tab2" class="hidden p-4">
                            @include('register.docs')
                            <div class="flex justify-between mt-10">
                                <button type="button" onclick="prevTab(1)"
                                    class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-3 px-8 rounded-xl transition-all duration-300 flex items-center group">
                                    <i
                                        class="fas fa-arrow-left mr-3 group-hover:-translate-x-1 transition-transform duration-300"></i>
                                    Kembali
                                </button>
                                <button type="submit"
                                    class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2.5 px-6 rounded-lg shadow-sm hover:shadow transition-all duration-200 text-sm">
                                    Hantar Pendaftaran
                                </button>
                            </div>
                        </div>

                        <!-- Tab 3 - NEW PACKAGE SELECTION TAB -->
                        <div id="tab3" class="hidden p-4">
                            <div class="text-center mb-6">
                                <h2 class="text-2xl font-bold text-gray-800 mb-1">Pilih Pakej</h2>
                                <div class="w-16 h-1 bg-gray-400 mx-auto rounded-full mb-2"></div>
                                <p class="text-gray-600 text-sm">Sila pilih pakej yang sesuai untuk institusi anda</p>
                            </div>

                            <!-- Package Options -->
                            <div class="grid md:grid-cols-3 gap-4 max-w-4xl mx-auto">
                                <!-- Package 1 - Basic -->
                                <div class="border border-gray-200 rounded-xl p-5 hover:shadow-md transition-all duration-300 cursor-pointer package-option"
                                    onclick="selectPackage(1)" id="package1">
                                    <div class="text-center">
                                        <div
                                            class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-3">
                                            <i class="fas fa-box text-blue-600 text-2xl"></i>
                                        </div>
                                        <h3 class="text-lg font-bold text-gray-800 mb-1">Pakej Basic</h3>
                                        <p class="text-gray-500 text-xs mb-3">Sesuai untuk institusi kecil</p>
                                        <div class="text-2xl font-bold text-blue-600 mb-2">RM 99<span
                                                class="text-sm font-normal text-gray-500">/tahun</span></div>
                                        <ul class="text-left text-xs space-y-2 mb-4">
                                            <li class="flex items-start">
                                                <i class="fas fa-check text-green-500 mt-0.5 mr-2 text-xs"></i>
                                                <span>Pendaftaran 1 institusi</span>
                                            </li>
                                            <li class="flex items-start">
                                                <i class="fas fa-check text-green-500 mt-0.5 mr-2 text-xs"></i>
                                                <span>Pengurusan ahli asas</span>
                                            </li>
                                            <li class="flex items-start">
                                                <i class="fas fa-check text-green-500 mt-0.5 mr-2 text-xs"></i>
                                                <span>Laporan bulanan</span>
                                            </li>
                                        </ul>
                                        <div class="flex justify-center">
                                            <div class="w-5 h-5 rounded-full border-2 border-gray-300 flex items-center justify-center package-radio"
                                                id="package1-radio"></div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Package 2 - Standard -->
                                <div class="border border-gray-200 rounded-xl p-5 hover:shadow-md transition-all duration-300 cursor-pointer package-option"
                                    onclick="selectPackage(2)" id="package2">
                                    <div class="text-center relative">
                                        <div
                                            class="absolute -top-3 right-0 bg-green-500 text-white text-xs px-2 py-1 rounded-full">
                                            Popular</div>
                                        <div
                                            class="w-16 h-16 bg-purple-100 rounded-full flex items-center justify-center mx-auto mb-3">
                                            <i class="fas fa-crown text-purple-600 text-2xl"></i>
                                        </div>
                                        <h3 class="text-lg font-bold text-gray-800 mb-1">Pakej Standard</h3>
                                        <p class="text-gray-500 text-xs mb-3">Sesuai untuk institusi sederhana</p>
                                        <div class="text-2xl font-bold text-purple-600 mb-2">RM 199<span
                                                class="text-sm font-normal text-gray-500">/tahun</span></div>
                                        <ul class="text-left text-xs space-y-2 mb-4">
                                            <li class="flex items-start">
                                                <i class="fas fa-check text-green-500 mt-0.5 mr-2 text-xs"></i>
                                                <span>Semua ciri Basic</span>
                                            </li>
                                            <li class="flex items-start">
                                                <i class="fas fa-check text-green-500 mt-0.5 mr-2 text-xs"></i>
                                                <span>Pengurusan kewangan</span>
                                            </li>
                                            <li class="flex items-start">
                                                <i class="fas fa-check text-green-500 mt-0.5 mr-2 text-xs"></i>
                                                <span>E-mel sokongan 24/7</span>
                                            </li>
                                        </ul>
                                        <div class="flex justify-center">
                                            <div class="w-5 h-5 rounded-full border-2 border-gray-300 flex items-center justify-center package-radio"
                                                id="package2-radio"></div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Package 3 - Premium -->
                                <div class="border border-gray-200 rounded-xl p-5 hover:shadow-md transition-all duration-300 cursor-pointer package-option"
                                    onclick="selectPackage(3)" id="package3">
                                    <div class="text-center">
                                        <div
                                            class="w-16 h-16 bg-yellow-100 rounded-full flex items-center justify-center mx-auto mb-3">
                                            <i class="fas fa-gem text-yellow-600 text-2xl"></i>
                                        </div>
                                        <h3 class="text-lg font-bold text-gray-800 mb-1">Pakej Premium</h3>
                                        <p class="text-gray-500 text-xs mb-3">Untuk institusi besar</p>
                                        <div class="text-2xl font-bold text-yellow-600 mb-2">RM 299<span
                                                class="text-sm font-normal text-gray-500">/tahun</span></div>
                                        <ul class="text-left text-xs space-y-2 mb-4">
                                            <li class="flex items-start">
                                                <i class="fas fa-check text-green-500 mt-0.5 mr-2 text-xs"></i>
                                                <span>Semua ciri Standard</span>
                                            </li>
                                            <li class="flex items-start">
                                                <i class="fas fa-check text-green-500 mt-0.5 mr-2 text-xs"></i>
                                                <span>API akses</span>
                                            </li>
                                            <li class="flex items-start">
                                                <i class="fas fa-check text-green-500 mt-0.5 mr-2 text-xs"></i>
                                                <span>Latihan percuma 2 sesi</span>
                                            </li>
                                        </ul>
                                        <div class="flex justify-center">
                                            <div class="w-5 h-5 rounded-full border-2 border-gray-300 flex items-center justify-center package-radio"
                                                id="package3-radio"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Hidden input for selected package -->
                            <input type="hidden" name="package_id" id="selected_package" value="">

                            <!-- Navigation Buttons -->
                            <div class="flex justify-between mt-10">
                                <button type="button" onclick="prevTab(2)"
                                    class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-3 px-8 rounded-xl transition-all duration-300 flex items-center group">
                                    <i
                                        class="fas fa-arrow-left mr-3 group-hover:-translate-x-1 transition-transform duration-300"></i>
                                    Kembali ke Dokumen
                                </button>
                                <button type="button" onclick="nextTab(4)"
                                    class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2.5 px-6 rounded-lg shadow-sm hover:shadow transition-all duration-200 text-sm">
                                    Seterusnya ke Pembayaran
                                    <i
                                        class="fas fa-arrow-right ml-3 group-hover:translate-x-1 transition-transform duration-300"></i>
                                </button>
                            </div>
                        </div>

                        <!-- Tab 4 - Pembayaran -->
                        <div id="tab4" class="hidden p-4">
                            <div class="text-center mb-6">
                                <h2 class="text-2xl font-bold text-gray-800 mb-1">Pembayaran</h2>
                                <div class="w-16 h-1 bg-gray-400 mx-auto rounded-full mb-2"></div>
                                <p class="text-gray-600 text-sm">Sila lengkapkan maklumat pembayaran</p>
                            </div>

                            <!-- Dynamic Package Information Display -->
                            <div id="payment-amount" class="max-w-2xl mx-auto mb-6">
                                <!-- This will be dynamically updated by JavaScript -->
                                <div class="bg-gray-50 p-4 rounded-lg border border-gray-200 text-center">
                                    <p class="text-gray-500">Sila pilih pakej terlebih dahulu</p>
                                </div>
                            </div>

                            @include('register.pay')

                            <div class="flex justify-between mt-12">
                                <button type="button" onclick="prevTab(3)"
                                    class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-4 px-10 rounded-2xl shadow-md hover:shadow-lg transition-all duration-300 flex items-center group">
                                    <i
                                        class="fas fa-arrow-left mr-3 group-hover:-translate-x-1 transition-transform duration-300"></i>
                                    Kembali ke Pakej
                                </button>
                                <button type="submit"
                                    class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2.5 px-6 rounded-lg shadow-sm hover:shadow transition-all duration-200 text-sm">
                                    Hantar Pendaftaran
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        let currentTab = 1;

        // SweetAlert2 untuk session messages
        document.addEventListener('DOMContentLoaded', function() {
            // ✅ Cek kalau session success/error memang dihantar
            @if (session('success'))
                setTimeout(() => {
                    Swal.fire({
                        icon: 'success',
                        title: 'Berjaya!',
                        text: "{{ trim(preg_replace('/\s+/', ' ', session('success'))) }}",
                        confirmButtonText: 'OK',
                        confirmButtonColor: '#f59e0b',
                        allowOutsideClick: false,
                        allowEscapeKey: false
                    }).then(() => {
                        // Redirect ke public selepas OK ditekan
                        window.location.href = "{{ url('/checkstatus') }}";
                    });
                }, 500);
            @endif

            @if (session('error'))
                setTimeout(() => {
                    Swal.fire({
                        icon: 'error',
                        title: 'Peringatan',
                        text: "{{ trim(preg_replace('/\s+/', ' ', session('error'))) }}",
                        confirmButtonText: 'OK',
                        confirmButtonColor: '#dc2626',
                    });
                }, 500);
            @endif

            // File input handling
            const fileInputs = document.querySelectorAll('.file-input-custom');
            fileInputs.forEach(input => {
                input.addEventListener('change', function() {
                    const fileName = this.files[0] ? this.files[0].name : 'Tiada fail dipilih';
                    const fileInputId = this.getAttribute('data-file-input');
                    const fileNameElement = document.getElementById(`${fileInputId}-name`);
                    if (fileNameElement) {
                        fileNameElement.textContent = fileName;
                    }
                });
            });
        });

        function nextTab(step) {
            if (!validateStep(currentTab)) {
                return;
            }

            document.getElementById(`tab${currentTab}`).classList.add("hidden");
            document.getElementById(`tab${step}`).classList.remove("hidden");
            updateStepIndicator(step);
            currentTab = step;
            window.scrollTo({
                top: 0,
                behavior: "smooth"
            });
        }

        function prevTab(step) {
            document.getElementById(`tab${currentTab}`).classList.add("hidden");
            document.getElementById(`tab${step}`).classList.remove("hidden");

            // Update step indicators
            document.getElementById(`step${currentTab}`).classList.remove("active", "bg-white", "text-gray-800",
                "border-yellow-400");
            document.getElementById(`step${currentTab}`).classList.add("bg-white/20", "text-white", "border-white/40");

            if (currentTab > 1) {
                document.getElementById(`progressLine${currentTab-1}`).classList.remove("active", "bg-white");
                document.getElementById(`progressLine${currentTab-1}`).classList.add("bg-white/40");
            }

            currentTab = step;
            window.scrollTo({
                top: 0,
                behavior: "smooth"
            });
        }

        function updateStepIndicator(step) {
            // Reset all steps
            for (let i = 1; i <= 2; i++) {
                const stepEl = document.getElementById(`step${i}`);
                const progressEl = document.getElementById(`progressLine${i-1}`);

                if (i <= step) {
                    stepEl.classList.add("active", "bg-white", "text-gray-800", "border-yellow-400");
                    stepEl.classList.remove("bg-white/20", "text-white", "border-white/40");
                    if (progressEl) {
                        progressEl.classList.add("active", "bg-white");
                        progressEl.classList.remove("bg-white/40");
                    }
                } else {
                    stepEl.classList.remove("active", "bg-white", "text-gray-800", "border-yellow-400");
                    stepEl.classList.add("bg-white/20", "text-white", "border-white/40");
                    if (progressEl) {
                        progressEl.classList.remove("active", "bg-white");
                        progressEl.classList.add("bg-white/40");
                    }
                }
            }
        }

        function validateStep(step) {
            let isValid = true;

            if (step === 1) {
                const requiredFields = ["negeri", "nama_masjid", "type", "alamat", "poskod", "email", "password",
                    "password_confirmation", "kariah"
                ];

                requiredFields.forEach(id => {
                    const input = document.getElementById(id);
                    if (input && !input.value.trim()) {
                        isValid = false;
                        input.classList.add("border-red-500");
                    } else if (input) {
                        input.classList.remove("border-red-500");
                    }
                });

                // Validate password confirmation
                const password = document.getElementById("password").value;
                const confirmPassword = document.getElementById("password_confirmation").value;

                if (password !== confirmPassword) {
                    isValid = false;
                    Swal.fire({
                        icon: "error",
                        title: "Kata laluan tidak sama!",
                        text: "Sila pastikan kata laluan dan pengesahan sama.",
                        confirmButtonColor: "#dc2626"
                    });
                }
            }

            return isValid;
        }

        // Second DOMContentLoaded for poskod functionality
        document.addEventListener('DOMContentLoaded', function() {
            // ========== POSKOD FUNCTIONALITY ==========
            const poskodInput = document.getElementById('poskod');
            const poskodDropdown = document.getElementById('poskod_dropdown');
            const poskodResults = document.getElementById('poskod_results');
            const poskodStatus = document.getElementById('poskod_status');
            const negeriSelect = document.getElementById('negeri');
            const bandarSelect = document.getElementById('bandar_select');
            const bandarManual = document.getElementById('bandar_manual');
            const kariahInput = document.getElementById('kariah');
            const kariahDropdown = document.getElementById('kariah_dropdown');
            const kariahResults = document.getElementById('kariah_results');
            const kariahStatus = document.getElementById('kariah_status');

            let allPoskods = []; // Store all poskod objects from server
            let allKariahs = []; // Store all kariah objects from server

            // Map negeri display names to IDs (based on your database)
            function getNegeriId(negeriName) {
                const mapping = {
                    'Perlis': 8,
                    'Kedah': 2,
                    'Pulau Pinang': 9,
                    'Perak': 7,
                    'Selangor': 12,
                    'Negeri Sembilan': 5,
                    'Melaka': 4,
                    'Johor': 1,
                    'Pahang': 6,
                    'Terengganu': 13,
                    'Kelantan': 3,
                    'WP Kuala Lumpur': 15,
                    'WP Putrajaya': 14,
                    'WP Labuan': 16,
                    'Sarawak': 11,
                    'Sabah': 10
                };
                return mapping[negeriName];
            }

            // Group poskods by poskod_num
            function groupPoskodsByNumber(poskodList) {
                const grouped = {};

                poskodList.forEach(item => {
                    if (!item.poskod_num) return;

                    if (!grouped[item.poskod_num]) {
                        grouped[item.poskod_num] = {
                            poskod_num: item.poskod_num,
                            names: [],
                            negeri_ids: new Set()
                        };
                    }

                    if (item.nama) {
                        // Store both name and id
                        grouped[item.poskod_num].names.push({
                            id: item.id,
                            nama: item.nama
                        });
                    }
                    if (item.negeri_id) {
                        grouped[item.poskod_num].negeri_ids.add(item.negeri_id);
                    }
                });

                // Convert to array and sort names
                return Object.values(grouped).map(group => ({
                    poskod_num: group.poskod_num,
                    names: [...new Set(group.names.map(JSON.stringify))].map(JSON
                        .parse), // remove duplicates
                    negeri_id: group.negeri_ids.size === 1 ? Array.from(group.negeri_ids)[0] : null
                }));
            }

            // Load all poskods when page loads
            fetch('/get-poskods')
                .then(res => {
                    if (!res.ok) {
                        throw new Error('HTTP error ' + res.status);
                    }
                    return res.json();
                })
                .then(data => {
                    console.log('Raw poskod data from server:', data);
                    allPoskods = data;

                    // DEBUG: Check if IDs are present
                    if (data.length > 0) {
                        console.log('First poskod item:', data[0]);
                        console.log('Has ID field?', data[0].hasOwnProperty('id'));
                    }

                    if (data.length > 0) {
                        const groupedCount = Object.keys(groupPoskodsByNumber(data)).length;
                        poskodStatus.innerHTML =
                            `<span class="text-blue-600">Ada ${groupedCount} poskod telah didaftar. Klik untuk pilih.</span>`;
                    } else {
                        poskodStatus.innerHTML =
                            '<span class="text-red-500">⚠ Tiada poskod didaftar. Sila taip baru.</span>';
                    }
                })
                .catch(error => {
                    console.error('Error loading poskods:', error);
                    poskodStatus.innerHTML =
                        '<span class="text-red-500">⚠ Ralat memuatkan data poskod. Sila taip poskod baru.</span>';
                    allPoskods = [];
                });

            // ========== KARIAH FUNCTIONALITY ==========
            function loadKariahByPoskod(poskodId, bandarName) {

                kariahStatus.innerHTML = '<span class="text-gray-400">Memuatkan kariah...</span>';
                kariahInput.value = '';
                allKariahs = [];

                fetch(`/get-kariah/${poskodId}/${encodeURIComponent(bandarName)}`)
                    .then(res => {
                        if (!res.ok) {
                            throw new Error('HTTP error ' + res.status);
                        }
                        return res.json();
                    })
                    .then(data => {

                        allKariahs = data;

                        if (data.length > 0) {
                            kariahStatus.innerHTML =
                                `<span class="text-blue-600">✓ ${data.length} kariah tersedia.</span>`;
                            kariahInput.placeholder = 'Taip atau pilih kariah...';
                        } else {
                            kariahStatus.innerHTML =
                                '<span class="text-orange-500">⚠ Tiada kariah. Sila tambah baru.</span>';
                            kariahInput.placeholder = 'Sila taip nama kariah baru...';
                        }
                    })
                    .catch(error => {
                        console.error('Error loading kariah:', error);
                        kariahStatus.innerHTML =
                            '<span class="text-red-500">⚠ Ralat memuatkan kariah.</span>';
                    });
            }

            // Filter poskods by selected negeri
            function filterPoskodsByNegeri(negeriDisplayName) {
                if (!negeriDisplayName || allPoskods.length === 0) {
                    return allPoskods;
                }

                const negeriId = getNegeriId(negeriDisplayName);

                if (!negeriId) {
                    return allPoskods;
                }

                // Filter poskods where negeri_id matches
                const filtered = allPoskods.filter(item => item.negeri_id == negeriId);

                console.log('Filtered poskods for negeri:', negeriDisplayName, 'ID:', negeriId, 'Count:', filtered
                    .length);

                return filtered;
            }

            // Filter kariahs by poskod (bandar)
            function filterKariahsByPoskod(poskodNum) {
                if (!poskodNum || allKariahs.length === 0) {
                    return [];
                }

                // Find poskod_id from poskod_num
                const poskodEntry = allPoskods.find(p =>
                    p.poskod_num == poskodNum && p.nama == bandarName
                );

                if (!poskodEntry || !poskodEntry.id) {
                    return [];
                }

                // Filter kariahs by poskod_id
                const filtered = allKariahs.filter(kariah => kariah.poskod_id == poskodEntry.id);

                console.log('Filtered kariahs for poskod:', poskodNum, 'Count:', filtered.length);

                return filtered;
            }

            // Show dropdown for poskod
            function showPoskodDropdown(groupedList) {
                if (!groupedList || groupedList.length === 0) {
                    poskodDropdown.classList.add('hidden');
                    return;
                }

                poskodResults.innerHTML = '';

                // Show grouped poskod results
                groupedList.forEach(group => {
                    if (!group.poskod_num) return;

                    const li = document.createElement('li');
                    li.className =
                        "px-4 py-2 hover:bg-blue-50 cursor-pointer border-b border-gray-50 last:border-b-0";

                    // Create display text with poskod and first few names
                    // let displayText = `${group.poskod_num} (ID: ${group.negeri_id || '-'})`;
                    let displayText = `${group.poskod_num} `;
                    if (group.names && group.names.length > 0) {
                        const namesDisplay = group.names.slice(0, 2).map(n => n.nama).join(', ');
                        const remainingCount = group.names.length - 2;
                        // displayText += ` - ${namesDisplay}`;
                        displayText;
                        if (remainingCount > 0) {
                            displayText += ` +${remainingCount} lagi`;
                        }
                    }
                    li.textContent = displayText;

                    // Store all names as JSON string in dataset
                    li.dataset.poskod = group.poskod_num;
                    li.dataset.names = JSON.stringify(group.names);

                    li.onclick = function(e) {
                        e.stopPropagation();
                        poskodInput.value = this.dataset.poskod;
                        hidePoskodDropdown();

                        // Update bandar with all available names for this poskod
                        updateBandarFromPoskod(this.dataset.poskod, JSON.parse(this.dataset.names));

                        // Clear kariah when poskod changes
                        kariahInput.value = '';
                        kariahInput.placeholder = 'Sila pilih bandar dahulu...';

                        const selectedPoskod = allPoskods.find(p =>
                            p.poskod_num == this.dataset.poskod
                        );

                        if (selectedPoskod && selectedPoskod.id) {
                            document.getElementById('poskod_id').value = selectedPoskod.id;
                        }
                    };
                    poskodResults.appendChild(li);
                });

                // Add "Add New" option at the bottom
                const addNewLi = document.createElement('li');
                addNewLi.className =
                    "px-4 py-2 bg-gray-50 hover:bg-yellow-50 cursor-pointer border-t border-gray-200 text-blue-600 font-medium italic text-xs flex items-center justify-between";
                addNewLi.innerHTML = `
                <span>Jika tiada dalam senarai, sila taip baru...</span>
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
            `;

                addNewLi.onclick = function(e) {
                    e.stopPropagation();
                    poskodInput.focus();
                    hidePoskodDropdown();
                };

                poskodResults.appendChild(addNewLi);

                poskodDropdown.classList.remove('hidden');
            }

            // Show dropdown for kariah
            function showKariahDropdown(kariahList) {
                if (!kariahList || kariahList.length === 0) {
                    kariahDropdown.classList.add('hidden');
                    return;
                }

                kariahResults.innerHTML = '';

                // Show kariah results
                kariahList.forEach(kariah => {
                    if (!kariah.nama) return;

                    const li = document.createElement('li');
                    li.className =
                        "px-4 py-2 hover:bg-blue-50 cursor-pointer border-b border-gray-50 last:border-b-0";

                    // li.textContent = `${kariah.nama} (ID: ${kariah.id})`;
                    li.textContent = `${kariah.nama}`;
                    li.dataset.kariahId = kariah.id;
                    li.dataset.kariahName = kariah.nama;

                    li.onclick = function(e) {
                        e.stopPropagation();
                        kariahInput.value = this.dataset.kariahName;
                        document.getElementById('kariah_id').value = this.dataset.kariahId;
                        hideKariahDropdown();
                        kariahInput.placeholder = '';

                    };
                    kariahResults.appendChild(li);
                });

                // Add "Add New" option at the bottom
                const addNewLi = document.createElement('li');
                addNewLi.className =
                    "px-4 py-2 bg-gray-50 hover:bg-yellow-50 cursor-pointer border-t border-gray-200 text-blue-600 font-medium italic text-xs flex items-center justify-between";
                addNewLi.innerHTML = `
                <span>Jika tiada dalam senarai, sila taip baru...</span>
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
            `;

                addNewLi.onclick = function(e) {
                    e.stopPropagation();
                    kariahInput.focus();
                    hideKariahDropdown();
                };

                kariahResults.appendChild(addNewLi);

                kariahDropdown.classList.remove('hidden');
            }

            // Hide dropdown functions
            function hidePoskodDropdown() {
                setTimeout(() => {
                    poskodDropdown.classList.add('hidden');
                }, 150);
            }

            function hideKariahDropdown() {
                setTimeout(() => {
                    kariahDropdown.classList.add('hidden');
                }, 150);
            }

            // Function to update bandar with multiple options
            function updateBandarFromPoskod(poskod, bandarNames) {
                bandarSelect.innerHTML = '<option value="">-- Pilih Bandar --</option>';

                if (bandarNames && bandarNames.length > 0) {
                    // Show available bandar from database
                    bandarNames.forEach(nameObj => {
                        if (!nameObj) return;

                        let bandarName = nameObj.nama;
                        let bandarId = nameObj.id || '-';

                        let option = document.createElement('option');
                        option.value = bandarName;
                        // option.textContent = `${bandarName} (ID: ${bandarId})`;
                        option.textContent = `${bandarName}`;
                        option.dataset.bandarId = bandarId;
                        bandarSelect.appendChild(option);
                    });

                    // Add "Lain-lain" option at the end
                    let otherOption = document.createElement('option');
                    otherOption.value = "manual";
                    otherOption.textContent = "Lain-lain (Tambah Baru)";
                    bandarSelect.appendChild(otherOption);

                    // Set default selection to the first bandar
                    bandarSelect.value = bandarNames[0].nama;
                    bandarManual.classList.add('hidden');
                    bandarManual.required = false;
                    bandarManual.value = '';

                    // Trigger change event to load kariah for selected bandar
                    bandarSelect.dispatchEvent(new Event('change'));

                } else {
                    // If no data, automatically select "Lain-lain (Tambah Baru)"
                    let otherOption = document.createElement('option');
                    otherOption.value = "manual";
                    otherOption.textContent = "Lain-lain (Tambah Baru)";
                    bandarSelect.appendChild(otherOption);

                    // Auto-select the manual option
                    bandarSelect.value = "manual";

                    // Show manual input field
                    bandarManual.classList.remove('hidden');
                    bandarManual.required = true;
                    bandarManual.focus();

                    // Clear kariah input
                    kariahInput.value = '';
                    kariahInput.placeholder = 'Sila masukkan nama bandar dahulu...';
                }
            }

            // Show poskod dropdown when input is focused
            poskodInput.addEventListener('focus', function() {
                if (allPoskods.length === 0) {
                    return; // No data to show
                }

                let poskodsToShow = allPoskods;

                // If negeri is selected, filter by it
                if (negeriSelect.value) {
                    poskodsToShow = filterPoskodsByNegeri(negeriSelect.value);
                }

                if (poskodsToShow.length > 0) {
                    // Group the poskods before showing
                    const groupedPoskods = groupPoskodsByNumber(poskodsToShow);
                    showPoskodDropdown(groupedPoskods);
                }
            });

            // Filter list as user types
            poskodInput.addEventListener('input', function() {
                if (allPoskods.length === 0) {
                    return;
                }

                const val = this.value.toLowerCase();

                let poskodsToShow = allPoskods;

                // If negeri is selected, filter by it
                if (negeriSelect.value) {
                    poskodsToShow = filterPoskodsByNegeri(negeriSelect.value);
                }

                // Group first, then filter
                const groupedPoskods = groupPoskodsByNumber(poskodsToShow);

                const filtered = groupedPoskods.filter(item =>
                    item.poskod_num && item.poskod_num.toLowerCase().includes(val)
                );
                showPoskodDropdown(filtered);

                if (filtered.length === 0 && val.length >= 4) {

                    // Reset bandar dropdown
                    bandarSelect.innerHTML = '<option value="">-- Pilih Bandar --</option>';

                    // Add manual option
                    let otherOption = document.createElement('option');
                    otherOption.value = "manual";
                    otherOption.textContent = "Lain-lain (Tambah Baru)";
                    bandarSelect.appendChild(otherOption);

                    // Auto select manual
                    bandarSelect.value = "manual";
                    bandarManual.classList.remove('hidden');
                    bandarManual.required = true;

                    // Reset kariah
                    kariahInput.value = '';
                    kariahInput.placeholder = 'Sila masukkan nama bandar dahulu...';
                }
            });

            // Update when negeri changes
            negeriSelect.addEventListener('change', function() {
                document.getElementById('negeri_id').value = getNegeriId(this.value) || '';
                // Clear poskod input when negeri changes
                poskodInput.value = '';

                // Clear bandar select
                bandarSelect.innerHTML = '<option value="">-- Pilih Bandar --</option>';

                // Clear kariah input
                kariahInput.value = '';
                kariahInput.placeholder = 'Sila pilih negeri dan bandar dahulu...';

                // Update status message
                if (allPoskods.length > 0 && this.value) {
                    const filteredPoskods = filterPoskodsByNegeri(this.value);
                    if (filteredPoskods.length > 0) {
                        const groupedCount = Object.keys(groupPoskodsByNumber(filteredPoskods)).length;
                        poskodStatus.innerHTML =
                            `<span class="text-blue-600">✓ ${groupedCount} poskod untuk ${this.value} tersedia.</span>`;

                        // Show grouped poskods in dropdown immediately when negeri is selected
                        const groupedPoskods = groupPoskodsByNumber(filteredPoskods);
                        showPoskodDropdown(groupedPoskods);
                    } else {
                        poskodStatus.innerHTML =
                            `<span class="text-orange-500">⚠ Tiada poskod untuk ${this.value}. Sila taip baru.</span>`;
                    }
                } else if (this.value) {
                    poskodStatus.innerHTML =
                        '<span class="text-orange-500">⚠ Tiada data poskod. Sila taip baru.</span>';
                } else {
                    poskodStatus.innerHTML =
                        '<span class="text-gray-400">*Jika tiada dalam senarai, sila taip poskod baru.</span>';
                }
            });

            // ========== BANDAR SELECT CHANGE - TRIGGER KARIAH UPDATE ==========
            bandarSelect.addEventListener('change', function() {
                if (this.value === "manual") {
                    bandarManual.classList.remove('hidden');
                    bandarManual.required = true;
                    bandarManual.focus();
                    kariahInput.value = '';
                    kariahInput.placeholder = 'Sila masukkan nama bandar dahulu...';
                    hideKariahDropdown();
                } else {
                    bandarManual.classList.add('hidden');
                    bandarManual.required = false;
                    bandarManual.value = '';

                    const selectedBandar = this.value;
                    if (selectedBandar && poskodInput.value) {
                        // Find correct poskod entry
                        const selectedPoskod = allPoskods.find(p =>
                            p.poskod_num == poskodInput.value &&
                            p.nama == selectedBandar
                        );

                        if (selectedPoskod && selectedPoskod.id) {
                            document.getElementById('poskod_id').value = selectedPoskod.id;
                        }

                        // Update kariah
                        updateKariahFromBandar(poskodInput.value, selectedBandar);
                    } else {
                        kariahInput.value = '';
                        kariahInput.placeholder = 'Sila pilih bandar dahulu...';
                    }
                }
            });

            // Function to update kariah based on selected bandar
            function updateKariahFromBandar(poskodNum, bandarName) {
                if (!poskodNum || !bandarName) return;

                const poskodEntry = allPoskods.find(p =>
                    p.poskod_num == poskodNum && p.nama == bandarName
                );

                if (!poskodEntry || !poskodEntry.id) return;

                loadKariahByPoskod(poskodEntry.id, bandarName);
            }

            // Kariah input filter
            kariahInput.addEventListener('input', function() {
                const val = this.value.toLowerCase();

                if (!bandarSelect.value || bandarSelect.value === "manual") {
                    return;
                }

                if (!poskodInput.value) {
                    return;
                }

                // Find poskod_id from poskod_num
                const poskodEntry = allPoskods.find(p => p.poskod_num == poskodInput.value);

                if (!poskodEntry || !poskodEntry.id) {
                    return;
                }

                // Filter kariahs by poskod_id and name
                const kariahsForPoskod = allKariahs.filter(kariah =>
                    kariah.poskod_id == poskodEntry.id &&
                    kariah.nama && kariah.nama.toLowerCase().includes(val)
                );

                if (kariahsForPoskod.length > 0) {
                    showKariahDropdown(kariahsForPoskod);
                } else {
                    // If no matches but we have input, show empty dropdown
                    if (val.length > 0) {
                        // Still show the "Add New" option
                        const emptyList = [];
                        showKariahDropdown(emptyList);
                    } else {
                        hideKariahDropdown();
                    }
                }
            });

            kariahInput.addEventListener('focus', function() {

                if (!bandarSelect.value || bandarSelect.value === "manual") return;

                if (allKariahs.length === 0) return;

                showKariahDropdown(allKariahs);
            });

            // Close dropdowns when clicking outside
            document.addEventListener('click', function(e) {
                if (!poskodInput.contains(e.target) && !poskodDropdown.contains(e.target)) {
                    poskodDropdown.classList.add('hidden');
                }
                if (!kariahInput.contains(e.target) && !kariahDropdown.contains(e.target)) {
                    kariahDropdown.classList.add('hidden');
                }
            });

            // Handle key events
            poskodInput.addEventListener('keydown', function(e) {
                if (e.key === 'Escape') {
                    hidePoskodDropdown();
                }
            });

            kariahInput.addEventListener('keydown', function(e) {
                if (e.key === 'Escape') {
                    hideKariahDropdown();
                }
            });
        });
    </script>
    <script>
    // ========== TEXT FIELD FORMATTING ==========
    document.addEventListener('DOMContentLoaded', function () {

        // NAMA MASJID/SURAU - Auto Uppercase
        const namaMasjid = document.getElementById('nama_masjid');
        if (namaMasjid) {
            namaMasjid.addEventListener('input', function () {
                const pos = this.selectionStart;
                this.value = this.value.toUpperCase();
                this.setSelectionRange(pos, pos);
            });
        }

        // ALAMAT - Auto Uppercase
        const alamat = document.getElementById('alamat');
        if (alamat) {
            alamat.addEventListener('input', function () {
                const pos = this.selectionStart;
                this.value = this.value.toUpperCase();
                this.setSelectionRange(pos, pos);
            });
        }

        // EMAIL - Auto Lowercase
        const email = document.getElementById('email');
        if (email) {
            email.addEventListener('input', function () {
                const pos = this.selectionStart;
                this.value = this.value.toLowerCase();
                this.setSelectionRange(pos, pos);
            });
        }

        // IC - Auto format with dash: 010413-03-0347
        const icInput = document.getElementById('ic');
        if (icInput) {
            icInput.addEventListener('input', function () {
                // Remove all non-digits
                let digits = this.value.replace(/\D/g, '');

                // Limit to 12 digits
                digits = digits.substring(0, 12);

                // Apply dash formatting: XXXXXX-XX-XXXX
                let formatted = '';
                if (digits.length <= 6) {
                    formatted = digits;
                } else if (digits.length <= 8) {
                    formatted = digits.substring(0, 6) + '-' + digits.substring(6);
                } else {
                    formatted = digits.substring(0, 6) + '-' + digits.substring(6, 8) + '-' + digits.substring(8);
                }

                this.value = formatted;
            });

            // Prevent non-numeric keys (allow backspace, delete, arrows, tab)
            icInput.addEventListener('keydown', function (e) {
                const allowedKeys = ['Backspace', 'Delete', 'ArrowLeft', 'ArrowRight', 'Tab', 'Home', 'End'];
                if (!allowedKeys.includes(e.key) && !/^\d$/.test(e.key)) {
                    e.preventDefault();
                }
            });
        }

        // NAMA PENDAFTAR - Auto Uppercase (if exists in docs tab)
        // Covers any input with name="nama_pendaftar" or id="nama_pendaftar"
        const namaPendaftar = document.getElementById('nama_pendaftar');
        if (namaPendaftar) {
            namaPendaftar.addEventListener('input', function () {
                const pos = this.selectionStart;
                this.value = this.value.toUpperCase();
                this.setSelectionRange(pos, pos);
            });
        }

        
        document.querySelectorAll('input[name="nama_pendaftar"]').forEach(function (el) {
            el.addEventListener('input', function () {
                const pos = this.selectionStart;
                this.value = this.value.toUpperCase();
                this.setSelectionRange(pos, pos);
            });
        });

    });
</script>
</body>

</html>
