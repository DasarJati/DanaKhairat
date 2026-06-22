 {{-- Seksyen Pembayaran Khairat --}}
        @if($pembayaran->count())
        <div class="mb-10">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-xl md:text-2xl font-bold text-gray-800">
                    Rekod Pembayaran Khairat
                </h2>
            </div>

            <div class="overflow-x-auto bg-white rounded-xl shadow-sm border">
                <table class="min-w-full divide-y divide-gray-200 text-sm">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left font-medium text-gray-600">Tarikh Mula</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-700">IC Penerima</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-700">Nama Penerima</th>
                            <th class="px-6 py-3 text-left font-medium text-gray-600">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-700">Dokumen</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-700">No Akaun</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-700">Jumlah Bayar (RM)</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-700">Resit</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-gray-100">
                        @foreach($pembayaran as $bayar)
                        <tr>
                     
                            <td class="px-6 py-3">
                                {{ $bayar->tarikh_kelulusan
                                        ? \Carbon\Carbon::parse($bayar->tarikh_kelulusan)->format('d/m/Y')
                                        : '-' }}
                            </td>
                            <td class="px-6 py-4 text-sm">{{ $bayar->ic_penerima ?? '-' }}</td>
                            <td class="px-6 py-4 text-sm">{{ $bayar->nama_penerima ?? '-' }}</td>
                            <td class="px-6 py-3">
                                <span class="px-2 py-1 rounded-full text-xs font-semibold
                                        {{ $bayar->status === 'SELESAI'
                                            ? 'bg-green-100 text-green-700'
                                            : 'bg-yellow-100 text-yellow-700' }}">
                                    {{ $bayar->status }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm">
                                @php $doc = $bayar->tuntutan->dokumen ?? null; @endphp

                                @if($doc)
                                    <div class="flex gap-2">

                                        @if($doc->sijil_kematian)
                                            <a href="{{ asset($doc->sijil_kematian) }}"
                                            download
                                            title="Sijil Kematian">
                                                📄
                                            </a>
                                        @endif

                                        @if($doc->ic_pewaris)
                                            <a href="{{ asset($doc->ic_pewaris) }}"
                                            download
                                            title="IC Pewaris">
                                                📄
                                            </a>
                                        @endif

                                        @if($doc->bukti_bank)
                                            <a href="{{ asset($doc->bukti_bank) }}"
                                            download
                                            title="Bukti Bank">
                                                📄
                                            </a>
                                        @endif

                                    </div>
                                @else
                                    -
                                @endif
                            </td>
                                <td class="px-6 py-4 text-sm text-gray-800">
                                    {{ $bayar->no_akaun ?? '-' }}
                                </td>

                            <td class="px-6 py-3 font-medium">
                                {{ $bayar->jumlah_bayar
                                        ? number_format($bayar->jumlah_bayar, 2)
                                        : '-' }}
                            </td>
                            <td class="px-6 py-4 text-sm">
                                @if($bayar->resit_bayaran)
                                    <a href="{{ asset($bayar->resit_bayaran) }}"
                                    target="_blank"
                                    class="text-blue-600 hover:underline">
                                        Lihat Resit
                                    </a>
                                @else
                                    <span class="text-gray-400">-</span>
                                @endif
                            </td>

                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @endif
