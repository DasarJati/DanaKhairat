    {{-- Top Sahabat Kariah --}}
       <section id="sahabat" class="py-20 bg-gray-100">
        <div class="max-w-7xl mx-auto">
            <div class="flex justify-between items-center mb-12">
                <h2 class="text-4xl font-extrabold text-center text-gray-900">
                    Senarai Sahabat Kariah
                </h2>
                <div class="flex gap-4">
                    <button id="prev" class="p-4 rounded-full bg-white shadow-lg hover:bg-yellow-500 hover:text-white transition-all duration-300 transform hover:scale-110 border border-yellow-200 disabled:opacity-50 disabled:cursor-not-allowed">
                        &#10094;
                    </button>
                    <button id="next" class="p-4 rounded-full bg-white shadow-lg hover:bg-yellow-500 hover:text-white transition-all duration-300 transform hover:scale-110 border border-yellow-200 disabled:opacity-50 disabled:cursor-not-allowed">
                        &#10095;
                    </button>
                </div>
            </div>

            <div class="relative">
                <div id="carousel-wrapper" class="overflow-hidden w-full"> 
                    <div id="carousel" class="flex gap-6 transition-transform duration-500 ease-in-out">
                        <!-- Cards -->
                        <div class="carousel-item flex-shrink-0 w-75 bg-white rounded-2xl overflow-hidden shadow-lg border border-yellow-100">
                            <img src="{{ asset('images/tnb.jpg') }}" class="h-56 w-full object-cover">
                            <div class="p-6">
                                <h3 class="text-xl font-bold text-gray-900">Masjid TNB Bangsar</h3>
                                <p class="text-gray-600 mt-2">Bangsar, Kuala Lumpur<br>800 Orang Ahli</p>
                            </div>
                        </div>
                        <div class="carousel-item flex-shrink-0 w-75 bg-white rounded-2xl overflow-hidden shadow-lg border border-yellow-100">
                            <img src="{{ asset('images/putra.jpg') }}" class="h-56 w-full object-cover">
                            <div class="p-6">
                                <h3 class="text-xl font-bold text-gray-900">Masjid Putra</h3>
                                <p class="text-gray-600 mt-2">Putrajaya, Wilayah Persekutuan Putrajaya<br>1200 Orang Ahli</p>
                            </div>
                        </div>
                        <div class="carousel-item flex-shrink-0 w-75 bg-white rounded-2xl overflow-hidden shadow-lg border border-yellow-100">
                            <img src="{{ asset('images/nur.jpg') }}" class="h-56 w-full object-cover">
                            <div class="p-6">
                                <h3 class="text-xl font-bold text-gray-900">Surau Nur Ramadhan</h3>
                                <p class="text-gray-600 mt-2">Kajang, Sg Merab<br>450 Orang Ahli</p>
                            </div>
                        </div>
                        <div class="carousel-item flex-shrink-0 w-75 bg-white rounded-2xl overflow-hidden shadow-lg border border-yellow-100">
                            <img src="{{ asset('images/kelantan.jpeg') }}" class="h-56 w-full object-cover">
                            <div class="p-6">
                                <h3 class="text-xl font-bold text-gray-900">Masjid Andalusia</h3>
                                <p class="text-gray-600 mt-2">Kota Bharu, Kelantan<br>2000 Orang Ahli</p>
                            </div>
                        </div>
                        <div class="carousel-item flex-shrink-0 w-75 bg-white rounded-2xl overflow-hidden shadow-lg border border-yellow-100">
                            <img src="{{ asset('images/shahalam.jpg') }}" class="h-56 w-full object-cover">
                            <div class="p-6">
                                <h3 class="text-xl font-bold text-gray-900">Surau Al-Mawaddah</h3>
                                <p class="text-gray-600 mt-2">Sesyen 7, Shah Alam<br>150 Orang Ahli</p>
                            </div>
                        </div>
                        <div class="carousel-item flex-shrink-0 w-75 bg-white rounded-2xl overflow-hidden shadow-lg border border-yellow-100">
                            <img src="{{ asset('images/sendayan.jpg') }}" class="h-56 w-full object-cover">
                            <div class="p-6">
                                <h3 class="text-xl font-bold text-gray-900">Masjid Sri Sendayan</h3>
                                <p class="text-gray-600 mt-2">Seremban, Negeri Sembilan<br>3000 Orang Ahli</p>
                            </div>
                        </div>
                        <div class="carousel-item flex-shrink-0 w-75 bg-white rounded-2xl overflow-hidden shadow-lg border border-yellow-100">
                            <img src="{{ asset('/images/pinang.jpeg') }}" class="h-56 w-full object-cover">
                            <div class="p-6">
                                <h3 class="text-xl font-bold text-gray-900">Surau Al-Muttaqin</h3>
                                <p class="text-gray-600 mt-2">Lilitan Sg Ara, Penang<br>200 Orang Ahli</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>