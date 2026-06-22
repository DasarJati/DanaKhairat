<section class="py-20 bg-gray-100">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <!-- Header Section -->
    <div class="text-center mb-16">
      <h2 class="text-4xl font-extrabold text-gray-900 mb-4">Mengapa Pilih DanaKhairat</h2>
      <p class="text-lg text-gray-600 max-w-3xl mx-auto">
        Sistem komprehensif yang direka khas untuk memudahkan pengurusan khairat kematian secara digital
      </p>
    </div>

    <!-- Features Grid dengan Flexbox Center -->
    <div class="flex flex-wrap justify-center gap-8">
      @php
        $features = [
          ['icon' => '💾', 'title' => 'Pengurusan Sistematik', 'desc' => 'Urus maklumat masjid, ahli jawatankuasa dan struktur organisasi dengan mudah.'],
          ['icon' => '👤', 'title' => 'Keahlian Digital', 'desc' => 'Pendaftaran ahli, pengurusan tanggungan dan kemaskini maklumat secara realtime.'],
          ['icon' => '🛡️', 'title' => 'Pembayaran Selamat', 'desc' => 'Sistem pembayaran yuran yang selamat dengan penjejakan transaksi yang telus.'],
          ['icon' => '📱', 'title' => 'Mudah Akses', 'desc' => 'Muat turun aplikasi android dan iOS untuk akses mudah di mana-mana sahaja untuk pengguna.'],
          ['icon' => '🌐', 'title' => 'Laman Web', 'desc' => 'Dashboard komprehensif untuk pentadbiran dan pengurusan institusi.'],
          ['icon' => '⏰', 'title' => 'Laporan Realtime', 'desc' => 'Jana laporan kewangan dan keahlian dengan data terkini.']
        ];
      @endphp
      
      @foreach($features as $feature)
      <div class="group w-80 h-48 [perspective:1000px] cursor-pointer">
        <div class="relative w-full h-full transition-transform duration-500 [transform-style:preserve-3d] group-hover:[transform:rotateY(180deg)]">
          <!-- Front -->
          <div class="absolute inset-0 bg-white rounded-xl p-6 shadow-lg border border-gray-100 flex flex-col items-center justify-center text-center [backface-visibility:hidden]">
            <span class="text-5xl mb-3">{{ $feature['icon'] }}</span>
            <h3 class="font-bold text-gray-900 text-lg">{{ $feature['title'] }}</h3>
          </div>
          <!-- Back -->
          <div class="absolute inset-0 bg-yellow-50 rounded-xl p-6 shadow-lg border border-yellow-200 flex flex-col items-center justify-center text-center [transform:rotateY(180deg)] [backface-visibility:hidden]">
            <h3 class="font-bold text-gray-900 text-lg mb-3">{{ $feature['title'] }}</h3>
            <p class="text-sm text-gray-600 leading-relaxed px-2">
              {{ $feature['desc'] }}
            </p>
          </div>
        </div>
      </div>
      @endforeach
    </div>
    <div class="text-center mt-16">
      <div class="bg-white rounded-2xl p-8 shadow-lg border border-yellow-200 max-w-2xl mx-auto">
        <h3 class="text-2xl font-bold text-gray-900 mb-4">Sedia untuk Mulakan?</h3>
        <p class="text-gray-600 mb-6">Daftar sekarang dan rasai kelainan urusan khairat digital</p>
        <div class="flex flex-col sm:flex-row gap-4 justify-center">
          <a href="/pilihan" class="px-8 py-4 bg-yellow-500 hover:bg-yellow-600 text-gray-900 font-bold rounded-xl transition-all duration-300 transform hover:-translate-y-1 shadow-lg hover:shadow-xl flex items-center justify-center gap-2">
            <i class="fas fa-rocket"></i>
            Daftar Sekarang
          </a>
        </div>
      </div>
    </div>
  </div>
</section>