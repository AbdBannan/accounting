<x-masterLayout.master>
    @section("title")
        {{ __("global.recycle_bin",["attribute"=>__("global.cash_invoices",[],session("lang"))],session("lang")) }}
    @endsection

    @section('content')
        <div class="container">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">{{__("global.deleted_",["attribute"=>__("global.cash_invoices",)],session("lang"))}}</h6>
                </div>
                <div class="card-body">

                    <div class="table-responsive">
                        <table class="table table-bordered table-striped" id="dataTable" width="100%" cellspacing="0">
                            <thead>
                            <tr>


                                <th>{{__("global.invoice_id",[],session("lang"))}}</th>
                                <th>{{__("global.second_part_name",[],session("lang"))}}</th>
                                <th>{{__("global.payed",[],session("lang"))}}</th>
                                <th>{{__("global.pound",[],session("lang"))}}</th>
                                <th>{{__("global.received",[],session("lang"))}}</th>
                                <th>{{__("global.delete",[],session("lang"))}}</th>

                            </tr>
                            </thead>

                            <tbody>

                                @foreach ($deletedInvoices as $invoice)

                                    <tr>
                                        <td>{{$invoice->invoice_id}}</td>
                                        <td>{{$invoice->first_part_name}}</td>
                                        <td>{{$invoice->deb}}</td>
                                        <td>{{$invoice->cred}}</td>
                                        <td>{{$invoice->pound_type}}</td>

                                        <td class="row m-0">
                                            <a id="btn-restore" title="{{__("global.restore",[],session("lang"))}}" class="dropdown-item col-7 m-0 p-0" href="#" data-toggle="modal" data-target="#restoreConfirmModal" data-route="{{route("invoice.restoreCashInvoice",$invoice->invoice_id)}}">
                                                <i class="fa fa-undo"></i>
                                            </a>
                                            <a id="btn-delete" title="{{__("global.delete",[],session("lang"))}}" class="dropdown-item col-5 m-0 p-0" href="#" data-toggle="modal" data-target="#deleteConfirmModal"  data-route="{{route("invoice.deleteCashInvoice",$invoice->invoice_id)}}">
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








