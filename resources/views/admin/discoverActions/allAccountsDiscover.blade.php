<x-masterLayout.master>
    @section("title")
        {{ __("global.all_accounts_discover") }}
    @endsection
    @section('content')
        <div class="container">
            @if($actions != null)
                <div class="card shadow">
                    <div class="card-header py-3">
                        <h6 class="d-inline-block m-0 font-weight-bold text-primary">{{__("global.all_accounts_discover")}}</h6>
                    </div>
                    <div class="card-body">

                        <div class="table-responsive">
                            <table class="table table-bordered table-striped" id="dataTable" width="100%" cellspacing="0">
                                <thead>
                                <tr>
                                    <th>{{__("global.account_id")}}</th>
                                    <th>{{__("global.account_name")}}</th>
                                    <th class="fade1" style="display: none">{{__("global.debit")}}</th>
                                    <th class="fade1" style="display: none">{{__("global.credit")}}</th>
                                </tr>
                                </thead>
                                <tbody>
                                @if (count($actions)>0)
                                    @foreach ($actions as $action)
                                        <tr id="discover_rows">
                                            <td>{{$action->id}}</td>
                                            <td id="first_part_name">{{$action->name}}</td>
                                            @if($action->credit - $action->debit > 0)
                                                <td class="fade1" style="display: none"></td>
                                                <td class="fade1" style="display: none">{{abs($action->credit - $action->debit)}}</td>
                                            @else
                                                <td class="fade1" style="display: none">{{abs($action->credit - $action->debit)}}</td>
                                                <td class="fade1" style="display: none"></td>
                                            @endif
                                        </tr>
                                    @endforeach
                                @endif
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th colspan="2" id="show_debit_credit">0</th>
                                        <th class="fade1" style="display:none;">{{$total_debit}}</th>
                                        <th class="fade1" style="display:none;">{{$total_credit}}</th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                    <form id="go_to_global_discover_form" hidden action="{{route("discover.globalDiscoverUntilNow")}}" method="get">
                        @csrf
                        <input id="account" name="account" type="hidden">
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
                let first_part_name = $(this).children("td#first_part_name").text();
                $("form#go_to_global_discover_form input#account").val(first_part_name);
                $("form#go_to_global_discover_form input[type='submit']").click();
            });
            $("#show_debit_credit").on("dblclick",function (){
                if ($(this).text() == "0") {
                    $(".fade1").fadeIn();
                    $(this).text("");
                }
                else {
                    $(".fade1").fadeOut();
                    $(this).text(0);
                }
            });
            setTimeout(columnShowHide,200);
            function columnShowHide(){// this is to assign function to pagination
                pagenationChangingShowHide();
                $(".custom-select").on("change",function (){
                    if ($("#show_debit_credit").text() == "") {
                        $(".fade1").show();
                    }
                    else{
                        $(".fade1").hide();
                    }
                    pagenationChangingShowHide();//when pagination anchor is clicked it will be replaced so reassign the function again
                });
                $("#dataTable_filter label input").on("keyup",function (){
                    if ($("#show_debit_credit").text() == "") {
                        $(".fade1").show();
                    }
                    else{
                        $(".fade1").hide();
                    }
                    pagenationChangingShowHide();//when pagination anchor is clicked it will be replaced so reassign the function again
                });
            }

            function pagenationChangingShowHide(){

                $("ul.pagination li,.sorting").on("click",function (){
                    if ($("#show_debit_credit").text() == "") {
                        $(".fade1").show();
                    }
                    else {
                        $(".fade1").hide();
                    }
                    pagenationChangingShowHide();//when pagination anchor is clicked it will be replaced so reassign the function again
                });
            }

        </script>

    @endsection
</x-masterLayout.master>
