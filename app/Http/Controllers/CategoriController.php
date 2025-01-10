<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Categori;

class CategoriController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('categori.index');
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
        // $categoris = new Categori();
        // $categoris->categori_name = $request->categori_name;
        // $categoris->save();
        Categori::create($request->all());

        return response()->json('Data berhasil di simpan', 200);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $categories = Categori::find($id);

        return response()->json($categories);
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
        $categories = Categori::find($id);
        $categories->categori_name = $request->categori_name;
        $categories->update();

        return response()->json('Data berhasil di simpan', 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $categoris = Categori::find($id);
        $categoris->delete();

        return response(null, 204);
    }

    public function data()
    {
        $categories = Categori::orderBy('categori_id', 'desc')->get();

        return datatables()
            ->of($categories)
            ->addIndexColumn()
            ->addColumn('action', function ($categori) {
                return '
                <div class="btn-group">
                    <button onclick="editForm(`'. route('categories.update', $categori->categori_id) .'`)"  class="btn btn-xs btn-info btn-flat"><i class="fa fa-pencil"></i></button>
                    <button onclick="deleteData(`'. route('categories.destroy', $categori->categori_id) .'`)" class="btn btn-xs btn-danger btn-flat"><i class="fa fa-trash"></i></button>
                </div>
                ';
            })
            ->rawColumns(['action'])
            ->make(true);
    }
}
