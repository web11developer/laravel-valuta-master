@extends('layouts.app')
{{-- Web site Title --}}
@section('title') Управление курсом :: @parent @stop

{{-- Content --}}
@section('content')
    <div class="row">
        <div class="page-header">
            <h2>{!! $exchange->title !!} <small>Управление курсом</small></h2>
            @if (Session::has('message'))
                <div class="alert alert-info">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                    <p><i class="glyphicon glyphicon-info-sign"></i> {{ Session::get('message') }}</p>
                </div>
            @endif
        </div>
        <div class="page-content">
            {!! Form::model($cash, array('url' => URL::to('cash') . '/' . $exchange->id, 'method' => 'put', 'class' => 'bf', 'files'=> true)) !!}
            {!! Form::hidden('exchange_id', $exchange->id, array('class' => 'form-control')) !!}

            <div class="center-block text-center">
            <table class="table-bordered table-hover table-striped table-condensed">
                <thead>
                <tr>
                    <th>Покупка</th>
                    <th class="text-center">Валюта</th>
                    <th>Продажа</th>
                </tr>
                </thead>
                <tbody>
                @foreach($currency as $idx => $item)

                    <tr>
                        <td>
                            <div class="form-group  {{ $errors->has(strtolower($item->code) .'_buy') ? 'has-error' : '' }}">
                                <div class="controls">
                                    {!! Form::text('items['.strtolower($item->code) .'_buy'.']', count($rates_json) >0 &&isset($rates_json[strtolower($item->code).'_buy'])?$rates_json[strtolower($item->code).'_buy']:null, array('class' => 'form-control')) !!}
                                    <span class="help-block">{{ $errors->first(strtolower($item->code) .'_buy', ':message') }}</span>
                                </div>
                            </div>
                        </td>
                        <td class="text-center text-success"><b>{!! $item->code !!}</b></td>
                        <td>
                            <div class="form-group  {{ $errors->has(strtolower($item->code) .'_sell') ? 'has-error' : '' }}">
                                <div class="controls">
                                    {!! Form::text('items['.strtolower($item->code) .'_sell'.']', count($rates_json) >0 &&isset($rates_json[strtolower($item->code).'_sell'])?$rates_json[strtolower($item->code).'_sell']:null, array('class' => 'form-control')) !!}
                                    <span class="help-block">{{ $errors->first(strtolower($item->code) .'_sell', ':message') }}</span>
                                </div>
                            </div>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
            </div>
            <div class="form-group pull-right">
                <button type="reset" class="btn btn-sm btn-default">
                    <span class="glyphicon glyphicon-remove-circle"></span> Сбросить
                </button>
                <button type="submit" class="btn btn-sm btn-success">
                    <span class="glyphicon glyphicon-ok-circle"></span> Сохранить
                </button>
            </div>


            {!! Form::close() !!}
        </div>
    </div>
@stop

@section('scripts')
    <script>

    </script>
@stop