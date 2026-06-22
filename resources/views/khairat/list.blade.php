@extends('layouts.app')
@section('title', 'Senarai Ahli Khairat')
@section('content')

    <style>
        /* Modern Scrollbar */
        .modern-scrollbar::-webkit-scrollbar {
            width: 5px;
            height: 5px;
        }

        .modern-scrollbar::-webkit-scrollbar-track {
            background: transparent;
        }

        .modern-scrollbar::-webkit-scrollbar-thumb {
            background: #e2e8f0;
            border-radius: 10px;
        }

        .modern-scrollbar::-webkit-scrollbar-thumb:hover {
            background: #cbd5e1;
        }

        /* Soft card elevations */
        .modern-card {
            background: #ffffff;
            border: 1px solid rgba(229, 231, 235, 0.5);
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.02), 0 2px 4px -1px rgba(0, 0, 0, 0.01);
        }

        /* Subtle row interaction */
        .table-row-hover {
            transition: all 0.15s ease-in-out;
        }

        .table-row-hover:hover {
            background-color: #f8fafc;
            transform: scale(1.002);
        }

        /* Glass effect for filter bar */
        .glass-filter {
            background: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(8px);
        }

        /* Year filter styling */
        .year-filter-group {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .year-input {
            width: 100px;
            text-align: center;
        }
    </style>

    <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="flex flex-col md:flex-row md:items-end md:justify-between mb-8">
            <div>
                <h1 class="text-3xl font-extrabold text-gray-900 tracking-tight">Senarai Ahli Khairat</h1>
                <p class="text-gray-500 mt-2 font-medium">Maklumat pengurusan dan data Ahli Khairat.</p>
            </div>
            <div class="mt-6 md:mt-0">
                <button id="share-link"
                    class="inline-flex items-center px-5 py-2.5 bg-slate-900 text-white rounded-xl hover:bg-slate-800 shadow-lg shadow-slate-200 transition-all duration-200 text-sm font-semibold">
                    <i class="fas fa-share-alt mr-2 text-slate-400"></i>
                    Kongsi Pautan Pendaftaran
                </button>
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-5 gap-4 mb-8">

            <!-- Jumlah Ahli -->
            <div
                class="modern-card bg-white rounded-2xl shadow-sm border border-gray-100 p-4 sm:p-5 flex items-center hover:-translate-y-1 hover:shadow-md transition-all duration-300">

                <div
                    class="w-11 h-11 sm:w-12 sm:h-12 bg-indigo-50 rounded-xl flex items-center justify-center mr-4 shrink-0">
                    <i class="fas fa-users text-indigo-600 text-lg sm:text-xl"></i>
                </div>

                <div>
                    <p class="text-[11px] font-bold text-gray-400 uppercase tracking-wider">
                        Jumlah Ahli
                    </p>

                    <p class="text-2xl sm:text-3xl font-black text-gray-900 leading-none mt-1">
                        {{ $jumlahAhli ?? 0 }}
                    </p>
                </div>
            </div>

            <!-- Ahli Aktif -->
            <div
                class="modern-card bg-white rounded-2xl shadow-sm border border-gray-100 p-4 sm:p-5 flex items-center hover:bg-emerald-50/40 hover:-translate-y-1 hover:shadow-md transition-all duration-300">

                <div
                    class="w-11 h-11 sm:w-12 sm:h-12 bg-emerald-100 rounded-xl flex items-center justify-center mr-4 shrink-0">
                    <i class="fas fa-user-check text-emerald-600 text-lg sm:text-xl"></i>
                </div>

                <div>
                    <p class="text-[11px] font-bold text-emerald-600 uppercase tracking-wider">
                        Aktif
                    </p>

                    <p class="text-2xl sm:text-3xl font-black text-gray-900 leading-none mt-1">
                        {{ $aktif ?? 0 }}
                    </p>
                </div>
            </div>

            <!-- Ahli mati -->
            <div
                class="modern-card bg-white rounded-2xl shadow-sm border border-gray-100 p-4 sm:p-5 flex items-center hover:bg-rose-50/40 hover:-translate-y-1 hover:shadow-md transition-all duration-300">

                <div
                    class="w-11 h-11 sm:w-12 sm:h-12 bg-rose-100 rounded-xl flex items-center justify-center mr-4 shrink-0">
                    <i class="fas fa-user-slash text-rose-600 text-lg sm:text-xl"></i>
                </div>

                <div>
                    <p class="text-[11px] font-bold text-rose-600 uppercase tracking-wider">
                        mati
                    </p>

                    <p class="text-2xl sm:text-3xl font-black text-gray-900 leading-none mt-1">
                        {{ $takAktif ?? 0 }}
                    </p>
                </div>
            </div>

            <!-- Subscription Aktif -->
            <div
                class="modern-card bg-white rounded-2xl shadow-sm border border-gray-100 p-4 sm:p-5 flex items-center hover:bg-cyan-50/40 hover:-translate-y-1 hover:shadow-md transition-all duration-300">

                <div
                    class="w-11 h-11 sm:w-12 sm:h-12 bg-cyan-100 rounded-xl flex items-center justify-center mr-4 shrink-0">
                    <i class="fas fa-hourglass-start text-cyan-600 text-lg sm:text-xl"></i>
                </div>

                <div>
                    <p class="text-[11px] font-bold text-cyan-600 uppercase tracking-wider">
                        Subscription Aktif
                    </p>

                    <p class="text-2xl sm:text-3xl font-black text-gray-900 leading-none mt-1">
                        {{ $aktifsubscription ?? 0 }}
                    </p>
                </div>
            </div>

            <!-- Subscription Tamat -->
            <div
                class="modern-card bg-white rounded-2xl shadow-sm border border-gray-100 p-4 sm:p-5 flex items-center hover:bg-amber-50/40 hover:-translate-y-1 hover:shadow-md transition-all duration-300">

                <div
                    class="w-11 h-11 sm:w-12 sm:h-12 bg-amber-100 rounded-xl flex items-center justify-center mr-4 shrink-0">
                    <i class="fas fa-hourglass-end text-amber-600 text-lg sm:text-xl"></i>
                </div>

                <div>
                    <p class="text-[11px] font-bold text-amber-600 uppercase tracking-wider">
                        Subscription Tamat
                    </p>

                    <p class="text-2xl sm:text-3xl font-black text-gray-900 leading-none mt-1">
                        {{ $takAktifSubscription ?? 0 }}
                    </p>
                </div>
            </div>

        </div>

        <div class="glass-filter sticky top-4 z-20 modern-card rounded-2xl p-4 mb-8 border-indigo-100/50">
            <div class="flex flex-col xl:flex-row gap-4">
                <div class="flex-1 relative">
                    <i class="fas fa-search absolute left-4 top-1/2 -translate-y-1/2 text-gray-400"></i>
                    <input type="text" id="search" placeholder="Cari nama, telefon atau alamat..."
                        class="w-full pl-11 pr-4 py-3 bg-gray-50/50 border border-gray-200 rounded-xl focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all text-sm outline-none">
                </div>

                <div class="flex flex-wrap items-center gap-2">
                    <!-- Year Filter Group -->
                    <div class="year-filter-group bg-white border border-gray-200 rounded-xl px-3 py-1.5">
                        <i class="fas fa-calendar-alt text-gray-400 text-sm"></i>
                        <select id="year-filter"
                            class="py-2 pr-8 text-sm font-medium focus:outline-none bg-transparent cursor-pointer hover:text-indigo-600 transition-colors">
                            <option value="all">Semua Tahun</option>
                            @php
                                $currentYear = date('Y');
                                $startYear = $currentYear - 2; // Show last 2 years
                                $endYear = $currentYear + 2; // Show next 2 years
                            @endphp
                            @for ($year = $startYear; $year <= $endYear; $year++)
                                <option value="{{ $year }}" {{ $year == $currentYear ? 'selected' : '' }}>
                                    {{ $year }}
                                </option>
                            @endfor
                        </select>
                    </div>

                    <select id="status-filter"
                        class="px-4 py-3 uppercase bg-white border border-gray-200 rounded-xl text-sm font-medium focus:ring-2 focus:ring-indigo-500/20 outline-none cursor-pointer hover:border-indigo-300 transition-colors">
                        <option value="all">Semua Status</option>
                        <option value="Aktif">Aktif</option>
                        <option value="mati">mati</option>
                    </select>

                    <select id="membership-status"
                        class="px-4 py-3 uppercase bg-white border border-gray-200 rounded-xl text-sm font-medium focus:ring-2 focus:ring-indigo-500/20 outline-none cursor-pointer hover:border-indigo-300 transition-colors">
                        <option value="all">Semua Tempoh</option>
                        <option value="aktif">Aktif (Dalam Tempoh)</option>
                        <option value="akan-tamat">Akan Tamat (< 30 hari)</option>
                        <option value="tamat">Telah Tamat</option>
                    </select>

                    <button id="reset-filters"
                        class="px-5 py-3 uppercase bg-white text-gray-700 rounded-xl hover:bg-gray-50 transition-all text-sm font-bold border border-gray-200 active:scale-95">
                        <i class="fas fa-redo-alt mr-2 text-gray-400"></i>Reset
                    </button>
                </div>
            </div>
        </div>

        <div class="modern-card rounded-2xl overflow-hidden border-none shadow-xl shadow-gray-200/50">
            <div class="overflow-x-auto modern-scrollbar">
                <table class="min-w-full">
                    <thead>
                        <tr class="bg-gray-50/50 border-b border-gray-100">
                            <th class="px-6 py-4 text-left text-[11px] font-bold text-gray-400 uppercase tracking-widest">#
                            </th>
                            <th class="px-6 py-4 text-left text-[11px] font-bold text-gray-400 uppercase tracking-widest">
                                Maklumat Ahli</th>
                            <th class="px-6 py-4 text-left text-[11px] font-bold text-gray-400 uppercase tracking-widest">
                                Hubungi / Alamat</th>
                            <th class="px-6 py-4 text-left text-[11px] font-bold text-gray-400 uppercase tracking-widest">
                                Status Ahli</th>
                            <th class="px-6 py-4 text-left text-[11px] font-bold text-gray-400 uppercase tracking-widest">
                                Tempoh Keahlian</th>
                            <th class="px-6 py-4 text-center text-[11px] font-bold text-gray-400 uppercase tracking-widest">
                                Tindakan</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50" id="members-table-body">
                        @forelse($paginatedSenarai as $index => $member)
                            @php
                                // Calculate membership status for filtering
                                $membershipStatus = 'tiada';
                                if ($member->membership_start && $member->membership_end) {
                                    $start = \Carbon\Carbon::parse($member->membership_start);
                                    $end = \Carbon\Carbon::parse($member->membership_end);
                                    $now = \Carbon\Carbon::now();
                                    $daysRemaining = (int) $now->diffInDays($end, false);

                                    if ($daysRemaining < 0) {
                                        $membershipStatus = 'tamat';
                                    } elseif ($daysRemaining < 30) {
                                        $membershipStatus = 'akan-tamat';
                                    } else {
                                        $membershipStatus = 'aktif';
                                    }
                                }

                                // Get membership year
                                $membershipYear = $member->membership_start
                                    ? date('Y', strtotime($member->membership_start))
                                    : null;
                            @endphp

                            <tr class="table-row-hover" data-membership-status="{{ $membershipStatus }}"
                                data-membership-year="{{ $membershipYear }}">
                                <td class="px-6 py-5 whitespace-nowrap text-sm font-bold text-gray-300">
                                    {{ ($paginatedSenarai->currentPage() - 1) * $paginatedSenarai->perPage() + $index + 1 }}
                                </td>

                                <td class="px-6 py-5 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div
                                            class="flex-shrink-0 w-11 h-11 rounded-xl bg-gradient-to-tr 
                                        {{ $member->jantina == 'LELAKI' ? 'from-indigo-500 to-blue-400' : 'from-rose-500 to-pink-400' }} 
                                        flex items-center justify-center text-white text-base font-black shadow-inner">
                                            {{ strtoupper(substr($member->nama, 0, 1) . substr(strstr($member->nama, ' '), 1, 1)) }}
                                        </div>

                                        <div class="ml-4">
                                            <div class="flex items-center gap-2">
                                                <span class="text-sm font-bold text-gray-900">{{ $member->nama }}</span>
                                                @if (isset($member->oku) && $member->oku)
                                                    <span
                                                        class="px-2 py-0.5 bg-amber-100 text-amber-700 rounded-md text-[10px] font-black">OKU</span>
                                                @endif
                                            </div>
                                            <div class="flex items-center gap-2 mt-1">
                                                <span class="text-xs font-medium text-gray-400">
                                                    {{ $member->ic }}
                                                </span>
                                                @if ($member->jumlah_tanggungan > 0)
                                                    <span
                                                        class="text-[10px] font-bold tracking-tight text-blue-600 bg-blue-50 px-2 py-0.5 rounded">
                                                        {{ $member->jumlah_tanggungan }} Tanggungan
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </td>

                                <td class="px-6 py-5 whitespace-nowrap">
                                    <div class="text-xs font-semibold text-gray-700">
                                        {{ $member->notel ?? '-' }}
                                    </div>
                                    <div class="text-xs text-gray-400 mt-1 flex items-center w-50">
                                        <i class="fas fa-map-marker-alt mr-1 text-gray-300"></i>
                                        {{ Str::limit($member->alamat ?? 'Tiada alamat',40 ) }}
                                    </div>
                                </td>


                                <td class="px-6 py-5 whitespace-nowrap uppercase">
                                    <span
                                        class="inline-flex items-center px-3 py-1 rounded-lg text-xs font-bold
        {{ $member->status === 'active' ? 'bg-emerald-50 text-emerald-700' : 'bg-rose-50 text-rose-700' }}">
                                        <span
                                            class="w-1.5 h-1.5 rounded-full mr-2
            {{ $member->status === 'active' ? 'bg-emerald-500' : 'bg-rose-500' }}">
                                        </span>
                                        {{ $member->status === 'active' ? 'Aktif' : 'mati' }}
                                    </span>
                                </td>
                                <td class="px-6 py-5 min-w-[200px]">
                                    @if ($member->membership_start && $member->membership_end)
                                        @php
                                            $start = \Carbon\Carbon::parse($member->membership_start);
                                            $end = \Carbon\Carbon::parse($member->membership_end);
                                            $now = \Carbon\Carbon::now();

                                            // Check if expired (end date is in the past)
                                            $isExpired = $end->isPast();
                                            $daysRemaining = (int) $now->diffInDays($end, false);
                                            $totalDays = (int) $start->diffInDays($end);
                                            $daysPassed = (int) $start->diffInDays($now);

                                            // Calculate percentage (cap at 100%)
                                            $percentage =
                                                $totalDays > 0 ? min(100, max(0, ($daysPassed / $totalDays) * 100)) : 0;

                                            // Determine status color and text based on expiry
                                            if ($isExpired) {
                                                $statusColor = 'rose';
                                                $statusText = 'Tamat';
                                                $percentage = 100; // Full bar for expired
                                            } elseif ($daysRemaining < 30) {
                                                $statusColor = 'amber';
                                                $statusText = 'Hampir Tamat';
                                            } else {
                                                $statusColor = 'emerald';
                                                $statusText = 'Aktif';
                                            }
                                        @endphp

                                        <div class="relative group cursor-help">
                                            <div class="flex items-center justify-between mb-2">
                                                <span
                                                    class="text-[10px] font-bold text-gray-400">{{ $start->format('d/m/y') }}</span>
                                                <span
                                                    class="text-[10px] font-black uppercase text-{{ $statusColor }}-600">{{ $statusText }}</span>
                                                <span
                                                    class="text-[10px] font-bold text-gray-400">{{ $end->format('d/m/y') }}</span>
                                            </div>

                                            <div class="w-full h-2 bg-gray-100 rounded-full overflow-hidden">
                                                <div class="h-full rounded-full transition-all duration-500 bg-{{ $statusColor }}-500 shadow-sm"
                                                    style="width: {{ $percentage }}%">
                                                </div>
                                            </div>

                                            <div
                                                class="absolute z-30 invisible group-hover:visible bg-slate-900 text-white text-[11px] rounded-xl py-3 px-4 -top-16 left-1/2 transform -translate-x-1/2 w-48 shadow-2xl transition-all">
                                                <div class="flex justify-between mb-1">
                                                    <span class="text-slate-400 font-medium">Baki:</span>
                                                    <span class="font-bold text-white">
                                                        @if ($isExpired)
                                                            0 hari (Tamat)
                                                        @else
                                                            {{ $daysRemaining }} hari
                                                        @endif
                                                    </span>
                                                </div>
                                                <div class="flex justify-between">
                                                    <span class="text-slate-400 font-medium">Berlalu:</span>
                                                    <span class="font-bold text-white">{{ min($daysPassed, $totalDays) }}
                                                        hari</span>
                                                </div>
                                                <div
                                                    class="absolute -bottom-1 left-1/2 -translate-x-1/2 w-2 h-2 bg-slate-900 rotate-45">
                                                </div>
                                            </div>
                                        </div>
                                    @else
                                        <span class="text-xs font-medium text-gray-300 italic">Tiada data tempoh</span>
                                    @endif
                                </td>

                                <td class="px-6 py-5 whitespace-nowrap text-center">
                                    @if ($member->type == 'ahli')
                                        <a href="{{ route('butiran.ahli', $member->id) }}"
                                            class="inline-flex items-center justify-center w-9 h-9 bg-gray-50 text-gray-600 rounded-xl hover:bg-indigo-600 hover:text-white transition-all shadow-sm border border-gray-100"
                                            title="Lihat Butiran">
                                            <i class="fas fa-eye text-sm"></i>
                                        </a>
                                        
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-20 text-center">
                                    <div
                                        class="inline-flex items-center justify-center w-20 h-20 bg-gray-50 rounded-full mb-4">
                                        <i class="fas fa-users-slash text-gray-300 text-3xl"></i>
                                    </div>
                                    <h3 class="text-lg font-bold text-gray-900">Tiada Data Ditemui</h3>
                                    <p class="text-gray-500 max-w-xs mx-auto mt-2">Maaf, kami tidak menemui sebarang ahli
                                        kariah buat masa ini.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($paginatedSenarai->hasPages())
                <div class="bg-gray-50/50 border-t border-gray-100 px-8 py-5">
                    <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
                        <div class="text-xs font-bold text-gray-400 uppercase tracking-widest">
                            Data <span class="text-gray-900">{{ $paginatedSenarai->firstItem() }}</span> - <span
                                class="text-gray-900">{{ $paginatedSenarai->lastItem() }}</span> dari <span
                                class="text-gray-900">{{ $paginatedSenarai->total() }}</span>
                        </div>

                        <div class="flex items-center gap-1.5">
                            @if ($paginatedSenarai->onFirstPage())
                                <span
                                    class="w-10 h-10 flex items-center justify-center bg-white border border-gray-100 text-gray-300 rounded-xl cursor-not-allowed">
                                    <i class="fas fa-chevron-left text-xs"></i>
                                </span>
                            @else
                                <a href="{{ $paginatedSenarai->previousPageUrl() }}"
                                    class="w-10 h-10 flex items-center justify-center bg-white border border-gray-200 text-gray-600 rounded-xl hover:bg-white hover:border-indigo-500 hover:text-indigo-600 transition-all shadow-sm active:scale-90">
                                    <i class="fas fa-chevron-left text-xs"></i>
                                </a>
                            @endif

                            @foreach ($paginatedSenarai->getUrlRange(max(1, $paginatedSenarai->currentPage() - 2), min($paginatedSenarai->lastPage(), $paginatedSenarai->currentPage() + 2)) as $page => $url)
                                <a href="{{ $url }}"
                                    class="w-10 h-10 flex items-center justify-center rounded-xl text-xs font-black transition-all shadow-sm
                               {{ $page == $paginatedSenarai->currentPage()
                                   ? 'bg-indigo-600 text-white shadow-indigo-200'
                                   : 'bg-white border border-gray-200 text-gray-600 hover:border-indigo-500' }}">
                                    {{ $page }}
                                </a>
                            @endforeach

                            @if ($paginatedSenarai->hasMorePages())
                                <a href="{{ $paginatedSenarai->nextPageUrl() }}"
                                    class="w-10 h-10 flex items-center justify-center bg-white border border-gray-200 text-gray-600 rounded-xl hover:bg-white hover:border-indigo-500 hover:text-indigo-600 transition-all shadow-sm active:scale-90">
                                    <i class="fas fa-chevron-right text-xs"></i>
                                </a>
                            @else
                                <span
                                    class="w-10 h-10 flex items-center justify-center bg-white border border-gray-100 text-gray-300 rounded-xl cursor-not-allowed">
                                    <i class="fas fa-chevron-right text-xs"></i>
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('search');
            const statusFilter = document.getElementById('status-filter');
            const yearFilter = document.getElementById('year-filter');
            const membershipStatusFilter = document.getElementById('membership-status');
            const resetBtn = document.getElementById('reset-filters');
            const rows = document.querySelectorAll('#members-table-body tr:not(:has(td[colspan]))');

            function filterTable() {
                const searchTerm = searchInput.value.toLowerCase();
                const statusValue = statusFilter.value;
                const yearValue = yearFilter.value;
                const membershipValue = membershipStatusFilter.value;

                let visibleCount = 0;

                rows.forEach(row => {
                    // Get name (column 2)
                    const name = row.querySelector('td:nth-child(2) .text-sm.font-bold')?.textContent
                        .toLowerCase() || '';

                    // Get IC (column 2 - inside the div after name)
                    const icElement = row.querySelector('td:nth-child(2) .text-xs.font-medium');
                    const ic = icElement?.textContent.toLowerCase() || '';

                    // Get contact/phone (column 3 - first div)
                    const contact = row.querySelector('td:nth-child(3) .text-sm.font-semibold')?.textContent
                        .toLowerCase() || '';

                    // Get address (column 3 - the text-xs element)
                    const addressElement = row.querySelector('td:nth-child(3) .text-xs');
                    const address = addressElement?.textContent.toLowerCase() || '';

                    // Also get the full alamat from data attribute if available
                    const fullAddress = row.querySelector('td:nth-child(3) .text-xs')?.textContent
                        .toLowerCase() || '';

                    const status = row.querySelector('td:nth-child(4) span')?.textContent.trim() || '';
                    const membershipStatus = row.dataset.membershipStatus;
                    const membershipYear = row.dataset.membershipYear;

                    // Search in name, IC, contact, and address
                    const matchesSearch = searchTerm === '' ||
                        name.includes(searchTerm) ||
                        ic.includes(searchTerm) ||
                        contact.includes(searchTerm) ||
                        address.includes(searchTerm) ||
                        fullAddress.includes(searchTerm);

                    // Year filter
                    const matchesYear = yearValue === 'all' || membershipYear === yearValue;

                    // Membership status filter
                    const matchesMembership = membershipValue === 'all' || membershipStatus ===
                        membershipValue;

                    // Status filter
                    const matchesStatus = statusValue === 'all' || status === statusValue;

                    const matchesAll = matchesSearch && matchesStatus && matchesYear && matchesMembership;

                    if (matchesAll) {
                        row.style.display = '';
                        visibleCount++;
                    } else {
                        row.style.display = 'none';
                    }
                });

                // Show/hide no results
                const noResultsRow = document.querySelector('#members-table-body tr td[colspan="6"]')
                ?.parentElement;
                if (noResultsRow) {
                    noResultsRow.style.display = visibleCount === 0 ? '' : 'none';
                }
            }

            // Event listeners
            searchInput.addEventListener('input', filterTable);
            statusFilter.addEventListener('change', filterTable);
            yearFilter.addEventListener('change', filterTable);
            membershipStatusFilter.addEventListener('change', filterTable);

            resetBtn.addEventListener('click', () => {
                searchInput.value = '';
                statusFilter.value = 'all';
                yearFilter.value = 'all';
                membershipStatusFilter.value = 'all';
                filterTable();
            });

            // Share link functionality
            document.getElementById('share-link')?.addEventListener('click', function() {
    const btn = this;
    const originalContent = btn.innerHTML;
    
    // Get masjid data from the authenticated user
    const masjid = @json(auth()->user()->masjid);
    
    if (!masjid) {
        alert('Tiada masjid dijumpai untuk pengguna ini.');
        return;
    }
    
    // Use slug from database if available, otherwise generate it
    let slug = masjid.slug;
    if (!slug) {
        // Generate slug from name (fallback)
        slug = masjid.nama.toLowerCase()
            .replace(/[^a-z0-9]/g, '-')
            .replace(/-+/g, '-')
            .replace(/^-|-$/g, '');
    }
    
    const shareUrl = window.location.origin + '/daftar/' + slug;
    
    // Try to share using Web Share API first
    if (navigator.share) {
        navigator.share({
            title: 'Daftar Ahli Khairat',
            text: 'Sila gunakan pautan ini untuk mendaftar sebagai Ahli Khairat:',
            url: shareUrl
        }).catch((error) => {
            // If share fails or is cancelled, fallback to clipboard
            if (error.name !== 'AbortError') {
                copyToClipboard(shareUrl, btn, originalContent);
            }
        });
    } else {
        // Fallback to clipboard copy
        copyToClipboard(shareUrl, btn, originalContent);
    }
});

// Helper function for clipboard copy with UI feedback
function copyToClipboard(text, btn, originalContent) {
    navigator.clipboard.writeText(text).then(() => {
        // Show success feedback
        btn.innerHTML = '<i class="fas fa-check mr-2"></i> Disalin!';
        btn.classList.replace('bg-slate-900', 'bg-emerald-600');
        
        // Reset after 2 seconds
        setTimeout(() => {
            btn.innerHTML = originalContent;
            btn.classList.replace('bg-emerald-600', 'bg-slate-900');
        }, 2000);
    }).catch(() => {
        // Manual copy fallback
        const textArea = document.createElement('textarea');
        textArea.value = text;
        document.body.appendChild(textArea);
        textArea.select();
        try {
            document.execCommand('copy');
            btn.innerHTML = '<i class="fas fa-check mr-2"></i> Disalin!';
            btn.classList.replace('bg-slate-900', 'bg-emerald-600');
            setTimeout(() => {
                btn.innerHTML = originalContent;
                btn.classList.replace('bg-emerald-600', 'bg-slate-900');
            }, 2000);
        } catch (err) {
            alert('Gagal menyalin link. Sila salin manual: ' + text);
        }
        document.body.removeChild(textArea);
    });
}
        });
    </script>

@endsection
