@extends('layouts.user')

@section('title', 'Dashboard - Ahli Khairat')

@section('content')
    <div class="container mx-auto">

        <div class="min-h-screen">
            <div class="relative mb-4">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 rounded-3xl overflow-hidden shadow-lg p-4">
                    <div class="hidden md:block shadow-lg shadow-gray-400 rounded-2xl h-full w-full overflow-hidden">
                        @if ($user->masjid->image_path)
                            <img src="{{ asset($user->masjid->image_path) }}" alt="Gambar Masjid"
                                class="w-full h-[445px] object-cover rounded-2xl" />
                        @else
                            <div
                                class="w-full h-[400px] md:h-full bg-gray-100 rounded-2xl flex flex-col items-center justify-center gap-2">
                                <span class="text-gray-400 text-sm">Tiada gambar masjid</span>
                            </div>
                        @endif
                    </div>
                    <div class="flex flex-col gap-4">
                        <div
                            class="col-span-1 relative bg-gradient-to-r from-[#2c7a7b] to-[#fbd38d] rounded-3xl shadow-xl p-6 overflow-hidden border border-white/10">
                            <div class="relative z-10">
                                <div class="relative flex flex-col md:flex-row md:justify-between md:items-start gap-4">
                                    <div class="flex-1">
                                        <h1
                                            class="text-2xl md:text-4xl font-extrabold tracking-tight text-white drop-shadow-sm">
                                            Selamat Datang,
                                        </h1>
                                        <p class="text-lg md:text-xl mt-1 text-white/90">
                                            <span class="font-bold border-b-2 border-white/50 pb-0.5 capitalize">
                                                {{ strtolower($user->nama) }}
                                            </span>
                                        </p>

                                        <div class="mt-6">
                                            <p
                                                class="text-xs md:text-sm font-medium uppercase tracking-[0.2em] text-white/70">
                                                Pendaftaran Di Bawah:
                                            </p>
                                            <div
                                                class="mt-2 inline-flex items-center px-4 py-2 bg-white/20 backdrop-blur-md rounded-2xl border border-white/30">
                                                <svg class="w-4 h-4 mr-2 text-white" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4">
                                                    </path>
                                                </svg>
                                                <span class="text-sm md:text-base font-bold text-white tracking-wide">
                                                    @if ($user->ahliKariah && $user->ahliKariah->masjid)
                                                        {{ strtoupper($user->ahliKariah->masjid->nama) }}
                                                    @else
                                                        TIADA REKOD MASJID
                                                    @endif
                                                </span>
                                            </div>
                                        </div>
                                    </div>

                                    <div
                                        class="hidden md:block absolute top-[-30px] right-[-40px] opacity-40 md:opacity-100 group-hover:scale-110 transition-transform duration-500">
                                        <img src="https://www.pngall.com/wp-content/uploads/4/Islam-Mosque-PNG-Pic.png"
                                            alt="Mosque Icon"
                                            class="w-48 h-48 md:w-56 md:h-56 object-contain drop-shadow-2xl brightness-110">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div
                            class="col-span-1 relative bg-gradient-to-r from-[#2c7a7b] to-[#fbd38d] rounded-3xl shadow-lg text-white p-6 overflow-hidden border border-white/10">
                            <div class="relative z-10 flex flex-col md:flex-row md:justify-between md:items-center gap-4">
                                <div class="flex-1 w-full">
                                    <p class="text-xs uppercase tracking-[0.2em] text-white/70 font-bold mb-1">
                                        Status Keahlian
                                    </p>

                                    @php
                                        // Get the LATEST subscription by end_date
                                        $subscription = $user
                                            ->subscriptions()
                                            ->orderBy('end_date', 'desc')
                                            ->orderBy('created_at', 'desc')
                                            ->first();
                                        $status = strtolower($subscription->status ?? 'expired');

                                        // Check if subscription is actually active (not expired)
                                        $isActuallyActive =
                                            $subscription &&
                                            $subscription->status === 'active' &&
                                            $subscription->end_date &&
                                            \Carbon\Carbon::now()->lte(\Carbon\Carbon::parse($subscription->end_date));

                                        $displayStatus = $isActuallyActive ? 'AKTIF' : 'TAMAT';
                                        $statusColor = $isActuallyActive ? 'text-emerald-300' : 'text-red-300';
                                    @endphp

                                    <div class="flex items-center gap-3 mb-6">
                                        <span
                                            class="text-4xl md:text-5xl font-extrabold tracking-tighter drop-shadow-md {{ $statusColor }}">
                                            {{ $displayStatus }}
                                        </span>
                                    </div>

                                    @php
                                        // Use the latest subscription for date calculations
                                        $latestSub = $user
                                            ->subscriptions()
                                            ->orderBy('end_date', 'desc')
                                            ->orderBy('created_at', 'desc')
                                            ->first();

                                        $hasValidSub = $latestSub && $latestSub->start_date && $latestSub->end_date;
                                        $start = $hasValidSub
                                            ? \Carbon\Carbon::parse($latestSub->start_date)
                                            : \Carbon\Carbon::now();
                                        $end = $hasValidSub
                                            ? \Carbon\Carbon::parse($latestSub->end_date)->endOfDay()
                                            : \Carbon\Carbon::now();
                                        $today = \Carbon\Carbon::now();

                                        if (!$hasValidSub || $today->lt($start)) {
                                            $percent = 0;
                                            $daysLeft = $hasValidSub ? (int) floor($today->floatDiffInDays($end)) : 0;
                                        } elseif ($today->gt($end)) {
                                            $percent = 100;
                                            $daysLeft = 0;
                                        } else {
                                            $totalDays = max((int) floor($start->floatDiffInDays($end)), 1);
                                            $passedDays = (int) floor($start->floatDiffInDays($today));
                                            $percent = round(($passedDays / $totalDays) * 100);
                                            $daysLeft = (int) floor($today->floatDiffInDays($end));
                                        }
                                    @endphp

                                    <div
                                        class="mb-2 flex items-center justify-between text-xs font-bold text-white/90 w-full max-w-md tracking-wide">
                                        <span class="bg-black/10 px-2 py-0.5 rounded">
                                            {{ $hasValidSub ? $start->format('d M Y') : 'Tiada Rekod' }}
                                        </span>
                                        <span class="bg-black/10 px-2 py-0.5 rounded">
                                            {{ $hasValidSub ? $end->format('d M Y') : 'Tiada Rekod' }}
                                        </span>
                                    </div>

                                    <div class="w-full max-w-md bg-black/20 rounded-full h-3 p-[2px] backdrop-blur-sm">
                                        <div class="bg-gradient-to-r from-emerald-400 to-green-300 h-full rounded-full transition-all duration-700 shadow-[0_0_10px_rgba(52,211,153,0.5)]"
                                            style="width: {{ $percent }}%;"></div>
                                    </div>

                                    <p class="text-sm mt-2 font-bold flex items-center gap-1.5">
                                        <svg class="w-4 h-4 text-white/80" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                                            </path>
                                        </svg>
                                        <span class="text-white">
                                            @if (!$hasValidSub)
                                                <span class="text-red-200">Tiada langganan aktif</span>
                                            @elseif ($daysLeft <= 0)
                                                <span class="text-red-200">Tempoh telah tamat pada
                                                    {{ $end->format('d M Y') }}</span>
                                            @else
                                                <span class="text-white">{{ $daysLeft }}</span>
                                                <span class="text-white/80 font-medium">Hari lagi</span>
                                                @if ($daysLeft <= 30)
                                                    <span class="ml-2 text-xs bg-red-500/30 px-2 py-0.5 rounded-full">Akan
                                                        tamat!</span>
                                                @endif
                                            @endif
                                        </span>
                                    </p>
                                </div>

                                <div class="hidden md:block absolute top-[-20px] right-[-30px] pointer-events-none">
                                    <img src="{{ asset('images/jam.png') }}" alt="Header Image"
                                        class="w-52 h-52 object-cover drop-shadow-2xl brightness-110">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>


                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mt-6">

                    <div class="lg:col-span-2">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                            <div
                                class="bg-gradient-to-br from-amber-50 to-white rounded-2xl shadow-lg p-6 hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1 border border-amber-100 flex flex-col justify-between h-full">
                                <div class="flex items-center justify-between mb-4">
                                    <div>
                                        <p class="text-sm text-amber-700 font-bold uppercase tracking-wider">Tempoh
                                            Berdaftar</p>
                                    </div>
                                    <div class="p-2 bg-amber-100 rounded-xl shadow-inner">
                                        <svg class="w-6 h-6 text-amber-600" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                    </div>
                                </div>

                                @php
                                    // Use the exact integers from diff(), based on $start and $end we defined above
                                    if ($hasValidSub) {
                                        $diff = $start->diff($end);
                                        $durationYears = $diff->y;
                                        $durationMonths = $diff->m;
                                    } else {
                                        $durationYears = 0;
                                        $durationMonths = 0;
                                    }
                                @endphp

                                <div class="mb-4">
                                    <h3 class="text-4xl font-extrabold text-gray-800 tracking-tight">
                                        @if ($durationYears > 0)
                                            {{ $durationYears }} <span
                                                class="text-xl font-semibold text-gray-500">Tahun</span>
                                        @elseif($durationMonths > 0)
                                            {{ $durationMonths }} <span
                                                class="text-xl font-semibold text-gray-500">Bulan</span>
                                        @else
                                            <span class="text-2xl font-semibold text-gray-500">Kurang sebulan</span>
                                        @endif
                                    </h3>
                                </div>

                                <div class="pt-4 border-t border-amber-100/50">
                                    <div class="flex items-center gap-2">
                                        <div class="w-2 h-2 rounded-full bg-amber-400 animate-pulse"></div>
                                        <p class="text-xs font-medium text-gray-500 uppercase tracking-tighter">Tarikh Tamat
                                            Keahlian:</p>
                                    </div>

                                    @if ($hasValidSub)
                                        <div class="mt-1 flex items-center gap-1.5">
                                            <span class="text-sm font-bold text-gray-700">
                                                {{ $end->format('d') }}
                                            </span>
                                            <span class="text-sm font-semibold text-gray-600">
                                                {{ $end->format('M Y') }}
                                            </span>
                                            <span
                                                class="text-[10px] px-1.5 py-0.5 bg-gray-100 text-gray-500 rounded font-medium">
                                                {{ $end->locale('ms')->translatedFormat('l') }}
                                            </span>
                                        </div>
                                    @else
                                        <div class="mt-1">
                                            <span class="text-sm font-medium text-gray-400 italic">Tiada rekod tamat</span>
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <div
                                class="bg-gradient-to-br from-blue-50 to-white rounded-2xl shadow-lg p-6 hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1 border border-blue-100">
                                <div class="flex items-center justify-between mb-2">
                                    <p class="text-sm text-gray-600 font-medium">Jumlah Ahli</p>
                                    <div class="p-2 bg-blue-100 rounded-lg">
                                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z">
                                            </path>
                                        </svg>
                                    </div>
                                </div>

                                {{-- Use variables passed from controller --}}
                                <h3 class="text-3xl font-bold text-gray-800">
                                    {{ $totalFamilyMembers ?? 1 }}
                                </h3>

                                <div class="grid grid-cols-2 gap-4 mt-3">
                                    <div
                                        class="text-center p-3 bg-gradient-to-br from-emerald-50 to-white rounded-xl border border-emerald-100">
                                        <div class="text-2xl font-bold text-emerald-700">{{ $ketuaCount ?? 1 }}</div>
                                        <div class="text-xs text-gray-600 mt-1">Ketua</div>
                                    </div>
                                    <div
                                        class="text-center p-3 bg-gradient-to-br from-blue-50 to-white rounded-xl border border-blue-100">
                                        <div class="text-2xl font-bold text-blue-700">{{ $tanggunganCount ?? 0 }}</div>
                                        <div class="text-xs text-gray-600 mt-1">Tanggungan</div>
                                    </div>
                                </div>

                                <div class="pt-3 border-t border-emerald-100/50 flex justify-end">
                                    <a href="/ahli-keluarga"
                                        class="text-[11px] font-bold text-emerald-600 hover:text-emerald-700 flex items-center gap-1 group transition-colors">
                                        KLIK UNTUK INFO LANJUT
                                        <svg class="w-3 h-3 transform group-hover:translate-x-1 transition-transform"
                                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                                        </svg>
                                    </a>
                                </div>
                            </div>

                            <div
                                class="bg-gradient-to-br from-emerald-50 to-white rounded-2xl shadow-lg p-6 hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1 border border-emerald-100 flex flex-col justify-between h-full relative overflow-hidden">
                                <div class="absolute -right-4 -top-4 w-20 h-20 bg-emerald-500/5 rounded-full"></div>
                                <div class="flex items-center justify-between mb-4">
                                    <p class="text-sm text-emerald-700 font-bold uppercase tracking-wider">Status Bayaran
                                        Khairat</p>
                                    <div
                                        class="p-2 {{ $statusAktif ?? false ? 'bg-emerald-100 text-emerald-600' : 'bg-red-100 text-red-600' }} rounded-xl">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                    </div>
                                </div>

                                <div class="mb-4">
                                    <p class="text-xs font-semibold text-gray-400 uppercase tracking-tighter">Tahun</p>
                                    <h3 class="text-3xl font-extrabold text-gray-800 leading-tight">
                                        {{ $hasValidSub ? $end->format('Y') : date('Y') }}
                                    </h3>

                                    <div
                                        class="mt-2 inline-flex items-center gap-1.5 px-3 py-1 rounded-lg {{ $statusAktif ?? false ? 'bg-emerald-500 text-white' : 'bg-red-500 text-white' }} text-xs font-bold shadow-sm">
                                        {{ $statusAktif ?? false ? 'SELESAI' : 'TERTUNGGAK' }}
                                        <span class="w-1.5 h-1.5 rounded-full bg-white animate-pulse"></span>
                                    </div>
                                </div>

                                <div class="pt-3 border-t border-emerald-100/50 flex justify-end">
                                    <a href="#"
                                        class="text-[11px] font-bold text-emerald-600 hover:text-emerald-700 flex items-center gap-1 group transition-colors">
                                        KLIK UNTUK INFO LANJUT
                                        <svg class="w-3 h-3 transform group-hover:translate-x-1 transition-transform"
                                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                                        </svg>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Aktiviti Terkini - Modern Timeline Design -->
                    <div class="relative bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
                        <div class="absolute top-0 left-0 w-1 h-full bg-gradient-to-b from-blue-500 to-purple-500"></div>
                        <div class="p-5">
                            <div class="flex items-center justify-between mb-4">
                                <div class="flex items-center gap-2.5">
                                    <div class="p-2 bg-gradient-to-br from-purple-50 to-pink-50 rounded-xl">
                                        <svg class="w-4 h-4 text-purple-600" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                    </div>
                                    <p class="text-[11px] font-black text-purple-600 uppercase tracking-wider">Aktiviti
                                        Terkini</p>
                                </div>
                                <span class="text-[10px] text-gray-400">5 terkini</span>
                            </div>

                            @if (isset($audit_logs) && $audit_logs->count() > 0)
                                <div class="space-y-3 max-h-[50vh] overflow-y-auto pr-1 custom-scrollbar">
                                    @foreach ($audit_logs->take(10) as $index => $log)
                                        @php
                                            $logDate = \Carbon\Carbon::parse($log->created_at);
                                            $logDate->locale('ms');
                                            $now = \Carbon\Carbon::now();
                                            $isToday = $logDate->isToday();
                                            $isYesterday = $logDate->isYesterday();
                                            $isThisWeek = $logDate->diffInDays($now) <= 7;

                                            // Determine time display
                                            if ($isToday) {
                                                $timeDisplay = 'Hari ini, ' . $logDate->format('h:i A');
                                            } elseif ($isYesterday) {
                                                $timeDisplay = 'Semalam, ' . $logDate->format('h:i A');
                                            } elseif ($isThisWeek) {
                                                $timeDisplay = $logDate->format('l') . ', ' . $logDate->format('h:i A');
                                            } else {
                                                $timeDisplay = $logDate->format('d M Y, h:i A');
                                            }

                                            // Get icon based on entity type
                                            $iconMap = [
                                                'login' => [
                                                    'icon' => '🔐',
                                                    'color' => 'from-emerald-500 to-teal-600',
                                                    'bg' => 'emerald',
                                                ],
                                                'logout' => [
                                                    'icon' => '🚪',
                                                    'color' => 'from-gray-500 to-gray-600',
                                                    'bg' => 'gray',
                                                ],
                                                'dashboard' => [
                                                    'icon' => '📊',
                                                    'color' => 'from-blue-500 to-indigo-600',
                                                    'bg' => 'blue',
                                                ],
                                                'payment' => [
                                                    'icon' => '💰',
                                                    'color' => 'from-green-500 to-emerald-600',
                                                    'bg' => 'green',
                                                ],
                                                'subscription' => [
                                                    'icon' => '⭐',
                                                    'color' => 'from-amber-500 to-orange-600',
                                                    'bg' => 'amber',
                                                ],
                                                'profile' => [
                                                    'icon' => '👤',
                                                    'color' => 'from-purple-500 to-pink-600',
                                                    'bg' => 'purple',
                                                ],
                                                'claim' => [
                                                    'icon' => '📋',
                                                    'color' => 'from-red-500 to-rose-600',
                                                    'bg' => 'red',
                                                ],
                                                'document' => [
                                                    'icon' => '📄',
                                                    'color' => 'from-cyan-500 to-blue-600',
                                                    'bg' => 'cyan',
                                                ],
                                                'family' => [
                                                    'icon' => '👨‍👩‍👧',
                                                    'color' => 'from-indigo-500 to-purple-600',
                                                    'bg' => 'indigo',
                                                ],
                                                'general' => [
                                                    'icon' => '📌',
                                                    'color' => 'from-gray-500 to-gray-600',
                                                    'bg' => 'gray',
                                                ],
                                            ];
                                            $entityType = $log->entity_type ?? 'general';
                                            $iconData = $iconMap[$entityType] ?? $iconMap['general'];
                                        @endphp

                                        <div class="relative group">
                                            <div class="absolute left-3 top-0 bottom-0 w-px bg-gray-100 group-last:hidden">
                                            </div>
                                            <div class="flex gap-3">
                                                <div class="relative z-10">
                                                    <div
                                                        class="w-7 h-7 rounded-full bg-gradient-to-br {{ $iconData['color'] }} flex items-center justify-center shadow-sm">
                                                        <span class="text-[11px]">{{ $iconData['icon'] }}</span>
                                                    </div>
                                                </div>
                                                <div class="flex-1 pb-3">
                                                    <div
                                                        class="bg-gray-50 rounded-xl p-3 hover:bg-gray-100 transition-all duration-200 hover:shadow-sm">
                                                        <p class="text-xs font-semibold text-gray-800 leading-relaxed">
                                                            {{ $log->description }}
                                                        </p>
                                                        <div class="flex flex-wrap items-center gap-2 mt-2">
                                                            <!-- Calendar Icon & Date -->
                                                            <div class="flex items-center gap-1.5">
                                                                <svg class="w-3 h-3 text-gray-400" fill="none"
                                                                    stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                                        stroke-width="2"
                                                                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                                                                    </path>
                                                                </svg>
                                                                <span
                                                                    class="text-[10px] font-medium text-gray-500">{{ $timeDisplay }}</span>
                                                            </div>

                                                            <!-- Clock Icon & Time Ago -->
                                                            <div class="flex items-center gap-1.5">
                                                                <svg class="w-3 h-3 text-gray-400" fill="none"
                                                                    stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                                        stroke-width="2"
                                                                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z">
                                                                    </path>
                                                                </svg>
                                                                <span
                                                                    class="text-[10px] text-gray-400">{{ $logDate->diffForHumans() }}</span>
                                                            </div>

                                                            <!-- Badge -->
                                                            <span
                                                                class="text-[9px] px-2 py-0.5 bg-{{ $iconData['bg'] }}-100 text-{{ $iconData['bg'] }}-700 rounded-full font-medium uppercase tracking-wider">
                                                                {{ ucfirst($entityType) }}
                                                            </span>
                                                        </div>

                                                        <!-- Time details expandable (optional) -->
                                                        <div
                                                            class="mt-1.5 text-[9px] text-gray-400 flex items-center gap-2">
                                                            <span>{{ $logDate->format('l, j F Y') }}</span>
                                                            <span>•</span>
                                                            <span>{{ $logDate->format('h:i:s A') }}</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="text-center py-8">
                                    <div
                                        class="w-12 h-12 bg-gradient-to-br from-gray-50 to-gray-100 rounded-2xl flex items-center justify-center mx-auto mb-3">
                                        <svg class="w-6 h-6 text-gray-300" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                                d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4">
                                            </path>
                                        </svg>
                                    </div>
                                    <p class="text-xs text-gray-400">Tiada rekod aktiviti terkini</p>
                                    <p class="text-[10px] text-gray-300 mt-1">Aktiviti akan dipaparkan di sini</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

<style>
    /* Custom Scrollbar */
    .custom-scrollbar::-webkit-scrollbar {
        width: 4px;
    }
    
    .custom-scrollbar::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 10px;
    }
    
    .custom-scrollbar::-webkit-scrollbar-thumb {
        background: #cbd5e1;
        border-radius: 10px;
    }
    
    .custom-scrollbar::-webkit-scrollbar-thumb:hover {
        background: #94a3b8;
    }
    
    /* Badge colors */
    .bg-emerald-100 { background-color: #d1fae5; }
    .text-emerald-700 { color: #047857; }
    .bg-gray-100 { background-color: #f3f4f6; }
    .text-gray-700 { color: #374151; }
    .bg-blue-100 { background-color: #dbeafe; }
    .text-blue-700 { color: #1d4ed8; }
    .bg-green-100 { background-color: #dcfce7; }
    .text-green-700 { color: #15803d; }
    .bg-amber-100 { background-color: #fef3c7; }
    .text-amber-700 { color: #b45309; }
    .bg-purple-100 { background-color: #f3e8ff; }
    .text-purple-700 { color: #6b21a5; }
    .bg-red-100 { background-color: #fee2e2; }
    .text-red-700 { color: #b91c1c; }
    .bg-cyan-100 { background-color: #cffafe; }
    .text-cyan-700 { color: #0e7490; }
    .bg-indigo-100 { background-color: #e0e7ff; }
    .text-indigo-700 { color: #4338ca; }
</style>
