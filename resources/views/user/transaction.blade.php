@extends('layouts.user')

@section('title', 'Laporan Kewangan')

@section('content')

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    </head>

    <body class="bg-slate-50/50 min-h-screen antialiased text-slate-800">
        <div class="lg:px-8 px-2 py-6 max-w-7xl mx-auto space-y-6">

            {{-- STATUS ALERTS --}}
            @if (session('success'))
                <div
                    class="bg-emerald-50 border border-emerald-200 text-emerald-800 p-4 rounded-xl flex items-center gap-3 text-sm shadow-sm">
                    <i class="fas fa-check-circle text-emerald-500 text-lg"></i>
                    <span class="font-medium">{{ session('success') }}</span>
                </div>
            @endif

            @if ($errors->any())
                <div class="bg-rose-50 border border-rose-200 text-rose-800 p-4 rounded-xl text-sm shadow-sm space-y-2">
                    <div class="flex items-center gap-3 font-semibold">
                        <i class="fas fa-exclamation-circle text-rose-500 text-lg"></i>
                        <span>Sila perbetulkan ralat berikut:</span>
                    </div>
                    <ul class="list-disc list-inside pl-5 space-y-1 text-rose-700">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- MAIN WRAPPER CONTAINER --}}
            <div class="space-y-6">

                {{-- SUBSCRIPTION STATUS CARD --}}
                <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
                    @if ($subscriptionStatus == 'active')
                        {{-- ACTIVE SUBSCRIPTION --}}
                        <div class="bg-gradient-to-r from-emerald-500/10 to-transparent border-b border-emerald-100 p-6">
                            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                                <div class="space-y-1.5">
                                    <div class="flex items-center gap-2.5">
                                        <span class="flex h-3 w-3 relative">
                                            <span
                                                class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                                            <span class="relative inline-flex rounded-full h-3 w-3 bg-emerald-500"></span>
                                        </span>
                                        <h3 class="text-lg font-bold text-slate-900">Status Keahlian: <span
                                                class="text-emerald-600">AKTIF</span></h3>
                                    </div>
                                    <div class="flex flex-wrap items-center gap-x-4 gap-y-1 text-sm text-slate-600">
                                        <span><i class="fas fa-calendar-alt text-slate-400 mr-1.5"></i>Tempoh:
                                            <b>{{ \Carbon\Carbon::parse($subscriptionStartDate)->format('d/m/Y') }} -
                                                {{ \Carbon\Carbon::parse($subscriptionEndDate)->format('d/m/Y') }}</b></span>
                                        <span class="hidden sm:inline text-slate-300">•</span>
                                        <span><i class="fas fa-hourglass-half text-slate-400 mr-1.5"></i>Baki: <span
                                                class="font-bold {{ $daysRemaining <= 30 ? 'text-amber-600' : 'text-emerald-600' }}">
                                                {{ floor($daysRemaining) }} Hari
                                            </span></span>
                                    </div>
                                    @if ($daysRemaining <= 30)
                                        <p
                                            class="text-xs font-medium text-amber-600 bg-amber-50 px-2.5 py-1 rounded-md w-fit">
                                            <i class="fas fa-exclamation-triangle mr-1"></i> Keahlian anda bakal tamat tidak
                                            lama lagi. Sila perbaharui sebelum tarikh tamat.
                                        </p>
                                    @endif
                                    @if ($renewalBlocked && $daysRemaining > 30)
                                        <p
                                            class="text-xs font-medium text-slate-500 bg-slate-50 px-2.5 py-1 rounded-md w-fit">
                                            <i class="fas fa-info-circle mr-1"></i> {{ $renewalMessage }}
                                        </p>
                                    @endif
                                </div>
                                @if ($canRenew && $daysRemaining <= 30)
                                    <button onclick="openModal()"
                                        class="bg-emerald-600 hover:bg-emerald-700 text-white px-5 py-2.5 rounded-xl text-sm font-semibold transition shadow-sm hover:shadow flex items-center justify-center gap-2 w-full sm:w-auto">
                                        <i class="fas fa-sync-alt"></i> Perbaharui Keahlian
                                    </button>
                                @elseif(!$canRenew && $daysRemaining > 30)
                                    <span
                                        class="bg-slate-100 text-slate-500 text-xs font-bold uppercase tracking-wider px-3.5 py-1.5 rounded-full shadow-sm w-fit flex items-center gap-1.5">
                                        <i class="fas fa-clock"></i> Tunggu 30 Hari Sebelum Tamat
                                    </span>
                                @elseif($canRenew && $daysRemaining > 30)
                                    <span
                                        class="bg-slate-100 text-slate-500 text-xs font-bold uppercase tracking-wider px-3.5 py-1.5 rounded-full shadow-sm w-fit flex items-center gap-1.5">
                                        <i class="fas fa-check-circle text-emerald-500"></i> Aktif
                                    </span>
                                @endif
                            </div>
                        </div>
                    @elseif($subscriptionStatus == 'pending_payment' && $transactionFor == 'new')
                        {{-- NEW MEMBER - PENDING PAYMENT --}}
                        <div class="bg-gradient-to-r from-amber-500/10 to-transparent border-b border-amber-100 p-6">
                            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                                <div class="space-y-1.5">
                                    <div class="flex items-center gap-2.5">
                                        <i class="fas fa-clock text-amber-500 text-xl"></i>
                                        <h3 class="text-lg font-bold text-slate-900">Status Keahlian: <span
                                                class="text-amber-600">MENUNGGU PEMBAYARAN</span></h3>
                                    </div>
                                    <p class="text-sm text-slate-600">
                                        <i class="fas fa-info-circle text-amber-500 mr-1.5"></i>Anda telah mendaftar sebagai
                                        ahli baru. Sila lengkapkan pembayaran untuk mengaktifkan keahlian.
                                    </p>
                                    <div class="flex flex-wrap items-center gap-x-4 gap-y-1 text-xs text-slate-500">
                                        <span><i class="fas fa-calendar-plus text-slate-400 mr-1.5"></i>Tarikh Daftar:
                                            <b>{{ \Carbon\Carbon::parse($subscription->created_at ?? now())->format('d/m/Y') }}</b></span>
                                        <span class="hidden sm:inline text-slate-300">•</span>
                                        <span><i class="fas fa-id-card text-slate-400 mr-1.5"></i>ID Permohonan:
                                            <b class="text-amber-600 font-mono">#{{ $subscription->id ?? 'N/A' }}</b></span>
                                    </div>
                                    @if ($subscription)
                                        <div class="mt-1 p-2 bg-amber-50 border border-amber-200 rounded-lg">
                                            <p class="text-[10px] text-amber-700">
                                                <i class="fas fa-info-circle mr-1"></i>
                                                Rujukan: <span class="font-mono font-bold">{{ $subscription->id }}</span>
                                                | Jenis: {{ ucfirst($transactionFor) }}
                                                | Status: {{ str_replace('_', ' ', $subscriptionStatus) }}
                                            </p>
                                        </div>
                                    @endif
                                </div>
                                <button onclick="openUpdateModalDirect('{{ $subscription->id }}')"
                                    class="bg-amber-600 hover:bg-amber-700 text-white px-5 py-2.5 rounded-xl text-sm font-semibold transition shadow-sm hover:shadow flex items-center justify-center gap-2 w-full sm:w-auto">
                                    <i class="fas fa-credit-card"></i> Buat Pembayaran
                                </button>
                            </div>
                        </div>
                    @elseif($subscriptionStatus == 'waiting_verification' && $transactionFor == 'new')
                        {{-- NEW MEMBER - WAITING VERIFICATION --}}
                        <div class="bg-gradient-to-r from-blue-500/10 to-transparent border-b border-blue-100 p-6">
                            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                                <div class="space-y-1.5">
                                    <div class="flex items-center gap-2.5">
                                        <span class="flex h-3 w-3 relative">
                                            <span
                                                class="animate-ping absolute inline-flex h-full w-full rounded-full bg-blue-400 opacity-75"></span>
                                            <span class="relative inline-flex rounded-full h-3 w-3 bg-blue-500"></span>
                                        </span>
                                        <h3 class="text-lg font-bold text-slate-900">Status Keahlian: <span
                                                class="text-blue-600">MENUNGGU VERIFIKASI</span></h3>
                                    </div>
                                    <p class="text-sm text-slate-600">
                                        <i class="fas fa-spinner fa-pulse text-blue-500 mr-1.5"></i>Resit pembayaran
                                        pendaftaran baru telah diterima. Sila tunggu pengesahan daripada pihak pengurusan.
                                    </p>
                                    <div class="flex flex-wrap items-center gap-x-4 gap-y-1 text-xs text-slate-500">
                                        <span><i class="fas fa-clock text-slate-400 mr-1.5"></i>Dalam Proses:
                                            <b>Verifikasi</b></span>
                                        <span class="hidden sm:inline text-slate-300">•</span>
                                        <span><i class="fas fa-file-invoice text-slate-400 mr-1.5"></i>Resit telah dimuat
                                            naik</span>
                                    </div>
                                </div>
                                <span
                                    class="bg-blue-100 text-blue-800 text-xs font-bold uppercase tracking-wider px-3.5 py-1.5 rounded-full shadow-sm w-fit flex items-center gap-1.5">
                                    <i class="fas fa-spinner fa-pulse"></i>Dalam Semakan
                                </span>
                            </div>
                        </div>
                    @elseif($subscriptionStatus == 'waiting_verification' && $transactionFor == 'renew')
                        {{-- RENEWAL - WAITING VERIFICATION --}}
                        <div class="bg-gradient-to-r from-blue-500/10 to-transparent border-b border-blue-100 p-6">
                            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                                <div class="space-y-1.5">
                                    <div class="flex items-center gap-2.5">
                                        <span class="flex h-3 w-3 relative">
                                            <span
                                                class="animate-ping absolute inline-flex h-full w-full rounded-full bg-blue-400 opacity-75"></span>
                                            <span class="relative inline-flex rounded-full h-3 w-3 bg-blue-500"></span>
                                        </span>
                                        <h3 class="text-lg font-bold text-slate-900">Status Keahlian: <span
                                                class="text-blue-600">MENUNGGU VERIFIKASI - PEMBAHARUAN</span></h3>
                                    </div>
                                    <p class="text-sm text-slate-600">
                                        <i class="fas fa-spinner fa-pulse text-blue-500 mr-1.5"></i>Resit pembayaran
                                        pembaharuan telah diterima. Sila tunggu pengesahan daripada pihak pengurusan.
                                    </p>
                                    <div class="flex flex-wrap items-center gap-x-4 gap-y-1 text-xs text-slate-500">
                                        <span><i class="fas fa-clock text-slate-400 mr-1.5"></i>Dalam Proses: <b>Verifikasi
                                                Pembaharuan</b></span>
                                        <span class="hidden sm:inline text-slate-300">•</span>
                                        <span><i class="fas fa-file-invoice text-slate-400 mr-1.5"></i>Resit telah dimuat
                                            naik</span>
                                    </div>
                                </div>
                                <span
                                    class="bg-blue-100 text-blue-800 text-xs font-bold uppercase tracking-wider px-3.5 py-1.5 rounded-full shadow-sm w-fit flex items-center gap-1.5">
                                    <i class="fas fa-spinner fa-pulse"></i>Dalam Semakan
                                </span>
                            </div>
                        </div>
                    @elseif($subscriptionStatus == 'expired')
                        {{-- EXPIRED SUBSCRIPTION --}}
                        <div class="bg-gradient-to-r from-rose-500/10 to-transparent border-b border-rose-100 p-6">
                            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                                <div class="space-y-1.5">
                                    <div class="flex items-center gap-2.5">
                                        <i class="fas fa-exclamation-triangle text-rose-500 text-xl"></i>
                                        <h3 class="text-lg font-bold text-slate-900">Status Keahlian: <span
                                                class="text-rose-600">TELAH TAMAT</span></h3>
                                    </div>
                                    @if ($lastExpiredDate)
                                        <p class="text-sm text-slate-600">
                                            <i class="fas fa-calendar-times text-slate-400 mr-1.5"></i>Tarikh Tamat
                                            Terakhir:
                                            <b>{{ \Carbon\Carbon::parse($lastExpiredDate)->format('d/m/Y') }}</b>
                                            <span class="text-rose-500 ml-2">
                                                <i class="fas fa-clock mr-1"></i>
                                                {{ \Carbon\Carbon::parse($lastExpiredDate)->diffForHumans() }}
                                            </span>
                                        </p>
                                    @endif
                                    <div class="flex flex-wrap items-center gap-2">
                                        <p class="text-sm text-rose-700/90 font-medium">
                                            <i class="fas fa-info-circle mr-1.5"></i>Sila perbaharui yuran keahlian untuk
                                            terus menerima manfaat perlindungan.
                                        </p>
                                        @if (isset($penaltyFee) && $penaltyFee > 0)
                                            <span
                                                class="text-xs font-bold bg-rose-100 text-rose-700 px-2.5 py-1 rounded-full">
                                                <i class="fas fa-exclamation-circle mr-1"></i>Yuran Penalti: RM
                                                {{ number_format($penaltyFee, 2) }}
                                            </span>
                                        @endif
                                    </div>
                                    <div class="flex flex-wrap items-center gap-x-4 gap-y-1 text-xs text-slate-500">
                                        <span><i class="fas fa-calendar-alt text-slate-400 mr-1.5"></i>Tempoh Keahlian
                                            Tamat</span>
                                        @if ($transactionFor == 'new')
                                            <span class="hidden sm:inline text-slate-300">•</span>
                                            <span><i class="fas fa-user-plus text-slate-400 mr-1.5"></i>Pendaftaran Baru
                                                (Tamat)</span>
                                        @else
                                            <span class="hidden sm:inline text-slate-300">•</span>
                                            <span><i class="fas fa-sync-alt text-slate-400 mr-1.5"></i>Pembaharuan
                                                (Tamat)</span>
                                        @endif
                                    </div>
                                </div>
                                <button onclick="openModal()"
                                    class="bg-rose-600 hover:bg-rose-700 text-white px-5 py-2.5 rounded-xl text-sm font-semibold transition shadow-sm hover:shadow flex items-center justify-center gap-2 w-full sm:w-auto">
                                    <i class="fas fa-sync-alt"></i> Perbaharui Sekarang
                                </button>
                            </div>
                        </div>
                    @elseif($subscriptionStatus == 'cancelled')
                        {{-- CANCELLED SUBSCRIPTION --}}
                        <div class="bg-gradient-to-r from-gray-500/10 to-transparent border-b border-gray-100 p-6">
                            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                                <div class="space-y-1.5">
                                    <div class="flex items-center gap-2.5">
                                        <i class="fas fa-ban text-gray-500 text-xl"></i>
                                        <h3 class="text-lg font-bold text-slate-900">Status Keahlian: <span
                                                class="text-gray-600">DIBATALKAN</span></h3>
                                    </div>
                                    <p class="text-sm text-slate-600">
                                        <i class="fas fa-info-circle text-gray-400 mr-1.5"></i>Keahlian anda telah
                                        dibatalkan. Sila hubungi pihak pengurusan untuk maklumat lanjut.
                                    </p>
                                    @if ($transactionFor == 'new')
                                        <p class="text-xs text-slate-500">
                                            <i class="fas fa-user-plus text-slate-400 mr-1.5"></i>Pendaftaran baru
                                            dibatalkan
                                        </p>
                                    @else
                                        <p class="text-xs text-slate-500">
                                            <i class="fas fa-sync-alt text-slate-400 mr-1.5"></i>Pembaharuan dibatalkan
                                        </p>
                                    @endif
                                </div>
                                <button onclick="openModal()"
                                    class="bg-gray-600 hover:bg-gray-700 text-white px-5 py-2.5 rounded-xl text-sm font-semibold transition shadow-sm hover:shadow flex items-center justify-center gap-2 w-full sm:w-auto">
                                    <i class="fas fa-plus-circle"></i> Daftar Semula
                                </button>
                            </div>
                        </div>
                    @else
                        {{-- NO SUBSCRIPTION / NOT REGISTERED --}}
                        <div class="bg-gradient-to-r from-amber-500/10 to-transparent border-b border-amber-100 p-6">
                            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                                <div class="space-y-1.5">
                                    <div class="flex items-center gap-2.5">
                                        <i class="fas fa-id-card text-amber-500 text-xl"></i>
                                        <h3 class="text-lg font-bold text-slate-900">Status Keahlian: <span
                                                class="text-amber-600">BELUM BERDAFTAR</span></h3>
                                    </div>
                                    <p class="text-sm text-slate-600">
                                        <i class="fas fa-info-circle text-amber-500 mr-1.5"></i>Anda belum berdaftar
                                        sebagai ahli kariah. Sila lakukan pendaftaran keahlian baru di bawah.
                                    </p>
                                    <div class="flex flex-wrap items-center gap-x-4 gap-y-1 text-xs text-slate-500">
                                        <span><i class="fas fa-info-circle text-amber-400 mr-1.5"></i>Yuran pendaftaran
                                            sekali sahaja</span>
                                        <span class="hidden sm:inline text-slate-300">•</span>
                                        <span><i class="fas fa-calendar-alt text-amber-400 mr-1.5"></i>Keahlian
                                            tahunan</span>
                                    </div>
                                </div>
                                <button onclick="openModal()"
                                    class="bg-amber-600 hover:bg-amber-700 text-white px-5 py-2.5 rounded-xl text-sm font-semibold transition shadow-sm hover:shadow flex items-center justify-center gap-2 w-full sm:w-auto">
                                    <i class="fas fa-plus-circle"></i> Daftar Sekarang
                                </button>
                            </div>
                        </div>
                    @endif

                    {{-- PAYMENT DETAILED CONTAINER --}}
                    <div class="p-6 space-y-6">
                        {{-- BLUE INFO BOX --}}
                        <div
                            class="bg-blue-50/60 border border-blue-100 rounded-xl p-4 text-xs md:text-sm text-blue-900/90 space-y-2">
                            <div class="flex items-center gap-2 font-bold text-blue-900">
                                <i class="fas fa-info-circle text-blue-500"></i>
                                <span>Panduan & Peraturan Pembayaran:</span>
                            </div>
                            <ul class="list-disc list-inside space-y-1 pl-1 text-slate-600">
                                <li>Sila buat pemindahan wang ke akaun di bawah terlebih dahulu.</li>
                                <li>Selepas pembayaran berjaya, muat naik resit pembayaran sebelum menekan butang
                                    <b>Hantar</b>.
                                </li>
                                <li>Permohonan hanya akan diproses selepas resit diterima.</li>
                                <li>DanaKhairat adalah platform digital yang mengurus transaksi dan rekod secara automatik.
                                </li>
                                <li>Semua bayaran diproses melalui akaun rasmi pengendali bagi menjamin sistem yang selamat
                                    dan tersusun.</li>
                                <li>Yuran tetap direkodkan dan disalurkan terus kepada tabung kariah pilihan ahli.</li>
                                <li>AJK kariah mempunyai akses penuh kepada laporan dan semakan rekod transaksi.</li>
                            </ul>
                        </div>

                        {{-- QR & ACCOUNT DETAILS GRID --}}
                        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                            <div
                                class="lg:col-span-2 flex flex-col sm:flex-row gap-6 items-center sm:items-start p-5 bg-slate-50 border border-slate-100 rounded-2xl">
                                <div class="relative group shrink-0">
                                    <img src="{{ asset($user->masjid->bank->qr_path) }}" alt="QR Bayaran"
                                        class="w-32 h-32 md:w-36 md:h-36 object-contain rounded-xl bg-white p-2 border border-slate-200 cursor-zoom-in transition group-hover:scale-[1.02]"
                                        onclick="openZoom(this)">
                                    <div
                                        class="absolute inset-0 bg-black/5 opacity-0 group-hover:opacity-100 rounded-xl transition flex items-center justify-center pointer-events-none">
                                        <i
                                            class="fas fa-search-plus text-slate-700 bg-white/90 p-1.5 rounded-full shadow-sm text-xs"></i>
                                    </div>
                                </div>

                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 w-full text-center sm:text-left">
                                    <div class="space-y-0.5">
                                        <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Nombor
                                            Akaun</span>
                                        <p
                                            class="font-mono font-bold text-slate-900 tracking-wide break-all text-sm md:text-base bg-white border border-slate-200/60 px-3 py-1.5 rounded-lg inline-block sm:block">
                                            {{ $user->masjid->bank->no_akaun }}
                                        </p>
                                    </div>
                                    <div class="space-y-0.5">
                                        <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Nama
                                            Akaun</span>
                                        <p class="font-semibold text-slate-800 text-sm md:text-base pt-1">
                                            {{ $user->masjid->bank->nama_akaun }}
                                        </p>
                                    </div>
                                    <div class="space-y-0.5 sm:col-span-2">
                                        <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Nama
                                            Bank</span>
                                        <p class="font-medium text-slate-700 text-sm">
                                            <i
                                                class="fas fa-university text-slate-400 mr-1.5"></i>{{ $user->masjid->bank->nama_bank }}
                                        </p>
                                    </div>
                                </div>
                            </div>

                            {{-- SUMMARY SECTION --}}
                            <div
                                class="bg-slate-900 text-white rounded-2xl p-6 flex flex-col justify-between shadow-sm relative overflow-hidden">
                                <div class="absolute -right-6 -bottom-6 opacity-10 text-9xl">
                                    <i class="fas fa-wallet"></i>
                                </div>

                                @php
                                    // Get the latest subscription to determine transaction type
                                    $latestSubscription = $subscription ?? $user->subscriptions()->latest()->first();
                                    $transactionFor = $latestSubscription ? $latestSubscription->transaction_for : null;
                                    $subscriptionStatus = $latestSubscription ? $latestSubscription->status : null;

                                    // Determine if this is a new registration
                                    $isNewRegistration =
                                        $transactionFor == 'new' &&
                                        in_array($subscriptionStatus, ['pending_payment', 'waiting_verification']);

                                    // Check if user has an active subscription
                                    $hasActiveSubscription =
                                        $subscriptionStatus == 'active' &&
                                        $subscriptionEndDate &&
                                        \Carbon\Carbon::now()->lte(\Carbon\Carbon::parse($subscriptionEndDate));

                                    // Calculate totals
                                    $totalAmount = $harga->bayaran_tahunan;
                                    $items = [];

                                    // Add items based on status
                                    if ($isNewRegistration) {
                                        $items[] = [
                                            'label' => 'Yuran Pendaftaran',
                                            'amount' => $harga->yuran_pendaftaran ?? 0,
                                            'type' => 'registration',
                                        ];
                                        $totalAmount += $harga->yuran_pendaftaran ?? 0;

                                        if (isset($harga->yuran_processing) && $harga->yuran_processing > 0) {
                                            $items[] = [
                                                'label' => 'Yuran Pemprosesan',
                                                'amount' => $harga->yuran_processing,
                                                'type' => 'processing',
                                            ];
                                            $totalAmount += $harga->yuran_processing;
                                        }
                                    }

                                    $items[] = [
                                        'label' => 'Bayaran Tahunan',
                                        'amount' => $harga->bayaran_tahunan,
                                        'type' => 'annual',
                                    ];

                                    if ($subscriptionStatus == 'expired') {
                                        $items[] = [
                                            'label' => 'Yuran Penalti Lewat',
                                            'amount' => $harga->yuran_penalti ?? 0,
                                            'type' => 'penalty',
                                        ];
                                        $totalAmount += $harga->yuran_penalti ?? 0;
                                    }
                                @endphp

                                <div class="space-y-2 relative z-10">
                                    <h4 class="text-xs uppercase tracking-widest text-slate-400 font-bold">Ringkasan Amaun
                                    </h4>

                                    <div class="space-y-1.5">
                                        @foreach ($items as $item)
                                            <div
                                                class="flex justify-between items-center text-sm border-b border-slate-800/50 pb-1.5">
                                                <span class="text-slate-300 text-xs">
                                                    @if ($item['type'] == 'registration')
                                                        <i
                                                            class="fas fa-user-plus text-emerald-400 mr-1.5 text-[10px]"></i>
                                                    @elseif($item['type'] == 'processing')
                                                        <i class="fas fa-cog text-amber-400 mr-1.5 text-[10px]"></i>
                                                    @elseif($item['type'] == 'annual')
                                                        <i
                                                            class="fas fa-calendar-alt text-blue-400 mr-1.5 text-[10px]"></i>
                                                    @elseif($item['type'] == 'penalty')
                                                        <i
                                                            class="fas fa-exclamation-triangle text-rose-400 mr-1.5 text-[10px]"></i>
                                                    @endif
                                                    {{ $item['label'] }}
                                                </span>
                                                <span
                                                    class="font-mono font-medium text-xs 
                                    @if ($item['type'] == 'registration' || $item['type'] == 'processing') text-emerald-400
                                    @elseif($item['type'] == 'penalty') text-rose-400
                                    @else text-white @endif">
                                                    RM {{ number_format($item['amount'], 2) }}
                                                </span>
                                            </div>
                                        @endforeach
                                    </div>

                                    <div class="pt-2 mt-1 border-t border-slate-700/50">
                                        <div class="flex justify-between items-center">
                                            <span
                                                class="text-[10px] uppercase tracking-wider text-slate-400 font-bold">Jumlah
                                                Bayaran</span>
                                            <span class="text-xl md:text-2xl font-mono font-bold text-blue-400">
                                                RM {{ number_format($totalAmount, 2) }}
                                            </span>
                                        </div>
                                    </div>

                                    @if ($isNewRegistration)
                                        <p class="text-[10px] text-emerald-400/60 mt-1 flex items-center gap-1">
                                            <i class="fas fa-info-circle text-[10px]"></i>
                                            Pendaftaran ahli baru (1 kali)
                                        </p>
                                    @endif

                                    @if ($subscriptionStatus == 'expired')
                                        <p class="text-[10px] text-rose-400/60 mt-1 flex items-center gap-1">
                                            <i class="fas fa-info-circle text-[10px]"></i>
                                            Termasuk yuran penalti kelewatan
                                        </p>
                                    @endif

                                    @if ($hasActiveSubscription)
                                        <p class="text-[10px] text-emerald-400/60 mt-1 flex items-center gap-1">
                                            <i class="fas fa-check-circle text-[10px]"></i>
                                            Bayaran tahunan untuk pembaharuan keahlian
                                        </p>
                                    @endif

                                    @if ($subscriptionStatus == 'pending_payment' || $subscriptionStatus == 'waiting_verification')
                                        <p class="text-[10px] text-amber-400/60 mt-1 flex items-center gap-1">
                                            <i class="fas fa-clock text-[10px]"></i>
                                            Menunggu pengesahan pembayaran
                                        </p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- HEADER & SECTION TITLES --}}
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 pt-4">
                    <div>
                        <h1 class="text-xl md:text-2xl font-black tracking-tight text-slate-900">Laporan Transaksi</h1>
                        <p class="text-xs md:text-sm text-slate-500">Rekod penyata terperinci bagi simpanan dan pengeluaran
                            khairat.</p>
                    </div>
                    <button onclick="openModal()"
                        class="w-full sm:w-auto bg-blue-600 hover:bg-blue-700 text-white px-5 py-2.5 rounded-xl text-sm font-semibold transition shadow-sm hover:shadow flex items-center justify-center gap-2">
                        <i class="fas fa-plus"></i> Tambah Transaksi
                    </button>
                </div>

                {{-- DATA FILTER SECTION --}}
                <div class="bg-white rounded-2xl border border-slate-200 p-5 shadow-sm">
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                        <div class="space-y-1.5">
                            <label class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider">Tarikh Mula
                                - Tamat</label>
                            <div class="flex items-center gap-1.5">
                                <input type="date" id="startDate"
                                    class="w-full border border-slate-200 bg-slate-50 rounded-xl px-3 py-2 text-xs focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none transition">
                                <span class="text-slate-400 text-xs">hingga</span>
                                <input type="date" id="endDate"
                                    class="w-full border border-slate-200 bg-slate-50 rounded-xl px-3 py-2 text-xs focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none transition">
                            </div>
                        </div>
                        <div class="space-y-1.5">
                            <label class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider">Jenis
                                Aliran</label>
                            <select id="typeFilter"
                                class="w-full border border-slate-200 bg-slate-50 rounded-xl px-3 py-2 text-xs focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none transition appearance-none cursor-pointer">
                                <option value="all">Semua Transaksi</option>
                                <option value="income">Duit Masuk (Income)</option>
                                <option value="expense">Duit Keluar (Expense)</option>
                            </select>
                        </div>
                        <div class="space-y-1.5">
                            <label class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider">Pintasan
                                Tempoh</label>
                            <div class="grid grid-cols-2 gap-2">
                                <button onclick="setDateRange('today')"
                                    class="px-3 py-2 text-xs border border-slate-200 bg-slate-50 rounded-xl hover:bg-slate-100 hover:text-slate-900 text-slate-600 font-medium transition">Hari
                                    Ini</button>
                                <button onclick="setDateRange('month')"
                                    class="px-3 py-2 text-xs border border-slate-200 bg-slate-50 rounded-xl hover:bg-slate-100 hover:text-slate-900 text-slate-600 font-medium transition">Bulan
                                    Ini</button>
                            </div>
                        </div>
                        <div class="space-y-1.5">
                            <label class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider">Carian Kata
                                Kunci</label>
                            <div class="relative">
                                <input type="text" id="searchInput" placeholder="Cari rujukan, nama..."
                                    class="w-full border border-slate-200 bg-slate-50 rounded-xl pl-9 pr-4 py-2 text-xs focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none transition">
                                <i class="fas fa-search absolute left-3 top-2.5 text-slate-400 text-xs"></i>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- TRANSACTIONS DATA TABLE --}}
                <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
                    <div class="px-6 py-4 border-b border-slate-100 flex justify-between items-center bg-slate-50/50">
                        <h3 class="text-sm font-bold text-slate-900 tracking-tight">Senarai Transaksi Terkini</h3>
                        <span class="text-xs font-semibold px-2.5 py-1 bg-slate-200/70 text-slate-700 rounded-md">
                            <span id="transactionCount">{{ $pembayaran->count() }}</span> Rekod
                        </span>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr
                                    class="bg-slate-50 border-b border-slate-200 text-slate-600 text-[11px] font-bold uppercase tracking-wider">
                                    <th class="py-3 px-6 whitespace-nowrap">Tarikh & Masa</th>
                                    <th class="py-3 px-6 whitespace-nowrap">Keterangan / Bank</th>
                                    <th class="py-3 px-6 whitespace-nowrap">No Rujukan</th>
                                    <th class="py-3 px-6 whitespace-nowrap">Status</th>
                                    <th class="py-3 px-6 whitespace-nowrap">Kategori</th>
                                    <th class="py-3 px-6 whitespace-nowrap text-right">Amaun (RM)</th>
                                </tr>
                            </thead>
                            <tbody id="transactionsBody" class="divide-y divide-slate-100 text-xs md:text-sm">
                                @forelse($pembayaran as $payment)
                                    @php
                                        $isIncome =
                                            $payment->flow_type == 'income' ||
                                            $payment->flow_type == 'income';
                                        $amountClass = $isIncome ? 'text-emerald-600' : 'text-rose-600';
                                        $amountPrefix = $isIncome ? '+' : '-';
                                        $typeText = $isIncome ? 'Masuk' : 'Keluar';
                                        $typeColor = $isIncome
                                            ? 'text-emerald-700 bg-emerald-50 border-emerald-100'
                                            : 'text-rose-700 bg-rose-50 border-rose-100';
                                        $datetime = $payment->paid_at ?? $payment->created_at;
                                        $date = $datetime ? \Carbon\Carbon::parse($datetime)->format('d/m/Y') : '-';
                                        $time = $datetime ? \Carbon\Carbon::parse($datetime)->format('H:i') : '-';
                                        $description = $payment->type ?? ($payment->description ?? 'Tiada Penerangan');
                                        $reference =
                                            $payment->reference_no ??
                                            'TRX-' . str_pad($payment->id, 5, '0', STR_PAD_LEFT);
                                        $bankName = $payment->payment_method ?? 'Tiada';

                                        $status = strtoupper($payment->status ?? 'PENDING');
                                        $statusColors = [
                                            'PAID' => 'bg-green-100 text-green-700 border-green-200',
                                            'SUCCESS' => 'bg-emerald-100 text-emerald-700 border-emerald-200',
                                            'PENDING' => 'bg-yellow-100 text-yellow-700 border-yellow-200',
                                            'FAILED' => 'bg-red-100 text-red-700 border-red-200',
                                            'CANCELLED' => 'bg-gray-100 text-gray-700 border-gray-200',
                                            'WAITING_VERIFICATION' => 'bg-blue-100 text-blue-700 border-blue-200',
                                            'PENDING_PAYMENT' => 'bg-amber-100 text-amber-700 border-amber-200',
                                            'EXPIRED' => 'bg-rose-100 text-rose-700 border-rose-200',
                                            'ACTIVE' => 'bg-emerald-100 text-emerald-700 border-emerald-200',
                                        ];
                                        $statusColor =
                                            $statusColors[$status] ?? 'bg-gray-100 text-gray-700 border-gray-200';

                                        $statusIcons = [
                                            'PAID' => 'fa-check-circle',
                                            'SUCCESS' => 'fa-check-double',
                                            'PENDING' => 'fa-clock',
                                            'FAILED' => 'fa-times-circle',
                                            'CANCELLED' => 'fa-ban',
                                            'WAITING_VERIFICATION' => 'fa-spinner fa-pulse',
                                            'PENDING_PAYMENT' => 'fa-hourglass-half',
                                            'EXPIRED' => 'fa-exclamation-triangle',
                                            'ACTIVE' => 'fa-check-circle',
                                        ];
                                        $statusIcon = $statusIcons[$status] ?? 'fa-info-circle';

                                        // Data attributes for JavaScript filtering
                                        $dataType = $isIncome ? 'income' : 'expense';
                                        $dataDate = $datetime ? \Carbon\Carbon::parse($datetime)->format('Y-m-d') : '';
                                        $dataSearch = strtolower($reference . ' ' . $description . ' ' . $bankName);

                                        // Get subscription info for this payment if exists
                                        $subscription = $payment->subscription ?? null;
                                        $transactionFor = $subscription ? $subscription->transaction_for : null;
                                        $subscriptionStatus = $subscription ? $subscription->status : null;

                                        // Subscription type label
                                        $subscriptionTypeLabel = '';
                                        if ($transactionFor == 'new') {
                                            $subscriptionTypeLabel = 'Pendaftaran Baru';
                                        } elseif ($transactionFor == 'renew') {
                                            $subscriptionTypeLabel = 'Pembaharuan';
                                        }

                                        // Subscription status label
                                        $subscriptionStatusLabel = '';
                                        $subscriptionStatusColor = '';
                                        if ($subscriptionStatus == 'active') {
                                            $subscriptionStatusLabel = 'Aktif';
                                            $subscriptionStatusColor = 'text-emerald-600';
                                        } elseif ($subscriptionStatus == 'pending_payment') {
                                            $subscriptionStatusLabel = 'Menunggu Bayaran';
                                            $subscriptionStatusColor = 'text-amber-600';
                                        } elseif ($subscriptionStatus == 'waiting_verification') {
                                            $subscriptionStatusLabel = 'Menunggu Verifikasi';
                                            $subscriptionStatusColor = 'text-blue-600';
                                        } elseif ($subscriptionStatus == 'expired') {
                                            $subscriptionStatusLabel = 'Tamat Tempoh';
                                            $subscriptionStatusColor = 'text-rose-600';
                                        } elseif ($subscriptionStatus == 'cancelled') {
                                            $subscriptionStatusLabel = 'Dibatalkan';
                                            $subscriptionStatusColor = 'text-gray-600';
                                        }
                                    @endphp
                                    <tr class="hover:bg-slate-50/80 transition transaction-row"
                                        data-type="{{ $dataType }}" data-date="{{ $dataDate }}"
                                        data-search="{{ $dataSearch }}">

                                        <td class="py-3.5 px-6 whitespace-nowrap">
                                            <div class="font-semibold text-slate-900">{{ $date }}</div>
                                            <div class="text-[10px] text-slate-400 font-medium">{{ $time }}</div>
                                        </td>

                                        <td class="py-3.5 px-6 whitespace-nowrap">
                                            <div class="font-semibold text-slate-900">{{ $description }}</div>
                                            <div class="flex flex-col gap-0.5 mt-0.5">
                                                <div class="text-[10px] text-slate-400 font-medium">{{ $bankName }}
                                                </div>
                                                @if ($subscriptionTypeLabel)
                                                    <span
                                                        class="text-[9px] font-semibold text-blue-600 bg-blue-50 px-1.5 py-0.5 rounded inline-block w-fit">
                                                        {{ $subscriptionTypeLabel }}
                                                    </span>
                                                @endif
                                            </div>
                                        </td>

                                        <td class="py-3.5 px-6 whitespace-nowrap">
                                            <div class="flex flex-col gap-0.5">
                                                <span
                                                    class="font-mono text-[11px] font-bold text-slate-600 bg-slate-100 px-2 py-0.5 rounded">
                                                    {{ $reference }}
                                                </span>
                                                @if ($subscriptionStatusLabel)
                                                    <span class="text-[9px] font-medium {{ $subscriptionStatusColor }}">
                                                        <i class="fas fa-id-card mr-0.5"></i>
                                                        {{ $subscriptionStatusLabel }}
                                                    </span>
                                                @endif
                                            </div>
                                        </td>

                                        <td class="py-3.5 px-6 whitespace-nowrap">
                                            <span
                                                class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[10px] font-bold uppercase border {{ $statusColor }}">
                                                <i class="fas {{ $statusIcon }} text-[9px]"></i>
                                                {{ $status }}
                                            </span>
                                        </td>

                                        <td class="py-3.5 px-6 whitespace-nowrap">
                                            <span
                                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase border {{ $typeColor }}">
                                                {{ $typeText }}
                                            </span>
                                        </td>

                                        <td class="py-3.5 px-6 whitespace-nowrap text-right">
                                            <span class="{{ $amountClass }} font-mono font-bold">
                                                {{ $amountPrefix }}{{ number_format($payment->amount, 2) }}
                                            </span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr id="noResults">
                                        <td colspan="6" class="py-16 text-center">
                                            <div
                                                class="bg-slate-50 w-12 h-12 rounded-2xl flex items-center justify-center mx-auto mb-3 border border-slate-100">
                                                <i class="fas fa-search text-slate-400"></i>
                                            </div>
                                            <h5 class="text-sm font-bold text-slate-800">Tiada Rekod Ditemui</h5>
                                            <p class="text-slate-400 text-xs max-w-xs mx-auto mt-0.5">Belum ada transaksi
                                                yang direkodkan.</p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        {{-- ============================================ --}}
        {{-- MODAL FOR RENEW / NEW REGISTRATION (No ID) --}}
        {{-- ============================================ --}}
        <div id="renewModal"
            class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm hidden flex items-center justify-center z-50 p-4 transition-all">
            <div class="bg-white rounded-2xl shadow-xl w-full max-w-lg overflow-hidden flex flex-col max-h-[90vh]">
                <div class="flex justify-between items-center px-6 py-4 border-b border-slate-100 bg-slate-50/50">
                    <h3 class="text-base font-bold text-slate-900">
                        <span id="renewModalTitle">Tambah Transaksi Baru</span>
                    </h3>
                    <button onclick="closeRenewModal()"
                        class="text-slate-400 hover:text-slate-600 hover:bg-slate-100 p-2 rounded-lg transition">
                        <i class="fas fa-times text-lg"></i>
                    </button>
                </div>

                <form action="{{ route('user.transactions.store', $user->id) }}" method="POST"
                    enctype="multipart/form-data" class="p-6 space-y-4 overflow-y-auto">
                    @csrf
                    <input type="hidden" name="flow_type" value="income">
                    <input type="hidden" name="type" value="Renew Membership">
                    <input type="hidden" name="is_renewal" value="1">

                    <div class="space-y-4">
                        <div>
                            <label class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-1.5">Amaun
                                Bayaran (RM)</label>
                            <div class="relative">
                                <span class="absolute left-4 top-2.5 text-sm font-bold text-slate-400">RM</span>
                                <input type="number" step="0.01" name="amount" required placeholder="0.00"
                                    class="w-full border border-slate-200 rounded-xl pl-12 pr-4 py-2.5 text-sm font-mono focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none transition bg-slate-50/50">
                            </div>
                        </div>

                        <div>
                            <label
                                class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-1.5">Kaedah
                                Pembayaran</label>
                            <select name="payment_method" required
                                class="w-full border border-slate-200 bg-slate-50/50 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none transition appearance-none cursor-pointer">
                                <option value="Manual Transfer">Manual Online Transfer</option>
                            </select>
                        </div>

                        <div>
                            <label
                                class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-1.5">Tarikh &
                                Masa Bayaran</label>
                            <input type="datetime-local" name="paid_at" required
                                class="w-full border border-slate-200 bg-slate-50/50 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none transition">
                        </div>

                        <div>
                            <label class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-1.5">Muat
                                Naik Resit Bukti Bayaran</label>
                            <div
                                class="border border-dashed border-slate-300 rounded-xl p-3 bg-slate-50/40 hover:bg-slate-50 transition">
                                <input type="file" name="receipt" accept=".pdf,.png,.jpg,.jpeg" required
                                    class="w-full text-xs text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 cursor-pointer">
                                <p class="text-[10px] text-slate-400 mt-2 pl-1"><i class="fas fa-info-circle"></i> Format
                                    dibenarkan: PDF, PNG, JPG, JPEG.</p>
                            </div>
                        </div>

                        <div>
                            <label
                                class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-1.5">Catatan
                                / Rujukan Tambahan</label>
                            <textarea name="remarks" rows="2" placeholder="Masukkan rujukan atau nota ringkas jika ada..."
                                class="w-full border border-slate-200 bg-slate-50/50 rounded-xl px-4 py-2 text-sm focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none transition resize-none"></textarea>
                        </div>
                    </div>

                    <div class="pt-4 border-t border-slate-100 flex flex-col-reverse sm:flex-row justify-end gap-2.5">
                        <button type="button" onclick="closeRenewModal()"
                            class="w-full sm:w-auto px-5 py-2.5 text-slate-600 bg-slate-100 rounded-xl hover:bg-slate-200 font-semibold text-sm transition">Batal</button>
                        <button type="submit"
                            class="w-full sm:w-auto px-5 py-2.5 bg-blue-600 text-white rounded-xl hover:bg-blue-700 font-semibold text-sm shadow-md shadow-blue-500/10 hover:shadow transition">
                            <i class="fas fa-paper-plane mr-1.5"></i> Hantar Transaksi
                        </button>
                    </div>
                </form>
            </div>
        </div>

        {{-- ============================================ --}}
        {{-- MODAL FOR UPDATE PENDING PAYMENT (With ID) --}}
        {{-- ============================================ --}}
        <div id="updatePaymentModal"
            class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm hidden flex items-center justify-center z-50 p-4 transition-all">
            <div class="bg-white rounded-2xl shadow-xl w-full max-w-lg overflow-hidden flex flex-col max-h-[90vh]">
                <div class="flex justify-between items-center px-6 py-4 border-b border-slate-100 bg-amber-50/50">
                    <h3 class="text-base font-bold text-slate-900">
                        <i class="fas fa-credit-card text-amber-600 mr-2"></i>
                        <span id="updateModalTitle">Selesaikan Pembayaran</span>
                    </h3>
                    <button onclick="closeUpdatePaymentModal()"
                        class="text-slate-400 hover:text-slate-600 hover:bg-slate-100 p-2 rounded-lg transition">
                        <i class="fas fa-times text-lg"></i>
                    </button>
                </div>

                {{-- FIX: Use JavaScript to set action dynamically --}}
                <form id="updatePaymentForm" action="" method="POST" enctype="multipart/form-data"
                    class="p-6 space-y-4 overflow-y-auto">
                    @csrf
                    <input type="hidden" name="flow_type" value="income">
                    <input type="hidden" name="is_update" value="1">

                    <div class="bg-amber-50 border border-amber-200 rounded-xl p-3 mb-2">
                        <p class="text-xs text-amber-800">
                            <i class="fas fa-info-circle mr-1"></i>
                            Anda sedang menyelesaikan pembayaran untuk permohonan <span class="font-bold">#<span
                                    id="subscriptionIdDisplay"></span></span>
                        </p>
                    </div>

                    <div class="space-y-4">
                        <div>
                            <label class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-1.5">Amaun
                                Bayaran (RM)</label>
                            <div class="relative">
                                <span class="absolute left-4 top-2.5 text-sm font-bold text-slate-400">RM</span>
                                <input type="number" step="0.01" name="amount" id="updateAmount" required
                                    placeholder="0.00"
                                    class="w-full border border-slate-200 rounded-xl pl-12 pr-4 py-2.5 text-sm font-mono focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500 outline-none transition bg-slate-50/50">
                            </div>
                        </div>

                        <div>
                            <label
                                class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-1.5">Kaedah
                                Pembayaran</label>
                            <select name="payment_method" required
                                class="w-full border border-slate-200 bg-slate-50/50 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500 outline-none transition appearance-none cursor-pointer">
                                <option value="Manual Transfer">Manual Online Transfer</option>
                            </select>
                        </div>

                        <div>
                            <label
                                class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-1.5">Tarikh &
                                Masa Bayaran</label>
                            <input type="datetime-local" name="paid_at" id="updatePaidAt" required
                                class="w-full border border-slate-200 bg-slate-50/50 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500 outline-none transition">
                        </div>

                        <div>
                            <label class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-1.5">Muat
                                Naik Resit Bukti Bayaran</label>
                            <div
                                class="border border-dashed border-slate-300 rounded-xl p-3 bg-slate-50/40 hover:bg-slate-50 transition">
                                <input type="file" name="receipt" accept=".pdf,.png,.jpg,.jpeg" required
                                    class="w-full text-xs text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-amber-50 file:text-amber-700 hover:file:bg-amber-100 cursor-pointer">
                                <p class="text-[10px] text-slate-400 mt-2 pl-1"><i class="fas fa-info-circle"></i> Format
                                    dibenarkan: PDF, PNG, JPG, JPEG.</p>
                            </div>
                        </div>

                        <div>
                            <label
                                class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-1.5">Catatan
                                / Rujukan Tambahan</label>
                            <textarea name="remarks" id="updateRemarks" rows="2"
                                placeholder="Masukkan rujukan atau nota ringkas jika ada..."
                                class="w-full border border-slate-200 bg-slate-50/50 rounded-xl px-4 py-2 text-sm focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500 outline-none transition resize-none"></textarea>
                        </div>
                    </div>

                    <div class="pt-4 border-t border-slate-100 flex flex-col-reverse sm:flex-row justify-end gap-2.5">
                        <button type="button" onclick="closeUpdatePaymentModal()"
                            class="w-full sm:w-auto px-5 py-2.5 text-slate-600 bg-slate-100 rounded-xl hover:bg-slate-200 font-semibold text-sm transition">Batal</button>
                        <button type="submit"
                            class="w-full sm:w-auto px-5 py-2.5 bg-amber-600 text-white rounded-xl hover:bg-amber-700 font-semibold text-sm shadow-md shadow-amber-500/10 hover:shadow transition">
                            <i class="fas fa-check-circle mr-1.5"></i> Sahkan Pembayaran
                        </button>
                    </div>
                </form>
            </div>
        </div>

        {{-- IMAGE LIGHTBOX ZOOM POPUP --}}
        <div id="zoomModal"
            class="fixed inset-0 bg-slate-950/90 hidden z-[100] flex items-center justify-center p-4 transition-all"
            onclick="closeZoom()">
            <button
                class="absolute top-6 right-6 text-white text-3xl hover:text-slate-300 transition bg-white/10 w-12 h-12 rounded-xl flex items-center justify-center">&times;</button>
            <img id="zoomImage" class="max-w-full max-h-[85vh] rounded-xl shadow-2xl object-contain">
        </div>

        <script>
            // ========================================
            // RENEW / NEW REGISTRATION MODAL (No ID)
            // ========================================
            function openRenewModal(title = 'Tambah Transaksi Baru', status = '') {
                const modal = document.getElementById('renewModal');
                const titleEl = document.getElementById('renewModalTitle');

                if (status === 'expired') {
                    titleEl.textContent = 'Perbaharui Keahlian (Termasuk Penalti)';
                } else if (status === 'none') {
                    titleEl.textContent = 'Daftar Keahlian Baru';
                } else if (status === 'active') {
                    titleEl.textContent = 'Perbaharui Keahlian';
                } else {
                    titleEl.textContent = title;
                }

                modal.classList.remove('hidden');
            }

            function closeRenewModal() {
                document.getElementById('renewModal').classList.add('hidden');
            }

            // ========================================
            // UPDATE PENDING PAYMENT MODAL (With ID)
            // ========================================
            function openUpdatePaymentModal(subscriptionId, amount = '', paidAt = '') {
                const modal = document.getElementById('updatePaymentModal');
                const form = document.getElementById('updatePaymentForm');
                const idDisplay = document.getElementById('subscriptionIdDisplay');
                const amountInput = document.getElementById('updateAmount');
                const paidAtInput = document.getElementById('updatePaidAt');
                const remarksInput = document.getElementById('updateRemarks');

                // Set subscription ID
                idDisplay.textContent = subscriptionId;

                // Build the URL manually
                const baseUrl = window.location.origin;
                const updateUrl = baseUrl + '/user/transactions/update/' + subscriptionId;
                form.action = updateUrl;

                // Remove _method field if exists (we're using POST)
                let methodField = form.querySelector('input[name="_method"]');
                if (methodField) {
                    methodField.remove();
                }

                // Set amount if provided
                if (amount) {
                    amountInput.value = amount;
                }

                // Set paid_at if provided
                if (paidAt) {
                    paidAtInput.value = paidAt;
                }

                // Clear remarks
                remarksInput.value = '';

                modal.classList.remove('hidden');
            }

            function closeUpdatePaymentModal() {
                document.getElementById('updatePaymentModal').classList.add('hidden');
            }

            // ========================================
            // SMART OPEN MODAL - Choose based on status
            // ========================================
            function openModal() {
                const subscriptionStatus = "{{ $subscriptionStatus }}";
                const transactionFor = "{{ $transactionFor }}";
                const subscriptionId = "{{ $subscription->id ?? '' }}";
                const totalAmount = "{{ $totalAmount ?? 0 }}";

                // Get current date/time in local format
                const now = new Date();
                const year = now.getFullYear();
                const month = String(now.getMonth() + 1).padStart(2, '0');
                const day = String(now.getDate()).padStart(2, '0');
                const hours = String(now.getHours()).padStart(2, '0');
                const minutes = String(now.getMinutes()).padStart(2, '0');
                const currentDate = `${year}-${month}-${day}T${hours}:${minutes}`;

                // If pending_payment or waiting_verification - open update modal (with ID)
                if (subscriptionStatus === 'pending_payment' || subscriptionStatus === 'waiting_verification') {
                    if (subscriptionId) {
                        openUpdatePaymentModal(
                            subscriptionId,
                            totalAmount,
                            currentDate
                        );
                        return;
                    }
                }

                // Otherwise - open renew/new registration modal
                let title = 'Tambah Transaksi Baru';
                let status = subscriptionStatus;

                if (subscriptionStatus === 'expired') {
                    title = 'Perbaharui Keahlian (Termasuk Penalti)';
                } else if (subscriptionStatus === 'none') {
                    title = 'Daftar Keahlian Baru';
                } else if (subscriptionStatus === 'active') {
                    title = 'Perbaharui Keahlian';
                } else if (subscriptionStatus === 'cancelled') {
                    title = 'Daftar Semula Keahlian';
                }

                openRenewModal(title, status);
            }

            // ========================================
            // DIRECT OPEN UPDATE MODAL (For buttons)
            // ========================================
            function openUpdateModalDirect(subscriptionId) {
                const totalAmount = "{{ $totalAmount ?? 0 }}";
                const now = new Date();
                const year = now.getFullYear();
                const month = String(now.getMonth() + 1).padStart(2, '0');
                const day = String(now.getDate()).padStart(2, '0');
                const hours = String(now.getHours()).padStart(2, '0');
                const minutes = String(now.getMinutes()).padStart(2, '0');
                const currentDate = `${year}-${month}-${day}T${hours}:${minutes}`;

                openUpdatePaymentModal(subscriptionId, totalAmount, currentDate);
            }

            // ========================================
            // CLOSE ALL MODALS
            // ========================================
            function closeAllModals() {
                closeRenewModal();
                closeUpdatePaymentModal();
            }

            // ========================================
            // KEYBOARD SHORTCUTS
            // ========================================
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape') {
                    closeAllModals();
                    closeZoom();
                }
            });

            // ========================================
            // CLOSE ON BACKDROP CLICK
            // ========================================
            document.getElementById('renewModal').addEventListener('click', function(e) {
                if (e.target === this) {
                    closeRenewModal();
                }
            });

            document.getElementById('updatePaymentModal').addEventListener('click', function(e) {
                if (e.target === this) {
                    closeUpdatePaymentModal();
                }
            });

            // ========================================
            // ZOOM FUNCTION
            // ========================================
            function openZoom(img) {
                const modal = document.getElementById('zoomModal');
                const zoomImg = document.getElementById('zoomImage');
                zoomImg.src = img.src;
                modal.classList.remove('hidden');
            }

            function closeZoom() {
                document.getElementById('zoomModal').classList.add('hidden');
            }

            // ========================================
            // FILTER TRANSACTIONS
            // ========================================
            function filterTransactions() {
                const startDate = document.getElementById('startDate').value;
                const endDate = document.getElementById('endDate').value;
                const typeFilter = document.getElementById('typeFilter').value;
                const searchTerm = document.getElementById('searchInput').value.toLowerCase().trim();

                const rows = document.querySelectorAll('.transaction-row');
                let visibleCount = 0;

                rows.forEach(row => {
                    const rowDate = row.dataset.date || '';
                    const rowType = row.dataset.type || '';
                    const rowSearch = row.dataset.search || '';

                    let show = true;

                    if (startDate && rowDate < startDate) show = false;
                    if (endDate && rowDate > endDate) show = false;
                    if (typeFilter !== 'all' && rowType !== typeFilter) show = false;
                    if (searchTerm && !rowSearch.includes(searchTerm)) show = false;

                    if (show) {
                        row.style.display = '';
                        visibleCount++;
                    } else {
                        row.style.display = 'none';
                    }
                });

                document.getElementById('transactionCount').textContent = visibleCount;

                const noResults = document.getElementById('noResults');
                if (noResults) {
                    if (visibleCount === 0) {
                        noResults.style.display = '';
                    } else {
                        noResults.style.display = 'none';
                    }
                }
            }

            function setDateRange(range) {
                const today = new Date();
                let startDate, endDate = today.toISOString().split('T')[0];

                if (range === 'today') {
                    startDate = endDate;
                } else if (range === 'month') {
                    startDate = new Date(today.getFullYear(), today.getMonth(), 1).toISOString().split('T')[0];
                }

                document.getElementById('startDate').value = startDate;
                document.getElementById('endDate').value = endDate;
                filterTransactions();
            }

            // ========================================
            // DOM READY
            // ========================================
            document.addEventListener('DOMContentLoaded', function() {
                setDateRange('month');

                document.getElementById('startDate').addEventListener('change', filterTransactions);
                document.getElementById('endDate').addEventListener('change', filterTransactions);
                document.getElementById('typeFilter').addEventListener('change', filterTransactions);
                document.getElementById('searchInput').addEventListener('input', filterTransactions);
            });
        </script>
    </body>
@endsection
