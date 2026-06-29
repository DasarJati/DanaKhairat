<!DOCTYPE html>
<html lang="ms">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Permohonan Tuntutan Khairat</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-gray-100">

    <div class="min-h-screen flex flex-col items-center py-10">

        <div class="bg-white rounded-xl w-full max-w-4xl p-6 shadow-lg">

            @if (isset($draft))
                <div class="bg-yellow-100 border-l-4 border-yellow-400 text-yellow-800 p-4 rounded m-4">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z"
                                clip-rule="evenodd" />
                        </svg>
                        <div>
                            <strong class="font-semibold">Maklumat Belum Lengkap!</strong>
                            <p class="text-sm mt-1">Terdapat laporan maklumat yang belum selesai. Sila sambung pengisian
                                maklumat kematian dan muat naik dokumen untuk proses pengemaskinian rekod.</p>
                        </div>
                    </div>
                </div>
            @endif

            {{-- Global Error Summary --}}
            @if ($errors->any())
                <div class="mb-6 bg-red-50 border-l-4 border-red-500 p-4 rounded-r">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 text-red-500 mr-3" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                                clip-rule="evenodd" />
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

            <div class="p-6 pt-10">
                <div class="text-center mb-6">
                    <h2 class="text-2xl font-bold text-gray-800">Maklumkan Kematian Ahli</h2>
                    <p class="text-gray-600 mt-1">Sila lengkapkan butiran di bawah bagi tujuan pelaporan maklumat dan
                        penyerahan dokumen berkaitan.</p>
                </div>

                <form id="tuntutanForm" method="POST" action="{{ route('tuntutan.store') }}"
                    enctype="multipart/form-data" novalidate>
                    @csrf

                    <!-- hidden wajib -->
                    <input type="hidden" name="ahli_id" id="ahli_id" value="{{ $ahli->id }}">

                    <!-- SECTION 1: MAKLUMAT AHLI YANG MENINGGAL -->
                    <div class="mb-8">
                        <div class="flex items-center mb-4 pb-2 border-b border-gray-200">
                            <div class="bg-red-100 p-2 rounded-lg mr-3">
                                <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                            </div>
                            <h3 class="text-lg font-semibold text-gray-800">Maklumat Ahli Yang Meninggal</h3>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="space-y-2">
                                <label class="block text-sm font-medium text-gray-700">Nama Ahli</label>
                                <div class="relative">
                                    <input type="text" name="nama_ahli" value="{{ $ahli->nama }}" readonly
                                        class="w-full border border-gray-300 bg-gray-50 text-gray-700 p-3 rounded-lg">
                                    <div class="absolute right-3 top-3 text-gray-400">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                    </div>
                                </div>
                            </div>

                            <div class="space-y-2">
                                <label class="block text-sm font-medium text-gray-700">No. Kad Pengenalan</label>
                                <div class="relative">
                                    <input type="text" name="ic_ahli" value="{{ $ahli->ic_number }}" readonly
                                        class="w-full border border-gray-300 bg-gray-50 text-gray-700 p-3 rounded-lg">
                                    <div class="absolute right-3 top-3 text-gray-400">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mt-4 space-y-2">
                            <label class="block text-sm font-medium text-gray-700">Tarikh Meninggal <span
                                    class="text-red-500">*</span></label>
                            <div class="relative">
                                <input type="date" name="tarikh_meninggal"
                                    value="{{ old('tarikh_meninggal', $draft->tarikh_meninggal ?? '') }}" required
                                    class="w-full border {{ $errors->has('tarikh_meninggal') ? 'border-red-500 ring-1 ring-red-500' : 'border-gray-300' }} text-gray-700 p-3 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 cursor-pointer date-picker">
                                <div class="absolute right-3 top-3 text-gray-400 pointer-events-none">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                </div>
                            </div>
                            @error('tarikh_meninggal')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- SECTION 2: MAKLUMAT PEWARIS -->
                    <div class="mb-8 p-6 border rounded-lg shadow-sm bg-gray-50">
                        <div class="flex items-center mb-4 pb-2 border-b border-gray-200">
                            <div class="bg-blue-100 p-2 rounded-lg mr-3">
                                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                            </div>
                            <h3 class="text-xl font-semibold text-gray-800">Maklumat Pewaris</h3>
                        </div>

                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Pilih Pewaris <span
                                    class="text-red-500">*</span></label>
                            <select id="warisDropdown" name="selected_waris_id"
                                class="w-full border {{ $errors->has('selected_waris_id') ? 'border-red-500 ring-1 ring-red-500' : 'border-gray-300' }} p-3 rounded-lg">
                                <option value="">-- Sila Pilih Pewaris --</option>

                                @php
                                    $ahli_meninggal_id = is_object($ahli) ? $ahli->id : $ahli['id'];
                                @endphp

                                @foreach ($pewaris as $w)
                                    @php
                                        $w_id = is_object($w) ? $w->id : $w['id'];
                                        $w_nama = is_object($w) ? $w->nama : $w['nama'];
                                        $w_ic = is_object($w) ? $w->ic_number ?? $w->ic : $w['ic_number'] ?? $w['ic'];
                                        $w_hubungan = is_object($w) ? $w->hubungan : $w['hubungan'];
                                    @endphp

                                    @if ($w_id != $ahli_meninggal_id)
                                        <option value="{{ $w_id }}" data-nama="{{ $w_nama }}"
                                            data-ic="{{ $w_ic }}" data-hubungan="{{ $w_hubungan }}"
                                            data-alamat="{{ $w->alamat ?? ($w['alamat'] ?? ($user->alamat ?? ($user->address ?? ''))) }}"
                                            {{ old('selected_waris_id') == $w_id ? 'selected' : '' }}>
                                            {{ $w_nama }} ({{ $w_hubungan }})
                                        </option>
                                    @endif
                                @endforeach
                            </select>
                            @error('selected_waris_id')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Nama Pewaris <span
                                        class="text-red-500">*</span></label>
                                <input type="text" name="nama_pewaris" id="nama_pewaris"
                                    value="{{ old('nama_pewaris', $draft->nama_pewaris ?? '') }}" readonly
                                    class="w-full border {{ $errors->has('nama_pewaris') ? 'border-red-500 ring-1 ring-red-500' : 'border-gray-300' }} bg-gray-100 text-gray-700 p-3 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                @error('nama_pewaris')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">No. Kad Pengenalan <span
                                        class="text-red-500">*</span></label>
                                <input type="text" name="ic_pewaris" id="ic_pewaris"
                                    value="{{ old('ic_pewaris', $draft->ic_pewaris ?? '') }}" readonly
                                    class="w-full border {{ $errors->has('ic_pewaris') ? 'border-red-500 ring-1 ring-red-red-500' : 'border-gray-300' }} bg-gray-100 text-gray-700 p-3 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                @error('ic_pewaris')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Hubungan dengan Si Mati <span
                                        class="text-red-500">*</span></label>
                                <input type="text" name="hubungan" id="hubungan"
                                    value="{{ old('hubungan', $draft->hubungan ?? '') }}" readonly
                                    class="w-full border {{ $errors->has('hubungan') ? 'border-red-500 ring-1 ring-red-500' : 'border-gray-300' }} bg-gray-100 text-gray-700 p-3 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                @error('hubungan')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Telefon <span
                                        class="text-red-500">*</span></label>
                                <input type="text" name="telefon" id="telefon"
                                    value="{{ old('telefon', $draft->telefon ?? ($user->phone ?? ($user->telefon_bimbit ?? ''))) }}"
                                    class="w-full border {{ $errors->has('telefon') ? 'border-red-500 ring-1 ring-red-500' : 'border-gray-300' }} text-gray-700 p-3 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                @error('telefon')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="mt-4">
                            <label class="block text-sm font-medium text-gray-700">Alamat <span
                                    class="text-red-500">*</span></label>
                            <textarea name="alamat" id="alamat" rows="3" readonly
                                class="w-full border {{ $errors->has('alamat') ? 'border-red-500 ring-1 ring-red-500' : 'border-gray-300' }} bg-gray-100 text-gray-700 p-3 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">{{ old('alamat', $draft->alamat ?? ($user->alamat ?? ($user->address ?? ''))) }}</textarea>
                            @error('alamat')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- SECTION 3: MAKLUMAT BANK -->
                    {{-- <div class="mb-8">
                        <div class="flex items-center mb-4 pb-2 border-b border-gray-200">
                            <div class="bg-green-100 p-2 rounded-lg mr-3">
                                <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                                </svg>
                            </div>
                            <h3 class="text-lg font-semibold text-gray-800">Maklumat Bank untuk Pembayaran</h3>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="space-y-2">
                                <label class="block text-sm font-medium text-gray-700">Nama Bank <span
                                        class="text-red-500">*</span></label>
                                <select name="nama_bank" id="nama_bank"
                                    class="w-full border {{ $errors->has('nama_bank') ? 'border-red-500 ring-1 ring-red-500' : 'border-gray-300' }} text-gray-700 p-3 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                    <option value="">Pilih Bank</option>
                                    <option value="MAYBANK"
                                        {{ old('nama_bank', $draft->nama_bank ?? '') == 'MAYBANK' ? 'selected' : '' }}>
                                        Maybank</option>
                                    <option value="CIMB"
                                        {{ old('nama_bank', $draft->nama_bank ?? '') == 'CIMB' ? 'selected' : '' }}>
                                        CIMB</option>
                                    <option value="PUBLIC BANK"
                                        {{ old('nama_bank', $draft->nama_bank ?? '') == 'PUBLIC BANK' ? 'selected' : '' }}>
                                        Public Bank</option>
                                    <option value="RHB"
                                        {{ old('nama_bank', $draft->nama_bank ?? '') == 'RHB' ? 'selected' : '' }}>RHB
                                    </option>
                                    <option value="BANK ISLAM"
                                        {{ old('nama_bank', $draft->nama_bank ?? '') == 'BANK ISLAM' ? 'selected' : '' }}>
                                        Bank Islam</option>
                                    <option value="BANK RAKYAT"
                                        {{ old('nama_bank', $draft->nama_bank ?? '') == 'BANK RAKYAT' ? 'selected' : '' }}>
                                        Bank Rakyat</option>
                                    <option value="BSN"
                                        {{ old('nama_bank', $draft->nama_bank ?? '') == 'BSN' ? 'selected' : '' }}>BSN
                                    </option>
                                    <option value="LAIN-LAIN"
                                        {{ old('nama_bank', $draft->nama_bank ?? '') == 'LAIN-LAIN' ? 'selected' : '' }}>
                                        Lain-lain</option>
                                </select>
                                @error('nama_bank')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="space-y-2">
                                <label class="block text-sm font-medium text-gray-700">No. Akaun Bank <span
                                        class="text-red-500">*</span></label>
                                <input type="text" name="no_akaun" id="no_akaun"
                                    value="{{ old('no_akaun', $draft->no_akaun ?? '') }}"
                                    class="w-full border {{ $errors->has('no_akaun') ? 'border-red-500 ring-1 ring-red-500' : 'border-gray-300' }} text-gray-700 p-3 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                @error('no_akaun')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="mt-4 space-y-2">
                            <label class="block text-sm font-medium text-gray-700">Nama Pemegang Akaun <span
                                    class="text-red-500">*</span></label>
                            <input type="text" name="nama_akaun" id="nama_akaun"
                                value="{{ old('nama_akaun', $draft->nama_akaun ?? ($user->nama ?? '')) }}"
                                class="w-full border {{ $errors->has('nama_akaun') ? 'border-red-500 ring-1 ring-red-500' : 'border-gray-300' }} text-gray-700 p-3 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            @error('nama_akaun')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                            <p class="text-xs text-gray-500 mt-1">* Nama mesti sama seperti dalam buku bank</p>
                        </div>
                    </div> --}}

                    <!-- SECTION 4: DOKUMEN LAMPIRAN -->
                    <div class="mb-8">
                        <div class="flex items-center mb-4 pb-2 border-b border-gray-200">
                            <div class="bg-purple-100 p-2 rounded-lg mr-3">
                                <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                            </div>
                            <h3 class="text-lg font-semibold text-gray-800">Dokumen Lampiran (Format PDF, PNG, JPG)
                            </h3>
                        </div>

                        <div class="space-y-5">
                            <div
                                class="p-4 border-2 {{ $errors->has('sijil_kematian') ? 'border-red-400 bg-red-50' : 'border-dashed border-gray-300' }} rounded-lg hover:border-blue-400 transition-colors">
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    <span class="text-red-500">*</span> Sijil Kematian
                                </label>
                                <input type="file" name="sijil_kematian" accept="application/pdf,.png,.jpg,.jpeg"
                                    class="w-full text-gray-700 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                                @error('sijil_kematian')
                                    <p class="text-red-500 text-xs mt-2">{{ $message }}</p>
                                @enderror
                                <p class="text-xs text-gray-500 mt-2">Muat naik sijil kematian yang disahkan dalam
                                    format PDF atau gambar</p>
                            </div>

                            <div
                                class="p-4 border-2 {{ $errors->has('ic_pewaris_file') ? 'border-red-400 bg-red-50' : 'border-dashed border-gray-300' }} rounded-lg hover:border-blue-400 transition-colors">
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    <span class="text-red-500">*</span> Salinan Kad Pengenalan Pewaris
                                </label>
                                <input type="file" name="ic_pewaris_file" accept="application/pdf,.png,.jpg,.jpeg"
                                    class="w-full text-gray-700 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                                @error('ic_pewaris_file')
                                    <p class="text-red-500 text-xs mt-2">{{ $message }}</p>
                                @enderror
                                <p class="text-xs text-gray-500 mt-2">Salinan kad pengenalan anda (pewaris) dalam
                                    format PDF atau gambar</p>
                            </div>

                            <div
                                class="p-4 border-2 {{ $errors->has('laporan_polis') ? 'border-red-400 bg-red-50' : 'border-dashed border-gray-300' }} rounded-lg hover:border-blue-400 transition-colors">
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    <span class="text-red-500">*</span> Laporan Polis
                                </label>
                                <input type="file" name="laporan_polis" accept="application/pdf,.png,.jpg,.jpeg"
                                    class="w-full text-gray-700 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">

                                @error('laporan_polis')
                                    <p class="text-red-500 text-xs mt-2">{{ $message }}</p>
                                @enderror

                                {{-- Ayat pengganti untuk Laporan Polis --}}
                                <p class="text-xs text-gray-500 mt-2">
                                    Contoh: Salinan laporan polis yang melaporkan kejadian bagi tujuan pengesahan
                                    maklumat dan rujukan rasmi pihak pengurusan.
                                </p>
                            </div>
                            <div
                                class="p-4 border-2 {{ $errors->has('other_document') ? 'border-red-400 bg-red-50' : 'border-dashed border-gray-300' }} rounded-lg hover:border-blue-400 transition-colors">
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Dokumen Sokongan Lain (Pilihan)
                                </label>
                                <input type="file" name="other_document"
                                    accept="application/pdf,.png,.jpg,.jpeg"
                                    class="w-full text-gray-700 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">

                                @error('other_document')
                                    <p class="text-red-500 text-xs mt-2">{{ $message }}</p>
                                @enderror

                                <p class="text-xs text-gray-500 mt-2">
                                    Sila muat naik sebarang dokumen lain yang berkaitan jika perlu (contoh: Permit
                                    Kubur, Surat Hospital, atau dokumen pengesahan tambahan).
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="mb-8 p-5 bg-gradient-to-r from-blue-50 to-indigo-50 rounded-xl border border-blue-200">
                        <div class="flex items-start">
                            <div class="flex-shrink-0">
                                <svg class="w-6 h-6 text-blue-600 mt-0.5" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <div class="ml-3">
                                <h4 class="text-sm font-semibold text-blue-800">Pengesahan Maklumat Pelaporan</h4>
                                <div class="mt-2">
                                    <label class="flex items-start gap-3">
                                        <input type="checkbox" name="confirm_claim"
                                            {{ old('confirm_claim') ? 'checked' : '' }}
                                            class="mt-1 w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                                        <span class="text-sm text-gray-700">
                                            Saya dengan ini mengesahkan bahawa semua maklumat dan dokumen yang diberikan
                                            adalah benar dan lengkap.
                                            Pelaporan maklumat kematian ini adalah untuk ahli:
                                            <strong class="text-blue-700">{{ $ahli->nama }}</strong>
                                            (No. KP: {{ $ahli->ic_number }}).
                                        </span>
                                    </label>
                                    @error('confirm_claim')
                                        <p class="text-red-500 text-xs mt-2">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- BUTTONS -->
                    <div class="flex flex-col sm:flex-row gap-3 pt-4 border-t border-gray-200 mt-6">
                        <a href="{{ url()->previous() }}"
                            class="flex-1 bg-gray-100 hover:bg-gray-200 text-gray-700 py-3.5 px-4 rounded-xl font-medium transition-all duration-200 flex items-center justify-center gap-2 text-center">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12" />
                            </svg>
                            Batal
                        </a>

                        <button type="submit" name="action" value="submit"
                            class="flex-1 bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white py-3.5 px-4 rounded-xl font-medium transition-all duration-200 shadow-md hover:shadow-lg flex items-center justify-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M5 13l4 4L19 7" />
                            </svg>
                            Hantar Tuntutan
                        </button>
                    </div>

                </form>
            </div>
        </div>
    </div>

    <script>
        const hubunganSiMati = "{{ strtolower($ahli->hubungan ?? '') }}";

        document.getElementById('warisDropdown').addEventListener('change', function() {
            const selected = this.options[this.selectedIndex];

            if (this.value === "") {
                // Clear fields if no selection
                document.getElementById('nama_pewaris').value = '';
                document.getElementById('ic_pewaris').value = '';
                document.getElementById('hubungan').value = '';
                document.getElementById('alamat').value = '';
                return;
            }

            const nama = selected.dataset.nama || '';
            const ic = selected.dataset.ic || '';
            let hubungan = (selected.dataset.hubungan || '').toLowerCase();
            const alamat = selected.dataset.alamat || '';

            document.getElementById('nama_pewaris').value = nama;
            document.getElementById('ic_pewaris').value = ic;
            document.getElementById('alamat').value = alamat;

            if (hubunganSiMati === 'anak' && hubungan === 'anak') {
                document.getElementById('hubungan').value = 'adik-beradik';
            } else {
                document.getElementById('hubungan').value = hubungan;
            }
        });

        // Trigger change event on page load if old value exists
        window.addEventListener('DOMContentLoaded', function() {
            const dropdown = document.getElementById('warisDropdown');
            if (dropdown.value) {
                dropdown.dispatchEvent(new Event('change'));
            }
        });
    </script>

</body>

</html>
