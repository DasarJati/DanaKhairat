

@section('content')
<script src="https://cdn.tailwindcss.com"></script>


<div class="bg-gray-100">
    <!-- Header -->
    <div class="text-center mb-8 md:mb-12">
        <h1 class="text-3xl md:text-4xl font-bold text-gray-800 mb-3">Pakej Keahlian</h1>
        <p class="text-lg text-gray-600">Pilih pakej yang sesuai untuk institusi anda</p>
        
        @auth
            <div class="mt-4 inline-flex items-center px-4 py-2 rounded-full bg-blue-50 text-blue-700">
                <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd" />
                </svg>
                Log Masuk sebagai: {{ Auth::user()->name }}
            </div>
        @endauth
    </div>

    <!-- Error/Success Messages -->
    @if(session('error'))
        <div class="max-w-4xl mx-auto mb-6">
            <div class="bg-red-50 border-l-4 border-red-500 p-4 rounded">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-red-500" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-red-700">{{ session('error') }}</p>
                    </div>
                </div>
            </div>
        </div>
    @endif

    @if(session('success'))
        <div class="max-w-4xl mx-auto mb-6">
            <div class="bg-green-50 border-l-4 border-green-500 p-4 rounded">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-green-500" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-green-700">{{ session('success') }}</p>
                    </div>
                </div>
            </div>
        </div>
    @endif

        <!-- Packages Images -->
        <div class="flex w-full justify-center items-center">

        <div class="flex flex-col md:flex-row gap-8">

            <!-- BASIC PACKAGE -->
             <div class="shadow-2xl">
            <a href="{{ route('packages.select', ['package' => 'basic']) }}"
            class="group relative rounded-2xl overflow-hidden shadow-lg ">

                <img src="{{ asset('images/pakej basic.png') }}"
                    alt="Pakej Basic"
                    class="w-full h-full rounded rounded-4xl">

                <!-- Overlay -->
              
            </a>
            </div>

            <!-- PREMIUM PACKAGE -->
             <div class=" shadow-2xl">
            <a href="{{ route('packages.select', ['package' => 'premium']) }}"
            class="group relative rounded-2xl overflow-hidden shadow-lg ">

                <img src="{{ asset('images/pemium.png') }}"
                    alt="Pakej Premium"
                    class="w-full h-full rounded  rounded-4xl ">

                <!-- Overlay -->
               
            </a>
            </div>
</div>
        </div>


    <!-- Terms & FAQ -->
    <div class="max-w-4xl mx-auto mt-12 pt-8 border-t border-gray-200">
        <p class="text-center text-gray-600 mb-6">Terma & Syarat dikenakan</p>
        
        <div class="flex flex-col sm:flex-row justify-center items-center gap-6">
            <button type="button" 
                    onclick="openModal('termsModal')"
                    class="inline-flex items-center text-blue-600 hover:text-blue-800 transition-colors duration-200">
                <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z" clip-rule="evenodd" />
                </svg>
                Lihat Terma & Syarat Penuh
            </button>
            
            <span class="hidden sm:inline text-gray-300">|</span>
            
            <button type="button" 
                    onclick="openModal('faqModal')"
                    class="inline-flex items-center text-blue-600 hover:text-blue-800 transition-colors duration-200">
                <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-8-3a1 1 0 00-.867.5 1 1 0 11-1.731-1A3 3 0 0113 8a3.001 3.001 0 01-2 2.83V11a1 1 0 11-2 0v-1a1 1 0 011-1 1 1 0 100-2zm0 8a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd" />
                </svg>
                Bantuan & Soalan Lazim
            </button>
        </div>
    </div>
</div>

<!-- Terms Modal -->
<div id="termsModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4 z-50 hidden">
    <div class="bg-white rounded-xl shadow-2xl max-w-2xl w-full max-h-[90vh] overflow-y-auto">
        <div class="sticky top-0 bg-white border-b px-6 py-4 flex justify-between items-center">
            <h3 class="text-xl font-bold text-gray-800">Terma & Syarat</h3>
            <button onclick="closeModal('termsModal')" class="text-gray-400 hover:text-gray-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
        <div class="p-6 space-y-4">
            <div>
                <h4 class="font-semibold text-lg mb-2">1. Langganan</h4>
                <p class="text-gray-600">Semua langganan adalah berdasarkan bulanan dan akan dikenakan caj secara automatik setiap bulan.</p>
            </div>
            <div>
                <h4 class="font-semibold text-lg mb-2">2. Pembayaran</h4>
                <p class="text-gray-600">Pembayaran mesti dibuat sepenuhnya sebelum akses diberikan kepada sistem.</p>
            </div>
            <div>
                <h4 class="font-semibold text-lg mb-2">3. Pembatalan</h4>
                <p class="text-gray-600">Anda boleh membatalkan langganan pada bila-bila masa, tetapi bayaran untuk bulan semasa tidak akan dipulangkan.</p>
            </div>
            <div>
                <h4 class="font-semibold text-lg mb-2">4. Had Pengguna</h4>
                <p class="text-gray-600">Pakej BASIC had maksimum 500 ahli. Pakej PREMIUM tiada had ahli.</p>
            </div>
            <div>
                <h4 class="font-semibold text-lg mb-2">5. Penggunaan Data</h4>
                <p class="text-gray-600">Data masjid dan ahli akan dilindungi mengikut Akta Perlindungan Data Peribadi.</p>
            </div>
        </div>
        <div class="border-t px-6 py-4">
            <button onclick="closeModal('termsModal')" 
                    class="w-full bg-blue-600 hover:bg-blue-700 text-white font-medium py-3 rounded-lg transition-colors duration-200">
                Setuju & Tutup
            </button>
        </div>
    </div>
</div>

<!-- FAQ Modal -->
<div id="faqModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4 z-50 hidden">
    <div class="bg-white rounded-xl shadow-2xl max-w-2xl w-full max-h-[90vh] overflow-y-auto">
        <div class="sticky top-0 bg-white border-b px-6 py-4 flex justify-between items-center">
            <h3 class="text-xl font-bold text-gray-800">Soalan Lazim (FAQ)</h3>
            <button onclick="closeModal('faqModal')" class="text-gray-400 hover:text-gray-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
        <div class="p-6">
            <div class="space-y-4">
                <div class="border rounded-lg overflow-hidden">
                    <button onclick="toggleFAQ(1)" class="w-full px-6 py-4 text-left font-semibold text-gray-800 hover:bg-gray-50 flex justify-between items-center">
                        <span>Bagaimana untuk menaik taraf pakej?</span>
                        <svg id="faqIcon1" class="w-5 h-5 transform rotate-0 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>
                    <div id="faqContent1" class="px-6 py-4 bg-gray-50 hidden">
                        <p class="text-gray-600">Anda boleh menaik taraf pakej pada bila-bila masa. Baki langganan sedia ada akan dikira secara prorata.</p>
                    </div>
                </div>
                
                <div class="border rounded-lg overflow-hidden">
                    <button onclick="toggleFAQ(2)" class="w-full px-6 py-4 text-left font-semibold text-gray-800 hover:bg-gray-50 flex justify-between items-center">
                        <span>Berapa lama masa untuk mengaktifkan sistem?</span>
                        <svg id="faqIcon2" class="w-5 h-5 transform rotate-0 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>
                    <div id="faqContent2" class="px-6 py-4 bg-gray-50 hidden">
                        <p class="text-gray-600">Sistem akan diaktifkan dalam masa 24 jam selepas pembayaran disahkan.</p>
                    </div>
                </div>
                
                <div class="border rounded-lg overflow-hidden">
                    <button onclick="toggleFAQ(3)" class="w-full px-6 py-4 text-left font-semibold text-gray-800 hover:bg-gray-50 flex justify-between items-center">
                        <span>Bolehkah saya menambah ahli selepas langganan?</span>
                        <svg id="faqIcon3" class="w-5 h-5 transform rotate-0 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>
                    <div id="faqContent3" class="px-6 py-4 bg-gray-50 hidden">
                        <p class="text-gray-600">Ya, anda boleh menambah ahli pada bila-bila masa mengikut had pakej yang dipilih.</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="border-t px-6 py-4">
            <button onclick="closeModal('faqModal')" 
                    class="w-full bg-blue-600 hover:bg-blue-700 text-white font-medium py-3 rounded-lg transition-colors duration-200">
                Tutup
            </button>
        </div>
    </div>
</div>

<script>
    // Modal functions
    function openModal(modalId) {
        document.getElementById(modalId).classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }
    
    function closeModal(modalId) {
        document.getElementById(modalId).classList.add('hidden');
        document.body.style.overflow = 'auto';
    }
    
    // FAQ toggle function
    function toggleFAQ(faqNumber) {
        const content = document.getElementById(`faqContent${faqNumber}`);
        const icon = document.getElementById(`faqIcon${faqNumber}`);
        
        if (content.classList.contains('hidden')) {
            content.classList.remove('hidden');
            icon.classList.add('rotate-180');
        } else {
            content.classList.add('hidden');
            icon.classList.remove('rotate-180');
        }
    }
    
    // Close modal when clicking outside
    document.addEventListener('click', function(event) {
        if (event.target.id === 'termsModal' || event.target.id === 'faqModal') {
            closeModal(event.target.id);
        }
    });
    
    // Close modal with Escape key
    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape') {
            closeModal('termsModal');
            closeModal('faqModal');
        }
    });
</script>
