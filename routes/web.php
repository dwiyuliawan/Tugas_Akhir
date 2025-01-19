<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\{
    PurchaseDetailController,
    ExpenditureController,
    SaleDetailController,
    DashboardController,
    PurchaseController,
    SupplierController,
    CategoryController,
    SettingController,
    ProductController,
    MemberController,
    ReportController,
    SaleController,
    UserController,
};

Route::get('/', fn () => redirect()->route('login'));


//route setelah login
Route::group(['middleware' => 'auth'], function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::group(['middleware' => 'level:1'], function () {
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

        Route::get('/purchases/data', [PurchaseController::class, 'data'])->name('purchases.data');
        Route::get('/purchases/{id}/create', [PurchaseController::class, 'create'])->name('purchases.create');
        Route::resource('/purchases', PurchaseController::class)
            ->except('create');

        Route::get('/purchase_details/{id}/data', [PurchaseDetailController::class, 'data'])->name('purchase_details.data');
        Route::get('/purchase_details/loadform/{discount}/{total}', [PurchaseDetailController::class, 'loadForm'])->name('purchase_details.load_form');
        Route::resource('/purchase_details', PurchaseDetailController::class)
            ->except('create', 'show', 'edit');


        Route::get('sales/data', [SaleController::class, 'data'])->name('sales.data');
        Route::get('sales', [SaleController::class, 'index'])->name('sales.index');
        Route::get('sales/{id}', [SaleController::class, 'show'])->name('sales.show');
        Route::delete('sales/{id}', [SaleController::class, 'destroy'])->name('sales.destroy');
    });

    Route::group(['middleware' => 'level:1,2'], function () {
        Route::get('transactions/new', [SaleController::class, 'create'])->name('transactions.new');
        Route::post('transactions/simpan', [SaleController::class, 'store'])->name('transactions.simpan');
        Route::get('/transactions/selesai', [SaleController::class, 'selesai'])->name('transactions.selesai');
        Route::get('/transactions/nota-kecil', [SaleController::class, 'notaKecil'])->name('transactions.nota_kecil');
        Route::get('/transactions/nota-besar', [SaleController::class, 'notaBesar'])->name('transactions.nota_besar');

        Route::get('transactions/{id}/data', [SaleDetailController::class, 'data'])->name('transactions.data');
        Route::get('transactions/loadform/{discout}/{total}/{diterima}', [SaleDetailController::class, 'loadForm'])->name('transactions.loadform');
        Route::resource('/transactions', SaleDetailController::class)
        ->except('create','show','edit');
    });

    Route::group(['middleware' => 'level:1'], function () {
        Route::get('/report', [ReportController::class, 'index'])->name('report.index');
        Route::get('/report/data/{awal}/{akhir}', [ReportController::class, 'data'])->name('report.data');
        Route::get('/report/pdf/{awal}/{akhir}', [ReportController::class, 'exportPDF'])->name('report.export_pdf');

        Route::get('/users/data', [UserController::class, 'data'])->name('users.data');
        Route::resource('/users', UserController::class);

        Route::get('/setting', [SettingController::class, 'index'])->name('setting.index');
        Route::get('/setting/first', [SettingController::class, 'show'])->name('setting.show');
        Route::post('/setting', [SettingController::class, 'update'])->name('setting.update');
    });

    Route::group(['middleware' => 'level:1,2'], function () {
        Route::get('/profil', [UserController::class, 'profil'])->name('user.profil');
        Route::post('/profil', [UserController::class, 'updateProfil'])->name('user.update_profil');
    });
});
