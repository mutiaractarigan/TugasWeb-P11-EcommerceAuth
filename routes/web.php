<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', function (Request $request) {
        $orders = $request->user()->orders()
            ->with(['items.product', 'payment'])
            ->latest()
            ->take(5)
            ->get();

        return view('dashboard', compact('orders'));
    })->middleware('verified')->name('dashboard');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Semua role yang login
    Route::get('/products', [ProductController::class, 'index'])->name('products.index');

    // Admin & editor: tulis/edit/hapus post
    // (didaftarkan sebelum posts/{post} supaya "posts/create" tidak tertangkap route show)
    Route::middleware('role:admin,editor')->group(function () {
        Route::resource('posts', PostController::class)->except(['index', 'show']);
    });

    Route::resource('posts', PostController::class)->only(['index', 'show']);

    // Khusus admin
    Route::middleware('role:admin')->prefix('admin')->name('admin.')->group(function () {
        Route::get('/', [AdminController::class, 'index'])->name('index');
    });
});

require __DIR__.'/auth.php';
