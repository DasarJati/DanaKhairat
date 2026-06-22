@extends ('layouts.app')
@section('content')
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <body class="bg-gray-100">

        <div class="container mx-auto px-4 py-8">

            <!-- Statistics Cards -->
            <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-6 mb-8">
                <div
                    class="group relative bg-white border border-slate-100 rounded-2xl p-6 shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all duration-300 overflow-hidden">

                    <div
                        class="absolute -right-4 -top-4 w-24 h-24 bg-indigo-50 rounded-full blur-3xl group-hover:bg-indigo-100 transition-colors">
                    </div>

                    <div class="relative flex items-center justify-between">
                        <div>
                            <p class="text-xs font-bold text-slate-400 uppercase tracking-widest">
                                Jumlah Rekod
                            </p>

                            <h3 class="text-4xl font-extrabold text-slate-800 mt-2 tracking-tight">
                                {{ $totalCount }}
                            </h3>


                        </div>

                        <div
                            class="flex items-center justify-center w-14 h-14 bg-gradient-to-br from-indigo-50 to-blue-100 border border-indigo-100 rounded-xl shadow-inner group-hover:scale-110 transition-transform duration-300">
                            <i class="fas fa-layer-group text-indigo-600 text-xl"></i>
                        </div>
                    </div>
                </div>

                <div
                    class="group relative bg-white border border-slate-100 rounded-2xl p-6 shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all duration-300 overflow-hidden">
                    <div
                        class="absolute -right-4 -top-4 w-24 h-24 bg-yellow-50 rounded-full blur-3xl group-hover:bg-yellow-100 transition-colors">
                    </div>

                    <div class="relative flex items-center justify-between">
                        <div>
                            <p class="text-xs font-bold text-slate-400 uppercase tracking-widest">Dalam Pengurusan</p>
                            <h3 class="text-4xl font-extrabold text-slate-800 mt-2 tracking-tight">
                                {{ $progressCount }}
                            </h3>

                        </div>

                        <div
                            class="flex items-center justify-center w-14 h-14 bg-gradient-to-br from-yellow-50 to-orange-100 border border-yellow-100 rounded-xl shadow-inner group-hover:scale-110 transition-transform duration-300">
                            <i class="fas fa-clock text-yellow-600 text-xl"></i>
                        </div>
                    </div>
                </div>

                <div
                    class="group relative bg-white border border-slate-100 rounded-2xl p-6 shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all duration-300 overflow-hidden">
                    <div
                        class="absolute -right-4 -top-4 w-24 h-24 bg-green-50 rounded-full blur-3xl group-hover:bg-green-100 transition-colors">
                    </div>

                    <div class="relative flex items-center justify-between">
                        <div>
                            <p class="text-xs font-bold text-slate-400 uppercase tracking-widest">Pengurusan Selesai</p>
                            <h3 class="text-4xl font-extrabold text-slate-800 mt-2 tracking-tight">
                                {{ $approvedCount }}
                            </h3>

                        </div>

                        <div
                            class="flex items-center justify-center w-14 h-14 bg-gradient-to-br from-green-50 to-emerald-100 border border-green-100 rounded-xl shadow-inner group-hover:scale-110 transition-transform duration-300">
                            <i class="fas fa-check-circle text-green-600 text-xl"></i>
                        </div>
                    </div>
                </div>

                {{-- <div
                    class="group relative bg-white border border-slate-100 rounded-2xl p-6 shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all duration-300 overflow-hidden">
                    <div
                        class="absolute -right-4 -top-4 w-24 h-24 bg-red-50 rounded-full blur-3xl group-hover:bg-red-100 transition-colors">
                    </div>

                    <div class="relative flex items-center justify-between">
                        <div>
                            <p class="text-xs font-bold text-slate-400 uppercase tracking-widest">Dalam Pengurusan</p>
                            <h3 class="text-4xl font-extrabold text-slate-800 mt-2 tracking-tight">
                                {{ $rejectedCount }}
                            </h3>

                        </div>

                        <div
                            class="flex items-center justify-center w-14 h-14 bg-gradient-to-br from-red-50 to-rose-100 border border-red-100 rounded-xl shadow-inner group-hover:scale-110 transition-transform duration-300">
                            <i class="fas fa-times-circle text-red-600 text-xl"></i>
                        </div>
                    </div>
                </div> --}}
            </div>

            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6 mb-6">
                <div class="flex flex-col lg:flex-row justify-between items-end gap-6">
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 w-full lg:flex-1">
                        <div class="space-y-2">
                            <div class="relative group">
                                <i
                                    class="fas fa-search absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 group-focus-within:text-blue-500 transition-colors"></i>
                                <input type="text" id="searchInput" placeholder="Taip nama atau IC..."
                                    class="w-full pl-11 pr-4 py-2.5 bg-slate-50 border-none rounded-xl focus:ring-2 focus:ring-blue-500/20 focus:bg-white transition-all ring-1 ring-slate-200">
                            </div>
                        </div>

                        <div class="space-y-2">
                            <select id="statusFilter"
                                class="w-full px-4 py-2.5 bg-slate-50 border-none rounded-xl focus:ring-2 focus:ring-blue-500/20 focus:bg-white transition-all ring-1 ring-slate-200 text-slate-600">
                                <option value="all">Semua Status</option>
                                <option value="PENDING">Pending</option>
                                <option value="APPROVED">Approved</option>
                                <option value="REJECTED">Rejected</option>
                                <option value="SUCCESS">Selesai</option>
                                <option value="PROCESSING">Dalam Pengurusan</option>
                            </select>
                        </div>

                        <div class="space-y-2">
                            <select id="typeFilter"
                                class="w-full px-4 py-2.5 bg-slate-50 border-none rounded-xl focus:ring-2 focus:ring-blue-500/20 focus:bg-white transition-all ring-1 ring-slate-200 text-slate-600">
                                <option value="all">Semua Jenis</option>
                                <option value="AHLI">Ahli</option>
                                <option value="TANGGUNGAN">Tanggungan</option>
                                <option value="LUAR">Bukan Ahli</option>
                            </select>
                        </div>

                        <div class="space-y-2">
                            <input type="date" id="dateFilter"
                                class="w-full px-4 py-2.5 bg-slate-50 border-none rounded-xl focus:ring-2 focus:ring-blue-500/20 focus:bg-white transition-all ring-1 ring-slate-200 text-slate-600">
                        </div>
                    </div>

                    <div class="flex flex-col sm:flex-row gap-3 w-full lg:w-auto">
                        <button id="resetFilterBtn"
                            class="order-2 sm:order-1 px-5 py-2.5 text-sm font-semibold text-slate-500 bg-slate-100 hover:bg-slate-200 rounded-xl transition-colors flex items-center justify-center gap-2">
                            <i class="fas fa-undo-alt text-xs"></i> Reset
                        </button>
                        <button id="addDeathRecordBtn"
                            class="order-1 sm:order-2 px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-xl shadow-lg shadow-blue-200 hover:shadow-blue-300 transition-all flex items-center justify-center gap-2">
                            <i class="fas fa-plus-circle"></i> Tambah Rekod
                        </button>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full">
                        <thead>
                            <tr class="bg-slate-50/50 border-b border-slate-100">
                                <th class="px-6 py-4 text-left text-xs font-bold text-slate-500 uppercase tracking-widest">
                                    No</th>
                                <th class="px-6 py-4 text-left text-xs font-bold text-slate-500 uppercase tracking-widest">
                                    Nama / No. IC</th>
                                <th class="px-6 py-4 text-left text-xs font-bold text-slate-500 uppercase tracking-widest">
                                    Jenis</th>
                                <th class="px-6 py-4 text-left text-xs font-bold text-slate-500 uppercase tracking-widest">
                                    Tarikh</th>
                                <th class="px-6 py-4 text-left text-xs font-bold text-slate-500 uppercase tracking-widest">
                                    Status</th>
                                <th class="px-6 py-4 text-left text-xs font-bold text-slate-500 uppercase tracking-widest">
                                    Jumlah</th>
                                <th
                                    class="px-6 py-4 text-center text-xs font-bold text-slate-500 uppercase tracking-widest">
                                    Tindakan</th>
                            </tr>
                        </thead>
                        <tbody id="tuntutanTableBody" class="divide-y divide-slate-50">
                            @forelse($tuntutan as $index => $item)
                                <tr class="hover:bg-gray-50/50 transition-all duration-150"
                                    data-status="{{ $item->status }}" data-type="{{ $item->type }}"
                                    data-date="{{ \Carbon\Carbon::parse($item->date_death)->format('Y-m-d') }}">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-slate-400">
                                        {{ $index + 1 }}</td>

                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex flex-col gap-1">
                                            <div class="text-sm font-medium text-slate-700">
                                                <div class="flex items-center">
                                                    <i class="fas fa-user-alt mr-1 text-indigo-500"></i>
                                                    @if ($item->type == 'AHLI' && $item->ahli)
                                                        {{ $item->ahli->nama }}
                                                    @elseif($item->type == 'TANGGUNGAN' && $item->tanggungan)
                                                        <div class="flex flex-col gap-1">
                                                            <div class="text-sm font-semi text-slate-700">
                                                                {{ $item->tanggungan->nama }}
                                                            </div>
                                                            

                                                        </div>
                                                    @elseif($item->type == 'LUAR')
                                                        {{ $item->ahli->nama }}
                                                    @else
                                                        {{ $item->nama_ahli ?? 'N/A' }}
                                                    @endif
                                                </div>
                                                <div>
                                                    <i class="far fa-id-card mr-1 text-fuchsia-700"></i>
                                                    @if ($item->type == 'AHLI' && $item->ahli)
                                                        {{ $item->ahli->ic ?? 'N/A' }}
                                                    @elseif($item->type == 'TANGGUNGAN' && $item->tanggungan)
                                                        {{ $item->tanggungan->ic_number ?? 'N/A' }}
                                                        @if ($item->ahli)
                                                                <span
                                                                    class="block text-[10px] font-medium text-slate-400 uppercase leading-none">
                                                                    Ahli: {{ $item->ahli->nama }}
                                                                </span>
                                                            @endif
                                                    @elseif($item->type == 'LUAR')
                                                        {{ $item->ahli->ic ?? ($item->ic ?? 'N/A') }}
                                                    @else
                                                        {{ $item->ahli->ic ?? 'N/A' }}
                                                    @endif
                                                </div>
                                            </div>

                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-500">
                                        @if ($item->type == 'AHLI')
                                            <span
                                                class="bg-blue-100 text-blue-800 text-xs font-medium px-2.5 py-0.5 rounded">Ahli</span>
                                        @elseif($item->type == 'TANGGUNGAN')
                                            <span
                                                class="bg-green-100 text-green-800 text-xs font-medium px-2.5 py-0.5 rounded">Tanggungan</span>
                                        @elseif($item->type == 'LUAR')
                                            <span
                                                class="bg-yellow-100 text-yellow-800 text-xs font-medium px-2.5 py-0.5 rounded">Bukan
                                                Ahli</span>
                                        @else
                                            N/A
                                        @endif
                                    </td>
                                    <td class="flex flex-col px-6 py-4 whitespace-nowrap text-sm text-slate-700 font-medium">
                                        <div>
                                            {{ $item->created_at ? \Carbon\Carbon::parse($item->created_at)->format('d/m/Y') : '-' }}
                                        </div>
                                        <div class="flex items-center">
                                            <i class="far fa-clock mr-1 text-gray-700"></i>
                                            {{ $item->created_at ? \Carbon\Carbon::parse($item->created_at)->format('h:i A') : '-' }}
                                        </div>
                                    </td>

                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @php
                                            $statusClasses = [
                                                'APPROVED' => 'bg-emerald-50 text-emerald-600 border-emerald-100',
                                                'SUCCESS' => 'bg-emerald-600 text-emerald-100 border-emerald-200',
                                                'REJECT' => 'bg-rose-50 text-rose-600 border-rose-100',
                                                'PAID' => 'bg-sky-50 text-sky-600 border-sky-100',
                                                'PENDING' => 'bg-amber-50 text-amber-600 border-amber-100',
                                            ];
                                            $currentClass = $statusClasses[$item->status] ?? $statusClasses['PENDING'];
                                        @endphp
                                        <span class="px-3 py-1 text-xs font-bold rounded-full border {{ $currentClass }}">
                                            {{-- Status Label Logic --}}
                                            @if (in_array($item->status, ['APPROVED']))
                                                Diluluskan
                                            @elseif($item->status == 'SUCCESS')
                                                Selesai
                                            @elseif($item->status == 'REJECT')
                                                Ditolak
                                            @elseif($item->status == 'PROCESSING')
                                                Pengurusan
                                            @else
                                                Tiada
                                            @endif
                                        </span>
                                    </td>

                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="text-sm font-bold text-slate-800">RM
                                            {{ number_format($item->amount ? $item->amount : 0, 2) }}</span>
                                    </td>

                                    <td class="px-6 py-4 whitespace-nowrap text-center">
                                        <div class="flex items-center justify-center gap-2">
                                            <button
                                                onclick="viewDeathRecord('{{ $item->type }}', {{ $item->type == 'AHLI' ? $item->ahli_id ?? $item->ahli->id : ($item->type == 'TANGGUNGAN' ? $item->tanggungan_id ?? $item->tanggungan->id : $item->ahli_id ?? $item->ahli->id) }})"
                                                class="p-2 bg-gray-50 text-gray-500 rounded-lg hover:bg-blue-100 hover:text-blue-600 transition-colors">
                                                <i class="fas fa-eye text-sm"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-6 py-20 text-center">
                                        <div class="flex flex-col items-center">
                                            <div
                                                class="w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center mb-4">
                                                <i class="fas fa-folder-open text-slate-300 text-2xl"></i>
                                            </div>
                                            <p class="text-slate-400 font-medium text-sm">Tiada rekod tuntutan dijumpai</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>



        <!-- Modal for Add Death Record -->
        <div id="deathRecordModal"
            class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm overflow-y-auto h-full w-full hidden z-50 transition-all duration-300">
            <div
                class="relative top-10 mx-auto p-0 border-none w-full max-w-lg shadow-2xl rounded-3xl bg-white overflow-hidden">
                <div class="px-8 py-6 bg-slate-50/50 border-b border-slate-100 flex justify-between items-center">
                    <div>
                        <h3 class="text-xl font-bold text-slate-800">Tambah Rekod Kematian</h3>
                        <p class="text-xs text-slate-500 mt-1">Sila isi maklumat tuntutan di bawah.</p>
                    </div>
                    <button id="closeModalBtn"
                        class="w-10 h-10 flex items-center justify-center rounded-full bg-white border border-slate-200 text-slate-400 hover:text-rose-500 hover:border-rose-100 transition-all shadow-sm">
                        <i class="fas fa-times"></i>
                    </button>
                </div>

                <form id="deathRecordForm" class="p-8 space-y-6" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="masjid_id" value="{{ auth()->user()->masjid_id }}">
                    <input type="hidden" name="user_id" value="{{ auth()->user()->id }}">

                    <!-- Claim Type Selection -->
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-3">
                            Jenis Tuntutan <span class="text-rose-500">*</span>
                        </label>
                        <div class="grid grid-cols-3 gap-4">
                            <label
                                class="relative flex items-center justify-center p-4 border-2 rounded-2xl cursor-pointer transition-all border-slate-100 bg-slate-50 has-[:checked]:border-blue-500 has-[:checked]:bg-blue-50 group">
                                <input type="radio" name="claim_type" value="luar" checked class="hidden peer">
                                <div class="text-center">
                                    <i
                                        class="fas fa-user-plus block text-lg mb-1 text-slate-400 peer-checked:text-blue-600"></i>
                                    <span class="text-sm font-bold text-slate-600 peer-checked:text-blue-700">Bukan
                                        Ahli</span>
                                </div>
                            </label>
                        </div>
                    </div>

                    <!-- Ahli Section -->
                    <div id="ahliSection" class="hidden space-y-4">
                        <div class="space-y-2">
                            <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest ml-1">Cari Ahli
                                Kariah</label>
                            <div class="relative group">
                                <i
                                    class="fas fa-search absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 group-focus-within:text-blue-500 transition-colors"></i>
                                <input type="text" id="search_ahli" name="search_ahli"
                                    placeholder="Nama atau No. IC..."
                                    class="w-full pl-11 pr-4 py-3 bg-slate-50 border-none rounded-xl ring-1 ring-slate-200 focus:ring-2 focus:ring-blue-500/20 focus:bg-white transition-all">
                                <div id="searchResults"
                                    class="absolute z-20 w-full bg-white border border-slate-100 rounded-xl mt-2 hidden max-h-60 overflow-y-auto shadow-xl ring-1 ring-black/5">
                                </div>
                            </div>
                            <input type="hidden" name="ahli_id" id="ahli_id">
                        </div>
                    </div>

                    <!-- Tanggungan Section -->
                    <div id="tanggunganSection" class="hidden space-y-4">
                        <div class="space-y-2">
                            <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest ml-1">Pilih Ahli
                                Kariah (Pencarum)</label>
                            <select id="ahli_kariah_select" name="ahli_kariah_id"
                                class="w-full px-4 py-3 bg-slate-50 border-none rounded-xl ring-1 ring-slate-200 focus:ring-2 focus:ring-blue-500/20 focus:bg-white transition-all">
                                <option value="">Pilih Ahli Khairat</option>
                                @foreach ($ahliKariahList as $ahli)
                                    <option value="{{ $ahli->id }}" data-user_id="{{ $ahli->user_id }}">
                                        {{ $ahli->nama }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div id="tanggunganListSection" class="hidden space-y-2">
                            <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest ml-1">Pilih
                                Tanggungan</label>
                            <select id="tanggungan_id" name="tanggungan_id"
                                class="w-full px-4 py-3 bg-slate-50 border-none rounded-xl ring-1 ring-slate-200 focus:ring-2 focus:ring-blue-500/20 focus:bg-white transition-all">
                                <option value="">Pilih Tanggungan</option>
                            </select>
                        </div>
                    </div>

                    <!-- Basic Info Section (for all types) -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="space-y-2">
                            <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest ml-1">Nama Penuh
                                <span class="text-rose-500">*</span></label>
                            <input type="text" name="nama" id="nama" required
                                class="w-full px-4 py-3 bg-slate-50 border-none rounded-xl ring-1 ring-slate-200 focus:ring-2 focus:ring-blue-500/20 transition-all">
                        </div>
                        <div class="space-y-2">
                            <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest ml-1">No. Kad
                                Pengenalan <span class="text-rose-500">*</span></label>
                            <input type="text" name="ic" id="ic" required placeholder="000000-00-0000"
                                class="w-full px-4 py-3 bg-slate-50 border-none rounded-xl ring-1 ring-slate-200 focus:ring-2 focus:ring-blue-500/20 transition-all">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                        <div class="space-y-2">
                            <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest ml-1">Tarikh
                                Meninggal <span class="text-rose-500">*</span></label>
                            <input type="date" name="tarikh_meninggal" id="tarikh_meninggal" required
                                class="w-full px-4 py-3 bg-slate-50 border-none rounded-xl ring-1 ring-slate-200 focus:ring-2 focus:ring-blue-500/20 transition-all">
                        </div>
                    </div>

                    <!-- Status Pengurusan Jenazah Dropdown -->
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-2">Status
                            Pengurusan Jenazah <span class="text-rose-500">*</span></label>
                        <div class="space-y-3">
                            <!-- Option 1: Dalam Pengurusan -->
                            <label
                                class="flex items-start p-3 border border-slate-200 rounded-xl cursor-pointer hover:bg-slate-50 transition-all">
                                <input type="radio" name="status_jenazah" value="PROCESSING"
                                    onchange="toggleItemsSectionModal(false)"
                                    class="w-5 h-5 mt-0.5 text-amber-600 focus:ring-amber-500">
                                <div class="ml-3">
                                    <span class="font-semibold text-slate-700">Dalam Pengurusan Jenazah</span>
                                    <p class="text-xs text-slate-500">Jenazah sedang diuruskan. Tiada item perincian
                                        diperlukan.</p>
                                </div>
                            </label>

                            <!-- Option 2: Pengurusan Selesai -->
                            <label
                                class="flex items-start p-3 border border-slate-200 rounded-xl cursor-pointer hover:bg-slate-50 transition-all">
                                <input type="radio" name="status_jenazah" value="SUCCESS"
                                    onchange="toggleItemsSectionModal(true)"
                                    class="w-5 h-5 mt-0.5 text-emerald-600 focus:ring-emerald-500">
                                <div class="ml-3">
                                    <span class="font-semibold text-slate-700">Pengurusan Jenazah Selesai</span>
                                    <p class="text-xs text-slate-500">Sila nyatakan perincian item dan jumlah bayaran.</p>
                                </div>
                            </label>
                        </div>
                    </div>

                    <!-- Items Section - Shown when SUCCESS is selected -->
                    <div id="itemsSectionModal" class="hidden space-y-4 border-t border-slate-200 pt-4">
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest">Perincian Item &
                            Bayaran <span class="text-rose-500">*</span></label>

                        <div class="bg-slate-50 rounded-xl p-4 space-y-3">
                            <!-- Item 1: Pengurusan Jenazah -->
                            <div class="flex items-center gap-3">
                                <input type="checkbox" name="items_modal[]" value="pengurusan_jenazah"
                                    onchange="toggleAmountInputModal(this, 'amount_pengurusan_jenazah_modal')"
                                    class="w-5 h-5 text-emerald-600 rounded focus:ring-emerald-500">
                                <label class="flex-1 text-slate-700 font-medium">Pengurusan Jenazah</label>
                                <input type="number" name="amount_pengurusan_jenazah_modal"
                                    id="amount_pengurusan_jenazah_modal" placeholder="RM 0.00" disabled
                                    class="w-40 px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 disabled:bg-slate-100 disabled:cursor-not-allowed">
                            </div>

                            <!-- Item 2: Pengangkutan Jenazah -->
                            <div class="flex items-center gap-3">
                                <input type="checkbox" name="items_modal[]" value="pengangkutan_jenazah"
                                    onchange="toggleAmountInputModal(this, 'amount_pengangkutan_jenazah_modal')"
                                    class="w-5 h-5 text-emerald-600 rounded focus:ring-emerald-500">
                                <label class="flex-1 text-slate-700 font-medium">Van Jenazah</label>
                                <input type="number" name="amount_pengangkutan_jenazah_modal"
                                    id="amount_pengangkutan_jenazah_modal" placeholder="RM 0.00" disabled
                                    class="w-40 px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 disabled:bg-slate-100 disabled:cursor-not-allowed">
                            </div>

                            <!-- Item 3: Tanah Perkuburan -->
                            <div class="flex items-center gap-3">
                                <input type="checkbox" name="items_modal[]" value="tanah_perkuburan"
                                    onchange="toggleAmountInputModal(this, 'amount_tanah_perkuburan_modal')"
                                    class="w-5 h-5 text-emerald-600 rounded focus:ring-emerald-500">
                                <label class="flex-1 text-slate-700 font-medium">Gali Kubur</label>
                                <input type="number" name="amount_tanah_perkuburan_modal"
                                    id="amount_tanah_perkuburan_modal" placeholder="RM 0.00" disabled
                                    class="w-40 px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 disabled:bg-slate-100 disabled:cursor-not-allowed">
                            </div>

                            <!-- Item 4: Kain Kafan -->
                            <div class="flex items-center gap-3">
                                <input type="checkbox" name="items_modal[]" value="kain_kafan"
                                    onchange="toggleAmountInputModal(this, 'amount_kain_kafan_modal')"
                                    class="w-5 h-5 text-emerald-600 rounded focus:ring-emerald-500">
                                <label class="flex-1 text-slate-700 font-medium">Kain Kafan</label>
                                <input type="number" name="amount_kain_kafan_modal" id="amount_kain_kafan_modal"
                                    placeholder="RM 0.00" disabled
                                    class="w-40 px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 disabled:bg-slate-100 disabled:cursor-not-allowed">
                            </div>

                            <!-- Item 5: Air / Mandian -->
                            <div class="flex items-center gap-3">
                                <input type="checkbox" name="items_modal[]" value="air_mandian"
                                    onchange="toggleAmountInputModal(this, 'amount_air_mandian_modal')"
                                    class="w-5 h-5 text-emerald-600 rounded focus:ring-emerald-500">
                                <label class="flex-1 text-slate-700 font-medium">Air / Mandian</label>
                                <input type="number" name="amount_air_mandian_modal" id="amount_air_mandian_modal"
                                    placeholder="RM 0.00" disabled
                                    class="w-40 px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 disabled:bg-slate-100 disabled:cursor-not-allowed">
                            </div>

                            <!-- Item 6: Imam / Bilal -->
                            <div class="flex items-center gap-3">
                                <input type="checkbox" name="items_modal[]" value="imam_bilal"
                                    onchange="toggleAmountInputModal(this, 'amount_imam_bilal_modal')"
                                    class="w-5 h-5 text-emerald-600 rounded focus:ring-emerald-500">
                                <label class="flex-1 text-slate-700 font-medium">Imam / Bilal</label>
                                <input type="number" name="amount_imam_bilal_modal" id="amount_imam_bilal_modal"
                                    placeholder="RM 0.00" disabled
                                    class="w-40 px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 disabled:bg-slate-100 disabled:cursor-not-allowed">
                            </div>

                            <!-- Item 7: Lain-lain -->
                            <div class="flex items-center gap-3">
                                <input type="checkbox" name="items_modal[]" value="lain_lain"
                                    onchange="toggleAmountInputModal(this, 'amount_lain_lain_modal'); toggleLainLainTextModal()"
                                    class="w-5 h-5 text-emerald-600 rounded focus:ring-emerald-500">
                                <label class="flex-1 text-slate-700 font-medium">Lain-lain</label>
                                <input type="text" name="lain_lain_text_modal" id="lain_lain_text_modal"
                                    placeholder="Nyatakan item" disabled
                                    class="hidden w-32 px-2 py-2 border border-slate-300 rounded-lg text-sm">
                                <input type="number" name="amount_lain_lain_modal" id="amount_lain_lain_modal"
                                    placeholder="RM 0.00" disabled
                                    class="w-40 px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 disabled:bg-slate-100 disabled:cursor-not-allowed">
                            </div>
                        </div>

                        <!-- Total Amount Display -->
                        <div class="bg-emerald-50 rounded-xl p-4 border border-emerald-200">
                            <div class="flex justify-between items-center">
                                <span class="font-bold text-slate-700">Jumlah Keseluruhan:</span>
                                <span id="totalAmountDisplayModal" class="text-xl font-bold text-emerald-600">RM
                                    0.00</span>
                            </div>
                            <input type="hidden" name="total_amount_modal" id="total_amount_modal" value="0">
                        </div>
                    </div>

                    <!-- File Upload Section -->
                    <div class="space-y-4">
                        <div class="space-y-2">
                            <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest ml-1">Sijil
                                Kematian <span class="text-rose-500">*</span></label>
                            <input type="file" name="sijil_kematian" id="sijil_kematian"
                                accept=".pdf,.jpg,.jpeg,.png"
                                class="w-full px-4 py-2 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500/20 transition-all">
                            <p class="text-xs text-slate-400 ml-1">Format: PDF, JPG, JPEG, PNG (Max: 5MB)</p>
                        </div>
                        <div class="space-y-2">
                            <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest ml-1">Laporan
                                Polis</label>
                            <input type="file" name="laporan_polis" id="laporan_polis" accept=".pdf,.jpg,.jpeg,.png"
                                class="w-full px-4 py-2 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500/20 transition-all">
                        </div>
                        <div class="space-y-2">
                            <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest ml-1">Dokumen
                                Sokongan <span class="text-rose-500 text-xs">(Jika ada)</span></label>
                            <input type="file" name="maklumat_lain" id="maklumat_lain" accept=".pdf,.jpg,.jpeg,.png"
                                class="w-full px-4 py-2 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500/20 transition-all">
                        </div>
                        <div class="space-y-2">
                            <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest ml-1">Catatan /
                                Nota</label>
                            <textarea name="catatan" id="catatan" rows="3"
                                class="w-full px-4 py-3 bg-slate-50 border-none rounded-xl ring-1 ring-slate-200 focus:ring-2 focus:ring-blue-500/20 transition-all"
                                placeholder="Sebarang catatan tambahan..."></textarea>
                        </div>
                    </div>

                    <div class="flex flex-col sm:flex-row gap-3 pt-6 border-t border-slate-100">
                        <button type="button" id="cancelModalBtn"
                            class="flex-1 px-6 py-3 bg-slate-100 text-slate-500 font-bold rounded-xl hover:bg-slate-200 transition-all order-2 sm:order-1">
                            Batal
                        </button>
                        <button type="submit"
                            class="flex-1 px-6 py-3 bg-blue-600 text-white font-bold rounded-xl shadow-lg shadow-blue-200 hover:shadow-blue-300 hover:bg-blue-700 transition-all flex items-center justify-center gap-2 order-1 sm:order-2">
                            <i class="fas fa-save"></i> Hantar Rekod
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Loading Spinner -->
        <div id="loadingSpinner" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden items-center justify-center z-50">
            <div class="bg-white rounded-lg p-6 flex items-center gap-3">
                <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600"></div>
                <span class="text-gray-700">Memproses...</span>
            </div>
        </div>

        <!-- Success/Error Toast Notification -->
        <div id="toastNotification" class="fixed bottom-4 right-4 hidden z-50">
            <div class="bg-white rounded-lg shadow-lg p-4 min-w-64">
                <div class="flex items-center gap-3">
                    <div id="toastIcon" class="text-green-500">
                        <i class="fas fa-check-circle text-xl"></i>
                    </div>
                    <div>
                        <p id="toastMessage" class="text-gray-800 font-medium"></p>
                        <p id="toastSubMessage" class="text-gray-500 text-sm"></p>
                    </div>
                </div>
            </div>
        </div>

        <script>
            // Get DOM elements for modal
            const modal = document.getElementById('deathRecordModal');
            const addBtn = document.getElementById('addDeathRecordBtn');
            const closeModalBtn = document.getElementById('closeModalBtn');
            const cancelModalBtn = document.getElementById('cancelModalBtn');
            const form = document.getElementById('deathRecordForm');
            const searchInput = document.getElementById('searchInput');
            const statusFilter = document.getElementById('statusFilter');
            const dateFilter = document.getElementById('dateFilter');
            const resetFilterBtn = document.getElementById('resetFilterBtn');
            const tableBody = document.getElementById('tuntutanTableBody');

            // Get DOM elements for type selection
            const claimTypeRadios = document.querySelectorAll('input[name="claim_type"]');
            const ahliSection = document.getElementById('ahliSection');
            const tanggunganSection = document.getElementById('tanggunganSection');
            const searchAhliInput = document.getElementById('search_ahli');
            const searchResults = document.getElementById('searchResults');
            const ahliIdInput = document.getElementById('ahli_id');
            const ahliKariahSelect = document.getElementById('ahli_kariah_select');
            const tanggunganListSection = document.getElementById('tanggunganListSection');
            const tanggunganSelect = document.getElementById('tanggungan_id');

            // Store original data for filtering
            let originalRows = [];

            // Initialize: Store all rows
            function storeOriginalRows() {
                const rows = tableBody.querySelectorAll('tr');
                originalRows = Array.from(rows).filter(row => row.cells && row.cells.length > 1);
            }

            const typeFilter = document.getElementById('typeFilter');

            function filterTable() {
                const searchTerm = searchInput.value.toLowerCase().trim();
                const statusValue = statusFilter.value.toUpperCase();
                const typeValue = typeFilter.value.toUpperCase();
                const dateValue = dateFilter.value;

                originalRows.forEach(row => {
                    let showRow = true;

                    // SEARCH FILTER
                    if (searchTerm) {
                        const name = row.cells[1]?.textContent.toLowerCase() || '';
                        const ic = row.cells[2]?.textContent.toLowerCase() || '';

                        if (!name.includes(searchTerm) && !ic.includes(searchTerm)) {
                            showRow = false;
                        }
                    }

                    // STATUS FILTER
                    if (showRow && statusValue !== 'ALL') {

                        // better if you put data-status on row
                        const rowStatus = row.dataset.status || '';

                        if (rowStatus !== statusValue) {
                            showRow = false;
                        }
                    }

                    // TYPE FILTER
                    if (showRow && typeValue !== 'ALL') {

                        const rowType = row.dataset.type || '';

                        if (rowType !== typeValue) {
                            showRow = false;
                        }
                    }

                    // DATE FILTER
                    if (showRow && dateValue) {

                        const rowDate = row.dataset.date || '';

                        if (rowDate !== dateValue) {
                            showRow = false;
                        }
                    }

                    row.style.display = showRow ? '' : 'none';
                });
            }

            // Reset all filters
            function resetFilters() {

                searchInput.value = '';
                statusFilter.value = 'all';
                typeFilter.value = 'all';
                dateFilter.value = '';

                filterTable();

                showToast(
                    'Filter Reset',
                    'Semua penapis telah diresetkan',
                    'info'
                );
            }

            if (typeFilter) {
                typeFilter.addEventListener('change', filterTable);
            }

            // Show toast notification
            function showToast(title, message, type = 'success') {
                const toast = document.getElementById('toastNotification');
                const toastIcon = document.getElementById('toastIcon');
                const toastMessage = document.getElementById('toastMessage');
                const toastSubMessage = document.getElementById('toastSubMessage');

                if (type === 'success') {
                    toastIcon.innerHTML = '<i class="fas fa-check-circle text-xl text-green-500"></i>';
                } else if (type === 'error') {
                    toastIcon.innerHTML = '<i class="fas fa-exclamation-circle text-xl text-red-500"></i>';
                } else {
                    toastIcon.innerHTML = '<i class="fas fa-info-circle text-xl text-blue-500"></i>';
                }

                toastMessage.textContent = title;
                toastSubMessage.textContent = message;
                toast.classList.remove('hidden');

                setTimeout(() => {
                    toast.classList.add('hidden');
                }, 3000);
            }

            // Show/hide loading spinner
            function showLoading() {
                const spinner = document.getElementById('loadingSpinner');
                spinner.classList.remove('hidden');
                spinner.classList.add('flex');
            }

            function hideLoading() {
                const spinner = document.getElementById('loadingSpinner');
                spinner.classList.add('hidden');
                spinner.classList.remove('flex');
            }

            // Open modal
            function openModal() {
                if (modal) {
                    modal.classList.remove('hidden');
                    document.body.style.overflow = 'hidden';
                    form.reset();

                    // Set default to "luar" and make fields editable
                    const luarRadio = document.querySelector('input[name="claim_type"][value="luar"]');
                    if (luarRadio) {
                        luarRadio.checked = true;
                    }

                    // Hide sections for ahli and tanggungan
                    ahliSection.classList.add('hidden');
                    tanggunganSection.classList.add('hidden');

                    // Make name and IC fields editable for Luar
                    document.getElementById('nama').readOnly = false;
                    document.getElementById('ic').readOnly = false;
                    document.getElementById('nama').classList.remove('bg-slate-100');
                    document.getElementById('ic').classList.remove('bg-slate-100');

                    // Reset selections
                    if (ahliIdInput) ahliIdInput.value = '';
                    if (searchAhliInput) searchAhliInput.value = '';
                    if (ahliKariahSelect) ahliKariahSelect.value = '';
                    if (tanggunganSelect) tanggunganSelect.innerHTML = '<option value="">Pilih Tanggungan</option>';
                    if (tanggunganListSection) tanggunganListSection.classList.add('hidden');
                }
            }

            // Close modal
            function closeModal() {
                if (modal) {
                    modal.classList.add('hidden');
                    form.reset();
                    document.body.style.overflow = '';
                }
            }

            // Action functions
            function viewDetails(id) {
                showToast('Maklumat', 'Fungsi sedang dalam pembangunan', 'info');
            }

            function editRecord(id) {
                showToast('Edit Rekod', 'Fungsi sedang dalam pembangunan', 'info');
            }

            function deleteRecord(id) {
                if (confirm('Adakah anda pasti ingin menghapuskan rekod ini?')) {
                    showToast('Padam Rekod', 'Fungsi sedang dalam pembangunan', 'info');
                }
            }

            // Toggle sections based on radio button selection
            if (claimTypeRadios) {
                claimTypeRadios.forEach(radio => {
                    radio.addEventListener('change', function() {
                        // Hide all sections first
                        ahliSection.classList.add('hidden');
                        tanggunganSection.classList.add('hidden');

                        // Make name and IC fields editable by default (for Luar)
                        document.getElementById('nama').readOnly = false;
                        document.getElementById('ic').readOnly = false;
                        document.getElementById('nama').classList.remove('bg-slate-100');
                        document.getElementById('ic').classList.remove('bg-slate-100');
                        document.getElementById('nama').value = '';
                        document.getElementById('ic').value = '';

                        if (this.value === 'ahli') {
                            ahliSection.classList.remove('hidden');
                            // Name and IC will be auto-filled from search
                            document.getElementById('nama').readOnly = true;
                            document.getElementById('ic').readOnly = true;
                            document.getElementById('nama').classList.add('bg-slate-100');
                            document.getElementById('ic').classList.add('bg-slate-100');
                        } else if (this.value === 'tanggungan') {
                            tanggunganSection.classList.remove('hidden');
                            // Name and IC will be auto-filled from selection
                            document.getElementById('nama').readOnly = true;
                            document.getElementById('ic').readOnly = true;
                            document.getElementById('nama').classList.add('bg-slate-100');
                            document.getElementById('ic').classList.add('bg-slate-100');
                        } else {
                            // Luar - make fields editable, no sections shown
                            document.getElementById('nama').readOnly = false;
                            document.getElementById('ic').readOnly = false;
                        }

                        // Reset selections
                        if (ahliIdInput) ahliIdInput.value = '';
                        if (searchAhliInput) searchAhliInput.value = '';
                        if (ahliKariahSelect) ahliKariahSelect.value = '';
                        if (tanggunganSelect) tanggunganSelect.innerHTML =
                            '<option value="">Pilih Tanggungan</option>';
                        if (tanggunganListSection) tanggunganListSection.classList.add('hidden');
                    });
                });
            }

            // Search Ahli Khairat functionality
            if (searchAhliInput) {
                let searchTimeout;
                searchAhliInput.addEventListener('input', function() {
                    clearTimeout(searchTimeout);
                    const query = this.value.trim();

                    if (query.length < 2) {
                        searchResults.classList.add('hidden');
                        return;
                    }

                    searchTimeout = setTimeout(() => {
                        fetch(`/search-ahli?q=${encodeURIComponent(query)}`, {
                                headers: {
                                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                                        .getAttribute('content'),
                                    'Accept': 'application/json'
                                }
                            })
                            .then(response => response.json())
                            .then(data => {
                                if (data.length > 0) {
                                    searchResults.innerHTML = data.map(ahli => `
                            <div class="p-2 hover:bg-gray-100 cursor-pointer border-b" onclick="selectAhli(${ahli.id}, '${ahli.nama}', '${ahli.ic}')">
                                <div class="font-semibold">${ahli.nama}</div>
                                <div class="text-xs text-gray-600">IC: ${ahli.ic}</div>
                            </div>
                        `).join('');
                                    searchResults.classList.remove('hidden');
                                } else {
                                    searchResults.innerHTML =
                                        '<div class="p-2 text-gray-500">Tiada rekod dijumpai</div>';
                                    searchResults.classList.remove('hidden');
                                }
                            })
                            .catch(error => {
                                console.error('Error:', error);
                                searchResults.innerHTML =
                                    '<div class="p-2 text-red-500">Ralat semasa mencari</div>';
                                searchResults.classList.remove('hidden');
                            });
                    }, 300);
                });
            }

            // Function to select ahli
            window.selectAhli = function(id, name, ic) {
                ahliIdInput.value = id;
                document.getElementById('nama').value = name;
                document.getElementById('ic').value = ic;
                searchResults.classList.add('hidden');
                searchAhliInput.value = '';
            }

            // Close search results when clicking outside
            document.addEventListener('click', function(e) {
                if (searchAhliInput && searchResults && !searchAhliInput.contains(e.target) && !searchResults.contains(e
                        .target)) {
                    searchResults.classList.add('hidden');
                }
            });

            // Load tanggungan when Ahli Khairat is selected
            if (ahliKariahSelect) {
                ahliKariahSelect.addEventListener('change', function() {
                    const selectedOption = this.options[this.selectedIndex];
                    const ahliId = this.value;
                    const userId = selectedOption.getAttribute('data-user_id');

                    if (!ahliId) {
                        tanggunganListSection.classList.add('hidden');
                        return;
                    }

                    fetch(`/get-tanggungan/${userId}`, {
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                                    'content'),
                                'Accept': 'application/json'
                            }
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.length > 0) {
                                tanggunganSelect.innerHTML = '<option value="">Pilih Tanggungan</option>' +
                                    data.map(tg => `
                            <option value="${tg.id}" 
                                data-nama="${tg.nama}" 
                                data-ic="${tg.ic_number}" 
                                data-hubungan="${tg.hubungan}">
                                ${tg.nama} (${tg.hubungan}) - IC: ${tg.ic_number}
                            </option>
                        `).join('');
                                tanggunganListSection.classList.remove('hidden');
                            } else {
                                tanggunganSelect.innerHTML = '<option value="">Tiada tanggungan</option>';
                                tanggunganListSection.classList.remove('hidden');
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            tanggunganSelect.innerHTML = '<option value="">Ralat memuat tanggungan</option>';
                            tanggunganListSection.classList.remove('hidden');
                        });
                });
            }

            // Display selected tanggungan info and auto-fill name/IC
            if (tanggunganSelect) {
                tanggunganSelect.addEventListener('change', function() {
                    const selectedOption = this.options[this.selectedIndex];
                    if (this.value) {
                        const nama = selectedOption.getAttribute('data-nama');
                        const ic = selectedOption.getAttribute('data-ic');
                        document.getElementById('nama').value = nama;
                        document.getElementById('ic').value = ic;
                    } else {
                        document.getElementById('nama').value = '';
                        document.getElementById('ic').value = '';
                    }
                });
            }

            // Form submission
            if (form) {
                form.addEventListener('submit', async (e) => {
                    e.preventDefault();

                    const claimType = document.querySelector('input[name="claim_type"]:checked').value;
                    const formData = new FormData(form);

                    // Get status jenazah value
                    const statusJenazah = document.querySelector('input[name="status_jenazah"]:checked')?.value;

                    // Validate required fields based on claim type
                    const nama = formData.get('nama');
                    const ic = formData.get('ic');
                    const tarikhMeninggal = formData.get('tarikh_meninggal');
                    const sijilKematian = formData.get('sijil_kematian');

                    // Basic validation - nama, ic, tarikh are always required
                    if (!nama || !ic || !tarikhMeninggal) {
                        showToast('Ralat Validasi', 'Sila lengkapkan nama, no. IC dan tarikh meninggal', 'error');
                        return;
                    }

                    // Validate IC format
                    const icPattern = /^\d{6}-\d{2}-\d{4}$/;
                    if (!icPattern.test(ic)) {
                        showToast('Ralat Validasi', 'Format IC tidak sah. Gunakan format: 900101-01-1234', 'error');
                        return;
                    }

                    // For LUAR, sijil_kematian is optional - only validate if file is present
                    if (sijilKematian && sijilKematian.size > 0) {
                        // Validate file size (5MB max)
                        if (sijilKematian.size > 5 * 1024 * 1024) {
                            showToast('Ralat Validasi', 'Saiz fail sijil kematian melebihi 5MB', 'error');
                            return;
                        }

                        // Validate file type
                        const allowedTypes = ['application/pdf', 'image/jpeg', 'image/jpg', 'image/png'];
                        if (!allowedTypes.includes(sijilKematian.type)) {
                            showToast('Ralat Validasi', 'Format fail tidak sah. Gunakan PDF, JPG, JPEG atau PNG',
                                'error');
                            return;
                        }
                    }

                    // Validate status jenazah is selected
                    if (!statusJenazah) {
                        showToast('Ralat Validasi', 'Sila pilih status Pengurusan jenazah', 'error');
                        return;
                    }

                    // If status is SUCCESS, validate items
                    if (statusJenazah === 'SUCCESS') {
                        const checkedItems = document.querySelectorAll(
                            '#itemsSectionModal input[type="checkbox"]:checked');

                        if (checkedItems.length === 0) {
                            showToast('Ralat Validasi',
                                'Sila pilih sekurang-kurangnya satu item untuk Pengurusan yang selesai', 'error'
                            );
                            return;
                        }

                        // Validate each checked item has amount
                        let hasInvalidAmount = false;
                        let invalidItemName = '';

                        for (const checkbox of checkedItems) {
                            const itemValue = checkbox.value;
                            let amountInputId = '';
                            let itemLabel = '';

                            switch (itemValue) {
                                case 'pengurusan_jenazah':
                                    amountInputId = 'amount_pengurusan_jenazah_modal';
                                    itemLabel = 'Pengurusan Jenazah';
                                    break;
                                case 'pengangkutan_jenazah':
                                    amountInputId = 'amount_pengangkutan_jenazah_modal';
                                    itemLabel = 'Van Jenazah';
                                    break;
                                case 'tanah_perkuburan':
                                    amountInputId = 'amount_tanah_perkuburan_modal';
                                    itemLabel = 'Gali Kubur';
                                    break;
                                case 'kain_kafan':
                                    amountInputId = 'amount_kain_kafan_modal';
                                    itemLabel = 'Kain Kafan';
                                    break;
                                case 'air_mandian':
                                    amountInputId = 'amount_air_mandian_modal';
                                    itemLabel = 'Air / Mandian';
                                    break;
                                case 'imam_bilal':
                                    amountInputId = 'amount_imam_bilal_modal';
                                    itemLabel = 'Imam / Bilal';
                                    break;
                                case 'lain_lain':
                                    amountInputId = 'amount_lain_lain_modal';
                                    itemLabel = 'Lain-lain';
                                    // Also validate lain_lain text if provided
                                    const lainLainText = document.getElementById('lain_lain_text_modal');
                                    if (lainLainText && lainLainText.value.trim() === '') {
                                        hasInvalidAmount = true;
                                        invalidItemName = 'Lain-lain (Sila nyatakan item)';
                                    }
                                    break;
                            }

                            const amountInput = document.getElementById(amountInputId);
                            if (!amountInput || !amountInput.value || parseFloat(amountInput.value) <= 0) {
                                hasInvalidAmount = true;
                                invalidItemName = itemLabel;
                            }
                        }

                        if (hasInvalidAmount) {
                            showToast('Ralat Validasi',
                                `Sila masukkan jumlah bayaran yang sah untuk item: ${invalidItemName}`, 'error');
                            return;
                        }
                    }

                    // Add additional validation for ahli and tanggungan
                    if (claimType === 'ahli') {
                        if (!ahliIdInput || !ahliIdInput.value) {
                            showToast('Ralat Validasi', 'Sila pilih Ahli Khairat dari senarai', 'error');
                            return;
                        }
                    }

                    if (claimType === 'tanggungan') {
                        const ahliKariahId = formData.get('ahli_kariah_id');
                        const tanggunganId = formData.get('tanggungan_id');
                        if (!ahliKariahId || !tanggunganId) {
                            showToast('Ralat Validasi', 'Sila pilih Ahli Khairat dan tanggungan', 'error');
                            return;
                        }
                    }

                    // For LUAR, no additional validation needed

                    showLoading();

                    try {
                        let url = '';
                        if (claimType === 'ahli') {

                        } else if (claimType === 'tanggungan') {

                        } else {
                            url = '{{ route('khairat.death.store.luar') }}';
                        }

                        const response = await fetch(url, {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                                    .getAttribute('content'),
                                'Accept': 'application/json'
                            },
                            body: formData
                        });

                        const result = await response.json();

                        if (result.success) {
                            showToast('Berjaya!', result.message, 'success');
                            closeModal();
                            setTimeout(() => {
                                location.reload();
                            }, 1500);
                        } else {
                            showToast('Ralat!', result.message || 'Gagal menambah rekod', 'error');
                        }
                    } catch (error) {
                        console.error('Error:', error);
                        showToast('Ralat Sistem', error.message || 'Sila cuba sebentar lagi', 'error');
                    } finally {
                        hideLoading();
                    }
                });
            }

            // Event listeners for modal
            if (addBtn) addBtn.addEventListener('click', openModal);
            if (closeModalBtn) closeModalBtn.addEventListener('click', closeModal);
            if (cancelModalBtn) cancelModalBtn.addEventListener('click', closeModal);
            if (searchInput) searchInput.addEventListener('input', filterTable);
            if (statusFilter) statusFilter.addEventListener('change', filterTable);
            if (dateFilter) dateFilter.addEventListener('change', filterTable);
            if (resetFilterBtn) resetFilterBtn.addEventListener('click', resetFilters);

            // Close modal when clicking outside
            if (modal) {
                modal.addEventListener('click', (e) => {
                    if (e.target === modal) {
                        closeModal();
                    }
                });
            }

            // Initialize on page load
            document.addEventListener('DOMContentLoaded', () => {
                storeOriginalRows();
            });

            function viewDeathRecord(type, id) {
                window.location.href = '{{ route('khairat.butiran', ['type' => ':type', 'id' => ':id']) }}'
                    .replace(':type', type)
                    .replace(':id', id);
            }
        </script>
        <script>
            // Toggle items section based on status selection
            function toggleItemsSectionModal(show) {
                const itemsSection = document.getElementById('itemsSectionModal');
                const jumlahField = document.getElementById('jumlah');

                if (show) {
                    itemsSection.classList.remove('hidden');
                    // Make jumlah field readonly when items are selected
                    if (jumlahField) {
                        jumlahField.readOnly = true;
                        jumlahField.classList.add('bg-slate-100', 'cursor-not-allowed');
                        jumlahField.classList.remove('bg-blue-50/30');
                    }
                } else {
                    itemsSection.classList.add('hidden');
                    // Enable jumlah field when PROCESSING is selected
                    if (jumlahField) {
                        jumlahField.readOnly = false;
                        jumlahField.classList.remove('bg-slate-100', 'cursor-not-allowed');
                        jumlahField.classList.add('bg-blue-50/30');
                    }
                    // Reset all checkboxes and amounts when hiding
                    resetItemsAndAmountsModal();
                }
            }

            // Reset all checkboxes and amount inputs
            function resetItemsAndAmountsModal() {
                const checkboxes = document.querySelectorAll('#itemsSectionModal input[type="checkbox"]');
                checkboxes.forEach(checkbox => {
                    checkbox.checked = false;
                    toggleAmountInputModal(checkbox, checkbox.id === 'lain_lain_checkbox' ? 'amount_lain_lain_modal' :
                        checkbox.value === 'pengurusan_jenazah' ? 'amount_pengurusan_jenazah_modal' :
                        checkbox.value === 'pengangkutan_jenazah' ? 'amount_pengangkutan_jenazah_modal' :
                        checkbox.value === 'tanah_perkuburan' ? 'amount_tanah_perkuburan_modal' :
                        checkbox.value === 'kain_kafan' ? 'amount_kain_kafan_modal' :
                        checkbox.value === 'air_mandian' ? 'amount_air_mandian_modal' :
                        'amount_imam_bilal_modal');
                });
            }

            // Toggle amount input based on checkbox state
            function toggleAmountInputModal(checkbox, inputId) {
                const amountInput = document.getElementById(inputId);
                if (amountInput) {
                    if (checkbox.checked) {
                        amountInput.disabled = false;
                        amountInput.required = true;
                        amountInput.classList.remove('bg-slate-100', 'cursor-not-allowed');
                        amountInput.classList.add('bg-white');
                    } else {
                        amountInput.disabled = true;
                        amountInput.required = false;
                        amountInput.value = '';
                        amountInput.classList.add('bg-slate-100', 'cursor-not-allowed');
                        amountInput.classList.remove('bg-white');
                    }
                }
                calculateTotalModal();
            }

            // Toggle lain-lain text input
            function toggleLainLainTextModal() {
                const lainLainCheckbox = document.querySelector('#itemsSectionModal input[value="lain_lain"]');
                const lainLainText = document.getElementById('lain_lain_text_modal');

                if (lainLainCheckbox && lainLainCheckbox.checked) {
                    lainLainText.classList.remove('hidden');
                    lainLainText.disabled = false;
                    lainLainText.required = true;
                } else {
                    lainLainText.classList.add('hidden');
                    lainLainText.disabled = true;
                    lainLainText.required = false;
                    lainLainText.value = '';
                }
            }

            // Calculate total amount from all checked items
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
                const jumlahField = document.getElementById('jumlah');

                if (totalDisplay) {
                    totalDisplay.textContent = 'RM ' + total.toFixed(2);
                }
                if (totalHidden) {
                    totalHidden.value = total;
                }
                if (jumlahField && total > 0) {
                    jumlahField.value = total;
                }
            }

            // Add event listeners
            document.addEventListener('DOMContentLoaded', function() {
                const amountInputs = document.querySelectorAll('#itemsSectionModal input[type="number"]');
                amountInputs.forEach(input => {
                    input.addEventListener('input', calculateTotalModal);
                    input.addEventListener('keyup', calculateTotalModal);
                });
            });

            // Form submission validation
            document.getElementById('deathRecordForm')?.addEventListener('submit', function(e) {
                const statusRadio = document.querySelector('input[name="status_jenazah"]:checked');
                const isSuccess = statusRadio && statusRadio.value === 'SUCCESS';

                if (isSuccess) {
                    const checkedItems = document.querySelectorAll('#itemsSectionModal input[type="checkbox"]:checked');

                    if (checkedItems.length === 0) {
                        e.preventDefault();
                        Swal.fire({
                            icon: 'warning',
                            title: 'Perhatian!',
                            text: 'Sila pilih sekurang-kurangnya satu item untuk Pengurusan jenazah yang selesai.',
                            confirmButtonColor: '#d33'
                        });
                        return false;
                    }

                    let hasInvalidAmount = false;
                    checkedItems.forEach(checkbox => {
                        let amountInputId = '';
                        switch (checkbox.value) {
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
                        if (!amountInput || !amountInput.value || parseFloat(amountInput.value) <= 0) {
                            hasInvalidAmount = true;
                            const label = checkbox.parentElement.querySelector('label:not(.flex)')?.innerText ||
                                checkbox.value;
                            Swal.fire({
                                icon: 'warning',
                                title: 'Perhatian!',
                                text: `Sila masukkan jumlah bayaran untuk item: ${label}`,
                                confirmButtonColor: '#d33'
                            });
                            amountInput?.focus();
                            e.preventDefault();
                            return false;
                        }
                    });

                    if (hasInvalidAmount) {
                        return false;
                    }
                }

                return true;
            });
        </script>

    </body>
@endsection
