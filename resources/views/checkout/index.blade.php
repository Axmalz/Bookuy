@extends('layouts.app')
<!--
// Kode ditulis oleh :
// Nama  : Fadhiil Akmal Hamizan
// Github: Axmalz
// NRP   : 5026231128
// Kelas : PPPL B
-->
@section('content')
<div class="w-full h-full bg-white flex flex-col relative">

    <!-- 1. Header -->
    <div class="w-full bg-blue-600 pt-14 pb-5 rounded-b-[30px] shadow-md z-20 relative px-6 flex-shrink-0">
        <div class="relative flex flex-col items-center justify-center mb-2">
            <!-- Tombol Back Dinamis -->
            <button onclick="history.back()" class="absolute left-0 top-1 text-white hover:text-gray-200 transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-6 h-6"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" /></svg>
            </button>
            <h1 class="font-sugo text-3xl text-white tracking-wide">Checkout</h1>
        </div>
    </div>

    <!-- 2. Konten Scrollable -->
    <div class="flex-grow overflow-y-auto px-6 pt-6 pb-32 bg-white no-scrollbar">

        <!-- Delivery Address -->
        <div class="mb-6">
            <div class="flex justify-between items-center mb-3">
                <h3 class="font-bold text-gray-900 text-base">Delivery Address</h3>
                <a href="{{ route('address.index') }}" class="text-blue-600 text-xs font-bold underline">Change</a>
            </div>

            <!-- Menambahkan ID untuk validasi JS -->
            <div id="selected-address-container" data-has-address="{{ $defaultAddress ? 'true' : 'false' }}">
                @if($defaultAddress)
                    <div class="flex gap-4 items-start bg-white border border-gray-100 p-4 rounded-2xl shadow-sm">
                        <div class="w-10 h-10 bg-gray-100 rounded-full flex items-center justify-center flex-shrink-0">
                            <img src="{{ asset('images/icon-location-pin.png') }}" class="w-5 h-5">
                        </div>
                        <div>
                            <h4 class="font-bold text-gray-800 text-sm">{{ $defaultAddress->nickname }}</h4>
                            <p class="text-xs text-gray-400 mt-1 line-clamp-2">{{ $defaultAddress->full_address }}</p>
                        </div>
                    </div>
                @else
                    <a href="{{ route('address.index') }}" class="block w-full border-2 border-dashed border-red-300 rounded-xl py-3 text-center text-red-400 text-sm font-bold hover:border-red-500 hover:text-red-600 transition-colors bg-red-50/50">
                        + Add Delivery Address (Required)
                    </a>
                @endif
            </div>
        </div>

        <div class="h-px bg-gray-100 w-full mb-6"></div>

        <!-- ITEMS LIST (DITAMBAHKAN AGAR BISA CEK GAMBAR) -->
        <div class="mb-6">
            <h3 class="font-bold text-gray-900 text-base mb-3">Items</h3>
            <div class="space-y-3">
                @foreach($cartItems as $item)
                <div class="flex gap-3 bg-white border border-gray-100 p-3 rounded-2xl shadow-sm items-center">
                    <!-- Gambar Buku dengan Logika Baru -->
                    <div class="w-16 h-16 bg-gray-100 rounded-lg overflow-hidden flex-shrink-0 relative border border-gray-200">
                        @php
                            $imgSrc = asset('images/illustration-no-books.png');
                            if (isset($item->book->gambar_buku) && !empty($item->book->gambar_buku)) {
                                $img = is_array($item->book->gambar_buku) ? $item->book->gambar_buku[0] : $item->book->gambar_buku;
                                if (Illuminate\Support\Str::startsWith($img, 'http')) {
                                    $imgSrc = $img;
                                } else {
                                    // REVISI: Mengambil file dari folder 'public/books'
                                    $filename = basename($img);
                                    $imgSrc = asset('books/' . $filename);
                                }
                            }
                        @endphp
                        <img src="{{ $imgSrc }}" class="w-full h-full object-cover" onerror="this.src='{{ asset('images/illustration-no-books.png') }}'">
                    </div>
                    
                    <div class="flex-grow min-w-0">
                        <h4 class="font-bold text-gray-800 text-sm line-clamp-1">{{ $item->book->judul_buku }}</h4>
                        <p class="text-xs text-gray-400 capitalize">{{ $item->type }} • {{ $item->quantity }} {{ $item->type == 'sewa' ? 'Sem' : 'Pcs' }}</p>
                    </div>
                    
                    <div class="text-right flex-shrink-0">
                        <p class="text-blue-600 font-bold text-sm">Rp {{ number_format(($item->type == 'sewa' ? $item->book->harga_sewa : $item->book->harga_beli) * $item->quantity, 0, ',', '.') }}</p>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        <div class="h-px bg-gray-100 w-full mb-6"></div>

        <!-- Payment Method -->
        <div class="mb-6">
            <h3 class="font-bold text-gray-900 text-base mb-3">Payment Method</h3>

            <!-- Toggle Buttons -->
            <div class="flex gap-3 mb-4" id="payment-tabs">
                <button type="button" class="flex-1 py-2 rounded-xl border flex items-center justify-center gap-2 transition-all payment-tab active" data-method="Card">
                    <img src="{{ asset('images/icon-card.png') }}" class="w-5 h-4 object-contain"> Card
                </button>
                <button type="button" class="flex-1 py-2 rounded-xl border flex items-center justify-center gap-2 transition-all payment-tab" data-method="Cash">
                    <img src="{{ asset('images/icon-cash.png') }}" class="w-5 h-4 object-contain"> Cash
                </button>
                <button type="button" class="flex-1 py-2 rounded-xl border flex items-center justify-center gap-2 transition-all payment-tab" data-method="Apple Pay">
                    <img src="{{ asset('images/icon-apple.png') }}" class="w-10 h-4 object-contain">
                </button>
            </div>

            <!-- Card Info (Default View) -->
            <!-- Menambahkan ID untuk validasi JS -->
            <div id="card-info-panel" data-has-card="{{ $defaultCard ? 'true' : 'false' }}">
                @if($defaultCard)
                    <div class="border border-blue-500 bg-blue-50/30 rounded-2xl p-4 flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <img src="{{ asset('images/icon-' . strtolower($defaultCard->card_type) . '.png') }}" class="h-6 w-auto">
                            <div>
                                <span class="block text-xs text-gray-400">Default Method</span>
                                <span class="font-bold text-gray-800 text-sm tracking-widest">{{ $defaultCard->masked_number }}</span>
                            </div>
                        </div>
                        <a href="{{ route('payment.edit', $defaultCard->id) }}">
                            <img src="{{ asset('images/icon-edit-pencil.png') }}" class="w-5 h-5">
                        </a>
                    </div>
                @else
                    <a href="{{ route('payment.create') }}" class="block w-full border-2 border-dashed border-red-300 rounded-xl py-3 text-center text-red-400 text-sm font-bold hover:border-red-500 hover:text-red-600 transition-colors bg-red-50/50">
                        + Add Payment Method (Required)
                    </a>
                @endif
            </div>

            <!-- Placeholder untuk metode lain (Cash/Apple Pay) - Dianggap selalu valid jika dipilih -->
            <div id="other-payment-info" class="hidden text-center py-4 bg-gray-50 rounded-xl text-gray-500 text-sm border border-gray-200">
                Payment method selected. Proceed to order.
            </div>
        </div>

        <div class="h-px bg-gray-100 w-full mb-6"></div>

        <!-- Order Summary -->
        <div class="mb-6 space-y-2">
            <h3 class="font-bold text-gray-900 text-base mb-3">Order Summary</h3>
            <div class="flex justify-between text-sm">
                <span class="text-gray-500">Sub-total</span>
                <span class="font-bold text-gray-900">Rp {{ number_format($subtotal, 0, ',', '.') }}</span>
            </div>
            <div class="flex justify-between text-sm">
                <span class="text-gray-500">Biaya Admin</span>
                <span class="font-bold text-gray-900">Rp {{ number_format($adminFee, 0, ',', '.') }}</span>
            </div>
            <div class="flex justify-between text-sm">
                <span class="text-gray-500">Shipping fee</span>
                <span class="font-bold text-gray-900">Rp {{ number_format($shippingFee, 0, ',', '.') }}</span>
            </div>

            <!-- Promo Discount (Hidden by default) -->
            <div id="discount-row" class="flex justify-between text-sm text-green-600 hidden">
                <span>Discount</span>
                <span class="font-bold" id="discount-display">- Rp 0</span>
            </div>

            <div class="h-px bg-gray-200 w-full my-2"></div>

            <div class="flex justify-between text-lg">
                <span class="font-bold text-gray-900">Total</span>
                <span class="font-bold text-blue-600" id="total-display">Rp {{ number_format($subtotal + $adminFee + $shippingFee, 0, ',', '.') }}</span>
            </div>
        </div>

        <!-- Promo Code -->
        <div class="relative mb-6">
            <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                <img src="{{ asset('images/icon-discount.png') }}" class="w-5 h-5 opacity-50">
            </div>
            <input type="text" id="promo-input" class="block w-full p-3 pl-10 pr-20 text-sm text-gray-900 border border-gray-200 rounded-xl bg-gray-50 focus:border-blue-500 outline-none uppercase" placeholder="Enter promo code">
            <button type="button" id="apply-promo-btn" class="absolute right-2 top-1.5 bottom-1.5 bg-blue-600 text-white text-xs font-bold px-4 rounded-lg hover:bg-blue-700">
                Add
            </button>
        </div>

    </div>

    <!-- 3. Tombol Place Order (Bottom Fixed) -->
    <div class="absolute bottom-6 left-6 right-6 z-30">
        <form action="{{ route('checkout.process') }}" method="POST" id="checkout-form">
            @csrf
            <input type="hidden" name="payment_method" id="input-payment-method" value="Card">
            <input type="hidden" name="promo_code" id="input-promo-code" value="">

            <button type="submit" id="place-order-btn" class="w-full bg-gray-400 text-white font-bold text-lg py-4 rounded-full flex items-center justify-center gap-2 shadow-none cursor-not-allowed transition-all duration-300 group" disabled>
                Place Order
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="3" stroke="currentColor" class="w-5 h-5 group-hover:translate-x-1 transition-transform"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" /></svg>
            </button>
        </form>
    </div>

</div>

<style>
    .payment-tab.active { border-color: #2563EB; background-color: #EFF6FF; color: #2563EB; }
    .payment-tab:not(.active) { border-color: #E5E7EB; background-color: white; color: #6B7280; }
    .no-scrollbar::-webkit-scrollbar { display: none; }
    .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // --- Variables ---
        const tabs = document.querySelectorAll('.payment-tab');
        const cardInfo = document.getElementById('card-info-panel');
        const otherPaymentInfo = document.getElementById('other-payment-info');
        const inputMethod = document.getElementById('input-payment-method');
        const placeOrderBtn = document.getElementById('place-order-btn');
        const addressContainer = document.getElementById('selected-address-container');

        // --- Validation Function ---
        function validateCheckout() {
            const hasAddress = addressContainer.dataset.hasAddress === 'true';
            const currentMethod = inputMethod.value;
            let paymentValid = false;

            if (currentMethod === 'Card') {
                // Jika pilih Card, harus ada kartu tersimpan
                paymentValid = cardInfo.dataset.hasCard === 'true';
            } else {
                // Cash atau Apple Pay dianggap selalu valid (untuk demo ini)
                paymentValid = true;
            }

            if (hasAddress && paymentValid) {
                placeOrderBtn.disabled = false;
                placeOrderBtn.classList.remove('bg-gray-400', 'shadow-none', 'cursor-not-allowed');
                placeOrderBtn.classList.add('bg-blue-600', 'shadow-xl', 'hover:bg-blue-700', 'active:scale-95');
            } else {
                placeOrderBtn.disabled = true;
                placeOrderBtn.classList.add('bg-gray-400', 'shadow-none', 'cursor-not-allowed');
                placeOrderBtn.classList.remove('bg-blue-600', 'shadow-xl', 'hover:bg-blue-700', 'active:scale-95');
            }
        }

        // --- Tab Payment Logic ---
        tabs.forEach(tab => {
            tab.addEventListener('click', () => {
                tabs.forEach(t => t.classList.remove('active'));
                tab.classList.add('active');

                const method = tab.dataset.method;
                inputMethod.value = method;

                if (method === 'Card') {
                    cardInfo.classList.remove('hidden');
                    otherPaymentInfo.classList.add('hidden');
                } else {
                    cardInfo.classList.add('hidden');
                    otherPaymentInfo.classList.remove('hidden');
                    // Tampilkan pesan sesuai metode
                    otherPaymentInfo.innerText = method + ' selected. Proceed to order.';
                }

                // Re-validate setiap ganti metode pembayaran
                validateCheckout();
            });
        });

        // --- Promo Code Logic ---
        const promoInput = document.getElementById('promo-input');
        const applyBtn = document.getElementById('apply-promo-btn');
        const discountRow = document.getElementById('discount-row');
        const discountDisplay = document.getElementById('discount-display');
        const totalDisplay = document.getElementById('total-display');
        const inputPromoCode = document.getElementById('input-promo-code');

        const originalTotal = {{ $subtotal + $adminFee + $shippingFee }};
        const subtotal = {{ $subtotal }};

        applyBtn.addEventListener('click', () => {
            const code = promoInput.value.toUpperCase();
            let discountPercent = 0;

            if (code === 'PPPLBOOKUY') discountPercent = 0.30;
            else if (code === 'DESIGNBYOCID') discountPercent = 0.50;
            else if (code === 'BOOKUY') discountPercent = 0.10;

            if (discountPercent > 0) {
                const discount = subtotal * discountPercent;
                const newTotal = originalTotal - discount;

                discountRow.classList.remove('hidden');
                discountDisplay.innerText = '- Rp ' + new Intl.NumberFormat('id-ID').format(discount);
                totalDisplay.innerText = 'Rp ' + new Intl.NumberFormat('id-ID').format(newTotal);
                inputPromoCode.value = code;

                applyBtn.innerText = 'Applied';
                applyBtn.classList.add('bg-green-600', 'hover:bg-green-700');
                applyBtn.classList.remove('bg-blue-600', 'hover:bg-blue-700');
            } else {
                alert('Invalid Promo Code');
                discountRow.classList.add('hidden');
                totalDisplay.innerText = 'Rp ' + new Intl.NumberFormat('id-ID').format(originalTotal);
                inputPromoCode.value = '';

                applyBtn.innerText = 'Add';
                applyBtn.classList.remove('bg-green-600', 'hover:bg-green-700');
                applyBtn.classList.add('bg-blue-600', 'hover:bg-blue-700');
            }
        });

        // Initial Validation Check
        validateCheckout();
    });
</script>
@endsection