@php
    $status   = $tuntutan->status;
    $editable = $status === 'DRAFT';
    $doc      = $tuntutan->dokumen;
    
    // Status colors
    $statusColors = [
        'DRAFT' => 'bg-gray-100 text-gray-800 border-gray-300',
        'PENDING' => 'bg-yellow-100 text-yellow-800 border-yellow-300',
        'APPROVED' => 'bg-green-100 text-green-800 border-green-300',
        'REJECTED' => 'bg-red-100 text-red-800 border-red-300'
    ];
    
    $statusClass = $statusColors[$status] ?? 'bg-gray-100 text-gray-800 border-gray-300';
@endphp

<!DOCTYPE html>
<html lang="ms" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard eKhairat</title>
    {{-- Tailwind CDN (WAJIB kalau tak guna Vite/layout) --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50 min-h-screen">
    <div class="min-h-screen bg-gradient-to-br from-gray-50 to-blue-50 py-8 px-4">
    <div class="max-w-4xl mx-auto">

        {{-- ================= HEADER CARD ================= --}}
        <div class="bg-white rounded-2xl shadow-xl mb-8 overflow-hidden border border-gray-200">
            <div class="bg-gradient-to-r from-blue-600 to-blue-800 px-8 py-6">
                <div class="flex flex-col md:flex-row md:items-center justify-between">
                    <div>
                        <h1 class="text-2xl md:text-3xl font-bold text-white mb-2">
                            Borang Tuntutan Khairat
                        </h1>
                        <p class="text-blue-100">No. Rujukan: <span class="font-mono">#{{ str_pad($tuntutan->id, 6, '0', STR_PAD_LEFT) }}</span></p>
                    </div>
                    <div class="mt-4 md:mt-0">
                        <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-semibold border-2 {{ $statusClass }}">
                            @switch($status)
                                @case('DRAFT')
                                    <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                                    </svg>
                                    DRAF
                                    @break
                                @case('PENDING')
                                    <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                                    </svg>
                                    DALAM SEMAKAN
                                    @break
                                @case('APPROVED')
                                    <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                    </svg>
                                    DILULUSKAN
                                    @break
                                @case('REJECTED')
                                    <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                                    </svg>
                                    DITOLAK
                                    @break
                            @endswitch
                        </span>
                    </div>
                </div>
            </div>
            
            {{-- ================= INFO TABLE ================= --}}
            <div class="p-8">
                {{-- ================= ERROR MESSAGES ================= --}}
                @if ($errors->any())
                    <div class="mb-6 bg-red-50 border-l-4 border-red-500 p-4 rounded-r">
                        <div class="flex items-center">
                            <svg class="w-5 h-5 text-red-500 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                            </svg>
                            <div>
                                <p class="text-red-700 font-medium">Sila perbetulkan kesalahan berikut:</p>
                                <ul class="text-red-600 text-sm mt-1">
                                    @foreach ($errors->all() as $error)
                                        <li>• {{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                @endif

                {{-- ================= FORM UTAMA ================= --}}
                <form action="{{ route('tuntutan.update', $tuntutan->id) }}"
                      method="POST"
                      enctype="multipart/form-data"
                      class="space-y-8">
                    @csrf
                    @method('PUT')

                    {{-- ================= MAKLUMAT AHLI SECTION ================= --}}
                    <div class="bg-gradient-to-r from-blue-50 to-white rounded-xl p-6 border border-blue-100">
                        <div class="flex items-center mb-6">
                            <div class="bg-blue-100 p-3 rounded-lg mr-4">
                                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                </svg>
                            </div>
                            <h2 class="text-xl font-bold text-gray-800">Maklumat Ahli</h2>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Nama Ahli</label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                        </svg>
                                    </div>
                                    <input type="text" 
                                           value="{{ $tuntutan->nama_ahli }}" 
                                           class="pl-10 w-full p-3 border border-gray-300 rounded-lg bg-gray-50 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"
                                           readonly>
                                </div>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">No. KP Ahli</label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <svg class="h-5 w-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 2a1 1 0 00-1 1v1a1 1 0 002 0V3a1 1 0 00-1-1zM4 4h3a3 3 0 006 0h3a2 2 0 012 2v9a2 2 0 01-2 2H4a2 2 0 01-2-2V6a2 2 0 012-2zm2.5 7a1.5 1.5 0 100-3 1.5 1.5 0 000 3zm2.45 4a2.5 2.5 0 10-4.9 0h4.9zM12 9a1 1 0 100 2h3a1 1 0 100-2h-3zm-1 4a1 1 0 011-1h2a1 1 0 110 2h-2a1 1 0 01-1-1z" clip-rule="evenodd"/>
                                        </svg>
                                    </div>
                                    <input type="text" 
                                           value="{{ $tuntutan->ic_ahli }}" 
                                           class="pl-10 w-full p-3 border border-gray-300 rounded-lg bg-gray-50 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"
                                           readonly>
                                </div>
                            </div>
                        </div>

                        <div class="mt-6">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Tarikh Meninggal</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                </div>
                                <input type="date" 
                                       name="tarikh_meninggal"
                                       value="{{ $tuntutan->tarikh_meninggal }}"
                                       class="pl-10 w-full p-3 border border-gray-300 rounded-lg {{ $editable ? 'bg-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500' : 'bg-gray-50' }} transition"
                                       {{ $editable ? '' : 'readonly' }}>
                            </div>
                        </div>
                    </div>

                    {{-- ================= MAKLUMAT PEWARIS SECTION ================= --}}
                    <div class="bg-gradient-to-r from-green-50 to-white rounded-xl p-6 border border-green-100">
                        <div class="flex items-center mb-6">
                            <div class="bg-green-100 p-3 rounded-lg mr-4">
                                <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                                </svg>
                            </div>
                            <h2 class="text-xl font-bold text-gray-800">Maklumat Pewaris</h2>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Nama Pewaris</label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                        </svg>
                                    </div>
                                    <input type="text" 
                                           name="nama_pewaris"
                                           value="{{ $tuntutan->nama_pewaris }}"
                                           placeholder="Masukkan nama pewaris"
                                           class="pl-10 w-full p-3 border border-gray-300 rounded-lg {{ $editable ? 'bg-white focus:ring-2 focus:ring-green-500 focus:border-green-500' : 'bg-gray-50' }} transition"
                                           {{ $editable ? '' : 'readonly' }}>
                                </div>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">No. KP Pewaris</label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <svg class="h-5 w-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 2a1 1 0 00-1 1v1a1 1 0 002 0V3a1 1 0 00-1-1zM4 4h3a3 3 0 006 0h3a2 2 0 012 2v9a2 2 0 01-2 2H4a2 2 0 01-2-2V6a2 2 0 012-2zm2.5 7a1.5 1.5 0 100-3 1.5 1.5 0 000 3zm2.45 4a2.5 2.5 0 10-4.9 0h4.9zM12 9a1 1 0 100 2h3a1 1 0 100-2h-3zm-1 4a1 1 0 011-1h2a1 1 0 110 2h-2a1 1 0 01-1-1z" clip-rule="evenodd"/>
                                        </svg>
                                    </div>
                                    <input type="text" 
                                           name="ic_pewaris"
                                           value="{{ $tuntutan->ic_pewaris }}"
                                           placeholder="Contoh: 901231015678"
                                           class="pl-10 w-full p-3 border border-gray-300 rounded-lg {{ $editable ? 'bg-white focus:ring-2 focus:ring-green-500 focus:border-green-500' : 'bg-gray-50' }} transition"
                                           {{ $editable ? '' : 'readonly' }}>
                                </div>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Hubungan</label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-3.5a4.5 4.5 0 11-9 0 4.5 4.5 0 019 0z"/>
                                        </svg>
                                    </div>
                                    <input type="text" 
                                           name="hubungan"
                                           value="{{ $tuntutan->hubungan }}"
                                           placeholder="Contoh: Anak / Isteri"
                                           class="pl-10 w-full p-3 border border-gray-300 rounded-lg {{ $editable ? 'bg-white focus:ring-2 focus:ring-green-500 focus:border-green-500' : 'bg-gray-50' }} transition"
                                           {{ $editable ? '' : 'readonly' }}>
                                </div>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">No. Telefon</label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                                        </svg>
                                    </div>
                                    <input type="text" 
                                           name="telefon"
                                           value="{{ $tuntutan->telefon }}"
                                           placeholder="Contoh: 0123456789"
                                           class="pl-10 w-full p-3 border border-gray-300 rounded-lg bg-white focus:ring-2 focus:ring-green-500 focus:border-green-500 transition"
                                           required>
                                </div>
                            </div>
                        </div>

                        <div class="mt-6">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Alamat</label>
                            <div class="relative">
                                <div class="absolute top-3 left-3">
                                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    </svg>
                                </div>
                                <textarea name="alamat"
                                          rows="3"
                                          class="pl-10 w-full p-3 border border-gray-300 rounded-lg {{ $editable ? 'bg-white focus:ring-2 focus:ring-green-500 focus:border-green-500' : 'bg-gray-50' }} transition"
                                          placeholder="Masukkan alamat lengkap pewaris"
                                          {{ $editable ? '' : 'readonly' }}>{{ $tuntutan->alamat }}</textarea>
                            </div>
                        </div>
                    </div>

                    {{-- ================= MAKLUMAT BANK SECTION ================= --}}
                    <div class="bg-gradient-to-r from-purple-50 to-white rounded-xl p-6 border border-purple-100">
                        <div class="flex items-center mb-6">
                            <div class="bg-purple-100 p-3 rounded-lg mr-4">
                                <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                                </svg>
                            </div>
                            <h2 class="text-xl font-bold text-gray-800">Maklumat Bank</h2>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Nama Bank</label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                        </svg>
                                    </div>
                                    <input type="text" 
                                           name="nama_bank"
                                           value="{{ $tuntutan->nama_bank }}"
                                           placeholder="Contoh: Maybank"
                                           class="pl-10 w-full p-3 border border-gray-300 rounded-lg {{ $editable ? 'bg-white focus:ring-2 focus:ring-purple-500 focus:border-purple-500' : 'bg-gray-50' }} transition"
                                           {{ $editable ? '' : 'readonly' }}>
                                </div>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">No. Akaun</label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                        </svg>
                                    </div>
                                    <input type="text" 
                                           name="no_akaun"
                                           value="{{ $tuntutan->no_akaun }}"
                                           placeholder="Contoh: 1234567890"
                                           class="pl-10 w-full p-3 border border-gray-300 rounded-lg {{ $editable ? 'bg-white focus:ring-2 focus:ring-purple-500 focus:border-purple-500' : 'bg-gray-50' }} transition"
                                           {{ $editable ? '' : 'readonly' }}>
                                </div>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Nama Pemegang Akaun</label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                        </svg>
                                    </div>
                                    <input type="text" 
                                           name="nama_akaun"
                                           value="{{ $tuntutan->nama_akaun }}"
                                           placeholder="Nama seperti dalam kad bank"
                                           class="pl-10 w-full p-3 border border-gray-300 rounded-lg {{ $editable ? 'bg-white focus:ring-2 focus:ring-purple-500 focus:border-purple-500' : 'bg-gray-50' }} transition"
                                           {{ $editable ? '' : 'readonly' }}>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- ================= DOKUMEN SECTION ================= --}}
                    <div class="bg-gradient-to-r from-amber-50 to-white rounded-xl p-6 border border-amber-100">
                        <div class="flex items-center mb-6">
                            <div class="bg-amber-100 p-3 rounded-lg mr-4">
                                <svg class="w-6 h-6 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                            </div>
                            <h2 class="text-xl font-bold text-gray-800">Dokumen Sokongan</h2>
                        </div>

                        <div class="space-y-6">
                            {{-- Sijil Kematian --}}
                            <div class="border border-gray-200 rounded-lg p-4 hover:border-blue-300 transition">
                                <div class="flex items-center justify-between mb-3">
                                    <div class="flex items-center">
                                        <div class="bg-blue-100 p-2 rounded-lg mr-3">
                                            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                            </svg>
                                        </div>
                                        <div>
                                            <h4 class="font-semibold text-gray-800">Sijil Kematian</h4>
                                            <p class="text-sm text-gray-500">Format: PDF sahaja (Maks: 2MB)</p>
                                        </div>
                                    </div>
                                    @if($doc && $doc->sijil_kematian)
                                        <a href="{{ asset($doc->sijil_kematian) }}" 
                                           target="_blank"
                                           class="inline-flex items-center px-3 py-2 bg-blue-100 text-blue-700 rounded-lg hover:bg-blue-200 transition">
                                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                            </svg>
                                            Lihat
                                        </a>
                                    @endif
                                </div>
                                @if($editable)
                                    <input type="file" 
                                           name="sijil_kematian" 
                                           accept=".pdf,.png,.jpg,.jpeg"
                                           class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 transition">
                                @endif
                            </div>

                            {{-- IC Pewaris --}}
                            <div class="border border-gray-200 rounded-lg p-4 hover:border-green-300 transition">
                                <div class="flex items-center justify-between mb-3">
                                    <div class="flex items-center">
                                        <div class="bg-green-100 p-2 rounded-lg mr-3">
                                            <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2"/>
                                            </svg>
                                        </div>
                                        <div>
                                            <h4 class="font-semibold text-gray-800">Kad Pengenalan Pewaris</h4>
                                            <p class="text-sm text-gray-500">Format: PDF sahaja (Maks: 2MB)</p>
                                        </div>
                                    </div>
                                    @if($doc && $doc->ic_pewaris)
                                        <a href="{{ asset($doc->ic_pewaris) }}" 
                                           target="_blank"
                                           class="inline-flex items-center px-3 py-2 bg-green-100 text-green-700 rounded-lg hover:bg-green-200 transition">
                                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                            </svg>
                                            Lihat
                                        </a>
                                    @endif
                                </div>
                                @if($editable)
                                    <input type="file" 
                                           name="ic_pewaris_file" 
                                           accept=".pdf,.png,.jpg,.jpeg"
                                           class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-green-50 file:text-green-700 hover:file:bg-green-100 transition">
                                @endif
                            </div>

                            {{-- Bukti Bank --}}
                            <div class="border border-gray-200 rounded-lg p-4 hover:border-purple-300 transition">
                                <div class="flex items-center justify-between mb-3">
                                    <div class="flex items-center">
                                        <div class="bg-purple-100 p-2 rounded-lg mr-3">
                                            <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                                            </svg>
                                        </div>
                                        <div>
                                            <h4 class="font-semibold text-gray-800">Bukti Akaun Bank</h4>
                                            <p class="text-sm text-gray-500">Format: PDF sahaja (Maks: 2MB)</p>
                                        </div>
                                    </div>
                                    @if($doc && $doc->bukti_bank)
                                        <a href="{{ asset($doc->bukti_bank) }}" 
                                           target="_blank"
                                           class="inline-flex items-center px-3 py-2 bg-purple-100 text-purple-700 rounded-lg hover:bg-purple-200 transition">
                                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                            </svg>
                                            Lihat
                                        </a>
                                    @endif
                                </div>
                                @if($editable)
                                    <input type="file" 
                                           name="bukti_bank" 
                                           accept=".pdf,.png,.jpg,.jpeg"
                                           class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-purple-50 file:text-purple-700 hover:file:bg-purple-100 transition">
                                @endif
                            </div>
                        </div>
                    </div>

                    {{-- ================= BUTTONS ================= --}}
                    @if($editable)
                        <div class="flex flex-col sm:flex-row gap-4 pt-6 border-t border-gray-200">
                            <button type="submit"
                                    name="action"
                                    value="draft"
                                    class="flex-1 inline-flex items-center justify-center px-6 py-3 border border-gray-300 text-gray-700 font-medium rounded-lg hover:bg-gray-50 transition duration-300">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"/>
                                </svg>
                                Simpan Draf
                            </button>
                            
                            <button type="submit"
                                    name="action"
                                    value="submit"
                                    onclick="return confirm('Pastikan semua maklumat dan dokumen lengkap sebelum hantar')"
                                    class="flex-1 inline-flex items-center justify-center px-6 py-3 bg-gradient-to-r from-green-600 to-emerald-600 text-white font-medium rounded-lg hover:from-green-700 hover:to-emerald-700 shadow-lg hover:shadow-xl transition duration-300 transform hover:-translate-y-0.5">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                Hantar Tuntutan
                            </button>
                        </div>
                    @endif
                </form>
            </div>
        </div>
    </div>
</div>
</body>

</html>

