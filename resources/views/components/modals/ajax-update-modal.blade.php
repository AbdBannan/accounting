<!-- update Modal-->
<div class="modal fade" id="updateModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
     aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">{{__("global.update")}}</h5>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="form_update" action="" method="POST" accept-charset="UTF-8" enctype="multipart/form-data" autocomplete="off">
                    @csrf
                    <input type="hidden" name="_method" value="PUT">
                    @if($modalName == "account")
                        <x-forms.accounts-form></x-forms.accounts-form>
                    @elseif($modalName == "product")
                        <x-forms.products-form></x-forms.products-form>
                    @elseif($modalName == "category")
                        <x-forms.categories-form></x-forms.categories-form>
                    @elseif($modalName == "store")
                        <x-forms.stores-form></x-forms.stores-form>
                    @elseif($modalName == "role")
                        <x-forms.roles-form></x-forms.roles-form>
                    @elseif($modalName == "permission")
                        <x-forms.permissions-form></x-forms.permissions-form>
                    @elseif($modalName == "pound")
                        <x-forms.pounds-form></x-forms.pounds-form>
                    @endif
                    <span id="field_invalid_message" style="color: darkred;font-weight: bold" hidden>{{__("messages.field_invalid")}}</span>
                </form>
            </div>
            <div class="modal-footer">
                <div class="form-group">
                    <input tabindex="100" id="btn_update" form="form_update" class="btn btn-primary" type="button" value="{{__("global.update")}}">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">{{__("global.cancel")}}</button>
                </div>
            </div>
        </div>
    </div>
</div>
