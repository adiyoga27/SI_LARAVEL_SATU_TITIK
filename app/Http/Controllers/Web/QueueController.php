<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\OrderDetail;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class QueueController extends Controller
{
    public function index(Request $request)
    {

        $pending = OrderDetail::where('status', 'waiting')->where('created_at', '>=',Carbon::now()->format('Y-m-d'))->count();
        $proses = OrderDetail::where('status', 'proses')->where('created_at', '>=',Carbon::now()->format('Y-m-d'))->count();
        $finish = OrderDetail::where('status', 'finish')->where('created_at', '>=',Carbon::now()->format('Y-m-d'))->count();
        if ($request->ajax()) {
            $data = OrderDetail::orderBy('status', 'ASC')->where('status', '<>', 'pending')->where('created_at', '>=',Carbon::now()->format('Y-m-d'));
            return DataTables::of($data)->addIndexColumn()
                ->addColumn('action', function ($data) {
                    return view('datatables._action_dinamyc', [
                        'model'           => $data,
                        'proses'          => $data->status == 'waiting' ? url('queue/proses/'.$data->id) : NULL,
                        'serve'          => $data->status == 'proses'   ? url('queue/serve/'.$data->id) : NULL,
                        'padding'         => '85px',
                    ]);
                })
                ->addColumn('menu', function ($data) {
                    return $data->product->name;
                })
                ->addColumn('dinning_number', function ($data) {
                    return $data->order->diningTable->name;
                })
                ->addColumn('status', function ($data) {
                    switch ($data->status) {
                        case 'pending':
                            return 'Menuggu';
                            break;
                            case 'proses':
                                return 'Sedang di Proses';
                                break;
                                case 'finish':
                                    return 'Sudah di Antarkan';
                                    break;
                        default:
                        //    return 'Sudah di Antarkan';
                            break;
                    }
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('queue', compact('proses', 'pending', 'finish'));
    }

    public function proses(Request $request, $id)
    {
        OrderDetail::where('id', $id)->update([
            'status' => 'proses'
        ]);

        return redirect()->back()->with('success', 'Data berhasil dirubah');

    }
    public function serve(Request $request, $id)
    {
        OrderDetail::where('id', $id)->update([
            'status' => 'finish'
        ]);

        return redirect()->back()->with('success', 'Data berhasil dirubah');

    }

    public function queueStatus()
    {
        $pending = OrderDetail::where('status', 'waiting')->where('created_at', '>=',Carbon::now()->format('Y-m-d'))->count();
        $proses = OrderDetail::where('status', 'proses')->where('created_at', '>=',Carbon::now()->format('Y-m-d'))->count();
        $finish = OrderDetail::where('status', 'finish')->where('created_at', '>=',Carbon::now()->format('Y-m-d'))->count();

        return response()->json([
            'status' => true,
            'message' => 'success',
            'data' => array(
                'waiting' => $pending,
                'proses' => $proses,
                'serve' => $finish
            )
        ]);
    }
}
