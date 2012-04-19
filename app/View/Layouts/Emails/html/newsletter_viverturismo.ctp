<?php
/**
 * LAYOUT DA NEWSLETTER VIVERTURISMO
*/
	$pathimg        = getFullBaseUrl().'img/newsletter/viverturismo/';
	$FULL_BASE_URL  = 'http://viverturismo.coop.br/';
	$show_full_html = isset($show_full_html) ? $show_full_html : true;


	$content = '<div align="center" style="width:100%;background:#f38000;"> 
		<div style="width:600px;margin:0 auto;font-family:Arial, Helvetica, sans-serif;background:#f38000;">
			<div style="border:1px solid #ccc;-moz-border-radius:4px;-webkit-border-radius:4px;border-radius:4px;background:#fff;overflow:hidden">
				<div style="background:#fff;height:110px;text-align:center;border-top:3px solid #0a6691;padding:10px 0">
					<a href="'. $FULL_BASE_URL .'" target="_blank" style="border:0 none;display: block;">
						<img src="'.$pathimg.'logomarca-viverturismo-newsheader-laranja.gif'.'" alt="ViverTurismo - Passagens aéreas, roteiros, pacotes, promoções" border="0" width="600" height="139" />
					</a>
				</div>

				<div style="padding:10px;background:#fff;border-top:3px solid #f0f0f0">
					
					<div style="width:580px;overflow:hidden">'.$content_for_layout.'</div>

					<br clear="all" />
				</div>

				<div style="height:50px;background:#fff;padding:0 5px">
					<div style="width:280px;float:left">
						<img src="'. $pathimg.'email.gif" alt="EMAIL: contato@viverturismo.coop.br" />
					</div>
					<div style="width:280px;float:right;text-align:right">
						<img src="'. $pathimg.'telefone.gif" alt="TELEFONE: 84 3092-3501" />
					</div>
				</div>
				<br clear="all" />
			</div>
		</div>

		

	</div>';

	/*<div style="width:600px;margin:0 auto;font-family:Arial, Helvetica, sans-serif;font-size:12px;color:#fff;">
			<p>Não quer mais receber emails? Você pode cancelar aqui.</p>
			<p>Este email foi enviado para: </p>
		</div>*/

if( $show_full_html ){
	echo '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
	<html>
	<head>
		'.$this->Html->charset().'
		<title>'.$title_for_layout.'</title>
	</head>
	<body style="background:#f38000;padding:0;margin:0">
	'.$content.'
	</body>
	</html>';
}else{
	echo isset($NewslettersId) ? '<div align="center" style="padding:10px;background:#fff;font-size:12px;font-family:Arial, Helvetica, sans-serif">Visualizar esta mensagem '. '<a href="'.$this->Html->url(array('controller'=>'newsletters','action'=>'view',$NewslettersId),true).'">no navegador web</a>' .'</div>' : '';
	
	echo $content;
	
	if(isset($unsubscribe_id) && !empty($unsubscribe_id)) {
		echo '<div style="margin:0 auto;font-family:Arial, Helvetica, sans-serif;font-size:12px;color:#333; text-align:center">
			<p>Deseja cancelar o recebimento de nossos informativos? Você pode '. '<a href="'.$this->Html->url(array('controller'=>'emails','action'=>'unsubscribe',base64_encode($unsubscribe_id)),true).'">cancelar aqui</a>'.'.<br />
			Este email foi enviado para: '.$unsubscribe_id.'</p>
		</div>';
	}
}