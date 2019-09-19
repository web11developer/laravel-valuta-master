@extends('admin.layouts.default')

@section('main')

    @if (isset($user))
        {!! Form::model($user, array('url' => URL::to('admin/user') . '/' . $user->id, 'method' => 'put', 'class' => 'bf', 'files'=> true)) !!}
    @else
        {!! Form::open(array('url' => URL::to('admin/user'), 'method' => 'post', 'class' => 'bf', 'files'=> true)) !!}
    @endif

    <div class="form-group  {{ $errors->has('username') ? 'has-error' : '' }}">
        {!! Form::label('username', trans("admin/users.username"), array('class' => 'control-label')) !!}
        <div class="controls">
            {!! Form::text('username', null, array('class' => 'form-control')) !!}
            <span class="help-block">{{ $errors->first('username', ':message') }}</span>
        </div>
    </div>
    <div class="form-group  {{ $errors->has('max_exchangers') ? 'has-error' : '' }}">
        {!! Form::label('max_exchangers', "Ограничение по обменным пунктам", array('class' => 'control-label')) !!}
        <div class="controls">
            {!! Form::text('max_exchangers', null, array('class' => 'form-control')) !!}
            <span class="help-block">{{ $errors->first('max_exchangers', ':message') }}</span>
        </div>
    </div>
    <div class="form-group  {{ $errors->has('email') ? 'has-error' : '' }}">
        {!! Form::label('email', trans("admin/users.email"), array('class' => 'control-label')) !!}
        <div class="controls">
            {!! Form::text('email', null, array('class' => 'form-control')) !!}
            <span class="help-block">{{ $errors->first('email', ':message') }}</span>
        </div>
    </div>
    <div class="form-group  {{ $errors->has('password') ? 'has-error' : '' }}">
        {!! Form::label('password', trans("admin/users.password"), array('class' => 'control-label')) !!}
        <div class="controls">
            {!! Form::password('password', array('class' => 'form-control')) !!}
            <span class="help-block">{{ $errors->first('password', ':message') }}</span>
        </div>
    </div>
    <div class="form-group  {{ $errors->has('password_confirmation') ? 'has-error' : '' }}">
        {!! Form::label('password_confirmation', trans("admin/users.password_confirmation"), array('class' => 'control-label')) !!}
        <div class="controls">
            {!! Form::password('password_confirmation', array('class' => 'form-control')) !!}
            <span class="help-block">{{ $errors->first('password_confirmation', ':message') }}</span>
        </div>
    </div>
    <div class="form-group  {{ $errors->has('expire') ? 'has-error' : '' }}">
        {!! Form::label('expire', trans("admin/users.expire"), array('class' => 'control-label')) !!}
        <div class="controls" style="position: relative">
            {!! Form::text('expire', null, array('class' => 'form-control', 'id'=>'datetimepicker2')) !!}
            <span class="help-block">{{ $errors->first('expire', ':message') }}</span>
        </div>
    </div>
    <div class="form-group  {{ $errors->has('confirmed') ? 'has-error' : '' }}">
        {!! Form::label('confirmed', trans("admin/users.active_user"), array('class' => 'control-label')) !!}
        <div class="controls">
            {!! Form::label('confirmed', trans("admin/users.yes"), array('class' => 'control-label')) !!}
            {!! Form::radio('confirmed', '1', isset($user)? $user->confirmed : 'false') !!}
            {!! Form::label('confirmed', trans("admin/users.no"), array('class' => 'control-label')) !!}
            {!! Form::radio('confirmed', '0', isset($user)? $user->confirmed : 'true') !!}
            <span class="help-block">{{ $errors->first('confirmed', ':message') }}</span>
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
@stop
@section('scripts')
    <script type="text/javascript">
        $(function () {
            $('#datetimepicker2').datetimepicker({
                locale: 'ru',
                showClear: true,
                format: 'YYYY-MM-DD HH:mm:ss'
            });
        });
    </script>
@stop