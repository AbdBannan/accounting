<div class="form-group">
    <label style="font-size: x-large" for="id" class="font-weight-bolder">{{__("global.id")}}</label>
    <input tabindex="97" class="form-control" type="number" name="id" id="id" value="@yield("id",\App\Models\Store::withTrashed()->selectRaw("max(id) as id")->first()->id + 1)">
</div>
<div class="form-group">
    <label style="font-size: x-large" for="name" class="font-weight-bolder">{{__("global.name")}}</label>
    <input tabindex="98" class="form-control" type="text" name="name" id="name" value="">
</div>
<div class="form-group">
    <label style="font-size: x-large" for="location" class="font-weight-bolder">{{__("global.location")}}</label>
    <input tabindex="99" class="form-control" type="text" name="location" id="location" value="">
</div>

