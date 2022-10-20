<x-masterLayout.master>

    @section("title")
        {{ __("global.new_cash_invoice",[],session("lang")) }}
    @endsection


    @section("recycle_bin")
        <a class="dropdown-item" href="{{route("invoice.viewCashRecyclebin")}}">
            <i class="fas fa-trash fa-sm fa-fw mr-2 text-gray-400"></i>
            {{__("global.recycle_bin",["attribute"=>__("global.cash_invoices",[],session("lang"))],session("lang"))}}
        </a>
    @endsection

    @section('content')
        <div class="container">
            <x-invoices.cash-body></x-invoices.cash-body>
            <x-models.close-invoice-model></x-models.close-invoice-model>
        </div>
    @endsection
    @section("script")
            <script>
                let ids = [];
                let isNewLineMode = true;
                let isLineInEditing = false;

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

                        let second_part_name = $("#second_part_name").val();
                        let payed = $("#payed").val();
                        let received = $("#received").val();
                        let notes = $("#notes").val();

                        let ctr = $("#body").children().filter("tr").length + 1;
                        if (second_part_name == "" || (payed == "" && received == "")){
                            alert("should not be empty");
                            return;
                        }
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
                    $("#payed").focus();
                    if ($("#payed").val()==0)
                        $("#payed").attr("disabled",true);
                    else if ($("#received").val()==0)
                        $("#received").attr("disabled",true);
                }

                $("#btn-reset").on("click",function (){
                    $("#payed").focus();
                    $("#payed").attr("disabled",false);
                    $("#received").attr("disabled",false);
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
                    $(e).parent().parent().siblings().filter("td").children().prop("disabled ",false).css("color","black");;
                    reCalcInvoiceTotalPrice();
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
                    $("#total_payed").text(payed);
                    $("#total_received").text(received);
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


            <!-- Page level plugins -->
        <script src={{asset("vendor/datatables/jquery.dataTables.js")}}></script>
        <script src={{asset("vendor/datatables/dataTables.bootstrap4.js")}}></script>

        <!-- Page level custom scripts -->
        <script src={{asset("js/demo/datatables-demo.js?var=415".rand(1,100))}}></script>
    @endsection
</x-masterLayout.master>








