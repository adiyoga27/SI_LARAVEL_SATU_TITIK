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
                                <label for="example-text-input" class="col-md-2 col-form-label font-size-18"><b>Laba Rugi</b></label>
                            </div>
                            <hr>
                        
                            <form action="{{url('report/laba  ')}}" method="POST">
                               <form action="{{url('report')}}" method="POST">
                                @csrf
                            <div class="mb-3 row">
                                <label for="example-text-input" class="col-md-2 col-form-label">Tanggal</label>
                                <div class="col-md-10">
                                    <input class="form-control" type="date" name="start_at" id="start_at" value="{{$startAt ?? ""}}">
                                </div>
                            </div>
              
                            <hr>
                            <div class="row">
                                <div class="text-end">
                                 <button type="submit" id="find" class="btn btn-primary">Cari</button>
                                </div>
                            </div>
                           
                            </form>
                        </div>
                    </div>
                </div> <!-- end col -->
            </div> <!-- end row -->

              <!-- end page title -->

              @if (isset($data))
              @if (count($data) )
                  
                  
              <div class="row">
              <div class="col-md-3">
</div>
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-body">
                        <table width="100%">
                            <tr>
                                <th colspan="3">
                                    <center>Satu Titik </center></th>
                            </tr>
                            <tr>
                                <th colspan="3">
                                    <center>Laporan Laba Rugi </center></th>
                            </tr>
                            <tr>
                                <th colspan="3">
                                    <center>Per Tanggal {{$dateReport}} </center></th>
                            </tr>
                                <tr height="20px">

                            </tr>
                            <tr>
                                <th colspan="2">Pendapatan</th>
                            </tr>
                            @php
                            $pendapatan = 0;
                        @endphp
                            @foreach($data as $d)
                       
                            <tr>
                            <td width="20px"></td>
                                <td>{{$d['name']}}</td>
                                <td>Rp {{number_format($d['benefit'],0,',','.')}}</td>
                                @php
                                    $pendapatan = $pendapatan + $d['benefit'];
                                @endphp
                            </tr>
                            @endforeach
                            <tr>
                            <td width="20px"></td>

                                <th>Total Pendapatan</th>
                                <td>Rp {{number_format($pendapatan,0,',','.')}}</td>
                            <tr>
                                <th colspan="2">Beban Pokok Penjualan</th>
                            </tr>
                            @php
                            $cost = 0;
                        @endphp
                            @foreach($data as $d)
                         
                            <tr>
                            <td width="20px"></td>
                                <td>{{$d['name']}}</td>
                                <td>Rp {{number_format($d['cost'],0,',','.')}}</td>
                                @php
                                    $cost = $cost + $d['cost'];
                                @endphp
                            </tr>
                            @endforeach
                            <tr>
                            <td width="20px"></td>

                                <th>Total Beban</th>
                                <td>Rp {{number_format($cost,0,',','.')}}</td>
                            <tr>
                            <tr>
                            <td colspan="3"><hr></td>

                            <tr>
                            <tr>

                                <th colspan="2">Laba</th>
                                <td>Rp {{number_format($pendapatan - $cost,0,',','.')}}</td>
                            </tr>
    </table>
                        </div>
                    </div>
                </div> <!-- end col -->
                <div class="col-md-3">
</div>
            </div> <!-- end row -->
            @endif
            @endif

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

