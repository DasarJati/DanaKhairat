@extends('layouts.app')
@section('title', 'Butiran Tuntutan Khairat')

@section('content')
    <div class="min-h-screen bg-gray-50/50 py-6 px-4 sm:px-6 lg:px-8">
        <div class="max-w-8xl mx-auto">

            <!-- Header Navigation -->
            <div class="flex items-center justify-between mb-6 animate-fade-in">
                <a href="{{ url()->previous() }}"
                    class="group flex items-center text-gray-600 hover:text-indigo-600 transition-colors">
                    <div
                        class="w-8 h-8 rounded-full bg-white shadow-sm flex items-center justify-center mr-3 group-hover:bg-indigo-50 transition-colors">
                        <i class="fas fa-arrow-left text-sm"></i>
                    </div>
                    <span class="font-semibold text-sm">Kembali</span>
                </a>
                <div class="flex gap-2">
                    @if ($tuntutan->status == 'SUCCESS')
                        <a href="{{ route('khairat.generate.receipt', $tuntutan->id) }}" target="_blank"
                            class="px-4 py-2 bg-emerald-600 border border-emerald-600 text-white rounded-xl text-sm font-medium hover:bg-emerald-700 transition-all shadow-sm">
                            <i class="fas fa-receipt mr-2"></i> E-Resit
                        </a>
                    @endif
                    <button onclick="window.print()"
                        class="px-4 py-2 bg-white border border-gray-200 text-gray-700 rounded-xl text-sm font-medium hover:bg-gray-50 transition-all shadow-sm">
                        <i class="fas fa-print mr-2 text-gray-400"></i> Cetak
                    </button>
                </div>
            </div>

            <!-- Main Profile Card -->
            <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden mb-6">
                <div class="p-6 sm:p-8">
                    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
                        <div class="flex items-center gap-5">
                            <div
                                class="w-16 h-16 sm:w-20 sm:h-20 bg-indigo-600 rounded-2xl flex items-center justify-center text-white shadow-indigo-200 shadow-lg">
                                @if (in_array($tuntutan->type, ['AHLI', 'LUAR']))
                                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                    </svg>
                                @elseif($tuntutan->type == 'TANGGUNGAN')
                                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                    </svg>
                                @else
                                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                    </svg>
                                @endif
                            </div>
                            <div>
                                <span
                                    class="inline-block px-3 py-1 rounded-full text-[10px] font-bold bg-indigo-50 text-indigo-700 uppercase tracking-wider mb-2">
                                    {{ $tuntutan->type }}
                                </span>
                                <h1 class="text-xl sm:text-3xl font-bold text-gray-900 leading-tight">
                                    @if ($tuntutan->type == 'AHLI')
                                        {{ $tuntutan->ahli->nama ?? 'Nama Tidak Ditemui' }}
                                    @elseif($tuntutan->type == 'TANGGUNGAN')
                                        {{ $tuntutan->tanggungan->nama ?? 'Nama Tanggungan' }}
                                    @elseif($tuntutan->type == 'LUAR')
                                        {{ $tuntutan->ahli->nama ?? 'Nama Tidak Ditemui' }}
                                    @else
                                        {{ $tuntutan->nama_simpanan ?? 'Nama Tidak Ditemui' }}
                                    @endif
                                </h1>
                            </div>
                        </div>

                        <div class="w-full md:w-auto text-left md:text-right border-t md:border-t-0 pt-4 md:pt-0">
                            @php
                                $statusConfig = [
                                    'PENDING' => [
                                        'bg' => 'bg-amber-50',
                                        'text' => 'text-amber-700',
                                        'label' => 'Menunggu',
                                    ],
                                    'PROCESSING' => [
                                        'bg' => 'bg-blue-50',
                                        'text' => 'text-blue-700',
                                        'label' => 'Dalam Pengurusan',
                                    ],
                                    'APPROVED' => [
                                        'bg' => 'bg-emerald-50',
                                        'text' => 'text-emerald-700',
                                        'label' => 'Diluluskan',
                                    ],
                                    'PAID' => [
                                        'bg' => 'bg-emerald-50',
                                        'text' => 'text-emerald-700',
                                        'label' => 'Dibayar',
                                    ],
                                    'SUCCESS' => [
                                        'bg' => 'bg-gray-900',
                                        'text' => 'text-white',
                                        'label' => 'Pengurusan Jenazah Selesai',
                                    ],
                                    'REJECTED' => ['bg' => 'bg-red-50', 'text' => 'text-red-700', 'label' => 'Ditolak'],
                                ];
                                $status = $statusConfig[$tuntutan->status] ?? [
                                    'bg' => 'bg-gray-100',
                                    'text' => 'text-gray-700',
                                    'label' => $tuntutan->status,
                                ];
                            @endphp
                            <span
                                class="px-4 py-1.5 rounded-lg text-xs font-bold uppercase tracking-wider {{ $status['bg'] }} {{ $status['text'] }}">
                                {{ $status['label'] }}
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Stats Bar -->
                <div class="grid grid-cols-2 md:grid-cols-4 bg-gray-50 border-t border-gray-100">
                    <div class="p-4 border-r border-gray-100">
                        <p class="text-[10px] font-bold text-gray-400 uppercase">Tarikh Kematian</p>
                        <p class="text-sm font-semibold text-gray-800">
                            {{ \Carbon\Carbon::parse($tuntutan->date_death)->format('d/m/Y') }}</p>
                    </div>
                    <div class="p-4 md:border-r border-gray-100">
                        <p class="text-[10px] font-bold text-gray-400 uppercase">Daftar Pada</p>
                        <p class="text-sm font-semibold text-gray-800">{{ $tuntutan->created_at->format('d/m/Y') }}</p>
                    </div>
                    <div class="p-4 border-r border-gray-100">
                        <p class="text-[10px] font-bold text-gray-400 uppercase">Diluluskan Oleh</p>
                        <p class="text-sm font-semibold text-gray-800">{{ $tuntutan->user->nama ?? '—' }}</p>
                    </div>
                    <div class="p-4">
                        <p class="text-[10px] font-bold text-gray-400 uppercase">Tarikh Selesai</p>
                        <p class="text-sm font-semibold text-gray-800">
                            {{ $tuntutan->approved_at ? \Carbon\Carbon::parse($tuntutan->approved_at)->format('d/m/Y H:i A') : '—' }}
                        </p>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Left: Document & Items -->
                <div class="lg:col-span-2 space-y-6">

                    <!-- Items Table Card -->

                    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
                        <div class="p-6 border-b border-gray-50 flex justify-between items-center">
                            <h3 class="font-bold text-gray-900 flex items-center">
                                <i class="fas fa-list-ul mr-3 text-indigo-500"></i> Senarai Kos
                            </h3>
                            @if ($tuntutan->status == 'PROCESSING')
                                <button
                                    onclick="openStatusModal({{ $tuntutan->id }}, '{{ addslashes($deceasedName) }}', '{{ route('khairat.update.status.tuntutan', $tuntutan->id) }}')"
                                    class="text-xs bg-emerald-600 hover:bg-emerald-700 text-white px-4 py-2 rounded-lg font-bold transition">
                                    Kemaskini Selesai
                                </button>
                            @endif
                        </div>
                        <div class="overflow-x-auto">
                            <table class="w-full text-left border-collapse">
                                <thead>
                                    <tr class="bg-gray-50/50 text-[11px] uppercase tracking-widest text-gray-500">
                                        <th class="px-6 py-4 font-bold">Item</th>
                                        <th class="px-6 py-4 font-bold text-right">Jumlah</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-50">
                                    @foreach ($tuntutan->items as $item)
                                        <tr>
                                            <td class="px-6 py-4">
                                                <p class="font-semibold text-gray-800 text-sm">{{ $item->item_label }}
                                                </p>
                                                {{-- <p class="text-xs text-gray-500">{{ $item->description }}</p> --}}
                                            </td>
                                            <td class="px-6 py-4 text-right font-bold text-gray-800">
                                                RM {{ number_format($item->amount, 2) }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot class="divide-y bg-emerald-50/50">
                                    <tr>
                                        <td class="px-6 py-4 font-bold text-gray-600">Jumlah Keseluruhan</td>
                                        <td class="px-6 py-4 text-right font-bold text-emerald-700 text-md">
                                            RM {{ number_format($tuntutan->items->sum('amount'), 2) }}
                                        </td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>


                    <!-- Documents -->
                    <div class="bg-white rounded-3xl p-6 shadow-sm border border-gray-100">
                        <h3 class="font-bold text-gray-900 mb-6 flex items-center">
                            <i class="fas fa-file-alt mr-3 text-indigo-500"></i> Dokumen Sokongan
                        </h3>
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                            @foreach ([['Sijil Mati', 'death_certificate'], ['Polis', 'police_report'], ['Lain-lain', 'other_report']] as [$label, $field])
                                <div
                                    class="p-4 rounded-2xl border border-gray-100 bg-gray-50 flex flex-col items-center text-center">
                                    <span class="text-2xl mb-2">{{ $tuntutan->$field ? '📄' : '❌' }}</span>
                                    <p class="text-[10px] font-bold text-gray-400 uppercase">{{ $label }}</p>
                                    @if ($tuntutan->$field)
                                        @php
                                            // Check if it's already a full URL (S3)
$fileUrl = $tuntutan->$field;
if (!filter_var($fileUrl, FILTER_VALIDATE_URL)) {
    // If not a URL, assume it's a local path
                                                $fileUrl = asset('storage/' . $fileUrl);
                                            }
                                        @endphp
                                        <a href="{{ $fileUrl }}" target="_blank"
                                            class="mt-2 text-xs font-bold text-indigo-600 hover:underline">Lihat Fail</a>
                                    @else
                                        <span class="mt-2 text-xs text-gray-400 italic">Tiada fail</span>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- Right Column -->
                <div class="space-y-6">
                    <!-- Notes -->
                    <div class="bg-white rounded-3xl p-6 shadow-sm border border-gray-100">
                        <h3 class="font-bold text-gray-900 mb-4 flex items-center text-sm">
                            <i class="fas fa-pen-fancy mr-3 text-amber-500"></i> Nota
                        </h3>
                        <div class="p-4 bg-amber-50/50 rounded-2xl border border-amber-100">
                            <p class="text-sm text-amber-900 leading-relaxed italic">
                                "{{ $tuntutan->note ?: 'Tiada sebarang catatan direkodkan.' }}"
                            </p>
                        </div>
                    </div>

                    <!-- Help Card -->
                    <div
                        class="bg-indigo-700 rounded-3xl p-6 text-white relative overflow-hidden shadow-lg shadow-indigo-200">
                        <div class="relative z-10">
                            <h4 class="font-bold text-lg mb-2">Perlu Bantuan?</h4>
                            <p class="text-indigo-100 text-xs leading-relaxed mb-6">Hubungi pentadbiran masjid untuk
                                pembetulan maklumat rekod ini.</p>
                            <a href="mailto:support@masjid.com"
                                class="block w-full py-3 bg-white text-indigo-700 text-center rounded-xl font-bold text-sm hover:bg-indigo-50 transition-colors">
                                Hubungi Kami
                            </a>
                        </div>
                        <div class="absolute -right-4 -bottom-4 w-24 h-24 bg-white/10 rounded-full blur-2xl"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Status Update Modal -->
    <div id="statusUpdateModal"
        class="fixed inset-0 z-50 hidden bg-gray-900/60 backdrop-blur-sm flex items-center justify-center p-4">
        <div class="bg-white rounded-3xl w-full max-w-md max-h-[90vh] overflow-y-auto shadow-2xl">
            <div class="sticky top-0 bg-white rounded-t-3xl px-6 pt-6 pb-4 border-b border-gray-100">
                <div class="flex justify-between items-center">
                    <h2 class="text-xl font-bold text-gray-800">Kemaskini Status Pengurusan</h2>
                    <button onclick="closeStatusModal()" class="text-gray-400 hover:text-gray-600 transition-colors">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>
                <p class="text-xs text-gray-500 mt-1" id="modalDeceasedName"></p>
            </div>

            <form id="statusUpdateForm" method="POST" action="" class="p-6">
                @csrf
                @method('PUT')
                <input type="hidden" name="tuntutan_id" id="status_tuntutan_id">

                <!-- Status Selection -->
                <div class="mb-6">
                    <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Status Pengurusan</label>
                    <select name="status" id="status_select"
                        class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-indigo-500 outline-none transition-all"
                        required>
                        <option value="">Pilih Status</option>
                        <option value="PROCESSING">Dalam Pengurusan</option>
                        <option value="SUCCESS">Selesai / Success</option>
                    </select>
                </div>

                <!-- Items Section - Shown when SUCCESS is selected -->
                <div id="itemsSectionModal" class="hidden mb-6">
                    <label class="block text-xs font-bold text-gray-500 uppercase mb-3">Perincian Item & Bayaran</label>

                    <div class="bg-gray-50 rounded-xl p-4 space-y-3 border border-gray-200">
                        <div class="flex items-center gap-3">
                            <input type="checkbox" name="items[]" value="pengurusan_jenazah"
                                onchange="toggleAmountInputModal(this, 'amount_pengurusan_jenazah_modal')"
                                class="w-5 h-5 text-emerald-600 rounded focus:ring-emerald-500">
                            <label class="flex-1 text-gray-700 font-medium text-sm">Pengurusan Jenazah</label>
                            <input type="number" name="amount_pengurusan_jenazah" id="amount_pengurusan_jenazah_modal"
                                placeholder="RM 0.00" disabled
                                class="w-36 px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-emerald-500 disabled:bg-gray-100">
                        </div>

                        <div class="flex items-center gap-3">
                            <input type="checkbox" name="items[]" value="pengangkutan_jenazah"
                                onchange="toggleAmountInputModal(this, 'amount_pengangkutan_jenazah_modal')"
                                class="w-5 h-5 text-emerald-600 rounded focus:ring-emerald-500">
                            <label class="flex-1 text-gray-700 font-medium text-sm">Van Jenazah</label>
                            <input type="number" name="amount_pengangkutan_jenazah"
                                id="amount_pengangkutan_jenazah_modal" placeholder="RM 0.00" disabled
                                class="w-36 px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-emerald-500 disabled:bg-gray-100">
                        </div>

                        <div class="flex items-center gap-3">
                            <input type="checkbox" name="items[]" value="tanah_perkuburan"
                                onchange="toggleAmountInputModal(this, 'amount_tanah_perkuburan_modal')"
                                class="w-5 h-5 text-emerald-600 rounded focus:ring-emerald-500">
                            <label class="flex-1 text-gray-700 font-medium text-sm">Gali Kubur</label>
                            <input type="number" name="amount_tanah_perkuburan" id="amount_tanah_perkuburan_modal"
                                placeholder="RM 0.00" disabled
                                class="w-36 px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-emerald-500 disabled:bg-gray-100">
                        </div>

                        <div class="flex items-center gap-3">
                            <input type="checkbox" name="items[]" value="kain_kafan"
                                onchange="toggleAmountInputModal(this, 'amount_kain_kafan_modal')"
                                class="w-5 h-5 text-emerald-600 rounded focus:ring-emerald-500">
                            <label class="flex-1 text-gray-700 font-medium text-sm">Kain Kafan</label>
                            <input type="number" name="amount_kain_kafan" id="amount_kain_kafan_modal"
                                placeholder="RM 0.00" disabled
                                class="w-36 px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-emerald-500 disabled:bg-gray-100">
                        </div>

                        <div class="flex items-center gap-3">
                            <input type="checkbox" name="items[]" value="air_mandian"
                                onchange="toggleAmountInputModal(this, 'amount_air_mandian_modal')"
                                class="w-5 h-5 text-emerald-600 rounded focus:ring-emerald-500">
                            <label class="flex-1 text-gray-700 font-medium text-sm">Air / Mandian</label>
                            <input type="number" name="amount_air_mandian" id="amount_air_mandian_modal"
                                placeholder="RM 0.00" disabled
                                class="w-36 px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-emerald-500 disabled:bg-gray-100">
                        </div>

                        <div class="flex items-center gap-3">
                            <input type="checkbox" name="items[]" value="imam_bilal"
                                onchange="toggleAmountInputModal(this, 'amount_imam_bilal_modal')"
                                class="w-5 h-5 text-emerald-600 rounded focus:ring-emerald-500">
                            <label class="flex-1 text-gray-700 font-medium text-sm">Imam / Bilal</label>
                            <input type="number" name="amount_imam_bilal" id="amount_imam_bilal_modal"
                                placeholder="RM 0.00" disabled
                                class="w-36 px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-emerald-500 disabled:bg-gray-100">
                        </div>

                        <div class="flex items-center gap-3">
                            <input type="checkbox" name="items[]" value="lain_lain"
                                onchange="toggleAmountInputModal(this, 'amount_lain_lain_modal'); toggleLainLainTextModal()"
                                class="w-5 h-5 text-emerald-600 rounded focus:ring-emerald-500">
                            <label class="flex-1 text-gray-700 font-medium text-sm">Lain-lain</label>
                            <input type="text" name="lain_lain_text" id="lain_lain_text_modal"
                                placeholder="Nyatakan item" disabled
                                class="hidden w-28 px-2 py-2 border border-gray-300 rounded-lg text-sm">
                            <input type="number" name="amount_lain_lain" id="amount_lain_lain_modal"
                                placeholder="RM 0.00" disabled
                                class="w-36 px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-emerald-500 disabled:bg-gray-100">
                        </div>
                    </div>

                    <div class="bg-emerald-50 rounded-xl p-4 mt-4 border border-emerald-200">
                        <div class="flex justify-between items-center">
                            <span class="font-bold text-gray-700 text-sm">Jumlah Keseluruhan:</span>
                            <span id="totalAmountDisplayModal" class="text-xl font-bold text-emerald-600">RM 0.00</span>
                        </div>
                        <input type="hidden" name="total_amount" id="total_amount_modal" value="0">
                    </div>
                </div>

                <div class="mb-6">
                    <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Catatan / Keterangan</label>
                    <textarea name="note" id="status_note" rows="3"
                        class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-indigo-500 outline-none transition-all"
                        placeholder="Contoh: Selesai diuruskan..."></textarea>
                </div>

                <div class="flex gap-3">
                    <button type="button" onclick="closeStatusModal()"
                        class="flex-1 py-3 text-sm font-bold text-gray-500 hover:bg-gray-50 rounded-xl transition-colors">
                        Batal
                    </button>
                    <button type="submit"
                        class="flex-1 py-3 text-sm font-bold bg-indigo-600 text-white rounded-xl shadow-lg shadow-indigo-100 hover:bg-indigo-700 transition-all">
                        Kemaskini Status
                    </button>
                </div>
            </form>
        </div>
    </div>

    <style>
        @keyframes fade-in {
            from {
                opacity: 0;
                transform: translateY(5px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .animate-fade-in {
            animation: fade-in 0.4s ease-out;
        }

        @media print {

            .no-print,
            nav,
            .flex.gap-2,
            a[href*="previous"] {
                display: none !important;
            }

            .max-w-6xl {
                max-width: 100%;
            }

            body {
                background: white;
            }

            .shadow-sm,
            .shadow-lg {
                box-shadow: none !important;
                border: 1px solid #eee !important;
            }
        }
    </style>

    <script>
        // Status Modal Functions
        let currentTuntutanId = null;
        let currentUpdateUrl = null;

        function openStatusModal(tuntutanId, userName, updateUrl) {
            currentTuntutanId = tuntutanId;
            currentUpdateUrl = updateUrl;

            document.getElementById('statusUpdateForm').action = updateUrl;
            document.getElementById('status_tuntutan_id').value = tuntutanId;
            document.getElementById('modalDeceasedName').innerHTML = '<i class="fas fa-user mr-1"></i> ' + userName;

            document.getElementById('statusUpdateForm').reset();
            document.getElementById('itemsSectionModal').classList.add('hidden');
            resetItemsAndAmountsModal();

            document.getElementById('statusUpdateModal').classList.remove('hidden');
        }

        function closeStatusModal() {
            document.getElementById('statusUpdateModal').classList.add('hidden');
            currentTuntutanId = null;
            currentUpdateUrl = null;
        }

        // Show/hide items section based on status selection
        document.getElementById('status_select')?.addEventListener('change', function() {
            const itemsSection = document.getElementById('itemsSectionModal');
            if (this.value === 'SUCCESS') {
                itemsSection.classList.remove('hidden');
            } else {
                itemsSection.classList.add('hidden');
                resetItemsAndAmountsModal();
            }
        });

        function toggleAmountInputModal(checkbox, inputId) {
            const amountInput = document.getElementById(inputId);
            if (amountInput) {
                if (checkbox.checked) {
                    amountInput.disabled = false;
                    amountInput.classList.remove('bg-gray-100');
                    amountInput.classList.add('bg-white');
                } else {
                    amountInput.disabled = true;
                    amountInput.value = '';
                    amountInput.classList.add('bg-gray-100');
                    amountInput.classList.remove('bg-white');
                }
            }
            calculateTotalModal();
        }

        function toggleLainLainTextModal() {
            const lainLainCheckbox = document.querySelector('#itemsSectionModal input[value="lain_lain"]');
            const lainLainText = document.getElementById('lain_lain_text_modal');

            if (lainLainCheckbox && lainLainCheckbox.checked) {
                lainLainText.classList.remove('hidden');
                lainLainText.disabled = false;
            } else {
                lainLainText.classList.add('hidden');
                lainLainText.disabled = true;
                lainLainText.value = '';
            }
        }

        function calculateTotalModal() {
            let total = 0;
            const amountInputs = document.querySelectorAll('#itemsSectionModal input[type="number"]');

            amountInputs.forEach(input => {
                if (!input.disabled && input.value && !isNaN(parseFloat(input.value))) {
                    total += parseFloat(input.value);
                }
            });

            const totalDisplay = document.getElementById('totalAmountDisplayModal');
            const totalHidden = document.getElementById('total_amount_modal');

            if (totalDisplay) {
                totalDisplay.textContent = 'RM ' + total.toFixed(2);
            }
            if (totalHidden) {
                totalHidden.value = total;
            }
        }

        function resetItemsAndAmountsModal() {
            const checkboxes = document.querySelectorAll('#itemsSectionModal input[type="checkbox"]');
            checkboxes.forEach(checkbox => {
                checkbox.checked = false;
                checkbox.dispatchEvent(new Event('change'));
            });
            calculateTotalModal();
        }

        // Form submission handler
        document.getElementById('statusUpdateForm')?.addEventListener('submit', async function(e) {
            e.preventDefault();

            const status = document.getElementById('status_select').value;

            if (status === 'SUCCESS') {
                const checkedItems = document.querySelectorAll(
                    '#itemsSectionModal input[type="checkbox"]:checked');
                if (checkedItems.length === 0) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Perhatian!',
                        text: 'Sila pilih sekurang-kurangnya satu item untuk Pengurusan yang selesai.',
                        confirmButtonColor: '#d33'
                    });
                    return false;
                }

                for (const checkbox of checkedItems) {
                    const itemValue = checkbox.value;
                    let amountInputId = '';

                    switch (itemValue) {
                        case 'pengurusan_jenazah':
                            amountInputId = 'amount_pengurusan_jenazah_modal';
                            break;
                        case 'pengangkutan_jenazah':
                            amountInputId = 'amount_pengangkutan_jenazah_modal';
                            break;
                        case 'tanah_perkuburan':
                            amountInputId = 'amount_tanah_perkuburan_modal';
                            break;
                        case 'kain_kafan':
                            amountInputId = 'amount_kain_kafan_modal';
                            break;
                        case 'air_mandian':
                            amountInputId = 'amount_air_mandian_modal';
                            break;
                        case 'imam_bilal':
                            amountInputId = 'amount_imam_bilal_modal';
                            break;
                        case 'lain_lain':
                            amountInputId = 'amount_lain_lain_modal';
                            break;
                    }

                    const amountInput = document.getElementById(amountInputId);
                    if (!amountInput.value || parseFloat(amountInput.value) <= 0) {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Perhatian!',
                            text: 'Sila masukkan jumlah bayaran untuk setiap item yang dipilih.',
                            confirmButtonColor: '#d33'
                        });
                        amountInput?.focus();
                        return false;
                    }
                }
            }

            const formData = new FormData(this);
            const submitBtn = this.querySelector('button[type="submit"]');
            const originalText = submitBtn.innerHTML;

            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Memproses...';

            try {
                const response = await fetch(this.action, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute(
                            'content'),
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });

                const result = await response.json();

                if (result.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Berjaya!',
                        text: result.message,
                        confirmButtonColor: '#3085d6'
                    }).then(() => {
                        location.reload();
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Ralat!',
                        text: result.message || 'Gagal mengemaskini status',
                        confirmButtonColor: '#d33'
                    });
                }
            } catch (error) {
                Swal.fire({
                    icon: 'error',
                    title: 'Ralat!',
                    text: 'Ralat berlaku. Sila cuba lagi.',
                    confirmButtonColor: '#d33'
                });
            } finally {
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalText;
            }
        });
    </script>
@endsection
