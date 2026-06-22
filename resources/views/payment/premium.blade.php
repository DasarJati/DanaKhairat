@php
    $package = session('selected_package');
@endphp

@if(!$package)
    <script>window.location.href = "{{ route('pic.package') }}"</script>
@endif

<div class="max-w-xl mx-auto bg-white p-6 rounded-xl shadow">
    <h1 class="text-2xl font-bold text-yellow-600 mb-4">
        Pembayaran – {{ $package['name'] }}
    </h1>

    <ul class="space-y-2 text-gray-700">
        <li><strong>Harga:</strong> RM {{ $package['price'] }}</li>
        <li><strong>Tempoh:</strong> {{ $package['duration_months'] }} Bulan</li>
        <li><strong>Had Ahli:</strong> {{ $package['max_members'] }}</li>
    </ul>

    <button class="mt-6 w-full bg-yellow-500 hover:bg-yellow-600 text-white py-3 rounded-lg">
        Bayar RM {{ $package['price'] }}
    </button>
</div>
