<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{

    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required',
            'password' => 'required',
        ]);

        $credentials = $request->only('username', 'password');
        if (!auth()->attempt($credentials)) {
            return response(['error_message' => 'Incorrect Details. 
            Please try again']);
        }

        $token = auth()->user()->createToken('API Token')->plainTextToken;
        $user = User::where('id', auth()->user()->id)->first();
        $user->token = $token;
        return response()->json([
            'status' => true,
            'message' => 'success',
            'data' => $user
        ]);
    }
    public function registration(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'username' => 'required|unique:users',
            'email' => 'required|unique:users',
            'hp' => 'required',
            'password' => 'required',
        ]);
        try {
            

            $user = User::create([
                'name' => $request->name,
                'username' => $request->username,
                'email' => $request->email,
                'role' =>'customer',
                'hp' => $request->hp,
                'password' => Hash::make($request->password),
            ]);
            return response()->json([
                'status' => true,
                'message' => 'success',
                'data' => $user
            ]);
        } catch (\Throwable $th) {
            //throw $th;
            return response()->json([
                'status' => false,
                'message' => "Gagal registrasi : ".$th->getMessage()
            ], 422);
        }
    }
}
