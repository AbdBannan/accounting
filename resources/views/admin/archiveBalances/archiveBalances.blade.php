<x-masterLayout.master>
    @section("title")
        {{ __("global.archive_balances",[],session("lang")) }}
    @endsection
    @section('content')

            <div class="container">
                <div class="row bg-gradient-light shadow" style="width: 40%;margin: auto;">
                    <form id="archive_form" method="post" style="margin: auto" action="{{route("archive.archiveBalances")}}">
                        @csrf
                        <div class="form-group text-center">
                            <label style="font-size: x-large" for="invoice_id" >{{__("global.archive_date",[],session("lang"))}}</label>
                            <input type="date" class="form-control" id="date" name="date" value="{{\Carbon\Carbon::now()->toDateString("mm/dd/yyyy")}}" autofocus>
                        </div>
                        <div class="form-group">
                            <input id="btn_archive" type="submit" class="btn btn-block btn-outline-primary" value="{{__("global.archive",[],session("lang"))}}">
                        </div>
                    </form>
                </div>
            </div>

    @endsection
    @section("script")
        <!-- Page level plugins -->
        <script src={{asset("vendor/datatables/jquery.dataTables.js")}}></script>
        <script src={{asset("vendor/datatables/dataTables.bootstrap4.js")}}></script>

        <!-- Page level custom scripts -->
        <script src={{asset("js/demo/datatables-demo.js?var=415".rand(1,100))}}></script>
    @endsection
</x-masterLayout.master>
