@extends('layouts.app')

@section('title', 'Dashboard - e-Khairat')

@section('content')
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8" x-data="{
        tab: new URLSearchParams(window.location.search).get('tab') || 'masjid'
    }" x-init="$watch('tab', value => {
        const url = new URL(window.location.href);
        url.searchParams.set('tab', value);
        window.history.replaceState({}, '', url);
    })">

        <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-8">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 tracking-tight">Pengurusan Maklumat</h1>
                <p class="text-sm text-gray-500 mt-1">Kemaskini maklumat dan bayaran.</p>
            </div>
        </div>

        <!-- SweetAlert for Success Messages -->
        <div x-data="{
            showSuccessAlert() {
                @if (session('success')) Swal.fire({
                icon: 'success',
                title: 'Berjaya!',
                text: '{{ session('success') }}',
                timer: 3000,
                showConfirmButton: true,
                confirmButtonText: 'OK',
                confirmButtonColor: '#4f46e5',
                timerProgressBar: true,
                background: '#ffffff',
                iconColor: '#10b981',
                customClass: {
                    title: 'text-lg font-bold text-gray-800',
                    popup: 'rounded-xl shadow-xl',
                    confirmButton: 'px-5 py-2 text-sm font-medium bg-indigo-600 hover:bg-indigo-700 focus:ring-2 focus:ring-indigo-500 rounded-lg'
                }
            }); @endif
            }
        }" x-init="showSuccessAlert()"></div>

        <div class="border-b border-gray-200 mb-6">
            <nav class="-mb-px flex space-x-8">
                <button @click="tab = 'masjid'"
                    :class="tab === 'masjid' ? 'border-indigo-500 text-indigo-600' :
                        'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                    class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-colors">
                    Maklumat
                </button>
                <button @click="tab = 'payment'"
                    :class="tab === 'payment' ? 'border-indigo-500 text-indigo-600' :
                        'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                    class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-colors">
                    Bayaran Khairat
                </button>
                <button @click="tab = 'bank'"
                    :class="tab === 'bank' ? 'border-indigo-500 text-indigo-600' :
                        'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                    class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-colors">
                    Bank
                </button>
                <button @click="tab = 'subscription'"
                    :class="tab === 'subscription' ? 'border-indigo-500 text-indigo-600' :
                        'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                    class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-colors">
                    Langganan Portal
                </button>
                <button @click="tab = 'policy'"
                    :class="tab === 'policy' ? 'border-indigo-500 text-indigo-600' :
                        'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                    class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-colors">
                    Polisi Khairat
                </button>
            </nav>
        </div>

        <div class="mt-6">

            <div x-show="tab === 'masjid'" x-data="{
                isDirty: false,
                originalData: {
                    nama: '{{ $masjid->nama }}',
                    type: '{{ $masjid->type }}',
                    negeri: '{{ $masjid->negeri }}',
                    bandar: '{{ $masjid->bandar }}',
                    poskod: '{{ $masjid->poskod }}',
                    kariah: '{{ $masjid->kariah }}',
                    alamat: '{{ $masjid->alamat }}',
                    alamat2: '{{ $masjid->alamat2 }}'
                },
                checkChanges() {
                    let form = this.$refs.masjidForm;
                    let formData = new FormData(form);
                    this.isDirty = false;
                    for (let [key, value] of formData.entries()) {
                        if (this.originalData.hasOwnProperty(key) && value !== this.originalData[key]) {
                            this.isDirty = true;
                            break;
                        }
                    }
                },
                cancelUpdate() {
                    this.$refs.masjidForm.reset();
                    this.isDirty = false;
                    // This event tells all child x-data components to stop editing
                    this.$dispatch('close-all-editors');
                }
            }"
                class="bg-white shadow-sm rounded-xl border border-gray-200 overflow-hidden">

                @php
                    $details = [
                        [
                            'label' => 'Nama',
                            'db' => 'nama',
                            'value' => $masjid->nama,
                            'icon' =>
                                'M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-10V4a1 1 0 011-1h2a1 1 0 011 1v3M12 7h1m-1 4h1m-5 12v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4',
                        ],
                        [
                            'label' => 'Jenis',
                            'db' => 'type',
                            'value' => $masjid->type,
                            'icon' =>
                                'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2',
                        ],
                        [
                            'label' => 'Negeri',
                            'db' => 'negeri',
                            'value' => $masjid->negeri->nama,
                            'icon' =>
                                'M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z',
                        ],
                        [
                            'label' => 'Bandar',
                            'db' => 'bandar',
                            'value' => $masjid->bandar,
                            'icon' =>
                                'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6',
                        ],
                        [
                            'label' => 'Poskod',
                            'db' => 'poskod',
                            'value' => $masjid->poskod->poskod_num,
                            'icon' =>
                                'M12 11c0 3.517-1.009 6.799-2.753 9.571m-3.44-2.04l.054-.09A10.003 10.003 0 0012 3v8h8a10.003 10.003 0 00-9.571-7.247l-.09.054a10 10 0 01-2.04 3.44t1.284 1.284',
                        ],
                        [
                            'label' => 'Kariah',
                            'db' => 'kariah',
                            'value' => $masjid->kariah->nama,
                            'icon' =>
                                'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z',
                        ],
                    ];
                @endphp

                <form x-ref="masjidForm" @input="checkChanges" action="{{ route('masjid.update') }}" method="POST">
                    @csrf
                    @method('PUT')
 
                    <input type="hidden" name="status" value="{{ $masjid->status }}">
 
                    <div class="px-6 py-4 bg-gray-50 border-b border-gray-200 flex justify-between items-center">
                        <h3 class="text-lg font-bold text-gray-800 tracking-tight">Maklumat Utama</h3>
                        <div class="flex space-x-2">
                            <button x-show="isDirty" x-cloak @click="cancelUpdate" type="button"
                                class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 text-gray-700 text-xs font-bold uppercase rounded-lg hover:bg-gray-50 transition shadow-sm">
                                Batal
                            </button>
 
                            <button type="submit" :disabled="!isDirty"
                                :class="isDirty ? 'bg-indigo-600 hover:bg-indigo-700 text-white cursor-pointer' :
                                    'bg-gray-300 text-gray-500 cursor-not-allowed'"
                                class="inline-flex items-center px-4 py-2 text-xs font-bold uppercase rounded-lg transition shadow-sm">
                                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 13l4 4L19 7"></path>
                                </svg>
                                Simpan Perubahan
                            </button>
                        </div>
                    </div>
 
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-px bg-gray-200">
                        @foreach ($details as $item)
                            <div x-data="{ editing: false }" {{-- Only allow editing if the field is NOT 'nama' --}}
                                @if ($item['db'] !== 'nama') @click="editing = true; $nextTick(() => $refs.input.focus())"
            class="bg-white p-6 relative group cursor-pointer hover:bg-gray-50 transition-colors"
         @else
            class="bg-gray-50/50 p-6 relative group border-r border-gray-100" @endif
                                @close-all-editors.window="editing = false">
 
                                <div class="flex items-center space-x-3">
                                    <div
                                        class="p-2 {{ $item['db'] === 'nama' ? 'bg-gray-200 text-gray-500' : 'bg-indigo-50 text-indigo-600' }} rounded-lg">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="{{ $item['icon'] }}"></path>
                                        </svg>
                                    </div>
                                    <div class="flex-1">
                                        <dt class="text-xs font-semibold text-gray-400 uppercase tracking-wider">
                                            {{ $item['label'] }}
                                            @if ($item['db'] === 'nama')
                                            @endif
                                        </dt>
 
                                        <dd x-show="!editing"
                                            class="text-sm font-bold {{ $item['db'] === 'nama' ? 'text-gray-500' : 'text-gray-900' }} mt-0.5">
                                            {{ $item['value'] ?? '-' }}
                                        </dd>
 
                                        @if ($item['db'] !== 'nama')
                                            <div x-show="editing" @click.away="editing = false">
                                                @if ($item['db'] === 'type')
                                                    <select x-ref="input" name="type" @change="checkChanges"
                                                        class="mt-1 block w-full text-sm font-bold border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 p-1">
                                                        <option value="Masjid"
                                                            {{ $item['value'] == 'Masjid' ? 'selected' : '' }}>Masjid
                                                        </option>
                                                        <option value="Surau"
                                                            {{ $item['value'] == 'Surau' ? 'selected' : '' }}>Surau
                                                        </option>
                                                        <option value="Institusi"
                                                            {{ $item['value'] == 'Institusi' ? 'selected' : '' }}>Institusi
                                                        </option>
                                                        <option value="Lain-lain"
                                                            {{ $item['value'] == 'Lain-lain' ? 'selected' : '' }}>Lain-lain
                                                        </option>
                                                    </select>
                                                @else
                                                    <input x-ref="input" type="text" name="{{ $item['db'] }}"
                                                        value="{{ $item['value'] }}"
                                                        class="mt-1 block w-full text-sm font-bold border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 p-1">
                                                @endif
                                            </div>
                                        @else
                                            {{-- Hidden input so the value is still sent to the controller during update --}}
                                            <input type="hidden" name="nama" value="{{ $item['value'] }}">
                                        @endif
                                    </div>
                                </div>
 
                                {{-- Hide pencil icon for 'nama' --}}
                                @if ($item['db'] !== 'nama')
                                    <div x-show="!editing"
                                        class="absolute top-4 right-4 opacity-0 group-hover:opacity-100 transition-opacity text-gray-400">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z">
                                            </path>
                                        </svg>
                                    </div>
                                @endif
                            </div>
                        @endforeach
 
                        {{-- Alamat Penuh --}}
                        <div x-data="{ editing: false }" @click="editing = true; $nextTick(() => $refs.input.focus())"
                            @close-all-editors.window="editing = false"
                            class="bg-white p-6 md:col-span-2 lg:col-span-2 border-t border-gray-100 group relative cursor-pointer hover:bg-gray-50">
                            <div class="flex items-start space-x-3">
                                <div class="p-2 bg-indigo-50 rounded-lg text-indigo-600 mt-1">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z">
                                        </path>
                                    </svg>
                                </div>
                                <div class="flex-1">
                                    <dt class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Alamat Penuh
                                    </dt>
                                    <dd x-show="!editing" class="text-sm text-gray-900 mt-1 font-medium leading-relaxed">
                                        {{ $masjid->alamat ?? '-' }} {{ $masjid->alamat2 ? ', ' . $masjid->alamat2 : '' }}
                                    </dd>
                                    <div x-show="editing" class="space-y-2 mt-2" @click.away="editing = false">
                                        <input x-ref="input" type="text" name="alamat"
                                            value="{{ $masjid->alamat }}"
                                            class="block w-full text-sm border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 p-2">
                                        <input type="text" name="alamat2" value="{{ $masjid->alamat2 }}"
                                            class="block w-full text-sm border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 p-2">
                                    </div>
                                </div>
                            </div>
                            {{-- Pencil icon --}}
                            <div x-show="!editing"
                                class="absolute top-4 right-4 opacity-0 group-hover:opacity-100 transition-opacity text-gray-400">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z">
                                    </path>
                                </svg>
                            </div>
                        </div>
 
                        {{-- Gambar Masjid — separate form so enctype/file upload works independently --}}
                        <div class="bg-white p-6 border-t border-l border-gray-100 lg:col-span-1"
                            x-data="masjidImageUpload()" x-init="init('{{ $masjid->image_path ? asset($masjid->image_path) : '' }}')">
 
                            <div class="flex items-start space-x-3 mb-3">
                                <div class="p-2 bg-indigo-50 rounded-lg text-indigo-600 mt-1 shrink-0">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z">
                                        </path>
                                    </svg>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <dt class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">Gambar
                                        Masjid</dt>
 
                                    {{-- Preview area --}}
                                    <div class="relative group/img">
                                        <template x-if="previewUrl">
                                            <div class="relative">
                                                <img :src="previewUrl" alt="Gambar Masjid"
                                                    class="w-full h-32 object-cover rounded-lg border border-gray-200 shadow-sm">
                                                {{-- Remove / change overlay --}}
                                                <div
                                                    class="absolute inset-0 bg-black/40 opacity-0 group-hover/img:opacity-100 transition-opacity rounded-lg flex items-center justify-center gap-2">
                                                    <label
                                                        class="cursor-pointer bg-white/90 text-gray-800 text-xs font-bold px-2 py-1 rounded-md hover:bg-white transition">
                                                        Tukar
                                                        <input type="file" accept="image/jpeg,image/png,image/jpg,image/webp"
                                                            class="hidden" @change="handleFile($event)">
                                                    </label>
                                                </div>
                                            </div>
                                        </template>
 
                                        {{-- Drop zone (shown when no image) --}}
                                        <template x-if="!previewUrl">
                                            <label
                                                class="flex flex-col items-center justify-center w-full h-32 border-2 border-dashed border-gray-300 rounded-lg cursor-pointer hover:border-indigo-400 hover:bg-indigo-50/30 transition-colors"
                                                @dragover.prevent="isDragging = true"
                                                @dragleave.prevent="isDragging = false"
                                                @drop.prevent="handleDrop($event)"
                                                :class="isDragging ? 'border-indigo-500 bg-indigo-50' : ''">
                                                <svg class="w-8 h-8 text-gray-400 mb-1" fill="none"
                                                    stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="1.5"
                                                        d="M12 16v-4m0 0V8m0 4H8m4 0h4M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14">
                                                    </path>
                                                </svg>
                                                <span class="text-xs text-gray-500">Klik atau seret gambar ke sini</span>
                                                <span class="text-xs text-gray-400 mt-0.5">JPG, PNG, WEBP — maks 2MB</span>
                                                <input type="file" accept="image/jpeg,image/png,image/jpg,image/webp"
                                                    class="hidden" x-ref="fileInput" @change="handleFile($event)">
                                            </label>
                                        </template>
                                    </div>
 
                                    {{-- Upload / Save button — only visible when a new file is chosen --}}
                                    <template x-if="hasNewFile">
                                        <form method="POST" action="{{ route('masjid.update-image') }}"
                                            enctype="multipart/form-data" x-ref="imageForm" class="mt-2">
                                            @csrf
                                            @method('PUT')
                                            <input type="file" name="image_path" class="hidden" x-ref="hiddenFile">
                                            <div class="flex gap-2">
                                                <button type="button" @click="submitImage"
                                                    class="flex-1 inline-flex items-center justify-center px-3 py-1.5 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold uppercase rounded-lg transition shadow-sm">
                                                    <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2" d="M5 13l4 4L19 7"></path>
                                                    </svg>
                                                    Simpan Gambar
                                                </button>
                                                <button type="button" @click="cancelImage"
                                                    class="px-3 py-1.5 bg-white border border-gray-300 text-gray-600 text-xs font-bold uppercase rounded-lg hover:bg-gray-50 transition shadow-sm">
                                                    Batal
                                                </button>
                                            </div>
                                        </form>
                                    </template>
 
                                    {{-- Remove existing image button --}}
                                    <template x-if="!hasNewFile && originalUrl && !previewUrl">
                                        <p class="text-xs text-gray-400 mt-1 text-center">Tiada gambar disimpan.</p>
                                    </template>
                                </div>
                            </div>
                        </div>
 
                    </div>
                </form>
            </div>

            <div x-show="tab === 'payment'" class="space-y-6">
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                    <div class="px-6 py-4 bg-gray-50 border-b border-gray-200 flex justify-between items-center">
                        <h3 class="text-lg font-bold text-gray-800 flex items-center">
                            <svg class="w-5 h-5 mr-2 text-emerald-600" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                                </path>
                            </svg>
                            Tetapan Harga Khairat
                        </h3>
                        <span
                            class="px-3 py-1 bg-emerald-100 text-emerald-700 text-xs font-bold rounded-full uppercase">Kadar
                            Semasa</span>
                    </div>

                    <form method="POST" action="{{ route('masjid.update-harga') }}" class="p-6">
                        @csrf @method('PUT')

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                            <div class="p-4 bg-gray-50 rounded-lg border border-gray-100">
                                <label class="block text-sm font-bold text-gray-600 uppercase tracking-tight">Bayaran
                                    Tahunan</label>
                                <p class="text-xs text-gray-500 mb-3">Kadar yang perlu dibayar setiap tahun oleh ahli.</p>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <span class="text-gray-500 sm:text-sm font-bold">RM</span>
                                    </div>
                                    <input type="number" step="0.01" name="bayaran_tahunan"
                                        value="{{ $harga->bayaran_tahunan ?? 0 }}"
                                        class="block w-full pl-10 pr-4 py-3 text-xl font-bold text-gray-900 border-gray-300 focus:ring-emerald-500 focus:border-emerald-500 rounded-lg shadow-sm"
                                        required>
                                </div>
                            </div>

                            <div class="p-4 bg-gray-50 rounded-lg border border-gray-100">
                                <label class="block text-sm font-bold text-gray-600 uppercase tracking-tight">Yuran
                                    Pendaftaran</label>
                                <p class="text-xs text-gray-500 mb-3">Bayaran sekali sahaja untuk ahli baru mendaftar.</p>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <span class="text-gray-500 sm:text-sm font-bold">RM</span>
                                    </div>
                                    <input type="number" step="0.01" name="yuran_pendaftaran"
                                        value="{{ $harga->yuran_pendaftaran ?? 0 }}"
                                        class="block w-full pl-10 pr-4 py-3 text-xl font-bold text-gray-900 border-gray-300 focus:ring-emerald-500 focus:border-emerald-500 rounded-lg shadow-sm"
                                        required>
                                </div>
                            </div>
                        </div>

                        <div class="mt-8 flex justify-end">
                            <button type="submit"
                                class="w-full md:w-auto px-8 py-3 bg-emerald-600 text-white font-bold rounded-lg hover:bg-emerald-700 shadow-lg hover:shadow-emerald-200 transition-all duration-200">
                                Kemaskini Harga Khairat
                            </button>
                        </div>
                    </form>
                </div>

                {{-- <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                    <div class="px-6 py-4 bg-gray-50 border-b border-gray-200 flex justify-between items-center">
                        <h3 class="text-lg font-bold text-gray-800 flex items-center">
                            <svg class="w-5 h-5 mr-2 text-blue-600" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                                </path>
                            </svg>
                            Tetapan Jumlah Pengurusan Kematian (Seorang)
                        </h3>
                        <span class="px-3 py-1 bg-blue-100 text-blue-700 text-xs font-bold rounded-full uppercase">Kadar
                            Semasa</span>
                    </div>

                    <form method="POST" action="{{ route('masjid.update-sumbangan') }}" class="p-6">
                        @csrf
                        @method('PUT')

                        <div class="p-4 bg-gray-50 rounded-lg border border-gray-100">
                            <label class="block text-sm font-bold text-gray-600 uppercase tracking-tight">Tetapan
                                Kadar</label>
                            <p class="text-xs text-gray-500 mb-3">Untuk seorang ahli terima.</p>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <span class="text-gray-500 sm:text-sm font-bold">RM</span>
                                </div>
                                <input type="number" step="0.01" name="sumbangan_seorang"
                                    value="{{ $harga->sumbangan_seorang ?? 0 }}"
                                    class="block w-full pl-10 pr-4 py-3 text-xl font-bold text-gray-900 border-gray-300 focus:ring-emerald-500 focus:border-emerald-500 rounded-lg shadow-sm"
                                    required>
                            </div>
                        </div>

                        <div class="mt-8 flex justify-end">
                            <button type="submit"
                                class="w-full md:w-auto px-8 py-3 bg-blue-600 text-white font-bold rounded-lg hover:bg-blue-700 shadow-lg hover:shadow-blue-200 transition-all duration-200">
                                Kemaskini Jumlah Pengurusan Kematian
                            </button>
                        </div>
                    </form>
                </div> --}}


            </div>

            <div x-show="tab === 'bank'" class="bg-white p-6 rounded-xl shadow-md border border-gray-200"
                x-data="bankForm()" x-init="initPreview('{{ $bank->qr_path ?? '' }}')">

                <!-- Zoom Modal -->
                <div x-show="showZoomModal" x-cloak class="fixed inset-0 z-50 overflow-y-auto"
                    @keydown.escape.window="showZoomModal = false">
                    <!-- Background overlay -->
                    <div class="fixed inset-0 bg-black bg-opacity-75 transition-opacity" @click="showZoomModal = false">
                    </div>

                    <!-- Modal content -->
                    <div class="flex min-h-full items-center justify-center p-4">
                        <div class="relative bg-white rounded-xl shadow-2xl max-w-3xl w-full mx-auto transform transition-all"
                            @click.away="showZoomModal = false">

                            <!-- Header -->
                            <div class="flex items-center justify-between p-4 border-b border-gray-200">
                                <h3 class="text-lg font-bold text-gray-900">Kod QR Pembayaran</h3>
                                <button @click="showZoomModal = false"
                                    class="text-gray-400 hover:text-gray-600 transition-colors">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M6 18L18 6M6 6l12 12"></path>
                                    </svg>
                                </button>
                            </div>


                            <!-- Image container -->
                            <div class="p-6 flex flex-col items-center justify-center bg-gray-50">
                                <template x-if="zoomImageUrl">
                                    <!-- CHANGE THIS LINE - INCREASE THE SIZE -->
                                    <img :src="zoomImageUrl" alt="QR Code Zoom"
                                        class="max-w-full max-h-[90vh] object-contain rounded-lg shadow-lg">
                                    <!-- Changed from max-h-[70vh] to max-h-[90vh] -->
                                </template>

                                <!-- Loading state -->
                                <template x-if="!zoomImageUrl">
                                    <div class="w-64 h-64 flex items-center justify-center">
                                        <svg class="animate-spin h-8 w-8 text-indigo-600"
                                            xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10"
                                                stroke="currentColor" stroke-width="4"></circle>
                                            <path class="opacity-75" fill="currentColor"
                                                d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                            </path>
                                        </svg>
                                    </div>
                                </template>
                            </div>

                            <!-- Footer with download button -->
                            <div class="p-4 border-t border-gray-200 flex justify-between items-center">
                                <p class="text-sm text-gray-500" x-text="zoomImageName || 'Kod QR'"></p>
                                <a :href="zoomImageUrl" download
                                    class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white text-sm font-bold rounded-lg hover:bg-indigo-700 transition shadow-lg">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                                    </svg>
                                    Muat Turun
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                    <div class="px-6 py-4 bg-gray-50 border-b border-gray-200 flex justify-between items-center">
                        <h3 class="text-lg font-bold text-gray-800">Maklumat Perbankan & QR</h3>
                        <span
                            class="text-xs font-medium text-indigo-600 bg-indigo-50 px-3 py-1 rounded-full uppercase tracking-wider">Tetapan
                            Akaun</span>
                    </div>

                    <div class="p-6">
                        <div class="flex flex-col lg:flex-row gap-10">
                            <div class="lg:w-1/3 space-y-6">
                                <div
                                    class="relative h-48 w-full bg-gradient-to-br from-indigo-700 via-indigo-800 to-blue-900 rounded-2xl p-6 text-white shadow-xl overflow-hidden transform hover:scale-[1.02] transition-transform duration-300">
                                    <div class="relative z-10 h-full flex flex-col justify-between">
                                        <div class="flex justify-between items-start">
                                            <span
                                                class="text-sm font-medium tracking-widest opacity-80 uppercase">{{ $bank->nama_bank ?? 'SILA KEMASKINI' }}</span>
                                            <svg class="w-10 h-10 opacity-50" fill="currentColor" viewBox="0 0 24 24">
                                                <path d="M2 10h20v2H2zm0 4h20v2H2zm0-8h20v2H2zm0 12h20v2H2z" />
                                            </svg>
                                        </div>
                                        <div class="text-2xl font-mono tracking-widest">
                                            {{ $bank->no_akaun ?? '**** **** ****' }}
                                        </div>
                                        <div class="flex justify-between items-end">
                                            <div class="uppercase">
                                                <p class="text-[10px] opacity-60">Pemegang Akaun</p>
                                                <p class="text-sm font-bold truncate max-w-[180px]">
                                                    {{ $bank->nama_akaun ?? 'NAMA MASJID / SURAU' }}
                                                </p>
                                            </div>
                                            <div class="bg-yellow-400 w-10 h-7 rounded-md opacity-80 shadow-inner"></div>
                                        </div>
                                    </div>
                                    <div class="absolute -right-10 -top-10 w-40 h-40 bg-white opacity-5 rounded-full">
                                    </div>
                                    <div
                                        class="absolute -left-10 -bottom-10 w-32 h-32 bg-indigo-400 opacity-10 rounded-full">
                                    </div>
                                </div>

                                <div
                                    class="border-2 border-dashed border-gray-200 rounded-2xl p-4 flex flex-col items-center justify-center bg-gray-50">
                                    <p class="text-[10px] font-bold text-gray-400 uppercase mb-3">Kod QR Pembayaran</p>

                                    <!-- Preview Image Container with Zoom Click -->
                                    <template x-if="previewUrl">
                                        <div class="relative group cursor-pointer"
                                            @click="openZoomModal(previewUrl, fileName)">
                                            <img :src="previewUrl" alt="QR Code Preview"
                                                class="w-32 h-32 object-contain rounded-lg shadow-sm bg-white p-2 group-hover:opacity-75 transition">
                                            <!-- Zoom icon overlay -->
                                            <div
                                                class="absolute inset-0 flex items-center justify-center opacity-0 group-hover:opacity-100 transition">
                                                <div class="bg-indigo-600 rounded-full p-2 shadow-lg">
                                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m-3 0H7">
                                                        </path>
                                                    </svg>
                                                </div>
                                            </div>
                                        </div>
                                    </template>

                                    <template x-if="!previewUrl && originalQrPath">
                                        <div class="relative group cursor-pointer"
                                            @click="openZoomModal('{{ $bank->qr_path ? asset($bank->qr_path) : '' }}', 'QR Code')">
                                            <img src="{{ $bank->qr_path ? asset($bank->qr_path) : '' }}" alt="QR Code"
                                                class="w-32 h-32 object-contain rounded-lg shadow-sm bg-white p-2 group-hover:opacity-75 transition">
                                            <!-- Zoom icon overlay -->
                                            <div
                                                class="absolute inset-0 flex items-center justify-center opacity-0 group-hover:opacity-100 transition">
                                                <div class="bg-indigo-600 rounded-full p-2 shadow-lg">
                                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m-3 0H7">
                                                        </path>
                                                    </svg>
                                                </div>
                                            </div>
                                        </div>
                                    </template>

                                    <template x-if="!previewUrl && !originalQrPath">
                                        <div
                                            class="w-32 h-32 flex flex-col items-center justify-center bg-white border border-gray-100 rounded-lg text-gray-300">
                                            <svg class="w-12 h-12" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                                    d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z" />
                                            </svg>
                                            <span class="text-[10px] mt-2">Tiada QR</span>
                                        </div>
                                    </template>

                                    <!-- Zoom instruction -->
                                    <p class="text-[8px] text-gray-400 mt-2">Klik pada QR untuk zoom</p>
                                </div>
                            </div>

                            <div class="lg:w-2/3">
                                <form method="POST" action="{{ route('masjid.update-bank') }}"
                                    enctype="multipart/form-data" class="space-y-5">
                                    @csrf @method('PUT')

                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                                        <div class="md:col-span-2">
                                            <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Nama
                                                Bank</label>
                                            <input type="text" name="nama_bank" value="{{ $bank->nama_bank ?? '' }}"
                                                class="w-full rounded-lg border-gray-300 focus:ring-indigo-500 focus:border-indigo-500 shadow-sm transition"
                                                placeholder="Contoh: Maybank / CIMB / Bank Islam" required>
                                        </div>

                                        <div>
                                            <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Nama
                                                Akaun</label>
                                            <input type="text" name="nama_akaun"
                                                value="{{ $bank->nama_akaun ?? '' }}"
                                                class="w-full rounded-lg border-gray-300 focus:ring-indigo-500 focus:border-indigo-500 shadow-sm transition"
                                                placeholder="Nama penuh pemilik akaun" required>
                                        </div>

                                        <div>
                                            <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Nombor
                                                Akaun</label>
                                            <input type="text" name="no_akaun" value="{{ $bank->no_akaun ?? '' }}"
                                                class="w-full rounded-lg border-gray-300 focus:ring-indigo-500 focus:border-indigo-500 shadow-sm transition"
                                                placeholder="0000 0000 0000" required>
                                        </div>

                                        <div class="md:col-span-2">
                                            <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Muat Naik
                                                QR (DuitNow/QRIS)</label>

                                            <!-- File Upload Area with Preview -->
                                            <div class="flex items-center justify-center w-full">
                                                <label
                                                    class="flex flex-col items-center justify-center w-full h-32 border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-gray-50 hover:bg-gray-100 transition relative overflow-hidden"
                                                    :class="{ 'border-indigo-500 bg-indigo-50': isDragging }"
                                                    @dragenter.prevent="isDragging = true"
                                                    @dragleave.prevent="isDragging = false" @dragover.prevent
                                                    @drop.prevent="handleFileDrop($event)">

                                                    <!-- Preview inside upload area -->
                                                    <template x-if="!previewUrl">
                                                        <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                                            <svg class="w-8 h-8 mb-3 text-gray-400" fill="none"
                                                                stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2"
                                                                    d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12">
                                                                </path>
                                                            </svg>
                                                            <p class="mb-2 text-sm text-gray-500">
                                                                <span class="font-semibold">Klik untuk muat naik</span>
                                                                atau tarik fail ke sini
                                                            </p>
                                                            <p class="text-xs text-gray-400">PNG, JPG atau JPEG (Max: 2MB)
                                                            </p>
                                                        </div>
                                                    </template>

                                                    <template x-if="previewUrl">
                                                        <div
                                                            class="relative w-full h-full flex items-center justify-center">
                                                            <img :src="previewUrl"
                                                                class="max-h-28 max-w-full object-contain" alt="Preview">
                                                            <button type="button" @click.stop="removePreview()"
                                                                class="absolute top-1 right-1 bg-red-500 text-white rounded-full p-1 hover:bg-red-600 transition shadow-lg">
                                                                <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                                    viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                                        stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                                                </svg>
                                                            </button>
                                                        </div>
                                                    </template>

                                                    <input type="file" name="qr_path" class="hidden"
                                                        accept="image/png,image/jpg,image/jpeg"
                                                        @change="previewFile($event)" />
                                                </label>
                                            </div>
                                            <p class="text-xs text-gray-500 mt-1" x-show="fileName"
                                                x-text="'Fail dipilih: ' + fileName"></p>
                                        </div>
                                    </div>

                                    <div class="mt-8 flex justify-end">
                                        <button type="submit"
                                            class="px-8 py-3 bg-indigo-600 text-white font-bold rounded-lg hover:bg-indigo-700 shadow-lg transition-all">
                                            Simpan Maklumat Bank
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Subscription Tab --}}
            <div x-show="tab === 'subscription'" class="space-y-6 animate-in fade-in slide-in-from-bottom-4 duration-700">

                {{-- Main Container --}}
                <div class="bg-white rounded-[2.5rem] border border-slate-100 shadow-sm overflow-hidden">

                    {{-- Header Section --}}
                    <div class="p-8 pb-4 flex flex-col md:flex-row md:items-center justify-between gap-4">
                        <div>
                            <h3 class="text-2xl font-extrabold text-slate-900 tracking-tight">Status Langganan</h3>
                            <p class="text-slate-500 text-sm">Urus pelan dan lihat sejarah transaksi anda secara telus.</p>
                        </div>

                        @if ($activeSubscription)
                            <div class="flex">
                                <span
                                    class="inline-flex items-center gap-1.5 px-4 py-2 bg-emerald-50 text-emerald-700 rounded-full text-xs font-bold ring-1 ring-emerald-100">
                                    <span class="relative flex h-2 w-2">
                                        <span
                                            class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                                        <span class="relative inline-flex rounded-full h-2 w-2 bg-emerald-500"></span>
                                    </span>
                                    Langganan Aktif
                                </span>
                            </div>
                        @endif
                    </div>

                    {{-- Active Plan Hero --}}
                    @if ($activeSubscription)
                        <div class="px-8 py-6">
                            <div class="relative overflow-hidden bg-slate-900 rounded-[2rem] p-8 text-white group">
                                {{-- Decorative Background Glow --}}
                                <div
                                    class="absolute -right-20 -top-20 w-64 h-64 bg-indigo-500/20 rounded-full blur-3xl group-hover:bg-indigo-500/30 transition-colors duration-700">
                                </div>

                                <div class="relative z-10 grid grid-cols-1 lg:grid-cols-3 gap-8 items-center">
                                    <div class="lg:col-span-2">
                                        <span
                                            class="text-indigo-400 text-[10px] font-black uppercase tracking-[0.2em]">Pakej
                                            Semasa</span>
                                        <h4 class="text-4xl font-black mt-1 mb-4 italic tracking-tighter">
                                            {{ $activeSubscription->package->name ?? 'Standard' }}
                                        </h4>

                                        <div class="flex flex-wrap gap-6 text-slate-300">
                                            <div class="flex flex-col">
                                                <span class="text-[10px] uppercase font-bold text-slate-500">Tarikh
                                                    Mula</span>
                                                <span
                                                    class="text-sm font-medium text-white">{{ $activeSubscription->formatted_start }}</span>
                                            </div>
                                            <div class="flex flex-col border-l border-slate-700 pl-6">
                                                <span class="text-[10px] uppercase font-bold text-slate-500">Tarikh
                                                    Tamat</span>
                                                <span
                                                    class="text-sm font-medium text-white">{{ $activeSubscription->formatted_end }}</span>
                                            </div>
                                            <div class="flex flex-col border-l border-slate-700 pl-6">
                                                <span class="text-[10px] uppercase font-bold text-slate-500">Had Kariah</span>
                                                <span
                                                    class="text-sm font-medium text-white">{{ $activeSubscription->package->limit_kariah }} Ahli</span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="bg-white/10 backdrop-blur-md rounded-2xl p-5 border border-white/10">
                                        @php
                                            $daysLeft = (int) $activeSubscription->days_left;
                                            $totalDays = (int) ($activeSubscription->total_days ?? 365);
                                            $progress = min(100, max(0, (($totalDays - $daysLeft) / $totalDays) * 100));
                                        @endphp
                                        <div class="flex justify-between items-end mb-2">
                                            <span class="text-xs font-bold uppercase tracking-tight">Tempoh Matang</span>
                                            <span class="text-xl font-black">{{ $daysLeft }} <span
                                                    class="text-[10px] font-normal opacity-60">HARI LAGI</span></span>
                                        </div>
                                        <div class="w-full bg-white/20 rounded-full h-2 overflow-hidden">
                                            <div class="h-full bg-gradient-to-r from-indigo-400 to-cyan-400 rounded-full transition-all duration-1000"
                                                style="width: {{ $progress }}%"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

                    {{-- Transaction List --}}
                    <div class="px-8 pb-8 mt-4">
                        <div class="flex items-center justify-between mb-6">
                            <h4 class="text-xs font-black text-slate-400 uppercase tracking-widest">Transaksi Terkini</h4>
                            <div class="h-px flex-1 bg-slate-100 mx-4"></div>
                        </div>

                        <div class="space-y-3">
                            @forelse($pendingOrders->concat($approvedOrders->take(3)) as $order)
                                <div
                                    class="group flex items-center justify-between p-4 rounded-2xl border border-slate-50 hover:border-slate-200 hover:bg-slate-50/50 transition-all">
                                    <div class="flex items-center gap-4">
                                        {{-- Icon with Payment Type indicator --}}
                                        <div class="relative">
                                            <div
                                                class="w-12 h-12 flex items-center justify-center rounded-2xl bg-white border border-slate-100 shadow-sm group-hover:scale-110 transition-transform">
                                                <svg class="w-6 h-6 text-slate-400" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="1.5"
                                                        d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                                                </svg>
                                            </div>
                                        </div>

                                        <div>
                                            <div class="flex items-center gap-2">
                                                <h5 class="text-sm font-bold text-slate-800">
                                                    {{ $order->package->name ?? 'Pakej' }}</h5>
                                                {{-- NEW or RENEW label --}}
                                                @php
                                                    $isRenew = $order->payment_type === 'Renew'; // Adjust based on your actual logic
                                                @endphp
                                                <span
                                                    class="px-2 py-0.5 rounded text-[9px] font-black uppercase tracking-tighter {{ $isRenew ? 'bg-blue-100 text-blue-700' : 'bg-purple-100 text-purple-700' }}">
                                                    {{ $isRenew ? 'Renew' : 'New' }}
                                                </span>
                                            </div>
                                            <p class="text-[11px] text-slate-400">{{ $order->formatted_created }}</p>
                                        </div>
                                    </div>

                                    <div class="text-right">
                                        <p class="text-sm font-black text-slate-900 leading-tight">RM
                                            {{ number_format($order->amount, 2) }}</p>
                                        <span
                                            class="inline-flex items-center text-[10px] font-bold {{ $order->status == 'pending' ? 'text-amber-500' : 'text-emerald-500' }}">
                                            <span
                                                class="w-1 h-1 rounded-full mr-1.5 {{ $order->status == 'pending' ? 'bg-amber-500' : 'bg-emerald-500' }}"></span>
                                            {{ $order->status == 'pending' ? 'Pending' : 'Berjaya' }}
                                        </span>
                                    </div>
                                </div>
                            @empty
                                <div
                                    class="text-center py-12 bg-slate-50/50 rounded-[2rem] border-2 border-dashed border-slate-100">
                                    <p class="text-slate-400 text-xs font-bold uppercase tracking-widest">Tiada rekod
                                        dijumpai</p>
                                </div>
                            @endforelse
                        </div>
                    </div>

                    {{-- Footer Action --}}
                    <div class="p-8 bg-slate-50/50 border-t border-slate-100 flex justify-center sm:justify-end">
                        <a href="{{ route('subscription.package') }}"
                            class="w-full sm:w-auto inline-flex items-center justify-center px-10 py-4 bg-indigo-600 text-white text-xs font-bold uppercase tracking-[0.15em] rounded-2xl hover:bg-indigo-700 transition-all shadow-lg shadow-indigo-200 active:scale-[0.98]">
                            {{ $activeSubscription ? 'Naiktaraf Pelan' : 'Mulakan Langganan' }}
                            <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13 7l5 5m0 0l-5 5m5-5H6" />
                            </svg>
                        </a>
                    </div>
                </div>
            </div>

            <div x-show="tab === 'policy'" class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden"
                x-data="policyManager()" x-init="initPolicies({{ json_encode($policyHeader->sections) }})">

                <div class="px-8 py-6 bg-gray-50/50 border-b border-gray-100">
                    <h3 class="text-xl font-semibold text-gray-900 leading-tight">
                        {{ $policyHeader->big_title ?? 'Polisi Khairat' }}
                    </h3>
                    <p class="mt-1 text-sm text-gray-500">Urus dan kemaskini tetapan polisi khairat masjid anda.</p>
                </div>

                <div class="p-8 space-y-8">
                    <form method="POST" action="{{ route('masjid.update-policy-header') }}"
                        class="p-6 bg-slate-50 rounded-xl border border-slate-100 transition-all">
                        @csrf
                        @method('PUT')

                        <div class="space-y-2 mb-6">
                            <label class="block text-sm font-medium text-gray-700">Tajuk Utama Halaman</label>
                            <input type="text" name="big_title" value="{{ $policyHeader->big_title ?? '' }}"
                                class="block w-full px-4 py-2.5 text-gray-900 border-gray-200 rounded-lg focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-colors"
                                placeholder="Contoh: Polisi Khairat Kematian" @input="checkHeaderChanges">
                        </div>

                        <div class="flex items-center justify-end gap-3">
                            <button type="button" x-show="headerDirty" @click="cancelHeaderEdit"
                                class="px-4 py-2 text-sm font-medium text-gray-600 bg-white border border-gray-200 rounded-lg hover:bg-gray-50 transition-all">
                                Batal
                            </button>
                            <button type="submit" :disabled="!headerDirty"
                                :class="headerDirty ? 'bg-indigo-600 hover:bg-indigo-700 text-white shadow-md' :
                                    'bg-gray-200 text-gray-400 cursor-not-allowed'"
                                class="px-5 py-2 text-sm font-semibold rounded-lg transition-all">
                                Simpan Tajuk
                            </button>
                        </div>
                    </form>

                    <hr class="border-gray-100">

                    <div class="space-y-6">
                        <h4 class="text-sm font-bold text-gray-400 uppercase tracking-wider">Seksyen Polisi</h4>

                        @foreach ($policyHeader->sections as $index => $policy)
                            <div x-data="policySection({{ $index }})"
                                x-init='initData({{ json_encode([
                                    'title' => $policy->title,
                                    'description' => $policy->description,
                                    'id' => $policy->id,
                                ]) }})'
                                class="group">

                                <form method="POST" action="{{ route('masjid.update-policy', $policy->id) }}"
                                    class="p-6 border border-gray-100 rounded-xl hover:bg-gray-100 hover:shadow-sm transition-all bg-gray-50"
                                    @input="checkChanges">
                                    @csrf
                                    @method('PUT')

                                    <div class="grid gap-5">
                                        <div class="space-y-2">
                                            {{-- Tambah Indeks Number di sini --}}
                                            <div class="flex items-center gap-2 mb-1">
                                                <span
                                                    class="flex items-center justify-center w-6 h-6 text-[10px] font-black bg-slate-900 text-white rounded-md shadow-sm">
                                                    {{ $index + 1 }}
                                                </span>
                                                <label class="block text-sm font-medium text-gray-700">Tajuk</label>
                                            </div>

                                            <input type="text" name="title" x-model="currentData.title"
                                                class="block w-full px-4 py-2.5 border-gray-200 rounded-lg focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all"
                                                :class="{ 'border-indigo-200 bg-indigo-50/30': isDirty }">
                                        </div>

                                        <div class="space-y-2">
                                            <label class="block text-sm font-medium text-gray-700">Penerangan</label>
                                            <textarea name="description" x-model="currentData.description" rows="4"
                                                class="block w-full px-4 py-2.5 border-gray-200 rounded-lg focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all"
                                                :class="{ 'border-indigo-200 bg-indigo-50/30': isDirty }"></textarea>
                                        </div>

                                        <div class="flex justify-end items-center gap-3 pt-2">
                                            <button type="button" x-show="isDirty" @click="cancelEdit"
                                                class="px-4 py-2 text-sm font-medium text-gray-600 hover:text-gray-800 transition-colors">
                                                Set Semula
                                            </button>
                                            <button type="submit" :disabled="!isDirty"
                                                :class="isDirty ? 'bg-indigo-600 hover:bg-indigo-700 text-white shadow-sm' :
                                                    'bg-gray-100 text-gray-400 cursor-not-allowed'"
                                                class="px-6 py-2 text-sm font-semibold rounded-lg transition-all">
                                                Kemaskini Seksyen {{ $index + 1 }}
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection

<style>
    [x-cloak] {
        display: none !important;
    }
</style>

<script>
    function masjidImageUpload() {
        return {
            previewUrl: null,
            originalUrl: null,
            hasNewFile: false,
            isDragging: false,
            _file: null,
 
            init(existingUrl) {
                this.originalUrl = existingUrl || null;
                this.previewUrl = existingUrl || null;
            },
 
            handleFile(event) {
                const file = event.target.files[0];
                if (file) this._setFile(file);
            },
 
            handleDrop(event) {
                this.isDragging = false;
                const file = event.dataTransfer.files[0];
                if (file && file.type.startsWith('image/')) this._setFile(file);
            },
 
            _setFile(file) {
                if (file.size > 2 * 1024 * 1024) {
                    alert('Saiz fail melebihi 2MB. Sila pilih gambar yang lebih kecil.');
                    return;
                }
                this._file = file;
                this.previewUrl = URL.createObjectURL(file);
                this.hasNewFile = true;
            },
 
            removeImage() {
                this.previewUrl = null;
                this.hasNewFile = false;
                this._file = null;
            },
 
            cancelImage() {
                this.previewUrl = this.originalUrl;
                this.hasNewFile = false;
                this._file = null;
            },
 
            submitImage() {
                if (!this._file) return;
                // Transfer the file object into the hidden file input, then submit
                const dt = new DataTransfer();
                dt.items.add(this._file);
                this.$refs.hiddenFile.files = dt.files;
                this.$refs.imageForm.submit();
            }
        }
    }
</script>

<script>
    function bankForm() {
        return {
            previewUrl: null,
            originalQrPath: '{{ $bank->qr_path ?? '' }}',
            fileName: '',
            isDragging: false,
            showZoomModal: false,
            zoomImageUrl: null,
            zoomImageName: '',

            initPreview(qrPath) {
                this.originalQrPath = qrPath;
            },

            previewFile(event) {
                const file = event.target.files[0];
                if (file) {
                    this.fileName = file.name;
                    this.previewUrl = URL.createObjectURL(file);
                }
            },

            handleFileDrop(event) {
                this.isDragging = false;
                const file = event.dataTransfer.files[0];
                if (file && file.type.startsWith('image/')) {
                    this.fileName = file.name;
                    this.previewUrl = URL.createObjectURL(file);

                    // Set the file to the input
                    const input = this.$el.querySelector('input[type="file"]');
                    const dataTransfer = new DataTransfer();
                    dataTransfer.items.add(file);
                    input.files = dataTransfer.files;
                }
            },

            removePreview() {
                this.previewUrl = null;
                this.fileName = '';
                // Clear the file input
                const input = this.$el.querySelector('input[type="file"]');
                if (input) input.value = '';
            },

            openZoomModal(imageUrl, imageName) {
                this.zoomImageUrl = imageUrl;
                this.zoomImageName = imageName || 'Kod QR';
                this.showZoomModal = true;

                // Prevent body scrolling when modal is open
                document.body.style.overflow = 'hidden';
            },

            closeZoomModal() {
                this.showZoomModal = false;
                this.zoomImageUrl = null;
                this.zoomImageName = '';

                // Restore body scrolling
                document.body.style.overflow = '';
            }
        }
    }
</script>

<script>
    function policyManager() {
        return {
            headerDirty: false,
            originalHeaderTitle: '{{ $policyHeader->big_title ?? '' }}',

            checkHeaderChanges(event) {
                this.headerDirty = event.target.value !== this.originalHeaderTitle;
            },

            cancelHeaderEdit() {
                this.$el.querySelector('input[name="big_title"]').value = this.originalHeaderTitle;
                this.headerDirty = false;
            },

            initPolicies(policies) {
                // Store original data if needed
                window.policySections = policies;
            }
        }
    }

    function policySection(index) {
        return {
            isDirty: false,
            originalData: {
                title: '',
                description: '',
                id: null
            },
            currentData: {
                title: '',
                description: '',
                id: null
            },

            initData(data) {
                this.originalData = {
                    ...data
                };
                this.currentData = {
                    ...data
                };
            },

            checkChanges() {
                this.isDirty = (this.currentData.title !== this.originalData.title) ||
                    (this.currentData.description !== this.originalData.description);
            },

            cancelEdit() {
                this.currentData = {
                    ...this.originalData
                };
                this.isDirty = false;

                // Reset form fields
                const form = this.$el.querySelector('form');
                form.querySelector('input[name="title"]').value = this.originalData.title;
                form.querySelector('textarea[name="description"]').value = this.originalData.description;
            }
        }
    }
</script>
