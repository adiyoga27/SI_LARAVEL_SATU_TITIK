<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\OrderResource;
use App\Models\DiningTable;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class OrderController extends Controller
{
    public function reservasi(Request $request)
    {
        $payload = $request->validate([
            'table_uuid' => 'required',
            'customer_name' => 'required',
            'customer_phone' => 'required'
        ]);
        try {
            $dinningTable = DiningTable::where('uuid', $request->table_uuid)->first();

            $orders = Order::where('table_id', $dinningTable->id)->where('status', 'pending')->first();
            if (!$orders) {
                $order = Order::create([
                    'table_id' => $dinningTable->id,
                    'customer_name' =>  $request->customer_name,
                    'customer_hp' => $request->customer_phone,
                    'total_price' => 0,
                    'status' => 'pending',
                    'payment_method' => 'cash',
                    'uuid' => Str::uuid()->toString()
                ]);

                return response()->json([
                    'status' => true,
                    'message' => 'Anda berhasil reservasi tempat ',
                    'data' => Order::where('uuid', $order->uuid)->first()
                ]);
            }
            return response()->json([
                'status' => true,
                'message' => 'Anda memiliki orderan belum selesai',
                'data' => $orders
            ]);
        } catch (\Throwable $th) {
            throw $th;
        }

    }

    public function addCart(Request $request)
    {
        try {
            $request->validate([
                'uuid' => 'required',
                'product_id' => 'required',
                'quantity' => 'required',
            ]);
    
            $order = Order::where('uuid', $request->uuid)->first();
            if(!$order){
                return response()->json([
                    'status' => false,
                    'message' => 'Anda tidak memiliki order',
                ], 400);
            }
    
            $product = Product::where('id', $request->product_id)->first();
            if(!$product){
                return response()->json([
                    'status' => false,
                    'message' => 'Product yang anda pesan tidak ada',
                ], 400);
            }
    
            $check = OrderDetail::where('order_id' , $order->id)->where('product_id', $request->product_id)->first();
            if($check){
                $qty = $request->quantity + $check->quantity; 
                $check->update([
                    'quantity' => $qty,
                    'total_price' => $qty * $product->price
                ]);
            }else{
                OrderDetail::create([
                    'order_id' => $order->id,
                    'product_id' => $request->product_id,
                    'quantity' => $request->quantity,
                    'price' => $product->price,
                    'total_price' => $request->quantity * $product->price
                ]);
            }
           
    
            Order::where('id', $order->id)->update([
                'total_price' => $this->calculate($order->id),
                'tax' => 0,
                'discount' => 0,
                'total_payment' => $this->calculate($order->id)
            ]);
            return response()->json([
                'status' => true,
                'message' => 'Pesan anda berhasil, mohon menunggu pesanan anda !',
            ]);
        } catch (\Throwable $th) {
            throw $th;
        }
      

    }

    public function order(Request $request, $uuid)
    {
        $order = Order::where('uuid', $uuid)->first();
        return response()->json([
            'status' => true,
            'message' => 'Berikut Pesanan Anda',
            'data' => new OrderResource($order)
        ]);
    }

    public function calculate($order_id)
    {
        $totalTrx = OrderDetail::where('order_id', $order_id)->sum('total_price');

        return $totalTrx;
    }
}
