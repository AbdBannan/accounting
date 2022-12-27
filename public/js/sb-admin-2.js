(function($) {
    "use strict"; // Start of use strict

    // var Toast = Swal.mixin({
    //     toast: true,
    //     position: 'bottom-start',
    //     showConfirmButton: false,
    //     timer: 4000
    // });
    //this is to fill the image from file input
    $("input#file").on("change",function(event){
        let url = URL.createObjectURL(event.target.files[0])
        $("img#profile_image").attr("src",url);
    });

    //this is to fill the image from file input
    $("input#product_image,input#invoice_image").on("change",function(event){
        let url = URL.createObjectURL(event.target.files[0]);
        $(this).siblings().filter("#image").attr("src",url);
    });

    //this is click the next anchor when image is dbClicked so , it opens the image in new tap
    $("img#image").on("dblclick",function(event){
        $(this).siblings("a").get(0).click();
    });


    // this is to populate delete confirm model
    $("a#btn_delete,a#btn_multi_delete").on("click",function(){
        let route = $(this).data("route");
        $(this).siblings("div").children("input[type='checkbox']").attr("checked",true);
        $("#form_delete").attr("action",route);
    });

    // this is to populate restore confirm model
    $("a#btn_restore,a#btn_multi_restore").on("click",function(){
        let route = $(this).data("route");
        $(this).siblings("div").children("input[type='checkbox']").attr("checked",true);
        $("#form_restore").attr("action",route);
    });

    // this is to make checkboxes in table rows belongs to form whose button is pressed (btn_multi_delete or btn_multi_restore)
    $("a#btn_multi_delete").on("click",function(){
       $("input[name='multi_ids[]']").attr("form","form_delete");
    });

    // this is to make checkboxes in table rows belongs to form whose button is pressed (btn_multi_delete or btn_multi_restore)
    $("a#btn_multi_restore").on("click",function(){
        $("input[name='multi_ids[]']").attr("form","form_restore");
    });

    // to populate the update form
    $("a#btn_update").on("click",function(){
        let route = $(this).data("route");
        let fields = $(this).data("fields");
        for (let field in fields){
            if (field == "image"){
                $("#form_update #"+field).attr("src","http://"+location.hostname+"/"+fields[field]);
            } else if (field == "account_type" || field == "group" || field == "reference" || field == "store_id" || field == "is_raw"){
                $("#form_update #"+field).children("option").filter(function (){
                    if ($(this).text() == fields[field]){
                        $(this).attr("selected",true);
                    }
                });
            } else {
                $("#form_update #"+field).val(fields[field]);
            }
        }
        $("#form_update").attr("action",route);
    });

    // to populate the add form
    $("a#btn_add").on("click",function(){
        let route = $(this).data("route");
        $("#form_add").attr("action",route);
    });

    // to populate the fast add form
    $("a#btn_add_product,a#btn_add_account").on("click",function(){
        let route = $(this).data("route");
        let id = $(this).data("target");
        $(id+" .modal-dialog .modal-content #form_add").attr("action",route);
        setTimeout(function (){
            $(id+" .modal-dialog .modal-content #form_add .modal-body .form-group #id").focus();
        },500);
    });

    // to activate_user,attach_role,attach_permission,track_user_activity
    let element = null;
    let siblingElement = null;
    $("a#btn_activate_user,a#btn_attach_role,a#btn_attach_permission,#btn_track_user_activity").on("click", function (message){
        let route = $(this).attr("route-attr");
        element = $(this);
        siblingElement = $(this).siblings()[0];
        $.ajax({
                url:route,
                method:"POST",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success:function (){
                    element.attr("hidden",true);
                    element.attr("disabled",true);
                    $(siblingElement).attr("hidden",false);
                    $(siblingElement).attr("disabled",false);
                },
                error:function (e){
                    alert("error");
                }
            }
        );
    });

    // to deactivate_user,detach_role,detach_permission,no_track_user_activity and toggle btn_activate ,btn_attach and btn_track_activity buttons
    $("a#btn_deactivate_user,a#btn_detach_role,a#btn_detach_permission,#btn_no_track_user_activity").on("click",function(){
        let route = $(this).attr("route-attr");
        element = $(this);
        siblingElement = $(this).siblings()[0];
        $.ajax({
                url:route,
                method:"POST",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success:function (){
                    $(siblingElement).attr("hidden",false);
                    $(siblingElement).attr("disabled",false);
                    element.attr("hidden",true);
                    element.attr("disabled",true);
                },
                error:function (e){
                    alert("error!!");
                }
            }
        );
    });

    // to update pound using ajax
    // $("input#btn_update").on("click",function(){
    //     var form = $("#form_update");
    //     let edit_element = null;
    //     $("a#btn_update").each(function (){
    //     if ($(this).data("fields")["name"] == form.children("div").children("#name").val())
    //         edit_element = this;
    //     })
    //     var route = form.attr('action');
    //     $.ajax({
    //             url:route,
    //             method:"POST",
    //             data: form.serialize(),
    //             headers: {
    //                 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    //             },
    //             success:function (e){
    //                 $(edit_element).data("fields")["value"] = form.children("div").children("#value").val();
    //                 $(".modal-header .close").click();
    //                 $.ajax({
    //                     url: "http://"+location.host+"/translate/pound_has_been_updated_succesfully",
    //                     success:function (e) {
    //                         toastr.success(e);
    //                     },
    //                 });
    //             },
    //             error:function (e){
    //                 $.ajax({
    //                     url: "http://"+location.host+"/translate/pound_has_not_been_updated_succesfully",
    //                     success:function (e) {
    //                         toastr.error(e);
    //                     },
    //                 });
    //             },
    //         }
    //     );
    // });

    // to add product or account using ajax
    // $("input#btn_add").on("click",function(){
    //     let form = $(this).parent(".form-group").parent(".modal-footer").siblings(".modal-body").children("form");
    //     var route = form.attr('action');
    //     $.ajax({
    //             url:route,
    //             method:"POST",
    //             data: form.serialize(),
    //             headers: {
    //                 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    //             },
    //             success:function (e){
    //                 $(".modal-header .close").click();
    //                 Toast.fire({
    //                     icon: 'success',
    //                     title: e
    //                 });
    //             },
    //             error:function (e){
    //                 Toast.fire({
    //                     icon: 'error',
    //                     title: e.responseJSON.message
    //                 });
    //             }
    //         }
    //     );
    // });


    // auto calc the total price when quantity or price is changed
    $("#price,#quantity").on("keyup",function (){
        if ($("#quantity").val() != "" && $("#price").val() != ""){
            let total_price = parseFloat($("#quantity").val()) * parseFloat($("#price").val())
            $("#total_price").val(total_price);
        }
    });

    // to filter the menu and copy its value when enter key is pressed
    $("input[class~='dropdown-toggle']").on("keyup", filter);
    $("input[class~='dropdown-toggle']").on("focus", filter);
    function filter(e) {
        const MOBILE_ENTER = 13;
        const PC_ENTER = 13;
        // TODO : see the ascci code for enter in mobile keyboard
        if (e.which == PC_ENTER || e.which == MOBILE_ENTER){
            e.preventDefault();

            let options = $(this).siblings("div#dropdown_menu.dropdown-menu").children("option");
            for (const option in options) {
                if (!Number(options[option]) && options[option].style != undefined && options[option].style.display != "none") {
                    $(options[option]).click();
                    return;
                }
            }
        }
        let value = $(this).val().toLowerCase();
        let options = $(this).siblings().children().filter("option");
        options.each(function() {
            let isDesired = intersection(value,$(this).text().toLowerCase()) || value == $(this).val();
            $(this).toggle(isDesired);
        });
    }

    // to copy the option value into its input
    $("div option").on("click",function (){
        // $(this).parent().siblings().filter("input").val($(this).text()).data("correct",true);
        $(this).parent().siblings().filter("input").val($(this).text());
        $(this).parent().filter("div#dropdown_menu.dropdown-menu").removeClass("show");
        // alert();
    });

    // to add the class show into the menu when focus
    $("input[class~='dropdown-toggle").on("focus", function(e) {
        $(this).siblings().filter("div#dropdown_menu.dropdown-menu").addClass("show")
    });

    // to remove the class show from the menu when blur
    $("input[class~='dropdown-toggle").on("blur", function(e) {
        let thisItem = $(this);
        let error = validateDropDownBox(this);
        if (error != ""){
            $(this).val("");
        }
        setTimeout(function (){
            thisItem.siblings().filter("div#dropdown_menu.dropdown-menu").removeClass("show")
        },200);
    });

    // see if an intersection is existed
    function intersection(str1,str2){
        let i = 0;
        for (let j=0 ;j<str2.length;j++){
            if (str2[j] == str1[i])
                i++;
        }
        if (i == str1.length)
            return true;
        else
            return false;
    }

    // to move into the next input by pressing enter
    //  $("input,select,textarea,button#btn_add_item_to_invoice").on("keypress",function (e){//to prevent submitting and focus on next input
    //     const MOBILE_ENTER = 13;
    //     const PC_ENTER = 13;
    //     // TODO : see the ascci code for enter in mobile keyboard
    //     if (e.which == PC_ENTER || e.which == MOBILE_ENTER){
    //         e.preventDefault();
    //         // $(this).trigger("keydown", [9]);
    //         let inputs = $("input,select,textarea");
    //
    //         for (let item in inputs){
    //             if (Number(inputs[item])){
    //                 break;
    //             }
    //             if ($(inputs[item]).attr("id") === $(this).attr("id")){
    //                 setTimeout(
    //                     function (){
    //                         if ($(inputs[item]).attr("id") == "notes" && (location.pathname.indexOf("Invoice"))) {
    //                             $("#btn_add_item_to_invoice").click();
    //                             return;
    //                         }
    //                         else if ($(inputs[item]).attr("type") == "submit") {
    //                             $(inputs[item]).click();
    //                             return;
    //                         } else if ($(inputs[parseInt(item)+2]).attr("id") == "payed" && !Number($(inputs[parseInt(item)+2]).val())) {
    //                             $(inputs[parseInt(item) + 1]).focus();
    //                             return;
    //                         } else if ($(inputs[parseInt(item)+1]).attr("id") == "total_price" ||
    //                             ($(inputs[item]).attr("id") == "received" && Number($(inputs[item]).val())) ||
    //                             ($(inputs[parseInt(item)+3]).attr("id") == "payed" && !Number($(inputs[parseInt(item)+3]).val())) ||
    //                             ($(inputs[parseInt(item)+2]).attr("id") == "payed" && Number($(inputs[parseInt(item)+2]).val()))
    //                         ) {
    //                             $(inputs[parseInt(item) + 2]).focus();
    //                             return;
    //                         }
    //                         else if (($(inputs[parseInt(item)+1]).attr("id") == "pound_type" && $(inputs[parseInt(item)+2]).attr("id") =="total_price")||
    //                             ($(inputs[parseInt(item)+3]).attr("id") == "payed" && Number($(inputs[parseInt(item)+3]).val()))
    //                         ) {
    //                             $(inputs[parseInt(item) + 3]).focus();
    //                             return;
    //                         } else{
    //                             $(inputs[parseInt(item)+1]).focus();
    //                             return;
    //                         }
    //                     },140
    //                 );
    //             }
    //         }
    //     }
    // });
    $("input,select,textarea,button#btn_add_item_to_invoice").on("keypress",function (e){
        const MOBILE_ENTER = 13;
        const PC_ENTER = 13;
        // TODO : see the ascci code for enter in mobile keyboard
        if (e.which == PC_ENTER || e.which == MOBILE_ENTER){
            e.preventDefault();

            let currentTabIndex = $(this).attr("tabindex");
            let elem = $(this);
            setTimeout(function(){
                if (elem.attr("type") == "submit" ){
                } else if($("[tabindex="+(parseInt( currentTabIndex)+1)+"]").attr("id") == "btn_add_item_to_invoice"){
                    $("[tabindex="+(parseInt( currentTabIndex)+1)+"]").click();
                } else if ($("[tabindex="+(parseInt( currentTabIndex)+1)+"]").attr("disabled") != "disabled"&&
                    $("[tabindex="+(parseInt( currentTabIndex)+1)+"]").attr("readonly") != "readonly") {
                    $("[tabindex="+(parseInt( currentTabIndex)+1)+"]").focus();
                } else if($("[tabindex="+(parseInt( currentTabIndex)+2)+"]").attr("disabled") != "disabled"&&
                    $("[tabindex="+(parseInt( currentTabIndex)+2)+"]").attr("readonly") != "readonly") {
                    $("[tabindex="+(parseInt( currentTabIndex)+2)+"]").focus();
                } else if($("[tabindex="+(parseInt( currentTabIndex)+3)+"]").attr("disabled") != "disabled"&&
                    $("[tabindex="+(parseInt( currentTabIndex)+3)+"]").attr("readonly") != "readonly") {
                    $("[tabindex="+(parseInt( currentTabIndex)+3)+"]").focus();
                }
            },200);

        }
    });


    // to handel the function key
    $("body").on("keydown",function (e){
        const F1 = 112; // accounts tree
        const F2 = 113; // close the invoice
        const F3 = 114; // search window for invoice
        const F4 = 115; // go to welcome page
        const F5 = 116; // refresh
        const F6 = 117; // view recyclebin

        if (e.which == F1 ) {
            e.preventDefault();
            if ($("a#btn_show_all_accounts_balances").get(0) != undefined) {
                $("a#btn_show_all_accounts_balances").get(0).click();
            } else if ($("a#btn_show_discover_dashboard").get(0) != undefined) {
                $("a#btn_show_discover_dashboard").get(0).click();
            }
        } else if (e.which == F2 ) {
            e.preventDefault();
            if ($("#btn_close_invoice").get(0) != undefined && $("#btn_close_invoice").attr("hidden") != "hidden"){
                $("#btn_close_invoice").get(0).click();
            }
        }
        else if (e.which == F3 ) {
            e.preventDefault();
            if (location.pathname.indexOf("Cash") > -1 && $("#btn_search_edit_delete_cash").get(0) != undefined) {
                $("#btn_search_edit_delete_cash").get(0).click();
            } else if (location.pathname.indexOf("Movement") > -1 && $("#btn_search_edit_delete_product_movement").get(0) != undefined) {
                $("#btn_search_edit_delete_product_movement").get(0).click();
            } else if (location.pathname.indexOf("Manufacturing") > -1 && $("#btn_search_edit_delete_manufacturing_action").get(0) != undefined) {
                $("#btn_search_edit_delete_manufacturing_action").get(0).click();
            } else if (location.pathname.indexOf("Invoice") > -1 && $("#btn_search_edit_delete_invoice").get(0) != undefined) {
                $("#btn_search_edit_delete_invoice").get(0).click();
            }
        }
        else if (e.which == F4 ) {
            e.preventDefault();
            if ($("a#btn_welcome").get(0) != undefined){
                $("a#btn_welcome").get(0).click();
            }
        }
        else if (e.which == F5 ) {
            // e.preventDefault();
        }
        else if (e.which == F6 ) {
            e.preventDefault();
            if (location.pathname.indexOf("Cash") > -1 && $("#btn_cashes_recycle_bin").get(0) != undefined) {
                $("#btn_cashes_recycle_bin").get(0).click();
            } else if (location.pathname.indexOf("Movement") > -1 && $("#btn_product_movement_recycle_bin").get(0) != undefined) {
                $("#btn_product_movement_recycle_bin").get(0).click();
            } else if (location.pathname.indexOf("Invoice") > -1 && $("#btn_invoices_recycle_bin").get(0) != undefined) {
                $("#btn_invoices_recycle_bin").get(0).click();
            }
            // btn_invoices_recycle_bin
            // btn_cashes_recycle_bin
            // btn_product_movement_recycle_bin
        }
    });

    //this is for focus on closing date for invoice
    $("#btn_close_invoice").on("click",function (){
        setTimeout(function (){
            $("#closing_date").get(0).focus();
        },500);
    });

    $("#btn_save_invoice").on("click",function (){
        $('.auto-save').savy('destroy');
        let keys = [
            "custom-savy-"+location.pathname+"-rows",
            "custom-savy-"+location.pathname+"-delete_row_button_status",
        ];
        keys.forEach(function (key){
            if(localStorage.getItem(key)!=null){
                localStorage.removeItem(key)
            }
        });
    });

    // this is for copying the pound_type into the bottom of invoice
    $("#pound_type").on("keyup",function (){
        $("#invoice_pound").text($(this).val());
    });

    // this is to validate the dropdown select menu and see if the typed text is exist in the dropdown menu
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

    // this is to highlight the selected row in the table
    $("tr").on("click",function (){
        if ($(this).children("th").length>0)
            return;
        $(this).addClass("selected");
        $(this).siblings("tr").filter(function (){
            $(this).removeClass("selected");
        });
    });

    // this is to copy the row_id into the input for submitting check point
    $("tr#discover_rows").on("click",function (){
        $("#check_point_row_id").val($(this).children("#row_id").text());
    });

    // this is for moving into show page when tr is dblClicked
    $("tr").on("dblclick",function (){
        if ($(this).children("td").children("a#btn_show_element")[0]==undefined){
            return;
        }
        $(this).children("td").children("a#btn_show_element")[0].click();
    });

    // to set the route for the closing invoice model
    $("#btn_add,#btn_update").on("click",function (){
        let model_id = $(this).data("target");
        setTimeout(function (){
            if (model_id == "#updateModal") {
                $(model_id+" .modal-body form input").get(2).focus();
            } else if (model_id == "#addModal") {
                $(model_id+" .modal-body form input").get(1).focus();
            }
        },500);

    });


    // // this is to handel the back action in the custom back button
    // $("#back_arrow").on("click",function (){
    //     history.back();
    //     // location = document.referrer;
    // });

    // this is to check all checkbox in dataTable rows
    $("input#check_all").on("click",function (){
        if ($(this).is(":checked")){
            $("td input[type='checkbox']").each(function (){
               this.checked = true;
            });
            $("a#btn_multi_delete").removeClass("disable-pointer")
            $("a#btn_multi_restore").removeClass("disable-pointer");
        } else {
            $("td input[type='checkbox']").filter(function (){
                this.checked = false;
            });
            $("a#btn_multi_delete").addClass("disable-pointer")
            $("a#btn_multi_restore").addClass("disable-pointer");
        }
    });

    // this is to make the "check_all" checkbox checked or unchecked when any checkbox in dataTable rows is changed
    $("td input[type='checkbox']").on("click",function (){
        if (this.checked==true) {

            let all_checked = true;
            $(this).parent("td").parent("tr").siblings("tr").children("td").children("input").each(function (){
                if (this.checked == false){
                    all_checked=false;
                }
            });
            if (all_checked){
                $("input#check_all").each(function (){
                    this.checked = true;
                });
            }
            $("a#btn_multi_delete").removeClass("disable-pointer")
            $("a#btn_multi_restore").removeClass("disable-pointer");
        } else {

            let all_not_checked = true;
            $(this).parent("td").parent("tr").siblings("tr").children("td").children("input").each(function (){
                if (this.checked == true){
                    all_not_checked=false;
                }
            });
            if (all_not_checked){

                $("a#btn_multi_delete").addClass("disable-pointer")
                $("a#btn_multi_restore").addClass("disable-pointer");
            }
            $("input#check_all").each(function (){
                this.checked = false;
            });

        }
    });

    // this is to make the head of tables as minimum as fits the content perfectly
    // $("th").each(function () {
    //     if ($(this).children("input").val() == undefined) {
    //         $("#test_size_label").text($(this).text());
    //         let width = $("#test_size_label").css("width");
    //         $("#test_size_label").text("");
    //         $(this).css("min-width", width);
    //     }
    // });

    // // this is to handle the "reload" back button and skip duplicated
    // window.addEventListener("pageshow",function (event){
    //     // var historyTraversal = event.persisted;
    //     var historyTraversal = event.persisted || (typeof window.performance != undefined && window.performance.navigation.type === 2);
    //     if (historyTraversal){
    //         sessionStorage.setItem("custom_back_button_pressed",false);
    //         location.reload();
    //     }
    // });

    // to switch between choose_image and scan_qr
    $("#toggle_qr,#toggle_image").on("click",function (){
       $("#qr_code_container").fadeToggle(0);
       $("#image_container").fadeToggle(0);
       $("#toggle_image").fadeToggle(0);
       $("#toggle_qr").fadeToggle(0);
       if ($("#toggle_image").css("display") == "none"){
           $("#toggle_image").children("input").attr("form","aa");
           $("#toggle_qr").children("input").attr("form","form");
       } else {
           $("#toggle_image").children("input").attr("form","form");
           $("#toggle_qr").children("input").attr("form","aa");
       }
    });

})(jQuery); // End of use strict
