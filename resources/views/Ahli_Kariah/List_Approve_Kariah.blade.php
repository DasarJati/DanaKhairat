@extends('layouts.app')

@section('title', 'Senarai Ahli Khairat - e-Khairat')

@section('content')
    <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-6">



        <div class="flex flex-col md:flex-row md:items-end md:justify-between mb-8">
            <div>
                <h1 class="text-3xl font-extrabold text-gray-900 tracking-tight">Senarai Pengesahan Ahli</h1>
                <p class="text-gray-500 mt-2 font-medium">Dashboard pengurusan dan data Pengesahan Ahli.</p>
            </div>
            <div class="mt-6 md:mt-0">
                <button id="share-link"
                    class="inline-flex items-center px-5 py-2.5 bg-slate-900 text-white rounded-xl hover:bg-slate-800 shadow-lg shadow-slate-200 transition-all duration-200 text-sm font-semibold">
                    <i class="fas fa-share-alt mr-2 text-slate-400"></i>
                    Kongsi Pautan Pendaftaran
                </button>
            </div>
        </div>

        <!-- Stats Cards with Animations -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            <div
                class="bg-white rounded-2xl shadow-lg shadow-gray-100 p-6 transform hover:scale-105 transition-all duration-300 border border-gray-100">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Jumlah Senarai</p>
                        <p class="text-3xl font-black text-gray-900 mt-2">{{ $total }}</p>
                    </div>
                    <div class="w-14 h-14 bg-indigo-50 rounded-2xl flex items-center justify-center">
                        <svg class="w-7 h-7 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z">
                            </path>
                        </svg>
                    </div>
                </div>
            </div>

            <div
                class="bg-white rounded-2xl shadow-lg shadow-gray-100 p-6 transform hover:scale-105 transition-all duration-300 border border-gray-100">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Menunggu</p>
                        <p class="text-3xl font-black text-yellow-600 mt-2">{{ $pending }}</p>
                    </div>
                    <div class="w-14 h-14 bg-yellow-50 rounded-2xl flex items-center justify-center">
                        <svg class="w-7 h-7 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <div
                class="bg-white rounded-2xl shadow-lg shadow-gray-100 p-6 transform hover:scale-105 transition-all duration-300 border border-gray-100">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Diluluskan</p>
                        <p class="text-3xl font-black text-green-600 mt-2">{{ $approved }}</p>
                    </div>
                    <div class="w-14 h-14 bg-green-50 rounded-2xl flex items-center justify-center">
                        <svg class="w-7 h-7 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <div
                class="bg-white rounded-2xl shadow-lg shadow-gray-100 p-6 transform hover:scale-105 transition-all duration-300 border border-gray-100">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Ditolak</p>
                        <p class="text-3xl font-black text-red-600 mt-2">{{ $rejected }}</p>
                    </div>
                    <div class="w-14 h-14 bg-red-50 rounded-2xl flex items-center justify-center">
                        <svg class="w-7 h-7 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filter Section -->
        <div class="bg-white rounded-2xl shadow-lg shadow-gray-100 p-6 border border-gray-100">
            <form id="filterForm" method="GET" action="{{ route('kariah.list.pengesahan') }}" class="space-y-4">
                <div class="flex flex-col lg:flex-row gap-4">
                    <!-- Search -->
                    <div class="flex-1">
                        <div class="relative">
                            <input type="text" name="search" value="{{ $currentSearch }}"
                                placeholder="Cari nama, no. IC atau alamat..."
                                class="w-full pl-11 pr-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all text-sm">
                            <svg class="w-5 h-5 text-gray-400 absolute left-4 top-1/2 -translate-y-1/2" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                        </div>
                    </div>

                    <!-- Status Filter -->
                    <div class="w-full lg:w-48">
                        <select name="status"
                            class="w-full px-4 py-3 uppercase bg-gray-50 border border-gray-200 rounded-xl text-sm focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all">
                            <option value="">Semua Status</option>
                            <option value="PENDING" {{ $currentStatus == 'PENDING' ? 'selected' : '' }}>Menunggu</option>
                            <option value="APPROVED" {{ $currentStatus == 'APPROVED' ? 'selected' : '' }}>Diluluskan
                            </option>
                            <option value="REJECTED" {{ $currentStatus == 'REJECTED' ? 'selected' : '' }}>Ditolak</option>
                        </select>
                    </div>

                    <!-- Buttons -->
                    <!-- Reset Only -->
                    <div class="flex items-end">
                        <a href="{{ route('kariah.list.pengesahan') }}"
                            class="w-full lg:w-auto text-center px-6 py-3 uppercase bg-gray-100 text-gray-700 rounded-xl hover:bg-gray-200 transition-all text-sm font-semibold">
                            Reset
                        </a>
                    </div>
                </div>
            </form>
        </div>

        <!-- Table Section -->
        <div class="bg-white rounded-2xl shadow-lg shadow-gray-100 border border-gray-100 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-100">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-400 uppercase tracking-wider">#</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-400 uppercase tracking-wider">
                                Nama Permohon</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-400 uppercase tracking-wider">No. IC
                                / Alamat
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-400 uppercase tracking-wider">Tarikh
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-400 uppercase tracking-wider">Status
                            </th>
                            <th class="px-6 py-4 text-right text-xs font-bold text-gray-400 uppercase tracking-wider">
                                Tindakan</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($applications as $index => $user)
                            <tr class="hover:bg-gray-50/50 transition-all duration-150">
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-400">
                                    {{ ($applications->currentPage() - 1) * $applications->perPage() + $index + 1 }}
                                </td>

                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div
                                            class="flex-shrink-0 w-10 h-10 rounded-xl bg-gradient-to-br from-indigo-500 to-indigo-600 flex items-center justify-center text-white text-sm font-bold shadow-lg shadow-indigo-100">
                                            {{ strtoupper(substr($user->nama, 0, 1)) }}
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-bold text-gray-900">{{ $user->nama }}</div>
                                            <div class="text-xs text-gray-500 mt-0.5">
                                                {{ $user->jantina ?? '-' }} | {{ $user->umur ?? '-' }} tahun
                                            </div>
                                        </div>
                                    </div>
                                </td>



                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm">
                                        <i class="far fa-id-card mr-1 text-gray-400"></i>
                                        @php
                                            $ic = $user->ic_number;
                                            if (strlen($ic) >= 16) {
                                                echo substr($ic, 0, 12) .
                                                    '-' .
                                                    substr($ic, 12, 4) .
                                                    '-' .
                                                    substr($ic, 16);
                                            } else {
                                                echo $ic;
                                            }
                                        @endphp
                                    </div>
                                    <div class="text-sm text-black mt-1 flex items-center w-50">
                                        <i class="fas fa-map-marker-alt mr-1 text-gray-400"></i>
                                        {{ Str::limit($user->alamat ?? 'Tiada alamat', 60) }}
                                    </div>
                                </td>

                                <td class="flex flex-col items-start px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-700">
                                        {{ $user->created_at->format('d/m/Y') }}
                                    </div>
                                    <div class="text-xs text-gray-700 flex items-center gap-1">
                                        <i class="fas fa-clock text-gray-400 text-xs "></i>
                                        <span
                                            class="font-bold text-gray-500">{{ $user->created_at->format('h:i A') }}</span>
                                    </div>
                                </td>



                                <td class="px-6 py-4 whitespace-nowrap uppercase">
                                    @if ($user->approval_status == 'APPROVED')
                                        <span
                                            class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-700">
                                            <span class="w-1.5 h-1.5 rounded-full bg-green-600 mr-1.5"></span>
                                            Diluluskan
                                        </span>
                                    @elseif($user->approval_status == 'PENDING')
                                        <span
                                            class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-700">
                                            <span class="w-1.5 h-1.5 rounded-full bg-yellow-600 mr-1.5"></span>
                                            Menunggu
                                        </span>
                                    @elseif($user->approval_status == 'REJECTED')
                                        <span
                                            class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-red-100 text-red-700">
                                            <span class="w-1.5 h-1.5 rounded-full bg-red-600 mr-1.5"></span>
                                            Ditolak
                                        </span>
                                    @endif
                                </td>


                                <td class="px-6 py-4 whitespace-nowrap text-right">
                                    <a href="{{ route('approve.kariah', $user->id) }}"
                                        class="px-4 py-2 bg-blue-600 text-white rounded-lg">
                                        Lihat
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-20 text-center">
                                    <div class="flex flex-col items-center justify-center">
                                        <div
                                            class="w-20 h-20 bg-gray-50 rounded-2xl flex items-center justify-center mb-4">
                                            <svg class="w-10 h-10 text-gray-300" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z">
                                                </path>
                                            </svg>
                                        </div>
                                        <h3 class="text-lg font-bold text-gray-900">Tiada Data Ditemui</h3>
                                        <p class="text-sm text-gray-500 mt-1 max-w-sm">Maaf, kami tidak menemui sebarang
                                            rekod Ahli Khairat.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if ($applications->hasPages())
                <div class="px-6 py-4 bg-gray-50 border-t border-gray-100">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                        <div class="text-xs text-gray-500">
                            Menunjukkan <span class="font-medium text-gray-900">{{ $applications->firstItem() }}</span>
                            - <span class="font-medium text-gray-900">{{ $applications->lastItem() }}</span>
                            daripada <span class="font-medium text-gray-900">{{ $applications->total() }}</span> rekod
                        </div>

                        <div class="flex items-center gap-2">
                            @if ($applications->onFirstPage())
                                <span
                                    class="px-4 py-2 bg-white border border-gray-200 text-gray-300 rounded-xl cursor-not-allowed text-sm">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 19l-7-7 7-7"></path>
                                    </svg>
                                </span>
                            @else
                                <a href="{{ $applications->previousPageUrl() }}"
                                    class="px-4 py-2 bg-white border border-gray-200 text-gray-600 rounded-xl hover:bg-indigo-50 hover:border-indigo-300 hover:text-indigo-600 transition-all text-sm">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 19l-7-7 7-7"></path>
                                    </svg>
                                </a>
                            @endif

                            @foreach ($applications->getUrlRange(max(1, $applications->currentPage() - 2), min($applications->lastPage(), $applications->currentPage() + 2)) as $page => $url)
                                <a href="{{ $url }}"
                                    class="px-4 py-2 {{ $page == $applications->currentPage() ? 'bg-indigo-600 text-white' : 'bg-white text-gray-600 hover:bg-indigo-50 hover:text-indigo-600' }} border {{ $page == $applications->currentPage() ? 'border-indigo-600' : 'border-gray-200' }} rounded-xl transition-all text-sm font-medium">
                                    {{ $page }}
                                </a>
                            @endforeach

                            @if ($applications->hasMorePages())
                                <a href="{{ $applications->nextPageUrl() }}"
                                    class="px-4 py-2 bg-white border border-gray-200 text-gray-600 rounded-xl hover:bg-indigo-50 hover:border-indigo-300 hover:text-indigo-600 transition-all text-sm">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 5l7 7-7 7"></path>
                                    </svg>
                                </a>
                            @else
                                <span
                                    class="px-4 py-2 bg-white border border-gray-200 text-gray-300 rounded-xl cursor-not-allowed text-sm">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 5l7 7-7 7"></path>
                                    </svg>
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <!-- Modals (same as before) -->
    <!-- Approve Modal -->
    <div id="approveModal" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modal-title"
        role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-gray-900 bg-opacity-50 backdrop-blur-sm transition-opacity" aria-hidden="true">
            </div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div
                class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <form id="approveForm" method="POST" action="">
                    @csrf
                    @method('PUT')
                    <div class="bg-white px-6 pt-6 pb-4">
                        <div class="sm:flex sm:items-start">
                            <div
                                class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-xl bg-green-100 sm:mx-0 sm:h-10 sm:w-10">
                                <svg class="h-6 w-6 text-green-600" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 13l4 4L19 7"></path>
                                </svg>
                            </div>
                            <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                                <h3 class="text-lg font-bold text-gray-900">Luluskan Permohonan</h3>
                                <div class="mt-2">
                                    <p class="text-sm text-gray-500">
                                        Anda pasti untuk meluluskan permohonan <span id="approveUserName"
                                            class="font-bold text-gray-900"></span>?
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-6 py-4 flex flex-row-reverse gap-3">
                        <button type="submit"
                            class="inline-flex justify-center rounded-xl border border-transparent shadow-sm px-4 py-2 bg-green-600 text-sm font-medium text-white hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                            Luluskan
                        </button>
                        <button type="button" onclick="closeModal('approveModal')"
                            class="inline-flex justify-center rounded-xl border border-gray-300 shadow-sm px-4 py-2 bg-white text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            Batal
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Reject Modal -->
    <div id="rejectModal" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog"
        aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-gray-900 bg-opacity-50 backdrop-blur-sm transition-opacity" aria-hidden="true">
            </div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div
                class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <form id="rejectForm" method="POST" action="">
                    @csrf
                    @method('PUT')
                    <div class="bg-white px-6 pt-6 pb-4">
                        <div class="sm:flex sm:items-start">
                            <div
                                class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-xl bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                                <svg class="h-6 w-6 text-red-600" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </div>
                            <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                                <h3 class="text-lg font-bold text-gray-900">Tolak Permohonan</h3>
                                <div class="mt-2">
                                    <p class="text-sm text-gray-500 mb-3">
                                        Anda pasti untuk menolak permohonan <span id="rejectUserName"
                                            class="font-bold text-gray-900"></span>?
                                    </p>
                                    <textarea name="rejection_reason" rows="3"
                                        class="w-full rounded-xl border-gray-200 focus:ring-red-500 focus:border-red-500 text-sm"
                                        placeholder="Sebab penolakan (opsional)"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-6 py-4 flex flex-row-reverse gap-3">
                        <button type="submit"
                            class="inline-flex justify-center rounded-xl border border-transparent shadow-sm px-4 py-2 bg-red-600 text-sm font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                            Tolak
                        </button>
                        <button type="button" onclick="closeModal('rejectModal')"
                            class="inline-flex justify-center rounded-xl border border-gray-300 shadow-sm px-4 py-2 bg-white text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            Batal
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Delete Modal -->
    <div id="deleteModal" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog"
        aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-gray-900 bg-opacity-50 backdrop-blur-sm transition-opacity" aria-hidden="true">
            </div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div
                class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <form id="deleteForm" method="POST" action="">
                    @csrf
                    @method('DELETE')
                    <div class="bg-white px-6 pt-6 pb-4">
                        <div class="sm:flex sm:items-start">
                            <div
                                class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-xl bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                                <svg class="h-6 w-6 text-red-600" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z">
                                    </path>
                                </svg>
                            </div>
                            <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                                <h3 class="text-lg font-bold text-gray-900">Padam Permohonan</h3>
                                <div class="mt-2">
                                    <p class="text-sm text-gray-500">
                                        Anda pasti untuk memadam permohonan <span id="deleteUserName"
                                            class="font-bold text-gray-900"></span>? Tindakan ini tidak boleh dibatalkan.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-6 py-4 flex flex-row-reverse gap-3">
                        <button type="submit"
                            class="inline-flex justify-center rounded-xl border border-transparent shadow-sm px-4 py-2 bg-red-600 text-sm font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                            Padam
                        </button>
                        <button type="button" onclick="closeModal('deleteModal')"
                            class="inline-flex justify-center rounded-xl border border-gray-300 shadow-sm px-4 py-2 bg-white text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            Batal
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
    <script>
        // Modal functions
        function openApproveModal(userId, userName) {
            document.getElementById('approveUserName').textContent = userName;
            document.getElementById('approveForm').action = `/ajk/approve-user/${userId}`;
            document.getElementById('approveModal').classList.remove('hidden');
        }

        function openRejectModal(userId, userName) {
            document.getElementById('rejectUserName').textContent = userName;
            document.getElementById('rejectForm').action = `/ajk/reject-user/${userId}`;
            document.getElementById('rejectModal').classList.remove('hidden');
        }

        function deleteUser(userId, userName) {
            document.getElementById('deleteUserName').textContent = userName;
            document.getElementById('deleteForm').action = `/ajk/delete-user/${userId}`;
            document.getElementById('deleteModal').classList.remove('hidden');
        }

        function closeModal(modalId) {
            document.getElementById(modalId).classList.add('hidden');
        }

        // Close modal when clicking outside
        window.onclick = function(event) {
            if (event.target.classList.contains('bg-gray-900')) {
                document.querySelectorAll('[id$="Modal"]').forEach(modal => {
                    modal.classList.add('hidden');
                });
            }
        }

        // Share link functionality
        document.getElementById('share-link')?.addEventListener('click', function() {
            // Get masjid name from the authenticated user
            const masjidName = '{{ auth()->user()->masjid->nama ?? '' }}';

            // Generate slug from masjid name
            const slug = masjidName.toLowerCase()
                .replace(/[^a-z0-9]/g, '-')
                .replace(/-+/g, '-')
                .replace(/^-|-$/g, '');

            const shareUrl = window.location.origin + '/daftar/' + slug;

            if (navigator.share) {
                navigator.share({
                    title: 'Daftar Ahli Khairat',
                    text: 'Sila gunakan pautan ini untuk mendaftar sebagai Ahli Khairat:',
                    url: shareUrl
                }).catch(() => {
                    copyToClipboard(shareUrl);
                });
            } else {
                copyToClipboard(shareUrl);
            }
        });

        function copyToClipboard(text) {
            navigator.clipboard.writeText(text).then(() => {
                const btn = document.getElementById('share-link');
                const originalContent = btn.innerHTML;
                btn.innerHTML = `
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
            </svg>
            Disalin!
        `;
                btn.classList.add('bg-green-600', 'hover:bg-green-700');
                btn.classList.remove('bg-white/10', 'hover:bg-white/20');

                setTimeout(() => {
                    btn.innerHTML = originalContent;
                    btn.classList.remove('bg-green-600', 'hover:bg-green-700');
                    btn.classList.add('bg-white/10', 'hover:bg-white/20');
                }, 2000);
            }).catch(() => {
                alert('Gagal menyalin link. Sila salin manual: ' + text);
            });
        }

        // AUTO SEARCH + AUTO FILTER
        const filterForm = document.getElementById('filterForm');
        const searchInput = document.querySelector('input[name="search"]');
        const statusSelect = document.querySelector('select[name="status"]');

        let searchTimer;

        // Auto search when typing
        searchInput.addEventListener('keyup', function() {

            clearTimeout(searchTimer);

            searchTimer = setTimeout(() => {
                filterForm.submit();
            }, 300); // delay 0.3s
        });

        // Auto filter when status change
        statusSelect.addEventListener('change', function() {
            filterForm.submit();
        });
    </script>
@endpush
