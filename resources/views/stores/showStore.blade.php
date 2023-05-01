<x-masterLayout.master>
    @section("title")
        {{ __("store") }}
    @endsection

    @section("recycle_bin")
        <a class="dropdown-item" href="{{route("store.viewRecyclebin")}}">
            <i class="fas fa-trash fa-sm fa-fw mr-2 text-gray-400"></i>
            {{__("global.recycle_bin",["attribute"=>"stores"])}}
        </a>
    @endsection

    @section('content')

        <div class="container">
            <div class="bg-gray-100 card o-hidden border-0 shadow-lg my-5 p-4 col-lg-3 col-sm-12">
                <h1>{{$store->id}}</h1>
                <h3>{{$store->name}}</h3>
                <h3>{{$store->location}}</h3>
                <p>{{$store->created_at}}</p>
                <p>{{$store->updated_at}}</p>
            </div>
        </div>

    @endsection
</x-masterLayout.master>








