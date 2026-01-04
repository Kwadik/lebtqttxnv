$(() => {
	console.log('script started');

	const body = $('body');

	function showPreloader() {
		body.addClass('ajax-loading');
	}

	function removePreloader() {
		body.removeClass('ajax-loading');
	}

	$(document).on('pjax:send', function() {
		showPreloader();
	})
	$(document).on('pjax:complete', function() {
		removePreloader();
	})

	/* Форма создания поста */

	var $form = $('#post-create-form');
	$form.on('beforeSubmit', function() {

		let formData = new FormData($form[0]);

		showPreloader();
		$.ajax({
			url         : $form.attr('action'),
			type        : 'POST',
			data        : formData,
			cache       : false,
			dataType    : 'json',
			processData : false,
			contentType : false,
			success     : function(data){
				console.log(data);
				let modal = $('#modal-main');
				if(data.success){
					$([
						'[name="PostCreateForm[author_name]"]',
						'[name="PostCreateForm[author_email]"]',
						'[name="PostCreateForm[content]"]',
						'[name="PostCreateForm[imageFile]"]',
						'[name="PostCreateForm[captcha]"]',
					].join(', ')).val('');
					$.pjax.reload({
						timeout: 5000,
						container: '#post-list-pjax',
						url: '/post/list',
						replace: false,
						push: false,
						scrollTo: false
					});
					if (data.message) {
						modal.find('.modal-main__message').html(data.message);
						modal.modal('show');
					}
				}else{
					if (data.message) {
						modal.find('.modal-main__message').html(data.message);
						modal.modal('show');
					}
				}
			},
			error: function( jqXHR, status, errorThrown ){
				console.log( 'ОШИБКА AJAX запроса: ' + status, jqXHR );
			},
			complete: function( jqXHR, status, errorThrown ){
				removePreloader();
				$('#post-create-captcha').yiiCaptcha("refresh");
			}
		});

		return false; // prevent default submit
	});

	/* ###Форма создания поста */
});
