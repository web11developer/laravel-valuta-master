@extends('layouts.app')
@section('styles')
    <link rel="stylesheet" href="{{ asset('css/font-awesome-4.4.0/css/font-awesome.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/editor.min.css') }}">
@stop
{{-- Content --}}
@section('content')
    <div class="col-md-12">
        <div class="page-header">
            <h2>Создание новой статьи</h2>
        </div>
        @if (isset($article))
            {!! Form::model($article, array('url' => URL::to('article') . '/' . $article->id, 'method' => 'put','id'=>'fupload', 'class' => 'bf', 'files'=> true)) !!}
        @else
            {!! Form::open(array('url' => URL::to('article'), 'method' => 'post', 'class' => 'bf','id'=>'fupload', 'files'=> true)) !!}
        @endif

        <div class="form-group {{ $errors->has('language_id') ? 'has-error' : '' }}">
            {!! Form::label('language_id', "Язык", array('class' => 'control-label')) !!}
            <div class="controls">
                {!! Form::select('language_id', $languages, isset($article)? $article->language_id : 'default', array('class' => 'form-control')) !!}
                <span class="help-block">{{ $errors->first('language_id', ':message') }}</span>
            </div>
        </div>
        <div class="form-group {{ $errors->has('article_category_id') ? 'has-error' : '' }}">
            {!! Form::label('article_category_id', "Категория", array('class' => 'control-label')) !!}
            <div class="controls">
                {!! Form::select('article_category_id', $articlecategories, isset($article)? $article->article_category_id : '1', array('class' => 'form-control')) !!}
                <span class="help-block">{{ $errors->first('article_category_id', ':message') }}</span>
            </div>
        </div>
        <div class="form-group {{ $errors->has('title') ? 'has-error' : '' }}">
            {!! Form::label('title', "Название", array('class' => 'control-label')) !!}
            <div class="controls">
                {!! Form::text('title', null, array('class' => 'form-control')) !!}
                <span class="help-block">{{ $errors->first('title', ':message') }}</span>
            </div>
        </div>

        <div class="form-group {{ $errors->has('content') ? 'has-error' : '' }}">
            {!! Form::label('content', "Описание", array('class' => 'control-label')) !!}
            <div class="controls">
                {!! Form::textarea('content', null, array('class' => 'form-control', 'id'=>'content')) !!}
                <span class="help-block">{{ $errors->first('content', ':message') }}</span>
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
@stop
@section('scripts')
    <script src="{{ asset('js/summernote.min.js') }}"></script>
    <script>
        $('#content').summernote({height: 300});
    </script>
@stop
