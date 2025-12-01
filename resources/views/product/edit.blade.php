@extends('layouts.app')

@section('content')
<div class="w-full h-full bg-white flex flex-col relative">

    <!-- 1. Header Biru -->
    <div class="w-full bg-blue-600 pt-12 pb-6 rounded-b-[40px] shadow-md z-20 relative px-6 flex-shrink-0">
        <div class="flex items-center justify-center relative mb-2">
            <!-- Tombol Back -->
            <a href="{{ route('product.show', $book->id) }}" class="absolute left-0 text-white hover:text-gray-200 transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-6 h-6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
                </svg>
            </a>
        </div>
        <h1 class="font-sugo text-3xl text-center text-white tracking-wide">Edit Product</h1>
    </div>

    <!-- 2. Form Konten -->
    <div class="flex-grow overflow-y-auto px-6 pt-8 pb-10">
        <form action="{{ route('product.update', $book->id) }}" method="POST" enctype="multipart/form-data" class="space-y-5">
            @csrf

            <!-- Gambar Buku -->
            <div>
                <label class="font-bold text-sm text-gray-800 mb-2 block">Gambar Buku (Max 3)</label>

                <!-- Container Grid -->
                <div class="grid grid-cols-3 gap-2 mb-3" id="image-grid">

                    <!-- 1. Gambar Lama (Existing) -->
                    @foreach($book->gambar_buku as $img)
                    <div class="relative rounded-lg overflow-hidden aspect-[2/3] group image-slot existing-image">
                        <img src="{{ $img }}" class="w-full h-full object-cover">

                        <!-- Input Hidden untuk menjaga gambar ini saat save -->
                        <!-- Jika elemen ini dihapus, gambar ini tidak akan dikirim kembali ke server, artinya dihapus -->
                        <input type="hidden" name="keep_images[]" value="{{ $img }}">

                        <!-- Tombol Hapus (Silang Merah) -->
                        <button type="button" onclick="removeImage(this)" class="absolute top-1 right-1 w-6 h-6 bg-red-500 rounded-full flex items-center justify-center text-white shadow-md hover:bg-red-600 transition-colors z-10">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                    @endforeach

                    <!-- 2. Gambar Baru akan disisipkan di sini via JS -->

                    <!-- 3. Tombol Add Photo -->
                    <!-- Div pembungkus tombol add, ID: add-photo-wrapper -->
                    <div id="add-photo-wrapper" class="aspect-[2/3] {{ count($book->gambar_buku) >= 3 ? 'hidden' : '' }}">
                        <label class="w-full h-full border-2 border-dashed border-gray-300 rounded-lg flex flex-col items-center justify-center text-gray-400 cursor-pointer hover:border-blue-500 hover:text-blue-500 transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-8 h-8 mb-1">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                            </svg>
                            <span class="text-[10px] font-bold">Add Photo</span>
                        </label>
                    </div>

                </div>

                <!-- Container untuk Input File (Hidden) -->
                <!-- Kita butuh wadah ini untuk menampung input file yang BELUM dipilih -->
                <div id="file-inputs-holder" class="hidden">
                    <!-- Input pertama -->
                    <input type="file" name="new_images[]" class="file-input-trigger" accept="image/*" onchange="handleFileSelect(this)">
                </div>

                <p class="text-[10px] text-gray-400">* Klik tombol silang merah untuk menghapus gambar.</p>
            </div>

            <!-- Judul -->
            <div class="form-group">
                <label class="font-bold text-sm text-gray-800 mb-1 block">Judul Buku</label>
                <input type="text" name="judul_buku" value="{{ old('judul_buku', $book->judul_buku) }}" class="w-full border-2 border-gray-200 rounded-full px-4 py-3 text-sm focus:border-blue-500 outline-none transition-colors">
            </div>

            <!-- Penulis -->
            <div class="form-group">
                <label class="font-bold text-sm text-gray-800 mb-1 block">Nama Penulis</label>
                <input type="text" name="nama_penulis" value="{{ old('nama_penulis', $book->nama_penulis) }}" class="w-full border-2 border-gray-200 rounded-full px-4 py-3 text-sm focus:border-blue-500 outline-none transition-colors">
            </div>

            <!-- Harga (Flex Row) -->
            <div class="flex gap-4">
                <div class="flex-1">
                    <label class="font-bold text-sm text-gray-800 mb-1 block">Harga Beli</label>
                    <input type="number" name="harga_beli" value="{{ old('harga_beli', $book->harga_beli) }}" class="w-full border-2 border-gray-200 rounded-full px-4 py-3 text-sm focus:border-blue-500 outline-none transition-colors">
                </div>
                <div class="flex-1">
                    <label class="font-bold text-sm text-gray-800 mb-1 block">Harga Sewa</label>
                    <input type="number" name="harga_sewa" value="{{ old('harga_sewa', $book->harga_sewa) }}" class="w-full border-2 border-gray-200 rounded-full px-4 py-3 text-sm focus:border-blue-500 outline-none transition-colors">
                </div>
            </div>

            <!-- STOK (Flex Row - BARU) -->
            <div class="flex gap-4">
                <div class="flex-1">
                    <label class="font-bold text-sm text-gray-800 mb-1 block">Stok Beli</label>
                    <input type="number" name="stok_beli" value="{{ old('stok_beli', $book->stok_beli) }}" class="w-full border-2 border-gray-200 rounded-full px-4 py-3 text-sm focus:border-blue-500 outline-none transition-colors">
                </div>
                <div class="flex-1">
                    <label class="font-bold text-sm text-gray-800 mb-1 block">Stok Sewa</label>
                    <input type="number" name="stok_sewa" value="{{ old('stok_sewa', $book->stok_sewa) }}" class="w-full border-2 border-gray-200 rounded-full px-4 py-3 text-sm focus:border-blue-500 outline-none transition-colors">
                </div>
            </div>

            <!-- Deskripsi -->
            <div class="form-group">
                <label class="font-bold text-sm text-gray-800 mb-1 block">Deskripsi Buku</label>
                <textarea name="deskripsi_buku" rows="4" class="w-full border-2 border-gray-200 rounded-3xl px-4 py-3 text-sm focus:border-blue-500 outline-none transition-colors resize-none">{{ old('deskripsi_buku', $book->deskripsi_buku) }}</textarea>
            </div>

            <!-- Kondisi (Dropdown) -->
            <div class="form-group">
                <label class="font-bold text-sm text-gray-800 mb-1 block">Kondisi Buku</label>
                <div class="relative">
                    <select name="kondisi_buku" class="w-full border-2 border-gray-200 rounded-full px-4 py-3 text-sm focus:border-blue-500 outline-none appearance-none cursor-pointer bg-transparent">
                        <option value="baru" {{ $book->kondisi_buku == 'baru' ? 'selected' : '' }}>Baru</option>
                        <option value="bekas premium" {{ $book->kondisi_buku == 'bekas premium' ? 'selected' : '' }}>Bekas Premium</option>
                        <option value="bekas usang" {{ $book->kondisi_buku == 'bekas usang' ? 'selected' : '' }}>Bekas Usang</option>
                    </select>
                    <div class="absolute right-4 top-1/2 -translate-y-1/2 pointer-events-none text-gray-500">▼</div>
                </div>
            </div>

            <!-- Alamat -->
            <div class="form-group">
                <label class="font-bold text-sm text-gray-800 mb-1 block">Alamat Buku</label>
                <input type="text" name="alamat_buku" value="{{ old('alamat_buku', $book->alamat_buku) }}" class="w-full border-2 border-gray-200 rounded-full px-4 py-3 text-sm focus:border-blue-500 outline-none transition-colors">
            </div>

            <!-- Kategori (Dropdown Names) -->
            <div class="form-group">
                <label class="font-bold text-sm text-gray-800 mb-1 block">Category</label>
                <div class="relative">
                    <select name="category_id" class="w-full border-2 border-gray-200 rounded-full px-4 py-3 text-sm focus:border-blue-500 outline-none appearance-none cursor-pointer bg-transparent">
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ $book->category_id == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                        @endforeach
                    </select>
                    <div class="absolute right-4 top-1/2 -translate-y-1/2 pointer-events-none text-gray-500">▼</div>
                </div>
            </div>

            <!-- Halaman & Semester -->
            <div class="flex gap-4">
                <div class="flex-1">
                    <label class="font-bold text-sm text-gray-800 mb-1 block">Jumlah Halaman</label>
                    <input type="number" name="jumlah_halaman" value="{{ old('jumlah_halaman', $book->jumlah_halaman) }}" class="w-full border-2 border-gray-200 rounded-full px-4 py-3 text-sm focus:border-blue-500 outline-none transition-colors">
                </div>
                <div class="flex-1">
                    <label class="font-bold text-sm text-gray-800 mb-1 block">Semester</label>
                    <div class="relative">
                        <select name="semester" class="w-full border-2 border-gray-200 rounded-full px-4 py-3 text-sm focus:border-blue-500 outline-none appearance-none cursor-pointer bg-transparent">
                            @foreach(['1','2','3','4','5','6','7','8','Tidak ada'] as $sem)
                                <option value="{{ $sem }}" {{ $book->semester == $sem ? 'selected' : '' }}>{{ $sem }}</option>
                            @endforeach
                        </select>
                        <div class="absolute right-4 top-1/2 -translate-y-1/2 pointer-events-none text-gray-500">▼</div>
                    </div>
                </div>
            </div>

            <!-- Save Button -->
            <div class="pt-4">
                <button type="submit" class="w-full bg-yellow-500 text-white font-bold text-lg py-3.5 rounded-full shadow-md hover:bg-yellow-600 transition-all active:scale-95">
                    Save
                </button>
            </div>

        </form>
    </div>
</div>

<script>
    // Logic untuk tombol Add Photo (Label)
    // Saat label diklik, trigger input file yang sedang 'standby' (yang terakhir dibuat)
    const addPhotoBtn = document.getElementById('add-photo-wrapper');
    addPhotoBtn.addEventListener('click', function() {
        const activeInput = document.querySelector('#file-inputs-holder .file-input-trigger:last-child');
        if(activeInput) activeInput.click();
    });

    // Handle saat file dipilih
    function handleFileSelect(input) {
        if (input.files && input.files[0]) {
            const file = input.files[0];
            const reader = new FileReader();

            reader.onload = function(e) {
                // 1. Buat elemen preview visual (div)
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

                // 2. PINDAHKAN input file yang sudah ada isinya KE DALAM previewDiv
                // Ini trik kuncinya: Input file menjadi anak dari div preview.
                // Jadi kalau div preview dihapus, input filenya juga ikut terhapus.
                input.classList.add('hidden'); // Sembunyikan input aslinya
                input.removeAttribute('onchange'); // Hapus listener agar tidak memicu event lagi
                previewDiv.appendChild(input);

                // 3. Masukkan preview ke grid (sebelum tombol Add Photo)
                const grid = document.getElementById('image-grid');
                grid.insertBefore(previewDiv, addPhotoBtn);

                // 4. SIAPKAN input file BARU untuk slot berikutnya
                // Kita buat elemen input baru yang bersih dan taruh di #file-inputs-holder
                const newInput = document.createElement('input');
                newInput.type = 'file';
                newInput.name = 'new_images[]'; // Array name sama, backend akan terima array
                newInput.className = 'file-input-trigger';
                newInput.accept = 'image/*';
                newInput.onchange = function() { handleFileSelect(this); };

                document.getElementById('file-inputs-holder').appendChild(newInput);

                // 5. Cek slot apakah sudah penuh
                checkSlots();
            }
            reader.readAsDataURL(file);
        }
    }

    // Fungsi Hapus Gambar
    function removeImage(btn) {
        // Hapus elemen parent (div.image-slot) dari DOM
        // Ini secara otomatis menghapus apa pun yang ada di dalamnya:
        // - Jika gambar lama: Input hidden 'keep_images[]' ikut terhapus -> Backend akan tahu gambar ini dihapus.
        // - Jika gambar baru: Input file 'new_images[]' yang kita pindahkan tadi ikut terhapus -> File tidak akan terkirim.
        const slot = btn.closest('.image-slot');
        slot.remove();
        checkSlots();
    }

    // Fungsi Cek Slot (Maksimal 3)
    function checkSlots() {
        // Hitung semua elemen dengan class .image-slot (baik lama maupun baru)
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
