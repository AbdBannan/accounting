{{--@extends('layout.master');--}}

<x-masterLayout.master>
    @section("title")
        {{ "edit post" }}
    @endsection

@section('content')

{{--<h1>update a post</h1>--}}
{{--<form action="/posts/{{$post->id}}" method="POST" accept-charset="UTF-8" enctype="multipart/form-data">--}}
{{--    {{csrf_field()}}--}}
{{--    <input type="hidden" name="_method" value="PUT">--}}
{{--    <div class="form-group">--}}
{{--        <label for="name">name</label>--}}
{{--        <input class="form-control" type="text" name="name" id="name" value="{{$post->name}}">--}}
{{--    </div>--}}
{{--     <div class="form-group">--}}
{{--         <input class="form-control-file" name="file" type="file">--}}
{{--     </div>--}}
{{--    <div class="form-group">--}}
{{--        <textarea class="form-control" name="content" cols="50" rows="10"></textarea>--}}
{{--    </div>--}}
{{--    <input class="btn btn-primary" type="submit" value="update">--}}
{{--    <a class="btn btn-warning" href="/posts/{{$post->id}}">back</a>--}}
{{--</form>--}}


{!! Form::model($post,["method"=>"PATCH","action"=>["postController@update",$post->id],"files"=>true]) !!}
{{csrf_field()}}
<div class="form-group">
    {!!Form::label("name","name")!!}
    {!!Form::text("name",null,["class"=>"form-control-file"])!!}
</div>
<div class="form-group">
    {!!Form::file("file",["class"=>"form-control-file"])!!}
</div>
<div class="form-group">
    {!!Form::textarea("content",null,["class"=>"form-control"])!!}
</div>
{!!Form::submit("update",["class"=>"btn btn-primary"])!!}
<a class="btn btn-warning" href="/posts/{{$post->id}}">back</a>
{!!Form::close()!!}


@endsection
</x-masterLayout.master>
