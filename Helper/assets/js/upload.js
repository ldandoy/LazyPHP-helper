$(document).ready(function() {
	if ($("#formUpload").length == 0) {
		var formUpload = document.createElement("form");
		formUpload.id = "formUpload";
		formUpload.className = "formUpload";
		formUpload.method = "post";
		formUpload.action = "/upload";
		formUpload.enctype = "multipart/form-data";

		var input = document.createElement("input");
		input.id = "upload_file";
		input.name = "upload_file";
		input.type = "file";
		$(input).on("change", uploadSubmit);
		formUpload.appendChild(input);

		var input = document.createElement("input");
		input.id = "upload_type";
		input.name = "upload_type";
		input.type = "hidden";
		formUpload.appendChild(input);

		var input = document.createElement("input");
		input.id = "upload_input_name";
		input.name = "upload_input_name";
		input.type = "hidden";
		formUpload.appendChild(input);

		var input = document.createElement("input");
		input.id = "upload_input_id";
		input.name = "upload_input_id";
		input.type = "hidden";
		formUpload.appendChild(input);

		var input = document.createElement("input");
		input.name = "form";
		input.type = "hidden";
		input.value = "formUpload"
		formUpload.appendChild(input);

		document.body.appendChild(formUpload);		
	}

	$(".input-upload-trigger").on("click", uploadFile);
	$(".input-upload-action-del").on("click", uploadDel);
});

function uploadFile(event) {
	var inputUpload = $(event.currentTarget).parents(".input-upload")[0];

	var type = inputUpload.hasAttribute("data-type") ? inputUpload.getAttribute("data-type") : '';
	var inputName = inputUpload.hasAttribute("data-input-name") ? inputUpload.getAttribute("data-input-name") : '';
	var inputId = inputUpload.hasAttribute("data-input-id") ? inputUpload.getAttribute("data-input-id") : '';

	var $formUpload = $("#formUpload");

	$formUpload.find("input[name=upload_type]").val(type);
	$formUpload.find("input[name=upload_input_name]").val(inputName);
	$formUpload.find("input[name=upload_input_id]").val(inputId);

	var inputFile = $(formUpload).find("input[name=upload_file]")[0];
	$(inputFile).trigger("click");

	event.stopPropagation();
	event.preventDefault();
}

function uploadSubmit(event)
{
	showHourglass();

	var formUpload = $("#formUpload")[0];
	var postData = new FormData(formUpload);

	$.ajax({
		url: "/upload",
		method: "post",
		data: postData,
		processData: false,
		contentType: false,
		dataType: 'text',
		success: uploadSuccess,
		error: uploadError
	});
}

function uploadSuccess(data, textStatus, jqXHR)
{
	try {
		var res = JSON.parse(data);

		if(res.error == false)
		{
			var $inputUpload = $("#input_upload_"+res.inputId);

			var thumbnail = $inputUpload.find(".input-upload-thumbnail")[0];
			var d = new Date();
			thumbnail.src = res.url+"?"+d.getTime();

			$("input[name="+res.inputName+"]").val(res.url);

			$inputUpload.removeClass("no-file");
		}
		else
		{
			alert("Erreur :\n"+res.message);
		}
	} finally {
		hideHourglass();
	}
}

function uploadError(jqXHR, textStatus, errorThrown)
{
	alert("Erreur:\n"+textStatus+"\n"+errorThrown);
	hideHourglass();
}

function uploadDel(event) {
	var $inputUpload = $(event.currentTarget).parents(".input-upload");

	var thumbnail = $inputUpload.find(".input-upload-thumbnail")[0];
	thumbnail.src = "";

	$inputUpload.addClass("no-file");
}