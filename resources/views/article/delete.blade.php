@extends('layouts.app')
@section('content')
    <div class="col-sm-12">
        <div class="page-header">
            <h4>Подтвердите удаление: {!! $article->title !!}</h4>
        </div>
        {!! Form::model($article, array('url' => URL::to('article') . '/' . $article->id, 'method' => 'delete', 'class' => 'bf', 'files'=> true)) !!}
        <div class="form-group">
            <div class="controls">
                <button type="submit" class="btn btn-sm btn-danger">
                    <span class="glyphicon glyphicon-trash"></span>
                    Удалить
                </button>
            </div>
        </div>
        {!! Form::close() !!}
    </div>
@stop
