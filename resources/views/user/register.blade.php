<!DOCTYPE html>
<html lang="ms">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pendaftaran Khairat</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
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

        /* connector line between steps */
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
            /* fa-check */
            font-family: 'Font Awesome 6 Free';
            font-weight: 900;
            font-size: 13px;
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
                <div class="step-item" data-tab="waris">
                    <div class="step-circle"><i class="fas fa-heart" style="font-size:13px;"></i></div>
                    <span class="step-label">Waris</span>
                </div>
                <div class="step-item" data-tab="login">
                    <div class="step-circle"><i class="fas fa-key" style="font-size:13px;"></i></div>
                    <span class="step-label">Maklumat Akaun</span>
                </div>
                <div class="step-item" data-tab="payment">
                    <div class="step-circle"><i class="fas fa-money-bill-wave" style="font-size:13px;"></i></div>
                    <span class="step-label">Pembayaran</span>
                </div>
            </div>

            <form action="{{ route('user.register') }}" method="POST" enctype="multipart/form-data" class="p-6"
                id="registrationForm">

                @csrf

                <!-- Hidden input for ahli_type -->
                <input type="hidden" name="ahli_type" id="ahli_type" value="New">
                <input type="hidden" name="alamat" id="combined_address">
                <input type="hidden" name="waris_alamat" id="combined_waris_address">

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
                                    IC)</label>
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
                                <input type="date" id="tarikh_lahir" name="tarikh_lahir"
                                    value="{{ old('tarikh_lahir') }}"
                                    class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-djariah-500 focus:border-djariah-500 transition-all"
                                    required>
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
                        <!-- 5 ADDRESS FIELDS - Auto uppercase, separated for user input, combined on submit -->
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
                        {{-- <p class="text-xs text-gray-400 mt-2"><i class="fas fa-info-circle"></i> Alamat akan
                            digabungkan secara automatik dengan tanda ' / ' apabila dihantar.</p> --}}
                    </section>

                    <section class="bg-white p-6 rounded-xl border border-gray-200 shadow-sm">
                        <h3
                            class="text-md font-semibold text-djariah-700 mb-5 flex items-center gap-2 uppercase tracking-wider">
                            <i class="fas fa-map-marked-alt"></i>
                            Maklumat Alamat & Kariah
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
                            class="next-tab bg-djariah-600 hover:bg-djariah-700 text-white py-3 px-10 rounded-xl font-bold transition-all shadow-lg hover:shadow-djariah-200 flex items-center gap-3 group"
                            data-next="waris">
                            Seterusnya
                            <i class="fas fa-arrow-right group-hover:translate-x-1 transition-transform"></i>
                        </button>
                    </div>
                </div>

                <!-- Tab 2: Waris Terdekat -->
                <div id="waris" class="tab-content">
                    <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center gap-2">
                        <i class="fas fa-heart text-djariah-600"></i>
                        Waris Terdekat
                    </h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="space-y-2">
                            <label class="block text-sm font-medium text-gray-700">
                                Nama
                            </label>
                            <input type="text" name="waris_nama" id="waris_nama" value="{{ old('waris_nama') }}"
                                class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-djariah-500 focus:border-djariah-500 transition-all duration-200 uppercase"
                                placeholder="Nama waris" required>
                        </div>

                        <div class="space-y-2">
                            <label class="block text-sm font-medium text-gray-700">
                                No. Kad Pengenalan
                            </label>
                            <input type="text" id="waris_ic" name="waris_ic" value="{{ old('waris_ic') }}"
                                class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-djariah-500 focus:border-djariah-500 transition-all duration-200"
                                placeholder="010413-03-1234" maxlength="14" required>
                        </div>

                        <div class="space-y-2 md:col-span-2">


                            <div class="grid grid-cols-1 md:grid-cols-5 gap-3">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">
                                        No. Rumah
                                    </label>
                                    <input type="text" id="waris_no_rumah" name="waris_no_rumah"
                                        value="{{ old('waris_no_rumah') }}"
                                        class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-djariah-500 focus:border-djariah-500 transition-all uppercase text-sm"
                                        placeholder="No. Rumah">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">
                                        Jalan
                                    </label>
                                    <input type="text" id="waris_jalan" name="waris_jalan"
                                        value="{{ old('waris_jalan') }}"
                                        class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-djariah-500 focus:border-djariah-500 transition-all uppercase text-sm"
                                        placeholder="Jalan">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">
                                        Taman/Kampung
                                    </label>
                                    <input type="text" id="waris_taman" name="waris_taman"
                                        value="{{ old('waris_taman') }}"
                                        class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-djariah-500 focus:border-djariah-500 transition-all uppercase text-sm"
                                        placeholder="Taman/Kampung">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">
                                        Poskod
                                    </label>
                                    <input type="text" id="waris_poskod" name="waris_poskod"
                                        value="{{ old('waris_poskod') }}"
                                        class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-djariah-500 focus:border-djariah-500 transition-all text-sm"
                                        placeholder="Poskod" maxlength="5">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">
                                        Bandar
                                    </label>
                                    <input type="text" id="waris_bandar" name="waris_bandar"
                                        value="{{ old('waris_bandar') }}"
                                        class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-djariah-500 focus:border-djariah-500 transition-all uppercase text-sm"
                                        placeholder="Bandar">
                                </div>
                            </div>
                        </div>

                        <div class="space-y-2">
                            <label class="block text-sm font-medium text-gray-700">
                                No. Tel (Pejabat)
                            </label>
                            <input type="text" name="waris_telefon_pejabat"
                                value="{{ old('waris_telefon_pejabat') }}"
                                class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-djariah-500 focus:border-djariah-500 transition-all duration-200"
                                placeholder="No. telefon pejabat">
                        </div>

                        <div class="space-y-2">
                            <label class="block text-sm font-medium text-gray-700">
                                No. Tel (Bimbit)
                            </label>
                            <input type="text" name="waris_telefon_bimbit"
                                value="{{ old('waris_telefon_bimbit') }}"
                                class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-djariah-500 focus:border-djariah-500 transition-all duration-200"
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
                </div>

                <!-- Tab 3: Login & Maklumat Akaun -->
                <div id="login" class="tab-content">
                    <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center gap-2">
                        <i class="fas fa-key text-djariah-600"></i> Maklumat Log Masuk
                    </h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
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

                <!-- Tab 4: Pembayaran -->
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
                                        secara fizikal), sila pilih "Ya" dan muat naik resit pembayaran sedia ada. Jika
                                        belum pernah
                                        mendaftar, pilih "Tidak" dan lengkapkan pendaftaran seperti biasa.
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
                                    </div>
                                </div>

                                <div id="paymentDefault" class="space-y-6">
                                    <div
                                        class="bg-blue-50 border border-blue-200 rounded-xl p-4 text-sm text-blue-800">
                                        <strong>Perhatian:</strong>
                                        <ul class="list-disc list-inside mt-2 space-y-1">
                                            <li>Sila buat pemindahan wang ke akaun di bawah terlebih dahulu.</li>
                                            <li>Selepas pembayaran berjaya, muat naik resit pembayaran sebelum menekan
                                                butang <b>Hantar</b>.</li>
                                            <li>Permohonan hanya akan diproses selepas resit diterima.</li>
                                            <li>DanaKhairat adalah platform digital yang mengurus transaksi dan rekod
                                                secara automatik.</li>
                                            <li>Bagi memastikan sistem pembayaran lebih selamat, tersusun dan boleh
                                                dijejak, semua bayaran diproses melalui akaun rasmi pengendali sistem.
                                            </li>
                                            <li>Yuran khairat tetap direkodkan dan disalurkan kepada tabung kariah yang
                                                dipilih oleh ahli.</li>
                                            <li>AJK kariah mempunyai akses penuh kepada laporan dan rekod transaksi.
                                            </li>
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

                                <div id="yesContent" class="hidden"></div>

                                <!-- Shared upload section — label/hint changes with Yes/No -->
                                <div class="space-y-3">
                                    <label id="upload_label" class="block text-sm font-medium text-gray-700">
                                        Muat Naik Resit Pembayaran
                                    </label>
                                    <div id="upload_hint_yes"
                                        class="hidden bg-blue-50 border border-blue-200 rounded-lg p-3 text-xs text-blue-700">
                                        <i class="fas fa-file-upload mr-1"></i>
                                        Sila muat naik resit atau bukti pembayaran sedia ada.
                                    </div>
                                    <div
                                        class="bg-yellow-50 border border-yellow-200 rounded-lg px-3 py-2 text-xs text-yellow-800 flex items-center gap-2">
                                        <i class="fas fa-exclamation-circle"></i>
                                        Had saiz fail: <strong>5MB</strong>. Format dibenarkan: JPG, PNG, PDF sahaja.
                                    </div>
                                    <!-- Updated accept attribute - no camera -->
                                    <input type="file" id="resit_file" name="resit_file"
                                        accept="image/jpeg,image/jpg,image/png,.pdf" onchange="previewReceipt(event)"
                                        class="block w-full text-sm file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:bg-djariah-600 file:text-white hover:file:bg-djariah-700">
                                    <div id="resit_file_info" class="hidden text-xs mt-1 flex items-center gap-2">
                                    </div>
                                    <div id="receiptPreview" class="hidden mt-4">
                                        <p class="text-xs text-gray-500 mb-2">Preview Resit:</p>
                                        <img id="receiptImage"
                                            class="w-48 rounded-xl border shadow-sm cursor-pointer hover:scale-105 transition"
                                            onclick="openZoom(this)">
                                    </div>
                                    <p id="upload_note" class="text-xs text-gray-400">* Sila muat naik resit
                                        pembayaran untuk pengesahan.</p>
                                </div>

                                <hr class="border-gray-100">
                                <p class="text-xs text-gray-400">Jumlah bayaran akan dikira secara automatik selepas
                                    masjid
                                    dipilih.</p>
                            </div>
                        </div>

                        <div class="flex flex-col sm:flex-row justify-between gap-3 mt-4">
                            <button type="button"
                                class="prev-tab w-full sm:w-auto justify-center bg-gray-200 hover:bg-gray-300 text-gray-700 py-2.5 px-6 rounded-lg font-medium transition-colors flex items-center gap-2"
                                data-prev="login">
                                <i class="fas fa-arrow-left"></i> Sebelumnya
                            </button>
                            <button type="submit"
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
        const TAB_ORDER = ['pemohon', 'waris', 'login', 'payment'];

        function switchTab(tabId) {
            const currentIdx = TAB_ORDER.indexOf(tabId);

            // Update stepper visual states
            document.querySelectorAll('.step-item').forEach((item, idx) => {
                item.classList.remove('active', 'done');
                if (idx < currentIdx) item.classList.add('done');
                else if (idx === currentIdx) item.classList.add('active');
            });

            // Update done connector lines (handled via CSS on .done)

            // Switch content panels
            document.querySelectorAll('.tab-content').forEach(c => c.classList.remove('active'));
            document.getElementById(tabId).classList.add('active');
        }

        // Step items are NOT clickable — navigation only via Next/Prev buttons

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

            // Clear previous errors
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
                // Age check
                const umur = document.getElementById('umur');
                if (umur && umur.value && parseInt(umur.value) < 24) {
                    showError(ic, 'Pendaftaran hanya dibenarkan untuk umur 24 tahun ke atas.');
                    errorMessages.push('Umur pemohon mesti 24 tahun ke atas.');
                    valid = false;
                }
            }

            // IC format validation (waris)
            if (tabId === 'waris') {
                const wic = document.getElementById('waris_ic');
                if (wic && wic.value && !/^\d{6}-\d{2}-\d{4}$/.test(wic.value)) {
                    showError(wic, 'Format IC tidak sah. Contoh: 010413-03-1234');
                    errorMessages.push('Format IC Waris tidak sah.');
                    valid = false;
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
                // Show SweetAlert with all error messages
                Swal.fire({
                    icon: 'warning',
                    title: 'Sila Semak Maklumat',
                    html: '<ul class="text-left text-sm list-disc list-inside space-y-1 text-red-700">' +
                        errorMessages.map(m => `<li>${m}</li>`).join('') +
                        '</ul>',
                    confirmButtonText: 'OK, Semak Semula',
                    confirmButtonColor: '#ea580c',
                });
                // Scroll to first error
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
        let icIsValid = false; // tracks whether IC passed server duplicate check

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
                    // If endpoint not available, don't block user
                    setIcStatus('hide');
                    icIsValid = true;
                });
        }

        // ── File size display helper ─────────────────────────────────────
        const MAX_FILE_BYTES = 5 * 1024 * 1024; // 5MB

        function formatFileSize(bytes) {
            if (bytes < 1024) return bytes + ' B';
            if (bytes < 1024 * 1024) return (bytes / 1024).toFixed(1) + ' KB';
            return (bytes / (1024 * 1024)).toFixed(2) + ' MB';
        }

        function showFileInfo(infoElId, file) {
            const el = document.getElementById(infoElId);
            if (!el || !file) return true; // Return true if element not found

            const MAX_FILE_BYTES = 5 * 1024 * 1024; // 5MB
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

                if (!validateTab(currentTab.id)) return;
                switchTab(button.getAttribute('data-next'));
            });
        });

        document.querySelectorAll('.prev-tab').forEach(button => {
            button.addEventListener('click', () => {
                switchTab(button.getAttribute('data-prev'));
            });
        });

        // Negeri, Poskod, Bandar, Masjid
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
                });
        });

        // Auto-fill IC
        (function() {
            const icInput = document.getElementById('ic_number');
            const tarikhLahirInput = document.getElementById('tarikh_lahir');
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
                    umurInput.value = '';
                    return;
                }
                let birthDate = parseMalaysiaIC(icValue);
                if (birthDate) {
                    tarikhLahirInput.value = formatDateToInput(birthDate);
                    umurInput.value = calculateAge(birthDate);
                }
            }

            // ── IC formatter helper ──────────────────────────────────────
            function formatIC(raw) {
                let d = raw.replace(/\D/g, '').slice(0, 12);
                if (d.length > 8) return d.slice(0, 6) + '-' + d.slice(6, 8) + '-' + d.slice(8);
                if (d.length > 6) return d.slice(0, 6) + '-' + d.slice(6);
                return d;
            }

            icInput.addEventListener('input', function(e) {
                let pos = this.selectionStart;
                let before = this.value;
                this.value = formatIC(this.value);
                // Adjust cursor if a dash was inserted before cursor
                let added = this.value.length - before.length;
                if (added > 0) pos += added;
                try {
                    this.setSelectionRange(pos, pos);
                } catch (e) {}
                updateDobAndAge();
                // Trigger IC duplicate check with debounce
                clearTimeout(icCheckTimer);
                icIsValid = false;
                const cleaned = this.value.replace(/\D/g, '');
                if (cleaned.length === 12) {
                    icCheckTimer = setTimeout(() => checkIcDuplicate(this.value), 600);
                } else {
                    setIcStatus('hide');
                }
            });

            // ── Waris IC formatter ───────────────────────────────────────
            const warisIcInput = document.getElementById('waris_ic');
            if (warisIcInput) {
                warisIcInput.addEventListener('input', function() {
                    let pos = this.selectionStart;
                    let before = this.value;
                    this.value = formatIC(this.value);
                    let added = this.value.length - before.length;
                    if (added > 0) pos += added;
                    try {
                        this.setSelectionRange(pos, pos);
                    } catch (e) {}
                });
                // Format pre-filled value (old())
                if (warisIcInput.value) warisIcInput.value = formatIC(warisIcInput.value);
            }

            if (icInput.value) {
                icInput.value = formatIC(icInput.value);
                updateDobAndAge();
                const cleaned = icInput.value.replace(/\D/g, '');
                if (cleaned.length === 12) checkIcDuplicate(icInput.value);
            }
        })();

        // Masjid change - load bank info and harga

        document.getElementById('masjid').addEventListener('change', function() {
            let masjidId = this.value;
            let selectedOption = this.options[this.selectedIndex];
            let masjidName = selectedOption ? selectedOption.text : '';

            if (!masjidId) return;

            // Create or update hidden input for masjid_name
            let hiddenInput = document.querySelector('input[name="masjid_name"]');
            if (!hiddenInput) {
                hiddenInput = document.createElement('input');
                hiddenInput.type = 'hidden';
                hiddenInput.name = 'masjid_name';
                this.form.appendChild(hiddenInput);
            }
            hiddenInput.value = masjidName;

            console.log('Selected Masjid:', masjidId, masjidName); // For debugging

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
                    document.getElementById('bayaran_tahunan').innerText = 'RM ' + bayaranTahunan.toFixed(2);
                    document.getElementById('yuran_pendaftaran').innerText = 'RM ' + yuranDaftar.toFixed(2);
                    document.getElementById('jumlah_bayaran').innerText = 'RM ' + jumlah.toFixed(2);
                })
                .catch(() => {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Perhatian',
                        text: 'Harga khairat belum ditetapkan oleh masjid ini. Sila hubungi pentadbir.',
                        confirmButtonColor: '#ea580c'
                    });
                });
        });

        // Radio button toggle functionality
        const radioYes = document.querySelector('input[name="payment_status"][value="yes"]');
        const radioNo = document.querySelector('input[name="payment_status"][value="no"]');
        const paymentDefault = document.getElementById('paymentDefault');
        const ahliTypeInput = document.getElementById('ahli_type');

        function togglePaymentContent() {
            const isYes = radioYes && radioYes.checked;

            // Show/hide the full payment block (bank info, fee summary)
            if (paymentDefault) {
                isYes ? paymentDefault.classList.add('hidden') : paymentDefault.classList.remove('hidden');
            }

            // Update ahli_type
            if (ahliTypeInput) ahliTypeInput.value = isYes ? 'Existing' : 'New';

            // Swap upload label & hint
            document.getElementById('upload_label').innerHTML = isYes ?
                'Muat Naik Resit / Bukti Pembayaran <span class="text-red-500">*</span>' :
                'Muat Naik Resit Pembayaran';
            document.getElementById('upload_note').innerHTML = isYes ?
                '⚠️ <strong>Penting:</strong> Sila muat naik resit atau bukti pembayaran sedia ada.' :
                '* Sila muat naik resit pembayaran untuk pengesahan.';

            const hintYes = document.getElementById('upload_hint_yes');
            isYes ? hintYes.classList.remove('hidden') : hintYes.classList.add('hidden');

            // Clear any existing preview when toggling
            document.getElementById('resit_file').value = '';
            document.getElementById('receiptPreview').classList.add('hidden');
            document.getElementById('resit_file_info').classList.add('hidden');
        }

        if (radioYes) radioYes.addEventListener('change', togglePaymentContent);
        if (radioNo) radioNo.addEventListener('change', togglePaymentContent);
        if (radioNo) radioNo.checked = true;
        togglePaymentContent();

        // Single unified preview function for the shared file input
        function previewReceipt(event) {
            const file = event.target.files[0];
            if (!file) return;
            const sizeOk = showFileInfo('resit_file_info', file);
            if (!sizeOk) {
                Swal.fire({
                    icon: 'error',
                    title: 'Fail Terlalu Besar',
                    html: `Saiz fail yang dipilih ialah <strong>${formatFileSize(file.size)}</strong>.<br>Had maksimum ialah <strong>5MB</strong>.<br>Sila pilih fail yang lebih kecil.`,
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

        window.previewReceipt = previewReceipt;
        window.openZoom = openZoom;
        window.closeZoom = closeZoom;
        window.loadBankInfo = loadBankInfo;

        function compressImage(file, callback) {
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

                    // Max dimensions
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

                    canvas.toBlob((blob) => {
                        const compressedFile = new File([blob], file.name, {
                            type: 'image/jpeg',
                            lastModified: Date.now()
                        });
                        callback(compressedFile);
                    }, 'image/jpeg', 0.7); // 70% quality
                };
            };
        }

        // Modify previewReceipt to use compression
        function previewReceipt(event) {
            const file = event.target.files[0];
            if (!file) return;

            compressImage(file, (compressedFile) => {
                // Use compressedFile instead of original file
                const sizeOk = showFileInfo('resit_file_info', compressedFile);
                if (!sizeOk) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Fail Terlalu Besar',
                        text: 'Sila pilih fail yang lebih kecil walaupun selepas kompresi.',
                        confirmButtonColor: '#ea580c',
                    });
                    event.target.value = '';
                    return;
                }

                // Replace the file in input with compressed version
                const dataTransfer = new DataTransfer();
                dataTransfer.items.add(compressedFile);
                document.getElementById('resit_file').files = dataTransfer.files;

                // Show preview
                const preview = document.getElementById('receiptPreview');
                const img = document.getElementById('receiptImage');
                if (compressedFile.type.startsWith('image/')) {
                    img.src = URL.createObjectURL(compressedFile);
                    preview.classList.remove('hidden');
                }
            });
        }
    </script>

    <script>
        // Override the form submission to use AJAX with SweetAlert
        // Override the form submission to use AJAX with SweetAlert
        document.querySelector('form').addEventListener('submit', function(e) {
            e.preventDefault();

            // First validate the current tab (payment tab)
            const activeTab = document.querySelector('.tab-content.active');
            if (activeTab.id === 'payment') {
                // Validate required fields in payment tab
                const radioYes = document.querySelector('input[name="payment_status"][value="yes"]');
                const radioNo = document.querySelector('input[name="payment_status"][value="no"]');
                const fileInput = document.getElementById('resit_file');

                // Check if payment option is selected
                if (!radioYes.checked && !radioNo.checked) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Pilihan Pembayaran',
                        text: 'Sila pilih sama ada anda pernah mendaftar secara fizikal atau belum.',
                        confirmButtonColor: '#ea580c'
                    });
                    return;
                }

                // For new members (No), file is required
                if (radioNo.checked && (!fileInput.files || fileInput.files.length === 0)) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Resit Pembayaran Diperlukan',
                        text: 'Sila muat naik resit pembayaran untuk pendaftaran baharu.',
                        confirmButtonColor: '#ea580c'
                    });
                    return;
                }

                // Also validate masjid is selected
                const masjidSelect = document.getElementById('masjid');
                if (!masjidSelect.value) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Masjid Belum Dipilih',
                        text: 'Sila pilih masjid/surau terlebih dahulu.',
                        confirmButtonColor: '#ea580c'
                    });
                    return;
                }
            }

            // Show loading state
            Swal.fire({
                title: 'Memproses Pendaftaran...',
                text: 'Sila tunggu sebentar.',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            // Create FormData object
            const formData = new FormData(this);

            // Submit via AJAX
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
                        // ========== FIX: Properly handle error messages ==========
                        let errorMessage = '';

                        if (data.errors) {
                            if (typeof data.errors === 'string') {
                                errorMessage = data.errors;
                            } else if (typeof data.errors === 'object') {
                                // Get all error messages from the object
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

                    let errorMessage = 'Terjadi masalah semasa memproses pendaftaran. Sila cuba lagi.';

                    if (error.response && error.response.data) {
                        const data = error.response.data;
                        if (data.errors) {
                            if (typeof data.errors === 'string') {
                                errorMessage = data.errors;
                            } else if (typeof data.errors === 'object') {
                                const errorList = Object.values(data.errors).flat();
                                errorMessage = errorList.join('<br>');
                            }
                        } else if (data.message) {
                            errorMessage = data.message;
                        }
                    }

                    Swal.fire({
                        icon: 'error',
                        title: 'Ralat Sistem',
                        html: `<p>${errorMessage}</p>`,
                        confirmButtonColor: '#ea580c'
                    });
                });
        });

        // ========== COMBINE ADDRESS BEFORE SUBMIT ==========
        function combineAddressForSubmit() {
            const noRumah = document.getElementById('no_rumah')?.value.trim() || '';
            const jalan = document.getElementById('jalan')?.value.trim() || '';
            const taman = document.getElementById('taman')?.value.trim() || '';
            const poskod = document.getElementById('poskod_field')?.value.trim() || '';
            const bandar = document.getElementById('bandar_field')?.value.trim() || '';

            // Build array of non-empty parts
            const parts = [];
            if (noRumah) parts.push(noRumah);
            if (jalan) parts.push(jalan);
            if (taman) parts.push(taman);
            if (poskod) parts.push(poskod);
            if (bandar) parts.push(bandar);

            // Join with ' / ' separator
            const combinedAddress = parts.join(' / ');

            console.log('Combined Address:', combinedAddress); // Debug

            // Set the hidden input value
            const hiddenInput = document.getElementById('combined_address');
            if (hiddenInput) {
                hiddenInput.value = combinedAddress;
            }

            return combinedAddress;
        }


        // Auto uppercase for address fields
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

        // Override form submit to combine address first
        // const form = document.getElementById('registrationForm');
        // if (form) {
        //     form.addEventListener('submit', function(e) {
        //         // Combine address fields into hidden input before submission
        //         combineAddressForSubmit();
        //         // The form will submit normally with the combined address in the hidden field
        //     });
        // }

        const registrationForm = document.getElementById('registrationForm');
        if (registrationForm) {
            registrationForm.addEventListener('submit', function(e) {
                e.preventDefault();

                // ========== CONVERT NAMA TO UPPERCASE ==========
                const namaField = this.querySelector('input[name="nama"]');
                if (namaField && namaField.value) {
                    namaField.value = namaField.value.toUpperCase();
                }

                // Also convert waris_nama
                const warisNamaField = this.querySelector('input[name="waris_nama"]');
                if (warisNamaField && warisNamaField.value) {
                    warisNamaField.value = warisNamaField.value.toUpperCase();
                }

                // Also convert other fields as needed
                const fieldsToUppercase = [
                    'nama',
                    'waris_nama',
                    'jantina',
                    'bangsa',
                    'statususer'
                ];

                fieldsToUppercase.forEach(fieldName => {
                    const field = this.querySelector(`input[name="${fieldName}"]`);
                    if (field && field.value) {
                        field.value = field.value.toUpperCase();
                    }
                });

                // FIRST: Combine address before anything else
                const combinedAddress = combineAddressForSubmit();

                // Debug: Check if address is combined
                console.log('Submitting with address:', combinedAddress);

                // Now validate the current tab (payment tab)
                const activeTab = document.querySelector('.tab-content.active');
                if (activeTab && activeTab.id === 'payment') {
                    const radioYes = document.querySelector('input[name="payment_status"][value="yes"]');
                    const radioNo = document.querySelector('input[name="payment_status"][value="no"]');
                    const fileInput = document.getElementById('resit_file');

                    if (!radioYes.checked && !radioNo.checked) {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Pilihan Pembayaran',
                            text: 'Sila pilih sama ada anda pernah mendaftar secara fizikal atau belum.',
                            confirmButtonColor: '#ea580c'
                        });
                        return;
                    }

                    if (radioNo.checked && (!fileInput.files || fileInput.files.length === 0)) {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Resit Pembayaran Diperlukan',
                            text: 'Sila muat naik resit pembayaran untuk pendaftaran baharu.',
                            confirmButtonColor: '#ea580c'
                        });
                        return;
                    }

                    const masjidSelect = document.getElementById('masjid');
                    if (!masjidSelect.value) {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Masjid Belum Dipilih',
                            text: 'Sila pilih masjid/surau terlebih dahulu.',
                            confirmButtonColor: '#ea580c'
                        });
                        return;
                    }
                }

                // Show loading state
                Swal.fire({
                    title: 'Memproses Pendaftaran...',
                    text: 'Sila tunggu sebentar.',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });

                // Create FormData object
                const formData = new FormData(this);

                // Debug: Check what's being sent
                for (let pair of formData.entries()) {
                    if (pair[0] === 'alamat') {
                        console.log('alamat field value:', pair[1]);
                    }
                }

                // Submit via AJAX
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
                            // ========== FIX: Properly handle error messages ==========
                            let errorMessage = '';

                            if (data.errors) {
                                if (typeof data.errors === 'string') {
                                    errorMessage = data.errors;
                                } else if (typeof data.errors === 'object') {
                                    // Get all error messages from the object
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

                        let errorMessage = 'Terjadi masalah semasa memproses pendaftaran. Sila cuba lagi.';

                        if (error.response && error.response.data) {
                            const data = error.response.data;
                            if (data.errors) {
                                if (typeof data.errors === 'string') {
                                    errorMessage = data.errors;
                                } else if (typeof data.errors === 'object') {
                                    const errorList = Object.values(data.errors).flat();
                                    errorMessage = errorList.join('<br>');
                                }
                            } else if (data.message) {
                                errorMessage = data.message;
                            }
                        }

                        Swal.fire({
                            icon: 'error',
                            title: 'Ralat Sistem',
                            html: `<p>${errorMessage}</p>`,
                            confirmButtonColor: '#ea580c'
                        });
                    });
            });
        }

        // Initialize uppercase for address fields
        setupAddressUppercase();

        const warisNama = document.getElementById('waris_nama');
        if (warisNama) {
            warisNama.addEventListener('input', function(e) {
                this.value = this.value.toUpperCase();
            });
        }

        // Auto uppercase for waris_alamat
        const warisAlamat = document.getElementById('waris_alamat');
        if (warisAlamat) {
            warisAlamat.addEventListener('input', function(e) {
                this.value = this.value.toUpperCase();
            });
        }



        const pemohonAddressFields = ['no_rumah', 'jalan', 'taman', 'poskod_field', 'bandar_field'];
        pemohonAddressFields.forEach(fieldId => {
            const field = document.getElementById(fieldId);
            if (field) {
                field.addEventListener('input', function() {
                    const warisAlamatField = document.getElementById('waris_alamat');
                    // Only auto-fill if waris address is still empty
                    if (warisAlamatField && !warisAlamatField.value) {
                        const pemohonAddress = getPemohonAddress();
                        if (pemohonAddress) {
                            warisAlamatField.value = pemohonAddress.toUpperCase();
                        }
                    }
                });
            }
        });

        // Call auto-fill when entering waris tab
        const warisTabButton = document.querySelector('[data-next="waris"]');
        if (warisTabButton) {
            warisTabButton.addEventListener('click', function() {
                // Small delay to ensure tab is switched
                setTimeout(autoFillWarisAddress, 100);
            });
        }

        // Also auto-fill when page loads if waris address is empty
        document.addEventListener('DOMContentLoaded', function() {
            autoFillWarisAddress();
        });

        // ========== WARIS ADDRESS - SAME METHOD AS PEMOHON ==========

        // Auto uppercase for waris address fields
        function setupWarisAddressUppercase() {
            const warisAddressFields = ['waris_no_rumah', 'waris_jalan', 'waris_taman', 'waris_bandar'];
            warisAddressFields.forEach(fieldId => {
                const field = document.getElementById(fieldId);
                if (field) {
                    field.addEventListener('input', function(e) {
                        this.value = this.value.toUpperCase();
                    });
                }
            });
        }

        // Function to get combined waris address (same method as combineAddressForSubmit)
        function combineWarisAddressForSubmit() {
            const noRumah = document.getElementById('waris_no_rumah')?.value.trim() || '';
            const jalan = document.getElementById('waris_jalan')?.value.trim() || '';
            const taman = document.getElementById('waris_taman')?.value.trim() || '';
            const poskod = document.getElementById('waris_poskod')?.value.trim() || '';
            const bandar = document.getElementById('waris_bandar')?.value.trim() || '';

            // Build array of non-empty parts
            const parts = [];
            if (noRumah) parts.push(noRumah);
            if (jalan) parts.push(jalan);
            if (taman) parts.push(taman);
            if (poskod) parts.push(poskod);
            if (bandar) parts.push(bandar);

            // Join with ' / ' separator (same as pemohon)
            const combinedAddress = parts.join(' / ');

            // Set the hidden input value
            const hiddenInput = document.getElementById('combined_waris_address');
            if (hiddenInput) {
                hiddenInput.value = combinedAddress;
            }

            return combinedAddress;
        }

        // Function to get pemohon address (for auto-fill)
        function getPemohonAddressParts() {
            const noRumah = document.getElementById('no_rumah')?.value.trim() || '';
            const jalan = document.getElementById('jalan')?.value.trim() || '';
            const taman = document.getElementById('taman')?.value.trim() || '';
            const poskod = document.getElementById('poskod_field')?.value.trim() || '';
            const bandar = document.getElementById('bandar_field')?.value.trim() || '';

            return {
                noRumah,
                jalan,
                taman,
                poskod,
                bandar
            };
        }

        // Auto fill waris address from pemohon (only if empty)
        function autoFillWarisAddress() {
            const pemohon = getPemohonAddressParts();

            const warisNoRumah = document.getElementById('waris_no_rumah');
            const warisJalan = document.getElementById('waris_jalan');
            const warisTaman = document.getElementById('waris_taman');
            const warisPoskod = document.getElementById('waris_poskod');
            const warisBandar = document.getElementById('waris_bandar');

            if (warisNoRumah && !warisNoRumah.value && pemohon.noRumah) {
                warisNoRumah.value = pemohon.noRumah;
            }
            if (warisJalan && !warisJalan.value && pemohon.jalan) {
                warisJalan.value = pemohon.jalan;
            }
            if (warisTaman && !warisTaman.value && pemohon.taman) {
                warisTaman.value = pemohon.taman;
            }
            if (warisPoskod && !warisPoskod.value && pemohon.poskod) {
                warisPoskod.value = pemohon.poskod;
            }
            if (warisBandar && !warisBandar.value && pemohon.bandar) {
                warisBandar.value = pemohon.bandar;
            }

            // Update hidden combined field
            combineWarisAddressForSubmit();
        }

        // Force copy pemohon address to waris (overwrite all)
        function copyPemohonAddressToWaris() {
            const pemohon = getPemohonAddressParts();

            document.getElementById('waris_no_rumah').value = pemohon.noRumah;
            document.getElementById('waris_jalan').value = pemohon.jalan;
            document.getElementById('waris_taman').value = pemohon.taman;
            document.getElementById('waris_poskod').value = pemohon.poskod;
            document.getElementById('waris_bandar').value = pemohon.bandar;

            combineWarisAddressForSubmit();

            Swal.fire({
                icon: 'success',
                title: 'Alamat Disalin',
                text: 'Alamat pemohon telah disalin ke alamat waris.',
                timer: 1500,
                showConfirmButton: false
            });
        }

        // Watch waris fields to update hidden input on change
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

        // Initialize waris address
        function initWarisAddress() {
            setupWarisAddressUppercase();
            setupWarisAddressWatchers();
            autoFillWarisAddress();


        }

        // Call when page loads
        document.addEventListener('DOMContentLoaded', function() {
            initWarisAddress();
        });

        // Auto-fill when entering waris tab
        const warisNextBtn = document.querySelector('[data-next="waris"]');
        if (warisNextBtn) {
            warisNextBtn.addEventListener('click', function() {
                setTimeout(autoFillWarisAddress, 150);
            });
        }

        // Also update waris address when pemohon address changes (only if waris empty)
        const pemohonFields = ['no_rumah', 'jalan', 'taman', 'poskod_field', 'bandar_field'];
        pemohonFields.forEach(fieldId => {
            const field = document.getElementById(fieldId);
            if (field) {
                field.addEventListener('input', function() {
                    // Only auto-fill if waris address fields are still empty
                    const warisNoRumah = document.getElementById('waris_no_rumah');
                    if (warisNoRumah && !warisNoRumah.value) {
                        autoFillWarisAddress();
                    }
                });
            }
        });

        // Update combined waris address before main form submit
        const mainFormSubmit = document.getElementById('registrationForm');
        if (mainFormSubmit) {
            mainFormSubmit.addEventListener('submit', function() {
                combineWarisAddressForSubmit();
            });
        }
    </script>

</body>

</html>
