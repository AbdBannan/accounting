<x-masterLayout.master>
    @section("title")
        {{ __("global.recycle_bin",["attribute"=>__("global.activity_log")]) }}
    @endsection

    @section('content')
        <div class="container">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">{{__("global.deleted_",["attribute"=>__("global.activity_log")])}}</h6>
                </div>
                <div class="card-body">

                    <div class="table-responsive">
                        <table class="table table-bordered table-striped" id="dataTable" width="100%" cellspacing="0">
                            <thead>
                            <tr>
                                <th>{{__("global.id")}}</th>
                                <th>{{__("global.name")}}</th>
                                <th>{{__("global.email")}}</th>
                                <th>{{__("global.delete_date")}}</th>
                                <th>{{__("global.delete")}}</th>
                            </tr>
                            </thead>

                            <tbody>

                            @foreach ($deletedUsersActivityLog as $userActivity)

                                <tr>
                                    <td>{{$userActivity->id}}</td>
                                    <td>{{$userActivity->first_name}}</td>
                                    <td>{{$userActivity->email}}</td>
                                    <td>{{$userActivity->deleted_at}}</td>

                                    <td class="row m-0">
                                        <a id="btn_restore" title="{{__("global.restore")}}" class="dropdown-item col-7 m-0 p-0" href="#" data-toggle="modal" data-target="#restoreConfirmModal" data-route="{{route("activityLog.restoreActivityLog",$userActivity->id)}}">
                                            <i class="fa fa-undo"></i>
                                        </a>
                                        <a id="btn_delete" title="{{__("global.delete")}}" class="dropdown-item col-5 m-0 p-0" href="#" data-toggle="modal" data-target="#deleteConfirmModal"  data-route="{{route("activityLog.deleteActivityLog",$userActivity->id)}}">
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








