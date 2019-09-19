@extends('layouts.app')
{{-- Web site Title --}}
@section('title') {!!  $article->title !!} :: @parent @stop

@section('meta_author')
    <meta name="author" content="{!! $article->author->username !!}"/>
@stop
{{-- Content --}}
@section('content')
    <style>
        .MsoNormal {
            margin: 9pt 10.85pt 10.0pt 1.7pt !important;
            text-align: justify !important;
            text-indent: 28.4pt !important;
        }
    </style>
    <div class="col-md-10">
        <div class="page-header">
            @if(Auth::check() && (Auth::user()->admin==1 || in_array("article", explode(",", Auth::user()->access))))
                <div class="pull-right">
                    <a class="btn btn-warning" href="{{ URL::to('article/'.$article->id.'/edit') }}">
                        <i class="glyphicon glyphicon-pencil"></i> Редактировать
                    </a>
                    @if(Auth::check() && Auth::user()->admin==1)
                    <a class="btn btn-danger" href="{{ URL::to('article/'.$article->id.'/delete') }}">
                        <i class="glyphicon glyphicon-pencil"></i> Удалить
                    </a>
                    @endif
                </div>
            @endif
            <h3>{{ $article->title }}</h3>


        </div>
        <div class="page-content">
            <p>{!! $article->content !!}</p>
            <div>
                <span class="badge badge-info">Дата {!!  $article->created_at !!} </span>
            </div>
            <br>
            <br>
            <div class="comments">
                <div id="disqus_thread"></div>
            </div>
        </div>
    </div>
@stop

@section('right_sidebar')
    @include('partials.right_column')
@stop
@section('scripts')
    <script>
        var disqus_config = function () {
            this.page.url = '{!! Request::url() !!}';
            this.page.identifier = '{!! $article->slug !!}';
        };

        (function () {
            var d = document, s = d.createElement('script');

            s.src = '//valutakz.disqus.com/embed.js';

            s.setAttribute('data-timestamp', +new Date());
            (d.head || d.body).appendChild(s);
        })();
    </script>
    <noscript>
        Please enable JavaScript to view the <a href="https://disqus.com/?ref_noscript" rel="nofollow">
            comments powered by Disqus.</a>
    </noscript>
    <script id="dsq-count-scr" src="//valutakz.disqus.com/count.js" async></script>
@stop
