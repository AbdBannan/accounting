<!-- delete confirm Modal-->
<div class="modal fade" id="deleteConfirmModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
     aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">{{__("global.delete_confirm")}}</h5>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">{{__("messages.delete_confirm")}}</div>
            <div class="modal-footer">
                <form id="form_delete" action="" method="post">
                    @csrf
                    @method("DELETE")
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">{{__("global.cancel")}}</button>
                    <input type="submit" class="btn btn-primary" value="{{__("global.delete")}}">
                </form>
            </div>
        </div>
    </div>
</div>
