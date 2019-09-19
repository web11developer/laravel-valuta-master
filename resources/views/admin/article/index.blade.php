@extends('admin.layouts.default')

{{-- Web site Title --}}
@section('title') {!! trans("admin/article.article") !!} :: @parent @stop

{{-- Content --}}
@section('main')
    <div class="page-header">
        <h3>
            {!! trans("admin/article.article") !!}
            <div class="pull-right">
                <div class="pull-right">
                    <a href="{!! URL::to('admin/article/create') !!}"
                       class="btn btn-sm  btn-primary"><span
                                class="glyphicon glyphicon-plus-sign"></span> {{
					trans("admin/modal.new") }}</a>
                </div>
            </div>
        </h3>
    </div>

    <table id="table" class="table table-striped table-hover">
        <thead>
        <tr>
            <th>{{ trans("admin/modal.title") }}</th>
            <th>{{ trans("admin/article.category") }}</th>
            <th>{{ trans("admin/admin.language") }}</th>
            <th>{{ trans("admin/admin.created_at") }}</th>
            <th>{{ trans("admin/admin.action") }}</th>
        </tr>
        </thead>
        <tbody></tbody>
    </table>
@stop

{{-- Scripts --}}
@section('scripts')
    <script type="text/javascript">

            var columns=[
                {data: 'title', name: 'title'},
                {data: 'category', name: 'category'},
                {data: 'name', name: 'name'},
                {data: 'created_at', name: 'created_at'},
                {data: 'actions', name: 'actions', orderable: false},
                {data: 'id', name: 'id', 'visible': false},
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

    @parent

@stop
