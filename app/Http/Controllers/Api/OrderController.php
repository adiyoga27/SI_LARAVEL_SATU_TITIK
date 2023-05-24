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

    // public function checkBooked(Request $request)
    // {
    //     $order = 
    // }
    public function bookTable(Request $request)
    {
        $dinningTable = DiningTable::all();
        foreach ($dinningTable as $dt) {
            $order = Order::where('table_id', $dt['id'])->where(fn($q) => $q->where('status', 'pending')->orWhere('status', 'progress'))->count();

            $tables[] = array(
                'name' => $dt['name'],
                'description' => $dt['description'],
                'uuid' => $dt['uuid'],
                'is_active' => $order > 0 ? FALSE : TRUE,
            );
        }

        return response()->json([
            'status' => true,
            'message' => 'success',
            'data' => $tables
        ]);
    }
    public function reservasi(Request $request)
    {
        $payload = $request->validate([
            'table_uuid' => 'required',
            'customer_name' => 'required',
            'customer_phone' => 'required'
        ]);
        try {
            $dinningTable = DiningTable::where('uuid', $request->table_uuid)->first();
            if(!$dinningTable){
                return response()->json([
                    'status' => false,
                    'message' => 'Meja yang anda scan tidak ditemukan '.$request->table_uuid,
                ], 400);
            }
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
                $totalPrice =   $qty * $product->price;
                $discount = $totalPrice * ($product->discount / 100);
                $check->update([
                    'quantity' => $qty,
                    'discount' => $discount,
                    'total_price' => $totalPrice
                ]);
            }else{
                $totalPrice =  $request->quantity * $product->price;
                $discount = $totalPrice * ($product->discount / 100);
                OrderDetail::create([
                    'order_id' => $order->id,
                    'product_id' => $request->product_id,
                    'quantity' => $request->quantity,
                    'price' => $product->price,
                    'discount' => $discount,
                    'total_price' => $totalPrice-$discount
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
        $order = Order::where('uuid', $uuid)->where(fn($q)=> $q->where('status', 'pending')->orWhere('status', 'progress'))->first();
        if($order){
            return response()->json([
                'status' => true,
                'message' => 'Berikut Pesanan Anda',
                'data' => new OrderResource($order)
            ]);
        }

        return response()->json([
            'status' => false,
            'message' => 'Tidak Ada pesanan'
        ], 400);
      
    }

    public function calculate($order_id)
    {
        $totalTrx = OrderDetail::where('order_id', $order_id)->sum('total_price');

        return $totalTrx;
    }

    public function checkout(Request $request, $uuid)
    {
        try {
            // Order::where('uuid', $uuid)->update([
            //     'status' => 'progress'
            // ]);
              $order =  Order::where('uuid', $uuid)->first();
                $orderDetail = OrderDetail::where('order_id', $order->id)->where('status', 'pending')->get();
                $orderDetail->update([
                    'status' => 'waiting'
                ]);
            return response()->json([
                'status' => true,
                'message' => 'Pesanan anda sedang di proses'
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => true,
                'message' => 'Gagal memesan !'
            ]);
        }
      
    }

    public function deleteCart(Request $request, $id)
    {
        try {
                OrderDetail::where('id', $id)->where('status', 'pending')->delete();
            return response()->json([
                'status' => true,
                'message' => 'Pesanan anda telah dihapus!!'
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => true,
                'message' => 'Gagal menghapus pesan !'
            ]);
        }
    }
}
