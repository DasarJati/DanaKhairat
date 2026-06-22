@extends('layouts.app')

@section('content')

    <div class="container mx-auto px-4 py-10">
        {{-- Header --}}
        <header class="bg-white rounded-xl shadow-sm p-6 mb-8 border border-gray-100">
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center">
                <div>
                    <h1 class="text-2xl font-bold text-gray-800">Edit Profil Surau Al-Hidayah</h1>
                    <p class="text-gray-500 mt-2">Kemaskini maklumat surau, tetapan pembayaran dan maklumat AJK</p>
                </div>
                <div class="flex space-x-3 mt-4 md:mt-0">
                    <button class="bg-gradient-to-r from-gray-500 to-gray-600 hover:from-gray-600 hover:to-gray-700 text-white font-medium py-2.5 px-5 rounded-xl transition-all duration-200 shadow-md hover:shadow-lg">
                        <i class="fas fa-times mr-2"></i>Batal
                    </button>
                    <button type= "submit" class="bg-gradient-to-r from-green-500 to-emerald-500 hover:from-green-600 hover:to-emerald-600 text-white font-medium py-2.5 px-5 rounded-xl transition-all duration-200 shadow-md hover:shadow-lg">
                        <i class="fas fa-save mr-2"></i>Simpan Perubahan
                    </button>
                </div>
            </div>
        </header>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            {{-- Kiri (Tetapan & AJK) --}}
            <div class="lg:col-span-2 space-y-8">

                {{-- Maklumat Surau --}}
                <form action="{{ route('profile.update') }}" method="POST">
                    @csrf

                <div class="mb-6 p-4 bg-yellow-50 border-l-4 border-yellow-400 rounded-xl">
                    <p class="text-yellow-800 font-medium">
                        ⚠️ Peringatan: Segala maklumat yang anda isi akan dipaparkan kepada Ahli Khairat.
                        Sila pastikan semua maklumat adalah tepat, terkini dan berkaitan dengan masjid/surau anda.
                    </p>
                </div>
                <div class="bg-white rounded-xl shadow-sm overflow-hidden border border-gray-100">
                    <div class="bg-gradient-to-r from-purple-500 to-indigo-500 px-6 py-4">
                        <h2 class="text-xl font-bold text-white">Maklumat</h2>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Nama Masjid/Surau</label>
                                <input 
                                    type="text" 
                                    value="{{ $ajk->masjid->nama }}"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-xl bg-gray-100 cursor-not-allowed"
                                    disabled>
                            </div>
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Alamat</label>
                                <textarea 
                                    name="alamat" 
                                    rows="3"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 transition-colors"
                                >{{ $ajk->alamat }}</textarea>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">No. Telefon</label>
                                <input type="text" value="03-8736 1234" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 transition-colors">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                                <input type="email" value="surau.alhidayah@example.com" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 transition-colors">
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Tetapan Pembayaran --}}
                <div class="bg-white rounded-xl shadow-sm overflow-hidden border border-gray-100">
                    <div class="bg-gradient-to-r from-yellow-500 to-amber-500 px-6 py-4">
                        <h2 class="text-xl font-bold text-white">Tetapan Pembayaran</h2>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="bg-gradient-to-br from-blue-50 to-sky-100 p-5 rounded-xl border border-blue-200">
                                <div class="flex justify-between items-start mb-4">
                                    <div>
                                        <h3 class="font-medium text-gray-700">Bayaran Bulanan</h3>
                                    </div>
                                </div>
                                <div class="relative">
                                    <span class="absolute left-3 top-3 text-gray-500">RM</span>
                                    <input type="number" value="20.00" step="0.01" class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                                </div>
                                <p class="text-sm text-gray-500 mt-2">Per rumah sebulan</p>
                            </div>
                            <div class="bg-gradient-to-br from-amber-50 to-orange-100 p-5 rounded-xl border border-amber-200">
                                <div class="flex justify-between items-start mb-4">
                                    <div>
                                        <h3 class="font-medium text-gray-700">Bayaran Tahunan</h3>
                                    </div>
                                </div>
                                <div class="relative">
                                    <span class="absolute left-3 top-3 text-gray-500">RM</span>
                                    <input type="number" value="220.00" step="0.01" class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-amber-500 focus:border-amber-500 transition-colors">
                                </div>
                                <p class="text-sm text-gray-500 mt-2">Per rumah setahun</p>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Maklumat AJK --}}
                <div class="bg-white rounded-xl shadow-sm overflow-hidden border border-gray-100">
                    <div class="bg-gradient-to-r from-gray-600 to-gray-700 px-6 py-4 flex justify-between items-center">
                        <h2 class="text-xl font-bold text-white">Maklumat AJK Surau</h2>
                        <button class="text-white hover:text-gray-200 transition duration-200 p-2 rounded-lg hover:bg-white/10">
                            <i class="fas fa-plus mr-1"></i> Tambah AJK
                        </button>
                    </div>
                    <div class="p-6">
                        <div class="overflow-x-auto">
                            <table class="w-full text-sm text-left text-gray-700">
                                <thead class="text-xs text-gray-700 uppercase bg-gradient-to-r from-gray-100 to-gray-200">
                                    <tr>
                                        <th class="px-4 py-3 font-semibold">Jawatan</th>
                                        <th class="px-4 py-3 font-semibold">Nama</th>
                                        <th class="px-4 py-3 font-semibold">No. Telefon</th>
                                        <th class="px-4 py-3 font-semibold">Tindakan</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr class="bg-white border-b border-gray-200 hover:bg-blue-50 transition-colors">
                                        <td class="px-4 py-3">
                                            <select class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                                <option selected>Pengerusi</option>
                                                <option>Naib Pengerusi</option>
                                                <option>Bendahari</option>
                                                <option>Setiausaha</option>
                                                <option>AJK Biasa</option>
                                            </select>
                                        </td>
                                        <td class="px-4 py-3">
                                            <input type="text" value="Ahmad bin Hassan" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                        </td>
                                        <td class="px-4 py-3">
                                            <input type="text" value="012-345 6789" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                        </td>
                                        <td class="px-4 py-3">
                                            <button class="text-red-500 hover:text-red-700 p-1">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                    <tr class="bg-white border-b border-gray-200 hover:bg-amber-50 transition-colors">
                                        <td class="px-4 py-3">
                                            <select class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                                <option>Pengerusi</option>
                                                <option selected>Naib Pengerusi</option>
                                                <option>Bendahari</option>
                                                <option>Setiausaha</option>
                                                <option>AJK Biasa</option>
                                            </select>
                                        </td>
                                        <td class="px-4 py-3">
                                            <input type="text" value="Siti binti Abdullah" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                        </td>
                                        <td class="px-4 py-3">
                                            <input type="text" value="013-456 7890" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                        </td>
                                        <td class="px-4 py-3">
                                            <button class="text-red-500 hover:text-red-700 p-1">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Kanan (Bank + T&C) --}}
            <div class="space-y-8">
                {{-- Bank --}}
                <div class="bg-white rounded-xl shadow-sm overflow-hidden border border-gray-100">
                    <div class="bg-gradient-to-r from-sky-500 to-blue-500 px-6 py-4">
                        <h2 class="text-xl font-bold text-white">Maklumat Bank</h2>
                    </div>
                    <div class="p-6">
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Nama Bank</label>
                                <select class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-sky-500 focus:border-sky-500 transition-colors">
                                    <option selected>Bank Islam Malaysia Berhad</option>
                                    <option>Maybank</option>
                                    <option>CIMB Bank</option>
                                    <option>RHB Bank</option>
                                    <option>Bank Muamalat</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Nama Akaun</label>
                                <input type="text" value="Surau Al-Ikhlas" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-sky-500 focus:border-sky-500 transition-colors">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Nombor Akaun</label>
                                <input type="text" value="12-345-6789-0" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-sky-500 focus:border-sky-500 transition-colors">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Footer --}}
        <footer class="bg-gradient-to-r from-gray-50 to-gray-100 border-t border-gray-200 mt-12 p-6 rounded-xl text-center text-gray-500 text-sm">
            <div class="flex items-center justify-center">
                <i class="fas fa-mosque text-yellow-500 mr-2"></i>
                <span>&copy; 2023 Surau Al-Hidayah. Semua hak cipta terpelihara.</span>
            </div>
        </footer>
    </div>

@endsection