@extends('layouts.app')
{{-- Web site Title --}}
@section('title')  Сессии KASE :: @parent / {!!  $kase->title !!}  @stop

{{-- Content --}}
@section('content')
    <div class="col-md-10">
        <div class="page-header">
            <h4>{{ $kase->title }}</h4>
        </div>
        <div class="page-content">
            <p>{!! $kase->news_body_value !!}</p>

            <div>
                <span class="badge badge-info">
                    <i class="glyphicon glyphicon-time"></i> {!! Date('d.m.Y h:i', $kase->created) !!} </span>
            </div>
        </div>
    </div>
@stop

@section('right_sidebar')
    @include('partials.right_column')
@stop