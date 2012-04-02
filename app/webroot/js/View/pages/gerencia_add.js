(function($){
	// var loadercontent = $('#loader-content');
	var webroot = $('#webroot').val();
	var ABS_PATH = $('#ABS_PATH').val();
	var SessionID = $('#SessionID').val();
	var working = false;
	var AnexosAdicionados = 0;


	var configContent = {
		toolbar: 'Geral',
		// extraPlugins : 'uicolor',
		filebrowserBrowseUrl 		: webroot+'js/ckeditor/ckfinder/ckfinder.html',
		filebrowserImageBrowseUrl 	: webroot+'js/ckeditor/ckfinder/ckfinder.html?type=Images',
		filebrowserFlashBrowseUrl 	: webroot+'js/ckeditor/ckfinder/ckfinder.html?type=Flash',
		filebrowserUploadUrl 		: webroot+'js/ckeditor/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Files',
		filebrowserImageUploadUrl 	: webroot+'js/ckeditor/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Images',
		filebrowserFlashUploadUrl 	: webroot+'js/ckeditor/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Flash'
	};

	$('#NewslettersqueueEmailbody').ckeditor(configContent);

	/* Chosen Select Box Plugin */
	/*if($.fn.chosen) {
		$('select.chzn-select').chosen();
	}*/
	
	/*var sufixoObjGal = '-attach';
	$('#uploadify'+sufixoObjGal).uploadify({
		'uploader'			: webroot+'js/plugins/uploadify/uploadify.swf',
		'script'			: webroot+'uploads/tempUploadFile/' + SessionID,
		'cancelImg'			: webroot+'css/plugins/uploadify/cancel.png',
		'folder'			: webroot+'files/tmp/',
		'queueID'			: 'fileQueue'+sufixoObjGal,
		'queueSizeLimit'	: 10,
		'auto'				: true,
		'multi'				: true,
		'fileDesc'			: 'Todos os arquivos',
		'fileExt'			:  '*.*',
		// 'sizeLimit'			: 1048576, // Allow a maximum of 1 MB per file
		'buttonText'		: 'Adicionar',
		'onError'			: function(event,queueID,fileObj,errorObj){
			if (errorObj.type === 'File Size'){
				$('#uploadify'+queueID).addClass('uploadifyError');
				$('#uploadify'+queueID+' .percentage').text(' - '+'Extension: '+ fileObj.type+', Erro: '+errorObj.info);
			}
		},
		'onComplete'		: function(event,queueID,fileObj,response,data){
			var json = eval('(' + response + ')'); // converte a  string para objeto Json
			
			if(json.status == 'ok'){
				AnexosAdicionados++;
				$('#uploadify'+queueID).addClass('uploadifySucess');
				$('#uploadify'+queueID+' .percentage').text(' - Enviado com sucesso');

				var classOddEven = (AnexosAdicionados % 2 == 0) ? ' odd' : ' even';
				// Inclui o anexo na lista
				$('#ListFiles tbody').append( '<tr class="gradeA '+classOddEven+'"> <td> <img src="'+ webroot +'css/admin/icons/32/attach.png" alt="" width="32" /> <span class="filename"> '+ json.file +' </span> <input type="hidden" name="data[Page][tempattach]['+ AnexosAdicionados +'][file]" value="'+ json.file +'" /> <input type="hidden" name="data[Page][tempattach]['+ AnexosAdicionados +'][file_default]" value="" /> </td> <td> <span class="filelink"> '+ ABS_PATH +'files/page/'+ PageCodigo +'/'+ json.file +' </span> </td> <td> <ul class="mws-form-list inline"><li> <input type="checkbox" name="data[Page][tempattach]['+ AnexosAdicionados +'][remove]" id="tempattach'+ AnexosAdicionados +'" value="'+ AnexosAdicionados +'"> <label for="tempattach'+ AnexosAdicionados +'">Remover</label> </li></ul> </td> </tr>' );
				$('#AnexosAdicionados').val(AnexosAdicionados);

				if( $('#ListFiles').hasClass('hidden') ){ $('#ListFiles').removeClass('hidden'); }
			}else if(json.status == 'erro'){
				$('#uploadify'+queueID).addClass('uploadifyError');
				$('#uploadify'+queueID+' .percentage').text(' - '+json.msg);
				return false;
			}
		}
	});*/
/*end uploadfy*/
	
})(jQuery);