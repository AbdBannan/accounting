<x-masterLayout.master>
    @section("title")
        {{__("global.home")}}
    @endsection
    @section("style")
        <style>
            body {
                background-image: url('{{asset("images/systemImages/istockphoto-1335050708-170667a.jpg")}}')!important;
                background-size:cover!important;
                background-repeat: no-repeat!important;
            }
        </style>

    @endsection
    @section("content")
        <div class="container">
            <h1 style="text-align: center">{{__("global.Welcome",[],session("lang"))}}</h1>
        </div>
    @show
    @section("script")

    @endsection
</x-masterLayout.master>
