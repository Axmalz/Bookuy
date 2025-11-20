@extends('layouts.app')

@section('content')
<!--
  Kontainer utama untuk halaman onboarding.
  Menggunakan flex-col untuk menata elemen secara vertikal.
  Latar belakang putih solid.
-->
<div class="w-full h-full bg-white flex flex-col relative">

    <!--
      Konten Latar Belakang (Gambar Ilustrasi)
      - Ditempatkan secara absolut di bagian bawah.
      - z-index: 0 (paling belakang)
    -->
    <div class="absolute bottom-0 left-0 w-full z-0">
        <img src="{{ asset('images/onboarding-2.png') }}" alt="Ilustrasi Onboarding 2" class="w-full object-cover">
    </div>

    <!--
      Konten Utama (Header, Teks, Tombol)
      - Ditempatkan di atas gambar (z-index: 10)
      - Padding atas (pt-12) untuk memberi ruang di bawah notch.
      - Padding horizontal (px-6) untuk jarak dari tepi.
    -->
    <div class="relative z-10 flex flex-col flex-grow p-6 pt-12">

        <!-- Bagian Header: Indikator Halaman dan Tombol Skip -->
        <div class="flex justify-between items-center w-full">

            <!-- 1. Indikator Halaman (Dibuat dengan Div/Tailwind) -->
            <div>
                <div class="flex items-center gap-1.5">
                    <!-- Titik Tidak Aktif (Abu-abu) -->
                    <div class="w-2.5 h-2.5 bg-gray-300 rounded-full"></div>
                    <!-- Baris Aktif (Biru) -->
                    <div class="w-8 h-2.5 bg-blue-600 rounded-full"></div>
                    <!-- Titik Tidak Aktif (Abu-abu) -->
                    <div class="w-2.5 h-2.5 bg-gray-300 rounded-full"></div>
                </div>
            </div>

            <!-- 2. Tombol Skip (Tetap sama) -->
            <div>
                <!-- DIEDIT: Menghapus font-poppins (diwarisi dari body) -->
                <a href="{{ url('/signup') }}" class="font-medium text-gray-500 text-lg">
                    Skip
                </a>
            </div>
        </div>

        <!-- Bagian Teks Judul -->
        <div class="mt-12">
            <!-- Font 'Sugo' dan ukuran besar -->
            <h1 class="font-sugo text-4xl font-bold text-blue-600 leading-tight">
                Ubah rak
                <br>
                <!--
                  Ini adalah bagian untuk menggabungkan logo 'B' dengan teks 'uku'
                -->
                <span class="inline-flex items-baseline">
                    <!-- Gambar Logo 'B' (Menggunakan logo-color-icon.png) -->
                    <img src="{{ asset('images/logo-color-icon.png') }}" alt="B" class="h-9 w-auto inline-block -mb-1 mr-0.5">
                    <!-- Teks sisanya -->
                    <span>uku</span>
                </span>
                jadi
                <br>
                penghasilan!
            </h1>
        </div>

        <!-- Bagian Teks Deskripsi -->
        <div class="mt-4">
            <!-- DIEDIT: Menghapus font-poppins (diwarisi dari body) -->
            <p class="text-gray-600 text-base leading-relaxed">
                Ambil buku yang sudah tidak terpakai dan bantu mahasiswa lain mendapatkan buku dengan harga terjangkau.
            </p>
        </div>

        <!-- Tombol Continue (didorong ke bawah) -->
        <div class="flex-grow"></div> <!-- Spacer untuk mendorong tombol ke bawah -->

        <!-- 3. Tombol Continue -->
        <!-- DIEDIT: Menghapus font-poppins (diwarisi dari body) -->
        <a href="{{ url('/onboarding/3') }}" class="block w-full bg-blue-600 text-white font-semibold text-lg py-4 px-6 rounded-full text-center shadow-lg shadow-blue-500/50">
            Continue
        </a>

    </div>
</div>
@endsection
