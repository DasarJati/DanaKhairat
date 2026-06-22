@extends('layouts.admin')

@section('title', 'Dompet Digital - e-Khairat')

@section('content')
<div class="min-h-screen bg-slate-50 py-8 px-4 sm:px-6 lg:px-8">

    <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-8 space-y-4 md:space-y-0">
        <div>
            <h1 class="text-2xl font-bold text-slate-900 tracking-tight">Dompet Digital</h1>
            <p class="text-slate-500 text-sm">Pengurusan dana kariah, agihan masjid, dan wakalah sistem.</p>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">

        <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-bold text-amber-600 uppercase tracking-wider mb-1">Jumlah Transaksi</p>
                    <h3 class="text-2xl font-bold text-slate-800">
                        {{ $totalTransaksi }} Transaksi
                    </h3>
                    <p class="text-[11px] text-slate-400 mt-2 flex items-center">
                        <i class="fas fa-exclamation-circle text-amber-500 mr-1"></i> Perlu tindakan segera
                    </p>
                </div>
                <div class="h-12 w-12 bg-amber-50 rounded-full flex items-center justify-center">
                    <i class="fas fa-clock text-xl text-amber-500"></i>
                </div>
            </div>
        </div>

        <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-bold text-emerald-600 uppercase tracking-wider mb-1">Total harga yang perlu ditransfer</p>
                    <h3 class="text-2xl font-bold text-slate-800">
                        RM {{ number_format($totalTransfer, 2) }}
                    </h3>
                    <p class="text-[11px] text-slate-400 mt-2 flex items-center">
                        <i class="fas fa-check-double text-emerald-500 mr-1"></i> Dana telah dikreditkan
                    </p>
                </div>
                <div class="h-12 w-12 bg-emerald-50 rounded-full flex items-center justify-center">
                    <i class="fas fa-check-circle text-xl text-emerald-500"></i>
                </div>
            </div>
        </div>

        <div class="bg-slate-900 p-6 rounded-2xl shadow-xl relative overflow-hidden">
            <div class="relative z-10 flex items-center justify-between">
                <div>
                    <p class="text-xs font-bold text-blue-400 uppercase tracking-wider mb-1 opacity-90">Total Wakalah</p>
                    <h3 class="text-2xl font-bold text-white">
                        RM {{ number_format($totalWakalah, 2) }}
                    </h3>
                    <p class="text-[11px] text-slate-400 mt-2">Kutipan bersih sistem</p>
                </div>
                <div class="h-12 w-12 bg-white/10 rounded-full flex items-center justify-center backdrop-blur-sm">
                    <i class="fas fa-wallet text-xl text-blue-400"></i>
                </div>
            </div>
            <div class="absolute -right-6 -bottom-6 w-24 h-24 bg-blue-600/20 rounded-full blur-2xl"></div>
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">

        <div class="px-6 py-5 border-b border-slate-50 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <h6 class="font-bold text-slate-800">Senarai Pecahan Bayaran Terkini</h6>

            <form method="GET" class="relative w-full sm:w-64">
                <select name="masjid_id"
                    onchange="this.form.submit()"
                    class="block w-full pl-4 pr-10 py-2 bg-slate-50 border border-slate-200 text-xs font-semibold text-slate-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none appearance-none">

                    <option value="">Lokasi</option>

                    @foreach($masjids as $masjid)
                    <option value="{{ $masjid->id }}"
                        {{ $selectedMasjid == $masjid->id ? 'selected' : '' }}>
                        {{ $masjid->nama }}
                    </option>
                    @endforeach

                </select>

                <div class="absolute inset-y-0 right-0 flex items-center px-2 pointer-events-none text-slate-400">
                    <i class="fas fa-chevron-down text-[10px]"></i>
                </div>
            </form>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/50">
                        <th class="px-6 py-4 text-[10px] font-bold uppercase tracking-widest text-slate-400">Ahli Khairat</th>
                        <th class="px-6 py-4 text-[10px] font-bold uppercase tracking-widest text-slate-400 text-center">Masjid/Surau</th>
                        <th class="px-6 py-4 text-[10px] font-bold uppercase tracking-widest text-slate-400 text-center">Jumlah Kasar</th>
                        <th class="px-6 py-4 text-[10px] font-bold uppercase tracking-widest text-slate-400 text-center text-rose-500">Wakalah</th>
                        <th class="px-6 py-4 text-[10px] font-bold uppercase tracking-widest text-slate-400 text-center text-emerald-600">Net Ke Masjid</th>
                       
                        <th class="px-6 py-4 text-[10px] font-bold uppercase tracking-widest text-slate-400 text-right">Tindakan</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">

                    @forelse($payments as $payment)

                    <tr class="hover:bg-slate-50 transition-colors">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="h-10 w-10 rounded-full bg-blue-50 flex items-center justify-center mr-3 border border-blue-100">
                                    <span class="text-blue-600 font-bold text-xs">
                                        {{ strtoupper(substr($payment->name, 0, 2)) }}
                                    </span>
                                </div>
                                <div>
                                    <p class="text-sm font-bold text-slate-800">
                                        {{ $payment->name }}
                                    </p>
                                    <p class="text-[10px] text-slate-400">
                                        REF: #EK{{ str_pad($payment->id, 4, '0', STR_PAD_LEFT) }}
                                        • {{ $payment->created_at->format('d M Y') }}
                                    </p>
                                </div>
                            </div>
                        </td>

                        {{-- Nama Masjid --}}
                        <td class="px-6 py-4 text-center">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-slate-100 text-slate-600 border border-slate-200">
                                {{ $payment->masjid->nama ?? 'Masjid' }}
                            </span>
                        </td>

                        {{-- Amount Asal --}}
                        <td class="flex flex-col px-6 py-4 text-center text-sm font-semibold text-slate-700">
                            RM {{ number_format($payment->amount, 2) }}
                            @php
                            $type = $payment->type;
                        @endphp
                    
                        @if($type === 'New Member')
                            <span class="text-xs font-bold text-emerald-600 bg-emerald-50 px-3 py-1 rounded-lg border">
                                AHLI BARU
                            </span>
                    
                        @elseif($type === 'Renew Membership')
                            <span class="text-xs font-bold text-blue-600 bg-blue-50 px-3 py-1 rounded-lg border">
                                MEMPERBAHARUI
                            </span>
                    
                        @else
                            <span class="text-xs font-bold text-gray-600 bg-gray-100 px-3 py-1 rounded-lg">
                                {{ strtoupper($type) }}
                            </span>
                        @endif
                        </td>

                        {{-- Wakalah --}}
                        <td class="px-6 py-4 text-center">
                            <span class="text-xs font-bold text-rose-500">
                                - RM {{ number_format($wakalahFee, 2) }}
                            </span>
                        </td>

                        {{-- Net Transfer --}}
                        <td class="px-6 py-4 text-center">
                            <span class="text-sm font-bold text-emerald-600 bg-emerald-50 px-3 py-1 rounded-lg">
                                RM {{ number_format($payment->amount - $wakalahFee, 2) }}
                            </span>
                        </td>
                        

                        <td class="px-6 py-4 text-right">
                            @if($payment->status == 'PAID')
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-[11px] font-bold bg-emerald-100 text-emerald-700 border border-emerald-200">

                                ✓ SELESAI
                            </span>
                            @else
                            <a href="{{ route('payment.upload', $payment->id) }}"
                                class="text-[12px] font-bold text-blue-600 hover:text-blue-800 hover:underline transition-all">
                                Upload
                            </a>
                            @endif
                        </td>
                    </tr>

                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-6 text-slate-400 text-sm">
                            Tiada transaksi dijumpai.
                        </td>
                    </tr>
                    @endforelse

                </tbody>
            </table>
        </div>

        <div class="px-6 py-4 bg-slate-50/50 border-t border-slate-100 flex items-center justify-between">
            <span class="text-xs text-slate-500">Menunjukkan 2 daripada 1,240 rekod</span>
            <nav class="inline-flex -space-x-px shadow-sm rounded-md bg-white text-[11px] font-bold">
                <button class="px-3 py-2 rounded-l-md border border-slate-200 text-slate-400 cursor-not-allowed">Sebelum</button>
                <button class="px-4 py-2 border-t border-b border-blue-500 bg-blue-500 text-white leading-tight">1</button>
                <button class="px-4 py-2 border border-slate-200 text-slate-600 hover:bg-slate-50 leading-tight">2</button>
                <button class="px-4 py-2 border border-slate-200 text-slate-600 hover:bg-slate-50 leading-tight">3</button>
                <button class="px-3 py-2 rounded-r-md border border-slate-200 text-slate-600 hover:bg-slate-50">Seterusnya</button>
            </nav>
        </div>
    </div>

</div>
@endsection