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
            <!-- Tombol Back Dinamis -->
            <button onclick="history.back()" class="absolute left-0 top-1 text-white hover:text-gray-200 transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-6 h-6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
                </svg>
            </button>
            <h1 class="font-sugo text-3xl text-white tracking-wide">Payment</h1>
        </div>
    </div>

    <!-- 2. Konten List -->
    <div class="flex-grow overflow-y-auto px-4 pt-6 pb-24 bg-white no-scrollbar relative z-0">

        <h3 class="font-bold text-gray-900 text-lg mb-4 px-2">Saved Cards</h3>

        @if($cards->count() > 0)
            <div class="space-y-4">
                @foreach($cards as $card)
                <!-- Segmen Kartu -->
                <div class="w-full border rounded-2xl p-5 relative transition-all duration-300 shadow-sm flex flex-col justify-center
                            {{ $card->is_default ? 'border-blue-500 bg-blue-50/20' : 'border-gray-200 bg-white' }}">

                    <div class="flex items-center justify-between mb-1">
                        <div class="flex items-center gap-3 flex-grow min-w-0">
                            <!-- Icon Visa/Mastercard -->
                            <img src="{{ asset('images/icon-' . strtolower($card->card_type) . '.png') }}"
                                 alt="{{ $card->card_type }}"
                                 class="h-6 w-auto object-contain flex-shrink-0">

                            <!-- Nomor Kartu -->
                            <div class="flex flex-col min-w-0">
                                <span class="font-bold text-gray-800 text-sm tracking-widest truncate">{{ $card->masked_number }}</span>
                            </div>

                            <!-- Tag Default -->
                            @if($card->is_default)
                                <span class="bg-blue-100 text-blue-600 text-[10px] font-bold px-2 py-0.5 rounded flex-shrink-0 ml-2">Default</span>
                            @endif
                        </div>

                        <!-- Radio Button (Set Default) -->
                        <a href="{{ route('payment.setDefault', $card->id) }}" class="flex-shrink-0 ml-2">
                            <img src="{{ $card->is_default ? asset('images/icon-radio-active.png') : asset('images/icon-radio-inactive.png') }}"
                                 class="w-5 h-5 cursor-pointer hover:scale-110 transition-transform">
                        </a>
                    </div>

                    <!--
                      DIEDIT: Logika Tombol Edit & Delete (Mirip Address)
                      Hanya muncul di semua item untuk konsistensi manajemen,
                      tetapi tombol delete pada item default akan disabled.
                    -->
                    <div class="flex items-center gap-4 mt-4 pl-9 animate-fade-in">
                        <!-- Tombol Edit -->
                        <a href="{{ route('payment.edit', $card->id) }}" class="text-blue-600 text-xs font-bold flex items-center gap-1 hover:underline transition-colors">
                            <img src="{{ asset('images/icon-edit-pencil.png') }}" class="w-3 h-3"> Edit
                        </a>

                        <!-- Tombol Delete -->
                        @if(!$card->is_default)
                            <form action="{{ route('payment.destroy', $card->id) }}" method="POST" onsubmit="return confirm('Hapus kartu ini?');" class="flex items-center">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-red-500 text-xs font-bold flex items-center gap-1 hover:underline transition-colors">
                                    <img src="{{ asset('images/icon-trash-red.png') }}" class="w-3 h-3"> Delete
                                </button>
                            </form>
                        @else
                            <!-- Jika default, tombol delete disabled -->
                            <span class="text-gray-400 text-xs font-bold flex items-center gap-1 cursor-not-allowed" title="Ubah default ke kartu lain untuk menghapus">
                                <img src="{{ asset('images/icon-trash-red.png') }}" class="w-3 h-3 grayscale opacity-50"> Delete
                            </span>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>
        @else
            <!-- Empty State -->
             <div class="flex flex-col items-center justify-center h-64 text-center text-gray-400">
                <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mb-3">
                    <img src="{{ asset('images/icon-credit-card.png') }}" class="w-8 h-8 opacity-50">
                </div>
                <p class="text-sm">Belum ada metode pembayaran tersimpan.</p>
            </div>
        @endif

        <!-- Tombol Add New Card (Max 5) -->
        @if($cards->count() < 5)
        <a href="{{ route('payment.create') }}" class="mt-6 w-full border-2 border-dashed border-gray-300 rounded-xl py-4 flex items-center justify-center gap-2 text-gray-500 font-bold text-sm hover:border-blue-500 hover:text-blue-500 transition-colors bg-gray-50/50">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-5 h-5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
            </svg>
            Add New Card
        </a>
        @endif

        <div class="h-20 w-full"></div>
    </div>

    <!-- 3. Tombol Apply - DINAMIS -->
    <div class="absolute bottom-6 left-6 right-6 z-30">
        <button onclick="history.back()" class="block w-full bg-blue-600 text-white font-bold text-lg py-3.5 rounded-full text-center shadow-lg hover:bg-blue-700 transition-transform active:scale-95">
            Apply
        </button>
    </div>
</div>

<style>
    .animate-fade-in { animation: fadeIn 0.3s ease-in-out; }
    @keyframes fadeIn { from { opacity: 0; transform: translateY(-5px); } to { opacity: 1; transform: translateY(0); } }
</style>
@endsection
