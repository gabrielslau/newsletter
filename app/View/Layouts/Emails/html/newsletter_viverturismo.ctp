<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
	<?php echo $this->Html->charset(); ?>
	<title><?php echo $title_for_layout;?></title>
</head>
<?php
	$wrapbodyStyle = '
		
		background:#0A6691;
		font-family:Arial, Helvetica, sans-serif;
		font-size:12px;
		-moz-border-radius:6px;
		-webkit-border-radius:6px;
		border-radius:6px;

		width:600px;
		margin:0 auto;
		overflow:hidden;
	';

	$pathimg = getFullBaseUrl().'img/newsletter/viverturismo/';

	$FULL_BASE_URL = 'http://viverturismo.coop.br/'
?>


<body style="background:#0A6691;">
	<div id="wrapbody" style="<?php echo $wrapbodyStyle?>">
		<div id="logonews" style="background:#fff;height:110px;text-align:center;border-top:3px solid #00AFEF;padding:10px 0">
			<a href="<?php echo $FULL_BASE_URL?>" target="_blank" style="border:0 none;display: block;">
				<img src="<?php echo $pathimg.'logomarca-viverturismo-newsheader.gif'?>" alt="ViverTurismo - Passagens aéreas, roteiros, pacotes, promoções" />
			</a>
		</div>
		<div id="wrapcontent" style="padding:10px;background:#fff;border-top:3px solid #f0f0f0">
			<?php if(isset($content_for_layout)) echo $content_for_layout;?>
			<br clear="all"  />
		</div><!-- end #wrapcontent -->
		<div style="height:50px;background:#fff;padding:0 5px">
			<div style="width:280px;float:left">
				<img src="<?php echo $pathimg.'email.gif'?>" alt="EMAIL: denise@viverturismo.coop.br" />
			</div>
			<div style="width:280px;float:right;text-align:right">
				<img src="<?php echo $pathimg.'telefone.gif'?>" alt="TELEFONE: 84 3092-3501" />
			</div>
		</div>
	</div><!-- end #wrapbody -->
</body>
</html>