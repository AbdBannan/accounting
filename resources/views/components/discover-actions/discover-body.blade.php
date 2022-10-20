<div class="card shadow">
    <div class="card-header py-3">
        @yield("heading")
    </div>
    <div class="card-body">

        <div class="table-responsive">
            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                <thead>
                    <tr>
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
                                <td>{{$action->invoice_type}}</td>
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
            <label class="ml-md-5 ml-sm-3" style="font-size: large" >{{__("global.total_received",[],session("lang"))}} :
                <span id="total_received" style="font-style: italic; color:darkblue">{{$totalDebit}}</span>
{{--                <span id="invoice_pound">{{$actions->first()->pound_type}}</span>--}}
            </label>
            <label class="ml-md-5 ml-sm-3" style="font-size: large" >{{__("global.total_payed",[],session("lang"))}} :
                <span id="total_payed" style="font-style: italic; color:darkblue">{{$totalCredit}}</span>
{{--                <span id="invoice_pound">{{$actions->first()->pound_type}}</span>--}}{{--should be syrian pound--}}
            </label>
            <label  class="ml-md-5 ml-sm-3" style="font-size: large">
                @if(($totalCredit - $totalDebit)>0)
                    {{__("global.you_have",[],session("lang")) . " : ". abs($totalCredit - $totalDebit)}}
                @elseif(($totalCredit - $totalDebit)==0)
                    {{__("global.balance_is_zero",[],session("lang")) . " : ". abs($totalCredit - $totalDebit)}}
                @elseif(($totalCredit - $totalDebit)<0)
                    {{__("global.we_have",[],session("lang")) . " : ". abs($totalCredit - $totalDebit)}}
                @endif
            </label>
        @endif
    </div>
</div>
<div class="p-2">
    @yield("discover_list")
    <input type="submit" form="check_point_form" class="btn btn-outline-primary" value="{{__("global.check_point",[],session("lang"))}}">
    <form hidden id="check_point_form" method="post" action="{{route("discover.makeCheckPoint")}}">
        @csrf
        <input name="check_point_row_id" id="check_point_row_id" type="hidden" value="">
    </form>
</div>
