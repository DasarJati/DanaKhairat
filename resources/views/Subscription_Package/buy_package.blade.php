@extends('layouts.app')

@section('title', 'Pakej Tahunan - e-Khairat')

@section('content')
    <div class="min-h-screen py-12 px-4 sm:px-6 lg:px-8 " x-data="{ step: 1, selectedPackage: null }">

        <!-- Tabs Navigation -->
        <div class="max-w-6xl mx-auto mb-8 border-b border-gray-200 hidden">
            <nav class="flex space-x-8" aria-label="Tabs">
                <a href="{{ route('subscription.package') }}"
                    class="py-4 px-1 inline-flex items-center text-sm font-bold {{ request()->routeIs('subscription.package') ? 'text-amber-600 border-b-2 border-amber-600' : 'text-gray-500 hover:text-gray-700' }}">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z">
                        </path>
                    </svg>
                    Langganan Baru
                </a>
                <a
                    class="py-4 px-1 inline-flex items-center text-sm font-bold {{ request()->routeIs('subscription.status') ? 'text-amber-600 border-b-2 border-amber-600' : 'text-gray-500 hover:text-gray-700' }}">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    Status Langganan
                    @if (isset($pendingOrder) && $pendingOrder)
                        <span class="ml-2 bg-amber-500 text-white text-xs font-bold px-2 py-0.5 rounded-full">1</span>
                    @endif
                </a>
            </nav>
        </div>

        <!-- Check for pending order -->
        @if (isset($pendingOrder) && $pendingOrder)
            <div class="max-w-6xl mx-auto mb-12">
                <div
                    class="relative overflow-hidden bg-white/40 backdrop-blur-2xl border border-white/60 rounded-[40px] p-8 md:p-10 shadow-[0_32px_64px_-15px_rgba(0,0,0,0.05)]">

                    <div class="absolute -top-24 -right-24 w-64 h-64 bg-amber-200/20 rounded-full blur-3xl"></div>
                    <div class="absolute -bottom-24 -left-24 w-64 h-64 bg-amber-100/30 rounded-full blur-3xl"></div>

                    <div class="relative z-10">
                        <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 mb-10">
                            <div class="flex items-center">
                                <div class="relative">
                                    <div
                                        class="w-20 h-20 bg-amber-50 rounded-3xl flex items-center justify-center shadow-inner">
                                        <svg class="w-10 h-10 text-amber-500" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                    </div>
                                    <div class="absolute -top-1 -right-1 flex h-4 w-4">
                                        <span
                                            class="animate-ping absolute inline-flex h-full w-full rounded-full bg-amber-400 opacity-75"></span>
                                        <span
                                            class="relative inline-flex rounded-full h-4 w-4 bg-amber-500 border-2 border-white"></span>
                                    </div>
                                </div>
                                <div class="ml-6">
                                    <div class="flex flex-wrap items-center gap-3">
                                        <h3 class="text-2xl font-black text-gray-900 tracking-tight">Status Pesanan</h3>
                                        <span
                                            class="bg-amber-100 text-amber-700 text-[10px] font-black px-4 py-1.5 rounded-full uppercase tracking-[0.2em]">
                                            Processing
                                        </span>
                                    </div>
                                    <p class="text-gray-400 text-sm font-bold uppercase tracking-widest mt-2">ID:
                                        #{{ str_pad($pendingOrder->id, 6, '0', STR_PAD_LEFT) }}</p>
                                </div>
                            </div>

                            <div class="md:text-right bg-white/50 p-6 rounded-3xl border border-white/50 shadow-sm">
                                <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Nilai
                                    Transaksi</p>
                                <p class="text-4xl font-black text-gray-900">RM
                                    {{ number_format($pendingOrder->amount, 2) }}</p>
                            </div>
                        </div>

                        <div class="mb-10">
                            <div class="flex justify-between items-center mb-4 px-2">
                                <span class="text-xs font-black text-amber-600 uppercase tracking-widest">Langkah 2:
                                    Pengesahan Admin</span>
                                <span class="text-xs font-bold text-gray-400 uppercase tracking-widest">40% Selesai</span>
                            </div>
                            <div class="h-4 bg-gray-100/50 rounded-full overflow-hidden p-1 border border-white/50">
                                <div class="h-full bg-gradient-to-r from-amber-400 to-amber-500 rounded-full shadow-lg transition-all duration-1000"
                                    style="width: 40%"></div>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-10">
                            <div
                                class="bg-white/60 backdrop-blur-md rounded-[32px] p-6 border border-white/80 shadow-sm transition-transform hover:scale-[1.02]">
                                <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-3">Pakej
                                    Langganan</p>
                                <p class="text-lg font-black text-gray-900 leading-tight">
                                    {{ $pendingOrder->package->name ?? 'Pakej Tahunan' }}</p>
                                <p class="text-sm text-amber-600 font-bold mt-1">Siri 2026/2027</p>
                            </div>

                            <div
                                class="bg-white/60 backdrop-blur-md rounded-[32px] p-6 border border-white/80 shadow-sm transition-transform hover:scale-[1.02]">
                                <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-3">Diterima Pada
                                </p>
                                <p class="text-lg font-black text-gray-900 leading-tight">
                                    {{ $pendingOrder->created_at->format('d M Y') }}</p>
                                <p class="text-sm text-gray-400 font-bold mt-1">
                                    {{ $pendingOrder->created_at->format('h:i A') }}</p>
                            </div>

                            <div
                                class="bg-white/60 backdrop-blur-md rounded-[32px] p-6 border border-white/80 shadow-sm transition-transform hover:scale-[1.02]">
                                <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-3">Disahkan
                                    Sebelum</p>
                                <p class="text-lg font-black text-gray-900 leading-tight">
                                    {{ $pendingOrder->created_at->addDays(3)->format('d M Y') }}</p>
                                <p class="text-sm text-orange-500 font-bold mt-1">11:59 PM</p>
                            </div>
                        </div>

                        <div
                            class="flex flex-col md:flex-row items-center justify-between gap-6 pt-8 border-t border-white/60">
                            <div class="flex items-start gap-4 max-w-md">
                                <div class="p-2 bg-amber-100 rounded-full text-amber-600">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                                <p class="text-xs font-bold text-gray-500 leading-relaxed uppercase tracking-wider">
                                    Pesanan sedang dalam giliran pengesahan. Sila hubungi admin jika status tidak berubah
                                    dalam 3 hari.
                                </p>
                            </div>

                            <div class="flex items-center gap-4 w-full md:w-auto">
                                @if ($pendingOrder->receipt_path)
                                    <a href="{{ asset($pendingOrder->receipt_path) }}" target="_blank"
                                        class="flex-1 md:flex-none text-center px-8 py-4 bg-white/80 border border-gray-200 rounded-2xl text-xs font-black text-gray-900 uppercase tracking-widest hover:bg-gray-900 hover:text-white transition-all shadow-sm">
                                        View Receipt
                                    </a>
                                @endif

                                {{-- <a
                                    class="flex-1 md:flex-none text-center px-8 py-4 bg-amber-500 text-white rounded-2xl text-xs font-black uppercase tracking-widest hover:bg-amber-600 transition-all shadow-xl shadow-amber-200/50">
                                    Refresh Status
                                </a> --}}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        @if ($hasActiveSubscription && $activeSubscription)
            <div class="max-w-6xl mx-auto mb-8">
                <div class="relative overflow-hidden bg-white border border-green-200 rounded-[40px] p-8 md:p-10 shadow-xl">
                    <div class="absolute -top-16 -right-16 w-48 h-48 bg-green-100/40 rounded-full blur-3xl"></div>

                    <div class="flex flex-col md:flex-row items-center justify-between gap-6">
                        <div class="flex items-center gap-6">
                            <div class="w-16 h-16 bg-green-100 rounded-2xl flex items-center justify-center">
                                <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <div>
                                <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Status
                                    Langganan</p>
                                <h3 class="text-xl font-black text-gray-900">Langganan Aktif</h3>
                                <p class="text-sm text-green-600 font-bold mt-1">
                                    Pakej: {{ $approvedOrder->package->nama ?? '-' }}
                                </p>
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-4 text-center">
                            <div class="bg-gray-50 rounded-2xl p-4">
                                <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Mula</p>
                                <p class="text-sm font-black text-gray-900">
                                    {{ $activeSubscription->start_date->format('d M Y') }}</p>
                            </div>
                            <div class="bg-gray-50 rounded-2xl p-4">
                                <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Tamat</p>
                                <p class="text-sm font-black text-gray-900">
                                    {{ $activeSubscription->end_date->format('d M Y') }}</p>
                            </div>
                        </div>

                        <span
                            class="bg-green-500 text-white text-xs font-black px-6 py-3 rounded-2xl uppercase tracking-widest shadow-lg shadow-green-100">
                            ✓ Aktif
                        </span>
                    </div>
                </div>
            </div>
        @endif

        @if (!$hasActiveSubscription && !$pendingOrder)
            <div x-show="step === 1" x-transition:enter="transition ease-out duration-300">
                <div class="max-w-6xl mx-auto mb-16 text-left">
                    <p class="text-amber-500 font-bold tracking-widest text-xs uppercase mb-2">Pelan Langganan</p>
                    <h1 class="text-3xl md:text-3xl font-black text-gray-900 leading-tight">
                        Satu sistem, <span class="text-amber-500">Akses Penuh.</span>
                    </h1>
                </div>

                <!-- Responsive Package Cards -->
                <div class="relative">
                    <!-- Carousel wrapper — scrollable on mobile/tablet, grid on desktop -->
                    <div id="packageCarousel"
                        class="flex xl:grid xl:grid-cols-3 overflow-x-auto xl:overflow-visible snap-x xl:snap-none scroll-smooth gap-4 md:gap-6 xl:gap-8 pb-4 xl:pb-0 px-1 xl:px-0 xl:items-center xl:max-w-7xl xl:mx-auto z-10"
                        style="-webkit-overflow-scrolling: touch; scrollbar-width: none; msOverflowStyle: none;">

                        @foreach ($package as $index => $p)
                            @php $isPopular = $index === 1; @endphp

                            <div
                                class="snap-center flex-shrink-0 xl:flex-shrink w-[80vw] sm:w-[60vw] md:w-[45vw] xl:w-auto relative z-10
                    {{ $isPopular ? 'xl:-mt-4 xl:-mb-4' : '' }}">

                                @if ($isPopular)
                                    <div class="absolute -top-2 right-8 z-50">
                                        <span
                                            class="bg-gray-900 text-white text-[10px] font-bold px-4 py-1.5 rounded-full uppercase tracking-widest shadow-lg">
                                            Popular
                                        </span>
                                    </div>
                                @endif

                                <div
                                    class="relative bg-white border h-full flex flex-col
                        {{ $isPopular ? 'border-amber-400 shadow-2xl xl:scale-105 xl:z-10' : 'border-gray-100 shadow-xl' }} 
                        rounded-[32px] md:rounded-[40px] p-7 md:p-9 xl:p-10
                        transition-all duration-300 hover:shadow-2xl hover:-translate-y-1">

                                    <!-- Package Header -->
                                    <div class="mb-5 md:mb-6">
                                        <h3 class="text-xs md:text-sm font-bold text-gray-400 uppercase tracking-widest">
                                            {{ $p->nama }}
                                        </h3>
                                        <div class="flex items-baseline mt-2">
                                            <span class="text-4xl md:text-5xl font-black text-gray-900">
                                                RM{{ number_format($p->price, 0) }}
                                            </span>
                                            <span class="ml-2 text-gray-400 font-medium text-sm">/Daftar</span>
                                        </div>
                                        <div class="mt-3 md:mt-4">
                                            <span
                                                class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold 
                                     {{ $isPopular ? 'bg-amber-50 text-amber-700' : 'bg-gray-50 text-gray-500' }}">
                                                RENEWAL: RM{{ number_format($p->renewal_price, 0) }}/thn
                                            </span>
                                        </div>
                                    </div>

                                    <!-- Features -->
                                    <ul class="space-y-3 md:space-y-4 xl:space-y-5 mb-8 md:mb-10 flex-grow">
                                        @foreach ([['popular' => true, 'text' => 'Maksima ' . $p->limit_kariah . ' Ahli Khairat'], ['popular' => false, 'text' => 'Akses Penuh Sistem (Tanpa Had)'], ['popular' => false, 'text' => 'Urus Ahli Khairat secara Digital'], ['popular' => false, 'text' => 'Laporan Automatik & Analitik']] as $feature)
                                            <li class="flex items-start">
                                                <div
                                                    class="flex-shrink-0 w-5 h-5 md:w-6 md:h-6 rounded-full 
                                    {{ $feature['popular'] ? 'bg-amber-500' : 'bg-amber-100' }} 
                                    flex items-center justify-center mr-3 mt-0.5">
                                                    <svg class="w-3 h-3 md:w-3.5 md:h-3.5 {{ $feature['popular'] ? 'text-white' : 'text-amber-600' }}"
                                                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="3" d="M5 13l4 4L19 7" />
                                                    </svg>
                                                </div>
                                                <span
                                                    class="{{ $feature['popular'] ? 'text-gray-700 font-bold' : 'text-gray-500 font-medium' }} text-xs md:text-sm">
                                                    {{ $feature['text'] }}
                                                </span>
                                            </li>
                                        @endforeach
                                    </ul>

                                    <!-- CTA Button -->
                                    <button
                                        @click="selectedPackage = { id: '{{ $p->id }}', name: '{{ $p->nama }}', price: '{{ $p->price }}' }; step = 2"
                                        class="w-full py-3 md:py-4 rounded-xl md:rounded-2xl font-bold text-sm md:text-base transition-all duration-300
                           {{ $isPopular
                               ? 'bg-amber-500 text-white hover:bg-amber-600 shadow-lg shadow-amber-100'
                               : 'bg-white border-2 border-gray-900 text-gray-900 hover:bg-gray-900 hover:text-white' }}">
                                        {{ $isPopular ? 'Daftar Pakej Ini' : 'Pilih Pakej' }}
                                    </button>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Dot indicators — hidden on xl -->
                    <div class="flex xl:hidden justify-center gap-2 mt-5">
                        @foreach ($package as $index => $p)
                            <button onclick="scrollToCard({{ $index }})" id="dot-{{ $index }}"
                                class="h-2 rounded-full transition-all duration-300 {{ $index === 0 ? 'bg-amber-500 w-6' : 'bg-gray-300 w-2' }}">
                            </button>
                        @endforeach
                    </div>
                </div>
            </div>

            <div x-show="step === 2" x-transition:enter="transition ease-out duration-300" class="max-w-3xl mx-auto"
                x-data="{ isZoomed: false }">

                <div
                    class="bg-white border border-gray-100 shadow-2xl rounded-[40px] p-8 md:p-12 relative overflow-hidden">

                    <button @click="step = 1"
                        class="mb-8 flex items-center text-sm font-bold text-gray-400 hover:text-amber-500 transition-colors uppercase tracking-widest">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7">
                            </path>
                        </svg>
                        Kembali
                    </button>

                    <div class="mb-8 border-b border-gray-50 pb-8">
                        <h2 class="text-sm font-bold text-amber-500 uppercase tracking-widest mb-1">Pengesahan Pembayaran
                        </h2>
                        <div class="flex justify-between items-end">
                            <h3 class="text-3xl font-black text-gray-900" x-text="selectedPackage?.name"></h3>
                            <div class="text-right">
                                <span class="text-4xl font-black text-gray-900">RM <span
                                        x-text="selectedPackage ? parseFloat(selectedPackage.price).toFixed(2) : '0.00'"></span></span>
                            </div>
                        </div>
                    </div>

                    <div class="space-y-8">
                        <div
                            class="grid grid-cols-1 md:grid-cols-2 gap-6 p-6 bg-amber-50/30 rounded-[32px] border border-amber-100/50 items-center">

                            <div class="text-center space-y-3">
                                <p class="text-amber-700 text-[10px] font-black uppercase tracking-widest">Imbas QR DuitNow
                                </p>
                                <div class="relative inline-block cursor-zoom-in group" @click="isZoomed = !isZoomed">
                                    <img src="https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=eKhairatPayment"
                                        alt="QR"
                                        class="w-40 h-40 mx-auto rounded-2xl shadow-md bg-white p-2 transition-transform duration-300"
                                        :class="isZoomed ? 'scale-[1.8] z-50 relative' : 'scale-100'">

                                    <div x-show="!isZoomed"
                                        class="absolute inset-0 bg-black/0 group-hover:bg-black/5 transition-colors rounded-2xl flex items-center justify-center">
                                        <svg class="w-6 h-6 text-white opacity-0 group-hover:opacity-100 transition-opacity"
                                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m-3 0H7">
                                            </path>
                                        </svg>
                                    </div>
                                </div>
                                <p class="text-[9px] text-gray-400 font-bold uppercase tracking-tighter">Klik QR untuk zoom
                                </p>
                            </div>

                            <div
                                class="space-y-4 border-t md:border-t-0 md:border-l border-amber-200/50 pt-6 md:pt-0 md:pl-8">
                                <div>
                                    <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Nama Bank</p>
                                    <p class="text-lg font-black text-gray-900">{{ $bank->nama_bank }}</p>
                                </div>
                                <div>
                                    <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Nama Pemilik
                                    </p>
                                    <p class="text-lg font-black text-gray-900 uppercase">{{ $bank->nama_akaun }}</p>
                                </div>
                                <div>
                                    <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">No. Akaun</p>
                                    <div class="flex items-center space-x-2">
                                        <p class="text-xl font-black text-amber-600 tracking-wider">5{{ $bank->no_akaun }}
                                        </p>
                                        <button @click="navigator.clipboard.writeText('562100123456')"
                                            class="p-1 hover:bg-amber-100 rounded text-amber-600 transition-colors">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 012-2v-8a2 2 0 01-2-2h-8a2 2 0 01-2 2v8a2 2 0 012 2z">
                                                </path>
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <form action="{{ route('subscription.store') }}" method="POST" enctype="multipart/form-data"
                            class="space-y-6">
                            @csrf
                            <input type="hidden" name="package_id" x-model="selectedPackage.id">

                            <div class="space-y-2">
                                <label class="text-xs font-black text-gray-400 uppercase tracking-widest ml-1">Muat Naik
                                    Bukti
                                    Bayaran</label>
                                <div
                                    class="group relative border-2 border-dashed border-gray-200 rounded-2xl p-4 hover:border-amber-400 transition-colors bg-gray-50/50">
                                    <input type="file" name="receipt"
                                        class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-black file:uppercase file:bg-gray-900 file:text-white hover:file:bg-amber-500 cursor-pointer"
                                        required>
                                </div>
                            </div>

                            <button type="submit"
                                class="w-full bg-amber-500 text-white py-5 rounded-2xl font-black text-lg uppercase tracking-widest hover:bg-amber-600 shadow-xl shadow-amber-100 transition-all transform active:scale-95">
                                Sahkan Langganan
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        @endif


    </div>

    <script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>

   <script>
    const carousel = document.getElementById('packageCarousel');
    const dots = document.querySelectorAll('[id^="dot-"]');

    function scrollToCard(index) {
        const cards = carousel.querySelectorAll('.snap-center');
        if (cards[index]) {
            cards[index].scrollIntoView({ behavior: 'smooth', block: 'nearest', inline: 'center' });
        }
    }

    if (carousel) {
        carousel.addEventListener('scroll', () => {
            const cards = carousel.querySelectorAll('.snap-center');
            let activeIndex = 0;
            let minDistance = Infinity;

            cards.forEach((card, i) => {
                const rect = card.getBoundingClientRect();
                const carouselRect = carousel.getBoundingClientRect();
                const distance = Math.abs(rect.left - carouselRect.left);
                if (distance < minDistance) {
                    minDistance = distance;
                    activeIndex = i;
                }
            });

            dots.forEach((dot, i) => {
                if (i === activeIndex) {
                    dot.classList.add('bg-amber-500', 'w-6');
                    dot.classList.remove('bg-gray-300', 'w-2');
                } else {
                    dot.classList.remove('bg-amber-500', 'w-6');
                    dot.classList.add('bg-gray-300', 'w-2');
                }
            });
        });
    }
</script>
@endsection
