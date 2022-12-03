<x-masterLayout.master>
    @section("title")
        {{ __("global.view_activity_log") }}
    @endsection
    @section("recycle_bin")
        <a class="dropdown-item" href="{{route("activityLog.viewRecyclebin")}}">
            <i class="fas fa-trash fa-sm fa-fw mr-2 text-gray-400"></i>
            {{__("global.recycle_bin",["attribute"=>__("global.activity_log")])}}
        </a>
    @endsection
    @section('content')

        <div class="container">
                <!-- DataTales Example -->
                <div class="card shadow">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">{{ __("global.user_activity_log") }}</h6>
                    </div>
                    <div class="card-body">

                        <div class="table-responsive">
                            <table class="table table-bordered table-striped" id="dataTable" width="100%" cellspacing="0">
                                <thead>
                                <tr>
                                    <th>{{__("global.id")}}</th>
                                    <th>{{__("global.name")}}</th>
                                    <th>{{__("global.value")}}</th>
                                    <th>{{__("global.delete")}}</th>
                                </tr>
                                </thead>

                                <tbody>
                                @foreach ($usersActivityLog as $user)

                                    <tr>
                                        <td><a id="btn_show_element" href="{{route("activityLog.showActivityLog",$user->id)}}">{{$user->id}}</a></td>
                                        <td>{{$user->first_name}}</td>
                                        <td>{{$user->email}}</td>

                                        <td style="width: 20px">
                                            <a id="btn_delete" title="{{__("global.delete")}}" class="dropdown-item m-0 p-0" href="#" data-toggle="modal" data-target="#deleteConfirmModal" data-route="{{route("activityLog.softDeleteActivityLog",$user->id)}}">
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
    @endsection
    @section("script")
  
    @endsection
</x-masterLayout.master>
