@extends('layouts.user')

@section('title', 'Profil Ahli Kariah')

@section('content')
<div class="container mx-auto max-w-6xl space-y-6">
    <div class="rounded-3xl bg-gradient-to-r from-teal-700 to-amber-400 p-6 md:p-8 text-white shadow-lg">
        <div class="flex items-center gap-4">
            <div class="flex h-16 w-16 shrink-0 items-center justify-center rounded-2xl bg-white/20 text-2xl font-bold ring-1 ring-white/30">
                {{ strtoupper(substr($user->nama ?? 'A', 0, 1)) }}
            </div>
            <div>
                <p class="text-xs font-bold uppercase tracking-[0.2em] text-white/75">Profil Ahli Kariah</p>
                <h1 class="mt-1 text-2xl font-extrabold md:text-3xl">{{ $user->nama }}</h1>
                <p class="mt-1 text-sm text-white/85">{{ optional(optional($ahli)->masjid)->nama ?? 'Masjid tidak dinyatakan' }}</p>
            </div>
        </div>
    </div>

    @if (session('success'))
        <div class="rounded-2xl border border-emerald-200 bg-emerald-50 px-5 py-4 text-sm font-semibold text-emerald-700">
            <i class="fas fa-check-circle mr-2"></i>{{ session('success') }}
        </div>
    @endif

    <div class="grid grid-cols-1 gap-6 lg:grid-cols-5">
        <section class="overflow-hidden rounded-3xl border border-gray-100 bg-white shadow-sm lg:col-span-3">
            <div class="border-b border-gray-100 px-6 py-5 md:px-8">
                <h2 class="font-bold text-gray-800"><i class="fas fa-id-card mr-2 text-gold-500"></i>Maklumat Profil</h2>
                <p class="mt-1 text-xs text-gray-500">Maklumat akaun Ahli Kariah anda.</p>
            </div>

            <dl class="grid grid-cols-1 gap-6 p-6 md:grid-cols-2 md:p-8">
                <div>
                    <dt class="text-xs font-bold uppercase tracking-wider text-gray-400">Nama Penuh</dt>
                    <dd class="mt-1 font-semibold text-gray-800">{{ $user->nama ?? '-' }}</dd>
                </div>
                <div>
                    <dt class="text-xs font-bold uppercase tracking-wider text-gray-400">E-mel</dt>
                    <dd class="mt-1 break-all font-semibold text-gray-800">{{ $user->email ?? '-' }}</dd>
                </div>
                <div>
                    <dt class="text-xs font-bold uppercase tracking-wider text-gray-400">No. Kad Pengenalan</dt>
                    <dd class="mt-1 font-semibold text-gray-800">{{ $user->ic_number ?? optional($ahli)->ic ?? '-' }}</dd>
                </div>
                <div>
                    <dt class="text-xs font-bold uppercase tracking-wider text-gray-400">No. Telefon</dt>
                    <dd class="mt-1 font-semibold text-gray-800">{{ $user->tel_number ?? optional($ahli)->notel ?? '-' }}</dd>
                </div>
                <div>
                    <dt class="text-xs font-bold uppercase tracking-wider text-gray-400">Jantina</dt>
                    <dd class="mt-1 font-semibold text-gray-800">{{ optional($ahli)->jantina ?? '-' }}</dd>
                </div>
                <div>
                    <dt class="text-xs font-bold uppercase tracking-wider text-gray-400">Status Akaun</dt>
                    <dd class="mt-1">
                        <span class="inline-flex rounded-full px-3 py-1 text-xs font-bold {{ strtolower($user->status ?? '') === 'active' ? 'bg-emerald-100 text-emerald-700' : 'bg-red-100 text-red-700' }}">
                            {{ ucfirst($user->status ?? '-') }}
                        </span>
                    </dd>
                </div>
                <div class="md:col-span-2">
                    <dt class="text-xs font-bold uppercase tracking-wider text-gray-400">Alamat</dt>
                    <dd class="mt-1 font-semibold leading-relaxed text-gray-800">{{ optional($ahli)->alamat ?? '-' }}</dd>
                </div>
            </dl>
        </section>

        <section class="rounded-3xl border border-gray-100 bg-white p-6 shadow-sm md:p-8 lg:col-span-2" x-data="{ showCurrent: false, showNew: false, showConfirmation: false }">
            <h2 class="font-bold text-gray-800"><i class="fas fa-lock mr-2 text-gold-500"></i>Tukar Kata Laluan</h2>
            <p class="mt-1 text-xs leading-relaxed text-gray-500">Kata laluan baharu mesti mempunyai sekurang-kurangnya 8 aksara, huruf, nombor dan simbol.</p>

            <form method="POST" action="{{ route('ahli.password.update') }}" class="mt-6 space-y-5">
                @csrf
                @method('PUT')

                @foreach ([
                    ['current_password', 'Kata Laluan Semasa', 'showCurrent'],
                    ['password', 'Kata Laluan Baharu', 'showNew'],
                    ['password_confirmation', 'Sahkan Kata Laluan Baharu', 'showConfirmation'],
                ] as [$name, $label, $visibility])
                    <div>
                        <label for="{{ $name }}" class="mb-2 block text-sm font-semibold text-gray-700">{{ $label }}</label>
                        <div class="relative">
                            <input :type="{{ $visibility }} ? 'text' : 'password'" id="{{ $name }}" name="{{ $name }}" required autocomplete="{{ $name === 'current_password' ? 'current-password' : 'new-password' }}"
                                class="w-full rounded-xl border px-4 py-3 pr-11 text-sm outline-none transition focus:border-gold-500 focus:ring-2 focus:ring-gold-200 {{ $errors->has($name) ? 'border-red-400 bg-red-50' : 'border-gray-200' }}">
                            <button type="button" @click="{{ $visibility }} = !{{ $visibility }}" class="absolute inset-y-0 right-0 flex w-11 items-center justify-center text-gray-400 hover:text-gold-600" aria-label="Papar atau sembunyikan kata laluan">
                                <i class="fas" :class="{{ $visibility }} ? 'fa-eye-slash' : 'fa-eye'"></i>
                            </button>
                        </div>
                        @error($name)
                            <p class="mt-2 text-xs font-medium text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                @endforeach

                <button type="submit" class="flex w-full items-center justify-center rounded-xl bg-gold-500 px-5 py-3 font-bold text-white shadow-md transition hover:bg-gold-600 focus:ring-2 focus:ring-gold-300 focus:ring-offset-2">
                    <i class="fas fa-key mr-2"></i>Tukar Kata Laluan
                </button>
            </form>
        </section>
    </div>
</div>
@endsection
