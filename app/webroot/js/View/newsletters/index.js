(function($){
	/**
	 * Importa uma lista de emails em formato .CSV, separados por virgula
	*/
	var webroot = $('#webroot').val();

	/* Chosen Select Box Plugin */
	if($.fn.chosen) {
			$('select.chzn-select').chosen();
		}


	$('#ImportEmailPopUp').click(function(e){
		e.preventDefault();
		
		$('.PopUpImport').show();
	});
		
		var sufixoObjGal = '-galeria', jquploadifyGal = $('#uploadify'+sufixoObjGal);
		jquploadifyGal.uploadify({
			'uploader'			: webroot+'js/plugins/uploadify/uploadify.swf',
			'script'			: webroot+'newslettersemails/temp_upload_csv/'+ $('#SessionId').val(),
			'scriptData'  		: {'session_id' : $('#SessionId').val()},
			'cancelImg'			: webroot+'js/plugins/uploadify/cancel.png',
			'folder'			: webroot+'app/webroot/files/tmp/',
			'queueID'			: 'fileQueue'+sufixoObjGal,
			'queueSizeLimit'	: 1,
			'auto'				: true,
			'removeCompleted'	: true,
			'multi'				: false,
			'fileDesc'			: 'Arquivos .CSV',
			'fileExt'			:  '*.csv;',
			'buttonText'		: 'Importar',
			'onError'			: function(event,queueID,fileObj,errorObj){
				if (errorObj.type === 'File Size'){
					$('#uploadify'+queueID).addClass('uploadifyError');
					$('#uploadify'+queueID+' .percentage').text(' - '+'Extension: '+ fileObj.type+', Erro: '+errorObj.info);
				}
			},
			'onSelect'			: function(event,ID,fileObj) {
				$('#uploader_errors'+sufixoObjGal).removeClass('hidden').removeClass('error').removeClass('success').addClass('loading').html('Aguarde enquanto adicionamos os emails. Isto pode levar alguns minutos...');
			},
			'onComplete'		: function(event,queueID,fileObj,response,data){
				var json = eval('(' + response + ')'); //converte a string para objeto Json
				// console.log(json);
				json.categorias = $('#categorias_grupos').val();

				$.ajax({
					type: "POST",
					url: webroot+'newslettersemails/import',
					data : json  ,
					cache: false,
					dataType: "json",
					success: function(j){
						var errosCount = 0, errosMsg ='';
						if( typeof j.erros != 'undefined' ){
							//Faz a contagem de mensagens de erros e faz uma lista
							for (erro in j.erros) {
								errosCount++;
								// errosMsg += '<p>'+j.erros[erro]+'</p>';
							}
						}

						// console.log( j['erros'] );
						$('#uploader_errors'+sufixoObjGal).removeClass('loading').addClass(j.status).html('<p class="mb10">'+j.msg+'</p>');
						if(errosCount > 0) {
							$('#uploader_errors'+sufixoObjGal).append('<p class="error-message mb10">'+errosCount+' erros foram encontrados. Solucione os problemas abaixo e tente enviar novamente o arquivo.</p>');
							// $('#uploader_errors'+sufixoObjGal).append('<div class="error-message">'+errosMsg+'</div>');
						}


						if(j.status == 'success'){
							//TODO
							// atualizar a tabela com os ultimos 15 emails cadastrados
							$('#uploader_errors'+sufixoObjGal).removeClass('hidden').removeClass('loading').removeClass('error').addClass('success').html('Os emails foram cadastrados com sucesso');

						}else if(j.status == 'error'){
							$('#uploader_errors'+sufixoObjGal).removeClass('hidden').removeClass('loading').removeClass('success').addClass('error').html('ERROR = '+ j.msg );
						}
					},
					error: function(jqXHR, textStatus, errorThrown){
						// $.prompt.close(); // Fecha o prompt de confirmação e exibe mensagem de sucesso
						alert('Houve um erro interno do sistema. Não foi possível importar os associados.');

						$('#uploader_errors'+sufixoObjGal).removeClass('hidden').removeClass('loading').removeClass('success').addClass('error').html('jqXHR = '+ jqXHR + '; textStatus = '+textStatus+';  errorThrown = '+errorThrown);

						console.log(jqXHR);
						console.log(textStatus);
						console.log(errorThrown);
					}
				});//end ajax

				
			}
		});//end uploadfy
	
})(jQuery);