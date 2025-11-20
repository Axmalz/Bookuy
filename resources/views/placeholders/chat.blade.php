@extends('layouts.app-main') <!-- DIEDIT: Menggunakan layout baru -->

@section('main-content') <!-- DIEDIT: Menggunakan section baru -->
    <!-- Konten halaman placeholder -->
    <div class="w-full h-full pt-12 px-6 flex flex-col items-center justify-center">
        <h1 class="text-2xl font-bold text-black text-center">
            Halaman Chat
        </h1>
        <p class="text-gray-700 text-center mt-4">
            (Ini adalah placeholder)
        </p>
        <a href="{{ route('home') }}" class="text-blue-600 text-center block mt-4">Kembali ke Home</a>
    </div>
@endsection
