@extends('layouts.app')

@section('title', 'Detail Ahli: ' . $user->nama)

@section('content')
    <div class="container mx-auto px-4 py-8 max-w-7xl">
        <div class="flex flex-col md:flex-row md:items-center justify-between mb-8 gap-4">
            <div>
                <h1 class="text-3xl font-black text-gray-900 tracking-tight">{{ $user->nama }}</h1>
                <div class="flex items-center gap-3 mt-2">
                    <span class="px-3 py-1 bg-gray-100 text-gray-600 text-xs font-bold rounded-full">
                        {{-- ID #{{ $user->id }}</span> --}}
                    <span class="text-gray-400 text-sm italic">Mendaftar pada {{ $user->created_at->format('d M Y') }}</span>
                </div>
            </div>

            <div class="flex items-center gap-3">
                @if ($user->approval_status != 'APPROVED')
                    <form action="{{ route('approve.kariah', $user->id) }}" method="POST"> @csrf
                        <button
                            class="bg-emerald-600 hover:bg-emerald-700 text-white px-6 py-2.5 rounded-xl font-bold text-sm shadow-lg shadow-emerald-200 transition-all">
                            <i class="fas fa-check mr-2"></i> Terima
                        </button>
                    </form>
                    <form action="{{ route('reject.kariah', $user->id) }}" method="POST"> @csrf
                        <button
                            class="bg-red-600 hover:bg-red-700 text-white px-6 py-2.5 rounded-xl font-bold text-sm shadow-lg shadow-red-200 transition-all">
                            <i class="fas fa-times mr-2"></i> Tolak
                        </button>
                    </form>
                @endif
                <a href="{{ route('kariah.list.pengesahan') }}"
                    class="bg-white border border-gray-200 text-gray-600 px-6 py-2.5 rounded-xl font-bold text-sm hover:bg-gray-50 transition-all">
                    Kembali
                </a>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">

            <div class="lg:col-span-8 space-y-8">

                <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="p-8">
                        <h2 class="text-xs font-black text-djariah-600 uppercase tracking-[0.2em] mb-6">Profil Utama</h2>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                            <div>
                                <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">No.
                                    Kad Pengenalan</label>
                                <p class="text-gray-900 font-bold text-lg">{{ $user->ic_number }}</p>
                            </div>
                            <div>
                                <label
                                    class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Tarikh
                                    Lahir</label>
                                <p class="text-gray-900 font-bold text-lg">
                                    {{ $user->tarikh_lahir ? $user->tarikh_lahir->format('d/m/Y') : '-' }}</p>
                            </div>
                            <div>
                                <label
                                    class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Umur</label>
                                <p class="text-gray-900 font-bold text-lg">{{ $user->umur }} Tahun</p>
                            </div>
                            <div>
                                <label
                                    class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Jantina</label>
                                <p class="text-gray-900 font-bold text-lg">{{ $user->jantina }}</p>
                            </div>
                            <div>
                                <label
                                    class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Bangsa</label>
                                <p class="text-gray-900 font-bold text-lg">{{ $user->bangsa }}</p>
                            </div>
                            <div>
                                <label
                                    class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Status
                                    Perkahwinan</label>
                                <p class="text-gray-900 font-bold text-lg">{{ $user->statususer }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="p-8">
                        <h2 class="text-xs font-black text-djariah-600 uppercase tracking-[0.2em] mb-6">Akaun & Perhubungan
                        </h2>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                            <div>
                                <label
                                    class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Emel</label>
                                <p class="text-gray-900 font-bold">{{ $user->email }}</p>
                            </div>
                            <div>
                                <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">No.
                                    Telefon</label>
                                <p class="text-gray-900 font-bold">{{ $user->telefon_bimbit }}</p>
                            </div>
                            <div class="md:col-span-2">
                                <label
                                    class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Alamat
                                    Penuh</label>
                                <p class="text-gray-700 font-medium leading-relaxed">
                                    {{ $user->alamat ?: 'Tiada maklumat alamat' }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tanggungan Section -->
                <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="p-8">
                        <div class="flex items-center justify-between mb-6">
                            <h2 class="text-xs font-black text-djariah-600 uppercase tracking-[0.2em]">Maklumat Tanggungan</h2>
                            <span class="px-3 py-1 bg-gray-100 text-gray-600 text-xs font-bold rounded-full">
                                {{ $user->tanggungan->count() }} Tanggungan
                            </span>
                        </div>

                        @if ($user->tanggungan && $user->tanggungan->count() > 0)
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                @foreach ($user->tanggungan as $tanggungan)
                                    <div class="bg-gray-50 rounded-2xl p-5 border border-gray-100 hover:border-djariah-600/30 transition-all">
                                        <div class="flex items-start justify-between mb-3">
                                            <div class="flex items-center gap-3">
                                                <div class="w-10 h-10 rounded-full bg-djariah-600/10 flex items-center justify-center">
                                                    <i class="fas fa-user text-djariah-600 text-sm"></i>
                                                </div>
                                                <div>
                                                    <p class="font-bold text-gray-900 text-sm">{{ $tanggungan->nama }}</p>
                                                    <p class="text-[10px] font-medium text-gray-400 uppercase tracking-wider">
                                                        {{ $tanggungan->hubungan }}
                                                    </p>
                                                </div>
                                            </div>
                                            @if ($tanggungan->oku)
                                                <span class="px-2 py-0.5 bg-amber-100 text-amber-700 text-[10px] font-bold rounded-full">
                                                    OKU
                                                </span>
                                            @endif
                                        </div>

                                        <div class="space-y-2">
                                            <div class="flex items-center gap-2 text-xs">
                                                <i class="fas fa-id-card text-gray-400 w-4"></i>
                                                <span class="text-gray-600 font-medium">{{ $tanggungan->ic_number }}</span>
                                            </div>
                                            @if ($tanggungan->document_path)
                                                <div class="flex items-center gap-2 text-xs">
                                                    <i class="fas fa-file text-gray-400 w-4"></i>
                                                    <a href="{{ asset($tanggungan->document_path) }}" target="_blank" 
                                                       class="text-djariah-600 hover:text-djariah-700 font-medium transition-colors">
                                                        Lihat Dokumen <i class="fas fa-external-link-alt ml-1 text-[10px]"></i>
                                                    </a>
                                                </div>
                                            @else
                                                <div class="flex items-center gap-2 text-xs">
                                                    <i class="fas fa-file text-gray-300 w-4"></i>
                                                    <span class="text-gray-400 italic">Tiada dokumen</span>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="h-32 flex items-center justify-center bg-gray-50 rounded-2xl border-2 border-dashed border-gray-100">
                                <span class="text-gray-400 text-sm italic">Tiada maklumat tanggungan</span>
                            </div>
                        @endif
                    </div>
                </div>

                
            </div>

            <div class="lg:col-span-4 space-y-6">

                <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-6">
                    <h3 class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-4">Status Permohonan</h3>
                    <div class="space-y-4">
                        <div class="flex justify-between items-center pb-3 border-b border-gray-50">
                            <span class="text-sm font-medium text-gray-500">Keahlian</span>
                            <span
                                class="px-3 py-1 rounded-lg text-[10px] font-black {{ $user->approval_status == 'APPROVED' ? 'bg-green-100 text-green-700' : 'bg-amber-100 text-amber-700' }}">
                                {{ $user->approval_status }}
                            </span>
                        </div>
                        <div class="flex justify-between items-center pb-3 border-b border-gray-50">
                            <span class="text-sm font-medium text-gray-500">Harga Pendaftaran dan Ahli</span>
                            <span class="px-3 py-1 rounded-lg text-md font-black bg-gray-100 text-gray-700">
                                {{ $user->amount ? 'RM ' . number_format($user->amount, 2) : '-' }}
                            </span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-sm font-medium text-gray-500">Terma & Syarat</span>
                            <span class="text-xs font-bold {{ $user->agree_terms ? 'text-green-600' : 'text-red-600' }}">
                                {{ $user->agree_terms ? 'Bersetuju' : 'Tidak' }}
                            </span>
                        </div>
                    </div>
                </div>

                

                {{-- <div class="text-center px-6">
                    <p class="text-[10px] font-medium text-gray-400 uppercase">Kemaskini Terakhir</p>
                    <p class="text-xs font-bold text-gray-500">{{ $user->updated_at->format('d/m/Y H:i:s') }}</p>
                </div> --}}
            </div>
        </div>
    </div>

    <div id="receiptModal"
        class="fixed inset-0 bg-gray-900/90 backdrop-blur-sm hidden z-50 flex items-center justify-center p-4 transition-all duration-300"
        onclick="closeReceiptModal()">

        <div class="relative max-w-4xl w-full max-h-[90vh] transform transition-transform scale-95 duration-300"
            id="modalContent" onclick="event.stopPropagation()">

            <button onclick="closeReceiptModal()"
                class="absolute -top-12 right-0 text-white hover:text-orange-400 transition flex items-center gap-2 font-bold uppercase tracking-widest text-xs">
                Tutup <i class="fas fa-times text-lg"></i>
            </button>

            <div class="bg-transparent p-2 overflow-hidden flex items-center justify-center">
                <!-- Image display (hidden by default) - now 50% size -->
                <img id="modalReceiptImage" src="" alt="Resit Pembayaran"
                    class="w-2/5 h-auto rounded-2xl shadow-inner hidden mx-auto">

                <!-- PDF display (hidden by default) -->
                <div id="modalPdfContainer" class="hidden w-full">
                    <iframe id="modalReceiptPdf" src="" class="w-full h-[80vh] rounded-2xl"
                        frameborder="0"></iframe>
                </div>

                <!-- Loading indicator -->
                <div id="modalLoading" class="flex flex-col items-center justify-center p-12">
                    <i class="fas fa-spinner fa-pulse text-djariah-600 text-4xl mb-4"></i>
                    <p class="text-gray-500 text-sm">Memuatkan dokumen...</p>
                </div>
            </div>

            <div class="mt-4 text-center">
                <p class="text-white/60 text-xs font-medium">Klik di luar gambar untuk kembali</p>
            </div>
        </div>
    </div>


    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #fbfbfb;
        }

        .text-djariah-600 {
            color: #f97316;
        }
        .border-djariah-600\/30 {
            border-color: rgba(249, 115, 22, 0.3);
        }
        .bg-djariah-600\/10 {
            background-color: rgba(249, 115, 22, 0.1);
        }
    </style>

    <script>
        function openReceiptModal(src, type) {
            const modal = document.getElementById('receiptModal');
            const modalImg = document.getElementById('modalReceiptImage');
            const modalPdf = document.getElementById('modalReceiptPdf');
            const modalPdfContainer = document.getElementById('modalPdfContainer');
            const modalLoading = document.getElementById('modalLoading');
            const content = document.getElementById('modalContent');

            // Hide all content first
            modalImg.classList.add('hidden');
            modalPdfContainer.classList.add('hidden');
            modalLoading.classList.remove('hidden');

            // Show the modal
            modal.classList.remove('hidden');

            if (type === 'pdf') {
                // Handle PDF
                modalPdfContainer.classList.remove('hidden');

                // Add timestamp to prevent caching
                const pdfSrc = src + '?t=' + new Date().getTime();
                modalPdf.src = pdfSrc;

                // Hide loading when iframe loads
                modalPdf.onload = function() {
                    modalLoading.classList.add('hidden');
                };

                // Timeout fallback
                setTimeout(() => {
                    modalLoading.classList.add('hidden');
                }, 3000);
            } else {
                // Handle Image
                modalImg.classList.remove('hidden');
                modalImg.src = src;

                // Hide loading when image loads
                modalImg.onload = function() {
                    modalLoading.classList.add('hidden');
                };

                // Timeout fallback
                setTimeout(() => {
                    modalLoading.classList.add('hidden');
                }, 2000);
            }

            // Trigger animation
            setTimeout(() => {
                content.classList.remove('scale-95');
                content.classList.add('scale-100');
            }, 10);

            // Prevent body scrolling when modal is open
            document.body.style.overflow = 'hidden';
        }

        function closeReceiptModal() {
            const modal = document.getElementById('receiptModal');
            const content = document.getElementById('modalContent');
            const modalPdf = document.getElementById('modalReceiptPdf');

            // Reverse animation
            content.classList.remove('scale-100');
            content.classList.add('scale-95');

            // Clear PDF src to stop loading
            if (modalPdf) {
                modalPdf.src = '';
            }

            // Hide after animation finishes
            setTimeout(() => {
                modal.classList.add('hidden');
                document.body.style.overflow = 'auto';
            }, 200);
        }

        // Close on Escape key
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') closeReceiptModal();
        });

        // Handle PDF download option (optional)
        function downloadReceipt(src, filename) {
            const link = document.createElement('a');
            link.href = src;
            link.download = filename || 'resit-pembayaran';
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
        }
    </script>
@endsection