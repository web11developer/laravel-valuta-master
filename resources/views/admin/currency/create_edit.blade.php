@extends('admin.layouts.default')
{{-- Content --}}
@section('main')

    @if (isset($currency))
        {!! Form::model($currency, array('url' => URL::to('admin/currency') . '/' . $currency->currency_id, 'method' => 'POST', 'class' => 'bf', 'files'=> true)) !!}
        @method('PUT')
    @else
        {!! Form::open(array('url' => URL::to('admin/currency'), 'method' => 'post', 'class' => 'bf', 'files'=> true)) !!}

    @endif

    <div class="form-group  {{ $errors->has('code') ? 'has-error' : '' }}">
        {!! Form::label('code', trans("admin/modal.code"), array('class' => 'control-label')) !!}
        <div class="controls">
            {!! Form::text('code', null, array('class' => 'form-control')) !!}
            <span class="help-block">{{ $errors->first('code', ':message') }}</span>
        </div>
    </div>

    <div class="form-group  {{ $errors->has('name') ? 'has-error' : '' }}">
        {!! Form::label('name', trans("admin/modal.name"), array('class' => 'control-label')) !!}
        <div class="controls">
            {!! Form::text('name', null, array('class' => 'form-control')) !!}
            <span class="help-block">{{ $errors->first('name', ':message') }}</span>
        </div>
    </div>

    <div class="form-group  {{ $errors->has('order_number') ? 'has-error' : '' }}">
        {!! Form::label('order_number', trans("admin/modal.order_number"), array('class' => 'control-label')) !!}
        <div class="controls">
            {!! Form::text('order_number', null, array('class' => 'form-control')) !!}
            <span class="help-block">{{ $errors->first('order_number', ':message') }}</span>
        </div>
    </div>

    <div class="form-group  {{ $errors->has('visible_bool') ? 'has-error' : '' }}">
        {!! Form::label('visible_bool', trans("admin/modal.visible_bool"), array('class' => 'control-label')) !!}
        <div class="controls">
            {!! Form::text('visible_bool', null, array('class' => 'form-control')) !!}
            <span class="help-block">{{ $errors->first('visible_bool', ':message') }}</span>
        </div>
    </div>


    <!-- Form Actions -->

    <div class="form-group">
        <div class="col-md-12">
            <button type="reset" class="btn btn-sm btn-default">
                <span class="glyphicon glyphicon-remove-circle"></span> {{
					trans("admin/modal.reset") }}
            </button>
            <button type="submit" class="btn btn-sm btn-success">
                <span class="glyphicon glyphicon-ok-circle"></span>
                @if (isset($currency))
                    {{ trans("admin/modal.edit") }}
                @else
                    {{trans("admin/modal.create") }}
                @endif
            </button>
        </div>
    </div>

    {!! Form::close() !!}
@stop
