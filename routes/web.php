<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PurchaseDetailController;
use App\Http\Controllers\ExpenditureController;
use App\Http\Controllers\SaleDetailController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\SaleController;

Route::get('/', fn () => redirect()->route('login'));

    Route::middleware(['auth:sanctum',config('jetstream.auth_session'),'verified',])
    ->group(function () {Route::get('/dashboard', function () {return view('home');})
    ->name('dashboard');
    });

//route setelah login
Route::group(['middleware' => 'auth'], function () {
    Route::get('/categories/data', [CategoryController::class, 'data'])->name('categories.data');
    Route::resource('/categories', CategoryController::class);

    Route::get('/products/data', [ProductController::class, 'data'])->name('products.data');
    Route::post('/products/delete-selected', [ProductController::class, 'deleteSelected'])->name('products.delete_selected');
    Route::post('/products/cetak-barcode', [ProductController::class, 'cetakBarcode'])->name('products.cetak_barcode');
    Route::resource('/products', ProductController::class);

    Route::get('/members/data', [MemberController::class, 'data'])->name('members.data');
    Route::post('/members/cetak-member', [MemberController::class, 'cetakMember'])->name('members.cetak_member');
    Route::resource('/members', MemberController::class);

    Route::get('/suppliers/data', [SupplierController::class, 'data'])->name('suppliers.data');
    Route::resource('/suppliers', SupplierController::class);

    Route::get('/expenditures/data', [ExpenditureController::class, 'data'])->name('expenditures.data');
    Route::resource('/expenditures', ExpenditureController::class);


    Route::get('/sales/{id}/create', [SaleController::class, 'create'])->name('sales.create');
    Route::get('/sales/data', [SaleController::class, 'data'])->name('sales.data');
    Route::resource('/sales', SaleController::class)
        ->except('create');

    Route::resource('/sale_details', SaleDetailController::class)
        ->except('create', 'show', 'edit');

    Route::get('/purchases/data', [PurchaseController::class, 'data'])->name('purchases.data');
    Route::get('/purchases/{id}/create', [PurchaseController::class, 'create'])->name('purchases.create');
    Route::resource('/purchases', PurchaseController::class)
        ->except('create');

    Route::get('/purchase_details/{id}/data', [PurchaseDetailController::class, 'data'])->name('purchase_details.data');
    Route::get('/purchase_details/loadform/{discount}/{total}', [PurchaseDetailController::class, 'loadForm'])->name('purchase_details.load_form');
    Route::resource('/purchase_details', PurchaseDetailController::class)
        ->except('create', 'show', 'edit');
});
