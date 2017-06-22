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

	$(".input-upload-trigger").on("click", upload);
});

function upload(event) {
	var inputUploadTrigger = $(event.currentTarget)[0];

	var type = inputUploadTrigger.hasAttribute("data-type") ? inputUploadTrigger.getAttribute("data-type") : '';
	var inputName = inputUploadTrigger.hasAttribute("data-input-name") ? inputUploadTrigger.getAttribute("data-input-name") : '';
	var inputId = inputUploadTrigger.hasAttribute("data-input-id") ? inputUploadTrigger.getAttribute("data-input-id") : '';

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
	var res = JSON.parse(data);

	console.log(res);
	if(res.error == false)
	{
		var element = $("#"+res.id)[0];

		var input = $(element).find("input[name="+res.name+"]")[0];
		$(input).val(res.file);

		if(res.type == "image")
		{
			var image = $(element).find("img")[0];
			image.src = "/uploads/tmp/"+res.file;
		}
	}
	else
	{
		alert("Erreur :\n"+res.error);
	}

	hideHourglass();
}

function uploadError(jqXHR, textStatus, errorThrown)
{
	alert("Erreur:\n"+textStatus+"\n"+errorThrown);
	hideHourglass();
}