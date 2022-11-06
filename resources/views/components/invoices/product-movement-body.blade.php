<dev class="col-lg-8 col-sm-12">
    <div class="card shadow">
        <div class="card-header py-3">

            <form id="f" class="row" autocomplete="false">
                <div class="col-md-10 col-sm-12">
                    <div class="row">
                        <div class="form-group col-md-3 col-sm-12">
                            <label class="" style="font-size: large" for="invoice_id" >{{__("global.invoice_id",[],session("lang"))}}</label>
                            <input form="form" id="invoice_id" name="invoice_id" min="0" type="number" class="form-control" readonly value="@yield("invoice_id",App\Models\Journal::withTrashed()->where("detail",0)->whereIn("invoice_type",[11])->selectRaw("max(invoice_id) as mid")->get()[0]["mid"]+1)">
                        </div>
                        <div class="form-group col-md-3 col-sm-12">
                            <label style="font-size: large" for="moved_product_name" >{{__("global.moved_product_name",[],session("lang"))}}</label>
                            <input id="moved_product_name" name="moved_product_name" type="text" placeholder="" class="form-control dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" value="@yield("moved_to_product_name")" @yield("auto_focus","autofocus")/>
                            <span class="invalid-feedback" role="alert">
                                <strong>{{__("messages.value_not_found",[],session("lang"))}}</strong>
                            </span>
                            <div style="max-height:200px;overflow-y: scroll" id="dropdown_menu" class="dropdown-menu" aria-labelledby="second_part_name">
                                @foreach(App\Models\Product::get() as $product)
                                    <option class="dropdown-item"  value="{{$product->id}}">{{$product->name}}</option>
                                @endforeach
                            </div>
                        </div>
                        <div class="form-group col-md-3 col-sm-12">
                            <label style="font-size: large" for="pound_type" >{{__("global.pound",[],session("lang"))}}</label>
                            <input value="@yield('pound_type',__("global.".auth()->user()->getConfig("default_pound"),[],session("lang")))" form="form" id="pound_type" name="pound_type" type="text" placeholder="" class="form-control dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" />
                            <span class="invalid-feedback" role="alert">
                                <strong>{{__("messages.value_not_found",[],session("lang"))}}</strong>
                            </span>
                            <div style="max-height:200px;overflow-y: scroll" id="dropdown_menu" class="dropdown-menu" aria-labelledby="pound_type">
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
                                <label style="font-size: large" for="total_price" >{{__("global.total_price",[],session("lang"))}}</label>
                                <input form="f" type="number" min="0" class="form-control" id="total_price" name="total_price" disabled>
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{__("messages.should_not_be_empty",[],session("lang"))}}</strong>
                                </span>
                            </div>
                        </div>
                        <div class="col-md-2 col-sm-12">
                            <div class="form-group">
                                <label style="font-size: large" for="quantity" >{{__("global.quantity",[],session("lang"))}}</label>
                                <input form="f" type="number" min="0" class="form-control" id="quantity" name="quantity" >
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{__("messages.should_not_be_empty",[],session("lang"))}}</strong>
                                </span>
                            </div>
                        </div>
                        <div class="col-md-2 col-sm-12">
                            <div class="form-group">
                                <label style="font-size: large" for="price" >{{__("global.price",[],session("lang"))}}</label>
                                <input form="f" type="number" min="0" class="form-control" id="price" name="price" >
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{__("messages.should_not_be_empty",[],session("lang"))}}</strong>
                                </span>
                            </div>
                        </div>

                        <div class="col-md-3 col-sm-12">
                            <div class="form-group">
                                <label style="font-size: large" for="moved_to_product_name" >{{__("global.moved_to_product_name",[],session("lang"))}}</label>
                                <input form="f" id="moved_to_product_name" name="moved_to_product_name"  type="text" placeholder="" class="form-control dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" />
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{__("messages.value_not_found",[],session("lang"))}}</strong>
                                </span>
                                <div style="max-height:200px;overflow-y: scroll" id="dropdown_menu" class="dropdown-menu" aria-labelledby="first_part_name">
                                    @foreach(App\Models\Product::get() as $product)
                                        <option class="dropdown-item"  value="{{$product->id}}">{{$product->name}}</option>
                                    @endforeach
                                </div>
                            </div>
                        </div>



                    </div>
                    <div class="col-md-5 col-sm-12">

                        <div class="form-group">
                            <label style="font-size: large" for="notes" >{{__("global.notes",[],session("lang"))}}</label>
                            <input form="f" id="notes" name="notes" type="text" class="form-control">
                        </div>

                        <div>
                            <button form="aa" id="btn_add_item_to_invoice" class="btn btn-outline-success">{{__("global.add",[],session("lang"))}}</button>
                            <input form="f" id="btn_reset" class="btn btn-outline-danger" type="reset" value="{{__("global.reset",[],session("lang"))}}" >
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
                <table class="table table-bordered table-striped" id="dataTable1" width="100%" cellspacing="0">
                    <thead>
                    <tr>
                        <th>{{__("global.id",[],session("lang"))}}</th>
                        <th>{{__("global.moved_product_name",[],session("lang"))}}</th>
                        <th>{{__("global.moved_to_product_name",[],session("lang"))}}</th>
                        <th>{{__("global.quantity",[],session("lang"))}}</th>
                        <th>{{__("global.price",[],session("lang"))}}</th>
                        <th>{{__("global.total_price",[],session("lang"))}}</th>
                        <th>{{__("global.notes",[],session("lang"))}}</th>
                        <th id="td_delete_restore"@yield("hidden")></th>
                    </tr>
                    </thead>
                    <form id="form" action="@yield("form_route",route("invoice.storeProductMovementInvoice"))" autocomplete="false" method="post" accept-charset="UTF-8" enctype="multipart/form-data">
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
            <button id="btn_close_invoice" title="{{__("global.close_invoice",[],session("lang"))}}" class="btn btn-success" data-toggle="modal" data-target="#closingDateModal" @yield("hide")>{{__("global.close_invoice",[],session("lang"))}}</button>
            <label class="ml-md-5 ml-sm-3" style="font-size: large" >{{__("global.total_invoice_price",[],session("lang"))}} : <span id="total_invoice_price" style="font-style: italic; color:darkblue">@yield("total_price",0)</span>  <span id="invoice_pound">@yield("pound_type")</span></label>
        </div>
    </div>
</dev>
