@extends('layouts.app')

@section('title', 'Kelulusan Tuntutan Khairat')

@section('content')

          <header>
            <div class="flex items-center mb-4">
              <a href="{{ route('butiran.ahli', $tuntutan->user_id) }}"
                class="flex items-center text-primary hover:text-secondary transition-colors mr-4">
                    <i class="fas fa-arrow-left mr-2"></i>
                    <span>Kembali ke Butiran Keluarga</span>
              </a>
            </div>
          </header>
@php
    $status = $tuntutan->status;
    $doc    = $tuntutan->dokumen;
@endphp

<div class="min-h-screen bg-gray-50 py-10">
    <div class="max-w-4xl mx-auto bg-white p-6 rounded-xl shadow-lg">

        {{-- ================= TAJUK ================= --}}
        <h2 class="text-2xl font-bold mb-4 text-gray-800">
            Borang Tuntutan Khairat (AJK)
        </h2>

        {{-- ================= STATUS ================= --}}
        <div class="mb-6">
            <span class="px-3 py-1 rounded text-white
                {{ $status === 'PENDING' ? 'bg-yellow-600' :
                   ($status === 'APPROVED' ? 'bg-green-600' : 'bg-red-600') }}">
                {{ $status }}
            </span>
        </div>

        {{-- ================= MAKLUMAT AHLI ================= --}}
        <h3 class="text-lg font-semibold mb-2 text-gray-700">Maklumat Ahli</h3>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
            <input type="text" value="{{ $tuntutan->nama_ahli }}"
                   class="border p-3 rounded w-full bg-gray-100" readonly>

            <input type="text" value="{{ $tuntutan->ic_ahli }}"
                   class="border p-3 rounded w-full bg-gray-100" readonly>
        </div>

        <div class="mb-6">
            <label class="block mb-1 text-gray-700">Tarikh Meninggal</label>
            <input type="date" value="{{ $tuntutan->tarikh_meninggal }}"
                   class="border p-3 rounded w-full bg-gray-100" readonly>
        </div>

        {{-- ================= MAKLUMAT PEWARIS ================= --}}
        <h3 class="text-lg font-semibold mb-2 text-gray-700">Maklumat Pewaris</h3>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
            <input type="text" value="{{ $tuntutan->nama_pewaris }}"
                   class="border p-3 rounded w-full bg-gray-100" readonly>

            <input type="text" value="{{ $tuntutan->ic_pewaris }}"
                   class="border p-3 rounded w-full bg-gray-100" readonly>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
            <input type="text" value="{{ $tuntutan->hubungan }}"
                   class="border p-3 rounded w-full bg-gray-100" readonly>

            <input type="text" value="{{ $tuntutan->telefon }}"
                   class="border p-3 rounded w-full bg-gray-100" readonly>
        </div>

        <div class="mb-6">
            <textarea class="border p-3 rounded w-full bg-gray-100" rows="3" readonly>
{{ $tuntutan->alamat }}
            </textarea>
        </div>

        {{-- ================= MAKLUMAT BANK ================= --}}
        <h3 class="text-lg font-semibold mb-2 text-gray-700">Maklumat Bank</h3>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
            <input type="text" value="{{ $tuntutan->nama_bank }}"
                   class="border p-3 rounded w-full bg-gray-100" readonly>

            <input type="text" value="{{ $tuntutan->no_akaun }}"
                   class="border p-3 rounded w-full bg-gray-100" readonly>

            <input type="text" value="{{ $tuntutan->nama_akaun }}"
                   class="border p-3 rounded w-full bg-gray-100" readonly>
        </div>

        {{-- ================= DOKUMEN ================= --}}
        <h3 class="text-lg font-semibold mb-3 text-gray-700">Dokumen Sokongan</h3>

        <div class="space-y-4 mb-8">

            <div>
                <p class="font-medium">Sijil Kematian</p>
                @if($doc && $doc->sijil_kematian)
                    <a href="{{ asset($doc->sijil_kematian) }}" target="_blank"
                       class="text-blue-600 underline">
                        Lihat Dokumen
                    </a>
                @else
                    <p class="text-gray-500">Tiada</p>
                @endif
            </div>

            <div>
                <p class="font-medium">IC Pewaris</p>
                @if($doc && $doc->ic_pewaris)
                    <a href="{{ asset($doc->ic_pewaris) }}" target="_blank"
                       class="text-blue-600 underline">
                        Lihat Dokumen
                    </a>
                @else
                    <p class="text-gray-500">Tiada</p>
                @endif
            </div>

            <div>
                <p class="font-medium">Bukti Bank</p>
                @if($doc && $doc->bukti_bank)
                    <a href="{{ asset($doc->bukti_bank) }}" target="_blank"
                       class="text-blue-600 underline">
                        Lihat Dokumen
                    </a>
                @else
                    <p class="text-gray-500">Tiada</p>
                @endif
            </div>

        </div>

        {{-- ================= TINDAKAN AJK ================= --}}
        @if($status === 'PENDING')
            <div class="border-t pt-6">
                <h3 class="text-lg font-semibold mb-4 text-gray-700">
                    Tindakan AJK
                </h3>

                <form action="{{ route('ajk.update', $tuntutan->id) }}"
                      method="POST" class="flex gap-4">
                    @csrf
                    @method('PUT')

                    <button name="action" value="approve"
                        class="bg-green-600 hover:bg-green-700 text-white px-6 py-3 rounded-lg font-medium">
                        ✅ Luluskan
                    </button>

                    <button name="action" value="reject"
                        class="bg-red-600 hover:bg-red-700 text-white px-6 py-3 rounded-lg font-medium">
                        ❌ Tolak
                    </button>
                </form>
            </div>
        @endif

    </div>
</div>
@endsection
