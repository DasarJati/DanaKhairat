@extends('layouts.app')

@section('title', 'Dashboard - e-Khairat')

@section('content')

    {{-- Flash message yang ringkas --}}
    <div class="container mx-auto px-6 mt-6 max-w-7xl">
        @if (session('success'))
            <div class="bg-emerald-500 text-white px-6 py-3 rounded-lg shadow-sm mb-4 text-sm font-medium">
                <i class="fas fa-check-circle mr-2"></i> {{ session('success') }}
            </div>
        @endif
    </div>


    <div class="min-h-screen  font-sans ">
        <div class="max-w-full mx-auto px-6 py-0 ">

            <div class="flex justify-between items-center mb-6">
                <h1 class="text-2xl font-bold text-gray-800 tracking-tight">Ringkasan Sistem</h1>
                {{-- <a href="/pic/IDlist"
                    class="flex items-center gap-2 bg-white px-4 py-2 rounded-lg border border-gray-200 text-gray-600 hover:bg-gray-50 transition shadow-sm">
                    <i class="fas fa-users-cog text-gray-400"></i>
                    <span class="text-sm font-semibold">Pengurusan AJK</span>
                </a> --}}
            </div>



            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-10 items-stretch">

                <!-- 🕌 IMAGE (Masjid) -->
                <div class="shadow-lg shadow-gray-400 rounded-2xl h-full w-full overflow-hidden">
                    @if ($imagePath)
                        <img src="{{ asset($imagePath) }}" alt="Gambar Masjid"
                            class="w-full h-[400px] object-cover rounded-2xl" />
                    @else
                        <div
                            class="w-full h-[400px] bg-gray-100 rounded-2xl flex flex-col items-center justify-center gap-2">
                            <svg class="w-12 h-12 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                    d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            <span class="text-gray-400 text-sm">Tiada gambar masjid</span>
                        </div>
                    @endif
                </div>

                <!-- 📊 CONTENT -->
                <div class="order-2 md:order-1 grid grid-cols-1 gap-3">

                    <!-- CARD 1 -->
                    <div
                        class="bg-slate-700 rounded-2xl p-6 text-white shadow-lg relative overflow-hidden flex items-center justify-center">
                        <div class="justify-between items-center gap-6 flex max-w-full w-full">

                            <div>
                                <div class="relative z-10">
                                    <p class="text-slate-300 text-xs font-bold uppercase tracking-widest mb-1">
                                        Jumlah Ahli
                                    </p>
                                    <h3 class="text-4xl font-bold">{{ $jumlahAhli ?? 0 }}</h3>

                                    <div class="flex gap-4 mt-4 text-xs font-medium opacity-90">
                                        <span><i class="fas fa-male mr-1 text-blue-300"></i> {{ $jumlahLelaki ?? 0 }}
                                            Lelaki</span>
                                        <span><i class="fas fa-female mr-1 text-pink-300"></i> {{ $jumlahPerempuan ?? 0 }}
                                            Wanita</span>
                                    </div>
                                </div>

                                <i class="fas fa-users absolute -bottom-4 -right-2 text-8xl opacity-10 rotate-12"></i>

                                <a href="/senaraiahli"
                                    class="block mt-6 text-xs font-bold text-slate-300 hover:text-white transition uppercase tracking-tighter">
                                    Lihat Senarai <i class="fas fa-arrow-right ml-1"></i>
                                </a>
                            </div>

                            <div class="hidden md:block w-px bg-slate-500 h-16 mx-6"></div>

                            <div>
                                <div class="relative z-10">
                                    <p class="text-slate-300 text-xs font-bold uppercase tracking-widest mb-1">
                                        Belum Disahkan
                                    </p>
                                    <h3 class="text-4xl font-bold">{{ $jumlahAhliPending ?? 0 }}</h3>

                                    <div class="flex gap-4 mt-4 text-xs font-medium opacity-90">
                                        <span><i class="fas fa-exclamation-triangle mr-1 text-yellow-300"></i>Perlu
                                            pengesahan segera</span>
                                    </div>
                                </div>

                                <i class="fas fa-users absolute -bottom-4 -right-2 text-8xl opacity-10 rotate-12"></i>

                                <a href="/senarai/pengesahan"
                                    class="block mt-6 text-xs font-bold text-slate-300 hover:text-white transition uppercase tracking-tighter">
                                    Semak Sekarang <i class="fas fa-arrow-right ml-1"></i>
                                </a>
                            </div>

                        </div>
                    </div>

                    <!-- CARD 2 -->
                    <div class="bg-[#F9D949] rounded-2xl p-6 text-[#443C20] shadow-lg relative overflow-hidden">
                        <div class="relative z-10">
                            <p class="text-[#86762E] text-xs font-bold uppercase tracking-widest mb-1">
                                Dana Terkumpul
                            </p>

                            <h3 class="text-4xl font-bold">
                                RM {{ number_format($totalDana, 2) }}
                            </h3>

                            <p class="text-[#86762E] text-[10px] mt-4 font-bold uppercase tracking-tight">
                                Kutipan Tahun Terkini
                            </p>
                        </div>

                        <i class="fas fa-chart-line absolute -bottom-4 -right-2 text-8xl opacity-20 rotate-12"></i>

                        <a href="/finance"
                            class="block mt-6 text-xs font-bold text-[#86762E] hover:text-[#443C20] transition uppercase tracking-tighter">
                            Laporan Kewangan <i class="fas fa-arrow-right ml-1"></i>
                        </a>
                    </div>

                </div>

            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

                <div class="lg:col-span-2 bg-white rounded-3xl border border-gray-100 p-8 shadow-sm">
                    <div class="flex items-center justify-between mb-8">
                        <h2 class="text-xl font-extrabold text-gray-800 tracking-tight">Tindakan Pantas</h2>
                        <div class="flex space-x-1">
                            <div class="h-1.5 w-1.5 bg-gray-200 rounded-full"></div>
                            <div class="h-1.5 w-6 bg-gray-200 rounded-full"></div>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-5">
                        <a href="/daftar/{{ $masjidId }}"
                            class="group flex flex-col items-center justify-center p-8 rounded-2xl bg-slate-100 hover:bg-slate-700 transition-all duration-300 shadow-sm hover:shadow-xl hover:-translate-y-1">
                            <div
                                class="w-16 h-16 bg-white rounded-full flex items-center justify-center mb-4 shadow-sm group-hover:scale-90 transition-transform duration-300">
                                <i class="fas fa-user-plus text-slate-600 text-xl"></i>
                            </div>
                            <span
                                class="text-sm font-black text-slate-700 group-hover:text-white tracking-wide transition-colors">Tambah
                                Ahli</span>
                        </a>

                        <a href="/masjid/info"
                            class="group flex flex-col items-center justify-center p-8 rounded-2xl bg-[#FEF3C7] hover:bg-[#F9D949] transition-all duration-300 shadow-sm hover:shadow-xl hover:-translate-y-1">
                            <div
                                class="w-16 h-16 bg-white rounded-full flex items-center justify-center mb-4 shadow-sm group-hover:scale-90 transition-transform duration-300">
                                <i class="fas fa-mosque text-amber-600 text-xl"></i>
                            </div>
                            <span class="text-sm font-black text-amber-900 tracking-wide transition-colors">Profil
                                Surau</span>
                        </a>

                        <a href="/finance"
                            class="group flex flex-col items-center justify-center p-8 rounded-2xl bg-emerald-50 hover:bg-emerald-600 transition-all duration-300 shadow-sm hover:shadow-xl hover:-translate-y-1">
                            <div
                                class="w-16 h-16 bg-white rounded-full flex items-center justify-center mb-4 shadow-sm group-hover:scale-90 transition-transform duration-300">
                                <i class="fas fa-money-bill-wave text-emerald-600 text-xl"></i>
                            </div>
                            <span
                                class="text-sm font-black text-emerald-900 group-hover:text-white tracking-wide transition-colors">Kutipan</span>
                        </a>

                        <a href="/ajk/tuntutan"
                            class="group flex flex-col items-center justify-center p-8 rounded-2xl bg-blue-50 hover:bg-blue-600 transition-all duration-300 shadow-sm hover:shadow-xl hover:-translate-y-1">
                            <div
                                class="w-16 h-16 bg-white rounded-full flex items-center justify-center mb-4 shadow-sm group-hover:scale-90 transition-transform duration-300">
                                <i class="fas fa-chart-bar text-blue-600 text-xl"></i>
                            </div>
                            <span
                                class="text-sm font-black text-blue-900 group-hover:text-white tracking-wide transition-colors">Senarai
                                Kematian</span>
                        </a>
                    </div>
                </div>

                <div class="bg-white rounded-2xl border border-gray-100 p-4 shadow-sm flex flex-col">
                    <h2 class="text-lg font-bold text-gray-800 mb-6">Log Aktiviti</h2>

                    <div class="relative ml-6">
                        <div class="max-h-[400px] overflow-y-auto p-4">
                            @forelse($logs as $date => $items)
                                <!-- Date -->
                                <div class="mb-6">
                                    <div class="flex w-full justify-center items-center mb-4">
                                        <p class="text-md font-bold text-gray-400 uppercase px-3 bg-white">
                                            {{ $date }}
                                        </p>
                                    </div>

                                    <div class="relative">
                                        <!-- Garis menegak untuk kumpulan tarikh ini sahaja -->
                                        <div class="absolute left-[-4px] top-0 bottom-0 w-1 bg-gray-300"></div>

                                        @foreach ($items as $log)
                                            <div class="relative mb-6 ml-4 last:mb-0">
                                                <!-- Circle -->
                                                @php
                                                    $circleColor = match ($log->entity_type) {
                                                        'Login' => 'bg-blue-500',
                                                        'Tuntutan' => 'bg-orange-500',
                                                        default => 'bg-gray-400',
                                                    };
                                                @endphp

                                                <span
                                                    class="absolute -left-[30px] top-3 w-6 h-6 {{ $circleColor }} rounded-full border-2 border-white z-10 shadow-sm"></span>

                                                <div>
                                                    <p class="text-xs font-medium text-black/50">
                                                        {{ $log->created_at->format('H:i A') }}
                                                    </p>
                                                    <p class="text-sm font-semibold text-gray-800">
                                                        {{ $log->description }}
                                                    </p>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @empty
                                <p class="text-sm text-gray-400">Tiada aktiviti direkodkan.</p>
                            @endforelse
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

@endsection
