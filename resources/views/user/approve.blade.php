@extends('layouts.admin')

@section('title', 'Kelulusan Pendaftaran Ahli')

@section('content')
<div class="max-w-6xl mx-auto py-8">

    <div class="bg-white shadow rounded-xl p-6">
        <h2 class="text-xl font-bold mb-6">Maklumat Pendaftaran Ahli</h2>

        <!-- Maklumat Asas -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
            <div><strong>Nama:</strong> {{ $user->nama }}</div>
            <div><strong>No IC:</strong> {{ $user->ic_number }}</div>
            <div><strong>Tarikh Lahir:</strong> {{ $user->tarikh_lahir }}</div>
            <div><strong>Umur:</strong> {{ $user->umur }}</div>
            <div><strong>Jantina:</strong> {{ $user->jantina }}</div>
            <div><strong>Bangsa:</strong> {{ $user->bangsa }}</div>
            <div><strong>Status:</strong> {{ $user->statususer }}</div>
            <div><strong>Telefon:</strong> {{ $user->telefon_bimbit }}</div>
            <div><strong>Email:</strong> {{ $user->email }}</div>
            <div class="md:col-span-2"><strong>Alamat:</strong> {{ $user->alamat }}</div>
        </div>

        <hr class="my-6">

        <!-- Maklumat Waris -->
        <h3 class="font-semibold mb-3">Maklumat Waris</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
            <div><strong>Nama Waris:</strong> {{ $user->waris_nama }}</div>
            <div><strong>No IC Waris:</strong> {{ $user->waris_ic }}</div>
            <div><strong>Telefon Pejabat:</strong> {{ $user->waris_telefon_pejabat }}</div>
            <div><strong>Telefon Bimbit:</strong> {{ $user->waris_telefon_bimbit }}</div>
            <div class="md:col-span-2"><strong>Alamat Waris:</strong> {{ $user->waris_alamat }}</div>
        </div>

        <hr class="my-6">

        <!-- Maklumat Pembayaran -->
        <h3 class="font-semibold mb-3">Maklumat Pembayaran</h3>
        @if($payment)
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                <div><strong>Jumlah:</strong> RM {{ number_format($payment->amount,2) }}</div>
                <div><strong>Kaedah:</strong> {{ $payment->payment_method }}</div>
                <div><strong>Status:</strong> {{ $payment->status }}</div>
                <div><strong>Tarikh Bayar:</strong> {{ $payment->paid_at }}</div>
                <div class="md:col-span-2">
                    <strong>Resit:</strong>
                    @if($payment->resit_path)
                        <a href="{{ asset('images/uploads/'.$payment->resit_path) }}" target="_blank" class="text-blue-600 underline">Lihat Resit</a>
                    @else
                        Tiada
                    @endif
                </div>
            </div>
        @else
            <p class="text-red-500 text-sm">Tiada rekod pembayaran.</p>
        @endif

        <hr class="my-6">

        <!-- Action -->
        <div class="flex gap-4">
            @if($user->approval_status === 'PENDING')
                <div class="flex gap-4">
                    <form action="{{ route('user.approve', $user->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <button class="px-5 py-2 bg-green-600 text-white rounded-lg">
                            Approve
                        </button>
                    </form>

                    <form action="{{ route('user.reject', $user->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <button class="px-5 py-2 bg-red-600 text-white rounded-lg">
                            Reject
                        </button>
                    </form>
                </div>
            @else
                <span class="inline-block px-4 py-2 rounded bg-gray-200 text-gray-700">
                    Status: {{ $user->approval_status }}
                </span>
            @endif

        </div>

    </div>
</div>
@endsection