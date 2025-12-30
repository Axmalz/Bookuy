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
    <div class="w-full bg-blue-600 pt-12 pb-6 rounded-b-[40px] shadow-md z-20 relative px-6">
        <div class="flex items-center justify-center relative mb-2">

            <!-- Tombol Back (SVG) -->
            <a href="{{ route('profile.index') }}" class="absolute left-0 text-white hover:text-gray-200 transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-6 h-6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
                </svg>
            </a>

            <!-- Logo Kecil (PNG) -->
            <img src="{{ asset('images/icon-bookuy-logo-white.png') }}" alt="Bookuy" class="h-16 w-auto drop-shadow-sm">
        </div>
        <!-- Judul -->
        <h1 class="font-sugo text-4xl text-center text-white tracking-wide">Edit Profile</h1>
    </div>

    <!-- 2. Konten Form -->
    <div class="flex-grow overflow-y-auto px-6 pt-8 pb-10">

        <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf

            <!-- Foto Profil & Upload -->
            <div class="flex flex-col items-center">
                <div class="w-28 h-28 rounded-full border-4 border-blue-50 overflow-hidden bg-gray-200 shadow-lg relative group">
                    <!-- Preview Image -->
                    <!-- PERBAIKAN: Menambahkan ?v=time() agar gambar langsung terupdate tanpa cache -->
                    <img id="profile-preview"
                         src="{{ $user->profile_photo_path ? asset('storage/' . $user->profile_photo_path) . '?v=' . time() : 'https://ui-avatars.com/api/?name=' . urlencode($user->name) . '&background=random&size=128' }}"
                         class="w-full h-full object-cover">

                    <!-- Overlay Upload saat Hover -->
                    <label for="profile_photo" class="absolute inset-0 bg-black/30 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity cursor-pointer">
                        <img src="{{ asset('images/icon-camera-white.png') }}" alt="Change" class="w-8 h-8">
                    </label>
                </div>
                <label for="profile_photo" class="text-xs text-gray-500 mt-2 cursor-pointer hover:text-blue-600">Change Picture</label>
                <input type="file" name="profile_photo" id="profile_photo" class="hidden" accept="image/*" onchange="previewImage(event)">
                @error('profile_photo') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
            </div>

            <!-- Input Name -->
            <div>
                <label class="font-bold text-sm text-gray-800 mb-1 block">Name</label>
                <div class="border-2 border-gray-200 rounded-full px-4 py-3 focus-within:border-blue-500 transition-colors">
                    <input type="text" name="name" value="{{ old('name', $user->name) }}" placeholder="Kenalan dong!" class="w-full bg-transparent outline-none text-gray-700 placeholder-gray-400 text-sm font-semibold">
                </div>
                @error('name') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
            </div>

            <!-- Dropdown Gender -->
            <div>
                <label class="font-bold text-sm text-gray-800 mb-1 block">Gender</label>
                <div class="border-2 border-gray-200 rounded-full px-4 py-3 focus-within:border-blue-500 transition-colors relative">
                    <select name="gender" class="w-full bg-transparent outline-none text-gray-700 text-sm font-semibold appearance-none cursor-pointer z-10 relative">
                        <option value="" disabled {{ is_null($user->gender) ? 'selected' : '' }} class="text-gray-400">Pilih jenis kelaminmu</option>
                        <option value="Male" {{ $user->gender == 'Male' ? 'selected' : '' }}>Male</option>
                        <option value="Female" {{ $user->gender == 'Female' ? 'selected' : '' }}>Female</option>
                        <option value="Prefer not to say" {{ $user->gender == 'Prefer not to say' ? 'selected' : '' }}>Prefer not to say</option>
                    </select>
                    <!-- Chevron Icon (SVG) -->
                    <div class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-500 pointer-events-none">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Dropdown Semester -->
            <div>
                <label class="font-bold text-sm text-gray-800 mb-1 block">Semester</label>
                <div class="border-2 border-gray-200 rounded-full px-4 py-3 focus-within:border-blue-500 transition-colors relative">
                    <select name="semester" class="w-full bg-transparent outline-none text-gray-700 text-sm font-semibold appearance-none cursor-pointer z-10 relative">
                        <option value="" disabled {{ is_null($user->semester) ? 'selected' : '' }} class="text-gray-400">Kamu semester berapa?</option>
                        @foreach(['1','2','3','4','5','6','7','8','Tidak ada'] as $sem)
                            <option value="{{ $sem }}" {{ $user->semester == $sem ? 'selected' : '' }}>{{ $sem }}</option>
                        @endforeach
                    </select>
                      <!-- Chevron Icon (SVG) -->
                      <div class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-500 pointer-events-none">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Input Description -->
            <div>
                <label class="font-bold text-sm text-gray-800 mb-1 block">Description</label>
                <div class="border-2 border-gray-200 rounded-3xl px-4 py-3 focus-within:border-blue-500 transition-colors">
                    <textarea name="description" rows="4" placeholder="Ceritain dirimu!" class="w-full bg-transparent outline-none text-gray-700 placeholder-gray-400 text-sm font-normal resize-none">{{ old('description', $user->description) }}</textarea>
                </div>
                @error('description') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
            </div>

            <!-- Tombol Save -->
            <button type="submit" class="w-full bg-yellow-500 text-white font-bold text-lg py-3.5 rounded-full shadow-md hover:bg-yellow-600 transition-all active:scale-95 mt-4">
                Save
            </button>

        </form>
    </div>

</div>

<script>
    function previewImage(event) {
        const reader = new FileReader();
        reader.onload = function(){
            const output = document.getElementById('profile-preview');
            output.src = reader.result;
        };
        reader.readAsDataURL(event.target.files[0]);
    }
</script>
@endsection