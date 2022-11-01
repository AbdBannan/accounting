<x-masterLayout.master>
    @section("title")
        {{ __("global.accounts_discover",[],session("lang")) }}
    @endsection
    @section('content')
        <div class="container">
            @if($info != null)
                <div class="card shadow">
                    <div class="card-header py-3">
                        <h6 class="d-inline-block m-0 font-weight-bold text-primary">{{__("global.accounts_discover",[],session("lang"))}}</h6>
                    </div>
                    <div class="card-body">

                        <div class="table-responsive">
                            <table class="table table-bordered table-striped" id="dataTable" width="100%" cellspacing="0">
                                <thead>
                                <tr>
                                    <th>{{__("global.account_name",[],session("lang"))}}</th>
                                    <th>{{__("global.balance",[],session("lang"))}}</th>
                                    <th>{{__("global.date",[],session("lang"))}}</th>
                                    <th>{{__("global.last_cash_action",[],session("lang"))}}</th>
                                    <th>{{__("global.percent",[],session("lang"))}}</th>
                                </tr>
                                </thead>
                                <tbody>
                                @if (count($info)>0)
                                    @foreach ($info as $inf)
                                        <tr id="discover_rows">
                                            <td>{{$inf["account_name"]}}</td>
                                            <td>{{$inf["balance"]}}</td>
                                            @if($inf["date"] != null)
                                                <td>{{$inf["date"]->format("Y-m-d")}}</td>
                                            @else
                                                <td></td>
                                            @endif
                                            <td>{{$inf["last_cash_action"]}}</td>
                                            <td>{{$inf["percentage"] . "%"}}</td>
                                        </tr>
                                    @endforeach
                                @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="card-footer">
                        @if($info!=null)
                            <label class="ml-md-5 ml-sm-3" style="font-size: large" >{{__("global.total_received",[],session("lang"))}} :
                            <span id="total_received" style="font-style: italic; color:darkblue">{{$total_debit}}</span>
{{--                                <span id="invoice_pound">{{$actions->first()->pound_type}}</span>--}}
                            </label>
                            <label class="ml-md-5 ml-sm-3" style="font-size: large" >{{__("global.total_payed",[],session("lang"))}} :
                                <span id="total_payed" style="font-style: italic; color:darkblue">{{$total_credit}}</span>
{{--                                 <span id="invoice_pound">{{$actions->first()->pound_type}}</span>--}}{{--should be syrian pound--}}
                            </label>
                            <label  class="ml-md-5 ml-sm-3" style="font-size: large">{{__("global.balance",[],session("lang"))}} :
                                <span id="total_payed" style="font-style: italic; color:darkblue">{{$total_credit - $total_debit}}</span>
                            </label>
                        @endif
                    </div>
                </div>
                <div class="p-2">
                    <a href="{{route("discover.chooseListGlobalDiscover")}}" class="btn btn-outline-primary">{{__("global.discovers",[],session("lang"))}}</a>
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

        </script>
      
    @endsection
</x-masterLayout.master>
