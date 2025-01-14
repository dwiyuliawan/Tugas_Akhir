<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\PurchaseDetail;
use Illuminate\Http\Request;
use App\Models\Supplier;
use App\Models\Purchase;
use App\Models\Product;

class PurchaseDetailController extends Controller
{
    public function index()
    {
        $purchase_id = session('purchase_id');
        $products = Product::orderBy('product_name')->get();
        $suppliers = Supplier::find(session('supplier_id'));
        $discount = Purchase::find($purchase_id)->discount ?? 0;
        if (! $suppliers) {
            abort(404);
        }

        return view('purchase_detail.index', compact('purchase_id', 'products', 'suppliers', 'discount'));
    }

    public function store(Request $request)
    {
        $products = Product::where('product_id', $request->product_id)->first();
        if (! $products) {
            return response()->json('Data gagal disimpan', 400);
        }

        $details = new PurchaseDetail();
        $details->purchase_id = $request->purchase_id;
        $details->product_id = $products->product_id;
        $details->purchase_price = $products->purchase_price;
        $details->qty = 1;
        $details->subtotal = $products->purchase_price;
        $details->save();

        return response()->json('Data berhasil disimpan', 200);
    }

    public function update(Request $request, $id)
    {
        $details = PurchaseDetail::find($id);
        $details->qty = $request->qty;
        $details->subtotal = $details->purchase_price * $request->qty;
        $details->update();
    }

    public function destroy($id)
    {
        $details = PurchaseDetail::find($id);
        $details->delete();

        return response(null, 204);
    }


    public function data($id)
    {
        $detail = PurchaseDetail::with('products')
            ->where('purchase_id', $id)
            ->get();
        $data = array();
        $total = 0;
        $total_item = 0;

        foreach ($detail as $item) {
            $row = array();
            $row['product_code'] = '<span class="label label-success">'. $item->products['product_code'] .'</span>';
            $row['product_name'] = $item->products['product_name'];
            $row['purchase_price']  = 'Rp. '. format_uang($item->purchase_price);
            $row['qty']      = '<input type="number" class="form-control input-sm quantity" data-id="'. $item->purchase_detail_id .'" value="'. $item->qty .'">';
            $row['subtotal']    = 'Rp. '. format_uang($item->subtotal);
            $row['action']        = '<div class="btn-group">
                                    <button onclick="deleteData(`'. route('purchase_details.destroy', $item->purchase_detail_id) .'`)" class="btn btn-xs btn-danger btn-flat"><i class="fa fa-trash"></i></button>
                                </div>';
            $data[] = $row;

            $total += $item->purchase_price * $item->qty;
            $total_item += $item->qty;
        }
        $data[] = [
            'product_code' => '
                <div class="total hide">'. $total .'</div>
                <div class="total_item hide">'. $total_item .'</div>',
            'product_name'    => '',
            'purchase_price'  => '',
            'qty'             => '',
            'subtotal'        => '',
            'action'          => '',
        ];

        return datatables()
            ->of($data)
            ->addIndexColumn()
            ->rawColumns(['action', 'product_code', 'qty'])
            ->make(true);
    }

    public function loadForm($discount, $total)
    {
        $bayar = $total - ($discount / 100 * $total);
        $data  = [
            'totalrp' => format_uang($total),
            'pay' => $bayar,
            'bayarrp' => format_uang($bayar),
            'terbilang' => ucwords(terbilang($bayar). ' Rupiah')
        ];

        return response()->json($data);
    }
}
