<x-masterLayout.master>
    @section("title")
        {{ __("global.view_pounds",[],session("lang")) }}
    @endsection
    @section("recycle_bin")
        <a class="dropdown-item" href="{{route("pound.viewRecyclebin")}}">
            <i class="fas fa-trash fa-sm fa-fw mr-2 text-gray-400"></i>
            {{__("global.recycle_bin",["attribute"=>__("global.pounds",[],session("lang"))],session("lang"))}}
        </a>
    @endsection
    @section('content')
        <div class="row">
            <div class="col-lg-3 col-sm-12 bg-gray-100 card o-hidden border-0 shadow-lg p-4" style="height: auto">
                <form action="{{route("pound.storePound")}}" method="POST">
                    @csrf
                    <x-forms.pounds-form></x-forms.pounds-form>
                    <input id="btn_add_pound" class="btn btn-primary btn-block" type="submit" value="create">
                </form>
            </div>

            <div style="height: 40px" class="col-sm-0 col-lg-1"></div>

            <div class="col-lg-8 col-sm-12">
                <!-- DataTales Example -->
                <div class="card shadow">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">{{ __("global.pounds",[],session("lang")) }}</h6>
                    </div>
                    <div class="card-body">

                        <div class="table-responsive">
                            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                <thead>
                                    <tr>
                                        <th>{{__("global.id",[],session("lang"))}}</th>
                                        <th>{{__("global.name",[],session("lang"))}}</th>
                                        <th>{{__("global.value",[],session("lang"))}}</th>
                                        <th>{{__("global.delete",[],session("lang"))}}</th>
                                    </tr>
                                </thead>

                                <tbody>
                                @foreach ($pounds as $pound)

                                    <tr>
                                        <td>{{$pound->id}}</td>
                                        <td>{{$pound->name}}</td>
                                        <td>{{$pound->value}}</td>

                                        <td class="row m-0">
                                            <a id="btn-update" title="{{__("global.update",[],session("lang"))}}" class="dropdown-item col-7 m-0 p-0" href="#" data-toggle="modal" data-target="#updateModal" data-fields="{{$pound}}" data-route="{{route("pound.updatePound",$pound->id)}}">
                                                <input class="grid-button grid-edit-button" type="button" title="Update">
                                            </a>
                                            <a id="btn-delete" title="{{__("global.delete",[],session("lang"))}}" class="dropdown-item col-5 m-0 p-0" href="#" data-toggle="modal" data-target="#deleteConfirmModal" data-route="{{route("pound.softDeletePound",$pound->id)}}">
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



    @endsection
    @section("models")
        <x-models.delete-confirm-model></x-models.delete-confirm-model>
        <x-models.update-model :modelName="$modelName = 'pound'"></x-models.update-model>
    @endsection
    @section("script")
    <!-- Page level plugins -->
        <script src={{asset("vendor/datatables/jquery.dataTables.js")}}></script>
        <script src={{asset("vendor/datatables/dataTables.bootstrap4.js")}}></script>

        <!-- Page level custom scripts -->
        <script src={{asset("js/demo/datatables-demo.js?var=415".rand(1,100))}}></script>
    @endsection
</x-masterLayout.master>
