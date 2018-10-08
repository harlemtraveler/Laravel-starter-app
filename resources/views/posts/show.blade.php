@extends('layouts.app')
{{ Html::style('../../sass/app.scss') }}

@section('content')
  <h1>{{$post->title}}</h1>
@endsection
