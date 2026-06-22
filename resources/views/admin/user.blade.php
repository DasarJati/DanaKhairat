@extends ('layouts.admin')

@section('title', 'Kelulusan Ahli Khairat')

@section('content')

<div class="min-h-screen bg-gray-50 p-6">
    <div class="max-w-7xl mx-auto">

        <!-- Header -->
        <div class="mb-8">
            <h2 class="text-3xl font-bold text-gray-800 mb-2">
                Senarai Permohonan Ahli Khairat
            </h2>
            <p class="text-gray-600">
                Uruskan permohonan pendaftaran ahli yang menunggu kelulusan
            </p>
        </div>

        <!-- Statistik -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <div class="bg-white p-5 rounded-xl shadow border">
                <p class="text-gray-500 text-sm">Jumlah Permohonan</p>
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

        <!-- Filter -->
        <form method="GET" class="mb-4">
            <select name="status" onchange="this.form.submit()"
                class="border rounded-lg px-4 py-2 font-medium">
                <option value="">Semua Status</option>
                <option value="PENDING"  {{ request('status')=='PENDING'?'selected':'' }}>Pending</option>
                <option value="APPROVED" {{ request('status')=='APPROVED'?'selected':'' }}>Approved</option>
                <option value="REJECTED" {{ request('status')=='REJECTED'?'selected':'' }}>Rejected</option>
            </select>

            <a href="{{ route('admin.user') }}"
               class="ml-3 px-5 py-2 bg-gray-200 rounded-lg">
               Reset
            </a>
        </form>

        <!-- Table -->
        <div class="bg-white rounded-xl shadow overflow-hidden">
            <table class="w-full">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="px-6 py-4 text-left">#</th>
                        <th class="px-6 py-4 text-left">Nama</th>
                        <th class="px-6 py-4 text-left">Amount</th>
                        <th class="px-6 py-4 text-left">Tarikh Mohon</th>
                        <th class="px-6 py-4 text-left">Status</th>
                        <th class="px-6 py-4 text-left">Tindakan</th>
                    </tr>
                </thead>

                <tbody class="divide-y">
                    @foreach ($applications as $app)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4">{{ $loop->iteration }}</td>
                        <td class="px-6 py-4 font-semibold">{{ $app->nama }}</td>
                        <td class="px-6 py-4">{{ $app->telefon_bimbit }}</td>
                        <td class="px-6 py-4">{{ $app->created_at->format('d/m/Y') }}</td>
                        <td class="px-6 py-4">
                            @if ($app->approval_status === 'PENDING')
                                <span class="text-yellow-600 font-semibold">Pending</span>
                            @elseif ($app->approval_status === 'APPROVED')
                                <span class="text-green-600 font-semibold">Approved</span>
                            @else
                                <span class="text-red-600 font-semibold">Rejected</span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            <a href="{{ route('user.view', $app->id) }}"
                               class="px-4 py-2 bg-blue-600 text-white rounded-lg">
                                Lihat
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

    </div>
</div>

@endsection
