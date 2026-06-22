@extends('layouts.app')
@section('content')

<div class="min-h-screen flex flex-col items-center py-10">

    <div class="bg-white rounded-xl w-full max-w-4xl p-6 shadow-lg"></div>

        @if(isset($draft))
        <div class="bg-yellow-100 border-l-4 border-yellow-400 text-yellow-800 p-4 rounded m-4">
            <div class="flex items-center">
                <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                </svg>
                <div>
                    <strong class="font-semibold">Tuntutan Belum Lengkap!</strong>
                    <p class="text-sm mt-1">Anda mempunyai tuntutan yang belum lengkap. Sila sambung isi untuk proses seterusnya.</p>
                </div>
            </div>
        </div>
        @endif

        <div class="p-6 pt-10">
            <div class="text-center mb-6">
                <h2 class="text-2xl font-bold text-gray-800">Mohon Tuntutan Khairat</h2>
                <p class="text-gray-600 mt-1">Sila isi maklumat di bawah untuk membuat tuntutan</p>
            </div>

            <form id="tuntutanForm" method="POST" action="{{ route('tuntutan.store') }}" enctype="multipart/form-data">
                @csrf

                <!-- hidden wajib -->
                <input type="hidden" name="ahli_id" id="ahli_id" value="{{ $ahli->id }}">

                <!-- SECTION 1: MAKLUMAT AHLI YANG MENINGGAL -->
                <div class="mb-8">
                    <div class="flex items-center mb-4 pb-2 border-b border-gray-200">
                        <div class="bg-red-100 p-2 rounded-lg mr-3">
                            <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
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
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
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
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mt-4 space-y-2">
                        <label class="block text-sm font-medium text-gray-700">Tarikh Meninggal <span class="text-red-500">*</span></label>
                        <div class="relative">
                            <input type="date" name="tarikh_meninggal"
                                value="{{ old('tarikh_meninggal', $draft->tarikh_meninggal ?? '') }}"
                                required
                                class="w-full border border-gray-300 text-gray-700 p-3 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 cursor-pointer date-picker">
                            <div class="absolute right-3 top-3 text-gray-400 pointer-events-none">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- SECTION 4: DOKUMEN LAMPIRAN -->
                <div class="mb-8">
                    <div class="flex items-center mb-4 pb-2 border-b border-gray-200">
                        <div class="bg-purple-100 p-2 rounded-lg mr-3">
                            <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-800">Dokumen Lampiran (Format PDF sahaja)</h3>
                    </div>

                    <div class="space-y-5">
                        <div class="p-4 border-2 border-dashed border-gray-300 rounded-lg hover:border-blue-400 transition-colors">
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                <span class="text-red-500">*</span> Sijil Kematian
                            </label>
                            <input type="file" name="sijil_kematian" accept="application/pdf" required
                                class="w-full text-gray-700 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                            <p class="text-xs text-gray-500 mt-2">Muat naik sijil kematian yang disahkan dalam format PDF</p>
                        </div>

                        <div class="p-4 border-2 border-dashed border-gray-300 rounded-lg hover:border-blue-400 transition-colors">
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                <span class="text-red-500">*</span> Salinan Kad Pengenalan Pewaris
                            </label>
                            <input type="file" name="ic_pewaris_file" accept="application/pdf" required
                                class="w-full text-gray-700 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                            <p class="text-xs text-gray-500 mt-2">Salinan kad pengenalan anda (pewaris) dalam format PDF</p>
                        </div>

                        <div class="p-4 border-2 border-dashed border-gray-300 rounded-lg hover:border-blue-400 transition-colors">
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                <span class="text-red-500">*</span> Bukti Akaun Bank
                            </label>
                            <input type="file" name="bukti_bank" accept="application/pdf" required
                                class="w-full text-gray-700 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                            <p class="text-xs text-gray-500 mt-2">Contoh: Buku bank atau penyata bank yang menunjukkan nama dan nombor akaun</p>
                        </div>
                    </div>
                </div>

                <!-- SECTION 5: PENGESAHAN -->
                <div class="mb-8 p-5 bg-gradient-to-r from-blue-50 to-indigo-50 rounded-xl border border-blue-200">
                    <div class="flex items-start">
                        <div class="flex-shrink-0">
                            <svg class="w-6 h-6 text-blue-600 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <h4 class="text-sm font-semibold text-blue-800">Pengesahan Tuntutan</h4>
                            <div class="mt-2">
                                <label class="flex items-start gap-3">
                                    <input type="checkbox" name="confirm_claim" required
                                        class="mt-1 w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                                    <span class="text-sm text-gray-700">
                                        Saya dengan ini mengesahkan bahawa semua maklumat yang diberikan adalah benar dan lengkap.
                                        Tuntutan ini adalah untuk ahli:
                                        <strong class="text-blue-700">{{ $ahli->nama }}</strong>
                                        (No. KP: {{ $ahli->ic_number }}).
                                    </span>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- BUTTONS -->
                <div class="flex flex-col sm:flex-row gap-3 pt-4 border-t border-gray-200 mt-6">
                    <a href="{{ url()->previous() }}"
                        class="flex-1 bg-gray-100 hover:bg-gray-200 text-gray-700 py-3.5 px-4 rounded-xl font-medium transition-all duration-200 flex items-center justify-center gap-2 text-center">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                        Batal
                    </a>

                    <button type="submit"
                        name="action"
                        value="submit"
                        class="flex-1 bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white py-3.5 px-4 rounded-xl font-medium transition-all duration-200 shadow-md hover:shadow-lg flex items-center justify-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        Hantar Tuntutan
                    </button>
                </div>

            </form>
        </div>
    </div>
</div>

@endsection
