@extends('layouts.app')
@section('content')
    <div class="page-content">
        <div class="container-fluid">

            <!-- start page title -->
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        <h4 class="mb-sm-0 font-size-18">Data Pesanan</h4>

                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="javascript: void(0);">Data</a></li>
                                <li class="breadcrumb-item active">Data Pesanan</li>
                            </ol>
                        </div>

                    </div>
                </div>
            </div>
            <!-- end page title -->
            <div class="row">
                                    <div class="col-md-12">
                                        <div class="row">
                                        <div class="col-md-3">
                                                <div class="card border shadow-none mb-2">
                                                <div class="text-body">
                                                        <div class="p-2">
                                                            <div class="d-flex">
                                                                <div class="avatar-xs align-self-center me-2">
                                                                    <div class="avatar-title rounded bg-transparent text-primary font-size-20">
                                                                        <i class="mdi mdi-account-multiple"></i>
                                                                    </div>
                                                                </div>

                                                                <div class="overflow-hidden me-auto">
                                                                    <h5 class="font-size-13 text-truncate mb-1">Menunggu</h5>
                                                                    <p class="text-muted text-truncate mb-0  st-waiting">5</p>
                                                                </div>


                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="card border shadow-none mb-2">
                                                <div class="text-body">
                                                        <div class="p-2">
                                                            <div class="d-flex">
                                                                <div class="avatar-xs align-self-center me-2">
                                                                    <div class="avatar-title rounded bg-transparent text-secondary font-size-20">
                                                                        <i class="mdi mdi-book-open"></i>
                                                                    </div>
                                                                </div>

                                                                <div class="overflow-hidden me-auto">
                                                                    <h5 class="font-size-13 text-truncate mb-1">Pesanan Di Proses</h5>
                                                                    <p class="text-muted text-truncate mb-0  st-proses">0</p>
                                                                </div>


                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="card border shadow-none mb-2">
                                                    <div class="text-body">
                                                        <div class="p-2">
                                                            <div class="d-flex">
                                                                <div class="avatar-xs align-self-center me-2">
                                                                    <div class="avatar-title rounded bg-transparent text-warning font-size-20">
                                                                        <i class="mdi mdi-cached"></i>
                                                                    </div>
                                                                </div>

                                                                <div class="overflow-hidden me-auto">
                                                                    <h5 class="font-size-13 text-truncate mb-1">Pesanan Diantarkan</h5>
                                                                    <p class="text-muted text-truncate mb-0  st-serve">0</p>
                                                                </div>


                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                   
                                     
                                        </div>
                                    </div>

                                </div>
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                          
                           
                            <table id="data-table" class="table table-bordered dt-responsive  nowrap w-100 data-table">
                                <thead>
                                    <tr>
                                        <th>Menu</th>
                                        <th>Harga</th>
                                        <th>Jumlah</th>
                                        <th>Meja</th>
                                        <th>Status</th>
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

            <!-- <table>
                <tr>
                    <td width="250px">Pesanan Menunggu</td>
                    <td >:</td>
                    <td>{{$pending}}</td>
                </tr>
                <tr>
                    <td>Pesanan Sedang Diproses</td>
                    <td>:</td>
                    <td>{{$proses}}</td>
                </tr>
                <tr>
                    <td>Pesanan Telah Diantarkan</td>
                    <td>:</td>
                    <td>{{$finish}}</td>
                </tr>
            </table> -->
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
                searching: false,
                processing: true,
                serverSide: true,
                lengthChange:false,
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
                        data: 'menu',
                        name: 'menu'
                    },
                    {
                        data: 'price',
                        name: 'price'
                    },
                    {
                        data: 'quantity',
                        name: 'quantity'
                    },
                    {
                        data: 'dinning_number',
                        name: 'dinning_number'
                    },
                   
                    {
                        data: 'status',
                        name: 'status'
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

        setInterval(function(){
            table.ajax.reload();
        }, 5000);
       
        setInterval(function(){
            $.ajax({
            url: "{{ url('queue') }}"+"/status",
            method: 'GET',
            success: function(response) {
                // Assuming the response is the updated content
                document.querySelector('.st-waiting').textContent = response.data.waiting;
                document.querySelector('.st-proses').textContent = response.data.proses;
                document.querySelector('.st-serve').textContent = response.data.serve;
            },
            error: function() {
                console.log('Error occurred during the AJAX request');
            }
            });
        }, 5000);
    </script>
@endsection
