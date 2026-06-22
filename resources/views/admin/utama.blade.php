@extends ('layouts.admin')

@section('title', 'Dashboard - e-Khairat')

@section('content')

<div class="min-h-screen bg-gray-50 p-6">
    <div class="max-w-7xl mx-auto">
        <!-- Header -->
        <div class="mb-8">
            <h2 class="text-3xl font-bold text-gray-800 mb-2">Senarai Permohonan E-Khairat (Pending)</h2>
            <p class="text-gray-600">Uruskan permohonan pendaftaran AJK yang sedang menunggu kelulusan</p>
        </div>

        <!-- Table Container -->
        <div class="  overflow-hidden  ">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">

                <div class="bg-white p-5 rounded-xl shadow border">
                    <p class="text-gray-500 text-sm">Jumlah Masjid/Surau berdaftar</p>
                    <p class="text-3xl font-bold text-gray-800">{{ $total }}</p>
                </div>

                <div class="bg-yellow-50 p-5 rounded-xl shadow border border-yellow-200">
                    <p class="text-yellow-600 text-sm">Pending</p>
                    <p class="text-3xl font-bold text-yellow-700">{{ $pending }}</p>
                </div>

                <div class="bg-green-50 p-5 rounded-xl shadow border border-green-200">
                    <p class="text-green-600 text-sm">Approved</p>
                    <p class="text-3xl font-bold text-green-700">{{ $approved }}</p>
                </div>

                <div class="bg-red-50 p-5 rounded-xl shadow border border-red-200">
                    <p class="text-red-600 text-sm">Rejected</p>
                    <p class="text-3xl font-bold text-red-700">{{ $rejected }}</p>
                </div>

            </div>
            <table class="w-full">

                <div class="mb-4  ">
                    <form method="GET" id="filterForm">
                        <select name="status"
                            id="statusFilter"
                            class="border rounded-lg px-4 py-2 font-medium
                            @if(request('status') === '0') bg-yellow-50 text-yellow-700 border-yellow-400
                            @elseif(request('status') === '1') bg-green-50 text-green-700 border-green-400
                            @elseif(request('status') === '2') bg-red-50 text-red-700 border-red-400
                            @else bg-white text-gray-700 border-gray-300
                            @endif">

                            <option value="">Semua Status</option>
                            <option value="0" {{ request('status') === '0' ? 'selected' : '' }}>Pending</option>
                            <option value="1" {{ request('status') === '1' ? 'selected' : '' }}>Approved</option>
                            <option value="2" {{ request('status') === '2' ? 'selected' : '' }}>Rejected</option>
                        </select>

                    </form>
                </div>
@endsection