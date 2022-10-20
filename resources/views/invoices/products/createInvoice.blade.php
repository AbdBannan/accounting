<x-masterLayout.master>

    @if($invoice_type == "sale")
        @section("title")
            {{ __("global.new_sale_invoice",[],session("lang")) }}
        @endsection
    @elseif($invoice_type == "purchase")
        @section("title")
            {{ __("global.new_purchase_invoice",[],session("lang")) }}
        @endsection
    @elseif($invoice_type == "sale_return")
        @section("title")
            {{ __("global.new_sale_return_invoice",[],session("lang")) }}
        @endsection
    @elseif($invoice_type == "purchase_return")
        @section("title")
            {{ __("global.new_purchase_return_invoice",[],session("lang")) }}
        @endsection
    @endif

    @section("recycle_bin")
        <a class="dropdown-item" href="{{route("invoice.viewRecyclebin")}}">
            <i class="fas fa-trash fa-sm fa-fw mr-2 text-gray-400"></i>
            {{__("global.recycle_bin",["attribute"=>__("global.invoices",[],session("lang"))],session("lang"))}}
        </a>
    @endsection

    @section('content')
        <div class="container">
            <x-invoices.invoice-body :invoiceType="$invoice_type"></x-invoices.invoice-body>
        </div>
    @endsection
    @section("models")
        <x-models.delete-confirm-model></x-models.delete-confirm-model>
        <x-models.close-invoice-model></x-models.close-invoice-model>
    @endsection
    @section("script")
            <script>
                let ids = [];
                let isNewLineMode = true;
                let isLineInEditing = false;
                // $(".row input[type='text'],.row input[type='number'],.row select").prop("disabled",true);
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

                    if (!isLineInEditing) {
                        // let entries = "<tr>"
                        // // let ctr=0;
                        // let inputs = $("form#f input");
                        // for (let input in inputs) {
                        //     if(Number(inputs[input]) || $(inputs[input]).attr("type")=="reset")
                        //         break;
                        //     let val = $(inputs[input]).val();
                        //     let name = $(inputs[input]).attr("name");
                        //     if(val == "") {
                        //         alert("must not be empty");
                        //         return;
                        //     }
                        //     entries += "<td ondblclick='putLineInEdit(this)' id='td'><input form='form' name='"+name+"_"+ctr+"' type='text' value='"+val+"' style='outline: none; border: none;background-color: transparent' readonly></td>"
                        //     ctr++;
                        //
                        // }
                        // entries += "</tr>";

                        // let invoice_id = $("#invoice_id").val();
                        let first_part_name = $("#first_part_name").val();
                        // let second_part_name = $("#second_part_name").val();
                        let product_name = $("#product_name").val();
                        let quantity = $("#quantity").val();
                        let price = $("#price").val();
                        let total_price = $("#total_price").val();
                        // let posting = $("#posting").val();
                        // let pound_type = $("#pound_type").val();
                        let notes = $("#notes").val();

                        let ctr = $("#body").children().filter("tr").length + 1;
                        if (first_part_name == "" || product_name == "" || quantity == "" || price == "" || total_price == "" ){
                            alert("should not be empty");
                            return;
                        }
                        let entries =
                            `<tr>
                                 <td ondblclick="putLineInEdit(this)" id="td">`+ctr+`</td>
                                 <td ondblclick="putLineInEdit(this)" id="td"><input form="form" name="first_part_name_`+ctr+`" type="text" value="`+first_part_name+`" style="outline: none; border: none;background-color: transparent" readonly></td>
                                 <td ondblclick="putLineInEdit(this)" id="td"><input form="form" name="product_name_`+ctr+`" type="text" value="`+product_name+`" style="outline: none;border: none;background-color: transparent" readonly></td>
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
                        // isNewLineMode = false;
                    }
                    else if(isLineInEditing){
                        for (let item in ids) {
                            $("input[name='"+ids[item][0]+"'").val($("#"+ids[item][1]).val());
                        }
                        ids = [];
                        isLineInEditing = false;
                    }
                    $("#btn-reset").click();
                    reCalcInvoiceTotalPrice();
                });

                function putLineInEdit(e) {
                    if (isLineInEditing)
                        return;

                    ids = [];
                    isLineInEditing = true;

                    $(e).parent().children("td#td").children("input").filter(function (){
                        let id = $(this).attr("name").split(/_[0-9]+/)[0];
                        ids.push([$(this).attr("name"),id]);
                        $("#"+id).val($(this).val());
                    });
                    $("#quantity").focus();
                }

                $("#btn-reset").on("click",function (){
                    // $(".row input[type='text'],.row input[type='number'],.row select").prop("disabled",true);
                    // $("#invoice_id").prop("disabled",false);
                    // $("#second_part_name").prop("disabled",false);
                    $("#quantity").focus();
                    isLineInEditing = false;
                    isNewLineMode = false;
                });

                function deleteLine(e){
                    $(e).parent().parent().siblings().filter("td").children().prop("disabled",true).css("color","lightgray");
                    $(e).parent().parent().siblings().filter("td").prop("disabled",true);
                    reCalcInvoiceTotalPrice();
                }

                function restoreLine(e){
                    $(e).parent().parent().siblings().filter("td").prop("disabled",false);
                    $(e).parent().parent().siblings().filter("td").children().prop("disabled",false).css("color","black");
                    reCalcInvoiceTotalPrice();
                }
                //
                // $("#btn-add-new-item-to-edited-invoice").on("click",function (e){
                //     e.preventDefault();
                //     // $(".row input[type='text'],.row input[type='number'],.row select").prop("disabled",false);
                //     isNewLineMode=true;
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

                function resize(){
                    $("td input").each(function(){
                        $("#test_size_label").text($(this).val());
                        let width = $("#test_size_label").css("width");
                        $("#test_size_label").text("");
                        $(this).css("min-width", width);
                    });
                }


            </script>

    <!-- Page level plugins -->
        <script src={{asset("vendor/datatables/jquery.dataTables.js")}}></script>
        <script src={{asset("vendor/datatables/dataTables.bootstrap4.js")}}></script>

        <!-- Page level custom scripts -->
        <script src={{asset("js/demo/datatables-demo.js?var=4215".rand(1,100))}}></script>
    @endsection
</x-masterLayout.master>








