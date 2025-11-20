<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Book;
use App\Models\Review;

class ProductController extends Controller
{
    public function show(Request $request, $id)
    {
        // Ambil buku dengan relasi yang dibutuhkan
        $book = Book::with(['user', 'category'])->findOrFail($id);

        // Ambil filter review dari request
        $reviewSort = $request->input('review_sort', 'relevant');
        $reviewLimit = $request->input('review_limit', 5);

        // Query Review dasar
        $reviewsQuery = $book->reviews()->with('user');

        // Sorting
        if ($reviewSort === 'newest') {
            $reviewsQuery->latest();
        } else {
            // Relevan: Rating tinggi & komentar panjang
            $reviewsQuery->orderByDesc('rating')->orderByRaw('LENGTH(comment) DESC');
        }

        // Pagination / Limiting
        $totalReviewsReal = $book->reviews()->count();

        // Logika limit
        if ($reviewLimit !== 'all') {
            $reviews = $reviewsQuery->take((int)$reviewLimit)->get();
        } else {
            $reviews = $reviewsQuery->get();
        }

        // Statistik Rating (Hitung manual dari koleksi review untuk akurasi)
        // Kita ambil SEMUA review untuk statistik, bukan yang sudah di-limit
        $allReviewsForStats = $book->reviews;
        $averageRating = $allReviewsForStats->avg('rating');

        $ratingCounts = [];
        for ($i = 5; $i >= 1; $i--) {
            $ratingCounts[$i] = $allReviewsForStats->where('rating', $i)->count();
        }

        return view('product.show', [
            'book' => $book,
            'reviews' => $reviews, // Review yang ditampilkan (sudah difilter/limit)
            'totalReviews' => $totalReviewsReal,
            'averageRating' => $averageRating ?? 0,
            'ratingCounts' => $ratingCounts,
            'currentSort' => $reviewSort,
            'currentLimit' => $reviewLimit
        ]);
    }
}
