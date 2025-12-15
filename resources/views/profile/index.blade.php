@extends('layouts.app-main')
<!--
// Kode ditulis oleh :
// Nama  : Fadhiil Akmal Hamizan
// Github: Axmalz
// NRP   : 5026231128
// Kelas : PPPL B
-->
@section('main-content')

<!-- FIX: Override CSS untuk menghilangkan blok putih / padding berlebih -->
@push('styles')
<style>
    /* Kita mereset padding-bottom dari layout utama agar tidak ada ruang kosong besar */
    body .iphone-screen .app-content {
        padding-bottom: 0 !important;
    }
</style>
@endpush

<div class="w-full bg-white relative">

    <!-- 1. Header Biru dengan Profil -->
    <div class="relative bg-blue-600 pb-10 pt-14 rounded-b-[40px] shadow-lg z-10 px-8 flex items-center gap-5">

        <!-- Foto Profil -->
        <div class="w-20 h-20 rounded-full border-4 border-white/30 overflow-hidden bg-gray-200 shadow-md flex-shrink-0">
            @if(Auth::user()->profile_photo_path)
                <img src="{{ asset('storage/' . Auth::user()->profile_photo_path) }}" class="w-full h-full object-cover">
            @else
                <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=random&size=128" class="w-full h-full object-cover">
            @endif
        </div>

        <!-- Info User (Nama & Link Edit) -->
        <div class="flex flex-col text-left text-white min-w-0">
            <!-- Nama User -->
            <h2 class="font-sugo text-3xl tracking-wide leading-none mb-1 truncate">{{ Auth::user()->name }}</h2>

            <!-- Tombol Edit Profile (Link dengan Panah) -->
            <a href="{{ route('profile.edit') }}" class="text-blue-100 text-xs font-medium flex items-center gap-1 hover:text-white transition-colors group">
                Edit Profile
                <!-- Icon Panah Kanan (SVG) -->
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-3 h-3 group-hover:translate-x-0.5 transition-transform">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" />
                </svg>
            </a>
        </div>
    </div>

    <!-- 2. Menu List -->
    <!--
      FIX: Menambah pb-32 untuk memberikan ruang yang cukup di bawah
      agar item terakhir bisa di-scroll melewati navbar, menggantikan padding global yang dihapus.
    -->
    <div class="px-6 pt-6 pb-32 space-y-6">

        <!-- Account Security -->
        <div>
            <h3 class="font-bold text-gray-900 text-base mb-3">Account Security</h3>
            <!-- Privacy & Security -->
            <a href="#" class="flex items-center justify-between py-3 border-b border-gray-100 group">
                <div class="flex items-center gap-4">
                    <div class="w-10 h-10 bg-gray-100 rounded-full flex items-center justify-center text-gray-500 group-hover:bg-blue-50 group-hover:text-blue-600 transition-colors">
                        <img src="{{ asset('images/icon-shield-check.png') }}" alt="Security" class="w-5 h-5">
                    </div>
                    <div>
                        <h4 class="font-bold text-gray-800 text-sm">Privacy & Security</h4>
                        <p class="text-xs text-gray-400">Password, E-mail, Security Stuff</p>
                    </div>
                </div>
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4 text-gray-300"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" /></svg>
            </a>
        </div>

        <!-- Sales List -->
        <div>
            <h3 class="font-bold text-gray-900 text-base mb-3">Sales List</h3>
            <a href="{{ route('search.index', ['seller' => Auth::id()]) }}" class="flex items-center justify-between py-3 border-b border-gray-100 group">
                <div class="flex items-center gap-4">
                    <div class="w-10 h-10 bg-gray-100 rounded-full flex items-center justify-center text-gray-500 group-hover:bg-blue-50 group-hover:text-blue-600 transition-colors">
                        <!-- Icon Shopping Bag -->
                        <img src="{{ asset('images/icon-shopping-bag.png') }}" alt="Sales List" class="w-5 h-5">
                    </div>
                    <div>
                        <h4 class="font-bold text-gray-800 text-sm">Sales List</h4>
                        <p class="text-xs text-gray-400">See what you are selling here!</p>
                    </div>
                </div>
                <!-- Indikator Jumlah Buku -->
                <div class="bg-blue-600 text-white text-[10px] font-bold w-5 h-5 rounded-full flex items-center justify-center">
                    {{ \App\Models\Book::where('user_id', Auth::id())->count() }}
                </div>
            </a>
        </div>

        <!-- Purchase History -->
        <div>
            <h3 class="font-bold text-gray-900 text-base mb-3">History</h3>
            <!-- Sales History -->
            <a href="{{ route('profile.sales_history') }}" class="flex items-center justify-between py-3 border-b border-gray-100 group">
                <div class="flex items-center gap-4">
                    <div class="w-10 h-10 bg-gray-100 rounded-full flex items-center justify-center text-gray-500 group-hover:bg-blue-50 group-hover:text-blue-600 transition-colors">
                        <img src="{{ asset('images/icon-clock-history.png') }}" alt="Sales" class="w-5 h-5">
                    </div>
                    <div>
                        <h4 class="font-bold text-gray-800 text-sm">Sales History</h4>
                        <p class="text-xs text-gray-400">History Penjualanmu</p>
                    </div>
                </div>
                <!-- Indikator Sales History: Hitung order di mana user ini adalah penjual (seller_id) -->
                <div class="bg-blue-600 text-white text-[10px] font-bold w-5 h-5 rounded-full flex items-center justify-center">
                    {{ \App\Models\Order::where('seller_id', Auth::id())->count() }}
                </div>
            </a>

            <!-- Purchase History Item -->
            <a href="{{ route('profile.purchase_history') }}" class="flex items-center justify-between py-3 border-b border-gray-100 group">
                <div class="flex items-center gap-4">
                    <div class="w-10 h-10 bg-gray-100 rounded-full flex items-center justify-center text-gray-500 group-hover:bg-blue-50 group-hover:text-blue-600 transition-colors">
                        <img src="{{ asset('images/icon-receipt-refresh.png') }}" alt="Purchase" class="w-5 h-5">
                    </div>
                    <div>
                        <h4 class="font-bold text-gray-800 text-sm">Purchase History</h4>
                        <p class="text-xs text-gray-400">Leave a review!</p>
                    </div>
                </div>
                <!-- Indikator Purchase History: Hitung order di mana user ini adalah pembeli (buyer_id) -->
                <div class="bg-blue-600 text-white text-[10px] font-bold w-5 h-5 rounded-full flex items-center justify-center">
                    {{ \App\Models\Order::where('buyer_id', Auth::id())->count() }}
                </div>
            </a>
        </div>

        <!-- Information -->
        <div>
            <h3 class="font-bold text-gray-900 text-base mb-3">Information</h3>
            <!-- Address -->
            <a href="{{ route('address.index') }}" class="flex items-center justify-between py-3 border-b border-gray-100 group">
                <div class="flex items-center gap-4">
                    <div class="w-10 h-10 bg-gray-100 rounded-full flex items-center justify-center text-gray-500 group-hover:bg-blue-50 group-hover:text-blue-600 transition-colors">
                        <img src="{{ asset('images/icon-map-pin.png') }}" alt="Address" class="w-5 h-5">
                    </div>
                    <div>
                        <h4 class="font-bold text-gray-800 text-sm">Address</h4>
                        <p class="text-xs text-gray-400">Set up address to your location!</p>
                    </div>
                </div>
            </a>

            <!-- Payment (DIEDIT: Mengarah ke payment.index) -->
            <a href="{{ route('payment.index') }}" class="flex items-center justify-between py-3 border-b border-gray-100 group">
                <div class="flex items-center gap-4">
                    <div class="w-10 h-10 bg-gray-100 rounded-full flex items-center justify-center text-gray-500 group-hover:bg-blue-50 group-hover:text-blue-600 transition-colors">
                        <img src="{{ asset('images/icon-credit-card.png') }}" alt="Payment" class="w-5 h-5">
                    </div>
                    <div>
                        <h4 class="font-bold text-gray-800 text-sm">Payment</h4>
                        <p class="text-xs text-gray-400">Set up your payment method!</p>
                    </div>
                </div>
            </a>
        </div>

        <!-- Logout -->
        <div class="pb-4 flex justify-center">
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="flex items-center gap-2 text-red-500 font-bold text-sm hover:text-red-600 transition-colors">
                    <img src="{{ asset('images/icon-logout-red.png') }}" alt="Logout" class="w-5 h-5">
                    Logout
                </button>
            </form>
        </div>

        <div class="h-10 w-full"></div>

    </div>
</div>
@endsection
