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
                                            lama lagi.
                                        </p>
                                    @endif
                                </div>
                                <span
                                    class="bg-emerald-100 text-emerald-800 text-xs font-bold uppercase tracking-wider px-3.5 py-1.5 rounded-full shadow-sm w-fit">
                                    <i class="fas fa-id-card mr-1.5"></i>Aktif
                                </span>
                            </div>
                        </div>
                    @elseif($subscriptionStatus == 'expired')
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
                                        </p>
                                    @endif
                                    <p class="text-sm text-rose-700/90 font-medium">
                                        <i class="fas fa-info-circle mr-1.5"></i>Sila perbaharui yuran keahlian untuk terus
                                        menerima manfaat perlindungan.
                                    </p>
                                </div>
                                <button onclick="openModal()"
                                    class="bg-rose-600 hover:bg-rose-700 text-white px-5 py-2.5 rounded-xl text-sm font-semibold transition shadow-sm hover:shadow flex items-center justify-center gap-2 w-full sm:w-auto">
                                    <i class="fas fa-sync-alt"></i> Perbaharui Sekarang
                                </button>
                            </div>
                        </div>
                    @else
                        <div class="bg-gradient-to-r from-amber-500/10 to-transparent border-b border-amber-100 p-6">
                            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                                <div class="space-y-1.5">
                                    <div class="flex items-center gap-2.5">
                                        <i class="fas fa-id-card text-amber-500 text-xl"></i>
                                        <h3 class="text-lg font-bold text-slate-900">Status Keahlian: <span
                                                class="text-amber-600">BELUM BERDAFTAR</span></h3>
                                    </div>
                                    <p class="text-sm text-slate-600">
                                        <i class="fas fa-info-circle text-amber-500 mr-1.5"></i>Anda belum berdaftar. Sila
                                        lakukan sumbangan/pendaftaran keahlian baru di bawah.
                                    </p>
                                </div>
                                <button onclick="openModal()"
                                    class="bg-amber-600 hover:bg-amber-700 text-white px-5 py-2.5 rounded-xl text-sm font-semibold transition shadow-sm hover:shadow flex items-center justify-center gap-2 w-full sm:w-auto">
                                    <i class="fas fa-plus-circle"></i> Daftar / Perbaharui
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
                                            {{ $user->masjid->bank->nama_akaun }}</p>
                                    </div>
                                    <div class="space-y-0.5 sm:col-span-2">
                                        <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Nama
                                            Bank</span>
                                        <p class="font-medium text-slate-700 text-sm"><i
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
                                <div class="space-y-3 relative z-10">
                                    <h4 class="text-xs uppercase tracking-widest text-slate-400 font-bold">Ringkasan Amaun
                                    </h4>
                                    <div class="flex justify-between items-center text-sm border-b border-slate-800 pb-3">
                                        <span class="text-slate-300">Bayaran Tahunan</span>
                                        <span class="font-mono font-medium">RM
                                            {{ number_format($harga->bayaran_tahunan, 2) }}</span>
                                    </div>
                                </div>
                                <div class="pt-4 sm:pt-0 relative z-10">
                                    <span class="text-[10px] uppercase tracking-wider text-slate-400 block mb-0.5">Jumlah
                                        Bayaran</span>
                                    <span class="text-2xl md:text-3xl font-mono font-bold text-blue-400">RM
                                        {{ number_format($harga->bayaran_tahunan, 2) }}</span>
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

                {{-- TRANSACTIONS DATA TABLE - STANDARD BLADE TABLE --}}
                <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
                    <div class="px-6 py-4 border-b border-slate-100 flex justify-between items-center bg-slate-50/50">
                        <h3 class="text-sm font-bold text-slate-900 tracking-tight">Senarai Transaksi Terkini</h3>
                        <span class="text-xs font-semibold px-2.5 py-1 bg-slate-200/70 text-slate-700 rounded-md">
                            {{ $pembayaran->count() }} Rekod
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
                            <tbody class="divide-y divide-slate-100 text-xs md:text-sm">
                                @forelse($pembayaran as $payment)
                                    @php
                                        $isIncome =
                                            $payment->transaction_type == 'transaction_in' ||
                                            $payment->transaction_type == 'income';
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
                                        $userName = $user->name ?? ($user->nama ?? 'Ahli Khairat');
                                        $bankName = $payment->payment_method ?? 'Tiada';
                                        $paystatus = $payment->status ?? 'Tiada';
                                    @endphp
                                    <tr class="hover:bg-slate-50/80 transition">
                                        <td class="py-3.5 px-6 whitespace-nowrap">
                                            <div class="font-semibold text-slate-900">{{ $date }}</div>
                                            <div class="text-[10px] text-slate-400 font-medium">{{ $time }}</div>
                                        </td>
                                        <td class="py-3.5 px-6 whitespace-nowrap">
                                            <div class="font-semibold text-slate-900">{{ $description }}</div>
                                            <div class="text-[10px] text-slate-400 font-medium">{{ $bankName }}</div>
                                        </td>
                                        <td class="py-3.5 px-6 whitespace-nowrap">
                                            <span
                                                class="font-mono text-[11px] font-bold text-slate-600 bg-slate-100 px-2 py-0.5 rounded">{{ $reference }}</span>
                                        </td>
                                        <td class="py-3.5 px-6 whitespace-nowrap">
                                            @php
                                                $status = strtoupper($payment->status ?? 'PENDING');
                                                $statusColors = [
                                                    'PAID' => 'bg-green-100 text-green-700 border-green-200',
                                                    'SUCCESS' => 'bg-emerald-100 text-emerald-700 border-emerald-200',
                                                    'PENDING' => 'bg-yellow-100 text-yellow-700 border-yellow-200',
                                                    'FAILED' => 'bg-red-100 text-red-700 border-red-200',
                                                    'CANCELLED' => 'bg-gray-100 text-gray-700 border-gray-200',
                                                ];
                                                $statusColor =
                                                    $statusColors[$status] ??
                                                    'bg-gray-100 text-gray-700 border-gray-200';

                                                $statusIcons = [
                                                    'PAID' => 'fa-check-circle',
                                                    'SUCCESS' => 'fa-check-double',
                                                    'PENDING' => 'fa-clock',
                                                    'FAILED' => 'fa-times-circle',
                                                    'CANCELLED' => 'fa-ban',
                                                ];
                                                $statusIcon = $statusIcons[$status] ?? 'fa-info-circle';
                                            @endphp
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
                                            <span
                                                class="{{ $amountClass }} font-mono font-bold">{{ $amountPrefix }}{{ number_format($payment->amount, 2) }}</span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
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

        {{-- MODAL POPUP FOR SUBMIT TRANSACTION --}}
        <div id="transactionModal"
            class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm hidden flex items-center justify-center z-50 p-4 transition-all">
            <div class="bg-white rounded-2xl shadow-xl w-full max-w-lg overflow-hidden flex flex-col max-h-[90vh]">
                <div class="flex justify-between items-center px-6 py-4 border-b border-slate-100 bg-slate-50/50">
                    <h3 class="text-base font-bold text-slate-900">Tambah Transaksi Baru</h3>
                    <button onclick="closeModal()"
                        class="text-slate-400 hover:text-slate-600 hover:bg-slate-100 p-2 rounded-lg transition">
                        <i class="fas fa-times text-lg"></i>
                    </button>
                </div>

                <form action="{{ route('user.transactions.store', $user->id) }}" method="POST"
                    enctype="multipart/form-data" class="p-6 space-y-4 overflow-y-auto">
                    @csrf
                    <input type="hidden" name="transaction_type" value="transaction_in">
                    <input type="hidden" name="type" value="Renew Membership">

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
                                <input type="file" name="resit" accept=".pdf,.png,.jpg,.jpeg" required
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
                        <button type="button" onclick="closeModal()"
                            class="w-full sm:w-auto px-5 py-2.5 text-slate-600 bg-slate-100 rounded-xl hover:bg-slate-200 font-semibold text-sm transition">Batal</button>
                        <button type="submit"
                            class="w-full sm:w-auto px-5 py-2.5 bg-blue-600 text-white rounded-xl hover:bg-blue-700 font-semibold text-sm shadow-md shadow-blue-500/10 hover:shadow transition">Hantar
                            Transaksi</button>
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

        {{-- JAVASCRIPT (Only for filters, modal, and zoom - NO table rendering JS) --}}
        <script>
            // Store PHP data in JavaScript for filtering only
            const rawDatabaseData = @json($pembayaran);
            const userData = @json($user);
            const userName = userData.name || userData.email || 'Ahli Khairat';

            // Build transactions array for filtering
            const transactionsData = rawDatabaseData.map(item => {
                const datetimeStr = item.paid_at || item.created_at;
                let datePart = '',
                    timePart = '';
                if (datetimeStr) {
                    if (datetimeStr.includes(' ')) {
                        const parts = datetimeStr.split(' ');
                        datePart = parts[0];
                        timePart = parts[1] || '00:00:00';
                    } else {
                        datePart = datetimeStr;
                        timePart = '00:00:00';
                    }
                }

                let transactionType = item.transaction_type;
                if (transactionType === 'transaction_in' || transactionType === 'income' || transactionType ===
                    'Renew Membership') {
                    transactionType = 'income';
                } else {
                    transactionType = 'expense';
                }

                return {
                    id: item.id,
                    date: datePart,
                    time: timePart,
                    description: item.type || item.description || 'Tiada Penerangan',
                    reference: item.reference_no || 'TRX-' + String(item.id).padStart(5, '0'),
                    name: userName,
                    type: transactionType,
                    amount: parseFloat(item.amount),
                    bank: item.payment_method || 'Tiada',
                    status: item.status || 'completed'
                };
            });

            function openModal() {
                document.getElementById('transactionModal').classList.remove('hidden');
            }

            function closeModal() {
                document.getElementById('transactionModal').classList.add('hidden');
            }

            function openZoom(img) {
                const modal = document.getElementById('zoomModal');
                const zoomImg = document.getElementById('zoomImage');
                zoomImg.src = img.src;
                modal.classList.remove('hidden');
            }

            function closeZoom() {
                document.getElementById('zoomModal').classList.add('hidden');
            }

            function formatDate(dateString) {
                if (!dateString) return '-';
                const date = new Date(dateString);
                return date.toLocaleDateString('ms-MY', {
                    day: '2-digit',
                    month: '2-digit',
                    year: 'numeric'
                });
            }

            function formatTime(timeString) {
                if (!timeString) return '';
                return timeString.substring(0, 5);
            }

            function renderTransactions(transactions) {
                const tableBody = document.getElementById('transactionsTable');
                const loadingState = document.getElementById('loadingState');
                const noResults = document.getElementById('noResults');
                const transactionCount = document.getElementById('transactionCount');

                if (loadingState) loadingState.classList.add('hidden');

                if (transactions.length === 0) {
                    if (noResults) noResults.classList.remove('hidden');
                    if (tableBody) tableBody.innerHTML = '';
                    if (transactionCount) transactionCount.textContent = '0';
                    return;
                }

                if (noResults) noResults.classList.add('hidden');
                if (transactionCount) transactionCount.textContent = transactions.length;

                if (tableBody) {
                    tableBody.innerHTML = transactions.map(transaction => {
                        const isIncome = transaction.type === 'income';
                        const amountClass = isIncome ? 'text-emerald-600' : 'text-rose-600';
                        const amountPrefix = isIncome ? '+' : '-';
                        const typeText = isIncome ? 'Masuk' : 'Keluar';
                        const typeColor = isIncome ? 'text-emerald-700 bg-emerald-50 border-emerald-100' :
                            'text-rose-700 bg-rose-50 border-rose-100';

                        return `
                        <tr class="hover:bg-slate-50/80 transition">
                            <td class="py-3.5 px-6 whitespace-nowrap">
                                <div class="font-semibold text-slate-900">${formatDate(transaction.date)}</div>
                                <div class="text-[10px] text-slate-400 font-medium">${formatTime(transaction.time)}</div>
                            </td>
                            <td class="py-3.5 px-6 whitespace-nowrap">
                                <div class="font-semibold text-slate-900">${transaction.description}</div>
                                <div class="text-[10px] text-slate-400 font-medium">${transaction.bank}</div>
                            </td>
                            <td class="py-3.5 px-6 whitespace-nowrap">
                                <span class="font-mono text-[11px] font-bold text-slate-600 bg-slate-100 px-2 py-0.5 rounded">${transaction.reference}</span>
                            </td>
                            <td class="py-3.5 px-6 whitespace-nowrap">
                                <div class="font-medium text-slate-700">${transaction.name}</div>
                            </td>
                            <td class="py-3.5 px-6 whitespace-nowrap">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase border ${typeColor}">
                                    ${typeText}
                                </span>
                            </td>
                            <td class="py-3.5 px-6 whitespace-nowrap text-right">
                                <span class="${amountClass} font-mono font-bold">${amountPrefix}${transaction.amount.toFixed(2)}</span>
                            </td>
                        </tr>
                    `;
                    }).join('');
                }
            }

            function filterTransactions() {
                const startDate = document.getElementById('startDate').value;
                const endDate = document.getElementById('endDate').value;
                const typeFilter = document.getElementById('typeFilter').value;
                const searchTerm = document.getElementById('searchInput').value.toLowerCase();

                let filtered = transactionsData;
                if (startDate) filtered = filtered.filter(t => t.date >= startDate);
                if (endDate) filtered = filtered.filter(t => t.date <= endDate);
                if (typeFilter !== 'all') filtered = filtered.filter(t => t.type === typeFilter);
                if (searchTerm) {
                    filtered = filtered.filter(t =>
                        t.name.toLowerCase().includes(searchTerm) ||
                        t.reference.toLowerCase().includes(searchTerm) ||
                        t.description.toLowerCase().includes(searchTerm)
                    );
                }
                filtered.sort((a, b) => new Date(b.date + ' ' + b.time) - new Date(a.date + ' ' + a.time));
                renderTransactions(filtered);
            }

            function setDateRange(range) {
                const today = new Date();
                let startDate, endDate = new Date().toISOString().split('T')[0];
                if (range === 'today') {
                    startDate = endDate;
                } else if (range === 'month') {
                    startDate = new Date(today.getFullYear(), today.getMonth(), 1).toISOString().split('T')[0];
                }
                if (document.getElementById('startDate')) document.getElementById('startDate').value = startDate;
                if (document.getElementById('endDate')) document.getElementById('endDate').value = endDate;
                filterTransactions();
            }

            document.addEventListener('DOMContentLoaded', function() {
                setDateRange('month');
                if (document.getElementById('startDate')) document.getElementById('startDate').addEventListener(
                    'change', filterTransactions);
                if (document.getElementById('endDate')) document.getElementById('endDate').addEventListener('change',
                    filterTransactions);
                if (document.getElementById('typeFilter')) document.getElementById('typeFilter').addEventListener(
                    'change', filterTransactions);
                if (document.getElementById('searchInput')) document.getElementById('searchInput').addEventListener(
                    'input', filterTransactions);
            });
        </script>
    </body>
@endsection
