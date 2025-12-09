@extends('layouts.app-main')

@section('main-content')
<div class="w-full bg-white relative pb-20">
    <div class="bg-blue-600 pt-14 pb-8 px-6 rounded-b-[40px] shadow-lg mb-6">
        <h1 class="font-sugo text-3xl text-white tracking-wide text-center">Broadcast Notifikasi</h1>
    </div>

    <div class="px-6">
        <form action="{{ route('notification.storeGlobal') }}" method="POST" class="space-y-4">
            @csrf

            <div>
                <label class="block text-sm font-bold text-gray-700 mb-1">Judul (Inggris/Singkat)</label>
                <input type="text" name="title" class="w-full border border-gray-300 rounded-xl p-3 focus:outline-none focus:border-blue-500" placeholder="Ex: System Maintenance" required>
            </div>

            <div>
                <label class="block text-sm font-bold text-gray-700 mb-1">Pesan (Detail)</label>
                <textarea name="message" rows="3" class="w-full border border-gray-300 rounded-xl p-3 focus:outline-none focus:border-blue-500" placeholder="Ex: Sistem akan maintenance pada jam 12 malam..." required></textarea>
            </div>

            <div>
                <label class="block text-sm font-bold text-gray-700 mb-1">Tipe Notifikasi</label>
                <select name="type" class="w-full border border-gray-300 rounded-xl p-3 bg-white">
                    <option value="system">System (Info Sistem)</option>
                    <option value="promo">Promo (Diskon/Event)</option>
                    <option value="info">Info (Umum)</option>
                </select>
            </div>

            <div>
                <label class="block text-sm font-bold text-gray-700 mb-2">Pilih Icon</label>
                <div class="grid grid-cols-4 gap-3">
                    <label class="cursor-pointer">
                        <input type="radio" name="icon" value="icon-info.png" class="peer hidden" checked>
                        <div class="border border-gray-200 rounded-lg p-2 flex items-center justify-center peer-checked:border-blue-600 peer-checked:bg-blue-50">
                            <img src="{{ asset('images/icon-info.png') }}" class="w-8 h-8">
                        </div>
                    </label>
                    <label class="cursor-pointer">
                        <input type="radio" name="icon" value="icon-discount.png" class="peer hidden">
                        <div class="border border-gray-200 rounded-lg p-2 flex items-center justify-center peer-checked:border-blue-600 peer-checked:bg-blue-50">
                            <img src="{{ asset('images/icon-discount.png') }}" class="w-8 h-8">
                        </div>
                    </label>
                    <label class="cursor-pointer">
                        <input type="radio" name="icon" value="icon-map-pin.png" class="peer hidden">
                        <div class="border border-gray-200 rounded-lg p-2 flex items-center justify-center peer-checked:border-blue-600 peer-checked:bg-blue-50">
                            <img src="{{ asset('images/icon-map-pin.png') }}" class="w-8 h-8">
                        </div>
                    </label>
                     <label class="cursor-pointer">
                        <input type="radio" name="icon" value="icon-check-blue.png" class="peer hidden">
                        <div class="border border-gray-200 rounded-lg p-2 flex items-center justify-center peer-checked:border-blue-600 peer-checked:bg-blue-50">
                            <img src="{{ asset('images/icon-check-blue.png') }}" class="w-8 h-8">
                        </div>
                    </label>
                </div>
            </div>

            <button type="submit" class="w-full bg-blue-600 text-white font-bold py-3 rounded-xl shadow-lg mt-6 hover:bg-blue-700 transition">
                Kirim ke Semua User
            </button>
        </form>
    </div>
</div>
@endsection
