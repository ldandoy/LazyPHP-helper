$(document).ready(function() {
	/*$(".datetimepicker").each(function(index, element) {
		rome(element, {

		});
	});*/

    jQuery.datetimepicker.setLocale('fr');
    $('.datetimepicker').datetimepicker({
        format:'Y.m.d H:i:s',
        lang:'fr'
    });
});
