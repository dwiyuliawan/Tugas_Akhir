@extends('layouts.master')

@section('title')
 Kategori
@endsection

@section('breadcrumb')
    @parent
    <li class="active">Kategori</li>
@endsection

@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="box">
            <div class="box-header with-border">
                <button onclick="addForm('{{ route('categoris.store') }}')" class="btn btn-success btn-xs btn-flat"><i class="fa fa-plus-circle"></i> Tambah</button>
            </div>
            <div class="box-body table-responsive">
                <table class="table table-stiped table-bordered">
                    <thead>
                        <th width="5%">No</th>
                        <th>Kategori</th>
                        <th width="15%"><i class="fa fa-cog"></i></th>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</div>

@includeIf('categori.form')
@endsection

@push('scripts')
<script type="text/javascript">
    let table;

    $(function () {
        table = $('.table').DataTable({
            processing: true,
            autoWidth : false,
            // ajax : {
            //     url: '{{route('categoris.data')}}'
            // }
        });
    });

    //memanggil modal
    function addForm(){
        $('#modal-form').modal('show');
        $('#modal-form .modal-title').text('Tambah Kategori');
    }

</script>
@endpush