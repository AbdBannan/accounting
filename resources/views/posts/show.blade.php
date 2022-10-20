{{--@extends('layout.master');--}}
<x-masterLayout.master>

    @section("title")
        {{ "view post" }}
    @endsection
@section('content')
    <h1>{{$post->name}}</h1>
    <img src="{{$post->image}}" alt="" style="width: 100px;height: auto ;display: block" >
    <p>{{$post->content}}</p>
    <p>posted at {{$post->created_at}}</p>


    {!! Form::model($post,["method"=>"DELETE","action"=>["postController@destroy",$post->id]]) !!}
    {{csrf_field()}}
        @can("update",$post)
            <a class="btn btn-warning" href="/posts/{{$post->id}}/edit">edit</a>
        @endcan
        @can("delete",$post)
            {!!Form::submit("delete",["class"=>"btn btn-primary"])!!}
        @endcan

        <a class="btn btn-warning" href="/posts">back</a>
    {!!Form::close()!!}
    <hr>





    <div class="container">
        <div class="row">
            <div class="bg-gray-100 card o-hidden border-0 shadow-lg my-5 m-lg-5 p-4 col-7">
            <div class="text-left">
                <h1 class="h4 text-gray-900 mb-4">leave a comment :</h1>
            </div>

            <form method="POST" class="user" action={{route("comment.storeComment",$post)}} accept-charset="UTF-8" enctype="multipart/form-data">
                @csrf
                <div class="form-group">
                    <input id="comment_body" type="text" class="form-control @error('comment_body') is-invalid @enderror" placeholder="comment..." name="comment_body">
                    @error('comment_body')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>
                <button type="submit" class="btn btn-primary">{{ __('add') }}</button>
            </form>
        </div>
        </div>
        <br>

        @foreach($post->comments as $comment)
            <div class="row bg-gradient-light shadow p-2" style="width: 600px">
                <div width="30%"><img  class="img-profile rounded-circle" style="width: 150px"  src={{asset($comment->user->profile_image)}}></div>
                <div width="70%" class="p-3">
                    <p>{{$comment->created_at->diffForHumans()}}</p>
                    <h3>{{$comment->user->first_name}}</h3>
                    <p class="mt-5 pt-3">{{$comment->comment_body}}</p>
                    @if($comment->created_at != $comment->updated_at)
                        <p>{{$comment->updated_at }}</p>
                    @endif

                    @can("update",$comment)
                        <a id="btn-comment-update" class="dropdown-item" href="#" data-toggle="modal" data-target="#updateModal" data-val="{{$comment->comment_body}}" data-route={{route("comment.updateComment",$comment->id)}}>
                            <input class="grid-button grid-edit-button" type="button" title="Update">
                        </a>
                    @endcan
                    @can("delete",$comment)
                        <a id="btn-delete" class="dropdown-item" href="#" data-toggle="modal" data-target="#deleteConfirmModal" data-route={{route("comment.deleteComment",$comment->id)}}>
                            <input class="grid-button grid-delete-button" type="button" title="Delete">
                        </a>
                    @endcan
                </div>
            </div>
            <div id="reply" class="pl-5">
                <button id="btn-reply" style="margin-left: 50% ; margin-top: 5px" class="btn btn-info">reply</button>
                <div id="reply-content" class="row" style="display: none" >
                    <div class="bg-gray-100 card o-hidden border-0 shadow-lg my-5 m-lg-5 p-4 col-7">
                        <div class="text-left">
                            <h1 class="h4 text-gray-900 mb-4">reply :</h1>
                        </div>

                        <form method="POST" class="user" action={{route("reply.storeReply","comment_".$comment->id)}}>
                            @csrf
                            <div class="form-group">
                                <input id="reply_body" type="text" class="form-control @error('reply_body') is-invalid @enderror" placeholder="comment..." name="reply_body">
                                @error('reply_body')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                            <button type="submit" class="btn btn-primary">{{ __('reply') }}</button>
                        </form>
                    </div>
                </div>
                <hr></hr>
                @if(count($comment->replies)!=0)
                    <button id="btn-replies-toggle" type="submit" class="btn btn-danger">{{ __('collapse') }}</button>
                    <div id="replies-section" class="border-left-dark">
                        <x-commentsAndReply.comments-and-reply-section :comment='$comment'></x-commentsAndReply.comments-and-reply-section>
                    </div>
                @endif
            </div>
        @endforeach
    </div>

    <div class="mb-5 pb-5" style="height: 2000px"></div>
    <x-models.delete-confirm-model></x-models.delete-confirm-model>
    <x-models.update-comment-and-reply-model></x-models.update-comment-and-reply-model>
@endsection
</x-masterLayout.master>
