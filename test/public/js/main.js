var init = function() {
	$('form#suggestForm').submit(function() {
		$.ajax({
			type:'POST', 
			url: $('form#suggestForm').attr('action'), 
			data:$('form#suggestForm').serialize(), 
			success: function(response) {
				$('#form-holder').html(response);
				init();
		    }
		});	
		return false;
	});
}
init();