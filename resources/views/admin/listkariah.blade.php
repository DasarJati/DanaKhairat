@extends ('layouts.admin')

@section('title', 'Senarai Ahli Khairat')

@section('content')

<div class="mb-8 flex flex-col md:flex-row md:items-center justify-between gap-4">
    <div>
        <h1 class="text-2xl font-bold text-gray-800 tracking-tight">Senarai Ahli Khairat</h1>
        <p class="text-sm text-gray-500 italic">Mengurus maklumat dan status keahlian kariah mengikut institusi.</p>
    </div>
    
    <form method="GET" action="{{ route('admin.listkariah') }}" class="flex flex-wrap md:flex-nowrap gap-3 items-end">
        <div class="w-full md:w-64">
            <label class="block text-[10px] font-bold uppercase tracking-wider text-gray-400 mb-1 ml-1">
                Tapis Institusi
            </label>
            <div class="relative">
                <select name="masjid_id" class="...">
                    <option value="">-- Semua Institusi --</option>

                    @foreach($institusi as $item)
                        <option value="{{ $item->id }}"
                            {{ request('masjid_id') == $item->id ? 'selected' : '' }}>
                            {{ $item->nama }}
                        </option>
                    @endforeach
                </select>
                <div class="absolute inset-y-0 right-0 flex items-center px-3 pointer-events-none text-gray-400 border-l border-gray-100 ml-2">
                    <i class="fas fa-chevron-down text-[10px]"></i>
                </div>
            </div>
        </div>

        <button type="submit"
                class="inline-flex items-center gap-2 bg-gray-900 text-white px-5 py-2.5 rounded-xl hover:bg-black transition-all shadow-lg shadow-gray-200 font-semibold text-sm">
            <i class="fas fa-filter text-xs text-gray-400"></i>
            Tapis
        </button>
        
        @if(request('masjid_id'))
            <a href="{{ route('admin.listkariah') }}" class="p-2.5 text-gray-400 hover:text-red-500 transition-colors" title="Reset">
                <i class="fas fa-undo-alt"></i>
            </a>
        @endif
    </form>
</div>

<div class="bg-white rounded-2xl border border-gray-100 shadow-xl shadow-gray-50 overflow-hidden transition-all">
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-100">
            <thead>
                <tr class="bg-gray-50/50">
                    <th class="px-6 py-4 text-left text-[11px] font-bold text-gray-400 uppercase tracking-widest">Maklumat Ahli</th>
                    <th class="px-6 py-4 text-left text-[11px] font-bold text-gray-400 uppercase tracking-widest">No. Kad Pengenalan</th>
                    <th class="px-6 py-4 text-left text-[11px] font-bold text-gray-400 uppercase tracking-widest">Masjid</th>
                    <th class="px-6 py-4 text-left text-[11px] font-bold text-gray-400 uppercase tracking-widest">Nombor Telefon</th>
                    <th class="px-6 py-4 text-center text-[11px] font-bold text-gray-400 uppercase tracking-widest">Status Keahlian</th>
                </tr>
            </thead>

            <tbody class="divide-y divide-gray-50">
                @forelse($kariah as $user)
                <tr class="hover:bg-blue-50/30 transition-colors group">
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-full bg-gradient-to-br from-gray-100 to-gray-200 flex items-center justify-center text-gray-500 font-bold text-sm shadow-inner group-hover:from-blue-100 group-hover:to-blue-200 group-hover:text-blue-600 transition-all">
                                {{ strtoupper(substr($user->nama, 0, 1)) }}
                            </div>
                            <div>
                                <div class="text-sm font-bold text-gray-800">{{ $user->nama }}</div>
                                <div class="text-[11px] text-gray-400 flex items-center gap-1">
                                    <i class="far fa-envelope text-[10px]"></i> {{ $user->email }}
                                </div>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 font-medium">
                        {{ $user->ic_number }}
                    </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                            {{ $user->masjid->nama ?? '-' }}
                        </td>

                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex flex-col">
                            <span class="text-sm text-gray-700 font-medium tracking-tight">
                                <i class="fas fa-phone-alt text-[10px] text-blue-400 mr-1"></i> {{ $user->telefon_bimbit }}
                            </span>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-center">
                        @if($user->status == 'active')
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-wide bg-emerald-50 text-emerald-600 border border-emerald-100">
                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                                Aktif
                            </span>
                        @else
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-wide bg-red-50 text-red-600 border border-red-100">
                                <span class="w-1.5 h-1.5 rounded-full bg-red-500"></span>
                                Tidak Aktif
                            </span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-6 py-16 text-center">
                        <div class="flex flex-col items-center justify-center">
                            <div class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center mb-4">
                                <i class="fas fa-search text-gray-200 text-2xl"></i>
                            </div>
                            <p class="text-gray-400 font-medium">Tiada data kariah dijumpai untuk institusi ini.</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="mt-8 px-2">
    {{ $kariah->withQueryString()->links() }}
</div>

<style>
    /* Customizing Laravel's default pagination to look cleaner */
    .pagination { @apply flex gap-2; }
    nav[role="navigation"] svg { width: 1.25rem; height: 1.25rem; }
</style>

@endsection