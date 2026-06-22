@extends ('layouts.admin')

@section('title', 'Admin Dashboard - e-Khairat')

@section('content')
<div class="transition-all duration-500 ease-in-out">
    <div class="p-4 md:p-8">
        <div class="max-w-7xl mx-auto">
            
            <div class="flex flex-col md:flex-row md:items-center justify-between mb-10 gap-4">
                <div>
                    <h2 class="text-3xl font-extrabold text-gray-900 tracking-tight">Senarai Permohonan Institusi</h2>
                    <p class="text-gray-500 mt-1">Uruskan pendaftaran masjid dan AJK yang menunggu kelulusan.</p>
                </div>
                
                <div class="flex items-center gap-3">
                    <form method="GET" id="filterForm" class="flex items-center gap-2">
                        <select name="status" id="statusFilter" 
                                class="border-gray-200 rounded-xl px-4 py-2.5 text-sm font-semibold shadow-sm focus:ring-2 focus:ring-emerald-500 transition-all
                                @if(request('status') === '0') bg-yellow-50 text-yellow-700 border-yellow-200
                                @elseif(request('status') === '1') bg-green-50 text-green-700 border-green-200
                                @elseif(request('status') === '2') bg-red-50 text-red-700 border-red-200
                                @else bg-white text-gray-700 border-gray-200 @endif">
                            <option value="">Semua Status</option>
                            <option value="0" {{ request('status') === '0' ? 'selected' : '' }}>Pending</option>
                            <option value="1" {{ request('status') === '1' ? 'selected' : '' }}>Approved</option>
                            <option value="2" {{ request('status') === '2' ? 'selected' : '' }}>Rejected</option>
                        </select>

                        @if(request('status'))
                        <a href="{{ route('admin.dashboard') }}" class="p-2.5 bg-gray-100 text-gray-500 rounded-xl hover:bg-gray-200 transition">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        </a>
                        @endif
                    </form>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-10">
                <div class="bg-white p-6 rounded-3xl shadow-sm border border-gray-100 relative overflow-hidden group">
                    <div class="absolute right-0 top-0 p-3 opacity-10 group-hover:scale-110 transition-transform">
                        <svg class="w-16 h-16 text-gray-900" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"/></svg>
                    </div>
                    <p class="text-gray-500 text-xs font-bold uppercase tracking-wider">Jumlah Berdaftar</p>
                    <p class="text-3xl font-black text-gray-900 mt-2">{{ $total }}</p>
                </div>

                <div class="bg-white p-6 rounded-3xl shadow-sm border border-gray-100 border-l-4 border-l-yellow-400">
                    <p class="text-yellow-600 text-xs font-bold uppercase tracking-wider">Menunggu (Pending)</p>
                    <p class="text-3xl font-black text-gray-900 mt-2">{{ $pending }}</p>
                </div>

                <div class="bg-white p-6 rounded-3xl shadow-sm border border-gray-100 border-l-4 border-l-emerald-400">
                    <p class="text-emerald-600 text-xs font-bold uppercase tracking-wider">Diluluskan (Approved)</p>
                    <p class="text-3xl font-black text-gray-900 mt-2">{{ $approved }}</p>
                </div>

                <div class="bg-white p-6 rounded-3xl shadow-sm border border-gray-100 border-l-4 border-l-red-400">
                    <p class="text-red-600 text-xs font-bold uppercase tracking-wider">Ditolak (Rejected)</p>
                    <p class="text-3xl font-black text-gray-900 mt-2">{{ $rejected }}</p>
                </div>
            </div>

            <div class="bg-white rounded-[2rem] shadow-sm border border-gray-100 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-gray-50/50 border-b border-gray-100">
                                <th class="py-5 px-6 text-xs font-bold text-gray-400 uppercase tracking-widest">#</th>
                                <th class="py-5 px-6 text-xs font-bold text-gray-400 uppercase tracking-widest">Nama Masjid</th>
                                <th class="py-5 px-6 text-xs font-bold text-gray-400 uppercase tracking-widest">Maklumat Hubungan</th>
                                <th class="py-5 px-6 text-xs font-bold text-gray-400 uppercase tracking-widest text-center">Status</th>
                                <th class="py-5 px-6 text-xs font-bold text-gray-400 uppercase tracking-widest text-right">Tindakan</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            @foreach ($applications as $app)
                            <tr class="hover:bg-gray-50/80 transition-colors group">
                                <td class="py-5 px-6 text-sm text-gray-500">{{ $loop->iteration }}</td>
                                <td class="py-5 px-6">
                                    <p class="font-bold text-gray-900 group-hover:text-emerald-600 transition">{{ $app->nama_masjid }}</p>
                                    <p class="text-xs text-gray-400 mt-1">Mohon: {{ $app->created_at->format('d M Y') }}</p>
                                </td>
                                <td class="py-5 px-6">
                                    <div class="text-sm text-gray-600">{{ $app->email }}</div>
                                    <div class="text-xs text-gray-400">{{ $app->notel ?? '-' }}</div>
                                </td>
                                <td class="py-5 px-6 text-center">
                                    @if ($app->status === 0)
                                        <span class="px-3 py-1 bg-yellow-50 text-yellow-600 text-[10px] font-bold rounded-full uppercase tracking-tighter ring-1 ring-yellow-200">Pending</span>
                                    @elseif ($app->status === 1)
                                        <span class="px-3 py-1 bg-emerald-50 text-emerald-600 text-[10px] font-bold rounded-full uppercase tracking-tighter ring-1 ring-emerald-200">Approved</span>
                                    @elseif ($app->status === 2)
                                        <span class="px-3 py-1 bg-red-50 text-red-600 text-[10px] font-bold rounded-full uppercase tracking-tighter ring-1 ring-red-200">Rejected</span>
                                    @endif
                                </td>
                                <td class="py-5 px-6 text-right">
                                    <a href="{{ route('admin.view', $app->id) }}" 
                                       class="inline-flex items-center px-4 py-2 bg-gray-900 text-white text-xs font-bold rounded-xl hover:bg-emerald-600 transition-all shadow-sm">
                                        Lihat Detail
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.getElementById('statusFilter').addEventListener('change', function() {
        document.getElementById('filterForm').submit();
    });
</script>
@endsection