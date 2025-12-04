@extends('layouts.app')

@section('content')
<div class="w-full h-full bg-white flex flex-col relative">

    <!-- 1. Header Biru -->
    <div class="w-full bg-blue-600 pt-14 pb-5 rounded-b-[30px] shadow-md z-20 relative px-6 flex-shrink-0">
        <div class="relative flex flex-col items-center justify-center mb-2">
            <!-- Tombol Back (Ke Profile) -->
            <a href="{{ route('profile.index') }}" class="absolute left-0 top-1 text-white hover:text-gray-200 transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-6 h-6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
                </svg>
            </a>
            <h1 class="font-sugo text-3xl text-white tracking-wide">Address</h1>
        </div>
    </div>

    <!-- 2. Konten List -->
    <div class="flex-grow overflow-y-auto px-6 pt-6 pb-24 bg-white no-scrollbar relative z-0">

        <h3 class="font-bold text-gray-900 text-lg mb-4">Saved Address</h3>

        <div class="space-y-4">
            @foreach($addresses as $address)
            <!-- Segmen Alamat -->
            <div class="border rounded-2xl p-4 flex items-start gap-3 relative transition-all duration-300
                        {{ $address->is_default ? 'border-blue-500 bg-blue-50/20' : 'border-gray-200 bg-white' }}">

                <!-- Icon Lokasi -->
                <div class="w-10 h-10 bg-gray-100 rounded-full flex items-center justify-center flex-shrink-0">
                    <img src="{{ asset('images/icon-location-pin.png') }}" class="w-5 h-5">
                </div>

                <!-- Detail -->
                <div class="flex-grow min-w-0 pr-8"> <!-- pr-8 memberi ruang untuk radio button -->
                    <div class="flex items-center gap-2 mb-1">
                        <h4 class="font-bold text-gray-900 text-sm truncate">{{ $address->nickname }}</h4>
                        @if($address->is_default)
                            <span class="bg-blue-100 text-blue-600 text-[10px] font-bold px-2 py-0.5 rounded-md">Default</span>
                        @endif
                    </div>
                    <p class="text-xs text-gray-500 leading-relaxed line-clamp-2">
                        {{ $address->full_address }}
                    </p>

                    <!-- Tombol Edit & Hapus (Hanya muncul jika alamat ini Default/Aktif) -->
                    @if($address->is_default)
                    <div class="flex items-center gap-3 mt-3">
                        <a href="{{ route('address.edit', $address->id) }}" class="text-blue-600 text-xs font-bold flex items-center gap-1 hover:underline">
                            Edit
                        </a>
                        <form action="{{ route('address.destroy', $address->id) }}" method="POST" onsubmit="return confirm('Hapus alamat ini?');">
                            @csrf @method('DELETE')
                            <button type="submit" class="text-red-500 text-xs font-bold flex items-center gap-1 hover:underline">
                                Delete
                            </button>
                        </form>
                    </div>
                    @endif
                </div>

                <!-- Radio Button (Absolute Right) -->
                <!-- Klik radio untuk set default -->
                <a href="{{ route('address.setDefault', $address->id) }}" class="absolute top-4 right-4">
                    <img src="{{ $address->is_default ? asset('images/icon-radio-active.png') : asset('images/icon-radio-inactive.png') }}"
                         class="w-6 h-6 cursor-pointer hover:scale-110 transition-transform">
                </a>
            </div>
            @endforeach
        </div>

        <!-- Tombol Add New Address -->
        @if($addresses->count() < 5)
        <a href="{{ route('address.create') }}" class="mt-6 w-full border-2 border-dashed border-gray-300 rounded-xl py-3 flex items-center justify-center gap-2 text-gray-500 font-bold text-sm hover:border-blue-500 hover:text-blue-500 transition-colors">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-5 h-5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
            </svg>
            Add New Address
        </a>
        @endif

        <!-- Spacer Bawah -->
        <div class="h-20 w-full"></div>
    </div>

    <!-- 3. Tombol Apply (Bottom Fixed) -->
    <div class="absolute bottom-6 left-6 right-6 z-30">
        <a href="{{ route('profile.index') }}" class="block w-full bg-blue-600 text-white font-bold text-lg py-3.5 rounded-full text-center shadow-lg hover:bg-blue-700 transition-transform active:scale-95">
            Apply
        </a>
    </div>
</div>
@endsection
