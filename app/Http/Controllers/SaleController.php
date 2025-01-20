<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SaleDetail;
use App\Models\Supplier;
use App\Models\Setting;
use App\Models\Product;
use App\Models\Sale;
use PDF;

class SaleController extends Controller
{

    public function index()
    {
        return view('sale.index');
    }

    public function create()
    {
         // Periksa apakah ada sesi pembelian yang belum selesai
        if (session('sale_id')) {
            // Ambil purchase_id dari sesi
            $existingSale = Sale::find(session('sale_id'));

            if ($existingSale && $existingSale->pay == 0) {
                return redirect()->route('transactions.index')
                    ->with('error', 'Selesaikan transaksi sebelumnya sebelum membuat yang baru.');
            }
        }

        $sales = new Sale();
        $sales->member_id = null;
        $sales->total_item  = 0;
        $sales->total_price = 0;
        $sales->discount    = 0;
        $sales->pay         = 0;
        $sales->accepted    = 0;
        $sales->users_id    = auth()->id();
        $sales->save();

        session(['sale_id' => $sales->sale_id]);



        return redirect()->route('transactions.index');
    }

    public function store(Request $request)
    {
        $sales = Sale::findOrFail($request->sale_id);
        $sales->member_id = $request->member_id;
        $sales->total_item = $request->total_item;
        $sales->total_price = $request->total;
        $sales->discount = $request->discount;
        $sales->accepted = $request->diterima;
        $sales->pay = $request->pay;
        $sales->update();

        $detail = SaleDetail::where('sale_id', $sales->sale_id)->get();
        foreach ($detail as $item) {
            $product = Product::find($item->product_id);
            $product->stock -= $item->qty;
            $product->update();
        }

        return redirect()->route('transactions.selesai');
    }

    public function show($id)
    {
        $detail = SaleDetail::with('products')->where('sale_id', $id)->get();

        return datatables()
            ->of($detail)
            ->addIndexColumn()
            ->addColumn('product_code', function ($detail) {
                return '<span class="label label-success">'. $detail->products->product_code .'</span>';
            })
            ->addColumn('product_name', function ($detail) {
                return $detail->products->product_name;
            })
            ->addColumn('sale_price', function ($detail) {
                return 'Rp. '. format_uang($detail->sale_price);
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

    public function data()
    {
        $sales = Sale::with('member')->orderBy('sale_id', 'desc')->get();

        return datatables()
            ->of($sales)
            ->addIndexColumn()
            ->addColumn('total_item', function ($sale) {
                return format_uang($sale->total_item);
            })
            ->addColumn('total_price', function ($sale) {
                return 'Rp. '. format_uang($sale->total_price);
            })
            ->addColumn('pay', function ($sale) {
                return 'Rp. '. format_uang($sale->pay);
            })
            ->addColumn('date', function ($sale) {
                return tanggal_indonesia($sale->created_at, false);
            })
            ->addColumn('member_code', function ($sale) {
                $member = $sale->member->member_code ?? '';
                return '<span class="label label-success">'. $member .'</spa>';
            })
            ->editColumn('discount', function ($sale) {
                return $sale->discount . '%';
            })
            ->editColumn('kasir', function ($sale) {
                return $sale->user->name ?? '';
            })
            ->addColumn('action', function ($sale) {
                return '
                <div class="btn-group">
                    <button onclick="showDetail(`'. route('sales.show', $sale->sale_id) .'`)" class="btn btn-xs btn-info btn-flat"><i class="fa fa-eye"></i></button>
                    <button onclick="deleteData(`'. route('sales.destroy', $sale->sale_id) .'`)" class="btn btn-xs btn-danger btn-flat"><i class="fa fa-trash"></i></button>
                </div>
                ';
            })
            ->rawColumns(['action', 'member_code'])
            ->make(true);
    }

    public function destroy($id)
    {
        $sales = Sale::find($id);
        $detail    = SaleDetail::where('sale_id', $sales->sale_id)->get();
        foreach ($detail as $item) {
            $produk = Product::find($item->product_id);
            if ($produk) {
                $produk->stock += $item->qty;
                $produk->update();
            }

            $item->delete();
        }

        $sales->delete();

        return response(null, 204);
    }

    public function selesai()
    {
        $setting = Setting::first();

        return view('sale.selesai', compact('setting'));
    }

    public function notaKecil()
    {
        $setting = Setting::first();
        $sales = Sale::find(session('sale_id'));
        if (! $sales) {
            abort(404);
        }
        $detail = SaleDetail::with('products')
            ->where('sale_id', session('sale_id'))
            ->get();
        
        return view('sale.nota_kecil', compact('setting', 'sales', 'detail'));
    }

    public function notaBesar()
    {
        $setting = Setting::first();
        $sales = Sale::find(session('sale_id'));
        if (! $sales) {
            abort(404);
        }
        $detail = SaleDetail::with('products')
            ->where('sale_id', session('sale_id'))
            ->get();

        $pdf = PDF::loadView('sale.nota_besar', compact('setting', 'sales', 'detail'));
        $pdf->setPaper(0,0,609,440, 'potrait');
        return $pdf->stream('Transaction-'. date('Y-m-d-his') .'.pdf');
    }
}
