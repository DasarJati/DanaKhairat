@extends ('layouts.app')
@section('title', '')
@section('content')

<!DOCTYPE html>
<html lang="ms">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Pendaftaran Keahlian</title>
@vite(['resources/css/app.css', 'resources/js/app.js'])
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
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
    .tab-button {
        transition: all 0.3s ease;
    }
    .tab-button.active {
        background: linear-gradient(135deg, #f97316, #ea580c);
        color: white;
        box-shadow: 0 4px 6px -1px rgba(249, 115, 22, 0.3);
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
                            <div class="w-14 h-14 rounded-full overflow-hidden flex items-center justify-center">
                                <img src="{{ asset('images/logos.png') }}" alt="Logo Masjid" class="w-full h-full object-cover">
                            </div>


                            <div>
                                <h5 class="font-bold text-2xl text-gray-800 leading-tight">Djariah</h5>
                                <p class="text-sm text-gray-600">eKhairat</p>
                            </div>
                        </div>

                        <a href="/login" class="text-sm text-djariah-600 hover:text-djariah-700 font-medium transition-colors flex items-center gap-1">
                            <i class="fas fa-sign-in-alt"></i>
                            Log Masuk
                        </a>
                    </div>

                    <div>
                        <h6 class="text-3xl font-extrabold text-gray-800 mb-2">Pendaftaran Keahlian</h6>
                        <p class="text-gray-600 text-sm">Sertai komuniti eKhairat Djariah kami</p>
                    </div>

             </div>

        <!-- Form Container -->
        <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
            <!-- Tab Navigation -->
            <div class="flex border-b border-gray-200 overflow-x-auto">
                <button class="tab-button flex-1 py-3 px-4 text-sm font-medium text-center border-b-2 border-transparent active" data-tab="pemohon">
                    <i class="fas fa-user mr-2"></i>Pemohon
                </button>
                <button class="tab-button flex-1 py-3 px-4 text-sm font-medium text-center border-b-2 border-transparent" data-tab="waris">
                    <i class="fas fa-heart mr-2"></i>Waris
                </button>
                <button class="tab-button flex-1 py-3 px-4 text-sm font-medium text-center border-b-2 border-transparent" data-tab="login">
                    <i class="fas fa-key mr-2"></i>Maklumat Akaun
                </button>
                <button class="tab-button flex-1 py-3 px-4 text-sm font-medium text-center border-b-2 border-transparent" data-tab="payment">
                    <i class="fas fa-key mr-2"></i>Pembayaran
                </button>
            </div>

            <form action="{{ route('user.register') }}" method="POST" class="p-6">
                @csrf

                <!-- Tab 1: Butir-butir Pemohon -->
                <div id="pemohon" class="tab-content active">
                    <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center gap-2">
                        <i class="fas fa-user-circle text-djariah-600"></i>
                        Butir-butir Pemohon
                    </h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div class="space-y-2">
                            <label class="block text-sm font-medium text-gray-700">
                                Nama Penuh (Huruf Besar)
                            </label>
                            <input type="text" name="nama" value="{{ old('nama') }}"
                                class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-djariah-500 focus:border-djariah-500 transition-all duration-200 uppercase" 
                                placeholder="MASUKKAN NAMA PENUH" required>
                        </div>

                        <div class="space-y-2">
                            <label class="block text-sm font-medium text-gray-700">
                                No. Kad Pengenalan
                            </label>
                            <input type="text" name="ic_number" value="{{ old('ic_number') }}"
                                class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-djariah-500 focus:border-djariah-500 transition-all duration-200"
                                placeholder="Contoh: 901231-01-1234" required>
                        </div>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-5">

                        <div class="space-y-2">
                            <label class="block text-sm font-medium text-gray-700">
                                Tarikh Lahir
                            </label>
                            <input type="date" name="tarikh_lahir" value="{{ old('tarikh_lahir') }}"
                                class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-djariah-500 focus:border-djariah-500 transition-all duration-200" required>
                        </div>

                        <div class="space-y-2">
                            <label class="block text-sm font-medium text-gray-700">
                                Umur (tahun)
                            </label>
                            <input type="number" id="umur" name="umur" value="{{ old('umur') }}"
                                class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-djariah-500 focus:border-djariah-500 transition-all duration-200"
                                readonly required>
                        </div>


                        <div class="space-y-2">
                            <label class="block text-sm font-medium text-gray-700">
                                Jantina
                            </label>
                            <select name="jantina" class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-djariah-500 focus:border-djariah-500 transition-all duration-200">
                                <option value="">-- Sila Pilih --</option>
                                <option value="LELAKI">LELAKI</option>
                                <option value="PEREMPUAN">PEREMPUAN</option>
                            </select>
                        </div>

                        <div class="space-y-2">
                            <label class="block text-sm font-medium text-gray-700">
                                Bangsa
                            </label>
                            <select name="bangsa" class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-djariah-500 focus:border-djariah-500 transition-all duration-200">
                                <option value="">-- Sila Pilih --</option>
                                <option value="MELAYU">MELAYU</option>
                                <option value="CINA">CINA</option>
                                <option value="INDIA">INDIA</option>
                                <option value="LAIN">LAIN-LAIN</option>
                            </select>
                        </div>
                        <div class="space-y-2">
                            <label class="block text-sm font-medium text-gray-700">
                                Negeri
                            </label>
                            <select id="negeri" class="w-full border border-gray-300 rounded-lg px-4 py-2.5 
                            focus:ring-2 focus:ring-djariah-500 focus:border-djariah-500 transition-all duration-200">
                                <option value="">-- Pilih Negeri --</option>
                                @foreach ($negeri as $n)
                                    <option value="{{ $n->nama }}">{{ $n->nama }}</option>
                                @endforeach
                            </select>
                        </div> 
                        <div class="space-y-2">
                            <label class="block text-sm font-medium text-gray-700">
                                Status
                            </label>
                            <select name="statususer" class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-djariah-500 focus:border-djariah-500 transition-all duration-200">
                                <option value="">-- Sila Pilih --</option>
                                <option value="BUJANG">BUJANG</option>
                                <option value="BERKELUARGA">BERKELUARGA</option>
                            </select>
                        </div>
                    </div>

                    <div class="mt-4 space-y-4">
                        <div class="space-y-2">
                            <label class="block text-sm font-medium text-gray-700">
                                Alamat Kediaman
                            </label>
                            <textarea name="alamat" rows="2" required
                                class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-djariah-500 focus:border-djariah-500 transition-all duration-200 resize-none"
                                placeholder="Masukkan alamat penuh">{{ old('alamat') }}</textarea>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div class="space-y-2">
                                <label class="block text-sm font-medium text-gray-700">
                                    Bandar
                                </label>

                                <select id="bandar"
                                    class="w-full border border-gray-300 rounded-lg px-4 py-2.5
                                        focus:ring-2 focus:ring-djariah-500 focus:border-djariah-500
                                        transition-all duration-200">
                                    <option value="">-- Pilih Negeri dulu --</option>
                                </select>
                            </div>
                            <div class="space-y-2">
                            <label class="block text-sm font-medium text-gray-700">
                                Poskod
                            </label>
                            <input type="text" id="poskod" name="poskod" value="{{ old('poskod') }}"
                                class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-djariah-500 focus:border-djariah-500 transition-all duration-200"
                                readonly required>
                        </div>
                            <div class="space-y-2">
                                <label class="block text-sm font-medium text-gray-700">
                                    Kariah
                                </label>
                                <select id="kariah" name="kariah" required
                                    class="w-full border border-gray-300 rounded-lg px-4 py-2.5
                                        focus:ring-2 focus:ring-djariah-500 focus:border-djariah-500
                                        transition-all duration-200">
                                    <option value="">-- Pilih Bandar dulu --</option>
                                </select>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="space-y-2">
                                <label class="block text-sm font-medium text-gray-700">
                                    No. Tel (Bimbit)
                                </label>
                                <input type="text" name="telefon_bimbit" value="{{ old('telefon_bimbit') }}"
                                    class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-djariah-500 focus:border-djariah-500 transition-all duration-200"
                                    placeholder="012-3456789" required>
                            </div>

                            <div class="space-y-2">
                                <label class="block text-sm font-medium text-gray-700">
                                    Alamat E-mel
                                </label>
                                <input type="email" name="email" value="{{ old('email') }}"
                                    class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-djariah-500 focus:border-djariah-500 transition-all duration-200"
                                    placeholder="nama@contoh.com" required>
                            </div>
                        </div>
                    </div>

                    <div class="flex justify-between mt-6">
                        <div></div>
                        <button type="button" class="next-tab bg-djariah-600 hover:bg-djariah-700 text-white py-2.5 px-6 rounded-lg font-medium transition-colors flex items-center gap-2" data-next="waris">
                            Seterusnya <i class="fas fa-arrow-right"></i>
                        </button>
                    </div>
                </div>

                <!-- Tab 3: Waris Terdekat -->
                <div id="waris" class="tab-content">
                    <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center gap-2">
                        <i class="fas fa-heart text-djariah-600"></i>
                        Waris Terdekat Yang Boleh Dihubungi
                    </h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="space-y-2">
                            <label class="block text-sm font-medium text-gray-700">
                                Nama
                            </label>
                            <input type="text" name="waris_nama" value="{{ old('waris_nama') }}"
                                class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-djariah-500 focus:border-djariah-500 transition-all duration-200"
                                placeholder="Nama waris" required>
                        </div>

                        <div class="space-y-2">
                            <label class="block text-sm font-medium text-gray-700">
                                No. Kad Pengenalan
                            </label>
                            <input type="text" name="waris_ic" value="{{ old('waris_ic') }}"
                                class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-djariah-500 focus:border-djariah-500 transition-all duration-200"
                                placeholder="No. Kad Pengenalan waris" required>
                        </div>

                        <div class="space-y-2 md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700">
                                Alamat
                            </label>
                            <textarea name="waris_alamat" rows="2" required
                                class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-djariah-500 focus:border-djariah-500 transition-all duration-200 resize-none"
                                placeholder="Alamat waris">{{ old('waris_alamat') }}</textarea>
                        </div>

                        <div class="space-y-2">
                            <label class="block text-sm font-medium text-gray-700">
                                No. Tel (Pejabat)
                            </label>
                            <input type="text" name="waris_telefon_pejabat" value="{{ old('waris_telefon_pejabat') }}"
                                class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-djariah-500 focus:border-djariah-500 transition-all duration-200"
                                placeholder="No. telefon pejabat">
                        </div>

                        <div class="space-y-2">
                            <label class="block text-sm font-medium text-gray-700">
                                No. Tel (Bimbit)
                            </label>
                            <input type="text" name="waris_telefon_bimbit" value="{{ old('waris_telefon_bimbit') }}"
                                class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-djariah-500 focus:border-djariah-500 transition-all duration-200"
                                placeholder="No. telefon bimbit" required>
                        </div>
                    </div>

                    <div class="flex justify-between mt-6">
                        <button type="button" class="prev-tab bg-gray-200 hover:bg-gray-300 text-gray-700 py-2.5 px-6 rounded-lg font-medium transition-colors flex items-center gap-2" data-prev="tanggungan">
                            <i class="fas fa-arrow-left"></i> Sebelumnya
                        </button>
                        <button type="button" class="next-tab bg-djariah-600 hover:bg-djariah-700 text-white py-2.5 px-6 rounded-lg font-medium transition-colors flex items-center gap-2" data-next="login">
                            Seterusnya <i class="fas fa-arrow-right"></i>
                        </button>
                    </div>
                </div>

                <!-- Tab 4: Login & Masjid -->
                <div id="login" class="tab-content">
                    <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center gap-2">
                        <i class="fas fa-key text-djariah-600"></i> Maklumat Log Masuk & Masjid
                    </h3>
                    
                    <input type="hidden" name="status" value="active">
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                        <div class="space-y-2">
                            <label class="block text-sm font-medium text-gray-700">
                                Kata Laluan
                            </label>
                            <input type="password" name="password" class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-djariah-500 focus:border-djariah-500 transition-all duration-200" placeholder="Minimum 6 aksara" required>
                        </div>
                        <div class="space-y-2">
                            <label class="block text-sm font-medium text-gray-700">
                                Sahkan Kata Laluan
                            </label>
                            <input type="password" name="password_confirmation" class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-djariah-500 focus:border-djariah-500 transition-all duration-200" placeholder="Masukkan semula kata laluan" required>
                        </div>
                    </div>

                    <div class="space-y-2 mb-4">
                        <label class="block text-sm font-medium text-gray-700">
                            Masjid / Surau
                        </label>
                        <select id="masjid" name="masjid_id" required
                            class="w-full border border-gray-300 rounded-lg px-4 py-2.5
                             focus:ring-2 focus:ring-djariah-500 focus:border-djariah-500 transition-all duration-200 appearance-none 
                             bg-white cursor-pointer pr-10" required>
                            <option value="">-- Pilih Kariah dulu --</option>
                        </select>
                    </div>


                    <!-- Terma dan Syarat -->
                    <div class="border border-gray-200 rounded-lg p-4 bg-gray-50 mb-4">
                        <h4 class="font-bold text-gray-800 mb-2">Terma dan Syarat Keahlian</h4>
                        <div class="text-xs text-gray-600 space-y-1">
                            <a href="/terms"
                                class="text-blue-600 underline underline-offset-4
                                        hover:text-blue-700 transition">
                                    • Terms & Conditions
                            </a>
                            <p>• Tanggungan termasuk isteri/suami/anak-anak berumur 24 tahun kebawah</p>
                            <p>• Setiap Keluarga yang berdaftar WAJIB membayar rm 10 sebagai wakalah untuk kegunaan sistem</p>
                        </div>
                        <div class="mt-3 flex items-center">
                            <input type="checkbox" id="agree_terms" name="agree_terms" required
                                class="w-4 h-4 text-djariah-600 border-gray-300 rounded focus:ring-djariah-500">
                            <label for="agree_terms" class="ml-2 text-sm text-gray-700">
                                Saya bersetuju dengan terma dan syarat di atas
                            </label>
                        </div>
                    </div>

                    <div class="flex justify-between mt-4">
                        <button type="button" class="prev-tab bg-gray-200 hover:bg-gray-300 text-gray-700 py-2.5 px-6 rounded-lg font-medium transition-colors flex items-center gap-2" data-prev="waris">
                            <i class="fas fa-arrow-left"></i> Sebelumnya
                        </button>
                        <button
                            type="button"class="next-tab bg-djariah-600 hover:bg-djariah-700 text-white py-2.5 px-6 rounded-lg font-medium"
                            data-next="payment">Seterusnya <i class="fas fa-arrow-right"></i>
                        </button>

                    </div>
                </div>

                <div id="payment" class="tab-content">
                    @include('user.bayar')
                    <div class="flex justify-between mt-4">
                        <button type="button" class="prev-tab bg-gray-200 hover:bg-gray-300 text-gray-700 py-2.5 px-6 rounded-lg font-medium transition-colors flex items-center gap-2" data-prev="login">
                            <i class="fas fa-arrow-left"></i> Sebelumnya
                        </button>
                        <button type="submit"
                            class="bg-djariah-600 hover:bg-djariah-700 text-white py-2.5 px-6 rounded-lg font-medium inline-flex items-center gap-2 ">
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

    @if(session('success'))
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
        // Tab Navigation
        document.querySelectorAll('.tab-button').forEach(button => {
            button.addEventListener('click', () => {
                const tabId = button.getAttribute('data-tab');
                
                // Update active tab button
                document.querySelectorAll('.tab-button').forEach(btn => {
                    btn.classList.remove('active');
                });
                button.classList.add('active');
                
                // Show active tab content
                document.querySelectorAll('.tab-content').forEach(content => {
                    content.classList.remove('active');
                });
                document.getElementById(tabId).classList.add('active');
            });
        });

        // Next/Prev buttons
        document.querySelectorAll('.next-tab').forEach(button => {
            button.addEventListener('click', () => {
                const nextTabId = button.getAttribute('data-next');
                document.querySelector(`[data-tab="${nextTabId}"]`).click();
            });
        });

        document.querySelectorAll('.prev-tab').forEach(button => {
            button.addEventListener('click', () => {
                const prevTabId = button.getAttribute('data-prev');
                document.querySelector(`[data-tab="${prevTabId}"]`).click();
            });
        });


        document.getElementById('negeri').addEventListener('change', function () {
            let negeri = this.value;

            fetch('/get-bandar/' + negeri)
                .then(res => res.json())
                .then(data => {
                    let bandarSelect = document.getElementById('bandar');
                    bandarSelect.innerHTML = '<option value="">-- Pilih Bandar --</option>';

                    data.forEach(b => {
                        bandarSelect.innerHTML += `<option value="${b.bandar}">${b.bandar}</option>`;
                    });
                });
        });

        document.getElementById('bandar').addEventListener('change', function () {
            let bandar = this.value;

            // Update kariah (existing)
            fetch('/get-kariah/' + bandar)
                .then(res => res.json())
                .then(data => {
                    let kariahSelect = document.getElementById('kariah');
                    kariahSelect.innerHTML = '<option value="">-- Pilih Kariah --</option>';

                    data.forEach(k => {
                        kariahSelect.innerHTML += `<option value="${k.kariah}">${k.kariah}</option>`;
                    });
                });

            // ✅ NEW — auto-fill poskod
            fetch('/get-poskod/' + bandar)
                .then(res => res.json())
                .then(data => {
                    if (data && data.poskod) {
                        document.getElementById('poskod').value = data.poskod;
                    } else {
                        document.getElementById('poskod').value = '';
                    }
                });
        });

            document.getElementById('kariah').addEventListener('change', function () {
    let kariah = this.value;

    fetch('/get-masjid/' + kariah)
        .then(res => res.json())
        .then(data => {
            let masjidSelect = document.getElementById('masjid');
            masjidSelect.innerHTML = '<option value="">-- Pilih Masjid/Surau --</option>';

            if (data.length === 0) {
                masjidSelect.innerHTML += '<option value="">Tiada masjid berdaftar</option>';
                return;
            }

            data.forEach(m => {
                masjidSelect.innerHTML += `<option value="${m.id}">${m.nama}</option>`;
            });
        });
});

        document.querySelector('input[name="tarikh_lahir"]').addEventListener('change', function () {
            let dob = new Date(this.value);
            let today = new Date();

            let age = today.getFullYear() - dob.getFullYear();
            let monthDiff = today.getMonth() - dob.getMonth();
            let dayDiff = today.getDate() - dob.getDate();

            // If birthday hasn't passed yet this year
            if (monthDiff < 0 || (monthDiff === 0 && dayDiff < 0)) {
                age--;
            }

            document.getElementById('umur').value = age > 0 ? age : '';
        });

        document.querySelector('input[name="ic_number"]')
        .addEventListener('input', function() {

        let ic = this.value;

        if (ic.length < 6) return; // tunggu cukup 6 digit depan

        let year = ic.substring(0, 2);
        let month = ic.substring(2, 4);
        let day = ic.substring(4, 6);

        year = year < 30 ? '20' + year : '19' + year;
        let birthdate = new Date(`${year}-${month}-${day}`);
        let today = new Date();
        let age = today.getFullYear() - birthdate.getFullYear();

        if (age < 18) {
            alert('❗ Umur mesti 18 tahun ke atas untuk daftar');
            this.value = ''; // clear field
        }
    });

  
function checkUmurTanggungan(input) {
    let ic = input.value;
    if (!ic || ic.length < 6) return;

    // Tarikh lahir dari IC (YYMMDD)
    let year = parseInt(ic.substring(0, 2));
    let month = parseInt(ic.substring(2, 4));
    let day = parseInt(ic.substring(4, 6));

    year = year < 30 ? 2000 + year : 1900 + year;
    let birthdate = new Date(year, month - 1, day);

    let today = new Date();
    let age = today.getFullYear() - birthdate.getFullYear();
    let m = today.getMonth() - birthdate.getMonth();
    if (m < 0 || (m === 0 && today.getDate() < birthdate.getDate())) {
        age--;
    }

    // Ambil container tanggungan
    let container = input.closest('.tanggungan-item');
    if (!container) return;

    // Ambil hubungan & OKU
    let hubunganEl = container.querySelector('select[name$="[hubungan]"]');
    let okuEl = container.querySelector('input[name$="[oku]"]');

    if (!hubunganEl) return; // kalau belum pilih hubungan, skip

    let hubungan = hubunganEl.value.trim().toLowerCase();
    let isOku = okuEl && okuEl.checked;

    // Auto layak kalau OKU atau warga emas
    if (isOku || age >= 60) return;

    // RULE KHAS: ANAK umur > 24
    if (hubungan === 'anak' && age > 24) {
        alert('❗ Anak berumur lebih 24 tahun perlu daftar sendiri');
        input.value = '';
        // optional: focus balik IC supaya user tahu kena isi sendiri
        input.focus();
    }
}


    document.querySelector('input[name="waris_ic"]')
        ?.addEventListener('input', function () {

        let ic = this.value;

        if (ic.length < 6) return;

        let year = ic.substring(0, 2);
        let month = ic.substring(2, 4);
        let day = ic.substring(4, 6);

        year = year < 30 ? '20' + year : '19' + year;
        let birthdate = new Date(`${year}-${month}-${day}`);
        let today = new Date();
        let age = today.getFullYear() - birthdate.getFullYear();

        if (age < 18) {
            alert('❗ Waris mesti berumur 18 tahun ke atas');
            this.value = '';
        }
    });

    document.getElementById('masjid').addEventListener('change', function () {
    let masjidId = this.value;

    if (!masjidId) return;

        fetch('/get-hargakhairat/' + masjidId)
        .then(res => {
            if (!res.ok) throw new Error('TIADA_HARGA');
            return res.json();
        })
        .then(harga => {
            let bayaranTahunan = parseFloat(harga.bayaran_tahunan ?? 0);
            let yuranDaftar   = parseFloat(harga.yuran_pendaftaran ?? 0);
            let wakalah       = 10;

            let jumlah = bayaranTahunan + yuranDaftar + wakalah;

            document.getElementById('bayaran_tahunan').innerText =
                'RM ' + bayaranTahunan.toFixed(2);

            document.getElementById('yuran_pendaftaran').innerText =
                'RM ' + yuranDaftar.toFixed(2);

            document.getElementById('wakalah_sistem').innerText =
                'RM ' + wakalah.toFixed(2);

            document.getElementById('jumlah_bayaran').innerText =
                'RM ' + jumlah.toFixed(2);
        })
        .catch(() => {
            alert('Harga khairat belum ditetapkan oleh masjid ini');
        });

});
    </script>

</body>
</html>

@endsection