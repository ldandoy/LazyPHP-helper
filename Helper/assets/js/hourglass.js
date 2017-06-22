function showHourglass()
{
	var $hourglass = $("#hourglass");

	if ($hourglass.length == 0) {
		var hourglass = document.createElement("div");
		hourglass.id = "hourglass";
		hourglass.innerHTML = '<i class="fa fa-spinner fa-spin"></i>';
		$("body").append(hourglass);

		$hourglass = $(hourglass);
	}

	$hourglass.show();
}

function hideHourglass()
{
	$("#hourglass").hide();
}