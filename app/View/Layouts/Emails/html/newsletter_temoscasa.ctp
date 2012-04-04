<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
	<?php echo $this->Html->charset(); ?>
	<title><?php echo $title_for_layout;?></title>
</head>
<?php
	$wrapbodyStyle = '
		
		background:#006b37;
		font-family:Arial, Helvetica, sans-serif;
		font-size:12px;
		-moz-border-radius:6px;
		-webkit-border-radius:6px;
		border-radius:6px;

		width:600px;
		margin:0 auto;
		overflow:hidden;
	';

	$pathimg = getFullBaseUrl().'img/newsletter/temoscasa/';

	$FULL_BASE_URL = 'http://temoscasa.com.br/'
?>


<body style="background:#006b37;">
	<div id="wrapbody" style="<?php echo $wrapbodyStyle?>">
		<div id="logonews" style="background:#fff;height:110px;text-align:center;border-top:3px solid #003d17;padding:10px 0">
			<a href="<?php echo $FULL_BASE_URL?>" target="_blank" style="border:0 none;display: block;">
				<img src="<?php echo $pathimg.'logomarca-temoscasa-newsheader.gif'?>" alt="Temoscasa.com" />
			</a>
		</div>
		<div id="wrapcontent" style="padding:10px;background:#fff;border-top:3px solid #f0f0f0">
			<?php if(isset($content_for_layout)) echo $content_for_layout;?>
			<br clear="all"  />
		</div><!-- end #wrapcontent -->
		<div style="height:50px;background:#fff;padding:0 5px">
			<div style="width:280px">
				<img src="<?php echo $pathimg.'email.gif'?>" alt="EMAIL: sac@temoscasa.com.br" />
			</div>
		</div>
	</div><!-- end #wrapbody -->
</body>
</html>