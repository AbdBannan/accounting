(function($) {

    let day = "01";
    let month = "01";
    let year = "2001";
    let date = new Date();
    $.fn.buildDateInput = function (param) {
        let classes = "";
        let id = "";
        let tabindex = -1;
        let focusElemAfterFinish = "";
        let initialDate = "";
        let autofocus = "";
        let form = "";
        if (param!=undefined){
            if (param.classes != undefined){
                classes = param.classes;
            }
            if (param.id != undefined){
                id = param.id;
            }
            if (param.tabindex != undefined){
                tabindex = param.tabindex;
            }
            if (param.focusElemAfterFinish != undefined){
                focusElemAfterFinish = param.focusElemAfterFinish;
            }
            if (param.initialDate != undefined){
                initialDate = param.initialDate;
                date = new Date(initialDate);
                if (date != "Invalid Date"){
                    let splited = initialDate.split("-");
                    day = splited[2];
                    month = splited[1];
                    year = splited[0];
                }
            }
            if (param.autofocus != undefined && param.autofocus == true){
                autofocus = "autofocus";
            }
            if (param.form != undefined){
                form = param.form;
            }

        }
        let dateBody =
            `
            <div id="container" style="position: relative">
                <div style="position: absolute;background-color: white;top:4px;left: 4px;direction: ltr;!important;">
                    <input id="day" tabindex="`+tabindex+`" class="field" type="text" class="` + classes + `" placeholder="dd">
                    <span>/</span>
                    <input id="month" class="field" type="text" class="` + classes + `" placeholder="mm">
                    <span>/</span>
                    <input id="year" class="field" type="text" style="width: 40px" class="` + classes + `" placeholder="yyyy">
                </div>
                <input id="` + id + `" name="` + id + `" tabindex="`+tabindex+`" type="date" class="` + classes + `" value="`+initialDate+`" form="`+form+`" `+autofocus+`>

            </div>
            `;
        $(this).append(dateBody);


        $("#"+id).siblings("div").children("input#day").on("keypress",checkDay);
        $("#"+id).siblings("div").children("input#month").on("keypress", checkMonth);
        $("#"+id).siblings("div").children("input#year").on("keypress",checkYear);
        $("#"+id).siblings("div").children(".field").on("click",moveCursorToLastIndex);
        $("#"+id).siblings("div").children(".field").on("focus",moveCursorToLastIndex);
        // $("#"+id).siblings("div").children(".field").on("keyup",moveCursorToLastIndex);
        function moveCursorToLastIndex() {
            let length = $(this).val().length ;
            let elem = $(this);
            // alert(length);
            setTimeout(function (){
                elem[0].setSelectionRange(0,length);
            });
        }
        $("#container #"+id).on("change", function () {
            let splited = $(this).val().split("-");
            $("#"+id).siblings("div").children("input#day").val(splited[2]);
            $("#"+id).siblings("div").children("input#month").val(splited[1]);
            $("#"+id).siblings("div").children("input#year").val(splited[0]);
        });
        $("#container #"+id).on("focus", function () {
            $("#"+id).siblings("div").children("input#day").focus();
        });
        $("#container #"+id).change();
        function checkDay(e) {
            let elem = $(this);
            if (e.which == 13) {
                e.preventDefault();
                $("#"+id).siblings("div").children("input#month").focus();
                return;
            }
            if (Number(e.key).toString() == "NaN") {
                setTimeout(function () {
                    elem.val(day);
                }, 1);
                return;
            }
            let val = $(this).val();
            day = val + e.key;
            if (day.length == 1) {
                day = "0".concat(day);
            } else if (day[0] == "0") {
                day = day.substr(1);
            }
            // date = new Date(year + "/" + month + "/" + day);
            $("#container #"+id).val(year + "-" + month + "-" + day);
            let result = $("#container #"+id).val();
            // if (date == "Invalid Date") {
            if (result == "") {
                day = "0" + e.key;
                if (day == "00") {
                    day = "01";
                }
                $("#container #"+id).val(year + "-" + month + "-" + day);
                let result = $("#container #"+id).val();
                if (result == ""){
                    day = val;
                    $("#container #"+id).val(year + "-" + month + "-" + day);
                }
                console.clear();
            }
            setTimeout(function () {
                elem.val(day);
            }, 10);
        }
        function checkMonth(e) {
            let elem = $(this);
            if (e.which == 13) {
                e.preventDefault();
                $("#"+id).siblings("div").children("input#year").focus();
                return;
            }
            if (Number(e.key).toString() == "NaN") {
                setTimeout(function () {
                    elem.val(month);
                }, 1);
                return;
            }
            let val = $(this).val();
            month = val + e.key;
            if (month.length == 1) {
                month = "0".concat(month);
            } else if (month[0] == "0") {
                month = month.substr(1);
            }
            // date = new Date(year + "/" + month + "/" + day);
            $("#container #"+id).val(year + "-" + month + "-" + day);
            let result = $("#container #"+id).val();
            // if (date == "Invalid Date") {
            if (result == "") {
                month = "0" + e.key;
                if (month == "00") {
                    month = "01";
                }
                $("#container #"+id).val(year + "-" + month + "-" + day);
                let result = $("#container #"+id).val();
                if (result == ""){
                    month = val;
                    $("#container #"+id).val(year + "-" + month + "-" + day);
                }
                console.clear();
            }
            setTimeout(function () {
                elem.val(month);
            }, 10);
        }
        function checkYear(e) {
            let elem = $(this);
            if (e.which == 13) {
                e.preventDefault();
                $("#"+focusElemAfterFinish).focus();
                return;
            }
            if (Number(e.key).toString() == "NaN") {
                setTimeout(function () {
                    elem.val(year);
                }, 1);
                return;
            }
            let val = $(this).val();
            year = val + e.key;

            if (year[0] == "2") {
                year = year.substr(1);
            }
            while (true) {
                if (year[0] == "0") {
                    year = year.substr(1);
                } else {
                    break;
                }
            }
            if (year.length > 3) {
                year = e.key;
            }
            while (true) {
                if (year.length < 3) {
                    year = "0".concat(year);
                } else {
                    break;
                }
            }
            year = "2" + year;

            // date = new Date(year + "/" + month + "/" + day);
            $("#container #"+id).val(year + "-" + month + "-" + day);
            let result = $("#container #"+id).val();
            // if (date == "Invalid Date") {
            if (result == "") {
                year = "200" + e.key;
                $("#container #"+id).val(year + "-" + month + "-" + day);
                let result = $("#container #"+id).val();
                if (result == ""){
                    year = val;
                    $("#container #"+id).val(year + "-" + month + "-" + day);
                }
                console.clear();
            }
            setTimeout(function () {
                elem.val(year);
            }, 10);
        }

    }



})(jQuery);
