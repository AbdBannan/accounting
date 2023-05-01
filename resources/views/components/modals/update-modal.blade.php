<!-- update Modal-->
<div class="modal fade" id="updateModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
     aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">{{__("global.update") .  " " . __("global.a_$modalName")}}</h5>
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


                </form>
            </div>
            <div class="modal-footer">
                <div class="form-group">
                    <input tabindex="100" id="btn_update" form="form_update" class="btn btn-primary" type="submit" value="{{__("global.update")}}">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">{{__("global.cancel")}}</button>
                </div>
            </div>
        </div>
    </div>
</div>




{{--                <form id="form_update" action="" method="POST" accept-charset="UTF-8" enctype="multipart/form-data">--}}
{{--                    {{csrf_field()}}--}}
{{--                    <input type="hidden" name="_method" value="PUT">--}}
{{--                    @foreach($fields as $field)--}}

{{--                        @if($field["type"] == 'text')--}}
{{--                            <div class="form-group">--}}
{{--                                <label for={{$field["label"]}}>{{__("global.".$field['label'])}}</label>--}}
{{--                                <input id={{$field["label"]}} name={{$field["label"]}} type="text" value="asldfjlsk" class="form-control" {{$field["extra"]}}>--}}
{{--                            </div>--}}
{{--                        @elseif($field["type"] == 'email')--}}
{{--                            <div class="form-group">--}}
{{--                                <label for={{$field["label"]}}>{{__("global.".$field['label'])}}</label>--}}
{{--                                <input id={{$field["label"]}} name={{$field["label"]}} type="email" class="form-control" {{$field["extra"]}}>--}}
{{--                            </div>--}}
{{--                        @elseif($field["type"] == 'file')--}}
{{--                            <div class="form-group">--}}
{{--                                <input id={{$field["label"]}} name={{$field["label"]}} type="file" class="form-control-file" {{$field["extra"]}}>--}}
{{--                            </div>--}}
{{--                        @elseif($field["type"] == 'textarea')--}}
{{--                            <div class="form-group">--}}
{{--                                <label for={{$field["label"]}}>{{__("global.".$field['label'])}}</label>--}}
{{--                                <textarea id={{$field["label"]}} name={{$field["label"]}} class="form-control" cols="50" rows="5" {{$field["extra"]}}></textarea>--}}
{{--                            </div>--}}
{{--                        @elseif($field["type"] == 'select')--}}
{{--                            <div class="form-group">--}}
{{--                                <select id={{$field["label"]}} name={{$field["label"]}} class="form-control" {{$field["extra"]}}>--}}
{{--                                    @foreach($field["options"] as $option)--}}
{{--                                        <option>{{__("global.".$option)}}</option>--}}
{{--                                    @endforeach--}}
{{--                                </select>--}}
{{--                            </div>--}}
{{--                        @elseif($field["type"] == 'number')--}}
{{--                            <div class="form-group">--}}
{{--                                <label for={{$field["label"]}}>{{__("global.".$field['label'])}}</label>--}}
{{--                                <input id={{$field["label"]}} name={{$field["label"]}} type="number" class="form-control" {{$field["extra"]}}>--}}
{{--                            </div>--}}
{{--                        @elseif($field["type"] == 'range')--}}
{{--                            <div class="form-group">--}}
{{--                                <label for={{$field["label"]}}>{{__("global.".$field['label'])}}</label>--}}
{{--                                <input id={{$field["label"]}} name={{$field["label"]}} type="range" class="form-control-range" {{$field["extra"]}}>--}}
{{--                            </div>--}}
{{--                        @elseif($field["type"] == 'checkbox')--}}
{{--                            <div class="form-group">--}}
{{--                                @foreach($field["values"] as $label)--}}
{{--                                    <div class="form-check">--}}
{{--                                        <label class="form-check-label">--}}
{{--                                            <input id={{$label}} name={{$label}} type="checkbox" value="{{$label}}" class="form-check-input">--}}
{{--                                            {{__("global.".$label)}}--}}
{{--                                        </label>--}}
{{--                                    </div>--}}
{{--                                @endforeach--}}
{{--                            </div>--}}
{{--                        @elseif($field["type"] == 'radio')--}}
{{--                            <div class="form-group">--}}
{{--                                <fieldset id="group1">--}}
{{--                                    @foreach($field["values"] as $label)--}}
{{--                                        <div class="form-check">--}}
{{--                                            <label class="form-check-label">--}}
{{--                                                <input id={{$field["label"]}} name={{$field["label"]}} type="radio" class="form-check-input" value="{{$label}}" ="aaa" checked="">--}}
{{--                                                {{__("global.".$label)}}--}}
{{--                                            </label>--}}
{{--                                        </div>--}}
{{--                                    @endforeach--}}
{{--                                </fieldset>--}}

{{--                            </div>--}}
{{--                        @elseif($field["type"] == 'color')--}}
{{--                            <div class="form-group">--}}
{{--                                <input id={{$field["label"]}} name={{$field["label"]}} type="color" class="form-control-color" {{$field["extra"]}}>--}}
{{--                            </div>--}}
{{--                        @elseif($field["type"] == 'submit')--}}
{{--                            <div class="form-group">--}}
{{--                                <input class="btn btn-primary" type={{$field["type"]}} value={{$field["label"]}} {{__("global.".$field["extra"])}}>--}}
{{--                                <button class="btn btn-secondary" type="button" data-dismiss="modal">{{__("global.cancel")}}</button>--}}
{{--                            </div>--}}
{{--                        @endif--}}
{{--                    @endforeach--}}

{{--                </form>--}}
