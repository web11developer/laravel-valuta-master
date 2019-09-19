@extends('layouts.app')
@section('title') Редктирование профиля пользователя :: @parent @stop
@section('content')
    <div class="col-md-10">
        <div class="page-header">
                <h2>Редактирование профиля: {!!  $user->username !!}</h2>
        </div>
        <div class="page-content">
            @if (isset($user))
                {!! Form::model($user, array('url' => URL::to('user') . '/' . $user->id, 'method' => 'put', 'class' => 'bf', 'files'=> true)) !!}
            @else
                {!! Form::open(array('url' => URL::to('user'), 'method' => 'post', 'class' => 'bf', 'files'=> true)) !!}
            @endif

            <div class="form-group {{ $errors->has('username') ? 'has-error' : '' }}">
                {!! Form::label('username', "Логин", array('class' => 'control-label')) !!}
                <div class="controls">
                    {!! Form::text('username', null, array('class' => 'form-control')) !!}
                    <span class="help-block">{{ $errors->first('username', ':message') }}</span>
                </div>
            </div>
            <div class="form-group {{ $errors->has('email') ? 'has-error' : '' }}">
                {!! Form::label('email', "E-mail", array('class' => 'control-label')) !!}
                <div class="controls">
                    {!! Form::text('email', null, array('class' => 'form-control')) !!}
                    <span class="help-block">{{ $errors->first('email', ':message') }}</span>
                </div>
            </div>
            <div class="form-group {{ $errors->has('password') ? 'has-error' : '' }}">
                {!! Form::label('password', "Новый пароль", array('class' => 'control-label')) !!}
                <div class="controls">
                    {!! Form::password('password', array('class' => 'form-control')) !!}
                    <span class="help-block">{{ $errors->first('password', ':message') }}</span>
                </div>
            </div>
            <div class="form-group {{ $errors->has('password_confirmation') ? 'has-error' : '' }}">
                {!! Form::label('password_confirmation', "Повторить пароль", array('class' => 'control-label')) !!}
                <div class="controls">
                    {!! Form::password('password_confirmation', array('class' => 'form-control')) !!}
                    <span class="help-block">{{ $errors->first('password_confirmation', ':message') }}</span>
                </div>
            </div>

                <div class="form-group {{ $errors->has('diff_exchange') ? 'has-error' : '' }}">
                    <div class="controls">
                        {!! Form::label('diff_exchange', "Показать разницу текущей и старой валюты") !!}
                        {!!   Form::checkbox('diff_exchange', 1, isset($user) && $user->diff_exchange == 1 ? true : false  ) !!}
                        <span class="help-block">{{ $errors->first('diff_exchange', ':message') }}</span>
                    </div>
                </div>



            <div class="form-group">
                <button type="reset" class="btn btn-sm btn-default">
                    <span class="glyphicon glyphicon-remove-circle"></span>
                    Сбросить
                </button>
                <button type="submit" class="btn btn-sm btn-success">
                    <span class="glyphicon glyphicon-ok-circle"></span>
                    Сохранить
                </button>
            </div>
            {!! Form::close() !!}
        </div>
    </div>
@stop
