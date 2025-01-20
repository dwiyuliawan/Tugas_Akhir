@extends('layouts.master')

@section('title')
    Transaction Purchase
@endsection

@push('css')
<style>
    .tampil-bayar {
        font-size: 5em;
        text-align: center;
        height: 100px;
    }

    .tampil-terbilang {
        padding: 10px;
        background: #f0f0f0;
    }

    .table-pembelian tbody tr:last-child {
        display: none;
    }

    @media(max-width: 768px) {
        .tampil-bayar {
            font-size: 3em;
            height: 70px;
            padding-top: 5px;
        }
    }
</style>
@endpush

@section('breadcrumb')
    @parent
    <li class="active">Transaction Purchase</li>
@endsection

@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="box">
            <div class="box-header with-border">
                <table>
                    <tr>
                        <td>Supplier</td>
                        <td>: {{ $suppliers->name }}</td>
                    </tr>
                    <tr>
                        <td>Phone Number</td>
                        <td>: {{ $suppliers->phone_number }}</td>
                    </tr>
                    <tr>
                        <td>Address</td>
                        <td>: {{ $suppliers->address }}</td>
                    </tr>
                </table>
            </div>
            <div class="box-body">
                    
                <form class="form-produk">
                    @csrf
                    <div class="form-group row">
                        <label for="produc_code" class="col-lg-2">Product Code</label>
                        <div class="col-lg-5">
                            <div class="input-group">
                                <input type="hidden" name="purchase_id" id="purchase_id" value="{{ $purchase_id }}">
                                <input type="hidden" name="product_id" id="product_id">
                                <input type="text" class="form-control" name="product_code" id="product_code">
                                <span class="input-group-btn">
                                    <button onclick="tampilProduk()" class="btn btn-info btn-flat" type="button"><i class="fa fa-arrow-right"></i></button>
                                </span>
                            </div>
                        </div>
                    </div>
                </form>

                <table class="table table-striped table-bordered table-pembelian">
                    <thead>
                        <th width="5%">No</th>
                        <th>Code</th>
                        <th>Name</th>
                        <th>Price</th>
                        <th width="15%">Qty</th>
                        <th>Subtotal</th>
                        <th width="15%"><i class="fa fa-cog"></i></th>
                    </thead>
                </table>

                <div class="row">
                    <div class="col-lg-8">
                        <div class="tampil-bayar bg-primary"></div>
                        <div class="tampil-terbilang"></div>
                    </div>
                    <div class="col-lg-4">
                        <form action="{{ route('purchases.store') }}" class="form-pembelian" method="post">
                            @csrf
                            <input type="hidden" name="purchase_id" value="{{ $purchase_id }}">
                            <input type="hidden" name="total" id="total">
                            <input type="hidden" name="total_item" id="total_item">
                            <input type="hidden" name="pay" id="pay">

                            <div class="form-group row">
                                <label for="totalrp" class="col-lg-2 control-label">Total</label>
                                <div class="col-lg-8">
                                    <input type="text" id="totalrp" class="form-control" readonly>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="discount" class="col-lg-2 control-label">Discount</label>
                                <div class="col-lg-8">
                                    <input type="number" name="discount" id="discount" class="form-control" value="{{$discount}}">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="pay" class="col-lg-2 control-label">Pay</label>
                                <div class="col-lg-8">
                                    <input type="text" id="bayarrp" class="form-control" readonly>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="box-footer">
                <button type="submit" class="btn btn-primary btn-sm btn-flat pull-right btn-simpan"><i class="fa fa-floppy-o"></i> Save Transaction</button>
            </div>
        </div>
    </div>
</div>

@includeIf('purchase_detail.product')
@endsection

@push('scripts')
<script>
    let table, table2;

    $(function () {
        $('body').addClass('sidebar-collapse');

        table = $('.table-pembelian').DataTable({
            responsive: true,
            processing: true,
            serverSide: true,
            autoWidth: false,
            ajax: {
                url: '{{ route('purchase_details.data', $purchase_id) }}',
            },
            columns: [
                {data: 'DT_RowIndex', searchable: false, sortable: false},
                {data: 'product_code'},
                {data: 'product_name'},
                {data: 'purchase_price'},
                {data: 'qty'},
                {data: 'subtotal'},
                {data: 'action', searchable: false, sortable: false},
            ],
            dom: 'Brt',
            bSort: false,
            paginate: false
        })
        .on('draw.dt', function () {
            loadForm($('#discount').val());
        });

        table2 = $('.table-produk').DataTable();

        $(document).on('input', '.quantity', function () {
            let id = $(this).data('id');
            let qty = parseInt($(this).val());

            if (qty < 1) {
                $(this).val(1);
                alert('Jumlah tidak boleh kurang dari 1');
                return;
            }
            if (qty > 10000) {
                $(this).val(10000);
                alert('Jumlah tidak boleh lebih dari 10000');
                return;
            }

            $.post(`{{ url('/purchase_details') }}/${id}`, {
                    '_token': $('[name=csrf-token]').attr('content'),
                    '_method': 'put',
                    'qty': qty
                })
                .done(response => {
                    $(this).on('mouseout', function () {
                        table.ajax.reload(() => loadForm($('#discount').val()));
                    });
                })
                .fail(errors => {
                    alert('Tidak dapat menyimpan data');
                    return;
                });
        });

        $(document).on('input', '#discount', function () {
            if ($(this).val() == "") {
                $(this).val(0).select();
            }

            loadForm($(this).val());
        });

        $('.btn-simpan').on('click', function () {
            $('.form-pembelian').submit();
        });
    });

    function tampilProduk() {
        $('#modal-produk').modal('show');
    }

    function hideProduk() {
        $('#modal-produk').modal('hide');
    }

    function pilihProduk(id, kode) {
        $('#product_id').val(id);
        $('#product_code').val(kode);
        hideProduk();
        tambahProduk();
    }

    function tambahProduk() {
        $.post('{{ route('purchase_details.store') }}', $('.form-produk').serialize())
            .done(response => {
                $('#product_code').focus();
                table.ajax.reload(() => loadForm($('#discount').val()));
            })
            .fail(errors => {
                alert('Tidak dapat menyimpan data');
                return;
            });
    }

    function deleteData(url) {
        if (confirm('Yakin ingin menghapus data terpilih?')) {
            $.post(url, {
                    '_token': $('[name=csrf-token]').attr('content'),
                    '_method': 'delete'
                })
                .done((response) => {
                    table.ajax.reload(() => loadForm($('#discount').val()));
                })
                .fail((errors) => {
                    alert('Tidak dapat menghapus data');
                    return;
                });
        }
    }

    function loadForm(discount = 0) {
        $('#total').val($('.total').text());
        $('#total_item').val($('.total_item').text());

        $.get(`{{ url('/purchase_details/loadform') }}/${discount}/${$('.total').text()}`)
            .done(response => {
                $('#totalrp').val('Rp. '+ response.totalrp);
                $('#bayarrp').val('Rp. '+ response.bayarrp);
                $('#pay').val(response.pay);
                $('.tampil-bayar').text('Rp. '+ response.bayarrp);
                $('.tampil-terbilang').text(response.terbilang);
            })
            .fail(errors => {
                alert('Tidak dapat menampilkan data');
                return;
            })
    }
</script>
@if (session('error'))
    <script>
        Swal.fire({
            icon: 'error',
            title: 'Peringatan...',
            text: "{{ session('error') }}"
        });
    </script>
@endif
@endpush