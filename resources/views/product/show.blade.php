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

    <!--
      1. Header (Sticky & Transition)
    -->
    <div id="product-header" class="absolute top-0 left-0 w-full z-40 px-6 pt-14 pb-4 flex justify-between items-center transition-colors duration-300 pointer-events-none">

        <!-- Tombol Back Dinamis -->
        <button onclick="history.back()" class="text-white drop-shadow-md hover:text-gray-200 transition-colors pointer-events-auto">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-7 h-7">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
            </svg>
        </button>

        <!-- Logo Tengah -->
        <img src="{{ asset('images/icon-bookuy-logo-white.png') }}" alt="Bookuy" class="h-16 w-auto drop-shadow-md pointer-events-auto">

        <!-- Kanan: SELALU Tombol Keranjang -->
        <div class="pointer-events-auto">
            <a href="{{ route('cart.index') }}" class="text-white drop-shadow-md relative hover:text-gray-200 transition-colors">
                <img src="{{ asset('images/icon-cart-white.png') }}" alt="Cart" class="w-7 h-7">
                @if($cartCount > 0)
                <span class="absolute -top-1 -right-1 bg-red-500 text-white text-[10px] font-bold w-4 h-4 rounded-full flex items-center justify-center transform scale-100 transition-transform duration-200" id="cart-badge">
                    {{ $cartCount }}
                </span>
                @endif
            </a>
        </div>
    </div>

    <!--
      2. Konten Scrollable
    -->
    <div id="product-scroll-container" class="flex-grow overflow-y-auto pb-[140px] no-scrollbar bg-white relative z-0">

        <!-- Bagian Atas: Carousel -->
        <div class="relative w-full bg-blue-600 rounded-b-[50px] pb-10 pt-28 overflow-hidden shadow-xl z-10">
            <div id="carousel-container" class="relative w-full h-[380px] flex items-center justify-center perspective-1000">
                <button id="prev-btn" class="absolute left-4 z-30 w-10 h-10 bg-yellow-400 rounded-full flex items-center justify-center shadow-lg {{ count($book->gambar_buku) <= 1 ? 'hidden' : '' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="3" stroke="white" class="w-6 h-6"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5" /></svg>
                </button>

                @foreach($book->gambar_buku as $index => $img)
                <div class="carousel-item absolute transition-all duration-500 ease-out shadow-2xl rounded-lg overflow-hidden bg-gray-200" data-index="{{ $index }}">
                    <!-- LOGIKA GAMBAR DIPERBAIKI -->
                    @if(Str::startsWith($img, 'http'))
                        <img src="{{ $img }}" class="w-full h-full object-cover">
                    @else
                        <img src="{{ asset('storage/' . $img) }}" class="w-full h-full object-cover">
                    @endif
                    <div class="blur-layer absolute inset-0 bg-white/30 backdrop-blur-[2px] opacity-0 transition-opacity duration-500"></div>
                </div>
                @endforeach

                <button id="next-btn" class="absolute right-4 z-30 w-10 h-10 bg-yellow-400 rounded-full flex items-center justify-center shadow-lg {{ count($book->gambar_buku) <= 1 ? 'hidden' : '' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="3" stroke="white" class="w-6 h-6"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" /></svg>
                </button>
            </div>

            <!-- Judul & Penulis -->
            <div class="px-8 mt-6 text-left text-white">
                <h1 class="font-sugo text-3xl tracking-wide leading-tight">{{ $book->judul_buku }}</h1>
                <p class="font-poppins text-sm text-blue-100 mt-1">{{ $book->nama_penulis }}</p>
            </div>
        </div>

        <!-- Bagian Konten Putih -->
        <div class="px-6 pt-8 space-y-8">
            <!-- Deskripsi -->
            <div>
                <h3 class="font-bold text-gray-900 text-lg mb-2">Deskripsi Produk</h3>
                <p class="text-gray-500 text-sm leading-relaxed text-justify">{{ $book->deskripsi_buku }}</p>
            </div>

            <!-- Detail Info -->
            <div class="grid grid-cols-2 gap-y-4 text-sm">
                <div class="text-gray-900 font-bold">Alamat</div>
                <div class="text-gray-500 text-right">{{ $book->alamat_buku }}</div>
                <div class="text-gray-900 font-bold">Kondisi</div>
                <div class="text-gray-500 text-right capitalize">{{ $book->kondisi_buku }}</div>
                <div class="text-gray-900 font-bold">Kategori</div>
                <div class="text-gray-500 text-right">{{ $book->category->name }}</div>
                <div class="text-gray-900 font-bold">Halaman</div>
                <div class="text-gray-500 text-right">{{ $book->jumlah_halaman }}</div>

                <!-- Info Stok -->
                <div class="text-gray-900 font-bold">Stok Beli</div>
                <div class="text-gray-500 text-right font-bold {{ ($book->stok_beli ?? 0) > 0 ? 'text-green-600' : 'text-red-500' }}">
                    {{ ($book->stok_beli ?? 0) > 0 ? $book->stok_beli . ' Pcs' : 'Habis' }}
                </div>

                <div class="text-gray-900 font-bold">Stok Sewa</div>
                <div class="text-gray-500 text-right font-bold {{ ($book->stok_sewa ?? 0) > 0 ? 'text-green-600' : 'text-red-500' }}">
                    {{ ($book->stok_sewa ?? 0) > 0 ? $book->stok_sewa . ' Pcs' : 'Habis' }}
                </div>
            </div>

            <!-- Penjual -->
            <div class="bg-white border border-gray-200 rounded-2xl p-4 flex items-center gap-4 shadow-sm">
                <div class="w-12 h-12 rounded-full bg-gray-300 overflow-hidden">
                    @if($book->user->profile_photo_path)
                        <img src="{{ asset('storage/' . $book->user->profile_photo_path) }}" class="w-full h-full object-cover">
                    @else
                        <img src="https://ui-avatars.com/api/?name={{ urlencode($book->user->name) }}&background=random" class="w-full h-full object-cover">
                    @endif
                </div>
                <div>
                    <p class="text-xs text-gray-400">Penjual</p>
                    <h4 class="font-bold text-gray-900">{{ $book->user->name }}</h4>
                    @if($book->user->semester && $book->user->semester !== 'Tidak ada')
                        <p class="text-xs text-gray-500">Mahasiswa Semester {{ $book->user->semester }}</p>
                    @endif
                </div>
            </div>

            <!-- Rating -->
            <div>
                <div class="flex items-end gap-2 mb-4">
                    <span class="text-5xl font-bold text-gray-900">{{ number_format($averageRating, 1) }}</span>
                    <div class="mb-2">
                        <div class="flex gap-1">
                            @for($i=1; $i<=5; $i++)
                                <img src="{{ $i <= round($averageRating) ? asset('images/icon-star-full.png') : asset('images/icon-star-empty.png') }}" class="w-6 h-6">
                            @endfor
                        </div>
                        <p class="text-xs text-gray-400 mt-1">{{ $totalReviews }} Ratings</p>
                    </div>
                </div>
                <div class="space-y-1">
                    @foreach($ratingCounts as $star => $count)
                    <div class="flex items-center gap-3 text-xs">
                        <div class="flex w-16 justify-end gap-0.5">
                             @for($k=0; $k<$star; $k++) <img src="{{ asset('images/icon-star-full.png') }}" class="w-2.5 h-2.5"> @endfor
                             @for($k=$star; $k<5; $k++) <img src="{{ asset('images/icon-star-empty.png') }}" class="w-2.5 h-2.5"> @endfor
                        </div>
                        <div class="flex-grow h-2 bg-gray-100 rounded-full overflow-hidden">
                            <div class="h-full bg-yellow-400 rounded-full" style="width: {{ $totalReviews > 0 ? ($count / $totalReviews) * 100 : 0 }}%"></div>
                        </div>
                        <span class="w-6 text-right text-gray-400">{{ $count }}</span>
                    </div>
                    @endforeach
                </div>
            </div>

            <!-- Reviews -->
            <div>
                <form id="review-filter-form" action="{{ route('product.show', $book->id) }}" method="GET">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="font-bold text-gray-900 text-lg">{{ $totalReviews }} Reviews</h3>
                        <select name="review_sort" onchange="this.form.submit()" class="text-xs text-gray-500 bg-transparent border-none focus:ring-0 cursor-pointer text-right font-bold">
                            <option value="relevant" {{ $currentSort == 'relevant' ? 'selected' : '' }}>Lebih Relevan</option>
                            <option value="newest" {{ $currentSort == 'newest' ? 'selected' : '' }}>Terbaru</option>
                        </select>
                    </div>

                    <div class="space-y-6">
                        @foreach($reviews as $review)
                        <div class="border-b border-gray-100 pb-4 last:border-0">
                            <div class="flex gap-0.5 mb-2">
                                @for($i=1; $i<=5; $i++)
                                    <img src="{{ $i <= $review->rating ? asset('images/icon-star-full.png') : asset('images/icon-star-empty.png') }}" class="w-3 h-3">
                                @endfor
                            </div>
                            <div class="text-sm text-gray-600 mb-2">
                                @if(strlen($review->comment) > 100)
                                    <span class="short-text">{{ \Illuminate\Support\Str::limit($review->comment, 100) }}</span>
                                    <span class="full-text hidden">{{ $review->comment }}</span>
                                    <button type="button" onclick="toggleReview(this)" class="text-blue-600 text-xs underline ml-1">...see more</button>
                                @else
                                    {{ $review->comment }}
                                @endif
                            </div>
                            <div class="flex items-center text-xs text-gray-400 gap-2">
                                <span class="font-bold text-gray-800">{{ $review->user->name }}</span>
                                <span class="w-1 h-1 bg-gray-300 rounded-full"></span>
                                <span>{{ $review->time_ago }}</span>
                            </div>
                        </div>
                        @endforeach
                    </div>

                    <div class="flex justify-center mt-6">
                         <select name="review_limit" onchange="this.form.submit()" class="text-xs text-gray-500 bg-gray-50 border border-gray-200 rounded-full px-4 py-2 focus:ring-0 cursor-pointer shadow-sm">
                            <option value="5" {{ $currentLimit == '5' ? 'selected' : '' }}>Show 5</option>
                            <option value="10" {{ $currentLimit == '10' ? 'selected' : '' }}>Show 10</option>
                            <option value="25" {{ $currentLimit == '25' ? 'selected' : '' }}>Show 25</option>
                            <option value="all" {{ $currentLimit == 'all' ? 'selected' : '' }}>Show All</option>
                        </select>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- 3. Sticky Bottom Bar -->
    <div class="absolute bottom-0 left-0 w-full h-[100px] bg-blue-600 rounded-t-[30px] shadow-[0_-5px_20px_rgba(0,0,0,0.1)] z-50 px-6 flex items-center justify-between">

        <!-- TOMBOL CHAT (MODIFIED) -->
        @if(Auth::check() && Auth::id() == $book->user_id)
            <!-- Jika User adalah Penjual (Tombol Disabled) -->
            <button class="w-12 h-12 bg-blue-800/50 rounded-full flex items-center justify-center shadow-none cursor-not-allowed" title="Anda tidak bisa chat diri sendiri">
                <img src="{{ asset('images/icon-chat-blue.png') }}" class="w-6 h-6 filter brightness-0 invert opacity-50">
            </button>
        @else
            <!-- Link ke Chat Room dengan ID Penjual -->
            <a href="{{ route('chat.show', $book->user_id) }}" class="w-12 h-12 bg-blue-800 rounded-full flex items-center justify-center shadow-lg hover:bg-blue-900 transition-colors">
                <img src="{{ asset('images/icon-chat-blue.png') }}" class="w-6 h-6 filter brightness-0 invert">
            </a>
        @endif

        <!-- KONTROL TOMBOL (MODIFIED: Edit Button di Bawah untuk Penjual) -->
        @if(Auth::check() && Auth::id() == $book->user_id)
            <!-- JIKA PENJUAL: Tombol Edit Panjang (Menggantikan Sewa & Beli) -->
            <a href="{{ route('product.edit', $book->id) }}" class="flex-grow ml-4 h-12 bg-yellow-500 rounded-full flex items-center justify-center text-white shadow-lg hover:bg-yellow-600 transition-colors font-bold text-sm tracking-wide">
                Edit Product
            </a>
        @else
            <!-- JIKA PEMBELI: Tombol Sewa & Beli Normal -->
            @if(($book->stok_sewa ?? 0) > 0)
                <button onclick="openPurchaseModal('sewa')" class="flex-grow ml-4 h-12 bg-blue-400 rounded-full flex flex-col items-center justify-center text-white shadow-lg hover:bg-blue-500 transition-colors">
                    <span class="text-sm font-bold leading-none">Sewa</span>
                    <span class="text-[10px] opacity-90">Rp {{ number_format($book->harga_sewa, 0, ',', '.') }}</span>
                </button>
            @else
                <button disabled class="flex-grow ml-4 h-12 bg-gray-400/50 rounded-full flex flex-col items-center justify-center text-white/70 shadow-none cursor-not-allowed border border-white/20">
                    <span class="text-sm font-bold leading-none">Stok Habis</span>
                    <span class="text-[10px]">Sewa</span>
                </button>
            @endif

            @if(($book->stok_beli ?? 0) > 0)
                <button onclick="openPurchaseModal('beli')" class="flex-grow ml-3 h-12 bg-yellow-500 rounded-full flex flex-col items-center justify-center text-white shadow-lg hover:bg-yellow-600 transition-colors">
                    <span class="text-sm font-bold leading-none">Beli</span>
                    <span class="text-[10px] opacity-90">Rp {{ number_format($book->harga_beli, 0, ',', '.') }}</span>
                </button>
            @else
                <button disabled class="flex-grow ml-3 h-12 bg-gray-400/50 rounded-full flex flex-col items-center justify-center text-white/70 shadow-none cursor-not-allowed border border-white/20">
                    <span class="text-sm font-bold leading-none">Stok Habis</span>
                    <span class="text-[10px]">Beli</span>
                </button>
            @endif
        @endif
    </div>

    <!-- PURCHASE POPUP (Modal) -->
    @if(Auth::check() && Auth::id() != $book->user_id)
    <div id="modal-container" class="absolute inset-0 z-[60] pointer-events-none overflow-hidden">
        <div id="modal-overlay" class="absolute inset-0 bg-black/60 transition-opacity duration-300 opacity-0 pointer-events-auto hidden"></div>
        <div id="modal-sheet" class="absolute bottom-0 left-0 w-full bg-white rounded-t-[30px] transform translate-y-full transition-transform duration-300 flex flex-col pointer-events-auto shadow-2xl" style="max-height: 90%;">
            <div class="w-full flex justify-center pt-3 pb-1"><div class="w-10 h-1 bg-gray-300 rounded-full"></div></div>
            <div class="px-6 flex justify-end">
                <button onclick="closePurchaseModal()" class="p-2 text-gray-400 hover:text-gray-800">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>

            <div id="modal-content-confirm" class="px-6 pb-8 overflow-y-auto">
                <div class="flex gap-4 mb-6">
                    <img src="{{ isset($book->gambar_buku[0]) ? (Str::startsWith($book->gambar_buku[0], 'http') ? $book->gambar_buku[0] : asset('storage/' . $book->gambar_buku[0])) : '' }}" class="w-24 h-24 rounded-xl object-cover shadow-md flex-shrink-0">
                    <div class="flex-grow min-w-0">
                        <h3 class="font-bold text-lg leading-tight mb-1 truncate">{{ $book->judul_buku }}</h3>
                        <p class="text-xs text-gray-500 mb-2">{{ $book->nama_penulis }}</p>
                        <div class="flex items-center gap-2">
                            <span id="modal-price" class="text-blue-600 font-bold text-lg">Rp 0</span>
                            <span class="text-gray-300 text-xs line-through">Rp {{ number_format($book->harga_beli * 1.2, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex items-center mt-2 gap-3">
                            <button onclick="updateQuantity(-1)" class="w-6 h-6 bg-gray-100 rounded text-blue-600 font-bold hover:bg-gray-200">-</button>
                            <span id="modal-quantity" class="text-sm font-bold text-blue-900 min-w-[80px] text-center">1 Semester</span>
                            <button onclick="updateQuantity(1)" class="w-6 h-6 bg-gray-100 rounded text-blue-600 font-bold hover:bg-gray-200">+</button>
                        </div>
                    </div>
                </div>
                <hr class="border-gray-100 mb-4">
                <div class="space-y-2 text-xs text-gray-600 mb-8">
                    <div class="flex justify-between"><span>Alamat</span> <span class="text-right">{{ $book->alamat_buku }}</span></div>
                    <div class="flex justify-between"><span>Kondisi</span> <span class="text-right capitalize">{{ $book->kondisi_buku }}</span></div>
                    <div class="flex justify-between"><span>Kategori</span> <span class="text-right">{{ $book->category->name }}</span></div>
                    <div class="flex justify-between"><span>Halaman</span> <span class="text-right">{{ $book->jumlah_halaman }}</span></div>
                </div>
                <div class="flex gap-4">
                    <a href="{{ route('cart.index') }}" class="flex-1 py-3 bg-blue-800 text-white rounded-full text-center font-bold text-sm shadow-lg">Keranjang</a>
                    <button id="modal-action-btn" onclick="showSuccessState()" class="flex-1 py-3 bg-yellow-500 text-white rounded-full text-center font-bold text-sm shadow-lg">Sewa</button>
                </div>
            </div>
            <div id="modal-content-success" class="hidden px-6 pb-12 pt-4 flex-col items-center text-center h-[350px] justify-center">
                <h2 class="text-3xl font-sugo text-blue-600 mb-2">Congratulations!</h2>
                <p class="text-gray-500 text-sm mb-8">Your order has been Added to Cart.</p>
                <div class="w-24 h-24 bg-transparent rounded-full flex items-center justify-center mb-10 animate-bounce">
                    <img src="{{ asset('images/icon-check-green.png') }}" class="w-24 h-24">
                </div>
                <a href="{{ route('cart.index') }}" class="w-full py-4 bg-blue-600 text-white rounded-full font-bold shadow-lg hover:bg-blue-700 transition-colors">Go To My Cart</a>
            </div>
        </div>
    </div>
    @endif
</div>

<style>
    .perspective-1000 { perspective: 1000px; }
    .no-scrollbar::-webkit-scrollbar { display: none; }
    .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
</style>

@endsection

@push('scripts')
<script>
    const items = document.querySelectorAll('.carousel-item');
    const nextBtn = document.getElementById('next-btn');
    const prevBtn = document.getElementById('prev-btn');
    let currentIndex = 0;
    const totalItems = items.length;

    function updateCarousel() {
        items.forEach((item, index) => {
            const blurLayer = item.querySelector('.blur-layer');
            item.classList.remove('z-20', 'z-10', 'scale-100', 'scale-90', 'translate-x-0', 'translate-x-[60%]', '-translate-x-[60%]');
            item.classList.add('hidden');
            if(blurLayer) blurLayer.classList.remove('opacity-0', 'opacity-100');
            if (index === currentIndex) {
                item.classList.remove('hidden');
                item.classList.add('z-20', 'scale-100', 'translate-x-0', 'w-[200px]', 'h-[300px]', 'left-1/2', '-ml-[100px]');
                if(blurLayer) blurLayer.classList.add('opacity-0');
            } else if (index === (currentIndex + 1) % totalItems) {
                item.classList.remove('hidden');
                item.classList.add('z-10', 'scale-90', 'translate-x-[60%]', 'w-[180px]', 'h-[270px]', 'left-1/2', '-ml-[90px]');
                if(blurLayer) blurLayer.classList.add('opacity-100');
            } else if (index === (currentIndex - 1 + totalItems) % totalItems) {
                item.classList.remove('hidden');
                item.classList.add('z-10', 'scale-90', '-translate-x-[60%]', 'w-[180px]', 'h-[270px]', 'left-1/2', '-ml-[90px]');
                if(blurLayer) blurLayer.classList.add('opacity-100');
            }
        });
        if(totalItems > 1) { prevBtn.classList.remove('hidden'); nextBtn.classList.remove('hidden'); }
        else { prevBtn.classList.add('hidden'); nextBtn.classList.add('hidden'); }
    }
    items.forEach(item => item.classList.add('absolute', 'top-1/2', '-translate-y-1/2', 'rounded-xl', 'shadow-2xl'));
    updateCarousel();
    nextBtn.addEventListener('click', () => { currentIndex = (currentIndex + 1) % totalItems; updateCarousel(); });
    prevBtn.addEventListener('click', () => { currentIndex = (currentIndex - 1 + totalItems) % totalItems; updateCarousel(); });

    const scrollContainer = document.getElementById('product-scroll-container');
    const header = document.getElementById('product-header');
    scrollContainer.addEventListener('scroll', () => {
        if (scrollContainer.scrollTop > 50) {
            header.classList.remove('bg-gradient-to-b', 'from-black/30', 'to-transparent');
            header.classList.add('bg-blue-600', 'shadow-md');
        } else {
            header.classList.add('bg-gradient-to-b', 'from-black/30', 'to-transparent');
            header.classList.remove('bg-blue-600', 'shadow-md');
        }
    });
    window.toggleReview = function(btn) {
        const parent = btn.parentElement;
        parent.querySelector('.short-text').classList.toggle('hidden');
        parent.querySelector('.full-text').classList.toggle('hidden');
        btn.style.display = 'none';
    }

    // --- Modal Logic ---
    @if(Auth::check() && Auth::id() != $book->user_id)
    const overlay = document.getElementById('modal-overlay');
    const sheet = document.getElementById('modal-sheet');
    const contentConfirm = document.getElementById('modal-content-confirm');
    const contentSuccess = document.getElementById('modal-content-success');
    const hargaSewa = {{ $book->harga_sewa }};
    const hargaBeli = {{ $book->harga_beli }};
    const stockBeli = {{ $book->stok_beli ?? 0 }};
    let currentMode = 'sewa';
    let quantity = 1;

    window.openPurchaseModal = function(mode) {
        currentMode = mode; quantity = 1;
        contentConfirm.classList.remove('hidden'); contentSuccess.classList.add('hidden'); contentSuccess.classList.remove('flex');
        updateModalUI();
        overlay.classList.remove('hidden'); void overlay.offsetWidth; overlay.classList.remove('opacity-0'); sheet.classList.remove('translate-y-full');
    }
    window.closePurchaseModal = function() { sheet.classList.add('translate-y-full'); overlay.classList.add('opacity-0'); setTimeout(() => overlay.classList.add('hidden'), 300); }
    window.updateQuantity = function(change) {
        let newQty = quantity + change;
        let max = 1;
        if (currentMode === 'sewa') {
            max = 8;
        } else {
            max = stockBeli;
        }
        if (newQty >= 1 && newQty <= max) { quantity = newQty; updateModalUI(); }
    }
    function updateModalUI() {
        const priceEl = document.getElementById('modal-price');
        const qtyEl = document.getElementById('modal-quantity');
        const actionBtn = document.getElementById('modal-action-btn');
        let basePrice = (currentMode === 'sewa') ? hargaSewa : hargaBeli;
        let totalPrice = basePrice * quantity;
        priceEl.innerText = 'Rp ' + new Intl.NumberFormat('id-ID').format(totalPrice);
        qtyEl.innerText = quantity + (currentMode === 'sewa' ? ' Semester' : '');
        actionBtn.innerText = (currentMode === 'sewa') ? 'Sewa' : 'Beli';
        if(currentMode === 'sewa') { actionBtn.className = "flex-1 py-3 bg-blue-400 text-white rounded-full text-center font-bold text-sm shadow-lg"; }
        else { actionBtn.className = "flex-1 py-3 bg-yellow-500 text-white rounded-full text-center font-bold text-sm shadow-lg"; }
    }
    window.showSuccessState = function() {
        const bookId = {{ $book->id }};
        fetch('{{ route("cart.add") }}', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' },
            body: JSON.stringify({ book_id: bookId, type: currentMode, quantity: quantity })
        })
        .then(async response => {
            const data = await response.json();
            if (!response.ok) {
                if (data.message && data.message.includes('exceed stock')) {
                    alert('Books ordered exceed stock!');
                    window.location.reload();
                } else {
                    alert('Error: ' + (data.message || 'Something went wrong'));
                }
                return;
            }
            contentConfirm.classList.add('hidden');
            contentSuccess.classList.remove('hidden');
            contentSuccess.classList.add('flex');
            const badge = document.getElementById('cart-badge');
            if (badge) { let count = parseInt(badge.innerText) || 0; badge.innerText = count + 1; }
        })
        .catch(error => { console.error('Error:', error); alert('Terjadi kesalahan jaringan.'); });
    }
    overlay.addEventListener('click', closePurchaseModal);
    @endif
</script>
@endpush
