(function($) {

    $.fn.custom_round = function (value,num_of_floating_point){
        if (typeof value == "string")
            value =  parseFloat(value);
        let power_number = Math.pow(10,num_of_floating_point);
        value = Math.round(value * power_number) / power_number;
        return value;
    }

})(jQuery);
