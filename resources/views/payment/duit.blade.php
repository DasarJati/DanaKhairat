@extends('layouts.app')

@section('content')
    <div class="min-h-screen bg-[#f8fafc] pb-12">
        <main class="container mx-auto px-4 sm:px-6 lg:px-8 py-8">

            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-10">
                <div>
                    <h1 class="text-3xl font-black text-slate-900 tracking-tight">Khairat Kewangan</h1>
                </div>
            </div>

            @if (session('success'))
                <div
                    class="mb-6 p-4 bg-emerald-50 border border-emerald-100 text-emerald-700 rounded-2xl flex items-center shadow-sm">
                    <i class="fas fa-check-circle mr-3 text-emerald-500"></i>
                    <span class="font-bold text-sm">{{ session('success') }}</span>
                </div>
            @endif

            @if (session('error'))
                <div
                    class="mb-6 p-4 bg-rose-50 border border-rose-100 text-rose-700 rounded-2xl flex items-center shadow-sm">
                    <i class="fas fa-exclamation-circle mr-3 text-rose-500"></i>
                    <span class="font-bold text-sm">{{ session('error') }}</span>
                </div>
            @endif

            <!-- Year & Month Filter Controls -->
            <div class="bg-white rounded-[2rem] shadow-sm border border-slate-100 p-6 mb-10">
                <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4">
                    <div class="flex items-center">
                        <div class="w-2 h-8 bg-indigo-600 rounded-full mr-4"></div>
                        <h3 class="text-lg font-black text-slate-900 tracking-tight">Filter Laporan Kewangan</h3>
                    </div>

                    <form method="GET" action="{{ route('finance') }}" class="flex flex-col sm:flex-row gap-3">
                        <!-- Year Filter -->
                        <select name="year"
                            class="px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm font-semibold text-slate-700 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 cursor-pointer"
                            onchange="this.form.submit()">
                            <option value="all" {{ $selectedYear == 'all' ? 'selected' : '' }}>Semua Tahun</option>
                            @foreach ($availableYears as $year)
                                <option value="{{ $year }}" {{ $selectedYear == $year ? 'selected' : '' }}>
                                    Tahun {{ $year }}
                                </option>
                            @endforeach
                        </select>

                        <!-- Month Filter -->
                        <select name="month"
                            class="px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm font-semibold text-slate-700 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 cursor-pointer"
                            onchange="this.form.submit()">
                            <option value="all" {{ $selectedMonth == 'all' ? 'selected' : '' }}>Semua Bulan</option>
                            <option value="1" {{ $selectedMonth == 1 ? 'selected' : '' }}>Januari</option>
                            <option value="2" {{ $selectedMonth == 2 ? 'selected' : '' }}>Februari</option>
                            <option value="3" {{ $selectedMonth == 3 ? 'selected' : '' }}>Mac</option>
                            <option value="4" {{ $selectedMonth == 4 ? 'selected' : '' }}>April</option>
                            <option value="5" {{ $selectedMonth == 5 ? 'selected' : '' }}>Mei</option>
                            <option value="6" {{ $selectedMonth == 6 ? 'selected' : '' }}>Jun</option>
                            <option value="7" {{ $selectedMonth == 7 ? 'selected' : '' }}>Julai</option>
                            <option value="8" {{ $selectedMonth == 8 ? 'selected' : '' }}>Ogos</option>
                            <option value="9" {{ $selectedMonth == 9 ? 'selected' : '' }}>September</option>
                            <option value="10" {{ $selectedMonth == 10 ? 'selected' : '' }}>Oktober</option>
                            <option value="11" {{ $selectedMonth == 11 ? 'selected' : '' }}>November</option>
                            <option value="12" {{ $selectedMonth == 12 ? 'selected' : '' }}>Disember</option>
                        </select>

                        <!-- Preserve existing transaction filters -->
                        @if (request('flow_type') && request('flow_type') != 'all')
                            <input type="hidden" name="flow_type" value="{{ request('flow_type') }}">
                        @endif
                        @if (request('status') && request('status') != 'all')
                            <input type="hidden" name="status" value="{{ request('status') }}">
                        @endif
                        @if (request('type') && request('type') != 'all')
                            <input type="hidden" name="type" value="{{ request('type') }}">
                        @endif
                        @if (request('start_date'))
                            <input type="hidden" name="start_date" value="{{ request('start_date') }}">
                        @endif
                        @if (request('end_date'))
                            <input type="hidden" name="end_date" value="{{ request('end_date') }}">
                        @endif

                        <!-- Reset Filters Button -->
                        @if (request('year') || request('month') || request('flow_type') || request('status') || request('type') || request('start_date') || request('end_date'))
                            <a href="{{ route('finance') }}"
                                class="px-6 py-2.5 bg-slate-100 text-slate-600 rounded-xl font-bold text-sm hover:bg-slate-200 transition-colors">
                                <i class="fas fa-times mr-2"></i> Reset Semua
                            </a>
                        @endif
                    </form>
                </div>

                <!-- Active Filters Display -->
                <div class="mt-4 flex flex-wrap items-center gap-2">
                    <span class="text-xs font-semibold text-slate-500">Filter Aktif:</span>
                    <span
                        class="px-3 py-1 bg-indigo-50 text-indigo-700 rounded-lg text-[10px] font-black uppercase tracking-wider">
                        Tahun: {{ $selectedYear == 'all' ? 'Semua' : $selectedYear }}
                    </span>
                    <span
                        class="px-3 py-1 bg-emerald-50 text-emerald-700 rounded-lg text-[10px] font-black uppercase tracking-wider">
                        Bulan:
                        {{ $selectedMonth == 'all' ? 'Semua' : ['Jan', 'Feb', 'Mac', 'Apr', 'Mei', 'Jun', 'Jul', 'Ogos', 'Sept', 'Okt', 'Nov', 'Dis'][$selectedMonth - 1] }}
                    </span>
                    @if (request('flow_type') && request('flow_type') != 'all')
                        <span
                            class="px-3 py-1 bg-purple-50 text-purple-700 rounded-lg text-[10px] font-black uppercase tracking-wider">
                            Jenis: {{ request('flow_type') == 'income' ? 'Masuk' : 'Keluar' }}
                        </span>
                    @endif
                    @if (request('status') && request('status') != 'all')
                        <span
                            class="px-3 py-1 bg-sky-50 text-sky-700 rounded-lg text-[10px] font-black uppercase tracking-wider">
                            Status: {{ ucfirst(str_replace('_', ' ', request('status'))) }}
                        </span>
                    @endif
                    @if (request('type') && request('type') != 'all')
                        <span
                            class="px-3 py-1 bg-fuchsia-50 text-fuchsia-700 rounded-lg text-[10px] font-black uppercase tracking-wider">
                            Kategori: {{ ucfirst(str_replace('_', ' ', request('type'))) }}
                        </span>
                    @endif
                    @if (request('start_date'))
                        <span
                            class="px-3 py-1 bg-amber-50 text-amber-700 rounded-lg text-[10px] font-black uppercase tracking-wider">
                            Dari: {{ \Carbon\Carbon::parse(request('start_date'))->format('d/m/Y') }}
                        </span>
                    @endif
                    @if (request('end_date'))
                        <span
                            class="px-3 py-1 bg-amber-50 text-amber-700 rounded-lg text-[10px] font-black uppercase tracking-wider">
                            Hingga: {{ \Carbon\Carbon::parse(request('end_date'))->format('d/m/Y') }}
                        </span>
                    @endif
                </div>
            </div>

            <!-- Summary Cards -->
            <div class="grid grid-cols-1 lg:grid-cols-4 gap-6 mb-10">
                <!-- Total Dana Card (Yearly) -->
                <div
                    class="lg:col-span-1 bg-slate-900 rounded-[2rem] p-8 shadow-2xl shadow-slate-200 relative overflow-hidden group">
                    <div class="relative z-10 h-full flex flex-col justify-between">
                        <div>
                            <div class="flex items-center justify-between mb-8">
                                <span
                                    class="px-3 py-1 bg-white/10 backdrop-blur-md border border-white/10 rounded-lg text-[10px] font-black uppercase tracking-widest text-indigo-300">
                                    Tahun {{ $selectedYear == 'all' ? 'Semua' : $selectedYear }}
                                </span>
                                <div
                                    class="w-10 h-10 bg-indigo-500/20 rounded-xl flex items-center justify-center border border-indigo-500/30">
                                    <svg class="w-5 h-5 text-indigo-400" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                                    </svg>
                                </div>
                            </div>
                            <p class="text-slate-400 text-xs font-bold uppercase tracking-wider">Jumlah Kewangan Tahunan</p>
                            <h2 class="text-4xl font-black text-white mt-2 tracking-tighter">
                                <span
                                    class="text-indigo-400 text-2xl font-medium mr-1">RM</span>{{ number_format($totalDana, 2) }}
                            </h2>
                        </div>

                        <div class="mt-12 flex justify-between items-center border-t border-white/5 pt-6">
                            <div>
                                <p class="text-[10px] text-slate-500 uppercase font-black tracking-widest">Kemas Kini
                                    Terakhir</p>
                                <p class="text-xs font-bold text-slate-300">
                                    {{ $wallet->updated_at ? $wallet->updated_at->format('d M Y') : 'Belum dikemaskini' }}
                                </p>
                            </div>
                            <div
                                class="h-8 w-12 bg-gradient-to-r from-indigo-500 to-purple-500 rounded-md opacity-40 group-hover:opacity-100 transition-opacity">
                            </div>
                        </div>
                    </div>
                    <div class="absolute -right-10 -top-10 w-40 h-40 bg-indigo-600/20 rounded-full blur-3xl"></div>
                </div>

                <!-- Income/Expense Cards -->
                <div class="lg:col-span-2 grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div
                        class="bg-emerald-500 p-8 rounded-[2rem] shadow-sm border border-slate-100 flex flex-col justify-between group hover:border-emerald-200 transition-all">
                        <div class="flex items-center justify-between mb-4">
                            <div
                                class="w-14 h-14 bg-emerald-50 text-emerald-600 rounded-2xl flex items-center justify-center shadow-inner group-hover:scale-110 transition-transform">
                                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                                </svg>
                            </div>
                            <span
                                class="text-[10px] font-black text-emerald-500 bg-emerald-50 px-2 py-1 rounded-md uppercase tracking-wider">
                                {{ $selectedMonth == 'all' ? 'Semua Bulan' : ['Jan', 'Feb', 'Mac', 'Apr', 'Mei', 'Jun', 'Jul', 'Ogos', 'Sept', 'Okt', 'Nov', 'Dis'][$selectedMonth - 1] }}
                                {{ $selectedYear == 'all' ? '(Semua Tahun)' : $selectedYear }}
                            </span>
                        </div>
                        <div>
                            <p class="text-slate-100 text-xs font-bold uppercase tracking-widest">Jumlah Kewangan Bulanan
                            </p>
                            <p class="text-4xl font-black text-slate-100 mt-1">RM
                                {{ number_format($totalIncomeThisMonth, 2) }}</p>
                        </div>
                    </div>

                    <div
                        class="bg-rose-500 p-8 rounded-[2rem] shadow-sm border border-slate-100 flex flex-col justify-between group hover:border-rose-200 transition-all">
                        <div class="flex items-center justify-between mb-4">
                            <div
                                class="w-14 h-14 bg-rose-50 text-rose-600 rounded-2xl flex items-center justify-center shadow-inner group-hover:scale-110 transition-transform">
                                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M13 17h8m0 0v-8m0 8l-8-8-4 4-6-6" />
                                </svg>
                            </div>
                            <span
                                class="text-[10px] font-black text-rose-500 bg-rose-50 px-2 py-1 rounded-md uppercase tracking-wider">
                                Tahun {{ $selectedYear == 'all' ? 'Semua' : $selectedYear }}
                            </span>
                        </div>
                        <div>
                            <p class="text-slate-200 text-xs font-bold uppercase tracking-widest">Jumlah Kos Keluar</p>
                            <p class="text-3xl font-black text-slate-100 mt-1">RM {{ number_format($totalOut, 2) }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Transactions Section with Filters -->
            <div class="bg-white rounded-[2rem] shadow-sm border border-slate-100 overflow-hidden">
                <!-- Transaction Filter Section -->
                <div class="px-8 py-6 border-b border-slate-100">
                    <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4">
                        <div class="flex items-center">
                            <div class="w-2 h-8 bg-indigo-600 rounded-full mr-4"></div>
                            <h3 class="text-lg font-black text-slate-900 tracking-tight">Senarai Transaksi</h3>
                        </div>

                        <!-- Auto-submit Transaction Filter Form -->
                        <form method="GET" action="{{ route('finance') }}" id="transactionFilterForm"
                            class="flex flex-col sm:flex-row gap-3">
                            <!-- Payment Type (category) Filter - Auto submit on change -->
                            <select name="type" id="typeFilterSelect"
                                class="px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm font-semibold text-slate-700 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 cursor-pointer"
                                onchange="handleTypeFilterChange(this)">
                                <option value="all" {{ request('type', 'all') == 'all' ? 'selected' : '' }}>Semua
                                    Kategori</option>
                                <option value="new_member" {{ request('type') == 'new_member' ? 'selected' : '' }}>
                                    Ahli Baru</option>
                                <option value="renew_member"
                                    {{ request('type') == 'renew_member' ? 'selected' : '' }}>Renew Ahli</option>
                                <option value="subscription"
                                    {{ request('type') == 'subscription' ? 'selected' : '' }}>Langganan</option>
                                <option value="khairat" {{ request('type') == 'khairat' ? 'selected' : '' }}>Khairat
                                    (Pengurusan Kematian)</option>
                            </select>

                            <!-- Transaction Flow Filter - Auto submit on change -->
                            <select name="flow_type" id="flowTypeFilterSelect"
                                class="px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm font-semibold text-slate-700 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 cursor-pointer"
                                onchange="this.form.submit()">
                                <option value="all" {{ request('flow_type') == 'all' ? 'selected' : '' }}>Semua
                                    Transaksi</option>
                                <option value="income"
                                    {{ request('flow_type') == 'income' ? 'selected' : '' }}>Transaksi Masuk
                                </option>
                                <option value="expense"
                                    {{ request('flow_type') == 'expense' ? 'selected' : '' }}>Transaksi
                                    Keluar</option>
                            </select>

                            <!-- Status Filter - Auto submit on change -->
                            <select name="status"
                                class="px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm font-semibold text-slate-700 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 cursor-pointer"
                                onchange="this.form.submit()">
                                <option value="all" {{ request('status') == 'all' ? 'selected' : '' }}>Semua Status
                                </option>
                                <option value="paid" {{ request('status') == 'paid' ? 'selected' : '' }}>Berjaya
                                </option>
                                <option value="waiting_verification"
                                    {{ request('status') == 'waiting_verification' ? 'selected' : '' }}>Menunggu
                                    Pengesahan</option>
                                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>
                                    Pending</option>
                                <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>
                                    Ditolak</option>
                            </select>

                            <!-- Start Date - Auto submit on change -->
                            <div class="relative">
                                <input type="date" name="start_date" value="{{ request('start_date') }}"
                                    class="px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm font-semibold text-slate-700 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 cursor-pointer"
                                    onchange="this.form.submit()">
                            </div>

                            <!-- End Date - Auto submit on change -->
                            <div class="relative">
                                <input type="date" name="end_date" value="{{ request('end_date') }}"
                                    class="px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm font-semibold text-slate-700 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 cursor-pointer"
                                    onchange="this.form.submit()">
                            </div>

                            <!-- Preserve year and month filters -->
                            @if (request('year'))
                                <input type="hidden" name="year" value="{{ request('year') }}">
                            @endif
                            @if (request('month'))
                                <input type="hidden" name="month" value="{{ request('month') }}">
                            @endif

                            <!-- Reset Button (only shows when filters are active) -->
                            @if (request()->has('flow_type') || request()->has('status') || request()->has('type') || request()->has('start_date') || request()->has('end_date'))
                                <a href="{{ route('finance', ['year' => request('year'), 'month' => request('month')]) }}"
                                    class="px-6 py-2.5 bg-slate-100 text-slate-600 rounded-xl font-bold text-sm hover:bg-slate-200 transition-colors text-center">
                                    <i class="fas fa-times mr-2"></i> Reset
                                </a>
                            @endif
                        </form>
                    </div>

                    <!-- Active Transaction Filters Display -->
                    @if (
                        (request()->filled('flow_type') && request('flow_type') != 'all') ||
                            (request()->filled('status') && request('status') != 'all') ||
                            (request()->filled('type') && request('type') != 'all') ||
                            request()->filled('start_date') ||
                            request()->filled('end_date'))
                        <div class="mt-4 flex flex-wrap items-center gap-2">
                            <span class="text-xs font-semibold text-slate-500">Tapis Transaksi:</span>
                            @if (request('flow_type') && request('flow_type') != 'all')
                                <span
                                    class="px-3 py-1 bg-indigo-50 text-indigo-700 rounded-lg text-[10px] font-black uppercase tracking-wider">
                                    {{ request('flow_type') == 'income' ? 'Transaksi Masuk' : 'Transaksi Keluar' }}
                                </span>
                            @endif
                            @if (request('status') && request('status') != 'all')
                                <span
                                    class="px-3 py-1 bg-sky-50 text-sky-700 rounded-lg text-[10px] font-black uppercase tracking-wider">
                                    Status: {{ ucfirst(str_replace('_', ' ', request('status'))) }}
                                </span>
                            @endif
                            @if (request('type') && request('type') != 'all')
                                <span
                                    class="px-3 py-1 bg-fuchsia-50 text-fuchsia-700 rounded-lg text-[10px] font-black uppercase tracking-wider">
                                    Kategori: {{ ucfirst(str_replace('_', ' ', request('type'))) }}
                                </span>
                            @endif
                            @if (request('start_date'))
                                <span
                                    class="px-3 py-1 bg-indigo-50 text-indigo-700 rounded-lg text-[10px] font-black uppercase tracking-wider">
                                    Dari: {{ \Carbon\Carbon::parse(request('start_date'))->format('d/m/Y') }}
                                </span>
                            @endif
                            @if (request('end_date'))
                                <span
                                    class="px-3 py-1 bg-indigo-50 text-indigo-700 rounded-lg text-[10px] font-black uppercase tracking-wider">
                                    Hingga: {{ \Carbon\Carbon::parse(request('end_date'))->format('d/m/Y') }}
                                </span>
                            @endif
                        </div>
                    @endif
                </div>

                <!-- Transactions Table -->
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="bg-slate-50/50">
                                <th
                                    class="px-8 py-4 text-left text-[10px] font-black text-slate-400 uppercase tracking-widest">
                                    Penerangan</th>
                                <th
                                    class="px-8 py-4 text-left text-[10px] font-black text-slate-400 uppercase tracking-widest">
                                    Tarikh</th>
                                <th
                                    class="px-8 py-4 text-left text-[10px] font-black text-slate-400 uppercase tracking-widest">
                                    Jumlah</th>
                                <th
                                    class="px-8 py-4 text-left text-[10px] font-black text-slate-400 uppercase tracking-widest">
                                    Jenis</th>
                                <th
                                    class="px-8 py-4 text-left text-[10px] font-black text-slate-400 uppercase tracking-widest">
                                    Status</th>
                                <th
                                    class="px-8 py-4 text-left text-[10px] font-black text-slate-400 uppercase tracking-widest">
                                    Kaedah</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50">
                            @forelse($filteredPayments as $transaction)
                                <tr class="hover:bg-slate-50/80 transition-colors group">
                                    <td class="px-8 py-5">
                                        <p
                                            class="text-sm font-bold text-slate-900 group-hover:text-indigo-600 transition-colors tracking-tight">
                                            {{ $transaction->name ?? ($transaction->remarks ?? 'Tiada Penerangan') }}</p>
                                    </td>
                                    <td class="px-8 py-5">
                                        <p class="text-sm font-semibold text-slate-500">
                                            {{ $transaction->created_at->format('d/m/Y') }}</p>
                                        <p class="text-xs text-slate-400">{{ $transaction->created_at->format('h:i A') }}
                                        </p>
                                    </td>
                                    <td class="px-8 py-5">
                                        @php
                                            $colors = [
                                                'income' => 'text-emerald-600',
                                                'expense' => 'text-rose-600',
                                            ];
                                            $symbols = [
                                                'income' => '+',
                                                'expense' => '−',
                                            ];
                                            $type = $transaction->flow_type;
                                            $color = $colors[$type] ?? 'text-slate-400';
                                            $symbol = $symbols[$type] ?? '';
                                        @endphp

                                        <span class="text-sm font-black {{ $color }}">
                                            {{ $symbol }}
                                            RM {{ number_format($transaction->amount, 2) }}
                                        </span>

                                        @if (!$type)
                                            <span
                                                class="ml-2 text-[10px] font-semibold text-slate-400 uppercase tracking-wider bg-slate-100 px-2 py-0.5 rounded-md">
                                                (tiada jenis)
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-8 py-5">
                                        @php
                                            $typeLabels = [
                                                'Renew Membership' => ['bg-amber-50', 'text-amber-700', 'Renew Ahli'],
                                                'KHAIRAT' => ['bg-rose-50', 'text-rose-700', 'Pengurusan Kematian'],
                                                'new_member' => ['bg-indigo-50', 'text-indigo-700', 'Ahli Baru'],
                                                'donation' => ['bg-emerald-50', 'text-emerald-700', 'Derma'],
                                                'default' => [
                                                    'bg-slate-50',
                                                    'text-slate-700',
                                                    $transaction->type ?? 'Transaksi',
                                                ],
                                            ];
                                            $typeKey = $transaction->type ?? 'default';
                                            $label = $typeLabels[$typeKey] ?? $typeLabels['default'];
                                        @endphp
                                        <span
                                            class="px-3 py-1 {{ $label[0] }} {{ $label[1] }} border border-opacity-20 rounded-lg text-[10px] font-black uppercase tracking-tighter">
                                            {{ $label[2] }}
                                        </span>
                                    </td>
                                    <td class="px-8 py-5">
                                        @if ($transaction->status === 'paid' || $transaction->status === 'success')
                                            <div class="flex items-center text-emerald-600">
                                                <span class="w-1.5 h-1.5 bg-emerald-500 rounded-full mr-2"></span>
                                                <span
                                                    class="text-[11px] font-black uppercase tracking-widest">Berjaya</span>
                                            </div>
                                        @else
                                            <div class="flex items-center text-amber-600">
                                                <span class="w-1.5 h-1.5 bg-amber-500 rounded-full mr-2"></span>
                                                <span
                                                    class="text-[11px] font-black uppercase tracking-widest">{{ ucfirst($transaction->status) }}</span>
                                            </div>
                                        @endif
                                    </td>
                                    <td class="px-8 py-5 text-sm font-bold text-slate-400">
                                        {{ $transaction->payment_method ?? 'Tunai' }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-8 py-20 text-center">
                                        <div class="flex flex-col items-center">
                                            <svg class="mx-auto h-12 w-12 text-slate-200" fill="none"
                                                stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                                            </svg>
                                            <p class="mt-4 text-slate-400 text-xs font-black uppercase tracking-widest">
                                                Tiada rekod transaksi</p>
                                            @if (request()->has('flow_type') || request()->has('status') || request()->has('type') || request()->has('start_date') || request()->has('end_date'))
                                                <a href="{{ route('finance', ['year' => request('year'), 'month' => request('month')]) }}"
                                                    class="mt-4 text-sm text-indigo-600 hover:text-indigo-700 font-semibold">
                                                    <i class="fas fa-undo mr-1"></i> Reset penapis
                                                </a>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                @if ($filteredPayments->hasPages())
                    <div class="px-8 py-6 border-t border-slate-100">
                        {{ $filteredPayments->links() }}
                    </div>
                @endif

                <!-- Results Info -->
                <div class="px-8 py-4 bg-slate-50/50 border-t border-slate-100 text-xs text-slate-500 font-semibold">
                    Menunjukkan {{ $filteredPayments->firstItem() ?? 0 }} - {{ $filteredPayments->lastItem() ?? 0 }}
                    daripada {{ $filteredPayments->total() }} rekod transaksi
                </div>
            </div>
        </main>
    </div>

    <script>
        // Khairat (pengurusan kematian) is always an expense — auto-align the
        // flow_type filter so the two don't end up contradicting each other.
        function handleTypeFilterChange(select) {
            if (select.value === 'khairat') {
                const flowTypeSelect = document.getElementById('flowTypeFilterSelect');
                if (flowTypeSelect) {
                    flowTypeSelect.value = 'expense';
                }
            }
            select.form.submit();
        }
    </script>

    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>

    <!-- Modal for Tambah Dana -->
    <div x-data="{ openModal: false }" @buka-modal-dana.window="openModal = true" x-cloak>

        <template x-teleport="body">
            <div x-show="openModal" class="fixed inset-0 z-[999] flex items-center justify-center px-4">

                <div x-show="openModal" x-transition.opacity @click="openModal = false"
                    class="absolute inset-0 bg-stone-900/60 backdrop-blur-sm"></div>

                <div x-show="openModal" x-transition:enter="transition ease-out duration-300"
                    x-transition:enter-start="opacity-0 scale-95 translate-y-8"
                    x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                    class="relative w-full max-w-md bg-white rounded-[2.5rem] p-8 shadow-2xl">

                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-xl font-black text-stone-800 tracking-tight italic">Tambah Dana</h3>
                        <button @click="openModal = false"
                            class="text-stone-400 hover:text-stone-600 transition-colors p-2">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>

                    <form action="{{ route('finance.dana.update') }}" method="POST" class="space-y-6">
                        @csrf
                        <div class="bg-stone-50 p-5 rounded-2xl border border-stone-100 flex justify-between items-center">
                            <span class="text-[10px] font-bold text-stone-400 uppercase tracking-widest">Baki Semasa</span>
                            <span class="text-lg font-black text-stone-800">RM
                                {{ number_format($wallet->balance, 2) }}</span>
                        </div>

                        <div>
                            <label
                                class="text-[10px] font-bold text-stone-400 uppercase tracking-widest block mb-2 ml-1">Jumlah
                                Ingin Ditambah (RM)</label>
                            <input type="number" name="amount" step="0.01" autofocus
                                class="w-full px-6 py-4 bg-white border-2 border-stone-100 rounded-2xl focus:border-stone-800 focus:ring-0 transition-all font-bold text-xl text-stone-800 outline-none"
                                placeholder="0.00" required>
                        </div>

                        <button type="submit"
                            class="w-full py-4 bg-stone-900 hover:bg-black text-white rounded-2xl font-bold transition-all shadow-xl active:scale-[0.98]">
                            Sahkan Transaksi
                        </button>
                    </form>
                </div>
            </div>
        </template>
    </div>

    <style>
        /* Custom spacing and anti-aliasing */
        body {
            -webkit-font-smoothing: antialiased;
        }

        .tracking-tighter {
            letter-spacing: -0.05em;
        }

        [x-cloak] {
            display: none !important;
        }
    </style>
@endsection