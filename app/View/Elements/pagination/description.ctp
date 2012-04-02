<div class="paginator mb20">
	<div class="description">
	<?php
		$total_resultados = (int) $this->Paginator->counter(array('format' => '%count%'));
		$s = ($total_resultados > 1) ? "s" : "";
		$lis = ($total_resultados > 1) ? "is" : "l";
		$iram = ($total_resultados > 1) ? "ram" : "i";
		
		echo $this->Paginator->counter(array(
			'format' => '<p class="fl"><b class="num_resultados">%count%</b> resultado'.$s.' fo'.$iram.' encontrado'.$s.'.</p><p class="fr">Mostrando de <b> %start% até %end% </b></p>'
		));
		//echo $this->Paginator->counter(array('format' => 'PAGE: %page%, PAGES: %pages%, CURRENT: %current%, COUNT: %count%, START: %start%, END: %end%'));
	?><div class="fix"></div>
	</div><!-- end .description -->
</div><!-- end .paginator -->