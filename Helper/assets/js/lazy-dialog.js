var lazyDialog = {
	id: "",
	title: "",
	actions: {
		load: null,
		close: null,
		cancel: null,
		valid: null
	}
};

$(document).ready(function() {
});

function lazyDialogOpen(options)
{
	var postData = options.postData != null ? options.postData : null;

	lazyDialog.id = options.id != null ? options.id : '';
	lazyDialog.title = options.title != null ? options.title : '';
	lazyDialog.actions = options.actions != null ? options.actions : null;

	$.ajax({
		url: options.url,
		method: "post",
		data: postData,
		processData: false,
		contentType: false,
		dataType: 'text',
		success: lazyDialogOpenSuccess,
		error: lazyDialogOpenError
	});
}

function lazyDialogOpenSuccess(data, textStatus, jqXHR)
{
	var validActionButton = '';
	if (lazyDialog.actions.valid != null) {
		validActionButton = '<button class="btn btn-success lazy-dialog-action" data-action="valid"><i class="fa fa-check"></i>&nbsp;OK</button>';
	}

	var cancelActionButton = '';
	if (lazyDialog.actions.cancel != null) {
		cancelActionButton = '<button class="btn btn-default lazy-dialog-action" data-action="cancel"><i class="fa fa-check"></i>&nbsp;Annuler</button>';
	}

	$html = 
		'<div id="'+lazyDialog.id+'" class="lazy-dialog lazy-dialog-fullscreen">'+
			'<div class="lazy-dialog-container">'+
				'<div class="lazy-dialog-header">'+
					'<h2 class="lazy-dialog-title">'+lazyDialog.title+'</h2>'+
					'<div class="lazy-dialog-close-button lazy-dialog-action" data-action="close"><i class="fa fa-remove fa-2x"></i></div>'+
				'</div>'+
				'<div class="lazy-dialog-body">'+
					data+
				'</div>'+
				'<div class="lazy-dialog-footer">'+
					'<div class="lazy-dialog-buttons">'+
						cancelActionButton+validActionButton+
					'</div>'+
				'</div>'+
			'</div>'+
		'</div>';

	$(".lazy-dialog").remove();
	
	$("body").append($html);

	$(".lazy-dialog-action").on("click", lazyDialogActionClick);
	$(".lazy-dialog").on("keydown", lazyDialogKeydown);

	if (lazyDialog.actions.load != null) {
		lazyDialog.actions.load();
	}
}

function lazyDialogOpenError(jqXHR, textStatus, errorThrown)
{
	console.log(textStatus, errorThrown);
}

function lazyDialogDo(action)
{
	var i = 0;
	var res = true;
	var $dialog = $(".lazy-dialog");

	switch (action) {
		case "cancel":
			if (lazyDialog.actions.cancel == null || lazyDialog.actions.cancel()) {
				$dialog.remove();
			}
			break;

		case "close":
			if (lazyDialog.actions.close == null || lazyDialog.actions.close()) {
				$dialog.remove();
			}
			break;

		case "valid":
			if (lazyDialog.actions.valid == null) {
				$dialog.remove();
			} else {
				if (Array.isArray(lazyDialog.actions.valid)) {
					for (i = 0; i < lazyDialog.actions.valid.length; i = i +1) {
						if (!lazyDialog.actions.valid[i]()) {
							res = false;
							break;
						}
					}
				} else {
					res = lazyDialog.actions.valid();
				}
				if (res) {
					$dialog.remove();
				}
			}
			break;
	}
}

function lazyDialogActionClick(event)
{
	var $target = $(event.currentTarget);
	var action = $target.data("action");
	lazyDialogDo(action);
}

function lazyDialogKeydown(event)
{
	console.log(event);
}