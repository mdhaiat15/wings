<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Master\ProductController;
use App\Http\Controllers\Master\TransactionController;
use App\Http\Controllers\Order\CartController;
use App\Http\Controllers\Order\ProductListController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('page.welcome');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'show'])->name('dashboard');

    Route::resource('product', ProductController::class)->except(['show']);

    Route::resource('product-list', ProductListController::class)->only(['index', 'store']);

    Route::resource('cart', CartController::class)->only(['store', 'index', 'update', 'destroy']);

    Route::resource('transaction', TransactionController::class)->except(['show']);

    Route::post('transaction/print', [TransactionController::class, 'print'])->name('transaction.print');

    Route::post('cart/check-out', [CartController::class, 'checkOut'])->name('cart.check-out');
});
