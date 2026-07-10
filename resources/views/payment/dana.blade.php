@extends('layouts.app')

@section('content')
    <div class="min-h-screen bg-[#f8fafc] pb-12">
        <main class="container mx-auto px-4 sm:px-6 lg:px-8 py-8">

            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-10">
                <div>
                    <h1 class="text-3xl font-black text-slate-900 tracking-tight">Pengesahan Bayaran</h1>
                    <p class="text-slate-500 text-sm mt-1">Urus dan sahkan pembayaran keahlian</p>
                </div>
            </div>

            @if (session('success'))
                <div class="mb-6 p-4 bg-emerald-50 border border-emerald-100 text-emerald-700 rounded-2xl flex items-center shadow-sm">
                    <i class="fas fa-check-circle mr-3 text-emerald-500"></i>
                    <span class="font-bold text-sm">{{ session('success') }}</span>
                </div>
            @endif

            @if (session('error'))
                <div class="mb-6 p-4 bg-rose-50 border border-rose-100 text-rose-700 rounded-2xl flex items-center shadow-sm">
                    <i class="fas fa-exclamation-circle mr-3 text-rose-500"></i>
                    <span class="font-bold text-sm">{{ session('error') }}</span>
                </div>
            @endif

            <!-- FILTER SECTION -->
            <div class="bg-white rounded-[2rem] shadow-sm border border-slate-100 overflow-hidden mb-8">
                <div class="px-8 py-6">
                    <div class="flex items-center mb-4">
                        <div class="w-2 h-8 bg-indigo-600 rounded-full mr-4"></div>
                        <h3 class="text-lg font-black text-slate-900 tracking-tight">Tapis Pembayaran</h3>
                    </div>

                    <!-- Filter Form -->
                    <form method="GET" action="{{ route('finance.dana') }}" class="flex flex-col sm:flex-row gap-3 flex-wrap">
                        <!-- Month Filter -->
                        <select name="month" class="px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm font-semibold text-slate-700 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500">
                            <option value="all" {{ request('month') == 'all' ? 'selected' : '' }}>Semua Bulan</option>
                            <option value="1" {{ request('month') == '1' ? 'selected' : '' }}>Januari</option>
                            <option value="2" {{ request('month') == '2' ? 'selected' : '' }}>Februari</option>
                            <option value="3" {{ request('month') == '3' ? 'selected' : '' }}>Mac</option>
                            <option value="4" {{ request('month') == '4' ? 'selected' : '' }}>April</option>
                            <option value="5" {{ request('month') == '5' ? 'selected' : '' }}>Mei</option>
                            <option value="6" {{ request('month') == '6' ? 'selected' : '' }}>Jun</option>
                            <option value="7" {{ request('month') == '7' ? 'selected' : '' }}>Julai</option>
                            <option value="8" {{ request('month') == '8' ? 'selected' : '' }}>Ogos</option>
                            <option value="9" {{ request('month') == '9' ? 'selected' : '' }}>September</option>
                            <option value="10" {{ request('month') == '10' ? 'selected' : '' }}>Oktober</option>
                            <option value="11" {{ request('month') == '11' ? 'selected' : '' }}>November</option>
                            <option value="12" {{ request('month') == '12' ? 'selected' : '' }}>Disember</option>
                        </select>

                        <!-- Year Filter -->
                        <select name="year" class="px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm font-semibold text-slate-700 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500">
                            <option value="all" {{ request('year') == 'all' ? 'selected' : '' }}>Semua Tahun</option>
                            @for($i = date('Y'); $i >= date('Y')-5; $i--)
                                <option value="{{ $i }}" {{ request('year') == $i ? 'selected' : '' }}>{{ $i }}</option>
                            @endfor
                        </select>

                        <!-- Start Date -->
                        <div class="relative">
                            <input type="date" name="start_date" value="{{ request('start_date') }}" placeholder="Tarikh Mula" class="px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm font-semibold text-slate-700 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500">
                        </div>

                        <!-- End Date -->
                        <div class="relative">
                            <input type="date" name="end_date" value="{{ request('end_date') }}" placeholder="Tarikh Tamat" class="px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm font-semibold text-slate-700 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500">
                        </div>

                        <!-- Quick Date Shortcuts -->
                        <div class="flex gap-2">
                            <button type="submit" name="quick_date" value="today" class="px-4 py-2.5 bg-slate-100 text-slate-700 rounded-xl text-xs font-bold hover:bg-slate-200 transition-colors">Hari Ini</button>
                            <button type="submit" name="quick_date" value="week" class="px-4 py-2.5 bg-slate-100 text-slate-700 rounded-xl text-xs font-bold hover:bg-slate-200 transition-colors">Minggu Ini</button>
                            <button type="submit" name="quick_date" value="month" class="px-4 py-2.5 bg-slate-100 text-slate-700 rounded-xl text-xs font-bold hover:bg-slate-200 transition-colors">Bulan Ini</button>
                        </div>

                        <!-- Filter Buttons -->
                        <div class="flex gap-2">
                            <button type="submit" class="px-6 py-2.5 bg-indigo-600 text-white rounded-xl font-bold text-sm hover:bg-indigo-700 transition-colors shadow-sm shadow-indigo-200">
                                <i class="fas fa-filter mr-2"></i> Tapis
                            </button>

                            @if(request()->has('month') || request()->has('year') || request()->has('start_date') || request()->has('end_date') || request()->has('quick_date'))
                                <a href="{{ route('finance.dana') }}" class="px-6 py-2.5 bg-slate-100 text-slate-600 rounded-xl font-bold text-sm hover:bg-slate-200 transition-colors">
                                    <i class="fas fa-times mr-2"></i> Reset
                                </a>
                            @endif
                        </div>
                    </form>

                    <!-- Active Filters Display -->
                    @if((request()->filled('month') && request('month') != 'all') || (request()->filled('year') && request('year') != 'all') || request()->filled('start_date') || request()->filled('end_date'))
                        <div class="mt-4 flex flex-wrap items-center gap-2">
                            <span class="text-xs font-semibold text-slate-500">Tapis Aktif:</span>
                            @if(request('month') && request('month') != 'all')
                                <span class="px-3 py-1 bg-indigo-50 text-indigo-700 rounded-lg text-[10px] font-black uppercase tracking-wider">
                                    Bulan: {{ DateTime::createFromFormat('!m', request('month'))->format('F') }}
                                </span>
                            @endif
                            @if(request('year') && request('year') != 'all')
                                <span class="px-3 py-1 bg-indigo-50 text-indigo-700 rounded-lg text-[10px] font-black uppercase tracking-wider">
                                    Tahun: {{ request('year') }}
                                </span>
                            @endif
                            @if(request('start_date'))
                                <span class="px-3 py-1 bg-indigo-50 text-indigo-700 rounded-lg text-[10px] font-black uppercase tracking-wider">
                                    Dari: {{ \Carbon\Carbon::parse(request('start_date'))->format('d/m/Y') }}
                                </span>
                            @endif
                            @if(request('end_date'))
                                <span class="px-3 py-1 bg-indigo-50 text-indigo-700 rounded-lg text-[10px] font-black uppercase tracking-wider">
                                    Hingga: {{ \Carbon\Carbon::parse(request('end_date'))->format('d/m/Y') }}
                                </span>
                            @endif
                        </div>
                    @endif
                </div>
            </div>

            <!-- PENDING PAYMENTS SECTION - WITH INDEX NUMBER -->
            @if(isset($pendingPayments) && $pendingPayments->count() > 0)
            <div class="bg-white rounded-[2rem] shadow-sm border border-amber-100 overflow-hidden mb-8">
                <div class="px-8 py-6 border-b border-amber-100 bg-amber-50/50">
                    <div class="flex items-center gap-3">
                        <div class="w-2 h-8 bg-amber-500 rounded-full"></div>
                        <h3 class="text-lg font-black text-slate-900 tracking-tight">Pembayaran Menunggu Kelulusan</h3>
                        <span class="bg-amber-100 text-amber-700 text-xs font-bold px-3 py-1 rounded-full">{{ $pendingPayments->count() }}</span>
                    </div>
                    <p class="text-slate-500 text-xs mt-2">Sahkan pembayaran untuk memperbaharui keahlian ahli secara automatik</p>
                </div>
                
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="bg-slate-50/50">
                                <th class="px-8 py-4 text-left text-[10px] font-black text-slate-400 uppercase tracking-widest">#</th>
                                <th class="px-8 py-4 text-left text-[10px] font-black text-slate-400 uppercase tracking-widest">Pemohon</th>
                                <th class="px-8 py-4 text-left text-[10px] font-black text-slate-400 uppercase tracking-widest">Tarikh Mohon</th>
                                <th class="px-8 py-4 text-left text-[10px] font-black text-slate-400 uppercase tracking-widest">Jumlah</th>
                                <th class="px-8 py-4 text-left text-[10px] font-black text-slate-400 uppercase tracking-widest">Kaedah</th>
                                <th class="px-8 py-4 text-left text-[10px] font-black text-slate-400 uppercase tracking-widest">Rujukan</th>
                                <th class="px-8 py-4 text-left text-[10px] font-black text-slate-400 uppercase tracking-widest">Resit</th>
                                <th class="px-8 py-4 text-left text-[10px] font-black text-slate-400 uppercase tracking-widest">Tindakan</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50">
                            @forelse($pendingPayments as $index => $payment)
                            <tr class="hover:bg-slate-50/80 transition-colors">
                                <td class="px-8 py-5">
                                    <span class="text-sm font-bold text-slate-500">{{ $pendingPayments->firstItem() + $index }}</span>
                                
                                <td class="px-8 py-5">
                                    <p class="text-sm font-bold text-slate-900">{{ $payment->user->name ?? $payment->name ?? 'Nama tidak dijumpai' }}</p>
                                    <p class="text-xs text-slate-400 mt-0.5">{{ $payment->user->ic_number ?? '-' }}</p>
                                
    
                                <td class="px-8 py-5">
                                    <p class="text-sm font-semibold text-slate-500">{{ $payment->created_at->format('d M Y') }}</p>
                                    <p class="text-xs text-slate-400">{{ $payment->created_at->format('h:i A') }}</p>
                                
    
                                <td class="px-8 py-5">
                                    <span class="text-sm font-black text-emerald-600">+ RM {{ number_format($payment->amount, 2) }}</span>
                                
    
                                <td class="px-8 py-5">
                                    <span class="text-sm font-bold text-slate-600">{{ $payment->payment_method ?? 'Tunai' }}</span>
                                
    
                                <td class="px-8 py-5">
                                    <span class="text-xs font-mono text-slate-500">{{ $payment->reference_no ?? 'TRX-'.str_pad($payment->id, 5, '0', STR_PAD_LEFT) }}</span>
                                
    
                                <td class="px-8 py-5">
                                    @if($payment->receipt_path)
                                        <a href="{{ $payment->receipt_path }}" target="_blank" class="text-indigo-600 hover:text-indigo-700 text-sm font-semibold inline-flex items-center gap-1">
                                            <i class="fas fa-file-pdf"></i> Lihat Resit
                                        </a>
                                    @else
                                        <span class="text-slate-400 text-xs">Tiada resit</span>
                                    @endif
                                
    
                                <td class="px-8 py-5">
                                    <div class="flex gap-2">
                                        <form method="POST" action="{{ route('finance.status.update') }}" class="inline" onsubmit="return confirm('Terima pembayaran ini? Keahlian akan diaktifkan secara automatik.')">
                                            @csrf
                                            <input type="hidden" name="payment_id" value="{{ $payment->id }}">
                                            <input type="hidden" name="action" value="approve">
                                            <button type="submit" class="px-4 py-2 bg-emerald-600 text-white rounded-xl text-xs font-bold hover:bg-emerald-700 transition-colors flex items-center gap-1">
                                                <i class="fas fa-check"></i> Sahkan
                                            </button>
                                        </form>
                                        <form method="POST" action="{{ route('finance.status.update') }}" class="inline reject-form">
                                            @csrf
                                            <input type="hidden" name="payment_id" value="{{ $payment->id }}">
                                            <input type="hidden" name="action" value="reject">
                                            <input type="hidden" name="reason" class="reject-reason-input">
                                            <button type="button" class="px-4 py-2 bg-rose-600 text-white rounded-xl text-xs font-bold hover:bg-rose-700 transition-colors flex items-center gap-1 btn-reject">
                                                <i class="fas fa-times"></i> Tolak
                                            </button>
                                        </form>
                                    </div>
                                </td>
                              </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="px-8 py-12 text-center">
                                        <div class="flex flex-col items-center">
                                            <i class="fas fa-check-circle text-4xl text-slate-300 mb-3"></i>
                                            <p class="text-slate-400 text-sm">Tiada pembayaran yang menunggu kelulusan</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                @if($pendingPayments->hasPages())
                    <div class="px-8 py-6 border-t border-slate-100">
                        {{ $pendingPayments->links() }}
                    </div>
                @endif

                <!-- Results Info -->
                <div class="px-8 py-4 bg-slate-50/50 border-t border-slate-100 text-xs text-slate-500 font-semibold">
                    Menunjukkan {{ $pendingPayments->firstItem() ?? 0 }} - {{ $pendingPayments->lastItem() ?? 0 }}
                    daripada {{ $pendingPayments->total() }} rekod pembayaran menunggu
                </div>
            </div>
            @else
            <div class="bg-white rounded-[2rem] shadow-sm border border-slate-100 overflow-hidden">
                <div class="px-8 py-16 text-center">
                    <div class="flex flex-col items-center">
                        <i class="fas fa-check-circle text-5xl text-slate-300 mb-4"></i>
                        <h3 class="text-lg font-bold text-slate-700">Tiada Pembayaran Menunggu</h3>
                        <p class="text-slate-400 text-sm mt-1">Semua pembayaran telah diproses</p>
                    </div>
                </div>
            </div>
            @endif
        </main>
    </div>

    <style>
        body {
            -webkit-font-smoothing: antialiased;
        }
        .tracking-tighter {
            letter-spacing: -0.05em;
        }
    </style>

    <script>
        document.querySelectorAll('.btn-reject').forEach(function (btn) {
            btn.addEventListener('click', function () {
                const form = btn.closest('form');
                const reason = prompt('Sila nyatakan sebab penolakan pembayaran ini:');

                if (reason === null) {
                    return; // user cancel
                }
                if (reason.trim() === '') {
                    alert('Sebab penolakan diperlukan.');
                    return;
                }

                form.querySelector('.reject-reason-input').value = reason.trim();
                form.submit();
            });
        });
    </script>
@endsection