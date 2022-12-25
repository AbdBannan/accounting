<x-masterLayout.master>


    @section("title")
        {{ __("global.new_manufacturing_action") }}
    @endsection

    @section("recycle_bin")
        <a class="dropdown-item" href="{{route("invoice.viewManufacturingRecyclebin")}}">
            <i class="fas fa-trash fa-sm fa-fw mr-2 text-gray-400"></i>
            {{__("global.recycle_bin",["attribute"=>__("global.manufacturing")])}}
        </a>
        <a class="dropdown-item" href="{{route("manufacturingTemplate.viewManufacturingTemplatesRecyclebin")}}">
            <i class="fas fa-trash fa-sm fa-fw mr-2 text-gray-400"></i>
            {{__("global.recycle_bin",["attribute"=>__("global.manufacturing_templates")])}}
        </a>
    @endsection

    @section('content')
        <div class="container">
            <x-invoices.manufacturing-body></x-invoices.manufacturing-body>
        </div>
    @endsection
    @section("modals")
        <x-modals.add-modal :modalName="$modalName = 'account'" :modalId="$modalId='addAccountModal'"></x-modals.add-modal>
        <x-modals.add-modal :modalName="$modalName = 'product'" :modalId="$modalId='addProductModal'"></x-modals.add-modal>
        <x-modals.close-invoice-modal></x-modals.close-invoice-modal>
        <x-modals.manufacturing-templates-modal></x-modals.manufacturing-templates-modal>
        <x-modals.delete-confirm-modal></x-modals.delete-confirm-modal>
        <x-modals.ajax-update-modal :modalName="$modalName = 'pound'"></x-modals.ajax-update-modal>

    @endsection
    @section("script")
            <script>

                let ids = [];
                let isNewLineMode = true;
                let isLineInEditing = false;
                setTimeout(
                    function (){
                        $("input#first_part_name").focus();
                        $("input#first_part_name").change();
                    },100
                );

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
                    $(".is-invalid").each(function () {
                        $(this).removeClass("is-invalid");
                    });
                    let error_found = false;
                    $("input[class~='dropdown-toggle']").each(function () {
                        let error = validateDropDownBox(this);
                        if (error !== "") {
                            error_found = true;
                            $("#" + error).addClass("is-invalid");
                        }
                    });

                    let first_part_name = $("#first_part_name").val();
                    let produced_quantity = $("#produced_quantity").val();
                    let raw_product_name = $("#raw_product_name").val();
                    let quantity = $("#quantity").val();
                    let price = $("#price").val();
                    let total_price = $("#total_price").val();
                    let notes = $("#notes").val();
                    if (first_part_name == "") {
                        $("#first_part_name").addClass("is-invalid");
                        error_found = true;
                    }
                    if (produced_quantity == "") {
                        $("#produced_quantity").addClass("is-invalid");
                        error_found = true;
                    }
                    if (raw_product_name == "") {
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
                    if (!isLineInEditing) {
                        let ctr = $("#body").children().filter("tr").length + 1;
                        let entries =
                        `<tr>
                            <td ondblclick="putLineInEdit(this)" id="td">`+ctr+`</td>
                            <td ondblclick="putLineInEdit(this)" id="td"><input form="form" name="raw_product_name_`+ctr+`" type="text" value="`+raw_product_name+`" style="outline: none;border: none;background-color: transparent" readonly></td>
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
                        ids = [];
                        isLineInEditing = false;
                    }
                    $("#btn_reset").click();
                    resize();
                    reCalcInvoiceTotalPrice();
                });

                function putLineInEdit(e) {
                    if (isLineInEditing)
                        return;
                    $(".is-invalid").each(function () {
                        $(this).removeClass("is-invalid");
                    });
                    ids = [];
                    isLineInEditing = true;

                    $(e).parent().children("td#td").children("input").filter(function (){
                        let id = $(this).attr("name").split(/_[0-9]+/)[0];
                        ids.push([$(this).attr("name"),id]);
                        $("#"+id).val($(this).val());
                    });
                    $("#raw_product_name").focus();
                }

                $("#btn_reset").on("click",function (){
                    $(".is-invalid").each(function () {
                        $(this).removeClass("is-invalid");
                    });
                    $("#raw_product_name").focus();
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
                function reCalcInvoiceTotalPrice(){
                    let total_price = 0;
                    $("#body tr td input[name^='total_price_']").each(function (){
                        if ($(this).parent().prop("disabled") != true){
                            total_price+= parseFloat($(this).val());
                        }
                    });
                    let produced_quantity = $("#produced_quantity").val()
                    if (produced_quantity != "") {
                        $("#pure_piece_price").val((total_price / produced_quantity).toFixed(3));
                        if ($("#gainful_value").attr("readonly") == true) {
                            calcGainfulPercentage();
                        } else {
                            calcGainfulValue();
                        }
                    }
                    $("#pure_piece_price").change();
                }



                function resize(){
                    $("td input").each(function(){
                        $("#test_size_label").text($(this).val());
                        let width = $("#test_size_label").css("width");
                        $("#test_size_label").text("");
                        $(this).css("min-width", width);
                    });
                }


                $("#produced_quantity,#gainful_percentage,#gainful_value").on("keyup",function (){
                    reCalcInvoiceTotalPrice();
                });

                $("#gainful_value").on("keyup",function () {
                    if ($("#gainful_value").attr("readonly") == "readonly"){
                        return;
                    }
                    calcGainfulPercentage();
                });
                function calcGainfulPercentage(){
                    if ($("#gainful_value").val() != "" ) {
                        if ($("#pure_piece_price").val() != "" && $("#pure_piece_price").val() != 0){
                            $("#gainful_percentage").val(($("#gainful_value").val() / $("#pure_piece_price").val() * 100).toFixed(3));
                        } else {
                            $("#gainful_percentage").val(0);
                        }
                        $("#gainful_percentage").attr("readonly", true);
                    } else {
                        $("#gainful_percentage").val("").attr("readonly", false);
                    }
                }

                $("#gainful_percentage").on("keyup",function (){
                    if ($("#gainful_percentage").attr("readonly") == "readonly"){
                        return;
                    }
                    calcGainfulValue();
                });
                function calcGainfulValue(){
                    if ($("#gainful_percentage").val() != "" ) {
                        if ($("#pure_piece_price").val() != "" && $("#pure_piece_price").val() != 0 && $("#pure_piece_price").val() != 0.00) {
                            $("#gainful_value").val(($("#gainful_percentage").val() * $("#pure_piece_price").val() / 100).toFixed(3));
                        } else {
                            $("#gainful_value").val(0);
                        }
                        $("#gainful_value").attr("readonly",true);
                    } else {
                        $("#gainful_value").val("").attr("readonly",false);
                    }
                }


                $("#pure_piece_price").on("change",function (){
                    let gainful_value = $("#gainful_value").val();
                    if (gainful_value != ""){
                        $("#with_gainful_piece_price").val((parseFloat($(this).val()) + parseFloat(gainful_value)).toFixed(3));
                    }
                });


                $("#btn_fill_manufacturing_template").on("click",fill_manufacturing_template);
                $("#btn_update_template").on("click",function (){
                    $(this).parent().parent("tr").addClass("selected");
                    fill_manufacturing_template();
                });
                $("#body tr").on("dblclick",fill_manufacturing_template);
                function fill_manufacturing_template(){
                    $("#body").children("tr").remove();
                    let components = $("#body tr.selected td#data_ccomponents").data("components");
                    $("#product_name").val($("#body tr.selected td#data_ccomponents").text());
                    $("#produced_quantity").val($("#body tr.selected td#data_ccomponents").data("quantity"));
                    let ctr = 1;
                    components.forEach(function (component){
                        let raw_product_name = component.name;
                        let quantity = component.pivot.quantity;
                        let price = component.pivot.price;
                        let total_price = price * quantity;
                        let notes = "";

                        let entries =
                            `<tr>
                            <td ondblclick="putLineInEdit(this)" id="td">`+ctr+`</td>
                            <td ondblclick="putLineInEdit(this)" id="td"><input form="form" name="raw_product_name_`+ctr+`" type="text" value="`+raw_product_name+`" style="outline: none;border: none;background-color: transparent" readonly></td>
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
                        reCalcInvoiceTotalPrice();
                        $("button[data-dismiss='modal']").click();
                        ctr++;
                    });

                }



                $("button#btn_save_template").on("click",function (e){
                   $("form#form").attr("action",$(this).data("route"));
                   $("form#form").submit();
                });
            </script>
    @endsection
</x-masterLayout.master>











