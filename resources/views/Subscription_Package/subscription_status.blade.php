@extends('layouts.app')

@section('title', 'Status Langganan - danakhairat')

@section('content')
    <style>
        :root {
            --glass-bg: rgba(255, 255, 255, 0.7);
            --glass-border: rgba(255, 255, 255, 0.4);
            --mirror-shine: linear-gradient(135deg, rgba(255, 255, 255, 0.4) 0%, rgba(255, 255, 255, 0) 50%);
        }

        /* Mirror/Glass Effect */
        .glass-card {
            background: var(--glass-bg);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border: 1px solid var(--glass-border);
            box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.07);
            position: relative;
            overflow: hidden;
        }

        .glass-card::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: var(--mirror-shine);
            pointer-events: none;
        }

        @keyframes float {

            0%,
            100% {
                transform: translateY(0px);
            }

            50% {
                transform: translateY(-10px);
            }
        }

        .animate-float {
            animation: float 6s ease-in-out infinite;
        }

        /* Custom Scrollbar */
        .history-scroll::-webkit-scrollbar {
            width: 4px;
        }

        .history-scroll::-webkit-scrollbar-thumb {
            background: #e2e8f0;
            border-radius: 10px;
        }
    </style>

    <div class="min-h-screen py-10 px-4">
        <div class="max-w-5xl mx-auto">

            {{-- Header Section --}}
            <div class="mb-8 flex flex-col md:flex-row md:items-end justify-between gap-4">
                <div>
                    <h1 class="text-4xl font-extrabold text-slate-900 tracking-tight">Status <span
                            class="text-red-500">Akaun</span></h1>
                    <p class="text-slate-500 mt-2 font-medium">Urus langganan dan semak sejarah transaksi portal danakhairat.
                    </p>
                </div>
                <div class="px-4 py-2 rounded-full glass-card flex items-center gap-2">
                    <span class="relative flex h-3 w-3">
                        <span
                            class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-3 w-3 bg-red-500"></span>
                    </span>
                    <span class="text-xs font-bold text-red-600 uppercase tracking-widest">Tamat Tempoh</span>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">

                {{-- Main Content: Status & Renew --}}
                <div class="lg:col-span-8 space-y-6">
                    <div class="glass-card rounded-[2rem] p-8 md:p-10 border-red-100">
                        <div class="flex flex-col md:flex-row gap-8 items-center mb-10">
                            {{-- Visual Indicator --}}
                            <div class="relative animate-float">
                                <div
                                    class="w-32 h-32 rounded-full bg-gradient-to-tr from-red-500 to-orange-400 flex items-center justify-center shadow-lg shadow-red-200">
                                    <svg class="w-14 h-14 text-white" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                            d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                            </div>

                            <div class="text-center md:text-left flex-1">
                                <h2 class="text-2xl font-bold text-slate-800">Akses Sistem Terhad</h2>
                                <p class="text-slate-500 mt-2 leading-relaxed">
                                    Langganan anda telah tamat pada <span
                                        class="font-bold text-slate-800">{{ $expiredSubscription ? $expiredSubscription->end_date->format('d M Y') : '-' }}</span>.
                                    Sila lakukan pembaharuan untuk kembali menggunakan modul pengurusan kariah.
                                </p>

                                <div class="mt-6 flex flex-wrap justify-center md:justify-start gap-3">
                                    <div class="px-4 py-2 rounded-xl bg-white/50 border border-white flex flex-col">
                                        <span class="text-[10px] uppercase text-slate-400 font-bold tracking-tighter">Pakej
                                            Terakhir</span>
                                        <span class="text-sm font-bold text-slate-700">
                                            {{ $lastPackage->name ?? ($expiredSubscription->package->name ?? 'Standard') }}
                                        </span>
                                    </div>
                                    <div
                                        class="px-3 py-2 rounded-xl bg-white/50 border border-white flex flex-col min-w-[120px]">
                                        <span class="text-[10px] uppercase text-slate-400 font-bold tracking-tighter">Tamat
                                            Tempoh</span>
                                        <div class="flex items-baseline gap-2">
                                            @if ($expiredSubscription)
                                                @php
                                                    $days = now()->diffInDays($expiredSubscription->end_date, false);
                                                @endphp
                                                <span
                                                    class="text-sm font-bold {{ $days < 0 ? 'text-red-700' : 'text-emerald-700' }}">
                                                    {{ $days < 0 ? abs((int) $days) . ' Hari Lepas' : 'Tinggal ' . (int) $days . ' Hari' }}
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="border-t border-slate-100 pt-8">
                            <form action="{{ route('subscription.renew') }}" method="POST" enctype="multipart/form-data"
                                class="max-w-md mx-auto space-y-4">
                                @csrf
                                <input type="hidden" name="package_id" value="{{ $lastPackage->id }}">
                                {{-- Use the $displayAmount from controller - this will be submitted --}}
                                <input type="hidden" name="amount" value="{{ $displayAmount }}">

                                {{-- Bank Info Section with Correct Amount Logic --}}
                                <div class="space-y-4">
                                    {{-- Bank Details Card --}}
                                    <div class="bg-white rounded-xl p-5 border border-slate-200 shadow-sm">
                                        <h4 class="text-sm font-bold text-slate-800 mb-4 flex items-center gap-2">
                                            <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                                            </svg>
                                            Maklumat Bank
                                        </h4>

                                        <div class="space-y-3 text-sm">
                                            <div class="flex justify-between items-center">
                                                <span class="text-slate-500">Bank:</span>
                                                <span
                                                    class="font-semibold text-slate-900">{{ $bank->nama_bank ?? 'Bank Islam' }}</span>
                                            </div>
                                            <div class="flex justify-between items-center">
                                                <span class="text-slate-500">No. Akaun:</span>
                                                <span
                                                    class="font-mono font-bold text-indigo-700 bg-indigo-50 px-2 py-1 rounded">
                                                    {{ $bank->no_akaun ?? '1234-5678-9012-3456' }}
                                                </span>
                                            </div>
                                            <div class="flex justify-between items-center">
                                                <span class="text-slate-500">Nama Akaun:</span>
                                                <span
                                                    class="font-semibold text-slate-900">{{ $bank->nama_akaun ?? 'DANA KHAIRAT' }}</span>
                                            </div>
                                        </div>
                                    </div>

                                    {{-- Payment Summary Card --}}
                                    <div class="bg-slate-50 rounded-xl p-5 border border-slate-200">
                                        @php
                                            $originalPrice = $lastPackage->price;
                                            $savings = $originalPrice - $displayAmount;
                                        @endphp

                                        <div class="space-y-2 text-sm">
                                            <div class="flex justify-between items-center pb-2 border-b border-slate-200">
                                                <span class="text-slate-600">Jenis Langganan:</span>
                                                <span class="font-medium text-slate-900">
                                                    {{ $isRenewal ? 'Pembaharuan (Renewal)' : 'Baru (New)' }}
                                                </span>
                                            </div>

                                            <div class="flex justify-between items-center pt-2">
                                                <span class="text-base font-semibold text-slate-700">Jumlah Bayaran:</span>
                                                <div class="text-right">
                                                    <span class="font-bold text-slate-900 text-2xl">
                                                        RM{{ number_format($displayAmount, 2) }}
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>


                                <div class="space-y-1">
                                    <label class="block text-xs font-medium text-slate-600 ml-1">Resit Pembayaran <span
                                            class="text-red-500">*</span></label>

                                    {{-- Hidden file input --}}
                                    <input type="file" name="receipt" id="receipt" class="hidden"
                                        accept=".jpg,.jpeg,.png,.pdf" required>

                                    {{-- Custom upload button --}}
                                    <div class="grid grid-cols-1 gap-2">
                                        <button type="button" id="uploadButton"
                                            class="w-full border-2 border-dashed border-slate-200 rounded-lg p-4 hover:bg-slate-50 transition-colors flex items-center justify-center gap-2">
                                            <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
                                            </svg>
                                            <span class="text-sm font-medium text-slate-600">Pilih fail resit...</span>
                                        </button>

                                        {{-- Preview area (hidden by default) --}}
                                        <div id="previewArea" class="hidden">
                                            <div
                                                class="flex items-center justify-between p-3 bg-slate-50 rounded-lg border border-slate-200">
                                                <div class="flex items-center gap-3">
                                                    <div
                                                        class="w-10 h-10 bg-white rounded flex items-center justify-center overflow-hidden">
                                                        <img src="" id="imagePreview"
                                                            class="object-cover h-full w-full hidden">
                                                        <svg id="pdfIcon" class="w-6 h-6 text-slate-400 hidden"
                                                            fill="currentColor" viewBox="0 0 20 20">
                                                            <path
                                                                d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z" />
                                                        </svg>
                                                    </div>
                                                    <div>
                                                        <p class="text-sm font-medium text-slate-700" id="fileName"></p>
                                                        <p class="text-xs text-slate-500" id="fileSize"></p>
                                                    </div>
                                                </div>
                                                <button type="button" id="removeFile"
                                                    class="text-xs text-red-500 font-semibold hover:text-red-700">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                                    </svg>
                                                </button>
                                            </div>
                                        </div>
                                    </div>

                                    @error('receipt')
                                        <p class="text-[10px] text-red-500 mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <button type="submit"
                                    class="w-full bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-bold py-2.5 rounded-lg transition-all shadow-sm active:scale-[0.98] {{ $pendingOrder ? 'opacity-50 cursor-not-allowed' : '' }}"
                                    {{ $pendingOrder ? 'disabled' : '' }}>
                                    {{ $pendingOrder ? 'Sedang Diproses...' : 'Hantar Bayaran Pembaharuan' }}
                                </button>
                            </form>
                        </div>
                    </div>

                    {{-- Blocked Modules Visual --}}
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                        @foreach (['Kariah', 'Kewangan', 'Laporan', 'Tetapan'] as $modul)
                            <div class="glass-card p-4 rounded-2xl text-center opacity-60 grayscale">
                                <div
                                    class="w-10 h-10 mx-auto mb-2 bg-slate-100 rounded-lg flex items-center justify-center">
                                    <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                    </svg>
                                </div>
                                <span class="text-xs font-bold text-slate-600">{{ $modul }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>

                {{-- Side Panel: History --}}
                <div class="lg:col-span-4 space-y-6">
                    <div class="glass-card rounded-[2rem] p-6 h-full flex flex-col">
                        <h3 class="text-lg font-bold text-slate-800 mb-6 flex items-center gap-2">
                            <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            Transaksi Terkini
                        </h3>

                        <div class="space-y-4 history-scroll overflow-y-auto pr-2 max-h-[500px]">
                            @forelse($orderHistory as $order)
                                <div
                                    class="p-4 rounded-2xl bg-white/40 border border-white/60 hover:bg-white/80 transition-colors">
                                    <div class="flex justify-between items-start mb-2">
                                        <span
                                            class="text-xs font-bold text-slate-400">{{ $order->created_at->format('d M, Y') }}</span>
                                        <span
                                            class="text-sm font-black text-slate-800">RM{{ number_format($order->amount, 0) }}</span>
                                    </div>
                                    <div class="flex justify-between items-center">
                                        <p class="text-xs font-bold text-slate-700 truncate w-32">
                                            {{ $order->package->name ?? 'Pakej' }}</p>
                                        <span
                                            class="text-[9px] px-2 py-0.5 rounded-full font-black uppercase
                                            @if ($order->status === 'approved') bg-emerald-100 text-emerald-700
                                            @elseif($order->status === 'pending') bg-amber-100 text-amber-700
                                            @else bg-red-100 text-red-700 @endif">
                                            {{ $order->status }}
                                        </span>
                                    </div>
                                </div>
                            @empty
                                <div class="text-center py-10">
                                    <p class="text-xs font-bold text-slate-300 uppercase tracking-widest">Tiada Rekod</p>
                                </div>
                            @endforelse
                        </div>

                        <div class="mt-auto pt-6">
                            <div class="p-4 rounded-2xl bg-slate-900 text-white shadow-xl shadow-slate-200">
                                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Bantuan
                                    Teknikal</p>
                                <p class="text-xs text-slate-300 leading-relaxed mb-3">Hadapi masalah pembayaran?</p>
                                <a href="#"
                                    class="text-xs font-bold text-white flex items-center gap-1 hover:underline">
                                    Hubungi Admin
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M17 8l4 4m0 0l-4 4m4-4H3" />
                                    </svg>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const fileInput = document.getElementById('receipt');
            const uploadButton = document.getElementById('uploadButton');
            const previewArea = document.getElementById('previewArea');
            const imagePreview = document.getElementById('imagePreview');
            const pdfIcon = document.getElementById('pdfIcon');
            const fileName = document.getElementById('fileName');
            const fileSize = document.getElementById('fileSize');
            const removeFile = document.getElementById('removeFile');

            // Click upload button triggers file input
            uploadButton.addEventListener('click', function() {
                fileInput.click();
            });

            // When file is selected
            fileInput.addEventListener('change', function(e) {
                if (this.files && this.files[0]) {
                    const file = this.files[0];

                    // Check file size (2MB max)
                    if (file.size > 2 * 1024 * 1024) {
                        alert('Saiz fail mesti kurang dari 2MB');
                        this.value = '';
                        return;
                    }

                    // Check file type
                    const validTypes = ['image/jpeg', 'image/jpg', 'image/png', 'application/pdf'];
                    if (!validTypes.includes(file.type)) {
                        alert('Sila pilih fail JPG, PNG atau PDF');
                        this.value = '';
                        return;
                    }

                    // Show preview area
                    previewArea.classList.remove('hidden');

                    // Display file name and size
                    fileName.textContent = file.name;
                    fileSize.textContent = (file.size / 1024).toFixed(1) + ' KB';

                    // Show appropriate icon/preview
                    if (file.type.startsWith('image/')) {
                        // Show image preview
                        const reader = new FileReader();
                        reader.onload = function(e) {
                            imagePreview.src = e.target.result;
                            imagePreview.classList.remove('hidden');
                            pdfIcon.classList.add('hidden');
                        };
                        reader.readAsDataURL(file);
                    } else {
                        // Show PDF icon
                        imagePreview.classList.add('hidden');
                        pdfIcon.classList.remove('hidden');
                    }
                }
            });

            // Remove file
            removeFile.addEventListener('click', function() {
                fileInput.value = '';
                previewArea.classList.add('hidden');
                imagePreview.classList.add('hidden');
                pdfIcon.classList.add('hidden');
            });
        });
    </script>
@endsection
