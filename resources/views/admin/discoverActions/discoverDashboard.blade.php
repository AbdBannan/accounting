<x-masterLayout.master>
    @section("title")
        {{ __("global.discovers",[],session("lang")) }}
    @endsection
    @section('content')
        <div class="container">
{{--                <div class="d-sm-flex align-items-center justify-content-between mb-4">--}}

{{--                    <div class="dropdown mb-4">--}}
{{--                        <button class="btn btn-primary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">--}}
{{--                            Discovers--}}
{{--                        </button>--}}
{{--                        <div class="dropdown-menu animated--fade-in" aria-labelledby="dropdownMenuButton" style="">--}}
{{--                            <a class="dropdown-item" href="{{route("discover.chooseListGlobalDiscover")}}">{{__("global.global_discover",[],session("lang"))}}</a>--}}
{{--                            <a class="dropdown-item" href="{{route("discover.chooseListCashDiscover")}}">{{__("global.cash_discover",[],session("lang"))}}</a>--}}
{{--                            <a class="dropdown-item" href="{{route("discover.chooseListProductDiscover")}}">{{__("global.product_discover",[],session("lang"))}}</a>--}}
{{--                            <a class="dropdown-item" href="{{route("discover.allAccountsDiscover")}}">{{__("global.all_accounts_discover",[],session("lang"))}}</a>--}}
{{--                            <a class="dropdown-item" href="{{route("discover.allProductsDiscover")}}">{{__("global.all_products_discover",[],session("lang"))}}</a>--}}
{{--                            <a class="dropdown-item" href="{{route("discover.dailyDiscover")}}">{{__("global.daily_discover",[],session("lang"))}}</a>--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                    <a href="{{route("archive.viewArchiveBalances")}}" class="btn btn-primary">{{__("global.role_balances",[],session("lang"))}}</a>--}}
{{--                </div>--}}

            <div style="width: 300px;margin: auto">
                <a class="btn btn-block btn-primary" href="{{route("discover.chooseListGlobalDiscover")}}">{{__("global.global_discover",[],session("lang"))}}</a>
                <a class="btn btn-block btn-primary" href="{{route("discover.chooseListCashDiscover")}}">{{__("global.cash_discover",[],session("lang"))}}</a>
                <a class="btn btn-block btn-primary" href="{{route("discover.chooseListProductDiscover")}}">{{__("global.product_discover",[],session("lang"))}}</a>
                <a class="btn btn-block btn-primary" href="{{route("discover.allAccountsDiscover")}}">{{__("global.all_accounts_discover",[],session("lang"))}}</a>
                <a class="btn btn-block btn-primary" href="{{route("discover.allProductsDiscover")}}">{{__("global.all_products_discover",[],session("lang"))}}</a>
                <a class="btn btn-block btn-primary" href="{{route("discover.dailyDiscover")}}">{{__("global.daily_discover",[],session("lang"))}}</a>
                <a class="btn btn-block btn-primary" href="{{route("archive.viewArchiveBalances")}}">{{__("global.role_balances",[],session("lang"))}}</a>
            </div>
        </div>

    @endsection
    @section("script")
        <!-- Page level plugins -->
        <script src={{asset("vendor/datatables/jquery.dataTables.js")}}></script>
        <script src={{asset("vendor/datatables/dataTables.bootstrap4.js")}}></script>

        <!-- Page level custom scripts -->
        <script src={{asset("js/demo/datatables-demo.js?var=415".rand(1,100))}}></script>
    @endsection
</x-masterLayout.master>
