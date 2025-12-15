@extends('layouts.app')
<!--
// Kode ditulis oleh :
// Nama  : Fadhiil Akmal Hamizan
// Github: Axmalz
// NRP   : 5026231128
// Kelas : PPPL B
-->
@section('content')
<!-- Container Utama: Menghandle Background Transition -->
<div id="onboarding-container" class="w-full h-full relative overflow-hidden bg-white transition-colors duration-700 ease-in-out">

    <!-- HEADER FIX (STATIS) -->
    <!-- Ditempatkan di luar slider-track agar tidak ikut bergerak -->
    <div class="absolute top-0 left-0 w-full z-50 p-6 pt-12 flex justify-between items-center">
        <!-- Indicators Container -->
        <div id="global-indicator-container" class="flex gap-1.5 transition-all duration-300">
            <!-- Indikator akan dirender oleh JS di sini -->
        </div>

        <!-- Skip Button (Hanya muncul di slide 1 & 2) -->
        <button id="skip-btn" onclick="jumpToLast()" class="font-medium text-gray-500 text-lg hover:text-blue-600 transition-colors duration-300">
            Skip
        </button>
    </div>

    <!-- Slider Track: Bergerak Horizontal -->
    <div id="slider-track" class="flex w-full h-full transition-transform duration-500 ease-out will-change-transform">

        <!-- ================= SLIDE 1 ================= -->
        <div class="w-full h-full flex-shrink-0 relative flex flex-col slide-item" data-theme="light">
            <!-- Background Image (Bottom) -->
            <div class="absolute bottom-0 left-0 w-full z-0 translate-y-10 opacity-0 transition-all duration-1000 ease-out slide-img">
                <img src="{{ asset('images/onboarding-1.png') }}" alt="Ilustrasi 1" class="w-full object-cover">
            </div>

            <!-- Content -->
            <div class="relative z-10 flex flex-col flex-grow p-6 pt-24"> <!-- pt ditambah karena header fix -->

                <!-- Text -->
                <div class="mt-4 translate-y-4 opacity-0 transition-all duration-700 delay-200 ease-out slide-text">
                    <h1 class="font-sugo text-4xl font-bold text-blue-600 leading-tight">
                        Temukan dan<br>sewakan<br>
                        <span class="inline-flex items-baseline">
                            <img src="{{ asset('images/logo-color-icon.png') }}" alt="B" class="h-9 w-auto inline-block -mb-1 mr-0.5">
                            <span>uku</span>
                        </span>
                        kuliah<br>dengan mudah!
                    </h1>
                    <p class="mt-4 text-gray-600 text-base leading-relaxed">
                        Tidak perlu repot cari pembeli atau penjual—kami hadir sebagai penghubung yang efisien dan terpercaya bagi mahasiswa.
                    </p>
                </div>

                <div class="flex-grow"></div>

                <!-- Button -->
                <div class="w-full mb-6 translate-y-4 opacity-0 transition-all duration-700 delay-500 ease-out slide-btn">
                    <button onclick="nextSlide()" class="block w-full bg-blue-600 text-white font-semibold text-lg py-4 px-6 rounded-full text-center shadow-lg shadow-blue-500/50 hover:bg-blue-700 transition-colors">
                        Continue
                    </button>
                </div>
            </div>
        </div>

        <!-- ================= SLIDE 2 ================= -->
        <div class="w-full h-full flex-shrink-0 relative flex flex-col slide-item" data-theme="light">
            <!-- Background Image (Bottom) -->
            <div class="absolute bottom-0 left-0 w-full z-0 translate-y-10 opacity-0 transition-all duration-1000 ease-out slide-img">
                <img src="{{ asset('images/onboarding-2.png') }}" alt="Ilustrasi 2" class="w-full object-cover">
            </div>

            <!-- Content -->
            <div class="relative z-10 flex flex-col flex-grow p-6 pt-24"> <!-- pt ditambah -->

                <!-- Text -->
                <div class="mt-4 translate-y-4 opacity-0 transition-all duration-700 delay-200 ease-out slide-text">
                    <h1 class="font-sugo text-4xl font-bold text-blue-600 leading-tight">
                        Ubah rak<br>
                        <span class="inline-flex items-baseline">
                            <img src="{{ asset('images/logo-color-icon.png') }}" alt="B" class="h-9 w-auto inline-block -mb-1 mr-0.5">
                            <span>uku</span>
                        </span>
                        jadi<br>penghasilan!
                    </h1>
                    <p class="mt-4 text-gray-600 text-base leading-relaxed">
                        Ambil buku yang sudah tidak terpakai dan bantu mahasiswa lain mendapatkan buku dengan harga terjangkau.
                    </p>
                </div>

                <div class="flex-grow"></div>

                <!-- Button -->
                <div class="w-full mb-6 translate-y-4 opacity-0 transition-all duration-700 delay-500 ease-out slide-btn">
                    <button onclick="nextSlide()" class="block w-full bg-blue-600 text-white font-semibold text-lg py-4 px-6 rounded-full text-center shadow-lg shadow-blue-500/50 hover:bg-blue-700 transition-colors">
                        Continue
                    </button>
                </div>
            </div>
        </div>

        <!-- ================= SLIDE 3 (Login/Register) ================= -->
        <div class="w-full h-full flex-shrink-0 relative flex flex-col slide-item bg-blue-600" data-theme="blue">
            <!-- Content -->
            <div class="relative z-10 flex flex-col flex-grow p-6 pt-24"> <!-- pt ditambah -->

                <!-- Text -->
                <div class="mt-4 translate-y-4 opacity-0 transition-all duration-700 delay-200 ease-out slide-text">
                    <h1 class="font-sugo text-4xl font-bold text-white leading-tight">
                        Mau<br>
                        <span class="inline-flex items-baseline">
                            <img src="{{ asset('images/logo-white.png') }}" alt="B" class="h-9 w-auto inline-block -mb-1 mr-0.5">
                            <span>eli</span>
                        </span>
                        permanen<br>atau sewa<br>sementara?<br>Kamu yang<br>tentukan.
                    </h1>
                    <p class="mt-4 text-white/80 text-base leading-relaxed">
                        Sesuaikan kebutuhanmu dengan opsi beli atau sewa buku, lalu lanjutkan transaksi dengan cara yang paling praktis.
                    </p>
                </div>

                <div class="flex-grow"></div>

                <!-- Buttons -->
                <div class="w-full space-y-4 mb-6 translate-y-4 opacity-0 transition-all duration-700 delay-500 ease-out slide-btn">
                    <!-- Register -->
                    <a href="{{ url('/signup') }}" class="block w-full bg-white text-blue-600 font-semibold text-lg py-4 px-6 rounded-full text-center shadow-lg hover:bg-gray-50 transition-colors">
                        Register Now!
                    </a>
                    <!-- Login -->
                    <a href="{{ url('/login') }}" class="block w-full text-white border border-white/30 font-semibold text-lg py-3 px-6 rounded-full text-center hover:bg-white/10 transition-colors">
                        Log In
                    </a>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const track = document.getElementById('slider-track');
        const container = document.getElementById('onboarding-container');
        const slides = document.querySelectorAll('.slide-item');
        const globalIndicator = document.getElementById('global-indicator-container');
        const skipBtn = document.getElementById('skip-btn');

        let currentIndex = 0;
        let startX = 0;
        let isDragging = false;

        // Inisialisasi awal
        updateSlideClasses(0);
        renderIndicators(0);

        // --- Core Navigation Function ---
        window.nextSlide = function() {
            if (currentIndex < slides.length - 1) {
                goToSlide(currentIndex + 1);
            }
        };

        window.jumpToLast = function() {
            goToSlide(slides.length - 1);
        };

        function goToSlide(index) {
            currentIndex = index;
            const translateX = -(currentIndex * 100);
            track.style.transform = `translateX(${translateX}%)`;

            updateSlideClasses(index);
            renderIndicators(index);

            // Ubah background container & warna elemen header jika masuk ke slide biru
            const currentTheme = slides[index].getAttribute('data-theme');
            if (currentTheme === 'blue') {
                container.classList.remove('bg-white');
                container.classList.add('bg-blue-600');

                // Sembunyikan tombol skip di slide terakhir
                skipBtn.style.opacity = '0';
                skipBtn.style.pointerEvents = 'none';
            } else {
                container.classList.add('bg-white');
                container.classList.remove('bg-blue-600');

                // Tampilkan tombol skip
                skipBtn.style.opacity = '1';
                skipBtn.style.pointerEvents = 'auto';
            }
        }

        // --- Animasi & Indikator ---
        function updateSlideClasses(activeIndex) {
            slides.forEach((slide, index) => {
                const img = slide.querySelector('.slide-img');
                const text = slide.querySelector('.slide-text');
                const btn = slide.querySelector('.slide-btn');

                if (index === activeIndex) {
                    // Reset animasi
                    requestAnimationFrame(() => {
                        if(img) { img.classList.remove('translate-y-10', 'opacity-0'); }
                        if(text) { text.classList.remove('translate-y-4', 'opacity-0'); }
                        if(btn) { btn.classList.remove('translate-y-4', 'opacity-0'); }
                    });
                } else {
                    // Hide elemen jika tidak aktif
                    if(img) { img.classList.add('translate-y-10', 'opacity-0'); }
                    if(text) { text.classList.add('translate-y-4', 'opacity-0'); }
                    if(btn) { btn.classList.add('translate-y-4', 'opacity-0'); }
                }
            });
        }

        function renderIndicators(activeIndex) {
            // Tentukan warna berdasarkan slide yang sedang aktif
            const theme = slides[activeIndex].getAttribute('data-theme');
            const activeColor = theme === 'blue' ? 'bg-white' : 'bg-blue-600';
            const inactiveColor = theme === 'blue' ? 'bg-white/40' : 'bg-gray-300';

            let html = '';
            for (let i = 0; i < slides.length; i++) {
                if (i === activeIndex) {
                    html += `<div class="w-8 h-2.5 ${activeColor} rounded-full transition-all duration-300"></div>`;
                } else {
                    html += `<div class="w-2.5 h-2.5 ${inactiveColor} rounded-full transition-all duration-300"></div>`;
                }
            }
            globalIndicator.innerHTML = html;
        }

        // --- Touch / Swipe Logic ---
        track.addEventListener('touchstart', (e) => {
            startX = e.touches[0].clientX;
            isDragging = true;
        });

        track.addEventListener('touchmove', (e) => {
            if (!isDragging) return;
        });

        track.addEventListener('touchend', (e) => {
            if (!isDragging) return;
            const endX = e.changedTouches[0].clientX;
            const diff = startX - endX;

            // Threshold swipe: 50px
            if (diff > 50) {
                // Swipe Kiri (Next)
                if (currentIndex < slides.length - 1) nextSlide();
            } else if (diff < -50) {
                // Swipe Kanan (Prev)
                if (currentIndex > 0) goToSlide(currentIndex - 1);
            }
            isDragging = false;
        });
    });
</script>
@endpush
