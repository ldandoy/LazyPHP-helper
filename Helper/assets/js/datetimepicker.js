$(document).ready(function() {
    jQuery.datetimepicker.setLocale('fr');

    $('.datetimepicker').each(function(index, element) {
        var $element = $(element);

        var selectDate = $element.data("selectDate");
        if (selectDate == null) {
            selectDate = true;
        } else {
            selectDate = selectDate == 1;
        }

        var selectTime = $element.data("selectTime");
        if (selectTime == null) {
            selectTime = true;
        } else {
            selectTime = selectTime == 1;
        }

        var format = $element.data("format");
        if (format == null) {
            format = "Y-m-d H:i:00";
        }

        $(element).datetimepicker({
            lang: 'fr',
            format: format,
            datepicker: selectDate,
            timepicker: selectTime,
            step: 1,
            defaultTime: "00:00"
         });
    });
});
