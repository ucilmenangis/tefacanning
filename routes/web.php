<?php

use App\Http\Controllers\OrderPdfController;
use App\Models\Batch;
use App\Models\Product;
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
    $products = Product::where('is_active', true)->get();
    $batches = Batch::where('status', 'open')->latest('event_date')->take(5)->get();
    return view('welcome', compact('products', 'batches'));
});

// Order PDF routes
Route::get('/order/{order}/pdf', [OrderPdfController::class, 'download'])
    ->name('order.pdf.download');
Route::get('/order/{order}/pdf/preview', [OrderPdfController::class, 'stream'])
    ->name('order.pdf.stream');
