@extends('layouts.app')

{{-- Web site Title --}}
@section('title') {!! trans('site/users.register') !!} :: @parent @stop

{{-- Content --}}
@section('content')
    <div class="col-md-10">
        <div class="page-header">
            <h2>{!! trans('site/users.register') !!}</h2>
        </div>
        <div class="col-md-8 col-md-offset-2">
            {!! Form::open(array('url' => URL::to('auth/register'), 'method' => 'post', 'files'=> true)) !!}
            <fieldset>
                <legend>
                    Профиль <b>Владельца</b> обменного пункта <br>
                </legend>
            </fieldset>
            <div class="form-group {{ $errors->has('username') ? 'has-error' : '' }}">
                {!! Form::label('username', 'Логин', array('class' => 'control-label')) !!}
                <div class="controls">
                    {!! Form::text('username', null, array('class' => 'form-control')) !!}
                    <span class="help-block">{{ $errors->first('username', ':message') }}</span>
                </div>
                <span class="help-block">Может использоваться для входа</span>
            </div>
            <div class="form-group {{ $errors->has('email') ? 'has-error' : '' }}">
                {!! Form::label('email', trans('site/users.e_mail'), array('class' => 'control-label')) !!}
                <div class="controls">
                    {!! Form::text('email', null, array('class' => 'form-control')) !!}
                    <span class="help-block">{{ $errors->first('email', ':message') }}</span>
                </div>
            </div>
            <div class="form-group {{ $errors->has('password') ? 'has-error' : '' }}">
                {!! Form::label('password', "Пароль", array('class' => 'control-label')) !!}
                <div class="controls">
                    {!! Form::password('password', array('class' => 'form-control')) !!}
                    <span class="help-block">{{ $errors->first('password', ':message') }}</span>
                </div>
            </div>
            <div class="form-group {{ $errors->has('password_confirmation') ? 'has-error' : '' }}">
                {!! Form::label('password_confirmation', "Повторите пароль", array('class' => 'control-label')) !!}
                <div class="controls">
                    {!! Form::password('password_confirmation', array('class' => 'form-control')) !!}
                    <span class="help-block">{{ $errors->first('password_confirmation', ':message') }}</span>
                </div>
            </div>

            <fieldset>
                <legend>Ваш <b>обменный пункт</b></legend>
            </fieldset>

            <div class="form-group {{ $errors->has('exchange.title') ? 'has-error' : '' }}">
                {!! Form::label('exchange[title]', "Название обменного пункта", array('class' => 'control-label')) !!}
                <div class="controls">
                    {!! Form::text('exchange[title]', null, array('class' => 'form-control')) !!}
                    <span class="help-block">{{ $errors->first('exchange.title', ':message') }}</span>
                </div>
            </div>

            <div class="form-group  {{ $errors->has('exchange.email') ? 'has-error' : '' }}">
                {!! Form::label('exchange[email]', "E-mail", array('class' => 'control-label')) !!}
                <div class="controls">
                    {!! Form::text('exchange[email]', null, array('class' => 'form-control')) !!}
                    <span class="help-block">{{ $errors->first('exchange.email', ':message') }}</span>
                </div>
            </div>

            <div class="form-group  {{ $errors->has('exchange.phones') ? 'has-error' : '' }}">
                {!! Form::label('exchange[phones]', "Телефоны", array('class' => 'control-label')) !!}
                <div class="controls">
                    {!! Form::text('exchange[phones]', null, array('class' => 'form-control')) !!}
                    <span class="help-block">{{ $errors->first('exchange.phones', ':message') }}</span>
                </div>
            </div>

            <div class="form-group  {{ $errors->has('exchange.city') ? 'has-error' : '' }}">
                {!! Form::label('exchange[city]', "Город", array('class' => 'control-label')) !!}
                <div class="controls">
                    {!! Form::select('exchange[city]', $cities, isset($exchange)? $exchange->city : '1', array('class' => 'form-control')) !!}
                    <span class="help-block">{{ $errors->first('exchange.city', ':message') }}</span>
                </div>
            </div>

            <div class="form-group  {{ $errors->has('exchange.address') ? 'has-error' : '' }}">
                {!! Form::label('exchange[address]', "Адрес обменного пункта", array('class' => 'control-label')) !!}
                <div class="controls">
                    {!! Form::textarea('exchange[address]', null, array('class' => 'form-control', 'cols' => 10, 'rows'=>3)) !!}
                    <span class="help-block">{{ $errors->first('exchange.address', ':message') }}</span>
                </div>
            </div>

            <div class="form-group">
                <div class="text-center">
                    <button type="submit" class="btn btn-primary">
                        Регистрация
                    </button>
                </div>
            </div>
            {!! Form::close() !!}
        </div>
    </div>
@endsection
@section('right_sidebar')
    @include('partials.right_column')
@stop
