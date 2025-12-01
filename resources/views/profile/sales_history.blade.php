@extends('layouts.app')

@section('content')
<div class="w-full h-full bg-white flex flex-col relative">

    <!-- 1. Header Biru -->
    <div class="w-full bg-blue-600 pt-12 pb-6 rounded-b-[40px] shadow-md z-20 relative px-6 flex-shrink-0">

        <!-- Navigasi Atas -->
        <div class="relative flex flex-col items-center justify-center mb-6">
            <!-- Tombol Back -->
            <a href="{{ route('profile.index') }}" class="absolute left-0 top-1 text-white hover:text-gray-200 transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-6 h-6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
                </svg>
            </a>

            <!-- Logo Putih -->
            <img src="{{ asset('images/icon-bookuy-logo-white.png') }}" alt="Bookuy" class="h-16 w-auto mb-1 drop-shadow-sm">

            <!-- Judul -->
            <h1 class="font-sugo text-3xl text-white tracking-wide">Sales History</h1>
        </div>

        <!-- Tab Navigasi (Ongoing / Completed) -->
        <div class="bg-white/20 p-1 rounded-full flex shadow-inner backdrop-blur-sm border border-white/10">
            <a href="{{ route('profile.sales_history', ['tab' => 'ongoing']) }}"
               class="flex-1 py-2 rounded-full text-center text-sm font-bold transition-all duration-300
                      {{ $tab == 'ongoing' ? 'bg-blue-600 text-white shadow-md' : 'text-white/80 hover:bg-white/10' }}">
                Ongoing
            </a>
            <a href="{{ route('profile.sales_history', ['tab' => 'completed']) }}"
               class="flex-1 py-2 rounded-full text-center text-sm font-bold transition-all duration-300
                      {{ $tab == 'completed' ? 'bg-blue-600 text-white shadow-md' : 'text-white/80 hover:bg-white/10' }}">
                Completed
            </a>
        </div>
    </div>

    <!-- 2. Konten List (Scrollable) -->
    <div class="flex-grow overflow-y-auto px-6 pt-6 pb-10 bg-white no-scrollbar">

        @if($orders->count() > 0)
            <div class="space-y-4">
                @foreach($orders as $order)
                <!-- Item Card -->
                <div class="bg-white border border-gray-100 rounded-2xl p-4 shadow-sm relative">

                    <!-- Status Badge (Pojok Kanan Atas) -->
                    <div class="absolute top-4 right-4 px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-wide
                        @if($order->status == 'Delivered') bg-green-100 text-green-600
                        @elseif($order->status == 'In Transit') bg-yellow-100 text-yellow-700
                        @else bg-gray-100 text-gray-600 @endif">
                        {{ $order->status == 'Delivered' ? 'Completed' : $order->status }}
                    </div>

                    <div class="flex gap-4">
                        <!-- Foto Buku -->
                        <div class="w-20 h-20 flex-shrink-0 rounded-lg overflow-hidden bg-gray-100 border border-gray-200">
                            <img src="{{ isset($order->book->gambar_buku[0]) ? $order->book->gambar_buku[0] : '' }}"
                                 class="w-full h-full object-cover">
                        </div>

                        <!-- Detail Info -->
                        <div class="flex-grow min-w-0 flex flex-col justify-center">
                            <!-- Judul -->
                            <h4 class="font-bold text-gray-800 text-sm truncate leading-tight pr-20" title="{{ $order->book->judul_buku }}">
                                {{ $order->book->judul_buku }}
                            </h4>

                            <!-- Meta Info -->
                            <div class="flex items-center text-xs text-gray-400 mt-1 mb-2">
                                <span class="capitalize">{{ $order->type }}</span>
                                <span class="mx-1.5">|</span>
                                <span class="capitalize">{{ $order->book->kondisi_buku }}</span>
                            </div>

                            <!-- Harga -->
                            <div class="text-blue-600 font-bold text-base">
                                Rp {{ number_format($order->price, 0, ',', '.') }}
                            </div>

                            <!-- Rating (Hanya di Completed) -->
                            @if($tab == 'completed' && $order->rating)
                            <div class="flex items-center gap-1 mt-1">
                                <img src="{{ asset('images/icon-star-full.png') }}" class="w-3 h-3">
                                <span class="text-xs font-bold text-gray-600">{{ $order->rating }}/5</span>
                            </div>
                            @endif
                        </div>
                    </div>

                    <!-- Tombol Track Order (Hanya di Ongoing) -->
                    @if($tab == 'ongoing')
                    <div class="flex justify-end mt-3">
                        <!-- Placeholder Link -->
                        <a href="#" class="bg-blue-600 text-white text-xs font-bold px-6 py-2 rounded-full shadow-md hover:bg-blue-700 transition-colors">
                            Track Order
                        </a>
                    </div>
                    @endif

                </div>
                @endforeach
            </div>
        @else
            <!-- Empty State -->
            <div class="flex flex-col items-center justify-center h-full pt-10 text-center text-gray-400">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-16 h-16 mb-4 opacity-50">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                </svg>
                <p class="text-sm">Belum ada transaksi di sini.</p>
            </div>
        @endif

        <!-- Spacer Bawah -->
        <div class="h-10"></div>
    </div>

</div>

<style>
    .no-scrollbar::-webkit-scrollbar { display: none; }
    .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
</style>
@endsection
