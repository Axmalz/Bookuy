<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Book;

class HomeController extends Controller
{
    public function index()
    {
        $categories = Category::all();

        // Mengambil buku dengan rata-rata rating (reviews_avg_rating)
        // dan jumlah review (reviews_count)
        $recommendedBooks = Book::withAvg('reviews', 'rating')
                                ->withCount('reviews')
                                ->latest()
                                ->take(5)
                                ->get();

        $popularBooks = Book::withAvg('reviews', 'rating')
                            ->withCount('reviews')
                            ->orderByDesc('reviews_avg_rating') // Urutkan dari rating tertinggi
                            ->take(5)
                            ->get();

        return view('home', [
            'categories' => $categories,
            'recommendedBooks' => $recommendedBooks,
            'popularBooks' => $popularBooks
        ]);
    }
}
