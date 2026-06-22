@extends('layouts.admin')

@section('title', 'Upload Resit')

@section('content')
<div class="min-h-screen bg-slate-50 py-10 px-4">
    <div class="max-w-2xl mx-auto">

        <div class="mb-8">
            <h1 class="text-2xl font-bold text-slate-900">Muat Naik Resit Bayaran</h1>
            <p class="text-slate-500 text-sm">Sila buat pindahan ke akaun di bawah dan muat naik bukti pembayaran.</p>
        </div>

        <div class="grid gap-6">

            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
                <div class="bg-blue-600 px-6 py-4">
                    <h3 class="text-white font-semibold flex items-center">
                        <i class="fas fa-university mr-2"></i> Maklumat Bank Masjid/Surau
                    </h3>
                </div>
                <div class="p-6">
                    <div class="space-y-4">
                        <div class="flex justify-between border-b border-slate-50 pb-3">
                            <span class="text-sm text-slate-500">Nama Masjid/Surau</span>
                            <span class="text-sm font-bold text-slate-800 text-right">{{ $payment->masjid->nama }}</span>
                        </div>
                        <div class="flex justify-between border-b border-slate-50 pb-3">
                            <span class="text-sm text-slate-500">Nama Bank</span>
                            <span class="text-sm font-bold text-slate-800">{{ $bank->nama_bank ?? '-' }}</span>
                        </div>
                        <div class="flex justify-between border-b border-slate-50 pb-3">
                            <span class="text-sm text-slate-500">Nombor Akaun</span>
                            <div class="text-right">
                                <span class="text-lg font-mono font-bold text-blue-700 tracking-wider">{{ $bank->no_akaun ?? '-' }}</span>
                                <p class="text-[10px] text-slate-400 uppercase tracking-tight">Klik untuk salin no. akaun</p>
                            </div>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-sm text-slate-500">Rujukan (Optional)</span>
                            <span class="text-sm font-semibold text-slate-700 underline decoration-dotted">KHAIRAT-2024</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
                <form action="{{ route('resit.upload', $payment->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="space-y-6">

                        <!-- Jumlah Bayaran -->
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase mb-2">Jumlah Bayaran (RM)</label>
                            <input type="text" value="{{ number_format($payment->amount - 5, 2) }}"
                                class="w-full bg-slate-50 border border-slate-200 rounded-lg px-4 py-2.5 text-slate-800 font-bold focus:ring-2 focus:ring-blue-500 outline-none"
                                readonly>
                        </div>

                        <!-- Upload Resit -->
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase mb-2">
                                Salinan Resit (Gambar/PDF)
                            </label>
                            <div class="relative border-2 border-dashed border-slate-200 rounded-xl p-8 text-center hover:bg-slate-50 transition group">
                                <input type="file" name="receipt" id="receipt" accept=".jpg,.jpeg,.png,.pdf" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer" required>


                                <div class="space-y-2">
                                    <div class="h-12 w-12 bg-blue-50 text-blue-600 rounded-full flex items-center justify-center mx-auto">
                                        <i class="fas fa-cloud-upload-alt text-xl"></i>
                                    </div>
                                    <p class="text-sm font-medium text-slate-600">
                                        Klik atau drag & drop resit
                                    </p>
                                    <p class="text-xs text-slate-400">
                                        Saiz maksimum: 2MB
                                    </p>
                                    <!-- PREVIEW FILE NAME -->
                                    <p id="file-name"
                                        class="text-green-600 font-semibold text-sm mt-2 hidden">
                                    </p>


                                </div>

                            </div>

                        </div>
                        <!-- Submit Button -->
                        <div class="pt-2">
                            <button type="submit"
                                class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 rounded-xl shadow-lg shadow-blue-200 transition-all flex items-center justify-center">
                                <i class="fas fa-check-circle mr-2"></i>
                                Hantar Pengesahan Bayaran
                            </button>
                            <a href="{{ route('admin.wallet') }}" class="block text-center mt-4 text-xs font-semibold text-slate-400 hover:text-slate-600">
                                Batal & Kembali
                            </a>
                        </div>
                    </div>
                </form>

            </div>

            <div class="bg-amber-50 rounded-xl p-4 border border-amber-100 flex items-start space-x-3">
                <i class="fas fa-info-circle text-amber-500 mt-0.5"></i>
                <p class="text-xs text-amber-700 leading-relaxed">
                    <strong>Nota:</strong> Pastikan resit yang dimuat naik adalah jelas dan menunjukkan tarikh serta masa transaksi. Pengesahan biasanya mengambil masa 1-2 hari waktu bekerja.
                </p>
            </div>
        </div>
    </div>
</div>

<script>

    document.getElementById('receipt').addEventListener('change', function(){

    let fileName = this.files[0].name;

    let preview = document.getElementById('file-name');

    preview.innerHTML = "File dipilih: " + fileName;

    preview.classList.remove('hidden');

    });

</script>
@endsection