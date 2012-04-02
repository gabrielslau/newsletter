<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
	<?php echo $this->Html->charset(); ?>
	<title><?php echo $title_for_layout;?></title>
</head>

<body style="background:#3C56A3;">
	<div id="wrapbody" style="padding:20px 10px 10px;background:#3C56A3;font-family:Arial, Helvetica, sans-serif;font-size:12px;-moz-border-radius:6px;-webkit-border-radius:6px;border-radius:6px">
		<div id="logonews" style="margin-bottom:10px;background:#fff">
			<a href="<?php echo getFullBaseUrl()?>" target="_blank" style="border:0 none;display: block;">
				<img src="<?php echo getFullBaseUrl().'img/logomarca.gif'?>" alt="" />
			</a>
		</div>
		<div id="wrapcontent" style="padding:10px;background:#fff;border-top:3px solid #464646">
			<?php if(isset($content_for_layout)) echo $content_for_layout;?>
			<br clear="all"  />
		</div><!-- end #wrapcontent -->
	</div><!-- end #wrapbody -->
</body>
</html>