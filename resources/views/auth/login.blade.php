@extends('layouts.app')
<!--
// Kode ditulis oleh :
// Nama  : Fadhiil Akmal Hamizan
// Github: Axmalz
// NRP   : 5026231128
// Kelas : PPPL B
-->
@section('content')
<!-- CSS KHUSUS UNTUK MENGATASI AUTOFILL BACKGROUND -->
<style>
    /* Menggunakan style preferensi Anda */
    input:-webkit-autofill,
    input:-webkit-autofill:hover,
    input:-webkit-autofill:focus,
    input:-webkit-autofill:active {
        -webkit-box-shadow: 0 0 0 30px #3B72ED inset !important;
        -webkit-text-fill-color: white !important;
        transition: background-color 5000s ease-in-out 0s;
    }
</style>

<!-- Kontainer utama -->
<div class="w-full h-full bg-blue-600 flex flex-col relative p-6 pt-12 overflow-y-auto">

    <!-- 1. Tombol Kembali -->
    <div class="absolute top-12 left-6 z-20">
        <a href="{{ url('/onboarding') }}" class="text-white">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-7 h-7">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5" />
            </svg>
        </a>
    </div>

    <!-- 2. Teks Login -->
    <div class="w-full flex justify-center mb-10">
        <h1 class="font-sugo text-5xl font-bold text-white text-center">Login</h1>
    </div>

    <!-- Formulir Login -->
    <form id="login-form" action="{{ route('login.post') }}" method="POST" class="flex flex-col h-full">
        @csrf

        <!-- 3. Kolom Form -->
        <div class="space-y-5">
            <!-- Email -->
            <div class="form-group">
                <label for="email" class="text-white font-medium mb-2 block">Email</label>
                <div class="input-wrapper relative flex items-center w-full px-5 py-3.5 border-2 border-white/50 bg-white/10 rounded-full transition-all duration-300">
                    <input type="email" name="email" id="email" placeholder="Enter your email address" value="{{ old('email') }}" class="flex-1 min-w-0 bg-transparent text-white placeholder-white/70 outline-none border-none p-0">
                    <svg class="validation-icon w-6 h-6 text-yellow-400 hidden flex-shrink-0 ml-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                @error('email') <span class="text-red-300 text-sm mt-1 pl-2">{{ $message }}</span> @enderror
            </div>

            <!-- Password -->
            <div class="form-group">
                <label for="password" class="text-white font-medium mb-2 block">Password</label>
                <div class="input-wrapper relative flex items-center w-full px-5 py-3.5 border-2 border-white/50 bg-white/10 rounded-full transition-all duration-300">
                    <input type="password" name="password" id="password" placeholder="Enter your password" class="flex-1 min-w-0 bg-transparent text-white placeholder-white/70 outline-none border-none p-0">

                    <!-- Toggle Password -->
                    <button type="button" id="password-toggle" class="focus:outline-none ml-2 flex-shrink-0">
                        <!-- Default: Mata Tertutup -->
                        <img src="{{ asset('images/icon-eye-closed.png') }}" alt="Show Password" class="w-6 h-6 opacity-70 hover:opacity-100 transition-opacity">
                    </button>

                    <!-- Icon Validasi -->
                    <svg class="validation-icon w-6 h-6 text-yellow-400 hidden flex-shrink-0 ml-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <a href="#" class="text-white/80 text-sm mt-2 block text-right pr-2">Forgot your password?</a>
            </div>
        </div>

        <!-- ... (Sisa kode HTML sama: Button, Social, SignUp Link) ... -->
        <button id="login-button" type="submit" disabled class="w-full py-4 mt-5 rounded-full text-lg font-semibold transition-all duration-300 bg-gray-400 text-gray-600 cursor-not-allowed">Login</button>
        <div class="flex items-center justify-center my-6 relative z-10">
            <div class="flex-grow h-0.5 bg-white/30"></div><span class="px-4 text-white/80 font-medium">Or</span><div class="flex-grow h-0.5 bg-white/30"></div>
        </div>
        <div class="space-y-4">
            <a href="#" class="flex items-center justify-center w-full bg-white py-3.5 rounded-full text-black font-semibold text-center"><img src="{{ asset('images/logo-google.png') }}" alt="Google" class="w-6 h-6 mr-3">Login with Google</a>
            <a href="#" class="flex items-center justify-center w-full bg-white py-3.5 rounded-full text-black font-semibold text-center"><img src="{{ asset('images/logo-facebook.png') }}" alt="Facebook" class="w-6 h-6 mr-3">Login with Facebook</a>
        </div>
        <div class="flex-grow"></div>
        <p class="text-white/80 text-center text-base mt-8 mb-4">Don't have an account? <a href="{{ route('register.form') }}" class="font-bold text-white underline">SignUp</a></p>

    </form>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const emailInput = document.getElementById('email');
    const passwordInput = document.getElementById('password');
    const loginButton = document.getElementById('login-button');
    const passwordToggle = document.getElementById('password-toggle');

    // ... (Fungsi validasi sama seperti sebelumnya) ...
    function validateField(field) {
        const value = field.value.trim();
        let isValid = false;
        switch (field.name) {
            case 'email': isValid = /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(value); break;
            case 'password': isValid = value.length > 0; break;
        }
        return isValid;
    }
    function updateFieldStyle(field, isValid) {
        const wrapper = field.closest('.input-wrapper');
        const icon = wrapper.querySelector('.validation-icon');
        if (isValid) {
            wrapper.classList.add('border-yellow-400'); wrapper.classList.remove('border-white/50');
            if(icon) icon.classList.remove('hidden');
        } else {
            wrapper.classList.remove('border-yellow-400'); wrapper.classList.add('border-white/50');
            if(icon) icon.classList.add('hidden');
        }

        if(field.id === 'password') {
            const passWrapper = passwordInput.closest('.input-wrapper');
            const passIcon = passWrapper.querySelector('.validation-icon');
            if(passwordInput.value.length > 7) {
                passWrapper.classList.add('border-yellow-400'); passWrapper.classList.remove('border-white/50');
                if(passIcon) passIcon.classList.remove('hidden');
            } else {
                passWrapper.classList.remove('border-yellow-400'); passWrapper.classList.add('border-white/50');
                if(passIcon) passIcon.classList.add('hidden');
            }
        }
    }
    function checkFormValidity() {
        const isEmailValid = validateField(emailInput);
        updateFieldStyle(emailInput, isEmailValid);
        updateFieldStyle(passwordInput, false); // Manual trigger untuk visual password

        if (isEmailValid && passwordInput.value.length > 0) {
            loginButton.disabled = false;
            loginButton.classList.remove('bg-gray-400', 'text-gray-600', 'cursor-not-allowed');
            loginButton.classList.add('bg-yellow-400', 'text-blue-600');
        } else {
            loginButton.disabled = true;
            loginButton.classList.add('bg-gray-400', 'text-gray-600', 'cursor-not-allowed');
            loginButton.classList.remove('bg-yellow-400', 'text-blue-600');
        }
    }
    [emailInput, passwordInput].forEach(input => { input.addEventListener('input', checkFormValidity); });
    checkFormValidity();

    // Toggle Password
    passwordToggle.addEventListener('click', function() {
        const currentType = passwordInput.getAttribute('type');
        const newType = currentType === 'password' ? 'text' : 'password';
        const start = passwordInput.selectionStart;
        const end = passwordInput.selectionEnd;

        passwordInput.setAttribute('type', newType);
        passwordInput.setSelectionRange(start, end);

        const img = this.querySelector('img');
        if (newType === 'password') {
            img.src = "{{ asset('images/icon-eye-closed.png') }}";
            img.alt = "Show Password";
        } else {
            img.src = "{{ asset('images/icon-eye-open.png') }}";
            img.alt = "Hide Password";
        }
    });
});
</script>
@endpush
