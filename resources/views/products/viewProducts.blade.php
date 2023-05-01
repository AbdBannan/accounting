<x-masterLayout.master>
    @section("title")
        {{ __("global.products") }}
    @endsection
    @section("recycle_bin")
        <a class="dropdown-item" href="{{route("product.viewRecyclebin")}}">
            <i class="fas fa-trash fa-sm fa-fw mr-2 text-gray-400"></i>
            {{__("global.recycle_bin",["attribute"=>__("global.products")])}}
        </a>
    @endsection

    @section('content')

        <div class="container">
            @if(auth()->user()->getConfig("add_method") != "modal")
                <div class="form-group">
                    <a id="btn_multi_delete" title="{{__("global.delete_selected")}}" class="btn btn-sm btn-danger disable-pointer" href="#" data-toggle="modal" data-target="#deleteConfirmModal" @if(auth()->user()->getConfig("use_recyclebin") == "true") data-route="{{route("product.softDeleteProduct",-1)}}" @else data-route="{{route("product.deleteProduct",-1)}}" @endif >
                        <i class="fas fa-trash"></i>
                        {{__("global.delete_selected")}}
                    </a>
                </div>
                <div class="row">
                    <div class="bg-gray-100 card o-hidden border-0 shadow-lg p-4 col-lg-3 col-sm-12">
                        <form action="{{route("product.storeProduct")}}" method="POST"  accept-charset="UTF-8" enctype="multipart/form-data" autocomplete="off">
                            @csrf
                            <x-forms.products-form></x-forms.products-form>
                            <input tabindex="9" id="btn_add" class="btn btn-primary btn-block" type="submit" value="{{__("global.create")}}">
                        </form>
                    </div>

                    <div class="col-sm-0 col-lg-1"></div>

                    <div class="col-lg-8 col-sm-12">
        @else
            <div>
                <div>
                    <div class="form-group">
                        <a id="btn_add" title="{{__("global.add")}}" class="btn btn-sm btn-info" href="#" data-toggle="modal" data-target="#addModal" data-route="{{route("product.storeProduct")}}">
                            <i class="fas fa-plus"></i>
                            {{__("global.add")}}
                        </a>
                        <a id="btn_multi_delete" title="{{__("global.delete_selected")}}" class="btn btn-sm btn-danger disable-pointer" href="#" data-toggle="modal" data-target="#deleteConfirmModal" @if(auth()->user()->getConfig("use_recyclebin") == "true") data-route="{{route("product.softDeleteProduct",-1)}}" @else data-route="{{route("product.deleteProduct",-1)}}" @endif >
                            <i class="fas fa-trash"></i>
                            {{__("global.delete_selected")}}
                        </a>
                    </div>
        @endif
                    <div class="card shadow">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">{{__("global.products")}}</h6>
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
                                            <th>{{__("global.store")}}</th>
                                            <th>{{__("global.delete")}}</th>
                                        </tr>
                                    </thead>

                                    <tbody>

                                        @foreach ($products as $product)

                                            <tr>
                                                <td><input form="form_delete" name="multi_ids[]" value="{{$product->id}}" type="checkbox" class="form-check"></td>
                                                <td>{{$product->id}}</td>
                                                <td>{{$product->name}}</td>
{{--                                                <td>{{$product->account_type}}</td>--}}
                                                <td>{{$product->is_raw}}</td>
                                                <td>{{$product->reference}}</td>
                                                <td>{{$product->store->name}}</td>
                                                <td class="row m-0">
                                                    <a id="btn_update" title="{{__("global.update")}}" class="dropdown-item col-7 m-0 p-0" href="#" data-toggle="modal" data-target="#updateModal" data-fields="{{$product}}" data-route="{{route("product.updateProduct",$product->id)}}">
                                                        <i class="fas fa-edit text-green"></i>
                                                    </a>
                                                    <a id="btn_delete" title="{{__("global.delete")}}" class="dropdown-item col-5 m-0 p-0" href="#" data-toggle="modal" data-target="#deleteConfirmModal" @if(auth()->user()->getConfig("use_recyclebin") == "true") data-route="{{route("product.softDeleteProduct",$product->id)}}" @else data-route="{{route("product.deleteProduct",$product->id)}}"  @endif>
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
            </div>
        </div>

    @endsection
    @section("modals")
        <x-modals.delete-confirm-modal></x-modals.delete-confirm-modal>
        @if(auth()->user()->getConfig("add_method") == "modal")
            <x-modals.add-modal :modalName="$modalName = 'product'"></x-modals.add-modal>
        @endif
        <x-modals.update-modal :modalName="$modalName = 'product'"></x-modals.update-modal>

    @endsection
    @section("script")

    @endsection
</x-masterLayout.master>








