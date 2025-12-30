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
            <!-- Tombol Back Dinamis -->
            <button onclick="history.back()" class="absolute left-0 top-1 text-white hover:text-gray-200 transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-6 h-6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
                </svg>
            </button>
            <h1 class="font-sugo text-3xl text-white tracking-wide">New Card</h1>
        </div>
    </div>

    <!-- 2. Form Content -->
    <!-- Menambahkan class no-scrollbar agar konsisten -->
    <div class="flex-grow overflow-y-auto px-6 pt-8 pb-32 relative z-0 no-scrollbar">
        <h2 class="font-bold text-gray-900 text-lg mb-6">Add Debit or Credit Card</h2>

        <form id="card-form">
            @csrf

            <!-- Card Number -->
            <div class="mb-5">
                <label class="block text-sm font-bold text-gray-800 mb-2">Card Number</label>
                <input type="text" id="card_number" name="card_number" placeholder="Enter your card number" maxlength="16" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3.5 text-sm focus:border-blue-500 outline-none transition-colors tracking-wider">
                <p class="text-xs text-gray-400 mt-1 ml-1">Must be 16 digits number</p>
            </div>

            <div class="flex gap-4 mb-8">
                <!-- Expiry Date -->
                <div class="flex-1">
                    <label class="block text-sm font-bold text-gray-800 mb-2">Expiry Date</label>
                    <input type="text" id="expiry_date" name="expiry_date" placeholder="MM/YY" maxlength="5" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3.5 text-sm focus:border-blue-500 outline-none transition-colors">
                    <p class="text-xs text-gray-400 mt-1 ml-1">Format: MM/YY</p>
                </div>
                <!-- Security Code -->
                <div class="flex-1 relative">
                    <label class="block text-sm font-bold text-gray-800 mb-2">Security Code</label>
                    <input type="text" id="cvc" name="cvc" placeholder="CVC" maxlength="3" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3.5 text-sm focus:border-blue-500 outline-none transition-colors">
                    <!-- Icon Help -->
                    <img src="{{ asset('images/icon-help.png') }}" class="absolute right-3 top-[38px] w-5 h-5 opacity-50">
                    <p class="text-xs text-gray-400 mt-1 ml-1">3 digits on back</p>
                </div>
            </div>
        </form>
    </div>

    <!-- 3. Tombol Add Card (Bottom Fixed) -->
    <div class="absolute bottom-6 left-6 right-6 z-30">
        <button type="submit" form="card-form" id="submit-btn" disabled class="w-full bg-gray-300 text-white font-bold text-lg py-3.5 rounded-full shadow-none cursor-not-allowed transition-all duration-300">
            Add Card
        </button>
    </div>

    <!-- Success Modal -->
    <div id="success-modal" class="absolute inset-0 z-50 bg-black/60 flex items-center justify-center hidden opacity-0 transition-opacity duration-300">
        <div class="bg-white rounded-[30px] w-[85%] p-6 flex flex-col items-center text-center shadow-2xl transform scale-90 transition-transform duration-300" id="success-content">
            <div class="w-24 h-24 bg-blue-50 rounded-full flex items-center justify-center mb-4 animate-bounce">
                <img src="{{ asset('images/icon-check-green.png') }}" class="w-16 h-16">
            </div>
            <h2 class="text-2xl font-bold text-blue-900 mb-2">Congratulations!</h2>
            <p class="text-gray-500 text-sm mb-6">Your new card has been saved.</p>
            <!-- Tombol Thanks Dinamis -->
            <button onclick="history.back()" class="w-full bg-blue-600 text-white font-bold text-lg py-3.5 rounded-full shadow-lg hover:bg-blue-700 transition-transform active:scale-95">
                Thanks
            </button>
        </div>
    </div>
</div>

<style>
    .no-scrollbar::-webkit-scrollbar { display: none; }
    .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
</style>

<script>
    const form = document.getElementById('card-form');
    const inputs = [document.getElementById('card_number'), document.getElementById('expiry_date'), document.getElementById('cvc')];
    const submitBtn = document.getElementById('submit-btn');
    const successModal = document.getElementById('success-modal');
    const successContent = document.getElementById('success-content');

    // Validation Logic
    function checkInputs() {
        const allFilled = inputs.every(input => input.value.trim() !== '');

        // Tambahan validasi panjang karakter
        const cardNumber = document.getElementById('card_number').value.replace(/\D/g, '');
        const expiryDate = document.getElementById('expiry_date').value;
        const cvc = document.getElementById('cvc').value.replace(/\D/g, '');

        // Regex sederhana untuk MM/YY
        const expiryRegex = /^(0[1-9]|1[0-2])\/\d{2}$/;

        const isCardValid = cardNumber.length === 16;
        const isExpiryValid = expiryRegex.test(expiryDate);
        const isCvcValid = cvc.length === 3;

        if (allFilled && isCardValid && isExpiryValid && isCvcValid) {
            submitBtn.disabled = false;
            submitBtn.classList.remove('bg-gray-300', 'cursor-not-allowed');
            submitBtn.classList.add('bg-blue-600', 'shadow-lg', 'hover:bg-blue-700', 'active:scale-95');
        } else {
            submitBtn.disabled = true;
            submitBtn.classList.add('bg-gray-300', 'cursor-not-allowed');
            submitBtn.classList.remove('bg-blue-600', 'shadow-lg', 'hover:bg-blue-700', 'active:scale-95');
        }
    }

    // Format input MM/YY otomatis
    document.getElementById('expiry_date').addEventListener('input', function(e) {
        let input = e.target.value.replace(/\D/g, ''); // Hanya angka
        if (input.length > 2) {
            input = input.substring(0, 2) + '/' + input.substring(2, 4);
        }
        e.target.value = input;
        checkInputs();
    });

    // Hanya angka untuk Card Number dan CVC
    document.getElementById('card_number').addEventListener('input', function(e) {
        e.target.value = e.target.value.replace(/\D/g, '');
        checkInputs();
    });
    document.getElementById('cvc').addEventListener('input', function(e) {
        e.target.value = e.target.value.replace(/\D/g, '');
        checkInputs();
    });

    // Form Submit AJAX
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        const formData = new FormData(form);

        fetch('{{ route("payment.store") }}', {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
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