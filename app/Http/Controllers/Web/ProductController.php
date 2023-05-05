<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $categories = Category::all();
        if ($request->ajax()) {
            $data = Product::all();
            return DataTables::of($data)->addIndexColumn()
                ->addColumn('action', function ($data) {
                    return view('datatables._action_dinamyc', [
                        'model'           => $data,
                        'delete'          => route('product.destroy', $data->id),
                        'edit'          => route('product.edit', $data->id),
                        'confirm_message' =>  'Anda akan menghapus data "' . $data->name . '" ?',
                        'padding'         => '85px',
                    ]);
                })
                ->addColumn('category', function ($data) {
                    return $data->category->name;
                })
                ->addColumn('discount', function ($data) {
                    return $data->discount ?? 0;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('product', compact(['categories']));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name' => 'required',
            'description' => 'required',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'price' => 'required',
            'discount' => 'nullable',
            'is_active' => 'required',
        ]);

        try {
            $image = $request->file('image');
            $imageName = $image->getClientOriginalName();
            $request->file('image')->storeAs('public/product', $imageName);


            Product::create([
                'category_id' => $request->category_id,
                'name' => $request->name,
                'description' => $request->description,
                'image' => 'product' . "/" . $imageName,
                'price' => $request->price,
                'discount' => $request->discount ?? NULL,
                'is_active' => $request->is_active ? true : false,
            ]);
            return redirect()->back()->with('success', 'Data berhasil ditambahkan');
        } catch (\Throwable $th) {
            //throw $th;
            return redirect()->back()->withErrors($th->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $product = Product::find($id);

        return response()->json([
            'status' => true,
            'data' => $product
        ]);
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
        try {
            $product = Product::find($id);

            if (isset($request->image)) {
                $image = $request->file('image');
                $imageName = $image->getClientOriginalName();
                $request->file('image')->storeAs('public/product', $imageName);
                $product->update([
                    'image' => 'product' . "/" . $imageName,
                    'name' => $request->name,
                    'category_id' => $request->category_id,
                    'description' => $request->description,
                    'price' => $request->price,
                    'discount' => $request->discount ?? NULL,
                    'is_active' => $request->is_active ? true : false,
                ]);
            } else {
                $product->update([
                    'category_id' => $request->category_id,

                    'name' => $request->name,
                    'description' => $request->description,
                    'price' => $request->price,
                    'discount' => $request->discount ?? NULL,
                    'is_active' => $request->is_active ? true : false,
                ]);
            }



            return response()->json([
                'status' => true,
                'message' => 'Data berhasil diubah'
            ]);
        } catch (\Throwable $th) {
            //throw $th;
            return response()->json([
                'status' => false,
                'message' => $th
            ]);
            return redirect()->back()->withErrors($th->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $product = Product::find($id);
            $product->delete();
            return redirect()->back()->with('success', 'Data berhasil ditambahkan');
        } catch (\Throwable $th) {
            //throw $th;
            return redirect()->back()->withErrors($th->getMessage());
        }
    }
}
