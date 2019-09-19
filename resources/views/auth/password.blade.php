@extends('layouts.app')

@section('content')
    <div class="col-md-10">
        <div class="col-md-4 col-md-offset-4">
            <br>
            <br>
            <div class="panel panel-warning">
                <div class="panel-heading">Воссстанволение пароля</div>
                <div class="panel-body">
                    {!! Form::open(array('url' => URL::to('password/email'), 'method' => 'post', 'files'=> true)) !!}
                    <div class="form-group  {{ $errors->has('email') ? 'has-error' : '' }}">
                        {!! Form::label('email', "E-Mail адрес", array('class' => 'control-label')) !!}
                        <div class="controls">
                            {!! Form::text('email', null, array('class' => 'form-control')) !!}
                            <span class="help-block">{{ $errors->first('email', ':message') }}</span>
                        </div>
                    </div>
                    <div class="form-group text-center">
                            <button type="submit" class="btn btn-primary">
                                Отправить подтверждение
                            </button>
                    </div>
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
@endsection

@section('right_sidebar')
    @include('partials.right_column')
@stop
