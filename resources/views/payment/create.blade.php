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
    <div class="w-full bg-blue-600 pt-14 pb-5 rounded-b-[30px] shadow-md z-30 relative px-6 flex-shrink-0">
        <div class="relative flex flex-col items-center justify-center mb-2">
            <a href="{{ route('payment.index') }}" class="absolute left-0 top-1 text-white hover:text-gray-200 transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-6 h-6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
                </svg>
            </a>
            <h1 class="font-sugo text-3xl text-white tracking-wide">New Card</h1>
        </div>
    </div>

    <!-- 2. Form Content -->
    <div class="flex-grow overflow-y-auto px-6 pt-8 pb-10 relative z-0">
        <h2 class="font-bold text-gray-900 text-lg mb-6">Add Debit or Credit Card</h2>

        <form id="card-form">
            @csrf

            <!-- Card Number -->
            <div class="mb-5">
                <label class="block text-sm font-bold text-gray-800 mb-2">Card Number</label>
                <input type="text" id="card_number" name="card_number" placeholder="Enter your card number" maxlength="16" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3.5 text-sm focus:border-blue-500 outline-none transition-colors tracking-wider">
            </div>

            <div class="flex gap-4 mb-8">
                <!-- Expiry Date -->
                <div class="flex-1">
                    <label class="block text-sm font-bold text-gray-800 mb-2">Expiry Date</label>
                    <input type="text" id="expiry_date" name="expiry_date" placeholder="MM/YY" maxlength="5" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3.5 text-sm focus:border-blue-500 outline-none transition-colors">
                </div>
                <!-- Security Code -->
                <div class="flex-1 relative">
                    <label class="block text-sm font-bold text-gray-800 mb-2">Security Code</label>
                    <input type="text" id="cvc" name="cvc" placeholder="CVC" maxlength="3" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3.5 text-sm focus:border-blue-500 outline-none transition-colors">
                    <!-- Icon Help -->
                    <img src="{{ asset('images/icon-help.png') }}" class="absolute right-3 top-[38px] w-5 h-5 opacity-50">
                </div>
            </div>

            <!-- Add Card Button -->
            <button type="submit" id="submit-btn" disabled class="w-full bg-gray-300 text-white font-bold text-lg py-3.5 rounded-full shadow-none cursor-not-allowed transition-all duration-300 mt-auto">
                Add Card
            </button>
        </form>
    </div>

    <!-- Success Modal -->
    <div id="success-modal" class="absolute inset-0 z-50 bg-black/60 flex items-center justify-center hidden opacity-0 transition-opacity duration-300">
        <div class="bg-white rounded-[30px] w-[85%] p-6 flex flex-col items-center text-center shadow-2xl transform scale-90 transition-transform duration-300" id="success-content">
            <div class="w-24 h-24 bg-blue-50 rounded-full flex items-center justify-center mb-4 animate-bounce">
                <img src="{{ asset('images/icon-check-green.png') }}" class="w-16 h-16">
            </div>
            <h2 class="text-2xl font-bold text-blue-900 mb-2">Congratulations!</h2>
            <p class="text-gray-500 text-sm mb-6">Your new card has been saved.</p>
            <a href="{{ route('payment.index') }}" class="w-full bg-blue-600 text-white font-bold text-lg py-3.5 rounded-full shadow-lg hover:bg-blue-700 transition-transform active:scale-95">
                Thanks
            </a>
        </div>
    </div>
</div>

<script>
    const form = document.getElementById('card-form');
    const inputs = [document.getElementById('card_number'), document.getElementById('expiry_date'), document.getElementById('cvc')];
    const submitBtn = document.getElementById('submit-btn');
    const successModal = document.getElementById('success-modal');
    const successContent = document.getElementById('success-content');

    // Validation Logic
    function checkInputs() {
        const allFilled = inputs.every(input => input.value.trim() !== '');
        if (allFilled) {
            submitBtn.disabled = false;
            submitBtn.classList.remove('bg-gray-300', 'cursor-not-allowed');
            submitBtn.classList.add('bg-blue-600', 'shadow-lg', 'hover:bg-blue-700', 'active:scale-95');
        } else {
            submitBtn.disabled = true;
            submitBtn.classList.add('bg-gray-300', 'cursor-not-allowed');
            submitBtn.classList.remove('bg-blue-600', 'shadow-lg', 'hover:bg-blue-700', 'active:scale-95');
        }
    }
    inputs.forEach(input => input.addEventListener('input', checkInputs));

    // Form Submit AJAX
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        const formData = new FormData(form);

        fetch('{{ route("payment.store") }}', {
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
        .catch(error => alert('Error saving card'));
    });
</script>
@endsection
