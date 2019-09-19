@extends('admin.layouts.default')

{{-- Web site Title --}}
@section('title') {!! trans("admin/users.users") !!} :: @parent
@stop

{{-- Content --}}
@section('main')
    <div class="page-header">
        <h3>
            {!! trans("admin/users.users") !!}
            <div class="pull-right">
                <div class="pull-right">
                    <a href="{!! URL::to('admin/user/create') !!}"
                       class="btn btn-sm  btn-primary iframe"><span
                                class="glyphicon glyphicon-plus-sign"></span> {{
					trans("admin/modal.new") }}</a>
                </div>
            </div>
        </h3>
    </div>

    <table id="table" class="table table-striped table-hover">
        <thead>
        <tr>
            <th>{!! trans("admin/users.name") !!}</th>
            <th>{!! trans("admin/users.email") !!}</th>
            <th>{!! trans("admin/users.active_user") !!}</th>
            <th>{!! trans("admin/admin.created_at") !!}</th>
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
            {data: 'username', name: 'username'},
            {data: 'email', name: 'email'},
            {data: 'confirmed', name: 'confirmed'},
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
@stop
