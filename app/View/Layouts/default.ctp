<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<?php echo $this->element('estrutura/admin/head');?>
	<?php echo $this->element('estrutura/admin/analytics',array('cache' => array('time' => '+1 year')));?>
</head>

<body>
	<?php echo $this->element('estrutura/admin/header',array('cache' => array('time' => '+1 year')));?>
    
    <!-- Start Main Wrapper -->
    <div id="mws-wrapper">
    	<?php echo $this->element('estrutura/admin/sidebar',array('cache' => array('time' => '+1 year')));?>
    	
        <!-- Main Container Start -->
        <div id="mws-container" class="clearfix">
        	<!-- Inner Container Start -->
            <div class="container">
            	<?php
					echo $this->Form->hidden('ABS_PATH', array('value'=>getFullBaseUrl()));
					echo $this->Form->hidden('webroot', array('value'=>$this->webroot));
					
					echo $this->element('alert_messages');

					/*$uid = AuthComponent::user('id');
					if( !empty($uid) ) echo $uid;*/

					echo $content_for_layout; // CONTEÚDO INDIVIDUAL DAS PÁGINAS FICAM AQUI
				?>
            </div>
            <!-- Inner Container End -->
        </div>
        <!-- Main Container End -->
    </div><!-- Main Wrapper End -->
<?php
	$js_for_layout = isset($js_for_layout) ? $js_for_layout : array();
	$asset_js_for_layout = isset($asset_js_for_layout) ? $asset_js_for_layout : array();
	$jsExternal_for_layout = isset($jsExternal_for_layout) ? $jsExternal_for_layout : array();
	echo $this->element('estrutura/admin/scripts',array('js_for_layout'=>$js_for_layout,'jsExternal_for_layout'=>$jsExternal_for_layout,'asset_js_for_layout'=>$asset_js_for_layout));
	echo isset($scripts_for_layout) ? $scripts_for_layout : '';
	echo $this->Js->writeBuffer(); // Write cached scripts
	echo $this->element('sql_dump');
?>
</body>
</html>