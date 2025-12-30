@extends('layouts.app')
<!--
// Kode ditulis oleh :
// Nama  : Fadhiil Akmal Hamizan
// Github: Axmalz
// NRP   : 5026231128
// Kelas : PPPL B
-->
@section('content')
<div class="w-full h-full bg-white flex flex-col relative z-0">

    <!-- 1. Header Biru -->
    <div class="w-full bg-gradient-to-b from-blue-600 to-blue-500 px-6 pt-12 pb-6 rounded-b-[30px] shadow-md z-20 relative">
        <div class="flex items-center gap-3">
            <!--
              Tombol Kembali (DIPERBAIKI)
              Sekarang menggunakan javascript:history.back() agar dinamis
              kembali ke halaman sebelumnya (Home atau Profile).
            -->
            <button onclick="history.back()" class="text-white flex-shrink-0 hover:text-gray-200 transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-6 h-6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
                </svg>
            </button>

            <!-- Kolom Search -->
            <form id="main-search-form" action="{{ route('search.index') }}" method="GET" class="flex-grow">
                <input type="hidden" name="sort" value="{{ $filters['sort'] }}">
                <input type="hidden" name="min_price" value="{{ $filters['min_price'] }}">
                <input type="hidden" name="max_price" value="{{ $filters['max_price'] }}">
                <input type="hidden" name="semester" value="{{ $filters['semester'] }}">
                <input type="hidden" name="condition" value="{{ $filters['condition'] }}">
                @if(request('category'))
                    <input type="hidden" name="category" value="{{ request('category') }}">
                @endif

                <div class="relative w-full">
                    <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                        <img src="{{ asset('images/icon-search.png') }}" class="w-5 h-5">
                    </div>
                    <input type="search" name="q" class="block w-full p-3 pl-10 text-sm text-white border-2 border-white/50 rounded-full bg-white/10 placeholder-white/70 focus:ring-white focus:border-white outline-none" placeholder="Search for Books..." value="{{ $currentQuery }}" autocomplete="off">
                    <div class="absolute inset-y-0 right-0 flex items-center pr-3 cursor-pointer">
                        <img src="{{ asset('images/icon-microphone.png') }}" class="w-5 h-5">
                    </div>
                </div>
            </form>

            <!-- Tombol Filter -->
            <button id="open-filter-btn" class="flex-shrink-0 w-12 h-12 bg-blue-800 rounded-full flex items-center justify-center shadow-lg hover:bg-blue-900 transition-colors relative">
                <img src="{{ asset('images/icon-filter.png') }}" alt="Filter" class="w-6 h-6">
                @if($filters['sort'] !== 'relevance' || $filters['min_price'] != 0 || $filters['max_price'] != 200000)
                    <div class="absolute top-0 right-0 w-3 h-3 bg-yellow-400 rounded-full border-2 border-blue-800"></div>
                @endif
            </button>
        </div>
    </div>

    <!-- 2. Konten Scrollable -->
    <div class="flex-grow overflow-y-auto px-6 pt-6 pb-10 z-10">
        @if($books !== null)
            @if($books->count() > 0)
                <div class="mb-4 flex justify-between items-end">
                    <h3 class="text-xl font-bold text-gray-900">Search Results</h3>
                    <span class="text-sm text-gray-500">{{ $books->count() }} books found</span>
                </div>
                <div class="space-y-4">
                    @foreach($books as $book)

                    @php
                        $isOutOfStock = ($book->stok_beli <= 0 && $book->stok_sewa <= 0);
                    @endphp

                    <!-- Card Buku -->
                    <div class="w-full border border-gray-200 rounded-2xl p-3 flex flex-col gap-3 shadow-sm transition-all duration-300
                                {{ $isOutOfStock ? 'bg-gray-100 opacity-75 grayscale' : 'bg-white group hover:border-blue-300 hover:shadow-md hover:-translate-y-1' }}">

                        <div class="flex gap-3">
                            <a href="{{ route('product.show', $book->id) }}" class="flex-shrink-0 w-20 h-28 overflow-hidden rounded-lg relative">
                                <div class="w-full h-full bg-gray-200">
                                    <!-- PERBAIKAN LOGIKA GAMBAR -->
                                    @php
                                        $imgSrc = asset('images/illustration-no-books.png');
                                        if (isset($book->gambar_buku[0])) {
                                            $img = $book->gambar_buku[0];
                                            if (Str::startsWith($img, 'http')) {
                                                $imgSrc = $img;
                                            } else {
                                                $filename = basename($img);
                                                $imgSrc = asset('books/' . $filename);
                                            }
                                        }
                                    @endphp
                                    <img src="{{ $imgSrc }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500" onerror="this.src='{{ asset('images/illustration-no-books.png') }}'">
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
                                        <!-- Fallback statis jika perlu: 4.8 -->
                                    </span>
                                    <span class="text-gray-300">|</span>
                                    <span class="text-gray-400 truncate">
                                        Based on {{ ($book->reviews_count > 1000) ? number_format($book->reviews_count / 1000, 1) . 'k' : ($book->reviews_count ?? 0) }} Reviews
                                    </span>
                                </div>
                                <p class="text-base font-bold text-gray-900 mt-1">Rp {{ number_format($book->harga_beli, 0, ',', '.') }}</p>
                            </div>
                        </div>

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

            <!--
              DIEDIT: Tampilan "No Books Found" Besar & Responsif
            -->
            @else
                <div class="flex flex-col items-center justify-center h-full pt-10 text-center">
                    <!-- Gambar Ilustrasi (Much Larger, responsive) -->
                    <div class="mb-1 relative w-full max-w-full">
                        <div class="mx-auto w-56 h-56 sm:w-80 sm:h-80">
                            <img src="{{ asset('images/illustration-no-books.png') }}" alt="No Books Found" class="w-full h-full object-contain drop-shadow-lg">
                        </div>
                    </div>

                    <!-- Judul -->
                    <h3 class="text-2xl font-bold text-blue-900 mb-2">No Books Found!</h3>

                    <!-- Deskripsi -->
                    <p class="text-gray-500 text-sm mb-8 max-w-[250px]">
                        Maybe try searching for another keyword?
                    </p>

                    <!-- Tombol Clear (Oranye Besar) -->
                    <a href="{{ route('search.index') }}" class="w-full max-w-[280px] py-4 bg-yellow-500 text-white font-bold text-lg rounded-full shadow-lg hover:bg-yellow-600 transition-transform hover:scale-105 active:scale-95">
                        Clear Filters & Search
                    </a>
                </div>
            @endif

        @else
            <!-- TAMPILAN DEFAULT (Kategori & Recent) -->
            <div class="grid grid-cols-2 gap-4 mb-8">
                @foreach($categories as $category)
                <a href="{{ route('search.index', ['category' => $category->name]) }}" class="block">
                    <div class="w-full h-24 bg-white border border-blue-100 rounded-2xl p-4 shadow-sm flex flex-col justify-between relative group hover:border-blue-300 transition-all">
                        <h4 class="text-sm font-bold text-gray-800 leading-tight group-hover:text-blue-600 transition-colors">{{ $category->name }}</h4>
                        <div class="absolute bottom-3 right-3 w-9 h-9 bg-blue-600 rounded-full flex items-center justify-center shadow-md shadow-blue-200 group-hover:shadow-blue-300 transition-all">
                            <img src="{{ asset($category->icon_path) }}" alt="{{ $category->name }}" class="w-5 h-5">
                        </div>
                    </div>
                </a>
                @endforeach
            </div>
            @if(count($recentSearches) > 0)
                <div class="flex justify-between items-end mb-4">
                    <h3 class="text-lg font-bold text-gray-900">Recent Searches</h3>
                    <form action="{{ route('search.clear') }}" method="POST"> @csrf <button type="submit" class="text-xs font-medium text-gray-500 underline hover:text-red-500">Clear all</button> </form>
                </div>
                <div class="space-y-0">
                    @foreach($recentSearches as $search)
                    <div class="flex justify-between items-center py-3 border-b border-gray-100 last:border-0 group hover:bg-gray-50 transition-colors px-2 rounded-lg">
                        <a href="{{ route('search.index', ['q' => $search]) }}" class="text-gray-600 text-sm flex-grow truncate pr-4 group-hover:text-blue-600 transition-colors">{{ $search }}</a>
                        <form action="{{ route('search.remove') }}" method="POST"> @csrf <input type="hidden" name="q" value="{{ $search }}"> <button type="submit" class="p-1"><img src="{{ asset('images/icon-close.png') }}" class="w-4 h-4 opacity-40 hover:opacity-100 transition-opacity"></button> </form>
                    </div>
                    @endforeach
                </div>
            @endif
        @endif
        <div class="h-10"></div>
    </div>

</div>

<!-- FILTER BOTTOM SHEET (SAMA SEPERTI SEBELUMNYA) -->
<div id="filter-modal-container" class="absolute inset-0 pointer-events-none z-50 overflow-hidden">
    <div id="filter-overlay" class="absolute inset-0 bg-black/50 transition-opacity duration-300 opacity-0 pointer-events-auto hidden"></div>
    <div id="filter-sheet" class="absolute bottom-0 left-0 w-full bg-white rounded-t-[30px] transform translate-y-full transition-transform duration-300 flex flex-col shadow-[0_-10px_40px_rgba(0,0,0,0.2)] pointer-events-auto" style="max-height: 85%; height: auto;">
        <div class="w-full flex justify-center pt-3 pb-1"><div class="w-10 h-1 bg-gray-300 rounded-full"></div></div>
        <form id="filter-form" action="{{ route('search.index') }}" method="GET" class="flex flex-col h-full">
            <input type="hidden" name="q" value="{{ $currentQuery }}">
            @if(request('category')) <input type="hidden" name="category" value="{{ request('category') }}"> @endif
            <div class="px-6 py-2 flex justify-between items-center border-b border-gray-100">
                <h3 class="text-lg font-bold text-blue-900">Filters</h3>
                <button type="button" id="close-filter-btn"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="#1e3a8a" class="w-6 h-6"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg></button>
            </div>
            <div class="px-6 py-6 overflow-y-auto space-y-6 flex-grow">
                <!-- (Konten filter sama) -->
                <div>
                    <label class="text-sm font-bold text-blue-900 mb-3 block">Sort By</label>
                    <div class="flex gap-2 overflow-x-auto pb-2" style="scrollbar-width: none;">
                        <input type="hidden" name="sort" id="sort-input" value="{{ $filters['sort'] }}">
                        <button type="button" class="sort-btn whitespace-nowrap px-4 py-2 rounded-full text-xs font-semibold border transition-colors" data-value="relevance">Relevance</button>
                        <button type="button" class="sort-btn whitespace-nowrap px-4 py-2 rounded-full text-xs font-semibold border transition-colors" data-value="price_asc">Price: Low - High</button>
                        <button type="button" class="sort-btn whitespace-nowrap px-4 py-2 rounded-full text-xs font-semibold border transition-colors" data-value="price_desc">Price: High - Low</button>
                        <button type="button" class="sort-btn whitespace-nowrap px-4 py-2 rounded-full text-xs font-semibold border transition-colors" data-value="rating_asc">Rating: Low - High</button>
                        <button type="button" class="sort-btn whitespace-nowrap px-4 py-2 rounded-full text-xs font-semibold border transition-colors" data-value="rating_desc">Rating: High - Low</button>
                    </div>
                </div>
                 <div>
                    <div class="flex justify-between items-center mb-4">
                        <label class="text-sm font-bold text-blue-900">Price</label>
                        <span class="text-xs text-gray-500 font-medium">Rp <span id="price-min-label">{{ number_format($filters['min_price'], 0, ',', '.') }}</span> - Rp <span id="price-max-label">{{ number_format($filters['max_price'], 0, ',', '.') }}</span></span>
                    </div>
                    <div class="relative w-full h-6">
                        <div class="absolute top-1/2 left-0 w-full h-1 bg-gray-200 rounded-full -translate-y-1/2 z-0"></div>
                        <div id="slider-track" class="absolute top-1/2 h-1 bg-blue-600 rounded-full -translate-y-1/2 z-10"></div>
                        <input type="range" name="min_price" id="min-price-input" min="0" max="200000" step="1000" value="{{ $filters['min_price'] }}" class="absolute w-full h-6 opacity-0 cursor-pointer z-30 pointer-events-auto">
                        <input type="range" name="max_price" id="max-price-input" min="0" max="200000" step="1000" value="{{ $filters['max_price'] }}" class="absolute w-full h-6 opacity-0 cursor-pointer z-30 pointer-events-auto">
                        <div id="min-thumb" class="absolute top-1/2 w-5 h-5 bg-white border-2 border-gray-300 rounded-full shadow -translate-y-1/2 -translate-x-1/2 z-20 pointer-events-none"></div>
                        <div id="max-thumb" class="absolute top-1/2 w-5 h-5 bg-white border-2 border-gray-300 rounded-full shadow -translate-y-1/2 -translate-x-1/2 z-20 pointer-events-none"></div>
                    </div>
                </div>
                <hr class="border-gray-100">
                <div class="flex justify-between items-center">
                    <label class="text-sm font-bold text-blue-900">Semester</label>
                    <select name="semester" class="text-sm text-gray-500 bg-transparent border-none outline-none focus:ring-0 text-right cursor-pointer">
                        @foreach(['Semua', '1', '2', '3', '4', '5', '6', '7', '8', 'Tidak ada'] as $sem)
                            <option value="{{ $sem }}" {{ $filters['semester'] == $sem ? 'selected' : '' }}>@if($sem == 'Semua' || $sem == 'Tidak ada') {{ $sem }} @else {{ $sem }} ({{ ['1'=>'Satu','2'=>'Dua','3'=>'Tiga','4'=>'Empat','5'=>'Lima','6'=>'Enam','7'=>'Tujuh','8'=>'Delapan'][$sem] ?? '' }}) @endif</option>
                        @endforeach
                    </select>
                </div>
                <div class="flex justify-between items-center">
                    <label class="text-sm font-bold text-blue-900">Kondisi Buku</label>
                    <select name="condition" class="text-sm text-gray-500 bg-transparent border-none outline-none focus:ring-0 text-right cursor-pointer">
                        @foreach(['Semua', 'Baru', 'Bekas Premium', 'Bekas Usang'] as $cond)
                            <option value="{{ $cond }}" {{ $filters['condition'] == $cond ? 'selected' : '' }}>{{ $cond }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="p-6 border-t border-gray-100">
                <button type="submit" class="w-full bg-yellow-500 text-white font-bold text-lg py-4 rounded-full shadow-lg hover:bg-yellow-600 transition-colors">Apply Filters</button>
            </div>
        </form>
    </div>
</div>

<style>
    #min-price-input { pointer-events: none; }
    #max-price-input { pointer-events: none; }
    #min-price-input::-webkit-slider-thumb { pointer-events: auto; -webkit-appearance: none; width: 20px; height: 20px; cursor: pointer; }
    #max-price-input::-webkit-slider-thumb { pointer-events: auto; -webkit-appearance: none; width: 20px; height: 20px; cursor: pointer; }
    #min-price-input::-moz-range-thumb { pointer-events: auto; width: 20px; height: 20px; cursor: pointer; }
    #max-price-input::-moz-range-thumb { pointer-events: auto; width: 20px; height: 20px; cursor: pointer; }
</style>

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // ... (Script JS sama persis seperti sebelumnya) ...
    const openBtn = document.getElementById('open-filter-btn');
    const closeBtn = document.getElementById('close-filter-btn');
    const overlay = document.getElementById('filter-overlay');
    const sheet = document.getElementById('filter-sheet');
    function openSheet() { overlay.classList.remove('hidden'); void overlay.offsetWidth; overlay.classList.remove('opacity-0'); sheet.classList.remove('translate-y-full'); }
    function closeSheet() { sheet.classList.add('translate-y-full'); overlay.classList.add('opacity-0'); setTimeout(() => { overlay.classList.add('hidden'); }, 300); }
    openBtn.addEventListener('click', openSheet); closeBtn.addEventListener('click', closeSheet); overlay.addEventListener('click', closeSheet);

    const sortInput = document.getElementById('sort-input');
    const sortBtns = document.querySelectorAll('.sort-btn');
    function updateSortUI() {
        const currentSorts = sortInput.value.split(',').filter(s => s);
        sortBtns.forEach(btn => {
            const val = btn.dataset.value;
            if (currentSorts.includes(val)) { btn.classList.add('bg-blue-600', 'text-white', 'border-blue-600'); btn.classList.remove('bg-white', 'text-gray-500', 'border-gray-200'); }
            else { btn.classList.add('bg-white', 'text-gray-500', 'border-gray-200'); btn.classList.remove('bg-blue-600', 'text-white', 'border-blue-600'); }
        });
    }
    sortBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            const val = this.dataset.value; let currentSorts = sortInput.value.split(',').filter(s => s);
            if (val === 'relevance') { currentSorts = ['relevance']; }
            else { currentSorts = currentSorts.filter(s => s !== 'relevance'); const type = val.split('_')[0]; currentSorts = currentSorts.filter(s => !s.startsWith(type)); currentSorts.push(val); }
            if (currentSorts.length === 0) currentSorts = ['relevance']; sortInput.value = currentSorts.join(','); updateSortUI();
        });
    });
    updateSortUI();

    const minInput = document.getElementById('min-price-input');
    const maxInput = document.getElementById('max-price-input');
    const minLabel = document.getElementById('price-min-label');
    const maxLabel = document.getElementById('price-max-label');
    const track = document.getElementById('slider-track');
    const minThumb = document.getElementById('min-thumb');
    const maxThumb = document.getElementById('max-thumb');
    const maxVal = 200000;
    function updateSlider() {
        let min = parseInt(minInput.value); let max = parseInt(maxInput.value);
        if (min > max - 5000) { if (this === minInput) { min = max - 5000; minInput.value = min; } else { max = min + 5000; maxInput.value = max; } }
        const percentMin = (min / maxVal) * 100; const percentMax = (max / maxVal) * 100;
        track.style.left = percentMin + '%'; track.style.width = (percentMax - percentMin) + '%';
        minThumb.style.left = percentMin + '%'; maxThumb.style.left = percentMax + '%';
        minLabel.innerText = new Intl.NumberFormat('id-ID').format(min); maxLabel.innerText = new Intl.NumberFormat('id-ID').format(max);
    }
    minInput.addEventListener('input', updateSlider); maxInput.addEventListener('input', updateSlider); updateSlider();
});
</script>
@endpush