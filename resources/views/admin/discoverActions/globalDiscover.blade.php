<x-masterLayout.master>
    @section("title")
        {{ __("global.global_discover") }}
    @endsection
    @section('content')
        <div class="container">
            @if($actions != null)

                @if(!isset($is_last_year))
                    <form action="{{route("discover.globalDiscoverLastYear")}}" >
                        <div class="form-group">
                            <input name="account" type="hidden" value="{{$account_name}}">
                            <input type="submit" class="btn btn-outline-primary" value="{{__("global.last_year")}}">
                        </div>
                    </form>
                @endif

                    <div class="card shadow">
                    <div class="card-header py-3">
                        <h6 class="d-inline-block m-0 font-weight-bold text-primary">{{__("global.account_name") . " : " . $account_name}}</h6>
                        @if(isset($start_date) and isset($end_date) )
                            <p class="m-0 font-weight-bold text-secondary">{{__("global.date_between") . " : (" . $start_date . " , " . $end_date . ")"}}</p>
                        @endif
                    </div>
                    <div class="card-body">

                        <div class="table-responsive">
                            <table class="table table-bordered table-striped" id="dataTable1" width="100%" cellspacing="0">
                                <thead>
                                <tr>
                                    <th hidden></th>
                                    <th hidden></th>
                                    <th>{{__("global.balance")}}</th>
                                    <th>{{__("global.received")}}</th>
                                    <th>{{__("global.payed")}}</th>
                                    <th>{{__("global.quantity")}}</th>
                                    <th>{{__("global.price")}}</th>
                                    <th>{{__("global.date")}}</th>
                                    <th>{{__("global.account_name")}}</th>
                                    <th>{{__("global.product_name")}}</th>
                                    <th>{{__("global.notes")}}</th>
                                    <th>{{__("global.invoice_type")}}</th>
                                </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $sum_of_balance = 0;
                                    @endphp
                                    @foreach ($actions as $action)
                                        <tr id="discover_rows">
                                            <td hidden>
                                                @if(isset($action->invoice_id))
                                                    @if(in_array($action->invoice_type,["sale","purchase","sale_return","purchase_return"]))
                                                        <a id="btn_show_owner_invoice" href="{{route("invoice.showInvoice",[$action->invoice_id,$action->invoice_type])}}" hidden></a>
                                                    @elseif(in_array($action->invoice_type,["payment","receive"]))
                                                        <a id="btn_show_owner_invoice" href="{{route("invoice.showCashInvoice",$action->invoice_id)}}" hidden></a>
                                                    @elseif(in_array($action->invoice_type,["manufacturing_action"]))
                                                        <a id="btn_show_owner_invoice" href="{{route("invoice.showManufacturingInvoice",$action->invoice_id)}}" hidden></a>
                                                    @endif
                                                @endif
                                            </td>
                                            <td id="row_id" hidden>{{$action->row_id}}</td>
                                            @php
                                                $sum_of_balance += $action->credit - $action->debit
                                            @endphp
                                            <td>{{(round($sum_of_balance,2))}}</td>
                                            <td>{{round($action->debit,2)}}</td>
                                            <td>{{round($action->credit,2)}}</td>
                                            <td>{{round($action->quantity,2)}}</td>
                                            <td>{{$action->price}}</td>
                                            @if($action->closing_date != null)
                                                <td>{{$action->closing_date->format("Y-m-d")}}</td>
                                            @else
                                                <td></td>
                                            @endif
                                            <td>{{$action->second_part_name}}</td>
                                            <td>{{$action->product_name}}</td>
                                            @if($action->equivalent === 1)
                                            <td>{{$action->notes . " (" . __("global.check_point") . ")"}}</td>
                                            @elseif ($action->num_for_pound > 1)
                                            <td>{{$action->notes . " (" . $action->num_for_pound . $action->pound_type . ")"}}</td>
                                            @else
                                                <td>{{$action->notes  }}</td>
                                            @endif
                                            <td>{{__("global.$action->invoice_type")}}</td>
                                        </tr>
                                    @endforeach
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
                            <label  class="ml-md-5 ml-sm-3" style="font-size: large">
                                @if(($total_credit - $total_debit)>0)
                                    {{__("global.you_have") . " : ". round(abs($total_credit - $total_debit),2)}}
                                @elseif(($total_credit - $total_debit)==0)
                                    {{__("global.balance_is_zero") . " : ". round(abs($total_credit - $total_debit),2)}}
                                @elseif(($total_credit - $total_debit)<0)
                                    {{__("global.we_have") . " : ". round(abs($total_credit - $total_debit),2)}}
                                @endif
                            </label>
                        @endif
                    </div>
                </div>
                <div class="p-2">
                    <a href="{{route("discover.chooseListGlobalDiscover")}}" class="btn btn-outline-primary">{{__("global.discovers")}}</a>
                    <a href="{{route("invoice.createCashInvoice")}}" class="btn btn-outline-primary">{{__("global.payment")}}</a>
                    <a href="{{route("invoice.createCashInvoice")}}" class="btn btn-outline-primary">{{__("global.receive")}}</a>
                    <input type="submit" form="check_point_form" class="btn btn-outline-primary" value="{{__("global.check_point")}}">
                    <form hidden id="check_point_form" method="post" action="{{route("discover.makeCheckPoint")}}">
                        @csrf
                        <input name="check_point_row_id" id="check_point_row_id" type="hidden" value="">
                    </form>
                </div>
            @else
                <div id="accordion" class="bg-gradient-light shadow p-md-5 p-sm-2" style="width: 50%;margin: auto;">
                    <div id="buttons">
                        <button data-target="#globalDiscoverUntilNowCollapse" class="btn btn-primary btn-block">{{__("global.discover_until_now")}}</button>
                        <button data-target="#globalDiscoverAfterLastCheckedPointCollapse" class="btn btn-primary btn-block">{{__("global.discover_until_last_checked_point")}}</button>
                        <button data-target="#globalDiscoverUntilLastBalanceCollapse" class="btn btn-primary btn-block">{{__("global.discover_until_last_balance")}}</button>
                        <button data-target="#globalDiscoverBetweenTowDatesCollapse" class="btn btn-primary btn-block">{{__("global.discover_between_tow_dates")}}</button>
                        <button data-target="#globalDiscoverByAccountCollapse" class="btn btn-primary btn-block">{{__("global.discover_by_balance")}}</button>
                    </div>
                    <div id="globalDiscoverUntilNowCollapse" class="collapse">
                        <a id="back"><i @if(app()->getLocale() == "en") class="fas fa-arrow-left" @else class="fas fa-arrow-right" @endif title="{{__("global.back")}}"></i></a>
                        <form  style="margin: auto" action="{{route("discover.globalDiscoverUntilNow")}}" autocomplete="off">
                            <div class="position-relative form-group text-center">
                                <label style="font-size: x-large" for="account" >{{__("global.account")}}</label>
                                <input tabindex="1" id="account_1" name="account" type="text" placeholder="" class="form-control dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" />
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
                            <input tabindex="2" id="btn_submit_global_discover_until_now" type="submit" class="btn btn-outline-primary form-control" value="{{__("global.go")}}">
                        </form>
                    </div>
                    <div id="globalDiscoverAfterLastCheckedPointCollapse" class="collapse">
                        <a id="back"><i @if(app()->getLocale() == "en") class="fas fa-arrow-left" @else class="fas fa-arrow-right" @endif title="{{__("global.back")}}"></i></a>
                        <form  style="margin: auto" action="{{route("discover.globalDiscoverAfterLastCheckedPoint")}}" autocomplete="off">
                            <div class="position-relative form-group text-center">
                                <label style="font-size: x-large" for="account" >{{__("global.account")}}</label>
                                <input tabindex="3" id="account_2" name="account" type="text" placeholder="" class="form-control dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" />
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
                            <input tabindex="4" id="btn_submit_global_after_last_checked_point" type="submit" class="btn btn-outline-primary form-control" value="{{__("global.go")}}">
                        </form>
                    </div>
                    <div id="globalDiscoverUntilLastBalanceCollapse" class="collapse">
                        <a id="back"><i @if(app()->getLocale() == "en") class="fas fa-arrow-left" @else class="fas fa-arrow-right" @endif title="{{__("global.back")}}"></i></a>
                        <form  style="margin: auto" action="{{route("discover.globalDiscoverUntilLastBalance")}}" autocomplete="off">
                            <div class="position-relative form-group text-center">
                                <label style="font-size: x-large" for="account" >{{__("global.account")}}</label>
                                <input tabindex="5" id="account_3" name="account" type="text" placeholder="" class="form-control dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" />
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
                            <input tabindex="6" id="btn_submit_global_until_last_balance" type="submit" class="btn btn-outline-primary form-control" value="{{__("global.go")}}">
                        </form>
                    </div>
                    <div id="globalDiscoverBetweenTowDatesCollapse" class="collapse" >
                        <a id="back"><i @if(app()->getLocale() == "en") class="fas fa-arrow-left" @else class="fas fa-arrow-right" @endif title="{{__("global.back")}}"></i></a>
                        <form id="form_discover" style="margin: auto" action="{{route("discover.globalDiscoverBetweenTowDates")}}" autocomplete="off">
                            <div class="position-relative form-group text-center">
                                <label style="font-size: x-large" for="account" >{{__("global.account")}}</label>
                                <input tabindex="7" id="account_4" name="account" type="text" placeholder="" class="form-control dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" />
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{__("messages.value_not_found")}}</strong>
                                </span>
                                <div style="max-height:200px;overflow-y: scroll" id="dropdown_menu" class="dropdown-menu" aria-labelledby="account">
                                    @foreach(App\Models\Account::get() as $account)
                                        <option class="dropdown-item" value="{{$account->id}}">{{$account->name }}</option>
                                    @endforeach
                                </div>
                            </div>
                            <div class="row">
                                <label class="col-md-4 col-sm-12" style="font-size: x-large" for="account" >{{__("global.from")}}</label>
                                <div id="date_container_from" class="col-md-8 col-sm-12 ">

                                </div>
{{--                                <input tabindex="8" id="from" name="from" class="col-md-8 col-sm-12 form-control" type="date">--}}
                            </div>
                            <div  class="row">
                                <label class="col-md-4 col-sm-12" style="font-size: x-large" for="account" >{{__("global.to")}}</label>
                                <div id="date_container_to" class="col-md-8 col-sm-12 ">

                                </div>
{{--                                <input tabindex="9" id="to" name="to" class="col-md-8 col-sm-12 form-control" type="date">--}}
                            </div>
                            <hr>
                            <input id="btn_submit_global_between_tow_dates" type="submit" class="btn btn-outline-primary form-control" value="{{__("global.go")}}">
                        </form>
                    </div>
                    <div id="globalDiscoverByAccountCollapse" class="collapse" >
                        <a id="back"><i @if(app()->getLocale() == "en") class="fas fa-arrow-left" @else class="fas fa-arrow-right" @endif title="{{__("global.back")}}"></i></a>
                        <form id="accounts_form" style="margin: auto" action="{{route("discover.globalDiscoverByAccount")}}" autocomplete="off">
                            <div class="position-relative form-group text-center">
                                <label style="font-size: x-large" for="account_1" >{{__("global.account")}}</label>
                                <input tabindex="11" id="account_5" name="account_1" type="text" placeholder="" class="form-control dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" />
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{__("messages.value_not_found")}}</strong>
                                </span>
                                <div style="max-height:200px;overflow-y: scroll" id="dropdown_menu" class="dropdown-menu" aria-labelledby="account_1">
                                    @foreach(App\Models\Account::get() as $account)
                                        <option class="dropdown-item" value="{{$account->id}}">{{$account->name }}</option>
                                    @endforeach
                                </div>
                            </div>
                            <div class="position-relative form-group text-center">
                                <label style="font-size: x-large" for="account_2" >{{__("global.account")}}</label>
                                <input tabindex="12" id="account_6" name="account_2" type="text" placeholder="" class="form-control dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" />
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{__("messages.value_not_found")}}</strong>
                                </span>
                                <div style="max-height:200px;overflow-y: scroll" id="dropdown_menu" class="dropdown-menu" aria-labelledby="account_2">
                                    @foreach(App\Models\Account::get() as $account)
                                        <option class="dropdown-item" value="{{$account->id}}">{{$account->name }}</option>
                                    @endforeach
                                </div>
                            </div>
                            <hr>
                            <input tabindex="13" id="btn_submit_global_by_account" form="accounts_form" type="submit" class="btn btn-outline-primary form-control" value="{{__("global.go")}}">
                        </form>

                    </div>
                </div>
            @endif
        </div>

    @endsection
    @section("script")
        <script>

            $("#date_container_from").buildDateInput({classes: "form-control",id:"from",tabindex:8,focusElemAfterFinish:"to",form:"form_discover",initialDate:"{{\Carbon\Carbon::now()->format("Y-m-d")}}"});
            $("#date_container_to").buildDateInput({classes: "form-control",id:"to",focusElemAfterFinish:"btn_submit_global_between_tow_dates",form:"form_discover",initialDate:"{{\Carbon\Carbon::now()->format("Y-m-d")}}"});


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

                $("a#back").on("click",function (){
                    $("#accordion").children().filter(function (){
                        $(this).slideUp();
                    });
                    $("#buttons").slideDown();
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


                $("tr#discover_rows").on("dblclick",function (){
                    if ($(this).children("td").children("a#btn_show_owner_invoice")[0]==undefined || @if(isset($is_last_year)) true @else false @endif ){
                        return;
                    }
                    $(this).children("td").children("a#btn_show_owner_invoice")[0].click();
                });





                let info = "{{__("global.Showing")}} _START_ {{__("global.to")}} _END_ {{__("global.of")}} _TOTAL_ {{__("global.entries")}}";
                let emptyTable = "{{__("global.no_data_available_in_table")}}";
                let infoEmpty = "{{__("global.Showing")}} 0 {{__("global.to")}} 0 {{__("global.of")}} 0 {{__("global.entries")}}";
                let lengthMenu = "{{__("global.Show")}} _MENU_ {{__("global.entries")}}";let loadingRecords = "Please wait - loading...";
                let search = "{{__("global.Search")}}:";
                let next = "{{__("global.Next")}}";
                let previous = "{{__("global.Previous")}}";
                let infoFiltered = " - {{__("global.filtered_from")}} _MAX_ {{__("global.entries")}}";
                $('#dataTable1').DataTable(
                    {
                        "ordering":false,
                        "autoWidth": false,
                        "language": {
                            // "info": "Showing page _PAGE_ of _PAGES_",
                            "info": info,
                            "infoEmpty": infoEmpty,
                            "emptyTable": emptyTable,
                            "lengthMenu": lengthMenu,
                            "loadingRecords": loadingRecords,
                            "search": search,
                            "paginate": {
                                "next": next,
                                "previous": previous,
                            },
                            "infoFiltered": infoFiltered,
                        },
                        "processing": true,
                        "stateSave": true,
                        "createdRow": function( row, data, dataIndex ) {
                            // alert();
                        },
                        // "scrollCollapse": true,
                    });

            </script>
    @endsection
</x-masterLayout.master>
