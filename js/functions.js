function ajaxFileUpload() {
	jQuery("#loading")
	.ajaxStart(function(){
		jQuery(this).show();
	})
	.ajaxComplete(function(){
		jQuery(this).hide();
	});

	jQuery.ajaxFileUpload({
		/*	url: SITE_URL+"/wp-admin/admin-ajax.php",*/
		url:TEMPLATE_URL+'/doajaxfileupload.php',
		secureuri:false,
		fileElementId:'fileToUpload',
		dataType: 'json',
		data:{name:'logan', id:'id'},
		success: function (data, status) {
			var modal_photo = jQuery("div#uploadPhoto");
			var result_status = modal_photo.find("span.status");

			var form = jQuery("form#savePost");
			var control_group = form.find("div.control-group");
			var _url_photo = form.find("input#url_photo");

			if(typeof(data.error) != 'undefined') {
				if(data.error != '') {
					result_status.removeClass("hide").addClass("label-warning").html(data.error);
				} else {
					result_status.removeClass("hide").addClass("label-success").html("Enviado com sucesso");

					_url_photo.val(data.url);

					if(_url_photo.val() != "") {
						control_group.hide();
					}
					setTimeout(function(){
						modal_photo.modal("hide");
					}, 1000);
				}
			}
		},
		error: function (data, status, e) {
			alert(e);
		}
	}
	)
	return false;
}

jQuery(function(){

	var form, choice_media, modal_photo, modal_videos;

	// contador de caracteres
	// @number
	var counterWords = function() {
		var count = "300";
		var txt = document.save_post.post_content.value;
		var len = txt.length;
		if(len > count){
			txt = txt.substring(0,count);
			document.save_post.post_content.value = txt;
			return false;
		}
		document.save_post.counter.value = count-len;
	}

	// pega o valor 
	var getValueAttach = function(value) {
		var url_media = form.find("input#url_media");
		url_media.val(value);
	}

	//	some todas as opcoes de envio de arquivos
	var hideAttachment = function(){
		var _value_attach = form.find("input#url_media").val();
		var control_group = form.find("div.control-group");

		if(_value_attach !== "") {
			control_group.hide();
			return true
		} else {
			return false;
		}
	}

	jQuery("textarea#post_content").keyup(function(){
		counterWords();
	});

	form = jQuery("form#savePost");
	choice_media = form.find("input[type=radio]"); // pega os radios

	modal_photo = jQuery("div#uploadPhoto");
	modal_video = jQuery("div#videoYouTube");


	//	exibe a caixa de anexo
	choice_media.click(function(){
		var self = jQuery(this +":checked");
		var _value = self.val();

		if(_value === "photo") {
			modal_photo.modal("show");
			modal_video.modal("hide");
		} else {
			modal_video.modal("show");
			modal_photo.modal("hide");
		}
	});

	//	salva os link do youtube
	var btn_saveVideo = modal_video.find("a.save-video");
	btn_saveVideo.click(function(evt){
		var self = jQuery(this);
		var _value = self.parent().find("input#attach-video").val();

		if(_value == "") {
			console.log("Digite a url");
		} else {

			getValueAttach(_value);
			modal_video.modal("hide");	
			hideAttachment();

		}
		evt.preventDefault();
		evt.stopPropagation();
	});

	// envia os dados para o wordpress
	form.submit(function(evt){
		var fb_avatar, fb_username, fb_name, fb_email, _user_name, _user_email, _title, _content, _media, _media_url, _photo, _photo_url, _username_value, _name_value, _email_value, label, post_success;


		// dados do usuario caso venha do facebook
		fb_avatar = jQuery("input#avatar").val();
		fb_username = jQuery("input#fb_username").val();
		fb_name = jQuery("input#fb_name").val();
		fb_email = jQuery("input#fb_email").val();

		// dados do usuario se ele nao usar o facebook
		_user_name = jQuery("input#user_name").val();
		_user_email = jQuery("input#user_email").val();

		// sao enviadas por ajax
		_avatar_url = (fb_avatar === undefined) ? _avatar_url = "" : fb_avatar;

		_username_value = (fb_username === undefined) ? _username_value = "" : fb_username;

		_name_value = (fb_name === undefined) ? _name_value = _user_name : fb_name;
		_email_value = (fb_email === undefined) ? _email_value = _user_email : fb_email;

		_title = jQuery("input#post_title").val();
		_content = jQuery("textarea#post_content").val();
		_media = jQuery("input#url_media").val();
		_photo = jQuery("input#url_photo").val();

		_media_url = (_media === undefined) ? _media_url = "" : _media;
		_photo_url = (_photo === undefined) ? _photo_url = "" : _photo;

		label = jQuery(this).find("span.badge");

		post_success = jQuery("div.success-post");

		var before_success = jQuery("div.sending-post");

		jQuery.ajax({
			type: "POST",
			url: SITE_URL+"/wp-admin/admin-ajax.php",
			dataType: 'JSON',
			data: {
				action: "post_insert",
				avatar: _avatar_url,
				username: _username_value,
				name: _name_value,
				email: _email_value,
				title: _title,
				content: _content,
				photo: _photo_url,
				media: _media_url
			},
			beforeSend: function(){
				before_success.fadeIn();
			},
			success: function(data) {

				//console.log(data);

				if(data.status === 1) {

					jQuery(this).remove();
					before_success.fadeOut();
					post_success.fadeIn(1000).append("<p>"+data.message+"</p>");

					setTimeout(function(){
						window.location.reload();
					}, 5000);

				} else {

					before_success.fadeOut("fast", function(){
						label.fadeToggle().addClass("badge-important").html(data.message);
					});

				
					setTimeout(function(){
						label.fadeOut("fast").addClass("hide");
					}, 5000);
					
				}
			},
			error: function(status, txtStatus, data) {
				label.removeClass("hide").addClass("badge-warning").html(data.message);
			}
		});
		evt.preventDefault();
	});

})