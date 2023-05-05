<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderDetail;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class TransactionController extends Controller
{
    public function index(Request $request)
    {
        $type = $request->get('status');

        if ($request->ajax()) {
            $data = Order::where('status', $type);
            return DataTables::of($data)->addIndexColumn()
                ->addColumn('action', function ($data) {
                    return view('datatables._action_dinamyc', [
                        'model'           => $data,
                        'checkout'          => $data->status == 'pending' ? url('transaction/invoice/'.$data->uuid) : NULL,
                        'rollback'          => $data->status == 'canceled' || $data->status == 'paid'   ? url('transaction/rollback/'.$data->uuid) : NULL,
                        'cancel'          => $data->status == 'pending' ? url('transaction/cancel/'.$data->uuid) : NULL,
                        'confirm_message' =>  'Anda akan membatalkan invoice "' . $data->order_number . '" ?',
                        'padding'         => '85px',
                    ]);
                })
                ->addColumn('dinning_table', function ($data) {
                    return $data->diningTable->name;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('transaction.progress-invoice', compact('type'));
    }
    public function invoice(Request $request, $uuid)
    {
        $order = Order::where('uuid', $uuid)->first();
        return view('transaction.invoice', compact('order'));
    }
    public function cancel(Request $request, $uuid)
    {
        try {
           

           Order::where('uuid', $uuid)->update([
                'status' => 'canceled',
                'user_id' => $request->user()->id
           ]);
            return redirect()->back()->with('success', 'Invoice berhasil dibatalkan');
        } catch (\Throwable $th) {
            //throw $th;
            return redirect()->back()->withErrors($th->getMessage());
        }
    }

    public function rollback(Request $request, $uuid)
    {
        try {
           Order::where('uuid', $uuid)->update([
                'status' => 'pending',
                'payment_number' => NULL,
                'note' => NULL 
           ]);
            return redirect()->back()->with('success', 'Invoice berhasil dikembalikan');
        } catch (\Throwable $th) {
            //throw $th;
            return redirect()->back()->withErrors($th->getMessage());
        }
    }

    public function checkout(Request $request, $uuid)
    {
        try {
            Order::where('uuid', $uuid)->update([
                'user_id' => $request->user()->id,
                 'payment_method' => $request->payment_method,
                 'payment_number' => $request->payment_number,
                 'note' => $request->note,
                 'status' => 'paid',
            ]);
             return redirect()->back()->with('success', 'Invoice berhasil dibayarkan');
         } catch (\Throwable $th) {
             //throw $th;
             return redirect()->back()->withErrors($th->getMessage());
         }
    }
    public function detailCart(Request $request, $cartId)
    {
        $data = OrderDetail::where('id', $cartId)->first();

        return response()->json([
            'status' => true,
            'message' => 'success',
            'data' => array(
                'product' => $data->product->name,
                'quantity' => $data->quantity,
                'id' => $data->id
            )
            ]);
    }

    public function submitCart(Request $request, $cartId)
    {
        try {
            $order = OrderDetail::where('id', $cartId)->first();

            $totalPrice =  $request->quantity * $order->product->price;
            $discount = $totalPrice * ($order->product->discount / 100);
        tap($order->update([
            'quantity' => $request->quantity,
            'discount' => $discount,
            'total_price' => $totalPrice - $discount
         ]));

         $this->calculateTransaction($order->order_id);
        return response()->json([
            'status' => true,
            'message' => 'Berhasil merubah data pembelian'
        ]);

    } catch (\Throwable $th) {
        //throw $th;
        return response()->json([
            'status' => false,
            'message' => $th->getMessage()
        ], 400);

    }
    }

    public function calculateTransaction($orderId)
    {
     
            $totalPrice = OrderDetail::where('order_id', $orderId)->sum('total_price');

            $discount = OrderDetail::where('order_id', $orderId)->sum('discount');
            $price = OrderDetail::where('order_id', $orderId)->sum('price');
    
            Order::where('id', $orderId)->update([
                'total_price' => $totalPrice,
                'discount' => $discount,
                'tax' => 0,
                'total_payment' => $totalPrice
            ]);

       
      
    }

    public function deleteCart(Request $request, $cartId)
    {
        try {
            $order = OrderDetail::where('id', $cartId)->first();
            $order->delete();
            $this->calculateTransaction($order->order_id);
        return redirect()->back()->with('success', 'Berhasil menghapus data item pada cart !');
        } catch (\Throwable $th) {
            return redirect()->back()->withErrors($th->getMessage());

        }
       

    }

    
}
