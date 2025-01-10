<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Categori;
use App\Models\Product;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categoris = Categori::all()->pluck('categori_name', 'categori_id');
        return view('product.index', compact('categoris'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $products = Product::latest()->first() ?? new Product();
        $request['product_code'] = 'P'. tambah_nol_didepan((int)$products->product_id +1, 6);

        $products = Product::create($request->all());

        return response()->json('Data berahsil disimpan', 200);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $products = Product::find($id);

        return response()->json($products);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $products = Product::find($id);
        $products->update($request->all());

        return response()->json('Data berhasil disimpan', 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $products = Product::find($id);
        $products->delete();

        return response(null, 204);
    }

    public function data()
    {
        $products = Product::leftJoin('categoris', 'categoris.categori_id', 'products.product_id')
            ->select('products.*', 'categori_name')
            // ->orderBy('kode_produk', 'asc')
            ->get();

        return datatables()
            ->of($products)
            ->addIndexColumn()
            ->addColumn('select_all', function ($product) {
                return '
                    <input type="checkbox" name="product_id[]" value="'. $product->product_id .'">
                ';
            })
            ->addColumn('product_code', function ($product) {
                return '<span class="label label-success">'. $product->product_code .'</span>';
            })
            ->addColumn('purchase_price', function ($product) {
                return format_uang($product->purchase_price);
            })
            ->addColumn('sale_price', function ($product) {
                return format_uang($product->sale_price);
            })
            ->addColumn('stock', function ($product) {
                return format_uang($product->stock);
            })
            ->addColumn('action', function ($product) {
                return '
                <div class="btn-group">
                    <button type="button" onclick="editForm(`'. route('products.update', $product->product_id) .'`)" class="btn  btn-xs btn-info btn-flat"><i class="fa fa-pencil"></i></button>
                    <button type="button" onclick="deleteData(`'. route('products.destroy', $product->product_id) .'`)"   class="btn btn-xs btn-danger btn-flat"><i class="fa fa-trash"></i></button>
                </div>
                ';
            })
            ->rawColumns(['action', 'product_code', 'select_all'])
            ->make(true);
    }

    public function deleteSelected(Request $request)
    {
        foreach ($request->product_id as $id) {
            $products = Product::find($id);
            $products->delete();
        }

        return response(null, 204);
    }
}
