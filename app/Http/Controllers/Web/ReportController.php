<?php

namespace App\Http\Controllers\Web;

use App\Exports\TransactionExport;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ReportController extends Controller
{
    public function index()
    {
        return view('report.index');
    }

    public function exportTransaction(Request $request)
    {
        $startAt= $request->startAt;
        $endAt = $request->endAt;
        return Excel::download(new TransactionExport($startAt, $endAt), 'Transaction-'.now()->format('dmY').'.xlsx');

    }
}
