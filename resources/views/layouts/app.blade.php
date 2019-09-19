<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="google-site-verification" content="YbZMs9oIvFUpZnsATZg80rW37S7SfC4-5y8x26tFZDs">

    <title>@section('title') Valuta.kz @show</title>
    @section('meta_author')
        <meta name="author" content=""/>
    @show
    @section('meta_description')
        <meta name="description" content="Валютный рынок Казахстана - евро доллар сша, тенге.
        Прогноз валют. Продажа и покупка наличной валюты в обменных пунктах. Курс обмена валют."/>
    @show
    @section('meta_keywords')
        <meta name="keywords" content="рынок, evro, usd, dollar, sum, tenge, wmr, wmz, kaspi, almati, евро, bank, kurs,  доллар, сша, тенге, курс, обмен, валют"/>
    @show
    <link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/valuta.min.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/amsify.suggestags.css') }}">

    @yield('styles')

    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

    <link rel="shortcut icon" href="{!! asset('favicon.ico')  !!} ">

<script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-3332788-5', 'auto');
  ga('send', 'pageview');

</script>
</head>
<body>
@include('partials.nav')
{{--@include('partials.header')--}}

        <!-- BANNER PLACE -->
<div class="banner-place hidden-xs center-block">
    <iframe src="/banner.html" frameborder="0" width="681" height="70" scrolling="no" title="Албан Exchange"></iframe>
</div>
<div class="container">
    <div class="row">
        @yield('content')
        @yield('right_sidebar')
    </div>
</div>
@include('partials.footer')

<script src="{{ asset('js/jquery.min.js') }}"></script>
<script src="{{ asset('js/bootstrap.min.js') }}"></script>
<script src="{{ asset('js/jquery.amsify.suggestags.js') }}"></script>

<!-- Scripts -->
@yield('scripts')


<style>
    .popover{
        max-width: 400px; !important;
    }
     .view-calc{
         color: #337AB7;
         cursor: pointer;
         text-decoration: none;
         font-weight: 500;
     }
.calc{
    text-decoration: none !important;
}
</style>
</body>
</html>

