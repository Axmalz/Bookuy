@extends('layouts.app')

@section('content')
<div class="w-full h-full bg-blue-600 flex flex-col items-center justify-center relative overflow-hidden">

    <!-- Gambar Success (Di tengah atas) -->
    <div class="mb-10 w-64 h-64 relative z-10">
        <img src="{{ asset('images/image-success.png') }}" class="w-full h-full object-contain drop-shadow-2xl animate-bounce-slow">
    </div>

    <!-- Frame Putih Bawah (Slide Up) -->
    <div class="absolute bottom-0 w-full bg-white rounded-t-[40px] pt-10 pb-10 px-8 text-center animate-slide-up shadow-2xl z-20">

        <h2 class="font-sugo text-4xl text-blue-600 mb-2 tracking-wide">Congratulations!</h2>
        <p class="text-gray-500 text-sm mb-8 font-medium">Your order has been placed.</p>

        <!-- Icon Checklist Besar -->
        <div class="w-24 h-24 bg-blue-50 rounded-full flex items-center justify-center mx-auto mb-10 shadow-inner">
            <img src="{{ asset('images/icon-check-green.png') }}" class="w-14 h-14 drop-shadow-md">
        </div>

        <!-- Tombol Track -->
        <a href="{{ route('order.track', $orderId) }}" class="block w-full bg-blue-600 text-white font-bold text-lg py-4 rounded-full shadow-lg hover:bg-blue-700 transition-transform active:scale-95">
            Track Your Order
        </a>

    </div>
</div>

<style>
    @keyframes slide-up { from { transform: translateY(100%); } to { transform: translateY(0); } }
    .animate-slide-up { animation: slide-up 0.5s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
    .animate-bounce-slow { animation: bounce 3s infinite; }
</style>
@endsection
