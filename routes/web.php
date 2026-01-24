<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| PELANGGAN
|--------------------------------------------------------------------------
*/
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


/*
|--------------------------------------------------------------------------
| BACKOFFICE 
|--------------------------------------------------------------------------
*/
use App\Http\Controllers\BackofficeAuthSimpleController;
use App\Http\Controllers\BackofficeDashboardController;
use App\Http\Middleware\BackofficeAuth;
use App\Http\Controllers\BackofficeOrdersController;

// LOGIN BACKOFFICE
Route::get('/backoffice/login', [BackofficeAuthSimpleController::class, 'showLogin'])->name('backoffice.login');
Route::post('/backoffice/login', [BackofficeAuthSimpleController::class, 'login'])->name('backoffice.login.post');
Route::post('/backoffice/logout', [BackofficeAuthSimpleController::class, 'logout'])->name('backoffice.logout');

// AREA BACKOFFICE (WAJIB LOGIN)

Route::middleware([BackofficeAuth::class])->prefix('backoffice')->group(function () {
    Route::get('/', [BackofficeDashboardController::class, 'index'])->name('backoffice.dashboard');

// ORDERS
    Route::get('/orders', [BackofficeOrdersController::class, 'index'])->name('backoffice.orders.index');
    Route::get('/orders/{order}', [BackofficeOrdersController::class, 'show'])->name('backoffice.orders.show');

// ACTION STATUS
    Route::post('/orders/{order}/verify', [BackofficeOrdersController::class, 'verify'])->name('backoffice.orders.verify');
    Route::post('/orders/{order}/done', [BackofficeOrdersController::class, 'done'])->name('backoffice.orders.done');

// PRINT
    Route::get('/orders/{order}/print/receipt', [BackofficeOrdersController::class, 'printReceipt'])->name('backoffice.orders.print.receipt');
    Route::get('/orders/{order}/print/kitchen', [BackofficeOrdersController::class, 'printKitchen'])->name('backoffice.orders.print.kitchen');

    Route::view('/reports', 'backoffice.reports')->name('backoffice.reports.index');
});

// Manajemen Meja
use App\Http\Controllers\BackofficeTablesController;

Route::middleware([BackofficeAuth::class])->prefix('backoffice')->group(function () {

// TABLES (Backoffice)
Route::get('/tables', [BackofficeTablesController::class, 'index'])
        ->name('backoffice.tables.index');

Route::post('/tables/{table}/toggle', [BackofficeTablesController::class, 'toggle'])
        ->name('backoffice.tables.toggle');
});

// Manajemen Menu
use App\Http\Controllers\BackofficeMenuToggleController;

Route::middleware([BackofficeAuth::class])
    ->prefix('backoffice')
    ->group(function () {

Route::get('/menu', [BackofficeMenuToggleController::class, 'index'])
            ->name('backoffice.menu.index');

Route::post('/menu/{menu}/toggle', [BackofficeMenuToggleController::class, 'toggle'])
            ->name('backoffice.menu.toggle');
});

// Lihat Laporan Penjualan
use App\Http\Controllers\BackofficeReportController;

Route::middleware([BackofficeAuth::class])->prefix('backoffice')->group(function () {
    Route::get('/reports', [BackofficeReportController::class, 'index'])->name('backoffice.reports.index');

// tombol PDF
    Route::get('/reports/print', [BackofficeReportController::class, 'print'])->name('backoffice.reports.print');

// tombol Excel
Route::get('/reports/excel', [BackofficeReportController::class, 'exportCsv'])->name('backoffice.reports.excel');
});