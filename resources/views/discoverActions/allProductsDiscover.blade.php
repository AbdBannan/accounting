<x-masterLayout.master>
    @section("title")
        {{ __("global.all_products_discover",[],session("lang")) }}
    @endsection
    @section('content')
        <div class="container">
            @if($actions != null)
                <div class="card shadow">
                    <div class="card-header py-3">
                        <h6 class="d-inline-block m-0 font-weight-bold text-primary">{{__("global.all_products_discover",[],session("lang"))}}</h6>
                    </div>
                    <div class="card-body">

                        <div class="table-responsive">
                            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                <thead>
                                <tr>
                                    <th>{{__("global.product_id",[],session("lang"))}}</th>
                                    <th>{{__("global.product_name",[],session("lang"))}}</th>
                                    <th>{{__("global.in_quantity",[],session("lang"))}}</th>
                                    <th>{{__("global.out_quantity",[],session("lang"))}}</th>
                                    <th>{{__("global.balance",[],session("lang"))}}</th>
                                </tr>
                                </thead>
                                <tbody>
                                @if (count($actions)>0)
                                    @foreach ($actions as $action)
                                        <tr id="discover_rows">
                                            <td>{{$action->id}}</td>
                                            <td id="product_name">{{$action->name}}</td>
                                            <td>{{abs($action->in_quantity)}}</td>
                                            <td>{{abs($action->out_quantity)}}</td>
                                            <td>{{$action->balance}}</td>
                                        </tr>
                                    @endforeach
                                @endif
                                </tbody>
                                <tfoot>
                                <tr>
                                    <th colspan="2" id="show_debit_credit"></th>
                                    <th>{{$total_in_quantity}}</th>
                                    <th>{{$total_out_quantity}}</th>
                                    <th>{{$total_balance}}</th>
                                </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                    <form id="go_to_global_discover_form" hidden action="{{route("discover.productDiscoverUntilNow")}}" method="post">
                        @csrf
                        <input id="product" name="product" type="hidden">
                        <input type="submit" hidden>
                    </form>
                    <div class="card-footer">
                        @if($actions!=null)
                            <label class="ml-md-5 ml-sm-3" style="font-size: large" >{{__("global.total_received",[],session("lang"))}} :
                                <span id="total_received" style="font-style: italic; color:darkblue"></span>
                                {{--                                <span id="invoice_pound">{{$actions->first()->pound_type}}</span>--}}
                            </label>
                            <label class="ml-md-5 ml-sm-3" style="font-size: large" >{{__("global.total_payed",[],session("lang"))}} :
                                <span id="total_payed" style="font-style: italic; color:darkblue"></span>
                                {{--                                 <span id="invoice_pound">{{$actions->first()->pound_type}}</span>--}}{{--should be syrian pound--}}
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
                let product_name = $(this).children("td#product_name").text();
                $("form#go_to_global_discover_form input#product").val(product_name);
                $("form#go_to_global_discover_form input[type='submit']").click();
            });

        </script>
        <!-- Page level plugins -->
        <script src={{asset("vendor/datatables/jquery.dataTables.js")}}></script>
        <script src={{asset("vendor/datatables/dataTables.bootstrap4.js")}}></script>

        <!-- Page level custom scripts -->
        <script src={{asset("js/demo/datatables-demo.js?var=415".rand(1,100))}}></script>
    @endsection
</x-masterLayout.master>
