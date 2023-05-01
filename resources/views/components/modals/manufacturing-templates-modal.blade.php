<!-- add Modal-->
<div class="modal fade" id="manufacturingTemplatesModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
     aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">{{__("global.manufacturing_templates")}}</h5>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <a id="btn_multi_delete" title="{{__("global.delete_selected")}}" class="btn btn-sm btn-danger disable-pointer" href="#" data-toggle="modal" data-target="#deleteConfirmModal" @if(auth()->user()->getConfig("use_recyclebin") == "true") data-route="{{route("manufacturingTemplate.softDeleteManufacturingTemplate",-1)}}" @else data-route="{{route("manufacturingTemplate.deleteManufacturingTemplate",-1)}}" @endif >
                        <i class="fas fa-trash"></i>
                        {{__("global.delete_selected")}}
                    </a>
                </div>
                <div class="table-responsive">
                    <table class="table table-bordered table-striped" id="dataTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th><input id="check_all" type="checkbox" class="form-check"></th>
                                <th>{{__("global.id")}}</th>
                                <th>{{__("global.product_name")}}</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody id="body">
                            @foreach(App\Models\ManufacturingTemplate::all() as $template)
                                <tr>
                                    <td><input form="form_delete" name="multi_ids[]" value="{{$template->id}}" type="checkbox" class="form-check"></td>
                                    <td>{{$template->id}}</td>
                                    <td id="data_ccomponents" data-components="{{$template->components}}" data-quantity="{{$template->quantity}}">{{$template->name}}</td>
                                    <td class="row m-0">
                                        <a id="btn_update_template" title="{{__("global.update")}}" class="dropdown-item col-7 m-0 p-0" href="#">
                                            <i class="fas fa-edit text-green"></i>
                                        </a>
                                        <a id="btn_delete_template" title="{{__("global.delete")}}" class="dropdown-item col-5 m-0 p-0" href="#" data-toggle="modal" data-target="#deleteConfirmModal" @if(auth()->user()->getConfig("use_recyclebin") == "true") data-route="{{route("manufacturingTemplate.softDeleteManufacturingTemplate",$template->id)}}" @else data-route="{{route("manufacturingTemplate.deleteManufacturingTemplate",$template->id)}}"  @endif>
                                            <i class="fas fa-trash text-red"></i>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <div class="form-group">
                    <button id="btn_fill_manufacturing_template" class="btn btn-primary" >{{__("global.add")}}</button>
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">{{__("global.cancel")}}</button>
                </div>
            </div>
        </div>
    </div>
</div>
