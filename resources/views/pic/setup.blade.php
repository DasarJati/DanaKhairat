@extends('layouts.app')

@section('content')
<div class="min-h-screen py-12">
    <div class="max-w-2xl mx-auto px-4">
        <div class="text-center mb-10">
            <h1 class="text-3xl font-extrabold text-slate-900 mb-2">Setup Maklumat AJK</h1>
            <p class="text-slate-600 italic">Sila lengkapkan konfigurasi sistem khairat kematian di bawah</p>
        </div>

        @if(session('success'))
        <div class="mb-6 p-4 bg-emerald-50 border-l-4 border-emerald-500 rounded-r-lg shadow-sm">
            <div class="flex">
                <svg class="h-5 w-5 text-emerald-500" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                </svg>
                <p class="ml-3 text-sm font-medium text-emerald-800">{{ session('success') }}</p>
            </div>
        </div>
        @endif

        <form action="{{ route('ajk.setup.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf

            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
                <div class="bg-slate-800 px-6 py-4">
                    <h2 class="text-white font-semibold flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                        Tetapan Kewangan Khairat
                    </h2>
                </div>
                <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Bayaran Tahunan</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-slate-400 font-medium">RM</span>
                            <input type="number" step="0.01" name="bayaran_tahunan" class="block w-full pl-10 pr-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:bg-white transition" placeholder="0.00" required>
                        </div>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Yuran Pendaftaran</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-slate-400 font-medium">RM</span>
                            <input type="number" step="0.01" name="yuran_pendaftaran" class="block w-full pl-10 pr-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:bg-white transition" placeholder="0.00" required>
                        </div>
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Kadar Pengurusan Kematian (Per Individu)</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-slate-400 font-medium">RM</span>
                            <input type="number" step="0.01" name="bayaran_kematian" class="block w-full pl-10 pr-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:bg-white transition" placeholder="0.00" required>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
                <div class="bg-emerald-600 px-6 py-4">
                    <h2 class="text-white font-semibold flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 14v3m4-3v3m4-3v3M3 21h18M3 10h18M3 7l9-4 9 4M4 10h16v11H4V10z" />
                        </svg>
                        Maklumat Akaun Bank
                    </h2>
                </div>
                <div class="p-6 space-y-5">
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Nama Bank</label>
                        <input type="text" name="nama_bank" class="block w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:bg-white transition" placeholder="Contoh: Maybank / Bank Islam" required>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Nama Pemegang Akaun</label>
                            <input type="text" name="nama_akaun" class="block w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:bg-white transition" placeholder="Nama penuh" required>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">No. Akaun Bank</label>
                            <input type="text" name="no_akaun" class="block w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:bg-white transition" placeholder="Nombor akaun" required>
                        </div>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Muat Naik QR Code (DuitNow)</label>
                        <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-slate-200 border-dashed rounded-xl hover:border-emerald-400 transition">
                            <div class="space-y-1 text-center">
                                <svg class="mx-auto h-12 w-12 text-slate-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                    <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                                <div class="flex text-sm text-slate-600">
                                    <label class="relative cursor-pointer bg-white rounded-md font-medium text-emerald-600 hover:text-emerald-500 focus-within:outline-none">
                                        <span>Pilih fail imej</span>
                                        <input type="file" name="qr_bank" class="sr-only" accept="image/*" required>
                                    </label>
                                </div>
                                <p class="text-xs text-slate-500">PNG, JPG sehingga 2MB</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-indigo-50 rounded-2xl p-6 border border-indigo-100">
                <div class="flex items-start mb-6">
                    <div class="flex items-center h-5">
                        <input id="agreement" name="agreement" type="checkbox" onchange="document.getElementById('submitBtn').disabled = !this.checked" class="focus:ring-indigo-500 h-5 w-5 text-indigo-600 border-slate-300 rounded cursor-pointer">
                    </div>
                    <div class="ml-3 text-sm">
                        <label for="agreement" class="font-medium text-slate-700 cursor-pointer">
                            Saya mengesahkan bahawa segala maklumat di atas adalah benar dan akan digunakan sebagai rujukan rasmi bagi sistem khairat ini.
                        </label>
                    </div>
                </div>

                <button type="submit" id="submitBtn" disabled
                    class="w-full bg-indigo-600 hover:bg-indigo-700 disabled:bg-slate-300 disabled:cursor-not-allowed text-white font-bold py-4 px-6 rounded-xl shadow-lg shadow-indigo-200 transition duration-300 flex items-center justify-center text-lg">
                    <svg class="h-6 w-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    Simpan & Aktifkan Tetapan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection