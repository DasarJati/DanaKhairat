@extends('layouts.app')
@section('title', 'Tukar Ketua Keluarga - ' . $ketua->nama)
@section('content')

    <div class="max-w-6xl mx-auto px-4 py-10">
        <div class="mb-10 text-center">
            <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight mt-3">Pertukaran Ketua Keluarga</h1>
            <p class="text-sm text-slate-500 mt-2 max-w-md mx-auto">
                Proses memindahkan hak milik profil utama kepada pasangan sah (Suami/Isteri) di dalam sistem kariah.
            </p>
        </div>

        <div class="space-y-8">

            <!-- MODULE A: CURRENT STATUS DECK -->
            <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6 sm:p-8 relative overflow-hidden">
                <div class="absolute top-0 right-0 h-32 w-32 bg-slate-50 rounded-full -mr-10 -mt-10 pointer-events-none"></div>

                <div class="relative flex flex-col md:flex-row md:items-center justify-between gap-6">
                    <div class="space-y-1">
                        <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Ketua Asal Semasa</span>
                        <h2 class="text-xl font-bold text-slate-900">{{ $ketua->nama }}</h2>
                        <p class="text-xs text-slate-500 font-mono flex items-center gap-1.5 mt-1">
                            <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z" />
                            </svg>
                            No. KP: {{ $ketua->ic }}
                        </p>
                    </div>

                    <div class="grid grid-cols-2 gap-4 border-t md:border-t-0 md:border-l border-slate-100 pt-4 md:pt-0 md:pl-8">
                        <div>
                            <span class="text-[11px] text-slate-400 block font-medium">No. Telefon</span>
                            <span class="text-xs font-semibold text-slate-700 mt-0.5 block">{{ $ketua->notel ?? 'Tiada rekod' }}</span>
                        </div>
                        <div>
                            <span class="text-[11px] text-slate-400 block font-medium">Masjid Kariah</span>
                            <span class="text-xs font-semibold text-slate-700 mt-0.5 block max-w-[140px] truncate" title="{{ $ketua->masjid->nama ?? 'Masjid Terdaftar' }}">
                                {{ $ketua->masjid->nama ?? 'Masjid Terdaftar' }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- MODULE B: INTERACTIVE WORKFLOW FORM -->
            <div class="bg-white rounded-2xl border border-slate-100 shadow-md shadow-slate-100/50 overflow-hidden">
                <form action="{{ route('change-ketua.change', $ketua->id) }}" method="POST"
                    id="changeKetuaForm"
                    class="p-6 sm:p-8 space-y-8">
                    @csrf
                    @method('POST')

                    @if ($errors->any())
                        <div class="rounded-xl bg-rose-50 border border-rose-200 p-4">
                            <div class="flex items-start gap-3">
                                <svg class="w-5 h-5 text-rose-600 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <div class="flex-1">
                                    <p class="text-sm font-bold text-rose-800">Ralat:</p>
                                    <ul class="text-xs text-rose-700 mt-1 list-disc list-inside">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Phase 1: Interactive Target Selection -->
                    <div class="space-y-5 p-6 bg-slate-50/50 rounded-2xl border border-slate-100 shadow-inner">
                        <div class="flex items-start gap-4 border-b border-slate-200/60 pb-4">
                            <span class="flex h-7 w-7 shrink-0 items-center justify-center rounded-lg bg-slate-900 text-xs font-bold text-white shadow-sm ring-4 ring-slate-900/10">
                                01
                            </span>
                            <div>
                                <h3 class="text-base font-bold text-slate-900">Pilih Calon Pengganti</h3>
                                <p class="text-xs text-slate-500 mt-0.5">Sila klik pada salah satu kad tanggungan di bawah untuk menjadikannya Ketua Keluarga baharu.</p>
                            </div>
                        </div>

                        @if ($potentialWives->isEmpty())
                            <div class="rounded-xl bg-rose-50 border border-rose-200 p-5 text-center shadow-sm">
                                <div class="h-11 w-11 bg-rose-100 text-rose-600 rounded-full flex items-center justify-center mx-auto mb-3 ring-4 ring-rose-50">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                    </svg>
                                </div>
                                <h4 class="text-sm font-bold text-rose-900">Tiada Pasangan Sah Ditemui</h4>
                                <p class="text-xs text-rose-700/90 max-w-md mx-auto mt-1.5 leading-relaxed">
                                    Sistem memerlukan rekod bertaraf <span class="font-bold underline">ISTERI</span> atau
                                    <span class="font-bold underline">SUAMI</span> berdaftar di bawah tanggungan ahli ini untuk meneruskan konfigurasi pertukaran.
                                </p>
                            </div>
                        @else
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                @foreach ($potentialWives as $tanggungan)
                                    <label class="relative block cursor-pointer select-none group">
                                        <input type="radio" name="tanggungan_id" value="{{ $tanggungan->id }}"
                                            class="sr-only peer" required>

                                        <div class="h-full p-5 rounded-xl border-2 border-slate-200 bg-white shadow-sm transition-all duration-200 relative overflow-hidden
                                            hover:border-slate-400 hover:shadow-md
                                            peer-checked:border-emerald-600 peer-checked:bg-emerald-50/30 peer-checked:shadow-md peer-checked:shadow-emerald-600/5 peer-checked:scale-[1.01]
                                            flex flex-col justify-between gap-4">

                                            <div class="absolute top-0 left-0 right-0 h-[3px] bg-emerald-600 opacity-0 peer-checked:opacity-100 transition-opacity"></div>

                                            <div class="space-y-3">
                                                <div class="flex items-center justify-between gap-2">
                                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold tracking-wider uppercase bg-slate-100 text-slate-700 border border-slate-200 group-hover:bg-slate-200/70 transition-colors">
                                                        {{ $tanggungan->hubungan }}
                                                    </span>
                                                    @if ($tanggungan->oku)
                                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-[9px] font-extrabold bg-rose-50 text-rose-600 border border-rose-100 animate-pulse">OKU</span>
                                                    @endif
                                                </div>

                                                <div>
                                                    <h4 class="text-sm font-bold text-slate-800 tracking-tight group-hover:text-slate-900 transition-colors">
                                                        {{ $tanggungan->nama }}
                                                    </h4>
                                                    <p class="text-xs text-slate-400 font-mono mt-1">No. KP: {{ $tanggungan->ic_number }}</p>
                                                </div>
                                            </div>

                                            <div class="pt-3 border-t border-slate-100 flex items-center justify-between text-[11px] text-slate-400 font-medium">
                                                <span class="flex items-center gap-1">
                                                    <span class="h-1 w-1 rounded-full bg-slate-300 group-has-[:checked]:bg-emerald-500"></span>
                                                    Akses Log Masuk Baharu
                                                </span>
                                                <div class="h-5 w-5 rounded-full border border-slate-300 bg-white flex items-center justify-center transition-all shadow-inner
                                                    group-hover:border-slate-400
                                                    group-has-[:checked]:border-emerald-600 group-has-[:checked]:bg-emerald-600">
                                                    <svg class="w-3 h-3 text-white scale-0 transition-transform duration-200 group-has-[:checked]:scale-100"
                                                        fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                                                    </svg>
                                                </div>
                                            </div>
                                        </div>
                                    </label>
                                @endforeach
                            </div>
                        @endif
                    </div>

                    <!-- Phase 2: Dynamic System Impact Feedback -->
                    <div id="infoBox" class="hidden rounded-xl bg-slate-900 text-slate-300 p-5 space-y-4 animate-fade-in shadow-inner">
                        <div class="flex items-center gap-2 border-b border-slate-800 pb-3">
                            <div class="h-2 w-2 rounded-full bg-emerald-400 animate-ping"></div>
                            <h4 class="text-xs font-bold uppercase tracking-widest text-slate-400">Peta Struktur Hierarki Baharu</h4>
                        </div>
                        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 text-xs">
                            <div class="space-y-1">
                                <span class="text-slate-500 font-medium block text-[10px] uppercase">Ketua Keluarga Baru</span>
                                <span class="text-white font-bold text-sm tracking-tight" id="newKetuaName"></span>
                            </div>
                            <div class="hidden sm:block text-slate-600">→</div>
                            <div class="space-y-1">
                                <span class="text-slate-500 font-medium block text-[10px] uppercase">Ahli Tanggungan</span>
                                <span class="text-slate-400 font-semibold block">{{ $ketua->nama }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Phase 3: Credentials + Confirmation -->
                    <div class="space-y-5 pt-2">
                        <div class="flex items-start gap-4 border-b border-slate-100 pb-4">
                            <span class="flex h-7 w-7 shrink-0 items-center justify-center rounded-lg bg-slate-900 text-xs font-bold text-white shadow-sm ring-4 ring-slate-900/10">
                                02
                            </span>
                            <div>
                                <h3 class="text-base font-bold text-slate-900">Tetapan Akaun & Pengesahan</h3>
                                <p class="text-xs text-slate-500 mt-0.5">Isikan maklumat log masuk untuk ketua keluarga baharu.</p>
                            </div>
                        </div>

                        <!-- Credentials Card -->
                        <div class="p-5 bg-slate-50/50 rounded-2xl border border-slate-100 space-y-4">

                            <!-- Email -->
                            <div class="space-y-1.5">
                                <label for="new_email" class="text-xs font-semibold text-slate-700 flex items-center gap-1">
                                    <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                    </svg>
                                    Emel Baharu <span class="text-rose-500">*</span>
                                </label>
                                <input type="email" name="new_email" id="new_email"
                                    value="{{ old('new_email') }}"
                                    placeholder="contoh@email.com"
                                    required
                                    class="w-full text-xs rounded-xl border border-slate-200 bg-white px-3.5 py-2.5 text-slate-800 placeholder-slate-300 focus:outline-none focus:ring-2 focus:ring-slate-900/10 focus:border-slate-400 transition-all @error('new_email') border-rose-400 bg-rose-50/30 @enderror" />
                                @error('new_email')
                                    <p class="text-[11px] text-rose-600 flex items-center gap-1 mt-1">
                                        <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                                        {{ $message }}
                                    </p>
                                @enderror
                                <p class="text-[11px] text-slate-400">Emel ini akan digunakan untuk log masuk ketua baharu.</p>
                            </div>

                            <!-- Tel Number -->
                            <div class="space-y-1.5">
                                <label for="new_tel" class="text-xs font-semibold text-slate-700 flex items-center gap-1">
                                    <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                    </svg>
                                    No. Telefon <span class="text-rose-500">*</span>
                                </label>
                                <input type="tel" name="new_tel" id="new_tel"
                                    value="{{ old('new_tel') }}"
                                    placeholder="Cth: 0123456789"
                                    required
                                    class="w-full text-xs rounded-xl border border-slate-200 bg-white px-3.5 py-2.5 text-slate-800 placeholder-slate-300 focus:outline-none focus:ring-2 focus:ring-slate-900/10 focus:border-slate-400 transition-all @error('new_tel') border-rose-400 bg-rose-50/30 @enderror" />
                                @error('new_tel')
                                    <p class="text-[11px] text-rose-600 flex items-center gap-1 mt-1">
                                        <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>

                            <!-- Divider -->
                            <div class="relative py-1">
                                <div class="absolute inset-0 flex items-center"><div class="w-full border-t border-slate-200/60"></div></div>
                                <div class="relative flex justify-center">
                                    <span class="px-3 bg-slate-50 text-[10px] font-bold text-slate-400 uppercase tracking-wider">Kata Laluan</span>
                                </div>
                            </div>

                            <!-- Password -->
                            <div class="space-y-1.5">
                                <label for="new_password" class="text-xs font-semibold text-slate-700 flex items-center gap-1">
                                    <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                    </svg>
                                    Kata Laluan Baharu
                                </label>
                                <div class="relative">
                                    <input type="password" name="new_password" id="new_password"
                                        placeholder="Kosongkan untuk guna No. KP sebagai kata laluan lalai"
                                        class="w-full text-xs rounded-xl border border-slate-200 bg-white px-3.5 py-2.5 pr-10 text-slate-800 placeholder-slate-300 focus:outline-none focus:ring-2 focus:ring-slate-900/10 focus:border-slate-400 transition-all @error('new_password') border-rose-400 bg-rose-50/30 @enderror" />
                                    <button type="button" id="togglePassword"
                                        class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-700 transition-colors">
                                        <svg id="eyeIcon" class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                    </button>
                                </div>
                                @error('new_password')
                                    <p class="text-[11px] text-rose-600 flex items-center gap-1 mt-1">
                                        <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                                        {{ $message }}
                                    </p>
                                @enderror
                                <p class="text-[11px] text-slate-400">Jika dikosongkan, No. KP akan digunakan sebagai kata laluan lalai.</p>
                            </div>

                            <!-- Confirm Password -->
                            <div class="space-y-1.5">
                                <label for="new_password_confirmation" class="text-xs font-semibold text-slate-700 flex items-center gap-1">
                                    <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                    </svg>
                                    Sahkan Kata Laluan
                                </label>
                                <input type="password" name="new_password_confirmation" id="new_password_confirmation"
                                    placeholder="Ulang kata laluan baharu"
                                    class="w-full text-xs rounded-xl border border-slate-200 bg-white px-3.5 py-2.5 text-slate-800 placeholder-slate-300 focus:outline-none focus:ring-2 focus:ring-slate-900/10 focus:border-slate-400 transition-all" />
                            </div>
                        </div>

                        <!-- Warning Box -->
                        <div class="p-4 bg-amber-50/60 rounded-xl border border-amber-200/50 flex items-start gap-3">
                            <svg class="w-4 h-4 text-amber-600 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                            </svg>
                            <p class="text-xs text-amber-800 leading-relaxed font-medium">
                                Tindakan ini akan memadamkan hak capaian akaun pentadbir asal milik <strong class="text-amber-950">{{ $ketua->nama }}</strong> serta-merta. Segala log masuk masa hadapan akan ditugaskan kepada rekod KP ketua baharu yang dipilih.
                            </p>
                        </div>

                        <!-- Acknowledgment Checkbox -->
                        <label class="flex items-center gap-3 p-4 bg-slate-50 rounded-xl border border-slate-200/60 cursor-pointer select-none hover:bg-slate-100/50 transition-colors group">
                            <input type="checkbox" name="confirm_swap" id="confirm_swap" value="1" required
                                class="w-4 h-4 text-slate-900 border-slate-300 rounded focus:ring-slate-900/10 focus:ring-offset-0 focus:ring-2 transition-all">
                            <span class="text-xs font-semibold text-slate-600 group-hover:text-slate-800 transition-colors">
                                Saya mengaku dan mengesahkan pertukaran maklumat kariah ini adalah benar dan muktamad.
                            </span>
                        </label>
                    </div>

                    <!-- Step 3: Submit -->
                    <div class="flex flex-col sm:flex-row items-center gap-3 pt-4 border-t border-slate-100">
                        <a href="{{ route('change-ketua.index') }}"
                            class="w-full sm:flex-1 px-4 py-3 text-slate-500 hover:text-slate-800 text-xs font-bold rounded-xl text-center transition-colors">
                            Batal & Keluar
                        </a>
                        <button type="submit" id="submitBtn"
                            class="w-full sm:flex-1 inline-flex items-center justify-center gap-2 px-5 py-3 bg-slate-900 hover:bg-slate-800 text-white text-xs font-bold rounded-xl shadow-sm shadow-slate-900/10 transition-all disabled:opacity-25 disabled:cursor-not-allowed disabled:hover:bg-slate-900 active:scale-[0.985]">
                            Sahkan Pelantikan Baharu
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        // Toggle password visibility
        document.getElementById('togglePassword')?.addEventListener('click', function () {
            const input = document.getElementById('new_password');
            const icon = document.getElementById('eyeIcon');
            if (input.type === 'password') {
                input.type = 'text';
                icon.innerHTML = `<path stroke-linecap="round" stroke-linejoin="round" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />`;
            } else {
                input.type = 'password';
                icon.innerHTML = `<path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />`;
            }
        });

        // Show info box when radio is selected
        document.querySelectorAll('input[name="tanggungan_id"]').forEach(function (radio) {
            radio.addEventListener('change', function () {
                const label = this.closest('label');
                const name = label.querySelector('h4')?.textContent?.trim();
                const infoBox = document.getElementById('infoBox');
                const newKetuaName = document.getElementById('newKetuaName');
                if (infoBox && newKetuaName && name) {
                    infoBox.classList.remove('hidden');
                    newKetuaName.textContent = name;
                }
            });
        });

        // Form validation on submit
        document.getElementById('changeKetuaForm')?.addEventListener('submit', function (e) {
            const selectedRadio = document.querySelector('input[name="tanggungan_id"]:checked');
            const confirmCheckbox = document.getElementById('confirm_swap');
            const email = document.getElementById('new_email').value.trim();
            const tel = document.getElementById('new_tel').value.trim();
            const password = document.getElementById('new_password').value;
            const confirmation = document.getElementById('new_password_confirmation').value;

            if (!selectedRadio) {
                e.preventDefault();
                alert('Sila pilih calon pengganti terlebih dahulu.');
                return false;
            }

            if (!email) {
                e.preventDefault();
                alert('Sila masukkan emel baharu.');
                return false;
            }

            if (!tel) {
                e.preventDefault();
                alert('Sila masukkan no. telefon.');
                return false;
            }

            if (password && password !== confirmation) {
                e.preventDefault();
                alert('Kata laluan dan pengesahan tidak sepadan.');
                return false;
            }

            if (password && password.length < 8) {
                e.preventDefault();
                alert('Kata laluan mestilah sekurang-kurangnya 8 aksara.');
                return false;
            }

            if (!confirmCheckbox.checked) {
                e.preventDefault();
                alert('Sila tandakan pengesahan sebelum meneruskan.');
                return false;
            }

            return true;
        });
    </script>

@endsection