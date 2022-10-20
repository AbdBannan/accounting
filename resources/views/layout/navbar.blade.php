<nav class="navbar">
    <h2 class="logo">Bannan</h2>
    <ul class="list">
        @section('navbar')
            <li class="item"><a href="{{route("welcome")}}">Home</a></li>
            <li class="item"><a href="{{route("about")}}">accounts</a></li>
            {{-- <li class="item"><a href="{{route("with",[ "page_id"=>1 ,"section_id"=>2])}}">invoices</a></li> --}}
        @show
    </ul>
</nav>