@php
    $package = session('selected_package');
@endphp

@if(!$package)
    <script>window.location.href = "{{ route('pic.package') }}"</script>
@endif

<div class="max-w-xl mx-auto bg-white p-6 rounded-xl shadow">
    <h1 class="text-2xl font-bold mb-4">Pembayaran – {{ $package['name'] }}</h1>

    <ul class="space-y-2 text-gray-700">
        <li><strong>Harga:</strong> RM {{ $package['price'] }}</li>
        <li><strong>Tempoh:</strong> {{ $package['duration_months'] }} Bulan</li>
        <li><strong>Had Ahli:</strong> {{ $package['max_members'] }}</li>
    </ul>

    <hr class="my-4">

    <p class="font-semibold">Maklumat AJK</p>
    <p>{{ $package['user_name'] }} ({{ $package['user_email'] }})</p>

    <button class="mt-6 w-full bg-blue-600 hover:bg-blue-700 text-white py-3 rounded-lg">
        Teruskan Pembayaran RM {{ $package['price'] }}
    </button>
</div>
