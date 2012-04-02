<?php
	$modulus = 4;
	$numPaginas = (int)$this->Paginator->counter(array('format' => '{:pages}'));
	$paginaAtual = (int)$this->Paginator->counter(array('format' => '{:page}'));

	if($numPaginas>1):
?>
<div class="dataTables_paginate paging_full_numbers">

	<?php
		echo ( $numPaginas > $modulus && ($paginaAtual >= 4) && $this->Paginator->hasPrev()) ? $this->Paginator->first('primeiro', array('class' => 'first paginate_button', 'tag'=>'span'), null, array('class' => 'paginate_button_disabled')) : '';

		echo ($this->Paginator->hasPrev() && ($paginaAtual >= 4) ) ? $this->Paginator->prev('anterior', array('class' => 'previous paginate_button', 'tag'=>'span'), null, array('class' => 'paginate_button_disabled')) : '';

		echo '<span>'.$this->Paginator->numbers(array('class' => 'number paginate_button','modulus'=>$modulus,'separator'=>'','tag'=>'span')).'</span>'; 

		echo ($this->Paginator->hasNext() && ($numPaginas - $paginaAtual >= 3)) ? $this->Paginator->next('próximo', array('class' => 'next paginate_button', 'tag'=>'span'), null, array('class' => 'paginate_button_disabled')) : '';

		echo ( $numPaginas > $modulus && ($numPaginas - $paginaAtual >= 3) && $this->Paginator->hasNext()) ? $this->Paginator->last('último', array('class' => 'last paginate_button', 'tag'=>'span'), null, array('class' => 'paginate_button_disabled')) : '';
	?>

</ul><!-- end .paginator -->
<?php endif;?>