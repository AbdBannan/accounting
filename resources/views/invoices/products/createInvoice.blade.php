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

                        let ctr = $("#body").children().filter("tr").length + 1;
                        let entries =
                        `<tr>
                            <td ondblclick="putLineInEdit(this)" id="td">`+ctr+`</td>
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
                    $("#quantity").focus();
                }

                $("#btn_reset").on("click",function (){
                    $(".is-invalid").each(function () {
                        $(this).removeClass("is-invalid");
                    });
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
                // $("#btn_add-new-item-to-edited-invoice").on("click",function (e){
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
                    $("#total_invoice_price").text(total_price);
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
    @endsection
</x-masterLayout.master>

























{{--if (errors[0].indexOf("first_part_name")>=0){--}}
{{--@php--}}
{{--    $error = __("global.first_part_name_is_not_correct",[],session("lang"));--}}
{{--@endphp--}}
{{--}--}}
{{--let errMessage = `--}}
{{--<div id="errors" style="max-width: 200px; position: fixed;bottom: 10px; @if(auth()->user()->getConfig("language") == "arabic") left: 2px; @else right: 2px; @endif z-index: 100">--}}
{{--    <button id="btn_hide_all" onclick="$('#errors').slideToggle();" class="btn btn-info btn-sm btn-block mb-2" >--}}
{{--        {{__("global.hide_all",[],session("lang"))}}--}}
{{--    </button>--}}
{{--    <div class="alert alert-danger alert-dismissible fade show">--}}
{{--        <button class="close" data-dismiss="alert" type="button">--}}
{{--            <span>×</span>--}}
{{--        </button>--}}
{{--        {{$error}}--}}
{{--    </div>--}}
{{--</div>`;--}}
{{--document.body.innerHTML+=errMessage;--}}
{{--setTimeout(function (){--}}
{{--$("#errors").slideUp();--}}
{{--},6000);--}}
{{--$("#btn_hide_all").on("click",function (){--}}
{{--$('#errors').slideToggle();--}}
{{--});--}}

















