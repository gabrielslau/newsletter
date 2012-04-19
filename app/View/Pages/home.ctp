<div class="mws-panel grid_3">
	<div class="mws-panel-header">
    	<span class="mws-i-24 i-home-2">Seja bem vindo</span>
    </div>
    <div class="mws-panel-body">
    	<div class="mws-panel-content">Utilize o menu ao lado para escolher a operação</div>
    </div>
</div>

<div class="mws-panel grid_3">
    <div class="mws-panel-header">
        <span class="mws-i-24 i-graph">Resumo do sistema</span>
    </div>
    <div class="mws-panel-body">
        <ul class="mws-summary">
        <?php
            echo '<li><span>'.$countItens['Email'].'</span> '.$this->Html->link( 'Emails' , array('controller'=>'emails','action' => 'index')).' cadastrados</li>';
            echo '<li><span>'.$countItens['Group'].'</span> '.$this->Html->link( 'Grupos de emails' , array('controller'=>'groups','action' => 'index')).' cadastrados</li>';
            echo '<li><span>'.$countItens['Newsletter'].'</span> '.$this->Html->link( 'Newsletters' , array('controller'=>'newsletters','action' => 'index')).' cadastradas</li>';
            echo '<li><span>'.$countItens['LogInProgress'].'</span> Newsletters em progresso de envio</li>';
            echo '<li><span>'.$countItens['LogFinished'].'</span> Newsletters enviadas</li>';
        ?>
        </ul>
    </div>
</div>