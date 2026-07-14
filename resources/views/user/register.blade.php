<!DOCTYPE html>
<html lang="ms">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pendaftaran Khairat</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        .tab-content {
            display: none;
        }

        .tab-content.active {
            display: block;
        }

        /* ── Stepper ── */
        .stepper-wrap {
            display: flex;
            align-items: center;
            padding: 20px 24px 16px;
            background: #fff;
            border-bottom: 1px solid #f3f4f6;
            overflow-x: auto;
        }

        .step-item {
            display: flex;
            flex-direction: column;
            align-items: center;
            flex: 1;
            position: relative;
            cursor: default;
            user-select: none;
        }

        .step-item:not(:last-child)::after {
            content: '';
            position: absolute;
            top: 18px;
            left: calc(50% + 22px);
            right: calc(-50% + 22px);
            height: 2px;
            background: #e5e7eb;
            z-index: 0;
            transition: background 0.4s;
        }

        .step-item.done:not(:last-child)::after {
            background: #f97316;
        }

        .step-circle {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            border: 2px solid #d1d5db;
            background: #fff;
            color: #9ca3af;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 14px;
            font-weight: 700;
            z-index: 1;
            transition: all 0.3s ease;
        }

        .step-item.active .step-circle {
            background: linear-gradient(135deg, #f97316, #ea580c);
            border-color: #f97316;
            color: #fff;
            box-shadow: 0 0 0 4px rgba(249, 115, 22, 0.15);
        }

        .step-item.done .step-circle {
            background: #f97316;
            border-color: #f97316;
            color: #fff;
        }

        .step-label {
            margin-top: 6px;
            font-size: 11px;
            font-weight: 500;
            color: #9ca3af;
            text-align: center;
            white-space: nowrap;
            transition: color 0.3s;
        }

        .step-item.active .step-label {
            color: #ea580c;
            font-weight: 700;
        }

        .step-item.done .step-label {
            color: #f97316;
        }

        .step-item.done .step-circle i {
            display: none;
        }

        .step-item.done .step-circle::before {
            content: '\f00c';
            font-family: 'Font Awesome 6 Free';
            font-weight: 900;
            font-size: 13px;
        }

        /* Tanggungan card styles */
        .tanggungan-card {
            border: 1px solid #e5e7eb;
            border-radius: 12px;
            padding: 16px;
            margin-bottom: 16px;
            background: #fafafa;
            transition: all 0.3s ease;
            position: relative;
        }

        .tanggungan-card:hover {
            border-color: #f97316;
            box-shadow: 0 2px 8px rgba(249, 115, 22, 0.1);
        }

        .tanggungan-card .remove-btn {
            position: absolute;
            top: 8px;
            right: 8px;
            background: #fee2e2;
            color: #dc2626;
            border: none;
            border-radius: 50%;
            width: 30px;
            height: 30px;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.2s;
        }

        .tanggungan-card .remove-btn:hover {
            background: #dc2626;
            color: white;
        }

        .tanggungan-card .card-header {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 12px;
        }

        .tanggungan-card .card-header .badge {
            background: #f97316;
            color: white;
            padding: 2px 10px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 600;
        }

        .file-preview {
            max-width: 120px;
            max-height: 120px;
            border-radius: 8px;
            border: 1px solid #e5e7eb;
            padding: 4px;
            margin-top: 8px;
        }
    </style>
</head>

<body class="min-h-screen font-sans bg-gradient-to-br from-djariah-50 to-orange-50 py-4">

    <!-- Main Container -->
    <div class="max-w-7xl mx-auto px-4">
        <!-- Header -->
        <div class="bg-white rounded-2xl shadow-lg p-4 mb-4">

            <div class="flex justify-between items-center mb-6">
                <div class="flex items-center space-x-3">
                    <a href="#" class="flex items-center space-x-3">
                        <img src="{{ asset('images/logos.png') }}" alt="Logo Masjid" class="h-10 w-auto">
                    </a>
                </div>

                <a href="/login"
                    class="text-sm text-djariah-600 hover:text-djariah-700 font-medium transition-colors flex items-center gap-1">
                    <i class="fas fa-sign-in-alt"></i>
                    Log Masuk
                </a>
            </div>

            <div>
                <h6 class="text-3xl font-extrabold text-gray-800 mb-2">Pendaftaran Khairat</h6>
                <p class="text-gray-600 text-sm">Sertai komuniti DanaKhairat kami</p>
            </div>

        </div>

        <!-- Form Container -->
        <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
            <!-- Stepper Navigation -->
            <div class="stepper-wrap">
                <div class="step-item active" data-tab="pemohon">
                    <div class="step-circle"><i class="fas fa-user" style="font-size:13px;"></i></div>
                    <span class="step-label">Pemohon</span>
                </div>
                <div class="step-item" data-tab="tanggungan">
                    <div class="step-circle"><i class="fas fa-users" style="font-size:13px;"></i></div>
                    <span class="step-label">Tanggungan</span>
                </div>
                <div class="step-item" data-tab="login">
                    <div class="step-circle"><i class="fas fa-key" style="font-size:13px;"></i></div>
                    <span class="step-label">Maklumat Akaun</span>
                </div>
            </div>

            <form action="{{ route('user.register') }}" method="POST" enctype="multipart/form-data" class="p-6"
                id="registrationForm">

                @csrf

                <!-- Hidden input for ahli_type -->
                <input type="hidden" name="ahli_type" id="ahli_type" value="New">
                <input type="hidden" name="alamat" id="combined_address">
                <input type="hidden" name="tanggungan_data" id="tanggungan_data">

                <!-- Tab 1: Butir-butir Pemohon -->
                <div id="pemohon" class="tab-content active space-y-8">
                    <section class="bg-gray-50 p-6 rounded-xl border border-gray-100">
                        <h3
                            class="text-md font-semibold text-djariah-700 mb-5 flex items-center gap-2 uppercase tracking-wider">
                            <i class="fas fa-user-circle"></i>
                            Maklumat Peribadi Pendaftar
                        </h3>

                        <div class="grid grid-cols-1 md:grid-cols-6 gap-6">
                            <div class="space-y-2 md:col-span-4">
                                <label class="block text-sm font-medium text-gray-700">Nama Penuh (Seperti Dalam
                                    MYKAD)</label>
                                <input type="text" name="nama" value="{{ old('nama') }}"
                                    class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-djariah-500 focus:border-djariah-500 transition-all uppercase"
                                    placeholder="MASUKKAN NAMA PENUH" required>
                            </div>

                            <div class="space-y-2 md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700">No. Tel (Bimbit)</label>
                                <input type="text" name="telefon_bimbit" value="{{ old('telefon_bimbit') }}"
                                    class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-djariah-500 focus:border-djariah-500 transition-all duration-200"
                                    placeholder="012-3456789" required>
                            </div>

                            <div class="space-y-2 md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700">No. Kad Pengenalan</label>
                                <input type="text" id="ic_number" name="ic_number" value="{{ old('ic_number') }}"
                                    class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-djariah-500 focus:border-djariah-500 transition-all"
                                    placeholder="010413-03-1234" maxlength="14" required>
                                <div id="ic_check_status"
                                    class="hidden mt-1 flex items-center gap-2 text-xs font-medium"></div>
                            </div>

                            <div class="space-y-2 md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700">Tarikh Lahir</label>
                                <input type="text" id="tarikh_lahir_display"
                                    class="w-full border border-gray-300 rounded-lg px-4 py-2.5 bg-gray-50 transition-all"
                                    placeholder="dd/mm/yyyy" readonly>
                                <input type="hidden" id="tarikh_lahir" name="tarikh_lahir"
                                    value="{{ old('tarikh_lahir') }}" required>
                            </div>

                            <div class="space-y-2 md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700">Umur</label>
                                <input type="number" id="umur" name="umur" value="{{ old('umur') }}"
                                    class="w-full bg-gray-100 border border-gray-300 rounded-lg px-4 py-2.5 font-bold text-djariah-700"
                                    readonly>
                            </div>

                            <div class="md:col-span-6 grid grid-cols-1 md:grid-cols-3 gap-6">
                                <div class="space-y-2">
                                    <label class="block text-sm font-medium text-gray-700">Jantina</label>
                                    <select name="jantina"
                                        class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-djariah-500 focus:border-djariah-500"
                                        required>
                                        <option value="">-- Pilih --</option>
                                        <option value="LELAKI">LELAKI</option>
                                        <option value="PEREMPUAN">PEREMPUAN</option>
                                    </select>
                                </div>

                                <div class="space-y-2">
                                    <label class="block text-sm font-medium text-gray-700">Bangsa</label>
                                    <select name="bangsa"
                                        class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-djariah-500 focus:border-djariah-500"
                                        required>
                                        <option value="">-- Pilih --</option>
                                        <option value="MELAYU">MELAYU</option>
                                        <option value="CINA">CINA</option>
                                        <option value="INDIA">INDIA</option>
                                        <option value="LAIN">LAIN-LAIN</option>
                                    </select>
                                </div>

                                <div class="space-y-2">
                                    <label class="block text-sm font-medium text-gray-700">Status</label>
                                    <select name="statususer"
                                        class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-djariah-500 focus:border-djariah-500"
                                        required>
                                        <option value="">-- Pilih --</option>
                                        <option value="BUJANG">BUJANG</option>
                                        <option value="BERKELUARGA">BERKELUARGA</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <!-- 5 ADDRESS FIELDS -->
                        <div class="grid grid-cols-1 md:grid-cols-5 gap-4 mt-6 pt-4 border-t border-gray-200">
                            <div class="space-y-2">
                                <label class="block text-sm font-medium text-gray-700">No. Rumah</label>
                                <input type="text" id="no_rumah" name="no_rumah" value="{{ old('no_rumah') }}"
                                    class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-djariah-500 focus:border-djariah-500 transition-all uppercase"
                                    placeholder="No. Rumah / PT">
                            </div>
                            <div class="space-y-2">
                                <label class="block text-sm font-medium text-gray-700">Jalan</label>
                                <input type="text" id="jalan" name="jalan" value="{{ old('jalan') }}"
                                    class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-djariah-500 focus:border-djariah-500 transition-all uppercase"
                                    placeholder="Jalan / Lorong">
                            </div>
                            <div class="space-y-2">
                                <label class="block text-sm font-medium text-gray-700">Taman/Kampung</label>
                                <input type="text" id="taman" name="taman" value="{{ old('taman') }}"
                                    class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-djariah-500 focus:border-djariah-500 transition-all uppercase"
                                    placeholder="Taman / Kampung">
                            </div>
                            <div class="space-y-2">
                                <label class="block text-sm font-medium text-gray-700">Poskod</label>
                                <input type="text" id="poskod_field" name="poskod_field"
                                    value="{{ old('poskod_field') }}"
                                    class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-djariah-500 focus:border-djariah-500 transition-all"
                                    placeholder="43000" maxlength="5">
                            </div>
                            <div class="space-y-2">
                                <label class="block text-sm font-medium text-gray-700">Bandar</label>
                                <input type="text" id="bandar_field" name="bandar_field"
                                    value="{{ old('bandar_field') }}"
                                    class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-djariah-500 focus:border-djariah-500 transition-all uppercase"
                                    placeholder="Kuala Lumpur">
                            </div>
                        </div>
                    </section>

                    <section class="bg-white p-6 rounded-xl border border-gray-200 shadow-sm">
                        <h3
                            class="text-md font-semibold text-djariah-700 mb-5 flex items-center gap-2 uppercase tracking-wider">
                            <i class="fas fa-map-marked-alt"></i>
                            Alamat
                        </h3>

                        <div class="space-y-4">

                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">

                                <div class="space-y-2">
                                    <label class="block text-sm font-medium text-gray-700">Negeri</label>
                                    <select name="negeri" id="negeri"
                                        class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-djariah-500 focus:border-djariah-500 transition-all">
                                        <option value="">-- Pilih Negeri --</option>
                                        @foreach ($negeri as $n)
                                            <option value="{{ $n->id }}">{{ $n->nama }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="space-y-2">
                                    <label class="block text-sm font-medium text-gray-700">Poskod</label>
                                    <select id="poskod" name="poskod"
                                        class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-djariah-500 focus:border-djariah-500 transition-all"
                                        placeholder="Cth: 43000" required>
                                        <option value="">-- Pilih Poskod --</option>
                                    </select>
                                </div>

                                <div class="space-y-2">
                                    <label class="block text-sm font-medium text-gray-700">Bandar</label>
                                    <select id="bandar" name="bandar"
                                        class="w-full border border-gray-300 rounded-lg px-4 py-2.5">
                                        <option value="">-- Pilih Bandar --</option>
                                    </select>
                                </div>

                            </div>

                            <div class="pt-4 border-t border-dashed border-gray-200">
                                <div class="mt-4 pt-4 border-t border-dashed border-gray-200">
                                    <label class="block text-sm font-bold text-djariah-800">Pilih Masjid /
                                        Surau</label>
                                    <select id="masjid" name="masjid_id" required
                                        class="w-full border-2 border-djariah-100 rounded-lg px-4 py-3 bg-white">
                                        <option value="">-- Sila pilih kawasan pendaftaran anda --</option>
                                    </select>
                                </div>
                            </div>

                        </div>
                    </section>

                    <div class="flex justify-end pt-4">
                        <button type="button"
                            class="next-tab flex items-center gap-2 rounded-lg bg-indigo-600 px-5 py-2.5 text-sm font-semibold text-white shadow-md transition-all hover:bg-indigo-700 hover:shadow-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 group"
                            data-next="tanggungan">
                            Seterusnya
                            <i class="fas fa-arrow-right transition-transform group-hover:translate-x-0.5"></i>
                        </button>
                    </div>
                </div>


                <!-- Tab 2: Tanggungan -->
                <div id="tanggungan" class="tab-content">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-bold text-gray-800 flex items-center gap-2">
                            <i class="fas fa-users text-djariah-600"></i>
                            Maklumat Tanggungan
                            <span id="tanggunganCount" class="text-sm font-normal text-gray-500 ml-2">(0)</span>
                        </h3>
                        <button type="button" id="addTanggunganBtn"
                            class="bg-djariah-600 hover:bg-djariah-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors flex items-center gap-2">
                            <i class="fas fa-plus-circle"></i>
                            Tambah Tanggungan
                        </button>
                    </div>


                    <div class="mb-4 p-4 bg-gray-50 rounded-lg border">
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="checkbox" id="noTanggunganCheckbox" onchange="toggleNoTanggungan(this)">
                            <span class="text-sm font-medium">Saya mendaftar seorang diri (tiada
                                isteri/suami/anak)</span>
                        </label>
                    </div>

                    <!-- Container untuk kad tanggungan -->
                    <div id="tanggunganContainer">
                        <!-- Tanggungan pertama akan ditambah secara automatik -->
                    </div>

                    <!-- Butang tambah di bahagian bawah -->
                    <div class="mt-4 text-center">
                        <button type="button" id="addTanggunganBottomBtn"
                            class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-6 py-3 rounded-lg text-sm font-medium transition-colors flex items-center gap-2 mx-auto border-2 border-dashed border-gray-300 hover:border-djariah-400">
                            <i class="fas fa-plus-circle text-djariah-600"></i>
                            Tambah Tanggungan Lain
                        </button>
                    </div>

                    <div class="flex flex-col sm:flex-row justify-between gap-3 mt-6 pt-4 border-t border-gray-200">
                        <button type="button"
                            class="prev-tab w-full sm:w-auto justify-center bg-gray-200 hover:bg-gray-300 text-gray-700 py-2.5 px-6 rounded-lg font-medium transition-colors flex items-center gap-2"
                            data-prev="pemohon">
                            <i class="fas fa-arrow-left"></i> Sebelumnya
                        </button>

                        <button type="button"
                            class="next-tab flex items-center gap-2 rounded-lg bg-indigo-600 px-5 py-2.5 text-sm font-semibold text-white shadow-md transition-all hover:bg-indigo-700 hover:shadow-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 group"
                            data-next="login">
                            Seterusnya
                            <i class="fas fa-arrow-right transition-transform group-hover:translate-x-0.5"></i>
                        </button>
                    </div>
                </div>

                <!-- Tab 3: Login & Maklumat Akaun -->
                <div id="login" class="tab-content">
                    <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center gap-2">
                        <i class="fas fa-key text-djariah-600"></i> Maklumat Log Masuk
                    </h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                        <div class="space-y-2">
                            <label class="block text-sm font-medium text-gray-700">
                                Alamat E-mel
                            </label>
                            <input type="email" name="email" value="{{ old('email') }}"
                                class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-djariah-500 focus:border-djariah-500 transition-all duration-200"
                                placeholder="nama@contoh.com" required>
                        </div>
                    </div>
                    <input type="hidden" name="status" value="active">

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                        <div class="space-y-2">
                            <label class="block text-sm font-medium text-gray-700">
                                Kata Laluan
                            </label>
                            <input type="password" name="password"
                                class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-djariah-500 focus:border-djariah-500 transition-all duration-200"
                                placeholder="Minimum 6 aksara" required>
                        </div>
                        <div class="space-y-2">
                            <label class="block text-sm font-medium text-gray-700">
                                Sahkan Kata Laluan
                            </label>
                            <input type="password" name="password_confirmation"
                                class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-djariah-500 focus:border-djariah-500 transition-all duration-200"
                                placeholder="Masukkan semula kata laluan" required>
                        </div>
                    </div>

                    <!-- ========== MAKLUMAT PEMBAYARAN ========== -->
                    <div class="border border-gray-200 rounded-xl p-4 bg-gray-50 mb-4">
                        <h4 class="font-bold text-gray-800 mb-3 flex items-center gap-2">
                            <i class="fas fa-money-bill-wave text-djariah-600"></i>
                            Maklumat Pembayaran
                        </h4>


                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <!-- Yuran Pendaftaran -->
                            <div class="bg-white rounded-lg p-3 border border-gray-200">
                                <p class="text-xs text-gray-500">Yuran Pendaftaran</p>
                                <p id="yuran_pendaftaran_display" class="text-lg font-bold text-djariah-600">RM 0.00
                                </p>
                            </div>

                            <!-- Bayaran Tahunan -->
                            <div class="bg-white rounded-lg p-3 border border-gray-200">
                                <p class="text-xs text-gray-500">Bayaran Tahunan</p>
                                <p id="bayaran_tahunan_display" class="text-lg font-bold text-djariah-600">RM 0.00</p>
                            </div>

                            <!-- Jumlah Keseluruhan -->
                            <div class="bg-white rounded-lg p-3 border-2 border-djariah-200 md:col-span-2">
                                <p class="text-xs text-gray-500">Jumlah Keseluruhan</p>
                                <p id="jumlah_bayaran_display" class="text-2xl font-extrabold text-djariah-700">RM
                                    0.00</p>
                            </div>
                        </div>

                        <div class="mt-4 text-xs text-gray-500 flex items-center gap-2">
                            <i class="fas fa-exclamation-circle text-yellow-500"></i>
                            <span>Jumlah bayaran akan dikemaskini mengikut ketetapan pihak pengurusan masing-masing dan tertakluk dalam terma dan syarat khairat.</span>
                        </div>
                    </div>

                    <!-- Terma dan Syarat -->
                    <div class="border border-gray-200 rounded-lg p-4 bg-gray-50 mb-4">
                        <h4 class="font-bold text-gray-800 mb-2">Terma dan Syarat Keahlian</h4>
                        <div class="text-xs text-gray-600 space-y-1">
                            <a id="termsLink" href="#" target="_blank" rel="noopener"
                                class="text-djariah-600 hover:text-djariah-700 font-semibold underline inline-flex items-center gap-1 pointer-events-none opacity-50"
                                onclick="return this.dataset.masjidId ? true : false;">
                                <i class="fas fa-file-alt"></i> Klik Untuk Baca Terma & Syarat
                            </a>
                            <p id="termsLinkHint" class="text-gray-400 italic mt-1">
                                Sila pilih masjid/surau di Bahagian Pemohon terlebih dahulu.
                            </p>
                        </div>
                        <div class="mt-4 pt-3 border-t border-gray-200 flex items-center">
                            <input type="checkbox" id="agree_terms" name="agree_terms" required
                                class="w-4 h-4 text-djariah-600 border-gray-300 rounded focus:ring-djariah-500">
                            <label for="agree_terms" class="ml-2 text-sm text-gray-700 font-medium">
                                Saya bersetuju dengan terma dan syarat di atas
                            </label>
                        </div>
                    </div>

                    <div class="flex flex-col sm:flex-row justify-between gap-3 mt-6">
                        <button type="button"
                            class="prev-tab w-full sm:w-auto justify-center bg-gray-200 hover:bg-gray-300 text-gray-700 py-2.5 px-6 rounded-lg font-medium transition-colors flex items-center gap-2"
                            data-prev="tanggungan">
                            <i class="fas fa-arrow-left"></i> Sebelumnya
                        </button>

                        <button type="submit"
                            class="w-full sm:w-auto justify-center bg-emerald-600 hover:bg-emerald-700 text-white py-2.5 px-6 rounded-lg font-medium inline-flex items-center gap-2">
                            <i class="fas fa-paper-plane"></i> Hantar Permohonan
                        </button>
                    </div>
                </div>



            </form>

            @if ($errors->any())
                <div class="bg-red-100 text-red-800 p-3 rounded mx-6 mb-6">
                    <ul class="text-sm">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
        </div>
    </div>

    <!-- Zoom Modal -->
    <div id="zoomModal" class="fixed inset-0 bg-black bg-opacity-70 hidden z-50 flex items-center justify-center p-4">
        <button class="absolute top-4 right-5 text-white text-3xl font-bold hover:text-gray-300"
            onclick="closeZoom()">&times;</button>
        <img id="zoomImage" class="max-w-full max-h-full rounded-xl shadow-lg object-contain">
    </div>

    @if (session('success'))
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script>
            Swal.fire({
                title: "Berjaya!",
                text: "{{ session('success') }}",
                icon: "success",
                timer: 5000,
                showConfirmButton: false
            }).then(() => {
                window.location.href = "{{ route('login') }}";
            });
        </script>
    @endif

    <!-- JavaScript -->
    <script>
        // ── Stepper order ────────────────────────────────────────────────
        const TAB_ORDER = ['pemohon', 'tanggungan', 'login'];

        function switchTab(tabId) {
            const currentIdx = TAB_ORDER.indexOf(tabId);

            document.querySelectorAll('.step-item').forEach((item, idx) => {
                item.classList.remove('active', 'done');
                if (idx < currentIdx) item.classList.add('done');
                else if (idx === currentIdx) item.classList.add('active');
            });

            document.querySelectorAll('.tab-content').forEach(c => c.classList.remove('active'));
            document.getElementById(tabId).classList.add('active');
        }


        // ── Tanggungan Functions ──────────────────────────────────────────
        let tanggunganCount = 0;
        const MAX_TANGGUNGAN = 20; // Had maksimum untuk mengelakkan overload (boleh diubah)

        // Pilihan hubungan
        const RELATIONSHIP_OPTIONS = [
            'SUAMI',
            'ISTERI',
            'ANAK',
            'IBU',
            'AYAH',
            'MERTUA',
            'LAIN'
        ];

        function parseMalaysiaIC(icNumber) {
            let cleaned = icNumber.replace(/\D/g, '');
            if (cleaned.length < 6) return null;

            let yearPart = cleaned.substring(0, 2);
            let monthPart = cleaned.substring(2, 4);
            let dayPart = cleaned.substring(4, 6);

            if (!monthPart || !dayPart) return null;

            let century = parseInt(yearPart, 10);
            let fullYear = (century >= 0 && century <= 29) ? 2000 + century : 1900 + century;
            let birthDate = new Date(fullYear, parseInt(monthPart, 10) - 1, parseInt(dayPart, 10));

            if (birthDate.getFullYear() !== fullYear ||
                birthDate.getMonth() !== parseInt(monthPart, 10) - 1 ||
                birthDate.getDate() !== parseInt(dayPart, 10)) {
                return null;
            }
            return birthDate;
        }

        function formatDateToInput(dateObj) {
            let y = dateObj.getFullYear();
            let m = String(dateObj.getMonth() + 1).padStart(2, '0');
            let d = String(dateObj.getDate()).padStart(2, '0');
            return `${y}-${m}-${d}`;
        }

        function calculateAge(birthDate) {
            let today = new Date();
            return today.getFullYear() - birthDate.getFullYear();
        }

        function formatDateToDMY(dateObj) {
            if (!dateObj || isNaN(dateObj.getTime())) return '';

            let d = String(dateObj.getDate()).padStart(2, '0');
            let m = String(dateObj.getMonth() + 1).padStart(2, '0');
            let y = dateObj.getFullYear();

            return `${d}/${m}/${y}`;
        }

        function updateTanggunganDobAndAge(icInput) {
            const index = icInput.dataset.index;
            const card = icInput.closest('.tanggungan-card');
            const tarikhInput = card.querySelector('[name$="[tarikh_lahir]"]');
            const tarikhDisplay = document.getElementById(`tarikh_lahir_display_${index}`);
            if (!tarikhInput) return;

            let icValue = icInput.value;
            if (!icValue || icValue.length === 0) {
                tarikhInput.value = '';
                if (tarikhDisplay) tarikhDisplay.value = '';
                return;
            }

            let birthDate = parseMalaysiaIC(icValue);
            if (birthDate) {
                tarikhInput.value = formatDateToInput(birthDate);
                if (tarikhDisplay) tarikhDisplay.value = formatDateToDMY(birthDate);
                console.log(`✅ Tanggungan #${index + 1} - Tarikh lahir set to:`, tarikhInput.value);
            } else {
                tarikhInput.value = '';
                if (tarikhDisplay) tarikhDisplay.value = '';
                console.log(`⚠️ Tanggungan #${index + 1} - Invalid IC format`);
            }
        }


        function createTanggunganCard(index) {
            const card = document.createElement('div');
            card.className = 'tanggungan-card';
            card.dataset.index = index;
            card.id = `tanggungan_${index}`;

            const num = index + 1;

            card.innerHTML = `
        <div class="card-header">
            <span class="badge">Tanggungan</span>
            <span class="card-number-badge">
                <i class="fas fa-user"></i>
                #${num}
            </span>
            <span class="text-xs text-gray-400 ml-auto">ID: T-${String(index + 1).padStart(3, '0')}</span>
            ${index > 0 ? `<button type="button" class="remove-btn" onclick="removeTanggungan(${index})" title="Padam tanggungan">
                                                                        <i class="fas fa-times"></i>
                                                                    </button>` : 
            `<button type="button" class="remove-btn" onclick="removeTanggungan(${index})" title="Padam tanggungan" style="display:none;">
                                                                        <i class="fas fa-times"></i>
                                                                    </button>`}
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div class="space-y-2">
                <label class="block text-sm font-medium text-gray-700">
                    Nama Penuh <span class="text-red-500">*</span>
                    <span class="text-xs text-gray-400 ml-1">(Seperti Dalam MYKAD)</span>
                </label>
                <input type="text" name="tanggungan[${index}][nama]" 
                    class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-djariah-500 focus:border-djariah-500 transition-all uppercase"
                    placeholder="MASUKKAN NAMA PENUH" required
                    oninput="this.value = this.value.toUpperCase()">
            </div>
            <div class="space-y-2">
                <label class="block text-sm font-medium text-gray-700">
                    No. Kad Pengenalan <span class="text-red-500">*</span>
                    <span class="text-xs text-gray-400 ml-1">(Auto isi tarikh lahir)</span>
                </label>
                <input type="text" name="tanggungan[${index}][ic]" 
                    class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-djariah-500 focus:border-djariah-500 transition-all tanggungan-ic"
                    placeholder="010413-03-1234" maxlength="14" required
                    data-index="${index}">
                <div id="tanggungan_ic_status_${index}" class="hidden text-xs mt-1 flex items-center gap-2"></div>
            </div>
            <div class="space-y-2">
                <label class="block text-sm font-medium text-gray-700">
                    Tarikh Lahir <span class="text-red-500">*</span>
                    <span class="text-xs text-gray-400 ml-1">(Auto isi dari IC)</span>
                </label>
                <input type="text" id="tarikh_lahir_display_${index}"
                    class="w-full border border-gray-300 rounded-lg px-4 py-2.5 transition-all bg-gray-50"
                    readonly
                    placeholder="dd/mm/yyyy (Auto isi dari IC)">
                <input type="hidden" name="tanggungan[${index}][tarikh_lahir]" 
                    required>
            </div>
            <div class="space-y-2">
                <label class="block text-sm font-medium text-gray-700">
                    Hubungan <span class="text-red-500">*</span>
                </label>
                <select name="tanggungan[${index}][hubungan]" 
                    class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-djariah-500 focus:border-djariah-500 transition-all"
                    required>
                    <option value="">-- Pilih Hubungan --</option>
                    ${RELATIONSHIP_OPTIONS.map(opt => `<option value="${opt}">${opt}</option>`).join('')}
                </select>
            </div>
            <div class="space-y-2">
                <label class="block text-sm font-medium text-gray-700">
                    Jantina
                </label>
                <select name="tanggungan[${index}][jantina]" 
                    class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-djariah-500 focus:border-djariah-500 transition-all">
                    <option value="">-- Pilih Jantina --</option>
                    <option value="LELAKI">LELAKI</option>
                    <option value="PEREMPUAN">PEREMPUAN</option>
                    <option value="LAIN">LAIN</option>
                </select>
            </div>
            <div class="space-y-2">
                <label class="block text-sm font-medium text-gray-700">
                    OKU?
                </label>
                <select name="tanggungan[${index}][oku]" 
                    class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-djariah-500 focus:border-djariah-500 transition-all">
                    <option value="0">Tidak</option>
                    <option value="1">Ya</option>
                </select>
            </div>
            <div class="space-y-2 md:col-span-2">
                <label class="block text-sm font-medium text-gray-700">
                    Muat Naik Dokumen Sokongan
                    <span class="text-xs text-gray-400 ml-1">(Sijil Lahir / Kad Pengenalan)</span>
                </label>
                <div class="bg-yellow-50 border border-yellow-200 rounded-lg px-3 py-2 text-xs text-yellow-800 flex items-center gap-2 mb-2">
                    <i class="fas fa-exclamation-circle"></i>
                    Had saiz fail: <strong>5MB</strong>. Format dibenarkan: JPG, PNG, PDF sahaja.
                </div>
                <input type="file" name="tanggungan[${index}][dokumen]" 
                    accept="image/jpeg,image/jpg,image/png,.pdf"
                    class="block w-full text-sm file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:bg-djariah-600 file:text-white hover:file:bg-djariah-700 tanggungan-file"
                    onchange="previewTanggunganFile(this, ${index})"
                    data-index="${index}">
                <div id="tanggungan_file_info_${index}" class="hidden text-xs mt-1 flex items-center gap-2"></div>
                <div id="tanggungan_preview_${index}" class="hidden mt-2">
                    <p class="text-xs text-gray-500 mb-1">Preview:</p>
                    <img id="tanggungan_image_${index}" class="file-preview" onclick="openZoom(this)">
                </div>
            </div>
        </div>
    `;

            return card;
        }

        function addTanggungan() {
            // Semak had maksimum
            if (tanggunganCount >= MAX_TANGGUNGAN) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Had Maksimum',
                    text: `Anda hanya boleh menambah sehingga ${MAX_TANGGUNGAN} tanggungan.`,
                    confirmButtonColor: '#ea580c'
                });
                return;
            }

            const container = document.getElementById('tanggunganContainer');
            const index = tanggunganCount;
            const card = createTanggunganCard(index);
            container.appendChild(card);
            tanggunganCount++;

            // ✅ AUTO-FILL TARIKH LAHIR DARI IC
            const icInput = card.querySelector('.tanggungan-ic');
            if (icInput) {
                // Auto-format IC
                icInput.addEventListener('input', function(e) {
                    let pos = this.selectionStart;
                    let before = this.value;
                    this.value = formatIC(this.value);
                    let added = this.value.length - before.length;
                    if (added > 0) pos += added;
                    try {
                        this.setSelectionRange(pos, pos);
                    } catch (e) {}

                    // ✅ Auto fill tarikh lahir
                    updateTanggunganDobAndAge(this);

                    // Validate IC format
                    validateTanggunganIC(this);
                });

                // If IC already has value, auto fill tarikh lahir
                if (icInput.value) {
                    icInput.value = formatIC(icInput.value);
                    updateTanggunganDobAndAge(icInput);
                    validateTanggunganIC(icInput);
                }
            }

            // Kemas kini kiraan
            updateTanggunganCount();

            // Skrol ke kad baru
            setTimeout(() => {
                card.scrollIntoView({
                    behavior: 'smooth',
                    block: 'center'
                });
            }, 100);

            // Tunjukkan butang padam untuk semua kecuali yang pertama
            updateRemoveButtons();
        }

        function removeTanggungan(index) {
            const card = document.getElementById(`tanggungan_${index}`);
            if (!card) return;

            // Semak sama ada ini adalah satu-satunya tanggungan
            const totalCards = document.querySelectorAll('.tanggungan-card').length;
            if (totalCards <= 1) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Tanggungan Minimum',
                    text: 'Anda perlu mempunyai sekurang-kurangnya SATU tanggungan.',
                    confirmButtonColor: '#ea580c'
                });
                return;
            }

            // Papar pengesahan sebelum memadam
            Swal.fire({
                title: 'Padam Tanggungan?',
                text: 'Anda pasti mahu memadam tanggungan ini? Tindakan ini tidak boleh dibatalkan.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc2626',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Ya, Padam',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    card.remove();
                    tanggunganCount--;
                    reindexTanggungan();
                    updateTanggunganCount();
                    updateRemoveButtons();

                    Swal.fire({
                        icon: 'success',
                        title: 'Tanggungan Dipadam',
                        timer: 1500,
                        showConfirmButton: false
                    });
                }
            });
        }

        function reindexTanggungan() {
            const cards = document.querySelectorAll('.tanggungan-card');
            cards.forEach((card, idx) => {
                // Kemas kini ID
                card.id = `tanggungan_${idx}`;
                card.dataset.index = idx;

                // Kemas kini badge
                const badge = card.querySelector('.badge');
                if (badge) {
                    badge.textContent = 'Tanggungan';
                }

                // Kemas kini nombor
                const numberBadge = card.querySelector('.card-number-badge');
                if (numberBadge) {
                    numberBadge.innerHTML = `<i class="fas fa-user"></i> #${idx + 1}`;
                }

                // Kemas kini ID label
                const idLabel = card.querySelector('.card-header .text-gray-400');
                if (idLabel) {
                    idLabel.textContent = `ID: T-${String(idx + 1).padStart(3, '0')}`;
                }

                // Kemas kini atribut name untuk semua input dan select
                const inputs = card.querySelectorAll('input, select');
                inputs.forEach(input => {
                    const name = input.getAttribute('name');
                    if (name) {
                        const newName = name.replace(/tanggungan\[\d+\]/, `tanggungan[${idx}]`);
                        input.setAttribute('name', newName);
                    }
                    // Kemas kini data-index
                    if (input.dataset.index !== undefined) {
                        input.dataset.index = idx;
                    }
                });

                // Kemas kini ID untuk preview dan info
                const previewDiv = card.querySelector(`[id^="tanggungan_preview_"]`);
                if (previewDiv) {
                    previewDiv.id = `tanggungan_preview_${idx}`;
                    const img = previewDiv.querySelector('img');
                    if (img) img.id = `tanggungan_image_${idx}`;
                }

                const infoDiv = card.querySelector(`[id^="tanggungan_file_info_"]`);
                if (infoDiv) {
                    infoDiv.id = `tanggungan_file_info_${idx}`;
                }

                const statusDiv = card.querySelector(`[id^="tanggungan_ic_status_"]`);
                if (statusDiv) {
                    statusDiv.id = `tanggungan_ic_status_${idx}`;
                }

                // Kemas kini event listener untuk remove button
                const removeBtn = card.querySelector('.remove-btn');
                if (removeBtn) {
                    removeBtn.onclick = function() {
                        removeTanggungan(idx);
                    };
                    // Sembunyikan butang padam untuk yang pertama
                    if (idx === 0) {
                        removeBtn.style.display = 'none';
                    } else {
                        removeBtn.style.display = 'flex';
                    }
                }
            });
        }

        function updateTanggunganCount() {
            const count = document.querySelectorAll('.tanggungan-card').length;
            const countDisplay = document.getElementById('tanggunganCount');
            if (countDisplay) {
                countDisplay.textContent = `(${count})`;
            }
        }

        function updateRemoveButtons() {
            const cards = document.querySelectorAll('.tanggungan-card');
            cards.forEach((card, idx) => {
                const removeBtn = card.querySelector('.remove-btn');
                if (removeBtn) {
                    if (idx === 0 && cards.length > 1) {
                        // Sembunyikan butang padam untuk yang pertama jika ada lebih dari satu
                        removeBtn.style.display = 'none';
                    } else if (idx === 0 && cards.length === 1) {
                        // Sembunyikan butang padam jika hanya satu
                        removeBtn.style.display = 'none';
                    } else {
                        removeBtn.style.display = 'flex';
                    }
                }
            });
        }

        function validateTanggunganIC(input) {
            const index = input.dataset.index;
            const statusEl = document.getElementById(`tanggungan_ic_status_${index}`);
            if (!statusEl) return;

            const value = input.value;
            const cleaned = value.replace(/\D/g, '');

            if (cleaned.length === 0) {
                statusEl.classList.add('hidden');
                return;
            }

            if (cleaned.length < 12) {
                statusEl.classList.remove('hidden');
                statusEl.innerHTML =
                    `<span class="text-yellow-600"><i class="fas fa-exclamation-circle"></i> ${12 - cleaned.length} digit lagi</span>`;
                return;
            }

            if (!/^\d{6}-\d{2}-\d{4}$/.test(value)) {
                statusEl.classList.remove('hidden');
                statusEl.innerHTML =
                    `<span class="text-red-600"><i class="fas fa-times-circle"></i> Format IC tidak sah</span>`;
                return;
            }

            statusEl.classList.remove('hidden');
            statusEl.innerHTML = `<span class="text-green-600"><i class="fas fa-check-circle"></i> Format IC sah</span>`;
        }

        function formatIC(raw) {
            let d = raw.replace(/\D/g, '').slice(0, 12);
            if (d.length > 8) return d.slice(0, 6) + '-' + d.slice(6, 8) + '-' + d.slice(8);
            if (d.length > 6) return d.slice(0, 6) + '-' + d.slice(6);
            return d;
        }

        function formatFileSize(bytes) {
            if (bytes < 1024) return bytes + ' B';
            if (bytes < 1024 * 1024) return (bytes / 1024).toFixed(1) + ' KB';
            return (bytes / (1024 * 1024)).toFixed(2) + ' MB';
        }

        function previewTanggunganFile(input, index) {
            const file = input.files[0];
            if (!file) return;

            const MAX_FILE_BYTES = 5 * 1024 * 1024;
            const infoEl = document.getElementById(`tanggungan_file_info_${index}`);
            const previewEl = document.getElementById(`tanggungan_preview_${index}`);
            const imgEl = document.getElementById(`tanggungan_image_${index}`);

            if (file.size > MAX_FILE_BYTES) {
                if (infoEl) {
                    infoEl.classList.remove('hidden');
                    infoEl.innerHTML = `
                <i class="fas fa-times-circle text-red-500"></i>
                <span class="text-red-600">Saiz fail: <strong>${formatFileSize(file.size)}</strong> — melebihi had 5MB.</span>
            `;
                }
                Swal.fire({
                    icon: 'error',
                    title: 'Fail Terlalu Besar',
                    html: `Saiz fail yang dipilih ialah <strong>${formatFileSize(file.size)}</strong>.<br>Had maksimum ialah <strong>5MB</strong>.`,
                    confirmButtonColor: '#ea580c',
                });
                input.value = '';
                if (previewEl) previewEl.classList.add('hidden');
                return;
            }

            if (infoEl) {
                infoEl.classList.remove('hidden');
                infoEl.innerHTML = `
            <i class="fas fa-check-circle text-green-500"></i>
            <span class="text-green-600">Saiz fail: <strong>${formatFileSize(file.size)}</strong> — OK</span>
        `;
            }

            if (file.type.startsWith('image/') && previewEl && imgEl) {
                imgEl.src = URL.createObjectURL(file);
                previewEl.classList.remove('hidden');
            } else {
                if (previewEl) previewEl.classList.add('hidden');
            }
        }

        function getTanggunganData() {
            const cards = document.querySelectorAll('.tanggungan-card');
            const data = [];

            cards.forEach((card, idx) => {
                const nama = card.querySelector(`input[name="tanggungan[${idx}][nama]"]`)?.value || '';
                const ic = card.querySelector(`input[name="tanggungan[${idx}][ic]"]`)?.value || '';
                const tarikhLahir = card.querySelector(`input[name="tanggungan[${idx}][tarikh_lahir]"]`)?.value ||
                    '';
                const hubungan = card.querySelector(`select[name="tanggungan[${idx}][hubungan]"]`)?.value || '';
                const fileInput = card.querySelector(`input[name="tanggungan[${idx}][dokumen]"]`);

                const hasFile = fileInput && fileInput.files && fileInput.files.length > 0;

                data.push({
                    nama,
                    ic,
                    tarikh_lahir: tarikhLahir,
                    hubungan,
                    hasFile,
                    fileName: hasFile ? fileInput.files[0].name : null
                });
            });

            return data;
        }

        // ── Inisialisasi Tanggungan ────────────────────────────────────────
        document.addEventListener('DOMContentLoaded', function() {
            // Tambah tanggungan pertama secara automatik
            addTanggungan();

            // Event listener untuk butang tambah
            document.getElementById('addTanggunganBtn').addEventListener('click', addTanggungan);
            document.getElementById('addTanggunganBottomBtn').addEventListener('click', addTanggungan);

            // Expose fungsi ke global
            window.addTanggungan = addTanggungan;
            window.removeTanggungan = removeTanggungan;
            window.previewTanggunganFile = previewTanggunganFile;
            window.formatIC = formatIC;
            window.formatFileSize = formatFileSize;
            window.getTanggunganData = getTanggunganData;
            window.validateTanggunganIC = validateTanggunganIC;
        });

        // ── Tab Validation ───────────────────────────────────────────────
        function showError(input, msg) {
            clearError(input);
            input.classList.add('border-red-500', 'ring-2', 'ring-red-300');
            const err = document.createElement('p');
            err.className = 'tab-error text-xs text-red-600 mt-1';
            err.textContent = msg;
            input.parentNode.appendChild(err);
        }

        function clearError(input) {
            input.classList.remove('border-red-500', 'ring-2', 'ring-red-300');
            input.parentNode.querySelectorAll('.tab-error').forEach(e => e.remove());
        }

        function validateTab(tabId) {
            const tab = document.getElementById(tabId);
            let valid = true;
            const errorMessages = [];

            tab.querySelectorAll('.tab-error').forEach(e => e.remove());
            tab.querySelectorAll('.border-red-500').forEach(el => {
                el.classList.remove('border-red-500', 'ring-2', 'ring-red-300');
            });

            // Validate all required inputs, selects, textareas
            tab.querySelectorAll('[required]').forEach(field => {
                if (field.type === 'checkbox') {
                    if (!field.checked) {
                        showError(field, 'Sila tandakan kotak ini.');
                        const label = tab.querySelector(`label[for="${field.id}"]`);
                        errorMessages.push(label ? label.textContent.trim() + ' - wajib diisi.' :
                            'Sila tandakan persetujuan terma dan syarat.');
                        valid = false;
                    }
                } else if (!field.value.trim()) {
                    showError(field, 'Sila isi medan ini.');
                    const label = field.closest('.space-y-2')?.querySelector('label');
                    errorMessages.push((label ? label.textContent.trim() : field.name) + ' - wajib diisi.');
                    valid = false;
                }
            });

            // IC format validation (pemohon)
            if (tabId === 'pemohon') {
                const ic = document.getElementById('ic_number');
                if (ic && ic.value && !/^\d{6}-\d{2}-\d{4}$/.test(ic.value)) {
                    showError(ic, 'Format IC tidak sah. Contoh: 010413-03-1234');
                    errorMessages.push('Format No. Kad Pengenalan tidak sah.');
                    valid = false;
                }
                const umur = document.getElementById('umur');
                if (umur && umur.value && parseInt(umur.value) < 24) {
                    showError(ic, 'Pendaftaran hanya dibenarkan untuk umur 24 tahun ke atas.');
                    errorMessages.push('Umur pemohon mesti 24 tahun ke atas.');
                    valid = false;
                }
            }

            // Tanggungan validation
            if (tabId === 'tanggungan') {
                const noTanggungan = document.getElementById('noTanggunganCheckbox')?.checked;
                if (!noTanggungan) {
                    const cards = document.querySelectorAll('.tanggungan-card');
                    if (cards.length === 0) {
                        errorMessages.push(
                            'Sila tambah sekurang-kurangnya satu tanggungan, atau tandakan "Tiada Tanggungan".');
                        valid = false;
                    }
                    cards.forEach((card, idx) => {
                        const nama = card.querySelector(`input[name="tanggungan[${idx}][nama]"]`);
                        const ic = card.querySelector(`input[name="tanggungan[${idx}][ic]"]`);
                        const tarikh = card.querySelector(`input[name="tanggungan[${idx}][tarikh_lahir]"]`);
                        const hubungan = card.querySelector(`select[name="tanggungan[${idx}][hubungan]"]`);

                        if (nama && !nama.value.trim()) {
                            showError(nama, 'Nama tanggungan wajib diisi.');
                            errorMessages.push(`Tanggungan #${idx + 1}: Nama wajib diisi.`);
                            valid = false;
                        }
                        if (ic && ic.value && !/^\d{6}-\d{2}-\d{4}$/.test(ic.value)) {
                            showError(ic, 'Format IC tidak sah. Contoh: 010413-03-1234');
                            errorMessages.push(`Tanggungan #${idx + 1}: Format IC tidak sah.`);
                            valid = false;
                        }
                        if (tarikh && !tarikh.value) {
                            showError(tarikh, 'Tarikh lahir wajib diisi.');
                            errorMessages.push(`Tanggungan #${idx + 1}: Tarikh lahir wajib diisi.`);
                            valid = false;
                        }
                        if (hubungan && !hubungan.value) {
                            showError(hubungan, 'Sila pilih hubungan.');
                            errorMessages.push(`Tanggungan #${idx + 1}: Hubungan wajib dipilih.`);
                            valid = false;
                        }
                    });
                }
            }

            // Password confirmation check
            if (tabId === 'login') {
                const pw = tab.querySelector('[name="password"]');
                const pwc = tab.querySelector('[name="password_confirmation"]');
                if (pw && pwc && pw.value && pwc.value && pw.value !== pwc.value) {
                    showError(pwc, 'Kata laluan tidak sepadan.');
                    errorMessages.push('Kata laluan dan pengesahan tidak sepadan.');
                    valid = false;
                }
                const terms = tab.querySelector('#agree_terms');
                if (terms && !terms.checked) {
                    showError(terms, 'Sila bersetuju dengan terma dan syarat.');
                    errorMessages.push('Sila bersetuju dengan Terma dan Syarat sebelum meneruskan.');
                    valid = false;
                }
            }

            if (!valid) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Sila Semak Maklumat',
                    html: '<ul class="text-left text-sm list-disc list-inside space-y-1 text-red-700">' +
                        errorMessages.map(m => `<li>${m}</li>`).join('') +
                        '</ul>',
                    confirmButtonText: 'OK, Semak Semula',
                    confirmButtonColor: '#ea580c',
                });
                const firstErr = tab.querySelector('.border-red-500');
                if (firstErr) firstErr.scrollIntoView({
                    behavior: 'smooth',
                    block: 'center'
                });
            }

            return valid;
        }

        // ── IC duplicate check via AJAX ──────────────────────────────────
        let icCheckTimer = null;
        let icIsValid = false;

        function setIcStatus(type, message) {
            const el = document.getElementById('ic_check_status');
            el.classList.remove('hidden');
            if (type === 'loading') {
                el.innerHTML =
                    `<span class="inline-block w-3 h-3 border-2 border-djariah-500 border-t-transparent rounded-full animate-spin"></span><span class="text-gray-500">Menyemak nombor IC...</span>`;
            } else if (type === 'ok') {
                el.innerHTML =
                    `<i class="fas fa-check-circle text-green-500"></i><span class="text-green-600">${message}</span>`;
            } else if (type === 'error') {
                el.innerHTML =
                    `<i class="fas fa-times-circle text-red-500"></i><span class="text-red-600">${message}</span>`;
            } else {
                el.classList.add('hidden');
            }
        }

        function checkIcDuplicate(icValue) {
            const cleaned = icValue.replace(/\D/g, '');
            if (cleaned.length !== 12) {
                setIcStatus('hide');
                icIsValid = false;
                return;
            }
            setIcStatus('loading');
            fetch('/user/check-ic?ic=' + encodeURIComponent(icValue), {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(res => res.json())
                .then(data => {
                    if (data.exists) {
                        setIcStatus('error', 'No. IC ini telah didaftarkan. Sila log masuk atau gunakan IC lain.');
                        icIsValid = false;
                        const icInput = document.getElementById('ic_number');
                        showError(icInput, 'No. IC ini telah didaftarkan dalam sistem.');
                    } else {
                        setIcStatus('ok', 'No. IC boleh digunakan.');
                        icIsValid = true;
                        const icInput = document.getElementById('ic_number');
                        clearError(icInput);
                    }
                })
                .catch(() => {
                    setIcStatus('hide');
                    icIsValid = true;
                });
        }

        // ── Next / Prev buttons ──────────────────────────────────────────
        document.querySelectorAll('.next-tab').forEach(button => {
            button.addEventListener('click', async () => {
                const currentTab = document.querySelector('.tab-content.active');
                if (!currentTab) return;

                // Extra IC duplicate check before leaving pemohon tab
                if (currentTab.id === 'pemohon') {
                    const icInput = document.getElementById('ic_number');
                    const cleaned = (icInput?.value || '').replace(/\D/g, '');
                    if (cleaned.length === 12 && !icIsValid) {
                        Swal.fire({
                            icon: 'error',
                            title: 'No. IC Tidak Sah',
                            text: 'No. Kad Pengenalan ini telah wujud dalam sistem atau belum disahkan. Sila semak semula.',
                            confirmButtonColor: '#ea580c',
                        });
                        return;
                    }
                }

                // For tanggungan tab, validate before proceeding
                if (currentTab.id === 'tanggungan') {
                    const noTanggungan = document.getElementById('noTanggunganCheckbox')?.checked;
                    const cards = document.querySelectorAll('.tanggungan-card');
                    if (!noTanggungan && cards.length === 0) {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Tiada Tanggungan',
                            text: 'Sila tambah sekurang-kurangnya satu tanggungan, atau tandakan "Tiada Tanggungan".',
                            confirmButtonColor: '#ea580c',
                        });
                        return;
                    }
                }

                if (!validateTab(currentTab.id)) return;
                switchTab(button.getAttribute('data-next'));
            });
        });

        document.querySelectorAll('.prev-tab').forEach(button => {
            button.addEventListener('click', () => {
                switchTab(button.getAttribute('data-prev'));
            });
        });

        // ── Negeri, Poskod, Bandar, Masjid ──────────────────────────────
        document.getElementById('negeri').addEventListener('change', function() {
            let negeriId = this.value;
            fetch('/user/get-poskod/' + negeriId)
                .then(res => res.json())
                .then(data => {
                    let poskod = document.getElementById('poskod');
                    poskod.innerHTML = '<option value="">-- Pilih Poskod --</option>';
                    data.forEach(p => {
                        poskod.innerHTML += `<option value="${p.poskod_num}">${p.poskod_num}</option>`;
                    });
                    document.getElementById('bandar').innerHTML =
                        '<option value="">-- Pilih Bandar --</option>';
                    document.getElementById('masjid').innerHTML =
                        '<option value="">-- Pilih Masjid --</option>';
                    resetTermsLink();
                });
        });

        document.getElementById('poskod').addEventListener('change', function() {
            let poskod = this.value;
            fetch('/user/get-bandar/' + poskod)
                .then(res => res.json())
                .then(data => {
                    let bandar = document.getElementById('bandar');
                    bandar.innerHTML = '<option value="">-- Pilih Bandar --</option>';
                    data.forEach(b => {
                        bandar.innerHTML +=
                            `<option value="${b.id}" data-bandar="${b.nama}">${b.nama}</option>`;
                    });
                    document.getElementById('masjid').innerHTML =
                        '<option value="">-- Pilih Masjid --</option>';
                    resetTermsLink();
                });
        });

        document.getElementById('bandar').addEventListener('change', function() {
            let poskodId = this.value;
            let bandarNama = encodeURIComponent(this.options[this.selectedIndex].dataset.bandar);
            fetch(`/user/get-masjid/${poskodId}/${bandarNama}`)
                .then(res => res.json())
                .then(data => {
                    let masjid = document.getElementById('masjid');
                    masjid.innerHTML = '<option value="">-- Pilih Masjid/Surau --</option>';
                    if (!data.length) {
                        masjid.innerHTML += '<option value="">Tiada masjid berdaftar</option>';
                        return;
                    }
                    data.forEach(m => {
                        masjid.innerHTML += `<option value="${m.id}">${m.nama}</option>`;
                    });
                    resetTermsLink();
                });
        });

        // ── IC Auto-fill for Pemohon ─────────────────────────────────────
        (function() {
            const icInput = document.getElementById('ic_number');
            const tarikhLahirInput = document.getElementById('tarikh_lahir');
            const tarikhLahirDisplay = document.getElementById('tarikh_lahir_display');
            const umurInput = document.getElementById('umur');

            if (!icInput || !tarikhLahirInput || !umurInput) return;

            function parseMalaysiaIC(icNumber) {
                let cleaned = icNumber.replace(/\D/g, '');
                if (cleaned.length < 6) return null;
                let yearPart = cleaned.substring(0, 2);
                let monthPart = cleaned.substring(2, 4);
                let dayPart = cleaned.substring(4, 6);
                if (!monthPart || !dayPart) return null;
                let century = parseInt(yearPart, 10);
                let fullYear = (century >= 0 && century <= 29) ? 2000 + century : 1900 + century;
                let birthDate = new Date(fullYear, parseInt(monthPart, 10) - 1, parseInt(dayPart, 10));
                if (birthDate.getFullYear() !== fullYear ||
                    birthDate.getMonth() !== parseInt(monthPart, 10) - 1 ||
                    birthDate.getDate() !== parseInt(dayPart, 10)) {
                    return null;
                }
                return birthDate;
            }

            function formatDateToInput(dateObj) {
                let y = dateObj.getFullYear();
                let m = String(dateObj.getMonth() + 1).padStart(2, '0');
                let d = String(dateObj.getDate()).padStart(2, '0');
                return `${y}-${m}-${d}`;
            }

            function calculateAge(birthDate) {
                let today = new Date();
                return today.getFullYear() - birthDate.getFullYear();
            }

            function updateDobAndAge() {
                let icValue = icInput.value;
                if (!icValue || icValue.length === 0) {
                    tarikhLahirInput.value = '';
                    if (tarikhLahirDisplay) tarikhLahirDisplay.value = '';
                    umurInput.value = '';
                    return;
                }
                let birthDate = parseMalaysiaIC(icValue);
                if (birthDate) {
                    tarikhLahirInput.value = formatDateToInput(birthDate);
                    if (tarikhLahirDisplay) tarikhLahirDisplay.value = formatDateToDMY(birthDate);
                    umurInput.value = calculateAge(birthDate);
                }
            }

            icInput.addEventListener('input', function(e) {
                let pos = this.selectionStart;
                let before = this.value;
                this.value = formatIC(this.value);
                let added = this.value.length - before.length;
                if (added > 0) pos += added;
                try {
                    this.setSelectionRange(pos, pos);
                } catch (e) {}
                updateDobAndAge();
                clearTimeout(icCheckTimer);
                icIsValid = false;
                const cleaned = this.value.replace(/\D/g, '');
                if (cleaned.length === 12) {
                    icCheckTimer = setTimeout(() => checkIcDuplicate(this.value), 600);
                } else {
                    setIcStatus('hide');
                }
            });

            if (icInput.value) {
                icInput.value = formatIC(icInput.value);
                updateDobAndAge();
                const cleaned = icInput.value.replace(/\D/g, '');
                if (cleaned.length === 12) checkIcDuplicate(icInput.value);
            }
        })();

        // ── Terma & Syarat link helpers ──────────────────────────────────
        function resetTermsLink() {
            let termsLink = document.getElementById('termsLink');
            let hint = document.getElementById('termsLinkHint');
            if (termsLink) {
                termsLink.href = '#';
                termsLink.classList.add('pointer-events-none', 'opacity-50');
                delete termsLink.dataset.masjidId;
            }
            if (hint) hint.style.display = '';
        }

        function updateTermsLink(masjidId) {
            let termsLink = document.getElementById('termsLink');
            let hint = document.getElementById('termsLinkHint');
            if (!termsLink) return;
            if (masjidId) {
                termsLink.href = `/policy/${masjidId}`;
                termsLink.dataset.masjidId = masjidId;
                termsLink.classList.remove('pointer-events-none', 'opacity-50');
                if (hint) hint.style.display = 'none';
            } else {
                resetTermsLink();
            }
        }

        // ── Masjid change - load bank info and harga ────────────────────
        document.getElementById('masjid').addEventListener('change', function() {
            let masjidId = this.value;
            let selectedOption = this.options[this.selectedIndex];
            let masjidName = selectedOption ? selectedOption.text : '';

            updateTermsLink(masjidId);

            if (!masjidId) {
                // Reset display
                document.getElementById('yuran_pendaftaran_display').innerText = 'RM 0.00';
                document.getElementById('bayaran_tahunan_display').innerText = 'RM 0.00';
                document.getElementById('jumlah_bayaran_display').innerText = 'RM 0.00';
                return;
            }

            let hiddenInput = document.querySelector('input[name="masjid_name"]');
            if (!hiddenInput) {
                hiddenInput = document.createElement('input');
                hiddenInput.type = 'hidden';
                hiddenInput.name = 'masjid_name';
                this.form.appendChild(hiddenInput);
            }
            hiddenInput.value = masjidName;

            loadBankInfo(masjidId);

            fetch('/get-hargakhairat/' + masjidId)
                .then(res => {
                    if (!res.ok) throw new Error('TIADA_HARGA');
                    return res.json();
                })
                .then(harga => {
                    let bayaranTahunan = parseFloat(harga.bayaran_tahunan ?? 0);
                    let yuranDaftar = parseFloat(harga.yuran_pendaftaran ?? 0);
                    let jumlah = bayaranTahunan + yuranDaftar;

                    // Update payment display (new IDs)
                    document.getElementById('bayaran_tahunan_display').innerText = 'RM ' + bayaranTahunan
                        .toFixed(2);
                    document.getElementById('yuran_pendaftaran_display').innerText = 'RM ' + yuranDaftar
                        .toFixed(2);
                    document.getElementById('jumlah_bayaran_display').innerText = 'RM ' + jumlah.toFixed(2);
                })
                .catch(() => {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Perhatian',
                        text: 'Harga khairat belum ditetapkan oleh masjid ini. Sila hubungi pentadbir.',
                        confirmButtonColor: '#ea580c'
                    });
                    // Reset to 0 if no price
                    document.getElementById('yuran_pendaftaran_display').innerText = 'RM 0.00';
                    document.getElementById('bayaran_tahunan_display').innerText = 'RM 0.00';
                    document.getElementById('jumlah_bayaran_display').innerText = 'RM 0.00';
                });
        });



        // ── File preview functions ──────────────────────────────────────
        const MAX_FILE_BYTES = 5 * 1024 * 1024;

        function showFileInfo(infoElId, file) {
            const el = document.getElementById(infoElId);
            if (!el || !file) return true;

            const size = file.size;
            const overLimit = size > MAX_FILE_BYTES;

            el.classList.remove('hidden');
            if (overLimit) {
                el.innerHTML =
                    `<i class="fas fa-times-circle text-red-500"></i>
                    <span class="text-red-600">Saiz fail: <strong>${formatFileSize(size)}</strong> — melebihi had 5MB. Sila pilih fail yang lebih kecil.</span>`;
                return false;
            } else {
                el.innerHTML = `<i class="fas fa-check-circle text-green-500"></i>
                    <span class="text-green-600">Saiz fail: <strong>${formatFileSize(size)}</strong> — OK</span>`;
                return true;
            }
        }

        function previewReceipt(event) {
            const file = event.target.files[0];
            if (!file) return;
            const sizeOk = showFileInfo('resit_file_info', file);
            if (!sizeOk) {
                Swal.fire({
                    icon: 'error',
                    title: 'Fail Terlalu Besar',
                    html: `Saiz fail yang dipilih ialah <strong>${formatFileSize(file.size)}</strong>.<br>Had maksimum ialah <strong>5MB</strong>.`,
                    confirmButtonColor: '#ea580c',
                });
                event.target.value = '';
                document.getElementById('receiptPreview').classList.add('hidden');
                return;
            }
            const preview = document.getElementById('receiptPreview');
            const img = document.getElementById('receiptImage');
            if (file.type.startsWith('image/')) {
                img.src = URL.createObjectURL(file);
                preview.classList.remove('hidden');
            } else {
                preview.classList.add('hidden');
            }
        }

        function openZoom(img) {
            const modal = document.getElementById('zoomModal');
            const zoomImg = document.getElementById('zoomImage');
            zoomImg.src = img.src;
            modal.classList.remove('hidden');
        }

        function closeZoom() {
            document.getElementById('zoomModal').classList.add('hidden');
        }

        document.getElementById('zoomModal').addEventListener('click', function(e) {
            if (e.target.id === 'zoomModal') closeZoom();
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

        // ── Combine Address ──────────────────────────────────────────────
        function combineAddressForSubmit() {
            const noRumah = document.getElementById('no_rumah')?.value.trim() || '';
            const jalan = document.getElementById('jalan')?.value.trim() || '';
            const taman = document.getElementById('taman')?.value.trim() || '';
            const poskod = document.getElementById('poskod_field')?.value.trim() || '';
            const bandar = document.getElementById('bandar_field')?.value.trim() || '';

            const parts = [];
            if (noRumah) parts.push(noRumah);
            if (jalan) parts.push(jalan);
            if (taman) parts.push(taman);
            if (poskod) parts.push(poskod);
            if (bandar) parts.push(bandar);

            const combinedAddress = parts.join(', ');

            // Set hidden input value
            const hiddenInput = document.getElementById('combined_address');
            if (hiddenInput) {
                hiddenInput.value = combinedAddress;
                console.log('✅ Combined Address set to:', combinedAddress);
            } else {
                console.error('❌ Hidden input "combined_address" not found!');
            }

            return combinedAddress;
        }

        function setupAddressUppercase() {
            const addressFields = ['no_rumah', 'jalan', 'taman', 'bandar_field'];
            addressFields.forEach(fieldId => {
                const field = document.getElementById(fieldId);
                if (field) {
                    field.addEventListener('input', function(e) {
                        this.value = this.value.toUpperCase();
                    });
                }
            });
        }

        // ── Form Submit ──────────────────────────────────────────────────
        const registrationForm = document.getElementById('registrationForm');
        if (registrationForm) {
            registrationForm.addEventListener('submit', function(e) {
                e.preventDefault();

                // 1. Convert nama to uppercase
                const namaField = this.querySelector('input[name="nama"]');
                if (namaField && namaField.value) {
                    namaField.value = namaField.value.toUpperCase();
                }

                // 2. Combine address FIRST
                const combinedAddress = combineAddressForSubmit();
                console.log('📝 Combined Address:', combinedAddress);

                // 3. Debug: Check if alamat is set in hidden input
                const alamatHidden = document.getElementById('combined_address');
                console.log('🔍 Hidden alamat value:', alamatHidden?.value);

                // 4. Collect tanggungan data
                const noTanggungan = document.getElementById('noTanggunganCheckbox')?.checked;
                const tanggunganData = getTanggunganData();
                if (!noTanggungan && tanggunganData.length === 0) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Tiada Tanggungan',
                        text: 'Sila tambah sekurang-kurangnya satu tanggungan, atau tandakan "Tiada Tanggungan".',
                        confirmButtonColor: '#ea580c'
                    });
                    return;
                }

                // 5. Validate tanggungan
                let hasError = false;
                tanggunganData.forEach((t, idx) => {
                    if (!t.nama || !t.ic || !t.tarikh_lahir || !t.hubungan) {
                        hasError = true;
                    }
                    if (t.ic && !/^\d{6}-\d{2}-\d{4}$/.test(t.ic)) {
                        hasError = true;
                    }
                });

                if (hasError) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Maklumat Tanggungan Tidak Lengkap',
                        text: 'Sila pastikan semua maklumat tanggungan diisi dengan lengkap dan sah.',
                        confirmButtonColor: '#ea580c'
                    });
                    return;
                }

                // 6. Store tanggungan data as JSON
                const tanggunganHidden = document.getElementById('tanggungan_data');
                if (tanggunganHidden) {
                    tanggunganHidden.value = noTanggungan ? '[]' : JSON.stringify(tanggunganData);
                }

                // 7. Create FormData
                const formData = new FormData(this);

                // 8. DEBUG: Log semua data yang akan dihantar
                console.log('=== 📤 FormData Debug ===');
                for (let pair of formData.entries()) {
                    console.log(pair[0] + ':', pair[1]);
                }
                console.log('=== End FormData Debug ===');

                // 9. Append tanggungan data as JSON (if not already in form)
                if (tanggunganHidden) {
                    formData.append('tanggungan_data', tanggunganHidden.value);
                }

                // 10. Append each tanggungan file
                const tanggunganFiles = document.querySelectorAll('.tanggungan-file');
                tanggunganFiles.forEach((input, idx) => {
                    if (input.files && input.files.length > 0) {
                        formData.append(`tanggungan[${idx}][dokumen]`, input.files[0]);
                    }
                });

                // 11. Show loading
                Swal.fire({
                    title: 'Memproses Pendaftaran...',
                    text: 'Sila tunggu sebentar.',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });

                // 12. Submit via AJAX
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
                            Swal.fire({
                                icon: 'success',
                                title: 'Permohonan Dihantar!',
                                html: `<p>${data.message}</p>
                        <p class="text-sm mt-3 text-blue-600">
                            <i class="fas fa-envelope"></i> Sila semak e-mel anda
                        </p>`,
                                confirmButtonColor: '#ea580c',
                                confirmButtonText: 'OK',
                                timer: 4000,
                                timerProgressBar: true
                            }).then(() => {
                                window.location.href = data.redirect_url;
                            });
                        } else {
                            let errorMessage = '';
                            if (data.errors) {
                                if (typeof data.errors === 'string') {
                                    errorMessage = data.errors;
                                } else if (typeof data.errors === 'object') {
                                    const errorList = Object.values(data.errors).flat();
                                    errorMessage = errorList.join('<br>');
                                }
                            } else if (data.message) {
                                errorMessage = data.message;
                            } else {
                                errorMessage = 'Ralat berlaku. Sila cuba lagi.';
                            }

                            Swal.fire({
                                icon: 'error',
                                title: 'Pendaftaran Gagal',
                                html: `<div class="text-left">
                        <p class="font-semibold text-red-600 mb-2">Sila semak maklumat berikut:</p>
                        <p class="text-sm">${errorMessage}</p>
                    </div>`,
                                confirmButtonColor: '#ea580c'
                            });
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        Swal.fire({
                            icon: 'error',
                            title: 'Ralat Sistem',
                            html: `<p>Terjadi masalah semasa memproses pendaftaran. Sila cuba lagi.</p>`,
                            confirmButtonColor: '#ea580c'
                        });
                    });
            });
        }

        // ── Initialize ──────────────────────────────────────────────────
        document.addEventListener('DOMContentLoaded', function() {


            // Setup address uppercase
            setupAddressUppercase();

            // Uppercase for nama and other fields
            const namaField = document.querySelector('input[name="nama"]');
            if (namaField) {
                namaField.addEventListener('input', function() {
                    this.value = this.value.toUpperCase();
                });
            }


            // All waris references have been removed

            // Expose functions globally
            window.addTanggungan = addTanggungan;
            window.removeTanggungan = removeTanggungan;
            window.previewTanggunganFile = previewTanggunganFile;
            window.openZoom = openZoom;
            window.closeZoom = closeZoom;
            window.loadBankInfo = loadBankInfo;
            window.previewReceipt = previewReceipt;
            window.formatIC = formatIC;
            window.formatFileSize = formatFileSize;
            window.showFileInfo = showFileInfo;
        });

        function toggleNoTanggungan(checkbox) {
            const container = document.getElementById('tanggunganContainer');
            const addBtns = document.querySelectorAll('#addTanggunganBtn, #addTanggunganBottomBtn');

            if (checkbox.checked) {
                // Kosongkan semua kad supaya tiada field 'required' yang tinggal
                container.innerHTML = '';
                tanggunganCount = 0;
                addBtns.forEach(btn => btn.style.display = 'none');
                updateTanggunganCount();
            } else {
                addBtns.forEach(btn => btn.style.display = '');
                if (document.querySelectorAll('.tanggungan-card').length === 0) {
                    addTanggungan();
                }
            }
        }
    </script>

</body>

</html>