<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SaleDetailController extends Controller
{
    public function index()
    {
        $sale_id = session('sale_id');
        $products = Product::orderBy('name')->get();
        $suppliers = Supplier::find(session('supplier_id'));
        if (! $suppliers) {
            abort(404);
        }

        return view('saleDetail.index', compact('sale_id', 'products', 'suppliers'));
    }
}
