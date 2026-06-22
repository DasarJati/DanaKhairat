@foreach($tuntutan as $item)
<div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden hover:shadow-md transition-all duration-300">

    {{-- Header --}}
    <div class="bg-gradient-to-r from-red-500 to-red-600 p-4">
        <div class="flex justify-between items-center">
            <span class="text-white font-semibold">
                Tuntutan #T{{ str_pad($item->id, 6, '0', STR_PAD_LEFT) }}
            </span>

            <span class="bg-red-800 text-white text-xs px-2 py-1 rounded-full">
                {{ $item->status }}
            </span>
        </div>
    </div>

    <div class="p-6">

        {{-- Maklumat Pemohon --}}
        <div class="mb-6">
            <h3 class="font-bold text-gray-900 text-lg mb-3">Maklumat Pemohon</h3>

            <div class="space-y-2">
                <div class="flex justify-between">
                    <span class="text-gray-600">Nama:</span>
                    <span class="font-semibold">{{ $item->nama_pewaris }}</span>
                </div>

                <div class="flex justify-between">
                    <span class="text-gray-600">No. IC:</span>
                    <span class="font-semibold">{{ $item->ic_pewaris }}</span>
                </div>

                <div class="flex justify-between">
                    <span class="text-gray-600">Telefon:</span>
                    <span class="font-semibold">{{ $item->telefon }}</span>
                </div>
            </div>
        </div>

        {{-- Maklumat Tuntutan --}}
        <div class="mb-6">
            <h3 class="font-bold text-gray-900 text-lg mb-3">Butiran Tuntutan</h3>

            <div class="space-y-2">
                <div class="flex justify-between">
                    <span class="text-gray-600">Jenis Tuntutan:</span>
                    <span class="font-semibold">Kematian</span>
                </div>

                <div class="flex justify-between">
                    <span class="text-gray-600">Nama Si Mati:</span>
                    <span class="font-semibold">{{ $item->nama_ahli }}</span>
                </div>

                <div class="flex justify-between">
                    <span class="text-gray-600">Hubungan:</span>
                    <span class="font-semibold">{{ $item->hubungan }}</span>
                </div>

                <div class="flex justify-between">
                    <span class="text-gray-600">Tarikh Kematian:</span>
                    <span class="font-semibold">
                        {{ \Carbon\Carbon::parse($item->tarikh_meninggal)->format('d M Y') }}
                    </span>
                </div>
            </div>
        </div>

        {{-- Action --}}
        <div class="flex space-x-3">
            <a href="{{ route('khairat.butiran', $item->user_id) }}"
               class="flex-1 bg-gradient-to-r from-green-500 to-green-600 hover:from-green-600 hover:to-green-700 text-white py-3 rounded-xl font-semibold flex items-center justify-center gap-2">
                <i class="fas fa-check-circle"></i>
                Sahkan Tuntutan
            </a>
        </div>

    </div>
</div>
@endforeach
