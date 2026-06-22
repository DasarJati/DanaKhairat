<!-- Tab 2 -->
<div class="text-center mb-4">
    <h2 class="text-2xl font-bold text-gray-800 mb-1">Pendaftaran</h2>
    <div class="w-16 h-1 bg-gray-400 mx-auto rounded-full mb-2"></div>
    <p class="text-gray-600 text-sm">Sila muat naik semua dokumen yang diperlukan</p>
</div>

<div class="bg-white rounded-xl p-5 shadow-sm border border-gray-200">
    <div class="grid gap-4">

        <!-- Maklumat Pendaftar Section -->
        <div class="bg-white rounded-xl p-5 border border-gray-300 hover:border-gray-400 hover:shadow-md transition-all duration-300">

            <div class="flex items-center space-x-3 mb-4">
                <div class="w-10 h-10 bg-gray-100 rounded-lg flex items-center justify-center border border-gray-300">
                    <i class="fas fa-user-edit text-gray-600 text-base"></i>
                </div>
                <div>
                    <h3 class="font-bold text-gray-800">Maklumat Pendaftar</h3>
                    <p class="text-gray-600 text-xs">Sila isi maklumat pendaftar dan muat naik salinan IC</p>
                </div>
            </div>

            <!-- Fields -->
            <div class="grid md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">Nama Pendaftar</label>
                    <input type="text" name="nama_pendaftar" id="nama_pendaftar" required
                           class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:ring-1 focus:ring-gray-400 focus:border-gray-400 transition-all duration-200"
                           placeholder="Masukkan nama penuh">
                </div>

                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">Nombor Telefon Pendaftar</label>
                    <input type="text" name="notel" id="notel" required
                           class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:ring-1 focus:ring-gray-400 focus:border-gray-400 transition-all duration-200"
                           placeholder="Contoh: 0123456789">
                </div>

                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">Nombor IC Pendaftar</label>
                    <input type="text" name="ic" id="ic" required
                           class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:ring-1 focus:ring-gray-400 focus:border-gray-400 transition-all duration-200"
                           placeholder="Contoh: 901231-01-1234">
                </div>
            </div>

            <!-- Upload Sections -->
            <div class="space-y-4 mt-5">
                
                <!-- Pengesahan Identiti Section -->
                <div class="flex items-center space-x-2 mt-1">
                    <label class="text-lg font-semibold text-gray-800">
                        Pengesahan Identiti
                    </label>

                    <!-- Info Button -->
                    <button type="button" onclick="showIdentityInfo()" 
                            class="w-7 h-7 bg-blue-100 hover:bg-blue-200 rounded-full flex items-center justify-center transition-colors duration-200 group">
                        <i class="fas fa-info text-blue-600 text-xs group-hover:text-blue-800"></i>
                    </button>
                </div>


                <!-- Upload Salinan IC -->
                <div class="border border-gray-300 rounded-lg p-4 hover:border-gray-400 transition-all duration-200">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-3">
                            <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center border border-blue-200">
                                <i class="fas fa-id-card text-blue-600 text-sm"></i>
                            </div>
                            <div class="flex items-center space-x-2">
                                <label class="block text-sm font-medium text-gray-700">Salinan IC (Depan Belakang)</label>
                                <p class="text-gray-500 text-xs mt-0.5">Format PDF, PNG, JPG,J PEG maksimum 5MB</p>
                            </div>
                        </div>

                        <input type="file" name="salinan_ic" required accept="application/pdf, image/png, image/jpeg"
                            class="text-xs file:px-3 file:py-1.5 file:bg-gray-600 file:text-white file:border-0 file:rounded-md file:cursor-pointer hover:file:bg-gray-700 transition-colors duration-200" />
                    </div>
                </div>

                <!-- Upload Sijil Pendaftaran -->
                <div class="border border-gray-300 rounded-lg p-4 hover:border-gray-400 transition-all duration-200">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-3">
                            <div class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center border border-green-200">
                                <i class="fas fa-file-certificate text-green-600 text-sm"></i>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Sijil Pendaftaran Institusi/ROS</label>
                                <p class="text-gray-500 text-xs">Format PDF, PNG,JPG, JPEG maksimum 5MB</p>
                            </div>
                        </div>
                        <input type="file" name="sijil_pendaftaran" accept="application/pdf, image/png, image/jpeg"
                               class="text-xs file:px-3 file:py-1.5 file:bg-gray-600 file:text-white file:border-0 file:rounded-md file:cursor-pointer hover:file:bg-gray-700 transition-colors duration-200" />
                    </div>
                </div>

                <!-- Upload Slip Akaun -->
                <div class="border border-gray-300 rounded-lg p-4 hover:border-gray-400 transition-all duration-200">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-3">
                            <div class="w-8 h-8 bg-purple-100 rounded-lg flex items-center justify-center border border-purple-200">
                                <i class="fas fa-receipt text-purple-600 text-sm"></i>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Salinan Slip Akaun</label>
                                <p class="text-gray-500 text-xs">Format PDF, PNG, JPG, JPEG maksimum 5MB</p>
                            </div>
                        </div>
                        <input type="file" name="slip_akaun" required accept="application/pdf, image/png, image/jpeg"
                               class="text-xs file:px-3 file:py-1.5 file:bg-gray-600 file:text-white file:border-0 file:rounded-md file:cursor-pointer hover:file:bg-gray-700 transition-colors duration-200" />
                    </div>
                </div>

            </div>

        </div>
    </div>
</div>
<!-- ✅ MOVE THIS OUTSIDE, PASTE AT BOTTOM OF PAGE -->
<div id="exampleModal"
     class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">

    <div class="bg-white rounded-lg p-6 w-96 shadow-lg">
        <h3 class="text-lg font-semibold mb-3">Contoh Salinan IC</h3>

        <img src="{{ asset('sample/ic-example.jpg') }}"
             class="rounded border mb-4" />

        <div class="text-right">
            <button onclick="closeExample()"
                    class="px-3 py-1.5 bg-gray-600 text-white rounded text-sm hover:bg-gray-700">
                Tutup
            </button>
        </div>
    </div>
</div>

<script>
function showIdentityInfo() {
    Swal.fire({
        title: 'Pengesahan Identiti',
        html: `
            <div class="text-left">
                <p class="mb-3">Dokumen pengesahan identiti diperlukan untuk memastikan:</p>
                <ul class="list-disc pl-5 space-y-2 text-sm">
                    <li>Identiti pendaftar adalah sah dan disahkan</li>
                    <li>Maklumat yang diberikan adalah tepat dan benar</li>
                    <li>Mematuhi keperluan perundangan dan kawal selia</li>
                    <li>Mengelakkan penipuan dan penyalahgunaan sistem</li>
                </ul>
            </div>
        `,
        icon: 'info',
        confirmButtonText: 'OK',
        confirmButtonColor: '#3b82f6',
        width: '500px'
    });
}

function openExample(event) {
    event.stopPropagation();
    const modal = document.getElementById('exampleModal');
    modal.classList.remove('hidden');
    modal.classList.add('flex');
}


function closeExample() {
    const modal = document.getElementById('exampleModal');
    modal.classList.add('hidden');
    modal.classList.remove('flex');
}

// ✅ Close only when clicking BACKDROP
const modal = document.getElementById('exampleModal');

modal.addEventListener('click', function(e) {
    if (e.target === modal) {
        closeExample();
    }
});

</script>
<script>
// ========== TEXT FIELD FORMATTING ==========
document.addEventListener('DOMContentLoaded', function () {

    // Helper: apply uppercase on any input/textarea by id
    function applyUppercase(id) {
        const el = document.getElementById(id);
        if (!el) return;
        el.addEventListener('input', function () {
            const pos = this.selectionStart;
            this.value = this.value.toUpperCase();
            this.setSelectionRange(pos, pos);
        });
    }

    // Helper: apply lowercase on any input by id
    function applyLowercase(id) {
        const el = document.getElementById(id);
        if (!el) return;
        el.addEventListener('input', function () {
            const pos = this.selectionStart;
            this.value = this.value.toLowerCase();
            this.setSelectionRange(pos, pos);
        });
    }

    // 1. Nama Masjid/Surau → UPPERCASE
    applyUppercase('nama_masjid');

    // 2. Alamat → UPPERCASE
    applyUppercase('alamat');

    // 3. Nama Pendaftar → UPPERCASE
    applyUppercase('nama_pendaftar');

    // 4. Email → lowercase
    applyLowercase('email');

    // 5. IC → Auto format: 010413-03-0347
    const icInput = document.getElementById('ic');
    if (icInput) {
        // Set maxlength to allow 12 digits + 2 dashes = 14 chars
        icInput.setAttribute('maxlength', '14');

        icInput.addEventListener('keydown', function (e) {
            const allowedKeys = [
                'Backspace', 'Delete', 'ArrowLeft', 'ArrowRight',
                'Tab', 'Home', 'End'
            ];
            // Allow: digits only + control keys
            if (!allowedKeys.includes(e.key) && !/^\d$/.test(e.key)) {
                e.preventDefault();
            }
        });

        icInput.addEventListener('input', function () {
            // Strip everything except digits
            let digits = this.value.replace(/\D/g, '').substring(0, 12);

            // Format: XXXXXX-XX-XXXX
            let formatted = '';
            if (digits.length <= 6) {
                formatted = digits;
            } else if (digits.length <= 8) {
                formatted = digits.substring(0, 6) + '-' + digits.substring(6);
            } else {
                formatted = digits.substring(0, 6) + '-'
                          + digits.substring(6, 8) + '-'
                          + digits.substring(8);
            }

            this.value = formatted;
        });
    }

    // 6. Notel → digits and dash only, auto format: 012-3456789
    // const notelInput = document.getElementById('notel');
    // if (notelInput) {
    //     notelInput.setAttribute('maxlength', '12');

    //     notelInput.addEventListener('keydown', function (e) {
    //         const allowedKeys = [
    //             'Backspace', 'Delete', 'ArrowLeft', 'ArrowRight',
    //             'Tab', 'Home', 'End'
    //         ];
    //         if (!allowedKeys.includes(e.key) && !/^\d$/.test(e.key)) {
    //             e.preventDefault();
    //         }
    //     });

    //     notelInput.addEventListener('input', function () {
    //         let digits = this.value.replace(/\D/g, '').substring(0, 11);

    //         // Format: XXX-XXXXXXX (e.g. 012-3456789)
    //         let formatted = '';
    //         if (digits.length <= 3) {
    //             formatted = digits;
    //         } else {
    //             formatted = digits.substring(0, 3) + '-' + digits.substring(3);
    //         }

    //         this.value = formatted;
    //     });
    // }

});
</script>