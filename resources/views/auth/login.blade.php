@extends('layouts.app')

{{-- Web site Title --}}
@section('title') {!!  trans('site/users.login') !!} :: @parent @stop

{{-- Content --}}
@section('content')
    <div class="col-md-10">
        <div class="page-header">
            <h2>{!! trans('site/users.login_to_account') !!}</h2>
        </div>
        <div class="page-container">
            <div class="col-md-4 col-md-offset-4">
                {!! Form::open(array('url' => URL::to('auth/login'), 'method' => 'post', 'files'=> true)) !!}
                <div class="form-group  {{ $errors->has('login') ? 'has-error' : '' }}">
                    {!! Form::label('login', "Логин", array('class' => 'control-label')) !!}
                    <div class="controls">
                        {!! Form::text('login', null, array('class' => 'form-control')) !!}
                        <span class="help-block">{{ $errors->first('login', ':message') }}</span>
                    </div>
                </div>
                <div class="form-group  {{ $errors->has('password') ? 'has-error' : '' }}">
                    {!! Form::label('password', "Пароль", array('class' => 'control-label')) !!}
                    <div class="controls">
                        {!! Form::password('password', array('class' => 'form-control')) !!}
                        <span class="help-block">{{ $errors->first('password', ':message') }}</span>
                    </div>
                </div>
                <div class="form-group">
                    <p>
                        Если у Вас возникли проблемы со входом на сайт, обращайтесь по тел: <br>
                        8 (701) 082-75-30,
                        8 (727) 392-05-61
                    </p>
                </div>
                <div class="form-group">
                    <div class="checkbox">
                        <label>
                            <input type="checkbox" name="remember"> Запомнить меня
                        </label>
                    </div>
                </div>

                <div class="form-group">
                    <button type="submit" class="btn btn-primary" style="margin-right: 15px;">
                        Войти
                    </button>
                    <a href="{{ URL::to('/password/email') }}">Восстановление пароля</a>
                </div>
                {!! Form::close() !!}
            </div>
        </div>
    </div>
@endsection
@section('right_sidebar')
    @include('partials.right_column')
@stop
