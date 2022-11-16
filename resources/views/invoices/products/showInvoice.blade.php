<x-masterLayout.master>
    @if(count($invoiceLines)==0)
        @section("title")
            {{ __("global.search_in_invoices",[],session("lang")) }}
        @endsection
    @else
        @section("title")
            {{ __("global.".$invoiceLines[0]->invoice_type,[],session("lang")) }}
        @endsection
    @endif

    @section("recycle_bin")
        <a class="dropdown-item" href="{{route("invoice.viewInvoiceRecyclebin")}}">
            <i class="fas fa-trash fa-sm fa-fw mr-2 text-gray-400"></i>
            {{__("global.recycle_bin",["attribute"=>__("global.invoices",[],session("lang"))],session("lang"))}}
        </a>
    @endsection

    @section('content')
        <div class="container">
            @if(count($invoiceLines)>0)
                <x-invoices.invoice-body :invoiceType="$invoiceLines[0]->invoice_type">
                    @section("edit_delete")

                          <div class="row">
                              <a id="btn_update_invoice" title="{{__("global.update",[],session("lang"))}}" class="col-4">
                                  <input class="grid-button grid-edit-button" type="button" title="Update">
                              </a>
                              <a id="btn_delete" title="{{__("global.delete",[],session("lang"))}}" class="col-4" data-toggle="modal" data-target="#deleteConfirmModal" @if(auth()->user()->getConfig("use_recyclebin") == "true") data-route="{{route("invoice.softDeleteInvoice",$invoiceLines[0]->invoice_id)}}" @else data-route="{{route("invoice.deleteInvoice",$invoiceLines[0]->invoice_id)}}" @endif>
                                  <input class="grid-button grid-delete-button" type="button" title="Delete">
                              </a>
                          </div>
                    @endsection
                    @section("invoice_id"){{$invoiceLines[0]->invoice_id}}@endsection
                    @section("second_part_name"){{$invoiceLines[0]->second_part_name}}@endsection
                    @section("auto_focus")@endsection
                    @section("hidden") hidden="true" @endsection
                    @section("image_path"){{asset($invoiceLines[0]->image)}}@endsection
                    @section("form_route"){{route("invoice.updateInvoice",[$invoiceLines[0]->invoice_type,$invoiceLines[0]->invoice_id])}}@endsection
                    @section("method")
                        @method("put")
                    @endsection
                    @section("invoiceLines")
                        @foreach($invoiceLines as $line)
                            <tr>
                                <td ondblclick="putLineInEdit(this)" name="line_id" id="td">{{$line->line}}</td>
                                <td ondblclick="putLineInEdit(this)" id="td"><input form="form" name="first_part_name_{{$line->line}}" type="text" value="{{$line->first_part_name}}" style="outline: none; border: none;background-color: transparent" readonly></td>
                                <td ondblclick="putLineInEdit(this)" id="td"><input form="form" name="product_name_{{$line->line}}" type="text" value="{{$line->product_name}}" style="outline: none;border: none;background-color: transparent" readonly></td>
                                <td ondblclick="putLineInEdit(this)" id="td"><input form="form" name="quantity_{{$line->line}}" type="text" value="{{$line->quantity}}" style="outline: none;border: none;background-color: transparent" readonly></td>
                                <td ondblclick="putLineInEdit(this)" id="td"><input form="form" name="price_{{$line->line}}" type="text" value="{{$line->price}}" style="outline: none;border: none;background-color: transparent" readonly></td>
                                <td ondblclick="putLineInEdit(this)" id="td"><input form="form" name="total_price_{{$line->line}}" type="text" value="{{$line->quantity * $line->price}}" style="outline: none;border: none;background-color: transparent" readonly></td>
                                <td ondblclick="putLineInEdit(this)" id="td"><input form="form" name="notes_{{$line->line}}" type="text" value="{{$line->notes}}"style="outline: none;border: none;background-color: transparent" readonly></td>
                                <td id="td_delete_restore" hidden="true">
                                    <div class="d-flex">
                                        <a onclick="restoreLine(this)" id="btn_restore_invoice_line" class="fas fa-undo col-5"></a>
                                        <br>
                                        <a onclick="deleteLine(this)" id="btn_delete_invoice_line" class="fas fa-trash col-5"></a>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    @endsection
                    @section("total_price"){{\App\Models\Journal::where("invoice_id",$invoiceLines[0]->invoice_id)->where("detail",0)->where("invoice_type","!=",0)->selectRaw("sum(price * quantity) as total_price")->get()[0]->total_price}}@endsection
                    @section("pound_type"){{$invoiceLines[0]->pound_type}}@endsection
                    @section("hide")
                        hidden="true"
                    @endsection
                </x-invoices.invoice-body>
            @else
                <div class="container">
                    <div class="row bg-gradient-light shadow" style="width: 50%;margin: auto;">
                        <form id="search_form" style="margin: auto" action="{{route("invoice.searchInvoice")}}">
                            <div class="form-group text-center">
                                <label style="font-size: x-large" for="invoice_id" >{{__("global.enter_invoice_id",[],session("lang"))}}</label>
                                <input type="number" name="invoice_id" id="invoice_id" class="form-control" autofocus>
                                <input id="btn_search" type="submit" class="btn btn-outline-primary form-control" value="{{__("global.search",[],session("lang"))}}">
                            </div>
                        </form>
                    </div>
                </div>
            @endif
        </div>
    @endsection
    @section("modals")
        <x-modals.delete-confirm-modal></x-modals.delete-confirm-modal>
        <x-modals.close-invoice-modal></x-modals.close-invoice-modal>
    @endsection
    @section("script")
        <script>
            let ids = [];
            let editingMode = false;
            let tr = null;
            let isLineInEditing = false;
            @if(count($invoiceLines)!=0)
            $(".row input[type='text'],.row input[type='number'],.row select,.row input[type='file']").prop("disabled",true);
            @endif
            // $("#invoice_id").prop("disabled",false);
            // $("#second_part_name").prop("disabled",false);
            function validateDropDownBox(dropDownBox){
                let error="";
                let options = $(dropDownBox).siblings("div").children("option");
                let isThisInputCorrect = false;
                options.each(function (){
                    if ($(dropDownBox).val().trim() == $(this).text().trim()){
                        isThisInputCorrect=true;
                        return;
                    }
                });
                if (!isThisInputCorrect){
                    error=$(dropDownBox).attr("id");
                }
                return error;
            }

            $("#btn_add_item_to_invoice").on("click",function (e){
                e.preventDefault();
                if (!editingMode )
                    return
                $(".is-invalid").each(function () {
                    $(this).removeClass("is-invalid");
                });
                let error_found = false;
                $("input[class~='dropdown-toggle").each(function () {
                    let error = validateDropDownBox(this);
                    if (error !== "") {
                        error_found = true;
                        $("#" + error).addClass("is-invalid");
                    }
                });

                let first_part_name = $("#first_part_name").val();
                let product_name = $("#product_name").val();
                let quantity = $("#quantity").val();
                let price = $("#price").val();
                let total_price = $("#total_price").val();
                let notes = $("#notes").val();
                if (first_part_name == "") {
                    $("#first_part_name").addClass("is-invalid");
                    error_found = true;
                }
                if (product_name == "") {
                    $("#product_name").addClass("is-invalid");
                    error_found = true;
                }
                if (quantity == "") {
                    $("#quantity").addClass("is-invalid");
                    error_found = true;
                }
                if (price == "") {
                    $("#price").addClass("is-invalid");
                    error_found = true;
                }
                if (error_found) {
                    return;
                }
                if (!isLineInEditing){
                    let ctr=1;
                    $("#body tr td[name='line_id']").filter(function (){
                        if (parseInt($(this).text())>ctr) {
                            ctr = parseInt($(this).text());
                        }
                    });
                    ctr++;
                    let entries =
                    `<tr>
                        <td ondblclick="putLineInEdit(this)" id="td">`+(ctr)+`</td>
                        <td ondblclick="putLineInEdit(this)" id="td"><input form="form" name="first_part_name_`+ctr+`" type="text" value="`+first_part_name+`" style="outline: none; border: none;background-color: transparent" readonly></td>
                        <td ondblclick="putLineInEdit(this)" id="td"><input form="form" name="product_name_`+ctr+`" type="text" value="`+product_name+`" style="outline: none;border: none;background-color: transparent" readonly></td>
                        <td ondblclick="putLineInEdit(this)" id="td"><input form="form" name="quantity_`+ctr+`" type="text" value="`+quantity+`" style="outline: none;border: none;background-color: transparent" readonly></td>
                        <td ondblclick="putLineInEdit(this)" id="td"><input form="form" name="price_`+ctr+`" type="text" value="`+price+`" style="outline: none;border: none;background-color: transparent" readonly></td>
                        <td ondblclick="putLineInEdit(this)" id="td"><input form="form" name="total_price_`+ctr+`" type="text" value="`+total_price+`" style="outline: none;border: none;background-color: transparent" readonly></td>
                        <td ondblclick="putLineInEdit(this)" id="td"><input form="form" name="notes_`+ctr+`" type="text" value="`+notes+`" style="outline: none;border: none;background-color: transparent" readonly></td>
                        <td id="td_delete_restore">
                            <div class="d-flex">
                                <a onclick="restoreLine(this)" id="btn_restore_invoice_line" class="fas fa-undo col-5"></a>
                                <br>
                                <a onclick="deleteLine(this)" id="btn_delete_invoice_line" class="fas fa-trash col-5"></a>
                            </div>
                        </td>
                    </tr>
                    `;
                    $("#body").append(entries);
                }
                else if(isLineInEditing){
                    for (let item in ids) {
                        $("input[name='"+ids[item][0]+"'").val($("#"+ids[item][1]).val());
                    }
                    tr.css("transition", "all 0.5s ease-in-out");
                    tr.css("background-color", "lightgreen");
                    tr = null;
                    ids = [];
                    isLineInEditing = false;
                }
                $("#btn_reset").click();
                resize();
                reCalcInvoiceTotalPrice();
            });

            function putLineInEdit(e){
                if (!editingMode || isLineInEditing)
                    return;
                $(".is-invalid").each(function () {
                    $(this).removeClass("is-invalid");
                });
                ids = [];
                isLineInEditing = true;

                tr = $($(e).parent());
                $(e).parent().children("td#td").children("input").filter(function (){
                    let id = $(this).attr("name").split(/_[0-9]+/)[0];
                    ids.push([$(this).attr("name"),id]);
                    $("#"+id).val($(this).val());
                });

                $("#quantity").focus();
            }

            $("#btn_update_invoice").on("click",function (){
                $("td[id='td_delete_restore']").attr("hidden",false);
                $("th[id='td_delete_restore']").attr("hidden",false);
                $("#btn_close_invoice").attr("hidden",false);
                $(".row input[type='text'],.row input[type='number'],.row select,.row input[type='file']").prop("disabled",false);
                $("#total_price").prop("disabled",true);
                $("input#second_part_name").focus();
                editingMode = true;
            });

            $("#btn_reset").on("click",function (){
                if (!editingMode )
                    return
                $(".is-invalid").each(function () {
                    $(this).removeClass("is-invalid");
                });
                $("input#quantity").focus();
                isLineInEditing = false;
                tr = null;
            });

            function deleteLine(e){
                if (!editingMode)
                    return
                $(e).parent().parent().siblings().filter("td").children().prop("disabled",true).css("color","lightgray");
                $(e).parent().parent().siblings().filter("td").prop("disabled",true);
                reCalcInvoiceTotalPrice();
            }

            function restoreLine(e){
                if (!editingMode)
                    return
                $(e).parent().parent().siblings().filter("td").prop("disabled",false);
                $(e).parent().parent().siblings().filter("td").children().prop("disabled",false).css("color","black");;
                reCalcInvoiceTotalPrice();
            }


            // $("#btn_add-new-item-to-edited-invoice").on("click",function (e){
            //     e.preventDefault();
            //     if (!editingMode || isLineInEditing)
            //         return
            //     // $(".row input[type='text'],.row input[type='number'],.row select").prop("disabled",false);
            // });

            function reCalcInvoiceTotalPrice(){
                let total_price = 0;
                $("#body tr td input[name^='total_price_']").filter(function (){
                    if ($(this).parent().prop("disabled") != true){
                        total_price+= parseFloat($(this).val());
                    }
                });
                $("#total_invoice_price").text(total_price);
            }
            $("#btn_close_invoice").on("click",function (){
                setTimeout(function (){
                    $("#closing_date").get(0).focus();
                },40);
            });


            function resize(){
                $("td input").each(function(){
                    $("#test_size_label").text($(this).val());
                    let width = $("#test_size_label").css("width");
                    $("#test_size_label").text("");
                    $(this).css("min-width", width);
                });
            }

            resize();
        </script>
    @endsection
</x-masterLayout.master>








