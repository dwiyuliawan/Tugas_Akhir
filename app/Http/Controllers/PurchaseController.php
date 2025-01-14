<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\PurchaseDetail;
use Illuminate\Http\Request;
use App\Models\Supplier;
use App\Models\Purchase;
use App\Models\Product;

class PurchaseController extends Controller
{
    public function index()
    {
        $suppliers = Supplier::orderBy('name')->get();
        return view('purchase.index', compact('suppliers'));
    }

    public function create($id)
    {
        $purchases = new Purchase();
        $purchases->supplier_id = $id;
        $purchases->total_item  = 0;
        $purchases->total_price = 0;
        $purchases->discount    = 0;
        $purchases->pay         = 0;
        $purchases->save();

        session(['purchase_id'=> $purchases->purchase_id]);
        session(['supplier_id'=> $purchases->supplier_id]);

        return redirect()->route('purchase_details.index');
    }

    public function store(Request $request)
    {
        $purchase = Purchase::findOrFail($request->purchase_id);
        $purchase->total_item = $request->total_item;
        $purchase->total_price = $request->total;
        $purchase->discount = $request->discount;
        $purchase->pay = $request->pay;
        $purchase->update();

        $detail = PurchaseDetail::where('purchase_id', $purchase->purchase_id)->get();
        foreach ($detail as $item) {
            $product = Product::find($item->product_id);
            $product->stock += $item->qty;
            $product->update();
        }

        return redirect()->route('purchases.index');
    }

    public function show($id)
    {
        $detail = PurchaseDetail::with('products')->where('purchase_id', $id)->get();

        return datatables()
            ->of($detail)
            ->addIndexColumn()
            ->addColumn('product_code', function ($detail) {
                return '<span class="label label-success">'. $detail->products->product_code .'</span>';
            })
            ->addColumn('product_name', function ($detail) {
                return $detail->products->product_name;
            })
            ->addColumn('purchase_price', function ($detail) {
                return 'Rp. '. format_uang($detail->purchase_price);
            })
            ->addColumn('qty', function ($detail) {
                return format_uang($detail->qty);
            })
            ->addColumn('subtotal', function ($detail) {
                return 'Rp. '. format_uang($detail->subtotal);
            })
            ->rawColumns(['product_code'])
            ->make(true);
    }

    public function destroy($id)
    {
        $purchase = Purchase::find($id);
        $detail    = PurchaseDetail::where('purchase_id', $purchase->purchase_id)->get();
        foreach ($detail as $item) {
            $product = Product::find($item->product_id);
            if ($product) {
                $product->stock -= $item->qty;
                $product->update();
            }
            $item->delete();
        }

        $purchase->delete();

        return response(null, 204);
    }

    public function data()
    {
        $purchases = Purchase::orderBy('purchase_id', 'desc')->get();

        return datatables()
            ->of($purchases)
            ->addIndexColumn()
            ->addColumn('total_item', function ($purchase) {
                return format_uang($purchase->total_item);
            })
            ->addColumn('total_price', function ($purchase) {
                return 'Rp. '. format_uang($purchase->total_price);
            })
            ->addColumn('pay', function ($purchase) {
                return 'Rp. '. format_uang($purchase->pay);
            })
            ->addColumn('date', function ($purchase) {
                return tanggal_indonesia($purchase->created_at, false);
            })
            ->addColumn('supplier', function ($purchase) {
                return $purchase->supplier->name;
            })
            ->editColumn('discount', function ($purchase) {
                return $purchase->discount . '%';
            })
            ->addColumn('action', function ($purchase) {
                return '
                <div class="btn-group">
                    <button onclick="showDetail(`'. route('purchases.show', $purchase->purchase_id) .'`)" class="btn btn-xs btn-info btn-flat"><i class="fa fa-eye"></i></button>
                    <button onclick="deleteData(`'. route('purchases.destroy', $purchase->purchase_id) .'`)" class="btn btn-xs btn-danger btn-flat"><i class="fa fa-trash"></i></button>
                </div>
                ';
            })
            ->rawColumns(['action'])
            ->make(true);
    }
}
