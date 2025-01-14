@extends('layouts.master')

@section('title')
 Categori
@endsection

@section('breadcrumb')
    @parent
    <li class="active">Categori</li>
@endsection

@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="box">
            <div class="box-header with-border">
                <button onclick="addForm('{{ route('categories.store') }}')" class="btn btn-success btn-xs btn-flat"><i class="fa fa-plus-circle"></i>Add Categori</button>
            </div>
            <div class="box-body table-responsive">
                <table class="table table-striped table-bordered">
                    <thead>
                        <th width="5%">No</th>
                        <th>Categori</th>
                        <th width="15%"><i class="fa fa-cog"></i></th>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</div>

@includeIf('category.form')
@endsection

@push('scripts')
<script type="text/javascript">
    let table;

    $(function () {
        table = $('.table').DataTable({
            processing: true,
            autoWidth : false,
            ajax : {
                url: '{{route('categories.data')}}'
            },
            columns: [
                {data : 'DT_RowIndex', searchable : false, sortable : false},
                {data : 'categori_name'},
                {data : 'action', searchable : false, sortable : false, class: 'text-center'},
            ]
        });

        //membuat allert with validator
        $('#modal-form').validator().on('submit', function (e) {
            if (! e.preventDefault()) {
                $.post( $('#modal-form form').attr('action'), $('#modal-form form').serialize())
                .done((response) => {
                    $('#modal-form').modal('hide');
                    table.ajax.reload();
                }) 
                .fail((errors) => {
                    alert('Tidak dapat menyimpan data');
                    return;
                });
            }
        })
    });

    //memanggil modal add
    function addForm(url){
        $('#modal-form').modal('show');
        $('#modal-form .modal-title').text('Tambah Kategori');

        $('#modal-form form')[0].reset();
        $('#modal-form form').attr('action', url);
        $('#modal-form [name=_method]').val('post');
        $('#modal-form [name=categori_name]').focus();
    }

    //memanggil modal edit
    function editForm(url){
        $('#modal-form').modal('show');
        $('#modal-form .modal-title').text('Edit Kategori');

        $('#modal-form form')[0].reset();
        $('#modal-form form').attr('action', url);
        $('#modal-form [name=_method]').val('put');
        $('#modal-form [name=categori_name]').focus();

        $.get(url)
            .done((response) => {
                $('#modal-form [name=categori_name]').val(response.categori_name);
            })
            .fail((erors) => {
                alert('Tidak dapat menampilkan data');
                return;
            });
    }

    function deleteData(url){
        if (confirm('Yakin ingin menghapus data..?')) {
            $.post(url, {
                '_token' : $('[name=csrf-token').attr('content'),
                '_method' : 'delete'
            })
            .done((response) => {
                table.ajax.reload();
            })
            .fail((errors) => {
                alert('Tidak dapat menghapus data');
                return;
            });
        }
    }

</script>
@endpush