<?php
/**
 * LAYOUT DA NEWSLETTER PADRÃO
*/
	$show_full_html = isset($show_full_html) ? $show_full_html : true;

if( $show_full_html ){
	echo '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
	<html>
	<head>
		'.$this->Html->charset().'
		<title>'.$title_for_layout.'</title>
	</head>
	<body style="padding:0;margin:0">
	'.$content_for_layout.'

	</body>
	</html>';
}else{
	echo isset($NewslettersId) ? '<div align="center" style="padding:10px;background:#fff;font-size:12px;font-family:Arial, Helvetica, sans-serif">Visualizar esta mensagem '. '<a href="'.$this->Html->url(array('controller'=>'newsletters','action'=>'view',$NewslettersId),true).'">no navegador web</a>' .'</div>' : '';
	
	echo $content_for_layout;
	
	if(isset($unsubscribe_id) && !empty($unsubscribe_id)) {
		echo '<div style="margin:0 auto;font-family:Arial, Helvetica, sans-serif;font-size:12px;color:#333; text-align:center">
			<p>Nós respeitamos a sua privacidade. Se você não deseja mais receber nossos e-mails, '. '<a href="'.$this->Html->url(array('controller'=>'emails','action'=>'unsubscribe',base64_encode($unsubscribe_id)),true).'">cancele sua inscrição aqui</a>'.'.<br />
			Este email foi enviado para: '.$unsubscribe_id.'</p>
		</div>';
	}
}