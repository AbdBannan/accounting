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
                                {{--                                    <th>{{__("global.delete")}}</th>--}}
                            </tr>
                            </thead>

                            <tbody>
                            @foreach ($activityLog as $activity)

                                <tr>
                                    <td>{{$activity->id}}</td>
                                    <td>{{$activity->user->first_name}}</td>
                                    <td>{{$activity->content}}</td>
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
