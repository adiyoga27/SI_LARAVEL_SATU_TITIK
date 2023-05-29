@extends('layouts.app')
@section('content')
    <div class="page-content">
        <div class="container-fluid">

            <!-- start page title -->
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        <h4 class="mb-sm-0 font-size-18">Report</h4>

                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="javascript: void(0);">Data</a></li>
                                <li class="breadcrumb-item active">Report</li>
                            </ol>
                        </div>

                    </div>
                </div>
            </div>
            <!-- end page title -->
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="mb-2 row">
                                <label for="example-text-input" class="col-md-2 col-form-label font-size-18"><b>Rekap Transaksi</b></label>
                            </div>
                            <hr>
                        
                            <form action="{{url('report/export-transaction  ')}}" method="POST">
                                {{-- <form action="{{url('report')}}" method="POST"> --}}
                                @csrf
                            <div class="mb-3 row">
                                <label for="example-text-input" class="col-md-2 col-form-label">Tgl Awal</label>
                                <div class="col-md-10">
                                    <input class="form-control" type="date" name="start_at" id="start_at">
                                </div>
                            </div>
                            <div class="mb-3 row">
                                <label for="example-text-input" class="col-md-2 col-form-label">Tgl Akhir</label>
                                <div class="col-md-10">
                                    <input class="form-control" type="date" name="end_at" id="end_at">

                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="text-end">
                                 <button type="button" id="find" class="btn btn-primary">Cari</button>
                                 <button type="submit" id="export" class="btn btn-danger">Export</button>
                                </div>
                            </div>
                           
                            {{-- </form> --}}
                        </div>
                    </div>
                </div> <!-- end col -->
            </div> <!-- end row -->

              <!-- end page title -->
              <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                      
                       
                            <table id="data-table" class="table table-bordered dt-responsive  nowrap w-100 data-table">
                                <thead>
                                    <tr>
                                        <th>Invoice</th>
                                        <th>Meja</th>
                                        <th>Total</th>
                                        <th>Tgl Pembayaran</th>
                                        <th>Kasir</th>
                                    </tr>
                                </thead>
                                <tbody>


                                </tbody>
                            </table>

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
    <script type="text/javascript">
     let table;
        
    $(document).ready(function() {
             table = $('#data-table').DataTable({
                scrollX: true,
                ajax: {
                    "url": "{{ url()->current() }}",
                    "type": "GET",
                    "dataType": "JSON",
                    "data": function(data) {
                        data.startAt = $('#start_at').val();
                        data.endAt = $('#end_at').val();
                    }
                },
                order: [

                ],
                columns: [
                    {
                        data: 'order_number',
                        name: 'order_number'
                    },
                    {
                        data: 'dinning_table',
                        name: 'dinning_table'
                    },

                    {
                        data: 'payment',
                        name: 'payment'
                    },
                    {
                        data: 'paid_at',
                        name: 'paid_at'
                    },

                    {
                        data: 'cashier',
                        name: 'cashier'
                    },
                 
                ],
            });
          
        });
    
        $('#find').click(function() {
                table.ajax.reload();
            });

    </script>
@endsection

