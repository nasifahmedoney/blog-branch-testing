@extends('layout')

{{-- @section('banner')
    <h1 style="text-align: center">my blog</h1>
@endsection --}}

@section('content')
    @foreach ($posts_var_from_route as $post)
    <article>
        <a href="/post/{{$post->id}}"><h1>{{$post->title}}</h1></a>
        <p>{!! $post->body !!}</p> <!-- !! for fetching html content --> 
    </article>
    @endforeach
@endsection
    
  

