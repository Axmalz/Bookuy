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
    <div class="w-full bg-blue-600 pt-14 pb-5 rounded-b-[30px] shadow-md z-20 relative px-6 flex-shrink-0">
        <div class="relative flex flex-col items-center justify-center mb-2">
            <!--
              LOGIKA PENCEGAHAN LOOP (ADDRESS)
              Jika user kembali dari Add/Edit address (setelah redirect sukses),
              tombol back jangan ke halaman form lagi.
            -->
            @php
                $previousUrl = url()->previous();
                $isFromForm = str_contains($previousUrl, 'address/create') || str_contains($previousUrl, 'address/edit');
                $isSamePage = $previousUrl == url()->current();

                if ($isFromForm || $isSamePage) {
                    $backAction = 'href='.route('profile.index');
                    $isButton = false;
                } else {
                    $backAction = 'onclick=history.back()';
                    $isButton = true;
                }
            @endphp

            @if($isButton)
                <button {!! $backAction !!} class="absolute left-0 top-1 text-white hover:text-gray-200 transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-6 h-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
                    </svg>
                </button>
            @else
                <a {!! $backAction !!} class="absolute left-0 top-1 text-white hover:text-gray-200 transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-6 h-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
                    </svg>
                </a>
            @endif

            <h1 class="font-sugo text-3xl text-white tracking-wide">Address</h1>
        </div>
    </div>

    <!-- 2. Konten List -->
    <div class="flex-grow overflow-y-auto px-6 pt-6 pb-24 bg-white no-scrollbar relative z-0">

        <h3 class="font-bold text-gray-900 text-lg mb-4">Saved Address</h3>

        @if($addresses->count() > 0)
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

                        <!-- Tombol Edit & Hapus -->
                        <div class="flex items-center gap-4 mt-3">
                            <a href="{{ route('address.edit', $address->id) }}" class="text-blue-600 text-xs font-bold flex items-center gap-1 hover:underline">
                                <img src="{{ asset('images/icon-edit-pencil.png') }}" class="w-3 h-3 mr-1"> Edit
                            </a>

                            @if(!$address->is_default)
                                <form action="{{ route('address.destroy', $address->id) }}" method="POST" class="inline-block">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" onclick="return confirm('Apakah Anda yakin ingin menghapus alamat ini?')" class="text-red-500 text-xs font-bold flex items-center gap-1 hover:underline cursor-pointer">
                                        <img src="{{ asset('images/icon-trash-red.png') }}" class="w-3 h-3 mr-1"> Delete
                                    </button>
                                </form>
                            @else
                                <span class="text-gray-400 text-xs font-bold cursor-not-allowed" title="Ubah default ke alamat lain untuk menghapus">
                                    Delete
                                </span>
                            @endif
                        </div>
                    </div>

                    <!-- Radio Button (Absolute Right) -->
                    <a href="{{ route('address.setDefault', $address->id) }}" class="absolute top-4 right-4">
                        <img src="{{ $address->is_default ? asset('images/icon-radio-active.png') : asset('images/icon-radio-inactive.png') }}"
                             class="w-6 h-6 cursor-pointer hover:scale-110 transition-transform">
                    </a>
                </div>
                @endforeach
            </div>
        @else
             <!-- Empty State -->
             <div class="flex flex-col items-center justify-center h-64 text-center text-gray-400">
                <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mb-3">
                    <img src="{{ asset('images/icon-map-pin.png') }}" class="w-8 h-8 opacity-50">
                </div>
                <p class="text-sm">Belum ada alamat tersimpan.</p>
            </div>
        @endif

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

    <!-- 3. Tombol Apply (Bottom Fixed) - DINAMIS -->
    <div class="absolute bottom-6 left-6 right-6 z-30">
        <!-- Juga menggunakan logika yang sama, jika loop, kembali ke profile -->
        @if($isButton)
            <button onclick="history.back()" class="block w-full bg-blue-600 text-white font-bold text-lg py-3.5 rounded-full text-center shadow-lg hover:bg-blue-700 transition-transform active:scale-95">
                Apply
            </button>
        @else
            <a href="{{ route('profile.index') }}" class="block w-full bg-blue-600 text-white font-bold text-lg py-3.5 rounded-full text-center shadow-lg hover:bg-blue-700 transition-transform active:scale-95">
                Apply
            </a>
        @endif
    </div>
</div>
@endsection
