@extends('layouts.app')

@section('content')
<div class="w-full h-full bg-white flex flex-col relative">

    <!-- 1. Header Biru -->
    <div class="w-full bg-blue-600 pt-14 pb-5 rounded-b-[30px] shadow-md z-30 relative px-6 flex-shrink-0">
        <div class="relative flex flex-col items-center justify-center mb-2">
            <a href="{{ route('address.index') }}" class="absolute left-0 top-1 text-white hover:text-gray-200 transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-6 h-6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
                </svg>
            </a>
            <h1 class="font-sugo text-3xl text-white tracking-wide">New Address</h1>
        </div>
    </div>

    <!-- 2. Area Peta (Interaktif) -->
    <div class="relative flex-grow overflow-hidden bg-gray-200 z-0 cursor-grab active:cursor-grabbing" id="map-wrapper">

        <!-- Gambar Peta (Draggable & Zoomable) -->
        <div id="draggable-map"
             class="absolute w-[996px] h-[590px] bg-no-repeat bg-cover shadow-xl origin-center transition-transform duration-200 ease-out"
             style="background-image: url('{{ asset('images/image-map.png') }}');">
        </div>

        <!-- Pin Kuning -->
        <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 pointer-events-none z-10">
            <img src="{{ asset('images/icon-pin-map.png') }}" class="w-12 h-12 drop-shadow-xl animate-bounce">
        </div>

        <!-- KONTROL ZOOM (BARU) -->
        <div class="absolute top-4 right-4 z-20 flex flex-col gap-2 pointer-events-auto">
            <button id="zoom-in" class="w-10 h-10 bg-white rounded-lg shadow-md flex items-center justify-center text-blue-600 font-bold text-xl hover:bg-gray-50 active:scale-95 transition-all">
                +
            </button>
            <button id="zoom-out" class="w-10 h-10 bg-white rounded-lg shadow-md flex items-center justify-center text-blue-600 font-bold text-xl hover:bg-gray-50 active:scale-95 transition-all">
                -
            </button>
        </div>

        <!-- Tombol Set Address -->
        <div class="absolute bottom-8 left-6 right-6 z-20 pointer-events-none">
            <button id="open-form-btn" class="w-full bg-yellow-500 text-white font-bold text-lg py-3.5 rounded-full shadow-xl hover:bg-yellow-600 transition-transform active:scale-95 pointer-events-auto">
                Set this Address
            </button>
        </div>
    </div>

    <!-- ... (Bagian Bottom Sheet Form & Modal Sukses TETAP SAMA seperti sebelumnya) ... -->
    <!-- Saya sertakan ulang agar file lengkap dan tidak error saat di-copy -->

    <div id="address-sheet-container" class="absolute inset-0 z-40 pointer-events-none overflow-hidden">
        <div id="sheet-overlay" class="absolute inset-0 bg-black/50 transition-opacity duration-300 opacity-0 pointer-events-auto hidden"></div>
        <div id="sheet-content" class="absolute bottom-0 left-0 w-full bg-white rounded-t-[30px] transform translate-y-full transition-transform duration-300 flex flex-col shadow-2xl pointer-events-auto">
            <div class="w-full flex justify-center pt-3 pb-1"><div class="w-10 h-1 bg-gray-300 rounded-full"></div></div>
            <div class="px-6 flex justify-between items-center mb-2">
                <h3 class="text-lg font-bold text-blue-900">Address</h3>
                <button id="close-form-btn" class="p-2 text-gray-400 hover:text-gray-800">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>
            <div class="px-6 pb-8 pt-2">
                <form id="address-form">
                    @csrf
                    <div class="mb-4">
                        <label class="block text-sm font-bold text-gray-800 mb-1">Address Nickname</label>
                        <input type="text" id="nickname" name="nickname" placeholder="Enter address nickname..." class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 text-sm focus:border-blue-500 outline-none transition-colors">
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-bold text-gray-800 mb-1">Full Address</label>
                        <textarea id="full_address" name="full_address" rows="3" placeholder="Enter your full address..." class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 text-sm focus:border-blue-500 outline-none transition-colors resize-none"></textarea>
                    </div>
                    <div class="mb-6 flex items-center gap-2">
                        <input type="checkbox" id="is_default" name="is_default" class="w-5 h-5 text-blue-600 rounded focus:ring-blue-500 border-gray-300 cursor-pointer">
                        <label for="is_default" class="text-sm text-gray-500 font-medium cursor-pointer">Make this as a default address</label>
                    </div>
                    <button type="submit" id="submit-btn" disabled class="w-full bg-gray-300 text-white font-bold text-lg py-3.5 rounded-full shadow-none cursor-not-allowed transition-all duration-300">
                        Add
                    </button>
                </form>
            </div>
        </div>
    </div>

    <div id="success-modal" class="absolute inset-0 z-50 bg-black/60 flex items-center justify-center hidden opacity-0 transition-opacity duration-300">
        <div class="bg-white rounded-[30px] w-[85%] p-6 flex flex-col items-center text-center shadow-2xl transform scale-90 transition-transform duration-300" id="success-content">
            <div class="w-24 h-24 bg-transparent rounded-full flex items-center justify-center mb-4 animate-bounce">
                <img src="{{ asset('images/icon-check-green.png') }}" class="w-24 h-24">
            </div>
            <h2 class="text-2xl font-bold text-blue-900 mb-2">Congratulations!</h2>
            <p class="text-gray-500 text-sm mb-6">Your new address has been saved.</p>
            <a href="{{ route('address.index') }}" class="w-full bg-blue-600 text-white font-bold text-lg py-3.5 rounded-full shadow-lg hover:bg-blue-700 transition-transform active:scale-95">
                Thanks
            </a>
        </div>
    </div>

</div>
@endsection

@push('scripts')
<script>
    // --- MAP LOGIC (Drag & Zoom) ---
    const mapWrapper = document.getElementById('map-wrapper');
    const map = document.getElementById('draggable-map');
    const zoomInBtn = document.getElementById('zoom-in');
    const zoomOutBtn = document.getElementById('zoom-out');

    let isDragging = false;
    let startX, startY, initialLeft, initialTop;

    // Variable Zoom
    let scale = 1;
    const scaleStep = 0.8;
    const maxScale = 10;
    const minScale = 2.5;

    function centerMap() {
        const wrapperRect = mapWrapper.getBoundingClientRect();
        const mapRect = map.getBoundingClientRect();
        const centeredLeft = (wrapperRect.width - map.offsetWidth) / 2;
        const centeredTop = (wrapperRect.height - map.offsetHeight) / 2;
        map.style.left = `${centeredLeft}px`;
        map.style.top = `${centeredTop}px`;
    }
    window.addEventListener('load', centerMap);

    // Zoom Functions
    function updateZoom() {
        map.style.transform = `scale(${scale})`;
    }

    zoomInBtn.addEventListener('click', () => {
        if (scale < maxScale) {
            scale += scaleStep;
            updateZoom();
        }
    });

    zoomOutBtn.addEventListener('click', () => {
        if (scale > minScale) {
            scale -= scaleStep;
            updateZoom();
        }
    });

    // Drag Functions
    mapWrapper.addEventListener('mousedown', startDrag);
    mapWrapper.addEventListener('touchstart', startDrag, {passive: false});
    document.addEventListener('mousemove', drag);
    document.addEventListener('touchmove', drag, {passive: false});
    document.addEventListener('mouseup', endDrag);
    document.addEventListener('touchend', endDrag);

    function startDrag(e) {
        if (e.target.closest('button')) return;
        isDragging = true;
        mapWrapper.style.cursor = 'grabbing';

        const clientX = e.type === 'touchstart' ? e.touches[0].clientX : e.clientX;
        const clientY = e.type === 'touchstart' ? e.touches[0].clientY : e.clientY;

        startX = clientX;
        startY = clientY;

        initialLeft = parseInt(map.style.left || 0);
        initialTop = parseInt(map.style.top || 0);
    }

    function drag(e) {
        if (!isDragging) return;
        e.preventDefault();

        const clientX = e.type === 'touchmove' ? e.touches[0].clientX : e.clientX;
        const clientY = e.type === 'touchmove' ? e.touches[0].clientY : e.clientY;

        const dx = clientX - startX;
        const dy = clientY - startY;

        let newLeft = initialLeft + dx;
        let newTop = initialTop + dy;

        // Batas Drag (Sederhana, tanpa memperhitungkan scale kompleks agar user tetap bebas geser)
        const wrapperRect = mapWrapper.getBoundingClientRect();
        const mapWidth = map.offsetWidth * scale; // Perhitungkan scale untuk batas
        const mapHeight = map.offsetHeight * scale;

        // Kita izinkan drag lebih bebas karena zoom bisa mengubah ukuran visual
        // Jadi boundary check kita longgarkan atau hapus sementara agar tidak 'stuck' saat di-zoom

        map.style.left = `${newLeft}px`;
        map.style.top = `${newTop}px`;
    }

    function endDrag() {
        isDragging = false;
        mapWrapper.style.cursor = 'grab';
    }

    // ... (Kode Form Logic sama seperti sebelumnya) ...
    const openBtn = document.getElementById('open-form-btn');
    const closeBtn = document.getElementById('close-form-btn');
    const overlay = document.getElementById('sheet-overlay');
    const sheet = document.getElementById('sheet-content');

    function openSheet() { overlay.classList.remove('hidden'); setTimeout(() => overlay.classList.remove('opacity-0'), 10); sheet.classList.remove('translate-y-full'); }
    function closeSheet() { sheet.classList.add('translate-y-full'); overlay.classList.add('opacity-0'); setTimeout(() => overlay.classList.add('hidden'), 300); }
    openBtn.addEventListener('click', openSheet); closeBtn.addEventListener('click', closeSheet); overlay.addEventListener('click', closeSheet);

    const nicknameInput = document.getElementById('nickname');
    const addressInput = document.getElementById('full_address');
    const submitBtn = document.getElementById('submit-btn');
    function checkInputs() {
        if (nicknameInput.value.trim() !== '' && addressInput.value.trim() !== '') {
            submitBtn.disabled = false; submitBtn.classList.remove('bg-gray-300', 'shadow-none', 'cursor-not-allowed'); submitBtn.classList.add('bg-blue-600', 'shadow-lg', 'hover:bg-blue-700', 'active:scale-95');
        } else {
            submitBtn.disabled = true; submitBtn.classList.add('bg-gray-300', 'shadow-none', 'cursor-not-allowed'); submitBtn.classList.remove('bg-blue-600', 'shadow-lg', 'hover:bg-blue-700', 'active:scale-95');
        }
    }
    nicknameInput.addEventListener('input', checkInputs); addressInput.addEventListener('input', checkInputs);

    const form = document.getElementById('address-form');
    const successModal = document.getElementById('success-modal');
    const successContent = document.getElementById('success-content');
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        const formData = new FormData(form);
        fetch('{{ route("address.store") }}', { method: 'POST', body: formData })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                closeSheet(); successModal.classList.remove('hidden');
                setTimeout(() => { successModal.classList.remove('opacity-0'); successContent.classList.remove('scale-90'); successContent.classList.add('scale-100'); }, 50);
            }
        }).catch(error => alert('Error saving address'));
    });
</script>
@endpush
