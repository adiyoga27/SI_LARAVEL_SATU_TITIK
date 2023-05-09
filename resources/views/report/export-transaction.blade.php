<table>
    <thead>
        <tr>
            <td colspan="12" style="text-align: center; font-weight: bold" ><center>Transaksi {{date('d-M-Y', strtotime($startAt))}} s/d {{date('d-M-Y', strtotime($endAt))}}</center></td>
        </tr>
    <tr>
        <th>Invoice</th>
        <th>Nama Customer</th>
        <th>Hp</th>
        <th>Total</th>
        <th>Diskon</th>
        <th>Tax</th>
        <th>Total Pembayaran</th>
        <th>Tgl Pembayaran</th>
        <th>Metode Pembayaran</th>
        <th>Number Pembayaran</th>
        <th>Note</th>
        <th>Kasir</th>
    </tr>
    </thead>
    <tbody>
    @foreach($invoices as $invoice)
        <tr>
            <td>{{ $invoice->order_number }}</td>
            <td>{{ $invoice->customer_name }}</td>
            <td>{{ $invoice->customer_hp }}</td>
            <td>{{ $invoice->total_price }}</td>
            <td>{{ $invoice->discount }}</td>
            <td>{{ $invoice->tax }}</td>
            <td>{{ $invoice->total_payment }}</td>
            <td>{{ $invoice->paid_at }}</td>
            <td>{{ $invoice->payment_method }}</td>
            <td>{{ $invoice->payment_number }}</td>
            <td>{{ $invoice->note }}</td>
            <td>{{ $invoice->user->name }}</td>
        </tr>
    @endforeach
    </tbody>
</table>