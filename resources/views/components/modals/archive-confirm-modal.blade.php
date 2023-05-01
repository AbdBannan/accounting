<!-- archive confirm Modal-->
<div class="modal fade" id="archiveConfirmModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
     aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">{{__("global.archive_confirm")}}</h5>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">{{__("messages.archive_confirm")}}</div>
            <div class="modal-footer">
                <form id="form_archive" action="{{route("archive.archiveBalances")}}" method="post">
                    @csrf
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">{{__("global.cancel")}}</button>
                    <input type="submit" class="btn btn-primary" value="{{__("global.archive")}}">
                </form>
            </div>
        </div>
    </div>
</div>


