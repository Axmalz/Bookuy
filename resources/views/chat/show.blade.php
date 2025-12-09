@extends('layouts.app')

@section('content')
<div class="w-full h-full flex flex-col bg-white">
    <!-- Header -->
    <div class="bg-blue-600 pt-12 pb-5 rounded-b-[30px] shadow-lg px-6 flex items-center gap-4 z-20">
        <a href="{{ route('chat.index') }}" class="text-white"><svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg></a>
        <div class="w-10 h-10 rounded-full bg-white/20 overflow-hidden">
            <img src="{{ $partner->profile_photo_path ? asset('storage/'.$partner->profile_photo_path) : 'https://ui-avatars.com/api/?name='.urlencode($partner->name) }}" class="w-full h-full object-cover">
        </div>
        <h2 class="font-sugo text-2xl text-white tracking-wide truncate">{{ $partner->name }}</h2>
    </div>

    <!-- Messages Area -->
    <div class="flex-grow overflow-y-auto px-4 pt-6 pb-24 bg-gradient-to-b from-white via-blue-100 to-blue-600 flex flex-col" id="chat-container">
        @foreach($messages as $date => $msgs)
            <div class="flex justify-center mb-4"><span class="bg-white/80 text-blue-600 text-[10px] font-bold px-3 py-1 rounded-full shadow-sm">{{ $date }}</span></div>
            @foreach($msgs as $msg)
                @if($msg->sender_id == Auth::id())
                    <div class="flex flex-col items-end mb-4 self-end max-w-[80%]">
                        <div class="bg-yellow-400 text-white px-4 py-3 rounded-t-2xl rounded-bl-2xl shadow-md">
                            @if($msg->image_path) <img src="{{ asset('storage/'.$msg->image_path) }}" class="rounded-lg mb-2"> @endif
                            <p class="text-sm">{{ $msg->message }}</p>
                        </div>
                        <span class="text-[10px] text-white/80 mt-1 mr-1">{{ $msg->created_at->format('h:i a') }}</span>
                    </div>
                @else
                    <div class="flex flex-col items-start mb-4 self-start max-w-[80%]">
                        <div class="bg-white text-gray-800 px-4 py-3 rounded-t-2xl rounded-br-2xl shadow-md">
                            @if($msg->image_path) <img src="{{ asset('storage/'.$msg->image_path) }}" class="rounded-lg mb-2"> @endif
                            <p class="text-sm">{{ $msg->message }}</p>
                        </div>
                        <span class="text-[10px] text-gray-500 mt-1 ml-1">{{ $msg->created_at->format('h:i a') }}</span>
                    </div>
                @endif
            @endforeach
        @endforeach
        <div id="scroll-anchor"></div>
    </div>

    <!-- Input -->
    <div class="absolute bottom-6 left-0 w-full px-4 z-30">
        <form action="{{ route('chat.store') }}" method="POST" enctype="multipart/form-data" class="flex gap-3">
            @csrf
            <input type="hidden" name="receiver_id" value="{{ $partner->id }}">
            <div class="flex-grow relative">
                <input type="text" name="message" class="w-full bg-blue-500/30 backdrop-blur-md border border-white/60 rounded-full pl-5 pr-12 py-3.5 text-white placeholder-white/80 focus:outline-none" placeholder="Write your message..." autocomplete="off">
                <label for="img-up" class="absolute right-4 top-1/2 -translate-y-1/2 cursor-pointer text-white hover:scale-110 transition-transform">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                </label>
                <input type="file" name="image" id="img-up" class="hidden" onchange="this.form.submit()">
            </div>

            <!-- Microphone Placeholder (Ubah Button jadi Div) -->
            <div class="w-12 h-12 bg-yellow-400 rounded-full flex items-center justify-center shadow-lg flex-shrink-0 cursor-default">
                <img src="{{ asset('images/icon-microphone.png') }}" class="w-5 h-5">
            </div>
        </form>
    </div>
</div>
<script>document.getElementById('scroll-anchor').scrollIntoView();</script>
@endsection
