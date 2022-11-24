<x-masterLayout.master>
    @section("title")
        {{ __("products") }}
    @endsection

    @section("recycle_bin")
        <a class="dropdown-item" href="{{route("product.viewRecyclebin")}}">
            <i class="fas fa-trash fa-sm fa-fw mr-2 text-gray-400"></i>
            {{__("global.recycle_bin",["attribute"=>"products"])}}
        </a>
    @endsection

    @section('content')

        <div class="container">
            <div class="bg-gray-100 card o-hidden border-0 shadow-lg my-5 p-4 col-lg-3 col-sm-12">
                <h1>{{$product->id}}</h1>
                <h3>{{$product->name}}</h3>
                <p>{{$product->category->name}}</p>
                <p>{{$product->store->name}}</p>
                <img src="{{asset($product->image)}}"></img>
                <p>{{$product->created_at}}</p>
                <p>{{$product->updated_at}}</p>
            </div>
        </div>

    @endsection
</x-masterLayout.master>








