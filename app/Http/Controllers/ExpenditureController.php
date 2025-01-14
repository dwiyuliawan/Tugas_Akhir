<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Expenditure;

class ExpenditureController extends Controller
{
    public function index()
    {
        return view('expenditure.index');
    }

    public function store(Request $request)
    {
        $expenditure = Expenditure::create($request->all());

        return response()->json('Data berhasil disimpan', 200);
    }

    public function show($id)
    {
        $expenditure = Expenditure::find($id);

        return response()->json($expenditure);
    }

    public function update(Request $request, $id)
    {
        $expenditure = Expenditure::find($id)->update($request->all());

        return response()->json('Data berhasil disimpan', 200);
    }

    public function destroy($id)
    {
        $expenditure = Expenditure::find($id)->delete();

        return response(null, 204);
    }

    public function data()
    {
        $expenditures = Expenditure::orderBy('expenditure_id', 'desc')->get();

        return datatables()
            ->of($expenditures)
            ->addIndexColumn()
            ->addColumn('created_at', function ($expenditure) {
                return tanggal_indonesia($expenditure->created_at, false);
            })
            ->addColumn('nominal', function ($expenditure) {
                return format_uang($expenditure->nominal);
            })
            ->addColumn('action', function ($expenditure) {
                return '
                <div class="btn-group">
                    <button type="button" onclick="editForm(`'. route('expenditures.update', $expenditure->expenditure_id) .'`)" class="btn btn-xs btn-info btn-flat"><i class="fa fa-pencil"></i></button>
                    <button type="button" onclick="deleteData(`'. route('expenditures.destroy', $expenditure->expenditure_id) .'`)" class="btn btn-xs btn-danger btn-flat"><i class="fa fa-trash"></i></button>
                </div>
                ';
            })
            ->rawColumns(['action'])
            ->make(true);
    }
}
