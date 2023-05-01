<x-masterLayout.master>
    @section("title")
        {{ __("global.roles_permission") }}
    @endsection
    @section('content')
        <div class="container">
            <div class="row">
                <div class="col-4">
                    @if(strtolower($role->name) != "admin" )
                        <td>{{$role->name}}</td>
                    @else
                        <td>{{__("global.".$role->name)}}</td>
                    @endif
                    <hr>
                    <br>
                    <p>{{__("global.created_at") . " : " . $role->created_at . " (" . $role->created_at->diffForHumans() . ")"}}</p>
                </div>
                <div class="col-8">

                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">{{__("global.roles_permission")}}</h6>
                        </div>
                        <div class="card-body">

                            <div class="table-responsive">
                                <table class="table table-bordered table-striped" id="dataTable" width="100%" cellspacing="0">
                                    <thead>
                                    <tr>
                                        <th>{{__("global.id")}}</th>
                                        <th>{{__("global.name")}}</th>
                                        <th>{{__("global.assign_deassign_permission")}}</th>
                                    </tr>
                                    </thead>
                                    <tbody>

                                    @foreach (App\Models\Permission::all() as $permission)

                                        <tr>
                                            <td>{{$permission->id}}</td>
                                            <td> {{$permission->name}} </td>
                                            <td class="text-center">
                                                @if(count($role->permissions) != 0)
                                                    @php $found = false; @endphp
                                                    @foreach($role->permissions as $role_permission)
                                                        @if($role_permission->slug == $permission->slug)
                                                            @php $found = true; @endphp
                                                            @break
                                                        @endif
                                                    @endforeach

                                                    @if($found == true)
                                                        <a id="btn_detach_permission" route-attr="{{route("role.detachPermission",[$role->id,$permission->id])}}" class="btn btn-sm btn-success">{{__("global.detach")}}</a>
                                                        <a id="btn_attach_permission" route-attr="{{route("role.attachPermission",[$role->id,$permission->id])}}" hidden="true" class="btn btn-sm btn-danger">{{__("global.attach")}}</a>
                                                    @else
                                                        <a id="btn_detach_permission" route-attr="{{route("role.detachPermission",[$role->id,$permission->id])}}" hidden="true" class="btn btn-sm btn-success">{{__("global.detach")}}</a>
                                                        <a id="btn_attach_permission" route-attr="{{route("role.attachPermission",[$role->id,$permission->id])}}" class="btn btn-sm btn-danger">{{__("global.attach")}}</a>
                                                    @endif

                                                @else
                                                    <a id="btn_detach_permission" route-attr="{{route("role.detachPermission",[$role->id,$permission->id])}}" hidden="true" class="btn btn-sm btn-success">{{__("global.detach")}}</a>
                                                    <a id="btn_attach_permission" route-attr="{{route("role.attachPermission",[$role->id,$permission->id])}}" class="btn btn-sm btn-danger">{{__("global.attach")}}</a>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    @endsection
    @section("script")

    @endsection
</x-masterLayout.master>
