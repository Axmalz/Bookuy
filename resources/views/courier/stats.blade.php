@extends('layouts.app')
<!--
// Kode ditulis oleh :
// Nama  : Fadhiil Akmal Hamizan
// Github: Axmalz
// NRP   : 5026231128
// Kelas : PPPL B
-->
@section('content')
<div class="min-h-screen bg-gray-50 font-sans">

    <!-- 1. HEADER -->
    <div class="bg-blue-600 pb-16 pt-12 rounded-b-[40px] shadow-lg px-6 relative">
        <div class="flex items-center gap-3 mb-6">
            <a href="{{ route('courier.index', ['name' => $selectedCourier]) }}" class="bg-white/20 p-2 rounded-xl text-white backdrop-blur-sm hover:bg-white/30 transition">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
            </a>
            <h1 class="font-sugo text-3xl text-white tracking-wide">Performance</h1>
        </div>

        <!-- Summary Card (Floating) -->
        <div class="absolute -bottom-12 left-6 right-6 bg-white rounded-2xl p-5 shadow-lg flex items-center justify-between">
            <div>
                <p class="text-gray-400 text-xs font-bold uppercase tracking-wider">Total Paket Selesai</p>
                <h2 class="text-3xl font-extrabold text-gray-800">{{ $totalDelivered }} <span class="text-sm font-normal text-gray-500">paket</span></h2>
            </div>
            <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center">
                <img src="{{ asset('images/icon-check-green.png') }}" class="w-6 h-6">
            </div>
        </div>
    </div>

    <!-- 2. SPACER untuk Floating Card -->
    <div class="h-16"></div>

    <!-- 3. STATS GRID -->
    <div class="px-6 py-6">
        <h3 class="font-bold text-gray-800 mb-4 text-lg">Status Terkini</h3>

        <div class="grid grid-cols-2 gap-4">
            <!-- Packing -->
            <div class="bg-white p-5 rounded-2xl shadow-sm border border-gray-100 flex flex-col items-center text-center">
                <div class="w-10 h-10 bg-yellow-50 rounded-full flex items-center justify-center mb-3">
                    <span class="text-2xl">📦</span>
                </div>
                <h4 class="text-2xl font-bold text-gray-800">{{ $data['Packing'] }}</h4>
                <p class="text-xs text-gray-500 font-medium">Sedang Packing</p>
            </div>

            <!-- Picked -->
            <div class="bg-white p-5 rounded-2xl shadow-sm border border-gray-100 flex flex-col items-center text-center">
                <div class="w-10 h-10 bg-purple-50 rounded-full flex items-center justify-center mb-3">
                    <span class="text-2xl">🛵</span>
                </div>
                <h4 class="text-2xl font-bold text-gray-800">{{ $data['Picked'] }}</h4>
                <p class="text-xs text-gray-500 font-medium">Sudah Picked Up</p>
            </div>

            <!-- In Transit -->
            <div class="bg-white p-5 rounded-2xl shadow-sm border border-gray-100 flex flex-col items-center text-center col-span-2 relative overflow-hidden">
                <div class="absolute right-4 top-4 opacity-10">
                    <img src="{{ asset('images/icon-map-pin.png') }}" class="w-24 h-24">
                </div>
                <div class="flex items-center gap-4 w-full z-10">
                    <div class="w-12 h-12 bg-blue-50 rounded-full flex items-center justify-center flex-shrink-0">
                         <span class="text-2xl">🚚</span>
                    </div>
                    <div class="text-left">
                        <h4 class="text-3xl font-bold text-blue-600">{{ $data['In Transit'] }}</h4>
                        <p class="text-sm text-gray-500 font-medium">Sedang dalam perjalanan</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- 4. PROGRESS BAR VISUALIZATION -->
        <div class="mt-8">
            <h3 class="font-bold text-gray-800 mb-4 text-lg">Rasio Penyelesaian</h3>
            <div class="bg-white p-5 rounded-2xl shadow-sm border border-gray-100">
                @php
                    $total = $totalHeld + $totalDelivered;
                    $percent = $total > 0 ? ($totalDelivered / $total) * 100 : 0;
                @endphp

                <div class="flex justify-between text-sm mb-2">
                    <span class="text-gray-500">Progress ({{ round($percent) }}%)</span>
                    <span class="font-bold text-blue-600">{{ $totalDelivered }}/{{ $total }}</span>
                </div>

                <div class="w-full bg-gray-100 rounded-full h-3 overflow-hidden">
                    <div class="bg-blue-600 h-3 rounded-full transition-all duration-1000" style="width: {{ $percent }}%"></div>
                </div>

                <p class="text-xs text-gray-400 mt-3 leading-relaxed">
                    *Statistik dihitung berdasarkan total pesanan yang ditugaskan kepada <strong>{{ $selectedCourier }}</strong>.
                </p>
            </div>
        </div>
    </div>
</div>
@endsection
