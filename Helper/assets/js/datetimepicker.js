$(document).ready(function() {
	/*$(".datetimepicker").each(function(index, element) {
		rome(element, {

		});
	});*/

    jQuery.datetimepicker.setLocale('fr');
    $('.datetimepicker').datetimepicker({
        format:'d.m.Y H:i',
        lang:'fr'
    });
});
