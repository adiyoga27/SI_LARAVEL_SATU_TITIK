<?php

namespace App\Http\Controllers\Web;

use App\Exports\TransactionExport;
use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderDetail;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\DataTables;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $startAt= $request->startAt." 00:00:00" ?? date('Y-m-d 00:00:00');
        $endAt = $request->endAt." 23:59:59"  ?? date('Y-m-d 23:59:59');
        if ($request->ajax()) {
            $data =  Order::whereBetween('paid_at', [$startAt, $endAt])->where('status', 'paid')->get();
            return DataTables::of($data)->addIndexColumn()
                ->addColumn('action', function ($data) {
                    return view('datatables._action_dinamyc', [
                        'model'           => $data,
                        'padding'         => '85px',
                    ]);
                })
                ->addColumn('dinning_table', function($data){return $data->diningTable->name;})
                ->addColumn('cashier', function($data){return $data->user->name;})
                ->addColumn('payment', function($data){return number_format($data->total_payment, '0', '.',',');})
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('report.index');
    }

    public function laba(Request $request)
    {

        $startAt= $request->start_at;
        if(isset($startAt)){
            $date = Carbon::createFromFormat('Y-m-d', $startAt);
            $dateReport = $date->locale('id')->format('d F Y');
            $orders = OrderDetail::whereDate('created_at', $startAt)->where('status', 'finish')->groupBy('product_id')->get([
                DB::raw('product_id'),
                DB::raw('SUM(total_price) AS benefit'),
                DB::raw('SUM(quantity) AS quantity'),
            ]);
                if($orders){
                    $data = [];
                    foreach ($orders as $order) {
                        $data[] = array(
                            'product_id' => $order->product_id,
                            'name' => $order->product->name,
                            'benefit' => $order->benefit,
                            'cost' => $order->product->hpp,
                            'quantity' => $order->quantity,
            
                        ) ;
                    }
                    if(count($data) == 0){
                        return redirect()->back()->withErrors('Data Yang Kamu Cari Tidak Ada');

                    }

                    return view('report.laba', compact('data', 'dateReport' ,'startAt'));
                }

           
        }
        return view('report.laba');

      
    }

    public function exportTransaction(Request $request)
    {
        $startAt= $request->start_at;
        $endAt = $request->end_at;
        return Excel::download(new TransactionExport($startAt, $endAt), 'Transaction-'.now()->format('dmY').'.xlsx');

    }
}
