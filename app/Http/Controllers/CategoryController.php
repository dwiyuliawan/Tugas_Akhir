<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;

class CategoryController extends Controller
{
    public function index()
    {
        return view('category.index');
    }

    public function store(Request $request)
    {
        // $categoris = new Categori();
        // $categoris->categori_name = $request->categori_name;
        // $categoris->save();
        Category::create($request->all());

        return response()->json('Data berhasil di simpan', 200);
    }

    public function show(string $id)
    {
        $categories = Category::find($id);

        return response()->json($categories);
    }

    public function update(Request $request, string $id)
    {
        $categories = Category::find($id);
        $categories->categori_name = $request->categori_name;
        $categories->update();

        return response()->json('Data berhasil di simpan', 200);
    }

    public function destroy(string $id)
    {
        $categoris = Category::find($id);
        $categoris->delete();

        return response(null, 204);
    }

    public function data()
    {
        $categories = Category::orderBy('categori_id', 'desc')->get();

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
