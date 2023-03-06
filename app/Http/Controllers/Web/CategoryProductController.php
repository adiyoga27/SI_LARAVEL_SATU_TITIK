<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class CategoryProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Category::all();
            return DataTables::of($data)->addIndexColumn()
                ->addColumn('action', function ($data) {
                    return view('datatables._action_dinamyc', [
                        'model'           => $data,
                        'delete'          => route('category.destroy', $data->id),
                        'edit'          => route('category.edit', $data->id),
                        'confirm_message' =>  'Anda akan menghapus data "' . $data->name . '" ?',
                        'padding'         => '85px',
                    ]);
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('category');
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
            'name' => 'required',
            'description' => 'required',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'is_active' => 'required',
        ]);
        try {
            $image = $request->file('image');
            $imageName = $image->getClientOriginalName();
            $request->file('image')->storeAs('public/images', $imageName);
         

            Category::create([
                'name' => $request->name,
                'description' => $request->description,
                'image' => 'images'."/".$imageName,
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
        $category = Category::find($id);

        return response()->json([
            'status' => true,
            'data' => $category
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
            $category = Category::find($id);

            if(isset($request->image)){
                $image = $request->file('image');
                $imageName = $image->getClientOriginalName();
                $request->file('image')->storeAs('public/images', $imageName);
                $category->update([
                    'image' => 'images'."/".$imageName,
                    'name' => $request->name,
                    'description' => $request->description,
                    'is_active' => $request->is_active ? true : false,
                ]);
            }else{
                $category->update([
                    'name' => $request->name,
                    'description' => $request->description,
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
            $category = Category::find($id);
            $category->delete();
            return redirect()->back()->with('success', 'Data berhasil ditambahkan');

        } catch (\Throwable $th) {
            //throw $th;
            return redirect()->back()->withErrors($th->getMessage());
        }
    }
}
