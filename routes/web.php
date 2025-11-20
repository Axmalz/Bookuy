<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController; // <-- IMPORT BARU
use App\Http\Controllers\SearchController;
use App\Http\Controllers\ProductController; // Import
use App\Http\Controllers\CartController; // Import CartController

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// ... Rute Splash dan Onboarding ...
Route::get('/', function () { return view('splash'); });
Route::get('/welcome', function () { return view('onboarding.1'); }); // <-- Ganti welcome ke onboarding 1
Route::get('/onboarding/1', function () { return view('onboarding.1'); });
Route::get('/onboarding/2', function () { return view('onboarding.2'); });
Route::get('/onboarding/3', function () { return view('onboarding.3'); });

// ... Rute Auth (SignUp, Login, Logout) ...
Route::get('/signup', [AuthController::class, 'showRegisterForm'])->name('register.form');
Route::post('/signup', [AuthController::class, 'register'])->name('register');
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');


/**
 * Rute Home
 */
Route::get('/home', [HomeController::class, 'index'])->name('home')->middleware('auth');

/**
 * Rute Search (DIEDIT)
 * Menggunakan SearchController
 */
Route::get('/search', [SearchController::class, 'index'])->name('search.index')->middleware('auth');

// Rute untuk menghapus recent search
Route::post('/search/clear', [SearchController::class, 'clearRecent'])->name('search.clear')->middleware('auth');
Route::post('/search/remove', [SearchController::class, 'removeRecent'])->name('search.remove')->middleware('auth');

/*
|--------------------------------------------------------------------------
| Rute Placeholder Baru
|--------------------------------------------------------------------------
*/

Route::get('/cart', [CartController::class, 'index'])->name('cart.index')->middleware('auth');
Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add')->middleware('auth');
Route::post('/cart/update/{id}', [CartController::class, 'update'])->name('cart.update')->middleware('auth');
Route::delete('/cart/remove/{id}', [CartController::class, 'remove'])->name('cart.remove')->middleware('auth');

/**
 * Rute Search (DIEDIT)
 * Menggunakan SearchController
 */
Route::get('/search', [SearchController::class, 'index'])->name('search.index')->middleware('auth');

// Rute untuk menghapus recent search
Route::post('/search/clear', [SearchController::class, 'clearRecent'])->name('search.clear')->middleware('auth');
Route::post('/search/remove', [SearchController::class, 'removeRecent'])->name('search.remove')->middleware('auth');

// /product/{id} (dari buku)
Route::get('/product/{id}', [ProductController::class, 'show'])->name('product.show')->middleware('auth');

// --- Rute Nav Bar ---
Route::get('/chat', function () {
    return view('placeholders.chat');
})->name('chat.index')->middleware('auth');

Route::get('/create', function () {
    return view('placeholders.create');
})->name('create.index')->middleware('auth');

Route::get('/notifications', function () {
    return view('placeholders.notifications');
})->name('notifications.index')->middleware('auth');

Route::get('/profile', function () {
    return view('placeholders.profile');
})->name('profile.index')->middleware('auth');
