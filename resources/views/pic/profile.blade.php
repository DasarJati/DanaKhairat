{{-- resources/views/pic/profile.blade.php --}}
@extends('layouts.app')

@section('content')
@php
    $start = \Carbon\Carbon::parse(auth()->user()->membership_start ?? now());
    $end = \Carbon\Carbon::parse(auth()->user()->membership_end ?? now()->addYear());
    $now = now();
    $totalDays = $start->diffInDays($end) ?: 1;
    $daysPassed = $start->diffInDays($now);
    $percentage = min(100, max(0, ($daysPassed / $totalDays) * 100));
    $daysLeft = (int) max(0, $now->diffInDays($end, false));
@endphp

<div class="bg-slate-50 min-h-screen pb-20 font-sans">
    <div class="bg-slate-900 pt-16 pb-32">
        <div class="container mx-auto px-6 max-w-6xl">
            <div class="flex flex-col md:flex-row justify-between items-center gap-6">
                <div>
                    <div class="flex items-center gap-3 mb-2">
                        <span class="bg-amber-500 text-amber-950 text-[10px] font-bold px-2 py-0.5 rounded uppercase tracking-wider">
                            {{ auth()->user()->masjid->type ?? 'Pusat' }}
                        </span>
                    </div>
                    <h1 class="text-3xl md:text-4xl font-bold text-white tracking-tight">
                        {{ optional(auth()->user()->masjid)->nama ?? 'Pusat Komuniti' }}
                    </h1>
                    <p class="text-slate-400 mt-2 flex items-center gap-2">
                        <i class="fas fa-map-marker-alt text-xs"></i>
                        {{ auth()->user()->alamat ?? 'Alamat tidak dinyatakan' }}
                    </p>
                </div>
                
            </div>
        </div>
    </div>

    <div class="container mx-auto px-6 max-w-6xl -mt-16">
        <div class="grid grid-cols-12 gap-8">
            
            <div class="col-span-12 lg:col-span-8 space-y-6">
                
                <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
                    <div class="px-8 py-5 border-b border-slate-100 flex justify-between items-center">
                        <h2 class="font-bold text-slate-800 flex items-center gap-2">
                            <i class="fas fa-id-card text-slate-400"></i> Maklumat Akaun
                        </h2>
                        <span class="text-slate-400 text-xs font-mono uppercase tracking-widest">Verified User</span>
                    </div>
                    <div class="p-8 grid grid-cols-1 md:grid-cols-2 gap-x-12 gap-y-8">
                        <div>
                            <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Nama Penuh</label>
                            <p class="text-slate-700 font-semibold text-lg">{{ auth()->user()->nama }}</p>
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">E-mel Rasmi</label>
                            <p class="text-slate-700 font-semibold text-lg">{{ auth()->user()->email }}</p>
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">No. Kad Pengenalan</label>
                            <p class="text-slate-700 font-semibold text-lg">{{ auth()->user()->ic_number }}</p>
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">No. Telefon</label>
                            <p class="text-slate-700 font-semibold text-lg">{{ auth()->user()->telefon_bimbit ?? '-' }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden" x-data="{ showForm: false }">
                    <div class="p-6 flex items-center justify-between bg-slate-50/50">
                        <div class="flex items-center gap-4">
                            <div class="w-10 h-10 bg-white rounded-lg border border-slate-200 flex items-center justify-center text-indigo-600 shadow-sm">
                                <i class="fas fa-shield-alt"></i>
                            </div>
                            <div>
                                <h3 class="font-bold text-slate-800 leading-none">Keselamatan Akaun</h3>
                                <p class="text-xs text-slate-500 mt-1">Kemaskini kata laluan anda di sini</p>
                            </div>
                        </div>
                        <button @click="showForm = !showForm" class="text-xs font-bold uppercase tracking-wider px-4 py-2 rounded-lg bg-white border border-slate-200 text-slate-600 hover:bg-slate-50 transition-all">
                           <span x-text="showForm ? 'Tutup' : 'Tukar Kata Laluan'">Tukar Kata Laluan</span>
                        </button>
                    </div>
                    
                    <div x-show="showForm" x-collapse style="display: none;" class="border-t border-slate-100 p-8 bg-white">
                        <form action="{{ route('password.update') }}" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <div>
                                    <label class="block text-xs font-bold text-slate-500 mb-2 uppercase">Laluan Semasa</label>
                                    <input type="password" name="current_password" required class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:ring-2 focus:ring-indigo-500 outline-none text-sm">
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-slate-500 mb-2 uppercase">Laluan Baru</label>
                                    <input type="password" name="password" required class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:ring-2 focus:ring-indigo-500 outline-none text-sm">
                                </div>
                                <div class="flex flex-col">
                                    <label class="block text-xs font-bold text-slate-500 mb-2 uppercase">Sahkan</label>
                                    <div class="flex gap-2">
                                        <input type="password" name="password_confirmation" required class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:ring-2 focus:ring-indigo-500 outline-none text-sm">
                                        <button type="submit" class="bg-indigo-600 text-white px-4 py-2.5 rounded-xl hover:bg-indigo-700 transition-all shadow-md shadow-indigo-100">
                                            <i class="fas fa-check"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm flex items-center gap-4">
                        <div class="w-10 h-10 bg-emerald-50 rounded-lg flex items-center justify-center text-emerald-600"><i class="fas fa-check-circle"></i></div>
                        <div>
                            <p class="text-[10px] font-bold text-slate-400 uppercase">Akses Sistem</p>
                            <p class="text-slate-800 font-bold">Aktif (PIC)</p>
                        </div>
                    </div>
                    <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm flex items-center gap-4">
                        <div class="w-10 h-10 bg-blue-50 rounded-lg flex items-center justify-center text-blue-600"><i class="fas fa-clock"></i></div>
                        <div>
                            <p class="text-[10px] font-bold text-slate-400 uppercase">Log Masuk</p>
                            <p class="text-slate-800 font-bold">{{ now()->format('d M, H:i') }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-span-12 lg:col-span-4">
                <div class="bg-white rounded-3xl border border-slate-200 shadow-sm overflow-hidden sticky top-8">
                    <div class="p-8">
                        <div class="mb-8">
                            <h3 class="text-slate-900 font-bold text-xl leading-none">Status Langganan</h3>
                            <div class="mt-3">
                                <span class="py-1 px-3 rounded-full text-[10px] font-black uppercase tracking-widest {{ auth()->user()->status === 'Aktif' ? 'bg-emerald-100 text-emerald-700' : 'bg-red-100 text-red-700' }}">
                                    {{ auth()->user()->status }}
                                </span>
                            </div>
                        </div>

                        <div class="space-y-6">
                            <div class="bg-slate-50 p-5 rounded-2xl border border-slate-100">
                                <div class="flex justify-between text-xs font-bold mb-3">
                                    <span class="text-slate-400 uppercase">Usage</span>
                                    <span class="text-slate-900">{{ (int)$percentage }}%</span>
                                </div>
                                <div class="w-full bg-slate-200 h-2 rounded-full overflow-hidden">
                                    <div class="h-full bg-slate-900 transition-all duration-700" style="width: {{ $percentage }}%"></div>
                                </div>
                            </div>

                            <div class="grid grid-cols-2 gap-3">
                                <div class="bg-slate-50 p-4 rounded-2xl border border-slate-50 text-center">
                                    <p class="text-[9px] font-bold text-slate-400 uppercase">Mula</p>
                                    <p class="text-xs font-bold text-slate-800">{{ $start->format('d/m/Y') }}</p>
                                </div>
                                <div class="bg-slate-50 p-4 rounded-2xl border border-slate-50 text-center">
                                    <p class="text-[9px] font-bold text-slate-400 uppercase">Tamat</p>
                                    <p class="text-xs font-bold text-slate-800">{{ $end->format('d/m/Y') }}</p>
                                </div>
                            </div>

                            <div class="py-4 text-center border-y border-slate-50">
                                <span class="block text-5xl font-black text-slate-900 tracking-tighter">{{ $daysLeft }}</span>
                                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Hari Berbaki</span>
                            </div>
                        </div>

                        @if($daysLeft <= 60)
                            <button class="w-full mt-6 bg-slate-900 hover:bg-black text-white font-bold py-4 rounded-2xl transition-all flex items-center justify-center gap-2 group">
                                Renew Subscription
                                <i class="fas fa-arrow-right text-xs group-hover:translate-x-1 transition-transform"></i>
                            </button>
                        @endif
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
@endsection