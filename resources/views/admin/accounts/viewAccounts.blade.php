<x-masterLayout.master>
    @section("title")
        {{ __("global.accounts",[],session("lang")) }}
    @endsection
    @section("recycle_bin")
        <a class="dropdown-item" href="{{route("account.viewRecyclebin")}}">
            <i class="fas fa-trash fa-sm fa-fw mr-2 text-gray-400"></i>
            {{__("global.recycle_bin",["attribute"=>__("global.account",[],session("lang"))],session("lang"))}}
        </a>
    @endsection

    @section('content')

        @section("id"){{\App\Models\Account::withTrashed()->selectRaw("max(id) as id")->first()->id + 1}}@endsection

        <div class="container">

        @if(auth()->user()->getConfig("add_method") != "modal")
            <div class="form-group">
                <a id="btn_multi_delete" title="{{__("global.delete_selected",[],session("lang"))}}" class="btn btn-sm btn-danger" href="#" data-toggle="modal" data-target="#deleteConfirmModal" @if(auth()->user()->getConfig("use_recyclebin") == "true") data-route="{{route("account.softDeleteAccount",-1)}}" @else data-route="{{route("account.deleteAccount",-1)}}" @endif>
                    <i class="fas fa-trash"></i>
                    {{__("global.delete_selected",[],session("lang"))}}
                </a>
            </div>
            <div class="row">
                <div class="col-lg-3 col-sm-12 bg-gray-100 card o-hidden border-0 shadow-lg p-4 ">
                    <form action="{{route("account.storeAccount")}}" method="POST" autocomplete="off">
                        @csrf
                        <x-forms.accounts-form>
                        </x-forms.accounts-form>
                        <input id="btn_add" class="btn btn-primary btn-block" type="submit" value="{{__("global.create",[],session("lang"))}}">
                    </form>
                </div>

                <div style="height: 40px" class="col-sm-0 col-lg-1"></div>

                <div class="col-lg-8 col-sm-12">


        @else
            <div>
                <div>
                    <div class="form-group">
                        <a id="btn_add" title="{{__("global.add",[],session("lang"))}}" class="btn btn-sm btn-info" href="#" data-toggle="modal" data-target="#addModal" data-route="{{route("account.storeAccount")}}">
                            <i class="fas fa-plus"></i>
                            {{__("global.add",[],session("lang"))}}
                        </a>
                        <a id="btn_multi_delete" title="{{__("global.delete_selected",[],session("lang"))}}" class="btn btn-sm btn-danger" href="#" data-toggle="modal" data-target="#deleteConfirmModal" @if(auth()->user()->getConfig("use_recyclebin") == "true") data-route="{{route("account.softDeleteAccount",-1)}}" @else data-route="{{route("account.deleteAccount",-1)}}" @endif>
                            <i class="fas fa-trash"></i>
                            {{__("global.delete_selected",[],session("lang"))}}
                        </a>
                    </div>
        @endif
                <div class="card shadow">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">{{__("global.accounts",[],session("lang"))}}</h6>
                    </div>
                    <div class="card-body" >

                        <div class="table-responsive">
                            <table class="table table-bordered table-striped" id="dataTable" width="100%" cellspacing="0">
                                <thead>
                                <tr>
                                    <th><input id="check_all" type="checkbox" class="form-check"></th>
                                    <th>{{__("global.id",[],session("lang"))}}</th>
                                    <th>{{__("global.name",[],session("lang"))}}</th>
                                    <th>{{__("global.type",[],session("lang"))}}</th>
                                    <th>{{__("global.reference",[],session("lang"))}}</th>
                                    <th>{{__("global.group",[],session("lang"))}}</th>
                                    <th>{{__("global.delete",[],session("lang"))}}</th>
                                </tr>
                                </thead>

                                <tbody>
                                    @foreach ($accounts as $key=>$account)
                                        <tr>
                                            <td><input form="form_delete" name="multi_delete_ids[]" value="{{$account->id}}" type="checkbox" class="form-check"></td>
                                            <td>{{$account->id}}</td>
{{--                                            <td><a href={{route("account.showAccount",$account->id)}}>{{$account->name}}</a></td>--}}
                                            <td>{{$account->name}}</td>
                                            <td>{{$account->account_type}}</td>
                                            <td>{{$account->reference}}</td>
                                            <td>{{$account->group}}</td>
                                            <td class="row m-0">
                                                <a id="btn_update" title="{{__("global.update",[],session("lang"))}}" class="dropdown-item col-7 m-0 p-0" href="#" data-toggle="modal" data-target="#updateModal" data-fields="{{$account}}" data-route="{{route("account.updateAccount",$account->id)}}">
                                                    <input class="grid-button grid-edit-button" type="button" title="Update">
                                                </a>
                                                <a id="btn_delete" title="{{__("global.delete",[],session("lang"))}}" class="dropdown-item col-5 m-0 p-0" href="#" data-toggle="modal" data-target="#deleteConfirmModal" @if(auth()->user()->getConfig("use_recyclebin") == "true") data-route="{{route("account.softDeleteAccount",$account->id)}}" @else data-route="{{route("account.deleteAccount",$account->id)}}" @endif>
                                                    <input class="grid-button grid-delete-button" type="button" title="Delete">
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


        </div>
    </div>

    @endsection
    @section("modals")
        <x-modals.delete-confirm-modal></x-modals.delete-confirm-modal>
        @if(auth()->user()->getConfig("add_method") == "modal")
            <x-modals.add-modal :modelName="$modelName = 'account'"></x-modals.add-modal>
        @endif
        <x-modals.update-modal :modelName="$modelName = 'account'"></x-modals.update-modal>
    @endsection
    @section("script")

    @endsection
</x-masterLayout.master>








