@extends('layouts.app')

@section('content')
<!--
  Kontainer utama untuk halaman onboarding.
  DIEDIT: Latar belakang biru solid (bg-blue-600)
-->
<div class="w-full h-full bg-blue-600 flex flex-col relative">

    <!--
      Konten Utama (Header, Teks, Tombol)
      - Padding atas (pt-12) untuk memberi ruang di bawah notch.
      - Padding horizontal (px-6) untuk jarak dari tepi.
    -->
    <div class="relative z-10 flex flex-col flex-grow p-6 pt-12">

        <!-- Bagian Header: Indikator Halaman -->
        <div class="flex items-center w-full">

            <!-- 1. Indikator Halaman (Dibuat dengan Div/Tailwind) -->
            <div>
                <!-- == DIEDIT: Indikator untuk Halaman 3 (warna putih) == -->
                <div class="flex items-center gap-1.5">
                    <!-- Titik Tidak Aktif (Putih transparan) -->
                    <div class="w-2.5 h-2.5 bg-white/40 rounded-full"></div>
                    <!-- Titik Tidak Aktif (Putih transparan) -->
                    <div class="w-2.5 h-2.5 bg-white/40 rounded-full"></div>
                    <!-- Baris Aktif (Putih) -->
                    <div class="w-8 h-2.5 bg-white rounded-full"></div>
                </div>
            </div>

            <!-- 2. Tombol Skip DIHAPUS -->
        </div>

        <!-- Bagian Teks Judul -->
        <div class="mt-12">
            <!-- == DIEDIT: Teks Judul Baru (warna putih) == -->
            <h1 class="font-sugo text-4xl font-bold text-white leading-tight">
                Mau
                <!--
                  Ini adalah bagian untuk menggabungkan logo 'B' putih dengan teks 'eli'
                -->
                <span class="inline-flex items-baseline">
                    <!-- Gambar Logo 'B' (Menggunakan logo-white.png) -->
                    <img src="{{ asset('images/logo-white.png') }}" alt="B" class="h-9 w-auto inline-block -mb-1 mr-0.5">
                    <!-- Teks sisanya -->
                    <span>eli</span>
                </span>
                 permanen
                <br>
                atau sewa
                <br>
                sementara?
                <br>
                Kamu yang
                <br>
                tentukan.
            </h1>
        </div>

        <!-- Bagian Teks Deskripsi -->
        <div class="mt-4">
            <!-- == DIEDIT: Teks Deskripsi Baru (warna putih transparan) == -->
            <p class="text-white/80 text-base leading-relaxed">
                Sesuaikan kebutuhanmu dengan opsi beli atau sewa buku, lalu lanjutkan transaksi dengan cara yang paling praktis.
            </p>
        </div>

        <!-- Tombol (didorong ke bawah) -->
        <div class="flex-grow"></div> <!-- Spacer untuk mendorong tombol ke bawah -->

        <!-- == DIEDIT: Dua Tombol Baru == -->
        <div class="w-full space-y-4">
            <!-- 1. Tombol Register Now! (Primary) -->
            <a href="{{ url('/signup') }}" class="block w-full bg-white text-blue-600 font-semibold text-lg py-4 px-6 rounded-full text-center shadow-lg">
                Register Now!
            </a>

            <!-- 2. Tombol Log In (Secondary) -->
            <a href="{{ url('/login') }}" class="block w-full text-white font-semibold text-lg py-3 px-6 rounded-full text-center">
                Log In
            </a>
        </div>

    </div>
</div>
@endsection
