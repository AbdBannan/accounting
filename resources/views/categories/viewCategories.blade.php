<x-masterLayout.master>
    @section("title")
        {{ __("global.categories",[],session("lang")) }}
    @endsection

    @section("recycle_bin")
        <a class="dropdown-item" href="{{route("category.viewRecyclebin")}}">
            <i class="fas fa-trash fa-sm fa-fw mr-2 text-gray-400"></i>
            {{__("global.recycle_bin",["attribute"=>__("global.categories",[],session("lang"))],session("lang"))}}
        </a>
    @endsection

    @section('content')
        @section("id"){{\App\Models\Category::withTrashed()->selectRaw("max(id) as id")->first()->id + 1}}@endsection

        <div class="container">
            @if(auth()->user()->getConfig("add_method") != "modal")

                <div class="row">

                    <div class="bg-gray-100 card o-hidden border-0 shadow-lg p-4 col-lg-3 col-sm-12">
                        <form action={{route("category.storeCategory")}} method="POST">
                            @csrf
                            <x-forms.categories-form>
                            </x-forms.categories-form>
                            <input id="btn_add" class="btn btn-primary btn-block" type="submit" value={{__("global.create",[],session("lang"))}}>
                        </form>
                    </div>

                    <div class="col-sm-0 col-lg-1"></div>

                    <dev class="col-lg-8 col-sm-12">
            @else
                <div>
                    <div>
                        <div class="form-group">
                            <a id="btn_add" title="{{__("global.add",[],session("lang"))}}" class="btn btn-sm btn-info" href="#" data-toggle="modal" data-target="#addModal" data-route="{{route("category.storeCategory")}}">
                                <i class="fas fa-plus"></i>
                                {{__("global.add",[],session("lang"))}}
                            </a>
                        </div>
            @endif
                    <div class="card shadow">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">{{__("global.categories",[],session("lang"))}}</h6>
                        </div>
                        <div class="card-body">

                            <div class="table-responsive">
                                <table class="table table-bordered table-striped" id="dataTable" width="100%" cellspacing="0">
                                    <thead>
                                    <tr>
                                        <th>{{__("global.id",[],session("lang"))}}</th>
                                        <th>{{__("global.name",[],session("lang"))}}</th>
                                        <th>{{__("global.delete",[],session("lang"))}}</th>
                                    </tr>
                                    </thead>

                                    <tbody>

                                        @foreach ($categories as $category)

                                            <tr>
                                                <td>{{$category->id}}</td>
                                                <td><a href={{route("category.showCategory",$category)}}>{{$category->name}}</a></td>

                                                <td class="row m-0">
                                                    <a id="btn_update" title="{{__("global.update",[],session("lang"))}}" class="dropdown-item col-7 m-0 p-0" href="#" data-toggle="modal" data-target="#updateModal" data-fields="{{$category}}" data-route="{{route("category.updateCategory",$category->id)}}">
                                                        <input class="grid-button grid-edit-button" type="button" title="Update">
                                                    </a>
                                                    <a id="btn_delete" title="{{__("global.delete",[],session("lang"))}}" class="dropdown-item col-5 m-0 p-0" href="#" data-toggle="modal" data-target="#deleteConfirmModal" data-route="{{route("category.softDeleteCategory",$category->id)}}">
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
                </dev>


            </div>
        </div>

    @endsection
    @section("models")
        <x-models.delete-confirm-model></x-models.delete-confirm-model>
        @if(auth()->user()->getConfig("add_method") == "modal")
            <x-models.add-model :modelName="$modelName = 'category'"></x-models.add-model>
        @endif
        <x-models.update-model :modelName="$modelName = 'category'"></x-models.update-model>

    @endsection
    @section("script")
  
    @endsection
</x-masterLayout.master>








