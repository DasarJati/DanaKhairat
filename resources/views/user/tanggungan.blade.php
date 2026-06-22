@extends('layouts.user')

@section('title', 'Tambah Tanggungan')

@section('content')
    <div class="max-w-5xl mx-auto py-10 px-4">

        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
            <div>
                <h1 class="text-3xl font-extrabold text-gray-900 tracking-tight">Pendaftaran Tanggungan</h1>
                <p class="text-gray-500 text-sm mt-1">Sila isi maklumat ahli keluarga yang ingin didaftarkan di bawah pelan
                    khairat anda.</p>
            </div>

            <button type="button" id="addRow"
                class="inline-flex items-center justify-center px-5 py-2.5 rounded-xl bg-blue-600 text-white text-sm font-bold hover:bg-blue-700 transition-all shadow-lg shadow-blue-200 active:scale-95">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                        d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                </svg>
                Tambah Baris
            </button>
        </div>

        <form method="POST" action="{{ route('tanggungan.store') }}" class="space-y-8">
            @csrf

            <div id="tanggungan-wrapper" class="space-y-6">

                <div
                    class="tanggungan-item group bg-white rounded-3xl shadow-sm border border-gray-100 p-8 relative transition-all hover:shadow-md">

                    <div class="flex justify-between items-center mb-6">
                        <div class="flex items-center gap-3">
                            <span
                                class="row-number flex items-center justify-center w-8 h-8 rounded-full bg-blue-50 text-blue-600 font-bold text-sm">1</span>
                            <h3 class="font-bold text-gray-800 uppercase tracking-wider text-xs">Maklumat Tanggungan</h3>
                        </div>

                        <button type="button"
                            class="removeRow hidden p-2 rounded-lg text-gray-400 hover:bg-red-50 hover:text-red-500 transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>
                        </button>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-1.5">
                            <label class="text-xs font-bold text-gray-500 uppercase ml-1">Nama Penuh (Seperti dalam
                                MyKad)</label>
                            <input type="text" name="nama[]" placeholder="CONTOH: AHMAD BIN ALI"
                                class="nama-input w-full px-4 py-3 rounded-xl border-gray-200 bg-gray-50 focus:bg-white focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all uppercase"
                                style="text-transform: uppercase" oninput="this.value = this.value.toUpperCase()" required>
                        </div>

                        <div class="space-y-1.5">
                            <label class="text-xs font-bold text-gray-500 uppercase ml-1">No. Kad Pengenalan</label>
                            <input type="text" name="ic_number[]" placeholder="Contoh: 900101105566"
                                class="ic-input w-full px-4 py-3 rounded-xl border-gray-200 bg-gray-50 focus:bg-white focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all"
                                required>
                        </div>

                        <div class="space-y-1.5">
                            <label class="text-xs font-bold text-gray-500 uppercase ml-1">Tarikh Lahir</label>
                            <input type="text" name="tarikh_lahir[]"
                                class="dob-input w-full px-4 py-3 rounded-xl border-gray-200 bg-gray-50 focus:bg-white focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all"
                                readonly placeholder="Auto-isi dari IC" required>
                            <div class="umur-display hidden flex items-center gap-2 mt-1.5">
                                <span class="text-[11px] font-semibold text-slate-500">Umur:</span>
                                <span
                                    class="umur-value text-[11px] font-bold text-blue-700 bg-blue-50 px-2 py-0.5 rounded-md"></span>
                                <span class="umur-label text-[11px] text-slate-400"></span>
                            </div>
                        </div>

                        <div class="space-y-1.5">
                            <label class="text-xs font-bold text-gray-500 uppercase ml-1">Hubungan</label>
                            <select name="hubungan[]"
                                class="w-full px-4 py-3 rounded-xl border-gray-200 bg-gray-50 focus:bg-white focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all"
                                required>
                                <option value="" disabled selected>Pilih Hubungan</option>
                                <optgroup label="Pasangan / Anak">
                                    <option value="ISTERI">ISTERI</option>
                                    <option value="SUAMI">SUAMI</option>
                                    <option value="ANAK">ANAK</option>
                                </optgroup>
                                <optgroup label="Ibu / Bapa">
                                    <option value="IBU">IBU</option>
                                    <option value="AYAH">AYAH</option>
                                </optgroup>
                            </select>
                        </div>
                    </div>

                    <div class="mt-6 pt-6 border-t border-gray-50 flex items-center">
                        <label class="flex items-center cursor-pointer group">
                            <div class="relative">
                                <input type="checkbox" name="oku[]" value="1" class="oku-checkbox sr-only">
                                <div class="block bg-gray-200 w-10 h-6 rounded-full shadow-inner"></div>
                                <div class="dot absolute left-1 top-1 bg-white w-4 h-4 rounded-full transition shadow-sm">
                                </div>
                            </div>
                            <div class="ml-3 text-gray-700 font-bold text-sm">Status OKU</div>
                        </label>
                        <span class="ml-2 text-[10px] text-gray-400 font-medium tracking-tight italic">*Tanda jika
                            berkenaan</span>
                    </div>
                </div>
            </div>

            <div class="flex items-start gap-4 bg-amber-50 border border-amber-200 p-5 rounded-2xl shadow-sm">
                <div class="p-2 bg-amber-200 rounded-lg text-amber-700">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"
                            clip-rule="evenodd" />
                    </svg>
                </div>
                <div class="text-sm text-amber-900 leading-relaxed">
                    <p class="font-bold mb-1">Nota Penting:</p>
                    <ul class="list-disc ml-4 space-y-1 opacity-90">
                        <li>Anak hanya layak didaftarkan jika berumur <b>24 tahun dan ke bawah</b>. Anak berumur melebihi 24 tahun hanya layak jika berstatus <b>OKU</b>.</li>
                        <li>Pastikan nama penuh mengikut ejaan di dalam MyKad untuk tujuan tuntutan masa hadapan.</li>
                    </ul>
                </div>
            </div>

            <div class="flex flex-col md:flex-row items-center justify-end gap-4 mt-10">
                <a href="{{ url()->previous() }}"
                    class="text-sm font-bold text-gray-400 hover:text-gray-600 transition-colors">Batal</a>
                <button type="submit"
                    class="w-full md:w-auto px-10 py-4 rounded-2xl bg-emerald-600 text-white font-bold text-sm uppercase tracking-widest hover:bg-emerald-700 transition-all shadow-lg shadow-emerald-100 active:scale-95">
                    Simpan & Hantar Rekod
                </button>
            </div>
        </form>
    </div>

    <style>
        /* Custom Toggle Switch Style */
        .oku-checkbox:checked~.dot {
            transform: translateX(100%);
            background-color: #10b981;
        }

        .oku-checkbox:checked~.block {
            background-color: #d1fae5;
        }

        /* Uppercase for all name inputs */
        input[type="text"].nama-input {
            text-transform: uppercase;
        }

        /* Readonly input styling */
        .dob-input:readonly {
            background-color: #f3f4f6;
            cursor: not-allowed;
        }
    </style>

    <script>
        const wrapper = document.getElementById('tanggungan-wrapper');
        const addBtn = document.getElementById('addRow');
        const max = 8;

        // Function to parse IC and get birth date
        function parseMalaysiaIC(icNumber) {
            // Remove non-digits
            let cleaned = icNumber.replace(/\D/g, '');

            // IC must have at least 6 digits
            if (cleaned.length < 6) return null;

            // Extract YYMMDD
            let yearPart = cleaned.substring(0, 2);
            let monthPart = cleaned.substring(2, 4);
            let dayPart = cleaned.substring(4, 6);

            if (!monthPart || !dayPart) return null;

            // Determine century (00-29 = 2000s, 30-99 = 1900s)
            let century = parseInt(yearPart, 10);
            let fullYear = (century >= 0 && century <= 29) ? 2000 + century : 1900 + century;

            // Validate date
            let birthDate = new Date(fullYear, parseInt(monthPart, 10) - 1, parseInt(dayPart, 10));

            if (birthDate.getFullYear() !== fullYear ||
                birthDate.getMonth() !== parseInt(monthPart, 10) - 1 ||
                birthDate.getDate() !== parseInt(dayPart, 10)) {
                return null;
            }

            return birthDate;
        }

        // Function to format date as DD-MM-YYYY
        function formatDateToDMY(dateObj) {
            if (!dateObj || isNaN(dateObj.getTime())) return '';
            let d = String(dateObj.getDate()).padStart(2, '0');
            let m = String(dateObj.getMonth() + 1).padStart(2, '0');
            let y = dateObj.getFullYear();
            return `${d}-${m}-${y}`;
        }

        // Function to format IC with dashes
        function formatIC(raw) {
            let d = raw.replace(/\D/g, '').slice(0, 12);
            if (d.length > 8) return d.slice(0, 6) + '-' + d.slice(6, 8) + '-' + d.slice(8);
            if (d.length > 6) return d.slice(0, 6) + '-' + d.slice(6);
            return d;
        }

        // Calculate age and return object with years, months, label
        function calculateAge(birthDate) {
            const today = new Date();
            let years = today.getFullYear() - birthDate.getFullYear();
            let months = today.getMonth() - birthDate.getMonth();

            if (months < 0 || (months === 0 && today.getDate() < birthDate.getDate())) {
                years--;
                months += 12;
            }

            // Fine-grain label for young children
            let label = '';
            if (years === 0) {
                label = months <= 1 ? `${months} bulan` : `${months} bulan`;
            } else if (years < 2) {
                label = `(${years} tahun ${months} bulan)`;
            } else {
                label = 'tahun';
            }

            return {
                years,
                months,
                label
            };
        }

        // Updated updateBirthDate — now also updates umur display
        function updateBirthDate(icInput, dobInput) {
            const row = icInput.closest('.tanggungan-item');
            const umurDiv = row?.querySelector('.umur-display');
            const umurValue = row?.querySelector('.umur-value');
            const umurLabel = row?.querySelector('.umur-label');

            const icValue = icInput.value;

            if (!icValue || icValue.replace(/\D/g, '').length < 6) {
                dobInput.value = '';
                if (umurDiv) umurDiv.classList.add('hidden');
                return;
            }

            const birthDate = parseMalaysiaIC(icValue);

            if (birthDate) {
                dobInput.value = formatDateToDMY(birthDate);

                if (umurDiv && umurValue && umurLabel) {
                    const age = calculateAge(birthDate);

                    if (age.years === 0) {
                        umurValue.textContent = age.months;
                        umurLabel.textContent = 'bulan';
                    } else {
                        umurValue.textContent = age.years;
                        umurLabel.textContent = age.years < 2 ?
                            `tahun ${age.months} bulan` :
                            'tahun';
                    }

                    umurDiv.classList.remove('hidden');
                }
            } else {
                dobInput.value = '';
                if (umurDiv) umurDiv.classList.add('hidden');
            }
        }

        // Function to setup auto-format and DOB detection for a single row
        function setupRowEvents(row) {
            const icInput = row.querySelector('.ic-input');
            const dobInput = row.querySelector('.dob-input');
            const namaInput = row.querySelector('.nama-input');

            if (!icInput || !dobInput) return;

            // IC Input formatting and DOB detection
            icInput.addEventListener('input', function(e) {
                // Store cursor position
                let pos = this.selectionStart;
                let before = this.value;

                // Format IC
                this.value = formatIC(this.value);

                // Adjust cursor position
                let added = this.value.length - before.length;
                if (added > 0) pos += added;
                try {
                    this.setSelectionRange(pos, pos);
                } catch (e) {}

                // Update birth date
                updateBirthDate(this, dobInput);
            });

            // Handle paste event for IC
            icInput.addEventListener('paste', function(e) {
                setTimeout(() => {
                    this.value = formatIC(this.value);
                    updateBirthDate(this, dobInput);
                }, 10);
            });

            // Ensure name input is always uppercase
            if (namaInput) {
                namaInput.addEventListener('input', function() {
                    this.value = this.value.toUpperCase();
                });
            }

            // Format existing IC value if any
            if (icInput.value) {
                icInput.value = formatIC(icInput.value);
                updateBirthDate(icInput, dobInput);
            }

            // Convert existing name to uppercase
            if (namaInput && namaInput.value) {
                namaInput.value = namaInput.value.toUpperCase();
            }
        }

        // Update row numbers
        function updateNumbers() {
            const rows = wrapper.querySelectorAll('.tanggungan-item');
            rows.forEach((row, index) => {
                const numberSpan = row.querySelector('.row-number');
                if (numberSpan) numberSpan.innerText = index + 1;
            });
        }

        // Setup all existing rows
        function setupAllRows() {
            const rows = wrapper.querySelectorAll('.tanggungan-item');
            rows.forEach(row => setupRowEvents(row));
        }

        // Add new row
        addBtn.addEventListener('click', () => {
            const count = wrapper.querySelectorAll('.tanggungan-item').length;
            if (count >= max) {
                alert('Maksimum 8 tanggungan sahaja dibenarkan');
                return;
            }

            // Clone the first row
            const firstRow = wrapper.children[0];
            const clone = firstRow.cloneNode(true);

            // Reset all inputs in the cloned row
            clone.querySelectorAll('input').forEach(input => {
                if (input.type === 'checkbox') {
                    input.checked = false;
                } else {
                    input.value = '';
                }
            });

            // Reset select dropdowns
            clone.querySelectorAll('select').forEach(select => {
                select.selectedIndex = 0;
            });

            // Show remove button on cloned row
            const removeBtn = clone.querySelector('.removeRow');
            if (removeBtn) removeBtn.classList.remove('hidden');

            // Append the cloned row
            wrapper.appendChild(clone);

            // Setup events for the new row
            setupRowEvents(clone);

            // Update row numbers
            updateNumbers();
        });

        // Remove row handler
        wrapper.addEventListener('click', e => {
            const removeBtn = e.target.closest('.removeRow');
            if (removeBtn) {
                const row = removeBtn.closest('.tanggungan-item');
                if (wrapper.querySelectorAll('.tanggungan-item').length > 1) {
                    row.remove();
                    updateNumbers();
                } else {
                    alert('Minimum satu tanggungan diperlukan');
                }
            }
        });

        // Initialize on page load
        document.addEventListener('DOMContentLoaded', function() {
            setupAllRows();

            // Format IC and DOB for existing first row
            const firstIc = document.querySelector('.ic-input');
            const firstDob = document.querySelector('.dob-input');
            if (firstIc && firstDob) {
                if (firstIc.value) {
                    firstIc.value = formatIC(firstIc.value);
                    updateBirthDate(firstIc, firstDob);
                }
            }

            // Ensure all name inputs are uppercase initially
            document.querySelectorAll('.nama-input').forEach(input => {
                if (input.value) input.value = input.value.toUpperCase();
            });
        });
    </script>
@endsection
