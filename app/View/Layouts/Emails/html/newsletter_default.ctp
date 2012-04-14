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
	echo $content_for_layout;
}