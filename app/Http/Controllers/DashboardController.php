<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Expenditure;
use App\Models\Category;
use App\Models\Purchase;
use App\Models\Supplier;
use App\Models\Product;
use App\Models\Member;
use App\Models\Sale;

class DashboardController extends Controller
{

    public function index()
    {
        $categoris = Category::count();
        $products = Product::count();
        $suppliers = Supplier::count();
        $members = Member::count();

        $tanggal_awal = date('Y-m-01');
        $tanggal_akhir = date('Y-m-d');

        $data_tanggal = array();
        $data_pendapatan = array();

        while (strtotime($tanggal_awal) <= strtotime($tanggal_akhir)) {
            $data_tanggal[] = (int) substr($tanggal_awal, 8, 2);

            $total_penjualan = Sale::where('created_at', 'LIKE', "%$tanggal_awal%")->sum('pay');
            $total_pembelian = Purchase::where('created_at', 'LIKE', "%$tanggal_awal%")->sum('pay');
            $total_pengeluaran = Expenditure::where('created_at', 'LIKE', "%$tanggal_awal%")->sum('nominal');

            $pendapatan = $total_penjualan - $total_pembelian - $total_pengeluaran;
            $data_pendapatan[] += $pendapatan;

            $tanggal_awal = date('Y-m-d', strtotime("+1 day", strtotime($tanggal_awal)));
        }

        if (auth()->user()->level == 1) {
        return view('admin.dashboard', compact('categoris', 'products', 'suppliers', 'members', 'tanggal_awal', 'tanggal_akhir', 'data_tanggal', 'data_pendapatan'));
        }else{
            return view('kasir.dashboard');
        }
    }
}
