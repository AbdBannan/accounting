<x-masterLayout.master>
    @section("title")
        {{ __("global.product_discover",[],session("lang")) }}
    @endsection
    @section('content')
        <div class="container">
            @if($actions != null)
                <div class="card shadow">
                    <div class="card-header py-3">
                        <h6 class="d-inline-block m-0 font-weight-bold text-primary">{{__("global.product_name",[],session("lang")) . " : " . $product_name}}</h6>
                        <br>
                        @if(isset($account_name) )
                            <h6 class="d-inline-block m-0 font-weight-bold text-primary">{{__("global.account_name",[],session("lang")) . " : " . $account_name}}</h6>
                        @endif
                        @if(isset($start_date) and isset($end_date) )
                            <p class="m-0 font-weight-bold text-secondary">{{__("global.date_between",[],session("lang")) . " : (" . $start_date . " , " . $end_date . ")"}}</p>
                        @endif
                    </div>
                    <div class="card-body">

                        <div class="table-responsive">
                            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                <thead>
                                <tr>
                                    <th hidden></th>
                                    <th hidden></th>
                                    <th>{{__("global.balance",[],session("lang"))}}</th>
                                    <th>{{__("global.in_quantity",[],session("lang"))}}</th>
                                    <th>{{__("global.out_quantity",[],session("lang"))}}</th>
                                    <th>{{__("global.price",[],session("lang"))}}</th>
                                    <th>{{__("global.total_price",[],session("lang"))}}</th>
                                    <th>{{__("global.date",[],session("lang"))}}</th>
                                    <th>{{__("global.account_name",[],session("lang"))}}</th>
                                    <th>{{__("global.notes",[],session("lang"))}}</th>
                                    <th>{{__("global.invoice_type",[],session("lang"))}}</th>
                                </tr>
                                </thead>
                                <tbody>
                                @if (count($actions)>0)
                                    @foreach ($actions as $action)
                                        <tr id="discover_rows">
                                            <td hidden>
                                                @if(isset($action->invoice_id))
                                                    @if($action->detail == 0 and in_array($action->invoice_type,["sale","purchase","sale_return","purchase_return"]))
                                                        <a id="btn_show_owner_invoice" href="{{route("invoice.showInvoice",[$action->invoice_id,$action->invoice_type])}}" hidden></a>
                                                    @elseif($action->detail == 1 and in_array($action->invoice_type,["payment","receive"]))
                                                        <a id="btn_show_owner_invoice" href="{{route("invoice.showCashInvoice",$action->invoice_id)}}" hidden></a>
                                                    @elseif($action->detail == 0 and in_array($action->invoice_type,["product_movement"]))
                                                        <a id="btn_show_owner_invoice" href="{{route("invoice.showProductMovementInvoice",$action->invoice_id)}}" hidden></a>
                                                    @endif
                                                @endif
                                            </td>
                                            <td id="row_id" hidden>{{$action->row_id}}</td>
                                            <td>{{$action->sum_of_balance}}</td>
                                            <td>{{$action->in_quantity}}</td>
                                            <td>{{$action->out_quantity}}</td>
                                            <td>{{$action->price}}</td>
                                            <td>{{$action->price * $action->quantity}}</td>
                                            @if($action->closing_date != null)
                                                <td>{{$action->closing_date->format("Y-m-d")}}</td>
                                            @else
                                                <td></td>
                                            @endif
                                            <td>{{$action->second_part_name}}</td>
                                            <td>{{$action->notes}}</td>
                                            <td>{{__("global.$action->invoice_type",[],session("lang"))}}</td>
                                        </tr>
                                    @endforeach
                                @endif
                                </tbody>
                            </table>

                            {{--        {{$actions->render()}}--}}
                        </div>
                    </div>
                    <div class="card-footer">
                        @if($actions!=null)
                            <label class="ml-md-5 ml-sm-3" style="font-size: large" >{{__("global.in_quantity",[],session("lang"))}} :
                                <span id="total_received" style="font-style: italic; color:darkblue">{{$in_quantity}}</span>
                            </label>
                            <label class="ml-md-5 ml-sm-3" style="font-size: large" >{{__("global.out_quantity",[],session("lang"))}} :
                                <span id="total_payed" style="font-style: italic; color:darkblue">{{$out_quantity}}</span>
                            </label>
                            <label  class="ml-md-5 ml-sm-3" style="font-size: large">
                                @if(($in_quantity - $out_quantity)>0)
                                    {{__("global.store_has",[],session("lang")) . " : ". abs($in_quantity - $out_quantity)}}
                                @elseif(($in_quantity - $out_quantity)==0)
                                    {{__("global.store_not_has_product",[],session("lang")) . " : ". abs($in_quantity - $out_quantity)}}
                                @elseif(($in_quantity - $out_quantity)<0)
                                    {{__("global.store_is_down_by",[],session("lang")) . " : ". abs($in_quantity - $out_quantity)}}
                                @endif
                                    {{__("global.piece",[],session("lang"))}}
{{--                                    <sapn> \ {{abs($total_price) . $actions->first()->pound_type}}</sapn>--}}
                                    <sapn> \ {{abs($total_price)}}</sapn>
                            </label>
                        @endif
                    </div>
                </div>
                <div class="p-2">
                    <a href="{{route("discover.chooseListProductDiscover")}}" class="btn btn-outline-primary">{{__("global.discovers",[],session("lang"))}}</a>
                    <form hidden id="check_point_form" method="post" action="{{route("discover.makeCheckPoint")}}">
                        @csrf
                        <input name="check_point_row_id" id="check_point_row_id" type="hidden" value="">
                    </form>
                </div>
            @else
                <div id="accordion" class="bg-gradient-light shadow p-md-5 p-sm-2" style="width: 50%;margin: auto;">
                    <div id="buttons">
                        <button data-target="#productDiscoverUntilNowCollapse" class="btn btn-primary btn-block">{{__("global.discover_until_now",[],session("lang"))}}</button>
                        <button data-target="#productDiscoverUntilLastBalanceCollapse" class="btn btn-primary btn-block">{{__("global.discover_until_last_balance",[],session("lang"))}}</button>
                        <button data-target="#productDiscoverWithAccountCollapse" class="btn btn-primary btn-block">{{__("global.discover_with_account",[],session("lang"))}}</button>
                        <button data-target="#productDiscoverBetweenTowDatesCollapse" class="btn btn-primary btn-block">{{__("global.discover_between_tow_dates",[],session("lang"))}}</button>
                        <button data-target="#productDiscoverByStoreCollapse" class="btn btn-primary btn-block">{{__("global.discover_by_store",[],session("lang"))}}</button>
                    </div>
                    <div id="productDiscoverUntilNowCollapse" class="collapse">
                        <a id="back"><i class="fas fa-arrow-left" title="{{__("global.back",[],session("lang"))}}"></i></a>
                        <form  style="margin: auto" action="{{route("discover.productDiscoverUntilNow")}}" method="POST">
                            @csrf
                            <div class="position-relative form-group text-center">
                                <label style="font-size: x-large" for="product" >{{__("global.account",[],session("lang"))}}</label>
                                <input id="product" name="product" type="text" placeholder="" class="form-control dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" />
                                <div style="max-height:200px;overflow-y: scroll" id="dropdown-menu" class="dropdown-menu" aria-labelledby="product">
                                    @foreach(App\Models\Product::get() as $product)
                                        <option class="dropdown-item" value="{{$product->id}}">{{$product->name }}</option>
                                    @endforeach
                                </div>
                                <hr>
                            </div>
                            <input id="btn_product_until_now" type="submit" class="btn btn-outline-primary form-control">
                        </form>
                    </div>
                    <div id="productDiscoverUntilLastBalanceCollapse" class="collapse">
                        <a id="back"><i class="fas fa-arrow-left" title="{{__("global.back",[],session("lang"))}}"></i></a>
                        <form  style="margin: auto" action="{{route("discover.productDiscoverUntilLastBalance")}}" method="POST">
                            @csrf
                            <div class="position-relative form-group text-center">
                                <label style="font-size: x-large" for="product" >{{__("global.account",[],session("lang"))}}</label>
                                <input id="product" name="product" type="text" placeholder="" class="form-control dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" />
                                <div style="max-height:200px;overflow-y: scroll" id="dropdown-menu" class="dropdown-menu" aria-labelledby="product">
                                    @foreach(App\Models\Product::get() as $product)
                                        <option class="dropdown-item" value="{{$product->id}}">{{$product->name }}</option>
                                    @endforeach
                                </div>
                                <hr>
                            </div>
                            <input id="btn_product_after_last_checked_point" type="submit" class="btn btn-outline-primary form-control">
                        </form>
                    </div>
                    <div id="productDiscoverWithAccountCollapse" class="collapse">
                        <a id="back"><i class="fas fa-arrow-left" title="{{__("global.back",[],session("lang"))}}"></i></a>
                        <form style="margin: auto" action="{{route("discover.productDiscoverWithAccount")}}" method="POST">
                            @csrf
                            <div class="position-relative form-group text-center">
                                <label style="font-size: x-large" for="product" >{{__("global.product",[],session("lang"))}}</label>
                                <input id="product" name="product" type="text" placeholder="" class="form-control dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" />
                                <div style="max-height:200px;overflow-y: scroll" id="dropdown-menu" class="dropdown-menu" aria-labelledby="product">
                                    @foreach(App\Models\Product::get() as $product)
                                        <option class="dropdown-item" value="{{$product->id}}">{{$product->name }}</option>
                                    @endforeach
                                </div>
                            </div>
                            <div class="position-relative form-group text-center">
                                <label style="font-size: x-large" for="account" >{{__("global.account",[],session("lang"))}}</label>
                                <input id="account" name="account" type="text" placeholder="" class="form-control dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" />
                                <div style="max-height:200px;overflow-y: scroll" id="dropdown-menu" class="dropdown-menu" aria-labelledby="account">
                                    @foreach(App\Models\Account::get() as $account)
                                        <option class="dropdown-item" value="{{$account->id}}">{{$account->name }}</option>
                                    @endforeach
                                </div>
                                <hr>
                            </div>
                            <input id="btn_product_with_account" type="submit" class="btn btn-outline-primary form-control">
                        </form>
                    </div>
                    <div id="productDiscoverBetweenTowDatesCollapse" class="collapse" >
                        <a id="back"><i class="fas fa-arrow-left" title="{{__("global.back",[],session("lang"))}}"></i></a>
                        <form  style="margin: auto" action="{{route("discover.productDiscoverBetweenTowDates")}}" method="POST">
                            @csrf
                            <div class="position-relative form-group text-center">
                                <label style="font-size: x-large" for="product" >{{__("global.account",[],session("lang"))}}</label>
                                <input id="product" name="product" type="text" placeholder="" class="form-control dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" />
                                <div style="max-height:200px;overflow-y: scroll" id="dropdown-menu" class="dropdown-menu" aria-labelledby="product">
                                    @foreach(App\Models\Product::get() as $product)
                                        <option class="dropdown-item" value="{{$product->id}}">{{$product->name }}</option>
                                    @endforeach
                                </div>
                                <br>
                                <div class="row">
                                    <label class="col-md-4 col-sm-12" style="font-size: x-large" for="product" >{{__("global.from",[],session("lang"))}}</label>
                                    <input id="from" name="from" class="col-md-8 col-sm-12 form-control" type="date">
                                </div>
                                <div class="row">
                                    <label class="col-md-4 col-sm-12" style="font-size: x-large" for="product" >{{__("global.to",[],session("lang"))}}</label>
                                    <input id="to" name="to" class="col-md-8 col-sm-12 form-control" type="date">
                                </div>
                                <hr>
                            </div>
                            <input id="btn_product_between_tow_dates" type="submit" class="btn btn-outline-primary form-control">
                        </form>
                    </div>
                    <div id="productDiscoverByStoreCollapse" class="collapse" >
                        <a id="back"><i class="fas fa-arrow-left" title="{{__("global.back",[],session("lang"))}}"></i></a>
                        <form  style="margin: auto" action="{{route("discover.productDiscoverByStore")}}" method="POST">
                            @csrf
                            <div class="position-relative form-group text-center">
                                <label style="font-size: x-large" for="store" >{{__("global.product",[],session("lang"))}}</label>
                                <input id="store" name="store" type="text" placeholder="" class="form-control dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" />
                                <div style="max-height:200px;overflow-y: scroll" id="dropdown-menu" class="dropdown-menu" aria-labelledby="store">
                                    @foreach(App\Models\Store::get() as $store)
                                        <option class="dropdown-item" value="{{$store->id}}">{{$store->name }}</option>
                                    @endforeach
                                </div>
                                <hr>
                            </div>
                            <fieldset class="form-group">
{{--                                <legend>What are you using this service for?</legend>--}}
                                <div class="form-check">
                                    <label class="form-check-label">
                                        <input name="store_discover_type"  type="radio" class="form-check-input" value="last" >
                                        {{__("global.by_last_purchase_price",[],session("lang"))}}
                                    </label>
                                </div>
                                <div class="form-check">
                                    <label class="form-check-label">
                                        <input name="store_discover_type" type="radio" class="form-check-input" value="mean" >
                                        {{__("global.by_mean_of_purchase_prices",[],session("lang"))}}
                                    </label>
                                </div>
                            </fieldset>
                            <input id="btn_product_by_store" type="submit" class="btn btn-outline-primary form-control">
                        </form>
                    </div>
                </div>
            @endif
        </div>

    @endsection
    @section("script")
        <script>
            $("#buttons button").on("click",function (){// for collapse all and expand just onw
                $(this).parent().slideUp();
                $("#accordion").children().filter(function (){
                    $(this).slideUp();
                });
                $($(this).data("target")).slideToggle();
                let elem = this;
                setTimeout(function (){
                    let v = $(elem).data("target") + " form div input";
                    $(v).get(0).focus();
                },300);
            });


            $("input[type='submit']").on("click",function (e){
                let error = validateDropDownBox($(this).parent("form").children("div").children("input"))
                if(error !== ""){
                    e.preventDefault();
                    alert(error);
                }
            });

            $("a#back").on("click",function (){
                $("#accordion").children().filter(function (){
                    $(this).slideUp();
                });
                $("#buttons").slideDown();
            });


            function validateDropDownBox(dropDownBox){
                let error="";

                let options = $(dropDownBox).siblings("div").children("option");
                console.log(options);
                let isThisInputCorrect = false;
                for (let opt in options){
                    if (Number(options[opt]))
                        break;
                    if ($(dropDownBox).val().trim() == $(options[opt]).text().trim()){
                        isThisInputCorrect=true;
                        break;
                    }
                }
                if (!isThisInputCorrect){
                    error=$(dropDownBox).attr("id") + " : is not correct";
                }
                return error;
            }


            $("tr#discover_rows").on("dblclick",function (){
                if ($(this).children("td").children("a#btn_show_owner_invoice")[0]==undefined){
                    return;
                }
                $(this).children("td").children("a#btn_show_owner_invoice")[0].click();
            });
        </script>
        <!-- Page level plugins -->
        <script src={{asset("vendor/datatables/jquery.dataTables.js")}}></script>
        <script src={{asset("vendor/datatables/dataTables.bootstrap4.js")}}></script>

        <!-- Page level custom scripts -->
        <script src={{asset("js/demo/datatables-demo.js?var=415".rand(1,100))}}></script>
    @endsection
</x-masterLayout.master>
