@extends('admin.layouts.default')

{{-- Web site Title --}}
@section('title') {!! $title !!} :: @parent @stop

{{-- Content --}}
@section('main')
    <h3>
        {{$title}}
    </h3>
    <table id="table_exchange" class="table table-striped table-hover">
        <thead>
        <tr>
            <th>ID</th>
            <th>Обменный пункт</th>
            <th>Статус</th>
            <th>Город</th>
            <th>Владелец</th>
            <th>Дата создания</th>
            <th>Дата обновления</th>
        </tr>
        </thead>
        <tbody></tbody>
    </table>
@endsection

@section('scripts')
    <script>
        var oTable;
        $(document).ready(function () {
            oTable = $('#table_exchange').DataTable({
                "sDom": "<'row'<'col-md-6'l><'col-md-6'f>r>t<'row'<'col-md-6'i><'col-md-6'p>>",
                "sPaginationType": "bootstrap",
                "oLanguage": {
                    "sProcessing": "",
                    "sLengthMenu": "Show _MENU_ elements",
                    "sZeroRecords": "There is no match found",
                    "sInfo": "Showing _START_ do _END_ of _TOTAL_ elements",
                    "sEmptyTable": "",
                    "sInfoEmpty": "Showing 0 to 0 of a total of 0 elements",
                    "sInfoFiltered": "(filtered from a total of _MAX_ elements)",
                    "sInfoPostFix": "",
                    "sSearch": "Поиск:",
                    "sUrl": "",
                    "oPaginate": {
                        "sFirst": "",
                        "sPrevious": "",
                        "sNext": "",
                        "sLast": ""
                    }
                },
                "processing": true,
                "serverSide": true,
                "ajax": "exchange/data",
                "order": [[ 5, "desc" ]],
                columns: [
                    {data: 'id', name: 'exchangers.id'},
                    {data: 'title', name: 'exchangers.title'},
                    {data: 'is_visible', name: 'exchangers.is_visible'},
                    {data: 'name', name: 'cities.name'},
                    {data: 'username', name: 'users.username'},
                    {data: 'created_at', name: 'exchangers.created_at'},
                    {data: 'updated_at', name: 'exchangers.updated_at'},
                    {data: 'user_id', name: 'user_id', 'visible': false},
                    {data: 'address', name: 'address', 'visible': false},
                    {data: 'phones', name: 'phones', 'visible': false},
                ]
            });
        });
    </script>
@stop
