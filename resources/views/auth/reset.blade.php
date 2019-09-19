@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-4 col-md-offset-4">
                <br>
                <br>
                <div class="panel panel-default">
                    <div class="panel-heading">Восстановление пароля</div>
                    <div class="panel-body">
                        {!! Form::open(array('url' => URL::to('password/reset'), 'method' => 'post', 'files'=> true)) !!}
                        {!! Form::hidden('token', $token) !!}
                        <div class="form-group  {{ $errors->has('name') ? 'has-error' : '' }}">
                            {!! Form::label('email', "E-Mail адрес", array('class' => 'control-label')) !!}
                            <div class="controls">
                                {!! Form::text('email', null, array('class' => 'form-control')) !!}
                                <span class="help-block">{{ $errors->first('email', ':message') }}</span>
                            </div>
                        </div>
                        <div class="form-group  {{ $errors->has('password') ? 'has-error' : '' }}">
                            {!! Form::label('password', "Пароль", array('class' => 'control-label')) !!}
                            <div class="controls">
                                {!! Form::password('password', array('class' => 'form-control')) !!}
                                <span class="help-block">{{ $errors->first('password', ':message') }}</span>
                            </div>
                        </div>
                        <div class="form-group  {{ $errors->has('password_confirmation') ? 'has-error' : '' }}">
                            {!! Form::label('password_confirmation', "Повторить пароль", array('class' => 'control-label')) !!}
                            <div class="controls">
                                {!! Form::password('password_confirmation', array('class' => 'form-control')) !!}
                                <span class="help-block">{{ $errors->first('password_confirmation', ':message') }}</span>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="text-center">
                                <button type="submit" class="btn btn-primary">
                                    Восстановить!
                                </button>
                            </div>
                        </div>
                        {!! Form::close() !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
