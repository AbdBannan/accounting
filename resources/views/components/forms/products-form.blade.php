<div class="row">
    <div class="form-group col-6">
        <label style="font-size: x-large" for="id" class="font-weight-bolder">{{__("global.id",[],session("lang"))}}</label>
        <input class="form-control" type="number" name="id" id="id" value="@yield("id")">
    </div>
    <div class="form-group col-6">
        <label style="font-size: x-large" for="store_id" class="font-weight-bolder">{{__("global.store",[],session("lang"))}}</label>
        <div class="form-group">
            <select id="store_id" name="store_id" class="form-control" >
                @foreach(App\Models\Store::get() as $Store)
                    <option id="{{$Store->id}}" value="{{$Store->id}}">{{$Store->name}}</option>
                @endforeach
            </select>
        </div>
    </div>
</div>
<div class="form-group">
    <label style="font-size: x-large" for="name" class="font-weight-bolder">{{__("global.name",[],session("lang"))}}</label>
    <input class="form-control" type="text" name="name" id="name" value="">
</div>

<div class="row">
    <div class="col-6 form-group">
        <label style="font-size: x-large" for="account_type" class="font-weight-bolder">{{__("global.type",[],session("lang"))}}</label>
        <select class="form-control" name="account_type" id="account_type">
            <option value="0">{{__("global.secondary",[],session("lang"))}}</option>
            <option value="1">{{__("global.primary",[],session("lang"))}}</option>
        </select>
    </div>
    <div class="col-6 form-group">
        <label style="font-size: x-large" for="reference" class="font-weight-bolder">{{__("global.reference",[],session("lang"))}}</label>
        <input class="form-control" type="number" name="reference" id="reference" value="0">
    </div>
</div>
<div class="form-group">
    <label style="font-size: x-large" for="last_name" class="font-weight-bolder">{{__("global.notes",[],session("lang"))}}</label>
    <textarea class="form-control" name="notes" id="notes" value="" rows="3"></textarea>
</div>
<div class="form-group">
    <img id="image"  src="{{asset("images/systemImages/default_product_img.png")}}" style="width:100%;max-width:200px;margin:10px auto; ;border-radius:50%">
    <input id="product_image" type="file" class=" form-control-file" name="product_image" placeholder="profile image">
</div>

