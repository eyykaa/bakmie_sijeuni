<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TableController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ThankYouController;

Route::get('/', function () {
    return view('customer.pelanggan1', ['title' => 'Sijeuni - Dashboard']);
})->name('home');

// Pilih meja
Route::get('/pilih-meja', [TableController::class, 'index'])->name('tables.index');
Route::post('/pilih-meja', [TableController::class, 'store'])->name('tables.store');

// Menu
Route::get('/menu/{category?}', [MenuController::class, 'index'])->name('menu.index');

// Cart / Detail Pesanan
Route::get('/cart', [CartController::class, 'show'])->name('cart.show');
Route::get('/pesanan', [CartController::class, 'show'])->name('cart.pesanan'); 
Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
Route::post('/cart/update', [CartController::class, 'update'])->name('cart.update');
Route::post('/cart/remove', [CartController::class, 'remove'])->name('cart.remove');
Route::post('/cart/clear', [CartController::class, 'clear'])->name('cart.clear');

// Pembayaran 
Route::get('/pembayaran', [PaymentController::class, 'show'])->name('pembayaran.show');
Route::post('/pembayaran', [PaymentController::class, 'store'])->name('pembayaran.store');

Route::get('/pembayaran/lanjut', [PaymentController::class, 'lanjut'])->name('pembayaran.lanjut');

// TRANSFER
Route::get('/pembayaran/transfer', [PaymentController::class, 'transfer'])->name('pembayaran.transfer');
Route::post('/pembayaran/transfer/confirm', [PaymentController::class, 'transferConfirm'])->name('pembayaran.transfer.confirm');

// CASH
Route::get('/pembayaran/cash', [PaymentController::class, 'cash'])->name('pembayaran.cash');
Route::post('/pembayaran/cash/done', [PaymentController::class, 'cashDone'])->name('pembayaran.cash.done');

// Selesai
Route::get('/thankyou', [ThankYouController::class, 'show'])->name('thankyou.show');
Route::post('/thankyou/done', [ThankYouController::class, 'done'])->name('thankyou.done');