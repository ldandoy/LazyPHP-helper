$(document).ready(function() {
	$(".magicsuggest").each(function(index, input) {
		var $input = $(input);
		var name = $input.data("name");
		var options =  JSON.parse(decodeURIComponent($input.data("options")));
		var valueField = $input.data("valueField");
		var displayField = $input.data("displayField");
		var selectionContainer = document.createElement("div");
		$input.after(selectionContainer);
		$(input).magicSuggest({
			data: options,
			valueField: valueField,
			displayField: displayField,
			name: name,
			placeholder: "Saisissez ou cliquer ici",
			allowFreeEntries: false,
			maxSelection: null,
			selectionContainer: $(selectionContainer)
		});
	});
});