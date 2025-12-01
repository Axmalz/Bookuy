@extends('layouts.app')

<!--
  File layout BARU ini adalah perpanjangan dari layouts.app
  Tujuannya HANYA untuk menambahkan Navigation Bar.
  Gunakan layout ini untuk 5 halaman utama (Home, Chat, Create, Notif, Profile).
-->

@push('styles')
<style>
    /* == GAYA UNTUK NAV BAR == */

    /* DIEDIT: Padding sangat besar (180px) agar konten aman */
    .app-content {
        padding-bottom: 180px !important;
    }

    .nav-bar {
        position: absolute; /* Menempel di .iphone-screen (induk) */
        bottom: 0;
        left: 0;
        right: 0;
        height: 95px; /* Tinggi total termasuk area ikon aktif */
        z-index: 1000;
        display: flex;
        justify-content: center;
        pointer-events: none; /* Agar klik tembus di area kosong atas nav bar */
    }

    .nav-bar-bg-img {
        position: absolute;
        bottom: 0;
        left: 0;
        width: 100%;
        height: 85px; /* Tinggi bar utama */
        object-fit: cover; /* Pastikan gambar memenuhi area */
        pointer-events: auto; /* Kembalikan pointer events untuk gambar */
    }

    .nav-items {
        display: flex;
        justify-content: space-around;
        align-items: center;
        position: absolute;
        bottom: 15px; /* Sedikit naik dari bawah */
        left: 0;
        right: 0;
        height: 65px;
        padding: 0 1rem;
        pointer-events: auto; /* Kembalikan pointer events untuk ikon */
    }

    .nav-item {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        width: 50px;
        height: 50px;
        position: relative;
        transition: all 0.3s ease;
    }

    .nav-item img {
        width: 28px;
        height: 28px;
        filter: grayscale(100%) brightness(200%); /* Ikon putih tidak aktif */
    }

    .nav-item.active {
        /* Ikon aktif (di tengah) */
        transform: translateY(-25px); /* Naik ke atas */
        background-color: #2563EB; /* Biru */
        border-radius: 50%;
        width: 64px;
        height: 64px;
        border: 5px solid white; /* Lingkaran putih di sekeliling */
        box-shadow: 0 -4px 10px rgba(0,0,0,0.1);
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .nav-item.active img {
        filter: none; /* Warna ikon asli (putih) */
        width: 32px;
        height: 32px;
    }
</style>
@endpush

@section('content')
    <!-- Ini akan mengisi .app-content (area scroll) -->
    @yield('main-content')
@endsection

@push('navbar')
    <!-- DIEDIT: Ini akan mengisi @stack('navbar') (area statis) -->
    @auth
    <nav class="nav-bar">
        <!-- Latar Belakang Gambar (Asumsi 'Subtract.png' diganti nama jadi 'nav-bar-bg.png') -->
        <img src="{{ asset('images/nav-bar-bg.png') }}" alt="Nav Background" class="nav-bar-bg-img">

        <!-- Ikon-Ikon Navigasi -->
        <div class="nav-items">
            @php
                $currentRoute = request()->route()->getName();
            @endphp

            <a href="{{ route('home') }}" class="nav-item {{ $currentRoute == 'home' ? 'active' : '' }}">
                <img src="{{ asset('images/nav-home.png') }}" alt="Home">
            </a>
            <a href="{{ route('chat.index') }}" class="nav-item {{ $currentRoute == 'chat.index' ? 'active' : '' }}">
                <img src="{{ asset('images/nav-chat.png') }}" alt="Chat">
            </a>
            <a href="{{ route('product.create') }}" class="nav-item {{ $currentRoute == 'product.create' ? 'active' : '' }}">
                <img src="{{ asset('images/nav-create.png') }}" alt="Create">
            </a>
            <a href="{{ route('notifications.index') }}" class="nav-item {{ $currentRoute == 'notifications.index' ? 'active' : '' }}">
                <img src="{{ asset('images/nav-notification.png') }}" alt="Notification">
            </a>
            <a href="{{ route('profile.index') }}" class="nav-item {{ $currentRoute == 'profile.index' ? 'active' : '' }}">
                <img src="{{ asset('images/nav-profile.png') }}" alt="Profile">
            </a>
        </div>
    </nav>
    @endauth
@endpush
