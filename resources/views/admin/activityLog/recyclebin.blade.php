<x-masterLayout.master>
    @section("title")
        {{ __("global.recycle_bin",["attribute"=>__("global.activity_log",[],session("lang"))],session("lang")) }}
    @endsection

    @section('content')
        <div class="container">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">{{__("global.deleted_",["attribute"=>__("global.activity_log",[],session("lang"))],session("lang"))}}</h6>
                </div>
                <div class="card-body">

                    <div class="table-responsive">
                        <table class="table table-bordered table-striped" id="dataTable" width="100%" cellspacing="0">
                            <thead>
                            <tr>
                                <th>{{__("global.id",[],session("lang"))}}</th>
                                <th>{{__("global.name",[],session("lang"))}}</th>
                                <th>{{__("global.email",[],session("lang"))}}</th>
                                <th>{{__("global.delete_date",[],session("lang"))}}</th>
                                <th>{{__("global.delete",[],session("lang"))}}</th>
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
                                        <a id="btn-restore" title="{{__("global.restore",[],session("lang"))}}" class="dropdown-item col-7 m-0 p-0" href="#" data-toggle="modal" data-target="#restoreConfirmModal" data-route="{{route("activityLog.restoreActivityLog",$userActivity->id)}}">
                                            <i class="fa fa-undo"></i>
                                        </a>
                                        <a id="btn-delete" title="{{__("global.delete",[],session("lang"))}}" class="dropdown-item col-5 m-0 p-0" href="#" data-toggle="modal" data-target="#deleteConfirmModal"  data-route="{{route("activityLog.deleteActivityLog",$userActivity->id)}}">
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
    <!-- Page level plugins -->
        <script src={{asset("vendor/datatables/jquery.dataTables.js")}}></script>
        <script src={{asset("vendor/datatables/dataTables.bootstrap4.js")}}></script>

        <!-- Page level custom scripts -->
        <script src={{asset("js/demo/datatables-demo.js?var=415".rand(1,100))}}></script>
    @endsection
</x-masterLayout.master>








