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
                <div class="step-item" data-tab="waris">
                    <div class="step-circle"><i class="fas fa-heart"></i></div>
                    <span class="step-label">Waris</span>
                </div>
                <div class="step-item" data-tab="login">
                    <div class="step-circle"><i class="fas fa-key"></i></div>
                    <span class="step-label">Maklumat Akaun</span>
                </div>
                <div class="step-item" data-tab="payment">
                    <div class="step-circle"><i class="fas fa-money-bill-wave"></i></div>
                    <span class="step-label">Pembayaran</span>
                </div>
            </div>

            <form action="{{ route('public.daftar.store', ['slug' => $masjid->slug]) }}" method="POST"
                enctype="multipart/form-data" class="p-6" id="publicRegisterForm">

                @csrf
                <input type="hidden" name="ahli_type" id="ahli_type">

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
                                    <input type="date" id="tarikh_lahir" name="tarikh_lahir"
                                        class="w-full border border-gray-300 rounded-lg px-4 py-2.5 bg-gray-50"
                                        readonly>
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
                                class="next-tab w-full md:w-auto bg-djariah-600 hover:bg-djariah-700 text-white py-3 px-10 rounded-xl font-bold transition-all shadow-lg hover:shadow-djariah-200 flex justify-center items-center gap-3 group"
                                data-next="waris">
                                Seterusnya
                                <i class="fas fa-arrow-right group-hover:translate-x-1 transition-transform"></i>
                            </button>
                        </div>
                    </section>
                </div>

                {{-- ══════════════════════════════════════════
                     TAB 2 — Waris Terdekat
                ══════════════════════════════════════════ --}}
                <div id="waris" class="tab-content">
                    <section class="bg-gray-50 p-6 rounded-xl border border-gray-100">
                        <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center gap-2">
                            <i class="fas fa-heart text-djariah-600"></i>
                            Waris Terdekat
                        </h3>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="space-y-2">
                                <label class="block text-sm font-medium text-gray-700">Nama</label>
                                <input type="text" name="waris_nama" value="{{ old('waris_nama') }}"
                                    class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-djariah-500 focus:border-djariah-500 transition-all uppercase"
                                    placeholder="Nama waris" required>
                            </div>

                            <div class="space-y-2">
                                <label class="block text-sm font-medium text-gray-700">No. Kad Pengenalan</label>
                                <input type="text" id="waris_ic" name="waris_ic" value="{{ old('waris_ic') }}"
                                    class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-djariah-500 focus:border-djariah-500 transition-all"
                                    placeholder="010413-03-1234" maxlength="14" required>
                            </div>

                            <div class="space-y-2 md:col-span-2">
                                <!-- With these waris address fields and hidden input -->
                                <input type="hidden" name="waris_alamat" id="combined_waris_address">
                                <div class="grid grid-cols-1 md:grid-cols-5 gap-3">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">No. Rumah</label>
                                        <input type="text" id="waris_no_rumah" name="waris_no_rumah"
                                            value="{{ old('waris_no_rumah') }}"
                                            class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-djariah-500 focus:border-djariah-500 transition-all uppercase text-sm"
                                            placeholder="No. Rumah">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Jalan</label>
                                        <input type="text" id="waris_jalan" name="waris_jalan"
                                            value="{{ old('waris_jalan') }}"
                                            class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-djariah-500 focus:border-djariah-500 transition-all uppercase text-sm"
                                            placeholder="Jalan">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Taman/Kampung</label>
                                        <input type="text" id="waris_taman" name="waris_taman"
                                            value="{{ old('waris_taman') }}"
                                            class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-djariah-500 focus:border-djariah-500 transition-all uppercase text-sm"
                                            placeholder="Taman/Kampung">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Poskod</label>
                                        <input type="text" id="waris_poskod" name="waris_poskod"
                                            value="{{ old('waris_poskod') }}"
                                            class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-djariah-500 focus:border-djariah-500 transition-all text-sm"
                                            placeholder="Poskod" maxlength="5">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Bandar</label>
                                        <input type="text" id="waris_bandar" name="waris_bandar"
                                            value="{{ old('waris_bandar') }}"
                                            class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-djariah-500 focus:border-djariah-500 transition-all uppercase text-sm"
                                            placeholder="Bandar">
                                    </div>
                                </div>
                            </div>

                            <div class="space-y-2">
                                <label class="block text-sm font-medium text-gray-700">No. Tel (Pejabat)</label>
                                <input type="text" name="waris_telefon_pejabat"
                                    value="{{ old('waris_telefon_pejabat') }}"
                                    class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-djariah-500 focus:border-djariah-500 transition-all"
                                    placeholder="No. telefon pejabat">
                            </div>

                            <div class="space-y-2">
                                <label class="block text-sm font-medium text-gray-700">No. Tel (Bimbit)</label>
                                <input type="text" name="waris_telefon_bimbit"
                                    value="{{ old('waris_telefon_bimbit') }}"
                                    class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-djariah-500 focus:border-djariah-500 transition-all"
                                    placeholder="No. telefon bimbit" required>
                            </div>
                        </div>

                        <div class="flex flex-col sm:flex-row justify-between gap-3 mt-6">
                            <button type="button"
                                class="prev-tab w-full sm:w-auto justify-center bg-gray-200 hover:bg-gray-300 text-gray-700 py-2.5 px-6 rounded-lg font-medium transition-colors flex items-center gap-2"
                                data-prev="pemohon">
                                <i class="fas fa-arrow-left"></i> Sebelumnya
                            </button>
                            <button type="button"
                                class="next-tab w-full sm:w-auto justify-center bg-djariah-600 hover:bg-djariah-700 text-white py-2.5 px-6 rounded-lg font-medium transition-colors flex items-center gap-2"
                                data-next="login">
                                Seterusnya <i class="fas fa-arrow-right"></i>
                            </button>
                        </div>
                    </section>
                </div>

                {{-- ══════════════════════════════════════════
                     TAB 3 — Maklumat Akaun
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

                    <!-- Terma dan Syarat -->
                    <div class="border border-gray-200 rounded-lg p-4 bg-gray-50 mb-4">
                        <h4 class="font-bold text-gray-800 mb-2">Terma dan Syarat Keahlian</h4>
                        <div class="text-xs text-gray-600 space-y-1">
                            <p class="font-semibold text-gray-700 mt-2">1. Kelayakan & Maklumat</p>
                            <p>• Tanggungan termasuk isteri/suami/anak-anak berumur 24 tahun kebawah.</p>
                            <p>• Ahli wajib mengisi maklumat yang tepat dan lengkap.</p>
                            <p>• Semua laporan dan maklumat yang dihantar mestilah benar dan sah.</p>

                            <p class="font-semibold text-gray-700 mt-2">2. Bayaran & Tunggakan</p>
                            <p>• Bayaran keahlian hendaklah dijelaskan mengikut tempoh ditetapkan.</p>
                            <p>• Tunggakan bayaran boleh menyebabkan akaun digantung atau dinyahaktifkan.</p>
                            <p>• Jika bayaran tertunggak selama satu tahun, akaun boleh dinyahaktifkan dan direset
                                sebagai akaun baharu.</p>

                            <p class="font-semibold text-gray-700 mt-2">3. Pentadbiran</p>
                            <p>• Keahlian tertakluk kepada semakan dan kelulusan AJK.</p>
                            <p>• Pendaftaran semula tertakluk kepada syarat semasa dan kelulusan AJK.</p>
                            <p>• AJK berhak meminda terma dan syarat dari semasa ke semasa.</p>
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
                            data-prev="waris">
                            <i class="fas fa-arrow-left"></i> Sebelumnya
                        </button>
                        <button type="button"
                            class="next-tab w-full sm:w-auto justify-center bg-djariah-600 hover:bg-djariah-700 text-white py-2.5 px-6 rounded-lg font-medium transition-colors flex items-center gap-2"
                            data-next="payment">
                            Seterusnya <i class="fas fa-arrow-right"></i>
                        </button>
                    </div>
                </div>

                {{-- ══════════════════════════════════════════
                     TAB 4 — Pembayaran
                ══════════════════════════════════════════ --}}
                <div id="payment" class="tab-content">
                    <div class="bg-white border border-gray-200 rounded-2xl p-6 space-y-6">
                        <h3 class="text-base font-semibold text-gray-800 flex items-center gap-2">
                            <i class="fas fa-file-invoice text-gray-500"></i>
                            Ringkasan Pembayaran
                        </h3>

                        <div class="space-y-3 text-sm">
                            <div class="max-w-4xl mx-auto bg-white rounded-2xl shadow-sm border p-6 space-y-6">

                                <!-- Radio Button Section -->
                                <div class="bg-gray-50 rounded-xl p-4 border border-gray-200">
                                    <label class="block text-sm font-semibold text-gray-700 mb-3">
                                        Pernah Mendaftar Sebagai Ahli Khairat?
                                    </label>
                                    <div
                                        class="bg-red-50 border-l-4 border-red-500 p-3 mb-4 text-sm text-red-500 rounded">
                                        <i class="fas fa-info-circle mr-2"></i>
                                        <strong>Nota:</strong> Bagi ahli yang pernah mendaftar di luar sistem (daftar
                                        secara fizikal), sila pilih "Ya" dan muat naik resit pembayaran sedia ada (jika
                                        ada).
                                        Jika belum pernah mendaftar, pilih "Tidak" dan lengkapkan pendaftaran seperti
                                        biasa.
                                    </div>
                                    <div class="flex gap-6">
                                        <label class="flex items-center gap-2 cursor-pointer">
                                            <input type="radio" name="payment_status" value="yes"
                                                id="payment_yes"
                                                class="w-4 h-4 text-djariah-600 focus:ring-djariah-500">
                                            <span class="text-gray-700 font-medium">Ya (Pernah Daftar Secara
                                                Fizikal)</span>
                                        </label>
                                        <label class="flex items-center gap-2 cursor-pointer">
                                            <input type="radio" name="payment_status" value="no"
                                                id="payment_no"
                                                class="w-4 h-4 text-djariah-600 focus:ring-djariah-500">
                                            <span class="text-gray-700 font-medium">Tidak (Belum Pernah Daftar)</span>
                                        </label>
                                    </div>
                                </div>

                                <!-- Bank & Payment Details Section - Only shown for "Tidak" option -->
                                <div id="bankDetailsSection" class="space-y-6">
                                    <div
                                        class="bg-blue-50 border border-blue-200 rounded-xl p-4 text-sm text-blue-800">
                                        <strong>Perhatian:</strong>
                                        <ul class="list-disc list-inside mt-2 space-y-1">
                                            <li>Sila buat pemindahan wang ke akaun di bawah terlebih dahulu.</li>
                                            <li>Selepas pembayaran berjaya, muat naik resit pembayaran sebelum menekan
                                                butang <b>Hantar</b>.</li>
                                            <li>Permohonan hanya akan diproses selepas resit diterima.</li>
                                        </ul>
                                    </div>

                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                        <div class="flex gap-4 items-start">
                                            <img id="qrImage" src="{{ asset('images/default-qr.png') }}"
                                                alt="QR Bayaran"
                                                class="w-40 h-40 object-contain rounded-xl border cursor-pointer hover:scale-105 transition"
                                                onclick="openZoom(this)">
                                            <div class="space-y-3">
                                                <div>
                                                    <p class="text-xs text-gray-500">Nombor Akaun</p>
                                                    <p id="bank_no_akaun"
                                                        class="font-semibold text-gray-800 tracking-wide">-</p>
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
                                </div>

                                {{-- ── Single File Upload (shown for both Yes/No) ── --}}
                                <div id="fileUploadSection" class="space-y-6">
                                    <!-- Dynamic message based on selection -->
                                    <div id="fileUploadMessage"
                                        class="bg-blue-50 border border-blue-200 rounded-xl p-4 text-sm text-blue-800">
                                        <i class="fas fa-info-circle mr-2"></i>
                                        <span id="fileUploadText">Sila muat naik resit pembayaran untuk pendaftaran
                                            baharu.</span>
                                    </div>

                                    <div class="space-y-3">
                                        <label class="block text-sm font-medium text-gray-700">
                                            Muat Naik Resit / Bukti Pembayaran <span id="fileRequiredStar"
                                                class="text-red-500">*</span>
                                        </label>
                                        <div
                                            class="bg-yellow-50 border border-yellow-200 rounded-lg px-3 py-2 text-xs text-yellow-800 flex items-center gap-2">
                                            <i class="fas fa-exclamation-circle"></i>
                                            Had saiz fail: <strong>5MB</strong>. Format dibenarkan: JPG, PNG, PDF.
                                        </div>
                                        <input type="file" id="resit_file_single" name="resit_file"
                                            accept="image/jpeg,image/jpg,image/png,.pdf"
                                            onchange="previewReceiptSingle(event)"
                                            class="block w-full text-sm file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:bg-djariah-600 file:text-white hover:file:bg-djariah-700">
                                        <div id="resit_file_single_info"
                                            class="hidden text-xs mt-1 flex items-center gap-2"></div>
                                        <div id="receiptPreviewSingle" class="hidden mt-4">
                                            <p class="text-xs text-gray-500 mb-2">Preview Resit:</p>
                                            <img id="receiptImageSingle"
                                                class="w-48 rounded-xl border shadow-sm cursor-pointer hover:scale-105 transition"
                                                onclick="openZoom(this)">
                                        </div>
                                        <p id="fileUploadHint" class="text-xs text-gray-400">* Sila muat naik resit
                                            pembayaran untuk pengesahan.</p>
                                    </div>
                                </div>

                            </div>

                            <hr class="border-gray-100">
                            <p class="text-xs text-gray-400">Jumlah bayaran akan dikira secara automatik.</p>
                        </div>
                    </div>

                    <div class="flex flex-col sm:flex-row justify-between gap-3 mt-4">
                        <button type="button"
                            class="prev-tab w-full sm:w-auto justify-center bg-gray-200 hover:bg-gray-300 text-gray-700 py-2.5 px-6 rounded-lg font-medium transition-colors flex items-center gap-2"
                            data-prev="login">
                            <i class="fas fa-arrow-left"></i> Sebelumnya
                        </button>
                        <button type="submit" id="submitBtn"
                            class="w-full sm:w-auto justify-center bg-djariah-600 hover:bg-djariah-700 text-white py-2.5 px-6 rounded-lg font-medium inline-flex items-center gap-2">
                            Buat Pembayaran <i class="fas fa-arrow-right"></i>
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
        // ── Stepper ──────────────────────────────────────────────────────
        const TAB_ORDER = ['pemohon', 'waris', 'login', 'payment'];

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

            // IC format — pemohon
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

            // IC format — waris
            if (tabId === 'waris') {
                const wic = document.getElementById('waris_ic');
                if (wic && wic.value && !/^\d{6}-\d{2}-\d{4}$/.test(wic.value)) {
                    showError(wic, 'Format IC tidak sah. Contoh: 010413-03-1234');
                    errorMessages.push('Format IC Waris tidak sah.');
                    valid = false;
                }
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

            // Uses the same endpoint as RegisterController::checkIc
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
                    // Network error — do not block user, but log it
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

        // ── IC auto-fill DOB + Umur ──────────────────────────────────────
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

                // Validate month and day
                if (mm < 1 || mm > 12) return null;
                if (dd < 1 || dd > 31) return null;

                // Determine century (Malaysian IC: 00-29 = 2000s, 30-99 = 1900s)
                let fy = yy <= 29 ? 2000 + yy : 1900 + yy;

                let dt = new Date(fy, mm - 1, dd);

                // Validate the date is real
                if (dt.getFullYear() !== fy || dt.getMonth() !== mm - 1 || dt.getDate() !== dd) {
                    return null;
                }

                return dt;
            }

            function calcAge(birthDate) {
                const today = new Date();
                let age = today.getFullYear() - birthDate.getFullYear();
                const monthDiff = today.getMonth() - birthDate.getMonth();

                // If birthday hasn't occurred yet this year, subtract 1
                if (monthDiff < 0 || (monthDiff === 0 && today.getDate() < birthDate.getDate())) {
                    age--;
                }
                return age;
            }

            function updateDobAndAge() {
                const bd = parseMalaysiaIC(icInput.value);
                if (bd) {
                    const y = bd.getFullYear(),
                        m = String(bd.getMonth() + 1).padStart(2, '0'),
                        d = String(bd.getDate()).padStart(2, '0');
                    tarikhLahirInput.value = `${y}-${m}-${d}`;
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

                // Debounced server IC check
                clearTimeout(icCheckTimer);
                icIsValid = false;
                const cleaned = this.value.replace(/\D/g, '');
                if (cleaned.length === 12) {
                    icCheckTimer = setTimeout(() => checkIcDuplicate(this.value), 600);
                } else {
                    setIcStatus('hide');
                }
            });

            // Pre-fill from Blade prefill
            if (icInput.value) {
                icInput.value = formatIC(icInput.value);
                updateDobAndAge();
                const cleaned = icInput.value.replace(/\D/g, '');
                if (cleaned.length === 12) checkIcDuplicate(icInput.value);
            }
        })();

        // ── Waris IC mask + age gate (≥18) ──────────────────────────────
        (function() {
            const warisIcInput = document.getElementById('waris_ic');
            if (!warisIcInput) return;

            warisIcInput.addEventListener('input', function() {
                let pos = this.selectionStart;
                let before = this.value;
                this.value = formatIC(this.value);
                let delta = this.value.length - before.length;
                if (delta > 0) pos += delta;
                try {
                    this.setSelectionRange(pos, pos);
                } catch (e) {}

                const d = this.value.replace(/\D/g, '');
                if (d.length === 12) {
                    let yy = parseInt(d.slice(0, 2), 10);
                    let mm = parseInt(d.slice(2, 4), 10);
                    let dd = parseInt(d.slice(4, 6), 10);
                    let fy = yy <= 29 ? 2000 + yy : 1900 + yy;
                    let bd = new Date(fy, mm - 1, dd);
                    let t = new Date();
                    let age = t.getFullYear() - bd.getFullYear();
                    if (t.getMonth() < bd.getMonth() ||
                        (t.getMonth() === bd.getMonth() && t.getDate() < bd.getDate())) age--;
                    if (age < 18) {
                        showError(this, '❗ Waris mesti berumur 18 tahun ke atas.');
                        this.value = '';
                    }
                }
            });

            if (warisIcInput.value) warisIcInput.value = formatIC(warisIcInput.value);
        })();

        // ── Next / Prev buttons ──────────────────────────────────────────
        document.querySelectorAll('.next-tab').forEach(button => {
            button.addEventListener('click', async () => {
                const currentTab = document.querySelector('.tab-content.active');
                if (!currentTab) return;

                // ★ IC duplicate check blocks leaving pemohon tab (same as register_blade)
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

                if (!validateTab(currentTab.id)) return;
                switchTab(button.getAttribute('data-next'));
            });
        });

        document.querySelectorAll('.prev-tab').forEach(button => {
            button.addEventListener('click', () => switchTab(button.getAttribute('data-prev')));
        });

        // ── Load Harga on page load ──────────────────────────────────────
        document.addEventListener('DOMContentLoaded', function() {
            const masjidId = '{{ $masjid->id }}';
            if (!masjidId) return;
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
                        (document.getElementById('jumlah_bayaran').innerText = 'RM ' + (byr + yur).toFixed(2));
                })
                .catch(() => {});

            // Load bank info for the fixed masjid
            loadBankInfo(masjidId);
            initAddressFeatures();
            combineAddressForSubmit();
            combineWarisAddressForSubmit();
        });

        const publicRegisterForm = document.getElementById('publicRegisterForm');
        if (publicRegisterForm) {
            publicRegisterForm.addEventListener('submit', function(e) {
                combineAddressForSubmit();
                combineWarisAddressForSubmit();
            });
        }

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

        // ── File size helpers (same as register_blade) ───────────────────
        const MAX_FILE_BYTES = 5 * 1024 * 1024; // 5 MB

        function formatFileSize(bytes) {
            if (bytes < 1024) return bytes + ' B';
            if (bytes < 1024 * 1024) return (bytes / 1024).toFixed(1) + ' KB';
            return (bytes / (1024 * 1024)).toFixed(2) + ' MB';
        }

        // ── Image Compression Function (Fixes iPhone uploads) ──
        function compressImage(file, callback) {
            // If not an image, return original file
            if (!file.type.startsWith('image/')) {
                callback(file);
                return;
            }

            const reader = new FileReader();
            reader.readAsDataURL(file);
            reader.onload = (e) => {
                const img = new Image();
                img.src = e.target.result;
                img.onload = () => {
                    const canvas = document.createElement('canvas');
                    let width = img.width;
                    let height = img.height;

                    // Max dimensions - reduce large images from iPhone
                    const MAX_WIDTH = 1200;
                    const MAX_HEIGHT = 1200;

                    if (width > height) {
                        if (width > MAX_WIDTH) {
                            height *= MAX_WIDTH / width;
                            width = MAX_WIDTH;
                        }
                    } else {
                        if (height > MAX_HEIGHT) {
                            width *= MAX_HEIGHT / height;
                            height = MAX_HEIGHT;
                        }
                    }

                    canvas.width = width;
                    canvas.height = height;
                    const ctx = canvas.getContext('2d');
                    ctx.drawImage(img, 0, 0, width, height);

                    // Convert to JPEG with 70% quality
                    canvas.toBlob((blob) => {
                        const compressedFile = new File([blob], file.name.replace(/\.[^/.]+$/, '.jpg'), {
                            type: 'image/jpeg',
                            lastModified: Date.now()
                        });
                        callback(compressedFile);
                    }, 'image/jpeg', 0.7);
                };
            };
            reader.onerror = () => {
                // If compression fails, use original file
                callback(file);
            };
        }

        /**
         * Show size badge below the input. Returns false when over limit.
         */
        function showFileInfo(infoElId, file) {
            const el = document.getElementById(infoElId);
            if (!el || !file) return true;
            const overLimit = file.size > MAX_FILE_BYTES;
            el.classList.remove('hidden');
            if (overLimit) {
                el.innerHTML =
                    `<i class="fas fa-times-circle text-red-500"></i>
                    <span class="text-red-600">Saiz fail: <strong>${formatFileSize(file.size)}</strong> — melebihi had 5MB. Sila pilih fail yang lebih kecil.</span>`;
            } else {
                el.innerHTML = `<i class="fas fa-check-circle text-green-500"></i>
                    <span class="text-green-600">Saiz fail: <strong>${formatFileSize(file.size)}</strong> — OK</span>`;
            }
            return !overLimit;
        }

        // ── Preview Receipt with Compression ───────────────────
        function previewReceiptSingle(event) {
            const file = event.target.files[0];
            if (!file) return;

            // Get file extension
            const fileName = file.name;
            const fileExt = fileName.split('.').pop().toLowerCase();

            console.log('Original file:', {
                name: fileName,
                type: file.type,
                size: formatFileSize(file.size),
                extension: fileExt
            });

            // Allow more formats including HEIC from iPhone
            const allowedExtensions = ['jpg', 'jpeg', 'png', 'pdf', 'heic', 'heif'];

            // Check by extension
            if (!allowedExtensions.includes(fileExt)) {
                Swal.fire({
                    icon: 'error',
                    title: 'Format Fail Tidak Dibenarkan',
                    html: `Format <strong>${fileExt.toUpperCase()}</strong> tidak dibenarkan.<br>Sila gunakan format JPG, PNG, PDF atau HEIC.`,
                    confirmButtonColor: '#ea580c',
                });
                event.target.value = '';
                document.getElementById('receiptPreviewSingle').classList.add('hidden');
                document.getElementById('resit_file_single_info').classList.add('hidden');
                return;
            }

            // Compress image first (for photos)
            compressImage(file, (compressedFile) => {
                console.log('Compressed file:', {
                    name: compressedFile.name,
                    size: formatFileSize(compressedFile.size),
                    compression: ((1 - compressedFile.size / file.size) * 100).toFixed(1) + '% reduction'
                });

                // Validate file size after compression (5MB max)
                if (compressedFile.size > MAX_FILE_BYTES) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Fail Terlalu Besar',
                        html: `Saiz fail selepas kompresi: <strong>${formatFileSize(compressedFile.size)}</strong><br>Had maksimum: <strong>5MB</strong><br>Sila pilih fail yang lebih kecil.`,
                        confirmButtonColor: '#ea580c',
                    });
                    event.target.value = '';
                    document.getElementById('receiptPreviewSingle').classList.add('hidden');
                    return;
                }

                // Replace original file with compressed version (for images)
                if (compressedFile.size !== file.size) {
                    const dataTransfer = new DataTransfer();
                    dataTransfer.items.add(compressedFile);
                    event.target.files = dataTransfer.files;
                }

                // Show success message with compression info
                const infoEl = document.getElementById('resit_file_single_info');
                if (infoEl) {
                    infoEl.classList.remove('hidden');
                    infoEl.innerHTML =
                        `<i class="fas fa-check-circle text-green-500"></i>
                <span class="text-green-600">Fail: <strong>${fileName}</strong> (${formatFileSize(compressedFile.size)}) — OK</span>`;

                    if (compressedFile.size !== file.size && file.type.startsWith('image/')) {
                        const reduction = ((1 - compressedFile.size / file.size) * 100).toFixed(0);
                        infoEl.innerHTML +=
                            `<br><i class="fas fa-compress-alt text-blue-500"></i>
                    <span class="text-gray-600">Gambar telah dimampatkan ${reduction}% untuk muat naik lebih pantas.</span>`;
                    } else if (['heic', 'heif'].includes(fileExt)) {
                        infoEl.innerHTML += `<br><i class="fas fa-info-circle text-blue-500"></i>
                    <span class="text-gray-600">Fail HEIC dari iPhone telah ditukar ke JPEG.</span>`;
                    }
                }

                // Handle preview
                const preview = document.getElementById('receiptPreviewSingle');
                const img = document.getElementById('receiptImageSingle');

                if (compressedFile.type.startsWith('image/')) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        img.src = e.target.result;
                        preview.classList.remove('hidden');
                    };
                    reader.onerror = function() {
                        preview.classList.add('hidden');
                    };
                    reader.readAsDataURL(compressedFile);
                } else if (fileExt === 'pdf') {
                    preview.classList.add('hidden');
                    if (infoEl) {
                        infoEl.innerHTML += `<br><i class="fas fa-file-pdf text-red-500"></i>
                    <span class="text-gray-600">Fail PDF diterima. Tiada preview.</span>`;
                    }
                } else {
                    preview.classList.add('hidden');
                }
            });
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

        // ── Payment radio toggle ─────────────────────────────────────────
        const radioYes = document.querySelector('input[name="payment_status"][value="yes"]');
        const radioNo = document.querySelector('input[name="payment_status"][value="no"]');
        const yesContent = document.getElementById('yesContent');
        const noContent = document.getElementById('noContent');
        const ahliTypeInput = document.getElementById('ahli_type');
        const fileMain = document.getElementById('resit_file_main');
        const fileYes = document.getElementById('resit_file_yes');

        function togglePaymentContent() {
            const isYes = radioYes && radioYes.checked;
            const bankDetailsSection = document.getElementById('bankDetailsSection');
            const fileUploadText = document.getElementById('fileUploadText');
            const fileRequiredStar = document.getElementById('fileRequiredStar');
            const fileUploadHint = document.getElementById('fileUploadHint');

            if (isYes) {
                // Hide bank details for "Yes" option
                if (bankDetailsSection) bankDetailsSection.style.display = 'none';

                // Update message - file is OPTIONAL
                fileUploadText.innerHTML =
                    'Jika anda mempunyai resit atau bukti pembayaran sedia ada, sila muat naik. Permohonan anda akan diproses selepas resit/bukti diterima dan disahkan (jika ada).';

                // Remove required star and update hint
                if (fileRequiredStar) fileRequiredStar.style.display = 'none';
                if (fileUploadHint) fileUploadHint.innerHTML =
                    '⚠️ Nota: Muat naik resit adalah TIDAK WAJIB jika anda pernah mendaftar secara fizikal, tetapi disyorkan untuk mempercepatkan proses pengesahan.';
            } else {
                // Show bank details for "Tidak" option
                if (bankDetailsSection) bankDetailsSection.style.display = 'block';

                // Update message - file is REQUIRED
                fileUploadText.innerHTML =
                    'Sila buat pemindahan wang ke akaun di bawah terlebih dahulu. Selepas pembayaran berjaya, muat naik resit pembayaran sebelum menekan butang Hantar. Permohonan hanya akan diproses selepas resit diterima.';

                // Show required star and update hint
                if (fileRequiredStar) fileRequiredStar.style.display = 'inline';
                if (fileUploadHint) fileUploadHint.innerHTML =
                    '* Sila muat naik resit pembayaran untuk pengesahan (WAJIB).';
            }

            if (ahliTypeInput) ahliTypeInput.value = isYes ? 'Existing' : 'New';
        }

        radioYes?.addEventListener('change', togglePaymentContent);
        radioNo?.addEventListener('change', togglePaymentContent);
        // Default: "Tidak" selected
        if (radioNo) radioNo.checked = true;
        togglePaymentContent();

        // ── AJAX Submit with SweetAlert ──────────────────────────────────
        document.getElementById('publicRegisterForm').addEventListener('submit', function(e) {
            e.preventDefault();

            // Validate payment tab before submit
            const activeTab = document.querySelector('.tab-content.active');
            if (activeTab && activeTab.id === 'payment') {
                const radioYes = document.querySelector('input[name="payment_status"][value="yes"]');
                const radioNo = document.querySelector('input[name="payment_status"][value="no"]');

                if (!radioYes?.checked && !radioNo?.checked) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Pilihan Pembayaran',
                        text: 'Sila pilih sama ada anda pernah mendaftar secara fizikal atau belum.',
                        confirmButtonColor: '#ea580c'
                    });
                    return;
                }

                // File validation: REQUIRED for "Tidak", OPTIONAL for "Ya"
                const singleFile = document.getElementById('resit_file_single');
                const isYesSelected = radioYes?.checked;

                if (!isYesSelected) {
                    // "Tidak" option - file is REQUIRED
                    if (!singleFile || !singleFile.files || singleFile.files.length === 0) {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Resit Pembayaran Diperlukan',
                            text: 'Sila muat naik resit pembayaran untuk pendaftaran baharu. Ini adalah WAJIB.',
                            confirmButtonColor: '#ea580c'
                        });
                        return;
                    }
                }
                // "Ya" option - no file validation, file is optional
            }

            // Show loading
            Swal.fire({
                title: 'Memproses Pendaftaran...',
                text: 'Sila tunggu sebentar.',
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
                        // Build error HTML — support string or object errors
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

        // Expose globals needed by inline onchange/onclick attributes
        window.previewReceiptSingle = previewReceiptSingle;
        window.openZoom = openZoom;
        window.closeZoom = closeZoom;
    </script>
    <script>
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

        function combineWarisAddressForSubmit() {
            const noRumah = document.getElementById('waris_no_rumah')?.value.trim() || '';
            const jalan = document.getElementById('waris_jalan')?.value.trim() || '';
            const taman = document.getElementById('waris_taman')?.value.trim() || '';
            const poskod = document.getElementById('waris_poskod')?.value.trim() || '';
            const bandar = document.getElementById('waris_bandar')?.value.trim() || '';

            const parts = [];
            if (noRumah) parts.push(noRumah);
            if (jalan) parts.push(jalan);
            if (taman) parts.push(taman);
            if (poskod) parts.push(poskod);
            if (bandar) parts.push(bandar);

            const combinedAddress = parts.join(' / ');
            const hiddenInput = document.getElementById('combined_waris_address');
            if (hiddenInput) hiddenInput.value = combinedAddress;
            return combinedAddress;
        }

        function setupAddressUppercase() {
            const addressFields = ['no_rumah', 'jalan', 'taman', 'bandar_field', 'waris_no_rumah', 'waris_jalan',
                'waris_taman', 'waris_bandar'
            ];
            addressFields.forEach(fieldId => {
                const field = document.getElementById(fieldId);
                if (field) {
                    field.addEventListener('input', function(e) {
                        this.value = this.value.toUpperCase();
                    });
                }
            });
        }

        function autoFillWarisAddress() {
            // Get values from Pemohon address fields
            const pemohonAddresses = {
                noRumah: document.getElementById('no_rumah')?.value || '',
                jalan: document.getElementById('jalan')?.value || '',
                taman: document.getElementById('taman')?.value || '',
                poskod: document.getElementById('poskod_field')?.value || '',
                bandar: document.getElementById('bandar_field')?.value || ''
            };

            // Get Waris address fields
            const warisFields = {
                noRumah: document.getElementById('waris_no_rumah'),
                jalan: document.getElementById('waris_jalan'),
                taman: document.getElementById('waris_taman'),
                poskod: document.getElementById('waris_poskod'),
                bandar: document.getElementById('waris_bandar')
            };

            // Auto-fill only if waris field is empty
            if (warisFields.noRumah && !warisFields.noRumah.value && pemohonAddresses.noRumah) {
                warisFields.noRumah.value = pemohonAddresses.noRumah;
            }
            if (warisFields.jalan && !warisFields.jalan.value && pemohonAddresses.jalan) {
                warisFields.jalan.value = pemohonAddresses.jalan;
            }
            if (warisFields.taman && !warisFields.taman.value && pemohonAddresses.taman) {
                warisFields.taman.value = pemohonAddresses.taman;
            }
            if (warisFields.poskod && !warisFields.poskod.value && pemohonAddresses.poskod) {
                warisFields.poskod.value = pemohonAddresses.poskod;
            }
            if (warisFields.bandar && !warisFields.bandar.value && pemohonAddresses.bandar) {
                warisFields.bandar.value = pemohonAddresses.bandar;
            }

            combineWarisAddressForSubmit();
        }

        function setupWarisAddressWatchers() {
            const warisFields = ['waris_no_rumah', 'waris_jalan', 'waris_taman', 'waris_poskod', 'waris_bandar'];
            warisFields.forEach(fieldId => {
                const field = document.getElementById(fieldId);
                if (field) {
                    field.addEventListener('input', function() {
                        combineWarisAddressForSubmit();
                    });
                }
            });
        }

        function initAddressFeatures() {
            setupAddressUppercase();
            setupWarisAddressWatchers();
            autoFillWarisAddress();

            // Watch pemohon fields to auto-fill waris
            const pemohonFields = ['no_rumah', 'jalan', 'taman', 'poskod_field', 'bandar_field'];
            pemohonFields.forEach(fieldId => {
                const field = document.getElementById(fieldId);
                if (field) {
                    field.addEventListener('input', function() {
                        const warisNoRumah = document.getElementById('waris_no_rumah');
                        if (warisNoRumah && !warisNoRumah.value) {
                            autoFillWarisAddress();
                        }
                        combineAddressForSubmit();
                    });
                }
            });
        }

        // Auto-fill when clicking Next from Pemohon tab
        const nextToWarisBtn = document.querySelector('[data-next="waris"]');
        if (nextToWarisBtn) {
            nextToWarisBtn.addEventListener('click', function() {
                setTimeout(autoFillWarisAddress, 150);
            });
        }

        // Auto-fill in real-time when Pemohon types (only if waris empty)
        pemohonFields.forEach(fieldId => {
            const field = document.getElementById(fieldId);
            if (field) {
                field.addEventListener('input', function() {
                    const warisNoRumah = document.getElementById('waris_no_rumah');
                    if (warisNoRumah && !warisNoRumah.value) {
                        autoFillWarisAddress();
                    }
                });
            }
        });

        // Auto uppercase for waris_nama field
        const warisNamaInput = document.querySelector('input[name="waris_nama"]');
        if (warisNamaInput) {
            warisNamaInput.addEventListener('input', function(e) {
                this.value = this.value.toUpperCase();
            });
        }
    </script>

</body>

</html>
