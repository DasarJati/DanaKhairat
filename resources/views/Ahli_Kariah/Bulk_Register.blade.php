{{-- resources/views/ajk/bulk_register.blade.php --}}
@extends('layouts.app')

@section('title', 'Senarai Ahli Khairat - e-Khairat')

@section('content')
<body class="bg-gray-50 min-h-screen">
    <div class="container mx-auto px-4 py-8 max-w-7xl">
        <!-- Header / Tajuk Utama -->
        <div class="bg-white rounded-2xl shadow-xl p-8 mb-8 border-b-4 border-emerald-600">
            <div class="flex items-center justify-between flex-wrap gap-4">
                <div>
                    <h1 class="text-3xl font-black text-gray-900 flex items-center gap-3">
                        <i class="fas fa-users text-emerald-600 text-4xl"></i>
                        Daftar Ahli Kariah (Banyak-Banyak)
                    </h1>
                    <p class="text-gray-700 text-lg mt-2 font-medium">Muat naik fail Excel untuk memasukkan nama ahli kariah secara serentak ke dalam sistem.</p>
                </div>
            </div>
        </div>

        <!-- Results Display / Keputusan Kemasukan Data -->
        @if(session('results'))
            <div class="bg-white rounded-2xl shadow-xl p-8 mb-8 border-l-8 border-emerald-500">
                <h3 class="font-bold text-2xl text-gray-900 mb-4">📊 Ringkasan Keputusan:</h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4">
                    <div class="bg-green-100 p-5 rounded-xl border-2 border-green-400">
                        <p class="text-lg font-bold text-green-800">✅ BERJAYA</p>
                        <p class="text-4xl font-black text-green-900 mt-1">{{ session('results')['success'] ?? 0 }} Orang</p>
                    </div>
                    <div class="bg-red-100 p-5 rounded-xl border-2 border-red-400">
                        <p class="text-lg font-bold text-red-800">❌ GAGAL</p>
                        <p class="text-4xl font-black text-red-900 mt-1">{{ session('results')['failed'] ?? 0 }} Orang</p>
                    </div>
                    <div class="bg-blue-100 p-5 rounded-xl border-2 border-blue-400">
                        <p class="text-lg font-bold text-blue-800">📋 JUMLAH</p>
                        <p class="text-4xl font-black text-blue-900 mt-1">
                            {{ (session('results')['success'] ?? 0) + (session('results')['failed'] ?? 0) }} Orang
                        </p>
                    </div>
                    <div class="bg-yellow-100 p-5 rounded-xl border-2 border-yellow-400">
                        <p class="text-lg font-bold text-yellow-800">⏳ MENUNGGU</p>
                        <p class="text-4xl font-black text-yellow-900 mt-1">{{ session('results')['success'] ?? 0 }} Orang</p>
                    </div>
                </div>

                @if(!empty(session('results')['users']))
                    <div class="mt-6">
                        <h4 class="font-bold text-xl text-gray-800 mb-2">✅ Senarai Ahli yang BERJAYA Didaftarkan:</h4>
                        <div class="max-h-60 overflow-y-auto bg-gray-100 p-4 rounded-xl border border-gray-300">
                            <ul class="text-base space-y-2 font-medium">
                                @foreach(session('results')['users'] as $user)
                                    <li class="text-green-800 flex items-center bg-white p-2 rounded shadow-sm">
                                        <i class="fas fa-check-circle text-green-600 mr-3 text-lg"></i>
                                        <span><strong>{{ $user['nama'] }}</strong> (IC: {{ $user['ic'] }}) @if($user['email']) - {{ $user['email'] }} @endif</span>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                @endif

                @if(!empty(session('results')['errors']))
                    <div class="mt-6">
                        <h4 class="font-bold text-xl text-red-800 mb-2">❌ Senarai Masalah / Ralat:</h4>
                        <div class="max-h-60 overflow-y-auto bg-red-50 p-4 rounded-xl border border-red-300">
                            <ul class="text-base space-y-2 text-red-900 font-medium">
                                @foreach(session('results')['errors'] as $error)
                                    <li class="bg-white p-2 rounded shadow-sm border-l-4 border-red-500">• {{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                @endif
            </div>
        @endif

        <!-- Upload Form / Borang Muat Naik -->
        <div class="bg-white rounded-2xl shadow-xl p-8 mb-8">
            <form action="{{ route('ajk.bulk-register.upload') }}" method="POST" enctype="multipart/form-data" id="uploadForm">
                @csrf

                <!-- Masjid Selection -->
                <div class="mb-8">
                    <label class="block text-xl font-bold text-gray-900 mb-3" for="masjid_id">
                        <i class="fas fa-mosque text-emerald-600 mr-2"></i>
                        Langkah 1: Pilih Masjid Anda <span class="text-red-600 text-2xl">*</span>
                    </label>
                    <select name="masjid_id" id="masjid_id" required
                        class="w-full md:w-3/4 border-2 border-gray-400 rounded-xl px-4 py-4 text-lg font-medium text-gray-900 focus:ring-4 focus:ring-emerald-500 focus:border-emerald-600 transition bg-gray-50">
                        <option value="" class="font-bold">-- Sila Klik Di Sini Untuk Pilih Masjid --</option>
                        @foreach($masjids as $masjid)
                            <option value="{{ $masjid->id }}">{{ $masjid->nama }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Template Download Box (Placed before file selection to guide them) -->
                <div class="bg-emerald-50 border-2 border-emerald-400 rounded-2xl p-6 mb-8">
                    <div class="flex items-center justify-between flex-wrap gap-4">
                        <div class="max-w-xl">
                            <h4 class="font-bold text-xl text-emerald-900 flex items-center gap-2">
                                <i class="fas fa-download text-2xl"></i>
                                Belum ada contoh borang Excel?
                            </h4>
                            <p class="text-base text-emerald-800 mt-1 font-medium">Sila muat turun (download) contoh fail Excel ini terlebih dahulu, isi nama ahli di dalamnya, kemudian simpan di komputer anda.</p>
                        </div>
                        <a href="{{ route('ajk.bulk-register.template') }}" 
                           class="bg-emerald-700 hover:bg-emerald-800 text-white text-lg font-bold px-6 py-4 rounded-xl transition flex items-center gap-3 shadow-md transform hover:scale-105">
                            <i class="fas fa-file-excel text-xl"></i>
                            Muat Turun Contoh Excel
                        </a>
                    </div>
                </div>

                <!-- File Upload Box -->
                <div class="mb-8">
                    <label class="block text-xl font-bold text-gray-900 mb-3">
                        <i class="fas fa-file-excel text-emerald-600 mr-2"></i>
                        Langkah 2: Pilih Fail Excel Yang Telah Diisi <span class="text-red-600 text-2xl">*</span>
                    </label>
                    
                    <div id="dropZone" class="border-4 border-dashed border-gray-400 rounded-2xl p-10 text-center bg-gray-50 hover:bg-emerald-50 hover:border-emerald-500 transition cursor-pointer" onclick="document.getElementById('fileInput').click()">
                        <div class="flex flex-col items-center justify-center">
                            <i class="fas fa-cloud-upload-alt text-7xl text-emerald-600 mb-4"></i>
                            <p class="text-gray-900 font-black text-2xl mb-1">Klik di sini untuk memilih fail Excel</p>
                            <p class="text-gray-600 text-base font-medium">(Boleh juga tarik dan letak fail Excel anda terus ke dalam kotak ini)</p>
                            <p class="text-sm text-gray-500 mt-4 font-bold bg-white px-3 py-1 rounded-full border">
                                <i class="fas fa-info-circle mr-1"></i> Format fail mestilah: .xlsx atau .xls sahaja
                            </p>
                            <input type="file" name="file" id="fileInput" 
                                   accept=".xlsx,.xls"
                                   class="hidden" required>
                        </div>
                    </div>

                    <!-- File Info (Shows after file selected) -->
                    <div id="fileInfo" class="hidden mt-4 p-4 bg-green-100 border-2 border-green-400 rounded-xl flex items-center gap-4">
                        <i class="fas fa-file-excel text-green-700 text-4xl"></i>
                        <div class="flex-1">
                            <p class="text-sm font-bold text-green-700">FAIL TELAH DIPILIH:</p>
                            <p id="fileName" class="text-xl font-black text-gray-900"></p>
                            <p id="fileSize" class="text-sm font-medium text-gray-600"></p>
                        </div>
                        <button type="button" onclick="removeFile(); event.stopPropagation();" class="bg-red-600 hover:bg-red-700 text-white font-bold px-4 py-2 rounded-lg text-sm flex items-center gap-1">
                            <i class="fas fa-times"></i> Padam
                        </button>
                    </div>
                    @error('file')
                        <p class="text-red-600 text-base font-bold mt-2 bg-red-50 p-2 rounded border border-red-300">⚠️ {{ $message }}</p>
                    @enderror
                </div>

                <!-- Preview Section -->
                <div id="previewSection" class="hidden mb-8">
                    <div class="flex items-center justify-between mb-3 bg-gray-100 p-3 rounded-xl border">
                        <h3 class="font-bold text-xl text-gray-900">
                            <i class="fas fa-eye text-blue-600 mr-2"></i>
                            Semakan Data Anda (Preview)
                        </h3>
                        <span id="previewCount" class="text-base font-bold bg-blue-600 text-white px-3 py-1 rounded-full"></span>
                    </div>
                    <div class="border-2 border-gray-300 rounded-xl overflow-hidden shadow-sm">
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y-2 divide-gray-300 text-base">
                                <thead class="bg-gray-800 text-white">
                                    <tr id="previewHeaders" class="font-bold"></tr>
                                </thead>
                                <tbody id="previewBody" class="bg-white divide-y divide-gray-200 font-medium text-gray-900"></tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Submit Button / Butang Hantar -->
                <div class="flex flex-col sm:flex-row items-center gap-4 pt-6 border-t-2 border-gray-200">
                    <button type="submit" id="submitBtn"
                            class="w-full sm:w-auto bg-emerald-600 hover:bg-emerald-700 text-white px-10 py-5 rounded-xl font-black text-xl transition flex items-center justify-center gap-3 shadow-lg disabled:opacity-50 disabled:cursor-not-allowed transform hover:scale-105">
                        <i class="fas fa-upload text-2xl"></i>
                        MULA IMPORT SEKARANG
                    </button>
                    <span class="text-base font-bold text-amber-800 bg-amber-50 p-3 rounded-xl border border-amber-300 flex items-center gap-2">
                        <i class="fas fa-exclamation-circle text-xl text-amber-600"></i>
                        Sila pastikan maklumat di atas telah betul sebelum menekan butang hijau ini.
                    </span>
                </div>
            </form>
        </div>

        <!-- Instructions / Panduan yang Jelas -->
        <div class="bg-white rounded-2xl shadow-xl p-8 border-2 border-gray-300">
            <h3 class="font-black text-2xl text-gray-900 mb-6 flex items-center gap-2">
                <i class="fas fa-info-circle text-emerald-600 text-3xl"></i>
                📖 Buku Panduan & Syarat Fail Excel
            </h3>
            <div class="grid md:grid-cols-2 gap-8 text-lg font-medium text-gray-900">
                <div class="bg-green-50 p-5 rounded-xl border border-green-200">
                    <h4 class="font-black text-xl text-green-900 flex items-center gap-2 border-b pb-2 border-green-300">
                        <i class="fas fa-check-circle text-green-600"></i>
                        Maklumat Wajib Ada (Mesti Isi)
                    </h4>
                    <ul class="list-disc list-inside space-y-2 mt-3 font-bold text-green-950">
                        <li>Nama Penuh</li>
                        <li>No. Kad Pengenalan <span class="text-sm font-normal text-gray-700">(Contoh: 600102-10-5544)</span></li>
                        <li>Jantina <span class="text-sm font-normal text-gray-700">(Tulis: LELAKI atau PEREMPUAN)</span></li>
                        <li>No. Telefon Bimbit</li>
                    </ul>
                </div>
                
                <div class="bg-blue-50 p-5 rounded-xl border border-blue-200">
                    <h4 class="font-black text-xl text-blue-900 flex items-center gap-2 border-b pb-2 border-blue-300">
                        <i class="fas fa-plus-circle text-blue-600"></i>
                        Maklumat Tambahan (Boleh Kosongkan)
                    </h4>
                    <ul class="list-disc list-inside space-y-2 mt-3 text-blue-950">
                        <li>Alamat Emel</li>
                        <li>Alamat Rumah</li>
                        <li>Tarikh Lahir & Umur</li>
                        <li>Bangsa & Status Perkahwinan</li>
                        <li>Nama Waris / Hubungan</li>
                    </ul>
                </div>
                
                <div class="md:col-span-2 bg-red-50 p-6 rounded-xl border-2 border-red-200">
                    <h4 class="font-black text-xl text-red-900 flex items-center gap-2 border-b pb-2 border-red-300">
                        <i class="fas fa-exclamation-triangle text-red-600 text-2xl"></i>
                        Perkara Sangat Penting!
                    </h4>
                    <ul class="list-disc list-inside space-y-3 mt-3 text-red-950">
                        <li><strong>No. Kad Pengenalan:</strong> Sila pastikan ada tanda sempang/dash. Contoh: <code class="bg-white px-2 py-1 rounded border font-bold">550203-08-5123</code></li>
                        <li><strong>Jangan Ubah Susunan Kotak:</strong> Jangan sesekali menukar atau mengalih kedudukan ruangan/kolum asal dalam fail contoh yang diberikan.</li>
                        <li><strong>Kelulusan:</strong> Semua ahli baru yang dimasukkan akan diletakkan di bawah status <span class="bg-amber-100 text-amber-900 px-2 py-0.5 rounded font-bold">Menunggu Kelulusan</span> pihak pentadbir masjid terlebih dahulu.</li>
                        <li><strong>Kata Laluan (Password) Asal:</strong> Sistem akan menetapkan kata laluan ahli sebagai <code class="bg-white px-2 py-1 rounded border font-mono text-base font-bold">password123</code>. Ahli boleh menukarnya sendiri selepas berjaya masuk nanti.</li>
                        <li><strong>Nama Sedia Ada:</strong> Jika nama atau No. IC ahli tersebut sudah pun didaftarkan sebelum ini, sistem akan melangkau (skip) nama tersebut secara automatik untuk mengelakkan nama bertindih.</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <script>
        // File Input & Drop Zone
        const dropZone = document.getElementById('dropZone');
        const fileInput = document.getElementById('fileInput');
        const fileInfo = document.getElementById('fileInfo');
        const fileName = document.getElementById('fileName');
        const fileSize = document.getElementById('fileSize');
        const submitBtn = document.getElementById('submitBtn');

        // Drag and Drop
        dropZone.addEventListener('dragover', (e) => {
            e.preventDefault();
            dropZone.classList.add('dragover');
        });

        dropZone.addEventListener('dragleave', (e) => {
            e.preventDefault();
            dropZone.classList.remove('dragover');
        });

        dropZone.addEventListener('drop', (e) => {
            e.preventDefault();
            dropZone.classList.remove('dragover');
            if (e.dataTransfer.files.length) {
                fileInput.files = e.dataTransfer.files;
                handleFile(fileInput.files[0]);
            }
        });

        dropZone.addEventListener('click', () => {
            fileInput.click();
        });

        fileInput.addEventListener('change', (e) => {
            if (e.target.files.length) {
                handleFile(e.target.files[0]);
            }
        });

        function handleFile(file) {
            const ext = file.name.split('.').pop().toLowerCase();
            if (!['xlsx', 'xls'].includes(ext)) {
                Swal.fire({
                    icon: 'error',
                    title: 'Format Fail Tidak Dibenarkan',
                    text: 'Sila muat naik fail .xlsx atau .xls sahaja.',
                    confirmButtonColor: '#ea580c'
                });
                fileInput.value = '';
                return;
            }

            if (file.size > 10 * 1024 * 1024) {
                Swal.fire({
                    icon: 'error',
                    title: 'Fail Terlalu Besar',
                    text: 'Saiz fail maksimum ialah 10MB.',
                    confirmButtonColor: '#ea580c'
                });
                fileInput.value = '';
                return;
            }

            fileInfo.classList.remove('hidden');
            dropZone.classList.add('has-file');
            fileName.textContent = file.name;
            fileSize.textContent = (file.size / 1024 / 1024).toFixed(2) + ' MB';
            submitBtn.disabled = false;

            // Preview file
            previewFile(file);
        }

        function removeFile() {
            fileInput.value = '';
            fileInfo.classList.add('hidden');
            dropZone.classList.remove('has-file');
            document.getElementById('previewSection').classList.add('hidden');
            submitBtn.disabled = true;
        }

        // Preview Function
        function previewFile(file) {
            const formData = new FormData();
            formData.append('file', file);

            // Show loading
            document.getElementById('previewSection').classList.remove('hidden');
            document.getElementById('previewBody').innerHTML = `
                <tr>
                    <td colspan="15" class="text-center py-8 text-gray-500">
                        <i class="fas fa-spinner fa-spin text-2xl mr-2"></i>
                        Memuatkan preview...
                    </td>
                </tr>
            `;

            fetch('{{ route("ajk.bulk-register.preview") }}', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    renderPreview(data);
                } else {
                    document.getElementById('previewBody').innerHTML = `
                        <tr>
                            <td colspan="15" class="text-center py-8 text-red-500">
                                <i class="fas fa-exclamation-circle text-2xl mr-2"></i>
                                ${data.message || 'Gagal memuatkan preview'}
                            </td>
                        </tr>
                    `;
                }
            })
            .catch(error => {
                document.getElementById('previewBody').innerHTML = `
                    <tr>
                        <td colspan="15" class="text-center py-8 text-red-500">
                            <i class="fas fa-exclamation-circle text-2xl mr-2"></i>
                            Ralat: ${error.message}
                        </td>
                    </tr>
                `;
            });
        }

        function renderPreview(data) {
            const headers = data.headers || [];
            const rows = data.data || [];
            const totalRows = data.total_rows || 0;

            // Render headers
            const headerRow = document.getElementById('previewHeaders');
            headerRow.innerHTML = headers.map(h => 
                `<th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">${h || '-'}</th>`
            ).join('');

            // Render body
            const body = document.getElementById('previewBody');
            if (rows.length === 0) {
                body.innerHTML = `
                    <tr>
                        <td colspan="${headers.length}" class="text-center py-8 text-gray-500">
                            Tiada data untuk dipreview
                        </td>
                    </tr>
                `;
            } else {
                body.innerHTML = rows.map(row => {
                    const cells = row.map(cell => 
                        `<td class="px-4 py-2 whitespace-nowrap text-sm text-gray-700">${cell || '-'}</td>`
                    ).join('');
                    return `<tr class="hover:bg-gray-50">${cells}</tr>`;
                }).join('');

                if (totalRows > 10) {
                    body.innerHTML += `
                        <tr>
                            <td colspan="${headers.length}" class="px-4 py-3 text-center text-sm text-gray-500 bg-gray-50">
                                <i class="fas fa-ellipsis-h mr-2"></i>
                                Dan ${totalRows - 10} baris lagi...
                            </td>
                        </tr>
                    `;
                }
            }

            document.getElementById('previewCount').textContent = `Menunjukkan ${Math.min(rows.length, 10)} daripada ${totalRows} baris`;
        }

        // Form Submit with SweetAlert
        let isImporting = false; // guards against double-submit (double-click, slow network, etc.)

        document.getElementById('uploadForm').addEventListener('submit', function(e) {
            e.preventDefault();

            // If a request is already in flight, ignore this extra submit trigger entirely.
            if (isImporting) {
                return;
            }

            const masjid = document.getElementById('masjid_id');
            const file = fileInput;

            if (!masjid.value) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Sila Pilih Masjid',
                    text: 'Anda perlu memilih masjid terlebih dahulu.',
                    confirmButtonColor: '#ea580c'
                });
                return;
            }

            if (!file.files || !file.files.length) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Sila Pilih Fail',
                    text: 'Anda perlu memuat naik fail Excel untuk import.',
                    confirmButtonColor: '#ea580c'
                });
                return;
            }

            Swal.fire({
                title: 'Pengesahan Import',
                html: `
                    <p>Adakah anda pasti mahu mengimport ahli dari fail ini?</p>
                    <div class="mt-2 text-sm text-gray-500">
                        <i class="fas fa-info-circle"></i>
                        ${file.files[0].name} (${(file.files[0].size / 1024 / 1024).toFixed(2)} MB)
                    </div>
                    <p class="mt-2 text-sm text-yellow-600">
                        <i class="fas fa-exclamation-triangle"></i>
                        Ahli yang telah wujud akan dilangkau
                    </p>
                `,
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#ea580c',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Ya, Import!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (!result.isConfirmed) {
                    return;
                }

                // Lock immediately - synchronous, runs before any async work,
                // so even a rapid double-click can't slip a second submit through.
                isImporting = true;
                submitBtn.disabled = true;

                Swal.fire({
                    title: 'Sedang Memproses...',
                    text: 'Sila tunggu sementara data diimport.',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });

                const formData = new FormData(this);

                fetch(this.action, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        const rows = (data.results && data.results.rows) || [];

                        const rowsHtml = rows.length
                            ? `
                                <div class="mt-4 text-left">
                                    <p class="font-semibold text-gray-700 text-sm mb-2">📋 Butiran Setiap Baris:</p>
                                    <div class="max-h-72 overflow-y-auto border border-gray-200 rounded-lg">
                                        <table class="w-full text-xs">
                                            <thead class="bg-gray-100 sticky top-0">
                                                <tr>
                                                    <th class="px-2 py-2 text-left">Baris</th>
                                                    <th class="px-2 py-2 text-left">Nama</th>
                                                    <th class="px-2 py-2 text-left">No. IC</th>
                                                    <th class="px-2 py-2 text-left">Status / Sebab</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                ${rows.map(r => `
                                                    <tr class="${r.status === 'success' ? 'bg-green-50' : 'bg-red-50'} border-t border-gray-100">
                                                        <td class="px-2 py-2 align-top">${r.row}</td>
                                                        <td class="px-2 py-2 align-top">${r.nama}</td>
                                                        <td class="px-2 py-2 align-top">${r.ic}</td>
                                                        <td class="px-2 py-2 align-top ${r.status === 'success' ? 'text-green-700' : 'text-red-700'}">
                                                            ${r.status === 'success' ? '✅ Berjaya' : '❌ Gagal'}
                                                            ${r.message ? `<div class="text-[11px] mt-0.5 opacity-80">${r.message}</div>` : ''}
                                                        </td>
                                                    </tr>
                                                `).join('')}
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            `
                            : '';

                        Swal.fire({
                            icon: data.results.failed > 0 ? 'warning' : 'success',
                            title: 'Import Selesai!',
                            width: 640,
                            html: `
                                <p class="text-gray-600">${data.message}</p>
                                <div class="mt-4 grid grid-cols-2 gap-2 text-sm">
                                    <div class="bg-green-50 p-2 rounded">✅ Berjaya: <strong>${data.results.success}</strong></div>
                                    <div class="bg-red-50 p-2 rounded">❌ Gagal: <strong>${data.results.failed}</strong></div>
                                </div>
                                ${rowsHtml}
                            `,
                            confirmButtonColor: '#ea580c'
                        }).then(() => {
                            window.location.reload();
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Import Gagal',
                            text: data.message || 'Sila semak fail anda dan cuba lagi.',
                            confirmButtonColor: '#ea580c'
                        });
                    }
                })
                .catch(error => {
                    Swal.fire({
                        icon: 'error',
                        title: 'Ralat Sistem',
                        text: 'Terjadi masalah semasa memproses. Sila cuba lagi.',
                        confirmButtonColor: '#ea580c'
                    });
                })
                .finally(() => {
                    // Always release the lock, whether it succeeded, failed, or errored.
                    isImporting = false;
                    submitBtn.disabled = false;
                });
            });
        });

        // Check for results from session and show notification
        @if(session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Berjaya!',
                text: "{{ session('success') }}",
                confirmButtonColor: '#ea580c'
            });
        @endif

        @if(session('error'))
            Swal.fire({
                icon: 'error',
                title: 'Ralat!',
                text: "{{ session('error') }}",
                confirmButtonColor: '#ea580c'
            });
        @endif
    </script>
</body>
@endsection