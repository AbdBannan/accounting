<form action="{{route("config.saveConfig")}}" method="post" autocomplete="off">
    @csrf
    <div class="form-group row">
        <label style="font-size: x-large" for="language" class="font-weight-bolder col-3">{{__("global.language")}}</label>
        <select  id="language" name="language" class="form-control col-3">
            <option value="arabic" @if(isset($config["language"]) and $config["language"] == "arabic") selected @endif>{{__("global.arabic")}}</option>
            <option value="english" @if(isset($config["language"]) and $config["language"] == "english") selected @endif>{{__("global.english")}}</option>
        </select>
    </div>

    <div class="form-group row">
        <label style="font-size: x-large" for="default_pound" class="font-weight-bolder col-3">{{__("global.default_pound")}}</label>
        <select id="default_pound" name="default_pound" class="form-control col-3">
            @foreach(App\Models\Pound::all() as $pound)
                <option value="{{$pound->name}}" class="dropdown-item" @if(isset($config["default_pound"]) and $config["default_pound"] == $pound->name) selected @endif>{{$pound->name}}</option>
            @endforeach
        </select>
    </div>
    <div class="form-group row">
        <label style="font-size: x-large" for="add_method" class="font-weight-bolder col-3">{{__("global.add_method")}}</label>
        <select id="add_method" name="add_method" class="form-control col-3">
            <option value="modal" class="dropdown-item" @if(isset($config["add_method"]) and $config["add_method"] == "modal") selected @endif>{{__("global.modal")}}</option>
            <option value="fixed" class="dropdown-item" @if(isset($config["add_method"]) and $config["add_method"] == "fixed") selected @endif>{{__("global.fixed")}}</option>
        </select>
    </div>
    <div class="form-group row">
        <label style="font-size: x-large" for="use_recyclebin" class="font-weight-bolder col-3">{{__("global.use_recyclebin")}}</label>
        <select id="use_recyclebin" name="use_recyclebin" class="form-control col-3">
            <option value="true" class="dropdown-item" @if(isset($config["use_recyclebin"]) and $config["use_recyclebin"] == "true") selected @endif>{{__("global.yes")}}</option>
            <option value="false" class="dropdown-item" @if(isset($config["use_recyclebin"]) and $config["use_recyclebin"] == "false") selected @endif>{{__("global.no")}}</option>
        </select>
    </div>
    <div class="form-group row">
        <label style="font-size: x-large" for="clean_recyclebin_after" class="font-weight-bolder col-3">{{__("global.clean_recyclebin_after")}}</label>
        <input type="number" id="clean_recyclebin_after" name="clean_recyclebin_after" class="form-control col-3" value="{{$config["clean_recyclebin_after"]}}">
        <label style="font-size: x-large" class="font-weight-bolder col-3">{{__("global.day")}}</label>
    </div>
    <input type="hidden" name="config_type" value="global">
    <input id="btn_save_config" type="submit" class="btn btn-primary" value="{{__("global.save")}}">
</form>
