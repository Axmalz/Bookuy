<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Book;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function index()
    {
        $categories = Category::all();

        // Recommended
        $recommendedBooks = Book::withAvg('reviews', 'rating')
                                ->withCount('reviews')
                                ->orderByStockAvailability() // FIX: Panggil Scope DI SINI (sebelum get)
                                ->latest()
                                ->take(5)
                                ->get();

        // Popular
        $popularBooks = Book::withAvg('reviews', 'rating')
                            ->withCount('reviews')
                            ->orderByStockAvailability() // FIX: Panggil Scope DI SINI (sebelum get)
                            ->orderByDesc('reviews_avg_rating')
                            ->take(5)
                            ->get();

        return view('home', [
            'categories' => $categories,
            'recommendedBooks' => $recommendedBooks,
            'popularBooks' => $popularBooks
        ]);
    }
}
