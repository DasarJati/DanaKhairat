@extends('layouts.app')

@section('title', 'Dashboard - e-Khairat')

@section('content')


    <!-- Main Content -->
    <div class="flex-1 p-8 bg-gray-50 min-h-screen">
        <!-- Header -->
        <div class="flex justify-between items-center mb-8">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">ID Pengguna</h1>
                <p class="text-gray-600 mt-2">Senarai pengguna sistem e-Khairat</p>
            </div>
            <button class="bg-yellow-500 hover:bg-yellow-600 text-white px-6 py-3 rounded-xl font-semibold flex items-center transition-colors">
                <i class="fas fa-plus mr-2"></i>Tambah Pengguna
            </button>
        </div>

        <!-- User Cards Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
            <!-- User Card 1 -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 hover:shadow-md transition-all duration-300">
                <div class="flex items-center space-x-4 mb-4">
                    <div class="w-16 h-16 bg-gradient-to-r from-yellow-500 to-yellow-600 rounded-full flex items-center justify-center text-white font-bold text-xl">
                        AM
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900">Ahmad bin Mahmud</h3>
                        <p class="text-yellow-600 font-medium">Ketua Khairat Kematian</p>
                    </div>
                </div>
                <div class="space-y-2 text-sm text-gray-600">
                    <div class="flex items-center">
                        <i class="fas fa-envelope mr-3 w-5"></i>
                        <span>ahmad.mahmud@ekhairat.com</span>
                    </div>
                    <div class="flex items-center">
                        <i class="fas fa-phone mr-3 w-5"></i>
                        <span>012-345 6789</span>
                    </div>
                    <div class="flex items-center">
                        <i class="fas fa-calendar-alt mr-3 w-5"></i>
                        <span>Didaftarkan: 15 Jan 2024</span>
                    </div>
                </div>
                <div class="mt-4 pt-4 border-t border-gray-100 flex justify-between">
                    <span class="inline-flex items-center px-3 py-1 bg-green-100 text-green-800 rounded-full text-xs font-medium">
                        <i class="fas fa-circle mr-1 text-xs"></i>Aktif
                    </span>
                    <button class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                        Edit Profil
                    </button>
                </div>
            </div>

            <!-- User Card 2 -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 hover:shadow-md transition-all duration-300">
                <div class="flex items-center space-x-4 mb-4">
                    <div class="w-16 h-16 bg-gradient-to-r from-blue-500 to-blue-600 rounded-full flex items-center justify-center text-white font-bold text-xl">
                        SA
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900">Siti Aishah</h3>
                        <p class="text-blue-600 font-medium">AJK Kewangan</p>
                    </div>
                </div>
                <div class="space-y-2 text-sm text-gray-600">
                    <div class="flex items-center">
                        <i class="fas fa-envelope mr-3 w-5"></i>
                        <span>siti.aishah@ekhairat.com</span>
                    </div>
                    <div class="flex items-center">
                        <i class="fas fa-phone mr-3 w-5"></i>
                        <span>013-987 6543</span>
                    </div>
                    <div class="flex items-center">
                        <i class="fas fa-calendar-alt mr-3 w-5"></i>
                        <span>Didaftarkan: 20 Feb 2024</span>
                    </div>
                </div>
                <div class="mt-4 pt-4 border-t border-gray-100 flex justify-between">
                    <span class="inline-flex items-center px-3 py-1 bg-green-100 text-green-800 rounded-full text-xs font-medium">
                        <i class="fas fa-circle mr-1 text-xs"></i>Aktif
                    </span>
                    <button class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                        Edit Profil
                    </button>
                </div>
            </div>

            <!-- User Card 3 -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 hover:shadow-md transition-all duration-300">
                <div class="flex items-center space-x-4 mb-4">
                    <div class="w-16 h-16 bg-gradient-to-r from-green-500 to-green-600 rounded-full flex items-center justify-center text-white font-bold text-xl">
                        MR
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900">Mohd Rizal</h3>
                        <p class="text-green-600 font-medium">AJK Kebajikan</p>
                    </div>
                </div>
                <div class="space-y-2 text-sm text-gray-600">
                    <div class="flex items-center">
                        <i class="fas fa-envelope mr-3 w-5"></i>
                        <span>mohd.rizal@ekhairat.com</span>
                    </div>
                    <div class="flex items-center">
                        <i class="fas fa-phone mr-3 w-5"></i>
                        <span>011-223 3445</span>
                    </div>
                    <div class="flex items-center">
                        <i class="fas fa-calendar-alt mr-3 w-5"></i>
                        <span>Didaftarkan: 5 Mac 2024</span>
                    </div>
                </div>
                <div class="mt-4 pt-4 border-t border-gray-100 flex justify-between">
                    <span class="inline-flex items-center px-3 py-1 bg-green-100 text-green-800 rounded-full text-xs font-medium">
                        <i class="fas fa-circle mr-1 text-xs"></i>Aktif
                    </span>
                    <button class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                        Edit Profil
                    </button>
                </div>
            </div>
        </div>

        <!-- Activity Log Table -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100">
                <h2 class="text-xl font-semibold text-gray-900">Log Aktiviti Pengguna</h2>
                <p class="text-gray-600 text-sm mt-1">Rekod login dan aktiviti terkini</p>
            </div>

            <!-- Filter Controls -->
            <div class="px-6 py-4 bg-gray-50 border-b border-gray-100 flex flex-wrap gap-4">
                <select class="px-4 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500">
                    <option>Semua Pengguna</option>
                    <option>Ahmad bin Mahmud</option>
                    <option>Siti Aishah</option>
                    <option>Mohd Rizal</option>
                </select>
                <select class="px-4 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500">
                    <option>Semua Aktiviti</option>
                    <option>Login</option>
                    <option>Logout</option>
                    <option>Access Data</option>
                    <option>Edit Data</option>
                </select>
                <input type="date" class="px-4 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500">
                <button class="px-4 py-2 bg-yellow-500 text-white rounded-lg text-sm font-medium hover:bg-yellow-600 transition-colors">
                    <i class="fas fa-filter mr-2"></i>Filter
                </button>
            </div>

            <!-- Table -->
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pengguna</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aktiviti</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Butiran</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">IP Address</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Masa</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-100">
                        <!-- Rows sama seperti sebelum ini -->
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="px-6 py-4 bg-gray-50 border-t border-gray-100 flex items-center justify-between">
                <div class="text-sm text-gray-700">
                    Menunjukkan <span class="font-medium">1</span> hingga <span class="font-medium">5</span> daripada <span class="font-medium">127</span> rekod
                </div>
                <div class="flex space-x-2">
                    <button class="px-3 py-1 border border-gray-300 rounded text-sm font-medium text-gray-500 hover:bg-gray-50">Sebelumnya</button>
                    <button class="px-3 py-1 bg-yellow-500 text-white rounded text-sm font-medium hover:bg-yellow-600">1</button>
                    <button class="px-3 py-1 border border-gray-300 rounded text-sm font-medium text-gray-500 hover:bg-gray-50">2</button>
                    <button class="px-3 py-1 border border-gray-300 rounded text-sm font-medium text-gray-500 hover:bg-gray-50">3</button>
                    <button class="px-3 py-1 border border-gray-300 rounded text-sm font-medium text-gray-500 hover:bg-gray-50">Seterusnya</button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
