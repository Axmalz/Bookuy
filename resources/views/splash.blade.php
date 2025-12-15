@extends('layouts.app')
<!--
// Kode ditulis oleh :
// Nama  : Fadhiil Akmal Hamizan
// Github: Axmalz
// NRP   : 5026231128
// Kelas : PPPL B
-->
@section('content')
<style>
    /* Karena layout utama (layouts.app) sudah menangani bingkai HP,
       kita hanya perlu memastikan konten splash mengisi penuh area .app-content
    */

    #splash-container {
        /* Mengisi penuh parent (.app-content) */
        position: absolute;
        inset: 0;
        width: 100%;
        height: 100%;
        z-index: 40; /* Di bawah poni (z-50) tapi di atas konten lain */
        overflow: hidden;
    }

    /* Base class untuk setiap slide */
    .splash-slide {
        position: absolute;
        inset: 0;
        width: 100%;
        height: 100%;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        opacity: 0;
        visibility: hidden;
        transition: opacity 0.6s ease-in-out, transform 0.6s ease-out;
        transform: scale(0.95);
    }

    /* Class saat slide aktif */
    .splash-slide.active {
        opacity: 1;
        visibility: visible;
        transform: scale(1);
    }

    /* Animasi Spinner Custom */
    .custom-spinner {
        width: 40px;
        height: 40px;
        border: 4px solid rgba(255, 255, 255, 0.3);
        border-radius: 50%;
        border-top-color: #fff;
        animation: spin 1s ease-in-out infinite;
    }

    @keyframes spin {
        to { transform: rotate(360deg); }
    }
</style>

<!-- Konten Splash Langsung (Tanpa Bingkai Tambahan) -->
<div id="splash-container" class="bg-white transition-colors duration-700 ease-in-out">

    <!-- Halaman 1: Putih Polos -->
    <div class="splash-slide active" data-duration="500" data-bg="bg-white"></div>

    <!-- Halaman 2: Icon Muncul (Efek Pantul Sedikit) -->
    <div class="splash-slide" data-duration="1200" data-bg="bg-white">
        <img src="{{ asset('images/logo-color-icon.png') }}" alt="Bookuy Icon" class="w-24 h-auto drop-shadow-md animate-bounce-slight">
    </div>

    <!-- Halaman 3: Logo Full -->
    <div class="splash-slide" data-duration="1500" data-bg="bg-white">
        <img src="{{ asset('images/logo-color-full.png') }}" alt="Bookuy Logo" class="w-48 h-auto drop-shadow-md">
    </div>

    <!-- Halaman 4: Kembali ke Icon -->
    <div class="splash-slide" data-duration="800" data-bg="bg-white">
        <img src="{{ asset('images/logo-color-icon.png') }}" alt="Bookuy Icon" class="w-24 h-auto">
    </div>

    <!-- Halaman 5: Background Biru + Loading -->
    <div class="splash-slide" data-duration="2500" data-bg="bg-blue-600">
        <div class="flex flex-col items-center gap-6">
            <img src="{{ asset('images/logo-white.png') }}" alt="Bookuy Logo White" class="w-32 h-auto drop-shadow-lg">
            <div class="custom-spinner"></div>
            <p class="text-white text-sm font-medium tracking-wide animate-pulse">Memuat aplikasi...</p>
        </div>
    </div>

</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const container = document.getElementById('splash-container');
        const slides = document.querySelectorAll('.splash-slide');
        let currentIndex = 0;

        function runAnimationSequence() {
            if (currentIndex >= slides.length) {
                // Selesai, redirect ke halaman baru onboarding
                window.location.href = '{{ url("/onboarding") }}'; // <-- URL DIPERBARUI
                return;
            }

            const currentSlide = slides[currentIndex];
            const duration = parseInt(currentSlide.dataset.duration) || 1000;
            const bgClass = currentSlide.dataset.bg;

            // 1. Ubah background container
            if (currentIndex > 0) {
                const prevBg = slides[currentIndex - 1].dataset.bg;
                if (prevBg !== bgClass) {
                    container.classList.remove(prevBg);
                    container.classList.add(bgClass);
                }
            } else {
                container.classList.add(bgClass);
            }

            // 2. Aktifkan slide sekarang
            slides.forEach((s, i) => {
                if (i === currentIndex) {
                    s.classList.add('active');
                } else {
                    s.classList.remove('active');
                }
            });

            // 3. Jadwalkan langkah berikutnya
            setTimeout(() => {
                currentIndex++;
                runAnimationSequence();
            }, duration);
        }

        // Mulai
        runAnimationSequence();
    });
</script>
@endpush
