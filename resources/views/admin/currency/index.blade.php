@extends('admin.layouts.default')

{{-- Web site Title --}}
@section('title') {!! trans("admin/newscategory.newscategories") !!}
:: @parent @stop

{{-- Content --}}
@section('main')
    <div class="page-header">
        <h3>
            {!! trans("admin/articlecategory.articlecategories") !!}
            <div class="pull-right">
                <div class="pull-right">
                    <a href="{!! URL::to('admin/currency/create') !!}"
                       class="btn btn-sm  btn-primary"><span
                                class="glyphicon glyphicon-plus-sign"></span> {{ trans("admin/modal.new") }}</a>
                </div>
            </div>
        </h3>
    </div>

    <table id="table" class="table table-striped table-hover">
        <thead>
        <tr>
            <th>ID</th>
            <th>{!!   trans("admin/modal.code") !!}</th>
            <th>{!!   trans("admin/modal.name") !!}</th>
            <th>{!!   trans("admin/modal.order_number") !!}</th>
            <th>{!!   trans("admin/modal.visible_bool") !!}</th>
            <th>{!! trans("admin/admin.action") !!}</th>
        </tr>
        </thead>
        <tbody></tbody>
    </table>
@stop

{{-- Scripts --}}
@section('scripts')

    <script type="text/javascript">

        var columns=[
            {data: 'currency_id', name: 'currency_id'},
            {data: 'code', name: 'code'},
            {data: 'name', name: 'name'},
            {data: 'order_number', name: 'order_number'},
            {data: 'visible_bool', name: 'visible_bool'},
            {data: 'actions', name: 'actions', orderable: false},
        ];

        var oTable;
        $.fn.dataTable.ext.errMode = 'none';
        $(document).ready(function () {
            oTable = $('#table').DataTable({
                "sDom": "<'row'<'col-md-6'l><'col-md-6'f>r>t<'row'<'col-md-6'i><'col-md-6'p>>",
                "sPaginationType": "bootstrap",
                "oLanguage": {
                    "sProcessing": "{{ trans('table.processing') }}",
                    "sLengthMenu": "{{ trans('table.showmenu') }}",
                    "sZeroRecords": "{{ trans('table.noresult') }}",
                    "sInfo": "{{ trans('table.show') }}",
                    "sEmptyTable": "{{ trans('table.emptytable') }}",
                    "sInfoEmpty": "{{ trans('table.view') }}",
                    "sInfoFiltered": "{{ trans('table.filter') }}",
                    "sInfoPostFix": "",
                    "sSearch": "{!!   trans("admin/modal.search") !!}:",
                    "sUrl": "",
                    "oPaginate": {
                        "sFirst": "{{ trans('table.start') }}",
                        "sPrevious": "{{ trans('table.prev') }}",
                        "sNext": "{{ trans('table.next') }}",
                        "sLast": "{{ trans('table.last') }}"
                    }
                },
                columns:columns,
                "processing": true,
                "serverSide": true,
                "ajax": {

                    url:"{!! $type !!}/data",
                    type:'GET'
                }
            });
        });

    </script>
@stop
