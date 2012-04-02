(function($){
	// var loadercontent = $('#loader-content');
	var webroot = $('#webroot').val();
	var SessionID = $('#SessionID').val();
	var working = false;
	var ImagensAdicionadas = 0;


	var configContent = {
		toolbar: 'Geral',
		extraPlugins : 'uicolor',
		filebrowserBrowseUrl 		: webroot+'js/ckeditor/ckfinder/ckfinder.html',
		filebrowserImageBrowseUrl 	: webroot+'js/ckeditor/ckfinder/ckfinder.html?type=Images',
		filebrowserFlashBrowseUrl 	: webroot+'js/ckeditor/ckfinder/ckfinder.html?type=Flash',
		filebrowserUploadUrl 		: webroot+'js/ckeditor/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Files',
		filebrowserImageUploadUrl 	: webroot+'js/ckeditor/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Images',
		filebrowserFlashUploadUrl 	: webroot+'js/ckeditor/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Flash'
	}, configChamada = {
		toolbar: 'Basico',
		extraPlugins : 'uicolor',
		resize_enabled : false,
		height: '100px',
		MaxLength: 200
	};

	$('#NoticiaConteudo').ckeditor(configContent);
	$('#NoticiaChamada').ckeditor(configChamada);

	/* Chosen Select Box Plugin */
	if($.fn.chosen) {
		$('select.chzn-select').chosen();
	}


	/*$('#fileupload').fileupload({
        dataType: 'json',
        url: webroot+'uploads/tempUpload/' + SessionID,
        done: function (e, data) {
            $.each(data.result, function (index, file) {
                $('<p/>').text(file.name).appendTo(document.body);
            });
        }
    });*/
	

	
	var sufixoObjGal = '-galeria';
	$('#uploadify'+sufixoObjGal).uploadify({
		'uploader'			: webroot+'js/plugins/uploadify/uploadify.swf',
		'script'			: webroot+'uploads/tempUpload/' + SessionID,
		'cancelImg'			: webroot+'css/plugins/uploadify/cancel.png',
		'folder'			: webroot+'files/tmp/',
		'queueID'			: 'fileQueue'+sufixoObjGal,
		'queueSizeLimit'	: 1,
		'auto'				: true,
		'multi'				: false,
		'fileDesc'			: 'Imagens JPEG, GIF , BMP ou PNG',
		'fileExt'			:  '*.jpg;*.jpeg;*.gif;*.bmp;*.png',
		'sizeLimit'			: 1048576, // Allow a maximum of 1 MB per file
		'buttonText'		: 'Adicionar',
		'onError'			: function(event,queueID,fileObj,errorObj){
			if (errorObj.type === 'File Size'){
				$('#uploadify'+queueID).addClass('uploadifyError');
				$('#uploadify'+queueID+' .percentage').text(' - '+'Extension: '+ fileObj.type+', Erro: '+errorObj.info);
			}
		},
		'onComplete'		: function(event,queueID,fileObj,response,data){
			var json = eval('(' + response + ')'); //converte a  string para objeto Json
			
			if(json.status == 'ok'){
				var reqImagem = json.imagem; // mostra o nome da imagem enviada
				var imgpreview = json.preview;
				//var path = webroot.'files/image/produto/';
				//var path = webroot.'files/tmp/';
				ImagensAdicionadas++;
				
				$('#uploadify'+queueID).addClass('uploadifySucess');
				$('#uploadify'+queueID+' .percentage').text(' - Enviado com sucesso');

				// var imgUploaded = '<div> <img class="imagemtemp preview " src="'+ imgpreview +'" alt="" width="132" /> <p> <input type="hidden" class="imagem" name="data[Noticia][tempfoto]['+ ImagensAdicionadas +'][imagem]" value="'+ reqImagem +'" /> <input type="hidden" class="imagem_default" name="data[Noticia][tempfoto]['+ ImagensAdicionadas +'][imagem_default]" value="" /> </p> <p> <input type="checkbox" name="data[Noticia][tempfoto]['+ ImagensAdicionadas +'][remove]" id="tempfoto'+ ImagensAdicionadas +'" value="'+ ImagensAdicionadas +'"> <label for="tempfoto'+ ImagensAdicionadas +'">Remover</label> </p> </div>';

				$('#imgcapa_preview').attr('src',imgpreview);
				$('#imgcapa_post').val(json.imagem);

				// $('#temporary-imgs'+sufixoObjGal+' .content_inside').append(imgUploaded);//Inclui o thumb da imagem recem enviada
				$('#ImagensAdicionadas').val(ImagensAdicionadas);

				if( $('#temporary-imgs'+sufixoObjGal).hasClass('hidden') ){
					$('#temporary-imgs'+sufixoObjGal).removeClass('hidden');
				}
			}else if(json.status == 'erro'){
				$('#uploadify'+queueID).addClass('uploadifyError');
				$('#uploadify'+queueID+' .percentage').text(' - '+json.msg);
				return false;
			}
		}
	});/*end uploadfy*/
	
})(jQuery);