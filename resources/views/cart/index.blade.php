@extends('layouts.app')

@section('content')
<div class="w-full h-full bg-white flex flex-col relative">

    <!-- 1. Header Biru (Fixed) -->
    <div class="w-full bg-blue-600 pt-12 pb-6 rounded-b-[30px] shadow-md z-20 relative px-6">

        <!-- Baris Atas: Back, Title -->
        <div class="flex items-center justify-between mb-6">
            <a href="{{ route('home') }}" class="text-white hover:text-gray-200 transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-6 h-6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
                </svg>
            </a>

            <h1 class="font-sugo text-3xl text-white tracking-wide">Keranjang</h1>

            <!-- Spacer agar judul di tengah -->
            <div class="w-6"></div>
        </div>

        <!-- Tab Navigasi (Beli / Sewa) -->
        <div class="bg-white p-1 rounded-full flex shadow-inner">
            <a href="{{ route('cart.index', ['tab' => 'beli']) }}"
               class="flex-1 py-2 rounded-full text-center text-sm font-bold transition-all duration-300
                      {{ $activeTab == 'beli' ? 'bg-blue-600 text-white shadow-md' : 'text-gray-400 hover:bg-gray-100' }}">
                Beli
            </a>
            <a href="{{ route('cart.index', ['tab' => 'sewa']) }}"
               class="flex-1 py-2 rounded-full text-center text-sm font-bold transition-all duration-300
                      {{ $activeTab == 'sewa' ? 'bg-blue-600 text-white shadow-md' : 'text-gray-400 hover:bg-gray-100' }}">
                Sewa
            </a>
        </div>
    </div>

    <!-- 2. Konten Keranjang (Scrollable) -->
    <div class="flex-grow overflow-y-auto px-6 pt-6 pb-40 bg-white no-scrollbar relative">

        @if($items->count() > 0)
            <!-- LIST ITEM (Jika keranjang terisi) -->
            <div class="space-y-4">
                @foreach($items as $item)
                <div class="bg-white border border-gray-100 rounded-2xl p-4 shadow-sm flex gap-3 items-start relative group transition-all hover:border-blue-200">

                    <!-- Checkbox -->
                    <div class="flex items-center h-full pt-8">
                        <button onclick="toggleSelection({{ $item->id }})" class="focus:outline-none">
                            <!-- Ikon Checkbox (Ganti src sesuai status) -->
                            <img id="check-img-{{ $item->id }}"
                                 src="{{ $item->is_selected ? asset('images/icon-check-blue.png') : asset('images/icon-uncheck-grey.png') }}"
                                 class="w-6 h-6 transition-transform active:scale-90">
                        </button>
                    </div>

                    <!-- Gambar Buku -->
                    <div class="w-20 h-20 flex-shrink-0 rounded-lg overflow-hidden bg-gray-100 border border-gray-200">
                        <!-- Ambil gambar pertama dari array gambar buku -->
                        <img src="{{ isset($item->book->gambar_buku[0]) ? $item->book->gambar_buku[0] : '' }}"
                             class="w-full h-full object-cover">
                    </div>

                    <!-- Detail Info -->
                    <div class="flex-grow min-w-0 flex flex-col h-full justify-between">
                        <div class="flex justify-between items-start">
                            <div class="pr-6">
                                <h4 class="font-bold text-gray-800 text-sm truncate leading-tight">{{ $item->book->judul_buku }}</h4>
                                <p class="text-xs text-gray-400 mt-0.5 capitalize">{{ $item->book->kondisi_buku }}</p>
                            </div>

                            <!-- Tombol Hapus -->
                            <form action="{{ route('cart.remove', $item->id) }}" method="POST" class="absolute top-4 right-4">
                                @csrf @method('DELETE')
                                <button type="submit" class="p-1 opacity-50 hover:opacity-100 transition-opacity">
                                    <img src="{{ asset('images/icon-trash-red.png') }}" class="w-4 h-4">
                                </button>
                            </form>
                        </div>

                        <div class="flex justify-between items-end mt-2">
                            <!-- Harga Total Item -->
                            <div class="text-blue-600 font-bold text-sm">
                                Rp {{ number_format(($item->type == 'sewa' ? $item->book->harga_sewa : $item->book->harga_beli) * $item->quantity, 0, ',', '.') }}
                            </div>

                            <!-- Kontrol Kuantitas / Durasi -->
                            <div class="flex items-center gap-2 bg-gray-50 rounded-lg px-2 py-1 border border-gray-100">
                                @if($item->type == 'beli')
                                    <!-- Mode Beli: Plus Minus -->
                                    <button onclick="updateQty({{ $item->id }}, -1)" class="w-5 h-5 flex items-center justify-center text-gray-400 hover:text-blue-600 disabled:opacity-30">
                                        <img src="{{ asset('images/icon-minus-grey.png') }}" class="w-3 h-3">
                                    </button>
                                    <span id="qty-text-{{ $item->id }}" class="text-xs font-bold text-gray-700 w-4 text-center">{{ $item->quantity }}</span>
                                    <button onclick="updateQty({{ $item->id }}, 1)" class="w-5 h-5 flex items-center justify-center text-gray-400 hover:text-blue-600">
                                        <img src="{{ asset('images/icon-plus-blue.png') }}" class="w-3 h-3">
                                    </button>
                                @else
                                    <!-- Mode Sewa: Info Semester -->
                                    <span class="text-xs font-bold text-gray-600 px-1">{{ $item->quantity }} Semester</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

        @else
            <!-- EMPTY STATE (Jika keranjang kosong) -->
            <div class="flex flex-col items-center justify-center h-full pt-10 text-center fade-in">
                <div class="mb-1 relative w-full max-w-full">
                    <div class="mx-auto w-56 h-56 sm:w-80 sm:h-80">
                        <!-- Menggunakan gambar ilustrasi yang sudah ada -->
                        <img src="{{ asset('images/illustration-no-books.png') }}" alt="Empty Cart" class="w-full h-full object-contain drop-shadow-lg">
                    </div>
                </div>
                <h3 class="text-2xl font-bold text-blue-900 mb-2">Your Cart Is Empty!</h3>
                <p class="text-gray-500 text-sm mb-8 max-w-[250px]">
                    When you add products, they’ll appear here.
                </p>
                <a href="{{ route('home') }}" class="w-full max-w-[280px] py-4 bg-yellow-500 text-white font-bold text-lg rounded-full shadow-lg hover:bg-yellow-600 transition-transform hover:scale-105 active:scale-95">
                    Go Shop
                </a>
            </div>
        @endif
    </div>

    <!-- 3. Frame Kalkulasi (Bottom Sheet) -->
    <!-- Hanya muncul jika ada item DAN ada item yang dicentang ($hasSelected) -->
    @if($items->count() > 0 && $hasSelected)
    <div class="absolute bottom-0 left-0 w-full bg-white rounded-t-[30px] shadow-[0_-5px_30px_rgba(0,0,0,0.1)] z-30 px-6 py-6 animate-slide-up">
        <!-- Rincian Biaya -->
        <div class="space-y-2 mb-6">
            <div class="flex justify-between text-sm">
                <span class="text-gray-500">Sub-total</span>
                <span class="font-bold text-blue-600">Rp {{ number_format($subtotal, 0, ',', '.') }}</span>
            </div>
            <div class="flex justify-between text-sm">
                <span class="text-gray-500">Biaya Admin</span>
                <span class="font-bold text-blue-600">Rp {{ number_format($adminFee, 0, ',', '.') }}</span>
            </div>
            <div class="flex justify-between text-sm">
                <span class="text-gray-500">Shipping fee</span>
                <span class="font-bold text-blue-600">Rp {{ number_format($shippingFee, 0, ',', '.') }}</span>
            </div>
            <div class="border-t border-gray-100 my-2"></div>
            <div class="flex justify-between items-center">
                <span class="font-bold text-gray-900 text-lg">Total</span>
                <span class="font-bold text-blue-600 text-xl">Rp {{ number_format($total, 0, ',', '.') }}</span>
            </div>
        </div>

        <!-- Tombol Checkout (Placeholder) -->
        <a href="#" class="w-full py-4 bg-blue-600 text-white rounded-full flex items-center justify-center gap-2 shadow-lg hover:bg-blue-700 transition-colors group">
            <span class="font-bold text-lg">Go To Checkout</span>
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="3" stroke="currentColor" class="w-5 h-5 group-hover:translate-x-1 transition-transform">
                <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" />
            </svg>
        </a>
    </div>
    @endif

</div>

<!-- CSS Tambahan -->
<style>
    .no-scrollbar::-webkit-scrollbar { display: none; }
    .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
    @keyframes slide-up {
        from { transform: translateY(100%); }
        to { transform: translateY(0); }
    }
    .animate-slide-up { animation: slide-up 0.3s ease-out forwards; }
    .fade-in { animation: fadeIn 0.5s ease-in; }
    @keyframes fadeIn { from { opacity: 0; } to { opacity: 1; } }
</style>
@endsection

@push('scripts')
<script>
    // Fungsi untuk mengupdate status checkbox (Centang/Tidak Centang)
    function toggleSelection(itemId) {
        const checkImg = document.getElementById('check-img-' + itemId);
        const isChecked = checkImg.src.includes('check-blue'); // Cek state saat ini dari gambar

        // Kirim request ke server untuk update database
        fetch(`{{ url('/cart/update') }}/${itemId}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                is_selected: !isChecked // Balik nilainya
            })
        }).then(() => {
            // Reload halaman agar total harga dihitung ulang oleh server
            window.location.reload();
        });
    }

    // Fungsi untuk mengupdate kuantitas (Plus/Minus)
    function updateQty(itemId, change) {
        const qtySpan = document.getElementById('qty-text-' + itemId);
        let currentQty = parseInt(qtySpan.innerText);
        let newQty = currentQty + change;

        if (newQty < 1) return; // Minimal 1

        fetch(`{{ url('/cart/update') }}/${itemId}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                quantity: newQty
            })
        }).then(() => {
            // Reload halaman agar total harga dihitung ulang
            window.location.reload();
        });
    }
</script>
@endpush
