<x-masterLayout.master>

    @if($invoice_type == "sale")
        @section("title")
            {{ __("global.sale_invoices") }}
        @endsection
        @elseif($invoice_type == "purchase")
        @section("title")
            {{ __("global.purchase_invoices") }}
        @endsection
        @elseif($invoice_type == "sale_return")
        @section("title")
            {{ __("global.sale_return_invoices") }}
        @endsection
        @elseif($invoice_type == "purchase_return")
        @section("title")
            {{ __("global.purchase_return_invoices") }}
        @endsection
    @endif

    @section("recycle_bin")
        <a class="dropdown-item" href="{{route("invoice.viewInvoiceRecyclebin")}}">
            <i class="fas fa-trash fa-sm fa-fw mr-2 text-gray-400"></i>
            {{__("global.recycle_bin",["attribute"=>__("global.invoices")])}}
        </a>
    @endsection

    @section('content')
        <div class="container">
            <div class="form-group">
                <a id="btn_multi_delete" title="{{__("global.delete_selected")}}" class="btn btn-sm btn-danger disable-pointer" href="#" data-toggle="modal" data-target="#deleteConfirmModal" @if(auth()->user()->getConfig("use_recyclebin") == "true") data-route="{{route("invoice.softDeleteInvoice",-1)}}" @else data-route="{{route("invoice.deleteInvoice",-1)}}" @endif>
                    <i class="fas fa-trash"></i>
                    {{__("global.delete_selected")}}
                </a>
            </div>
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">{{__("global.invoices")}}</h6>
                </div>
                <div class="card-body">

                    <div class="table-responsive">
                        <table class="table table-bordered table-striped" id="dataTable" width="100%" cellspacing="0">
                            <thead>
                            <tr>
                                <th><input id="check_all" type="checkbox" class="form-check"></th>
                                <th>{{__("global.invoice_id")}}</th>
                                <th>{{__("global.second_part_name")}}</th>
                                <th>{{__("global.value")}}</th>
                                <th>{{__("global.delete")}}</th>
                            </tr>
                            </thead>

                            <tbody>

                                @foreach ($invoices as $invoice)

                                    <tr>
                                        <td><input form="form_delete" name="multi_ids[]" value="{{$invoice->invoice_id}}" type="checkbox" class="form-check"></td>
                                        <td><a id="btn_show_element" href="{{route("invoice.showInvoice",[$invoice->invoice_id,$invoice_type])}}">{{$invoice->invoice_id}}</a></td>
                                        <td>{{$invoice->second_part_name}}</td>
                                        <td>{{$invoice->value}}</td>

                                        <td class="row m-0">
                                            <a id="btn_update" title="{{__("global.update")}}" class="dropdown-item col-7 m-0 p-0" onclick="$('#btn_show_element').get(0).click();">
                                                <i class="fas fa-edit text-green"></i>
                                            </a>
                                            <a id="btn_delete" title="{{__("global.delete")}}" class="dropdown-item col-5 m-0 p-0" href="#" data-toggle="modal" data-target="#deleteConfirmModal" @if(auth()->user()->getConfig("use_recyclebin") == "true") data-route="{{route("invoice.softDeleteInvoice",$invoice->invoice_id)}}" @else data-route="{{route("invoice.deleteInvoice",$invoice->invoice_id)}}" @endif  >
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
        <x-modals.update-modal :modalName="$modalName = 'saleInvoice'"></x-modals.update-modal>

    @endsection
    @section("script")

    @endsection
</x-masterLayout.master>








