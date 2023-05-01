<x-masterLayout.master>
    @section("title")
        {{ __("global.discovers") }}
    @endsection
    @section('content')
        <div class="container">
            <div style="width: 300px;margin: auto">
                <a class="btn btn-block bg-gradient-success" href="{{route("discover.chooseListGlobalDiscover")}}">{{__("global.global_discover")}}</a>
                <a class="btn btn-block bg-gradient-success" href="{{route("discover.chooseListCashDiscover")}}">{{__("global.cash_discover")}}</a>
                <a class="btn btn-block bg-gradient-success" href="{{route("discover.chooseListProductDiscover")}}">{{__("global.product_discover")}}</a>
                <a id="btn_show_all_accounts_balances" class="btn btn-block bg-gradient-success" href="{{route("discover.allAccountsDiscover")}}">{{__("global.all_accounts_discover")}}</a>
                <a class="btn btn-block bg-gradient-success" href="{{route("discover.allProductsDiscover")}}">{{__("global.all_products_discover")}}</a>
                <a class="btn btn-block bg-gradient-success" href="{{route("discover.dailyDiscover")}}">{{__("global.daily_discover")}}</a>
            </div>
        </div>

    @endsection
    @section("script")

    @endsection
</x-masterLayout.master>
