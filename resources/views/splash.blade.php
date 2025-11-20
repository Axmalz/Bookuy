@extends('layouts.app')

@section('content')
<style>
    /* Sembunyikan semua halaman splash secara default.
      Pastikan mereka mengisi seluruh area konten dan ditumpuk.
    */
    .splash-page {
        display: none;
        width: 100%;
        height: 100%;
        position: absolute;
        top: 0;
        left: 0;
    }
</style>

<!--
  Kontainer untuk semua halaman splash.
  Semua halaman ini akan ditumpuk di dalam .app-content
  dan ditampilkan satu per satu menggunakan JavaScript.
-->

<!-- Halaman 1: Putih Polos (Durasi: 500ms) -->
<div class="splash-page bg-white" data-duration="500" data-display="block">
    <!-- Konten kosong -->
</div>

<!-- Halaman 2: Putih + Ikon Berwarna (Durasi: 1000ms) -->
<div class="splash-page bg-white flex items-center justify-center" data-duration="1000" data-display="flex">
    <!-- Ganti 'w-24' jika ukuran logo Anda berbeda -->
    <img src="{{ asset('images/logo-color-icon.png') }}" alt="Bookuy Icon" class="w-24 h-auto">
</div>

<!-- Halaman 3: Putih + Logo Penuh Berwarna (Durasi: 1500ms) -->
<div class="splash-page bg-white flex items-center justify-center" data-duration="1500" data-display="flex">
    <!-- Ganti 'w-48' jika ukuran logo Anda berbeda -->
    <img src="{{ asset('images/logo-color-full.png') }}" alt="Bookuy Logo" class="w-48 h-auto">
</div>

<!-- Halaman 4: Putih + Ikon Berwarna (Durasi: 1000ms) -->
<div class="splash-page bg-white flex items-center justify-center" data-duration="1000" data-display="flex">
    <img src="{{ asset('images/logo-color-icon.png') }}" alt="Bookuy Icon" class="w-24 h-auto">
</div>

<!-- Halaman 5: Biru + Logo Putih + Spinner (Durasi: 2000ms) -->
<div class="splash-page bg-blue-600 flex flex-col items-center justify-center gap-8" data-duration="2000" data-display="flex">
    <!-- Ini menggunakan logo putih yang sudah ada -->
    <img src="{{ asset('images/logo-white.png') }}" alt="Bookuy Logo White" class="w-32 h-auto">

    <!-- Spinner dari layout utama -->
    <div class="spinner"></div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Ambil semua elemen halaman splash
        const pages = document.querySelectorAll('.splash-page');
        let currentPageIndex = 0;

        function showNextPage() {
            // Sembunyikan halaman sebelumnya (jika ada)
            if (currentPageIndex > 0) {
                pages[currentPageIndex - 1].style.display = 'none';
            }

            // Cek jika masih ada halaman untuk ditampilkan
            if (currentPageIndex < pages.length) {
                const page = pages[currentPageIndex];

                // Ambil durasi dari atribut data-duration
                const duration = parseInt(page.dataset.duration, 10) || 1000;

                // == PERBAIKAN ==
                // Ambil tipe display (block atau flex) dari atribut data-display
                const displayType = page.dataset.display || 'block';

                // Tampilkan halaman saat ini MENGGUNAKAN TIPE DISPLAY YANG BENAR
                page.style.display = displayType;

                // Siapkan untuk halaman berikutnya
                currentPageIndex++;

                // Atur timer untuk memanggil fungsi ini lagi
                setTimeout(showNextPage, duration);
            } else {
                // Sekuens selesai, alihkan ke halaman welcome
                window.location.href = '{{ url("/welcome") }}';
            }
        }

        // Mulai sekuens animasi
        showNextPage();
    });
</script>
@endpush
