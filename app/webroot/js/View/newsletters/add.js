(function($){
	var webroot  = $('#webroot').val();
	var ABS_PATH = $('#ABS_PATH').val();


	$("#NewsletterDateSend").datetimepicker({ 
		minDate: 0, 
		maxDate: "+1M +10D",
		dateFormat : 'dd/mm/yy'
	});

	var configContent = {
		toolbar: 'Geral',
		filebrowserBrowseUrl 		: webroot+'js/ckeditor/ckfinder/ckfinder.html',
		filebrowserImageBrowseUrl 	: webroot+'js/ckeditor/ckfinder/ckfinder.html?type=Images',
		filebrowserImageUploadUrl 	: webroot+'js/ckeditor/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Images',
		width : '700'
		
	};

	$('#NewsletterEmailbody').ckeditor(configContent);
	
	/* Chosen Select Box Plugin */
	if($.fn.chosen) {
		$('select.chzn-select').chosen();
	}

	
})(jQuery);