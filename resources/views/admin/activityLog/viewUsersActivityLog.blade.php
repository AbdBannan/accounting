<x-masterLayout.master>
    @section("title")
        {{ __("global.view_activity_log",[],session("lang")) }}
    @endsection
    @section("recycle_bin")
        <a class="dropdown-item" href="{{route("activityLog.viewRecyclebin")}}">
            <i class="fas fa-trash fa-sm fa-fw mr-2 text-gray-400"></i>
            {{__("global.recycle_bin",["attribute"=>__("global.activity_log",[],session("lang"))],session("lang"))}}
        </a>
    @endsection
    @section('content')

        <div class="container">
                <!-- DataTales Example -->
                <div class="card shadow">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">{{ __("global.user_activity_log",[],session("lang")) }}</h6>
                    </div>
                    <div class="card-body">

                        <div class="table-responsive">
                            <table class="table table-bordered table-striped" id="dataTable" width="100%" cellspacing="0">
                                <thead>
                                <tr>
                                    <th>{{__("global.id",[],session("lang"))}}</th>
                                    <th>{{__("global.name",[],session("lang"))}}</th>
                                    <th>{{__("global.value",[],session("lang"))}}</th>
                                    <th>{{__("global.delete",[],session("lang"))}}</th>
                                </tr>
                                </thead>

                                <tbody>
                                @foreach ($usersActivityLog as $user)

                                    <tr>
                                        <td><a id="btn_show_element" href="{{route("activityLog.showActivityLog",$user->id)}}">{{$user->id}}</a></td>
                                        <td>{{$user->first_name}}</td>
                                        <td>{{$user->email}}</td>

                                        <td style="width: 20px">
                                            <a id="btn-delete" title="{{__("global.delete",[],session("lang"))}}" class="dropdown-item m-0 p-0" href="#" data-toggle="modal" data-target="#deleteConfirmModal" data-route="{{route("activityLog.softDeleteActivityLog",$user->id)}}">
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


    @endsection
    @section("models")
        <x-models.delete-confirm-model></x-models.delete-confirm-model>
    @endsection
    @section("script")
    <!-- Page level plugins -->
        <script src={{asset("vendor/datatables/jquery.dataTables.js")}}></script>
        <script src={{asset("vendor/datatables/dataTables.bootstrap4.js")}}></script>

        <!-- Page level custom scripts -->
        <script src={{asset("js/demo/datatables-demo.js?var=415".rand(1,100))}}></script>
    @endsection
</x-masterLayout.master>
