@extends('layouts.app-main')

@section('main-content')
<div class="w-full h-full bg-blue-600 flex flex-col relative overflow-hidden">

    <!-- 1. Header -->
    <div class="pt-14 pb-6 px-6 text-center flex-shrink-0 z-10">
        <h1 class="font-sugo text-4xl text-white tracking-wide">Chat</h1>
    </div>

    <!-- 2. Frame Putih (Dari Bawah) -->
    <div class="flex-grow bg-white rounded-t-[40px] flex flex-col relative z-0 overflow-hidden shadow-[0_-10px_40px_rgba(0,0,0,0.2)]">

        <!-- Toggle Switch (Penjual / Pembeli) -->
        <div class="px-6 pt-8 pb-4 flex-shrink-0">
            <div class="bg-gray-100 p-1.5 rounded-full flex relative">
                <!-- Background Slider (Opsional, pakai class active sederhana saja) -->
                <a href="{{ route('chat.index', ['tab' => 'penjual']) }}"
                   class="flex-1 py-3 rounded-full text-center text-sm font-bold transition-all duration-300 relative z-10
                          {{ $tab == 'penjual' ? 'bg-blue-600 text-white shadow-md' : 'text-gray-400 hover:text-gray-600' }}">
                    Penjual
                </a>
                <a href="{{ route('chat.index', ['tab' => 'pembeli']) }}"
                   class="flex-1 py-3 rounded-full text-center text-sm font-bold transition-all duration-300 relative z-10
                          {{ $tab == 'pembeli' ? 'bg-blue-600 text-white shadow-md' : 'text-gray-400 hover:text-gray-600' }}">
                    Pembeli
                </a>
            </div>
        </div>

        <!-- Daftar Chat -->
        <div class="flex-grow overflow-y-auto px-6 pb-32 no-scrollbar">
            @forelse($chats as $chat)
            <a href="{{ route('chat.show', $chat->user->id) }}" class="flex items-center gap-4 py-4 border-b border-gray-50 hover:bg-gray-50 transition-colors group">

                <!-- Foto Profil -->
                <div class="w-14 h-14 rounded-full bg-gray-200 overflow-hidden border border-gray-100 flex-shrink-0">
                    @if($chat->user->profile_photo_path)
                        <img src="{{ asset('storage/' . $chat->user->profile_photo_path) }}" class="w-full h-full object-cover">
                    @else
                        <img src="https://ui-avatars.com/api/?name={{ urlencode($chat->user->name) }}&background=random" class="w-full h-full object-cover">
                    @endif
                </div>

                <!-- Info Chat -->
                <div class="flex-grow min-w-0">
                    <div class="flex justify-between items-baseline mb-1">
                        <h4 class="font-bold text-gray-800 text-base truncate pr-2 max-w-[70%]">
                            {{ \Illuminate\Support\Str::limit($chat->user->name, 18, '...') }}
                        </h4>
                        <span class="text-[10px] text-gray-400 font-medium">
                            {{ $chat->time->format('h:i a') }}
                        </span>
                    </div>
                    <div class="flex justify-between items-center">
                        <p class="text-xs text-gray-500 truncate max-w-[85%] {{ $chat->unread > 0 ? 'font-bold text-gray-800' : '' }}">
                            {{ \Illuminate\Support\Str::limit($chat->last_message, 35, '...') }}
                        </p>

                        <!-- Indikator Unread -->
                        @if($chat->unread > 0)
                        <div class="w-5 h-5 bg-yellow-400 rounded-full flex items-center justify-center text-white text-[10px] font-bold shadow-sm">
                            {{ $chat->unread }}
                        </div>
                        @endif
                    </div>
                </div>
            </a>
            @empty
            <div class="flex flex-col items-center justify-center h-64 text-center text-gray-400">
                <img src="{{ asset('images/icon-chat-blue.png') }}" class="w-16 h-16 opacity-30 mb-2 grayscale">
                <p class="text-sm">Belum ada percakapan.</p>
            </div>
            @endforelse
        </div>
    </div>
</div>

<style>
    .no-scrollbar::-webkit-scrollbar { display: none; }
    .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
</style>
@endsection
