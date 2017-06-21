var LazyDialog = function(params) {
	this.id = '';
	this.title = '';
	this.actions = {
		load: null,
		close: null,
		cancel: null,
		valid: null
	};
};

$(document).ready(function() {
});

LazyDialog.prototype.open = function(options) {
	var postData = options.postData != null ? options.postData : null;

	if (postData == null) {
		postData = new FormData();
	}

	this.id = options.id != null ? options.id : this.id;
	this.title = options.title != null ? options.title : this.title;
	this.actions = options.actions != null ? options.actions : this.actions;

	if (options.url != "") {
		$.ajax({
			url: options.url,
			method: "post",
			data: postData,
			processData: false,
			contentType: false,
			dataType: 'text',
			success: this.openSuccess,
			error: this.openError,
			context: this
		});
	} else {
		this.openSuccess("", null, null);
	}
}

LazyDialog.prototype.openSuccess = function(data, textStatus, jqXHR) {
	var validActionButton = '';
	if (this.actions.valid != null) {
		validActionButton = '<button class="btn btn-success lazy-dialog-action" data-action="valid"><i class="fa fa-check"></i>&nbsp;OK</button>';
	}

	var cancelActionButton = '';
	if (this.actions.cancel != null) {
		cancelActionButton = '<button class="btn btn-default lazy-dialog-action" data-action="cancel"><i class="fa fa-remove"></i>&nbsp;Annuler</button>';
	}

	$html = 
		'<div id="'+this.id+'" class="lazy-dialog lazy-dialog-fullscreen" tabindex="1">'+
			'<div class="lazy-dialog-container">'+
				'<div class="lazy-dialog-header">'+
					'<h2 class="lazy-dialog-title">'+this.title+'</h2>'+
					'<div class="lazy-dialog-close-button lazy-dialog-action" data-action="close"><i class="fa fa-remove fa-2x"></i></div>'+
				'</div>'+
				'<div class="lazy-dialog-body">'+
					data+
				'</div>'+
				'<div class="lazy-dialog-footer">'+
					'<div class="lazy-dialog-buttons">'+
						validActionButton+cancelActionButton
					'</div>'+
				'</div>'+
			'</div>'+
		'</div>';

	$("#"+this.id).remove();
	
	$("body").append($html);

	$(".lazy-dialog-action").on("click", {lazyDialog: this}, this.actionClick);

	$(".lazy-dialog").on("keydown", {lazyDialog: this}, this.keydown);
	$(".lazy-dialog").focus();

	if (this.actions.load != null) {
		this.actions.load();
	}
}

LazyDialog.prototype.openError = function(jqXHR, textStatus, errorThrown)
{
	console.log(textStatus, errorThrown);
}

LazyDialog.prototype.doAction = function(action)
{
	var i = 0;
	var res = true;
	var $dialog = $("#"+this.id);

	switch (action) {
		case "cancel":
			if (this.actions.cancel == null || this.actions.cancel()) {
				$dialog.remove();
			} else {
				if (Array.isArray(this.actions.cancel)) {
					for (i = 0; i < this.actions.cancel.length; i = i +1) {
						if (!this.actions.cancel[i]()) {
							res = false;
							break;
						}
					}
				} else {
					res = this.actions.cancel();
				}
				if (res) {
					$dialog.remove();
				}
			}
			break;

		case "close":
			if (this.actions.close == null || this.actions.close()) {
				$dialog.remove();
			} else {
				if (Array.isArray(this.actions.close)) {
					for (i = 0; i < this.actions.close.length; i = i +1) {
						if (!this.actions.close[i]()) {
							res = false;
							break;
						}
					}
				} else {
					res = this.actions.close();
				}
				if (res) {
					$dialog.remove();
				}
			}
			break;

		case "valid":
			if (this.actions.valid == null) {
				$dialog.remove();
			} else {
				if (Array.isArray(this.actions.valid)) {
					for (i = 0; i < this.actions.valid.length; i = i +1) {
						if (!this.actions.valid[i]()) {
							res = false;
							break;
						}
					}
				} else {
					res = this.actions.valid();
				}
				if (res) {
					$dialog.remove();
				}
			}
			break;
	}
}

LazyDialog.prototype.actionClick = function(event)
{
	var $target = $(event.currentTarget);
	var action = $target.data("action");
	event.data.lazyDialog.doAction(action);
}

LazyDialog.prototype.keydown = function(event)
{
	switch (event.which) {
		case 27:
			event.data.lazyDialog.doAction("close");
			break;
	}
}