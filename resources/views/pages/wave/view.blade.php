@extends('layouts.app')
{{-- Web site Title --}}
@section('title')  {{ $titlePage }} :: @parent / {!!  $data->title !!}  @stop

{{-- Content --}}
@section('content')
    <style>
        img.inline {
            display: block;
            max-width: 100%;
        }
    </style>
    <div class="col-md-10">
        <div class="page-header">
            <h4>{{ $data->title }}</h4>
        </div>
        <div class="page-content">
            <p>{!! $data->news_body_value !!}</p>

            <div>
                <span class="badge badge-info">
                    <i class="glyphicon glyphicon-time"></i> {!! Date('d.m.Y h:i', $data->created) !!} </span>
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
            this.page.identifier = '{!! $page !!}';
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
