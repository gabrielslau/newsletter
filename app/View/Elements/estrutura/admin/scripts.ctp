<?php
	//TODO: verificar se o minify está setado, senão, usa o método padrão de setar estilos
	// $view = new View($this);
	// $Minify = $view->loadHelper('Minify');

	$scripts = isset($scripts) ? $scripts : array(
		'jquery-1.7.2.min',
		'plugins/jquery.mousewheel.min',
		// 'admin/jquery-ui',
		'admin/jquery-ui-1.8.18.custom.min',
		// 'admin/jquery.ui.touch-punch.min', 

		//Plugin Scripts
		// 'plugins/placeholder/jquery.placeholder-min',
		// 'plugins/swfobject',
		'admin/mws' // Core script
	);

	if(isset($js_for_layout) && !empty($js_for_layout)){
		$scripts = array_merge($scripts,$js_for_layout); // Carrega os scripts específicos de cada página
	}

	if(count($scripts)>15){
		$scripts = array_chunk($scripts, 15); // Separa os scripts em "pacotes" de 15 em 15
		foreach($scripts as $script):
			echo $this->Minify->js($script);
		endforeach;
	}else echo $this->Minify->js($scripts);

	/*foreach($scripts as $s=>$script) 
		$scripts[$s] = $script.'.js';

	$this->AssetCompress->addScript($scripts);
	$this->AssetCompress->autoInclude = false;*/

	
	if(isset($jsExternal_for_layout) && !empty($jsExternal_for_layout)){
		echo $this->Html->script($jsExternal_for_layout); // Carrega os scripts externos
	}
	// echo $this->AssetCompress->includeJs();
?>