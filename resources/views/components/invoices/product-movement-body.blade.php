<div class="col-sm-12">
    <div class="card shadow">
        <div class="card-header py-3">

            <form id="f" class="row" autocomplete="off">
                <div class="col-md-10 col-sm-12">
                    <div class="row">
                        <div class="form-group col-md-3 col-sm-12">
                            <label class="" style="font-size: large" for="invoice_id" >{{__("global.invoice_id")}}</label>
                            @php
                                $invoice_id = App\Models\Journal::withTrashed()->where("detail",0)->whereIn("invoice_type",[11])->selectRaw("max(invoice_id) as mid")->get()[0]["mid"]+1;
                            @endphp
                            <input form="form" id="invoice_id" name="invoice_id" min="0" type="number" class="form-control" readonly value="@yield("invoice_id",$invoice_id)">
                        </div>
                        <div class="form-group col-md-3 col-sm-12">
                            <label style="font-size: large" for="moved_product_name" >{{__("global.moved_product_name")}}</label>
                            <div class="input-group mb-3">
                                <input tabindex="1" id="moved_product_name" name="moved_product_name" type="text" placeholder="" class="form-control dropdown-toggle auto-save" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" value="@yield("moved_to_product_name")"/>
                                <div class="input-group-prepend">
                                    <span class="input-group-text">
                                          <a id="btn_add_product" data-toggle="modal" data-target="#addProductModal" data-route="{{route("product.storeProduct")}}">
                                                <i class="fas fa-plus text-green"></i>
                                          </a>
                                    </span>
                                </div>
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{__("messages.value_not_found")}}</strong>
                                </span>
                                <div style="max-height:200px;overflow-y: scroll" id="dropdown_menu" class="dropdown-menu" aria-labelledby="second_part_name">
                                    @foreach(App\Models\Product::get() as $product)
                                        <option class="dropdown-item"  value="{{$product->id}}">{{$product->name}}</option>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                        <div class="form-group col-md-3 col-sm-12">
                            <label style="font-size: large" for="pound_type" >{{__("global.pound")}}</label>

                            <div class="input-group mb-3">
                                <input tabindex="1" value="@yield('pound_type',auth()->user()->getConfig("default_pound"))" form="form" id="pound_type" name="pound_type" type="text" placeholder="" class="form-control dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" />
                                <div class="input-group-prepend">
                                    <span class="input-group-text">
                                          <a id="btn_update_pound" onclick="$('a#btn_update').each(function (){
                                                          if ($(this).data('fields')['name'].trim() == $('#pound_type').val().trim()){
                                                              $(this).click();
                                                          }
                                                      })">
                                              <i class="fas fa-edit text-green"></i>
                                          </a>
                                          <span class="ml-1 mr-1" id="correct_message" hidden><i class="fas fa-check text-green"></i></span>

                                    </span>
                                </div>
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{__("messages.value_not_found")}}</strong>
                                </span>
                                <div style="max-height:200px;overflow-y: scroll" id="dropdown_menu" class="dropdown-menu row" aria-labelledby="pound_type">
                                    @foreach(App\Models\Pound::all() as $pound)
                                        <option value="{{$pound->name}}" class="dropdown-item" >{{$pound->name}}
                                            <a id="btn_update" title="{{__("global.update")}}"  href="#" data-toggle="modal" data-target="#updateModal" data-fields="{{$pound}}" data-route="{{route("pound.updatePound",$pound->id)}}"><i class="fas fa-edit text-green" ></i></a>
                                        </option>
                                    @endforeach
                                </div>

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
                                <label style="font-size: large" for="total_price" >{{__("global.total_price")}}</label>
                                <input form="f" type="number" min="0" class="form-control auto-save" id="total_price" name="total_price" disabled>
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{__("messages.should_not_be_empty")}}</strong>
                                </span>
                            </div>
                        </div>
                        <div class="col-md-2 col-sm-12">
                            <div class="form-group">
                                <label style="font-size: large" for="quantity" >{{__("global.quantity")}}</label>
                                <input tabindex="2" form="f" type="number" min="0" class="form-control auto-save" id="quantity" name="quantity" >
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{__("messages.should_not_be_empty")}}</strong>
                                </span>
                            </div>
                        </div>
                        <div class="col-md-2 col-sm-12">
                            <div class="form-group">
                                <label style="font-size: large" for="price" >{{__("global.price")}}</label>
                                <input tabindex="3" form="f" type="number" min="0" class="form-control auto-save" id="price" name="price" >
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{__("messages.should_not_be_empty")}}</strong>
                                </span>
                            </div>
                        </div>

                        <div class="col-md-3 col-sm-12">
                            <div class="form-group">
                                <label style="font-size: large" for="moved_to_product_name" >{{__("global.moved_to_product_name")}}</label>
                                <input tabindex="4" form="f" id="moved_to_product_name" name="moved_to_product_name"  type="text" placeholder="" class="form-control dropdown-toggle auto-save" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" />
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{__("messages.value_not_found")}}</strong>
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
                            <label style="font-size: large" for="notes" >{{__("global.notes")}}</label>
                            <input tabindex="5" form="f" id="notes" name="notes" type="text" class="form-control auto-save">
                        </div>

                        <div>
                            <button tabindex="6" form="aa" id="btn_add_item_to_invoice" class="btn btn-outline-success">{{__("global.add")}}</button>
                            <input form="f" id="btn_reset" class="btn btn-outline-danger" type="reset" value="{{__("global.reset")}}" >
                        </div>
                    </div>
                </div>
                <div class="form-group col-md-2 col-sm-12 text-center">
                    <a id="toggle_qr">{{__("global.qr")}}<input type="hidden" value="image" name="upload_image_method" form="form"></a>
                    <a id="toggle_image" style="display: none">{{__("global.image")}}<input type="hidden" value="qr" name="upload_image_method" form="aa"></a>
                    <div id="qr_code_container" class="p-2" style="display: none">
                        {!! QrCode::size(100)->generate(route("uploadImage")."#productMovement_$invoice_id") !!}
                    </div>
                    <div id="image_container" >
                        <a target="_blank" href="@yield("image_path",asset("images/systemImages/default_invoice_img.png"))" hidden></a>
                        <img id="image" src="@yield("image_path",asset("images/systemImages/default_invoice_img.png"))" style="padding:5px;width: 100%;border-radius:50%;max-width:200px;">
                        {{--                        <img class="position-absolute" id="image" src="@yield("image_path",asset("images/systemImages/default_invoice_img.png"))" style="width:100%;max-width:200px;margin:10px auto ;border-radius:50%">--}}
                        <input form="form" type="file" id="invoice_image" name="image" class="form-control-file">
                        <span class="invalid-feedback" role="alert">
                            <strong>{{__("messages.max_size_is_3_MB")}}</strong>
                        </span>
                    </div>
                </div>

            </form>

        </div>

        <div class="card-body">

            <div class="table-responsive">
                <table class="table table-bordered table-striped" id="dataTable1" width="100%" cellspacing="0">
                    <thead>
                    <tr>
                        <th>{{__("global.id")}}</th>
                        <th>{{__("global.moved_product_name")}}</th>
                        <th>{{__("global.moved_to_product_name")}}</th>
                        <th>{{__("global.quantity")}}</th>
                        <th>{{__("global.price")}}</th>
                        <th>{{__("global.total_price")}}</th>
                        <th>{{__("global.notes")}}</th>
                        <th id="td_delete_restore"@yield("hidden")></th>
                    </tr>
                    </thead>
                    <form id="form" action="@yield("form_route",route("invoice.storeProductMovementInvoice"))" method="post" accept-charset="UTF-8" enctype="multipart/form-data" autocomplete="off">
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
            <div class="d-flex justify-content-between">
                <button id="btn_close_invoice" title="{{__("global.close_invoice")}}" class="btn btn-success" data-toggle="modal" data-target="#closingDateModal" @yield("hidden")>{{__("global.close_invoice")}}</button>
                <label class="ml-md-5 ml-sm-3" style="font-size: large" >{{__("global.total_invoice_price")}} : <span id="total_invoice_price" style="font-style: italic; color:darkblue">@yield("total_price",0)</span>  <span id="invoice_pound">@yield("pound_type")</span></label>
                <strong class="mr-5 d-inline-block">@yield("date",\Illuminate\Support\Carbon::now()->format("d/m/Y"))</strong>
            </div>
        </div>
    </div>
</div>
