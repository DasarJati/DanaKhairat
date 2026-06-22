@extends ('layouts.admin')

@section('title', 'Dashboard - e-Khairat')

@section('content')
<div class="min-h-screen bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

        <!-- Header -->
        <div class="mb-10 text-center">
            <h1 class="text-3xl font-bold text-gray-800 mb-3">Maklumat Pendaftaran Masjid</h1>
            <p class="text-gray-600 max-w-2xl mx-auto">Semak dan uruskan permohonan pendaftaran masjid</p>
        </div>

        <!-- Three Column Cards Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            
            <!-- Card 1: Basic Information -->
            <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden h-fit">
                <div class="px-6 py-5 bg-gradient-to-r from-blue-500 to-blue-600">
                    <h3 class="text-xl font-bold text-white flex items-center">
                        <svg class="w-6 h-6 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                        </svg>
                        Maklumat Asas Masjid
                    </h3>
                </div>
                <div class="p-6">
                    <div class="space-y-6">
                        <div class="space-y-1">
                            <label class="block text-sm font-medium text-gray-500">Nama Masjid</label>
                            <p class="text-gray-900 text-lg font-semibold">{{ $app->nama_masjid }}</p>
                        </div>
                        
                        <div class="grid grid-cols-1 gap-4">
                            <div class="space-y-1">
                                <label class="block text-sm font-medium text-gray-500">Negeri</label>
                                <p class="text-gray-900 font-medium">{{ $app->negeri }}</p>
                            </div>
                            
                            <div class="space-y-1">
                                <label class="block text-sm font-medium text-gray-500">Alamat</label>
                                <p class="text-gray-900">{{ $app->alamat }}</p>
                                @if($app->alamat2)
                                <p class="text-gray-900">{{ $app->alamat2 }}</p>
                                @endif
                                <p class="text-gray-900 font-medium">{{ $app->poskod }} {{ $app->bandar }}</p>
                            </div>
                            
                            <div class="space-y-1">
                                <label class="block text-sm font-medium text-gray-500">Email</label>
                                <p class="text-gray-900 font-medium">{{ $app->email }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Card 2: Registrar Information -->
            <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden h-fit">
                <div class="px-6 py-5 bg-gradient-to-r from-amber-500 to-amber-600">
                    <h3 class="text-xl font-bold text-white flex items-center">
                        <svg class="w-6 h-6 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                        Maklumat Pendaftar
                    </h3>
                </div>
                <div class="p-6">
                    <div class="space-y-6">
                        <div class="space-y-1">
                            <label class="block text-sm font-medium text-gray-500">Nama Pendaftar</label>
                            <p class="text-gray-900 text-lg font-semibold">{{ $app->nama_pendaftar ?? '-' }}</p>
                        </div>
                        
                        <div class="grid grid-cols-1 gap-4">
                            <div class="space-y-1">
                                <label class="block text-sm font-medium text-gray-500">No Telefon</label>
                                <p class="text-gray-900 font-medium">{{ $app->notel ?? '-' }}</p>
                            </div>
                            
                            <div class="space-y-1">
                                <label class="block text-sm font-medium text-gray-500">No. Kad Pengenalan</label>
                                <p class="text-gray-900 font-medium">{{ $app->ic ?? '-' }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Card 3: Documents -->
            <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden h-fit">
                <div class="px-6 py-5 bg-gradient-to-r from-purple-500 to-purple-600">
                    <h3 class="text-xl font-bold text-white flex items-center">
                        <svg class="w-6 h-6 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        Dokumen
                    </h3>
                </div>
                <div class="p-6">
                    <div class="space-y-5">
                        <!-- Slip Akaun -->
                        <div class="bg-gray-50 p-4 rounded-xl border border-gray-200 hover:bg-gray-100 transition">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center">
                                    <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center mr-4">
                                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="font-semibold text-gray-800">Slip Akaun</p>
                                    </div>
                                </div>
                                @if($app && $app->slip_akaun)
                                    <a href="{{ asset($app->slip_akaun) }}" target="_blank"
                                    class="px-4 py-2 bg-blue-600 text-white rounded-lg text-sm font-semibold hover:bg-blue-700 transition flex items-center shadow-sm">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                        </svg>
                                        Lihat
                                    </a>
                                @else
                                    <span class="text-gray-400 text-sm font-medium">Tiada</span>
                                @endif
                            </div>
                        </div>

                        <!-- Kad Pengenalan -->
                        <div class="bg-gray-50 p-4 rounded-xl border border-gray-200 hover:bg-gray-100 transition">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center">
                                    <div class="w-10 h-10 bg-amber-100 rounded-lg flex items-center justify-center mr-4">
                                        <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="font-semibold text-gray-800">Kad Pengenalan</p>
                                    </div>
                                </div>
                                @if($app && $app->salinan_ic)
                                    <a href="{{ asset($app->salinan_ic) }}" target="_blank"
                                    class="px-4 py-2 bg-amber-600 text-white rounded-lg text-sm font-semibold hover:bg-amber-700 transition flex items-center shadow-sm">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                        </svg>
                                        Lihat
                                    </a>
                                @else
                                    <span class="text-gray-400 text-sm font-medium">Tiada</span>
                                @endif
                            </div>
                        </div>

                        <!-- Sijil Pendaftaran -->
                        <div class="bg-gray-50 p-4 rounded-xl border border-gray-200 hover:bg-gray-100 transition">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center">
                                    <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center mr-4">
                                        <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="font-semibold text-gray-800">Sijil Pendaftaran</p>
                                    </div>
                                </div>
                                @if($app && $app->sijil_pendaftaran)
                                    <a href="{{ asset($app->sijil_pendaftaran) }}" target="_blank"
                                    class="px-4 py-2 bg-purple-600 text-white rounded-lg text-sm font-semibold hover:bg-purple-700 transition flex items-center shadow-sm">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                        </svg>
                                        Lihat
                                    </a>
                                @else
                                    <span class="text-gray-400 text-sm font-medium">Tiada</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection