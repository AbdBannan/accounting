<x-masterLayout.master>
    @section("title")
        {{ __("global.products_discover_by_store",[],session("lang")) }}
    @endsection
    @section('content')
        <div class="container">
            @if($actions != null)
                <div class="card shadow">
                    <div class="card-header py-3">
                        <h6 class="d-inline-block m-0 font-weight-bold text-primary">{{__("global.products_discover_by_store",[],session("lang"))}}</h6>
                    </div>
                    <div class="card-body">

                        <div class="table-responsive">
                            <table class="table table-bordered table-striped" id="dataTable" width="100%" cellspacing="0">
                                <thead>
                                <tr>
                                    <th>{{__("global.product_id",[],session("lang"))}}</th>
                                    <th>{{__("global.product_name",[],session("lang"))}}</th>
                                    <th>{{__("global.balance",[],session("lang"))}}</th>
                                    <th>{{__("global.price",[],session("lang"))}}</th>
                                    <th>{{__("global.total",[],session("lang"))}}</th>
                                </tr>
                                </thead>
                                <tbody>
                                @if (count($actions)>0)
                                    @foreach ($actions as $action)
                                        <tr id="discover_rows">
                                            <td>{{$action["product_id"]}}</td>
                                            <td id="product_name">{{$action["product_name"]}}</td>
                                            <td>{{$action["balance"]}}</td>
                                            <td>{{$action["price"]}}</td>
                                            <td>{{$action["balance"] * $action["price"]}}</td>
                                        </tr>
                                    @endforeach
                                @endif
                                </tbody>
                                <tfoot>
                                <tr>
                                    <th colspan="4"></th>
                                    <th>{{$accumulated_total_price}}</th>
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
                            <label class="ml-md-5 ml-sm-3" style="font-size: large" >{{__("global.total_in_quantity",[],session("lang"))}} :
                                <span id="total_received" style="font-style: italic; color:darkblue">{{$total_in_quantity}}</span>
                                {{--                                <span id="invoice_pound">{{$actions->first()->pound_type}}</span>--}}
                            </label>
                            <label class="ml-md-5 ml-sm-3" style="font-size: large" >{{__("global.total_out_quantity",[],session("lang"))}} :
                                <span id="total_payed" style="font-style: italic; color:darkblue">{{$total_out_quantity}}</span>
                                {{--                                 <span id="invoice_pound">{{$actions->first()->pound_type}}</span>--}}{{--should be syrian pound--}}
                            </label>
                            <label class="ml-md-5 ml-sm-3" style="font-size: large" >{{__("global.balance",[],session("lang"))}} :
                                <span id="total_payed" style="font-style: italic; color:darkblue">{{$total_balance}}</span>
                                {{--                                 <span id="invoice_pound">{{$actions->first()->pound_type}}</span>--}}{{--should be syrian pound--}}
                            </label>
                        @endif
                    </div>
                </div>
            @endif
            <a href="{{route("discover.chooseListProductDiscover")}}" class="btn btn-outline-primary">{{__("global.discovers",[],session("lang"))}}</a>
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
