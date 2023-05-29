<?php

namespace App\Http\Controllers\Web;

use App\Exports\TransactionExport;
use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
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

    public function exportTransaction(Request $request)
    {
        $startAt= $request->start_at;
        $endAt = $request->end_at;
        return Excel::download(new TransactionExport($startAt, $endAt), 'Transaction-'.now()->format('dmY').'.xlsx');

    }
}
