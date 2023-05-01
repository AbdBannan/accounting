<div class="row">
    <div class="form-group col-6">
        <label style="font-size: x-large" for="id" class="font-weight-bolder">{{__("global.id")}}</label>
        <input tabindex="92" class="form-control" type="number" name="id" id="id" value="@yield("id",\App\Models\Product::withTrashed()->selectRaw("max(id) as id")->first()->id + 1)">
    </div>
    <div class="form-group col-6">
        <label style="font-size: x-large" for="store_id" class="font-weight-bolder">{{__("global.store")}}</label>
        <div class="form-group">
            <select tabindex="93" id="store_id" name="store_id" class="form-control" >
                @foreach(App\Models\Store::get() as $Store)
                    <option id="{{$Store->id}}" value="{{$Store->id}}">{{$Store->name}}</option>
                @endforeach
            </select>
        </div>
    </div>
</div>
<div class="form-group">
    <label style="font-size: x-large" for="name" class="font-weight-bolder">{{__("global.name")}}</label>
    <input tabindex="94" class="form-control" type="text" name="name" id="name" value="">
</div>

<div class="row">
    <div class="col-4 form-group">
        <label style="font-size: x-large" for="account_type" class="font-weight-bolder">{{__("global.type")}}</label>
        <select tabindex="95" class="form-control" name="account_type" id="account_type">
            <option value="0">{{__("global.secondary")}}</option>
            <option value="1">{{__("global.primary")}}</option>
        </select>
    </div>
    <div class="col-4 form-group">
        <label style="font-size: x-large" for="reference" class="font-weight-bolder">{{__("global.reference")}}</label>
        <input tabindex="96" class="form-control" type="number" name="reference" id="reference" value="0">
    </div>
    <div class="col-4 form-group">
        <label style="font-size: x-large" for="is_raw" class="font-weight-bolder">{{__("global.is_raw_product")}}</label>
        <select tabindex="97" class="form-control" name="is_raw" id="is_raw">
            <option value="1">{{__("global.raw")}}</option>
            <option value="0">{{__("global.manufactured")}}</option>
        </select>
    </div>
</div>
<div class="form-group">
    <label style="font-size: x-large" for="last_name" class="font-weight-bolder">{{__("global.notes")}}</label>
    <textarea tabindex="98" class="form-control" name="notes" id="notes" value="" rows="3"></textarea>
</div>
<div class="form-group">
    <img id="image"  src="{{asset("images/systemImages/default_product_img.png")}}" style="width:100%;max-width:200px;margin:10px auto; ;border-radius:50%">
    <input tabindex="99" id="product_image" type="file" class=" form-control-file" name="product_image" placeholder="profile image">
    <span class="invalid-feedback" role="alert">
        <strong>{{__("messages.max_size_is_3_MB")}}</strong>
    </span>
</div>

