@extends('layouts.app-main')

@section('main-content')
<div class="w-full bg-white relative">

    <!-- 1. Header Biru -->
    <div class="relative bg-blue-600 pb-10 pt-14 rounded-b-[40px] shadow-lg z-10 px-6 flex flex-col items-center justify-center">
        <!-- Logo Putih -->
        <img src="{{ asset('images/icon-bookuy-logo-white.png') }}" alt="Bookuy" class="h-16 w-auto mb-2 drop-shadow-sm">
        <!-- Judul -->
        <h1 class="font-sugo text-4xl text-white tracking-wide">Notifications</h1>
    </div>

    <!-- 2. Daftar Notifikasi -->
    <div class="px-6 pt-6 pb-32 space-y-6">

        @forelse($groups as $dateGroup => $notifications)
            <!-- Group Wrapper -->
            <div>
                <!-- Judul Waktu (Today, Yesterday, Date) -->
                <h3 class="font-bold text-gray-900 text-base mb-3">{{ $dateGroup }}</h3>

                <div class="bg-white border border-gray-100 rounded-2xl shadow-sm overflow-hidden">
                    @foreach($notifications as $index => $notif)
                        <!-- Item Notifikasi -->
                        <div class="flex items-start gap-4 p-4 {{ !$loop->last ? 'border-b border-gray-100' : '' }} hover:bg-gray-50 transition-colors cursor-pointer relative">

                            <!-- Unread Indicator (Dot Biru) -->
                            @if(!$notif->is_read)
                                <div class="absolute right-4 top-1/2 -translate-y-1/2 w-2 h-2 bg-blue-600 rounded-full"></div>
                            @endif

                            <!-- Icon Theme -->
                            <div class="w-10 h-10 bg-gray-50 rounded-full flex items-center justify-center flex-shrink-0 border border-gray-100">
                                <!-- Menggunakan nama file icon dari database, fallback ke icon-info jika tidak ada -->
                                <img src="{{ asset('images/' . ($notif->icon ?? 'icon-info.png')) }}"
                                     alt="Icon"
                                     class="w-5 h-5 object-contain"
                                     onerror="this.src='{{ asset('images/icon-info.png') }}'">
                            </div>

                            <!-- Konten Teks -->
                            <div class="flex-grow pr-4">
                                <!-- Judul (Inggris, Bersemangat) -->
                                <h4 class="font-bold text-gray-800 text-sm leading-tight mb-1">{{ $notif->title }}</h4>
                                <!-- Subjudul (Indo, Deskriptif) -->
                                <p class="text-xs text-gray-500 leading-relaxed">{{ $notif->message }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @empty
            <!-- Empty State -->
            <div class="flex flex-col items-center justify-center py-20 text-center">
                <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="gray" class="w-8 h-8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 005.454-1.31A8.967 8.967 0 0118 9.75v-.7V9A6 6 0 006 9v.75a8.967 8.967 0 01-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 01-5.714 0m5.714 0a3 3 0 11-5.714 0" />
                    </svg>
                </div>
                <p class="text-gray-400 text-sm">Belum ada notifikasi saat ini.</p>
            </div>
        @endforelse

    </div>

    <!-- Spacer Bawah -->
    <div class="h-10 w-full"></div>
</div>
@endsection
