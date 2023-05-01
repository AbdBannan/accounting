<x-masterLayout.master>
    @if(Auth::user()->isAdmin())
        @section("title")
            {{__("global.dashboard")}}
        @endsection
        @section("style")
            <!-- Tempusdominus Bootstrap 4 -->
                <link rel="stylesheet" href="{{asset("css/plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css")}}">
                <!-- iCheck -->
                <link rel="stylesheet" href="{{asset("css/plugins/icheck-bootstrap/icheck-bootstrap.min.css")}}">
                <!-- JQVMap -->
                <link rel="stylesheet" href="{{asset("css/plugins/jqvmap/jqvmap.min.css")}}">
                <!-- Daterange picker -->
                <link rel="stylesheet" href="{{asset("css/plugins/daterangepicker/daterangepicker.css")}}">
                <!-- summernote -->
                <link rel="stylesheet" href="{{asset("css/plugins/summernote/summernote-bs4.min.css")}}">
        @endsection
        @section("content")
        <x-masterLayout.content></x-masterLayout.content>
        @show
        @section("script")
        <!-- ChartJS -->
            <script src="{{asset("js/plugins/chart.js/Chart.min.js")}}"></script>
            <!-- Sparkline -->
            <script src="{{asset("js/plugins/sparklines/sparkline.js")}}"></script>
            <!-- JQVMap -->
            <script src="{{asset("js/plugins/jqvmap/jquery.vmap.min.js")}}"></script>
            <script src="{{asset("js/plugins/jqvmap/maps/jquery.vmap.usa.js")}}"></script>
            <!-- jQuery Knob Chart -->
            <script src="{{asset("js/plugins/jquery-knob/jquery.knob.min.js")}}"></script>
            <!-- daterangepicker -->
            <script src="{{asset("js/plugins/moment/moment.min.js")}}"></script>
            <script src="{{asset("js/plugins/daterangepicker/daterangepicker.js")}}"></script>
            <!-- Tempusdominus Bootstrap 4 -->
            <script src="{{asset("js/plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js")}}"></script>
            <!-- Summernote -->
            <script src="{{asset("js/plugins/summernote/summernote-bs4.min.js")}}"></script>

            <!-- AdminLTE dashboard demo (This is only for demo purposes) -->
            <script src="{{asset("js/dist/js/pages/dashboard.js")}}"></script>
        @endsection
    @endif
</x-masterLayout.master>
