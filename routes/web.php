<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CategoriController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\MemberController;

Route::get('/', fn () => redirect()->route('login'));

    Route::middleware(['auth:sanctum',config('jetstream.auth_session'),'verified',])
    ->group(function () {Route::get('/dashboard', function () {return view('home');})
    ->name('dashboard');
    });

//route setelah login
Route::group(['middleware' => 'auth'], function () {
    Route::get('/categories/data', [CategoriController::class, 'data'])->name('categories.data');
    Route::resource('/categories', CategoriController::class);

    Route::get('/products/data', [ProductController::class, 'data'])->name('products.data');
    Route::post('/products/delete-selected', [ProductController::class, 'deleteSelected'])->name('products.delete_selected');
    Route::post('/products/cetak-barcode', [ProductController::class, 'cetakBarcode'])->name('products.cetak_barcode');
    Route::resource('/products', ProductController::class);

    Route::get('/members/data', [MemberController::class, 'data'])->name('members.data');
    Route::post('/members/cetak-member', [MemberController::class, 'cetakMember'])->name('members.cetak_member');
    Route::resource('/members', MemberController::class);
});
