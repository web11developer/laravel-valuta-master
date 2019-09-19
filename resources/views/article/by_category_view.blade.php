@extends('layouts.app')
{{-- Web site Title --}}
@section('title')
    {!! $articles->title !!} :: @parent
@stop

@section('content')
    <div class="col-md-10">
        <div class="page-header">
            <h2>{!! $articles->title !!}</h2>
        </div>
        @if (Session::has('message'))
            <div class="alert alert-info">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                <p><i class="glyphicon glyphicon-info-sign"></i> {{ Session::get('message') }}</p>
            </div>
        @endif
        <div class="page-content">
            <div class="row">
                <style>
                    .MsoNormal {
                        margin: 9pt 10.85pt 10.0pt 1.7pt !important;
                        text-align: justify !important;
                        text-indent: 28.4pt !important;
                    }
                </style>
                @foreach($articles as $item)
                    <div class="col-md-12">
                        <div class="news-content">
                            <h3>
                                <a href="{{ URL::to('article/'.$item->slug) }}">
                                    {{ $item->title }}
                                </a>
                            </h3>
                            <p>{!! $function_text($item->content) !!}</p>
                            <div class="news-footer">
                                <i class="glyphicon glyphicon-time"></i> {!! $item->created_at !!}
                                <div class="pull-right">
                                    <a class="btn btn-link" href="{{ URL::to('article/'.$item->slug) }}">Читать</a>
                                </div>
                            </div>
                            <div class="clearfix"></div>
                        </div>
                    </div>
                @endforeach
                {!! $articles->render() !!}
            </div>
        </div>
    </div>
@stop


@section('right_sidebar')
    @include('partials.right_column')
@stop