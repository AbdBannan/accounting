<x-masterLayout.master>
    @section("title")
        {{ __("global.archive_balances") }}
    @endsection
    @section('content')

        <div class="container">
            <div class="bg-gradient-light shadow" style="width: 40%;margin: auto;padding: 40px;">
                <div class="form-group text-center">
                    <label style="font-size: x-large" for="invoice_id" >{{__("global.archive_date")}}</label>
                </div>
                <div class="form-group">
                    <input tabindex="2" id="btn_archive" data-toggle="modal" data-target="#archiveConfirmModal" type="button" class="btn btn-block btn-outline-primary" value="{{__("global.archive")}}">
                </div>
            </div>
        </div>

    @endsection
    @section("modals")
        <x-modals.archive-confirm-modal></x-modals.archive-confirm-modal>
    @endsection
    @section("script")
        <script>
            $(".form-group.text-center").buildDateInput({classes: "form-control",id:"date",tabindex:2,focusElemAfterFinish:"btn_archive",form:"form_archive",autofocus:true,initialDate:"{{\Carbon\Carbon::now()->format("Y-m-d")}}"});
        </script>
    @endsection
</x-masterLayout.master>
