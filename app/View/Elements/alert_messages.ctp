<?php
	$AlertFlash = $this->Session->flash();
	$AlertFlashAuth = $this->Session->flash('auth');
	$AlertFlashNotice = $this->Session->flash('notice');
	$AlertFlashError = $this->Session->flash('error');
	echo $AlertFlash.$AlertFlashAuth.$AlertFlashNotice.$AlertFlashError;
?>