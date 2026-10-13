@php
    $features = [
        ['icon' => 'fa-database', 'title' => 'Pengurusan Sistematik', 'desc' => 'Urus maklumat masjid, ahli jawatankuasa dan struktur organisasi dengan mudah.', 'tags' => 'Maklumat masjid · Jawatankuasa · Struktur organisasi'],
        ['icon' => 'fa-user', 'title' => 'Keahlian Digital', 'desc' => 'Pendaftaran ahli, pengurusan tanggungan dan kemaskini maklumat secara realtime.', 'tags' => 'Pendaftaran ahli · Tanggungan · Kemaskini realtime'],
        ['icon' => 'fa-shield-halved', 'title' => 'Pembayaran Selamat', 'desc' => 'Sistem pembayaran yuran yang selamat dengan penjejakan transaksi yang telus.', 'tags' => 'Yuran · Penjejakan transaksi · Telus'],
        ['icon' => 'fa-mobile-screen', 'title' => 'Mudah Akses', 'desc' => 'Muat turun aplikasi android dan iOS untuk akses mudah di mana-mana sahaja untuk pengguna.', 'tags' => 'Android · iOS · Di mana-mana sahaja'],
        ['icon' => 'fa-globe', 'title' => 'Laman Web', 'desc' => 'Dashboard komprehensif untuk pentadbiran dan pengurusan institusi.', 'tags' => 'Dashboard · Pentadbiran · Institusi'],
        ['icon' => 'fa-clock', 'title' => 'Laporan Realtime', 'desc' => 'Jana laporan kewangan dan keahlian dengan data terkini.', 'tags' => 'Kewangan · Keahlian · Data terkini'],
    ];
@endphp

<section id="kelebihan" class="lp-section lp-dark" data-theme="dark">
    <div class="lp-wrap lp-why">
        <div class="lp-why-aside">
            <span class="lp-label"><i class="lp-dot"></i>Kelebihan</span>
            <h2 class="lp-h1">Mengapa Pilih DanaKhairat</h2>
            <p class="lp-body">Sistem komprehensif yang direka khas untuk memudahkan pengurusan khairat kematian secara digital.</p>
        </div>

        <div class="lp-why-list">
            @foreach ($features as $i => $feature)
                <article class="lp-feat">
                    <div class="lp-feat-text">
                        <div>
                            <span>/0{{ $i + 1 }}</span>
                            <h3 class="lp-h3">{{ $feature['title'] }}</h3>
                            <p class="lp-body">{{ $feature['desc'] }}</p>
                        </div>
                        <p>{{ $feature['tags'] }}</p>
                    </div>
                    <div class="lp-feat-art"><i class="fas {{ $feature['icon'] }}"></i></div>
                </article>
            @endforeach
        </div>
    </div>
</section>
