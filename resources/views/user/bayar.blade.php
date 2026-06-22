<input type="hidden" name="ahli_type" id="ahli_type" value="Existing">

<div class="bg-white border border-gray-200 rounded-2xl p-6 space-y-6">

    <h3 class="text-base font-semibold text-gray-800 flex items-center gap-2">
        <i class="fas fa-file-invoice text-gray-500"></i>
        Ringkasan Pembayaran
    </h3>

    <div class="space-y-3 text-sm">

        <!-- Yuran Tahunan -->
        <div class="max-w-4xl mx-auto bg-white rounded-2xl shadow-sm border p-6 space-y-6">

            <!-- Radio Button Section -->
            <div class="bg-gray-50 rounded-xl p-4 border border-gray-200">
                <label class="block text-sm font-semibold text-gray-700 mb-3">
                    Pernah Mendaftar Sebagai Ahli Khairat?
                </label>

                <!-- Small Note for ahli fizikal -->
                <div
                    class="bg-red-50 border-l-4 border-red-500 p-3 mb-4 text-sm text-red-500 rounded">
                    <i class="fas fa-info-circle mr-2"></i>
                    <strong>Nota:</strong> Bagi ahli yang pernah mendaftar di luar sistem (daftar
                    secara fizikal),
                    sila pilih "Ya" dan muat naik resit pembayaran sedia ada. Jika belum pernah
                    mendaftar,
                    pilih "Tidak" dan lengkapkan pendaftaran seperti biasa.
                </div>

                <div class="flex gap-6">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="radio" name="payment_status" value="yes"
                            class="w-4 h-4 text-djariah-600 focus:ring-djariah-500">
                        <span class="text-gray-700 font-medium">Ya (Pernah Daftar Secara
                            Fizikal)</span>
                    </label>
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="radio" name="payment_status" value="no"
                            class="w-4 h-4 text-djariah-600 focus:ring-djariah-500">
                        <span class="text-gray-700 font-medium">Tidak (Belum Pernah Daftar)</span>
                    </label>
                    {{-- <label class="flex items-center gap-2 cursor-pointer">
                                            <input type="radio" name="payment_status" value="asnaf"
                                                class="w-4 h-4 text-djariah-600 focus:ring-djariah-500">
                                            <span class="text-gray-700 font-medium">Asnaf</span>
                                        </label> --}}
                </div>
            </div>

            <div id="paymentDefault" class="space-y-6">
            <!-- Instruction -->
            <div class="bg-blue-50 border border-blue-200 rounded-xl p-4 text-sm text-blue-800">
                <strong>Perhatian:</strong>
                <ul class="list-disc list-inside mt-2 space-y-1">
                    <li>Sila buat pemindahan wang ke akaun di bawah terlebih dahulu.</li>
                    <li>Selepas pembayaran berjaya, muat naik resit pembayaran sebelum menekan butang <b>Hantar</b>.</li>
                    <li>Permohonan hanya akan diproses selepas resit diterima.</li>
                    <li>DanaKhairat adalah platform digital yang mengurus transaksi dan rekod secara automatik.</li>
                    <li>Bagi memastikan sistem pembayaran lebih selamat, tersusun dan boleh dijejak, semua bayaran diproses melalui akaun rasmi pengendali sistem.</li>
                    <li>Yuran khairat tetap direkodkan dan disalurkan kepada tabung kariah yang dipilih oleh ahli.</li>
                    <li>AJK kariah mempunyai akses penuh kepada laporan dan rekod transaksi.</li>
                </ul>
            </div>

            <!-- Payment Info -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                <!-- QR & Bank Info -->
                <div class="flex gap-4 items-start">
                    <!-- QR Image -->
                    <img id="qrImage"
                        src="{{ asset('images/default-qr.png') }}"
                        alt="QR Bayaran"
                        class="w-40 h-40 object-contain rounded-xl border cursor-pointer hover:scale-105 transition"
                        onclick="openZoom(this)">

                    <div class="space-y-3">
                        <div>
                            <p class="text-xs text-gray-500">Nombor Akaun</p>
                            <p id="bank_no_akaun" class="font-semibold text-gray-800 tracking-wide">-</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500">Nama Akaun</p>
                            <p id="bank_nama_akaun" class="font-semibold text-gray-800">-</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500">Bank</p>
                            <p id="bank_nama_bank" class="font-semibold text-gray-800">-</p>
                        </div>
                    </div>
                </div>

                <div id="zoomModal"
                    class="fixed inset-0 bg-black bg-opacity-70 hidden z-50 flex items-center justify-center p-4">

                    <!-- Close Button -->
                    <button class="absolute top-4 right-5 text-white text-3xl font-bold hover:text-gray-300"
                        onclick="closeZoom()">&times;</button>

                    <!-- Zoomed Image -->
                    <img id="zoomImage"
                        class="max-w-full max-h-full rounded-xl shadow-lg object-contain">
                </div>


                <!-- Amount Breakdown -->
                <div class="bg-gray-50 rounded-xl p-4 space-y-3 text-sm">

                    <div class="flex justify-between">
                        <span class="text-gray-500">Yuran Pendaftaran</span>
                        <span id="yuran_pendaftaran" class="font-medium">RM 0.00</span>
                    </div>

                    <div class="flex justify-between">
                        <span class="text-gray-500">Bayaran Tahunan</span>
                        <span id="bayaran_tahunan" class="font-medium">RM 0.00</span>
                    </div>

                    <div class="border-t pt-3 flex justify-between text-base font-semibold">
                        <span>Jumlah Pembayaran</span>
                        <span id="jumlah_bayaran" class="text-djariah-600">RM 0.00</span>
                    </div>

                </div>
            </div>

            <!-- Upload Receipt -->
            <div class="space-y-3">
                <label class="block text-sm font-medium text-gray-700">
                    Muat Naik Resit Pembayaran
                </label>

                <input type="file"
                    name="resit_file"
                    accept="image/*,application/pdf"
                    onchange="previewReceipt(event)"
                    class="block w-full text-sm file:mr-4 file:py-2 file:px-4
                        file:rounded-lg file:border-0
                        file:bg-djariah-600 file:text-white
                        hover:file:bg-djariah-700">

                <!-- Preview -->
                <div id="receiptPreview" class="hidden mt-4">
                    <p class="text-xs text-gray-500 mb-2">Preview Resit:</p>
                    <img id="receiptImage"
                        class="w-48 rounded-xl border shadow-sm">
                </div>
                <p class="text-xs text-gray-400">* Sila muat naik resit pembayaran untuk
                    pengesahan.</p>
            </div>
        </div>

        <div id="yesContent" class="hidden space-y-6">
        <!-- Instruction for existing members with receipt -->
        <div
            class="bg-green-50 border border-green-200 rounded-xl p-4 text-sm text-green-800">
            <i class="fas fa-receipt mr-2"></i>
            <strong>Maklumat Penting:</strong>
            <ul class="list-disc list-inside mt-2 space-y-1">
                <li>Jika anda mempunyai resit atau bukti pembayaran sedia ada, sila muat
                    naik segera.</li>
                <li>Jika ada resit atau bukti, sila muat naik untuk pengesahan pendaftaran.
                </li>
                <li>Permohonan anda akan diproses selepas resit/bukti diterima dan disahkan.
                </li>
            </ul>
        </div>



        <!-- Upload Receipt (required if have receipt) -->
        <div class="space-y-3">
            <label class="block text-sm font-medium text-gray-700">
                Muat Naik Resit / Bukti Pembayaran <span class="text-red-500">*</span>
            </label>
            <div
                class="bg-blue-50 border border-blue-200 rounded-lg p-3 mb-2 text-xs text-blue-700">
                <i class="fas fa-file-upload mr-1"></i>
                Sila muat naik resit atau bukti pembayaran jika ada. Ini penting untuk
                pengesahan pendaftaran anda.
            </div>
            <input type="file" name="resit_file_yes" accept="image/*,application/pdf"
                onchange="previewReceiptYes(event)"
                class="block w-full text-sm file:mr-4 file:py-2 file:px-4
              file:rounded-lg file:border-0
              file:bg-djariah-600 file:text-white
              hover:file:bg-djariah-700">
            <div id="receiptPreviewYes" class="hidden mt-4">
                <p class="text-xs text-gray-500 mb-2">Preview Resit / Bukti:</p>
                <img id="receiptImageYes" class="w-48 rounded-xl border shadow-sm">
            </div>
            <p class="text-xs text-gray-400 font-semibold text-djariah-600">
                ⚠️ Penting: Jika ada resit atau bukti, sila muat naik segera. Permohonan
                tanpa bukti akan ditangguhkan.
            </p>
        </div>
    </div>
    </div>
    



    <hr class="border-gray-100">



    <p class="text-xs text-gray-400">
        Jumlah bayaran akan dikira secara automatik selepas masjid dipilih.
    </p>

</div>

<script>
    function previewReceipt(event) {
        const file = event.target.files[0];
        if (!file) return;

        const preview = document.getElementById('receiptPreview');
        const img = document.getElementById('receiptImage');

        if (file.type.startsWith('image/')) {
            img.src = URL.createObjectURL(file);
            preview.classList.remove('hidden');
        } else {
            preview.classList.add('hidden');
        }
    }
</script>

<script>
    function openZoom(img) {
        const modal = document.getElementById('zoomModal');
        const zoomImg = document.getElementById('zoomImage');
        zoomImg.src = img.src;
        modal.classList.remove('hidden');
    }

    function closeZoom() {
        document.getElementById('zoomModal').classList.add('hidden');
    }

    // Close modal on click outside image
    document.getElementById('zoomModal').addEventListener('click', function(e) {
        if (e.target.id === 'zoomModal') {
            closeZoom();
        }
    });

    function loadBankInfo(masjidId) {

        fetch('/get-bank/' + masjidId)
            .then(res => res.json())
            .then(data => {

                if (!data) return;

                document.getElementById('bank_no_akaun').textContent = data.no_akaun ?? '-';
                document.getElementById('bank_nama_akaun').textContent = data.nama_akaun ?? '-';
                document.getElementById('bank_nama_bank').textContent = data.nama_bank ?? '-';

                if (data.qr_path) {
                    document.getElementById('qrImage').src = '/' + data.qr_path;
                }

            })
            .catch(err => console.log(err));
    }
</script>
<script>
    // Sync ahli_type from the partial
    document.addEventListener('DOMContentLoaded', function() {
        const radioYes = document.querySelector('input[name="payment_status"][value="yes"]');
        const radioNo = document.querySelector('input[name="payment_status"][value="no"]');
        const ahliTypeInput = document.getElementById('ahli_type');
        
        function updateAhliType() {
            if (ahliTypeInput) {
                if (radioYes && radioYes.checked) {
                    ahliTypeInput.value = 'Existing';
                    console.log('Partial: ahli_type = Existing');
                } else if (radioNo && radioNo.checked) {
                    ahliTypeInput.value = 'New';
                    console.log('Partial: ahli_type = New');
                }
            }
        }
        
        if (radioYes) radioYes.addEventListener('change', updateAhliType);
        if (radioNo) radioNo.addEventListener('change', updateAhliType);
        
        // Initial update
        updateAhliType();
    });
</script>