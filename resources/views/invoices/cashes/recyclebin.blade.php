<x-masterLayout.master>
    @section("title")
        {{ __("global.recycle_bin",["attribute"=>__("global.cash_invoices")]) }}
    @endsection

    @section('content')
        <div class="container">
            <div class="form-group">
                <a id="btn_multi_restore" title="{{__("global.restore_selected")}}" class="btn btn-sm btn-success disable-pointer" href="#" data-toggle="modal" data-target="#restoreConfirmModal" data-route="{{route("invoice.restoreCashInvoice",-1)}}">
                    <i class="fas fa-undo"></i>
                    {{__("global.restore_selected")}}
                </a>
                <a id="btn_multi_delete" title="{{__("global.delete_selected")}}" class="btn btn-sm btn-danger disable-pointer" href="#" data-toggle="modal" data-target="#deleteConfirmModal" data-route="{{route("invoice.deleteCashInvoice",-1)}}">
                    <i class="fas fa-trash"></i>
                    {{__("global.delete_selected")}}
                </a>
            </div>
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">{{__("global.deleted_",["attribute"=>__("global.cash_invoices",)])}}</h6>
                </div>
                <div class="card-body">

                    <div class="table-responsive">
                        <table class="table table-bordered table-striped" id="dataTable" width="100%" cellspacing="0">
                            <thead>
                            <tr>
                                <th><input id="check_all" type="checkbox" class="form-check"></th>
                                <th>{{__("global.invoice_id")}}</th>
                                <th>{{__("global.second_part_name")}}</th>
                                <th>{{__("global.payed")}}</th>
                                <th>{{__("global.pound")}}</th>
                                <th>{{__("global.received")}}</th>
                                <th>{{__("global.delete")}}</th>

                            </tr>
                            </thead>

                            <tbody>

                                @foreach ($deletedInvoices as $invoice)

                                    <tr>
                                        <td><input form="form_restore" name="multi_ids[]" value="{{$invoice->invoice_id}}" type="checkbox" class="form-check"></td>
                                        <td>{{$invoice->invoice_id}}</td>
                                        <td>{{$invoice->first_part_name}}</td>
                                        <td>{{$invoice->debit}}</td>
                                        <td>{{$invoice->credit}}</td>
                                        <td>{{$invoice->pound_type}}</td>

                                        <td class="row m-0">
                                            <a id="btn_restore" title="{{__("global.restore")}}" class="dropdown-item col-7 m-0 p-0" href="#" data-toggle="modal" data-target="#restoreConfirmModal" data-route="{{route("invoice.restoreCashInvoice",$invoice->invoice_id)}}">
                                                <i class="fa fa-undo"></i>
                                            </a>
                                            <a id="btn_delete" title="{{__("global.delete")}}" class="dropdown-item col-5 m-0 p-0" href="#" data-toggle="modal" data-target="#deleteConfirmModal"  data-route="{{route("invoice.deleteCashInvoice",$invoice->invoice_id)}}">
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








