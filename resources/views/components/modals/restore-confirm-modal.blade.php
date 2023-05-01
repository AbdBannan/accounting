<!-- restore confirm Modal-->
<div class="modal fade" id="restoreConfirmModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
     aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">{{__("global.restore_confirm")}}</h5>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">{{__("messages.restore_confirm")}}</div>
            <div class="modal-footer">
                <form id="form_restore" action="" method="get">
                    @csrf
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">{{__("global.cancel")}}</button>
                    <input type="submit" class="btn btn-primary" value="{{__("global.restore")}}">
                </form>
            </div>
        </div>
    </div>
</div>
