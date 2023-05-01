<x-masterLayout.master>
    @section("title")
        {{ __("global.recycle_bin",["attribute"=>"Accounts"]) }}
    @endsection

    @section('content')
        <div class="container">
            <div class="form-group">
                <a id="btn_multi_restore" title="{{__("global.restore_selected")}}" class="btn btn-sm btn-success disable-pointer" href="#" data-toggle="modal" data-target="#restoreConfirmModal" data-route="{{route("account.restoreAccount",-1)}}">
                    <i class="fas fa-undo"></i>
                    {{__("global.restore_selected")}}
                </a>
                <a id="btn_multi_delete" title="{{__("global.delete_selected")}}" class="btn btn-sm btn-danger disable-pointer" href="#" data-toggle="modal" data-target="#deleteConfirmModal" data-route="{{route("account.deleteAccount",-1)}}">
                    <i class="fas fa-trash"></i>
                    {{__("global.delete_selected")}}
                </a>
            </div>
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">{{__("global.deleted_",["attribute"=>__("global.accounts")])}}</h6>
                </div>
                <div class="card-body">

                    <div class="table-responsive">
                        <table class="table table-bordered table-striped" id="dataTable" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th><input id="check_all" type="checkbox" class="form-check"></th>
                                    <th>{{__("global.id")}}</th>
                                    <th>{{__("global.name")}}</th>
                                    <th>{{__("global.type")}}</th>
                                    <th>{{__("global.reference")}}</th>
                                    <th>{{__("global.group")}}</th>
                                    <th>{{__("global.delete")}}</th>
                                </tr>
                            </thead>

                            <tbody>
                                @foreach ($deletedAccounts as $account)

                                        <tr>
                                            <td><input form="form_restore" name="multi_ids[]" value="{{$account->id}}" type="checkbox" class="form-check"></td>
                                            <td>{{$account->id}}</td>
                                            <td>{{$account->name}}</td>
                                            <td>{{$account->account_type}}</td>
                                            <td>{{$account->reference}}</td>
                                            <td>{{$account->group}}</td>

                                            <td class="row m-0">
                                                <a id="btn_restore" title="{{__("global.restore")}}" class="dropdown-item col-7 m-0 p-0" href="#" data-toggle="modal" data-target="#restoreConfirmModal" data-route="{{route("account.restoreAccount",$account->id)}}">
                                                    <i class="fa fa-undo"></i>
                                                </a>
                                                <a id="btn_delete" title="{{__("global.delete")}}" class="dropdown-item col-5 m-0 p-0" href="#" data-toggle="modal" data-target="#deleteConfirmModal"  data-route="{{route("account.deleteAccount",$account->id)}}">
                                                    <i class="fas fa-trash text-red"></i>
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








