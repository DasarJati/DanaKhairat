@extends('layouts.app')
@section('title', 'Tukar Ketua Keluarga')
@section('content')

    <div class="max-w-8xl mx-auto px-4 sm:px-6 lg:px-8 py-8 animate-fade-in">
        <!-- Header Section -->
        <header class="mb-8 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>

                <h1 class="text-2xl font-bold tracking-tight text-slate-900 sm:text-3xl">Tukar Ketua Keluarga</h1>
            </div>
        </header>

        <!-- Alert Messages -->
        @if (session('success'))
            <div class="mb-6 rounded-xl bg-emerald-50 border border-emerald-200 p-4 shadow-sm flex items-start gap-3">
                <div class="flex-shrink-0 p-0.5 text-emerald-600">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div>
                    <p class="text-sm font-medium text-emerald-800">{{ session('success') }}</p>
                </div>
            </div>
        @endif

        @if (session('error'))
            <div class="mb-6 rounded-xl bg-rose-50 border border-rose-200 p-4 shadow-sm flex items-start gap-3">
                <div class="flex-shrink-0 p-0.5 text-rose-600">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div>
                    <p class="text-sm font-medium text-rose-800">{{ session('error') }}</p>
                </div>
            </div>
        @endif

        <!-- Stats Dashboard Grid -->
        <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-3 mb-8">
            <!-- Stat Card 1 -->
            <div class="bg-white p-6 rounded-xl border border-slate-200 shadow-sm flex items-center justify-between">
                <div>
                    <span class="text-sm uppercase font-bold text-slate-500">Jumlah Ketua Keluarga</span>
                    <h3 class="text-3xl font-bold text-slate-900 mt-1 tracking-tight">{{ $ketuaList->count() }}</h3>
                </div>
                <div class="bg-blue-50 text-blue-600 p-3 rounded-lg">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                </div>
            </div>

            <!-- Stat Card 2 -->
            <div class="bg-white p-6 rounded-xl border border-slate-200 shadow-sm flex items-center justify-between">
                <div>
                    <span class="text-sm uppercase font-bold text-slate-500">Jumlah Tanggungan</span>
                    <h3 class="text-3xl font-bold text-slate-900 mt-1 tracking-tight">
                        {{ $ketuaList->sum(fn($k) => $k->tanggungan->count()) }}</h3>
                </div>
                <div class="bg-indigo-50 text-indigo-600 p-3 rounded-lg">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                </div>
            </div>

            <!-- Stat Card 3 -->
            <div class="bg-white p-6 rounded-xl border border-slate-200 shadow-sm flex items-center justify-between">
                <div>
                    <span class="text-sm uppercase font-bold text-slate-500">Jumlah Ketua Aktif</span>
                    <h3 class="text-3xl font-bold text-slate-900 mt-1 tracking-tight">
                        {{ $ketuaList->where('status', 'active')->count() }}</h3>
                </div>
                <div class="bg-emerald-50 text-emerald-600 p-3 rounded-lg">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>
        </div>

        <!-- Section 1: Filter/Search Panel Container -->
        <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-5 mb-6">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <div>
                    <h2 class="text-base font-semibold text-slate-900">Carian & Tapisan</h2>
                    <p class="text-xs text-slate-500 mt-0.5">Gunakan ruangan di bawah untuk mencari ketua keluarga tertentu
                        secara pantas.</p>
                </div>
                <div class="relative w-full md:w-80">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>
                    <input type="text" id="searchInput" placeholder="Nama, No. IC, atau No. Telefon..."
                        class="w-full pl-9 pr-4 py-2 border border-slate-200 rounded-lg text-sm bg-slate-50/50 placeholder-slate-400 text-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 focus:bg-white transition-all">
                </div>
            </div>
        </div>

        <!-- Section 2: Main Data Table Container -->
        <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">


            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr
                            class="bg-slate-50 border-b border-slate-200 text-xs font-semibold text-slate-600 uppercase tracking-wider">
                            <th class="px-6 py-3.5 w-12 text-center">No.</th>
                            <th class="px-6 py-3.5">Maklumat Ketua</th>
                            <th class="px-6 py-3.5">Tanggungan</th>
                            <th class="px-6 py-3.5 w-32">Status</th>
                            <th class="px-6 py-3.5 text-right w-44">Tindakan</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200 text-slate-700 bg-white" id="ketuaTableBody">
                        @forelse($ketuaList as $index => $ketua)
                            <tr class="hover:bg-slate-50/60 transition-colors">
                                <td class="px-6 py-4 text-center text-sm font-medium text-slate-400">{{ $index + 1 }}
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div
                                            class="flex-shrink-0 h-10 w-10 bg-slate-900 text-white rounded-lg flex items-center justify-center font-semibold text-sm tracking-wider shadow-sm">
                                            {{ collect(explode(' ', $ketua->nama))->map(fn($n) => strtoupper(substr($n, 0, 1)))->take(2)->implode('') }}
                                        </div>
                                        <div>
                                            <h4 class="text-sm  font-semibold uppercase text-slate-900 leading-tight">
                                                {{ $ketua->nama }}</h4>
                                            <p class="text-xs text-slate-500 mt-1 font-mono tracking-tight">
                                                {{ $ketua->ic }} <span class="text-slate-300 mx-1">•</span>
                                                {{ $ketua->notel }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex flex-wrap items-center gap-1.5">
                                        <span
                                            class="inline-flex items-center px-2 py-0.5 rounded-md text-xs font-semibold bg-blue-50 text-blue-700 border border-blue-100">
                                            {{ $ketua->tanggungan->where('hubungan', 'ISTERI')->count() }}
                                            
                                        </span>
                                        
                                        @foreach ($ketua->tanggungan->take(1) as $t)
                                            <span
                                                class="inline-flex items-center px-2 py-0.5 rounded-md text-xs font-medium bg-slate-100 text-slate-600 max-w-[120px] truncate">
                                                {{ $t->nama }}
                                            </span>
                                        @endforeach
                                        @if ($ketua->tanggungan->count() > 1)
                                            <span
                                                class="inline-flex items-center px-2 py-0.5 rounded-md text-xs font-medium bg-slate-100 text-slate-500">
                                                +{{ $ketua->tanggungan->count() - 1 }} lagi
                                            </span>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-6 py-4 uppercase whitespace-nowrap">
                                    @if ($ketua->status === 'active')
                                        <span
                                            class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-emerald-500/10 text-emerald-700 border border-emerald-500/10">
                                            <span class="h-1.5 w-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                                            Aktif
                                        </span>
                                    @else
                                        <span
                                            class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-slate-100 text-slate-600 border border-slate-200">
                                            <span class="h-1.5 w-1.5 rounded-full bg-slate-400"></span>
                                            Tidak Aktif
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right">
                                    @if ($ketua->tanggungan->whereIn('hubungan', ['ISTERI', 'SUAMI'])->whereIn('status', 'active')->count() > 0)
                                        <a href="{{ route('change-ketua.form', $ketua->id) }}"
                                            class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-white hover:bg-slate-50 text-slate-800 text-xs font-semibold rounded-lg border border-slate-300 shadow-sm transition-all active:scale-[0.98]">
                                            <svg class="w-3.5 h-3.5 text-slate-500" fill="none" stroke="currentColor"
                                                stroke-width="2" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" />
                                            </svg>
                                            Tukar Ketua
                                        </a>
                                    @else
                                        <button disabled
                                            class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-slate-50 text-slate-400 text-xs font-medium rounded-lg border border-slate-200 cursor-not-allowed">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor"
                                                stroke-width="2" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                            </svg>
                                            Tiada Pasangan
                                        </button>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-16 text-center">
                                    <div class="flex flex-col items-center justify-center max-w-sm mx-auto">
                                        <div class="p-3 bg-slate-100 rounded-full text-slate-400 mb-3">
                                            <svg class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="2"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                            </svg>
                                        </div>
                                        <h3 class="text-sm font-semibold text-slate-900">Tiada Data Ditemui</h3>
                                        <p class="mt-1 text-xs text-slate-500">Tiada rekod data ketua keluarga tersimpan
                                            dalam pangkalan data pada masa ini.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script>
        // Search handler logic
        document.getElementById('searchInput')?.addEventListener('keyup', function() {
            const searchTerm = this.value.toLowerCase();
            const rows = document.querySelectorAll('#ketuaTableBody tr');

            rows.forEach(row => {
                if (row.querySelector('td[colspan]')) return;

                const text = row.textContent.toLowerCase();
                row.style.display = text.includes(searchTerm) ? '' : 'none';
            });
        });
    </script>
@endsection
