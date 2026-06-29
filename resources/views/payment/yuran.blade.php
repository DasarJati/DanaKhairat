@extends ('layouts.app')

@section('content')

<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bayaran Yuran - Sistem Pengurusan Surau</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        .shadow-custom {
            box-shadow: 0 10px 25px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
        }
    </style>
</head>
<body class="bg-gray-50 min-h-screen">
    <!-- Main Content -->
    <main class="container mx-auto px-4 py-8">
        <div class="max-w-6xl mx-auto">
            <!-- Page Title -->
            <div class="text-center mb-10">
                <h2 class="text-3xl font-bold text-gray-800 mb-3">Bayaran Yuran Bulanan</h2>
                <div class="w-24 h-1 bg-yellow-500 mx-auto rounded-full mb-4"></div>
                <p class="text-gray-600">Sila lengkapkan pembayaran yuran untuk meneruskan akses penuh sistem</p>
            </div>

            <!-- Payment Content -->
            <div class="grid lg:grid-cols-3 gap-8">
                <!-- Left Column - Yuran Cards -->
                <div class="lg:col-span-2 space-y-6">
                    <!-- Yuran Sistem -->
                    <div class="bg-white rounded-2xl shadow-custom p-6 border-l-4 border-yellow-500">
                        <div class="flex items-center mb-4">
                            <div class="bg-yellow-100 p-3 rounded-full mr-4">
                                <i class="fas fa-calculator text-yellow-600 text-xl"></i>
                            </div>
                            <h3 class="text-xl font-bold text-gray-800">Yuran Sistem Pengurusan</h3>
                        </div>
                        
                        <div class="space-y-4">
                            <div class="flex justify-between items-center pb-3 border-b border-gray-200">
                                <span class="text-gray-700">Yuran Bulanan:</span>
                                <span class="font-medium text-gray-900">RM159.00</span>
                            </div>
                            <div class="flex justify-between items-center pb-3 border-b border-gray-200">
                                <span class="text-gray-700">Tempoh Bayaran:</span>
                            </div>
                            <div class="flex justify-between items-center pt-2">
                                <span class="text-gray-700 font-bold text-lg">Jumlah Tahunan:</span>
                                <span class="text-2xl font-bold text-yellow-600">RM1,908.00</span>
                            </div>
                        </div>
                        
                        <div class="mt-6 bg-yellow-50 rounded-xl p-4 border border-yellow-200">
                            <div class="flex items-start">
                                <i class="fas fa-exclamation-circle text-yellow-500 mt-1 mr-3"></i>
                                <div>
                                    <p class="text-yellow-800 font-medium">Tarikh terakhir penjelasan yuran urusan sistem adalah pada 1/4/2026</p>
                                    <p class="text-yellow-700 text-sm mt-1">Sila bayar yuran tahunan untuk mendapatkan akses penuh kepada sistem pengurusan.</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Yuran Wakalah -->
                    <div class="bg-white rounded-2xl shadow-custom p-6 border-l-4 border-yellow-500">
                        <div class="flex items-center mb-4">
                            <div class="bg-yellow-100 p-3 rounded-full mr-4">
                                <i class="fas fa-hand-holding-usd text-yellow-600 text-xl"></i>
                            </div>
                            <h3 class="text-xl font-bold text-gray-800">Yuran Wakalah (10 Hari)</h3>
                        </div>
                        
                        <div class="space-y-4">
                            <div class="flex justify-between items-center">
                                <span class="text-gray-700">Bilangan Ahli:</span>
                                <div class="flex items-center">
                                    <span id="member-count" class="mx-4 font-bold text-lg">200</span>     
                                </div>
                            </div>
                            <div class="flex justify-between items-center pb-3 border-b border-gray-200">
                                <span class="text-gray-700">Kadar Wakalah:</span>
                                <span class="font-medium text-gray-900">RM10.00 / Ahli</span>
                            </div>
                            <div class="flex justify-between items-center pt-2">
                                <span class="text-gray-700 font-bold text-lg">Jumlah Wakalah:</span>
                                <span id="wakalah-total" class="text-2xl font-bold text-yellow-600">RM2000.00</span>
                            </div>  
                        </div>
                        
                        <div class="mt-6 bg-gray-50 rounded-xl p-4 border border-gray-200">
                            <div class="flex items-start">
                                <i class="fas fa-info-circle text-gray-500 mt-1 mr-3"></i>
                                <div>
                                    <p class="text-gray-800 font-medium">Bayaran Setiap 10 Hari</p>
                                    <p class="text-gray-700 text-sm mt-1">Yuran ini perlu dibayar setiap 10 hari berdasarkan bilangan ahli semasa.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right Column - Payment Instructions -->
                <div class="lg:col-span-1">
                    <div class="bg-white rounded-2xl shadow-custom p-6 sticky top-6">
                        <h3 class="text-xl font-bold text-gray-800 mb-6 flex items-center">
                            <i class="fas fa-university text-yellow-500 mr-3"></i>
                            Maklumat Pembayaran
                        </h3>
                        
                        <!-- Bank Details -->
                        <div class="mb-8">
                            <h4 class="font-bold text-gray-700 mb-4">Butiran Bank Syarikat</h4>
                            <div class="space-y-4">
                                <div class="flex justify-between items-center pb-3 border-b border-gray-200">
                                    <span class="text-gray-600">Nama Bank:</span>
                                    <span class="font-medium text-gray-900">Bank Islam Malaysia Berhad</span>
                                </div>
                                <div class="flex justify-between items-center pb-3 border-b border-gray-200">
                                    <span class="text-gray-600">Nama Akaun:</span>
                                    <span class="font-medium text-gray-900">DJati Sdn Bhd</span>
                                </div>
                                <div class="flex justify-between items-center pb-3 border-b border-gray-200">
                                    <span class="text-gray-600">Nombor Akaun:</span>
                                    <span class="font-medium text-gray-900">1208 3012 3456 7890</span>
                                </div>
                            </div>
                        </div>
                 

                        <!-- Payment Steps -->
                        <div class="pt-6 border-t border-gray-200">
                            <h4 class="font-bold text-gray-700 mb-4">Langkah Pembayaran</h4>
                            <div class="space-y-4">
                                <div class="flex items-start">
                                    <div class="bg-yellow-100 w-8 h-8 rounded-full flex items-center justify-center mr-3 flex-shrink-0">
                                        <span class="text-yellow-700 font-bold text-sm">1</span>
                                    </div>
                                    <p>Bank-in jumlah yuran ke akaun syarikat</p>
                                </div>
                                <div class="flex items-start">
                                    <div class="bg-yellow-100 w-8 h-8 rounded-full flex items-center justify-center mr-3 flex-shrink-0">
                                        <span class="text-yellow-700 font-bold text-sm">2</span>
                                    </div>
                                    <p>Ambil gambar atau screenshot bukti bayaran</p>
                                </div>
                                <div class="flex items-start">
                                    <div class="bg-yellow-100 w-8 h-8 rounded-full flex items-center justify-center mr-3 flex-shrink-0">
                                        <span class="text-yellow-700 font-bold text-sm">3</span>
                                    </div>
                                    <span>Hantar bukti bayaran melalui WhatsApp: <span class="font-semibold">012-345 6789</span></span>
                                </div>
                                <div class="flex items-start">
                                    <div class="bg-yellow-100 w-8 h-8 rounded-full flex items-center justify-center mr-3 flex-shrink-0">
                                        <span class="text-yellow-700 font-bold text-sm">4</span>
                                    </div>
                                    <p>Tunggu pengesahan dan Akses penuh akan diaktifkan dalam 24 jam</p>
                                </div>
                            </div>
                        </div>

                        <!-- Contact Info -->
                        <div class="mt-8 bg-yellow-50 rounded-xl p-4 border border-yellow-200">
                            <h5 class="font-bold text-gray-700 mb-2">Bantuan & Sokongan</h5>
                            <div class="space-y-2 text-sm text-gray-600">
                                <div class="flex items-center">
                                    <i class="fas fa-phone-alt text-yellow-500 mr-2 w-4"></i>
                                    <span>03-1234 5678 (Pejabat)</span>
                                </div>
                                <div class="flex items-center">
                                    <i class="fab fa-whatsapp text-yellow-500 mr-2 w-4"></i>
                                    <span>012-345 6789 (WhatsApp)</span>
                                </div>
                                <div class="flex items-center">
                                    <i class="fas fa-envelope text-yellow-500 mr-2 w-4"></i>
                                    <span>support@surausystem.com</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>


</body>
</html>

@endsection