<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Task;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
     
        for ($i = 1; $i <= 12; $i++) {
            $approve[] = Order::whereMonth('paid_at', $i)->whereYear('paid_at', date('Y'))->where('status', 'paid')->sum('total_payment');
            // $reject[] = Task::whereMonth('updated_at', $i)->whereYear('updated_at', date('Y'))->where('status', 'reject')->count();

        }
        $charts = [

            'labels' => ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'],
            'datasets' => [
                [
                    'label' => 'Terbayar',
                    'backgroundColor' =>  'rgba(105, 0, 132, .2)',
                    'borderColor' => 'rgba(255, 99, 132, 1)',
                    'borderWidth' => 1,
                    'data' => $approve,
                ],
                // [
                //     'label' => 'Reject',
                //     'backgroundColor' =>  'rgba(0, 137, 132, .2)',
                //     'borderColor' =>  'rgba(0, 10, 130, .7)',
                //     'borderWidth' => 1,
                //     'data' => $reject,
                // ]
            ]
        ];

        return view('dashboard', compact(['charts']));
    }
}
