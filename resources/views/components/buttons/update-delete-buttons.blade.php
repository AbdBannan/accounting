<td class="row m-0">
    <a id="btn_update" title="{{__("global.update")}}" class="dropdown-item col-7 m-0 p-0" href="#" data-toggle="modal" data-target="#updateModal" data-fields="@yield("data_field")" data-route="@yield("data_route_update")">
        <input class="grid-button grid-edit-button" type="button" title="Update">
    </a>
    <a id="btn_delete" title="{{__("global.delete")}}" class="dropdown-item col-5 m-0 p-0" href="#" data-toggle="modal" data-target="#deleteConfirmModal" data-route="@yield("data_route_delete")">
        <input class="grid-button grid-delete-button" type="button" title="Delete">
    </a>
</td>
