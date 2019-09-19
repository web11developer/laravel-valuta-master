@extends('admin.layouts.default')

{{-- Web site Title --}}
@section('title') {!! trans("admin/language.languages") !!} ::
@parent @stop

@section('styles')
    @parent
@endsection

{{-- Content --}}
@section('main')
    <div class="page-header">
        <h3>
           Языки

            <div class="pull-right">
                <a href="{!!  URL::to('admin/language/create') !!}"
                   class="btn btn-sm btn-primary"><span
                            class="glyphicon glyphicon-plus-sign"></span> {!!
				trans("admin/modal.new") !!}</a>
            </div>
        </h3>
    </div>

    <table id="table" class="table table-striped table-hover">
        <thead>
        <tr>
            <th>{{ trans("admin/modal.title") }}</th>
            <th>{{ trans("admin/language.code") }}</th>
{{--            <th>{{ trans("admin/language.icon") }}</th>--}}
            <th>{{ trans("admin/admin.action") }}</th>
        </tr>
        </thead>
        <tbody>
        </tbody>
    </table>
@stop

{{-- Scripts --}}
@section('scripts')
    <script type="text/javascript">

        var columns=[
            {data: 'id', name: 'id', 'visible': false},
            {data: 'name', name: 'name'},
            {data: 'lang_code', name: 'lang_code'},
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
                    "sSearch": "{{ trans('table.search') }}:",
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
