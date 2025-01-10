<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CategoriController;
use App\Http\Controllers\ProductController;

Route::get('/', fn () => redirect()->route('login'));

    Route::middleware(['auth:sanctum',config('jetstream.auth_session'),'verified',])
    ->group(function () {Route::get('/dashboard', function () {return view('home');})
    ->name('dashboard');
    });

//route setelah login
Route::group(['middleware' => 'auth'], function () {
    Route::get('/categoris/data', [CategoriController::class, 'data'])->name('categoris.data');
    Route::resource('/categoris', CategoriController::class);

    Route::get('/products/data', [ProductController::class, 'data'])->name('products.data');
    Route::post('/products/delete-selected', [ProductController::class, 'deleteSelected'])->name('products.delete_selected');
    Route::resource('/products', ProductController::class);
});
