<?php

// Kode ditulis oleh :
// Nama  : Fadhiil Akmal Hamizan
// Github: Axmalz
// NRP   : 5026231128
// Kelas : PPPL B

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
        $sellerId = $request->input('seller');

        $sort = $request->input('sort', 'relevance');
        $minPrice = $request->input('min_price', 0);
        $maxPrice = $request->input('max_price', 200000);
        $semester = $request->input('semester');
        $condition = $request->input('condition');

        $books = null;

        if ($query || $categoryFilter || $sellerId || $request->has('sort') || $request->has('min_price')) {

            $books = Book::withAvg('reviews', 'rating')
                         ->withCount('reviews')
                         ->orderByStockAvailability(); // FIX: Pastikan stok habis ada di bawah

            if ($sellerId) {
                $books->where('user_id', $sellerId);
            }

            if ($query) {
                $books->where(function ($q) use ($query) {
                    $q->where('judul_buku', 'like', "%{$query}%")
                      ->orWhere('nama_penulis', 'like', "%{$query}%");
                });
                if (!$sellerId) $this->addToRecentSearches($query);
            }

            if ($categoryFilter) {
                $books->whereHas('category', function ($q) use ($categoryFilter) {
                    $q->where('name', $categoryFilter);
                });
            }

            $books->whereBetween('harga_beli', [$minPrice, $maxPrice]);

            if ($semester && $semester !== 'Semua') {
                $books->where('semester', $semester);
            }

            if ($condition && $condition !== 'Semua') {
                $books->where('kondisi_buku', strtolower($condition));
            }

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

    private function addToRecentSearches($query) {
        if (empty($query)) return;
        $recent = Session::get('recent_searches', []);
        if (($key = array_search($query, $recent)) !== false) unset($recent[$key]);
        array_unshift($recent, $query);
        Session::put('recent_searches', array_slice($recent, 0, 5));
        Session::save();
    }
    public function clearRecent() { Session::forget('recent_searches'); return redirect()->route('search.index'); }
    public function removeRecent(Request $request) {
        $queryToRemove = $request->input('q');
        $recent = Session::get('recent_searches', []);
        if (($key = array_search($queryToRemove, $recent)) !== false) {
            unset($recent[$key]);
            Session::put('recent_searches', array_values($recent));
        }
        return redirect()->route('search.index');
    }
}
