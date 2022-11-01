<x-masterLayout.master>
    @section("title")
        {{ __("global.recycle_bin",["attribute"=>__("global.role",[],session("lang"))],session("lang")) }}
    @endsection

    @section('content')
        <div class="container">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">{{__("global.deleted_",["attribute"=>__("global.role",[],session("lang"))],session("lang"))}}</h6>
                </div>
                <div class="card-body">

                    <div class="table-responsive">
                        <table class="table table-bordered table-striped" id="dataTable" width="100%" cellspacing="0">
                            <thead>
                                    <tr>
                                        <th>{{__("global.id",[],session("lang"))}}</th>
                                        <th>{{__("global.name",[],session("lang"))}}</th>
                                        <th></th>
                                    </tr>
                                    </thead>

                            <tbody>
                                @foreach ($deletedRoles as $role)

                                        <tr>
                                            <td>{{$role->id}}</td>
                                            <td>{{$role->name}}</td>
                                            <td class="row m-0">
                                                <a id="btn_restore" title="{{__("global.restore",[],session("lang"))}}" class="dropdown-item col-7 m-0 p-0" href="#" data-toggle="modal" data-target="#restoreConfirmModal" data-route="{{route("role.restoreRole",$role->id)}}">
                                                    <i class="fa fa-undo"></i>
                                                </a>
                                                <a id="btn_delete" title="{{__("global.delete",[],session("lang"))}}" class="dropdown-item col-5 m-0 p-0" href="#" data-toggle="modal" data-target="#deleteConfirmModal" data-route="{{route("role.deleteRole",$role->id)}}">
                                                    <input class="grid-button grid-delete-button" type="button">
                                                </a>
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
    @section("models")
        <x-models.delete-confirm-model></x-models.delete-confirm-model>
        <x-models.restore-confirm-model></x-models.restore-confirm-model>
    @endsection
    @section("script")
  
    @endsection
</x-masterLayout.master>








