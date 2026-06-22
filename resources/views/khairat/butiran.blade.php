@extends('layouts.app')
@section('title', 'Details Khairat')
@section('content')

    <style>
        #claimModal {
            transition: opacity 0.3s ease;
        }

        #claimModal:not(.hidden) {
            display: flex;
            align-items: flex-start;
            justify-content: center;
        }

        @keyframes modalSlideIn {
            from {
                transform: translateY(-50px);
                opacity: 0;
            }

            to {
                transform: translateY(0);
                opacity: 1;
            }
        }

        #claimModal .relative {
            animation: modalSlideIn 0.3s ease-out;
        }

        /* Mobile optimizations */
        @media (max-width: 640px) {
            .container {
                padding-left: 0.75rem;
                padding-right: 0.75rem;
            }

            .card-padding {
                padding: 1rem;
            }

            .text-responsive {
                font-size: 0.875rem;
            }
        }

        @media (min-width: 641px) and (max-width: 768px) {
            .container {
                padding-left: 1rem;
                padding-right: 1rem;
            }
        }
    </style>

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#1e40af',
                        secondary: '#3b82f6',
                        accent: '#f59e0b',
                        success: '#10b981',
                        warning: '#f59e0b',
                        danger: '#ef4444',
                        light: '#f8fafc',
                        dark: '#1e293b'
                    }
                }
            }
        }
    </script>

    <div class="max-w-8xl px-3 sm:px-4 py-4 sm:py-8">
        <!-- Header dengan navigasi kembali -->
        <header class="mb-4 sm:mb-8">
            <div class="flex flex-wrap items-center mb-3 sm:mb-4">
                <a href="/list"
                    class="flex items-center text-primary hover:text-secondary transition-colors mr-3 sm:mr-4 text-sm sm:text-base">
                    <i class="fas fa-arrow-left mr-1 sm:mr-2"></i>
                    <span>Kembali ke Senarai</span>
                </a>
                <div class="h-4 w-px bg-gray-300 mx-2 sm:mx-4"></div>
                <h1 class="text-lg sm:text-xl md:text-2xl font-bold text-dark">Butiran Keluarga</h1>
            </div>

            <!-- Maklumat Ketua Keluarga -->
            <div class="rounded-xl p-4 sm:p-6 mb-4 sm:mb-6 bg-gray-50 border opacity-80 shadow-stone-600 shadow-2xl">
                <div class="flex flex-col md:flex-row md:items-center justify-between gap-3 sm:gap-4">
                    <div class="flex items-center mb-3 md:mb-0">
                        <div
                            class="flex-shrink-0 h-12 w-12 sm:h-16 sm:w-16 border shadow-stone-800 shadow-2xl bg-gradient-to-br from-blue-600 to-blue-700 rounded-xl flex items-center justify-center text-white font-bold text-base sm:text-xl mr-3 sm:mr-4">
                            {{ collect(explode(' ', $ahli->nama))->map(fn($n) => strtoupper(substr($n, 0, 1)))->take(2)->implode('') }}
                        </div>
                        <div>
                            <div class="flex flex-wrap gap-2 items-center">
                                <h2 class="text-lg sm:text-xl font-bold text-gray-900">{{ $ahliKariah->nama }}</h2>
                                <span
                                    class="inline-flex items-center gap-1.5 px-2 sm:px-3 py-1 rounded-full text-[8px] sm:text-[10px] font-bold ring-1 ring-inset 
                                {{ $ahliKariah->status === 'active' ? 'bg-emerald-50 text-emerald-700 ring-emerald-600/20' : 'bg-rose-50 text-rose-700 ring-rose-600/20' }}">
                                    <span class="relative flex h-1.5 w-1.5 sm:h-2 sm:w-2">
                                        @if ($ahliKariah->status === 'active')
                                            <span
                                                class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                                            <span
                                                class="relative inline-flex rounded-full h-1.5 w-1.5 sm:h-2 sm:w-2 bg-emerald-500"></span>
                                        @else
                                            <span
                                                class="relative inline-flex rounded-full h-1.5 w-1.5 sm:h-2 sm:w-2 bg-rose-500"></span>
                                        @endif
                                    </span>
                                    {{ $ahliKariah->status === 'active' ? 'Aktif' : 'mati' }}


                                </span>
                            </div>
                            <p class="text-gray-600 text-xs sm:text-sm break-words">
                                Ketua Keluarga • {{ $ahli->ic }} • {{ $ahli->notel }}
                            </p>
                            <p class="text-gray-500 text-xs sm:text-sm break-words">{{ $ahli->alamat }}</p>
                        </div>
                    </div>

                    @php
                        // Use the latest subscription from controller (includes expired)
                        $subscription =
                            $subscriptionForDisplay ?? ($latestSubscription ?? ($activeSubscription ?? null));

                        $start = $subscription ? \Carbon\Carbon::parse($subscription->start_date) : null;
                        $end = $subscription ? \Carbon\Carbon::parse($subscription->end_date) : null;

                        $now = now();

                        // Check if expired
                        $isExpired = false;
                        $daysExpiredAgo = 0;

                        if ($start && $end) {
                            $totalDays = $start->diffInDays($end);
                            $elapsedDays = $start->diffInDays($now);

                            // Check if subscription is expired
                            if ($end->isPast() || ($subscription && $subscription->status === 'expired')) {
                                $isExpired = true;
                                $percentage = 100;
                                $barColor = 'bg-rose-500';
                                $daysExpiredAgo = (int) $end->diffInDays($now); // Days since expired
                            } else {
                                $percentage = $totalDays > 0 ? min(100, max(0, ($elapsedDays / $totalDays) * 100)) : 0;

                                // Color based on percentage
                                if ($percentage > 85) {
                                    $barColor = 'bg-rose-500';
                                } elseif ($percentage > 60) {
                                    $barColor = 'bg-amber-500';
                                } else {
                                    $barColor = 'bg-emerald-600';
                                }
                            }

                            $hasSubscription = true;
                        } else {
                            $percentage = 0;
                            $barColor = 'bg-gray-500';
                            $hasSubscription = false;
                        }
                    @endphp

                    @if ($waris->isNotEmpty())
                        <div class="flex gap-2">

                            <div class="bg-stone-200 rounded-lg py-1 px-2 shadow-stone-800 shadow-sm w-full md:w-auto">
                                <div class="flex flex-col items-start ">
                                    <div class="mb-1">
                                        <span
                                            class="text-[8px] sm:text-xs font-bold uppercase tracking-widest text-black">Maklumat
                                            Waris</span>
                                    </div>
                                    <div class="">
                                        <div class="flex flex-col items-start gap-2 sm:gap-3">
                                            <div>
                                                <p class="text-[10px] sm:text-[10px] text-slate-700 font-medium">Nama:</p>
                                                <p class="text-[10px] sm:text-[12px] font-bold text-black">
                                                    {{ $ahli->waris->first()?->nama ?? '-' }}</p>
                                            </div>
                                            <div>
                                                <p class="text-[10px] sm:text-[10px] text-slate-700 font-medium">NO TELEFON:
                                                </p>
                                                <p class="text-[10px] sm:text-[12px] font-bold text-black">
                                                    {{ $ahli->waris->first()?->telefon_bimbit ?? '-' }}</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                    @endif

                    @if ($hasSubscription && $start && $end)
                        <div class="bg-stone-300 rounded-lg p-2 shadow-stone-800 shadow-sm w-full md:w-64">
                            <div class="flex flex-col gap-2 sm:gap-3">
                                <div class="flex items-center justify-between">
                                    <span
                                        class="text-[8px] sm:text-xs font-bold uppercase tracking-widest text-black">Membership</span>
                                    @if ($isExpired)
                                        <span
                                            class="px-2 py-0.5 bg-rose-600 text-white rounded text-[8px] sm:text-[10px] font-bold flex items-center gap-1">
                                            <i class="fas fa-exclamation-circle text-[8px]"></i>
                                            EXPIRED
                                        </span>
                                    @else
                                        <span
                                            class="px-2 py-0.5 bg-emerald-600 text-white rounded text-[8px] sm:text-[10px] font-bold flex items-center gap-1">
                                            <i class="fas fa-check-circle text-[8px]"></i>
                                            AKTIF
                                        </span>
                                    @endif
                                </div>

                                <div class="flex justify-between items-end gap-2 sm:gap-3">
                                    <div>
                                        <p class="text-[8px] sm:text-[10px] text-slate-700 font-medium">Mula</p>
                                        <p class="text-xs sm:text-sm font-bold text-black">{{ $start->format('d/m/Y') }}</p>
                                    </div>
                                    <p class="text-xl sm:text-2xl text-slate-100 font-medium">-</p>
                                    <div class="text-right">
                                        <p class="text-[8px] sm:text-[10px] text-slate-700 font-medium">Tamat</p>
                                        <p
                                            class="text-xs sm:text-sm font-bold {{ $isExpired ? 'text-rose-600 line-through' : 'text-black' }}">
                                            {{ $end->format('d/m/Y') }}
                                        </p>
                                    </div>
                                </div>

                                <div class="space-y-1 sm:space-y-2">
                                    <div class="w-full bg-slate-100 rounded-full h-1.5 sm:h-2 overflow-hidden">
                                        <div class="{{ $barColor }} h-full rounded-full transition-all duration-500"
                                            style="width: {{ $percentage }}%"></div>
                                    </div>

                                    <div
                                        class="flex justify-between items-center text-xs sm:text-sm font-bold uppercase tracking-tight">
                                        @if ($isExpired)
                                            <div class="flex flex-col items-end">
                                                <span class="text-rose-600">
                                                    <i class="fas fa-calendar-times mr-1"></i>
                                                    Tamat: {{ $daysExpiredAgo }} hari lepas
                                                </span>

                                            </div>
                                        @else
                                            <span class="text-red-600">
                                                <i class="fas fa-clock mr-1"></i>
                                                {{ (int) max(0, $now->diffInDays($end, false)) }}
                                                <span class="text-black">Hari Lagi</span>
                                            </span>
                                        @endif
                                    </div>
                                </div>


                            </div>
                        </div>
                    @else
                        <div class="bg-stone-300 rounded-lg p-2 shadow-stone-800 shadow-sm w-full md:w-64">
                            <div class="flex flex-col gap-2 sm:gap-3">
                                <div class="flex items-center justify-between">
                                    <span
                                        class="text-[8px] sm:text-xs font-bold uppercase tracking-widest text-black">Membership</span>
                                    <span
                                        class="px-2 py-0.5 bg-gray-600 text-white rounded text-[8px] sm:text-[10px] font-bold">
                                        TIADA
                                    </span>
                                </div>
                                <div class="text-center py-4">
                                    <i class="fas fa-id-card text-gray-400 text-2xl mb-2"></i>
                                    <p class="text-xs text-gray-500">Tiada data keahlian</p>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
    </div>
    </header>
    <div class=" max-w-8xl px-3 sm:px-4  ">
        <h2 class="text-lg sm:text-xl font-bold text-dark mb-4 sm:mb-6">Senarai Ahli Keluarga</h2>

        <div class="grid grid-cols-1 sm:grid-cols-1 md:grid-cols-3 lg:grid-cols-3 xl:col-end-4 gap-4 sm:gap-6">
            {{-- ================= AHLI (KETUA KELUARGA) ================= --}}
            @php
                $ahliKariahId = $ahliKariah->id ?? null;
                $hasDeathRecord = $tuntutanPaid !== null;
                $hasPendingRecord = $tuntutanPending !== null;
                $hasProcessingRecord = isset($tuntutanProcessing) && $tuntutanProcessing !== null;
                $isPending = $hasPendingRecord;
                $isApproved =
                    $hasDeathRecord && in_array($tuntutanPaid->status ?? null, ['APPROVED', 'PAID', 'SUCCESS']);
                $isProcessing =
                    $hasProcessingRecord || ($hasDeathRecord && ($tuntutanPaid->status ?? null) === 'PROCESSING');
                $subscription = $activeSubscription ?? null;
                $isUserActive = $ahliKariah->status === 'active';
                $isSubscriptionValid = $subscription && $subscription->end_date && now()->lte($subscription->end_date);
                $isEligible = $isUserActive && $isSubscriptionValid;
                $showGrayButton = $hasDeathRecord && !$isProcessing;
                $showProcessingButton = $isProcessing && !$hasDeathRecord;
                $showPendingButton = $hasPendingRecord && !$hasDeathRecord && !$isProcessing;
                $showGreenButton = $isEligible && !$hasDeathRecord && !$hasPendingRecord && !$isProcessing;
                $showExpiredButton =
                    (!$isUserActive || !$isSubscriptionValid) &&
                    !$hasDeathRecord &&
                    !$hasPendingRecord &&
                    !$isProcessing;
                $statusText = 'TIDAK LAYAK';
                $statusColor = 'bg-red-100 text-red-700';

                if ($isEligible) {
                    $statusText = 'LAYAK';
                    $statusColor = 'bg-green-100 text-green-700';
                } elseif ($isProcessing) {
                    $statusText = 'DALAM Pengurusan';
                    $statusColor = 'bg-orange-100 text-orange-700';
                } elseif (!$isUserActive) {
                    $statusText = 'TIDAK AKTIF';
                    $statusColor = 'bg-red-100 text-red-700';
                } elseif (!$isSubscriptionValid) {
                    $statusText = 'TAMAT TEMPOH';
                    $statusColor = 'bg-orange-100 text-orange-700';
                }
            @endphp

            <div
                class="bg-white rounded-xl shadow-lg overflow-hidden transition-all duration-300 hover:shadow-xl {{ $isPending ? 'ring-2 ring-red-200 bg-red-50' : '' }} {{ $isApproved ? 'opacity-80 grayscale' : '' }}">
                <div class="p-4 sm:p-5 border-b border-gray-100">
                    <div class="flex items-start justify-between">
                        <div class="flex items-center justify-center gap-4">
                            <div class="  ">
                                <i class="fas fa-user-circle text-orange-600 text-4xl"></i>
                            </div>
                            <div>
                                <h3 class="font-bold text-gray-900 text-base sm:text-lg">{{ $ahli->nama }}</h3>
                                <div class="flex flex-wrap items-center gap-1 sm:gap-2 mt-1">
                                    <span
                                        class="inline-flex items-center px-1.5 py-0.5 sm:px-2 sm:py-1 rounded-full text-[10px] sm:text-xs font-medium {{ $ahliKariah->status === 'active' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                                        <span class="relative flex h-1.5 w-1.5 mr-1">
                                            @if ($ahliKariah->status === 'active')
                                                <span
                                                    class="animate-ping absolute inline-flex h-full w-full rounded-full bg-green-400 opacity-75"></span>
                                                <span
                                                    class="relative inline-flex rounded-full h-1.5 w-1.5 bg-green-500"></span>
                                            @else
                                                <span
                                                    class="relative inline-flex rounded-full h-1.5 w-1.5 bg-red-500"></span>
                                            @endif
                                        </span>
                                        {{ $ahliKariah->status === 'active' ? 'Aktif' : 'Tidak Aktif' }}
                                    </span>
                                    {{-- @if ($isApproved)
                                    <span
                                        class="inline-flex items-center px-1.5 py-0.5 rounded-full text-[10px] font-medium bg-green-100 text-green-700">✓
                                        Proses Selesai</span>
                                @endif --}}
                                </div>
                            </div>
                        </div>
                        {{-- @if ($isApproved)
                        <div class="bg-green-50 p-1.5 sm:p-2 rounded-lg">
                            <svg class="w-5 h-5 sm:w-6 sm:h-6 text-green-500" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                        </div>
                    @endif --}}
                    </div>
                </div>

                <div class="p-4 sm:p-5 space-y-3 sm:space-y-4 {{ $isApproved ? 'text-gray-400' : '' }}">
                    <div class="bg-gradient-to-r from-blue-50 to-white border border-blue-100 rounded-lg p-3 sm:p-4">
                        <div class="flex items-center mb-2">
                            <div class="p-1.5 sm:p-2 bg-blue-100 rounded-lg mr-2 sm:mr-3">
                                <svg class="w-3 h-3 sm:w-4 sm:h-4 text-blue-600" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                            </div>
                            <span class="font-medium text-gray-700 text-xs sm:text-sm">Tempoh Sah Khairat</span>
                        </div>
                        <p class="text-xs sm:text-sm text-gray-600 ml-8 sm:ml-11">
                            Sehingga: <span
                                class="font-bold text-blue-700">{{ $subscription && $subscription->end_date ? date('d M Y', strtotime($subscription->end_date)) : '-' }}</span>
                        </p>
                    </div>

                    <div class="bg-gradient-to-r from-green-50 to-white border border-green-100 rounded-lg p-3 sm:p-4">
                        <div class="flex items-center justify-between mb-2">
                            <div class="flex items-center">
                                <div class="p-1.5 sm:p-2 bg-green-100 rounded-lg mr-2 sm:mr-3">
                                    <svg class="w-3 h-3 sm:w-4 sm:h-4 text-green-600" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                                <span class="font-medium text-gray-700 text-xs sm:text-sm">Status Keahlian</span>
                            </div>
                            <span
                                class="px-2 sm:px-3 py-1 rounded-full text-[10px] sm:text-xs font-semibold {{ $statusColor }}">{{ $statusText }}</span>
                        </div>
                        @if (!$isEligible && !$hasDeathRecord && !$hasPendingRecord)
                            <div class="mt-2 text-[10px] sm:text-xs text-gray-500 ml-8 sm:ml-11">
                                @if (!$isUserActive)
                                    <span class="text-red-600">⚠️ Akaun pengguna tidak aktif</span>
                                @elseif (!$isSubscriptionValid)
                                    <span class="text-orange-600">⚠️ Keahlian khairat telah tamat tempoh</span>
                                @endif
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Action Button -->
                <div class="p-4 sm:p-5 pt-0">
                    @if ($showGrayButton)
                        <div class="space-y-2 sm:space-y-3">
                            <button
                                class="w-full bg-gray-400 text-white font-semibold py-2 sm:py-3 rounded-xl flex items-center justify-center cursor-not-allowed text-sm sm:text-base"
                                disabled>
                                <svg class="w-4 h-4 sm:w-5 sm:h-5 mr-2" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                Rekod Kematian Telah Direkodkan
                            </button>
                            <button
                                onclick="window.location.href='{{ route('khairat.butiran', ['type' => 'AHLI', 'id' => $ahliKariahId]) }}'"
                                class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 sm:py-3 rounded-xl flex items-center justify-center text-sm sm:text-base">
                                Lihat Rekod Kematian
                            </button>
                        </div>
                    @elseif($showProcessingButton)
                        <a href="{{ route('khairat.butiran', ['type' => 'AHLI', 'id' => $ahliKariahId]) }}"
                            class="w-full bg-gradient-to-r from-orange-500 to-red-500 hover:from-orange-600 hover:to-red-600 text-white font-semibold py-2 sm:py-3 rounded-xl flex items-center justify-center text-sm sm:text-base">
                            <svg class="w-4 h-4 sm:w-5 sm:h-5 mr-2 animate-pulse" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            Dalam Pengurusan Jenazah
                        </a>
                    @elseif($showPendingButton)
                        <a href="{{ route('khairat.butiran', ['type' => 'AHLI', 'id' => $ahliKariahId]) }}"
                            class="w-full bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 text-white font-semibold py-2 sm:py-3 rounded-xl flex items-center justify-center text-sm sm:text-base">
                            <svg class="w-4 h-4 sm:w-5 sm:h-5 mr-2" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            Lihat Borang (Dalam Proses)
                        </a>
                    @elseif($showGreenButton)
                        <button
                            onclick="openDeathModal({{ $ahliKariahId }}, '{{ addslashes($ahli->nama) }}', '{{ $ahli->ic }}')"
                            class="w-full bg-gradient-to-r from-green-600 to-emerald-600 hover:from-green-700 hover:to-emerald-700 text-white font-semibold py-2 sm:py-3 rounded-xl flex items-center justify-center text-sm sm:text-base">
                            <svg class="w-4 h-4 sm:w-5 sm:h-5 mr-2" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            Melaporkan Kematian
                        </button>
                    @elseif($showExpiredButton)
                        <button
                            class="w-full bg-orange-500 text-white font-semibold py-2 sm:py-3 rounded-xl flex items-center justify-center cursor-not-allowed opacity-75 text-sm sm:text-base"
                            disabled>
                            <svg class="w-4 h-4 sm:w-5 sm:h-5 mr-2" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            @if (!$isUserActive)
                                Akaun Pengguna Tidak Aktif - Sila rujuk AJK
                            @else
                                Keahlian Tamat Tempoh - Sila Perbaharui
                            @endif
                        </button>
                    @endif
                </div>
            </div>

            {{-- ================= OTHER AHLI IN FAMILY (OLD KETUA) ================= --}}
            @foreach ($familyAhli as $fa)
                @php
                    $isUserActive = $fa->status === 'active';
                    $isSubscriptionValid =
                        $activeSubscription &&
                        $activeSubscription->end_date &&
                        now()->lte($activeSubscription->end_date);
                    $isEligible = $isUserActive && $isSubscriptionValid;

                    // Check tuntutan for this ahli
                    $faTuntutanPaid = \App\Models\TuntutanKhairat::where('ahli_id', $fa->id)
                        ->whereIn('status', ['PAID', 'SUCCESS', 'APPROVED'])
                        ->where('type', 'AHLI')
                        ->latest()
                        ->first();

                    $faTuntutanProcessing = \App\Models\TuntutanKhairat::where('ahli_id', $fa->id)
                        ->where('type', 'AHLI')
                        ->where('status', 'PROCESSING')
                        ->latest()
                        ->first();

                    $faTuntutanPending = \App\Models\TuntutanKhairat::where('ahli_id', $fa->id)
                        ->where('type', 'AHLI')
                        ->where('status', 'PENDING')
                        ->first();

                    $faHasDeath = $faTuntutanPaid !== null;
                    $faIsProcessing = $faTuntutanProcessing !== null;
                    $faIsPending = $faTuntutanPending !== null;

                    $faShowGray = $faHasDeath;
                    $faShowGreen = $isEligible && !$faHasDeath && !$faIsProcessing && !$faIsPending;
                    $faShowPending = $faIsPending && !$faHasDeath;
                    $faShowExpired =
                        (!$isUserActive || !$isSubscriptionValid) && !$faHasDeath && !$faIsPending && !$faIsProcessing;
                @endphp

                <div
                    class="bg-white rounded-xl shadow-lg overflow-hidden transition-all duration-300 hover:shadow-xl border-l-4 border-l-blue-400">
                    <div class="p-4 sm:p-5 border-b border-gray-100">
                        <div class="flex items-center gap-4">
                            <i class="fas fa-user-circle text-blue-500 text-4xl"></i>
                            <div>
                                <h3 class="font-bold text-gray-900 text-base sm:text-lg">{{ $fa->nama }}</h3>
                                <div class="flex flex-wrap items-center gap-2 mt-1">
                                    <span
                                        class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-medium bg-blue-100 text-blue-700">
                                        Ahli Keluarga
                                    </span>
                                    <span
                                        class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-medium {{ $fa->status === 'active' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                                        {{ $fa->status === 'active' ? 'Aktif' : 'Tidak Aktif' }}
                                    </span>
                                </div>
                                {{-- <p class="text-xs text-gray-500 mt-1">{{ $fa->ic }}</p> --}}
                            </div>
                        </div>
                    </div>

                    <div class="p-4 sm:p-5 space-y-3">
                        <div class="bg-gradient-to-r from-blue-50 to-white border border-blue-100 rounded-lg p-3 sm:p-4">
                        <div class="flex items-center mb-2">
                            <div class="p-1.5 sm:p-2 bg-blue-100 rounded-lg mr-2 sm:mr-3">
                                <svg class="w-3 h-3 sm:w-4 sm:h-4 text-blue-600" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                            </div>
                            <span class="font-medium text-gray-700 text-xs sm:text-sm">Tempoh Sah Khairat</span>
                        </div>
                        <p class="text-xs sm:text-sm text-gray-600 ml-8 sm:ml-11">
                            Sehingga: <span
                                class="font-bold text-blue-700">{{ $subscription && $subscription->end_date ? date('d M Y', strtotime($subscription->end_date)) : '-' }}</span>
                        </p>
                    </div>
                        <div class="bg-gradient-to-r from-green-50 to-white border border-green-100 rounded-lg p-3 sm:p-4">
                        <div class="flex items-center justify-between mb-2">
                            <div class="flex items-center">
                                <div class="p-1.5 sm:p-2 bg-green-100 rounded-lg mr-2 sm:mr-3">
                                    <svg class="w-3 h-3 sm:w-4 sm:h-4 text-green-600" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                                <span class="font-medium text-gray-700 text-xs sm:text-sm">Status Keahlian</span>
                            </div>
                            <span
                                class="px-2 sm:px-3 py-1 rounded-full text-[10px] sm:text-xs font-semibold {{ $statusColor }}">{{ $statusText }}</span>
                        </div>
                        @if (!$isEligible && !$hasDeathRecord && !$hasPendingRecord)
                            <div class="mt-2 text-[10px] sm:text-xs text-gray-500 ml-8 sm:ml-11">
                                @if (!$isUserActive)
                                    <span class="text-red-600">⚠️ Akaun pengguna tidak aktif</span>
                                @elseif (!$isSubscriptionValid)
                                    <span class="text-orange-600">⚠️ Keahlian khairat telah tamat tempoh</span>
                                @endif
                            </div>
                        @endif
                    </div>
                    </div>

                    <div class="p-4 sm:p-5 pt-0">
                        @if ($faShowGray)
                            <button
                                class="w-full bg-gray-400 text-white font-semibold py-2 rounded-xl cursor-not-allowed text-sm"
                                disabled>
                                Rekod Kematian Telah Direkodkan
                            </button>
                        @elseif ($faShowPending)
                            <a href="{{ route('ajk.approve', $faTuntutanPending->id) }}"
                                class="w-full bg-gradient-to-r from-indigo-600 to-purple-600 text-white font-semibold py-2 rounded-xl flex items-center justify-center text-sm">
                                Lihat Rekod Kematian (Pending)
                            </a>
                        @elseif ($faShowGreen)
                            <button
                                onclick="openDeathModal({{ $fa->id }}, '{{ addslashes($fa->nama) }}', '{{ $fa->ic }}')"
                                class="w-full bg-gradient-to-r from-green-600 to-emerald-600 hover:from-green-700 hover:to-emerald-700 text-white font-semibold py-2 sm:py-3 rounded-xl flex items-center justify-center text-sm sm:text-base">
                            <svg class="w-4 h-4 sm:w-5 sm:h-5 mr-2" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                                Melaporkan Kematian
                            </button>
                        @elseif ($faShowExpired)
                            <button
                                class="w-full bg-orange-500 text-white font-semibold py-2 rounded-xl cursor-not-allowed opacity-75 text-sm"
                                disabled>
                                Keahlian Tamat Tempoh
                            </button>
                        @endif
                    </div>
                </div>
            @endforeach

            {{-- ================= TANGGUNGAN ================= --}}
            @foreach ($tanggungan as $t)
                @php
                    switch (strtolower($t->hubungan)) {
                        case 'isteri':
                            $border = 'border-l-4 border-l-pink-500';
                            $avatar = 'bg-gradient-to-br from-pink-500 to-rose-500';
                            $badge = 'bg-pink-100 text-pink-700';
                            break;
                        case 'anak':
                            $border = 'border-l-4 border-l-amber-500';
                            $avatar = 'bg-gradient-to-br from-amber-500 to-orange-500';
                            $badge = 'bg-amber-100 text-amber-700';
                            break;
                        default:
                            $border = 'border-l-4 border-l-gray-500';
                            $avatar = 'bg-gradient-to-br from-gray-500 to-gray-600';
                            $badge = 'bg-gray-100 text-gray-700';
                    }

                    $initials = collect(explode(' ', $t->nama))
                        ->map(fn($n) => strtoupper(substr($n, 0, 1)))
                        ->take(2)
                        ->implode('');
                    $dependentTuntutan = $t->latest_tuntutan ?? null;
                    $statusTuntutan = $dependentTuntutan->status ?? null;
                    $isPending = $statusTuntutan === 'PENDING';
                    $isApproved = in_array($statusTuntutan, ['SUCCESS', 'PROCESSING']);
                    $hasDeathRecord =
                        $dependentTuntutan && in_array($dependentTuntutan->status, ['SUCCESS', 'PROCESSING']);
                    $hasPendingRecord = $dependentTuntutan && $dependentTuntutan->status === 'PENDING';
                    $isUserActive = $ahli->status === 'active';
                    $subscription = $activeSubscription ?? null;
                    $isSubscriptionValid =
                        $subscription && $subscription->end_date && now()->lte($subscription->end_date);
                    $isEligible = $isUserActive && $isSubscriptionValid && !$hasDeathRecord && !$hasPendingRecord;
                    $statusText = 'TIDAK LAYAK';
                    $statusColor = 'bg-red-100 text-red-700';

                    if ($hasDeathRecord) {
                        $statusText = 'TELAH DIPROSES';
                        $statusColor = 'bg-gray-100 text-gray-700';
                    } elseif ($hasPendingRecord) {
                        $statusText = 'DALAM PROSES';
                        $statusColor = 'bg-yellow-100 text-yellow-700';
                    } elseif ($isEligible) {
                        $statusText = 'LAYAK';
                        $statusColor = 'bg-green-100 text-green-700';
                    } elseif (!$isUserActive) {
                        $statusText = 'KETUA TIDAK AKTIF';
                        $statusColor = 'bg-red-100 text-red-700';
                    } elseif (!$isSubscriptionValid) {
                        $statusText = 'TAMAT TEMPOH';
                        $statusColor = 'bg-orange-100 text-orange-700';
                    }

                    $showGrayButton = $hasDeathRecord;
                    $showPendingButton = $hasPendingRecord && !$hasDeathRecord;
                    $showGreenButton = $isEligible;
                    $showExpiredButton =
                        (!$isUserActive || !$isSubscriptionValid) && !$hasDeathRecord && !$hasPendingRecord;
                @endphp

                <div
                    class="bg-white rounded-xl shadow-lg overflow-hidden transition-all duration-300 hover:shadow-xl {{ $border }} {{ $isPending ? 'ring-2 ring-red-200 bg-red-50' : '' }} {{ $isApproved ? 'opacity-80 grayscale' : '' }}">
                    <div class="p-4 sm:p-5 border-b border-gray-100">
                        <div class="flex items-start justify-between">
                            <div class="flex items-center space-x-2 sm:space-x-2">
                                <div class="h-12 w-12 sm:h-14 sm:w-14  flex items-center justify-center ">
                                    <i class="fas fa-user-circle text-indigo-700 text-4xl"></i>
                                </div>
                                <div class="flex flex-col">
                                    <h3 class="font-bold text-gray-900 text-md sm:text-lg">{{ $t->nama }}</h3>
                                    {{-- <span class="text-xs sm:text-sm text-gray-500">ID Tanggungan:
                                        {{ $t->id }}</span> --}}
                                    <div class="flex flex-wrap items-center gap-1 sm:gap-2 mt-1">
                                        <span
                                            class="inline-flex items-center px-2 sm:px-3 py-0.5 sm:py-1 rounded-full text-[10px] sm:text-xs font-medium {{ $badge }}">{{ ucfirst($t->hubungan) }}</span>
                                        @if ($t->oku)
                                            <span
                                                class="inline-flex items-center px-1.5 py-0.5 rounded-full text-[10px] font-medium bg-red-100 text-red-700">OKU</span>
                                        @endif
                                        @if ($isPending)
                                            <span
                                                class="inline-flex items-center px-1.5 py-0.5 rounded-full text-[10px] font-medium bg-red-100 text-red-700 animate-pulse">⚠️
                                                PENDING</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            @if ($isApproved)
                                <div class="bg-green-50 p-1.5 sm:p-2 rounded-lg">
                                    <svg class="w-5 h-5 sm:w-6 sm:h-6 text-green-500" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M5 13l4 4L19 7" />
                                    </svg>
                                </div>
                            @endif
                        </div>
                    </div>

                    <div class="p-4 sm:p-5 space-y-3 sm:space-y-4 ">
                        <div class="bg-gradient-to-r from-blue-50 to-white border border-blue-100 rounded-lg p-3 sm:p-4">
                            <div class="flex items-center justify-between mb-2">
                                <div class="flex items-center">
                                    <div class="p-1.5 sm:p-2 bg-blue-100 rounded-lg mr-2 sm:mr-3">
                                        <svg class="w-3 h-3 sm:w-4 sm:h-4 text-blue-600" fill="none"
                                            stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                    </div>
                                    @if ($dependentTuntutan && in_array($dependentTuntutan->status, ['PROCESSING', 'SUCCESS']))
                                        <span class="font-medium text-gray-700 text-xs sm:text-sm">Penerangan</span>
                                    @else
                                        <span class="font-medium text-gray-700 text-xs sm:text-sm">Tempoh Sah
                                            Khairat</span>
                                    @endif
                                </div>
                            </div>
                            <p class="text-xs sm:text-sm ml-8 sm:ml-11">
                                @if ($dependentTuntutan && $dependentTuntutan->status === 'PROCESSING')
                                    <span class="font-semibold text-yellow-500">Pihak Masjid Masih Dalam Proses Menguruskan
                                        Jenazah</span>
                                @elseif($dependentTuntutan && $dependentTuntutan->status === 'SUCCESS')
                                    <span class="font-semibold text-green-500">Pihak Masjid Selesai Menguruskan
                                        Jenazah</span>
                                @else
                                    Sehingga: <span
                                        class="font-bold text-blue-700">{{ $subscription && $subscription->end_date ? date('d M Y', strtotime($subscription->end_date)) : '-' }}</span>
                                @endif
                            </p>
                        </div>

                        <div class="bg-gradient-to-r from-green-50 to-white border border-green-100 rounded-lg p-3 sm:p-4">
                            <div class="flex items-center justify-between mb-2">
                                <div class="flex items-center">
                                    <div class="p-1.5 sm:p-2 bg-green-100 rounded-lg mr-2 sm:mr-3">
                                        <svg class="w-3 h-3 sm:w-4 sm:h-4 text-green-600" fill="none"
                                            stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                    </div>
                                    <span class="font-medium text-gray-700 text-xs sm:text-sm">Status</span>
                                </div>
                                <span
                                    class="px-2 sm:px-3 py-1 rounded-full text-[10px] sm:text-xs font-semibold {{ $statusColor }}">
                                    {{ $dependentTuntutan?->status == 'PROCESSING' ? 'DALAM PENGURUSAN' : ($dependentTuntutan?->status == 'SUCCESS' ? 'PENGURUSAN SELESAI' : $dependentTuntutan?->status ?? 'TIDAK LAYAK') }}
                                </span>
                            </div>
                            @if (!$isEligible && !$hasDeathRecord && !$hasPendingRecord)
                                <div class="mt-2 text-[10px] sm:text-xs text-gray-500 ml-8 sm:ml-11">
                                    @if (!$isUserActive)
                                        <span class="text-red-600">⚠️ Ketua keluarga tidak aktif</span>
                                    @elseif (!$isSubscriptionValid)
                                        <span class="text-orange-600">⚠️ Keahlian khairat ketua keluarga telah tamat
                                            tempoh</span>
                                    @endif
                                </div>
                            @endif
                        </div>
                    </div>

                    <div class="p-4 sm:p-5 pt-0">
                        @if ($showGrayButton)
                            <div class="space-y-2 sm:space-y-3">
                                <button
                                    class="w-full bg-gray-400 text-white font-semibold py-2 sm:py-3 rounded-xl flex items-center justify-center cursor-not-allowed text-sm sm:text-base"
                                    disabled>
                                    <svg class="w-4 h-4 sm:w-5 sm:h-5 mr-2" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    Rekod Kematian Telah Direkodkan
                                </button>
                                <button
                                    onclick="window.location.href='{{ route('khairat.butiran', ['type' => 'TANGGUNGAN', 'id' => $t->id]) }}'"
                                    class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 sm:py-3 rounded-xl flex items-center justify-center text-sm sm:text-base">
                                    Lihat Rekod Kematian
                                </button>
                            </div>
                        @elseif($showPendingButton)
                            <a href="{{ route('ajk.approve', $dependentTuntutan->id) }}"
                                class="w-full bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 text-white font-semibold py-2 sm:py-3 rounded-xl flex items-center justify-center text-sm sm:text-base">
                                <svg class="w-4 h-4 sm:w-5 sm:h-5 mr-2" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                                Lihat Rekod Kematian (Pending)
                            </a>
                        @elseif($showGreenButton)
                            <button
                                onclick="openDeathModalTanggungan({{ $t->id }}, '{{ addslashes($t->nama) }}', '{{ $t->ic_number }}', '{{ $ahli->id }}')"
                                class="w-full bg-gradient-to-r from-green-600 to-emerald-600 hover:from-green-700 hover:to-emerald-700 text-white font-semibold py-2 sm:py-3 rounded-xl flex items-center justify-center text-sm sm:text-base">
                                <svg class="w-4 h-4 sm:w-5 sm:h-5 mr-2" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                Melaporkan Kematian
                            </button>
                        @elseif($showExpiredButton)
                            <button
                                class="w-full bg-orange-500 text-white font-semibold py-2 sm:py-3 rounded-xl flex items-center justify-center cursor-not-allowed opacity-75 text-sm sm:text-base"
                                disabled>
                                <svg class="w-4 h-4 sm:w-5 sm:h-5 mr-2" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                @if (!$isUserActive)
                                    Ketua Keluarga Tidak Aktif - Rekod Kematian Tidak Dibenarkan
                                @else
                                    Keahlian Keluarga Tamat Tempoh - Sila Perbaharui
                                @endif
                            </button>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    </div>
    </div>

    <!-- Modal for Adding Death Record -->
    <div id="deathModal"
        class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm flex items-center justify-center p-4 z-50 hidden transition-all duration-300">
        <div
            class="bg-white rounded-2xl shadow-2xl w-full max-w-lg overflow-hidden flex flex-col max-h-[92vh] animate-in fade-in zoom-in duration-200">

            <div class="px-6 py-5 border-b border-gray-100 flex justify-between items-center bg-white sticky top-0 z-10">
                <div>
                    <h3 class="text-xl font-bold text-gray-900 leading-tight">Lapor Kematian</h3>
                    <p class="text-xs text-gray-500 mt-0.5">Sila lengkapkan maklumat simati di bawah</p>
                </div>
                <button onclick="closeDeathModal()"
                    class="p-2 rounded-full hover:bg-gray-100 text-gray-400 hover:text-gray-600 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <form id="deathRecordForm" enctype="multipart/form-data" class="overflow-y-auto p-6 custom-scrollbar">
                @csrf
                <input type="hidden" name="ahli_id" id="death_ahli_id">

                <div class="flex items-start gap-4 p-4 rounded-xl bg-slate-50 border border-slate-100 mb-6">
                    <div class="p-3 bg-blue-100 text-blue-600 rounded-lg">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-xs font-bold text-slate-400 uppercase tracking-widest">Maklumat Si Mati</p>
                        <div id="death_user_name" class="text-lg font-bold text-slate-800"></div>
                        <div id="death_user_ic" class="text-sm text-slate-500 font-medium"></div>
                    </div>
                </div>

                <div class="space-y-6">
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Tarikh Meninggal <span
                                class="text-red-500">*</span></label>
                        <div class="relative">
                            <input type="date" name="tarikh_meninggal" id="tarikh_meninggal" required
                                class="w-full pl-4 pr-4 py-3 bg-white border border-gray-200 rounded-xl focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 transition-all outline-none text-gray-700">
                        </div>
                    </div>

                    <!-- Status Pengurusan Jenazah Dropdown -->
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Status Pengurusan Jenazah <span
                                class="text-red-500">*</span></label>

                        <div class="space-y-4">
                            <!-- Option 1: Dalam Pengurusan -->
                            <div class="border border-gray-200 rounded-xl p-4 bg-white">
                                <label class="flex items-center cursor-pointer">
                                    <input type="radio" name="status_jenazah" value="PROCESSING"
                                        onchange="toggleItemsSection(false)"
                                        class="w-5 h-5 text-amber-600 focus:ring-amber-500">
                                    <span class="ml-3 text-gray-700 font-semibold">Dalam Pengurusan Jenazah</span>
                                </label>
                                <p class="text-xs text-gray-500 mt-2 ml-8">Jenazah sedang diuruskan. Tiada item perincian
                                    diperlukan.</p>
                            </div>

                            <!-- Option 2: Pengurusan Selesai -->
                            <div class="border border-gray-200 rounded-xl p-4 bg-white">
                                <label class="flex items-center cursor-pointer">
                                    <input type="radio" name="status_jenazah" value="SUCCESS"
                                        onchange="toggleItemsSection(true)"
                                        class="w-5 h-5 text-emerald-600 focus:ring-emerald-500">
                                    <span class="ml-3 text-gray-700 font-semibold">Pengurusan Jenazah Selesai</span>
                                </label>
                                <p class="text-xs text-gray-500 mt-2 ml-8">Sila nyatakan perincian item dan jumlah bayaran.
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Items Section - Hidden by default, shown when SUCCESS is selected -->
                    <div id="itemsSection" class="hidden space-y-4 mt-4 border-t border-gray-200 pt-4">
                        <label class="block text-sm font-bold text-gray-700 mb-2">Perincian Item & Bayaran <span
                                class="text-red-500">*</span></label>

                        <div class="bg-gray-50 rounded-xl p-4 space-y-3">
                            <!-- Item 1: Pengurusan Jenazah -->
                            <div class="flex items-center gap-3">
                                <input type="checkbox" name="items[]" value="pengurusan_jenazah"
                                    onchange="toggleAmountInput(this, 'amount_pengurusan_jenazah')"
                                    class="w-5 h-5 text-emerald-600 rounded focus:ring-emerald-500">
                                <label class="flex-1 text-gray-700 font-medium">Pengurusan Jenazah</label>
                                <input type="number" name="amount_pengurusan_jenazah" id="amount_pengurusan_jenazah"
                                    placeholder="RM 0.00" disabled
                                    class="w-40 px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 disabled:bg-gray-100 disabled:cursor-not-allowed">
                            </div>

                            <!-- Item 2: Pengangkutan Jenazah -->
                            <div class="flex items-center gap-3">
                                <input type="checkbox" name="items[]" value="pengangkutan_jenazah"
                                    onchange="toggleAmountInput(this, 'amount_pengangkutan_jenazah')"
                                    class="w-5 h-5 text-emerald-600 rounded focus:ring-emerald-500">
                                <label class="flex-1 text-gray-700 font-medium">Van Jenazah</label>
                                <input type="number" name="amount_pengangkutan_jenazah" id="amount_pengangkutan_jenazah"
                                    placeholder="RM 0.00" disabled
                                    class="w-40 px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 disabled:bg-gray-100 disabled:cursor-not-allowed">
                            </div>

                            <!-- Item 3: Tanah Perkuburan -->
                            <div class="flex items-center gap-3">
                                <input type="checkbox" name="items[]" value="tanah_perkuburan"
                                    onchange="toggleAmountInput(this, 'amount_tanah_perkuburan')"
                                    class="w-5 h-5 text-emerald-600 rounded focus:ring-emerald-500">
                                <label class="flex-1 text-gray-700 font-medium">Gali Kubur</label>
                                <input type="number" name="amount_tanah_perkuburan" id="amount_tanah_perkuburan"
                                    placeholder="RM 0.00" disabled
                                    class="w-40 px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 disabled:bg-gray-100 disabled:cursor-not-allowed">
                            </div>

                            <!-- Item 4: Kain Kafan -->
                            <div class="flex items-center gap-3">
                                <input type="checkbox" name="items[]" value="kain_kafan"
                                    onchange="toggleAmountInput(this, 'amount_kain_kafan')"
                                    class="w-5 h-5 text-emerald-600 rounded focus:ring-emerald-500">
                                <label class="flex-1 text-gray-700 font-medium">Kain Kafan</label>
                                <input type="number" name="amount_kain_kafan" id="amount_kain_kafan"
                                    placeholder="RM 0.00" disabled
                                    class="w-40 px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 disabled:bg-gray-100 disabled:cursor-not-allowed">
                            </div>

                            <!-- Item 5: Air / Mandian -->
                            <div class="flex items-center gap-3">
                                <input type="checkbox" name="items[]" value="air_mandian"
                                    onchange="toggleAmountInput(this, 'amount_air_mandian')"
                                    class="w-5 h-5 text-emerald-600 rounded focus:ring-emerald-500">
                                <label class="flex-1 text-gray-700 font-medium">Air / Mandian</label>
                                <input type="number" name="amount_air_mandian" id="amount_air_mandian"
                                    placeholder="RM 0.00" disabled
                                    class="w-40 px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 disabled:bg-gray-100 disabled:cursor-not-allowed">
                            </div>

                            <!-- Item 6: Imam / Bilal -->
                            <div class="flex items-center gap-3">
                                <input type="checkbox" name="items[]" value="imam_bilal"
                                    onchange="toggleAmountInput(this, 'amount_imam_bilal')"
                                    class="w-5 h-5 text-emerald-600 rounded focus:ring-emerald-500">
                                <label class="flex-1 text-gray-700 font-medium">Imam / Bilal</label>
                                <input type="number" name="amount_imam_bilal" id="amount_imam_bilal"
                                    placeholder="RM 0.00" disabled
                                    class="w-40 px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 disabled:bg-gray-100 disabled:cursor-not-allowed">
                            </div>

                            <!-- Item 7: Lain-lain -->
                            <div class="flex items-center gap-3">
                                <input type="checkbox" name="items[]" value="lain_lain"
                                    onchange="toggleAmountInput(this, 'amount_lain_lain'); toggleLainLainText()"
                                    class="w-5 h-5 text-emerald-600 rounded focus:ring-emerald-500">
                                <label class="flex-1 text-gray-700 font-medium">Lain-lain</label>
                                <input type="text" name="lain_lain_text" id="lain_lain_text"
                                    placeholder="Nyatakan item" disabled
                                    class="hidden w-32 px-2 py-2 border border-gray-300 rounded-lg text-sm">
                                <input type="number" name="amount_lain_lain" id="amount_lain_lain"
                                    placeholder="RM 0.00" disabled
                                    class="w-40 px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 disabled:bg-gray-100 disabled:cursor-not-allowed">
                            </div>
                        </div>

                        <!-- Total Amount Display -->
                        <div class="bg-emerald-50 rounded-xl p-4 border border-emerald-200">
                            <div class="flex justify-between items-center">
                                <span class="font-bold text-gray-700">Jumlah Keseluruhan:</span>
                                <span id="totalAmountDisplay" class="text-xl font-bold text-emerald-600">RM 0.00</span>
                            </div>
                            <input type="hidden" name="total_amount" id="total_amount" value="0">
                        </div>
                    </div>

                    <div class="bg-gray-50/50 rounded-2xl p-4 border border-dashed border-gray-200">
                        <label class="block text-sm font-bold text-gray-700 mb-4">Muat Naik Dokumen <span
                                class="text-red-500">*</span></label>

                        <div class="grid grid-cols-1 gap-4">
                            <div class="group relative">
                                <label class="block text-[11px] font-bold text-gray-500 uppercase ml-1 mb-1">Sijil
                                    Kematian</label>
                                <input type="file" name="sijil_kematian" id="sijil_kematian"
                                    accept=".pdf,.jpg,.jpeg,.png"
                                    class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-xs file:font-bold file:bg-white file:text-blue-600 file:shadow-sm hover:file:bg-blue-50 border border-gray-200 rounded-xl p-1.5 bg-white/50 transition-all">
                            </div>

                            <div class="group relative">
                                <label class="block text-[11px] font-bold text-gray-500 uppercase ml-1 mb-1">Laporan
                                    Polis</label>
                                <input type="file" name="laporan_polis" id="laporan_polis"
                                    accept=".pdf,.jpg,.jpeg,.png"
                                    class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-xs file:font-bold file:bg-white file:text-blue-600 file:shadow-sm hover:file:bg-blue-50 border border-gray-200 rounded-xl p-1.5 bg-white/50 transition-all">
                            </div>

                            <div class="group relative">
                                <label class="block text-[11px] font-bold text-gray-500 uppercase ml-1 mb-1">Dokumen
                                    Sokongan<span class="text-xs text-red-500 px-2">(JIKA ADA)</span></label>
                                <input type="file" name="maklumat_lain" id="maklumat_lain"
                                    accept=".pdf,.jpg,.jpeg,.png"
                                    class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-xs file:font-bold file:bg-white file:text-blue-600 file:shadow-sm hover:file:bg-blue-50 border border-gray-200 rounded-xl p-1.5 bg-white/50 transition-all">
                            </div>
                        </div>
                        <p class="text-[10px] text-gray-400 mt-3 flex items-center gap-1 leading-relaxed">
                            <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"
                                    clip-rule="evenodd"></path>
                            </svg>
                            Format: PDF, JPG, PNG (Maksimum 5MB setiap fail)
                        </p>
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Catatan Tambahan</label>
                        <textarea name="catatan" id="catatan" rows="3"
                            placeholder="Masukkan sebarang maklumat tambahan jika perlu..."
                            class="w-full px-4 py-3 bg-white border border-gray-200 rounded-xl focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 transition-all outline-none resize-none text-gray-700"></textarea>
                    </div>
                </div>

                <div id="deathFormLoading" class="hidden text-center py-6 animate-pulse">
                    <svg class="animate-spin h-10 w-10 text-blue-600 mx-auto mb-2" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                            stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor"
                            d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                        </path>
                    </svg>
                    <p class="text-sm font-bold text-blue-600">Sedang menyimpan rekod...</p>
                </div>

                <div class="flex gap-3 pt-6 mt-6 border-t border-gray-100 sticky bottom-0 bg-white">
                    <button type="button" onclick="closeDeathModal()"
                        class="flex-1 px-6 py-3.5 bg-gray-100 text-gray-600 rounded-xl hover:bg-gray-200 transition-all font-bold text-sm">
                        Batal
                    </button>
                    <button type="submit"
                        class="flex-[2] px-6 py-3.5 bg-emerald-600 text-white rounded-xl hover:bg-emerald-700 hover:shadow-lg hover:shadow-emerald-200 transition-all font-bold text-sm">
                        Simpan Rekod Kematian
                    </button>
                </div>
            </form>
        </div>
    </div>



    <!-- Modal for Adding Death Record - TANGGUNGAN -->
    <div id="deathModalTanggungan"
        class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm flex items-center justify-center p-4 z-50 hidden transition-all duration-300">
        <div
            class="bg-white rounded-2xl shadow-2xl w-full max-w-lg overflow-hidden flex flex-col max-h-[92vh] animate-in fade-in zoom-in duration-200">

            <div class="px-6 py-5 border-b border-gray-100 flex justify-between items-center bg-white sticky top-0 z-10">
                <div>
                    <h3 class="text-xl font-bold text-gray-900 leading-tight">Lapor Kematian - Tanggungan</h3>
                    <p class="text-xs text-gray-500 mt-0.5">Sila lengkapkan maklumat simati di bawah</p>
                </div>
                <button onclick="closeDeathModalTanggungan()"
                    class="p-2 rounded-full hover:bg-gray-100 text-gray-400 hover:text-gray-600 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <form id="deathRecordTanggunganForm" enctype="multipart/form-data"
                class="overflow-y-auto p-6 custom-scrollbar">
                @csrf
                <input type="hidden" name="tanggungan_id" id="death_tanggungan_id">
                <input type="hidden" name="ahli_id_tanggungan" id="death_ahli_id_tanggungan">
                <input type="hidden" name="type" id="death_user_type" value="tanggungan">

                <div class="flex items-start gap-4 p-4 rounded-xl bg-indigo-50 border border-indigo-100 mb-6">
                    <div class="p-3 bg-white text-indigo-600 rounded-lg shadow-sm">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                    </div>

                    <div>
                        <p class="text-xs font-bold text-indigo-400 uppercase tracking-widest">Maklumat Si Mati</p>
                        <div id="death_tanggungan_name" class="text-lg font-bold text-gray-800"></div>
                        <div id="death_tanggungan_ic" class="text-sm text-gray-500 font-medium"></div>
                    </div>
                </div>

                <div class="space-y-6">
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Tarikh Meninggal <span
                                class="text-red-500">*</span></label>
                        <div class="relative">
                            <input type="date" name="tarikh_meninggal" id="tarikh_meninggal_tanggungan" required
                                class="w-full pl-4 pr-4 py-3 bg-white border border-gray-200 rounded-xl focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all outline-none text-gray-700">
                        </div>
                    </div>

                    <!-- Status Pengurusan Jenazah Dropdown -->
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Status Pengurusan Jenazah <span
                                class="text-red-500">*</span></label>

                        <div class="space-y-4">
                            <!-- Option 1: Dalam Pengurusan -->
                            <div class="border border-gray-200 rounded-xl p-4 bg-white">
                                <label class="flex items-center cursor-pointer">
                                    <input type="radio" name="status_jenazah" value="PROCESSING"
                                        onchange="toggleItemsSectionTanggungan(false)"
                                        class="w-5 h-5 text-amber-600 focus:ring-amber-500">
                                    <span class="ml-3 text-gray-700 font-semibold">Dalam Pengurusan Jenazah</span>
                                </label>
                                <p class="text-xs text-gray-500 mt-2 ml-8">Jenazah sedang diuruskan. Tiada item perincian
                                    diperlukan.</p>
                            </div>

                            <!-- Option 2: Pengurusan Selesai -->
                            <div class="border border-gray-200 rounded-xl p-4 bg-white">
                                <label class="flex items-center cursor-pointer">
                                    <input type="radio" name="status_jenazah" value="SUCCESS"
                                        onchange="toggleItemsSectionTanggungan(true)"
                                        class="w-5 h-5 text-emerald-600 focus:ring-emerald-500">
                                    <span class="ml-3 text-gray-700 font-semibold">Pengurusan Jenazah Selesai</span>
                                </label>
                                <p class="text-xs text-gray-500 mt-2 ml-8">Sila nyatakan perincian item dan jumlah bayaran.
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Items Section - Hidden by default, shown when SUCCESS is selected -->
                    <div id="itemsSectionTanggungan" class="hidden space-y-4 mt-4 border-t border-gray-200 pt-4">
                        <label class="block text-sm font-bold text-gray-700 mb-2">Perincian Item & Bayaran <span
                                class="text-red-500">*</span></label>

                        <div class="bg-gray-50 rounded-xl p-4 space-y-3">
                            <!-- Item 1: Pengurusan Jenazah -->
                            <div class="flex items-center gap-3">
                                <input type="checkbox" name="items[]" value="pengurusan_jenazah"
                                    onchange="toggleAmountInputTanggungan(this, 'amount_pengurusan_jenazah_tanggungan')"
                                    class="w-5 h-5 text-emerald-600 rounded focus:ring-emerald-500">
                                <label class="flex-1 text-gray-700 font-medium">Pengurusan Jenazah</label>
                                <input type="number" name="amount_pengurusan_jenazah"
                                    id="amount_pengurusan_jenazah_tanggungan" placeholder="RM 0.00" disabled
                                    class="w-40 px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 disabled:bg-gray-100 disabled:cursor-not-allowed">
                            </div>

                            <!-- Item 2: Pengangkutan Jenazah -->
                            <div class="flex items-center gap-3">
                                <input type="checkbox" name="items[]" value="pengangkutan_jenazah"
                                    onchange="toggleAmountInputTanggungan(this, 'amount_pengangkutan_jenazah_tanggungan')"
                                    class="w-5 h-5 text-emerald-600 rounded focus:ring-emerald-500">
                                <label class="flex-1 text-gray-700 font-medium">Van Jenazah</label>
                                <input type="number" name="amount_pengangkutan_jenazah"
                                    id="amount_pengangkutan_jenazah_tanggungan" placeholder="RM 0.00" disabled
                                    class="w-40 px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 disabled:bg-gray-100 disabled:cursor-not-allowed">
                            </div>

                            <!-- Item 3: Tanah Perkuburan -->
                            <div class="flex items-center gap-3">
                                <input type="checkbox" name="items[]" value="tanah_perkuburan"
                                    onchange="toggleAmountInputTanggungan(this, 'amount_tanah_perkuburan_tanggungan')"
                                    class="w-5 h-5 text-emerald-600 rounded focus:ring-emerald-500">
                                <label class="flex-1 text-gray-700 font-medium">Gali Kubur</label>
                                <input type="number" name="amount_tanah_perkuburan"
                                    id="amount_tanah_perkuburan_tanggungan" placeholder="RM 0.00" disabled
                                    class="w-40 px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 disabled:bg-gray-100 disabled:cursor-not-allowed">
                            </div>

                            <!-- Item 4: Kain Kafan -->
                            <div class="flex items-center gap-3">
                                <input type="checkbox" name="items[]" value="kain_kafan"
                                    onchange="toggleAmountInputTanggungan(this, 'amount_kain_kafan_tanggungan')"
                                    class="w-5 h-5 text-emerald-600 rounded focus:ring-emerald-500">
                                <label class="flex-1 text-gray-700 font-medium">Kain Kafan</label>
                                <input type="number" name="amount_kain_kafan" id="amount_kain_kafan_tanggungan"
                                    placeholder="RM 0.00" disabled
                                    class="w-40 px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 disabled:bg-gray-100 disabled:cursor-not-allowed">
                            </div>

                            <!-- Item 5: Air / Mandian -->
                            <div class="flex items-center gap-3">
                                <input type="checkbox" name="items[]" value="air_mandian"
                                    onchange="toggleAmountInputTanggungan(this, 'amount_air_mandian_tanggungan')"
                                    class="w-5 h-5 text-emerald-600 rounded focus:ring-emerald-500">
                                <label class="flex-1 text-gray-700 font-medium">Air / Mandian</label>
                                <input type="number" name="amount_air_mandian" id="amount_air_mandian_tanggungan"
                                    placeholder="RM 0.00" disabled
                                    class="w-40 px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 disabled:bg-gray-100 disabled:cursor-not-allowed">
                            </div>

                            <!-- Item 6: Imam / Bilal -->
                            <div class="flex items-center gap-3">
                                <input type="checkbox" name="items[]" value="imam_bilal"
                                    onchange="toggleAmountInputTanggungan(this, 'amount_imam_bilal_tanggungan')"
                                    class="w-5 h-5 text-emerald-600 rounded focus:ring-emerald-500">
                                <label class="flex-1 text-gray-700 font-medium">Imam / Bilal</label>
                                <input type="number" name="amount_imam_bilal" id="amount_imam_bilal_tanggungan"
                                    placeholder="RM 0.00" disabled
                                    class="w-40 px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 disabled:bg-gray-100 disabled:cursor-not-allowed">
                            </div>

                            <!-- Item 7: Lain-lain -->
                            <div class="flex items-center gap-3">
                                <input type="checkbox" name="items[]" value="lain_lain"
                                    onchange="toggleAmountInputTanggungan(this, 'amount_lain_lain_tanggungan'); toggleLainLainTextTanggungan()"
                                    class="w-5 h-5 text-emerald-600 rounded focus:ring-emerald-500">
                                <label class="flex-1 text-gray-700 font-medium">Lain-lain</label>
                                <input type="text" name="lain_lain_text" id="lain_lain_text_tanggungan"
                                    placeholder="Nyatakan item" disabled
                                    class="hidden w-32 px-2 py-2 border border-gray-300 rounded-lg text-sm">
                                <input type="number" name="amount_lain_lain" id="amount_lain_lain_tanggungan"
                                    placeholder="RM 0.00" disabled
                                    class="w-40 px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 disabled:bg-gray-100 disabled:cursor-not-allowed">
                            </div>
                        </div>

                        <!-- Total Amount Display -->
                        <div class="bg-emerald-50 rounded-xl p-4 border border-emerald-200">
                            <div class="flex justify-between items-center">
                                <span class="font-bold text-gray-700">Jumlah Keseluruhan:</span>
                                <span id="totalAmountDisplayTanggungan" class="text-xl font-bold text-emerald-600">RM
                                    0.00</span>
                            </div>
                            <input type="hidden" name="total_amount" id="total_amount_tanggungan" value="0">
                        </div>
                    </div>

                    <div class="bg-gray-50/50 rounded-2xl p-4 border border-dashed border-gray-200">
                        <label class="block text-sm font-bold text-gray-700 mb-4">Muat Naik Dokumen <span
                                class="text-red-500">*</span></label>

                        <div class="grid grid-cols-1 gap-4">
                            <div>
                                <label class="block text-[11px] font-bold text-gray-500 uppercase ml-1 mb-1">Sijil
                                    Kematian</label>
                                <input type="file" name="sijil_kematian" id="sijil_kematian_tanggungan"
                                    accept=".pdf,.jpg,.jpeg,.png"
                                    class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-xs file:font-bold file:bg-white file:text-indigo-600 file:shadow-sm hover:file:bg-indigo-50 border border-gray-200 rounded-xl p-1.5 bg-white/50 transition-all">
                            </div>

                            <div>
                                <label class="block text-[11px] font-bold text-gray-500 uppercase ml-1 mb-1">Laporan
                                    Polis</label>
                                <input type="file" name="laporan_polis" id="laporan_polis_tanggungan"
                                    accept=".pdf,.jpg,.jpeg,.png"
                                    class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-xs file:font-bold file:bg-white file:text-indigo-600 file:shadow-sm hover:file:bg-indigo-50 border border-gray-200 rounded-xl p-1.5 bg-white/50 transition-all">
                            </div>

                            <div class="group relative">
                                <label class="block text-[11px] font-bold text-gray-500 uppercase ml-1 mb-1">Dokumen
                                    Sokongan<span class="text-xs text-red-500 px-2">(JIKA ADA)</span></label>
                                <input type="file" name="maklumat_lain" id="maklumat_lain_tanggungan"
                                    accept=".pdf,.jpg,.jpeg,.png"
                                    class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-xs file:font-bold file:bg-white file:text-blue-600 file:shadow-sm hover:file:bg-blue-50 border border-gray-200 rounded-xl p-1.5 bg-white/50 transition-all">
                            </div>
                        </div>
                        <p class="text-[10px] text-gray-400 mt-3 flex items-center gap-1">
                            <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"
                                    clip-rule="evenodd"></path>
                            </svg>
                            Format: PDF, JPG, PNG (Maksimum 5MB setiap fail)
                        </p>
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Catatan Tambahan</label>
                        <textarea name="catatan" id="catatan_tanggungan" rows="3"
                            placeholder="Masukkan sebarang maklumat tambahan jika perlu..."
                            class="w-full px-4 py-3 bg-white border border-gray-200 rounded-xl focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all outline-none resize-none text-gray-700"></textarea>
                    </div>
                </div>

                <div id="deathFormTanggunganLoading" class="hidden text-center py-6 animate-pulse">
                    <svg class="animate-spin h-10 w-10 text-indigo-600 mx-auto mb-2" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                            stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor"
                            d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                        </path>
                    </svg>
                    <p class="text-sm font-bold text-indigo-600">Sedang menyimpan rekod...</p>
                </div>

                <div class="flex gap-3 pt-6 mt-6 border-t border-gray-100 sticky bottom-0 bg-white">
                    <button type="button" onclick="closeDeathModalTanggungan()"
                        class="flex-1 px-6 py-3.5 bg-gray-100 text-gray-600 rounded-xl hover:bg-gray-200 transition-all font-bold text-sm">
                        Batal
                    </button>
                    <button type="submit"
                        class="flex-[2] px-6 py-3.5 bg-emerald-600 text-white rounded-xl hover:bg-emerald-700 hover:shadow-lg hover:shadow-emerald-200 transition-all font-bold text-sm">
                        Simpan Rekod Kematian
                    </button>
                </div>
            </form>
        </div>
    </div>



    <script>
        // Store current claim data
        let currentClaimUrl = '';
        let currentSubscriptionValid = false;

        function openClaimModal(userId, userName, userIC, relation, startDate, endDate, status, claimUrl) {
            // Set modal data
            document.getElementById('modalUserName').innerHTML = `<i class="fas fa-user mr-2"></i>${userName}`;
            document.getElementById('modalUserIC').innerHTML = `<i class="fas fa-id-card mr-2"></i>${userIC}`;

            if (relation && relation !== '') {
                document.getElementById('modalUserRelation').innerHTML =
                    `<i class="fas fa-users mr-2"></i>Hubungan: ${relation}`;
                document.getElementById('modalUserRelation').style.display = 'block';
            } else {
                document.getElementById('modalUserRelation').style.display = 'none';
            }

            document.getElementById('modalStartDate').innerHTML = formatDate(startDate);
            document.getElementById('modalEndDate').innerHTML = formatDate(endDate);

            // Format and set status
            const isValid = status === 'active' || status === 'Aktif';
            const statusBadge = isValid ?
                '<span class="text-green-600 font-semibold">✓ Aktif</span>' :
                '<span class="text-red-600 font-semibold">✗ Tamat Tempoh</span>';
            document.getElementById('modalStatus').innerHTML = statusBadge;

            // Calculate remaining days
            if (endDate && endDate !== '') {
                const end = new Date(endDate);
                const now = new Date();
                const diffTime = end - now;
                const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));

                const remainingDaysElement = document.getElementById('modalRemainingDays');
                if (diffDays > 0) {
                    remainingDaysElement.innerHTML = `${diffDays} hari lagi`;
                    remainingDaysElement.className = 'text-sm font-bold text-green-600';
                } else if (diffDays === 0) {
                    remainingDaysElement.innerHTML = 'Hari terakhir';
                    remainingDaysElement.className = 'text-sm font-bold text-orange-600';
                } else {
                    remainingDaysElement.innerHTML = `Tamat ${Math.abs(diffDays)} hari lepas`;
                    remainingDaysElement.className = 'text-sm font-bold text-red-600';
                }
            } else {
                document.getElementById('modalRemainingDays').innerHTML = '-';
            }

            // Show/hide expired warning
            currentSubscriptionValid = isValid;

            if (!isValid) {
                document.getElementById('expiredWarning').classList.remove('hidden');
                document.getElementById('confirmClaimBtn').disabled = true;
                document.getElementById('confirmClaimBtn').classList.add('opacity-50', 'cursor-not-allowed');
                document.getElementById('confirmClaimBtn').classList.remove('hover:bg-blue-700');
            } else {
                document.getElementById('expiredWarning').classList.add('hidden');
                document.getElementById('confirmClaimBtn').disabled = false;
                document.getElementById('confirmClaimBtn').classList.remove('opacity-50', 'cursor-not-allowed');
                document.getElementById('confirmClaimBtn').classList.add('hover:bg-blue-700');
            }

            // Store claim URL
            currentClaimUrl = claimUrl;

            // Show modal with animation
            const modal = document.getElementById('claimModal');
            modal.classList.remove('hidden');
            setTimeout(() => {
                modal.style.opacity = '1';
            }, 10);
        }

        function closeModal() {
            const modal = document.getElementById('claimModal');
            modal.classList.add('hidden');
            modal.style.opacity = '0';
            currentClaimUrl = '';
            currentSubscriptionValid = false;
        }

        function formatDate(dateString) {
            if (!dateString || dateString === '') return '-';
            try {
                const date = new Date(dateString);
                return date.toLocaleDateString('ms-MY', {
                    day: 'numeric',
                    month: 'long',
                    year: 'numeric'
                });
            } catch (e) {
                return '-';
            }
        }

        // Confirm claim action
        document.addEventListener('DOMContentLoaded', function() {
            const confirmBtn = document.getElementById('confirmClaimBtn');
            if (confirmBtn) {
                confirmBtn.addEventListener('click', function() {
                    if (currentClaimUrl && currentSubscriptionValid) {
                        window.location.href = currentClaimUrl;
                    } else if (!currentSubscriptionValid) {
                        alert('Rekod kematian tidak boleh dibuat. Keahlian telah tamat tempoh.');
                        closeModal();
                    }
                });
            }
        });

        // Close modal when clicking outside
        window.onclick = function(event) {
            const modal = document.getElementById('claimModal');
            if (event.target === modal) {
                closeModal();
            }
        }
    </script>

    <script>
        // ========== DEATH RECORD MODAL FUNCTIONS ==========

        let currentDeathUserId = null;

        function openDeathModal(userId, userName, userIC) {
            currentDeathUserId = userId;
            document.getElementById('death_ahli_id').value = userId; // Changed from death_user_id
            document.getElementById('death_user_name').innerHTML = `<i class="fas fa-user mr-2"></i>${userName}`;
            document.getElementById('death_user_ic').innerHTML = `<i class="fas fa-id-card mr-2"></i>${userIC}`;

            // Reset form
            document.getElementById('deathRecordForm').reset();
            document.getElementById('deathFormLoading').classList.add('hidden');

            // Show modal
            const modal = document.getElementById('deathModal');
            modal.classList.remove('hidden');
        }

        function closeDeathModal() {
            const modal = document.getElementById('deathModal');
            modal.classList.add('hidden');
            document.getElementById('deathRecordForm').reset();
            currentDeathUserId = null;
        }

        // Handle death record form submission
        document.getElementById('deathRecordForm').addEventListener('submit', async function(e) {
            e.preventDefault();

            const formData = new FormData(this);
            const loadingDiv = document.getElementById('deathFormLoading');
            const submitBtn = this.querySelector('button[type="submit"]');

            // Show loading
            loadingDiv.classList.remove('hidden');
            submitBtn.disabled = true;

            try {
                const response = await fetch('{{ route('khairat.death.store.ahli') }}', {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute(
                            'content') || '{{ csrf_token() }}',
                        'Accept': 'application/json' // Add this header
                    }
                });

                // Check if response is OK
                if (!response.ok) {
                    const text = await response.text();
                    console.error('Response error:', text);
                    throw new Error(`HTTP error! status: ${response.status}`);
                }

                const result = await response.json();

                if (result.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Berjaya!',
                        text: result.message,
                        confirmButtonColor: '#3085d6'
                    }).then(() => {
                        location.reload();
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Ralat!',
                        text: result.message || 'Gagal menyimpan rekod',
                        confirmButtonColor: '#d33'
                    });
                }
            } catch (error) {
                console.error('Error:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Ralat!',
                    text: 'Ralat berlaku. Sila cuba lagi. Error: ' + error.message,
                    confirmButtonColor: '#d33'
                });
            } finally {
                loadingDiv.classList.add('hidden');
                submitBtn.disabled = false;
            }
        });

        // View death record
        async function openViewDeathModal(userId, userName) {


            // Show loading state
            content.innerHTML = `
            <div class="text-center py-8">
                <svg class="animate-spin h-8 w-8 text-blue-600 mx-auto" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <p class="text-gray-600 mt-2">Memuatkan rekod...</p>
            </div>
        `;

            modal.classList.remove('hidden');

            try {
                const response = await fetch('{{ url('/khairat/lihat-kematian') }}/' + userId);
                const result = await response.json();

                if (result.success && result.data) {
                    const data = result.data;
                    let certificateLink = '';
                    if (data.death_certificate_path) {
                        certificateLink = `<a href="${data.death_certificate_path}" target="_blank" class="text-blue-600 hover:text-blue-800 underline flex items-center gap-1">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        Lihat Sijil Kematian
                    </a>`;
                    } else {
                        certificateLink = '<span class="text-gray-500">Tiada sijil</span>';
                    }

                    content.innerHTML = `
                    <div class="bg-blue-50 rounded-lg p-4">
                        <div class="space-y-2">
                            <div class="flex justify-between">
                                <span class="text-sm text-gray-600">Nama:</span>
                                <span class="text-sm font-medium text-gray-800">${data.nama}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-sm text-gray-600">No. IC:</span>
                                <span class="text-sm font-medium text-gray-800">${data.ic}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-sm text-gray-600">Jenis:</span>
                                <span class="text-sm font-medium text-gray-800">${data.type === 'ahli' ? 'Ketua Keluarga' : 'Tanggungan'}</span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="bg-red-50 rounded-lg p-4">
                        <div class="space-y-3">
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-gray-600">Tarikh Meninggal:</span>
                                <span class="text-sm font-semibold text-gray-800">${data.tarikh_meninggal}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-sm text-gray-600">Tempat Meninggal:</span>
                                <span class="text-sm text-gray-800">${data.tempat_meninggal}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-sm text-gray-600">Sebab Meninggal:</span>
                                <span class="text-sm text-gray-800">${data.sebab_meninggal}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-sm text-gray-600">Status:</span>
                                <span class="text-sm font-semibold ${data.status === 'PAID' ? 'text-green-600' : (data.status === 'PENDING' ? 'text-yellow-600' : 'text-red-600')}">
                                    ${data.status === 'PAID' ? '✓ Diluluskan' : (data.status === 'PENDING' ? '⏳ Dalam Proses' : '✗ Ditolak')}
                                </span>
                            </div>
                            ${data.catatan && data.catatan !== '-' ? `
                                                                                                                                                                                               ` : ''}
                            <div class="border-t pt-2 mt-2">
                                <div class="flex justify-between items-center">
                                    <span class="text-sm text-gray-600">Sijil Kematian:</span>
                                    ${certificateLink}
                                </div>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-sm text-gray-600">Tarikh Rekod:</span>
                                <span class="text-sm text-gray-800">${data.created_at}</span>
                            </div>
                        </div>
                    </div>
                `;
                } else {
                    content.innerHTML = `
                    <div class="bg-yellow-50 rounded-lg p-4 text-center">
                        <svg class="w-12 h-12 text-yellow-600 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <p class="text-gray-700">Tiada rekod kematian ditemui.</p>
                    </div>
                `;
                }
            } catch (error) {
                console.error('Error:', error);
                content.innerHTML = `
                <div class="bg-red-50 rounded-lg p-4 text-center">
                    <svg class="w-12 h-12 text-red-600 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <p class="text-gray-700">Ralat memuatkan rekod. Sila cuba lagi.</p>
                </div>
            `;
            }
        }
    </script>

    <script>
        // ========== TANGGUNGAN DEATH RECORD MODAL FUNCTIONS ==========

        let currentTanggunganId = null;
        let currentAhliId = null;

        // Toggle items section based on status selection
        function toggleItemsSectionTanggungan(show) {
            const itemsSection = document.getElementById('itemsSectionTanggungan');
            if (show) {
                itemsSection.classList.remove('hidden');
            } else {
                itemsSection.classList.add('hidden');
                // Reset all checkboxes and amounts when hiding
                resetItemsAndAmountsTanggungan();
            }
        }

        // Reset all checkboxes and amount inputs
        function resetItemsAndAmountsTanggungan() {
            const checkboxes = document.querySelectorAll('#itemsSectionTanggungan input[type="checkbox"]');
            checkboxes.forEach(checkbox => {
                checkbox.checked = false;
                checkbox.dispatchEvent(new Event('change'));
            });
        }

        // Toggle amount input based on checkbox state
        function toggleAmountInputTanggungan(checkbox, inputId) {
            const amountInput = document.getElementById(inputId);
            if (amountInput) {
                if (checkbox.checked) {
                    amountInput.disabled = false;
                    amountInput.required = true;
                    amountInput.classList.remove('bg-gray-100', 'cursor-not-allowed');
                    amountInput.classList.add('bg-white');
                } else {
                    amountInput.disabled = true;
                    amountInput.required = false;
                    amountInput.value = '';
                    amountInput.classList.add('bg-gray-100', 'cursor-not-allowed');
                    amountInput.classList.remove('bg-white');
                }
            }
            calculateTotalTanggungan();
        }

        // Toggle lain-lain text input
        function toggleLainLainTextTanggungan() {
            const lainLainCheckbox = document.querySelector('#itemsSectionTanggungan input[value="lain_lain"]');
            const lainLainText = document.getElementById('lain_lain_text_tanggungan');

            if (lainLainCheckbox && lainLainCheckbox.checked) {
                lainLainText.classList.remove('hidden');
                lainLainText.disabled = false;
                lainLainText.required = true;
            } else {
                lainLainText.classList.add('hidden');
                lainLainText.disabled = true;
                lainLainText.required = false;
                lainLainText.value = '';
            }
        }

        // Calculate total amount from all checked items
        function calculateTotalTanggungan() {
            let total = 0;

            // Get all amount inputs that are enabled (from checked checkboxes)
            const amountInputs = document.querySelectorAll('#itemsSectionTanggungan input[type="number"]');

            amountInputs.forEach(input => {
                if (!input.disabled && input.value && !isNaN(parseFloat(input.value))) {
                    total += parseFloat(input.value);
                }
            });

            // Update display
            const totalDisplay = document.getElementById('totalAmountDisplayTanggungan');
            const totalHidden = document.getElementById('total_amount_tanggungan');

            if (totalDisplay) {
                totalDisplay.textContent = 'RM ' + total.toFixed(2);
            }
            if (totalHidden) {
                totalHidden.value = total;
            }
        }

        function openDeathModalTanggungan(tanggunganId, nama, ic, ahliId) {
            console.log('openDeathModalTanggungan called with:', {
                tanggunganId: tanggunganId,
                nama: nama,
                ic: ic,
                ahliId: ahliId
            });

            currentTanggunganId = tanggunganId;
            currentAhliId = ahliId;

            // Set the hidden inputs
            const tanggunganInput = document.getElementById('death_tanggungan_id');
            const ahliInput = document.getElementById('death_ahli_id_tanggungan');
            const typeInput = document.getElementById('death_user_type');

            if (tanggunganInput) {
                tanggunganInput.value = tanggunganId;
                console.log('Set tanggungan_id to:', tanggunganId);
            } else {
                console.error('death_tanggungan_id element not found!');
            }

            if (ahliInput) {
                ahliInput.value = ahliId;
                console.log('Set ahli_id_tanggungan to:', ahliId);
            } else {
                console.error('death_ahli_id_tanggungan element not found!');
            }

            if (typeInput) {
                typeInput.value = 'tanggungan';
            }

            // Set display values
            const nameElement = document.getElementById('death_tanggungan_name');
            const icElement = document.getElementById('death_tanggungan_ic');

            if (nameElement) nameElement.innerHTML = nama;
            if (icElement) icElement.innerHTML = ic;

            // Reset form
            const form = document.getElementById('deathRecordTanggunganForm');
            if (form) form.reset();

            // Reset items section
            const itemsSection = document.getElementById('itemsSectionTanggungan');
            if (itemsSection) itemsSection.classList.add('hidden');

            resetItemsAndAmountsTanggungan();

            const loadingDiv = document.getElementById('deathFormTanggunganLoading');
            if (loadingDiv) loadingDiv.classList.add('hidden');

            // Show modal
            const modal = document.getElementById('deathModalTanggungan');
            if (modal) modal.classList.remove('hidden');
        }

        function closeDeathModalTanggungan() {
            const modal = document.getElementById('deathModalTanggungan');
            modal.classList.add('hidden');
            document.getElementById('deathRecordTanggunganForm').reset();
            currentTanggunganId = null;
            currentAhliId = null;
        }

        // Add event listeners to amount inputs for real-time calculation
        document.addEventListener('DOMContentLoaded', function() {
            // Add event listeners to all amount inputs in tanggungan modal
            const amountInputs = document.querySelectorAll('#itemsSectionTanggungan input[type="number"]');
            amountInputs.forEach(input => {
                input.addEventListener('input', calculateTotalTanggungan);
                input.addEventListener('keyup', calculateTotalTanggungan);
            });
        });

        // Handle tanggungan death record form submission
        document.getElementById('deathRecordTanggunganForm')?.addEventListener('submit', async function(e) {
            e.preventDefault();

            const statusSelect = document.querySelector(
                '#deathRecordTanggunganForm input[name="status_jenazah"]:checked');
            const isSuccess = statusSelect && statusSelect.value === 'SUCCESS';

            if (isSuccess) {
                // Check if at least one item is selected
                const checkedItems = document.querySelectorAll(
                    '#itemsSectionTanggungan input[type="checkbox"]:checked');

                if (checkedItems.length === 0) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Perhatian!',
                        text: 'Sila pilih sekurang-kurangnya satu item untuk Pengurusan jenazah yang selesai.',
                        confirmButtonColor: '#d33'
                    });
                    return false;
                }

                // Validate each checked item has an amount
                let hasInvalidAmount = false;
                for (const checkbox of checkedItems) {
                    const itemValue = checkbox.value;
                    let amountInputId = '';

                    switch (itemValue) {
                        case 'pengurusan_jenazah':
                            amountInputId = 'amount_pengurusan_jenazah_tanggungan';
                            break;
                        case 'pengangkutan_jenazah':
                            amountInputId = 'amount_pengangkutan_jenazah_tanggungan';
                            break;
                        case 'tanah_perkuburan':
                            amountInputId = 'amount_tanah_perkuburan_tanggungan';
                            break;
                        case 'kain_kafan':
                            amountInputId = 'amount_kain_kafan_tanggungan';
                            break;
                        case 'air_mandian':
                            amountInputId = 'amount_air_mandian_tanggungan';
                            break;
                        case 'imam_bilal':
                            amountInputId = 'amount_imam_bilal_tanggungan';
                            break;
                        case 'lain_lain':
                            amountInputId = 'amount_lain_lain_tanggungan';
                            break;
                    }

                    const amountInput = document.getElementById(amountInputId);
                    if (!amountInput || !amountInput.value || parseFloat(amountInput.value) <= 0) {
                        hasInvalidAmount = true;
                        const label = checkbox.parentElement.querySelector('label:not(.flex)')?.innerText ||
                            itemValue;
                        Swal.fire({
                            icon: 'warning',
                            title: 'Perhatian!',
                            text: `Sila masukkan jumlah bayaran untuk item: ${label}`,
                            confirmButtonColor: '#d33'
                        });
                        amountInput?.focus();
                        return false;
                    }
                }

                if (hasInvalidAmount) {
                    return false;
                }
            }

            const formData = new FormData(this);
            const loadingDiv = document.getElementById('deathFormTanggunganLoading');
            const submitBtn = this.querySelector('button[type="submit"]');

            // Log form data for debugging
            console.log('Form Data Contents:');
            for (let pair of formData.entries()) {
                console.log(pair[0] + ': ' + pair[1]);
            }

            loadingDiv.classList.remove('hidden');
            submitBtn.disabled = true;

            try {
                const response = await fetch('{{ route('khairat.storeDeathTanggungan') }}', {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute(
                            'content') || '{{ csrf_token() }}',
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });

                const responseText = await response.text();
                console.log('Response text:', responseText.substring(0, 500));

                let result;
                try {
                    result = JSON.parse(responseText);
                } catch (e) {
                    console.error('Failed to parse JSON:', e);
                    throw new Error('Server returned invalid response.');
                }

                if (result.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Berjaya!',
                        text: result.message,
                        confirmButtonColor: '#3085d6'
                    }).then(() => {
                        location.reload();
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Ralat!',
                        text: result.message || 'Gagal menyimpan rekod',
                        confirmButtonColor: '#d33'
                    });
                }
            } catch (error) {
                console.error('Error:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Ralat!',
                    text: error.message || 'Ralat berlaku. Sila cuba lagi.',
                    confirmButtonColor: '#d33'
                });
            } finally {
                loadingDiv.classList.add('hidden');
                submitBtn.disabled = false;
            }
        });
    </script>

    <script>
        // Toggle items section based on status selection
        function toggleItemsSection(show) {
            const itemsSection = document.getElementById('itemsSection');
            if (show) {
                itemsSection.classList.remove('hidden');
            } else {
                itemsSection.classList.add('hidden');
                // Reset all checkboxes and amounts when hiding
                resetItemsAndAmounts();
            }
        }

        // Reset all checkboxes and amount inputs
        function resetItemsAndAmounts() {
            const checkboxes = document.querySelectorAll('#itemsSection input[type="checkbox"]');
            checkboxes.forEach(checkbox => {
                checkbox.checked = false;
                checkbox.dispatchEvent(new Event('change'));
            });
        }

        // Toggle amount input based on checkbox state
        function toggleAmountInput(checkbox, inputId) {
            const amountInput = document.getElementById(inputId);
            if (amountInput) {
                if (checkbox.checked) {
                    amountInput.disabled = false;
                    amountInput.required = true;
                    amountInput.classList.remove('bg-gray-100', 'cursor-not-allowed');
                    amountInput.classList.add('bg-white');
                } else {
                    amountInput.disabled = true;
                    amountInput.required = false;
                    amountInput.value = '';
                    amountInput.classList.add('bg-gray-100', 'cursor-not-allowed');
                    amountInput.classList.remove('bg-white');
                }
            }
            calculateTotal();
        }

        // Toggle lain-lain text input
        function toggleLainLainText() {
            const lainLainCheckbox = document.querySelector('input[value="lain_lain"]');
            const lainLainText = document.getElementById('lain_lain_text');

            if (lainLainCheckbox && lainLainCheckbox.checked) {
                lainLainText.classList.remove('hidden');
                lainLainText.disabled = false;
                lainLainText.required = true;
            } else {
                lainLainText.classList.add('hidden');
                lainLainText.disabled = true;
                lainLainText.required = false;
                lainLainText.value = '';
            }
        }

        // Calculate total amount from all checked items
        function calculateTotal() {
            let total = 0;

            // Get all amount inputs that are enabled (from checked checkboxes)
            const amountInputs = document.querySelectorAll('#itemsSection input[type="number"]');

            amountInputs.forEach(input => {
                if (!input.disabled && input.value && !isNaN(parseFloat(input.value))) {
                    total += parseFloat(input.value);
                }
            });

            // Update display
            const totalDisplay = document.getElementById('totalAmountDisplay');
            const totalHidden = document.getElementById('total_amount');

            if (totalDisplay) {
                totalDisplay.textContent = 'RM ' + total.toFixed(2);
            }
            if (totalHidden) {
                totalHidden.value = total;
            }
        }

        // Add event listeners to amount inputs for real-time calculation
        document.addEventListener('DOMContentLoaded', function() {
            // Add event listeners to all amount inputs
            const amountInputs = document.querySelectorAll('#itemsSection input[type="number"]');
            amountInputs.forEach(input => {
                input.addEventListener('input', calculateTotal);
                input.addEventListener('keyup', calculateTotal);
            });
        });

        // Override form submission to validate items when SUCCESS is selected
        const originalFormSubmit = document.getElementById('deathRecordForm')?.onsubmit;

        document.getElementById('deathRecordForm')?.addEventListener('submit', function(e) {
            const statusSelect = document.querySelector('input[name="status_jenazah"]:checked');
            const isSuccess = statusSelect && statusSelect.value === 'SUCCESS';

            if (isSuccess) {
                // Check if at least one item is selected
                const checkedItems = document.querySelectorAll('#itemsSection input[type="checkbox"]:checked');

                if (checkedItems.length === 0) {
                    e.preventDefault();
                    Swal.fire({
                        icon: 'warning',
                        title: 'Perhatian!',
                        text: 'Sila pilih sekurang-kurangnya satu item untuk Pengurusan jenazah yang selesai.',
                        confirmButtonColor: '#d33'
                    });
                    return false;
                }

                // Validate each checked item has an amount
                let hasInvalidAmount = false;
                checkedItems.forEach(checkbox => {
                    const itemValue = checkbox.value;
                    let amountInputId = '';

                    switch (itemValue) {
                        case 'pengurusan_jenazah':
                            amountInputId = 'amount_pengurusan_jenazah';
                            break;
                        case 'pengangkutan_jenazah':
                            amountInputId = 'amount_pengangkutan_jenazah';
                            break;
                        case 'tanah_perkuburan':
                            amountInputId = 'amount_tanah_perkuburan';
                            break;
                        case 'kain_kafan':
                            amountInputId = 'amount_kain_kafan';
                            break;
                        case 'air_mandian':
                            amountInputId = 'amount_air_mandian';
                            break;
                        case 'imam_bilal':
                            amountInputId = 'amount_imam_bilal';
                            break;
                        case 'lain_lain':
                            amountInputId = 'amount_lain_lain';
                            break;
                    }

                    const amountInput = document.getElementById(amountInputId);
                    if (!amountInput || !amountInput.value || parseFloat(amountInput.value) <= 0) {
                        hasInvalidAmount = true;
                        const label = checkbox.parentElement.querySelector('label:not(.flex)')?.innerText ||
                            itemValue;
                        Swal.fire({
                            icon: 'warning',
                            title: 'Perhatian!',
                            text: `Sila masukkan jumlah bayaran untuk item: ${label}`,
                            confirmButtonColor: '#d33'
                        });
                        amountInput?.focus();
                        e.preventDefault();
                        return false;
                    }
                });

                if (hasInvalidAmount) {
                    return false;
                }
            }

            // If validation passes, let the form submit normally
            return true;
        });
    </script>

@endsection
