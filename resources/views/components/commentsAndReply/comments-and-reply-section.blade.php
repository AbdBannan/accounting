@foreach($comment->replies as $reply)
    <div class="row bg-gray-100 shadow p-2" style="width: 600px">
        <div width="30%"><img  class="img-profile rounded-circle" style="width: 150px" src={{asset($reply->user->profile_image)}}></div>
        <div width="70%" class="p-3">
            <p>{{$reply->created_at->diffForHumans()}}</p>
            <h3>{{$reply->user->first_name}}</h3>
            <p class="mt-5 pt-3">{{$reply->reply_body}}</p>
            @if($reply->created_at != $reply->updated_at)
            <p>{{$reply->updated_at }}</p>
            @endif

            @can("update",$reply)
                <a id="btn-reply-update" class="dropdown-item" href="#" data-toggle="modal" data-target="#updateModal" data-val="{{$reply->reply_body}}" data-route={{route("reply.updateReply",$reply->id)}}>
                    <input class="grid-button grid-edit-button" type="button" title="Update">
                </a>
            @endcan
            @can("delete",$reply)
                <a id="btn_delete" class="dropdown-item" href="#" data-toggle="modal" data-target="#deleteConfirmModal" data-route={{route("reply.deleteReply",$reply->id)}}>
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

                <form method="POST" class="user"  action={{route("reply.storeReply","reply_".$reply->id)}}>
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
        <br>
        <hr></hr>
        @if(count($reply->replies)!=0)
            <button id="btn-replies-toggle" type="submit" class="btn btn-danger">{{ __('collapse') }}</button>
            <div id="replies-section" class="border-left-dark">
                <x-commentsAndReply.comments-and-reply-section :comment='$reply'></x-commentsAndReply.comments-and-reply-section>
            </div>
        @endif

    </div>
@endforeach
