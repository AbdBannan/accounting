<x-masterLayout.master>

    @section("title")
        {{ __("global.new_cash_invoice") }}
    @endsection


    @section("recycle_bin")
        <a class="dropdown-item" href="{{route("invoice.viewCashRecyclebin")}}">
            <i class="fas fa-trash fa-sm fa-fw mr-2 text-gray-400"></i>
            {{__("global.recycle_bin",["attribute"=>__("global.cash_invoices")])}}
        </a>
    @endsection

    @section('content')
        <div class="container">
            <x-invoices.cash-body></x-invoices.cash-body>
        </div>
    @endsection
    @section("modals")
        <x-modals.add-modal :modalName="$modalName = 'account'" :modalId="$modalId='addAccountModal'"></x-modals.add-modal>
        <x-modals.close-invoice-modal></x-modals.close-invoice-modal>
        <x-modals.update-modal :modalName="$modalName = 'pound'"></x-modals.update-modal>
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
                    $("form#form_update .form-group #name").attr("readonly","readonly");
                },100
            );

            // to restore the last saved for invoice rows
            function restore_last_saved_invoice_rows(){
                let saved_invoice_rows = localStorage.getItem("custom-savy-"+location.pathname+"-rows");
                if (saved_invoice_rows != null){
                    let ctr = 1;
                    saved_invoice_rows = JSON.parse(saved_invoice_rows);
                    let entries = "";
                    saved_invoice_rows.forEach(function (row){
                        entries +=
                            `<tr>
                            <td ondblclick="putLineInEdit(this)" id="td">`+ctr+`</td>
                            <td ondblclick="putLineInEdit(this)" id="td"><input class="auto-save" form="form" name="second_part_name_`+ctr+`" type="text" value="`+row.second_part_name+`" style="outline: none; border: none;background-color: transparent" readonly></td>
                            <td ondblclick="putLineInEdit(this)" id="td"><input class="auto-save" form="form" name="payed_`+ctr+`" type="text" value="`+row.payed+`" style="outline: none;border: none;background-color: transparent" readonly></td>
                            <td ondblclick="putLineInEdit(this)" id="td"><input class="auto-save" form="form" name="received_`+ctr+`" type="text" value="`+row.received+`" style="outline: none;border: none;background-color: transparent" readonly></td>
                            <td ondblclick="putLineInEdit(this)" id="td"><input class="auto-save" form="form" name="notes_`+ctr+`" type="text" value="`+row.notes+`" style="outline: none;border: none;background-color: transparent" readonly></td>
                            <td id="td_delete_restore">
                                <div class="d-flex">
                                    <a onclick="restoreLine(this)" id="btn_restore_invoice_line" class="fas fa-undo col-5"></a>
                                    <br>
                                    <a onclick="deleteLine(this)" id="btn_delete_invoice_line" class="fas fa-trash col-5"></a>
                                </div>
                            </td>
                        </tr>
                        `;
                        ctr++;
                    });
                    $("#body").append(entries);
                    let rows_to_delete = localStorage.getItem("custom-savy-"+location.pathname+"-delete_row_button_status");
                    if (rows_to_delete != null){
                        rows_to_delete = JSON.parse(rows_to_delete);
                        $("#body tr").each(function (){
                            if (rows_to_delete.indexOf(parseInt($(this).children("td:first").text())) > -1){
                                $(this).children("#td_delete_restore").children(".d-flex").children("a#btn_delete_invoice_line").click();
                            }
                        });
                    }

                }
                setTimeout(function (){
                    reCalcInvoiceTotalPrice();
                },100);
            }
            restore_last_saved_invoice_rows();


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
                let second_part_name = $("#second_part_name").val();
                let payed = $("#payed").val();
                let received = $("#received").val();
                let notes = $("#notes").val();
                if (second_part_name == "") {
                    $("#second_part_name").addClass("is-invalid");
                    error_found = true;
                }
                if (payed == "" && received == "") {
                    $("#payed").addClass("is-invalid");
                    $("#received").addClass("is-invalid");
                    error_found = true;
                }
                if (error_found) {
                    return;
                }
                if (!isLineInEditing) {
                    let ctr = $("#body").children().filter("tr").length + 1;
                    if (Number(received))
                        payed = 0;
                    else
                        received = 0;
                    let entries =
                    `<tr>
                        <td ondblclick="putLineInEdit(this)" id="td">`+ctr+`</td>
                        <td ondblclick="putLineInEdit(this)" id="td"><input form="form" name="second_part_name_`+ctr+`" type="text" value="`+second_part_name+`" style="outline: none; border: none;background-color: transparent" readonly></td>
                        <td ondblclick="putLineInEdit(this)" id="td"><input form="form" name="received_`+ctr+`" type="text" value="`+received+`" style="outline: none;border: none;background-color: transparent" readonly></td>
                        <td ondblclick="putLineInEdit(this)" id="td"><input form="form" name="payed_`+ctr+`" type="text" value="`+payed+`" style="outline: none;border: none;background-color: transparent" readonly></td>
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
                let row = {
                    second_part_name:second_part_name,
                    payed:payed,
                    received:received,
                    notes:notes
                }
                let lastRows = localStorage.getItem("custom-savy-"+location.pathname+"-rows");
                if (lastRows == null){
                    localStorage.setItem("custom-savy-"+location.pathname+"-rows",JSON.stringify([row]));
                }else{
                    lastRows = JSON.parse(lastRows);
                    lastRows.push(row);
                    localStorage.setItem("custom-savy-"+location.pathname+"-rows",JSON.stringify(lastRows));
                }
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
                $("#payed").focus();
                if ($("#payed").val()==0)
                    $("#payed").attr("disabled",true);
                else if ($("#received").val()==0)
                    $("#received").attr("disabled",true);
            }

            $("#btn_reset").on("click",function (){
                $(".is-invalid").each(function () {
                    $(this).removeClass("is-invalid");
                });
                $("#payed").attr("disabled",false);
                $("#received").attr("disabled",false);
                $("#received").focus();
                isLineInEditing = false;
                isNewLineMode = false;
            });

            function deleteLine(e){
                $(e).parent().parent().siblings().filter("td").children().prop("disabled",true).css("color","lightgray");
                $(e).parent().parent().siblings().filter("td").prop("disabled",true);
                reCalcInvoiceTotalPrice();
                let id = $(e).parent().parent().siblings().filter("td").get(0).innerHTML;
                id = parseInt(id);
                let ids = localStorage.getItem("custom-savy-"+location.pathname+"-delete_row_button_status");
                if (ids == null){
                    localStorage.setItem("custom-savy-"+location.pathname+"-delete_row_button_status",JSON.stringify([id]));
                }else if (ids.indexOf(id) == -1){
                    ids = JSON.parse(ids);
                    ids.push(id);
                    localStorage.setItem("custom-savy-"+location.pathname+"-delete_row_button_status",JSON.stringify(ids));
                }
            }

            function restoreLine(e){
                $(e).parent().parent().siblings().filter("td").prop("disabled",false);
                $(e).parent().parent().siblings().filter("td").children().prop("disabled ",false).css("color","black");;
                reCalcInvoiceTotalPrice();
                let id = $(e).parent().parent().siblings().filter("td").get(0).innerHTML;
                id = parseInt(id);
                let ids = localStorage.getItem("custom-savy-"+location.pathname+"-delete_row_button_status");
                if (ids != null){
                    ids = JSON.parse(ids);
                    let index = ids.indexOf(id);
                    if (index >-1){
                        ids.splice(index,1);
                    }
                    localStorage.setItem("custom-savy-"+location.pathname+"-delete_row_button_status",JSON.stringify(ids));
                }
            }

            function reCalcInvoiceTotalPrice(){
                let payed = 0;
                let received = 0;
                $("#body tr td input[name^='payed_']").filter(function (){
                    if ($(this).parent().prop("disabled") != true){
                        payed+= parseFloat($(this).val());
                        received+= parseFloat($(this).parent("td").siblings().children("input[name^='received_']").val());
                    }
                });
                $("#total_payed").text($.fn.custom_round(payed,2));
                $("#total_received").text($.fn.custom_round(received,2));
            }

            $("#payed").on("keyup",function (){
                if ($(this).val() !=""){
                    $("#received").attr("disabled",true);
                }
                else{
                    $("#received").attr("disabled",false);
                }
            });

            $("#received").on("keyup",function (){
                if ($(this).val() !=""){
                    $("#payed").attr("disabled",true);
                }
                else{
                    $("#payed").attr("disabled",false);
                }
            });


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








