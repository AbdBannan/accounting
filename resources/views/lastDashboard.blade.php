<x-masterLayout.master>
    @if(Auth::user()->hasRole("admin"))
        @section("title")
            {{ "dashboard : " . auth()->user()->name}}
        @endsection
        @section("content")
        @php
        $ernning = [
        "EARNINGS (MONTHLY)"=> 40.000,
        "EARNINGS (ANNUAL)"=>215.000,
        "TASKS"=>50,
        "PENDING REQUESTS"=>18
        ];
        @endphp
        <x-masterLayout.content :ernning="$ernning"></x-masterLayout.content>
        @show
        @section("script")
            <!-- Page level plugins -->
            <script src={{asset("vendor/chart.js/Chart.js?var=12".rand())}}></script>

            <!-- Page level custom scripts -->
            <script id="js" src={{asset("js/demo/chart-area-demo.js?data=[0,10000,5000,15000,10000,20000,15000,25000,20000,30000,25000,40000]&var=".rand())}}></script>
            <script src={{asset("js/demo/chart-pie-demo.js?var=12".rand())}}></script>
        @endsection
    @endif
</x-masterLayout.master>
