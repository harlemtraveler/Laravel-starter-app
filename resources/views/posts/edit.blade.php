@extends('layouts.app')

@section('content')
  <h1>Edit Post</h1>
  {{-- Placed "PostsController@update" in an array & passed "$post->id" to target specific post. --}}
  {{-- Cannot use a PUT or PATCH with Laravel. We will spoof the HTTP request with a hidden input below. --}}
  {!! Form::open(['action' => ['PostsController@update', $post->id], 'method' => 'POST']) !!}
    <div class="form-group">
      {{Form::label('title', 'Title')}}
      {{Form::text('title', $post->title, ['class' => 'form-control', 'placeholder' => 'Title'])}}
    </div>

    <div class="form-group">
      {{Form::label('body', 'Body')}}
      {{Form::textarea('body', $post->body, ['id' => 'article-ckeditor', 'class' => 'form-control', 'placeholder' => 'Body Text'])}}
    </div>
    {{-- A "hidden input" to spoof the above HTTP POST into a PUT request --}}
    {{Form::hidden('_method','PUT')}}
    {{Form::submit('Submit', ['class' => 'btn btn-primary'])}}
  {!! Form::close() !!}
@endsection
