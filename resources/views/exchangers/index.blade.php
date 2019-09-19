@extends('layouts.app')
{{-- Web site Title --}}
@section('title')
    Мои обменные пункты :: @parent
@stop

@section('content')
    <div class="col-sm-12">
        <div class="page-header">
            <a href="{{ URL::to('exchange/'.$user->id.'/create') }}" class="btn btn-success pull-right">
                <i class="glyphicon glyphicon-ok"></i> Новый пункт обмена
                <small> (Осталось: <b>{!! $user->max_exchangers - count($exchangers) !!}</b>)</small>
            </a>
            <h2>Обменные пункты ({!! count($exchangers) !!}): {!! $user->username !!}</h2>
        </div>
        @if (Session::has('message'))
            <div class="alert alert-info">
                <p><i class="glyphicon glyphicon-info-sign"></i> {{ Session::get('message') }}</p>
            </div>
        @endif
        <div class="page-content">
            <div class="row">
                @foreach($exchangers as $idx => $item)
                    @if($idx % 4 == 0)
                        <div class="clearfix"></div>
                    @endif
                    <div class="col-md-3 col-sm-6">
                        <div class="exchange-sidebar thumbnail">
                            <div class="exchange-pic">
                                @if ($item->picture)
                                    <img src="/images/exchange/{!! $item->id !!}/{!! $item->picture !!}"
                                         class="img-responsive" alt="">
                                @else
                                    <img src="/img/no_photo.png"
                                         class="img-responsive" alt="">
                                @endif
                            </div>
                            <div class="exchange-title">
                                <div class="exchange-title-name">
                                    {!! $item->title !!}
                                </div>
                                <div class="exchange-title-job">
                                    {!! $item->cities->name !!}
                                </div>
                            </div>

                            @if($item->is_visible)
                                <div class="exchange-buttons">
                                    <a href="{{ URL::to('cash/'.$item->id.'/show') }}" class="btn btn-success btn-sm">
                                        Обновить курс</a>
                                </div>
                            @else
                                <div class="alert alert-danger">
                                    <strong>Обменный пункт не активирован</strong>
                                </div>
                            @endif
                            <div class="exchange-menu">
                                <ul class="nav">
                                    <li>
                                        <a href="{{ URL::to('exchange/'.$item->id.'/show') }}">
                                            <i class="glyphicon glyphicon-home"></i>
                                            Страница
                                        </a>
                                    </li>
                                    {{--<li>
                                        <a href="{{ URL::to('exchange/map') }}">
                                            <i class="glyphicon glyphicon-map-marker"></i>
                                            На карте
                                        </a>
                                    </li>--}}
                                    <li>
                                        <a href="{{ URL::to('exchange/'.$item->id.'/edit') }}">
                                            <i class="glyphicon glyphicon-pencil"></i>
                                            Настройки
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@stop
