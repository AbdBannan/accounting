<!-- Logout Modal-->
<div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
     aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">{{__("global.ready_to_leave")}}</h5>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">{{__("global.select_logout_if_ready_to_end_session")}}</div>
            <div class="modal-footer">
                <button class="btn btn-secondary" type="button" data-dismiss="modal">{{__("global.cancel")}}</button>
                {!! Form::open(["method"=>"POST","action"=>"Auth\LoginController@logout"]) !!}
                {!! Form::submit(__("global.logout"),["class"=>"btn btn-primary"]) !!}
                {!! Form::close() !!}
            </div>
        </div>
    </div>
</div>
