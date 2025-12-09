@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50 font-sans">

    <!-- 1. HEADER (GSM UI Standard) -->
    <div class="relative bg-blue-600 pb-8 pt-12 rounded-b-[40px] shadow-lg px-6 mb-6">
        <div class="flex justify-between items-center mb-4">
            <h1 class="font-sugo text-3xl text-white tracking-wide">Courier Dashboard</h1>
            <!-- Link ke Statistik -->
            <a href="{{ route('courier.stats', ['name' => $selectedCourier]) }}" class="bg-white/20 hover:bg-white/30 text-white p-2 rounded-full transition backdrop-blur-sm">
                <img src="{{ asset('images/icon-clock-history.png') }}" class="w-6 h-6 invert brightness-0">
            </a>
        </div>

        <!-- Courier Selector (Pill Style) -->
        <div class="flex overflow-x-auto space-x-3 pb-2 scrollbar-hide">
            @foreach($couriers as $courier)
                <a href="{{ route('courier.index', ['name' => $courier]) }}"
                   class="px-5 py-2 rounded-full text-sm font-bold whitespace-nowrap transition {{ $selectedCourier == $courier ? 'bg-yellow-400 text-blue-900 shadow-md transform scale-105' : 'bg-blue-700/50 text-white hover:bg-blue-700' }}">
                   Hi, {{ $courier }}
                </a>
            @endforeach
        </div>
    </div>

    <!-- 2. CONTENT LIST -->
    <div class="px-6 pb-24 space-y-5">

        <!-- Header Section -->
        <div class="flex justify-between items-end">
            <div>
                <h2 class="text-xl font-bold text-gray-800">Daftar Pengiriman</h2>
                <p class="text-gray-500 text-sm">{{ $orders->count() }} paket perlu diantar</p>
            </div>
        </div>

        @forelse($orders as $order)
        <div class="bg-white rounded-2xl p-4 shadow-sm border border-gray-100 relative overflow-hidden group">

            <!-- Status Badge (Top Right) -->
            <div class="absolute top-0 right-0 px-4 py-1 rounded-bl-xl text-xs font-bold
                {{ $order->status == 'Delivered' ? 'bg-green-100 text-green-700' :
                  ($order->status == 'In Transit' ? 'bg-blue-100 text-blue-700' :
                  ($order->status == 'Picked' ? 'bg-purple-100 text-purple-700' : 'bg-yellow-100 text-yellow-700')) }}">
                {{ $order->status }}
            </div>

            <div class="flex gap-4 mt-2">
                <!-- Book Image -->
                <div class="w-20 h-28 flex-shrink-0 bg-gray-100 rounded-xl overflow-hidden shadow-inner">
                    <img src="{{ $order->book->gambar_buku[0] ?? asset('images/illustration-no-books.png') }}"
                         class="w-full h-full object-cover" alt="Book">
                </div>

                <!-- Details -->
                <div class="flex-grow flex flex-col justify-between">
                    <div>
                        <h3 class="font-bold text-gray-800 leading-tight line-clamp-2">{{ $order->book->judul_buku }}</h3>
                        <div class="mt-2 text-xs text-gray-500 space-y-1">
                            <div class="flex items-center gap-1">
                                <img src="{{ asset('images/icon-profile.png') }}" class="w-3 h-3 opacity-50" onerror="this.style.display='none'">
                                <span>Penerima: <span class="font-semibold text-gray-700">{{ $order->buyer->name }}</span></span>
                            </div>
                            <div class="flex items-center gap-1">
                                <img src="{{ asset('images/icon-location-pin.png') }}" class="w-3 h-3 opacity-50">
                                <span class="line-clamp-1">Lokasi Seller: {{ $order->book->alamat_buku }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Action Area -->
            <div class="mt-4 pt-3 border-t border-gray-100">
                <form action="{{ route('courier.update', $order->id) }}" method="POST" class="space-y-3">
                    @csrf

                    <!-- Message Input -->
                    <div class="relative">
                        <input type="text" name="message" value="{{ $order->courier_message }}"
                               class="w-full bg-gray-50 text-sm border-none rounded-lg py-2 px-3 focus:ring-2 focus:ring-blue-200"
                               placeholder="Tulis update lokasi/pesan...">
                    </div>

                    <div class="flex gap-2">
                        <!-- Status Select -->
                        <div class="relative flex-grow">
                            <select name="status" class="w-full appearance-none bg-white border border-gray-200 text-gray-700 py-2 px-4 pr-8 rounded-xl text-sm font-semibold focus:outline-none focus:border-blue-500">
                                <option value="Packing" {{ $order->status == 'Packing' ? 'selected' : '' }}>📦 Packing</option>
                                <option value="Picked" {{ $order->status == 'Picked' ? 'selected' : '' }}>🛵 Picked Up</option>
                                <option value="In Transit" {{ $order->status == 'In Transit' ? 'selected' : '' }}>🚚 In Transit</option>
                                <option value="Delivered" {{ $order->status == 'Delivered' ? 'selected' : '' }}>✅ Delivered</option>
                            </select>
                            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z"/></svg>
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-xl text-sm font-bold shadow-md transition transform active:scale-95">
                            Update
                        </button>
                    </div>
                </form>
            </div>
        </div>
        @empty
            <div class="flex flex-col items-center justify-center py-20 text-center">
                <img src="{{ asset('images/illustration-empty-cart.png') }}" class="w-48 mb-4 opacity-80" alt="Empty">
                <h3 class="font-bold text-gray-800 text-lg">Semua Aman!</h3>
                <p class="text-gray-500">Tidak ada pengiriman aktif untuk saat ini.</p>
            </div>
        @endforelse
    </div>
</div>
@endsection
