@extends('layouts.app-main') <!-- DIUBAH: Menggunakan app-main agar ada navbar -->
<!--
// Kode ditulis oleh :
// Nama  : Fadhiil Akmal Hamizan
// Github: Axmalz
// NRP   : 5026231128
// Kelas : PPPL B
-->
@section('main-content') <!-- DIUBAH: Mengisi section 'main-content' milik app-main -->

<!-- FIX: Override CSS untuk menghilangkan blok putih -->
@push('styles')
<style>
    /* Kita mereset padding-bottom dari layout utama (.app-content) menjadi 0
       agar container halaman ini bisa mengisi tinggi penuh (full height)
       dan background putihnya sampai ke paling bawah di belakang navbar.
       Kita gunakan selector spesifik agar menang dari style default.
    */
    body .iphone-screen .app-content {
        padding-bottom: 0 !important;
    }
</style>
@endpush

<div class="w-full h-full bg-white flex flex-col relative">

    <!-- 1. Header Biru -->
    <div class="w-full bg-blue-600 pt-12 pb-6 rounded-b-[40px] shadow-md z-20 relative px-6 flex-shrink-0">
        <!-- MODIFIKASI 1: Tombol Back DIHAPUS -->
        <div class="flex items-center justify-center relative mb-2 h-6">
            <!-- Kosong (Placeholder agar layout judul tetap rapi jika diperlukan) -->
        </div>

        <h1 class="font-sugo text-3xl text-center text-white tracking-wide">Sell Product</h1>
    </div>

    <!-- 2. Form Konten -->
    <!-- FIX: Padding bawah diperbesar menjadi pb-32 agar konten paling bawah (tombol Save)
         bisa di-scroll naik melebihi tinggi navbar -->
    <div class="flex-grow overflow-y-auto px-6 pt-8 pb-32 no-scrollbar">

        <!-- Form mengarah ke product.store -->
        <form action="{{ route('product.store') }}" method="POST" enctype="multipart/form-data" class="space-y-5">
            @csrf

            <!-- Gambar Buku -->
            <div>
                <label class="font-bold text-sm text-gray-800 mb-2 block">Gambar Buku (Max 3)</label>

                <!-- Container Grid -->
                <div class="grid grid-cols-3 gap-2 mb-3" id="image-grid">
                    <!-- Tombol Add Photo -->
                    <div id="add-photo-wrapper" class="aspect-[2/3]">
                        <label class="w-full h-full border-2 border-dashed border-gray-300 rounded-lg flex flex-col items-center justify-center text-gray-400 cursor-pointer hover:border-blue-500 hover:text-blue-500 transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-8 h-8 mb-1">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                            </svg>
                            <span class="text-[10px] font-bold">Add Photo</span>
                        </label>
                    </div>
                </div>

                <!-- Container Input File -->
                <div id="file-inputs-holder" class="hidden">
                    <input type="file" name="new_images[]" class="file-input-trigger" accept="image/*" onchange="handleFileSelect(this)">
                </div>

                <!-- Error Message untuk Gambar -->
                @error('new_images') <span class="text-red-500 text-xs block mt-1">{{ $message }}</span> @enderror

                <p class="text-[10px] text-gray-400">* Minimal 1 gambar wajib diupload.</p>
            </div>

            <!-- Judul -->
            <div class="form-group">
                <label class="font-bold text-sm text-gray-800 mb-1 block">Judul Buku</label>
                <input type="text" name="judul_buku" value="{{ old('judul_buku') }}" required class="w-full border-2 border-gray-200 rounded-full px-4 py-3 text-sm focus:border-blue-500 outline-none transition-colors">
                @error('judul_buku') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
            </div>

            <!-- Penulis -->
            <div class="form-group">
                <label class="font-bold text-sm text-gray-800 mb-1 block">Nama Penulis</label>
                <input type="text" name="nama_penulis" value="{{ old('nama_penulis') }}" required class="w-full border-2 border-gray-200 rounded-full px-4 py-3 text-sm focus:border-blue-500 outline-none transition-colors">
            </div>

            <!-- Harga (Flex Row) -->
            <div class="flex gap-4">
                <div class="flex-1">
                    <label class="font-bold text-sm text-gray-800 mb-1 block">Harga Beli</label>
                    <input type="number" name="harga_beli" value="{{ old('harga_beli') }}" required class="w-full border-2 border-gray-200 rounded-full px-4 py-3 text-sm focus:border-blue-500 outline-none transition-colors">
                </div>
                <div class="flex-1">
                    <label class="font-bold text-sm text-gray-800 mb-1 block">Harga Sewa</label>
                    <input type="number" name="harga_sewa" value="{{ old('harga_sewa') }}" required class="w-full border-2 border-gray-200 rounded-full px-4 py-3 text-sm focus:border-blue-500 outline-none transition-colors">
                </div>
            </div>

            <!-- Stok (Flex Row) -->
            <div class="flex gap-4">
                <div class="flex-1">
                    <label class="font-bold text-sm text-gray-800 mb-1 block">Stok Beli</label>
                    <input type="number" name="stok_beli" value="{{ old('stok_beli') }}" required class="w-full border-2 border-gray-200 rounded-full px-4 py-3 text-sm focus:border-blue-500 outline-none transition-colors">
                </div>
                <div class="flex-1">
                    <label class="font-bold text-sm text-gray-800 mb-1 block">Stok Sewa</label>
                    <input type="number" name="stok_sewa" value="{{ old('stok_sewa') }}" required class="w-full border-2 border-gray-200 rounded-full px-4 py-3 text-sm focus:border-blue-500 outline-none transition-colors">
                </div>
            </div>

            <!-- Deskripsi -->
            <div class="form-group">
                <label class="font-bold text-sm text-gray-800 mb-1 block">Deskripsi Buku</label>
                <textarea name="deskripsi_buku" rows="4" required class="w-full border-2 border-gray-200 rounded-3xl px-4 py-3 text-sm focus:border-blue-500 outline-none transition-colors resize-none">{{ old('deskripsi_buku') }}</textarea>
            </div>

            <!-- Kondisi (Dropdown) -->
            <div class="form-group">
                <label class="font-bold text-sm text-gray-800 mb-1 block">Kondisi Buku</label>
                <div class="relative">
                    <select name="kondisi_buku" required class="w-full border-2 border-gray-200 rounded-full px-4 py-3 text-sm focus:border-blue-500 outline-none appearance-none cursor-pointer bg-transparent">
                        <option value="" disabled selected class="text-gray-400">Pilih Kondisi</option>
                        <option value="baru" {{ old('kondisi_buku') == 'baru' ? 'selected' : '' }}>Baru</option>
                        <option value="bekas premium" {{ old('kondisi_buku') == 'bekas premium' ? 'selected' : '' }}>Bekas Premium</option>
                        <option value="bekas usang" {{ old('kondisi_buku') == 'bekas usang' ? 'selected' : '' }}>Bekas Usang</option>
                    </select>
                    <div class="absolute right-4 top-1/2 -translate-y-1/2 pointer-events-none text-gray-500">▼</div>
                </div>
            </div>

            <!-- Alamat -->
            <div class="form-group">
                <label class="font-bold text-sm text-gray-800 mb-1 block">Alamat Buku</label>
                <input type="text" name="alamat_buku" value="{{ old('alamat_buku') }}" required class="w-full border-2 border-gray-200 rounded-full px-4 py-3 text-sm focus:border-blue-500 outline-none transition-colors">
            </div>

            <!-- Kategori -->
            <div class="form-group">
                <label class="font-bold text-sm text-gray-800 mb-1 block">Category</label>
                <div class="relative">
                    <select name="category_id" required class="w-full border-2 border-gray-200 rounded-full px-4 py-3 text-sm focus:border-blue-500 outline-none appearance-none cursor-pointer bg-transparent">
                        <option value="" disabled selected class="text-gray-400">Pilih Kategori</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                        @endforeach
                    </select>
                    <div class="absolute right-4 top-1/2 -translate-y-1/2 pointer-events-none text-gray-500">▼</div>
                </div>
            </div>

            <!-- Halaman & Semester -->
            <div class="flex gap-4">
                <div class="flex-1">
                    <label class="font-bold text-sm text-gray-800 mb-1 block">Jumlah Halaman</label>
                    <input type="number" name="jumlah_halaman" value="{{ old('jumlah_halaman') }}" required class="w-full border-2 border-gray-200 rounded-full px-4 py-3 text-sm focus:border-blue-500 outline-none transition-colors">
                </div>
                <div class="flex-1">
                    <label class="font-bold text-sm text-gray-800 mb-1 block">Semester</label>
                    <div class="relative">
                        <select name="semester" required class="w-full border-2 border-gray-200 rounded-full px-4 py-3 text-sm focus:border-blue-500 outline-none appearance-none cursor-pointer bg-transparent">
                            <option value="" disabled selected class="text-gray-400">Pilih Semester</option>
                            @foreach(['1','2','3','4','5','6','7','8','Tidak ada'] as $sem)
                                <option value="{{ $sem }}" {{ old('semester') == $sem ? 'selected' : '' }}>{{ $sem }}</option>
                            @endforeach
                        </select>
                        <div class="absolute right-4 top-1/2 -translate-y-1/2 pointer-events-none text-gray-500">▼</div>
                    </div>
                </div>
            </div>

            <!-- Save Button -->
            <div class="pt-4">
                <button type="submit" class="w-full bg-yellow-500 text-white font-bold text-lg py-3.5 rounded-full shadow-md hover:bg-yellow-600 transition-all active:scale-95">
                    Save and Post!
                </button>
            </div>

        </form>
    </div>

    <!-- Spacer untuk Navbar Bawah -->
    <div class="h-10 w-full"></div>

</div>

<script>
    // LOGIKA JS GAMBAR (Sama dengan Edit)
    const addPhotoBtn = document.getElementById('add-photo-wrapper');
    addPhotoBtn.addEventListener('click', function() {
        const activeInput = document.querySelector('#file-inputs-holder .file-input-trigger:last-child');
        if(activeInput) activeInput.click();
    });

    function handleFileSelect(input) {
        if (input.files && input.files[0]) {
            const file = input.files[0];
            const reader = new FileReader();

            reader.onload = function(e) {
                const previewDiv = document.createElement('div');
                previewDiv.className = 'relative rounded-lg overflow-hidden aspect-[2/3] group image-slot new-image border-2 border-blue-500';

                previewDiv.innerHTML = `
                    <img src="${e.target.result}" class="w-full h-full object-cover">
                    <div class="absolute top-1 left-1 bg-blue-500 text-white text-[8px] px-2 py-0.5 rounded-full font-bold">NEW</div>
                    <button type="button" onclick="removeImage(this)" class="absolute top-1 right-1 w-6 h-6 bg-red-500 rounded-full flex items-center justify-center text-white shadow-md hover:bg-red-600 transition-colors z-10">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                `;

                input.classList.add('hidden');
                input.removeAttribute('onchange');
                previewDiv.appendChild(input);

                const grid = document.getElementById('image-grid');
                grid.insertBefore(previewDiv, addPhotoBtn);

                const newInput = document.createElement('input');
                newInput.type = 'file';
                newInput.name = 'new_images[]';
                newInput.className = 'file-input-trigger';
                newInput.accept = 'image/*';
                newInput.onchange = function() { handleFileSelect(this); };

                document.getElementById('file-inputs-holder').appendChild(newInput);
                checkSlots();
            }
            reader.readAsDataURL(file);
        }
    }

    function removeImage(btn) {
        const slot = btn.closest('.image-slot');
        slot.remove();
        checkSlots();
    }

    function checkSlots() {
        const currentImages = document.querySelectorAll('.image-slot').length;
        const addBtn = document.getElementById('add-photo-wrapper');

        if (currentImages >= 3) {
            addBtn.classList.add('hidden');
        } else {
            addBtn.classList.remove('hidden');
        }
    }
</script>
@endsection
