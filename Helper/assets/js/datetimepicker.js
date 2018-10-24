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

        var step = $element.data("step");
        if (step == null) {
            step = 1;
        }

        var startDate = $element.data("startdate");
        console.log(startDate);
        if (typeof startDate !== undefined) {
            if(startDate == "") {
                startDate = new Date();
                $(element).datetimepicker({
                    lang: 'fr',
                    format: format,
                    datepicker: selectDate,
                    timepicker: selectTime,
                    step: step,
                    defaultTime: "00:00",
                    dayOfWeekStart: 1,
                    startDate: startDate,
                });
            } else {
                startDate = "+1971/05/01";
                $(element).datetimepicker({
                    lang: 'fr',
                    format: format,
                    datepicker: selectDate,
                    timepicker: selectTime,
                    step: step,
                    defaultTime: "00:00",
                    dayOfWeekStart: 1,
                    startDate: startDate,
                    minDate: 0
                });
            }
        } else {
            startDate = new Date();
            $(element).datetimepicker({
                lang: 'fr',
                format: format,
                datepicker: selectDate,
                timepicker: selectTime,
                step: step,
                defaultTime: "00:00",
                dayOfWeekStart: 1,
                startDate: startDate,
            });
        }

        
    });
});
