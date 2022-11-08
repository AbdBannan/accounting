(function($) {
  "use strict"; // Start of use strict

    // alert(performance.navigation.type);
    // alert(document.referrer);
    // alert(location.pathname);

    if (document.referrer == location.href ){
        // alert("yes");
        // location = document.referrer;
    }
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

    // this is to populate delete confirm model
    $("a#btn_delete").on("click",function(){
        let route = $(this).data("route");
        $(this).siblings("div").children("input[type='checkbox']").attr("checked",true);
        $("#form_delete").attr("action",route);
    });
    // $("#btn_multi_delete").on("click",function(){
    //     let route = $(this).data("route");
    //     $("#form_delete").attr("action",route);
    // });

    // this is to populate restore confirm model
    $("a#btn_restore").on("click",function(){
        let route = $(this).data("route");
        $(this).siblings("div").children("input[type='checkbox']").attr("checked",true);
        $("#form_restore").attr("action",route);
    });

    // $("#btn_multi_restore").on("click",function(){
    //     let route = $(this).data("route");
    //     $("#form_delete").attr("action",route);
    // });

    $("a#btn_update").on("click",function(){ // to populate the update form
        let route = $(this).data("route");
        let fields = $(this).data("fields");
        for (let field in fields){
            if (field == "image"){
                $("#form_update #"+field).attr("src","http://accounting.com/"+fields[field]);
            } else if (field == "account_type" || field == "group" || field == "reference" || field == "store_id"){
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

    $("a#btn_add").on("click",function(){ // to populate the add form
        let route = $(this).data("route");
        $("#form_add").attr("action",route);
    });

    // to toggle the  btn_activate ,btn_attach and btn_track_activity buttons
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
                    alert("e");
                }
            }
        );
    });

    // to toggle the  btn_activate ,btn_attach and btn_track_activity buttons
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

    // auto calc the total price when quantity or price is changed
    $("#price,#quantity").on("change",function (){
        let total_price = parseFloat($("#quantity").val()) * parseFloat($("#price").val())
        $("#total_price").val(total_price);
    });

    // to filter the menu and copy its value when enter key is pressed
    $("input[class~='dropdown-toggle']").on("keyup", function(e) {
        const ENTER = 13;
        e.preventDefault();
        if (e.which == ENTER){

            let options = $(this).siblings().filter("div#dropdown_menu.dropdown-menu").children("option");
            for (const option in options) {
                if (options[option].style.display != "none") {
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
    });

    $("div option").on("click",function (){// to copy the option value into its input
        $(this).parent().siblings().filter("input").val($(this).text()).data("correct",true);
        $(this).parent().filter("div#dropdown_menu.dropdown-menu").removeClass("show")
    });

    $("input[class~='dropdown-toggle").on("focus", function(e) {
        $(this).siblings().filter("div#dropdown_menu.dropdown-menu").addClass("show")// to add the class show into the menu when focus
    });

    $("input[class~='dropdown-toggle").on("blur", function(e) {// to remove the class show from the menu when blur
        let thisItem = $(this);
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
    $("input,select,textarea,button#btn_add_item_to_invoice").on("keypress",function (e){//to prevent submitting and focus on next input
        if (e.keyCode == 13) {
            // $(this).trigger("keydown", [9]);
            e.preventDefault();
            let inputs = $("input,select,textarea");

            for (let item in inputs){
                if (Number(inputs[item])){
                    break;
                }
                if ($(inputs[item]).attr("id") === $(this).attr("id")){
                    setTimeout(
                        function (){
                            if ($(inputs[item]).attr("id") == "notes" && $(inputs[item]).attr("type") != undefined) {
                                $("#btn_add_item_to_invoice").click();
                                return;
                            }
                            else if ($(inputs[item]).attr("type") == "submit") {
                                $(inputs[item]).click();
                                return;
                            }
                            else if ($(inputs[parseInt(item)+1]).attr("id") == "btn_reset" || $(inputs[parseInt(item)+1]).attr("id") == "total_price" || ($(inputs[item]).attr("id") == "payed" && Number($(inputs[item]).val()) )) {
                                $(inputs[parseInt(item) + 2]).focus();
                                return;
                            }
                            else if ($(inputs[parseInt(item)+1]).attr("id") == "pound_type" && $(inputs[parseInt(item)+2]).attr("id") =="total_price") {
                                $(inputs[parseInt(item) + 3]).focus();
                                return;
                            } else if ($(inputs[parseInt(item)+1]).attr("id") == "pound_type" && $(inputs[parseInt(item)+4]).attr("id") =="total_price") {
                                $(inputs[parseInt(item) + 5]).focus();
                                return;
                            }
                            else if ($(inputs[parseInt(item)+1]).attr("id") == "pound_type" && $(inputs[parseInt(item)+2]).attr("id") =="payed") {
                                $(inputs[parseInt(item) + 2]).focus();
                                return;
                            } else if ($(inputs[parseInt(item)+1]).attr("id") == "pound_type" && $(inputs[parseInt(item)+4]).attr("id") =="payed") {
                                $(inputs[parseInt(item) + 4]).focus();
                                return;
                            }
                            else{
                                $(inputs[parseInt(item)+1]).focus();
                                return;
                            }
                        },140
                    );
                }
            }
        }
    });



    $("body").on("keydown",function (e){
        const F1 = 112;
        const F2 = 113;
        const F3 = 114;
        const F4 = 115;
        const F5 = 116;

        if (e.which == F1 ) {
            e.preventDefault();
            $("a#create_invoice").get(0).click();
        }
        else if (e.which == F2 ) {
            e.preventDefault();

        }
        else if (e.which == F3 ) {
            e.preventDefault();

        }
        else if (e.which == F4 ) {
            e.preventDefault();

        }
        else if (e.which == F5 ) {
            e.preventDefault();

        }
    });

    //this is for focus on closing date for invoice
    $("#btn_close_invoice").on("click",function (){
        setTimeout(function (){
            $("#closing_date").get(0).focus();
        },500);
    });

    // this is for copying the pound_type into the bottom of invoice
    $("#pound_type").on("keyup",function (){
        $("#invoice_pound").text($(this).val());
    });

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

    $("tr").on("click",function (){
        if ($(this).children("th").length>0)
            return;
        $(this).addClass("selected");
        $(this).siblings("tr").filter(function (){
            $(this).removeClass("selected");
        });
    });

    $("tr#discover_rows").on("click",function (){// this is to copy the row_id into the input for submitting check point
        $("#check_point_row_id").val($(this).children("#row_id").text());
    });

    // $("#back_arrow").on("click",function (){
    //    $("#back_submit")[0].click();
    // });

    $("tr").on("dblclick",function (){// this is for moving into show page when tr is dblClicked
        if ($(this).children("td").children("a#btn_show_element")[0]==undefined){
            return;
        }
        $(this).children("td").children("a#btn_show_element")[0].click();
    });


    $("#btn_add,#btn_update").on("click",function (){ // to set the route for the closing invoice model
        let model_id = $(this).data("target");
        setTimeout(function (){
            if (model_id == "#updateModal") {
                $(model_id+" .modal-body form input").get(2).focus();
            } else if (model_id == "#addModal") {
                $(model_id+" .modal-body form input").get(1).focus();
            }
        },500);

    });

    $("#back_arrow").on("click",function (){
        // history.back();
        location = document.referrer;
    });


    // $("#check_all").on("click",function (){
    //     if ($(this).is(":checked")){
    //         $("td div input[type='checkbox']").each(function (){
    //            this.checked = true;
    //         });
    //         $(this).siblings("#label_check_none").attr("hidden",false);
    //         $(this).siblings("#label_check_all").attr("hidden",true);
    //         $("#btn_multi_delete").attr("disabled",false);
    //     } else {
    //         $("td div input[type='checkbox']").filter(function (){
    //             this.checked = false;
    //         });
    //         $(this).siblings("#label_check_none").attr("hidden",true);
    //         $(this).siblings("#label_check_all").attr("hidden",false);
    //         $("#btn_multi_delete").attr("disabled",true);
    //     }
    // });

    // $("td div input[type='checkbox']").on("click",function (){
    //     if (this.checked==true) {
    //
    //         let all_checked = true;
    //         $(this).parent("div").parent("td").parent("tr").siblings("tr").children("td").children("div").children("input").each(function (){
    //             if (this.checked == false){
    //                 all_checked=false;
    //             }
    //         });
    //         if (all_checked){
    //             $("input#check_all").each(function (){
    //                 this.checked = true;
    //             });
    //         }
    //         $("#btn_multi_delete").attr("disabled",false);
    //
    //     } else {
    //
    //         let all_not_checked = true;
    //         $(this).parent("div").parent("td").parent("tr").siblings("tr").children("td").children("div").children("input").each(function (){
    //             if (this.checked == true){
    //                 all_not_checked=false;
    //             }
    //         });
    //         if (all_not_checked){
    //             $("#btn_multi_delete").attr("disabled",true);
    //         }
    //         $("input#check_all").each(function (){
    //             this.checked = false;
    //         });
    //
    //     }
    // });
    // $("th").each(function () {
    //     if ($(this).children("input").val() == undefined) {
    //         $("#test_size_label").text($(this).text());
    //         let width = $("#test_size_label").css("width");
    //         $("#test_size_label").text("");
    //         $(this).css("min-width", width);
    //     }
    // });

    // window.addEventListener("pageshow",function (){
    //     alert(performance.navigation.type);
    //     if (performance.navigation.type == 2){
    //         alert("new out");
    //
    //         location.reload();
    //     }
    // });

})(jQuery); // End of use strict
