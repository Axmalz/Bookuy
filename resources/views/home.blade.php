@extends('layouts.app-main')
<!--
// Kode ditulis oleh :
// Nama  : Fadhiil Akmal Hamizan
// Github: Axmalz
// NRP   : 5026231128
// Kelas : PPPL B
-->
@section('main-content')
<div class="w-full h-full bg-white">

    <!-- 1. Bagian Header (Gradien Biru) -->
    <div class="w-full h-48 bg-gradient-to-b from-blue-600 to-blue-500 pt-12 px-6 relative">
        <div class="flex justify-between items-center">
            <!-- ... (Logo & Sapaan Tetap Sama) ... -->
            <div class="flex items-center gap-3 flex-1 min-w-0 mr-4">
                <img src="{{ asset('images/logo-white.png') }}" alt="Logo" class="w-10 h-auto flex-shrink-0">
                <div class="min-w-0 flex-1">
                    <span class="text-white text-lg font-medium block">Welcome</span>
                    <h2 class="text-white text-xl font-bold -mt-1 truncate block" title="{{ Auth::user()->name }}">
                        {{ explode(' ', Auth::user()->name)[0] }}!
                    </h2>
                </div>
            </div>

            <!-- 3. Tombol Keranjang (FIXED BADGE) -->
            <a href="{{ route('cart.index') }}" class="text-white relative flex-shrink-0">
                <img src="{{ asset('images/icon-cart-white.png') }}" alt="Keranjang" class="w-7 h-7">

                <!-- Badge Indikator -->
                @if($cartCount > 0)
                <span class="absolute -top-1 -right-1 bg-red-500 text-white text-[10px] font-bold w-4 h-4 rounded-full flex items-center justify-center transform scale-100 transition-transform duration-200">
                    {{ $cartCount }}
                </span>
                @endif
            </a>
        </div>

        <!-- ... (Sisa konten Home tetap sama) ... -->
        <a href="{{ route('search.index') }}" class="block mt-4">
            <div class="flex items-center w-full bg-transparent border-2 border-white/50 rounded-full p-3.5 shadow-lg">
                <img src="{{ asset('images/icon-search.png') }}" alt="Search" class="w-5 h-5 mr-3">
                <span class="text-white/80">Search for Books...</span>
                <div class="flex-grow"></div>
                <img src="{{ asset('images/icon-microphone.png') }}" alt="Mic" class="w-5 h-5 ml-3">
            </div>
        </a>
    </div>

    <!-- ... (Konten Bawah Home Tetap Sama) ... -->
    <div class="w-full px-6 pt-6">
        <!-- ... -->
        <div class="flex justify-between items-center mb-3">
            <div>
                <h3 class="text-xl font-bold text-gray-900">Kategori</h3>
                <p class="text-xs text-gray-500">Pilih Kategori Bidang yang Kamu Inginkan</p>
            </div>
            <a href="{{ route('search.index') }}" class="bg-yellow-400 text-yellow-900 text-[9px] font-bold px-1 py-1 rounded-full inline-flex items-center justify-center w-2/5 hover:bg-yellow-500 transition-colors text-center">
                Lihat Semua
            </a>
        </div>

        <!-- Scroll Kategori -->
        <div class="flex gap-3 overflow-x-auto pb-4 -mx-6 px-6" style="scrollbar-width: none; -ms-overflow-style: none; &::-webkit-scrollbar { display: none; }">
            @foreach($categories as $category)
            <a href="{{ route('search.index', ['category' => $category->name]) }}" class="flex-shrink-0 w-36">
                <div class="w-full h-24 bg-white border border-blue-100 rounded-2xl p-3 flex flex-col justify-between relative shadow-sm group hover:border-blue-300 hover:shadow-md transition-all duration-200">
                    <h4 class="text-sm font-bold text-gray-800 leading-tight group-hover:text-blue-600 transition-colors">
                        {{ $category->name }}
                    </h4>
                    <div class="absolute bottom-3 right-3 w-9 h-9 bg-blue-600 rounded-full flex items-center justify-center shadow-md shadow-blue-200 group-hover:shadow-blue-300 transition-all">
                        <img src="{{ asset($category->icon_path) }}" alt="{{ $category->name }}" class="w-5 h-5">
                    </div>
                </div>
            </a>
            @endforeach
        </div>

        <!-- Recommended -->
        <div class="mt-4">
            <h3 class="text-lg font-bold text-gray-900 mb-3">Recommended for you</h3>
            <div class="flex gap-4 overflow-x-auto pb-4 -mx-6 px-6" style="scrollbar-width: none; -ms-overflow-style: none; &::-webkit-scrollbar { display: none; }">
                @foreach($recommendedBooks as $book)
                <a href="{{ route('product.show', $book->id) }}" class="flex-shrink-0 w-32 group">
                    <div class="w-full aspect-[9/16] bg-gray-200 rounded-lg overflow-hidden shadow-sm border border-transparent group-hover:border-blue-300 group-hover:shadow-md transition-all duration-300 relative">
                        <!-- PERBAIKAN LOGIKA GAMBAR -->
                        @php
                            $imgSrc = '';
                            if (isset($book->gambar_buku[0])) {
                                $img = $book->gambar_buku[0];
                                if (Str::startsWith($img, 'http')) {
                                    $imgSrc = $img;
                                } else {
                                    $imgSrc = asset('storage/' . $img);
                                }
                            }
                        @endphp
                        <img src="{{ $imgSrc }}" alt="{{ $book->judul_buku }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                        <div class="absolute inset-0 bg-black/0 group-hover:bg-black/5 transition-colors duration-300"></div>
                    </div>
                </a>
                @endforeach
            </div>
        </div>

        <!-- 8. Bagian Popular books -->
        <div class="mt-4">
            <h3 class="text-lg font-bold text-gray-900 mb-3">Popular books</h3>
            <div class="space-y-4 pb-4">
                @foreach($popularBooks as $book)

                @php
                    // Cek Stok Habis (Beli 0 DAN Sewa 0)
                    $isOutOfStock = ($book->stok_beli <= 0 && $book->stok_sewa <= 0);
                @endphp

                <!-- Jika stok habis: bg-gray-100, grayscale, opacity rendah -->
                <div class="w-full border border-gray-200 rounded-2xl p-3 flex flex-col gap-3 shadow-sm transition-all duration-300
                            {{ $isOutOfStock ? 'bg-gray-100 opacity-75 grayscale' : 'bg-white group hover:border-blue-300 hover:shadow-md hover:-translate-y-1' }}">

                    <div class="flex gap-3">
                        <a href="{{ route('product.show', $book->id) }}" class="flex-shrink-0 w-20 h-28 overflow-hidden rounded-lg relative">
                            <div class="w-full h-full bg-gray-200">
                                <!-- PERBAIKAN LOGIKA GAMBAR -->
                                @php
                                    $imgSrc = '';
                                    if (isset($book->gambar_buku[0])) {
                                        $img = $book->gambar_buku[0];
                                        if (Str::startsWith($img, 'http')) {
                                            $imgSrc = $img;
                                        } else {
                                            $imgSrc = asset('storage/' . $img);
                                        }
                                    }
                                @endphp
                                <img src="{{ $imgSrc }}" alt="{{ $book->judul_buku }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                            </div>
                        </a>

                        <div class="flex-grow flex flex-col justify-center min-w-0">
                            <a href="{{ route('product.show', $book->id) }}">
                                <h4 class="font-bold text-gray-900 text-base truncate leading-tight group-hover:text-blue-600 transition-colors" title="{{ $book->judul_buku }}">
                                    {{ $book->judul_buku }}
                                </h4>
                            </a>
                            <p class="text-xs text-gray-500 mt-0.5 truncate">{{ $book->nama_penulis }}</p>

                            <div class="flex items-center gap-1 text-xs mt-1.5">
                                <span class="font-bold text-yellow-500 text-sm">
                                    {{ number_format($book->reviews_avg_rating ?? 0, 1) }}
                                </span>
                                <span class="text-gray-300">|</span>
                                <span class="text-gray-400 truncate">
                                    Based on {{
                                        ($book->reviews_count > 1000)
                                        ? number_format($book->reviews_count / 1000, 1) . 'k'
                                        : ($book->reviews_count ?? 0)
                                    }} Reviews
                                </span>
                            </div>

                            <p class="text-base font-bold text-gray-900 mt-1">Rp {{ number_format($book->harga_beli, 0, ',', '.') }}</p>
                        </div>
                    </div>

                    <!-- Tombol Grab Now / Out of Stock -->
                    @if($isOutOfStock)
                        <button disabled class="block w-full bg-gray-400 text-white text-xs font-bold uppercase tracking-wide py-2.5 rounded-full text-center cursor-not-allowed">
                            Out of Stock
                        </button>
                    @else
                        <a href="{{ route('product.show', $book->id) }}" class="block w-full bg-yellow-400 text-yellow-900 text-xs font-bold uppercase tracking-wide py-2.5 rounded-full text-center hover:bg-yellow-500 hover:shadow-lg hover:scale-[1.02] transition-all duration-200">
                            Grab Now
                        </a>
                    @endif
                </div>
                @endforeach
            </div>
        </div>

        <div class="h-20 w-full"></div>
    </div>
</div>
@endsection
