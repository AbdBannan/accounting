<x-masterLayout.master>
    @section("title")
        {{ __("global.recycle_bin",["attribute"=>__("global.pounds",[],session("lang"))],session("lang")) }}
    @endsection

    @section('content')
        <div class="container">
            <div class="form-group">
                <a id="btn_multi_restore" title="{{__("global.restore_selected",[],session("lang"))}}" class="btn btn-sm btn-success disable-pointer" href="#" data-toggle="modal" data-target="#restoreConfirmModal" data-route="{{route("pound.restorePound",-1)}}">
                    <i class="fas fa-undo"></i>
                    {{__("global.restore_selected",[],session("lang"))}}
                </a>
                <a id="btn_multi_delete" title="{{__("global.delete_selected",[],session("lang"))}}" class="btn btn-sm btn-danger disable-pointer" href="#" data-toggle="modal" data-target="#deleteConfirmModal" data-route="{{route("pound.deletePound",-1)}}">
                    <i class="fas fa-trash"></i>
                    {{__("global.delete_selected",[],session("lang"))}}
                </a>
            </div>
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">{{__("global.deleted_",["attribute"=>__("global.pounds",[],session("lang"))],session("lang"))}}</h6>
                </div>
                <div class="card-body">

                    <div class="table-responsive">
                        <table class="table table-bordered table-striped" id="dataTable" width="100%" cellspacing="0">
                            <thead>
                                    <tr>
                                        <th><input id="check_all" type="checkbox" class="form-check"></th>
                                        <th>{{__("global.id",[],session("lang"))}}</th>
                                        <th>{{__("global.name",[],session("lang"))}}</th>
                                        <th>{{__("global.value",[],session("lang"))}}</th>
                                        <td></td>
                                    </tr>
                                    </thead>

                            <tbody>
                                @foreach ($deletedPounds as $pound)
                                    <tr>
                                        <td><input form="form_restore" name="multi_ids[]" value="{{$pound->id}}" type="checkbox" class="form-check"></td>
                                        <td>{{$pound->id}}</td>
                                        <td>{{$pound->name}}</td>
                                        <td>{{$pound->value}}</td>

                                        <td class="row m-0">
                                            <a id="btn_restore" title="{{__("global.restore",[],session("lang"))}}" class="dropdown-item col-7 m-0 p-0" href="#" data-toggle="modal" data-target="#restoreConfirmModal" data-route="{{route("pound.restorePound",$pound->id)}}">
                                                <i class="fa fa-undo"></i>
                                            </a>
                                            <a id="btn_delete" title="{{__("global.delete",[],session("lang"))}}" class="dropdown-item col-5 m-0 p-0" href="#" data-toggle="modal" data-target="#deleteConfirmModal"  data-route="{{route("pound.deletePound",$pound->id)}}">
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
    @section("modals")
        <x-modals.delete-confirm-modal></x-modals.delete-confirm-modal>
        <x-modals.restore-confirm-modal></x-modals.restore-confirm-modal>
    @endsection
    @section("script")

    @endsection
</x-masterLayout.master>








