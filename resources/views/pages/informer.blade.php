@extends('layouts.app')
@section('title') Валютный информер :: @parent @stop
@section('content')
    <div class="col-md-10">
        <div class="page-header">
            <h2>Валютный информер</h2>
        </div>
        <div class="page-content">
<p><img src="http://www.valuta.kz/exchangerate/informer" title="Рынок наличной валюты" hspace="10" align="left">Предлагаем Вашему вниманию валютный <b>информер PortEx</b>, который Вы можете установить на свой сайт. Для этого необходимо включить на Ваши страницы следующий код: <pre>&lt;a href="http://www.valuta.kz" target="_blank"&gt;<br>&lt;img src="http://www.valuta.kz/exchangerate/informer"<br>title="Рынок наличной валюты"&gt;&lt;/a&gt;</pre></p>
        </div>
    </div>
@endsection

@section('right_sidebar')
    @include('partials.right_column')
@stop