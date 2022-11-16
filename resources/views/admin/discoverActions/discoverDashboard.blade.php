<x-masterLayout.master>
    @section("title")
        {{ __("global.discovers",[],session("lang")) }}
    @endsection
    @section('content')
        <div class="container">
            <div style="width: 300px;margin: auto">
                <a class="btn btn-block bg-gradient-success" href="{{route("discover.chooseListGlobalDiscover")}}">{{__("global.global_discover",[],session("lang"))}}</a>
                <a class="btn btn-block bg-gradient-success" href="{{route("discover.chooseListCashDiscover")}}">{{__("global.cash_discover",[],session("lang"))}}</a>
                <a class="btn btn-block bg-gradient-success" href="{{route("discover.chooseListProductDiscover")}}">{{__("global.product_discover",[],session("lang"))}}</a>
                <a class="btn btn-block bg-gradient-success" href="{{route("discover.allAccountsDiscover")}}">{{__("global.all_accounts_discover",[],session("lang"))}}</a>
                <a class="btn btn-block bg-gradient-success" href="{{route("discover.allProductsDiscover")}}">{{__("global.all_products_discover",[],session("lang"))}}</a>
                <a class="btn btn-block bg-gradient-success" href="{{route("discover.dailyDiscover")}}">{{__("global.daily_discover",[],session("lang"))}}</a>
            </div>
        </div>

    @endsection
    @section("script")

    @endsection
</x-masterLayout.master>
