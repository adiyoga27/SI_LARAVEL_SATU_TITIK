<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\DiningTable;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Str;
class DiningController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = DiningTable::all();
            return DataTables::of($data)->addIndexColumn()
                ->addColumn('action', function ($data) {
                    return view('datatables._action_dinamyc', [
                        'model'           => $data,
                        'delete'          => route('dining.destroy', $data->id),
                        'edit'          => route('dining.edit', $data->id),
                        'confirm_message' =>  'Anda akan menghapus data "' . $data->name . '" ?',
                        'padding'         => '85px',
                    ]);
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('dining');
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
            'is_active' => 'required',
        ]);
        try {


            DiningTable::create([
                'name' => $request->name,
                'description' => $request->description,
                'is_active' => $request->is_active ? true : false,
                'uuid' => Str::uuid()->toString()
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
        $dining = DiningTable::find($id);

        return response()->json([
            'status' => true,
            'data' => $dining
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
            $dining = DiningTable::find($id);


            $dining->update([
                'name' => $request->name,
                'description' => $request->description,
                'is_active' => $request->is_active ? true : false,
            ]);



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
            $dining = DiningTable::find($id);
            $dining->delete();
            return redirect()->back()->with('success', 'Data berhasil ditambahkan');
        } catch (\Throwable $th) {
            //throw $th;
            return redirect()->back()->withErrors($th->getMessage());
        }
    }
}
