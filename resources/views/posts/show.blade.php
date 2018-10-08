@extends('layouts.app')

@section('content')
  <h1>{{$post->title}}</h1>
  <div>
    {{-- use "{!!...!!}" instead of "{{...}}" to parse HTML values --}}
    {!!$post->body!!}
  </div>
  <hr>
  <small>Written on {{$post->created_at}}</small>
  <br>
  <br>
  <br>
  <a href="/posts" class="btn btn-secondary">Go Back</a>
@endsection
