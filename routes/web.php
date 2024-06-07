<?php

use App\Http\Controllers\CartController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReportPenjualanController;
use App\Http\Controllers\TransactionControlle;
use App\Http\Controllers\TransactionController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('auth.login');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    Route::resource('product', ProductController::class);
    Route::post('/add-to-cart', [ProductController::class, 'addToCart'])->name('add-to-cart');

    Route::get('/product-detail/{id}', [ProductController::class, 'showDetail'])->name('product-detail.show');
    
    Route::get('/cart', [CartController::class, 'index'])->name('cart.show');
    Route::get('/count-cart', [CartController::class, 'countCart'])->name('count-cart.show');
    Route::PUT('/update-cart/{id}', [CartController::class, 'updateCart'])->name('update-cart');
    Route::delete('/remove-cart/{id}', [CartController::class, 'removeCart'])->name('remove-cart');
    Route::post('/clear-cart', [CartController::class, 'clearCart'])->name('clear-cart');

    Route::post('/transaction', [TransactionController::class, 'store'])->name('transaction');

    Route::get('/report-penjualan', [ReportPenjualanController::class, 'index'])->name('report-penjualan.index');


});

require __DIR__.'/auth.php';
