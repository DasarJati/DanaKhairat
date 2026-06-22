@extends ('layouts.user')
@section ('content')
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#1e40af',
                        secondary: '#3b82f6',
                        accent: '#f59e0b',
                        success: '#10b981',
                        warning: '#f59e0b',
                        danger: '#ef4444',
                        light: '#f8fafc',
                        dark: '#1e293b'
                    }
                }
            }
        }

        // Data status ahli - akan diupdate bila proses tuntutan
        let ahliStatus = {
            'Siti binti Mohd': 'active-claim', // merah - ada tuntutan aktif
            'Ahmad bin Mahmud': 'normal',      // normal
            'Ali bin Ahmad': 'normal',         // normal  
            'Aishah binti Ahmad': 'normal'     // normal
        };

        function updateAhliCardColors() {
            Object.keys(ahliStatus).forEach(nama => {
                const card = document.querySelector(`[data-ahli-name="${nama}"]`);
                if (card) {
                    // Remove all status classes
                    card.classList.remove('border-red-500', 'bg-red-50', 'border-gray-400', 'bg-gray-100');
                    
                    // Add appropriate classes based on status
                    if (ahliStatus[nama] === 'active-claim') {
                        card.classList.add('border-red-500', 'bg-red-50');
                    } else if (ahliStatus[nama] === 'completed-claim') {
                        card.classList.add('border-gray-400', 'bg-gray-100');
                    }
                }
            });
        }

        function processClaim(action, namaAhli) {
            if (action === 'lulus') {
                if (confirm(`Adakah anda pasti ingin meluluskan tuntutan untuk ${namaAhli}?`)) {
                    // Update status ahli
                    ahliStatus[namaAhli] = 'completed-claim';
                    
                    // Update UI
                    updateAhliCardColors();
                    
                    // Hide/selesaikan permintaan khairat
                    const permintaanCard = document.querySelector('.border-blue-200');
                    if (permintaanCard) {
                        permintaanCard.style.display = 'none';
                    }
                    
                    // Update notification
                    const notifBadge = document.querySelector('.bg-blue-100');
                    if (notifBadge) {
                        notifBadge.textContent = 'Tiada Permintaan';
                        notifBadge.className = 'inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-gray-100 text-gray-800';
                    }
                    
                    alert(`Tuntutan untuk ${namaAhli} telah berjaya diluluskan!`);
                }
            } else if (action === 'tolak') {
                if (confirm(`Adakah anda pasti ingin menolak tuntutan untuk ${namaAhli}?`)) {
                    // Kembalikan status kepada normal
                    ahliStatus[namaAhli] = 'normal';
                    updateAhliCardColors();
                    
                    // Hide permintaan
                    const permintaanCard = document.querySelector('.border-blue-200');
                    if (permintaanCard) {
                        permintaanCard.style.display = 'none';
                    }
                    
                    alert(`Tuntutan untuk ${namaAhli} telah ditolak.`);
                }
            }
        }

        // Initialize card colors on page load
        document.addEventListener('DOMContentLoaded', function() {
            updateAhliCardColors();
        });
    </script>

    <div class="container mx-auto px-4 py-8">
        <!-- Header dengan navigasi kembali -->
        <header class="mb-8">
            <div class="flex items-center mb-4">
                <a href="/list" class="flex items-center text-primary hover:text-secondary transition-colors mr-4">
                    <i class="fas fa-arrow-left mr-2"></i>
                    <span>Kembali ke Senarai</span>
                </a>
                <div class="h-6 w-px bg-gray-300 mx-4"></div>
                <h1 class="text-2xl font-bold text-dark">Butiran Keluarga</h1>
            </div>
            
            <!-- Maklumat Ketua Keluarga -->
            <div class="bg-white rounded-xl shadow-sm p-6 mb-6">
                <div class="flex flex-col md:flex-row md:items-center justify-between">
                    <div class="flex items-center mb-4 md:mb-0">
                        <div class="flex-shrink-0 h-16 w-16 bg-gradient-to-r from-primary to-secondary rounded-xl flex items-center justify-center text-white font-bold text-xl shadow-sm mr-4">
                            AM
                        </div>
                        <div>
                            <h2 class="text-xl font-bold text-gray-900">Ahmad bin Mahmud</h2>
                            <p class="text-gray-600">Ketua Keluarga • No. K/P: 750101-01-1234</p>
                            <p class="text-gray-500 text-sm">No. 12, Jalan Merak 5, Taman Seri Indah, 43000 Kajang, Selangor</p>
                        </div>
                    </div>
                    <div class="bg-green-50 border border-green-200 rounded-lg px-4 py-3">
                        <p class="text-green-800 font-medium">Status Keluarga: <span class="font-bold">Aktif</span></p>
                    </div>
                </div>
            </div>
        </header>

        <!-- Permintaan Khairat Aktif -->
        <div class="bg-white rounded-xl shadow-sm p-6 mb-8">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-xl font-bold text-dark">Permintaan Khairat Aktif</h2>
                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                    <i class="fas fa-bell mr-1.5"></i>
                    Permintaan Baru
                </span>
            </div>

            <div class="space-y-4">
                <!-- Permintaan 1 - Untuk Siti binti Mohd -->
                <div class="border border-blue-200 rounded-lg p-4 bg-blue-50">
                    <div class="flex flex-col md:flex-row md:items-center justify-between mb-3">
                        <div>
                            <h3 class="font-semibold text-gray-900">Khairat Kematian - Siti binti Mohd</h3>
                            <p class="text-sm text-gray-600">Dihantar: 15 Mac 2024 • Status: <span class="font-medium text-blue-700">Dalam Proses</span></p>
                        </div>
                        <div class="flex gap-2 mt-2 md:mt-0">
                            <button onclick="processClaim('lulus', 'Siti binti Mohd')" class="px-3 py-1.5 bg-green-500 text-white rounded-lg text-sm hover:bg-green-600 transition-colors">
                                <i class="fas fa-check mr-1"></i>Lulus
                            </button>
                            <button onclick="processClaim('tolak', 'Siti binti Mohd')" class="px-3 py-1.5 bg-red-500 text-white rounded-lg text-sm hover:bg-red-600 transition-colors">
                                <i class="fas fa-times mr-1"></i>Tolak
                            </button>
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <!-- Borang E-Form yang telah diisi -->
    <div>
        <h4 class="text-sm font-medium text-gray-700 mb-3 flex items-center">
            <i class="fas fa-file-contract mr-2 text-blue-500"></i>
            Borang E-Form Tuntutan
        </h4>
        <div class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-lg border-2 border-dashed border-blue-200 p-4 hover:shadow-md transition-all duration-300 cursor-pointer group">
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center mr-3 group-hover:bg-blue-200 transition-colors">
                        <i class="fas fa-file-alt text-blue-600 text-lg"></i>
                    </div>
                    <div>
                        <h5 class="font-semibold text-gray-900 text-sm">Borang Tuntutan Khairat Kematian</h5>
                        <p class="text-xs text-gray-600 mt-1">E-Form yang telah diisi oleh pemohon</p>
                        <div class="flex items-center mt-1">
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-green-100 text-green-800">
                                <i class="fas fa-check-circle mr-1 text-xs"></i>
                                Telah Diisi
                            </span>
                            <span class="text-xs text-gray-500 ml-2">Dihantar: 15 Mac 2024</span>
                        </div>
                    </div>
                </div>
                <button onclick="viewEForm()" class="flex items-center gap-2 px-3 py-2 bg-blue-500 text-white rounded-lg text-sm hover:bg-blue-600 transition-colors group-hover:scale-105">
                    <i class="fas fa-eye mr-1"></i>
                    Lihat Borang
                </button>
            </div>
        </div>
    </div>

    <!-- Dokumen Sokongan -->
    <div>
        <h4 class="text-sm font-medium text-gray-700 mb-3 flex items-center">
            <i class="fas fa-paperclip mr-2 text-green-500"></i>
            Dokumen Sokongan Dihantar
        </h4>
        <div class="space-y-3">
            <!-- Dokumen 1: Sijil Kematian -->
            <div class="flex items-center justify-between p-3 bg-white rounded-lg border border-gray-200 shadow-sm hover:shadow-md transition-all duration-200 group">
                <div class="flex items-center">
                    <div class="w-10 h-10 bg-red-100 rounded-lg flex items-center justify-center mr-3 group-hover:bg-red-200 transition-colors">
                        <i class="fas fa-file-pdf text-red-600"></i>
                    </div>
                    <div>
                        <span class="text-sm font-medium text-gray-700 block">Sijil Kematian.pdf</span>
                        <div class="flex items-center mt-1">
                            <span class="text-xs text-gray-500">2.4 MB</span>
                            <span class="w-1 h-1 bg-gray-400 rounded-full mx-2"></span>
                            <span class="text-xs text-green-600 font-medium">Wajib</span>
                        </div>
                    </div>
                </div>
                <div class="flex items-center gap-2">
                    <button onclick="previewDocument('sijil-kematian')" class="p-2 text-blue-500 hover:text-blue-700 transition-colors" title="Pratonton">
                        <i class="fas fa-eye"></i>
                    </button>
                    <button onclick="downloadDocument('sijil-kematian')" class="p-2 text-green-500 hover:text-green-700 transition-colors" title="Muat Turun">
                        <i class="fas fa-download"></i>
                    </button>
                </div>
            </div>

            <!-- Dokumen 2: Salinan IC Pewaris -->
            <div class="flex items-center justify-between p-3 bg-white rounded-lg border border-gray-200 shadow-sm hover:shadow-md transition-all duration-200 group">
                <div class="flex items-center">
                    <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center mr-3 group-hover:bg-blue-200 transition-colors">
                        <i class="fas fa-id-card text-blue-600"></i>
                    </div>
                    <div>
                        <span class="text-sm font-medium text-gray-700 block">Salinan IC Pewaris.pdf</span>
                        <div class="flex items-center mt-1">
                            <span class="text-xs text-gray-500">1.8 MB</span>
                            <span class="w-1 h-1 bg-gray-400 rounded-full mx-2"></span>
                            <span class="text-xs text-green-600 font-medium">Wajib</span>
                        </div>
                    </div>
                </div>
                <div class="flex items-center gap-2">
                    <button onclick="previewDocument('ic-pewaris')" class="p-2 text-blue-500 hover:text-blue-700 transition-colors" title="Pratonton">
                        <i class="fas fa-eye"></i>
                    </button>
                    <button onclick="downloadDocument('ic-pewaris')" class="p-2 text-green-500 hover:text-green-700 transition-colors" title="Muat Turun">
                        <i class="fas fa-download"></i>
                    </button>
                </div>
            </div>

            <!-- Dokumen 3: Salinan Akaun Bank -->
            <div class="flex items-center justify-between p-3 bg-white rounded-lg border border-gray-200 shadow-sm hover:shadow-md transition-all duration-200 group">
                <div class="flex items-center">
                    <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center mr-3 group-hover:bg-green-200 transition-colors">
                        <i class="fas fa-university text-green-600"></i>
                    </div>
                    <div>
                        <span class="text-sm font-medium text-gray-700 block">Salinan Akaun Bank.pdf</span>
                        <div class="flex items-center mt-1">
                            <span class="text-xs text-gray-500">2.1 MB</span>
                            <span class="w-1 h-1 bg-gray-400 rounded-full mx-2"></span>
                            <span class="text-xs text-green-600 font-medium">Wajib</span>
                        </div>
                    </div>
                </div>
                <div class="flex items-center gap-2">
                    <button onclick="previewDocument('akaun-bank')" class="p-2 text-blue-500 hover:text-blue-700 transition-colors" title="Pratonton">
                        <i class="fas fa-eye"></i>
                    </button>
                    <button onclick="downloadDocument('akaun-bank')" class="p-2 text-green-500 hover:text-green-700 transition-colors" title="Muat Turun">
                        <i class="fas fa-download"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
function viewEForm() {
    // Simulasi membuka borang e-form
    const modal = document.createElement('div');
    modal.className = 'fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4';
    modal.innerHTML = `
        <div class="bg-white rounded-xl max-w-4xl w-full max-h-[90vh] overflow-hidden">
            <div class="flex justify-between items-center p-6 border-b border-gray-200">
                <h3 class="text-lg font-bold text-gray-900">Borang Tuntutan Khairat Kematian</h3>
                <button onclick="this.closest('.fixed').remove()" class="text-gray-500 hover:text-gray-700 p-2">
                    <i class="fas fa-times text-lg"></i>
                </button>
            </div>
            <div class="p-6 overflow-y-auto max-h-[70vh]">
                <div class="bg-gray-50 rounded-lg p-6 mb-4">
                    <h4 class="font-semibold text-gray-900 mb-4">Maklumat Pemohon</h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                        <div><strong>Nama:</strong> Ahmad bin Mahmud</div>
                        <div><strong>No. IC:</strong> 750101-01-1234</div>
                        <div><strong>No. Telefon:</strong> 012-3456789</div>
                        <div><strong>Alamat:</strong> No. 12, Jalan Merak 5, Taman Seri Indah</div>
                    </div>
                </div>
                
                <div class="bg-gray-50 rounded-lg p-6">
                    <h4 class="font-semibold text-gray-900 mb-4">Maklumat Si Mati</h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                        <div><strong>Nama Si Mati:</strong> Siti binti Mohd</div>
                        <div><strong>No. IC Si Mati:</strong> 820315-08-5678</div>
                        <div><strong>Hubungan:</strong> Isteri</div>
                        <div><strong>Tarikh Kematian:</strong> 15 Mac 2024</div>
                        <div><strong>Tempat Kematian:</strong> Hospital Kajang</div>
                        <div><strong>Jumlah Tuntutan:</strong> RM 2,000.00</div>
                    </div>
                </div>
                
                <div class="mt-6 p-4 bg-yellow-50 border border-yellow-200 rounded-lg">
                    <p class="text-sm text-yellow-800 flex items-center">
                        <i class="fas fa-info-circle mr-2"></i>
                        Borang ini telah diisi secara digital oleh pemohon melalui sistem e-Khairat
                    </p>
                </div>
            </div>
            <div class="flex justify-end p-6 border-t border-gray-200 bg-gray-50">
                <button onclick="this.closest('.fixed').remove()" class="px-4 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600 transition-colors">
                    Tutup
                </button>
            </div>
        </div>
    `;
    document.body.appendChild(modal);
}

function previewDocument(docType) {
    const docNames = {
        'sijil-kematian': 'Sijil Kematian',
        'ic-pewaris': 'Salinan IC Pewaris', 
        'akaun-bank': 'Salinan Akaun Bank'
    };
    
    alert(`Membuka pratonton: ${docNames[docType]}\n\nDalam sistem sebenar, dokumen akan dipaparkan dalam viewer PDF.`);
}

function downloadDocument(docType) {
    const docNames = {
        'sijil-kematian': 'Sijil Kematian',
        'ic-pewaris': 'Salinan IC Pewaris',
        'akaun-bank': 'Salinan Akaun Bank'
    };
    
    alert(`Memuat turun: ${docNames[docType]}\n\nDokumen sedang dimuat turun...`);
    
    // Simulasi download delay
    setTimeout(() => {
        alert(`${docNames[docType]} berjaya dimuat turun!`);
    }, 1000);
}
</script>

<style>
/* Custom hover effects */
.group:hover .group-hover\:scale-105 {
    transform: scale(1.05);
}

/* Smooth transitions */
.transition-all {
    transition: all 0.3s ease;
}
</style>
