@extends('layouts.app')
@section('content')
    <style>
        td {
            vertical-align: middle !important;
        }
    </style>
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
                                <li class="breadcrumb-item active">#{{ $order->order_number }}</li>
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
                                <h4 class="float-end font-size-16"># {{ $order->order_number }}</h4>
                                <div class="mb-sm-0 font-size-18 ">
                                    <b>INVOICE</b>
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-sm-6">
                                    <address>
                                        <strong>Order:</strong><br>
                                        {{ date('d M Y H:i', strtotime($order->created_at)) }}<br>
                                        {{ $order->diningTable->name }}<br>
                                    </address>
                                </div>
                                <div class="col-sm-6 text-sm-end">
                                    <address class="mt-2 mt-sm-0">
                                        <strong>Biller To:</strong><br>
                                        {{ $order->customer_name }}<br>
                                        {{ $order->customer_hp }}<br>
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
                                            {{ strtoupper($order->payment_method) }}<br>
                                            {{ $order->payment_number }}<br>
                                            {{ $order->note }}<br>
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
                                            <th style="width:5%"></th>
                                            <th style="width:10%">Status</th>
                                            <th style="width:10%">Qty</th>
                                            <th style="width:50%">Pesanan</th>
                                            <th style="width:10%">Harga</th>
                                            <th style="width:10%">Discount</th>
                                            <th style="width:20%" class="text-end">Harga</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($order->details as $d)
                                            <tr>
                                                @if (Auth::user()->role == 'chief')
                                                    <td><a class="btn btn-sm btn-secondary" data-id="{{ $d->id }}"
                                                            id="edit" data-bs-toggle="modal"
                                                            data-bs-target="#modal_edit"><i class="mdi mdi-pencil"></i></a>
                                                    </td>
                                                @else
                                                    <td></td>
                                                @endif
                                                <td>
                                                    @switch($d->status)
                                                        @case('pending')
                                                            Menunggu
                                                        @break

                                                        @case('proses')
                                                            Sedang Di Proses
                                                        @break

                                                        @case('finish')
                                                            Sudah Di Antarkan
                                                        @break

                                                        @default
                                                    @endswitch
                                                    {{ $d->status }}</td>
                                                <td>{{ $d->quantity }}</td>
                                                <td>{{ $d->product->name }}</td>
                                                <td>{{ number_format($d->price, '0', ',', '.') }}</td>
                                                <td>{{ $d->discount > 0 ? '(' . $d->product->discount . '%) / ' . number_format($d->discount, '0', ',', '.') : '-' }}
                                                </td>
                                                <td class="text-end">{{ number_format($d->total_price, '0', ',', '.') }}</td>
                                            </tr>
                                        @endforeach
                                        <tr>
                                            <td colspan="6  " class="text-end">Sub Total</td>
                                            <td class="text-end">{{ number_format($order->total_price, '0', ',', '.') }}</td>
                                        </tr>
                                        {{-- <tr>
                                                <td colspan="5" class="border-0 text-end">
                                                    <strong>Diskon</strong></td>
                                                <td class="border-0 text-end">{{number_format($order->discount,'0',',','.')}}</td>
                                            </tr> --}}
                                        <form action="{{ url('transaction/checkout/') . '/' . $order->uuid }}" method="POST"
                                            enctype="multipart/form-data">
                                            @csrf
                                            <tr>
                                                <td colspan="4" class="border-0 text-end">
                                                    Metode Pembayaran
                                                </td>
                                                <td colspan="3">
                                                    <select class="form-control" name="payment_method" id="payment_method" onchange="methodTransaction(this)"
                                                        required  @if ($order->status != 'pending')
                                                        readonly
                                                    @endif>
                                                        <option value="">Pilih Methode Pembayaran</option>
                                                        <option value="cash" @if ($order->payment_method == 'cash')  selected @endif>CASH</option>
                                                        <option value="credit_card" @if ($order->payment_method == 'credit_card')  selected @endif>Credit Card</option>
                                                        <option value="debit_card" @if ($order->payment_method == 'debit_card')  selected @endif>Debit Card</option>
                                                        <option value="gopay" @if ($order->payment_method == 'gopay')  selected @endif>Gopay</option>
                                                        <option value="ovo" @if ($order->payment_method == 'ovo')  selected @endif>OVO</option>
                                                        <option value="linkaja" @if ($order->payment_method == 'linkaja')  selected @endif>LinkAja</option>
                                                        <option value="shopeepay" @if ($order->payment_method == 'shopeepay')  selected @endif>ShopeePay</option>
                                                        <option value="dana" @if ($order->payment_method == 'dana')  selected @endif>DANA</option>
                                                        <option value="other" @if ($order->payment_method == 'other')  selected @endif>OTHER</option>
                                                    </select>
                                                </td>
                                            </tr>
                                            <tr id="payment_group">
                                                <td colspan="4" class="border-0 text-end">
                                                    Nomor Pembayaran
                                                </td>
                                                <td colspan="3">
                                                    <input class="form-control" type="text" name="payment_number"  @if ($order->status != 'pending')
                                                    readonly
                                                @endif value="{{$order->payment_number}}"
                                                        id="payment_number" placeholder="Masukkan nomor pembayaran ...">
                                                </td>
                                            </tr>
                                            @php
                                                $tax = 0;
                                            @endphp
                                            @foreach ($additionalCost as $ac)
                                                @php
                                                    $tax = $tax + $ac->amount * $order->total_price;
                                                @endphp
                                                <tr>
                                                    <td colspan="4" class="border-0 text-end">
                                                        <strong>{{ $ac->name }} {{$ac->amount * 100}}%</strong>
                                                    </td>
                                                    <td colspan="3" class="border-0 text-end">
                                                        <input class="form-control" type="text" style="text-align: end"
                                                            name="taxes[]"
                                                            value="{{ number_format($ac->amount * $order->total_price, '0', ',', '.') }}"
                                                            readonly>
                                                    </td>
                                                </tr>
                                            @endforeach
                                            <input name="tax" id="tax" value="{{ $tax }}" hidden>
                                            <tr>
                                                <td colspan="4" class="border-0 text-end">
                                                    <strong>Total</strong>
                                                </td>
                                                <td colspan="3" class="border-0 text-end">
                                                    <h4 class="m-0">
                                                        <input class="form-control" type="text" style="text-align: end"
                                                            value="{{ number_format($order->total_payment + $tax, '0', ',', '.') }}"
                                                            id="payment" name="payment" readonly>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td colspan="4" class="border-0 text-end">
                                                    <strong>Pembayaran</strong>
                                                </td>
                                                <td colspan="3  " class="border-0 text-end">
                                                    <h4 class="m-0">
                                                        <input class="form-control" type="text"
                                                            style="text-align: end" value="{{number_format($order->pay,0,',','.')}}" id="pay" name="pay" @if ($order->status != 'pending')
                                                                readonly
                                                            @endif
                                                            onkeyup="calculate()">
                                                </td>
                                            </tr>
                                            <tr>
                                                <td colspan="4" class="border-0 text-end "
                                                    style="align-items: center">
                                                    <strong>Sisa</strong>
                                                </td>
                                                <td colspan="3  " class="border-0 text-end">
                                                    <h4 class="m-0">
                                                        <input class="form-control" type="text"
                                                            style="text-align: end" name="sisa" id="sisa"
                                                            value="0" readonly>
                                                </td>
                                            </tr>
                                            {{-- <tr>
                                                <td colspan="6"  class="border-0 text-end">
                                                    Note
                                                </td>
                                                <td> --}}
                                            {{-- <textarea class="form-control"  name="note" id="note" placeholder="Masukkan catatan jika ada" hidden>aa</textarea> --}}
                                            {{-- </td>
                                            </tr> --}}
                                            </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="d-print-none">
                                <div class="float-end">
                                    <a href="javascript:window.print()"
                                        class="btn btn-success waves-effect waves-light me-1"><i
                                            class="fa fa-print"></i></a>
                                    @if ($order->status == 'pending')
                                        {{-- <a href="javascript: void(0);" class="btn btn-danger waves-effect waves-light"  data-bs-toggle="modal" data-bs-target="#addForm"><i class="mdi mdi-cash-multiple"></i> &nbsp Checkout</a> --}}
                                        <button type="submit" class="btn btn-danger waves-effect waves-light"><i
                                                class="mdi mdi-cash-multiple"></i> &nbsp Checkout</button>
                                    @endif
                                </div>
                            </div>
                            </form>
                        </div> <!-- Static Backdrop Modal -->
                        <div class="modal fade" id="addForm" data-bs-backdrop="static" data-bs-keyboard="false"
                            tabindex="-1" role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="staticBackdropLabel">Payment</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                            aria-label="Close"></button>
                                    </div>
                                    <form action="{{ url('transaction/checkout/') . '/' . $order->uuid }}" method="POST"
                                        enctype="multipart/form-data">
                                        <div class="modal-body">
                                            @csrf
                                            <div class="mb-3 row">
                                                <label for="example-text-input" class="col-md-12 col-form-label">Metode
                                                    Pembayaran</label>
                                                <div class="col-md-12">
                                                    <select class="form-control" name="payment_method"
                                                        id="payment_method" required>
                                                        <option value="">Pilih Methode Pembayaran</option>
                                                        <option value="cash">CASH</option>
                                                        <option value="credit_card">Credit Card</option>
                                                        <option value="debit_card">Debit Card</option>
                                                        <option value="gopay">Gopay</option>
                                                        <option value="ovo">OVO</option>
                                                        <option value="linkaja">LinkAja</option>
                                                        <option value="shopeepay">ShopeePay</option>
                                                        <option value="dana">DANA</option>
                                                        <option value="other">OTHER</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="mb-3 row">
                                                <label for="example-text-input" class="col-md-12 col-form-label">Nomor
                                                    Pembayaran</label>
                                                <div class="col-md-12">
                                                    <input class="form-control" type="text" name="payment_number"
                                                        id="payment_number" placeholder="Masukkan nomor pembayaran ...">
                                                </div>
                                            </div>
                                            <div class="mb-3 row">
                                                <label for="example-text-input"
                                                    class="col-md-12 col-form-label">Note</label>
                                                <div class="col-md-12">
                                                    <textarea class="form-control" name="note" id="note" placeholder="Masukkan catatan jika ada"></textarea>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-light"
                                                data-bs-dismiss="modal">Batal</button>
                                            <button type="submit" class="btn btn-primary">Checkout</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div> <!-- end col -->
            </div> <!-- end row -->
        </div>
    </div>
    <div class="modal fade" id="modal_edit" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="staticBackdropLabel">Form Edit</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="form-edit" class="form-edit" method="POST" enctype="multipart/form-data">
                    <input name="_method" value="PUT" hidden>
                    <div class="modal-body">
                        <div class="mb-3 row">
                            <label for="example-text-input" class="col-md-2 col-form-label">Product</label>
                            <div class="col-md-10">
                                <input class="form-control" type="text" name="id" id="id" hidden>
                                <input class="form-control" type="text" id="product" readonly>
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <label for="example-text-input" class="col-md-2 col-form-label">Quantity</label>
                            <div class="col-md-10">
                                <input class="form-control" type="number" name="quantity" id="quantity">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <a type="button" class="btn btn-danger" id="delete">Hapus Item</a>
                        <button type="button" class="btn btn-secondary">Batal</button>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </form>
            </div>
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
    <script>
        calculate();
        methodTransaction();
        $('#modal_edit').on('show.bs.modal', function(event) {
            var div = $(event.relatedTarget) // Tombol dimana modal di tampilkan
            var modal = $(this)
            // Isi nilai pada field
            var div = $(event.relatedTarget) // Tombol dimana modal di tampilkan
            var modal = $(this)
            // Isi nilai pada field
            var id = div.data('id');
            $('#form-edit')[0].reset();
            $.ajax({
                type: "GET",
                url: "{{ url('transaction/order') }}/" + id,
                dataType: "JSON",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    console.log(response.data[0])
                    if (response.status) {
                        modal.find('#id').val(response.data['id']);
                        modal.find('#product').val(response.data['product']);
                        modal.find('#quantity').val(response.data['quantity']);
                        modal.find('#delete').attr('href', "{{ url('transaction/cart-delete') }}" +
                            "/" + response.data['id']);
                    } else {
                        Swal.fire(
                            'Gagal!',
                            'Gagal Menyimpan Data',
                            'error'
                        )
                    }
                }
            });
        });
        $("#form-edit").submit(function(e) {
            e.preventDefault();
            // var data = $(this).serialize();
            var id = $('#id').val();
            // const form = document.querySelector('#form-edit');
            var formData = new FormData(this);
            $.ajax({
                type: "POST",
                url: "{{ url('transaction/cart/') }}/" + id,
                data: formData,
                processData: false,
                contentType: false,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    if (response.status) {
                        Swal.fire(
                            'Berhasil',
                            'Berhasil Di Simpan',
                            'success'
                        ).then((result) => {
                            window.location.reload();
                        });
                    } else {
                        Swal.fire(
                            'Gagal!',
                            'Gagal Menyimpan Data',
                            'error'
                        )
                    }
                }
            });
        });

        function calculate() {
            let total = document.getElementById("payment").value
            let pay = document.getElementById("pay").value
            document.getElementById("sisa").value = convertNumber(total) - convertNumber(pay);
        }

        function convertNumber(params) {
            return params.replace(/[^\d]/g, '');
        }

        function methodTransaction(){
            let pilih  = document.getElementById("payment_method").value;
            if(pilih == 'cash'){
                document.getElementById("payment_group").style.visibility = 'collapse';
            }else{
                document.getElementById("payment_group").style.visibility = 'visible';
            }

        }
    </script>
@endsection
