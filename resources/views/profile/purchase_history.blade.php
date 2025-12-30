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
            <h1 class="font-sugo text-3xl text-white tracking-wide">Purchase History</h1>
        </div>

        <!-- Tab Navigasi (Ongoing / Completed) -->
        <div class="bg-white/20 p-1 rounded-full flex shadow-inner backdrop-blur-sm border border-white/10">
            <a href="{{ route('profile.purchase_history', ['tab' => 'ongoing']) }}"
               class="flex-1 py-2 rounded-full text-center text-sm font-bold transition-all duration-300
                      {{ $tab == 'ongoing' ? 'bg-blue-600 text-white shadow-md' : 'text-white/80 hover:bg-white/10' }}">
                Ongoing
            </a>
            <a href="{{ route('profile.purchase_history', ['tab' => 'completed']) }}"
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
                            @php
                                $imgSrc = asset('images/illustration-no-books.png');
                                if ($order->book) {
                                    // Ambil gambar pertama jika array, atau string langsung
                                    $img = is_array($order->book->gambar_buku) ? ($order->book->gambar_buku[0] ?? '') : $order->book->gambar_buku;

                                    // Cek apakah URL eksternal atau path lokal
                                    if (Illuminate\Support\Str::startsWith($img, 'http')) {
                                        $imgSrc = $img;
                                    } elseif (!empty($img)) {
                                        // REVISI: Mengambil file dari folder 'public/books' menggunakan basename
                                        $filename = basename($img);
                                        $imgSrc = asset('books/' . $filename);
                                    }
                                }
                            @endphp
                            <img src="{{ $imgSrc }}" class="w-full h-full object-cover" onerror="this.src='{{ asset('images/illustration-no-books.png') }}'">
                        </div>

                        <!-- Detail Info -->
                        <div class="flex-grow min-w-0 flex flex-col justify-center">
                            <!-- Judul -->
                            <h4 class="font-bold text-gray-800 text-sm truncate leading-tight pr-20" title="{{ $order->book->judul_buku }}">
                                {{ $order->book->judul_buku }}
                            </h4>

                            <!-- Meta Info -->
                            <div class="flex items-center text-xs text-gray-400 mt-1 mb-2">
                                <span class="capitalize">{{ $order->book->kondisi_buku }}</span>
                                <span class="mx-1.5">|</span>
                                <span class="font-medium text-gray-500">x{{ $order->quantity ?? 1 }}</span>
                            </div>

                            <!-- Harga -->
                            <div class="flex items-center justify-between">
                                <div class="text-blue-600 font-bold text-base">
                                    Rp {{ number_format($order->book->harga_beli, 0, ',', '.') }}
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Tombol Aksi -->
                    <div class="flex justify-end mt-3 border-t border-gray-50 pt-3">
                        @if($tab == 'ongoing')
                            <!-- Tombol Track Order -->
                            <a href="{{ route('order.track', $order->id) }}" class="bg-blue-600 text-white text-xs font-bold px-6 py-2 rounded-full shadow-md hover:bg-blue-700 transition-colors">
                                Track Order
                            </a>
                        @elseif($tab == 'completed')
                            <!-- Logic: Cek apakah sudah direview (rating != null) -->
                            @if($order->rating)
                                <!-- SUDAH REVIEW: Tampilkan Bintang -->
                                <div class="flex items-center gap-1.5 bg-yellow-50 px-4 py-1.5 rounded-full border border-yellow-100">
                                    <img src="{{ asset('images/icon-star-full.png') }}" class="w-4 h-4">
                                    <span class="text-yellow-600 text-xs font-bold">{{ $order->rating }}/5</span>
                                </div>
                            @else
                                <!-- BELUM REVIEW: Tampilkan Tombol -->
                                <button onclick="openReviewModal('{{ $order->book_id }}', '{{ $order->id }}', '{{ addslashes($order->book->judul_buku) }}')"
                                        class="bg-blue-600 text-white text-xs font-bold px-6 py-2 rounded-full shadow-md hover:bg-blue-700 transition-colors active:scale-95">
                                    Leave Review
                                </button>
                            @endif
                        @endif
                    </div>

                </div>
                @endforeach
            </div>
        @else
            <!-- Empty State -->
            <div class="flex flex-col items-center justify-center h-full pt-10 text-center text-gray-400">
                <img src="{{ asset('images/illustration-empty-cart.png') }}" class="w-32 opacity-50 mb-4 grayscale" alt="Empty">
                <p class="text-sm font-medium">Belum ada riwayat pembelian.</p>
                <a href="{{ route('home') }}" class="mt-4 text-blue-600 text-xs font-bold underline">Mulai Belanja</a>
            </div>
        @endif

        <!-- Spacer Bawah -->
        <div class="h-10"></div>
    </div>

    <!-- 3. MODAL LEAVE REVIEW -->
    <div id="review-modal" class="absolute inset-0 z-50 hidden">
        <!-- Overlay Gelap -->
        <div class="absolute inset-0 bg-black/60 transition-opacity duration-300 opacity-0" id="review-overlay"></div>

        <!-- Sheet Putih -->
        <div id="review-sheet" class="absolute bottom-0 left-0 w-full bg-white rounded-t-[30px] transform translate-y-full transition-transform duration-300 flex flex-col shadow-2xl pointer-events-auto pb-8 z-50">

            <!-- Handle Bar -->
            <div class="w-full flex justify-center pt-3 pb-1"><div class="w-10 h-1 bg-gray-300 rounded-full"></div></div>

            <!-- Header Modal -->
            <div class="px-6 flex justify-between items-center mb-2">
                <h3 class="text-lg font-bold text-gray-900">Leave a Review</h3>
                <button onclick="closeReviewModal()" class="p-2 text-gray-400 hover:text-gray-800 transition-colors">
                    <img src="{{ asset('images/icon-close.png') }}" class="w-6 h-6">
                </button>
            </div>

            <!-- Garis Pemisah -->
            <div class="h-px bg-gray-100 w-full mb-6"></div>

            <!-- Konten Modal -->
            <div class="px-6">
                <form action="{{ route('review.store') }}" method="POST" id="review-form">
                    @csrf
                    <!-- Hidden Inputs -->
                    <input type="hidden" name="book_id" id="review-book-id">
                    <input type="hidden" name="order_id" id="review-order-id">

                    <h4 class="font-bold text-gray-800 text-lg mb-1">How was your order?</h4>
                    <p class="text-xs text-gray-500 mb-6">Please give your rating and also your review.</p>

                    <!-- Bintang (Rating Input) -->
                    <div class="flex justify-center gap-2 mb-8" id="star-container">
                        <input type="hidden" name="rating" id="rating-input" value="0">
                        @for($i=1; $i<=5; $i++)
                            <img src="{{ asset('images/icon-star-empty.png') }}"
                                 class="w-10 h-10 cursor-pointer star-icon transition-transform hover:scale-110 active:scale-95"
                                 data-value="{{ $i }}"
                                 onclick="setRating({{ $i }})">
                        @endfor
                    </div>

                    <!-- Textarea Review -->
                    <div class="mb-6 relative">
                        <textarea name="comment" rows="4"
                                  class="w-full bg-gray-50 border border-gray-200 rounded-2xl p-4 text-sm focus:border-blue-500 focus:bg-white focus:ring-2 focus:ring-blue-100 outline-none resize-none transition-all placeholder-gray-400"
                                  placeholder="Write your review..."></textarea>
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" id="submit-review-btn" disabled
                            class="w-full bg-gray-300 text-white font-bold text-lg py-4 rounded-full shadow-none cursor-not-allowed transition-all duration-300">
                        Submit
                    </button>
                </form>
            </div>
        </div>
    </div>

</div>

<script>
    // --- Elemen DOM ---
    const reviewModal = document.getElementById('review-modal');
    const reviewOverlay = document.getElementById('review-overlay');
    const reviewSheet = document.getElementById('review-sheet');
    const ratingInput = document.getElementById('rating-input');
    const submitBtn = document.getElementById('submit-review-btn');
    const stars = document.querySelectorAll('.star-icon');

    // --- Buka Modal ---
    function openReviewModal(bookId, orderId, bookTitle) {
        document.getElementById('review-book-id').value = bookId;
        document.getElementById('review-order-id').value = orderId;

        // Reset State
        setRating(0);
        document.querySelector('textarea[name="comment"]').value = '';

        // Animasi Masuk
        reviewModal.classList.remove('hidden');
        // Force reflow
        void reviewModal.offsetWidth;

        setTimeout(() => {
            reviewOverlay.classList.remove('opacity-0');
            reviewSheet.classList.remove('translate-y-full');
        }, 10);
    }

    // --- Tutup Modal ---
    function closeReviewModal() {
        reviewSheet.classList.add('translate-y-full');
        reviewOverlay.classList.add('opacity-0');
        setTimeout(() => {
            reviewModal.classList.add('hidden');
        }, 300);
    }

    reviewOverlay.addEventListener('click', closeReviewModal);

    // --- Logika Bintang ---
    function setRating(value) {
        ratingInput.value = value;

        stars.forEach(star => {
            const starVal = parseInt(star.getAttribute('data-value'));
            if (starVal <= value) {
                star.src = "{{ asset('images/icon-star-full.png') }}";
            } else {
                star.src = "{{ asset('images/icon-star-empty.png') }}";
            }
        });

        // Validasi Tombol Submit
        if (value >= 1) {
            submitBtn.disabled = false;
            submitBtn.classList.remove('bg-gray-300', 'shadow-none', 'cursor-not-allowed');
            submitBtn.classList.add('bg-blue-600', 'shadow-lg', 'hover:bg-blue-700', 'active:scale-95');
        } else {
            submitBtn.disabled = true;
            submitBtn.classList.add('bg-gray-300', 'shadow-none', 'cursor-not-allowed');
            submitBtn.classList.remove('bg-blue-600', 'shadow-lg', 'hover:bg-blue-700', 'active:scale-95');
        }
    }
</script>

<style>
    .no-scrollbar::-webkit-scrollbar { display: none; }
    .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
</style>
@endsection