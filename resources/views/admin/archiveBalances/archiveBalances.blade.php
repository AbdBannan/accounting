<x-masterLayout.master>
    @section("title")
        {{ __("global.archive_balances") }}
    @endsection
    @section('content')

        <div class="container">
            <div class="bg-gradient-light shadow" style="width: 40%;margin: auto;padding: 40px;">
                    <div class="form-group text-center">
                        <label style="font-size: x-large" for="invoice_id" >{{__("global.archive_date")}}</label>
                        <input form="form_archive" type="date" class="form-control" id="date" name="date" value="{{\Carbon\Carbon::now()->toDateString("mm/dd/yyyy")}}" autofocus>
                    </div>
                    <div class="form-group">
                        <a id="btn_archive" data-toggle="modal" data-target="#archiveConfirmModal" type="submit" class="btn btn-block btn-outline-primary" >{{__("global.archive")}}</a>
                    </div>
            </div>
        </div>

    @endsection
    @section("modals")
        <x-modals.archive-confirm-modal></x-modals.archive-confirm-modal>
    @endsection
    @section("script")

    @endsection
</x-masterLayout.master>
