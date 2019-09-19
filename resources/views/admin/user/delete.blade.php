@extends('admin.layouts.default')
@section('main')
	{!! Form::model($user, array('url' => URL::to('admin/user') . '/' . $user->id, 'method' => 'delete', 'class' => 'bf', 'files'=> true)) !!}
	<div class="form-group">
		<div class="controls">
			<button type="submit" class="btn btn-sm btn-danger">
				<span class="glyphicon glyphicon-trash"></span> Удалить
			</button>
		</div>
	</div>
	{!! Form::close() !!}
@stop
