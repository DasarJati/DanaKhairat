<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload Resit Bayaran</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gradient-to-br from-gray-50 via-white to-gray-100 min-h-screen flex items-center justify-center p-8">

<div class="w-full max-w-6xl bg-white rounded-3xl shadow-[0_20px_60px_rgba(0,0,0,0.12)] p-12">

    <!-- Header -->
    <div class="flex items-center gap-5 mb-12">
        <div class="w-14 h-14 rounded-2xl bg-emerald-100 flex items-center justify-center text-2xl">
            📤
        </div>
        <div>
            <h1 class="text-3xl font-bold text-gray-900">
                Upload Resit Bayaran
            </h1>
            <p class="text-gray-600 mt-1">
                Lengkapkan bayaran untuk proses pengesahan
            </p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">

        <!-- LEFT -->
        <div>

            <!-- Bank Info -->
            <div class="mb-12 rounded-2xl border border-gray-200 p-8 bg-gray-50/70">
                <h3 class="text-xl font-semibold text-gray-900 mb-6 flex items-center gap-3">
                    💳 Maklumat Bayaran
                </h3>

                <div class="space-y-4 text-gray-700">
                    <div class="flex justify-between border-b pb-3">
                        <span>Nama Syarikat</span>
                        <span class="font-medium text-gray-900">Dasar Jati SDN. BHD.</span>
                    </div>
                    <div class="flex justify-between border-b pb-3">
                        <span>Nama Bank</span>
                        <span class="font-medium text-gray-900">Maybank</span>
                    </div>
                    <div class="flex justify-between border-b pb-3">
                        <span>No. Akaun</span>
                        <span class="font-semibold tracking-widest text-gray-900">
                            5621 8899 0034
                        </span>
                    </div>
                    <div class="flex justify-between">
                        <span>Rujukan Bayaran</span>
                        <span class="font-medium text-blue-600">
                            Gunakan nombor rujukan anda
                        </span>
                    </div>
                </div>

                <!-- QR -->
                <div class="mt-8 flex items-center gap-6">
                    <div class="w-28 h-28 rounded-2xl border border-dashed flex items-center justify-center bg-white text-gray-400">
                        QR
                    </div>
                    <p class="text-sm text-gray-600">
                        Imbas QR untuk bayaran pantas melalui aplikasi perbankan.
                    </p>
                </div>
            </div>

            <!-- Form -->
            <form action="{{ route('payment.submit-resit') }}" 
                method="POST" 
                enctype="multipart/form-data">
                @csrf

                <div class="mb-12">
                    <label class="block font-semibold text-gray-900 mb-2">
                        Upload Resit Bayaran
                    </label>
                    <div class="rounded-xl border border-dashed p-6 text-center bg-gray-50">
                        <input type="file" name="resit_file"
                               class="w-full text-sm"
                               accept=".jpg,.jpeg,.png,.pdf">
                        <p class="text-xs text-gray-500 mt-3">
                            JPG / PNG / PDF • Maks 2MB
                        </p>
                    </div>
                </div>
        </div>

        <!-- RIGHT -->
        <div>

            <!-- Summary -->
            <div class="mb-10 rounded-2xl border border-blue-200 bg-blue-50/70 p-8">
                <h3 class="text-xl font-semibold text-blue-900 mb-6">
                    Ringkasan Bayaran
                </h3>

                <p class="text-gray-600 mb-2">Jumlah Bayaran</p>
                <p class="text-3xl font-bold text-gray-900 mb-6">
                    RM {{ number_format($payment->amount, 2) }}
                </p>

                <button type="submit"
                    class="w-full rounded-xl bg-emerald-600 hover:bg-emerald-700
                           text-white font-semibold py-4 transition">
                    Hantar Resit
                </button>
            </div>

            <!-- Guide -->
            <div class="rounded-2xl border border-gray-200 p-6 bg-gray-50">
                <h4 class="font-semibold text-gray-900 mb-4">
                    Panduan Ringkas
                </h4>
                <ul class="space-y-3 text-sm text-gray-700">
                    <li>• Rujukan bayaran mestilah tepat</li>
                    <li>• Resit perlu jelas dan sah</li>
                    <li>• Pengesahan 2–3 hari bekerja</li>
                </ul>
            </div>

            </form>
        </div>
    </div>

</div>
</body>
</html>
