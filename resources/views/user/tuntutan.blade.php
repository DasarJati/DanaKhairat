@extends('layouts.user')

@section('title', 'Semakan Tuntutan Khairat - e-Khairat')

@section('content')
    <div class="bg-[#f8fafc] min-h-screen font-sans pb-12">
        <div class="container mx-auto px-6 lg:px-10 py-8 max-w-full">

            @if (session('success'))
                <div class="mb-6 flex items-center bg-green-50 border-l-4 border-green-500 p-4 shadow-sm rounded-r-lg">
                    <div class="flex-shrink-0 text-green-500 mr-3">
                        <i class="fas fa-check-circle fa-lg"></i>
                    </div>
                    <div class="text-green-800 font-medium">{{ session('success') }}</div>
                </div>
            @endif

            <div class="flex flex-col md:flex-row justify-between items-end mb-8 gap-4">
                <div>
                    <h1 class="text-4xl font-extrabold text-gray-900 tracking-tight">
                        Semua Laporan <span class="text-gold-600">Kematian</span>
                    </h1>
                    <p class="text-gray-500 mt-1 text-sm font-medium italic">Sahkan permohonan dengan teliti.</p>
                </div>

                <div
                    class="w-full md:w-auto bg-white/60 backdrop-blur-md border border-white/40 p-2 rounded-2xl shadow-xl flex flex-wrap items-center gap-2">
                    <form action="" method="GET" class="flex flex-wrap items-center gap-2 w-full">
                        <div class="relative flex-1 min-w-[200px]">
                            <i class="fas fa-search absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 text-xs"></i>
                            <input type="text" name="search" placeholder="Cari nama atau IC..."
                                class="w-full pl-10 pr-4 py-2 bg-white border-none rounded-xl focus:ring-2 focus:ring-gold-500 shadow-sm text-xs">
                        </div>

                        <select name="status"
                            class="bg-white border-none rounded-xl py-2 px-4 shadow-sm text-xs focus:ring-2 focus:ring-gold-500 font-semibold">
                            <option value="">Semua Status</option>
                            <option value="PENDING" {{ request('status') == 'PENDING' ? 'selected' : '' }}>Menunggu</option>
                            <option value="APPROVED" {{ request('status') == 'APPROVED' ? 'selected' : '' }}>Lulus</option>
                            <option value="REJECTED" {{ request('status') == 'REJECTED' ? 'selected' : '' }}>Ditolak
                            </option>
                        </select>

                        <button type="submit"
                            class="bg-gold-600 hover:bg-gold-700 text-white px-6 py-2 rounded-xl font-bold transition shadow-lg shadow-gold-200 text-xs uppercase tracking-wider">
                            Tapis
                        </button>
                    </form>
                </div>
            </div>


            <div class="relative">
                <div class="bg-white rounded-[2rem] shadow-xl shadow-gray-200/50 border border-gray-100 overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-gray-50/50 border-b border-gray-100 uppercase">
                                    <th class="px-6 py-5 text-[10px] font-black text-gray-400 tracking-widest">No.</th>
                                    <th class="px-6 py-5 text-[10px] font-black text-gray-400 tracking-widest">ID & Tarikh
                                    </th>
                                    <th class="px-6 py-5 text-[10px] font-black text-gray-400 tracking-widest">Pemohon</th>
                                    <th class="px-6 py-5 text-[10px] font-black text-gray-400 tracking-widest">Si Mati</th>
                                    <th class="px-6 py-5 text-[10px] font-black text-gray-400 tracking-widest">Dokumen</th>
                                    <th class="px-6 py-5 text-[10px] font-black text-gray-400 tracking-widest">Status</th>
                                    <th class="px-6 py-5 text-[10px] font-black text-gray-400 tracking-widest text-right">
                                        Tindakan</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-50">
                                @forelse($tuntutan as $item)
                                    <tr class="hover:bg-gold-50/20 transition-colors group">
                                        <td class="px-6 py-4">
                                            <span
                                                class="text-sm font-bold text-gray-300 group-hover:text-gold-600 transition-colors">
                                                {{ $loop->iteration }}.
                                            </span>
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="font-semibold text-gray-900 text-sm">
                                                {{ \Carbon\Carbon::parse($item->created_at)->format('d M Y, H:i') }}
                                            </div>

                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="font-bold text-gray-800 text-sm">{{ $item->nama_pewaris }}</div>
                                            <div class="text-[11px] text-gray-500 font-medium tracking-wider">
                                                {{ $item->ic_pewaris ? substr($item->ic_pewaris, 0, 6) . '-XX-XXXX' : 'Tiada IC' }}
                                            </div>
                                            <div class="text-[11px] text-blue-600 font-medium mt-1">
                                                <i class="fas fa-phone-alt mr-1"></i>
                                                {{ $item->telefon ?? 'Tiada telefon' }}
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 text-sm">
                                            <div class="font-semibold text-gray-700">{{ $item->nama_ahli }}</div>
                                            <span
                                                class="text-[9px] px-2 py-0.5 bg-gray-100 rounded-md text-gray-500 font-black uppercase tracking-widest">
                                                {{ optional($item->tanggungan)->hubungan ?? 'Ahli Utama' }}
                                            </span>
                                            <div class="text-[10px] text-gray-500 mt-1">
                                                <i class="fas fa-calendar-day mr-1"></i>
                                                {{ $item->tarikh_meninggal ? \Carbon\Carbon::parse($item->tarikh_meninggal)->format('d M Y') : 'Tiada tarikh' }}
                                            </div>
                                        </td>
                                        <td class="px-6 py-4">
                                            @if ($item->dokumen)
                                                <div class="flex flex-col  gap-2">
                                                    {{-- Sijil Kematian --}}
                                                    @if ($item->dokumen->sijil_kematian)
                                                        <button
                                                            onclick="showDocument('{{ $item->dokumen->sijil_kematian }}', 'Sijil Kematian')"
                                                            class="inline-flex items-center px-2.5 py-1 rounded-md bg-red-50 text-red-700 border border-red-100 text-[10px] font-semibold hover:bg-red-100 transition-all shadow-sm">
                                                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor"
                                                                viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2"
                                                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                                            </svg>
                                                            Sijil
                                                        </button>
                                                    @endif

                                                    {{-- IC Pewaris --}}
                                                    @if ($item->dokumen->ic_pewaris)
                                                        <button
                                                            onclick="showDocument('{{ $item->dokumen->ic_pewaris }}', 'IC Pewaris')"
                                                            class="inline-flex items-center px-2.5 py-1 rounded-md bg-blue-50 text-blue-700 border border-blue-100 text-[10px] font-semibold hover:bg-blue-100 transition-all shadow-sm">
                                                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor"
                                                                viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2"
                                                                    d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2" />
                                                            </svg>
                                                            IC
                                                        </button>
                                                    @endif

                                                    {{-- Bukti Bank --}}
                                                    @if ($item->dokumen->bukti_bank)
                                                        <button
                                                            onclick="showDocument('{{ $item->dokumen->bukti_bank }}', 'Bukti Bank')"
                                                            class="inline-flex items-center px-2.5 py-1 rounded-md bg-emerald-50 text-emerald-700 border border-emerald-100 text-[10px] font-semibold hover:bg-emerald-100 transition-all shadow-sm">
                                                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor"
                                                                viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2"
                                                                    d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                                                            </svg>
                                                            Bank
                                                        </button>
                                                    @endif
                                                </div>
                                            @else
                                                <div class="flex items-center text-gray-400">
                                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728L5.636 5.636" />
                                                    </svg>
                                                    <span class="text-[10px] italic font-medium">Tiada Dokumen</span>
                                                </div>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4">
                                            @php
                                                $statusClasses = [
                                                    'PENDING' => 'bg-amber-100 text-amber-700 ring-amber-600/20',
                                                    'APPROVED' => 'bg-emerald-100 text-emerald-700 ring-emerald-600/20',
                                                    'REJECTED' => 'bg-rose-100 text-rose-700 ring-rose-600/20',
                                                ];
                                                $class =
                                                    $statusClasses[$item->status] ??
                                                    'bg-gray-100 text-gray-700 ring-gray-600/20';
                                            @endphp
                                            <span
                                                class="inline-flex items-center rounded-full px-3 py-1 text-[10px] font-black ring-1 ring-inset shadow-sm {{ $class }}">
                                                <span class="mr-1.5 h-1.5 w-1.5 rounded-full bg-current"></span>
                                                {{ $item->status }}
                                            </span>
                                            @if ($item->diluluskan_oleh && $item->status != 'PENDING')
                                                <div class="text-[9px] text-gray-500 mt-1">
                                                    Oleh: {{ optional($item->diluluskanOleh)->name ?? 'Sistem' }}
                                                </div>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 text-right">
                                            <div class="flex justify-end">
                                                <!-- Three-dot dropdown menu -->
                                                <div class="" x-data="{ open: false }"
                                                    @click.outside="open = false">
                                                    <!-- Dropdown trigger -->
                                                    <button @click="open = !open"
                                                        class="p-2 text-gray-400 hover:text-gray-600 hover:bg-gray-100 rounded-xl transition-all"
                                                        title="Tindakan">
                                                        <i class="fas fa-ellipsis-v text-xs"></i>
                                                    </button>

                                                    <!-- Dropdown menu -->
                                                    <div x-show="open"
                                                        x-transition:enter="transition ease-out duration-100"
                                                        x-transition:enter-start="transform opacity-0 scale-95"
                                                        x-transition:enter-end="transform opacity-100 scale-100"
                                                        x-transition:leave="transition ease-in duration-75"
                                                        x-transition:leave-start="transform opacity-100 scale-100"
                                                        x-transition:leave-end="transform opacity-0 scale-95"
                                                        class="absolute right-0 mt-2 w-48 bg-white rounded-xl shadow-xl border border-gray-200 z-50 py-1">

                                                        <!-- Dokumen Button -->
                                                        @if ($item->dokumen)
                                                            <button onclick="showAllDocuments({{ $item->id }})"
                                                                class="w-full px-4 py-2.5 text-left text-sm hover:bg-gray-50 flex items-center text-purple-600">
                                                                <i class="fas fa-file-alt text-xs mr-3"></i>
                                                                Lihat Dokumen
                                                            </button>
                                                        @endif

                                                        <!-- Butiran Keluarga -->
                                                        <a href="{{ route('butiran.ahli', $item->user_id) }}"
                                                            class=" px-4 py-2.5 text-sm hover:bg-gray-50 flex items-center text-blue-600">
                                                            <i class="fas fa-eye text-xs mr-3"></i>
                                                            Butiran Keluarga
                                                        </a>

                                                        <!-- Actions for PENDING status -->
                                                        @if ($item->status == 'PENDING')
                                                            <button
                                                                onclick="openApproveModal({{ $item->id }}, '{{ $item->nama_pewaris }}')"
                                                                class="w-full px-4 py-2.5 text-left text-sm hover:bg-gray-50 flex items-center text-emerald-600">
                                                                <i class="fas fa-check text-xs mr-3"></i>
                                                                Luluskan
                                                            </button>

                                                            <form method="POST" action=""
                                                                onsubmit="return confirm('Tolak tuntutan ini?')"
                                                                class="w-full">
                                                                @csrf
                                                                @method('PUT')
                                                                <button type="submit"
                                                                    class="w-full px-4 py-2.5 text-left text-sm hover:bg-gray-50 flex items-center text-rose-600">
                                                                    <i class="fas fa-times text-xs mr-3"></i>
                                                                    Tolak
                                                                </button>
                                                            </form>
                                                        @endif

                                                        <!-- Catatan -->
                                                        <button
                                                            onclick="showCatatan({{ $item->id }}, '{{ $item->catatan }}')"
                                                            class="w-full px-4 py-2.5 text-left text-sm hover:bg-gray-50 flex items-center text-amber-600">
                                                            <i class="fas fa-sticky-note text-xs mr-3"></i>
                                                            Catatan
                                                        </button>

                                                        <!-- Status-specific actions (optional) -->
                                                        @if ($item->status == 'APPROVED')
                                                            <button
                                                                onclick="showBuktiKelulusan('{{ $item->bukti_kelulusan }}')"
                                                                class="w-full px-4 py-2.5 text-left text-sm hover:bg-gray-50 flex items-center text-green-600">
                                                                <i class="fas fa-receipt text-xs mr-3"></i>
                                                                Bukti Bayaran
                                                            </button>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="px-6 py-24 text-center">
                                            <i class="fas fa-folder-open text-gray-200 text-6xl"></i>
                                            <p class="text-gray-400 font-medium mt-4 italic">Rekod tidak ditemui...</p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Dokumen Modal -->
    <div id="dokumenModal" class="fixed inset-0 bg-black/70 flex items-center justify-center z-50 hidden p-4">
        <div class="bg-white rounded-3xl shadow-2xl w-full max-w-4xl max-h-[90vh] overflow-hidden">
            <div class="flex justify-between items-center p-6 border-b border-gray-200">
                <h3 class="text-xl font-bold text-gray-900" id="modalTitle">Dokumen Tuntutan</h3>
                <button onclick="closeModal()" class="text-gray-400 hover:text-gray-600 text-2xl">&times;</button>
            </div>
            <div class="p-6 overflow-y-auto max-h-[70vh]" id="modalContent">
                <!-- Content will be loaded via JavaScript -->
            </div>
        </div>
    </div>

    <!-- Catatan Modal -->
    <div id="catatanModal" class="fixed inset-0 bg-black/70 flex items-center justify-center z-50 hidden p-4">
        <div class="bg-white rounded-3xl shadow-2xl w-full max-w-md">
            <div class="p-6">
                <h3 class="text-xl font-bold text-gray-900 mb-4">Catatan Tuntutan</h3>
                <textarea id="catatanText" class="w-full border border-gray-300 rounded-xl p-4 min-h-[150px]"
                    placeholder="Tambah catatan..." readonly></textarea>
                <div class="flex justify-end gap-3 mt-6">
                    <button onclick="closeCatatanModal()"
                        class="px-6 py-2 bg-gray-100 text-gray-700 rounded-xl font-medium hover:bg-gray-200 transition-colors">
                        Tutup
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Approve Modal -->

    <div id="approveModal" class="fixed inset-0 bg-black/70 z-50 hidden">
        <div class="min-h-screen flex items-center justify-center p-4">
            <div class="bg-white rounded-3xl shadow-2xl w-full max-w-md overflow-hidden">
                <form id="approveForm" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT') {{-- Use PUT if updating --}}
                    <div class="p-6 border-b border-gray-100">
                        <h3 class="text-xl font-bold text-gray-900">Sahkan Kelulusan</h3>
                        <p class="text-sm text-gray-500 mt-1">
                            Sila muat naik bukti pembayaran untuk
                            <span id="approveTargetName" class="font-bold text-gray-700"></span>.
                        </p>
                    </div>

                    <div class="p-6 space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Bukti Kelulusan <span class="text-red-500">*</span>
                            </label>
                            <input type="file" name="bukti_kelulusan" required
                                class="w-full px-3 py-2 border border-gray-300 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Catatan
                            </label>
                            <textarea name="catatan" rows="3"
                                class="w-full px-3 py-2 border border-gray-300 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500"
                                placeholder="Catatan tambahan..."></textarea>
                        </div>
                    </div>

                    <div class="px-6 py-4 bg-gray-50 flex justify-end space-x-3">
                        <button type="button" onclick="closeApproveModal()"
                            class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-xl">
                            Batal
                        </button>
                        <button type="submit"
                            class="px-4 py-2 text-sm font-medium text-white bg-emerald-600 hover:bg-emerald-700 rounded-xl">
                            Sahkan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

<script>
    // Approve Modal Functions
    let currentApproveId = null;

    function openApproveModal(id, name) {
        currentApproveId = id;
        document.getElementById('approveTargetName').textContent = name;

        // Set form action dynamically
        const form = document.getElementById('approveForm');
        form.action = "" + id;

        // Show modal
        document.getElementById('approveModal').classList.remove('hidden');
        document.body.style.overflow = 'hidden'; // Prevent scrolling
    }

    function closeApproveModal() {
        document.getElementById('approveModal').classList.add('hidden');
        document.body.style.overflow = 'auto';
        currentApproveId = null;
    }

    // Close modal when clicking outside
    document.getElementById('approveModal').addEventListener('click', function(e) {
        if (e.target.id === 'approveModal') {
            closeApproveModal();
        }
    });

    // Close with Escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && !document.getElementById('approveModal').classList.contains('hidden')) {
            closeApproveModal();
        }
    });

    // Existing functions for other modals (keep these if you have them)
    function showDocument(url, title) {
        document.getElementById('modalTitle').textContent = title;
        document.getElementById('modalContent').innerHTML = `
        <div class="text-center">
            <img src="${url}" alt="${title}" class="max-w-full h-auto rounded-xl mx-auto shadow-lg">
            <div class="mt-4">
                <a href="${url}" target="_blank" 
                   class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-xl hover:bg-blue-700 transition-colors">
                    <i class="fas fa-external-link-alt mr-2"></i> Buka dalam Tab Baru
                </a>
            </div>
        </div>`;
        document.getElementById('dokumenModal').classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }

    function showAllDocuments(id) {
        // Your existing showAllDocuments function
    }

    function closeModal() {
        document.getElementById('dokumenModal').classList.add('hidden');
        document.body.style.overflow = 'auto';
    }

    function showCatatan(id, catatan) {
        document.getElementById('catatanText').value = catatan || 'Tiada catatan.';
        document.getElementById('catatanModal').classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }

    function closeCatatanModal() {
        document.getElementById('catatanModal').classList.add('hidden');
        document.body.style.overflow = 'auto';
    }
</script>
