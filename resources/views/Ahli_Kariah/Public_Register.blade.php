<!DOCTYPE html>
<html lang="ms">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pendaftaran Keahlian - {{ $masjid->nama }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    {{-- SweetAlert2 (needed for IC check alert & file-size alert) --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        djariah: {
                            50: '#fff7ed',
                            100: '#ffedd5',
                            500: '#f97316',
                            600: '#ea580c',
                            700: '#c2410c',
                            800: '#9a3412',
                        }
                    }
                }
            }
        }
    </script>
    <style>
        .tab-content {
            display: none;
        }

        .tab-content.active {
            display: block;
        }

        /* ── Stepper ─────────────────────────────────── */
        .stepper-wrap {
            display: flex;
            align-items: flex-start;
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
            font-size: 13px;
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

        .step-item.done .step-circle i {
            display: none;
        }

        .step-item.done .step-circle::before {
            content: '\f00c';
            font-family: 'Font Awesome 6 Free';
            font-weight: 900;
            font-size: 13px;
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

        /* inline field error */
        .tab-error {
            font-size: 11px;
            color: #dc2626;
            margin-top: 4px;
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
            <div class="flex justify-between items-center sm:flex-col sm:items-start gap-4">
                <div class="flex items-center space-x-3">
                    <a href="#" class="flex items-center space-x-3">
                        <img src="{{ asset('images/logos.png') }}" alt="Logo Masjid" class="h-8 sm:h-10 w-auto">
                    </a>
                    <div class="border-l-2 border-djariah-200 pl-3 ml-2">
                        <h6 class="text-lg sm:text-3xl font-extrabold text-gray-800 mb-1 sm:mb-2">
                            Pendaftaran Khairat
                            <span class="text-djariah-600 uppercase">{{ $masjid->nama }}</span>
                        </h6>
                    </div>
                </div>
            </div>
        </div>

        <!-- Masjid Information Card -->
        <div class="bg-white rounded-xl p-4 mb-4 shadow-lg">
            <div class="flex items-start gap-3">
                <div class="bg-djariah-500 rounded-full p-2 text-white">
                    <i class="fas fa-mosque"></i>
                </div>
                <div class="flex-1">
                    <h3 class="font-bold text-djariah-800">Maklumat Masjid</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-2 mt-2 text-sm">
                        <div>
                            <span class="text-gray-600">Nama Masjid:</span>
                            <span class="font-semibold text-gray-800 ml-2">{{ $masjid->nama }}</span>
                        </div>
                        <div>
                            <span class="text-gray-600">Jenis:</span>
                            <span class="font-semibold text-gray-800 ml-2">{{ $masjid->type ?? 'Masjid' }}</span>
                        </div>
                        <div class="md:col-span-2">
                            <span class="text-gray-600">Alamat:</span>
                            <span class="font-semibold text-gray-800 ml-2">
                                {{ $masjid->alamat }}, {{ $masjid->alamat2 }}, {{ $masjid->bandar }},
                                {{ $masjid->poskod->poskod_num ?? $masjid->poskod }},
                                {{ $masjid->negeri->nama ?? $masjid->negeri }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Form Container -->
        <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
            <!-- Stepper Navigation -->
            <div class="stepper-wrap">
                <div class="step-item active" data-tab="pemohon">
                    <div class="step-circle"><i class="fas fa-user"></i></div>
                    <span class="step-label">Pemohon</span>
                </div>
                <div class="step-item" data-tab="tanggungan">
                    <div class="step-circle"><i class="fas fa-users"></i></div>
                    <span class="step-label">Tanggungan</span>
                </div>
                <div class="step-item" data-tab="login">
                    <div class="step-circle"><i class="fas fa-key"></i></div>
                    <span class="step-label">Maklumat Akaun</span>
                </div>
            </div>


            <form action="{{ route('public.daftar.store', ['slug' => $masjid->slug]) }}" method="POST"
                enctype="multipart/form-data" class="p-6" id="publicRegisterForm">

                @csrf
                <input type="hidden" name="ahli_type" id="ahli_type">
                <input type="hidden" name="tanggungan_data" id="tanggungan_data">

                {{-- ══════════════════════════════════════════
                     TAB 1 — Butir-butir Pemohon
                ══════════════════════════════════════════ --}}
                <div id="pemohon" class="tab-content active space-y-8">
                    <section class="bg-gray-50 p-4 md:p-6 rounded-xl border border-gray-100">
                        <h3
                            class="text-md font-semibold text-djariah-700 mb-5 flex items-center gap-2 uppercase tracking-wider">
                            <i class="fas fa-user-circle"></i>
                            Maklumat Peribadi Pendaftar
                        </h3>

                        <div class="grid grid-cols-1 gap-6">
                            <div class="space-y-2">
                                <label class="block text-sm font-medium text-gray-700">Nama Penuh (Seperti Dalam
                                    IC)</label>
                                <input type="text" name="nama" value="{{ old('nama', $prefill['nama'] ?? '') }}"
                                    class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-djariah-500 focus:border-djariah-500 transition-all uppercase"
                                    placeholder="MASUKKAN NAMA PENUH" required>
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4">
                                <div class="space-y-2">
                                    <label class="block text-sm font-medium text-gray-700">No. Tel (Bimbit)</label>
                                    <input type="text" name="telefon_bimbit"
                                        value="{{ old('telefon_bimbit', $prefill['telefon_bimbit'] ?? '') }}"
                                        class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-djariah-500 focus:border-djariah-500 transition-all"
                                        placeholder="012-3456789" required>
                                </div>

                                <div class="space-y-2">
                                    <label class="block text-sm font-medium text-gray-700">No. Kad Pengenalan</label>
                                    <input type="text" id="ic_number" name="ic_number"
                                        value="{{ old('ic_number', $prefill['ic_number'] ?? '') }}"
                                        class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-djariah-500 focus:border-djariah-500 transition-all"
                                        placeholder="010413-03-1234" maxlength="14" required>
                                    {{-- ★ IC status indicator — same pattern as register_blade --}}
                                    <div id="ic_check_status"
                                        class="hidden mt-1 flex items-center gap-2 text-xs font-medium"></div>
                                </div>

                                <div class="space-y-2">
                                    <label class="block text-sm font-medium text-gray-700">Tarikh Lahir</label>
                                    <input type="text" id="tarikh_lahir" name="tarikh_lahir"
                                        class="w-full border border-gray-300 rounded-lg px-4 py-2.5 bg-gray-50" readonly
                                        placeholder="DD/MM/YYYY">
                                </div>

                                <div class="space-y-2">
                                    <label class="block text-sm font-medium text-gray-700">Umur</label>
                                    <input type="number" id="umur" name="umur"
                                        class="w-full bg-amber-50 border border-gray-300 rounded-lg px-4 py-2.5 font-bold text-djariah-700"
                                        readonly>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <div class="space-y-2">
                                    <label class="block text-sm font-medium text-gray-700">Jantina</label>
                                    <select name="jantina"
                                        class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-djariah-500 focus:border-djariah-500"
                                        required>
                                        <option value="">-- Pilih --</option>
                                        <option value="LELAKI"
                                            {{ old('jantina', $prefill['jantina'] ?? '') == 'LELAKI' ? 'selected' : '' }}>
                                            LELAKI</option>
                                        <option value="PEREMPUAN"
                                            {{ old('jantina', $prefill['jantina'] ?? '') == 'PEREMPUAN' ? 'selected' : '' }}>
                                            PEREMPUAN</option>
                                    </select>
                                </div>

                                <div class="space-y-2">
                                    <label class="block text-sm font-medium text-gray-700">Bangsa</label>
                                    <select name="bangsa"
                                        class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-djariah-500 focus:border-djariah-500"
                                        required>
                                        <option value="">-- Pilih --</option>
                                        <option value="MELAYU"
                                            {{ old('bangsa', $prefill['bangsa'] ?? '') == 'MELAYU' ? 'selected' : '' }}>
                                            MELAYU</option>
                                        <option value="CINA"
                                            {{ old('bangsa', $prefill['bangsa'] ?? '') == 'CINA' ? 'selected' : '' }}>
                                            CINA</option>
                                        <option value="INDIA"
                                            {{ old('bangsa', $prefill['bangsa'] ?? '') == 'INDIA' ? 'selected' : '' }}>
                                            INDIA</option>
                                        <option value="LAIN"
                                            {{ old('bangsa', $prefill['bangsa'] ?? '') == 'LAIN' ? 'selected' : '' }}>
                                            LAIN-LAIN</option>
                                    </select>
                                </div>

                                <div class="space-y-2">
                                    <label class="block text-sm font-medium text-gray-700">Status</label>
                                    <select name="statususer"
                                        class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-djariah-500 focus:border-djariah-500"
                                        required>
                                        <option value="">-- Pilih --</option>
                                        <option value="BUJANG" {{ old('statususer') == 'BUJANG' ? 'selected' : '' }}>
                                            BUJANG</option>
                                        <option value="BERKELUARGA"
                                            {{ old('statususer') == 'BERKELUARGA' ? 'selected' : '' }}>BERKELUARGA
                                        </option>
                                    </select>
                                </div>
                            </div>

                            <div class="space-y-2">
                                <!-- With these 5 address fields -->
                                <input type="hidden" name="alamat" id="combined_address">
                                <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
                                    <div class="space-y-2">
                                        <label class="block text-sm font-medium text-gray-700">No. Rumah</label>
                                        <input type="text" id="no_rumah" name="no_rumah"
                                            value="{{ old('no_rumah', $prefill['no_rumah'] ?? '') }}"
                                            class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-djariah-500 focus:border-djariah-500 transition-all uppercase"
                                            placeholder="No. Rumah / PT">
                                    </div>
                                    <div class="space-y-2">
                                        <label class="block text-sm font-medium text-gray-700">Jalan</label>
                                        <input type="text" id="jalan" name="jalan"
                                            value="{{ old('jalan', $prefill['jalan'] ?? '') }}"
                                            class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-djariah-500 focus:border-djariah-500 transition-all uppercase"
                                            placeholder="Jalan / Lorong">
                                    </div>
                                    <div class="space-y-2">
                                        <label class="block text-sm font-medium text-gray-700">Taman/Kampung</label>
                                        <input type="text" id="taman" name="taman"
                                            value="{{ old('taman', $prefill['taman'] ?? '') }}"
                                            class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-djariah-500 focus:border-djariah-500 transition-all uppercase"
                                            placeholder="Taman / Kampung">
                                    </div>
                                    <div class="space-y-2">
                                        <label class="block text-sm font-medium text-gray-700">Poskod</label>
                                        <input type="text" id="poskod_field" name="poskod_field"
                                            value="{{ old('poskod_field', $prefill['poskod_field'] ?? '') }}"
                                            class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-djariah-500 focus:border-djariah-500 transition-all"
                                            placeholder="43000" maxlength="5">
                                    </div>
                                    <div class="space-y-2">
                                        <label class="block text-sm font-medium text-gray-700">Bandar</label>
                                        <input type="text" id="bandar_field" name="bandar_field"
                                            value="{{ old('bandar_field', $prefill['bandar_field'] ?? '') }}"
                                            class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-djariah-500 focus:border-djariah-500 transition-all uppercase"
                                            placeholder="Kuala Lumpur">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="flex justify-center md:justify-end pt-6">
                            <button type="button"
                                class="next-tab w-full md:w-auto bg-indigo-600 hover:bg-indigo-700 text-white py-3 px-10 rounded-xl font-bold transition-all shadow-lg hover:shadow-indigo-200 flex justify-center items-center gap-3 group"
                                data-next="tanggungan">
                                Seterusnya
                                <i class="fas fa-arrow-right group-hover:translate-x-1 transition-transform"></i>
                            </button>
                        </div>
                    </section>
                </div>

                {{-- ══════════════════════════════════════════
                     TAB 2 — Tanggungan
                ══════════════════════════════════════════ --}}
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
                            <span class="text-sm font-medium">Saya mendaftar seorang diri (tiada isteri/suami/anak)</span>
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

                {{-- ══════════════════════════════════════════
                     TAB 3 — Maklumat Akaun & Pembayaran
                ══════════════════════════════════════════ --}}
                <div id="login" class="tab-content">
                    <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center gap-2">
                        <i class="fas fa-key text-djariah-600"></i> Maklumat Log Masuk
                    </h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="space-y-2">
                            <label class="block text-sm font-medium text-gray-700">Alamat E-mel</label>
                            <input type="email" name="email" value="{{ old('email', $prefill['email'] ?? '') }}"
                                class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-djariah-500 focus:border-djariah-500 transition-all"
                                placeholder="nama@contoh.com" required>
                        </div>
                    </div>
                    <input type="hidden" name="status" value="active">

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4 mt-4">
                        <div class="space-y-2">
                            <label class="block text-sm font-medium text-gray-700">Kata Laluan</label>
                            <input type="password" name="password"
                                class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-djariah-500 focus:border-djariah-500 transition-all"
                                placeholder="Minimum 6 aksara" required>
                        </div>
                        <div class="space-y-2">
                            <label class="block text-sm font-medium text-gray-700">Sahkan Kata Laluan</label>
                            <input type="password" name="password_confirmation"
                                class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-djariah-500 focus:border-djariah-500 transition-all"
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
                                <p id="yuran_pendaftaran" class="text-lg font-bold text-djariah-600">RM 0.00</p>
                            </div>

                            <!-- Bayaran Tahunan -->
                            <div class="bg-white rounded-lg p-3 border border-gray-200">
                                <p class="text-xs text-gray-500">Bayaran Tahunan</p>
                                <p id="bayaran_tahunan" class="text-lg font-bold text-djariah-600">RM 0.00</p>
                            </div>

                            <!-- Jumlah Keseluruhan -->
                            <div class="bg-white rounded-lg p-3 border-2 border-djariah-200 md:col-span-2">
                                <p class="text-xs text-gray-500">Jumlah Keseluruhan</p>
                                <p id="jumlah_bayaran" class="text-2xl font-extrabold text-djariah-700">RM 0.00</p>
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
                            <a id="termsLink" href="{{ route('policy.show', $masjid->id) }}" target="_blank"
                                rel="noopener"
                                class="text-djariah-600 hover:text-djariah-700 font-semibold underline inline-flex items-center gap-1">
                                <i class="fas fa-file-alt"></i> Klik Untuk Baca Terma & Syarat
                            </a>
                            <p class="text-gray-400 italic mt-1">
                                Terma & syarat khas {{ $masjid->nama }} akan dibuka pada tab baharu.
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

                        <button type="submit" id="submitBtn"
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

    <!-- ════════════════════════════════════════════════════════════
         JAVASCRIPT
    ════════════════════════════════════════════════════════════ -->
    <script>
        // ── Stepper order ────────────────────────────────────────────────
        const TAB_ORDER = ['pemohon', 'tanggungan', 'login'];

        function switchTab(tabId) {
            const idx = TAB_ORDER.indexOf(tabId);
            document.querySelectorAll('.step-item').forEach((item, i) => {
                item.classList.remove('active', 'done');
                if (i < idx) item.classList.add('done');
                else if (i === idx) item.classList.add('active');
            });
            document.querySelectorAll('.tab-content').forEach(c => c.classList.remove('active'));
            document.getElementById(tabId).classList.add('active');
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        }

        // ── Validation helpers ───────────────────────────────────────────
        function showError(input, msg) {
            clearError(input);
            input.classList.add('border-red-500', 'ring-2', 'ring-red-300');
            const p = document.createElement('p');
            p.className = 'tab-error text-xs text-red-600 mt-1';
            p.textContent = msg;
            input.parentNode.appendChild(p);
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
            tab.querySelectorAll('.border-red-500').forEach(el =>
                el.classList.remove('border-red-500', 'ring-2', 'ring-red-300'));

            tab.querySelectorAll('[required]').forEach(field => {
                if (field.type === 'checkbox') {
                    if (!field.checked) {
                        showError(field, 'Sila tandakan kotak ini.');
                        errorMessages.push('Sila bersetuju dengan Terma dan Syarat sebelum meneruskan.');
                        valid = false;
                    }
                } else if (!field.value.trim()) {
                    showError(field, 'Sila isi medan ini.');
                    const label = field.closest('.space-y-2')?.querySelector('label');
                    errorMessages.push((label ? label.textContent.trim() : field.name) + ' - wajib diisi.');
                    valid = false;
                }
            });

            // IC format + age — pemohon
            if (tabId === 'pemohon') {
                const ic = document.getElementById('ic_number');
                if (ic && ic.value && !/^\d{6}-\d{2}-\d{4}$/.test(ic.value)) {
                    showError(ic, 'Format IC tidak sah. Contoh: 010413-03-1234');
                    errorMessages.push('Format No. Kad Pengenalan tidak sah.');
                    valid = false;
                }
                const umur = parseInt(document.getElementById('umur')?.value || '0');
                if (ic && ic.value.length === 14 && umur < 24) {
                    showError(ic, '❗ Umur mesti 24 tahun ke atas untuk mendaftar.');
                    errorMessages.push('Umur pemohon mesti 24 tahun ke atas.');
                    valid = false;
                }
            }

            // Tanggungan validation
            if (tabId === 'tanggungan') {
                const noTanggungan = document.getElementById('noTanggunganCheckbox')?.checked;
                const cards = document.querySelectorAll('.tanggungan-card');
                if (!noTanggungan && cards.length === 0) {
                    errorMessages.push('Sila tambah sekurang-kurangnya satu tanggungan, atau tandakan "Tiada Tanggungan".');
                    valid = false;
                }
                if (!noTanggungan) cards.forEach((card, idx) => {
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

            // Password match — login
            if (tabId === 'login') {
                const pw = tab.querySelector('[name="password"]');
                const pwc = tab.querySelector('[name="password_confirmation"]');
                if (pw && pwc && pw.value && pwc.value && pw.value !== pwc.value) {
                    showError(pwc, 'Kata laluan tidak sepadan.');
                    errorMessages.push('Kata laluan dan pengesahan tidak sepadan.');
                    valid = false;
                }
            }

            if (!valid) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Sila Semak Maklumat',
                    html: '<ul class="text-left text-sm list-disc list-inside space-y-1 text-red-700">' +
                        errorMessages.map(m => `<li>${m}</li>`).join('') + '</ul>',
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

        // ── IC duplicate check (same approach as register_blade) ─────────
        let icCheckTimer = null;
        let icIsValid = false; // true once server confirms IC is free

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
                    const icInput = document.getElementById('ic_number');
                    if (data.exists) {
                        setIcStatus('error', 'No. IC ini telah didaftarkan. Sila log masuk atau gunakan IC lain.');
                        icIsValid = false;
                        showError(icInput, 'No. IC ini telah didaftarkan dalam sistem.');
                    } else {
                        setIcStatus('ok', 'No. IC boleh digunakan.');
                        icIsValid = true;
                        clearError(icInput);
                    }
                })
                .catch(() => {
                    setIcStatus('hide');
                    icIsValid = true;
                });
        }

        // ── IC format mask ───────────────────────────────────────────────
        function formatIC(raw) {
            let d = raw.replace(/\D/g, '').slice(0, 12);
            if (d.length > 8) return d.slice(0, 6) + '-' + d.slice(6, 8) + '-' + d.slice(8);
            if (d.length > 6) return d.slice(0, 6) + '-' + d.slice(6);
            return d;
        }

        // ── File size helper (used by tanggungan file preview) ───────────
        function formatFileSize(bytes) {
            if (bytes < 1024) return bytes + ' B';
            if (bytes < 1024 * 1024) return (bytes / 1024).toFixed(1) + ' KB';
            return (bytes / (1024 * 1024)).toFixed(2) + ' MB';
        }

        // ── IC auto-fill DOB + Umur (pemohon) ─────────────────────────────
        (function() {
            const icInput = document.getElementById('ic_number');
            const tarikhLahirInput = document.getElementById('tarikh_lahir');
            const umurInput = document.getElementById('umur');
            if (!icInput || !tarikhLahirInput || !umurInput) return;

            function parseMalaysiaIC(icStr) {
                let d = icStr.replace(/\D/g, '');
                if (d.length < 6) return null;

                let yy = parseInt(d.slice(0, 2), 10);
                let mm = parseInt(d.slice(2, 4), 10);
                let dd = parseInt(d.slice(4, 6), 10);

                if (mm < 1 || mm > 12) return null;
                if (dd < 1 || dd > 31) return null;

                let fy = yy <= 29 ? 2000 + yy : 1900 + yy;
                let dt = new Date(fy, mm - 1, dd);

                if (dt.getFullYear() !== fy || dt.getMonth() !== mm - 1 || dt.getDate() !== dd) {
                    return null;
                }
                return dt;
            }

            function calcAge(birthDate) {
                const today = new Date();
                let age = today.getFullYear() - birthDate.getFullYear();
                const monthDiff = today.getMonth() - birthDate.getMonth();
                if (monthDiff < 0 || (monthDiff === 0 && today.getDate() < birthDate.getDate())) {
                    age--;
                }
                return age;
            }

            function updateDobAndAge() {
                const bd = parseMalaysiaIC(icInput.value);
                if (bd) {
                    // Format as DD/MM/YYYY
                    const d = String(bd.getDate()).padStart(2, '0');
                    const m = String(bd.getMonth() + 1).padStart(2, '0');
                    const y = bd.getFullYear();
                    tarikhLahirInput.value = `${d}/${m}/${y}`;
                    umurInput.value = calcAge(bd);
                } else if (icInput.value.replace(/\D/g, '').length >= 6) {
                    tarikhLahirInput.value = '';
                    umurInput.value = '';
                }
            }

            icInput.addEventListener('input', function() {
                let pos = this.selectionStart;
                let before = this.value;
                this.value = formatIC(this.value);
                let delta = this.value.length - before.length;
                if (delta > 0) pos += delta;
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

        // ── Next / Prev buttons ──────────────────────────────────────────
        document.querySelectorAll('.next-tab').forEach(button => {
            button.addEventListener('click', async () => {
                const currentTab = document.querySelector('.tab-content.active');
                if (!currentTab) return;

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
            button.addEventListener('click', () => switchTab(button.getAttribute('data-prev')));
        });

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

            const combinedAddress = parts.join(' / ');
            const hiddenInput = document.getElementById('combined_address');
            if (hiddenInput) hiddenInput.value = combinedAddress;
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

        // ── Tanggungan Functions (same as register_blade) ─────────────────
        let tanggunganCount = 0;
        const MAX_TANGGUNGAN = 20;

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
            } else {
                tarikhInput.value = '';
                if (tarikhDisplay) tarikhDisplay.value = '';
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

            const icInput = card.querySelector('.tanggungan-ic');
            if (icInput) {
                icInput.addEventListener('input', function(e) {
                    let pos = this.selectionStart;
                    let before = this.value;
                    this.value = formatIC(this.value);
                    let added = this.value.length - before.length;
                    if (added > 0) pos += added;
                    try {
                        this.setSelectionRange(pos, pos);
                    } catch (e) {}

                    updateTanggunganDobAndAge(this);
                    validateTanggunganIC(this);
                });

                if (icInput.value) {
                    icInput.value = formatIC(icInput.value);
                    updateTanggunganDobAndAge(icInput);
                    validateTanggunganIC(icInput);
                }
            }

            updateTanggunganCount();

            setTimeout(() => {
                card.scrollIntoView({
                    behavior: 'smooth',
                    block: 'center'
                });
            }, 100);

            updateRemoveButtons();
        }

        function removeTanggungan(index) {
            const card = document.getElementById(`tanggungan_${index}`);
            if (!card) return;

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
                card.id = `tanggungan_${idx}`;
                card.dataset.index = idx;

                const badge = card.querySelector('.badge');
                if (badge) {
                    badge.textContent = 'Tanggungan';
                }

                const numberBadge = card.querySelector('.card-number-badge');
                if (numberBadge) {
                    numberBadge.innerHTML = `<i class="fas fa-user"></i> #${idx + 1}`;
                }

                const idLabel = card.querySelector('.card-header .text-gray-400');
                if (idLabel) {
                    idLabel.textContent = `ID: T-${String(idx + 1).padStart(3, '0')}`;
                }

                const inputs = card.querySelectorAll('input, select');
                inputs.forEach(input => {
                    const name = input.getAttribute('name');
                    if (name) {
                        const newName = name.replace(/tanggungan\[\d+\]/, `tanggungan[${idx}]`);
                        input.setAttribute('name', newName);
                    }
                    if (input.dataset.index !== undefined) {
                        input.dataset.index = idx;
                    }
                });

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

                const removeBtn = card.querySelector('.remove-btn');
                if (removeBtn) {
                    removeBtn.onclick = function() {
                        removeTanggungan(idx);
                    };
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
                    if (idx === 0) {
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

        // ── Zoom modal ───────────────────────────────────────────────────
        function openZoom(img) {
            document.getElementById('zoomImage').src = img.src;
            document.getElementById('zoomModal').classList.remove('hidden');
        }

        function closeZoom() {
            document.getElementById('zoomModal').classList.add('hidden');
        }
        document.getElementById('zoomModal').addEventListener('click', e => {
            if (e.target.id === 'zoomModal') closeZoom();
        });

        // ── Load Harga on page load (fixed masjid) ────────────────────────
        document.addEventListener('DOMContentLoaded', function() {
            const masjidId = '{{ $masjid->id }}';

            if (masjidId) {
                fetch('/get-hargakhairat/' + masjidId)
                    .then(r => {
                        if (!r.ok) throw new Error();
                        return r.json();
                    })
                    .then(h => {
                        const byr = parseFloat(h.bayaran_tahunan ?? 0);
                        const yur = parseFloat(h.yuran_pendaftaran ?? 0);
                        document.getElementById('bayaran_tahunan') &&
                            (document.getElementById('bayaran_tahunan').innerText = 'RM ' + byr.toFixed(2));
                        document.getElementById('yuran_pendaftaran') &&
                            (document.getElementById('yuran_pendaftaran').innerText = 'RM ' + yur.toFixed(2));
                        document.getElementById('jumlah_bayaran') &&
                            (document.getElementById('jumlah_bayaran').innerText = 'RM ' + (byr + yur).toFixed(
                                2));
                    })
                    .catch(() => {});
            }

            setupAddressUppercase();
            combineAddressForSubmit();

            // Tambah tanggungan pertama secara automatik
            addTanggungan();
            document.getElementById('addTanggunganBtn')?.addEventListener('click', addTanggungan);
            document.getElementById('addTanggunganBottomBtn')?.addEventListener('click', addTanggungan);

            // Uppercase for nama
            const namaField = document.querySelector('input[name="nama"]');
            if (namaField) {
                namaField.addEventListener('input', function() {
                    this.value = this.value.toUpperCase();
                });
            }

            // Expose functions globally
            window.addTanggungan = addTanggungan;
            window.removeTanggungan = removeTanggungan;
            window.previewTanggunganFile = previewTanggunganFile;
            window.openZoom = openZoom;
            window.closeZoom = closeZoom;
            window.formatIC = formatIC;
            window.formatFileSize = formatFileSize;
            window.toggleNoTanggungan = toggleNoTanggungan;
        });

        // ── Toggle "Tiada Tanggungan" ──────────────────────────────────────
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

        // ── AJAX Submit with SweetAlert ────────────────────────────────────
        const publicRegisterForm = document.getElementById('publicRegisterForm');
        if (publicRegisterForm) {
            publicRegisterForm.addEventListener('submit', function(e) {
                e.preventDefault();

                // 1. Uppercase nama
                const namaField = this.querySelector('input[name="nama"]');
                if (namaField && namaField.value) {
                    namaField.value = namaField.value.toUpperCase();
                }

                // 2. Combine address
                combineAddressForSubmit();

                // 3. Collect tanggungan data
                const noTanggungan = document.getElementById('noTanggunganCheckbox')?.checked;
                const tanggunganData = noTanggungan ? [] : getTanggunganData();
                if (!noTanggungan && tanggunganData.length === 0) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Tiada Tanggungan',
                        text: 'Sila tambah sekurang-kurangnya satu tanggungan, atau tandakan "Tiada Tanggungan".',
                        confirmButtonColor: '#ea580c'
                    });
                    return;
                }

                let hasError = false;
                if (!noTanggungan) {
                    tanggunganData.forEach((t) => {
                        if (!t.nama || !t.ic || !t.tarikh_lahir || !t.hubungan) {
                            hasError = true;
                        }
                        if (t.ic && !/^\d{6}-\d{2}-\d{4}$/.test(t.ic)) {
                            hasError = true;
                        }
                    });
                }

                if (hasError) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Maklumat Tanggungan Tidak Lengkap',
                        text: 'Sila pastikan semua maklumat tanggungan diisi dengan lengkap dan sah.',
                        confirmButtonColor: '#ea580c'
                    });
                    return;
                }

                // 4. Store tanggungan data as JSON
                const tanggunganHidden = document.getElementById('tanggungan_data');
                if (tanggunganHidden) {
                    tanggunganHidden.value = noTanggungan ? '[]' : JSON.stringify(tanggunganData);
                }

                // 5. Build FormData
                const formData = new FormData(this);

                if (tanggunganHidden) {
                    formData.append('tanggungan_data', tanggunganHidden.value);
                }

                // 6. Append each tanggungan file
                const tanggunganFiles = document.querySelectorAll('.tanggungan-file');
                tanggunganFiles.forEach((input, idx) => {
                    if (input.files && input.files.length > 0) {
                        formData.append(`tanggungan[${idx}][dokumen]`, input.files[0]);
                    }
                });

                // 7. Show loading
                Swal.fire({
                    title: 'Memproses Pendaftaran...',
                    text: 'Sila tunggu sebentar.',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });

                // 8. Submit via AJAX
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
                            let errorHtml = '';
                            if (typeof data.errors === 'string') {
                                errorHtml = `<p class="text-sm">${data.errors}</p>`;
                            } else if (typeof data.errors === 'object') {
                                const msgs = Object.values(data.errors).flat();
                                errorHtml = '<ul class="text-left text-sm list-disc pl-4">' +
                                    msgs.map(m => `<li>${m}</li>`).join('') +
                                    '</ul>';
                            } else {
                                errorHtml = '<p class="text-sm">Sila semak maklumat yang diisi.</p>';
                            }

                            Swal.fire({
                                icon: 'error',
                                title: 'Pendaftaran Gagal',
                                html: `<div class="text-left">
                                       <p class="font-semibold text-red-600 mb-2">Sila semak maklumat berikut:</p>
                                       ${errorHtml}
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
                            text: 'Terjadi masalah semasa memproses pendaftaran. Sila cuba lagi.',
                            confirmButtonColor: '#ea580c'
                        });
                    });
            });
        }
    </script>

</body>

</html>