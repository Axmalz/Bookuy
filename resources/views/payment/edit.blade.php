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

    <div class="w-full bg-blue-600 pt-14 pb-5 rounded-b-[30px] shadow-md z-30 relative px-6 flex-shrink-0">
        <div class="relative flex flex-col items-center justify-center mb-2">
            <!-- Tombol Back Dinamis -->
            <button onclick="history.back()" class="absolute left-0 top-1 text-white hover:text-gray-200 transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-6 h-6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
                </svg>
            </button>
            <h1 class="font-sugo text-3xl text-white tracking-wide">Edit Card</h1>
        </div>
    </div>

    <div class="flex-grow overflow-y-auto px-6 pt-8 pb-10 relative z-0">
        <h2 class="font-bold text-gray-900 text-lg mb-6">Edit Debit or Credit Card</h2>

        <form id="card-form">
            @csrf
            <div class="mb-5">
                <label class="block text-sm font-bold text-gray-800 mb-2">Card Number</label>
                <input type="text" id="card_number" name="card_number" value="{{ $card->card_number }}" maxlength="16" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3.5 text-sm focus:border-blue-500 outline-none transition-colors tracking-wider">
            </div>

            <div class="flex gap-4 mb-8">
                <div class="flex-1">
                    <label class="block text-sm font-bold text-gray-800 mb-2">Expiry Date</label>
                    <input type="text" id="expiry_date" name="expiry_date" value="{{ $card->expiry_date }}" maxlength="5" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3.5 text-sm focus:border-blue-500 outline-none transition-colors">
                </div>
                <div class="flex-1 relative">
                    <label class="block text-sm font-bold text-gray-800 mb-2">Security Code</label>
                    <input type="text" id="cvc" name="cvc" value="{{ $card->cvc }}" maxlength="3" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3.5 text-sm focus:border-blue-500 outline-none transition-colors">
                    <img src="{{ asset('images/icon-help.png') }}" class="absolute right-3 top-[38px] w-5 h-5 opacity-50">
                </div>
            </div>

            <button type="submit" id="submit-btn" class="w-full bg-blue-600 text-white font-bold text-lg py-3.5 rounded-full shadow-lg hover:bg-blue-700 transition-all duration-300 mt-auto active:scale-95">
                Update Card
            </button>
        </form>
    </div>

    <div id="success-modal" class="absolute inset-0 z-50 bg-black/60 flex items-center justify-center hidden opacity-0 transition-opacity duration-300">
        <div class="bg-white rounded-[30px] w-[85%] p-6 flex flex-col items-center text-center shadow-2xl transform scale-90 transition-transform duration-300" id="success-content">
            <div class="w-24 h-24 bg-blue-50 rounded-full flex items-center justify-center mb-4 animate-bounce">
                <img src="{{ asset('images/icon-check-green.png') }}" class="w-16 h-16">
            </div>
            <h2 class="text-2xl font-bold text-blue-900 mb-2">Updated!</h2>
            <p class="text-gray-500 text-sm mb-6">Your card has been updated.</p>
            <!-- Tombol Back Dinamis -->
            <button onclick="history.back()" class="w-full bg-blue-600 text-white font-bold text-lg py-3.5 rounded-full shadow-lg hover:bg-blue-700 transition-transform active:scale-95">
                Back
            </button>
        </div>
    </div>
</div>

<script>
    const form = document.getElementById('card-form');
    const successModal = document.getElementById('success-modal');
    const successContent = document.getElementById('success-content');

    form.addEventListener('submit', function(e) {
        e.preventDefault();
        const formData = new FormData(form);

        fetch('{{ route("payment.update", $card->id) }}', {
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
        .catch(error => alert('Error updating card'));
    });
</script>
@endsection
