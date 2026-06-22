<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <title>Resit Pembayaran Khairat</title>
</head>
<body class="bg-gray-100 py-10 px-4">

    <div class="max-w-2xl mx-auto bg-white shadow-lg rounded-lg overflow-hidden border border-gray-200">
        
        <div class="p-8 border-b border-gray-100 flex flex-col md:flex-row justify-between items-center bg-slate-50">
            <div class="flex items-center space-x-4">
                <div class="w-16 h-16 bg-blue-600 rounded-full flex items-center justify-center text-white font-bold text-2xl">
                    <img src="{{ asset('images/logos.png') }}" alt="Logo" class="w-full h-full object-contain rounded-full">
                </div>
                <div>
                    <h1 class="text-xl font-bold text-gray-800 uppercase tracking-tight">{{ $masjid->nama }}</h1>
                    <p class="text-sm text-gray-500 italic text-nowrap">Resit Rasmi Pembayaran Khairat</p>
                </div>
            </div>
        </div>

        <div class="px-8 pt-8">
            <p class="text-gray-600 text-sm">Assalamualaikum <span class="font-semibold text-gray-800">{{ $user->nama }}</span>,</p>
            <p class="text-gray-600 text-sm mt-1">Pendaftaran anda telah berjaya diluluskan.</p>
        </div>

        <hr>

            <h3>Maklumat Pembayaran</h3>

            <table width="100%" cellpadding="5" cellspacing="0" border="1">
                <tr>
                    <td>Nama Masjid</td>
                    <td>{{ $masjid->nama }}</td>
                </tr>
                <tr>
                    <td>Tarikh Bayaran</td>
                    <td>{{ \Carbon\Carbon::parse($payment->paid_at)->format('d/m/Y') }}</td>
                </tr>
                <tr>
                    <td>Yuran Pendaftaran</td>
                    <td>RM {{ number_format($harga->yuran_pendaftaran,2) }}</td>
                </tr>
                <tr>
                    <td>Bayaran Tahunan</td>
                    <td>RM {{ number_format($harga->bayaran_tahunan,2) }}</td>
                </tr>
                <tr>
                    <td>Wakalah</td>
                    <td>RM {{ number_format($wakalah,2) }}</td>
                </tr>
                <tr>
                    <td><strong>Jumlah Bayaran</strong></td>
                    <td><strong>RM {{ number_format($totalAmount,2) }}</strong></td>
                </tr>
            </table>

            <br>
            <p>Untuk log masuk, sila klik pautan berikut: 
                <a href="{{ url('/login') }}" style="color: #2563EB; text-decoration: underline;">Log Masuk</a>
            </p>
            <p>Terima kasih kerana mendaftar.</p>
</body>
</html>