@extends ('layouts.admin')

@section('title', 'Dashboard - e-Khairat')

@section('content')

<div class="mb-6">
    <h1 class="text-xl font-bold text-gray-800 tracking-tight text-center md:text-left">Admin Dashboard</h1>
</div>

{{-- Grid Layout: 3 Column pada Desktop, 2 Column pada Tablet, 1 Column pada Mobile --}}
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">

    {{-- Card 1: Institusi --}}
    <div class="group bg-white rounded-xl border border-gray-100 shadow-sm hover:shadow-md transition-all duration-300 flex flex-col">
        <div class="p-5 flex-1">
            <div class="flex items-center gap-3 mb-3 text-blue-600">
                <div class="w-10 h-10 bg-blue-50 rounded-lg flex items-center justify-center">
                    <i class="fas fa-university"></i>
                </div>
                <h2 class="text-xs font-bold text-gray-400 uppercase tracking-wider">Institusi</h2>
            </div>
            <div class="flex items-baseline justify-between">
                <span class="text-3xl font-black text-gray-800">{{ $totalRole1 }}</span>
                <div class="flex gap-1">
                    <span class="text-[9px] bg-blue-50 text-blue-700 px-1.5 py-0.5 rounded font-bold">{{ $totalMasjid }} Masjid</span>
                    <span class="text-[9px] bg-purple-50 text-purple-700 px-1.5 py-0.5 rounded font-bold">{{ $totalSurau }} Surau</span>
                </div>
            </div>
        </div>
        <a href="{{ route('admin.list.masjid') }}" class="bg-gray-50 group-hover:bg-blue-600 border-t border-gray-100 p-2.5 flex items-center justify-center gap-2 rounded-b-xl transition-colors">
            <span class="text-[10px] font-bold uppercase text-gray-500 group-hover:text-white">Lihat Institusi</span>
            <i class="fas fa-arrow-right text-[10px] text-blue-600 group-hover:text-white group-hover:translate-x-1 transition-all"></i>
        </a>
    </div>

    {{-- Card 2: Ahli Khairat (Aktif) --}}
    <div class="group bg-white rounded-xl border border-gray-100 shadow-sm hover:shadow-md transition-all duration-300 flex flex-col">
        <div class="p-5 flex-1">
            <div class="flex items-center gap-3 mb-3 text-green-600">
                <div class="w-10 h-10 bg-green-50 rounded-lg flex items-center justify-center">
                    <i class="fas fa-users"></i>
                </div>
                <h2 class="text-xs font-bold text-gray-400 uppercase tracking-wider">Ahli Khairat</h2>
            </div>
            <div class="flex items-baseline gap-2">
                <span class="text-3xl font-black text-gray-800">{{ $totalRole2 }}</span>
                <span class="text-[10px] font-bold text-green-500 uppercase tracking-tighter">Aktif</span>
            </div>
        </div>
        <a href="/admin/list-kariah" class="bg-gray-50 group-hover:bg-green-600 border-t border-gray-100 p-2.5 flex items-center justify-center gap-2 rounded-b-xl transition-colors">
            <span class="text-[10px] font-bold uppercase text-gray-500 group-hover:text-white">Urus Ahli</span>
            <i class="fas fa-arrow-right text-[10px] text-green-600 group-hover:text-white group-hover:translate-x-1 transition-all"></i>
        </a>
    </div>

    {{-- Card 3: Permohonan Tuntutan (Kematian/Lain-lain) --}}
    <div class="group bg-white rounded-xl border border-gray-100 shadow-sm hover:shadow-md transition-all duration-300 flex flex-col">
        <div class="p-5 flex-1">
            <div class="flex items-center gap-3 mb-3 text-red-600">
                <div class="w-10 h-10 bg-red-50 rounded-lg flex items-center justify-center">
                    <i class="fas fa-file-invoice"></i>
                </div>
                <h2 class="text-xs font-bold text-gray-400 uppercase tracking-wider">Permohonan Tuntutan Institusi</h2>
            </div>
            <div class="flex items-baseline gap-2">
                <span class="text-3xl font-black text-gray-800">{{ $pendingAJK?? 0 }}</span>
                <span class="text-[10px] font-bold text-red-500 uppercase tracking-tighter">Menunggu</span>
            </div>
        </div>
        <a href="/admin/dashboard" class="bg-red-50 group-hover:bg-red-600 border-t border-red-100 p-2.5 flex items-center justify-center gap-2 rounded-b-xl transition-colors">
            <span class="text-[10px] font-bold uppercase text-red-700 group-hover:text-white">Semak Tuntutan</span>
            <i class="fas fa-arrow-right text-[10px] text-red-600 group-hover:text-white group-hover:translate-x-1 transition-all"></i>
        </a>
    </div>

    {{-- NEW Card 4: Permohonan Ahli Baru --}}
    <div class="group bg-white rounded-xl border border-gray-100 shadow-sm hover:shadow-md transition-all duration-300 flex flex-col">
        <div class="p-5 flex-1">
            <div class="flex items-center gap-3 mb-3 text-orange-600">
                <div class="w-10 h-10 bg-orange-50 rounded-lg flex items-center justify-center">
                    <i class="fas fa-user-plus"></i>
                </div>
                <h2 class="text-xs font-bold text-gray-400 uppercase tracking-wider">Permohonan Tuntutan Ahli Khairat</h2>
            </div>
            <div class="flex items-baseline gap-2">
                <span class="text-3xl font-black text-gray-800">{{ $pendingUser }}</span>
                <span class="text-[10px] font-bold text-orange-500 uppercase tracking-tighter">Permohonan Baru</span>
            </div>
        </div>
        <a href="/user-approval" class="bg-orange-50 group-hover:bg-orange-600 border-t border-orange-100 p-2.5 flex items-center justify-center gap-2 rounded-b-xl transition-colors">
            <span class="text-[10px] font-bold uppercase text-orange-700 group-hover:text-white">Sahkan Ahli</span>
            <i class="fas fa-arrow-right text-[10px] text-orange-600 group-hover:text-white group-hover:translate-x-1 transition-all"></i>
        </a>
    </div>

    {{-- NEW Card 5: Jumlah Wang Terkumpul --}}
    <div class="group bg-white rounded-xl border border-gray-100 shadow-sm hover:shadow-md transition-all duration-300 flex flex-col lg:col-span-2">
        <div class="p-5 flex-1">
            <div class="flex items-center gap-3 mb-3 text-emerald-600">
                <div class="w-10 h-10 bg-emerald-50 rounded-lg flex items-center justify-center">
                    <i class="fas fa-wallet"></i>
                </div>
                <h2 class="text-xs font-bold text-gray-400 uppercase tracking-wider">Tabung Wakalah</h2>
            </div>
            <div class="flex items-baseline gap-2">
                <span class="text-xs font-bold text-gray-400">RM</span>
                <span class="text-3xl font-black text-gray-800 leading-none">
                    {{ number_format($totalKhairatAmount ?? 0, 2) }}
                </span>
            </div>
        </div>
        <a href="/adminwallet" class="bg-emerald-50 group-hover:bg-emerald-600 border-t border-emerald-100 p-2.5 flex items-center justify-center gap-2 rounded-b-xl transition-colors">
            <span class="text-[10px] font-bold uppercase text-emerald-700 group-hover:text-white">Laporan Kewangan</span>
            <i class="fas fa-arrow-right text-[10px] text-emerald-600 group-hover:text-white group-hover:translate-x-1 transition-all"></i>
        </a>
    </div>

</div>

@endsection