<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Book;
use App\Models\Review;
use App\Models\Category;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller
{
    public function show(Request $request, $id)
    {
        // ... (kode method show() sama seperti sebelumnya) ...
        $book = Book::with(['user', 'category'])->findOrFail($id);

        $reviewSort = $request->input('review_sort', 'relevant');
        $reviewLimit = $request->input('review_limit', 5);

        $reviewsQuery = $book->reviews()->with('user');

        if ($reviewSort === 'newest') {
            $reviewsQuery->latest();
        } else {
            $reviewsQuery->orderByDesc('rating')->orderByRaw('LENGTH(comment) DESC');
        }

        $totalReviewsReal = $book->reviews()->count();

        if ($reviewLimit !== 'all') {
            $reviews = $reviewsQuery->take((int)$reviewLimit)->get();
        } else {
            $reviews = $reviewsQuery->get();
        }

        $allReviewsForStats = $book->reviews;
        $averageRating = $allReviewsForStats->avg('rating');

        $ratingCounts = [];
        for ($i = 5; $i >= 1; $i--) {
            $ratingCounts[$i] = $allReviewsForStats->where('rating', $i)->count();
        }

        return view('product.show', [
            'book' => $book,
            'reviews' => $reviews,
            'totalReviews' => $totalReviewsReal,
            'averageRating' => $averageRating ?? 0,
            'ratingCounts' => $ratingCounts,
            'currentSort' => $reviewSort,
            'currentLimit' => $reviewLimit
        ]);
    }

    // Menampilkan Form Edit
    public function edit($id)
    {
        $book = Book::findOrFail($id);

        if (Auth::id() !== $book->user_id) {
            abort(403, 'Unauthorized action.');
        }

        $categories = Category::all();

        return view('product.edit', compact('book', 'categories'));
    }

    // Memproses Update
    public function update(Request $request, $id)
    {
        $book = Book::findOrFail($id);

        if (Auth::id() !== $book->user_id) {
            abort(403, 'Unauthorized action.');
        }

        // VALIDASI LENGKAP (Termasuk Stok)
        $request->validate([
            'judul_buku' => 'required|string|max:255',
            'nama_penulis' => 'required|string|max:255',
            'harga_beli' => 'required|numeric',
            'harga_sewa' => 'required|numeric',

            // Validasi Stok Baru
            'stok_beli' => 'required|integer|min:0',
            'stok_sewa' => 'required|integer|min:0',

            'deskripsi_buku' => 'required|string',
            'kondisi_buku' => 'required|in:baru,bekas premium,bekas usang',
            'alamat_buku' => 'required|string',
            'category_id' => 'required|exists:categories,id',
            'jumlah_halaman' => 'required|integer',
            'semester' => 'required',
            'new_images.*' => 'image|mimes:jpeg,png,jpg|max:3072',
        ]);

        // Update data
        $book->judul_buku = $request->judul_buku;
        $book->nama_penulis = $request->nama_penulis;
        $book->harga_beli = $request->harga_beli;
        $book->harga_sewa = $request->harga_sewa;

        // Simpan Stok
        $book->stok_beli = $request->stok_beli;
        $book->stok_sewa = $request->stok_sewa;

        $book->deskripsi_buku = $request->deskripsi_buku;
        $book->kondisi_buku = $request->kondisi_buku;
        $book->alamat_buku = $request->alamat_buku;
        $book->category_id = $request->category_id;
        $book->jumlah_halaman = $request->jumlah_halaman;
        $book->semester = $request->semester;

        // Handle Images
        $currentImages = $book->gambar_buku ?? [];
        $keepImages = $request->input('keep_images', []);
        $finalImages = [];

        foreach ($currentImages as $img) {
            if (in_array($img, $keepImages)) {
                $finalImages[] = $img;
            }
        }

        if ($request->hasFile('new_images')) {
            foreach ($request->file('new_images') as $file) {
                if (count($finalImages) < 3) {
                    $path = $file->store('book-images', 'public');
                    $finalImages[] = asset('storage/' . $path);
                }
            }
        }

        $book->gambar_buku = $finalImages;
        $book->save();

        return redirect()->route('product.show', $book->id)->with('success', 'Product updated successfully');
    }
    // == BARU: Menampilkan Halaman Jual Buku ==
    public function create()
    {
        $categories = Category::all();
        // Kita kirim objek buku kosong agar form bisa menggunakan old() tanpa error akses properti null
        $book = new Book();

        return view('product.create', compact('categories', 'book'));
    }

    // == BARU: Menyimpan Buku Baru ==
    public function store(Request $request)
    {
        // Validasi (Sama seperti update, tapi gambar WAJIB ada minimal 1)
        $request->validate([
            'judul_buku' => 'required|string|max:255',
            'nama_penulis' => 'required|string|max:255',
            'harga_beli' => 'required|numeric',
            'harga_sewa' => 'required|numeric',
            'stok_beli' => 'required|integer|min:0',
            'stok_sewa' => 'required|integer|min:0',
            'deskripsi_buku' => 'required|string',
            'kondisi_buku' => 'required|in:baru,bekas premium,bekas usang',
            'alamat_buku' => 'required|string',
            'category_id' => 'required|exists:categories,id',
            'jumlah_halaman' => 'required|integer',
            'semester' => 'required',
            'new_images' => 'required|array|min:1|max:3', // Wajib minimal 1 gambar
            'new_images.*' => 'image|mimes:jpeg,png,jpg|max:3072',
        ]);

        $book = new Book();
        $book->user_id = Auth::id(); // Set penjual sebagai user yang login

        // Isi data text
        $book->judul_buku = $request->judul_buku;
        $book->nama_penulis = $request->nama_penulis;
        $book->harga_beli = $request->harga_beli;
        $book->harga_sewa = $request->harga_sewa;
        $book->stok_beli = $request->stok_beli;
        $book->stok_sewa = $request->stok_sewa;
        $book->deskripsi_buku = $request->deskripsi_buku;
        $book->kondisi_buku = $request->kondisi_buku;
        $book->alamat_buku = $request->alamat_buku;
        $book->category_id = $request->category_id;
        $book->jumlah_halaman = $request->jumlah_halaman;
        $book->semester = $request->semester;

        // Handle Images (Hanya upload baru)
        $imagePaths = [];
        if ($request->hasFile('new_images')) {
            foreach ($request->file('new_images') as $file) {
                $path = $file->store('book-images', 'public');
                $imagePaths[] = asset('storage/' . $path);
            }
        }
        $book->gambar_buku = $imagePaths;

        $book->save();

        // Redirect ke halaman detail produk yang baru dibuat
        return redirect()->route('product.show', $book->id)->with('success', 'Product posted successfully!');
    }

}
