<x-masterLayout.master>
    @section("title")
        {{ __("global.view_users") }}
    @endsection
    @section("recycle_bin")
        <a class="dropdown-item" href="{{route("user.viewRecyclebin")}}">
            <i class="fas fa-trash fa-sm fa-fw mr-2 text-gray-400"></i>
            {{__("global.recycle_bin",["attribute"=>__("global.users")])}}
        </a>
    @endsection
    @section('content')
        <div class="container">
            <div class="form-group">
                <a class="btn btn-sm btn-info" href="{{route("user.createUser")}}"><i class="fas fa-plus"></i> {{__("global.add_new_user")}}</a>
                <a id="btn_multi_delete" title="{{__("global.delete_selected")}}" class="btn btn-sm btn-danger disable-pointer" href="#" data-toggle="modal" data-target="#deleteConfirmModal" @if(auth()->user()->getConfig("use_recyclebin") == "true") data-route="{{route("user.softDeleteUser",-1)}}" @else data-route="{{route("user.softDeleteUser",-1)}}" @endif>
                    <i class="fas fa-trash"></i>
                    {{__("global.delete_selected")}}
                </a>
            </div>

            <!-- DataTales Example -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">{{__("global.users")}}</h6>
                </div>
                <div class="card-body">

                    <div class="table-responsive">
                        <table class="table table-bordered table-striped" id="dataTable" width="100%" cellspacing="0">
                            <thead>
                            <tr>
                                <th><input id="check_all" type="checkbox" class="form-check"></th>
                                <th>{{__("global.first_name")}}</th>
                                <th>{{__("global.last_name")}}</th>
                                <th>{{__("global.email")}}</th>
                                <th>{{__("global.activate")}}</th>
                                <th>{{__("global.track_activity")}}</th>
                                <th>{{__("global.delete")}}</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach ($users as $user)
                                <tr>
                                    @if(!$user->isAdmin())
                                        <td><input form="form_delete" name="multi_ids[]" value="{{$user->id}}" type="checkbox" class="form-check"></td>
                                    @else
                                        <td></td>
                                    @endif
                                    <td><a id="btn_show_element" href="{{route("user.showUser",$user)}}"> {{$user->first_name}} </a></td>
                                    <td> {{$user->last_name}} </td>
                                    <td> {{$user->email}} </td>

                                    @if(!$user->isAdmin())
                                        <td class="text-center">
                                            @if(!$user->active)
                                                <a id="btn_activate_user" route-attr="{{route("user.activateUser",$user)}}" class="btn btn-sm btn-danger">{{__("global.activate")}}</a>
                                                <a id="btn_deactivate_user"  route-attr="{{route("user.deactivateUser",$user)}}" hidden="true" disabled class="btn btn-sm btn-success">{{__("global.deactivate")}}</a>
                                            @else
                                                <a id="btn_activate_user" route-attr="{{route("user.activateUser",$user)}}" hidden="true" disabled class="btn btn-sm btn-danger">{{__("global.activate")}}</a>
                                                <a id="btn_deactivate_user" route-attr="{{route("user.deactivateUser",$user)}}" class="btn btn-sm btn-success">{{__("global.deactivate")}}</a>
                                            @endif

                                        </td>
                                    @else
                                        <td></td>
                                    @endif
                                    <td class="text-center">
                                        @if($user->getConfig("user_activity_log") == "false")
                                            <a id="btn_track_user_activity" route-attr="{{route("user.trackUserActivity",$user)}}" class="btn btn-sm btn-danger">{{__("global.track")}}</a>
                                            <a id="btn_no_track_user_activity"  route-attr="{{route("user.noTrackUserActivity",$user)}}" hidden="true" disabled class="btn btn-sm btn-success">{{__("global.no_track")}}</a>
                                        @else
                                            <a id="btn_track_user_activity" route-attr="{{route("user.trackUserActivity",$user)}}" hidden="true" disabled class="btn btn-sm btn-danger">{{__("global.track")}}</a>
                                            <a id="btn_no_track_user_activity" route-attr="{{route("user.noTrackUserActivity",$user)}}" class="btn btn-sm btn-success">{{__("global.no_track")}}</a>
                                        @endif

                                    </td>
                                    <td class="row m-0">
                                        <a id="btn_update" title="{{__("global.update")}}" class="dropdown-item col-4 m-0 p-0" href="{{route("user.showUser",$user->id)}}">
                                            <i class="fas fa-edit text-green"></i>
                                        </a>
                                        @if(!$user->isAdmin())
                                            <a id="btn_delete" title="{{__("global.delete")}}" class="dropdown-item col-3 m-0 p-0" href="#" data-toggle="modal" data-target="#deleteConfirmModal" @if(auth()->user()->getConfig("use_recyclebin") == "true") data-route="{{route("user.softDeleteUser",$user->id)}}" @else data-route="{{route("user.deleteUser",$user->id)}}" @endif >
                                                <i class="fas fa-trash text-red"></i>
                                            </a>
                                        @else
                                            <div class="col-6"></div>
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


    @endsection
    @section("modals")
        <x-modals.delete-confirm-modal></x-modals.delete-confirm-modal>
    @endsection
    @section("script")

    @endsection

</x-masterLayout.master>
