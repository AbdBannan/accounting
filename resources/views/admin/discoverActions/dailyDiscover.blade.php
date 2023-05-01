<x-masterLayout.master>
    @section("title")
        {{ __("global.daily_discover") }}
    @endsection
    @section('content')
        <div class="container">
            @if($actions != null)
                <div class="card shadow">
                    <div class="card-header py-3">
                        <h6 class="d-inline-block m-0 font-weight-bold text-primary">{{\Carbon\Carbon::now()->format("d/m/Y")}}</h6>
                    </div>
                    <div class="card-body">

                        <div class="table-responsive">
                            <table class="table table-bordered table-striped" id="dataTable" width="100%" cellspacing="0">
                                <thead>
                                <tr>
                                    <th hidden></th>
                                    <th>{{__("global.debit")}}</th>
                                    <th>{{__("global.credit")}}</th>
                                    <th>{{__("global.quantity")}}</th>
                                    <th>{{__("global.price")}}</th>
                                    <th>{{__("global.account_name")}}</th>
                                    <th>{{__("global.product_name")}}</th>
                                    <th>{{__("global.notes")}}</th>
                                    <th>{{__("global.invoice_type")}}</th>
                                </tr>
                                </thead>
                                <tbody>
                                @if (count($actions)>0)
                                    @foreach ($actions as $action)
                                        <tr id="discover_rows">
                                            <td hidden>
                                                @if(isset($action->invoice_id))
                                                    @if(in_array($action->invoice_type,["sale","purchase","sale_return","purchase_return"]))
                                                        <a id="btn_show_owner_invoice" href="{{route("invoice.showInvoice",[$action->invoice_id,$action->invoice_type])}}" hidden></a>
                                                    @elseif(in_array($action->invoice_type,["payment","receive"]))
                                                        <a id="btn_show_owner_invoice" href="{{route("invoice.showCashInvoice",$action->invoice_id)}}" hidden></a>
                                                    @elseif(in_array($action->invoice_type,["product_movement"]))
                                                        <a id="btn_show_owner_invoice" href="{{route("invoice.showProductMovementInvoice",$action->invoice_id)}}" hidden></a>
                                                    @elseif(in_array($action->invoice_type,["manufacturing_action"]))
                                                        <a id="btn_show_owner_invoice" href="{{route("invoice.showManufacturingInvoice",$action->invoice_id)}}" hidden></a>
                                                    @endif
                                                @endif
                                            </td>
                                            <td>{{round($action->debit,2)}}</td>
                                            <td>{{round($action->credit,2)}}</td>
                                            <td>{{round($action->quantity,2)}}</td>
                                            <td>{{$action->price}}</td>
                                            <td>{{$action->first_part_name}}</td>
                                            <td>{{$action->product_name}}</td>
                                            <td>{{$action->notes}}</td>
                                            <td>{{__("global.$action->invoice_type")}}</td>
                                        </tr>
                                    @endforeach
                                @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="card-footer">
                        @if($actions!=null)
                            <label class="ml-md-5 ml-sm-3" style="font-size: large" >{{__("global.total_received")}} :
                                <span id="total_received" style="font-style: italic; color:darkblue">{{round($total_debit,2)}}</span>
                                {{--                <span id="invoice_pound">{{$actions->first()->pound_type}}</span>--}}
                            </label>
                            <label class="ml-md-5 ml-sm-3" style="font-size: large" >{{__("global.total_payed")}} :
                                <span id="total_payed" style="font-style: italic; color:darkblue">{{round($total_credit,2)}}</span>
                                {{--                <span id="invoice_pound">{{$actions->first()->pound_type}}</span>--}}{{--should be syrian pound--}}
                            </label>
                        @endif
                    </div>
                </div>
            @endif
        </div>

    @endsection
    @section("script")
        <script>


            $("tr#discover_rows").on("dblclick",function (){
                if ($(this).children("td").children("a#btn_show_owner_invoice")[0]==undefined){
                    return;
                }
                $(this).children("td").children("a#btn_show_owner_invoice")[0].click();
            });

        </script>

    @endsection
</x-masterLayout.master>
