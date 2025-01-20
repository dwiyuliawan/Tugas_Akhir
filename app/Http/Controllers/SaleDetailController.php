<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\SaleDetail;
use App\Models\Product;
use App\Models\Setting;
use App\Models\Member;
use App\Models\Sale;

class SaleDetailController extends Controller
{
    public function index()
    {
        $products = Product::orderBy('product_name')->get();
        $members = Member::orderBy('name')->get();
        $discount = Setting::first()->discount ?? 0;

        if ($sale_id = session('sale_id')) {
            $sales = Sale::find($sale_id);
            $member = $sales->member ?? new Member();
            return view('sale_detail.index', compact('products', 'members', 'discount', 'sale_id','member', 'sales'));
        }else{
            
            if (auth()->user()->level == 1) {
                return redirect()->route('transactions.new');
            }else {
                return redirect()->route('home');
            }
        }
    }

    public function store(Request $request)
    {
        $products = Product::where('product_id', $request->product_id)->first();
        if (! $products) {
            return response()->json('Data gagal disimpan', 400);
        }

        $details = new SaleDetail();
        $details->sale_id = $request->sale_id;
        $details->product_id = $products->product_id;
        $details->sale_price = $products->sale_price;
        $details->qty = 1;
        $details->discount = 0;
        $details->subtotal = $products->sale_price;
        $details->save();

        return response()->json('Data berhasil disimpan', 200);
    }

    public function update(Request $request, $id)
    {
        $details = SaleDetail::find($id);
        $details->qty = $request->qty;
        $details->subtotal = $details->sale_price *  $request->qty;
        $details->update();
    }

    public function destroy($id)
    {
        $details = SaleDetail::find($id);
        $details->delete();

        return response(null, 204);
    }

    public function data($id)
    {
        $detail = SaleDetail::with('products')
            ->where('sale_id', $id)
            ->get();
        $data = array();
        $total = 0;
        $total_item = 0;

        foreach ($detail as $item) {
            $row = array();
            $row['product_code'] = '<span class="label label-success">'. $item->products['product_code'] .'</span>';
            $row['product_name'] = $item->products['product_name'];
            $row['sale_price']  = 'Rp. '. format_uang($item->sale_price);
            $row['qty']      = '<input type="number" class="form-control input-sm quantity" data-id="'. $item->sale_detail_id .'" value="'. $item->qty .'">';
            $row['discount']    = $item->discount . '%';
            $row['subtotal']    = 'Rp. '. format_uang($item->subtotal);
            $row['action']        = '<div class="btn-group">
                                    <button onclick="deleteData(`'. route('transactions.destroy', $item->sale_detail_id) .'`)" class="btn btn-xs btn-danger btn-flat"><i class="fa fa-trash"></i></button>
                                </div>';
            $data[] = $row;

            $total += $item->sale_price * $item->qty;
            $total_item += $item->qty;
        }
        $data[] = [
            'product_code' => '
                <div class="total hide">'. $total .'</div>
                <div class="total_item hide">'. $total_item .'</div>',
            'product_name'    => '',
            'sale_price'  => '',
            'qty'             => '',
            'discount'        => '',
            'subtotal'        => '',
            'action'          => '',
        ];

        return datatables()
            ->of($data)
            ->addIndexColumn()
            ->rawColumns(['action', 'product_code', 'qty'])
            ->make(true);
    }

    public function loadForm($discount = 0, $total = 0, $diterima = 0)
    {
        $bayar = $total - ($discount / 100 * $total);
        $kembali =($diterima != 0) ? $diterima - $bayar : 0;
        $data  = [
            'totalrp' => format_uang($total),
            'pay' => $bayar,
            'bayarrp' => format_uang($bayar),
            'terbilang' => ucwords(terbilang($bayar). ' Rupiah'),
            'kembalirp' => format_uang($kembali),
            'kembali_terbilang' => ucwords(terbilang($kembali). ' Rupiah'),
        ];

        return response()->json($data);
    }

}
