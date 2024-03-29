@extends('admin.layouts.default')
{{-- Content --}}
@section('main')

    @if (isset($articlecategory))
        {!! Form::model($articlecategory, array('url' => URL::to('admin/articlecategory') . '/' . $articlecategory->id, 'method' => 'put', 'class' => 'bf', 'files'=> true)) !!}
    @else
        {!! Form::open(array('url' => URL::to('admin/articlecategory'), 'method' => 'post', 'class' => 'bf', 'files'=> true)) !!}
    @endif

    <div class="form-group  {{ $errors->has('language_id') ? 'has-error' : '' }}">
        {!! Form::label('language_id', trans("admin/admin.language"), array('class' => 'control-label')) !!}
        <div class="controls">
            {!! Form::select('language_id', $languages, isset($articlecategory)? $articlecategory->language_id : 'default', array('class' => 'form-control')) !!}
            <span class="help-block">{{ $errors->first('language_id', ':message') }}</span>
        </div>
    </div>

    <div class="form-group  {{ $errors->has('title') ? 'has-error' : '' }}">
        {!! Form::label('title', trans("admin/modal.title"), array('class' => 'control-label')) !!}
        <div class="controls">
            {!! Form::text('title', null, array('class' => 'form-control')) !!}
            <span class="help-block">{{ $errors->first('title', ':message') }}</span>
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
                @if (isset($newscategory))
                    {{ trans("admin/modal.edit") }}
                @else
                    {{trans("admin/modal.create") }}
                @endif
            </button>
        </div>
    </div>

    {!! Form::close() !!}
@stop
