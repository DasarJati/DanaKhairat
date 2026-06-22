@extends('layouts.admin')

@section('title', 'Senarai AJK Diluluskan')

@section('content')
{{-- FLASH MESSAGE --}}
@if (session('success'))
    <div class="mb-6 max-w-5xl mx-auto">
        <div class="flex items-center gap-3 bg-green-100 border border-green-300 text-green-800 px-4 py-3 rounded-lg shadow">
            <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M5 13l4 4L19 7" />
            </svg>
            <span class="font-medium">
                {{ session('success') }}
            </span>
        </div>
    </div>
@endif

<div class="p-6">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold text-gray-800">Senarai Masjid/Surau E-Khairat</h2>
    </div>

    <!-- Filter & Search Section -->
    <div class="bg-white rounded-lg shadow border p-4 mb-6">
        <div class="flex flex-col md:flex-row gap-4">

            <!-- Search Input -->
            <div class="flex-1">
                <label for="search" class="block text-sm font-medium text-gray-700 mb-1">Cari Masjid/Surau</label>
                <input 
                    type="text" 
                    id="search" 
                    placeholder="Cari mengikut nama masjid atau email..." 
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                >
            </div>
            
            <!-- Filter by Type -->
            <div class="w-full md:w-48">
                <label for="typeFilter" class="block text-sm font-medium text-gray-700 mb-1">Jenis</label>
                <select 
                    id="typeFilter" 
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                >
                    <option value="">Semua</option>
                    <option value="masjid">Masjid</option>
                    <option value="surau">Surau</option>
                </select>
            </div>

            <!-- Filter by Package -->
            <!-- <div class="w-full md:w-48">
                <label for="packageFilter" class="block text-sm font-medium text-gray-700 mb-1">Pakej</label>
                <select 
                    id="packageFilter" 
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                >
                    <option value="">Semua Pakej</option>
                    @foreach($packages as $package)
                        <option value="{{ $package->id }}">{{ $package->pakej }}</option>
                    @endforeach
                </select>
            </div> -->
        </div>
    </div>

    <!-- Table Section -->
    <div class="bg-white rounded-lg shadow border overflow-hidden">
        <div class="overflow-x-auto">
           <table class="w-full min-w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="py-3 px-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border-b">Nama Masjid/Surau</th>
                        <th class="py-3 px-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border-b">Email</th>
                        <th class="py-3 px-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border-b">Jenis</th>
                    </tr>
                </thead>

                <tbody class="bg-white divide-y divide-gray-200" id="tableBody">
                    @foreach($approved as $a)
                    <tr class="hover:bg-gray-50 cursor-pointer transition-colors duration-150"
                         onclick="window.location='{{ route('admin.details', $a->id) }}'"
                        data-package-id="{{ $a->package->id ?? '' }}">
                        
                        <td class="py-3 px-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">{{ $a->nama_masjid }}</div>
                        </td>

                        <td class="py-3 px-4 whitespace-nowrap">
                            <div class="text-sm text-gray-600">{{ $a->email }}</div>
                        </td>

                        <td class="py-3 px-4 whitespace-nowrap">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                {{ $a->type === 'masjid' ? 'bg-blue-100 text-blue-800' : 'bg-green-100 text-green-800' }}">
                                {{ $a->type === 'masjid' ? 'Masjid' : 'Surau' }}
                            </span>
                        </td>

                        <td class="py-3 px-4 whitespace-nowrap text-right">
                            <button onclick="event.stopPropagation(); window.location='{{ route('admin.details', $a->id) }}';" 
                                    class="inline-flex items-center p-2 rounded-full hover:bg-gray-100 transition">
                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                        d="M9 5l7 7-7 7" />
                                </svg>
                            </button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Empty State -->
        <div id="emptyState" class="hidden p-8 text-center">
            <div class="text-gray-400 mb-4">
                <svg class="mx-auto h-12 w-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
            <p class="text-gray-500 text-lg">Tiada rekod ditemui</p>
            <p class="text-gray-400 text-sm mt-1">Cuba ubah carian atau penapis anda</p>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('search');
    const typeFilter = document.getElementById('typeFilter');
    const packageFilter = document.getElementById('packageFilter');
    const tableBody = document.getElementById('tableBody');
    const emptyState = document.getElementById('emptyState');
    const originalRows = Array.from(tableBody.querySelectorAll('tr'));

    function filterTable() {
        const searchTerm = searchInput.value.toLowerCase();
        const selectedType = typeFilter.value;
        const selectedPackage = packageFilter.value;

        let visibleRows = 0;

        originalRows.forEach(row => {
            const namaMasjid = row.cells[0].textContent.toLowerCase();
            const email = row.cells[1].textContent.toLowerCase();
            const typeElement = row.cells[2].querySelector('span');
            const type = typeElement ? (typeElement.classList.contains('bg-blue-100') ? 'masjid' : 'surau') : '';
            const packageId = row.dataset.packageId || '';

            const matchesSearch = namaMasjid.includes(searchTerm) || email.includes(searchTerm);
            const matchesType = !selectedType || type === selectedType;
            const matchesPackage = !selectedPackage || packageId === selectedPackage;

            if (matchesSearch && matchesType && matchesPackage) {
                row.style.display = '';
                visibleRows++;
            } else {
                row.style.display = 'none';
            }
        });

        // Show/hide empty state
        if (visibleRows === 0) {
            tableBody.style.display = 'none';
            emptyState.classList.remove('hidden');
        } else {
            tableBody.style.display = '';
            emptyState.classList.add('hidden');
        }
    }

    searchInput.addEventListener('input', filterTable);
    typeFilter.addEventListener('change', filterTable);
    packageFilter.addEventListener('change', filterTable);
});
</script>

<style>
    table {
        border-collapse: separate;
        border-spacing: 0;
    }
    
    th, td {
        border-bottom: 1px solid #e5e7eb;
    }
    
    tr:last-child td {
        border-bottom: none;
    }
</style>
@endsection
