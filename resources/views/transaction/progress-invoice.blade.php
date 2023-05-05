@extends('layouts.app')
@section('content')
    <div class="page-content">
        <div class="container-fluid">

            <!-- start page title -->
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        <h4 class="mb-sm-0 font-size-18">Data Transaksi</h4>

                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="javascript: void(0);">Data</a></li>
                                <li class="breadcrumb-item active">Data Transaksi</li>
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
                            <div class="mb-4 row">
                                <label for="example-text-input" class="col-md-2 col-form-label font-size-18"><b>Filter Transaksi</b></label>
                                <div class="col-md-12">
                                    <select class="form-control" name="find" id="find">
                                        <option value="pending" {{$type == 'pending' ? 'selected' : ''}}>Belum Terbayarkan</option>
                                        <option value="paid" {{$type == 'paid' ? 'selected' : ''}}>Terbayarkan</option>
                                        <option value="canceled" {{$type == 'canceled' ? 'selected' : ''}}>Di Batalkan</option>
                                        </select>
                                </div>
                            </div>
                           
                            <hr>
                            <table id="data-table" class="table table-bordered dt-responsive  nowrap w-100 data-table">
                                <thead>
                                    <tr>
                                        <th>Invoice</th>
                                        <th>Meja</th>
                                        <th>Nama Pelanggan</th>
                                        <th>Hp</th>
                                        <th>Total Pembelanjaan</th>
                                        <th>Action</th>
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
        
            var table = $('#data-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    "url": "{{ url()->current() }}",
                    "type": "GET",
                    "dataType": "JSON",
                    "data": function(data) {
                        data.status = $('#find').val();
                    }
                },
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
                        data: 'customer_name',
                        name: 'customer_name'
                    },
                    {
                        data: 'customer_hp',
                        name: 'customer_hp'
                    },
                   
                    {
                        data: 'total_payment',
                        name: 'total_payment'
                    },
                   {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    },
                ],
            });

            $('#find').change(function() {
                table.ajax.reload();
            });
        });

       
    </script>
@endsection
