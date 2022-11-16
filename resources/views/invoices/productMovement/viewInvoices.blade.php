<x-masterLayout.master>

    @section("title")
        {{ __("global.product_movement",[],session("lang")) }}
    @endsection

    @section("recycle_bin")
        <a class="dropdown-item" href="{{route("invoice.viewProductMovementRecyclebin")}}">
            <i class="fas fa-trash fa-sm fa-fw mr-2 text-gray-400"></i>
            {{__("global.recycle_bin",["attribute"=>__("global.product_movement",[],session("lang"))],session("lang"))}}
        </a>
    @endsection

    @section('content')
        <div class="container">

            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">{{__("global.product_movement_invoices",[],session("lang"))}}</h6>
                </div>
                <div class="card-body">

                    <div class="table-responsive">
                        <table class="table table-bordered table-striped" id="dataTable" width="100%" cellspacing="0">
                            <thead>
                            <tr>
                                <th>{{__("global.invoice_id",[],session("lang"))}}</th>
                                <th>{{__("global.value",[],session("lang"))}}</th>
                                <th>{{__("global.date",[],session("lang"))}}</th>
                                <th>{{__("global.delete",[],session("lang"))}}</th>
                            </tr>
                            </thead>

                            <tbody>

                                @foreach ($invoices as $invoice)

                                    <tr>
                                        <td><a id="btn_show_element" href="{{route("invoice.showProductMovementInvoice",$invoice->invoice_id)}}>{{$invoice->invoice_id}}"</a></td>
                                        <td>{{$invoice->value}}</td>
                                        <td>{{$invoice->closing_date->format("d/m/Y")}}</td>

                                        <td class="row m-0">
                                            <a id="btn_update" title="{{__("global.update",[],session("lang"))}}" class="dropdown-item col-7 m-0 p-0" onclick="$('#btn_show_element').get(0).click();">
                                                <input class="grid-button grid-edit-button" type="button" title="Update">
                                            </a>
                                            <a id="btn_delete" title="{{__("global.delete",[],session("lang"))}}" class="dropdown-item col-5 m-0 p-0" href="#" data-toggle="modal" data-target="#deleteConfirmModal" data-route="{{route("invoice.softDeleteProductMovementInvoice",$invoice->invoice_id)}}">
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
    @section("modals")
        <x-modals.delete-confirm-modal></x-modals.delete-confirm-modal>
    @endsection
    @section("script")
  
    @endsection
</x-masterLayout.master>








