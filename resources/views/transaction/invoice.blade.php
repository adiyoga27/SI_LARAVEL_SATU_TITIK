@extends('layouts.app')
@section('content')
    <div class="page-content">
        <div class="container-fluid">

            <!-- start page title -->
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        <h4 class="mb-sm-0 font-size-18"></h4>

                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="javascript: void(0);">Data</a></li>
                                <li class="breadcrumb-item">Data Invoice</li>
                                <li class="breadcrumb-item active">#{{$order->order_number}}</li>
                            </ol>
                        </div>

                    </div>
                </div>
            </div>
            <!-- end page title -->
            <div class="row">
                <div class="col-2"></div>
                <div class="col-8">
                    <div class="card">
                        <div class="card-body">

                                <div class="invoice-title">
                                    <h4 class="float-end font-size-16"># {{$order->order_number}}</h4>
                                 <div class="mb-sm-0 font-size-18 ">
                                   <b>INVOICE</b> 
                                    </div>
                                 
                                </div>
                                <hr>
                                <div class="row">
                                    <div class="col-sm-6">
                                        <address>
                                            <strong>Order:</strong><br>
                                            {{date('d M Y H:i', strtotime($order->created_at))}}<br>
                                            {{$order->diningTable->name}}<br>

                                        </address>
                                    </div>
                                    <div class="col-sm-6 text-sm-end">
                                        <address class="mt-2 mt-sm-0">
                                            <strong>Biller To:</strong><br>
                                            {{$order->customer_name}}<br>
                                            {{$order->customer_hp}}<br>
                                  
                                        </address>
                                    </div>
                                </div>
                                @if ($order->status == 'paid')
                                    <div class="row">
                                    <div class="col-sm-6 mt-3">
                                        {{-- <address>
                                            <strong>Payment Method:</strong><br>
                                            Visa ending **** 4242<br>
                                            jsmith@email.com
                                        </address> --}}
                                    </div>
                                    <div class="col-sm-6 mt-3 text-sm-end">
                                        <address>
                                            <strong>Payment:</strong><br>
                                            {{strtoupper($order->payment_method)}}<br>
                                            {{$order->payment_number}}<br>
                                            {{$order->note}}<br>
                                        </address>
                                    </div>
                                </div>
                                @endif
                              
                                <div class="py-1 mt-1">
                                    <h3 class="font-size-15 fw-bold">Order summary</h3>
                                </div>
                                <div class="table-responsive">
                                    <table class="table table-nowrap">
                                        <thead>
                                            <tr>
                                                <th style="width: 70px;">Qty</th>
                                                <th>Pesanan</th>
                                                <th class="text-end">Harga</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($order->details as $d)
                                            <tr>
                                                <td>{{$d->quantity}}</td>
                                                <td>{{$d->product->name}}</td>
                                                <td class="text-end">{{number_format($d->total_price,'0',',','.')}}</td>
                                            </tr>
                                            @endforeach
                                        
                                            
                                            <tr>
                                                <td colspan="2" class="text-end">Sub Total</td>
                                                <td class="text-end">{{number_format($order->total_price)}}</td>
                                            </tr>
                                            <tr>
                                                <td colspan="2" class="border-0 text-end">
                                                    <strong>Diskon</strong></td>
                                                <td class="border-0 text-end">{{number_format($order->diskon)}}</td>
                                            </tr>
                                            <tr>
                                                <td colspan="2" class="border-0 text-end">
                                                    <strong>Tax</strong></td>
                                                <td class="border-0 text-end">{{number_format($order->tax)}}</td>
                                            </tr>
                                            <tr>
                                                <td colspan="2" class="border-0 text-end">
                                                    <strong>Total</strong></td>
                                                <td class="border-0 text-end"><h4 class="m-0">{{number_format($order->total_payment)}}</h4></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <div class="d-print-none">
                                    <div class="float-end">
                                        <a href="javascript:window.print()" class="btn btn-success waves-effect waves-light me-1"><i class="fa fa-print"></i></a>
                                        @if ($order->status == 'pending')
                                            <a href="javascript: void(0);" class="btn btn-primary w-md waves-effect waves-light">Checkout</a>
                                            
                                        @endif
                                    </div>
                                </div>

                        </div>
                    </div>
                </div> <!-- end col -->
            </div> <!-- end row -->
        </div>
    </div>
  


@endsection
@section('js')
    <!-- Required datatable js -->
    <script src="{{ url('assets') }}/libs/datatables.net/js/jquery.dataTables.min.js"></script>
    <script src="{{ url('assets') }}/libs/datatables.net-bs4/js/dataTables.bootstrap4.min.js"></script>
    <!-- Buttons examples -->
    <script src="{{ url('assets') }}/libs/datatables.net-buttons/js/dataTables.buttons.min.js"></script>
    <script src="{{ url('assets') }}/libs/datatables.net-buttons-bs4/js/buttons.bootstrap4.min.js"></script>
    <script src="{{ url('assets') }}/libs/jszip/jszip.min.js"></script>
    <script src="{{ url('assets') }}/libs/pdfmake/build/pdfmake.min.js"></script>
    <script src="{{ url('assets') }}/libs/pdfmake/build/vfs_fonts.js"></script>
    <script src="{{ url('assets') }}/libs/datatables.net-buttons/js/buttons.html5.min.js"></script>
    <script src="{{ url('assets') }}/libs/datatables.net-buttons/js/buttons.print.min.js"></script>
    <script src="{{ url('assets') }}/libs/datatables.net-buttons/js/buttons.colVis.min.js"></script>

    <!-- Responsive examples -->
    <script src="{{ url('assets') }}/libs/datatables.net-responsive/js/dataTables.responsive.min.js"></script>
    <script src="{{ url('assets') }}/libs/datatables.net-responsive-bs4/js/responsive.bootstrap4.min.js"></script>

    <!-- Datatable init js -->
    <script src="{{ url('assets') }}/js/pages/datatables.init.js"></script>
  
@endsection
