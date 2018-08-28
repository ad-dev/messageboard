$('document').ready(function() {
	var pageClickFunc = function (e) {
		$.ajax({
			url:'ajax.php?page=' + $(this).data('page'),
			type: 'GET',
			dataType: 'JSON',
			data: {},
			beforeSend: function(x) {
			},
			success: function(x) {
				$('#messagesLayer').html(x.messages);

				// rebinding after messages list reload
				$('#pages a').click(pageClickFunc);
			},
		});
		e.preventDefault();
	};
	
	$('#msg_form_submit_btn').click(function (e) {
		$.ajax({
			url:'ajax.php?action=save',
			type: 'POST',
			dataType: 'JSON',
			data: {
				fullname: $('#fullname').val(),
				birthdate: $('#birthdate').val(),
				email: $('#email').val(),
				message: encodeURIComponent($('#message').val())
			},
			beforeSend: function(x) {
				$(this).hide();
				$('form input').prop('disabled', true);
				$('.ajax-loader').show();
			},
			success: function(x) {
				$(this).show();
				$('.ajax-loader').hide();
				$('form input').prop('disabled', false);
				$('form p.err').removeClass('err');
				if (typeof (x.errors) != 'undefined') {
					$.each(x.errors, function(k, v) {
						$('#' + k).parent().addClass('err');
					});
				} else {
					// clears inputs after success
					$('form .user_input').val('');
				}
				$('#messagesLayer').html(x.messages);
				// rebinding after messages list reload
				$('#pages a').click(pageClickFunc);
			},
		});
		e.preventDefault();
	});
	
	$('#pages a').click(pageClickFunc);
});