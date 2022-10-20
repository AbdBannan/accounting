<div class="form-group">
    <label style="font-size: x-large" for="id" class="font-weight-bolder">{{__("global.id",[],session("lang"))}}</label>
    <input class="form-control" type="number" name="id" id="id" value="@yield("id")">
</div>
<div class="form-group">
    <label style="font-size: x-large" for="name" class="font-weight-bolder">{{__("global.name",[],session("lang"))}}</label>
    <input class="form-control" type="text" name="name" id="name" value="">
</div>
<div class="form-group">
    <label style="font-size: x-large" for="reference" class="font-weight-bolder">{{__("global.reference",[],session("lang"))}}</label>
    <input class="form-control" type="number" name="reference" id="reference" value="0">
</div>
<label style="font-size: x-large" for="account_type" class="font-weight-bolder">{{__("global.type",[],session("lang"))}}</label>
<div class="row">
    <div class="col-6 form-group">
        <select class="form-control" name="account_type" id="account_type">
            <option value="0">{{__("global.secondary",[],session("lang"))}}</option>
            <option value="1">{{__("global.primary",[],session("lang"))}}</option>
        </select>
    </div>
    <div class="col-6 form-group">
        <select class="form-control" name="group" id="group">
            <option value="0">{{__("global.budget",[],session("lang"))}}</option>
            <option value="1">{{__("global.gain_loss",[],session("lang"))}}</option>
            <option value="2">{{__("global.trades",[],session("lang"))}}</option>
        </select>
    </div>
</div>
<div class="form-group">
    <label style="font-size: x-large" for="last_name" class="font-weight-bolder">{{__("global.notes",[],session("lang"))}}</label>
    <textarea class="form-control" name="notes" id="notes" value="" rows="3"></textarea>
</div>
