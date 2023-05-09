<?php

namespace App\Exports;

use App\Models\Order;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class TransactionExport implements FromView
{

    public function __construct(protected $startAt, protected $endAt) {
    }
    
    public function view(): View
    {
        return view('report.export-transaction', [
            'invoices' => Order::whereBetween('paid_at', [$this->startAt, $this->endAt])->get(),
            'startAt' => $this->startAt,
            'endAt' => $this->endAt
        ]);
    }
}
