<div class="text-center mb-4">
    <h2 class="text-2xl font-bold text-gray-800 mb-3">Maklumat Pembayaran</h2>
    <div class="w-16 h-1 bg-gray-300 mx-auto rounded-full mb-3"></div>
    <p class="text-gray-500 text-sm">Selesaikan proses pendaftaran dengan pembayaran</p>
</div>

<div class="grid lg:grid-cols-2 gap-6">
    <div class="space-y-5">
        <div class="bg-white rounded-xl p-6 border-2 border-gray-800 shadow-sm relative overflow-hidden">
            <div class="absolute top-0 right-0 bg-gray-800 text-white text-[10px] px-3 py-1 uppercase tracking-widest font-bold">
                Pilihan Terbaik
            </div>

            <div class="flex justify-between items-start mb-4">
                <div>
                    <h3 class="text-xl font-bold text-gray-800">Pakej DanaKhairat</h3>
                </div>
                <div class="text-right">
                    <span class="text-2xl font-bold text-gray-800">RM 1,500</span>
                    <p class="text-[10px] text-gray-400">Tiada caj bulanan</p>
                </div>
            </div>

            <hr class="mb-4 border-gray-100">

            <ul class="space-y-3 mb-6">
                <li class="flex items-center text-sm text-gray-600">
                    <i class="fas fa-check-circle text-green-500 mr-2"></i> Pengurusan Khairat Lengkap
                </li>
                <li class="flex items-center text-sm text-gray-600">
                    <i class="fas fa-check-circle text-green-500 mr-2"></i> Akses 3 Admin Users
                </li>
                <li class="flex items-center text-sm text-gray-600">
                    <i class="fas fa-check-circle text-green-500 mr-2"></i> Rekod Pembayaran Sistematik
                </li>
                <li class="flex items-center text-sm text-gray-600">
                    <i class="fas fa-check-circle text-green-500 mr-2"></i> Activity Log & Audit Trail
                </li>
                <li class="flex items-center text-sm text-gray-600">
                    <i class="fas fa-check-circle text-green-500 mr-2"></i> Online E-Report Real-time
                </li>
            </ul>

            <div class="bg-gray-50 rounded-lg p-3 border border-dashed border-gray-300">
                <p class="text-[11px] text-gray-500 leading-relaxed">
                    *Akses penuh kepada semua modul sistem tanpa had tempoh penggunaan.
                </p>
            </div>
        </div>

        <div class="bg-white rounded-xl p-5 border border-gray-200">
            <h3 class="font-semibold text-gray-800 text-sm mb-4 flex items-center">
                <i class="fas fa-university mr-2 text-gray-400"></i> Maklumat Akaun Pembayaran
            </h3>

            <div class="grid grid-cols-2 gap-4">
                <div class="space-y-3">
                    <div>
                        <p class="text-[10px] uppercase text-gray-400 font-bold tracking-wider">Nama Bank</p>
                        <p class="text-sm font-medium text-gray-700">CIMB BANK</p>
                    </div>
                    <div>
                        <p class="text-[10px] uppercase text-gray-400 font-bold tracking-wider">No. Akaun</p>
                        <p class="text-sm font-bold text-gray-800 tracking-wider">8003189136</p>
                    </div>
                    <div>
                        <p class="text-[10px] uppercase text-gray-400 font-bold tracking-wider">Penama</p>
                        <p class="text-sm font-medium text-gray-700">DASAR JATI SDN BHD</p>
                    </div>
                </div>
                <div class="flex flex-col items-center justify-center border-l border-gray-100">
                    <div class="w-24 h-24 bg-gray-100 rounded flex items-center justify-center border border-gray-200 mb-2">
                        <img src="{{ asset('images/CIMB DJSB.png') }}"
                            alt="QR DuitNow"
                            class="w-full h-full object-contain cursor-pointer"
                            onclick="openQR()">
                    </div>
                    <p class="text-[10px] text-gray-400"></p>
                </div>
            </div>
        </div>
        <div id="qrModal" class="fixed inset-0 bg-black/70 hidden items-center justify-center z-50">
            <div class="relative">
                <button onclick="closeQR()"
                    class="absolute -top-10 right-0 text-white text-3xl">&times;</button>
                <img src="{{ asset('images/CIMB DJSB.png') }}"
                    class="max-w-[90vw] max-h-[90vh] rounded-lg shadow-lg">
            </div>
        </div>
    </div>

    <div class="space-y-5">
        <div class="bg-white rounded-xl p-5 border border-gray-200">
            <div class="flex items-start">
                <div class="flex-1">
                    <!-- Label -->
                    <label class="font-medium text-gray-800 text-sm block mb-2">
                        Terma & Syarat serta Akad Wakalah
                    </label>

                    <!-- Ringkasan & See More -->
                    <div class="bg-gray-50 rounded-lg p-3 border border-gray-200 mb-2 text-xs">

                        <div id="extra-terms" class="overflow-hidden max-h-24 transition-all duration-300 text-gray-700">
                            <p class="mb-2">Sila baca dan fahami perkara berikut sebelum meneruskan pendaftaran sistem DanaKhairat:</p>
                            <p class="mb-1">1️⃣ <strong>Pengurusan Kutipan & Pemindahan Dana</strong><br>
                                Semua kutipan yuran AJK dan Ahli Khairat yang dibuat melalui sistem akan diproses ke akaun rasmi pengendali DanaKhairat bagi tujuan penyelarasan transaksi dan rekod.
                                Jumlah kutipan bersih akan dipindahkan ke akaun rasmi kariah dalam tempoh maksimum lima (5) hari bekerja.</p>

                            <p class="mb-1">2️⃣ <strong>Caj Penyelenggaraan Sistem</strong><br>
                                Setiap bayaran ahli akan dikenakan caj penyelenggaraan sistem sebanyak RM5 bagi setiap transaksi tahunan bagi menampung kos operasi, keselamatan dan sokongan teknikal.</p>

                            <p class="mb-1">3️⃣ <strong>Keperluan Minimum Keahlian</strong><br>
                                AJK bertanggungjawab memastikan sekurang-kurangnya 50 orang ahli aktif berdaftar dan membuat bayaran tahunan bagi memastikan kelangsungan penggunaan sistem di peringkat kariah.</p>

                            <p class="mb-1">4️⃣ <strong>Tanggungjawab & Pematuhan Undang-Undang</strong><br>
                                AJK hendaklah memastikan penggunaan sistem adalah bagi tujuan kebajikan yang sah serta mematuhi undang-undang berkaitan pengurusan dana, perlindungan data peribadi dan komunikasi digital di Malaysia.</p>

                            <p class="mb-1">5️⃣ <strong>Hak Penamatan Perkhidmatan</strong><br>
                                Pihak DanaKhairat berhak menamatkan akses sistem dengan notis bertulis tiga (3) bulan sekiranya berlaku pelanggaran terma atau penyalahgunaan sistem.</p>

                            <div class="flex items-start gap-2 mt-2">
                                <input type="checkbox" id="terms" name="terms"
                                    class="mt-1 h-4 w-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">

                                <label for="terms" class="text-xs text-gray-700 cursor-pointer leading-relaxed">
                                    Dengan menekan butang “Terma & Syarat serta Akad Wakalah”, pihak AJK mengesahkan bahawa semua makluman di atas telah dibaca, difahami dan dipersetujui.
                                </label>
                            </div>
                        </div>

                        <!-- See More / Close Button -->
                        <button type="button" id="toggle-terms"
                            class="text-gray-600 text-xs font-medium mt-1 hover:text-gray-800">
                            See More...
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-gray-800 rounded-xl p-5 text-white">
            <div class="flex items-center mb-4">
                <div class="w-8 h-8 bg-gray-700 rounded-lg flex items-center justify-center mr-3">
                    <i class="fas fa-receipt text-white text-sm"></i>
                </div>
                <h3 class="font-semibold text-sm">Ringkasan Pendaftaran</h3>
            </div>

            <div class="space-y-3 text-sm mb-6">
                <div class="flex justify-between items-center pb-2 border-b border-gray-700">
                    <span class="text-gray-400">Pakej:</span>
                    <span class="font-medium">DanaKhairat</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-gray-400">Jumlah Bayaran:</span>
                    <span class="text-xl font-bold text-white">RM 1,500.00</span>
                </div>
            </div>

            <div class="space-y-2">
                <label class="text-[11px] uppercase tracking-wider text-gray-400 font-bold">Muat Naik Resit Pembayaran</label>
                <div class="relative group">
                    <input type="file"
                        id="receipt-upload"
                        name="resit"
                        accept="image/*,.pdf"
                        class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10">
                    <div class="border-2 border-dashed border-gray-600 rounded-xl p-6 transition-all duration-200 group-hover:border-gray-400 group-hover:bg-gray-700/50 flex flex-col items-center justify-center">
                        <div class="w-10 h-10 bg-gray-700 rounded-full flex items-center justify-center mb-3 group-hover:scale-110 transition-transform">
                            <i class="fas fa-cloud-upload-alt text-gray-300 group-hover:text-white"></i>
                        </div>
                        <p class="text-xs font-medium text-gray-300" id="file-name">Pilih fail atau tarik ke sini</p>
                        <p class="text-[10px] text-gray-500 mt-1">PNG, JPG atau PDF (Maks. 5MB)</p>
                    </div>
                </div>
            </div>

            <p class="text-gray-500 text-[10px] text-center mt-4 italic">
                <i class="fas fa-info-circle mr-1"></i> Pengesahan pendaftaran akan diproses selepas resit disahkan.
            </p>
        </div>
    </div>
</div>
<!-- JavaScript untuk handle package selection -->
<script>
    // Package selection summary update
    document.addEventListener('DOMContentLoaded', function() {
        const termsCheckbox = document.getElementById('terms');

        // Custom checkbox behavior
        termsCheckbox.addEventListener('change', function() {
            const checkIcon = this.parentElement.querySelector('.fa-check');
            const borderBox = this.parentElement.querySelector('.border-gray-400');

            if (this.checked) {
                borderBox.classList.add('bg-gray-600', 'border-gray-600');
                checkIcon.classList.remove('text-white');
            } else {
                borderBox.classList.remove('bg-gray-600', 'border-gray-600');
                checkIcon.classList.add('text-white');
            }
        });

    });

    document.getElementById('receipt-upload').addEventListener('change', function(e) {
        const fileName = e.target.files[0]?.name || "Pilih fail atau tarik ke sini";
        document.getElementById('file-name').textContent = fileName;
        document.getElementById('file-name').classList.add('text-green-400');
    });

    function openQR() {
        document.getElementById('qrModal').classList.remove('hidden');
        document.getElementById('qrModal').classList.add('flex');
    }

    function closeQR() {
        document.getElementById('qrModal').classList.add('hidden');
        document.getElementById('qrModal').classList.remove('flex');
    }

    const toggleBtn = document.getElementById('toggle-terms');
    const extraTerms = document.getElementById('extra-terms');

    toggleBtn.addEventListener('click', () => {
        if (extraTerms.style.maxHeight === 'none') {
            // Collapse
            extraTerms.style.maxHeight = '6rem'; // separuh tinggi
            toggleBtn.innerText = 'Lagi...';
        } else {
            // Expand
            extraTerms.style.maxHeight = 'none';
            toggleBtn.innerText = 'Tutup';
        }
    });
</script>

<style>
    .package-option.active {
        border-color: #6b7280;
        background-color: #f9fafb;
    }

    /* Custom checkbox styling */
    input[type="checkbox"]:checked+div {
        background-color: #4b5563;
        border-color: #4b5563;
    }

    input[type="checkbox"]:checked+div .fa-check {
        color: white;
    }
</style>