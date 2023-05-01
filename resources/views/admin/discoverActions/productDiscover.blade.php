<x-masterLayout.master>
    @section("title")
        {{ __("global.product_discover") }}
    @endsection
    @section('content')
        <div class="container">
            @if($actions != null)
                <div class="card shadow">
                    <div class="card-header py-3">
                        <h6 class="d-inline-block m-0 font-weight-bold text-primary">{{__("global.product_name") . " : " . $product_name}}</h6>
                        <br>
                        @if(isset($account_name) )
                            <h6 class="d-inline-block m-0 font-weight-bold text-primary">{{__("global.account_name") . " : " . $account_name}}</h6>
                        @endif
                        @if(isset($start_date) and isset($end_date) )
                            <p class="m-0 font-weight-bold text-secondary">{{__("global.date_between") . " : (" . $start_date . " , " . $end_date . ")"}}</p>
                        @endif
                    </div>
                    <div class="card-body">

                        <div class="table-responsive">
                            <table class="table table-bordered table-striped" id="dataTable" width="100%" cellspacing="0">
                                <thead>
                                <tr>
                                    <th hidden></th>
                                    <th hidden></th>
                                    <th>{{__("global.balance")}}</th>
                                    <th>{{__("global.in_quantity")}}</th>
                                    <th>{{__("global.out_quantity")}}</th>
                                    <th>{{__("global.price")}}</th>
                                    <th>{{__("global.total_price")}}</th>
                                    <th>{{__("global.date")}}</th>
                                    <th>{{__("global.account_name")}}</th>
                                    <th>{{__("global.notes")}}</th>
                                    <th>{{__("global.invoice_type")}}</th>
                                </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $sum_of_quantity = 0;
                                    @endphp
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
                                                    @elseif(in_array($action->invoice_type,["manufacturing_action"]))
                                                        <a id="btn_show_owner_invoice" href="{{route("invoice.showManufacturingInvoice",$action->invoice_id)}}" hidden></a>
                                                    @endif
                                                @endif
                                            </td>
                                            <td id="row_id" hidden>{{$action->row_id}}</td>
                                            @php
                                                $sum_of_quantity += $action->in_quantity - $action->out_quantity
                                            @endphp
                                            <td>{{round($sum_of_quantity,2)}}</td>
                                            <td>{{round($action->in_quantity,2)}}</td>
                                            <td>{{round($action->out_quantity,2)}}</td>
                                            <td>{{$action->price}}</td>
                                            <td>{{$action->price * $action->quantity}}</td>
                                            @if($action->closing_date != null)
                                                <td>{{$action->closing_date->format("Y-m-d")}}</td>
                                            @else
                                                <td></td>
                                            @endif
                                            <td>{{$action->second_part_name}}</td>
                                            <td>{{$action->notes}}</td>
                                            <td>{{__("global.$action->invoice_type")}}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>

                        </div>
                    </div>
                    <div class="card-footer">
                        @if($actions!=null)
                            <label class="ml-md-5 ml-sm-3" style="font-size: large" >{{__("global.in_quantity")}} :
                                <span id="total_received" style="font-style: italic; color:darkblue">{{round($in_quantity,2)}}</span>
                            </label>
                            <label class="ml-md-5 ml-sm-3" style="font-size: large" >{{__("global.out_quantity")}} :
                                <span id="total_payed" style="font-style: italic; color:darkblue">{{round($out_quantity,2)}}</span>
                            </label>
                            <label  class="ml-md-5 ml-sm-3" style="font-size: large">
                                @if(($in_quantity - $out_quantity)>0)
                                    {{__("global.store_has") . " : ". round(abs($in_quantity - $out_quantity),2)}}
                                @elseif(($in_quantity - $out_quantity)==0)
                                    {{__("global.store_not_has_product") . " : ". round(abs($in_quantity - $out_quantity),2)}}
                                @elseif(($in_quantity - $out_quantity)<0)
                                    {{__("global.store_is_down_by") . " : ". round(abs($in_quantity - $out_quantity),2)}}
                                @endif
                                    {{__("global.piece")}}
{{--                                    <sapn> \ {{abs($total_price) . $actions->first()->pound_type}}</sapn>--}}
                                    <sapn> \ {{round(abs($total_price),2)}}</sapn>
                            </label>
                        @endif
                    </div>
                </div>
                <div class="p-2">
                    <a href="{{route("discover.chooseListProductDiscover")}}" class="btn btn-outline-primary">{{__("global.discovers")}}</a>
                </div>
            @else
                <div id="accordion" class="bg-gradient-light shadow p-md-5 p-sm-2" style="width: 50%;margin: auto;">
                    <div id="buttons">
                        <button data-target="#productDiscoverUntilNowCollapse" class="btn btn-primary btn-block">{{__("global.discover_until_now")}}</button>
                        <button data-target="#productDiscoverUntilLastBalanceCollapse" class="btn btn-primary btn-block">{{__("global.discover_until_last_balance")}}</button>
                        <button data-target="#productDiscoverWithAccountCollapse" class="btn btn-primary btn-block">{{__("global.discover_with_account")}}</button>
                        <button data-target="#productDiscoverBetweenTowDatesCollapse" class="btn btn-primary btn-block">{{__("global.discover_between_tow_dates")}}</button>
                        <button data-target="#productDiscoverByStoreCollapse" class="btn btn-primary btn-block">{{__("global.discover_by_store")}}</button>
                    </div>
                    <div id="productDiscoverUntilNowCollapse" class="collapse">
                        <a id="back"><i @if(app()->getLocale() == "en") class="fas fa-arrow-left" @else class="fas fa-arrow-right" @endif title="{{__("global.back")}}"></i></a>
                        <form  style="margin: auto" action="{{route("discover.productDiscoverUntilNow")}}" autocomplete="off">
                            <div class="position-relative form-group text-center">
                                <label style="font-size: x-large" for="product" >{{__("global.account")}}</label>
                                <input tabindex="1" id="product_1" name="product" type="text" placeholder="" class="form-control dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" />
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{__("messages.value_not_found")}}</strong>
                                </span>
                                <div style="max-height:200px;overflow-y: scroll" id="dropdown_menu" class="dropdown-menu" aria-labelledby="product">
                                    @foreach(App\Models\Product::get() as $product)
                                        <option class="dropdown-item" value="{{$product->id}}">{{$product->name }}</option>
                                    @endforeach
                                </div>
                                <hr>
                            </div>
                            <input tabindex="2" id="btn_product_until_now" type="submit" class="btn btn-outline-primary form-control" value="{{__("global.go")}}">
                        </form>
                    </div>
                    <div id="productDiscoverUntilLastBalanceCollapse" class="collapse">
                        <a id="back"><i @if(app()->getLocale() == "en") class="fas fa-arrow-left" @else class="fas fa-arrow-right" @endif title="{{__("global.back")}}"></i></a>
                        <form  style="margin: auto" action="{{route("discover.productDiscoverUntilLastBalance")}}" autocomplete="off">
                            <div class="position-relative form-group text-center">
                                <label style="font-size: x-large" for="product" >{{__("global.account")}}</label>
                                <input tabindex="3" id="product_2" name="product" type="text" placeholder="" class="form-control dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" />
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{__("messages.value_not_found")}}</strong>
                                </span>
                                <div style="max-height:200px;overflow-y: scroll" id="dropdown_menu" class="dropdown-menu" aria-labelledby="product">
                                    @foreach(App\Models\Product::get() as $product)
                                        <option class="dropdown-item" value="{{$product->id}}">{{$product->name }}</option>
                                    @endforeach
                                </div>
                                <hr>
                            </div>
                            <input tabindex="4" id="btn_product_after_last_checked_point" type="submit" class="btn btn-outline-primary form-control" value="{{__("global.go")}}">
                        </form>
                    </div>
                    <div id="productDiscoverWithAccountCollapse" class="collapse">
                        <a id="back"><i @if(app()->getLocale() == "en") class="fas fa-arrow-left" @else class="fas fa-arrow-right" @endif title="{{__("global.back")}}"></i></a>
                        <form style="margin: auto" action="{{route("discover.productDiscoverWithAccount")}}" autocomplete="off">
                            <div class="position-relative form-group text-center">
                                <label style="font-size: x-large" for="product" >{{__("global.product")}}</label>
                                <input tabindex="5" id="product_3" name="product" type="text" placeholder="" class="form-control dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" />
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{__("messages.value_not_found")}}</strong>
                                </span>
                                <div style="max-height:200px;overflow-y: scroll" id="dropdown_menu" class="dropdown-menu" aria-labelledby="product">
                                    @foreach(App\Models\Product::get() as $product)
                                        <option class="dropdown-item" value="{{$product->id}}">{{$product->name }}</option>
                                    @endforeach
                                </div>
                            </div>
                            <div class="position-relative form-group text-center">
                                <label style="font-size: x-large" for="account" >{{__("global.account")}}</label>
                                <input tabindex="6"id="account" name="account" type="text" placeholder="" class="form-control dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" />
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{__("messages.value_not_found")}}</strong>
                                </span>
                                <div style="max-height:200px;overflow-y: scroll" id="dropdown_menu" class="dropdown-menu" aria-labelledby="account">
                                    @foreach(App\Models\Account::get() as $account)
                                        <option class="dropdown-item" value="{{$account->id}}">{{$account->name }}</option>
                                    @endforeach
                                </div>
                                <hr>
                            </div>
                            <input tabindex="7" id="btn_product_with_account" type="submit" class="btn btn-outline-primary form-control" value="{{__("global.go")}}">
                        </form>
                    </div>
                    <div id="productDiscoverBetweenTowDatesCollapse" class="collapse" >
                        <a id="back"><i @if(app()->getLocale() == "en") class="fas fa-arrow-left" @else class="fas fa-arrow-right" @endif title="{{__("global.back")}}"></i></a>
                        <form id="form_discover" style="margin: auto" action="{{route("discover.productDiscoverBetweenTowDates")}}" autocomplete="off">
                            <div class="position-relative form-group text-center">
                                <label style="font-size: x-large" for="product" >{{__("global.account")}}</label>
                                <input tabindex="8" id="product_4" name="product" type="text" placeholder="" class="form-control dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" />
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{__("messages.value_not_found")}}</strong>
                                </span>
                                <div style="max-height:200px;overflow-y: scroll" id="dropdown_menu" class="dropdown-menu" aria-labelledby="product">
                                    @foreach(App\Models\Product::get() as $product)
                                        <option class="dropdown-item" value="{{$product->id}}">{{$product->name }}</option>
                                    @endforeach
                                </div>
                            </div>
                            <div class="row">
                                <label class="col-md-4 col-sm-12" style="font-size: x-large" for="product" >{{__("global.from")}}</label>
{{--                                <input tabindex="9" id="from" name="from" class="col-md-8 col-sm-12 form-control" type="date">--}}
                                <div id="date_container_from" class="col-md-8 col-sm-12 ">

                                </div>
                            </div>
                            <div class="row">
                                <label class="col-md-4 col-sm-12" style="font-size: x-large" for="product" >{{__("global.to")}}</label>
{{--                                <input tabindex="10" id="to" name="to" class="col-md-8 col-sm-12 form-control" type="date">--}}
                                <div id="date_container_to" class="col-md-8 col-sm-12 ">

                                </div>
                            </div>
                            <hr>
                            <input id="btn_product_between_tow_dates" type="submit" class="btn btn-outline-primary form-control" value="{{__("global.go")}}">
                        </form>
                    </div>
                    <div id="productDiscoverByStoreCollapse" class="collapse" >
                        <a id="back"><i @if(app()->getLocale() == "en") class="fas fa-arrow-left" @else class="fas fa-arrow-right" @endif title="{{__("global.back")}}"></i></a>
                        <form  style="margin: auto" action="{{route("discover.productDiscoverByStore")}}" autocomplete="off">
                            <div class="position-relative form-group text-center">
                                <label style="font-size: x-large" for="store" >{{__("global.store")}}</label>
                                <input tabindex="12" id="store" name="store" type="text" placeholder="" class="form-control dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" />
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{__("messages.value_not_found")}}</strong>
                                </span>
                                <div style="max-height:200px;overflow-y: scroll" id="dropdown_menu" class="dropdown-menu" aria-labelledby="store">
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
                                        <input tabindex="13" id="radio_by_last_purchase_price" name="store_discover_type"  type="radio" class="form-check-input" value="last" >
                                        {{__("global.by_last_purchase_price")}}
                                    </label>
                                </div>
                                <div class="form-check">
                                    <label class="form-check-label">
                                        <input tabindex="14" id="radio_by_mean_of_purchase_prices" name="store_discover_type" type="radio" class="form-check-input" value="mean" >
                                        {{__("global.by_mean_of_purchase_prices")}}
                                    </label>
                                </div>
                            </fieldset>
                            <input tabindex="15" id="btn_product_by_store" type="submit" class="btn btn-outline-primary form-control" value="{{__("global.go")}}">
                        </form>
                    </div>
                </div>
            @endif
        </div>

    @endsection
    @section("script")
        <script>

            $("#date_container_from").buildDateInput({classes: "form-control",id:"from",tabindex:9,focusElemAfterFinish:"to",form:"form_discover",initialDate:"{{\Carbon\Carbon::now()->format("Y-m-d")}}"});
            $("#date_container_to").buildDateInput({classes: "form-control",id:"to",focusElemAfterFinish:"btn_product_between_tow_dates",form:"form_discover",initialDate:"{{\Carbon\Carbon::now()->format("Y-m-d")}}"});


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

            function validateDropDownBox(dropDownBox){
                let error="";
                let options = $(dropDownBox).siblings("div").children("option");
                let isThisInputCorrect = false;
                options.each(function (){
                    if ($(dropDownBox).val().trim() == $(this).text().trim()){
                        isThisInputCorrect=true;
                        return;
                    }
                });
                if (!isThisInputCorrect){
                    error=$(dropDownBox).attr("id");
                }
                return error;
            }


            $("a#back").on("click",function (){
                $("#accordion").children().filter(function (){
                    $(this).slideUp();
                });
                $("#buttons").slideDown();
            });



            $("input[type='submit']").on("click",function (e){ // for input select validation
                // let errors = "";
                $(this).parent("form").children("div").children("input[type='text']").each(function (){
                    let error=validateDropDownBox($(this));
                    if (error !== "") {
                        e.preventDefault();
                        $("#" + error).addClass("is-invalid");
                    }
                });
            });

            $("input[type='text']").on("keyup",function (){
                $(".is-invalid").each(function () {
                    $(this).removeClass("is-invalid");
                });
            });


            $("tr#discover_rows").on("dblclick",function (){
                if ($(this).children("td").children("a#btn_show_owner_invoice")[0]==undefined || @if(isset($is_last_year)) true @else false @endif ){
                    return;
                }
                $(this).children("td").children("a#btn_show_owner_invoice")[0].click();
            });
        </script>

    @endsection
</x-masterLayout.master>
