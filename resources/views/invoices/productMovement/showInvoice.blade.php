<x-masterLayout.master>
    @if(count($invoiceLines)==0)
        @section("title")
            {{ __("global.search",[],session("lang")) }}
        @endsection
    @else
        @section("title")
            {{ __("global.product_movement",[],session("lang")) }}
        @endsection
    @endif

    @section("recycle_bin")
        <a class="dropdown-item" href="{{route("invoice.viewProductMovementRecyclebin")}}">
            <i class="fas fa-trash fa-sm fa-fw mr-2 text-gray-400"></i>
            {{__("global.recycle_bin",["attribute"=>__("global.product_movement_invoices",[],session("lang"))],session("lang"))}}
        </a>
    @endsection

    @section('content')
        <div class="container">
            <label id="test_size_label"></label>
            @if(count($invoiceLines)>0)
                <x-invoices.product-movement-body>
                    @section("edit_delete")

                          <div class="row">
                              <a id="btn-update-invoice" title="{{__("global.update",[],session("lang"))}}" class="col-4">
                                  <input class="grid-button grid-edit-button" type="button" title="Update">
                              </a>
                              <a id="btn-delete" title="{{__("global.delete",[],session("lang"))}}" class="col-4" data-toggle="modal" data-target="#deleteConfirmModal" data-route={{route("invoice.softDeleteProductMovementInvoice",$invoiceLines[0]->invoice_id)}}>
                                  <input class="grid-button grid-delete-button" type="button" title="Delete">
                              </a>
                          </div>
                    @endsection
                    @section("invoice_id"){{$invoiceLines[0]->invoice_id}}@endsection
                    @section("auto_focus")@endsection
                    @section("hidden") hidden="true" @endsection

                    @section("image_path"){{asset($invoiceLines[0]->image)}}@endsection
                    @section("form-route"){{route("invoice.updateProductMovementInvoice",$invoiceLines[0]->invoice_id)}}@endsection
                    @section("method")
                        @method("put")
                    @endsection
                    @section("invoiceLines")
                        @foreach($invoiceLines as $line)

                            <tr>
                                <td ondblclick="putLineInEdit(this)" name="line_id" id="td">{{$line->line}}</td>
                                <td ondblclick="putLineInEdit(this)" id="td"><input form="form" name="moved_product_name_{{$line->line}}" type="text" value="{{$line->product_name}}" style="outline: none; border: none;background-color: transparent" readonly></td>
                                <td ondblclick="putLineInEdit(this)" id="td"><input form="form" name="moved_to_product_name_{{$line->line}}" type="text" value="{{$line->second_part_name}}" style="outline: none;border: none;background-color: transparent" readonly></td>
                                <td ondblclick="putLineInEdit(this)" id="td"><input form="form" name="quantity_{{$line->line}}" type="text" value="{{$line->quantity}}" style="outline: none;border: none;background-color: transparent" readonly></td>
                                <td ondblclick="putLineInEdit(this)" id="td"><input form="form" name="price_{{$line->line}}" type="text" value="{{$line->price}}" style="outline: none;border: none;background-color: transparent" readonly></td>
                                <td ondblclick="putLineInEdit(this)" id="td"><input form="form" name="total_price_{{$line->line}}" type="text" value="{{$line->quantity * $line->price}}" style="outline: none;border: none;background-color: transparent" readonly></td>
                                <td ondblclick="putLineInEdit(this)" id="td"><input form="form" name="notes_{{$line->line}}" type="text" value="{{$line->notes}}"style="outline: none;border: none;background-color: transparent" readonly></td>
                                <td id="td-delete-restore" hidden="true">
                                    <div class="d-flex">
                                        <a onclick="restoreLine(this)" id="btn-restore-invoice-line" class="fas fa-undo col-5"></a>
                                        <br>
                                        <a onclick="deleteLine(this)" id="btn-delete-invoice-line" class="fas fa-trash col-5"></a>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    @endsection
                    @section("hide")
                        hidden="true"
                    @endsection
                        @section("total_price"){{\App\Models\Journal::where("invoice_id",$invoiceLines[0]->invoice_id)->where("detail",0)->whereIn("invoice_type",[11])->selectRaw("sum(price*quantity) as total_price")->groupBy("num_for_pound")->first()->total_price}}@endsection
                </x-invoices.product-movement-body>
            @else
                <div class="container">
                    <div class="row bg-gradient-light shadow" style="width: 50%;margin: auto;">
                        <form id="search_form" style="margin: auto" action="{{route("invoice.searchProductMovementInvoice")}}" method="POST">
                            @csrf
                            <div class="form-group text-center">
                                <label style="font-size: x-large" for="invoice_id" >{{__("global.enter_invoice_id",[],session("lang"))}}</label>
                                <input type="number" name="invoice_id" id="invoice_id" class="form-control" autofocus>
                                <input id="btn_search" type="submit" class="btn btn-outline-primary form-control">
                            </div>
                        </form>
                    </div>
                </div>
            @endif
        </div>
    @endsection
    @section("models")
        <x-models.delete-confirm-model></x-models.delete-confirm-model>
        <x-models.close-invoice-model></x-models.close-invoice-model>
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
                for (let opt in options){
                    if (Number(options[opt]))
                        break;
                    if ($(dropDownBox).val().trim() == $(options[opt]).text().trim()){
                        isThisInputCorrect=true;
                        break;
                    }
                }
                if (!isThisInputCorrect){
                    error=$(dropDownBox).attr("id")+ " : is not correct";
                }
                return error;
            }
            $("#btn-add-item-to-invoice").on("click",function (e){
                e.preventDefault();
                if (!editingMode )
                    return

                let errors = [];
                let inputs = $("input[class~='dropdown-toggle");
                for (let item in inputs) {
                    if(Number(inputs[item]))
                        break;
                    let error = validateDropDownBox(inputs[item]);
                    if (error !== "") {
                        errors.push(error);
                    }
                }
                if(errors.length>0){
                    alert(errors);
                    return;;
                }

                if (!isLineInEditing){

                    let moved_product_name = $("#moved_product_name").val();
                    let moved_to_product_name = $("#moved_to_product_name").val();
                    let quantity = $("#quantity").val();
                    let price = $("#price").val();
                    let total_price = $("#total_price").val();
                    let notes = $("#notes").val();
                    let ctr=1;
                    $("#body tr td[name='line_id']").filter(function (){
                        if (parseInt($(this).text())>ctr)
                            ctr = parseInt($(this).text());
                    });
                    ctr++;
                    // let ctr = $("#body").children().filter("tr").length + 1;
                    if (moved_product_name == "" || moved_to_product_name == "" || quantity == "" || price == "" || total_price == "" ){
                        alert("should not be empty");
                        return;
                    }
                    let entries =
                        `<tr>
                             <td ondblclick="putLineInEdit(this)" id="td">`+(ctr)+`</td>
                             <td ondblclick="putLineInEdit(this)" id="td"><input form="form" name="moved_product_name_`+ctr+`" type="text" value="`+moved_product_name+`" style="outline: none; border: none;background-color: transparent" readonly></td>
                             <td ondblclick="putLineInEdit(this)" id="td"><input form="form" name="moved_to_product_name_`+ctr+`" type="text" value="`+moved_to_product_name+`" style="outline: none;border: none;background-color: transparent" readonly></td>
                             <td ondblclick="putLineInEdit(this)" id="td"><input form="form" name="quantity_`+ctr+`" type="text" value="`+quantity+`" style="outline: none;border: none;background-color: transparent" readonly></td>
                             <td ondblclick="putLineInEdit(this)" id="td"><input form="form" name="price_`+ctr+`" type="text" value="`+price+`" style="outline: none;border: none;background-color: transparent" readonly></td>
                             <td ondblclick="putLineInEdit(this)" id="td"><input form="form" name="total_price_`+ctr+`" type="text" value="`+total_price+`" style="outline: none;border: none;background-color: transparent" readonly></td>
                             <td ondblclick="putLineInEdit(this)" id="td"><input form="form" name="notes_`+ctr+`" type="text" value="`+notes+`" style="outline: none;border: none;background-color: transparent" readonly></td>
                             <td id="td-delete-restore">
                                <div class="d-flex">
                                    <a onclick="restoreLine(this)" id="btn-restore-invoice-line" class="fas fa-undo col-5"></a>
                                    <br>
                                    <a onclick="deleteLine(this)" id="btn-delete-invoice-line" class="fas fa-trash col-5"></a>
                                </div>
                             </td>
                        </tr>
                        `;
                    $("#body").append(entries);
                    resize();
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
                $("#btn-reset").click();
                reCalcInvoiceTotalPrice();
            });

            function putLineInEdit(e){
                if (!editingMode || isLineInEditing)
                    return;

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

            $("#btn-update-invoice").on("click",function (){
                $("td[id='td-delete-restore']").attr("hidden",false);
                $("th[id='td-delete-restore']").attr("hidden",false);
                $("#btn-close-invoice").attr("hidden",false);
                $(".row input[type='text'],.row input[type='number'],.row select,.row input[type='file']").prop("disabled",false);
                $("input#second_part_name").focus();
                editingMode = true;
            });

            $("#btn-reset").on("click",function (){
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


            // $("#btn-add-new-item-to-edited-invoice").on("click",function (e){
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
                $("#total-invoice-price").text(total_price);
            }
            $("#btn-close-invoice").on("click",function (){
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

        <!-- Page level plugins -->
        <script src={{asset("vendor/datatables/jquery.dataTables.js")}}></script>
        <script src={{asset("vendor/datatables/dataTables.bootstrap4.js")}}></script>

        <!-- Page level custom scripts -->
        <script src={{asset("js/demo/datatables-demo.js?var=415".rand(1,11220))}}></script>
    @endsection
</x-masterLayout.master>








