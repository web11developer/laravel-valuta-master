@extends('layouts.app')
@section('title')
    Прогнозы по паре EUR/USD :: @parent
@stop

@section('content')
    <div class="col-md-10">
        <div class="page-header">
            <h2>Прогнозы по паре EUR/USD</h2>
        </div>
        <div class="page-content">
            <div class="row">
                @foreach($kase as $item)
                    <div class="col-md-12">
                        <div class="news-content">
                            <h3>
                                <a href="{{ URL::to('forecasts-eur-usd/'.$item->nid) }}">
                                    {{ $item->title }}
                                </a>
                            </h3>
                            <p>{!! $function_text($item->news_body_value) !!}</p>
                            <div class="news-footer">
                                <i class="glyphicon glyphicon-time"></i> {!! Date('d.m.Y h:i', $item->created) !!}
                                <div class="pull-right">
                                    <a class="btn btn-link" href="{{ URL::to('forecasts-eur-usd/'.$item->nid) }}">Читать</a>
                                </div>
                            </div>
                            <div class="clearfix"></div>
                        </div>
                    </div>
                @endforeach
                {!! $kase->render() !!}
            </div>
        </div>
    </div>
@stop


@section('right_sidebar')
    @include('partials.right_column')
@stop