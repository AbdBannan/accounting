{{--@extends('layout.master')--}}

<x-masterLayout.master>
    @section("title")
        {{ "view posts" }}
    @endsection
    @section('content')

        <a class="btn btn-info m-3" href="/posts/create">add new post</a>

        <!-- DataTales Example -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">DataTables Example</h6>
            </div>
            <div class="card-body">

                <div class="table-responsive">
                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                        <thead>
                        <tr>
                            <th>post</th>
                            <th>owner</th>
                            <th>content</th>
                            <th>image</th>
                            <th>date</th>
                            <th>n-date</th>
                            <th></th>
                        </tr>
                        </thead>
                        <tfoot>
                        <tr>
                            <th>post</th>
                            <th>owner</th>
                            <th>content</th>
                            <th>image</th>
                            <th>date</th>
                            <th>n-date</th>
                            <th></th>
                        </tr>
                        </tfoot>
                        <tbody>

                        @if (count($posts)>0)
                            @foreach ($posts as $post)

                                <tr>
                                    <td><a href="/posts/{{$post->id}}"> {{$post->name}} </a></td>
                                    <td> {{$post->user->first_name}} </td>
                                    <td> {{Str::limit($post->content,20,"....")}} </td>
                                    <td> {{$post->image}} </td>
                                    <td> {{$post->created_at->diffForHumans()}} </td>
                                    <td> {{$post->created_at}} </td>
                                    <td>
                                        @can("update",$post)
                                            <a id="btn-post-update" class="dropdown-item" href="#" data-toggle="modal" data-target="#updateModal" data-val="{{$post}}" data-route={{route("posts.update",$post->id)}}>
                                                <input class="grid-button grid-edit-button" type="button" title="Update">
                                            </a>
                                        @endcan
                                        @can("delete",$post)
                                            <a id="btn-delete" class="dropdown-item" href="#" data-toggle="modal" data-target="#deleteConfirmModal" data-route={{route("posts.destroy",$post->id)}}>
                                                <input class="grid-button grid-delete-button" type="button" title="Delete">
                                            </a>
                                        @endcan
                                    </td>
                                </tr>
                            @endforeach
                            {{-- {{$posts->links()}} --}}
                        @else
                            <h3>no posts exist</h3>
                        @endif


                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    @endsection
    @section("models")
        <x-models.delete-confirm-model></x-models.delete-confirm-model>
        <x-models.update-post-model></x-models.update-post-model>
    @endsection
    @section("script")
        <!-- Page level plugins -->
            <script src={{asset("vendor/datatables/jquery.dataTables.js")}}></script>
            <script src={{asset("vendor/datatables/dataTables.bootstrap4.js")}}></script>

            <!-- Page level custom scripts -->
            <script src={{asset("js/demo/datatables-demo.js?var=415".rand(1,100))}}></script>
    @endsection
</x-masterLayout.master>
