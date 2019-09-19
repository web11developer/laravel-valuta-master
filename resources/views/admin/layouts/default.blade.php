<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>@section('title') Administration @show</title>

    <link href="{{ asset('vendor/bootstrap/dist/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/admin.min.css') }}" rel="stylesheet">
    <link href="{{ asset('vendor/eonasdan-bootstrap-datetimepicker/build/css/bootstrap-datetimepicker.min.css') }}" rel="stylesheet">
    {{--    <script src="{{ asset('js/admin.js') }}"></script>--}}
    <script src="{{ asset('js/jquery.min.js') }}"></script>
    <script src="{{ asset('js/plugins/bootstrap.min.js') }}"></script>
    {{--    <script src="{{ asset('js/plugins/jquery.colorbox-min.js') }}"></script>--}}
    <script src="{{ asset('js/plugins/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('js/plugins/dataTables.bootstrap.min.js') }}"></script>
    <script src="{{ asset('js/plugins/datatables-bootstrap3.min.js') }}"></script>
    <script src="{{ asset('js/plugins/dataTables.responsive.js') }}"></script>
    <script src="{{ asset('js/plugins/metisMenu.min.js') }}"></script>
    {{--    <script src="{{ asset('js/plugins/summernote.min.js') }}"></script>--}}
    <script src="{{ asset('js/plugins/select2.min.js') }}"></script>

    <script src="{{ asset('js/plugins/bootstrap-dataTables-paging.js') }}"></script>
    <script src="{{ asset('js/plugins/dataTables.bootstrap.js') }}"></script>
    <script src="{{ asset('js/plugins/datatables.fnReloadAjax.js') }}"></script>

    <script src="{{ asset('js/plugins/sb-admin-2.js') }}"></script>
    <script src="{{ asset('vendor/moment/min/moment.min.js') }}"></script>
    <script src="{{ asset('vendor/moment/locale/ru.js') }}"></script>
    <script src="{{ asset('vendor/eonasdan-bootstrap-datetimepicker/build/js/bootstrap-datetimepicker.min.js') }}"></script>
    <link rel="stylesheet" href="{{ asset('css/font-awesome-4.4.0/css/font-awesome.min.css') }}">
    @yield('styles')
</head>
<body>
<div id="wrapper">
    @include('admin.partials.nav')
    <div id="page-wrapper">
        @yield('main')
    </div>
</div>


@yield('scripts')
</body>
</html>