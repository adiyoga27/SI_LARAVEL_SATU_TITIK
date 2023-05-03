<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProductResource;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class TransactionController extends Controller
{
    public function product(Request $request, $category_id)
    {
        $products = Product::where('category_id', $category_id)->get();
        return response()->json([
            'status' => true,
            'message' => 'Data berhasil diambil',
            'data' => ProductResource::collection($products)
        ]);
    }

    public function detailProduct(Request $request, $id)
    {
        $product = Product::find($id);
        return response()->json([
            'status' => true,
            'message' => 'Data berhasil diambil',
            'data' =>  new ProductResource($product)
        ]);
    }

    public function createTranscation(Request $request)
    {
        $request->validate([
            'dinning_table_id' => 'required',
            'customer_name' => 'required',
            'customer_hp' => 'required'
        ]);
        try {
            DB::beginTransaction();
            $uuid = Str::uuid()->toString();
            $transaction = Order::create([
                'dinning_table_id' => $request->dinning_table_id,
                'customer_name' => $request->customer_name,
                'customer_hp' => $request->customer_hp,
                'uuid' => $uuid,
            ]);
            DB::commit();
            return response()->json([
                'status' => true,
                'message' => 'Data berhasil diambil',
                'data' => $transaction
            ]);
        } catch (\Throwable $th) {
            //throw $th;
            DB::rollBack();
            return response()->json([
                'status' => false,
                'message' => 'Data gagal diambil',
                'data' => $th->getMessage()
            ]);
        }
    }

    public function addCart(Request $request)
    {
        
    }
}
