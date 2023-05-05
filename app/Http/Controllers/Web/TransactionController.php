<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Order;
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
                        'checkout'          => url('transaction/invoice/'.$data->uuid),
                        'cancel'          => url('transaction/cancel/'.$data->uuid),
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
           ]);
            return redirect()->back()->with('success', 'Invoice berhasil dikembalikan');
        } catch (\Throwable $th) {
            //throw $th;
            return redirect()->back()->withErrors($th->getMessage());
        }
    }

    public function checkout(Request $request, $uuid)
    {
        # code...
    }

    
}
