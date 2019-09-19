@extends('layouts.app')
@section('title')
    {{$titlePage}} :: @parent
@stop

@section('content')
    <div class="col-md-10">
        <div class="page-header">
            <h2>{{$titlePage}}</h2>
        </div>
        <div class="page-content">
            <div class="row">
                @foreach($data as $item)
                    <div class="col-md-12">
                        <div class="news-content">
                            <h3>
                                <a href="{{ URL::to('wave/'.$page.'/'.$item->nid) }}">
                                    {{ $item->title }}
                                </a>
                            </h3>
                            <p>{!! $function_text($item->news_body_value) !!}</p>
                            <div class="news-footer">
                                <i class="glyphicon glyphicon-time"></i> {!! Date('d.m.Y h:i', $item->created) !!}
                                <div class="pull-right">
                                    <a class="btn btn-link"
                                       href="{{ URL::to('wave/'.$page.'/'.$item->nid) }}">Читать</a>
                                </div>
                            </div>
                            <div class="clearfix"></div>
                        </div>
                    </div>
                @endforeach
                {!! $data->render() !!}
            </div>
        </div>
    </div>
@stop


@section('right_sidebar')
    @include('partials.right_column')
@stop