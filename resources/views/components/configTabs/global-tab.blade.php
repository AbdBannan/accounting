<form action="{{route("config.saveConfig")}}" method="post">
    @csrf
    <div class="form-group row">
        <label style="font-size: x-large" for="language" class="font-weight-bolder col-3">{{__("global.language",[],session("lang"))}}</label>
        <select  id="language" name="language" class="form-control col-3">
            <option value="arabic" @if(isset($config["language"]) and $config["language"] == "arabic") selected @endif>{{__("global.arabic",[],session("lang"))}}</option>
            <option value="english" @if(isset($config["language"]) and $config["language"] == "english") selected @endif>{{__("global.english",[],session("lang"))}}</option>
        </select>
    </div>

    {{--    <div class="form-group row">--}}
    {{--        <label style="font-size: x-large" for="user_activity_log" class="font-weight-bolder col-3">{{__("global.track_activity",[],session("lang"))}}</label>--}}
    {{--        <div class="col-3 custom-control custom-switch custom-switch-off-danger custom-switch-on-success">--}}
    {{--            <input type="checkbox" class="custom-control-input" id="user_activity_log" name="user_activity_log">--}}
    {{--            <label class="custom-control-label" for="user_activity_log"></label>--}}
    {{--        </div>--}}
    {{--    </div>--}}

    <div class="form-group row">
        <label style="font-size: x-large" for="default_pound" class="font-weight-bolder col-3">{{__("global.default_pound",[],session("lang"))}}</label>
        <select id="default_pound" name="default_pound" class="form-control col-3">
            @foreach(App\Models\Pound::all() as $pound)
                <option value="{{$pound->name}}" class="dropdown-item" @if(isset($config["default_pound"]) and $config["default_pound"] == $pound->name) selected @endif>{{__("global.$pound->name",[],session("lang"))}}</option>
            @endforeach
{{--            <option value="syrian" >{{__("global.syrian",[],session("lang"))}}</option>--}}
{{--            <option value="dollar" @if($config["default_pound"] == "dollar") selected @endif>{{__("global.dollar",[],session("lang"))}}</option>--}}
        </select>
    </div>
    <div class="form-group row">
        <label style="font-size: x-large" for="add_method" class="font-weight-bolder col-3">{{__("global.add_method",[],session("lang"))}}</label>
        <select id="add_method" name="add_method" class="form-control col-3">
            <option value="modal" class="dropdown-item" @if(isset($config["add_method"]) and $config["add_method"] == "modal") selected @endif>{{__("global.modal",[],session("lang"))}}</option>
            <option value="fixed" class="dropdown-item" @if(isset($config["add_method"]) and $config["add_method"] == "fixed") selected @endif>{{__("global.fixed",[],session("lang"))}}</option>
        </select>
    </div>
    <input type="hidden" name="config_type" value="global">
    <input id="btn_save_config" type="submit" class="btn btn-primary" value="{{__("global.save",[],session("lang"))}}">
</form>
