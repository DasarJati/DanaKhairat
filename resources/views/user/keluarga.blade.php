@extends('layouts.user')

@section('title', 'Ahli Khairat')

@section('content')



    <div class="space-y-8">

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="bg-white rounded-3xl p-6 shadow-sm border border-gray-100 flex items-center gap-5">
                <div class="p-4 bg-blue-50 rounded-2xl">
                    <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-500 uppercase tracking-wider">Jumlah Tanggungan</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $jumlahAhli }} Orang</p>
                </div>
            </div>

            <div class="bg-white rounded-3xl p-6 shadow-sm border border-gray-100 flex items-center gap-5">
                <div class="p-4 bg-amber-50 rounded-2xl">
                    <svg class="w-8 h-8 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-500 uppercase tracking-wider">Rekod Kematian</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $jumlahTuntutan }}</p>
                </div>
            </div>
        </div>

        <div class="mb-10">
            <div class="flex justify-between items-center mb-8">
                <h2 class="text-2xl font-extrabold text-gray-800 tracking-tight">Profil Ahli Keluarga</h2>
                <div class="h-1 w-20 bg-gold-500 rounded-full mt-2 hidden md:block"></div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">


                <!-- Ketua Card -->
                <div
                    class="group relative rounded-3xl border {{ $ketuaHasClaim ? 'border-gray-300 bg-gray-100' : 'border-blue-100 bg-white' }} shadow-xl overflow-hidden transition-all duration-300 hover:-translate-y-2">

                    <div class="absolute top-0 left-0 w-full h-2 {{ $ketuaHasClaim ? 'bg-gray-400' : 'bg-blue-500' }}">
                    </div>

                    <div class="p-6">
                        <div class="flex items-center gap-4 mb-6">
                            <div
                                class="h-16 w-16 rounded-2xl flex items-center justify-center text-white shadow-lg ring-4 
                {{ $ketuaHasClaim ? 'bg-gradient-to-br from-gray-400 to-gray-600 ring-gray-200' : 'bg-gradient-to-br from-blue-500 to-indigo-600 ring-blue-50' }}">
                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                            </div>
                            <div>
                                <h3
                                    class="font-bold {{ $ketuaHasClaim ? 'text-gray-500' : 'text-gray-900' }} uppercase text-sm">
                                    {{ $user->nama }}
                                </h3>
                                <span
                                    class="inline-block mt-1 px-3 py-1 {{ $ketuaHasClaim ? 'bg-gray-200 text-gray-600' : 'bg-blue-100 text-blue-700' }} text-[10px] font-bold rounded-full uppercase">
                                    Ketua Keluarga
                                </span>
                                @if ($ketuaHasClaim)
                                    <span
                                        class="inline-block mt-1 ml-2 px-2 py-0.5 bg-red-100 text-red-600 text-[9px] font-bold rounded-full">
                                        Direkodkan
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="space-y-4">
                            <div class="flex justify-between text-xs">
                                <span class="text-gray-400 uppercase">No. MyKad</span>
                                <span class="font-bold {{ $ketuaHasClaim ? 'text-gray-500' : 'text-gray-900' }}">
                                    {{ $user->ic_number ?? '-' }}
                                </span>
                            </div>

                            <div class="flex justify-between text-xs border-t pt-2">
                                <span class="text-gray-400 uppercase">Umur</span>
                                <span class="font-bold {{ $ketuaHasClaim ? 'text-gray-500' : 'text-gray-900' }}">
                                    {{ $umurKetua }} tahun
                                </span>
                            </div>

                            @if ($ketuaHasClaim && $ketuaHasClaim->status === 'SUCCESS')
                                <div class="p-4 rounded-2xl bg-green-50 border border-green-200">
                                    <div class="flex justify-between mb-1">
                                        <span class="text-[10px] font-bold text-green-600 uppercase">Status</span>
                                        <span
                                            class="px-2 py-0.5 bg-green-200 text-green-800 text-[9px] font-black rounded-md">
                                            SELESAI
                                        </span>
                                    </div>
                                    <p class="text-sm font-extrabold text-gray-800">Pengurusan Jenazah Selesai</p>
                                </div>
                            @elseif ($ketuaHasClaim && $ketuaHasClaim->status === 'PROCESSING')
                                <div class="p-4 rounded-2xl bg-yellow-50 border border-yellow-200">
                                    <div class="flex justify-between mb-1">
                                        <span class="text-[10px] font-bold text-yellow-600 uppercase">Status</span>
                                        <span
                                            class="px-2 py-0.5 bg-yellow-200 text-yellow-800 text-[9px] font-black rounded-md">
                                            DALAM PROSES
                                        </span>
                                    </div>
                                    <p class="text-sm font-extrabold text-gray-800">Masih Dalam Pengurusan Jenazah</p>
                                </div>
                            @else
                                <div class="p-4 rounded-2xl bg-emerald-50 border border-emerald-100">
                                    <div class="flex justify-between mb-1">
                                        <span class="text-[10px] font-bold text-emerald-600 uppercase">Sah Sehingga</span>
                                        <span
                                            class="px-2 py-0.5 bg-emerald-200 text-emerald-800 text-[9px] font-black rounded-md">
                                            {{ $user->status == 'active' ? 'AKTIF' : 'TIDAK AKTIF' }}
                                        </span>
                                    </div>
                                    <p class="text-sm font-extrabold text-gray-800">
                                        {{ $user->subscriptions->first()->end_date ? \Carbon\Carbon::parse($user->subscriptions->first()->end_date)->translatedFormat('d M Y') : 'Tiada Rekod' }}
                                    </p>
                                </div>
                            @endif
                        </div>

                        @if (!$ketuaHasClaim)
                            <button
                                onclick="openDeathModal('{{ $user->id }}', '{{ $user->nama }}', '{{ $user->ic_number }}', 'KETUA KELUARGA')"
                                class="mt-6 w-full py-3 rounded-2xl bg-blue-500 text-white text-xs font-bold uppercase tracking-widest text-center inline-block hover:bg-blue-800 transition-colors">
                                Laporkan Kematian
                            </button>
                        @else
                            <div
                                class="mt-6 w-full py-3 rounded-2xl bg-gray-300 text-gray-500 text-xs font-bold uppercase tracking-widest text-center cursor-not-allowed">
                                Telah Direkodkan
                            </div>
                        @endif
                    </div>
                </div>

                {{-- ===== OTHER FAMILY AHLI (e.g. old ketua) ===== --}}
                @foreach ($familyAhli as $fa)
                    <div
                        class="group relative rounded-3xl border {{ $fa->hasClaim ? 'border-gray-300 bg-gray-100' : 'border-blue-100 bg-white' }} shadow-xl overflow-hidden transition-all duration-300 hover:-translate-y-2">

                        <div class="absolute top-0 left-0 w-full h-2 {{ $fa->hasClaim ? 'bg-gray-400' : 'bg-blue-400' }}">
                        </div>

                        <div class="p-6">
                            <div class="flex items-center gap-4 mb-6">
                                <div
                                    class="h-16 w-16 rounded-2xl flex items-center justify-center text-white shadow-lg ring-4 bg-gradient-to-br from-blue-400 to-blue-600 ring-blue-50">
                                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                    </svg>
                                </div>
                                <div>
                                    <h3
                                        class="font-bold {{ $fa->hasClaim ? 'text-gray-500' : 'text-gray-900' }} uppercase text-sm">
                                        {{ $fa->nama }}
                                    </h3>
                                    <span
                                        class="inline-block mt-1 px-3 py-1 bg-blue-100 text-blue-700 text-[10px] font-bold rounded-full uppercase">
                                        Ahli Keluarga
                                    </span>
                                </div>
                            </div>

                            <div class="space-y-4">
                                <div class="flex justify-between text-xs">
                                    <span class="text-gray-400 uppercase">No. MyKad</span>
                                    <span
                                        class="font-bold {{ $fa->hasClaim ? 'text-gray-500' : 'text-gray-900' }}">{{ $fa->ic ?? '-' }}</span>
                                </div>
                                <div class="flex justify-between text-xs border-t pt-2">
                                    <span class="text-gray-400 uppercase">Umur</span>
                                    <span class="font-bold">{{ $fa->umur }} tahun</span>
                                </div>

                                @if ($fa->tuntutanInfo?->status === 'SUCCESS')
                                    <div class="p-4 rounded-2xl bg-green-50 border border-green-200">
                                        <div class="flex justify-between mb-1">
                                            <span class="text-[10px] font-bold text-green-600 uppercase">Status</span>
                                            <span
                                                class="px-2 py-0.5 bg-green-200 text-green-800 text-[9px] font-black rounded-md">SELESAI</span>
                                        </div>
                                        <p class="text-sm font-extrabold text-gray-800">Pengurusan Jenazah Selesai</p>
                                    </div>
                                @elseif ($fa->tuntutanInfo?->status === 'PROCESSING')
                                    <div class="p-4 rounded-2xl bg-yellow-50 border border-yellow-200">
                                        <div class="flex justify-between mb-1">
                                            <span class="text-[10px] font-bold text-yellow-600 uppercase">Status</span>
                                            <span
                                                class="px-2 py-0.5 bg-yellow-200 text-yellow-800 text-[9px] font-black rounded-md">DALAM
                                                PROSES</span>
                                        </div>
                                        <p class="text-sm font-extrabold text-gray-800">Masih Dalam Pengurusan Jenazah</p>
                                    </div>
                                @else
                                    <div class="p-4 rounded-2xl bg-emerald-50 border border-emerald-100">
                                        <div class="flex justify-between mb-1">
                                            <span class="text-[10px] font-bold text-emerald-600 uppercase">Sah
                                                Sehingga</span>
                                            <span
                                                class="px-2 py-0.5 bg-emerald-200 text-emerald-800 text-[9px] font-black rounded-md">
                                                {{ $fa->status === 'active' ? 'AKTIF' : 'TIDAK AKTIF' }}
                                            </span>
                                        </div>
                                        <p class="text-sm font-extrabold text-gray-800">
                                            {{ $user->subscriptions->first()?->end_date
                                                ? \Carbon\Carbon::parse($user->subscriptions->first()->end_date)->translatedFormat('d M Y')
                                                : 'Tiada Rekod' }}
                                        </p>
                                    </div>
                                @endif
                            </div>

                            @if (!$fa->hasClaim)
                                <button
                                    onclick="openDeathModal('{{ $fa->id }}', '{{ addslashes($fa->nama) }}', '{{ $fa->ic }}', 'AHLI KELUARGA')"
                                    class="mt-6 w-full py-3 rounded-2xl bg-blue-500 text-white text-xs font-bold uppercase tracking-widest text-center hover:bg-blue-800 transition-colors">
                                    Laporkan Kematian
                                </button>
                            @else
                                <div
                                    class="mt-6 w-full py-3 rounded-2xl bg-gray-300 text-gray-500 text-xs font-bold text-center cursor-not-allowed">
                                    Telah Direkodkan
                                </div>
                            @endif
                        </div>
                    </div>
                @endforeach

                @foreach ($tanggungan as $t)
                    @php
                        $hasClaim = in_array($t->id, $tanggunganIdexits);
                    @endphp

                    <div
                        class="group relative rounded-3xl border {{ $hasClaim ? 'border-gray-300 bg-gray-100' : 'border-gray-100 bg-white' }} shadow-lg overflow-hidden transition-all duration-300 hover:-translate-y-2">

                        <div
                            class="absolute top-0 left-0 w-full h-2 {{ $hasClaim ? 'bg-gray-400' : ($t->layak ? 'bg-emerald-400' : 'bg-red-400') }}">
                        </div>

                        <div class="p-6">
                            <div class="flex items-center gap-4 mb-6">
                                <div
                                    class="h-16 w-16 bg-gradient-to-br rounded-2xl flex items-center justify-center text-white shadow-lg ring-4 
                    {{ $hasClaim ? 'from-gray-400 to-gray-600 ring-gray-200' : '' }}
                    @if (!$hasClaim) @if ($t->hubungan === 'ISTERI') from-pink-400 to-pink-600
                        @elseif($t->hubungan === 'SUAMI') from-blue-400 to-blue-600
                        @elseif($t->hubungan === 'ANAK') from-green-400 to-emerald-600
                        @elseif($t->hubungan === 'IBU') from-purple-400 to-purple-600
                        @elseif($t->hubungan === 'AYAH') from-indigo-400 to-indigo-600
                        @else from-gray-400 to-gray-600 @endif
                    @endif">

                                    @if ($hasClaim)
                                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                        </svg>
                                    @else
                                        @if ($t->hubungan === 'ISTERI')
                                            <svg class="w-8 h-8" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                                <circle cx="12" cy="7" r="2" fill="currentColor" />
                                            </svg>
                                        @elseif($t->hubungan === 'SUAMI')
                                            <svg class="w-8 h-8" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                            </svg>
                                        @elseif($t->hubungan === 'ANAK')
                                            <svg class="w-8 h-8" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                                            </svg>
                                        @elseif($t->hubungan === 'IBU')
                                            <svg class="w-8 h-8" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                                            </svg>
                                        @elseif($t->hubungan === 'AYAH')
                                            <svg class="w-8 h-8" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.66 0 3-4 3-9s-1.34-9-3-9m0 18c-1.66 0-3-4-3-9s1.34-9 3-9" />
                                            </svg>
                                        @endif
                                    @endif
                                </div>

                                <div>
                                    <h3
                                        class="font-bold {{ $hasClaim ? 'text-gray-500' : 'text-gray-900' }} uppercase text-sm">
                                        {{ $t->nama }}</h3>
                                    <span
                                        class="px-2 py-0.5 {{ $hasClaim ? 'bg-gray-200 text-gray-600' : 'bg-gray-100 text-gray-700' }} text-[10px] font-bold rounded-md uppercase">
                                        {{ $t->hubungan }}
                                    </span>
                                    @if ($hasClaim)
                                        <span
                                            class="inline-block ml-2 px-2 py-0.5 bg-red-100 text-red-600 text-[9px] font-bold rounded-full">
                                            Meninggal
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="space-y-3">
                                <div class="flex justify-between text-xs">
                                    {{-- <span
                                        class="{{ in_array($t->id, $tanggunganIdexits) ? 'text-red-600' : 'text-green-600' }} font-bold">
                                        {{ $t->id }} {{ $t->tuntutanInfo }}
                                    </span> --}}
                                    <span class="text-gray-400 uppercase">No. MyKad</span>
                                    <span
                                        class="font-bold {{ $hasClaim ? 'text-gray-500' : 'text-gray-900' }}">{{ $t->ic_number }}</span>
                                </div>
                                <div class="flex justify-between text-xs border-t pt-2">
                                    <span class="text-gray-400 uppercase">Umur</span>
                                    <span
                                        class="font-bold {{ $hasClaim ? 'text-gray-500' : 'text-gray-900' }}">{{ $t->umur }}
                                        Tahun</span>
                                </div>

                                @if ($t->tuntutanInfo && $t->tuntutanInfo->status === 'SUCCESS')
                                    <div class="p-4 rounded-2xl bg-green-50 border border-green-200">
                                        <div class="flex justify-between mb-1">
                                            <span class="text-[10px] font-bold text-green-600 uppercase">Status
                                            </span>
                                            <span
                                                class="px-2 py-0.5 bg-green-200 text-green-800 text-[9px] font-black rounded-md">
                                                SELESAI
                                            </span>
                                        </div>
                                        <p class="text-sm font-extrabold text-gray-800">Pengurusan Jenazah Selesai</p>
                                    </div>
                                @elseif ($t->tuntutanInfo && $t->tuntutanInfo->status === 'PROCESSING')
                                    <div class="p-4 rounded-2xl bg-yellow-50 border border-yellow-200">
                                        <div class="flex justify-between mb-1">
                                            <span class="text-[10px] font-bold text-yellow-600 uppercase">Status
                                            </span>
                                            <span
                                                class="px-2 py-0.5 bg-yellow-200 text-yellow-800 text-[9px] font-black rounded-md">
                                                DALAM PROSES
                                            </span>
                                        </div>
                                        <p class="text-sm font-extrabold text-gray-800">Masih Dalam Pengurusan Jenazah</p>
                                    </div>
                                @else
                                    <div class="p-4 rounded-2xl bg-emerald-50 border border-emerald-100">
                                        <div class="flex justify-between mb-1">
                                            <span class="text-[10px] font-bold text-emerald-600 uppercase">Sah
                                                Sehingga </span>
                                            <span
                                                class="px-2 py-0.5 bg-emerald-200 text-emerald-800 text-[9px] font-black rounded-md">
                                                {{ $user->status == 'active' ? 'AKTIF' : 'TIDAK AKTIF' }}
                                            </span>
                                        </div>
                                        <p class="text-sm font-extrabold text-gray-800">
                                            {{ $user->subscriptions->first()->end_date ? \Carbon\Carbon::parse($user->subscriptions->first()->end_date)->translatedFormat('d M Y') : 'Tiada Rekod' }}
                                        </p>
                                    </div>
                                @endif
                            </div>

                            @if ($t->bolehMohon && !$hasClaim)
                                <button
                                    onclick="openDeathModal('{{ $t->id }}', '{{ $t->nama }}', '{{ $t->ic_number }}', '{{ $t->hubungan }}')"
                                    class="mt-6 w-full py-3 rounded-2xl bg-blue-500 text-white text-xs font-bold uppercase tracking-widest text-center inline-block hover:bg-blue-800 transition-colors">
                                    Laporkan Kematian
                                </button>
                            @else
                                <div
                                    class="mt-6 w-full py-3 rounded-2xl {{ $hasClaim ? 'bg-gray-300 text-gray-500' : 'bg-gray-200 text-gray-600' }} text-xs font-bold text-center cursor-not-allowed">
                                    {{ $hasClaim ? 'Telah Direkodkan' : 'Kematian Telah Direkodkan' }}
                                </div>
                            @endif
                        </div>
                    </div>
                @endforeach

            </div>
        </div>
    </div>

@endsection

<!-- Modal -->
<div id="deathModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/50 backdrop-blur-sm p-4">

    <div class="bg-white w-full max-w-2xl rounded-3xl shadow-2xl overflow-hidden">

        <div class="px-6 py-5 border-b bg-gradient-to-r from-red-500 to-red-600 text-white">
            <div class="flex justify-between items-center">
                <div>
                    <h2 class="text-xl font-bold">Laporan Kematian</h2>
                    <p class="text-sm text-red-100">Sila lengkapkan maklumat berikut</p>
                </div>

                <button type="button" onclick="closeDeathModal()"
                    class="h-10 w-10 rounded-full bg-white/20 hover:bg-white/30 flex items-center justify-center transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>

        <form action="{{ route('tuntutan.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <input type="hidden" name="ahli_id" id="modal_ahli_id">

            <!-- Form Body -->
            <div class="p-6 md:p-8 space-y-6 max-h-[75vh] overflow-y-auto">

                <!-- Section: Maklumat Si Mati -->
                <div>
                    <h3 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-4 flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                        Maklumat Si Mati
                    </h3>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-semibold text-gray-600 uppercase mb-1.5">Nama
                                Penuh</label>
                            <input type="text" id="modal_nama"
                                class="w-full rounded-xl border border-gray-200 bg-gray-50 p-3 text-sm font-medium text-gray-700 cursor-not-allowed"
                                readonly>
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-gray-600 uppercase mb-1.5">No. Kad
                                Pengenalan</label>
                            <input type="text" id="modal_ic"
                                class="w-full rounded-xl border border-gray-200 bg-gray-50 p-3 text-sm font-medium text-gray-700 cursor-not-allowed"
                                readonly>
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-gray-600 uppercase mb-1.5">Hubungan</label>
                            <input type="text" id="modal_hubungan"
                                class="w-full rounded-xl border border-gray-200 bg-gray-50 p-3 text-sm font-medium text-gray-700 cursor-not-allowed"
                                readonly>
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-gray-600 uppercase mb-1.5">
                                Tarikh Kematian <span class="text-red-500">*</span>
                            </label>
                            <input type="date" name="tarikh_kematian"
                                class="w-full rounded-xl border border-gray-200 bg-white p-3 text-sm font-medium text-gray-800 focus:ring-4 focus:ring-red-500/10 focus:border-red-500 transition duration-200"
                                required>
                        </div>
                    </div>
                </div>

                <hr class="border-gray-100">

                <!-- Section: Maklumat Kematian -->
                <div>
                    <h3 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-4 flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        Maklumat Kematian
                    </h3>
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 uppercase mb-1.5">Catatan / Sebab
                            Kematian</label>
                        <textarea name="sebab_kematian" rows="3"
                            class="w-full rounded-xl border border-gray-200 bg-white p-3 text-sm font-medium text-gray-800 placeholder-gray-400 focus:ring-4 focus:ring-red-500/10 focus:border-red-500 transition duration-200"
                            placeholder="Contoh: Sakit tua, Kemalangan jalan raya, Kemalangan di tempat kerja, dll."></textarea>
                    </div>
                </div>

                <hr class="border-gray-100">

                <!-- Section: Dokumen Sokongan -->
                <div>
                    <h3 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-4 flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        Dokumen Sokongan
                    </h3>
                    <div class="grid grid-cols-1 gap-5">
                        <div class="bg-red-50 rounded-xl p-4 border border-red-100">
                            <label class="block text-xs font-semibold text-red-600 uppercase mb-2">
                                📄 Sijil Kematian <span class="text-red-500">*</span>
                            </label>
                            <input type="file" name="sijil_kematian"
                                class="block w-full text-sm text-gray-500 file:mr-4 file:py-2.5 file:px-5 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-red-100 file:text-red-700 hover:file:bg-red-200 transition duration-200 border border-red-200 rounded-xl p-2 bg-white"
                                accept=".pdf,.jpg,.jpeg,.png" required>
                            <p class="text-[10px] text-red-400 mt-1">Format: PDF, JPG, PNG (Max 2MB)</p>
                        </div>

                        <div class="bg-blue-50 rounded-xl p-4 border border-blue-100">
                            <label class="block text-xs font-semibold text-blue-600 uppercase mb-2">
                                📄 Laporan Polis
                            </label>
                            <input type="file" name="ic_pewaris"
                                class="block w-full text-sm text-gray-500 file:mr-4 file:py-2.5 file:px-5 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-blue-100 file:text-blue-700 hover:file:bg-blue-200 transition duration-200 border border-blue-200 rounded-xl p-2 bg-white"
                                accept=".pdf,.jpg,.jpeg,.png">
                            <p class="text-[10px] text-blue-400 mt-1">Format: PDF, JPG, PNG (Max 2MB)</p>
                        </div>

                        <div class="bg-purple-50 rounded-xl p-4 border border-purple-100">
                            <label class="block text-xs font-semibold text-purple-600 uppercase mb-2">
                                📄 Dokumen Lain (Sokongan)
                            </label>
                            <input type="file" name="bukti_bank"
                                class="block w-full text-sm text-gray-500 file:mr-4 file:py-2.5 file:px-5 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-purple-100 file:text-purple-700 hover:file:bg-purple-200 transition duration-200 border border-purple-200 rounded-xl p-2 bg-white"
                                accept=".pdf,.jpg,.jpeg,.png">
                            <p class="text-[10px] text-purple-400 mt-1">Format: PDF, JPG, PNG (Max 2MB)</p>
                        </div>
                    </div>
                </div>

            </div>

            <!-- Form Footer Buttons -->
            <div
                class="px-6 py-4 border-t border-gray-100 bg-gray-50 flex flex-col-reverse sm:flex-row justify-end gap-3">
                <button type="button" onclick="closeDeathModal()"
                    class="w-full sm:w-auto px-5 py-3 rounded-xl bg-gray-200 hover:bg-gray-300 text-gray-700 text-sm font-semibold transition duration-200">
                    Batal
                </button>

                <button type="submit"
                    class="w-full sm:w-auto px-6 py-3 rounded-xl bg-red-500 hover:bg-red-600 text-white text-sm font-semibold shadow-md shadow-red-500/20 transition duration-200 flex items-center justify-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    Hantar Laporan
                </button>
            </div>
        </form>

    </div>

</div>

<script>
    function openDeathModal(id, nama, ic, hubungan) {
        document.getElementById('modal_ahli_id').value = id;
        document.getElementById('modal_nama').value = nama;
        document.getElementById('modal_ic').value = ic;
        document.getElementById('modal_hubungan').value = hubungan;

        // Clear form fields
        document.querySelector('input[name="tarikh_kematian"]').value = '';
        document.querySelector('textarea[name="sebab_kematian"]').value = '';
        document.querySelector('input[name="sijil_kematian"]').value = '';
        document.querySelector('input[name="ic_pewaris"]').value = '';
        document.querySelector('input[name="bukti_bank"]').value = '';

        document.getElementById('deathModal').classList.remove('hidden');
        document.getElementById('deathModal').classList.add('flex');
        // Prevent body scroll
        document.body.style.overflow = 'hidden';
    }

    function closeDeathModal() {
        document.getElementById('deathModal').classList.add('hidden');
        document.getElementById('deathModal').classList.remove('flex');
        // Restore body scroll
        document.body.style.overflow = 'auto';
    }

    // Close modal when clicking outside
    document.getElementById('deathModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeDeathModal();
        }
    });
</script>
