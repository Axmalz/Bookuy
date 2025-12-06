@extends('layouts.app')

@section('content')
<div class="w-full h-full bg-white flex flex-col relative">

    <!-- 1. Header Biru -->
    <div class="w-full bg-blue-600 pt-14 pb-5 rounded-b-[30px] shadow-md z-30 relative px-6 flex-shrink-0">
        <div class="relative flex flex-col items-center justify-center mb-2">
            <!-- Back Button Logic: Jika dari success, back ke Home. Jika dari profile, back ke profile -->
            @php
                $prev = url()->previous();
                $backLink = str_contains($prev, 'success') || str_contains($prev, 'checkout') ? route('home') : $prev;
            @endphp
            <a href="{{ $backLink }}" class="absolute left-0 top-1 text-white hover:text-gray-200 transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-6 h-6"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" /></svg>
            </a>
            <h1 class="font-sugo text-3xl text-white tracking-wide">Track Order</h1>
        </div>
    </div>

    <!-- 2. Peta -->
    <div class="relative flex-grow bg-gray-100 z-0">
        <div class="w-full h-full bg-cover bg-center" style="background-image: url('{{ asset('images/image-map-track.png') }}');"></div>
    </div>

    <!-- 3. Status Sheet (Fixed Bottom) -->
    <div class="absolute bottom-0 w-full bg-white rounded-t-[30px] shadow-[0_-5px_30px_rgba(0,0,0,0.15)] z-20 pb-8 pt-2">
        <div class="w-full flex justify-center pt-3 pb-1"><div class="w-10 h-1 bg-gray-300 rounded-full"></div></div>

        <div class="px-6 pt-2">
            <h3 class="font-bold text-gray-900 text-lg mb-4">Order Status</h3>
            <div class="h-px bg-gray-100 w-full mb-6"></div>

            <!-- Timeline -->
            <div class="relative border-l-2 border-dashed border-gray-300 ml-3 space-y-8 pb-2">
                @foreach($statuses as $index => $status)
                @php
                    $isActive = $index <= $currentStatusIndex;
                    $isCurrent = $index == $currentStatusIndex;
                @endphp
                <div class="relative pl-8">
                    <!-- Icon Radio -->
                    <div class="absolute -left-[9px] top-0 bg-white">
                        <img src="{{ $isActive ? asset('images/icon-radio-active.png') : asset('images/icon-radio-inactive.png') }}" class="w-4 h-4">
                    </div>

                    <h4 class="font-bold text-sm {{ $isActive ? 'text-blue-600' : 'text-gray-400' }}">{{ $status }}</h4>
                    @if($isCurrent && $order->courier_message)
                        <p class="text-xs text-gray-500 mt-1">{{ $order->courier_message }}</p>
                    @endif
                </div>
                @endforeach
            </div>

            <div class="h-px bg-gray-100 w-full my-6"></div>

            <!-- Courier Info -->
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-full bg-gray-200 overflow-hidden border border-gray-100">
                        <img src="{{ asset('images/profile-courier.png') }}" class="w-full h-full object-cover">
                    </div>
                    <div>
                        <h4 class="font-bold text-gray-900 text-sm">{{ $order->courier_name }}</h4>
                        <p class="text-xs text-gray-400">Kurir</p>
                    </div>
                </div>

                <a href="tel:+6281234567890" class="w-10 h-10 bg-gray-100 rounded-full flex items-center justify-center text-blue-600 hover:bg-blue-100 transition-colors">
                    <img src="{{ asset('images/icon-phone.png') }}" class="w-5 h-5">
                </a>
            </div>

        </div>
    </div>
</div>
@endsection
