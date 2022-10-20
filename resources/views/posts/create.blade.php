{{--@extends('/layout.master');--}}
<x-masterLayout.master>
    @section("title")
        {{ "create post" }}
    @endsection
@section('content')
{{-- <h1>Create a new post</h1>
<form action="/posts" method="POST" enctype="multipart/form-data">
    {{csrf_field()}}
    <div class="form-group">
        <label for="name">name</label>
        <input class="form-control" type="text" name="name" id="name">
    </div>
    <input class="btn btn-primary" type="submit">
    <a class="btn btn-warning" href="/posts">back</a>
</form> --}}

{!! Form::open(["method"=>"POST","action"=>"postController@store","files"=>true]) !!}
{{csrf_field()}}
<div class="form-group">
    {!!Form::label("name","name")!!}
    {!!Form::text("name",null,["class"=>"form-control"])!!}
</div>
<div class="form-group">
    {!!Form::file("file",["class"=>"form-control-file"])!!}
</div>
<div class="form-group">
    {!!Form::textarea("content","",["class"=>"form-control"])!!}
</div>
{!!Form::submit("create",["class"=>"btn btn-primary"])!!}
<a class="btn btn-warning" href="/posts">back</a>
{!!Form::close()!!}

@endsection
</x-masterLayout.master>
