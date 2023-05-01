<x-masterLayout.master>
    @section("title")
        {{__("global.home")}}
    @endsection
    @section("style")
        <style>
            .content-wrapper {
                background-image: url('{{asset("images/systemImages/istockphoto-1339953021-612x612.jpg")}}')!important;
                background-size:100% 100%!important;
                background-repeat: no-repeat!important;
                background-attachment: fixed;
            }
        </style>

    @endsection
    @section("content")

    @show
    @section("script")

    @endsection
</x-masterLayout.master>
