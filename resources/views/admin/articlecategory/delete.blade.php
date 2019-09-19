@extends('admin.layouts.default')
@section('main')
    {!! Form::model($articlecategory, array('url' => URL::to('admin/articlecategory') . '/' . $articlecategory->id, 'method' => 'delete', 'class' => 'bf', 'files'=> true)) !!}
    <div class="form-group">
        <div class="controls">
            {{ trans("admin/modal.delete_message") }}<br>
            <button type="submit" class="btn btn-sm btn-danger">
                <span class="glyphicon glyphicon-trash"></span> {{
				trans("admin/modal.delete") }}
            </button>
        </div>
    </div>
    {!! Form::close() !!}
@stop
