<x-masterLayout.master>
    @section("title")
        {{ __("global.view_roles",[],session("lang")) }}
    @endsection
    @section("recycle_bin")
        <a class="dropdown-item" href="{{route("role.viewRecyclebin")}}">
            <i class="fas fa-trash fa-sm fa-fw mr-2 text-gray-400"></i>
            {{__("global.recycle_bin",["attribute"=>__("global.roles",[],session("lang"))],session("lang"))}}
        </a>
    @endsection
    @section('content')
    <div class="container">
        @if(auth()->user()->getConfig("add_method") != "modal")
            <div class="form-group">
                <a id="btn_multi_delete" title="{{__("global.delete_selected",[],session("lang"))}}" class="btn btn-sm btn-danger disable-pointer" href="#" data-toggle="modal" data-target="#deleteConfirmModal" @if(auth()->user()->getConfig("use_recyclebin") == "true") data-route="{{route("role.softDeleteRole",-1)}}" @else data-route="{{route("role.deleteRole",-1)}}" @endif>
                    <i class="fas fa-trash"></i>
                    {{__("global.delete_selected",[],session("lang"))}}
                </a>
            </div>
            <div class="row">
                <div class="col-lg-3 col-sm-12 bg-gray-100 card o-hidden border-0 shadow-lg p-4" style="height: auto">
                    <form action="{{route("role.storeRole")}}" method="POST" autocomplete="off">
                        @csrf
                        <x-forms.roles-form></x-forms.roles-form>
                        <input class="btn btn-primary btn-block" type="submit" value="create">
                    </form>
                </div>

                <div style="height: 40px" class="col-sm-0 col-lg-1"></div>

                <div class="col-lg-8 col-sm-12">
        @else
            <div>
                <div>
                    <div class="form-group">
                        <a id="btn_add" title="{{__("global.add",[],session("lang"))}}" class="btn btn-sm btn-info  disable-pointer" href="#" data-toggle="modal" data-target="#addModal" data-route="{{route("role.storeRole")}}">
                            <i class="fas fa-plus"></i>
                            {{__("global.add",[],session("lang"))}}
                        </a>
                        <a id="btn_multi_delete" title="{{__("global.delete_selected",[],session("lang"))}}" class="btn btn-sm btn-danger disable-pointer" href="#" data-toggle="modal" data-target="#deleteConfirmModal" @if(auth()->user()->getConfig("use_recyclebin") == "true") data-route="{{route("role.softDeleteRole",-1)}}" @else data-route="{{route("role.deleteRole",-1)}}" @endif>
                            <i class="fas fa-trash"></i>
                            {{__("global.delete_selected",[],session("lang"))}}
                        </a>
                    </div>
            @endif
            <!-- DataTales Example -->
                <div class="card shadow">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">{{ __("global.roles",[],session("lang")) }}</h6>
                    </div>
                    <div class="card-body">

                        <div class="table-responsive">
                            <table class="table table-bordered table-striped" id="dataTable" width="100%" cellspacing="0">
                                <thead>
                                <tr>
                                    <th><input id="check_all" type="checkbox" class="form-check"></th>
                                    <th>{{__("global.id",[],session("lang"))}}</th>
                                    <th>{{__("global.name",[],session("lang"))}}</th>
                                    <th>{{__("global.delete",[],session("lang"))}}</th>
                                </tr>
                                </thead>

                                <tbody>
                                @foreach ($roles as $role)

                                    <tr>
                                        @if(strtolower($role->name) != "admin" )
                                            <td><input form="form_delete" name="multi_ids[]" value="{{$role->id}}" type="checkbox" class="form-check"></td>
                                        @else
                                            <td></td>
                                        @endif
                                        <td>{{$role->id}}</td>
                                        @if(strtolower($role->name) != "admin" )
                                            <td><a id="btn_show_element" href="{{route("role.showRolePermission",$role)}}">{{$role->name}}</a></td>
                                            <td class="row m-0">
                                                <a id="btn_update" title="{{__("global.update",[],session("lang"))}}" class="dropdown-item col-7 m-0 p-0" href="#" data-toggle="modal" data-target="#updateModal" data-fields="{{$role}}" data-route="{{route("role.updateRole",$role->id)}}">
                                                    <input class="grid-button grid-edit-button" type="button" title="Update">
                                                </a>
                                                <a id="btn_delete" title="{{__("global.delete",[],session("lang"))}}" class="dropdown-item col-5 m-0 p-0" href="#" data-toggle="modal" data-target="#deleteConfirmModal" @if(auth()->user()->getConfig("use_recyclebin") == "true") data-route="{{route("role.softDeleteRole",$role->id)}}" @else  data-route="{{route("role.deleteRole",$role->id)}}" @endif>
                                                    <input class="grid-button grid-delete-button" type="button" title="Delete">
                                                </a>
                                            </td>
                                        @else
                                            <td><a id="btn_show_element" href="{{route("role.showRolePermission",$role)}}">{{__("global.".$role->name,[],session("lang"))}}</a></td>
                                            <td></td>
                                        @endif
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
    @section("modals")
        <x-modals.delete-confirm-modal></x-modals.delete-confirm-modal>
        @if(auth()->user()->getConfig("add_method") == "modal")
            <x-modals.add-modal :modelName="$modelName = 'role'"></x-modals.add-modal>
        @endif
        <x-modals.update-modal :modelName="$modelName = 'role'"></x-modals.update-modal>
        @endsection
    @section("script")

    @endsection
</x-masterLayout.master>
