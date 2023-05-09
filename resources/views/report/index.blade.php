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
                                 <button type="submit" class="btn btn-primary">Cari Report</button>
                                </div>
                            </div>
                            </form>
                        </div>
                    </div>
                </div> <!-- end col -->
            </div> <!-- end row -->
        </div>
    </div>
    
@endsection
@section('js')

    <script type="text/javascript">
        
    </script>
@endsection
