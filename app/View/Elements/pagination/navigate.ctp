<div class="paginator ui-corner-all">
	<div class="navigate">
	<strong>Página: </strong>
	<?php
		$modulus = 4;
		$paginas = (int)$this->Paginator->counter(array('format' => '%pages%'));
		$paginaAtual = (int)$this->Paginator->counter(array('format' => '%page%'));

		echo ( $paginas > $modulus && ($paginaAtual >= 4) && $this->Paginator->hasPrev()) ? $this->Paginator->first('primeiro', array('class' => 'paginacao first', 'tag'=>'em'), null, array('class' => 'disabled')) : '';
		echo ($this->Paginator->hasPrev() && ($paginaAtual >= 4) ) ? $this->Paginator->prev('anterior', array('class' => 'navigator prev', 'tag'=>'em'), null, array('class' => 'disabled')) : '';
		echo $this->Paginator->numbers(array('class' => 'number','modulus'=>$modulus,'separator'=>'')); 
		echo ($this->Paginator->hasNext() && ($paginas - $paginaAtual >= 3)) ? $this->Paginator->next('próximo', array('class' => 'navigator next', 'tag'=>'em'), null, array('class' => 'disabled')) : '';
		echo ( $paginas > $modulus && ($paginas - $paginaAtual >= 3) && $this->Paginator->hasNext()) ? $this->Paginator->last('último', array('class' => 'paginacao last', 'tag'=>'em'), null, array('class' => 'disabled')) : '';
	?>
	</div><!-- end .navigate -->
</div><!-- end .paginator -->