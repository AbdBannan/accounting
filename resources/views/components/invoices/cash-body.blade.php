<dev class="col-lg-8 col-sm-12">
    <div class="card shadow">
        <div class="card-header py-3">

            <form id="f" class="row" autocomplete="false">
                <div class="col-md-10 col-sm-12">
                    <div class="row">
                        <div class="form-group col-md-3 col-sm-12">
                            <label class="" style="font-size: large" for="invoice_id" >{{__("global.invoice_id",[],session("lang"))}}</label>
                            <input form="form" id="invoice_id" name="invoice_id" min="0" type="number" class="form-control" readonly value="@yield("invoice_id",App\Models\Journal::where("detail",1)->whereIn("invoice_type",[5])->selectRaw("max(invoice_id) as mid")->get()[0]["mid"]+1)">
                        </div>
                        <div class="form-group col-md-3 col-sm-12">
                            <label style="font-size: large" for="first_part_name" >{{__("global.first_part",[],session("lang"))}}</label>
                            <input form="form" id="first_part_name" name="first_part_name" type="text" placeholder="" class="form-control dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" value="@yield("second_part_name",181)" @yield("auto_focus","autofocus")/>
                            <div style="max-height:200px;overflow-y: scroll" id="dropdown-menu" class="dropdown-menu" aria-labelledby="first_part_name">
                                @foreach(App\Models\Account::get() as $account)
                                    <option class="dropdown-item"  value="{{$account->id}}">{{$account->name}}</option>
                                @endforeach
                            </div>
                        </div>
                        <div class="form-group col-md-3 col-sm-12">
                            <label style="font-size: large" for="pound_type" >{{__("global.pound",[],session("lang"))}}</label>
                            <input value="{{__("global.".auth()->user()->getConfig("default_pound"),[],session("lang"))}}" form="form" id="pound_type" name="pound_type" type="text" placeholder="" class="form-control dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" value="@yield('pound_type')"/>
                            <div style="max-height:200px;overflow-y: scroll" id="dropdown-menu" class="dropdown-menu" aria-labelledby="pound_type">
                                @foreach(App\Models\Pound::all() as $pound)
                                    <option value="{{$pound->name}}" class="dropdown-item" >{{__("global.$pound->name",[],session("lang"))}}</option>
                                @endforeach
                            </div>
                        </div>
                        <div class="form-group col-md-3 col-sm-12">
                            @yield("edit_delete")
                        </div>
                    </div>
                    <hr>
                    <div class="row" >

                        <div class="col-md-2 col-sm-12">
                            <div class="form-group">
                                <label style="font-size: large" for="payed" >{{__("global.payed",[],session("lang"))}}</label>
                                <input form="f" type="number" min="0" class="form-control" id="payed" name="payed" >
                            </div>
                        </div>
                        <div class="col-md-2 col-sm-12">
                            <div class="form-group">
                                <label style="font-size: large" for="received" >{{__("global.received",[],session("lang"))}}</label>
                                <input form="f" type="number" min="0" class="form-control" id="received" name="received" >
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-12">
                            <div class="form-group">
                                <label style="font-size: large" for="second_part_name" >{{__("global.first_part",[],session("lang"))}}</label>
                                <input form="f" id="second_part_name" name="second_part_name"  type="text" placeholder="" class="form-control dropdown-toggle" form="form" id="second_part_name" name="second_part_name" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" />
                                <div style="max-height:200px;overflow-y: scroll" id="dropdown-menu" class="dropdown-menu" aria-labelledby="second_part_name">
                                    @foreach(App\Models\Account::get() as $account)
                                        <option class="dropdown-item"  value="{{$account->id}}">{{$account->name}}</option>
                                    @endforeach
                                </div>
                            </div>
                        </div>

                        <div class="col-md-5 col-sm-12">
                            <div class="form-group">
                                <label style="font-size: large" for="notes" >{{__("global.notes",[],session("lang"))}}</label>
                                <input form="f" id="notes" name="notes" type="text" class="form-control">
                            </div>
                        </div>

                    </div>
                    <div class="col-md-5 col-sm-12">

                        <div>
                            <button form="aa" id="btn-add-item-to-invoice" class="btn btn-outline-success">{{__("global.add",[],session("lang"))}}</button>
                            {{--                                <button id="btn-add-new-item-to-edited-invoice" class="btn btn-outline-primary">{{__("global.new",[],session("lang"))}}</button>--}}
                            <input form="f" id="btn-reset" class="btn btn-outline-danger" type="reset" {{__("global.reset",[],session("lang"))}}>
                        </div>
                    </div>
                </div>
                <div class="form-group col-md-2 col-sm-12  ">
                    <img id="image" src="@yield("image_path",asset("images/systemImages/default_invoice_img.png"))" style="width:100%;max-width:200px;margin:10px auto ;border-radius:50%">
                    <input form="form" type="file" id="invoice_image" name="image" class="form-control-file">
                </div>
            </form>

        </div>

        <div class="card-body">

            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable1" width="100%" cellspacing="0">
                    <thead>
                    <tr>
                        <th>{{__("global.id",[],session("lang"))}}</th>
                        <th>{{__("global.second_part",[],session("lang"))}}</th>
                        <th>{{__("global.received",[],session("lang"))}}</th>
                        <th>{{__("global.payed",[],session("lang"))}}</th>
                        <th>{{__("global.notes",[],session("lang"))}}</th>
                        <th id="td-delete-restore"@yield("hidden")></th>
                    </tr>
                    </thead>
                    <form id="form" action="@yield("form-route",route("invoice.storeCashInvoice"))" autocomplete="false" method="post" accept-charset="UTF-8" enctype="multipart/form-data">
                        @csrf
                        @yield("method")
                        <tbody id="body">
                        @yield("invoiceLines")
                        </tbody>
                    </form>
                </table>
            </div>
        </div>

        <div class="card-footer">
            <button id="btn-close-invoice" title="{{__("global.close_invoice",[],session("lang"))}}" class="btn btn-success" data-toggle="modal" data-target="#closingDateModal" @yield("hide")>{{__("global.close_invoice",[],session("lang"))}}</button>
            <label class="ml-md-5 ml-sm-3" style="font-size: large" >{{__("global.total_received",[],session("lang"))}} : <span id="total_received" style="font-style: italic; color:darkblue">@yield("total_received",0)</span>  <span id="invoice_pound">@yield("pound_type")</span></label>
            <label class="ml-md-5 ml-sm-3" style="font-size: large" >{{__("global.total_payed",[],session("lang"))}} : <span id="total_payed" style="font-style: italic; color:darkblue">@yield("total_payed",0)</span>  <span id="invoice_pound">@yield("pound_type")</span></label>
        </div>

    </div>
</dev>







{{--<form id="form" method="post" action="{{route("invoice.storeCashInvoice")}}" accept-charset="UTF-8" enctype="multipart/form-data">--}}
{{--    @csrf--}}
{{--    <div class="form-group row">--}}
{{--        <label class="col-md-2 col-sm-12" style="font-size: large" for="invoice_id" >{{__("global.invoice_id",[],session("lang"))}}</label>--}}
{{--        <input id="invoice_id" name="invoice_id" min="0" type="number" class="form-control col-md-2 col-sm-12">--}}
{{--    </div>--}}
{{--    <hr>--}}
{{--    <div class="row">--}}

{{--        <div class="col-md-4 col-sm-12">--}}
{{--            <div class="form-group">--}}
{{--                <label style="font-size: large" for="first_part_name" >{{__("global.first_part",[],session("lang"))}}</label>--}}
{{--                <select id="first_part_name" name="first_part_name"  class="form-control" contenteditable="true">--}}
{{--                    <option></option>--}}
{{--                    @foreach(App\Models\Account::get() as $account)--}}
{{--                        <option  value="{{$account->name}}">{{$account->name }}</option>--}}
{{--                    @endforeach--}}
{{--                </select>--}}
{{--            </div>--}}
{{--            <div class="form-group">--}}
{{--                <label style="font-size: large" for="second_part_name" >{{__("global.second_part",[],session("lang"))}}</label>--}}
{{--                <select id="second_part_name" name="second_part_name" class="form-control" >--}}
{{--                    <option></option>--}}
{{--                    @foreach(App\Models\Account::get() as $account)--}}
{{--                        <option value="{{$account->name}}">{{$account->name}}</option>--}}
{{--                    @endforeach--}}
{{--                </select>--}}

{{--            </div>--}}

{{--        </div>--}}

{{--        <div class="col-md-3 col-sm-12">--}}
{{--            <div class="form-group">--}}
{{--                <label style="font-size: large" for="money" >{{__("global.money",[],session("lang"))}}</label>--}}
{{--                <input type="number" min="0" class="form-control" id="money" name="money" value="0">--}}
{{--            </div>--}}
{{--            <div class="form-group">--}}
{{--                <label style="font-size: large" for="pound_type" >{{__("global.pound",[],session("lang"))}}</label>--}}
{{--                <select id="pound_type" name="pound_type" class="form-control">--}}
{{--                    <option value="syrian">syrian</option>--}}
{{--                    <option value="dollar">dollar</option>--}}
{{--                </select>--}}
{{--            </div>--}}
{{--        </div>--}}

{{--        <div class="col-md-4 col-sm-12">--}}
{{--            <div class="form-group">--}}
{{--                <img id="image" src="{{asset("images/systemImages/default_invoice_img.png")}}" style="width:100%;max-width:200px;margin:10px auto ;border-radius:50%">--}}
{{--                <input type="file" id="image" name="image" class="form-control-file">--}}
{{--            </div>--}}
{{--        </div>--}}

{{--        <div class="col-md-5 col-sm-12">--}}
{{--            <div class="form-group">--}}
{{--                <label style="font-size: large" for="notes" >{{__("global.notes",[],session("lang"))}}</label>--}}
{{--                <input id="notes" name="notes" type="text" class="form-control">--}}
{{--            </div>--}}
{{--        </div>--}}

{{--    </div>--}}
{{--    <div>--}}
{{--        <input type="submit" class="btn btn-outline-success" value="{{__("global.save",[],session("lang"))}}">--}}
{{--        <input id="btn-reset" class="btn btn-outline-danger" form="form" type="{{__("global.reset",[],session("lang"))}}">--}}
{{--    </div>--}}
{{--</form>--}}
