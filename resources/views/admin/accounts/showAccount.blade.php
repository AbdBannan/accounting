<x-masterLayout.master>
    @section("title")
        {{ __("account") }}
    @endsection

    @section("recycle_bin")
        <a class="dropdown-item" href="{{route("account.viewRecyclebin")}}">
            <i class="fas fa-trash fa-sm fa-fw mr-2 text-gray-400"></i>
            {{__("global.recycle_bin",["attribute"=>"accounts"])}}
        </a>
    @endsection

    @section('content')

        <div class="container">
                <div class="bg-gray-100 card o-hidden border-0 shadow-lg my-5 p-4 col-lg-3 col-sm-12">
                    <h1>{{$account->id}}</h1>
                    <h3>{{$account->first_name}}</h3>
                    <h3>{{$account->last_name}}</h3>
                    <p>{{$account->credit}}</p>
                    <p>{{$account->debit}}</p>
                    <p>{{$account->created_at}}</p>
                    <p>{{$account->updated_at}}</p>
                </div>
        </div>

    @endsection
</x-masterLayout.master>








