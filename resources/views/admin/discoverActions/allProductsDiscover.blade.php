<x-masterLayout.master>
    @section("title")
        {{ __("global.all_products_discover") }}
    @endsection
    @section('content')
        <div class="container">
            @if($actions != null)
                <div class="card shadow">
                    <div class="card-header py-3">
                        <h6 class="d-inline-block m-0 font-weight-bold text-primary">{{__("global.all_products_discover")}}</h6>
                    </div>
                    <div class="card-body">

                        <div class="table-responsive">
                            <table class="table table-bordered table-striped" id="dataTable" width="100%" cellspacing="0">
                                <thead>
                                <tr>
                                    <th>{{__("global.product_id")}}</th>
                                    <th>{{__("global.product_name")}}</th>
                                    <th>{{__("global.in_quantity")}}</th>
                                    <th>{{__("global.out_quantity")}}</th>
                                    <th>{{__("global.balance")}}</th>
                                </tr>
                                </thead>
                                <tbody>
                                @if (count($actions)>0)
                                    @foreach ($actions as $action)
                                        <tr id="discover_rows">
                                            <td>{{$action->id}}</td>
                                            <td id="product_name">{{$action->name}}</td>
                                            <td>{{round(abs($action->in_quantity),2)}}</td>
                                            <td>{{round(abs($action->out_quantity),2)}}</td>
                                            <td>{{round($action->balance,2)}}</td>
                                        </tr>
                                    @endforeach
                                @endif
                                </tbody>
                                <tfoot>
                                <tr>
                                    <th colspan="2" id="show_debit_credit"></th>
                                    <th>{{round($total_in_quantity,2)}}</th>
                                    <th>{{round($total_out_quantity,2)}}</th>
                                    <th>{{round($total_balance,2)}}</th>
                                </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                    <form id="go_to_global_discover_form" hidden action="{{route("discover.productDiscoverUntilNow")}}" method="get">
                        @csrf
                        <input id="product" name="product" type="hidden">
                        <input type="submit" hidden>
                    </form>
                    <div class="card-footer">
{{--                        @if($actions!=null)--}}
{{--                            <label class="ml-md-5 ml-sm-3" style="font-size: large" >{{__("global.total_received")}} :--}}
{{--                                <span id="total_received" style="font-style: italic; color:darkblue"></span>--}}
{{--                                --}}{{--                                <span id="invoice_pound">{{$actions->first()->pound_type}}</span>--}}
{{--                            </label>--}}
{{--                            <label class="ml-md-5 ml-sm-3" style="font-size: large" >{{__("global.total_payed")}} :--}}
{{--                                <span id="total_payed" style="font-style: italic; color:darkblue"></span>--}}
{{--                                --}}{{--                                 <span id="invoice_pound">{{$actions->first()->pound_type}}</span>--}}{{----}}{{--should be syrian pound--}}
{{--                            </label>--}}
{{--                        @endif--}}
                    </div>
                </div>
            @endif
        </div>

    @endsection
    @section("script")
        <script>
            $("tr#discover_rows").on("dblclick",function (){
                let product_name = $(this).children("td#product_name").text();
                $("form#go_to_global_discover_form input#product").val(product_name);
                $("form#go_to_global_discover_form input[type='submit']").click();
            });

        </script>

    @endsection
</x-masterLayout.master>
