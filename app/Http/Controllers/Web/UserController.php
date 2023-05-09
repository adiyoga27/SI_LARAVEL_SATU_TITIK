<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
     /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = User::all();
            return DataTables::of($data)->addIndexColumn()
                ->addColumn('action', function ($data) {
                    return view('datatables._action_dinamyc', [
                        'model'           => $data,
                        'delete'          => route('user.destroy', $data->id),
                        'edit'          => route('user.edit', $data->id),
                        'confirm_message' =>  'Anda akan menghapus data "' . $data->name . '" ?',
                        'padding'         => '85px',
                    ]);
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('user');
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
            'username' => 'required|unique:users',
            'email' => 'required|unique:users',
            'role' => 'required',
            'hp' => 'required',
            'password' => 'required',
        ]);
        try {
            

            User::create([
                'name' => $request->name,
                'username' => $request->username,
                'email' => $request->email,
                'role' =>$request->role,
                'hp' => $request->hp,
                'password' => Hash::make($request->password),
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
        $user = User::find($id);

        return response()->json([
            'status' => true,
            'data' => $user
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
            $user = User::find($id);

            if(isset($request->password)){
                $user->update([
                    'name' => $request->name,
                    'username' => $request->username,
                    'email' => $request->email,
                    'role' =>$request->role,
                    'hp' => $request->hp,
                    'password' => Hash::make($request->password),
                ]);
            }else{
                $user->update([
                    'name' => $request->name,
                    'username' => $request->username,
                    'email' => $request->email,
                    'role' =>$request->role,
                    'hp' => $request->hp,
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
            $user = User::find($id);
            $user->delete();
            return redirect()->back()->with('success', 'Data berhasil ditambahkan');

        } catch (\Throwable $th) {
            //throw $th;
            return redirect()->back()->withErrors($th->getMessage());
        }
    }
}
