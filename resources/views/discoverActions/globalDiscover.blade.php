<x-masterLayout.master>
    @section("title")
        {{ __("global.global_discover",[],session("lang")) }}
    @endsection
    @section('content')
        <div class="container">
            @if($actions != null)

                @if(!isset($is_last_year))
                    <form action="{{route("discover.globalDiscoverLastYear")}}" method="post">
                        @csrf
                        <div class="form-group">
                            <input name="account" type="hidden" value="{{$account_name}}">
                            <input type="submit" class="btn btn-outline-primary" value="{{__("global.last_year",[],session("lang"))}}">
                        </div>
                    </form>
                @endif

                    <div class="card shadow">
                    <div class="card-header py-3">
                        <h6 class="d-inline-block m-0 font-weight-bold text-primary">{{__("global.account_name",[],session("lang")) . " : " . $account_name}}</h6>
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
                                    <th>{{__("global.received",[],session("lang"))}}</th>
                                    <th>{{__("global.payed",[],session("lang"))}}</th>
                                    <th>{{__("global.quantity",[],session("lang"))}}</th>
                                    <th>{{__("global.price",[],session("lang"))}}</th>
                                    <th>{{__("global.date",[],session("lang"))}}</th>
                                    <th>{{__("global.account_name",[],session("lang"))}}</th>
                                    <th>{{__("global.product_name",[],session("lang"))}}</th>
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
                                                    @if(in_array($action->invoice_type,["sale","purchase","sale_return","purchase_return"]))
                                                        <a id="btn_show_owner_invoice" href="{{route("invoice.showInvoice",[$action->invoice_id,$action->invoice_type])}}" hidden></a>
                                                    @elseif(in_array($action->invoice_type,["payment","receive"]))
                                                        <a id="btn_show_owner_invoice" href="{{route("invoice.showCashInvoice",$action->invoice_id)}}" hidden></a>
                                                    @endif
                                                @endif
                                            </td>
                                            <td id="row_id" hidden>{{$action->row_id}}</td>
                                            <td>{{$action->sum_of_balance}}</td>
                                            <td>{{$action->debit}}</td>
                                            <td>{{$action->credit}}</td>
                                            <td>{{$action->quantity}}</td>
                                            <td>{{$action->price}}</td>
                                            @if($action->closing_date != null)
                                                <td>{{$action->closing_date->format("Y-m-d")}}</td>
                                            @else
                                                <td></td>
                                            @endif
                                            <td>{{$action->second_part_name}}</td>
                                            <td>{{$action->product_name}}</td>
                                            <td>{{$action->notes}}</td>
                                            <td>{{__("global.$action->invoice_type",[],session("lang"))}}</td>
                                        </tr>
                                    @endforeach
                                @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="card-footer">
                        @if($actions!=null)
                            <label class="ml-md-5 ml-sm-3" style="font-size: large" >{{__("global.total_received",[],session("lang"))}} :
                                <span id="total_received" style="font-style: italic; color:darkblue">{{$total_debit}}</span>
                                {{--                <span id="invoice_pound">{{$actions->first()->pound_type}}</span>--}}
                            </label>
                            <label class="ml-md-5 ml-sm-3" style="font-size: large" >{{__("global.total_payed",[],session("lang"))}} :
                                <span id="total_payed" style="font-style: italic; color:darkblue">{{$total_credit}}</span>
                                {{--                <span id="invoice_pound">{{$actions->first()->pound_type}}</span>--}}{{--should be syrian pound--}}
                            </label>
                            <label  class="ml-md-5 ml-sm-3" style="font-size: large">
                                @if(($total_credit - $total_debit)>0)
                                    {{__("global.you_have",[],session("lang")) . " : ". abs($total_credit - $total_debit)}}
                                @elseif(($total_credit - $total_debit)==0)
                                    {{__("global.balance_is_zero",[],session("lang")) . " : ". abs($total_credit - $total_debit)}}
                                @elseif(($total_credit - $total_debit)<0)
                                    {{__("global.we_have",[],session("lang")) . " : ". abs($total_credit - $total_debit)}}
                                @endif
                            </label>
                        @endif
                    </div>
                </div>
                <div class="p-2">
                    <a href="{{route("discover.chooseListGlobalDiscover")}}" class="btn btn-outline-primary">{{__("global.discovers",[],session("lang"))}}</a>
                    <a href="{{route("invoice.createCashInvoice")}}" class="btn btn-outline-primary">{{__("global.payment",[],session("lang"))}}</a>
                    <a href="{{route("invoice.createCashInvoice")}}" class="btn btn-outline-primary">{{__("global.receive",[],session("lang"))}}</a>
                    <input type="submit" form="check_point_form" class="btn btn-outline-primary" value="{{__("global.check_point",[],session("lang"))}}">
                    <form hidden id="check_point_form" method="post" action="{{route("discover.makeCheckPoint")}}">
                        @csrf
                        <input name="check_point_row_id" id="check_point_row_id" type="hidden" value="">
                    </form>
                </div>
            @else
                <div id="accordion" class="bg-gradient-light shadow p-md-5 p-sm-2" style="width: 50%;margin: auto;">
                    <div id="buttons">
                        <button data-target="#globalDiscoverUntilNowCollapse" class="btn btn-primary btn-block">{{__("global.discover_until_now",[],session("lang"))}}</button>
                        <button data-target="#globalDiscoverAfterLastCheckedPointCollapse" class="btn btn-primary btn-block">{{__("global.discover_until_last_checked_point",[],session("lang"))}}</button>
                        <button data-target="#globalDiscoverUntilLastBalanceCollapse" class="btn btn-primary btn-block">{{__("global.discover_until_last_balance",[],session("lang"))}}</button>
                        <button data-target="#globalDiscoverBetweenTowDatesCollapse" class="btn btn-primary btn-block">{{__("global.discover_between_tow_dates",[],session("lang"))}}</button>
                        <button data-target="#globalDiscoverByAccountCollapse" class="btn btn-primary btn-block">{{__("global.discover_by_balance",[],session("lang"))}}</button>
                    </div>
                    <div id="globalDiscoverUntilNowCollapse" class="collapse">
                        <a id="back"><i class="fas fa-arrow-left" title="{{__("global.back",[],session("lang"))}}"></i></a>
                        <form  style="margin: auto" action="{{route("discover.globalDiscoverUntilNow")}}" method="POST">
                            @csrf
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
                            <input id="btn_submit_global_discover_until_now" type="submit" class="btn btn-outline-primary form-control">
                        </form>
                    </div>
                    <div id="globalDiscoverAfterLastCheckedPointCollapse" class="collapse">
                        <a id="back"><i class="fas fa-arrow-left" title="{{__("global.back",[],session("lang"))}}"></i></a>
                        <form  style="margin: auto" action="{{route("discover.globalDiscoverAfterLastCheckedPoint")}}" method="POST">
                            @csrf
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
                            <input id="btn_submit_global_after_last_checked_point" type="submit" class="btn btn-outline-primary form-control">
                        </form>
                    </div>
                    <div id="globalDiscoverUntilLastBalanceCollapse" class="collapse">
                        <a id="back"><i class="fas fa-arrow-left" title="{{__("global.back",[],session("lang"))}}"></i></a>
                        <form  style="margin: auto" action="{{route("discover.globalDiscoverUntilLastBalance")}}" method="POST">
                            @csrf
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
                            <input id="btn_submit_global_until_last_balance" type="submit" class="btn btn-outline-primary form-control">
                        </form>
                    </div>
                    <div id="globalDiscoverBetweenTowDatesCollapse" class="collapse" >
                        <a id="back"><i class="fas fa-arrow-left" title="{{__("global.back",[],session("lang"))}}"></i></a>
                        <form  style="margin: auto" action="{{route("discover.globalDiscoverBetweenTowDates")}}" method="POST">
                            @csrf
                            <div class="position-relative form-group text-center">
                                <label style="font-size: x-large" for="account" >{{__("global.account",[],session("lang"))}}</label>
                                <input id="account" name="account" type="text" placeholder="" class="form-control dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" />
                                <div style="max-height:200px;overflow-y: scroll" id="dropdown-menu" class="dropdown-menu" aria-labelledby="account">
                                    @foreach(App\Models\Account::get() as $account)
                                        <option class="dropdown-item" value="{{$account->id}}">{{$account->name }}</option>
                                    @endforeach
                                </div>
                                <br>
                                <div class="row">
                                    <label class="col-md-4 col-sm-12" style="font-size: x-large" for="account" >{{__("global.from",[],session("lang"))}}</label>
                                    <input id="from" name="from" class="col-md-8 col-sm-12 form-control" type="date">
                                </div>
                                <div class="row">
                                    <label class="col-md-4 col-sm-12" style="font-size: x-large" for="account" >{{__("global.to",[],session("lang"))}}</label>
                                    <input id="to" name="to" class="col-md-8 col-sm-12 form-control" type="date">
                                </div>
                                <hr>
                            </div>
                            <input id="btn_submit_global_between_tow_dates" type="submit" class="btn btn-outline-primary form-control">
                        </form>
                    </div>
                    <div id="globalDiscoverByAccountCollapse" class="collapse" >
                        <a id="back"><i class="fas fa-arrow-left" title="{{__("global.back",[],session("lang"))}}"></i></a>
                        <form id="accounts_form" style="margin: auto" action="{{route("discover.globalDiscoverByAccount")}}" method="POST">
                            @csrf
                            <div class="position-relative form-group text-center">
                                <label style="font-size: x-large" for="account_1" >{{__("global.account",[],session("lang"))}}</label>
                                <input id="account_1" name="account_1" type="text" placeholder="" class="form-control dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" />
                                <div style="max-height:200px;overflow-y: scroll" id="dropdown-menu" class="dropdown-menu" aria-labelledby="account_1">
                                    @foreach(App\Models\Account::get() as $account)
                                        <option class="dropdown-item" value="{{$account->id}}">{{$account->name }}</option>
                                    @endforeach
                                </div>
                            </div>
                            <div class="position-relative form-group text-center">
                                <label style="font-size: x-large" for="account_2" >{{__("global.account",[],session("lang"))}}</label>
                                <input id="account_2" name="account_2" type="text" placeholder="" class="form-control dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" />
                                <div style="max-height:200px;overflow-y: scroll" id="dropdown-menu" class="dropdown-menu" aria-labelledby="account_2">
                                    @foreach(App\Models\Account::get() as $account)
                                        <option class="dropdown-item" value="{{$account->id}}">{{$account->name }}</option>
                                    @endforeach
                                </div>
                            </div>
                            <hr>
                            <input id="btn_submit_global_by_account" form="accounts_form" type="submit" class="btn btn-outline-primary form-control">
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
                    let errors = "";
                    $(this).parent("form").children("div").filter(function (){
                        errors+=validateDropDownBox($(this).children("input"))+"\n";
                    });
                    if(errors.trim()!==""){
                        e.preventDefault();
                        alert(errors);
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
                        error=$(dropDownBox).attr("id")+ " : is not correct";
                    }
                    return error;
                }

                $("tr#discover_rows").on("dblclick",function (){
                    if ($(this).children("td").children("a#btn_show_owner_invoice")[0]==undefined){
                        return;
                    }
                    $(this).children("td").children("a#btn_show_owner_invoice")[0].click();
                });

                // let pageNumber = 1;
                // setTimeout(function (){
                //     $("li.paginate_button a").on("click",function (){
                //         pageNumber = parseInt($(this).text());
                //         alert(pageNumber);
                //     })
                // },300);
            </script>
            <!-- Page level plugins -->
            <script src={{asset("vendor/datatables/jquery.dataTables.js")}}></script>
            <script src={{asset("vendor/datatables/dataTables.bootstrap4.js")}}></script>

            <!-- Page level custom scripts -->
            <script src={{asset("js/demo/datatables-demo.js?var=415".rand(1,100))}}></script>
        @endsection
</x-masterLayout.master>
