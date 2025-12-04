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
            <h1 class="font-sugo text-3xl text-white tracking-wide">Edit Address</h1>
        </div>
    </div>

    <!-- 2. Area Peta (Background) -->
    <div class="relative flex-grow overflow-hidden bg-gray-100 z-0">
        <div class="w-[150%] h-[150%] -ml-[25%] -mt-[25%] bg-cover bg-center opacity-50"
             style="background-image: url('{{ asset('images/image-map.png') }}');">
        </div>
        <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 pointer-events-none z-10 opacity-50">
            <img src="{{ asset('images/icon-pin-map.png') }}" class="w-12 h-12">
        </div>
    </div>

    <!-- 3. Bottom Sheet Form (Always Open for Edit) -->
    <div id="sheet-content" class="absolute bottom-0 left-0 w-full bg-white rounded-t-[30px] flex flex-col shadow-2xl z-40" style="max-height: 85%;">

        <div class="w-full flex justify-center pt-3 pb-1"><div class="w-10 h-1 bg-gray-300 rounded-full"></div></div>
        <div class="px-6 flex justify-between items-center mb-2">
            <h3 class="text-lg font-bold text-blue-900">Edit Address</h3>
            <a href="{{ route('address.index') }}" class="p-2 text-gray-400 hover:text-gray-800">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </a>
        </div>

        <div class="px-6 pb-8 pt-2">
            <form id="address-form">
                @csrf

                <div class="mb-4">
                    <label class="block text-sm font-bold text-gray-800 mb-1">Address Nickname</label>
                    <input type="text" id="nickname" name="nickname" value="{{ $address->nickname }}" class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 text-sm focus:border-blue-500 outline-none transition-colors">
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-bold text-gray-800 mb-1">Full Address</label>
                    <textarea id="full_address" name="full_address" rows="3" class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 text-sm focus:border-blue-500 outline-none transition-colors resize-none">{{ $address->full_address }}</textarea>
                </div>

                <div class="mb-6 flex items-center gap-2">
                    <input type="checkbox" id="is_default" name="is_default" {{ $address->is_default ? 'checked' : '' }} class="w-5 h-5 text-blue-600 rounded focus:ring-blue-500 border-gray-300 cursor-pointer">
                    <label for="is_default" class="text-sm text-gray-500 font-medium cursor-pointer">Make this as a default address</label>
                </div>

                <button type="submit" id="submit-btn" class="w-full bg-blue-600 text-white font-bold text-lg py-3.5 rounded-full shadow-lg hover:bg-blue-700 active:scale-95 transition-all">
                    Update
                </button>
            </form>
        </div>
    </div>

    <!-- Success Modal -->
    <div id="success-modal" class="absolute inset-0 z-50 bg-black/60 flex items-center justify-center hidden opacity-0 transition-opacity duration-300">
        <div class="bg-white rounded-[30px] w-[85%] p-6 flex flex-col items-center text-center shadow-2xl transform scale-90 transition-transform duration-300" id="success-content">
            <div class="w-24 h-24 bg-blue-50 rounded-full flex items-center justify-center mb-4 animate-bounce">
                <img src="{{ asset('images/icon-check-green.png') }}" class="w-16 h-16">
            </div>
            <h2 class="text-2xl font-bold text-blue-900 mb-2">Updated!</h2>
            <p class="text-gray-500 text-sm mb-6">Your address has been updated.</p>
            <a href="{{ route('address.index') }}" class="w-full bg-blue-600 text-white font-bold text-lg py-3.5 rounded-full shadow-lg hover:bg-blue-700 transition-transform active:scale-95">
                Back to List
            </a>
        </div>
    </div>
</div>

<script>
    const form = document.getElementById('address-form');
    const successModal = document.getElementById('success-modal');
    const successContent = document.getElementById('success-content');

    form.addEventListener('submit', function(e) {
        e.preventDefault();
        const formData = new FormData(form);

        fetch('{{ route("address.update", $address->id) }}', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                successModal.classList.remove('hidden');
                setTimeout(() => {
                    successModal.classList.remove('opacity-0');
                    successContent.classList.remove('scale-90');
                    successContent.classList.add('scale-100');
                }, 50);
            }
        })
        .catch(error => alert('Error updating address'));
    });
</script>
@endsection
