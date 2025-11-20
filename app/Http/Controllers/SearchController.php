<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Book;
use App\Models\Category;
use Illuminate\Support\Facades\Session;

class SearchController extends Controller
{
    public function index(Request $request)
    {
        $categories = Category::all();
        $query = $request->input('q');
        $categoryFilter = $request->input('category');

        // Filter parameter
        $sort = $request->input('sort', 'relevance');
        $minPrice = $request->input('min_price', 0);
        $maxPrice = $request->input('max_price', 200000);
        $semester = $request->input('semester');
        $condition = $request->input('condition');

        $books = null;

        // Cek apakah ada pencarian aktif
        if ($query || $categoryFilter || $request->has('sort') || $request->has('min_price')) {

            $books = Book::withAvg('reviews', 'rating')
                         ->withCount('reviews');

            if ($query) {
                $books->where(function ($q) use ($query) {
                    $q->where('judul_buku', 'like', "%{$query}%")
                      ->orWhere('nama_penulis', 'like', "%{$query}%");
                });
                // SIMPAN PENCARIAN KE SESSION
                $this->addToRecentSearches($query);
            }

            if ($categoryFilter) {
                $books->whereHas('category', function ($q) use ($categoryFilter) {
                    $q->where('name', $categoryFilter);
                });
                // Opsional: Simpan kategori juga jika diinginkan sebagai recent search
                // $this->addToRecentSearches($categoryFilter);
            }

            $books->whereBetween('harga_beli', [$minPrice, $maxPrice]);

            if ($semester && $semester !== 'Semua') {
                $books->where('semester', $semester);
            }

            if ($condition && $condition !== 'Semua') {
                $books->where('kondisi_buku', strtolower($condition));
            }

            // Logika Sorting
            if ($sort && $sort !== 'relevance') {
                $sorts = explode(',', $sort);
                foreach ($sorts as $s) {
                    switch ($s) {
                        case 'price_asc': $books->orderBy('harga_beli', 'asc'); break;
                        case 'price_desc': $books->orderBy('harga_beli', 'desc'); break;
                        case 'rating_asc': $books->orderBy('reviews_avg_rating', 'asc'); break;
                        case 'rating_desc': $books->orderBy('reviews_avg_rating', 'desc'); break;
                    }
                }
            }

            $books = $books->get();
        }

        // AMBIL RECENT SEARCHES DARI SESSION
        // Pastikan session key konsisten ('recent_searches')
        $recentSearches = Session::get('recent_searches', []);

        return view('search.index', [
            'categories' => $categories,
            'books' => $books,
            'recentSearches' => $recentSearches,
            'currentQuery' => $query,
            'currentCategory' => $categoryFilter,
            'filters' => [
                'sort' => $sort,
                'min_price' => $minPrice,
                'max_price' => $maxPrice,
                'semester' => $semester,
                'condition' => $condition
            ]
        ]);
    }

    // Fungsi helper untuk menyimpan pencarian
    private function addToRecentSearches($query)
    {
        if (empty($query)) return; // Jangan simpan string kosong

        $recent = Session::get('recent_searches', []);

        // Hapus jika sudah ada (agar tidak duplikat dan naik ke paling atas)
        if (($key = array_search($query, $recent)) !== false) {
            unset($recent[$key]);
        }

        // Tambahkan ke awal array
        array_unshift($recent, $query);

        // Batasi hanya simpan 5 pencarian terakhir
        $recent = array_slice($recent, 0, 5);

        // Simpan kembali ke session
        Session::put('recent_searches', array_values($recent));
        Session::save(); // Paksa simpan session
    }

    public function clearRecent()
    {
        Session::forget('recent_searches');
        Session::save();
        return redirect()->route('search.index');
    }

    public function removeRecent(Request $request)
    {
        $queryToRemove = $request->input('q');
        $recent = Session::get('recent_searches', []);

        if (($key = array_search($queryToRemove, $recent)) !== false) {
            unset($recent[$key]);
            Session::put('recent_searches', array_values($recent));
            Session::save();
        }

        return redirect()->route('search.index');
    }
}
