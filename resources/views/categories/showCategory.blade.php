<x-masterLayout.master>
    @section("title")
        {{ __("category") }}
    @endsection

    @section("recycle_bin")
        <a class="dropdown-item" href="{{route("category.viewRecyclebin")}}">
            <i class="fas fa-trash fa-sm fa-fw mr-2 text-gray-400"></i>
            {{__("global.recycle_bin",["attribute"=>"categories"])}}
        </a>
    @endsection

    @section('content')

        <div class="container">
            <div class="bg-gray-100 card o-hidden border-0 shadow-lg my-5 p-4 col-lg-3 col-sm-12">
                <h1>{{$category->id}}</h1>
                <h3>{{$category->name}}</h3>
                <p>{{$category->created_at}}</p>
                <p>{{$category->updated_at}}</p>
            </div>
        </div>

    @endsection
</x-masterLayout.master>








