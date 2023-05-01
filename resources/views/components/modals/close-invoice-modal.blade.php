<!-- close invoice Modal-->
<div class="modal fade" id="closingDateModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
     aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">{{__("global.closing_date")}}</h5>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <div id="date_container" class="form-group">
{{--                    <div class="input-append date" id="dp3" data-date="12-02-2012" data-date-format="dd-mm-yyyy">--}}
{{--                        <input tabindex="12" class="datepicker form-group" size="16" type="text" value="20-12-2012">--}}
{{--                        <span class="add-on"><i class="icon-th"></i></span>--}}
{{--                    </div>--}}
{{--                    <input tabindex="11" form="form" type="date" id="closing_date" name="closing_date"  class="form-control" value="{{Carbon\Carbon::now()->toDateString("mm/dd/yyyy")}}">--}}
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" type="button" data-dismiss="modal">{{__("global.cancel")}}</button>
                <input id="btn_save_invoice" tabindex="12" id="btn-submit-invoice" form="form" class="btn btn-success" type="submit" value="{{__("global.save")}}">
            </div>
        </div>
    </div>
</div>
